@extends('layouts.app')

@section('page_title', 'Startups')

@section('content')

<section class="unified-user-page startups-page startups-figma-page">

    <header class="startups-header">
        <div class="startups-header-copy">
            <p class="startups-kicker">Portfolio Desk</p>
            <h2 class="page-title">Venture Startups</h2>
            <p class="page-subtitle">startup overview and metrics.</p>
        </div>

        <div class="startups-header-actions">
            <a href="{{ route('startup.create') }}" class="btn btn-primary">+ Add Startup</a>

            @if($startup)
            <a href="{{ route('startup.show') }}" class="btn btn-secondary">Open Startup</a>
            @endif
        </div>
    </header>

    @if(!$startup)

    <article class="startup-card startup-card-empty">
        <p class="startups-kicker">No Startup Found</p>
        <h3>Create your startup</h3>
        <div class="form-actions">
            <a href="{{ route('startup.create') }}" class="btn btn-primary">+ Add Startup</a>
        </div>
    </article>

    @else

    <!-- TOP CARDS -->
    <div class="startups-grid startups-grid-top">

        <!-- CAPITAL -->
        <article class="startup-card startup-card-compact">
            <div class="startup-card-top">
                <span class="startup-icon">CR</span>
                <span class="startup-score">{{ $capitalScore }}%</span>
            </div>

            <div class="startup-card-body">
                <h3>{{ $startup->name }}</h3>
                <p>Capital reserve level</p>
            </div>
        </article>

        <!-- REVENUE -->
        <article class="startup-card startup-card-compact">
            <div class="startup-card-top">
                <span class="startup-icon">RV</span>
                <span class="startup-score">{{ $revenueShare }}%</span>
            </div>

            <div class="startup-card-body">
                <h3>Revenue Share</h3>
                <p>Income vs total flow</p>
            </div>
        </article>

        <!-- EFFICIENCY -->
        <article class="startup-card startup-card-compact">
            <div class="startup-card-top">
                <span class="startup-icon">EX</span>
                <span class="startup-score">{{ $efficiencyScore }}%</span>
            </div>

            <div class="startup-card-body">
                <h3>Efficiency</h3>
                <p>Expense control level</p>
            </div>
        </article>

    </div>

    <!-- MAIN GRID -->
    <div class="startups-grid startups-grid-bottom">

        <!-- LEFT -->
        <article class="startup-card startup-card-featured">

            <h3>{{ $startup->name }}</h3>

            <div class="featured-metrics">
                <div>
                    <span>Initial Budget</span>
                    <strong>${{ number_format($initialBudget, 2) }}</strong>
                </div>

                <div>
                    <span>Cashflow</span>
                    <strong class="{{ $cashflow >= 0 ? 'is-positive' : 'is-negative' }}">
                        ${{ number_format($cashflow, 2) }}
                    </strong>
                </div>
            </div>

        </article>

        <!-- CENTER -->
        <article class="startup-card startup-card-analytics">

            <div class="analytics-header">
                <h3>Analytics</h3>
                <p>Real financial distribution</p>
            </div>

            <div class="analytics-panel">

                <div class="analytics-row">
                    <span>Revenue</span>
                    <strong>{{ $revenueShare }}%</strong>
                </div>
                <progress class="progress-line progress-line-green" value="{{ $revenueShare }}" max="100"></progress>

                <div class="analytics-row">
                    <span>Expenses</span>
                    <strong>{{ $expenseShare }}%</strong>
                </div>
                <progress class="progress-line progress-line-amber" value="{{ $expenseShare }}" max="100"></progress>

            </div>

        </article>

        <!-- RIGHT -->
        <aside class="startups-widgets">

            <article class="startup-widget">
                <p class="startups-kicker">Resilience</p>
                <h4>{{ $resilienceScore }}%</h4>
            </article>

            <article class="startup-widget">
                <p class="startups-kicker">Signals</p>
                <ul class="startup-alert-list">

                    <li>
                        <span class="status-dot {{ $cashflow >= 0 ? 'is-good' : 'is-warning' }}"></span>
                        <span>Cashflow {{ $cashflow >= 0 ? 'positive' : 'negative' }}</span>
                    </li>

                    <li>
                        <span class="status-dot is-neutral"></span>
                        <span>Runway {{ number_format($runwayMonths, 1) }} months</span>
                    </li>

                    <li>
                        <span class="status-dot is-neutral"></span>
                        <span>{{ $startup->employees_count }} employees</span>
                    </li>

                </ul>
            </article>

        </aside>

    </div>

    @endif

</section>

@endsection