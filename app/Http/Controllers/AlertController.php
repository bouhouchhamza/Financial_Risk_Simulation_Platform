<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alert;

class AlertController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.alerts.index');
        }

        $startup = $user->startup;

        if (! $startup) {
            return redirect()
                ->route('startup.create')
                ->with('error', 'Please create your startup first');
        }

        $alerts = $startup->alerts()
            ->with('transaction')
            ->latest('id')
            ->paginate(15);

        return view('alerts.index', compact('alerts', 'startup'));
    }
    public function approve($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $alert = Alert::findOrFail($id);
        $alert->update([
            'status' => 'reviewed',
            'review_status' => 'approved',
            'review_note' => 'Approved after review',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        return redirect()->route('admin.alerts.index')->with('success', 'Alert approved successfully.');
    }

    public function reject($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $alert = Alert::findOrFail($id);

        $alert->update([
            'status' => 'reviewed',
            'review_status' => 'rejected',
            'review_note' => 'Rejected after review.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.alerts.index')->with('success', 'Alert rejected successfully.');
    }

    public function confirmFraud($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $alert = Alert::findOrFail($id);

        $alert->update([
            'status' => 'resolved',
            'review_status' => 'confirmed_fraud',
            'review_note' => 'Confirmed as fraud.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.alerts.index')->with('success', 'Alert marked as confirmed fraud.');
    }

    public function markFalsePositive($id)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $alert = Alert::findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'review_status' => 'false_positive',
            'review_note' => 'Marked as false positive.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.alerts.index')->with('success', 'Alert marked as false positive.');
    }
}
