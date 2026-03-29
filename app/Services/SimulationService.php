<?php

namespace App\Services;

use App\Models\Startup;
use Exception;

class SimulationService
{
    public function run(Startup $startup, string $duration)
    {
        if (!in_array($duration, ['6_month', '12_month'])) {
            throw new \InvalidArgumentException('Invalid duration');
        }
        $months = $duration === '6_month' ? 6 : 12;
        $totalRevenue = $startup->monthly_revenue * $months;
        $totalExpenses = $startup->monthly_expenses * $months;
        $cashflow = $totalRevenue - $totalExpenses;
        $risk = 'low';
        if ($cashflow < 0) {
            $risk = 'high';
        } elseif ($cashflow < 1000) {
            $risk = 'medium';
        }
        return [
            'duration' => $duration,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'final_cashflow' => $cashflow,
            'risk_level' => $risk,
        ];
    }
}
