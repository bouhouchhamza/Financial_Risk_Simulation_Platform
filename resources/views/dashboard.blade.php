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

    $cashflow = (float) $totalRevenue - (float) $totalExpenses;
    $marginPercent = $totalRevenue > 0 ? round(($cashflow / (float) $totalRevenue) * 100, 1) : 0;
    $expensePercent = $totalRevenue > 0 ? round(((float) $totalExpenses / (float) $totalRevenue) * 100, 1) : 0;

    $safeTransactions = max((int) $totalTransactions - (int) $alertsCount, 0);
    $riskPercent = $totalTransactions > 0
        ? (int) round(((int) $alertsCount / (int) $totalTransactions) * 100)
        : 0;

    $weeklyMap = [];
    $monthlyMap = [];

    foreach ($dailyTransactions as $date => $count) {
        try {
            $pointDate = \Illuminate\Support\Carbon::parse($date);

            $weekKey = 'W' . $pointDate->isoWeek() . ' ' . $pointDate->format('Y');
            $monthlyKey = $pointDate->format('M Y');

            $weeklyMap[$weekKey] = ($weeklyMap[$weekKey] ?? 0) + (int) $count;
            $monthlyMap[$monthlyKey] = ($monthlyMap[$monthlyKey] ?? 0) + (int) $count;
        } catch (\Throwable $e) {
            // Skip invalid labels safely.
        }
    }

    $weeklyLabels = array_keys($weeklyMap);
    $weeklyValues = array_values($weeklyMap);

    $monthlyLabels = array_keys($monthlyMap);
    $monthlyValues = array_values($monthlyMap);
@endphp

<section class="unified-user-page dashboard-page dashboard-figma-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h3 class="card-title">Financial intelligence overview for your startup operations.</h3>
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
        <article class="card chart-card">
            <div class="card-header">
                <h3 class="card-title">No Startup Found</h3>
            </div>
            <p class="empty-state">Create your startup to unlock dashboard metrics, analytics, and alerts.</p>
            <div class="form-actions">
                <a href="{{ route('startup.create') }}" class="btn btn-primary">Create Startup</a>
            </div>
        </article>
    @else
        <div class="dashboard-blueprint">
            <div class="dashboard-grid dashboard-grid-stats">
                <article class="card stat-card">
                    <p class="stat-label">Total Revenue</p>
                    <p class="stat-value">${{ number_format((float) $totalRevenue, 2) }}</p>
                    <p class="stat-meta stat-trend {{ $marginPercent >= 0 ? 'is-positive' : 'is-negative' }}">
                        {{ $marginPercent >= 0 ? '+' : '-' }}{{ number_format(abs($marginPercent), 1) }}% margin
                    </p>
                </article>

                <article class="card stat-card">
                    <p class="stat-label">Total Expenses</p>
                    <p class="stat-value">${{ number_format((float) $totalExpenses, 2) }}</p>
                    <p class="stat-meta stat-trend {{ $expensePercent > 100 ? 'is-negative' : 'is-positive' }}">
                        {{ $expensePercent > 100 ? '+' : '-' }}{{ number_format(abs($expensePercent), 1) }}% of revenue
                    </p>
                </article>

                <article class="card stat-card">
                    <p class="stat-label">Cashflow</p>
                    <p class="stat-value">${{ number_format($cashflow, 2) }}</p>
                    <p class="stat-meta stat-trend {{ $cashflow >= 0 ? 'is-positive' : 'is-negative' }}">
                        {{ $cashflow >= 0 ? '+' : '-' }}{{ number_format(abs($cashflow), 2) }} net
                    </p>
                </article>
            </div>

            <div class="dashboard-grid dashboard-grid-main">
                <article class="card chart-card main-chart-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Cashflow Activity</h3>
                            <p class="card-subtitle">Daily and weekly transaction behavior.</p>
                        </div>
                        <div class="chart-toggles" data-user-main-chart-toggle>
                            <button type="button" class="chart-toggle-btn is-active" data-mode="daily">Daily</button>
                            <button type="button" class="chart-toggle-btn" data-mode="weekly">Weekly</button>
                        </div>
                    </div>
                    <div class="chart-wrapper dashboard-main-chart-wrap">
                        <canvas
                            id="userMainPerformanceChart"
                            data-daily-labels='@json($trendLabels)'
                            data-daily-values='@json($trendValues)'
                            data-weekly-labels='@json($weeklyLabels)'
                            data-weekly-values='@json($weeklyValues)'
                        ></canvas>
                    </div>
                </article>

                <aside class="dashboard-side-stack">
                    <article class="card chart-card risk-circle-card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Risk Circle</h3>
                                <p class="card-subtitle">Alert pressure against transaction volume.</p>
                            </div>
                        </div>
                        <div class="risk-circle-wrap">
                            <canvas
                                id="userRiskDonutChart"
                                data-labels='@json(["Alerts", "Safe"])'
                                data-values='@json([(int) $alertsCount, (int) $safeTransactions])'
                                data-risk-percent="{{ $riskPercent }}"
                            ></canvas>
                            <div class="risk-circle-copy">
                                <span>{{ $riskPercent }}%</span>
                                <small>Risk Index</small>
                            </div>
                        </div>
                    </article>

                    <article class="card chart-card alerts-summary-card">
                        <div class="alerts-summary-grid">
                            <div class="alerts-mini-card">
                                <p class="stat-label">Active Alerts</p>
                                <p class="stat-value">{{ $alertsCount }}</p>
                                <p class="stat-meta">Detected by fraud rules</p>
                            </div>
                            <div class="alerts-mini-card">
                                <p class="stat-label">Safe Transactions</p>
                                <p class="stat-value">{{ $safeTransactions }}</p>
                                <p class="stat-meta">Normal activity records</p>
                            </div>
                        </div>
                    </article>
                </aside>
            </div>

            <div class="dashboard-grid dashboard-grid-bottom">
                <article class="card chart-card monthly-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Monthly Performance</h3>
                            <p class="card-subtitle">Monthly transaction volume by period.</p>
                        </div>
                    </div>
                    <div class="chart-wrapper dashboard-bottom-chart-wrap">
                        <canvas
                            id="userMonthlyPerformanceChart"
                            data-labels='@json($monthlyLabels)'
                            data-values='@json($monthlyValues)'
                        ></canvas>
                    </div>
                </article>

                <article class="card chart-card allocation-card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Allocation</h3>
                            <p class="card-subtitle">Revenue and expense distribution.</p>
                        </div>
                    </div>
                    <div class="allocation-wrap">
                        <div class="allocation-chart">
                            <canvas
                                id="userAllocationChart"
                                data-labels='@json(["Revenue", "Expenses"])'
                                data-values='@json([(float) $totalRevenue, (float) $totalExpenses])'
                            ></canvas>
                        </div>
                        <ul class="allocation-legend">
                            <li>
                                <span class="legend-dot is-revenue"></span>
                                <div>
                                    <small>Revenue</small>
                                    <strong>${{ number_format((float) $totalRevenue, 2) }}</strong>
                                </div>
                            </li>
                            <li>
                                <span class="legend-dot is-expenses"></span>
                                <div>
                                    <small>Expenses</small>
                                    <strong>${{ number_format((float) $totalExpenses, 2) }}</strong>
                                </div>
                            </li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    @endif
</section>
@endsection
