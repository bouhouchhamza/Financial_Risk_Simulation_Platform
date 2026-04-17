<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'notify_high_risk' => $request->has('notify_high_risk'),
            'notify_weekly_report' => $request->has('notify_weekly_report'),
            'notify_login_events' => $request->has('notify_login_events'),
        ]);
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
