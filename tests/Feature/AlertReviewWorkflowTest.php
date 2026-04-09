<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alert;
use App\Models\Startup;
use App\Models\User;


class AlertReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;
    private function createUserWithStartup(): array
    {
        $user = User::factory()->create();
        $startup = Startup::create([
            'user_id' => $user->id,
            'name' => 'Test Startup',
            'activity_type' => 'Fintech',
            'initial_budget' => 10000,
            'monthly_revenue' => 5000,
            'monthly_expenses' => 3000,
            'employees_count' => 5,
        ]);
        return [$user, $startup];
    }
    private function createAlert(Startup $startup):Alert
    {
        return Alert::create([
            'startup_id' => $startup->id,
            'type' => 'high_amount',
            'severity' => 'high',
            'message' => 'High amount transaction detected',
            'status' => 'new',
            'review_status' => 'pending_review',
        ]);
    }

    public function test_can_approve_alert(): void
    {
        [$user, $startup] = $this->createUserWithStartup();
        $alert = $this->createAlert($startup);

        $response = $this->actingAs($user)->patch(route('alerts.approve', $alert->id));
        $response->assertRedirect(route('alerts.index'));
        $alert->refresh();

        $this->assertEquals('reviewed', $alert->status);
        $this->assertEquals('approved', $alert->review_status);
        $this->assertEquals($user->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }

    public function test_user_can_reject_alert(): void
    {
        [$user, $startup] = $this->createUserWithStartup();
        $alert = $this->createAlert($startup);
        $response = $this->actingAs($user)->patch(route('alerts.reject', $alert->id));

        $response->assertRedirect(route('alerts.index'));
        $alert->refresh();
        $this->assertEquals('reviewed', $alert->status);
        $this->assertEquals('rejected', $alert->review_status);
        $this->assertEquals($user->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }
    public function test_user_can_confirm_fraud_alert(): void
    {
        [$user, $startup] = $this->createUserWithStartup();
        $alert = $this->createAlert($startup);
        $response = $this->actingAs($user)->patch(route('alerts.confirmFraud', $alert->id));
        $response->assertRedirect(route('alerts.index'));
        $alert->refresh();

        $this->assertEquals('resolved', $alert->status);
        $this->assertEquals('confirmed_fraud', $alert->review_status);
        $this->assertEquals($user->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }

    public function test_user_can_mark_alert_as_false_positive(): void
    {
        [$user, $startup] = $this->createUserWithStartup();
        $alert = $this->createAlert($startup);

        $response = $this->actingAs($user)->patch(route('alerts.markFalsePositive', $alert->id));

        $response->assertRedirect(route('alerts.index'));
        $alert->refresh();

        $this->assertEquals('resolved', $alert->status);
        $this->assertEquals('false_positive', $alert->review_status);
        $this->assertEquals($user->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }
}
