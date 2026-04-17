<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Services\FraudDetectionService;
use App\Services\FraudAnalysisService;
use App\Services\SimulationService;
use App\Services\AlertService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function show(
        Request $request,
        Startup $startup,
        SimulationService $simulationService,
        FraudDetectionService $fraudDetectionService
    ): JsonResponse
    {
        abort_unless($startup->user_id === auth()->id(), 403);
        $validated = $request->validate([
            'duration' => ['nullable', 'in:6_month,12_month'],
        ]);

        $duration = $validated['duration'] ?? '6_month';
        $simulation = $simulationService->run($startup, $duration);
        $fraud = $fraudDetectionService->analyze($startup);

        return response()->json(
            [
                'mode' => 'preview',
                'startup' => [
                    'id' => $startup->id,
                    'name' => $startup->name,
                ],
                'simulation' => $simulation,
                'fraud_detection' => $fraud,
            ]
        );
    }

    public function run(
        Request $request,
        Startup $startup,
        SimulationService $simulationService,
        FraudAnalysisService $fraudAnalysisService,
        AlertService $alertService
    ): JsonResponse
    {
        abort_unless($startup->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'duration' => ['nullable', 'in:6_month,12_month'],
        ]);

        $duration = $validated['duration'] ?? '6_month';
        $simulation = $simulationService->run($startup, $duration);
        $fraud = $fraudAnalysisService->runForStartup($startup);
        $alertService->storeSimulationAlerts($startup, $simulation);

        return response()->json(
            [
                'mode' => 'executed',
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
