<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Services\FraudAnalysisService;
use App\Services\FraudDetectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FraudDetectionController extends Controller
{
    public function show(Startup $startup, FraudDetectionService $fraudDetectionService): View
    {
        abort_unless($startup->user_id === auth()->id(), 403);

        $result = $fraudDetectionService->analyze($startup);

        return view('fraud_detection.show', compact('startup', 'result'));
    }

    public function run(Startup $startup, FraudAnalysisService $fraudAnalysisService): RedirectResponse
    {
        abort_unless($startup->user_id === auth()->id(), 403);

        $fraudAnalysisService->runForStartup($startup);

        return redirect()
            ->route('fraud-detection.show', $startup)
            ->with('success', 'Fraud detection completed. Alerts and suspicious flags were updated.');
    }
}
