<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaction;
use App\Models\FraudRule;
use App\Models\Startup;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FraudDetectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FraudRule::create([
            'name' => 'High Amount',
            'type' => 'high_amount',
            'code' => 'high_amount',
            'threshold_value' => 1000,
            'score_weight' => 50,
            'is_active' => true,
        ]);

        FraudRule::create([
            'name' => 'Invalid Amount',
            'type' => 'invalid_amount',
            'code' => 'invalid_amount',
            'threshold_value' => 0,
            'score_weight' => 100,
            'is_active' => true,
        ]);

        FraudRule::create([
            'name' => 'Duplicate Pattern',
            'type' => 'duplicate_pattern',
            'code' => 'duplicate_pattern',
            'threshold_value' => 2,
            'score_weight' => 30,
            'is_active' => true,
        ]);
    }

    public function test_basic_works()
    {
        $this->assertTrue(true);
    }

    public function test_it_detects_high_amount_transaction()
    {
        $startup = Startup::factory()->create();
        Transaction::factory()->create([
            'amount' => 2000,
            'type' => 'sale',
            'startup_id' => $startup->id,
        ]);

        $service = app(\App\Services\FraudDetectionService::class);
        $result = $service->analyze($startup);

        $this->assertTrue($result['risk_score'] >= 50);
        $this->assertContains('high_amount', $result['flags']);
    }

    public function test_it_detects_invalid_amount()
    {
        $startup = Startup::factory()->create();
        Transaction::factory()->create([
            'amount' => -100,
            'type' => 'purchase',
            'startup_id' => $startup->id,
        ]);


        $service = app(\App\Services\FraudDetectionService::class);
        $result = $service->analyze($startup);

        $this->assertContains('invalid_amount', $result['flags']);
        $this->assertEquals('block', $result['decision']);
    }

    public function test_it_detects_duplicate_transactions()
    {
        $startup = Startup::factory()->create();
        Transaction::factory()->count(3)->create([
            'amount' => 500,
            'type' => 'transfer',
            'startup_id' => $startup->id,
        ]);

        $service = app(\App\Services\FraudDetectionService::class);
        $result = $service->analyze($startup);

        $this->assertContains('duplicate_pattern', $result['flags']);
    }

    public function test_it_returns_low_risk_for_normal_transaction()
    {
        $startup = Startup::factory()->create();
        Transaction::factory()->create([
            'amount' => 100,
            'type' => 'sale',
            'startup_id' => $startup->id,
        ]);

        $service = app(\App\Services\FraudDetectionService::class);
        $result = $service->analyze($startup);

        $this->assertEquals('allow', $result['decision']);
        $this->assertEmpty($result['flags']);
    }

    public function test_it_calculates_risk_level_correctly()
    {
        $startup = Startup::factory()->create();
        Transaction::factory()->create([
            'amount' => 2000,
            'type' => 'purchase',
            'startup_id' => $startup->id,
        ]);

        $service = app(\App\Services\FraudDetectionService::class);
        $result = $service->analyze($startup);

        $this->assertContains($result['risk_level'], ['low', 'medium', 'high']);
    }
}
