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
@endphp

<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Fraud Detection</h2>
        <p class="page-subtitle">Automated rule analysis for your startup transaction behavior.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('fraud.show', $startup->id) }}" class="btn btn-primary" data-loading-link data-loading-text="Analyzing...">Run Fraud Detection</a>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Transactions</a>
    </div>
</div>

<div class="card">
    <div class="key-value-grid">
        <div class="kv-item">
            <p class="stat-label">Risk Score</p>
            <p class="risk-score">{{ (int) ($result['risk_score'] ?? 0) }}</p>
        </div>
        <div class="kv-item">
            <p class="kv-label">Risk Level</p>
            <p class="kv-value">
                <span class="badge {{ $riskBadgeClass }}">{{ strtoupper($result['risk_level'] ?? 'low') }}</span>
            </p>
        </div>
        <div class="kv-item">
            <p class="kv-label">Decision</p>
            <p class="kv-value">
                <span class="badge {{ $decisionBadgeClass }}">{{ strtoupper($result['decision'] ?? 'allow') }}</span>
            </p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Triggered Flags</h3>
            <p class="card-subtitle">Rules that matched current startup transactions.</p>
        </div>
    </div>
    @if(!empty($result['flags']))
        <div class="tag-list">
            @foreach($result['flags'] as $flag)
                <span class="tag">{{ $flag }}</span>
            @endforeach
        </div>
    @else
        <p class="empty-state">No fraud flags triggered.</p>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Rule Details</h3>
            <p class="card-subtitle">Detailed output returned by each triggered fraud rule.</p>
        </div>
    </div>

    @forelse($details as $ruleCode => $items)
        <div class="rule-card">
            <h4 class="rule-title">{{ $ruleCode }}</h4>
            <ul class="rule-list">
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
</div>
@endsection
