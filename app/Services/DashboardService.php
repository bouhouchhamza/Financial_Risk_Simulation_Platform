<?php

namespace App\Services;

use App\Models\Startup;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class DashboardService
{
    public function summarizeForStartup(?Startup $startup): array
    {
        if (! $startup) {
            return [
                'totalTransactions' => 0,
                'totalRevenue' => 0.0,
                'totalExpenses' => 0.0,
                'lastTransactions' => collect(),
                'alertsCount' => 0,
            ];
        }

        return [
            'totalTransactions' => $startup->transactions()->count(),
            'totalRevenue' => (float) $startup->transactions()->where('type', 'sale')->sum('amount'),
            'totalExpenses' => (float) $startup->transactions()->where('type', 'purchase')->sum('amount'),
            'lastTransactions' => $this->lastTransactions($startup->id),
            'alertsCount' => $startup->alerts()->count(),
        ];
    }

    private function lastTransactions(int $startupId): Collection
    {
        return Transaction::query()
            ->where('startup_id', $startupId)
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();
    }
}
