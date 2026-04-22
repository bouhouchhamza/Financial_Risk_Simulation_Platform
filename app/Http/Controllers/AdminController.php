<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        try {
            $totalUsers = User::count();
            $totalStartups = class_exists(\App\Models\Startup::class) ? \App\Models\Startup::count() : 0;
            $totalTxns = class_exists(\App\Models\Transaction::class) ? \App\Models\Transaction::count() : 0;
            $totalAlerts = class_exists(\App\Models\Alert::class) ? \App\Models\Alert::count() : 0;

            // Real 7-day activity series for the admin chart.
            $chartLabels = [];
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartLabels[] = $date->format('M d');
                $chartData[] = User::whereDate('created_at', $date->toDateString())->count();
            }

            $users = User::latest()->take(10)->get();
        } catch (\Exception $e) {
            $totalUsers = 0;
            $totalStartups = 0;
            $totalTxns = 0;
            $totalAlerts = 0;
            $users = collect();
            $chartLabels = [];
            $chartData = [];
        }

        return view('admin.dashboard.dashboard', compact(
            'totalUsers',
            'totalStartups',
            'totalTxns',
            'totalAlerts',
            'users',
            'chartLabels',
            'chartData'
        ));
    }
    public function alerts()
    {
        $alerts = \App\Models\Alert::with(['startup', 'transaction'])->latest()->paginate(15);
        return view('admin.alerts.index', compact('alerts'));
    }
    public function transactions()
    {
        $transactions = \App\Models\Transaction::with('startup')->latest()->paginate(15);
        return view('admin.transactions.index', compact('transactions'));
    }
    public function startups()
    {
        $startups = \App\Models\Startup::with('user')->latest()->paginate(15);
        return view('admin.startups.index', compact('startups'));
    }
    public function users()
    {
        $users = \App\Models\User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }
}
