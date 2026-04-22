@extends('layouts.app')

@section('page_title', 'Simulation Details')

@section('content')
@php
    $results = $simulation->simulationResults()->orderBy('month_number')->get();
    $linkedReport = $simulation->reports()->latest('generated_at')->first();
    $durationLabel = ucfirst(str_replace('_', ' ', (string) $simulation->duration));
    $cashflowTone = (float) $simulation->final_cashflow >= 0 ? 'is-positive' : 'is-negative';
    $riskTone = match (strtolower((string) ($simulation->risk_level ?? ''))) {
        'high' => 'risk-high',
        'medium' => 'risk-medium',
        'low' => 'risk-low',
        default => 'risk-neutral',
    };
@endphp

<section class="unified-user-page simulation-page simulation-detail-page">
    <header class="simulation-hero">
        <div class="simulation-hero-copy">
            <h1 class="page-title">Simulation #{{ $simulation->id }}</h1>
            <p class="page-subtitle">Stored projection details, linked report access, and monthly output from this simulation run.</p>
        </div>

        <div class="form-actions">
            <a href="{{ route('simulations.index') }}" class="btn btn-secondary">Back to Simulations</a>
            @if($linkedReport)
                <a href="{{ route('reports.show', $linkedReport->id) }}" class="btn btn-primary">View Linked Report</a>
            @endif
        </div>
    </header>

    <div class="simulation-metrics-grid">
        <article class="card simulation-metric-card">
            <p class="stat-label">Duration</p>
            <p class="stat-value">{{ $durationLabel }}</p>
            <p class="stat-meta">Configured projection horizon</p>
        </article>

        <article class="card simulation-metric-card">
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value is-positive">${{ number_format((float) $simulation->total_revenue, 2) }}</p>
            <p class="stat-meta">Aggregate projected inflow</p>
        </article>

        <article class="card simulation-metric-card">
            <p class="stat-label">Total Expenses</p>
            <p class="stat-value">${{ number_format((float) $simulation->total_expenses, 2) }}</p>
            <p class="stat-meta">Aggregate projected outflow</p>
        </article>

        <article class="card simulation-metric-card">
            <p class="stat-label">Final Cashflow</p>
            <p class="stat-value {{ $cashflowTone }}">${{ number_format((float) $simulation->final_cashflow, 2) }}</p>
            <p class="stat-meta">Revenue minus expenses</p>
        </article>
    </div>

    <article class="card simulation-table-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Linked Report</h2>
                <p class="card-subtitle">Report record generated from this simulation.</p>
            </div>
            <span class="simulation-risk-pill {{ $riskTone }}">{{ strtoupper((string) ($simulation->risk_level ?? 'n/a')) }}</span>
        </div>

        @if($linkedReport)
            <div class="key-value-grid">
                <div class="kv-item">
                    <p class="kv-label">Report</p>
                    <p class="kv-value">{{ $linkedReport->title ?: ('Report #' . $linkedReport->id) }}</p>
                </div>
                <div class="kv-item">
                    <p class="kv-label">Generated</p>
                    <p class="kv-value">{{ optional($linkedReport->generated_at ?: $linkedReport->created_at)->format('Y-m-d H:i') }}</p>
                </div>
                <div class="kv-item">
                    <p class="kv-label">Archive</p>
                    <p class="kv-value">Open the reports area to review all generated outputs.</p>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">All Reports</a>
            </div>
        @else
            <p class="empty-state">No report is linked to this simulation yet.</p>
        @endif
    </article>

    <article class="card simulation-table-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Monthly Results</h2>
                <p class="card-subtitle">Revenue, expenses, cashflow, and critical-month output for each period.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Revenue</th>
                        <th>Expenses</th>
                        <th>Cashflow</th>
                        <th>Critical</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="{{ $result->is_critical ? 'row-suspicious' : '' }}">
                            <td>{{ $result->month_number }}</td>
                            <td>${{ number_format((float) $result->revenue, 2) }}</td>
                            <td>${{ number_format((float) $result->expenses, 2) }}</td>
                            <td>${{ number_format((float) $result->cashflow, 2) }}</td>
                            <td>
                                <span class="badge {{ $result->is_critical ? 'badge-high' : 'badge-low' }}">
                                    {{ $result->is_critical ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No monthly results found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <div class="simulation-footer">
        <p>Sentinel Finance</p>
        <div class="simulation-footer-links">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Security</a>
            <a href="#">Status</a>
        </div>
    </div>
</section>
@endsection
