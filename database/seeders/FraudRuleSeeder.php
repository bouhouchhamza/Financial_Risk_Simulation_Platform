<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FraudRule;

class FraudRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            [
            'name' => 'High_amount',
            'code' => 'high_amount',
            'threshold_value' => 10000,
            'score_weight' => 20,
            'decision_if_matched' => 'review',
            'is_active' => true,
            'description' => 'Trigger when a transaction amount exceeds the allowed threshold.',
        ],
        [
            'name' => 'Duplicate Pattern',
            'code' => 'duplicate_pattern',
            'threshold_value' => 2,
            'score_weight' => 25,
            'decision_if_matched' => 'review',
            'is_active' => true,
            'description' => 'Trigger when the same transaction pattern appears multiple times.',

        ],
        [
            
            'name' => 'Invalid_Amount',
            'code' => 'invalid_amount',
            'threshold_value' => 0,
            'score_weight' => 40,
            'decision_if_matched' => 'review',
            'is_active' => true,
            'description' => 'Trigger when the transaction amount is invalid or non-positive.',

        ],
        [
            'name' => 'Unusual Activity',
            'code' => 'unusual_activity',
            'threshold_value' => 5,
            'score_weight' => 15,
            'decision_if_matched' => 'review',
            'is_active' => true,
            'description' => 'Trigger when too many transactions happen in one day.',
        ],
        ];
        foreach ($rules as $rule) {
            FraudRule::updateOrCreate(
                ['code' => $rule['code']],
                $rule
            );
        }
    }
}
