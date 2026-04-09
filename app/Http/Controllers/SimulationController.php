<?php

namespace App\Http\Controllers;

use App\Services\SimulationService;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index()
    {
        return view('simulations.index');
    }

    public function store(Request $request, SimulationService $simulationService)
    {
        $startup = auth()->user()->startup;
        if (!$startup) {
            return redirect()->route('startup.create')->with('error', 'Please create your startup first');
        }
        $validated = $request->validate([
            'duration' => ['required', 'in:6_month,12_month'],
        ]);
        $simulationData = $simulationService->run($startup, $validated['duration']);
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

        return redirect()->route('simulations.show', $simulation->id)->with('success', 'Simulation created successfully');
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
