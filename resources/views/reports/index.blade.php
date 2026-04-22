@extends('layouts.app')

@section('content')
@php
    $reportItems = method_exists($reports, 'getCollection') ? $reports->getCollection() : collect($reports);
    $reportsCount = (int) $reportItems->count();
    $highRiskCount = (int) $reportItems->filter(fn ($report) => strtolower((string) $report->risk_level) === 'high')->count();
    $withFileCount = (int) $reportItems->filter(fn ($report) => !empty($report->file_path))->count();
@endphp

<section class="unified-user-page reports-page reports-index-page reports-figma-page">
    <header class="reports-hero">
        <div class="reports-hero-copy">
            <p class="reports-kicker">Risk Archive</p>
            <h1 class="page-title">Reports</h1>
            <p class="page-subtitle">Simulation and risk reports generated from your startup activity.</p>
        </div>
        <div class="reports-hero-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
        </div>
    </header>

    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="notice notice-error">{{ session('error') }}</div>
    @endif

    <div class="reports-metrics-grid">
        <article class="card reports-metric-card reports-metric-card-primary">
            <p class="stat-label">Listed Reports</p>
            <p class="reports-metric-value">{{ $reportsCount }}</p>
            <p class="reports-metric-meta">Reports visible on this page</p>
        </article>

        <article class="card reports-metric-card">
            <p class="stat-label">High Risk</p>
            <p class="reports-metric-value">{{ $highRiskCount }}</p>
            <p class="reports-metric-meta">Reports marked with high risk level</p>
        </article>

        <article class="card reports-metric-card">
            <p class="stat-label">Downloadable</p>
            <p class="reports-metric-value">{{ $withFileCount }}</p>
            <p class="reports-metric-meta">Reports with file attachments</p>
        </article>
    </div>

    <article class="card reports-table-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Generated Reports</h2>
                <p class="card-subtitle">Latest simulation/risk reports for your startup.</p>
            </div>
        </div>

        @if($reports->isEmpty())
            <p class="empty-state">No reports generated yet. Run a simulation to generate your first report.</p>
        @else
            <div class="table-wrap">
                <table class="table reports-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Simulation</th>
                            <th>Risk Level</th>
                            <th>Generated</th>
                            <th class="is-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>
                                    <strong>{{ $report->title ?: ('Report #' . $report->id) }}</strong>
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit((string) $report->summary, 90) }}</div>
                                </td>
                                <td>{{ $report->type ?: 'Simulation Report' }}</td>
                                <td>
                                    @if($report->simulation)
                                        #{{ $report->simulation->id }} ({{ str_replace('_', ' ', (string) $report->simulation->duration) }})
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $risk = strtolower((string) $report->risk_level);
                                        $riskClass = match ($risk) {
                                            'high' => 'badge-high',
                                            'medium' => 'badge-medium',
                                            default => 'badge-low',
                                        };
                                    @endphp
                                    <span class="badge {{ $riskClass }}">{{ strtoupper((string) ($report->risk_level ?: 'low')) }}</span>
                                </td>
                                <td>{{ optional($report->generated_at ?: $report->created_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="reports-actions">
                                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-secondary">View</a>

                                        @if($report->file_path)
                                            <a href="{{ route('reports.download', $report->id) }}" class="btn btn-secondary">Download</a>
                                        @endif

                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $reports->links() }}
            </div>
        @endif
    </article>
</section>
@endsection
