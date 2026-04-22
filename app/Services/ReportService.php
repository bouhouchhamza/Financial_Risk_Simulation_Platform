<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Simulation;
use App\Models\Startup;
use Illuminate\Support\Carbon;

class ReportService
{
    public function createFromSimulation(Startup $startup, Simulation $simulation): Report
    {
        $generatedAt = Carbon::now();
        $duration = str_replace('_', ' ', (string) $simulation->duration);
        $riskLevel = strtolower((string) ($simulation->risk_level ?? 'low'));

        $summary = sprintf(
            'Duration: %s. Projected Revenue: $%s. Projected Expenses: $%s. Net Cashflow: $%s.',
            $duration,
            number_format((float) $simulation->total_revenue, 2),
            number_format((float) $simulation->total_expenses, 2),
            number_format((float) $simulation->final_cashflow, 2)
        );

        $recommendations = $this->buildRecommendations($riskLevel, (float) $simulation->final_cashflow);

        return $startup->reports()->create([
            'simulation_id' => $simulation->id,
            'title' => sprintf(
                'Simulation Report - %s - %s',
                $startup->name,
                $generatedAt->format('Y-m-d H:i')
            ),
            'type' => 'Simulation Report',
            'summary' => $summary,
            'risk_level' => $riskLevel,
            'recommendations' => $recommendations,
            'generated_at' => $generatedAt,
            'file_path' => null,
        ]);
    }

    private function buildRecommendations(string $riskLevel, float $finalCashflow): string
    {
        if ($riskLevel === 'high') {
            return 'High risk detected. Reduce monthly expenses, review pricing strategy, and monitor suspicious transactions more frequently.';
        }

        if ($riskLevel === 'medium') {
            return 'Medium risk detected. Improve margin control, optimize operating costs, and continue monthly monitoring.';
        }

        if ($finalCashflow <= 0) {
            return 'Cashflow is neutral or negative. Review revenue assumptions and expense allocation before scaling operations.';
        }

        return 'Low risk profile. Keep current strategy and continue periodic fraud/risk monitoring.';
    }
}

