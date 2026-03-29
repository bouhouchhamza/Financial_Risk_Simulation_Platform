<?php

namespace App\Services;

use App\Models\FraudRule;
use App\Models\Startup;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FraudDetectionService
{
    public function analyze(Startup $startup): array
    {
        $startup->loadMissing('transactions');
        $transactions = $startup->transactions;

        $flags = [];
        $details = [];

        $highAmountFlags = $this->detectHighAmountTransactions($transactions);
        if (!empty($highAmountFlags)) {
            $flags[] = 'high_amount';
            $details['high_amount'] = $highAmountFlags;
        }

        $duplicateFlags = $this->detectDuplicateTransactions($transactions);
        if (!empty($duplicateFlags)) {
            $flags[] = 'duplicate_pattern';
            $details['duplicate_pattern'] = $duplicateFlags;
        }

        $invalidFlags = $this->detectInvalidAmounts($transactions);
        if (!empty($invalidFlags)) {
            $flags[] = 'invalid_amount';
            $details['invalid_amount'] = $invalidFlags;
        }

        $unusualFlags = $this->detectUnusualActivity($transactions);
        if (!empty($unusualFlags)) {
            $flags[] = 'unusual_activity';
            $details['unusual_activity'] = $unusualFlags;
        }

        $alertsCount = count($flags);

        return [
            'startup_id' => $startup->id,
            'alerts_count' => $alertsCount,
            'flags' => $flags,
            'risk_level' => $this->calculateRiskLevel($alertsCount),
            'details' => $details,
        ];
    }

    private function detectHighAmountTransactions(Collection $transactions): array
    {
        $rule = FraudRule::where('name', 'high_amount')->where('is_active',true)->first();
        $threshold = $rule?->threshold_value ?? 1000;

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

    private function detectDuplicateTransactions(Collection $transactions): array
    {
        $grouped = $transactions->groupBy(function ($transaction) {
            return $transaction->type . '-' . $transaction->amount . '-' . Carbon::parse($transaction->transaction_date)->toDateString();
        });

        $duplicates = [];

        foreach ($grouped as $group) {
            if ($group->count() > 1) {
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

    private function detectUnusualActivity(Collection $transactions): array
    {
        $dailyCounts = $transactions->groupBy(function ($transaction) {
            return Carbon::parse($transaction->transaction_date)->toDateString();
        });

        $alerts = [];

        foreach ($dailyCounts as $date => $dayTransactions) {
            if ($dayTransactions->count() >= 5) {
                $alerts[] = [
                    'date' => $date,
                    'transactions_count' => $dayTransactions->count(),
                    'message' => 'Unusual number of transactions detected in one day',
                ];
            }
        }

        return $alerts;
    }

    private function calculateRiskLevel(int $alertCount): string
    {
        if ($alertCount >= 3) {
            return 'high';
        }

        if ($alertCount >= 1) {
            return 'medium';
        }

        return 'low';
    }
}
