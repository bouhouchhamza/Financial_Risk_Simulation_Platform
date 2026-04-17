<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alert;
use App\Models\Startup;
use App\Models\User;
use App\Models\Role;


class AlertReviewWorkflowTest extends TestCase
{
    use RefreshDatabase;
    private function createAdminWithStartup(): array
    {
        $admin = User::factory()->create();
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ]);
        $admin->roles()->attach($adminRole->id);
        $startup = Startup::create([
            'user_id' => $admin->id,
            'name' => 'Test Startup',
            'activity_type' => 'Fintech',
            'initial_budget' => 10000,
            'monthly_revenue' => 5000,
            'monthly_expenses' => 3000,
            'employees_count' => 5,
        ]);
        return [$admin, $startup];
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

    public function test_admin_can_approve_alert(): void
    {
        [$admin, $startup] = $this->createAdminWithStartup();
        $alert = $this->createAlert($startup);

        $response = $this->actingAs($admin)->patch(route('admin.alerts.approve', $alert->id));
        $response->assertRedirect(route('admin.alerts.index'));
        $alert->refresh();

        $this->assertEquals('reviewed', $alert->status);
        $this->assertEquals('approved', $alert->review_status);
        $this->assertEquals($admin->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }

    public function test_admin_can_reject_alert(): void
    {
        [$admin, $startup] = $this->createAdminWithStartup();
        $alert = $this->createAlert($startup);
        $response = $this->actingAs($admin)->patch(route('admin.alerts.reject', $alert->id));

        $response->assertRedirect(route('admin.alerts.index'));
        $alert->refresh();
        $this->assertEquals('reviewed', $alert->status);
        $this->assertEquals('rejected', $alert->review_status);
        $this->assertEquals($admin->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }
    public function test_admin_can_confirm_fraud_alert(): void
    {
        [$admin, $startup] = $this->createAdminWithStartup();
        $alert = $this->createAlert($startup);
        $response = $this->actingAs($admin)->patch(route('admin.alerts.confirmFraud', $alert->id));
        $response->assertRedirect(route('admin.alerts.index'));
        $alert->refresh();

        $this->assertEquals('resolved', $alert->status);
        $this->assertEquals('confirmed_fraud', $alert->review_status);
        $this->assertEquals($admin->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }

    public function test_admin_can_mark_alert_as_false_positive(): void
    {
        [$admin, $startup] = $this->createAdminWithStartup();
        $alert = $this->createAlert($startup);

        $response = $this->actingAs($admin)->patch(route('admin.alerts.markFalsePositive', $alert->id));

        $response->assertRedirect(route('admin.alerts.index'));
        $alert->refresh();

        $this->assertEquals('resolved', $alert->status);
        $this->assertEquals('false_positive', $alert->review_status);
        $this->assertEquals($admin->id, $alert->reviewed_by);
        $this->assertNotNull($alert->reviewed_at);
    }
}
