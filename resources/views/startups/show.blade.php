@extends('layouts.app')

@section('page_title', 'Startup')

@section('content')
<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Startup Profile</h2>
        <p class="page-subtitle">Overview of your startup and latest transaction activity.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">Add Transaction</a>
        <a href="{{ route('fraud.show', $startup->id) }}" class="btn btn-secondary" data-loading-link data-loading-text="Analyzing...">Run Fraud Detection</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">{{ $startup->name }}</h3>
            <p class="card-subtitle">{{ $startup->activity_type }}</p>
        </div>
        <span class="badge badge-primary">Startup</span>
    </div>

    <div class="key-value-grid">
        <div class="kv-item">
            <p class="stat-label">Activity Type</p>
            <p class="kv-value">{{ $startup->activity_type }}</p>
        </div>
        <div class="kv-item">
            <p class="stat-label">Initial Budget</p>
            <p class="kv-value">${{ number_format((float) $startup->initial_budget, 2) }}</p>
        </div>
        <div class="kv-item">
            <p class="stat-label">Monthly Revenue</p>
            <p class="kv-value">${{ number_format((float) $startup->monthly_revenue, 2) }}</p>
        </div>
        <div class="kv-item">
            <p class="stat-label">Monthly Expenses</p>
            <p class="kv-value">${{ number_format((float) $startup->monthly_expenses, 2) }}</p>
        </div>
        <div class="kv-item">
            <p class="stat-label">Employees</p>
            <p class="kv-value">{{ $startup->employees_count }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Recent Transactions</h3>
            <p class="card-subtitle">Last 5 transactions linked to this startup.</p>
        </div>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Suspicious</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($startupTransactions as $transaction)
                    <tr class="{{ $transaction->is_suspicious ? 'row-suspicious' : '' }}">
                        <td>#{{ $transaction->id }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format((float) $transaction->amount, 2) }}</td>
                        <td>{{ optional($transaction->transaction_date)->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge {{ $transaction->is_suspicious ? 'badge-high' : 'badge-success' }}">
                                {{ $transaction->is_suspicious ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">No transactions available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
