<?php

namespace App\Http\Controllers;

use App\Models\Startup;
use App\Services\FraudAnalysisService;
use Illuminate\View\View;

class FraudDetectionController extends Controller
{
    public function show(Startup $startup, FraudAnalysisService $fraudAnalysisService): View
    {
        abort_unless($startup->user_id === auth()->id(), 403);

        $result = $fraudAnalysisService->runForStartup($startup);

        return view('fraud_detection.show', compact('startup', 'result'));
    }
}
