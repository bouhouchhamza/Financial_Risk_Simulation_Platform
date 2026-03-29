<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Services\FraudDetectionService;
use App\Services\SimulationService;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function show(Request $request, Startup $startup, SimulationService $simulationService, FraudDetectionService $fraudDetectionService, AlertService $alertService)
    {

        abort_unless($startup->user_id === auth()->id(),403);
        $validated = $request->validate([
            'duration' => ['nullable', 'in:6_month,12_month'],
        ]);
        $duration = $validated['duration'] ?? '6_month';

        $simulation = $simulationService->run($startup, $duration);
        $fraud = $fraudDetectionService->analyze($startup);
        $alertService->storeFraudAlerts($startup, $fraud);
        $alertService->storeSimulationAlerts($startup, $simulation);
        return response()->json(
            [
                'startup' => [
                    'id' => $startup->id,
                    'name' => $startup->name,
                ],
                'simulation' => $simulation,
                'fraud_detection' => $fraud,
            ]
        );
    }
}
