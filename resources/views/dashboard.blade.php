@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
@php
    $dailyTransactions = $transactionsPerDay ?? $transactions_per_day ?? $transactions_by_day ?? [];

    if ($dailyTransactions instanceof \Illuminate\Support\Collection) {
        $dailyTransactions = $dailyTransactions->toArray();
    }

    if (empty($dailyTransactions) && isset($lastTransactions)) {
        $dailyTransactions = $lastTransactions
            ->groupBy(fn ($transaction) => optional($transaction->transaction_date)->format('Y-m-d') ?? 'Unknown')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    ksort($dailyTransactions);
    $trendLabels = array_keys($dailyTransactions);
    $trendValues = array_values($dailyTransactions);
@endphp

<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Financial Overview</h2>
        <p class="page-subtitle">Track your startup metrics, risk signals, and recent activity.</p>
    </div>
    <div class="page-actions">
        @if($startup)
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">Add Transaction</a>
            <form method="POST" action="{{ route('fraud-detection.run', $startup->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-secondary" data-loading-text="Analyzing...">Run Fraud Detection</button>
            </form>
        @else
            <a href="{{ route('startup.create') }}" class="btn btn-primary">Create Startup</a>
        @endif
    </div>
</div>

@if(!$startup)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">No Startup Found</h3>
        </div>
        <p class="empty-state">Create your startup to unlock dashboard metrics, fraud detection, and alerts.</p>
        <div class="form-actions">
            <a href="{{ route('startup.create') }}" class="btn btn-primary">Create Startup</a>
        </div>
    </div>
@else
    <div class="grid">
        <div class="card stat-card">
            <p class="stat-label">Total Transactions</p>
            <p class="stat-value">{{ $totalTransactions }}</p>
            <p class="stat-meta">Across all recorded startup operations.</p>
        </div>
        <div class="card stat-card">
            <p class="stat-label">Revenue</p>
            <p class="stat-value">${{ number_format((float) $totalRevenue, 2) }}</p>
            <p class="stat-meta">Total `sale` transactions.</p>
        </div>
        <div class="card stat-card">
            <p class="stat-label">Expenses</p>
            <p class="stat-value">${{ number_format((float) $totalExpenses, 2) }}</p>
            <p class="stat-meta">Total `purchase` transactions.</p>
        </div>
        <div class="card stat-card">
            <p class="stat-label">Alerts Count</p>
            <p class="stat-value">{{ $alertsCount }}</p>
            <p class="stat-meta">Triggered fraud or rule-based alerts.</p>
        </div>
    </div>

    <div class="charts-grid">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Revenue vs Expenses</h3>
                    <p class="card-subtitle">Compare income and outgoing cash flow.</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas
                    id="revenueExpensesChart"
                    data-revenue="{{ (float) $totalRevenue }}"
                    data-expenses="{{ (float) $totalExpenses }}"
                ></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Transactions Over Time</h3>
                    <p class="card-subtitle">Daily transaction volume trend.</p>
                </div>
            </div>
            <div class="chart-wrapper">
                <canvas
                    id="transactionsOverTimeChart"
                    data-labels='@json($trendLabels)'
                    data-values='@json($trendValues)'
                ></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Latest Transactions</h3>
                <p class="card-subtitle">Most recent 5 entries from your startup ledger.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lastTransactions as $transaction)
                        <tr class="{{ $transaction->is_suspicious ? 'row-suspicious' : '' }}">
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>${{ number_format((float) $transaction->amount, 2) }}</td>
                            <td>{{ optional($transaction->transaction_date)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge {{ $transaction->is_suspicious ? 'badge-high' : 'badge-success' }}">
                                    {{ $transaction->is_suspicious ? 'Suspicious' : 'Normal' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">View All Transactions</a>
        </div>
    </div>
@endif
@endsection
