<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startup;

        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $transactionsCount = method_exists($startup, 'transactions') ? $startup->transactions()->count() : null;
        $alertsCount = method_exists($startup, 'alerts') ? $startup->alerts()->count() : null;
        $reportsCount = method_exists($startup, 'reports') ? $startup->reports()->count() : null;
        $simulationsCount = method_exists($startup, 'simulations') ? $startup->simulations()->count() : null;

        $hasDescription = !empty($startup->description);
        $createdAt = $startup->created_at ? $startup->created_at->format('M d, Y') : 'Not available';
        $updatedAt = $startup->updated_at ? $startup->updated_at->format('M d, Y') : 'Not available';

        $monthlyRevenue = (float) $startup->monthly_revenue;
        $monthlyExpenses = (float) $startup->monthly_expenses;
        $initialBudget = (float) $startup->initial_budget;

        $cashflow = $monthlyRevenue - $monthlyExpenses;

        $totalFlow = max($monthlyRevenue + $monthlyExpenses, 1);

        $revenueShare = (int) round(($monthlyRevenue / $totalFlow) * 100);
        $expenseShare = (int) round(($monthlyExpenses / $totalFlow) * 100);

        $runwayMonths = $monthlyExpenses > 0 ? ($initialBudget / $monthlyExpenses) : 0;

        $capitalScore = (int) round(min(100, max(0, $runwayMonths * 8)));
        $efficiencyScore = (int) round(min(100, max(0, 100 - $expenseShare)));
        $resilienceScore = (int) round(($capitalScore + $revenueShare + $efficiencyScore) / 3);

        return view('startups.index', compact(
            'startup',
            'transactionsCount',
            'alertsCount',
            'reportsCount',
            'simulationsCount',
            'hasDescription',
            'createdAt',
            'updatedAt',
            'monthlyRevenue',
            'monthlyExpenses',
            'initialBudget',
            'cashflow',
            'revenueShare',
            'expenseShare',
            'runwayMonths',
            'capitalScore',
            'efficiencyScore',
            'resilienceScore'
        ));
    }
    public function create()
    {
        return view('startups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'activity_type' => 'required',
            'initial_budget' => 'required|numeric',
            'monthly_revenue' => 'required|numeric',
            'monthly_expenses' => 'required|numeric',
            'employees_count' => 'required|integer',
        ]);

        Startup::create([
            'name' => $request->name,
            'activity_type' => $request->activity_type,
            'initial_budget' => $request->initial_budget,
            'monthly_revenue' => $request->monthly_revenue,
            'monthly_expenses' => $request->monthly_expenses,
            'employees_count' => $request->employees_count,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('startup.show')->with('success', 'Startup created');
    }

    public function show()
    {
        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please Create your startup first.');
        }

        $startupTransactions = $startup->transactions()
            ->latest('transaction_date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('startups.show', compact('startup', 'startupTransactions'));
    }
}
