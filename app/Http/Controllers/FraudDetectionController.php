<?php

namespace App\Http\Controllers;

use App\Services\FraudDetectionService;
use Illuminate\Http\Request;
use App\Models\Startup;

class FraudDetectionController  extends Controller
{
    public function show(Startup $startup, FraudDetectionService $fraudDetectionService){
        abort_unless($startup->user_id === auth()->id(),403);
        $result = $fraudDetectionService->analyze($startup);
        return response()->json($result);
    }
}
