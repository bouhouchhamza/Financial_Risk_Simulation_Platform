<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Startup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create();

        $routes = [
            'admin.dashboard',
            'admin.alerts.index',
            'admin.transactions.index',
            'admin.startups.index',
            'admin.users.index',
            'admin.fraud_rules.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $response->assertForbidden();
        }
    }

    public function test_non_admin_cannot_execute_alert_review_actions(): void
    {
        $user = User::factory()->create();
        $startup = Startup::factory()->create(['user_id' => $user->id]);

        $alert = Alert::create([
            'startup_id' => $startup->id,
            'type' => 'high_amount',
            'severity' => 'high',
            'message' => 'High amount transaction detected.',
            'status' => 'new',
            'review_status' => 'pending_review',
        ]);

        $actions = [
            'admin.alerts.approve',
            'admin.alerts.reject',
            'admin.alerts.confirmFraud',
            'admin.alerts.markFalsePositive',
        ];

        foreach ($actions as $routeName) {
            $response = $this->actingAs($user)->patch(route($routeName, $alert->id));
            $response->assertForbidden();
        }
    }
}

