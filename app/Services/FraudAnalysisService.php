<?php

namespace App\Services;

use App\Models\Startup;
use Illuminate\Support\Collection;

class FraudAnalysisService
{
    public function __construct(
        private readonly FraudDetectionService $fraudDetectionService,
        private readonly AlertService $alertService
    ) {}

    public function runForStartup(Startup $startup): array
    {
        $fraudResult = $this->fraudDetectionService->analyze($startup);

        $this->alertService->storeFraudAlerts($startup, $fraudResult);
        $this->markSuspiciousTransactions($startup, $fraudResult);

        return $fraudResult;
    }

    private function markSuspiciousTransactions(Startup $startup, array $fraudResult): void
    {
        $transactionIds = $this->extractFlaggedTransactionIds($fraudResult);
        $startup->transactions()->update(['is_suspicious' => false]);
        if ($transactionIds->isEmpty()) {
            return;
        }

        $startup->transactions()
            ->whereIn('id', $transactionIds->all())
            ->update(['is_suspicious' => true]);
    }

    private function extractFlaggedTransactionIds(array $fraudResult): Collection
    {
        return collect($fraudResult['details'] ?? [])
            ->flatMap(function ($ruleDetails) {
                if (! is_array($ruleDetails)) {
                    return [];
                }

                return collect($ruleDetails)
                    ->filter(fn($item) => is_array($item) && isset($item['transaction_id']))
                    ->pluck('transaction_id');
            })
            ->filter(fn($id) => is_numeric($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }
}
