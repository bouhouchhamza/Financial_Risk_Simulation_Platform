@extends('layouts.app')

@section('page_title', 'Transactions')

@section('content')
<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Transactions</h2>
        <p class="page-subtitle">All startup operations with suspicious activity highlighting.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">Add Transaction</a>
        @if(isset($startup))
        <form method="POST" action="{{ route('fraud-detection.run', $startup->id) }}" class="inline-form">
            @csrf
            <button type="submit" class="btn btn-secondary" data-loading-text="Analyzing...">Run Fraud Detection</button>
        </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Transaction History</h3>
            <p class="card-subtitle">Red rows indicate transactions marked suspicious by fraud analysis.</p>
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
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="{{ $transaction->is_suspicious ? 'row-suspicious' : '' }}">
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>${{ number_format((float) $transaction->amount, 2) }}</td>
                    <td>{{ optional($transaction->transaction_date)->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge {{ $transaction->is_suspicious ? 'badge-high' : 'badge-success' }}">
                            {{ $transaction->is_suspicious ? 'Suspicious' : 'Normal' }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn">View</a>
                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Delete this transaction?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-loading-text="Deleting...">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($transactions, 'links'))
    <div class="pagination-wrap small text-muted">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
