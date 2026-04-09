@extends('layouts.app')

@section('page_title', 'Transaction Details')

@section('content')
<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Transaction #{{ $transaction->id }}</h2>
        <p class="page-subtitle">Detailed view of this transaction and linked alerts.</p>
    </div>
    <div class="page-actions">
        @if(isset($startup))
            <a href="{{ route('fraud.show', $startup->id) }}" class="btn btn-secondary" data-loading-link data-loading-text="Analyzing...">Run Fraud Detection</a>
        @endif
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Transaction Details</h3>
            <p class="card-subtitle">Recorded transaction information.</p>
        </div>
    </div>
    <div class="key-value-grid">
        <div class="kv-item">
            <p class="kv-label">Type</p>
            <p class="kv-value">{{ ucfirst($transaction->type) }}</p>
        </div>
        <div class="kv-item">
            <p class="stat-label">Amount</p>
            <p class="kv-value">${{ number_format((float) $transaction->amount, 2) }}</p>
        </div>
        <div class="kv-item">
            <p class="kv-label">Date</p>
            <p class="kv-value">{{ optional($transaction->transaction_date)->format('Y-m-d') }}</p>
        </div>
        <div class="kv-item">
            <p class="kv-label">Suspicious</p>
            <p class="kv-value">
                <span class="badge {{ $transaction->is_suspicious ? 'badge-high' : 'badge-success' }}">
                    {{ $transaction->is_suspicious ? 'Yes' : 'No' }}
                </span>
            </p>
        </div>
        <div class="kv-item">
            <p class="kv-label">Description</p>
            <p class="kv-value">{{ $transaction->description ?: 'No description provided.' }}</p>
        </div>
    </div>
</div>

@if($transaction->alerts->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Linked Alerts</h3>
                <p class="card-subtitle">Alerts generated for this transaction.</p>
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Rule Code</th>
                        <th>Message</th>
                        <th>Severity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->alerts as $alert)
                        <tr>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <p class="empty-state">No alerts are linked to this transaction.</p>
    </div>
@endif
@endsection

