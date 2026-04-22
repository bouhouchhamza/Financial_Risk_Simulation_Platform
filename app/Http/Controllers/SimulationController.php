<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\SimulationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller
{
    public function index()
    {
        $startup = auth()->user()?->startup;
        $recentSimulations = $startup ? $startup->simulations()->latest()->take(5)->get():collect();
        return view('simulations.index', compact('startup', 'recentSimulations'));
    }

    public function store(Request $request, SimulationService $simulationService, ReportService $reportService)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $validated = $request->validate([
            'duration' => ['required', 'in:6_month,12_month'],
        ]);
        $simulationData = $simulationService->run($startup, $validated['duration']);

        $simulation = DB::transaction(function () use ($startup, $simulationData, $reportService) {
            $simulation = $startup->simulations()->create([
                'duration' => $simulationData['duration'],
                'total_revenue' => $simulationData['total_revenue'],
                'total_expenses' => $simulationData['total_expenses'],
                'final_cashflow' => $simulationData['final_cashflow'],
                'risk_level' => $simulationData['risk_level'],
            ]);

            foreach ($simulationData['monthly_results'] as $result) {
                $simulation->simulationResults()->create([
                    'month_number' => $result['month_number'],
                    'revenue' => $result['revenue'],
                    'expenses' => $result['expenses'],
                    'cashflow' => $result['cashflow'],
                    'is_critical' => $result['is_critical'],
                ]);
            }

            $reportService->createFromSimulation($startup, $simulation);

            return $simulation;
        });

        return redirect()
            ->route('simulations.show', $simulation->id)
            ->with('success', 'Simulation created successfully. Report generated.');
    }

    public function show($id)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $simulation = $startup->simulations()->findOrFail($id);
        return view('simulations.show', compact('simulation'));
    }
}
