@extends('layouts.admin')

@section('content')
@php
    $totalVolume = $transactions->sum('amount');
    $inflow = $transactions->where('type', 'sale')->sum('amount');

    $outflow = $transactions->where('type', 'purchase')->sum('amount');
    $transferVolume = $transactions->where('type', 'transfer')->sum('amount');

    $liquidityRatio = $outflow > 0 ? round($inflow / max($outflow, 1), 2) : 0;
    $suspiciousCount = $transactions->where('is_suspicious', true)->count();
@endphp

<div class="admin-transactions-page">

    <div class="transactions-header">
        <div class="hero-metric-card">
            <div class="hero-metric-kicker">Total Ledger Volume</div>
            <div class="hero-metric-value">{{ number_format((float) $totalVolume, 2) }} MAD</div>
            <div class="hero-metric-trend">Upward live transaction monitoring</div>
            <div class="hero-accent-line"></div>
        </div>

        <div class="metrics-panel">
            <div class="mini-metric">
                <div class="mini-label">Outflow</div>
                <div class="mini-value">{{ number_format((float) $outflow, 2) }} MAD</div>
                <div class="mini-bar">
                    <span class="mini-bar-fill red" style="width: 33%;"></span>
                </div>
            </div>

            <div class="mini-metric">
                <div class="mini-label">Inflow</div>
                <div class="mini-value">{{ number_format((float) $inflow, 2) }} MAD</div>
                <div class="mini-bar">
                    <span class="mini-bar-fill green" style="width: 66%;"></span>
                </div>
            </div>

            <div class="mini-metric">
                <div class="mini-label">Liquidity Ratio (Sale/Purchase)</div>
                <div class="mini-value">{{ $liquidityRatio }}</div>
                <div class="mini-bar">
                    <span class="mini-bar-fill blue" style="width: 80%;"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="transactions-toolbar">
        <div class="toolbar-filters">
            <div class="toolbar-chip">All Startups</div>
            <div class="toolbar-chip">All Types</div>
            <div class="toolbar-chip">All Status</div>
            <div class="toolbar-link">Reset filters</div>
        </div>

        <div class="toolbar-actions">
            <a href="{{ route('admin.alerts.index') }}" class="toolbar-btn secondary-btn">
                View Alerts
            </a>
            <a href="{{ route('admin.dashboard') }}" class="toolbar-btn primary-btn">
                Return Dashboard
            </a>
        </div>
    </div>

    <div class="transactions-table-card">
        <div class="table-scroll">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Startup</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Risk</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($transactions as $txn)
                        @php
                            $type = strtolower((string) $txn->type);
                            $isSuspicious = (bool) $txn->is_suspicious;

                            $typeTone = match($type) {
                                'sale' => 'income',
                                'purchase' => 'expense',
                                default => 'neutral',
                            };
                        @endphp

                        <tr>
                            <td>
                                <div class="txn-cell">
                                    <div class="txn-avatar">
                                        {{ strtoupper(substr((string) ($txn->startup->name ?? 'T'), 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="txn-id">#{{ $txn->id }}</div>
                                        <div class="txn-sub">
                                            {{ $txn->reference ?? 'Ledger entry' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="startup-name-cell">
                                    {{ $txn->startup->name ?? 'N/A' }}
                                </div>
                            </td>

                            <td class="amount-cell">
                                <span class="amount-accent {{ $typeTone }}"></span>
                                {{ number_format((float) $txn->amount, 2) }} MAD
                            </td>

                            <td>
                                <span class="type-pill">
                                    {{ ucfirst($txn->type) }}
                                </span>
                            </td>

                            <td>
                                @if($isSuspicious)
                                    <span class="risk-pill suspicious">
                                        <span class="risk-dot"></span>
                                        Suspicious
                                    </span>
                                @else
                                    <span class="risk-pill safe">
                                        <span class="risk-dot"></span>
                                        Safe
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($isSuspicious)
                                    <span class="status-pill danger">Flagged</span>
                                @else
                                    <span class="status-pill good">Normal</span>
                                @endif
                            </td>

                            <td class="date-cell">
                                {{ $txn->transaction_date?->format('Y-m-d') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="table-footer-text">
                Showing monitored ledger entries. Suspicious count: {{ $suspiciousCount }} | Transfer volume: {{ number_format((float) $transferVolume, 2) }} MAD
            </div>
            <div class="pagination-wrap">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
