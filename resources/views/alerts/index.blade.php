@extends('layouts.app')

@section('page_title', 'Alerts')

@section('content')
<section class="unified-user-page alerts-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h2 class="page-title">Alerts</h2>
            <p class="page-subtitle">Fraud and anomaly alerts generated from detection rules.</p>
        </div>
        <div class="page-actions">
            @if(isset($startup))
            <form method="POST" action="{{ route('fraud-detection.run', $startup->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-primary" data-loading-text="Analyzing...">Run Fraud Detection</button>
            </form>
            @endif
        </div>
    </div>

    <div class="card dashboard-panel">
        <div class="card-header">
            <div>
                <h3 class="card-title">Alert Feed</h3>
                <p class="card-subtitle">Review severity and related transaction context.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rule Code</th>
                        <th>Message</th>
                        <th>Severity</th>
                        <th>Related Transaction</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                    <tr class="{{ strtolower((string) $alert->severity) === 'high' ? 'row-danger' : '' }}">
                        <td>#{{ $alert->id }}</td>
                        <td>{{ $alert->rule_code ?: $alert->type }}</td>
                        <td>{{ $alert->message }}</td>
                        <td>
                            @php
                            $severityClass = match (strtolower($alert->severity)) {
                            'high' => 'badge-high',
                            'medium' => 'badge-medium',
                            default => 'badge-low',
                            };
                            @endphp
                            <span class="badge {{ $severityClass }}">{{ strtoupper($alert->severity) }}</span>
                        </td>
                        <td>
                            @if($alert->transaction)
                            <a href="{{ route('transactions.show', $alert->transaction->id) }}" class="btn btn-secondary">
                                #{{ $alert->transaction->id }}
                            </a>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $reviewClass = match ($alert->review_status) {
                            'approved' => 'badge-high',
                            'rejected' => 'badge-low',
                            'confirmed_fraud' => 'badge-high',
                            'false_positive' => 'badge-medium',
                            default => 'badge-primary',
                            };
                            @endphp

                            <span class="badge {{ $reviewClass }}">
                                {{ strtoupper($alert->review_status) }}
                            </span>
                        </td>
                        <td>{{ optional($alert->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">No alerts available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($alerts, 'links'))
        <div class="pagination-wrap small text-muted">
            {{ $alerts->links() }}
        </div>
        @endif
    </div>
</section>
@endsection
