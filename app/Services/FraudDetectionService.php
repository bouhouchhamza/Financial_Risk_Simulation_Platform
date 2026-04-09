<?php

namespace App\Services;

use App\Models\FraudRule;
use App\Models\Startup;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FraudDetectionService
{
    private function executeRule(FraudRule $rule, Collection $transactions): array
    {
        $handlers = $this->getRuleHandlers();
        if (!array_key_exists($rule->code, $handlers)) {
            return [];
        }
        $handler = $handlers[$rule->code];
        $method = $handler['method'];
        $usesThreshold = (bool) ($handler['uses_threshold'] ?? false);
        if (!is_string($method) || !method_exists($this, $method)) {
            return [];
        }
        if ($usesThreshold) {
            if (!is_numeric($rule->threshold_value)) {
                return [];
            }
            // Cast threshold based on method
            $threshold = in_array($method, ['detectDuplicateTransactions', 'detectUnusualActivity']) 
                ? (int) $rule->threshold_value 
                : (float) $rule->threshold_value;
            return $this->$method($transactions, $threshold);
        }
        return $this->$method($transactions);
    }

    public function analyze(Startup $startup): array
    {
        $startup->loadMissing('transactions');
        $transactions = $startup->transactions;

        $flags = [];
        $details = [];
        $riskScore = 0;
        $decisions = [];
        $rules = FraudRule::where('is_active', true)->get();
        foreach ($rules as $rule) {
            $matched = $this->executeRule($rule, $transactions);

            if (!empty($matched)) {
                $flags[] = $rule->code;
                $details[$rule->code] = $matched;
                $riskScore += $rule->score_weight;
                $decisions[] = $rule->decision_if_matched;
            }
        }
        return [
            'startup_id' => $startup->id,
            'risk_score' => $riskScore,
            'risk_level' => $this->calculateRiskLevel($riskScore),
            'decision' => $this->calculateDecision($decisions, $riskScore),
            'flags' => $flags,
            'details' => $details,
        ];
    }

    private function getRuleHandlers(): array
    {
        return [
            'high_amount' => [
                'method' => 'detectHighAmountTransactions',
                'uses_threshold' => true,
            ],
            'duplicate_pattern' => [
                'method' => 'detectDuplicateTransactions',
                'uses_threshold' => true,
            ],
            'invalid_amount' => [
                'method' => 'detectInvalidAmounts',
                'uses_threshold' => false,
            ],
            'unusual_activity' => [
                'method' => 'detectUnusualActivity',
                'uses_threshold' => true,
            ],
        ];
    }

    private function detectHighAmountTransactions(Collection $transactions, float $threshold): array
    {
        return $transactions
            ->filter(fn($transaction) => $transaction->amount > $threshold)
            ->map(fn($transaction) => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'message' => 'Transaction amount exceeds allowed threshold',
            ])
            ->values()
            ->toArray();
    }

    private function detectDuplicateTransactions(Collection $transactions, int $repeatThreshold): array
    {
        $grouped = $transactions->groupBy(function ($transaction) {
            return $transaction->type . '-' . $transaction->amount . '-' . Carbon::parse($transaction->transaction_date)->toDateString();
        });

        $duplicates = [];

        foreach ($grouped as $group) {
            if ($group->count() >= $repeatThreshold) {
                foreach ($group as $transaction) {
                    $duplicates[] = [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'type' => $transaction->type,
                        'transaction_date' => $transaction->transaction_date,
                        'message' => 'Possible duplicate transaction detected',
                    ];
                }
            }
        }

        return $duplicates;
    }

    private function detectInvalidAmounts(Collection $transactions): array
    {
        return $transactions
            ->filter(fn($transaction) => $transaction->amount <= 0)
            ->map(fn($transaction) => [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'message' => 'Invalid transaction amount',
            ])
            ->values()
            ->toArray();
    }

    private function detectUnusualActivity(Collection $transactions, int $dailyThreshold): array
    {
        $dailyCounts = $transactions->groupBy(function ($transaction) {
            return Carbon::parse($transaction->transaction_date)->toDateString();
        });

        $alerts = [];

        foreach ($dailyCounts as $date => $dayTransactions) {
            if ($dayTransactions->count() >= $dailyThreshold) {
                $alerts[] = [
                    'date' => $date,
                    'transactions_count' => $dayTransactions->count(),
                    'message' => 'Unusual number of transactions detected in one day',
                ];
            }
        }

        return $alerts;
    }

    private function calculateRiskLevel(int $riskScore): string
    {
        if ($riskScore >= 60) {
            return 'high';
        }

        if ($riskScore >= 25) {
            return 'medium';
        }

        return 'low';
    }

    private function calculateDecision(array $decisions, int $riskScore): string
    {
        if (in_array('block', $decisions, true) || $riskScore >= 80) {
            return 'block';
        }

        if (in_array('review', $decisions, true) || $riskScore >= 30) {
            return 'review';
        }

        return 'allow';
    }
}