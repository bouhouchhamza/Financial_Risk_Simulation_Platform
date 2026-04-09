<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $startup = auth()->user()?->startup;
        $summary = $this->dashboardService->summarizeForStartup($startup);

        return view('dashboard', array_merge(
            ['startup' => $startup],
            $summary
        ));
    }
}
