@extends('layouts.app')

@section('page_title', 'Simulations')

@section('content')
@php
    $latestSimulation = $recentSimulations->first();
    $hasLatest = !is_null($latestSimulation);

    $projectionResults = $hasLatest
        ? $latestSimulation->simulationResults()->orderBy('month_number')->get()
        : collect();

    $projectionLabels = $projectionResults->map(fn ($result) => 'M' . $result->month_number)->values();
    $optimisticValues = $projectionResults->map(fn ($result) => (float) $result->revenue)->values();
    $conservativeValues = $projectionResults->map(fn ($result) => (float) $result->expenses)->values();

    $projectedRevenue = $hasLatest ? (float) $latestSimulation->total_revenue : null;
    $projectedExpenses = $hasLatest ? (float) $latestSimulation->total_expenses : null;
    $netCashflow = $hasLatest ? (float) $latestSimulation->final_cashflow : null;
    $riskLevel = $hasLatest ? strtoupper((string) $latestSimulation->risk_level) : null;

    $cashflowTone = $netCashflow === null ? 'is-neutral' : ($netCashflow >= 0 ? 'is-positive' : 'is-negative');
    $riskTone = match (strtolower((string) ($latestSimulation->risk_level ?? ''))) {
        'high' => 'risk-high',
        'medium' => 'risk-medium',
        'low' => 'risk-low',
        default => 'risk-neutral',
    };
@endphp

<div class="simulation-page">
    <div class="simulation-hero">
        <div class="simulation-hero-copy">
            <h1 class="page-title">Simulations</h1>
            <p class="page-subtitle">Model future startup performance using live operational data and scenario variance.</p>
        </div>

        <div class="simulation-mode-switch" data-mode-switch>
            <button type="button" class="mode-btn is-active" data-mode="live">Live Data</button>
            <button type="button" class="mode-btn" data-mode="historical">Historical</button>
        </div>
    </div>

    @if(!$startup)
        <div class="card simulation-empty-card">
            <h2 class="card-title">No Startup Connected</h2>
            <p class="empty-state">Create a startup before running simulations.</p>
            <div class="form-actions">
                <a href="{{ route('startup.create') }}" class="btn btn-primary">Create Startup</a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    @else
        <div class="simulation-main-grid">
            <div class="card simulation-config-card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Configure Run</h2>
                        <p class="card-subtitle">Tune the simulation horizon and launch a new projection.</p>
                    </div>
                </div>

                <form action="{{ route('simulations.store') }}" method="POST" class="form-grid simulation-form">
                    @csrf

                    <div>
                        <label for="simulationStartup">Select Startup</label>
                        <select id="simulationStartup" class="select" disabled>
                            <option>{{ $startup->name }}</option>
                        </select>
                    </div>

                    <div>
                        <label>Simulation Duration</label>
                        <div class="simulation-duration-toggle" role="radiogroup" aria-label="Simulation duration">
                            <label class="duration-pill">
                                <input type="radio" name="duration" value="6_month" {{ old('duration', '6_month') === '6_month' ? 'checked' : '' }} required>
                                <span>6 Months</span>
                            </label>
                            <label class="duration-pill">
                                <input type="radio" name="duration" value="12_month" {{ old('duration') === '12_month' ? 'checked' : '' }} required>
                                <span>12 Months</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Run Simulation</button>
                    </div>
                </form>
            </div>

            <div class="simulation-metrics-grid">
                <div class="card simulation-metric-card">
                    <p class="stat-label">Projected Revenue</p>
                    <p class="stat-value is-positive">{{ $projectedRevenue !== null ? '$' . number_format($projectedRevenue, 2) : 'N/A' }}</p>
                    <p class="stat-meta">{{ $hasLatest ? 'From latest simulation run' : 'Run a simulation to populate' }}</p>
                </div>

                <div class="card simulation-metric-card">
                    <p class="stat-label">Projected Expenses</p>
                    <p class="stat-value">{{ $projectedExpenses !== null ? '$' . number_format($projectedExpenses, 2) : 'N/A' }}</p>
                    <p class="stat-meta">{{ $hasLatest ? 'Expected outgoing cashflow' : 'Awaiting simulation data' }}</p>
                </div>

                <div class="card simulation-metric-card">
                    <p class="stat-label">Net Cashflow</p>
                    <p class="stat-value {{ $cashflowTone }}">{{ $netCashflow !== null ? '$' . number_format($netCashflow, 2) : 'N/A' }}</p>
                    <p class="stat-meta">{{ $hasLatest ? 'Revenue minus expenses' : 'Not available yet' }}</p>
                </div>

                <div class="card simulation-metric-card">
                    <p class="stat-label">Risk Index</p>
                    <p class="stat-value"><span class="simulation-risk-pill {{ $riskTone }}">{{ $riskLevel ?? 'N/A' }}</span></p>
                    <p class="stat-meta">{{ $hasLatest ? 'Model risk assessment level' : 'No projection assessed' }}</p>
                </div>
            </div>
        </div>

        <div class="card simulation-chart-card">
            <div class="card-header simulation-chart-header">
                <div>
                    <h2 class="card-title">Cashflow Projection Curve</h2>
                    <p class="card-subtitle">{{ $hasLatest ? 'Scenario spread from your latest run variance.' : 'Run a simulation to view projection curves.' }}</p>
                </div>

                <div class="simulation-legend">
                    <span class="legend-item optimistic"><i></i>Optimistic</span>
                    <span class="legend-item conservative"><i></i>Conservative</span>
                </div>
            </div>

            <div class="simulation-chart-wrap">
                <canvas
                    id="simulationProjectionChart"
                    data-labels='@json($projectionLabels)'
                    data-optimistic='@json($optimisticValues)'
                    data-conservative='@json($conservativeValues)'></canvas>

                @if(!$hasLatest)
                    <p class="empty-state simulation-chart-empty">No projection data available yet.</p>
                @endif
            </div>
        </div>

        <div class="card simulation-table-card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Recent Simulations</h2>
                    <p class="card-subtitle">Most recent simulation runs with risk and final cashflow output.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Duration</th>
                            <th>Cashflow</th>
                            <th>Risk</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSimulations as $simulation)
                            <tr>
                                <td>#{{ $simulation->id }}</td>
                                <td>{{ str_replace('_', ' ', $simulation->duration) }}</td>
                                <td>${{ number_format((float) $simulation->final_cashflow, 2) }}</td>
                                <td>
                                    @php
                                        $risk = strtolower((string) $simulation->risk_level);
                                        $riskClass = match ($risk) {
                                            'high' => 'badge-high',
                                            'medium' => 'badge-medium',
                                            default => 'badge-low',
                                        };
                                    @endphp
                                    <span class="badge {{ $riskClass }}">{{ strtoupper((string) $simulation->risk_level) }}</span>
                                </td>
                                <td><a class="btn" href="{{ route('simulations.show', $simulation->id) }}">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No simulations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="simulation-footer">
        <p>Sentinel Finance</p>
        <div class="simulation-footer-links">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Security</a>
            <a href="#">Status</a>
        </div>
    </div>
</div>
@endsection
