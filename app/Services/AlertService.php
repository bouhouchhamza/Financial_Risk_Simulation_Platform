<?php

namespace App\Services;

use App\Models\Startup;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AlertService
{
    public function storeFraudAlerts(Startup $startup, array $fraudResult): void
    {
        $detailsByRule = collect($fraudResult['details'] ?? []);

        if ($detailsByRule->isEmpty()) {
            return;
        }

        $detailsByRule->each(function ($ruleDetails, string $ruleCode) use ($startup) {
            $this->normalizeRuleDetails($ruleDetails, $ruleCode)->each(function (array $detail) use ($startup, $ruleCode) {
                $this->createAlertIfNotDuplicate(
                    startup: $startup,
                    transactionId: $detail['transaction_id'],
                    ruleCode: $ruleCode,
                    message: $detail['message'],
                    payload: [
                        'transaction_id' => $detail['transaction_id'],
                        'rule_code' => $ruleCode,
                        'message' => $detail['message'],
                        'type' => $ruleCode,
                        'severity' => $detail['severity'],
                        'status' => 'new',
                        'review_status' => 'pending_review',
                        'data' => $detail['data'],
                    ]
                );
            });
        });
    }

    public function storeSimulationAlerts(Startup $startup, array $simulationResult): void
    {
        if (($simulationResult['risk_level'] ?? 'low') !== 'high') {
            return;
        }

        $message = 'Simulation shows a high financial risk level.';

        $this->createAlertIfNotDuplicate(
            startup: $startup,
            transactionId: null,
            ruleCode: 'simulation_risk',
            message: $message,
            payload: [
                'transaction_id' => null,
                'rule_code' => 'simulation_risk',
                'message' => $message,
                'type' => 'simulation_risk',
                'severity' => 'high',
                'status' => 'new',
                'review_status' => 'pending_review',
                'data' => $simulationResult,
            ]
        );
    }

    private function recentDuplicateExists(
        Startup $startup,
        ?int $transactionId,
        string $ruleCode,
        string $message,
        int $hours = 24
    ): bool {
        return $startup->alerts()
            ->where('transaction_id', $transactionId)
            ->where('rule_code', $ruleCode)
            ->where('message', $message)
            ->where('created_at', '>=', Carbon::now()->subHours($hours))
            ->exists();
    }

    /**
     * Prevent duplicate alerts under concurrent requests by serializing writes per startup.
     */
    private function createAlertIfNotDuplicate(
        Startup $startup,
        ?int $transactionId,
        string $ruleCode,
        string $message,
        array $payload
    ): void {
        DB::transaction(function () use ($startup, $transactionId, $ruleCode, $message, $payload): void {
            // Lock startup row to make duplicate check + create atomic for this startup.
            Startup::query()
                ->whereKey($startup->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($this->recentDuplicateExists(
                startup: $startup,
                transactionId: $transactionId,
                ruleCode: $ruleCode,
                message: $message,
                hours: 24
            )) {
                return;
            }

            $startup->alerts()->create($payload);
        }, 3);
    }

    private function normalizeRuleDetails(mixed $ruleDetails, string $ruleCode): Collection
    {
        if (!is_array($ruleDetails) || empty($ruleDetails)) {
            return collect([[
                'transaction_id' => null,
                'severity' => $this->mapFraudSeverity($ruleCode),
                'message' => $this->fraudMessage($ruleCode),
                'data' => null,
            ]]);
        }

        return collect($ruleDetails)->map(function ($detail) use ($ruleCode) {
            $payload = is_array($detail) ? $detail : [];

            return [
                'transaction_id' => isset($payload['transaction_id']) && is_numeric($payload['transaction_id'])
                    ? (int) $payload['transaction_id']
                    : null,
                'severity' => $this->mapFraudSeverity($ruleCode),
                'message' => (string) ($payload['message'] ?? $this->fraudMessage($ruleCode)),
                'data' => empty($payload) ? null : $payload,
            ];
        })->values();
    }

    private function mapFraudSeverity(string $flag): string
    {
        return match ($flag) {
            'high_amount', 'invalid_amount' => 'high',
            'duplicate_pattern', 'unusual_activity' => 'medium',
            default => 'low',
        };
    }

    private function fraudMessage(string $flag): string
    {
        return match ($flag) {
            'high_amount' => 'High amount transaction detected.',
            'duplicate_pattern' => 'Possible duplicate transactions detected.',
            'invalid_amount' => 'Invalid transaction amount detected.',
            'unusual_activity' => 'Unusual transaction activity detected.',
            default => 'Fraud alert detected.',
        };
    }
}
