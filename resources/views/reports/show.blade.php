@extends('layouts.app')

@section('content')
@php
    $risk = strtolower((string) $report->risk_level);
    $riskClass = match ($risk) {
        'high' => 'badge-high',
        'medium' => 'badge-medium',
        default => 'badge-low',
    };
@endphp

<section class="unified-user-page reports-page reports-show-page reports-figma-page reports-show-figma-page">
    <header class="reports-hero">
        <div class="reports-hero-copy">
            <p class="reports-kicker">Report Record</p>
            <h1 class="page-title">{{ $report->title ?: ('Report #' . $report->id) }}</h1>
            <p class="page-subtitle">Detailed report output generated from simulation/risk data.</p>
        </div>
        <div class="reports-hero-actions">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Back to Reports</a>
            @if($report->file_path)
                <a href="{{ route('reports.download', $report->id) }}" class="btn btn-secondary">Download</a>
            @endif
        </div>
    </header>

    <article class="card reports-details-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Report Details</h2>
                <p class="card-subtitle">Core metadata and linked simulation context.</p>
            </div>
        </div>
        <div class="reports-meta-grid">
            <div class="kv-item">
                <p class="stat-label">Type</p>
                <p class="kv-value">{{ $report->type ?: 'Simulation Report' }}</p>
            </div>
            <div class="kv-item">
                <p class="stat-label">Risk Level</p>
                <p><span class="badge {{ $riskClass }}">{{ strtoupper((string) ($report->risk_level ?: 'low')) }}</span></p>
            </div>
            <div class="kv-item">
                <p class="stat-label">Generated</p>
                <p class="kv-value">{{ optional($report->generated_at ?: $report->created_at)->format('Y-m-d H:i') }}</p>
            </div>
            <div class="kv-item">
                <p class="stat-label">Linked Simulation</p>
                @if($report->simulation)
                    <p class="kv-value">
                        #{{ $report->simulation->id }} -
                        {{ str_replace('_', ' ', (string) $report->simulation->duration) }}
                    </p>
                @else
                    <p class="kv-value text-muted">No simulation linked.</p>
                @endif
            </div>
            <div class="kv-item">
                <p class="stat-label">Created At</p>
                <p class="kv-value">{{ optional($report->created_at)->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="rule-item reports-detail-block">
            <p class="stat-label">Summary</p>
            <p>{{ $report->summary ?: 'No summary available.' }}</p>
        </div>

        <div class="rule-item reports-detail-block">
            <p class="stat-label">Recommendations</p>
            <p>{{ $report->recommendations ?: 'No recommendations available.' }}</p>
        </div>

        @if($report->simulation)
            <div class="rule-item reports-detail-block">
                <p class="stat-label">Simulation Snapshot</p>
                <div class="key-value-grid">
                    <div class="kv-item">
                        <p class="kv-label">Projected Revenue</p>
                        <p class="kv-value">${{ number_format((float) $report->simulation->total_revenue, 2) }}</p>
                    </div>
                    <div class="kv-item">
                        <p class="kv-label">Projected Expenses</p>
                        <p class="kv-value">${{ number_format((float) $report->simulation->total_expenses, 2) }}</p>
                    </div>
                    <div class="kv-item">
                        <p class="kv-label">Net Cashflow</p>
                        <p class="kv-value">${{ number_format((float) $report->simulation->final_cashflow, 2) }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-actions">
            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Delete this report?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Report</button>
            </form>
        </div>
    </article>
</section>
@endsection
