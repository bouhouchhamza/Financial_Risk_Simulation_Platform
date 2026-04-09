@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Report #{{ $report->id }}</h1>
    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn">Back to Reports</a>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Report Details</h2>
    <div class="grid">
        <div>
            <p class="stat-label">Risk Level</p>
            @php
                $risk = strtolower((string) $report->risk_level);
                $riskClass = match ($risk) {
                    'high' => 'badge-high',
                    'medium' => 'badge-medium',
                    default => 'badge-low',
                };
            @endphp
            <p><span class="badge {{ $riskClass }}">{{ strtoupper($report->risk_level ?: 'low') }}</span></p>
        </div>
        <div>
            <p class="stat-label">Created</p>
            <p>{{ optional($report->created_at)->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="rule-item">
        <p class="stat-label">Summary</p>
        <p>{{ $report->summary ?: 'No summary available.' }}</p>
    </div>

    <div class="rule-item">
        <p class="stat-label">Recommendations</p>
        <p>{{ $report->recommendations ?: 'No recommendations available.' }}</p>
    </div>
</div>
@endsection
