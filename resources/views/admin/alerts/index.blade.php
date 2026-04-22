@extends('layouts.admin')

@section('content')
@php
    $criticalCount = $alerts->where('severity', 'high')->count();
    $warningCount = $alerts->where('severity', 'medium')->count();
@endphp

<div class="alerts-page">
    <div class="alerts-header">
        <div>
            <h1 class="alerts-title">Risk Alerts</h1>
            <p class="alerts-subtitle">
                Real-time surveillance of global startup transactions. Action required on detected anomalies.
            </p>
        </div>

        <div class="alerts-stats">
            <div class="alert-stat-card">
                <div class="alert-stat-icon critical">CR</div>
                <div>
                    <div class="alert-stat-label">Critical</div>
                    <div class="alert-stat-value">{{ $criticalCount }}</div>
                </div>
            </div>

            <div class="alert-stat-card">
                <div class="alert-stat-icon warning">WR</div>
                <div>
                    <div class="alert-stat-label">Warning</div>
                    <div class="alert-stat-value">{{ $warningCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="alerts-feed">
        @forelse($alerts as $alert)
            @php
                $severity = strtolower($alert->severity ?? 'low');

                $tone = match($severity) {
                    'high' => 'critical',
                    'medium' => 'warning',
                    default => 'safe',
                };

                $badgeText = match($severity) {
                    'high' => 'Danger',
                    'medium' => 'Warning',
                    default => 'Info',
                };

                $amount = $alert->transaction->amount ?? null;
                $eventTime = $alert->transaction->created_at ?? $alert->created_at;
                $entity = $alert->startup->name ?? 'Unknown Startup';
            @endphp

            <div class="alert-card {{ $tone }}">
                <div class="alert-main">
                    <div class="alert-top-row">
                        <div class="alert-title-wrap">
                            <div class="alert-icon {{ $tone }}">
                                {{ $tone === 'critical' ? 'AL' : ($tone === 'warning' ? 'WR' : 'IN') }}
                            </div>
                            <h3>{{ ucwords(str_replace('_', ' ', $alert->type ?? $alert->rule_code ?? 'Fraud alert')) }}</h3>
                        </div>

                        <span class="alert-badge {{ $tone }}">{{ $badgeText }}</span>
                    </div>

                    <p class="alert-message">
                        {{ $alert->message }}
                    </p>

                    <div class="alert-meta-grid">
                        <div class="alert-meta-item">
                            <span class="meta-label">Amount</span>
                            <strong>{{ $amount !== null ? number_format((float) $amount, 2) . ' MAD' : 'N/A' }}</strong>
                        </div>

                        <div class="alert-meta-item">
                            <span class="meta-label">Detected</span>
                            <strong>{{ $eventTime ? $eventTime->format('M d, Y - H:i') : 'N/A' }}</strong>
                        </div>

                        <div class="alert-meta-item">
                            <span class="meta-label">Entity</span>
                            <strong>{{ $entity }}</strong>
                        </div>
                    </div>
                </div>

                <div class="alert-actions-panel">
                    @if(($alert->review_status ?? 'pending_review') === 'pending_review')
                        @if($severity === 'high')
                            <form method="POST" action="{{ route('admin.alerts.confirmFraud', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn danger-btn">
                                    Confirm Fraud
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.alerts.markFalsePositive', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn secondary-btn">
                                    Mark False Positive
                                </button>
                            </form>
                        @elseif($severity === 'medium')
                            <form method="POST" action="{{ route('admin.alerts.approve', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn primary-btn">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.alerts.reject', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn secondary-btn">
                                    Reject
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.alerts.approve', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn primary-btn">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.alerts.reject', $alert->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-btn secondary-btn">
                                    Reject
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="reviewed-box">
                            <span class="meta-label">Reviewed</span>
                            <strong>{{ ucwords(str_replace('_', ' ', $alert->review_status)) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-alerts-card">
                <h3>No alerts found</h3>
                <p>The monitoring system has not generated any alerts yet.</p>
            </div>
        @endforelse
    </div>

    <div class="alerts-pagination">
        {{ $alerts->links() }}
    </div>
</div>
@endsection
