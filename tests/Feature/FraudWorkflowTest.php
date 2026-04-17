<?php

namespace Tests\Feature;

use App\Models\FraudRule;
use App\Models\Role;
use App\Models\Startup;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FraudWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FraudRule::create([
            'name' => 'High Amount',
            'code' => 'high_amount',
            'threshold_value' => 1000,
            'score_weight' => 50,
            'decision_if_matched' => 'review',
            'is_active' => true,
        ]);
    }

    public function test_transaction_creation_triggers_fraud_alert_then_admin_reviews_it(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('transactions.store'), [
            'type' => 'sale',
            'amount' => 5000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Large customer payment',
        ]);

        $transaction = Transaction::where('startup_id', $startup->id)->latest('id')->firstOrFail();
        $response->assertRedirect(route('transactions.show', $transaction->id));

        $this->assertTrue((bool) $transaction->fresh()->is_suspicious);

        $alert = $startup->alerts()
            ->where('transaction_id', $transaction->id)
            ->where('rule_code', 'high_amount')
            ->first();

        $this->assertNotNull($alert);
        $this->assertEquals('pending_review', $alert->review_status);

        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $reviewResponse = $this->actingAs($admin)->patch(route('admin.alerts.confirmFraud', $alert->id));
        $reviewResponse->assertRedirect(route('admin.alerts.index'));

        $alert->refresh();
        $this->assertEquals('resolved', $alert->status);
        $this->assertEquals('confirmed_fraud', $alert->review_status);
        $this->assertEquals($admin->id, $alert->reviewed_by);
    }

    public function test_fraud_detection_get_is_read_only_and_post_executes_detection(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create([
            'startup_id' => $startup->id,
            'type' => 'sale',
            'amount' => 4000,
            'is_suspicious' => false,
        ]);

        $showResponse = $this->actingAs($user)->get(route('fraud-detection.show', $startup->id));
        $showResponse->assertOk();

        $this->assertFalse((bool) $transaction->fresh()->is_suspicious);
        $this->assertDatabaseCount('alerts', 0);

        $runResponse = $this->actingAs($user)->post(route('fraud-detection.run', $startup->id));
        $runResponse->assertRedirect(route('fraud-detection.show', $startup->id));

        $this->assertTrue((bool) $transaction->fresh()->is_suspicious);
        $this->assertDatabaseHas('alerts', [
            'startup_id' => $startup->id,
            'transaction_id' => $transaction->id,
            'rule_code' => 'high_amount',
            'review_status' => 'pending_review',
        ]);
    }

    public function test_analysis_get_is_read_only_and_post_creates_simulation_alerts(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create([
            'user_id' => $user->id,
            'monthly_revenue' => 1000,
            'monthly_expenses' => 5000,
        ]);

        $previewResponse = $this->actingAs($user)
            ->get(route('analysis.show', ['startup' => $startup->id, 'duration' => '6_month']));

        $previewResponse->assertOk()
            ->assertJsonPath('mode', 'preview');

        $this->assertDatabaseCount('alerts', 0);

        $runResponse = $this->actingAs($user)
            ->post(route('analysis.run', ['startup' => $startup->id]), ['duration' => '6_month']);

        $runResponse->assertOk()
            ->assertJsonPath('mode', 'executed');

        $this->assertDatabaseHas('alerts', [
            'startup_id' => $startup->id,
            'rule_code' => 'simulation_risk',
            'review_status' => 'pending_review',
        ]);
    }
}
