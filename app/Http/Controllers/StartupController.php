<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startup; // حيث عندك unique

        return view('startups.index', compact('startup'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->startup) {
            return redirect()->route('startup.show');
        }

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
