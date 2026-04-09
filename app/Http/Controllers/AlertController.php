<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alerts = $startup->alerts()
            ->with('transaction')
            ->latest('id')
            ->paginate(15);

        return view('alerts.index', compact('alerts', 'startup'));
    }

    public function show($id)
    {

        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);

        return view('alerts.show', compact('alert'));
    }

    public function edit($id)
    {

        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);

        return view('alerts.edit', compact('alert'));
    }

    public function update(Request $request, $id)
    {

        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $validated = $request->validate([
            'status' => ['required', 'in:new,reviewed,resolved'],
            'message' => ['nullable', 'string'],
        ]);
        $alert->update($validated);

        return redirect()->route('alerts.index')->with('success', 'Alert updated successfully.');
    }

    public function destroy($id)
    {

        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $alert->delete();

        return redirect()->route('alerts.index')->with('success', 'Alert deleted successfully.');
    }

    public function approve($id)
    {
        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $alert->update(
            [
                'status' => 'reviewed',
                'review_status' => 'approved',
                'review_note' => 'Approved after review.',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]
        );

        return redirect()->route('alerts.index')->with('success', 'Alert approved successfully.');
    }

    public function reject($id)
    {
        $startup = auth()->user()->startup;

        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);

        $alert->update([
            'status' => 'reviewed',
            'review_status' => 'rejected',
            'review_note' => 'Rejected after review.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('alerts.index')->with('success', 'Alert rejected successfully.');
    }

    public function confirmFraud($id)
    {
        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $alert = $startup->alerts()->findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'review_status' => 'confirmed_fraud',
            'review_note' => 'Confirmed as fraud.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('alerts.index')->with('success', 'Alert marked as confirmed fraud.');
    }

    public function markFalsePositive($id)
    {
        $startup = auth()->user()->startup;
        if (! $startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $alert = $startup->alerts()->findOrFail($id);
        $alert->update([
            'status' => 'resolved',
            'review_status' => 'false_positive',
            'review_note' => 'Marked as false positive.',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('alerts.index')->with('success', 'Alert marked as false positive.');
    }
}
