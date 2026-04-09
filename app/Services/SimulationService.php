<?php

namespace App\Services;

use App\Models\Startup;
use InvalidArgumentException;

class SimulationService
{
    public function run(Startup $startup, string $duration)
    {
        if (!in_array($duration, ['6_month', '12_month'])) {
            throw new InvalidArgumentException('Invalid duration');
        }
        $months = $duration === '6_month' ? 6 : 12;
        $monthlyRevenue = $startup->monthly_revenue;
        $monthlyExpenses = $startup->monthly_expenses;
        $totalRevenue = $monthlyRevenue * $months;
        $totalExpenses = $monthlyExpenses * $months;
        $cashflow = $totalRevenue - $totalExpenses;
        $risk = 'low';
        if ($cashflow < 0) {
            $risk = 'high';
        } elseif ($cashflow < 1000) {
            $risk = 'medium';
        }
        $results = [];
        for ($month = 1; $month <= $months; $month++) {
            $monthCashflow = $monthlyRevenue - $monthlyExpenses;
        $results[] = [
            'month_number' => $month,
            'revenue' => $monthlyRevenue,
            'expenses' => $monthlyExpenses,
            'cashflow' => $monthCashflow,
            'is_critical' => $monthCashflow < 0,
        ];
    }
    return [
        'duration' => $duration,
        'total_revenue' => $totalRevenue,
        'total_expenses' => $totalExpenses,
        'final_cashflow' => $cashflow,
        'risk_level' => $risk,
        'monthly_results' => $results,
    ];
    }

}
