<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $reports = $startup->reports()
            ->with('simulation:id,duration')
            ->orderByDesc('generated_at')
            ->latest()
            ->paginate(12);

        return view('reports.index', compact('reports'));
    }

    public function show($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $report = $startup->reports()
            ->with('simulation:id,duration,total_revenue,total_expenses,final_cashflow,risk_level')
            ->findOrFail($id);

        return view('reports.show', compact('report'));
    }

    public function download($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $report = $startup->reports()->findOrFail($id);

        if (!$report->file_path || !Storage::disk('public')->exists($report->file_path)) {
            return redirect()
                ->route('reports.show', $report->id)
                ->with('error', 'No downloadable file is attached to this report.');
        }

        return Storage::disk('public')->download($report->file_path);
    }

    public function destroy($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }

        $report = $startup->reports()->findOrFail($id);

        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
    }
}
