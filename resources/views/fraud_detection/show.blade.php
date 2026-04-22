@extends('layouts.app')

@section('page_title', 'Fraud Detection')

@section('content')
@php
    $riskLevel = strtolower($result['risk_level'] ?? 'low');
    $riskBadgeClass = match ($riskLevel) {
        'high' => 'badge-high',
        'medium' => 'badge-medium',
        default => 'badge-low',
    };

    $decision = strtolower($result['decision'] ?? 'allow');
    $decisionBadgeClass = match ($decision) {
        'block' => 'badge-danger',
        'review' => 'badge-warning',
        default => 'badge-success',
    };

    $details = $result['details'] ?? [];
    $flagCount = is_array($result['flags'] ?? null) ? count($result['flags']) : 0;
    $ruleCount = is_array($details) ? count($details) : 0;
@endphp

<section class="unified-user-page fraud-detection-page fraud-figma-page">
    <header class="fraud-hero">
        <div class="fraud-hero-copy">
            <p class="fraud-kicker">Risk Intelligence</p>
            <h2 class="page-title">Fraud Detection</h2>
            <p class="page-subtitle">Automated rule analysis for your startup transaction behavior.</p>
        </div>
        <div class="fraud-hero-actions">
            <form method="POST" action="{{ route('fraud-detection.run', $startup->id) }}" class="inline-form">
                @csrf
                <button type="submit" class="btn btn-primary" data-loading-text="Analyzing...">Run Fraud Detection</button>
            </form>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Transactions</a>
        </div>
    </header>

    <div class="fraud-metrics-grid">
        <article class="card fraud-metric-card fraud-metric-card-primary">
            <p class="stat-label">Risk Score</p>
            <p class="fraud-metric-value">{{ (int) ($result['risk_score'] ?? 0) }}</p>
            <p class="fraud-metric-meta">Current startup threat confidence index</p>
        </article>

        <article class="card fraud-metric-card">
            <div class="fraud-metric-row">
                <p class="stat-label">Risk Level</p>
                <span class="badge {{ $riskBadgeClass }}">{{ strtoupper($result['risk_level'] ?? 'low') }}</span>
            </div>
            <p class="fraud-metric-meta">Severity returned by detection engine</p>
        </article>

        <article class="card fraud-metric-card">
            <div class="fraud-metric-row">
                <p class="stat-label">Decision</p>
                <span class="badge {{ $decisionBadgeClass }}">{{ strtoupper($result['decision'] ?? 'allow') }}</span>
            </div>
            <p class="fraud-metric-meta">Recommended system action state</p>
        </article>
    </div>

    <div class="fraud-panels-grid">
        <article class="card fraud-panel-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Detection Overview</h3>
                    <p class="card-subtitle">Summary of triggered outputs from the latest run.</p>
                </div>
            </div>
            <div class="key-value-grid">
                <div class="kv-item">
                    <p class="kv-label">Triggered Flags</p>
                    <p class="kv-value">{{ $flagCount }}</p>
                </div>
                <div class="kv-item">
                    <p class="kv-label">Triggered Rules</p>
                    <p class="kv-value">{{ $ruleCount }}</p>
                </div>
                <div class="kv-item">
                    <p class="kv-label">Startup</p>
                    <p class="kv-value">{{ $startup->name ?? 'N/A' }}</p>
                </div>
            </div>
        </article>

        <article class="card fraud-panel-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Triggered Flags</h3>
                    <p class="card-subtitle">Rules that matched current startup transactions.</p>
                </div>
            </div>
            @if(!empty($result['flags']))
                <div class="tag-list fraud-flags-list">
                    @foreach($result['flags'] as $flag)
                        <span class="tag">{{ $flag }}</span>
                    @endforeach
                </div>
            @else
                <p class="empty-state">No fraud flags triggered.</p>
            @endif
        </article>
    </div>

    <article class="card fraud-details-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Rule Details</h3>
                <p class="card-subtitle">Detailed output returned by each triggered fraud rule.</p>
            </div>
        </div>

        @forelse($details as $ruleCode => $items)
            <div class="fraud-rule-block">
                <h4 class="fraud-rule-title">{{ $ruleCode }}</h4>
                <ul class="fraud-rule-list">
                    @foreach($items as $item)
                        <li>
                            @if(isset($item['transaction_id']))
                                <strong>Transaction:</strong> #{{ $item['transaction_id'] }}
                            @endif

                            @if(isset($item['amount']))
                                | <strong>Amount:</strong> ${{ number_format((float) $item['amount'], 2) }}
                            @endif

                            @if(isset($item['type']))
                                | <strong>Type:</strong> {{ ucfirst((string) $item['type']) }}
                            @endif

                            @if(isset($item['date']))
                                | <strong>Date:</strong> {{ $item['date'] }}
                            @endif

                            @if(isset($item['transaction_date']))
                                | <strong>Transaction Date:</strong> {{ \Illuminate\Support\Carbon::parse($item['transaction_date'])->format('Y-m-d') }}
                            @endif

                            @if(isset($item['transactions_count']))
                                | <strong>Count:</strong> {{ $item['transactions_count'] }}
                            @endif

                            | <strong>Message:</strong> {{ $item['message'] ?? 'Rule triggered.' }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="empty-state">No detailed rule results available.</p>
        @endforelse
    </article>
</section>
@endsection
