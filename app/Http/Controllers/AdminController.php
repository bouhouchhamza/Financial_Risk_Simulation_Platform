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
            
            $users = User::latest()->take(10)->get();
        } catch (\Exception $e) {
            $totalUsers = 0;
            $totalStartups = 0;
            $totalTxns = 0;
            $totalAlerts = 0;
            $users = [];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStartups',
            'totalTxns',
            'totalAlerts',
            'users'
        ));
    }
}
