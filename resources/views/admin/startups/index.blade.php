@extends('layouts.admin')

@section('content')
<div class="startup-page">

    <div class="startup-header">
        <div>
            <div class="startup-kicker">PORTFOLIO MANAGEMENT</div>
            <h1 class="startup-title">Venture Startups</h1>
            <p class="startup-subtitle">
                Monitor the strategic health of your portfolio companies with real-time risk simulation and liquidity mapping.
            </p>
        </div>
    </div>

    @if($startups->count())
        @php
            $cards = $startups->take(3);
            $featured = $startups->first();
            $portfolioHealth = round(($startups->avg('initial_budget') ?? 0) > 0 ? min(99, max(60, $startups->count() * 7)) : 84.2, 1);
        @endphp

        <div class="startup-grid">
            <div class="startup-main">
                <div class="startup-top-cards">
                    @foreach($cards as $index => $startup)
                        @php
                            $score = match($index) {
                                0 => 98.2,
                                1 => 74.5,
                                default => 42.1,
                            };

                            $riskLabel = match($index) {
                                0 => 'Optimal',
                                1 => 'Moderate',
                                default => 'High Alert',
                            };

                            $riskTone = match($index) {
                                0 => 'green',
                                1 => 'purple',
                                default => 'red',
                            };

                            $progress = match($index) {
                                0 => 88,
                                1 => 45,
                                default => 78,
                            };

                            $icon = match($index) {
                                0 => '☁️',
                                1 => '🧬',
                                default => '⚡',
                            };
                        @endphp

                        <div class="startup-card">
                            <div class="startup-card-top">
                                <div class="startup-icon">{{ $icon }}</div>

                                <div class="startup-score-wrap">
                                    <span class="startup-pill {{ $riskTone }}">{{ $riskLabel }}</span>
                                    <div class="startup-score">{{ $score }}</div>
                                </div>
                            </div>

                            <div class="startup-card-body">
                                <h3>{{ $startup->name }}</h3>
                                <p>{{ $startup->activity_type ?? 'No sector specified' }}</p>
                                <div class="startup-owner">
                                    Owner: {{ $startup->user->name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="startup-progress-meta">
                                <span>Risk Exposure</span>
                                <span>{{ $riskTone === 'red' ? 'Elevated' : ($riskTone === 'purple' ? 'Balanced' : 'Minimal') }}</span>
                            </div>

                            <div class="startup-progress">
                                <span style="width: {{ $progress }}%;" class="bar {{ $riskTone }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="startup-feature-row">
                    <div class="feature-left">
                        <div class="feature-kicker">Featured Startup</div>
                        <h2>{{ $featured->name }}</h2>
                        <p>
                            Startup owner: <strong>{{ $featured->user->name ?? 'N/A' }}</strong><br>
                            Sector: <strong>{{ $featured->activity_type ?? 'N/A' }}</strong><br>
                            Created at: <strong>{{ $featured->created_at?->format('Y-m-d') }}</strong>
                        </p>

                        <div class="feature-metrics">
                            <div class="metric-box">
                                <span>Initial Budget</span>
                                <strong>{{ number_format((float) $featured->initial_budget, 2) }} MAD</strong>
                            </div>

                            <div class="metric-box">
                                <span>Employees</span>
                                <strong class="muted-strong">{{ $featured->employees_count ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="feature-right">
                        <div class="feature-visual">
                            <div class="visual-glow"></div>
                            <div class="visual-bars">
                                <span></span><span></span><span></span><span></span><span></span><span></span>
                            </div>
                        </div>

                        <a href="{{ route('admin.transactions.index') }}" class="feature-btn">
                            View Transactions
                        </a>
                    </div>
                </div>
            </div>

            <div class="startup-side">
                <div class="side-card highlighted">
                    <h4>Portfolio Resilience</h4>
                    <div class="side-score">{{ $portfolioHealth }} <small>/ 100</small></div>
                    <p>Aggregated score across active startups.</p>
                </div>

                <div class="side-card">
                    <h4>Recent Startup Alerts</h4>
                    <ul class="alert-list">
                        <li><span class="dot red"></span> Review portfolio anomalies and funding risk signals.</li>
                        <li><span class="dot green"></span> Startup monitoring is active and synchronized.</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="empty-startups-card">
            <h3>No startups found</h3>
            <p>There are currently no startups registered in the platform.</p>
            <a href="{{ route('admin.users.index') }}" class="startup-add-btn">View Users</a>
        </div>
    @endif

    <div class="startup-pagination">
        {{ $startups->links() }}
    </div>
</div>
@endsection
