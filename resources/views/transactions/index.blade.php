@extends('layouts.app')

@section('page_title', 'Transactions')

@section('content')
@php
    $txCollection = method_exists($transactions, 'getCollection')
        ? $transactions->getCollection()
        : collect($transactions);

    $totalVolume = (float) $txCollection->sum(fn ($tx) => (float) $tx->amount);
    $outflow = (float) $txCollection->where('type', 'purchase')->sum(fn ($tx) => (float) $tx->amount);
    $inflow = (float) $txCollection->where('type', 'sale')->sum(fn ($tx) => (float) $tx->amount);
    $transactionCount = (int) $txCollection->count();
    $suspiciousCount = (int) $txCollection->where('is_suspicious', true)->count();
    $averageAmount = $transactionCount > 0 ? $totalVolume / $transactionCount : 0.0;
    $liquidityRatio = $outflow > 0 ? round($inflow / $outflow, 2) : null;
@endphp

<section class="unified-user-page transactions-page transactions-figma-page">
    <header class="transactions-header">
        <div class="transactions-header-copy">
            <p class="transactions-kicker">Ledger Operations</p>
            <h2 class="page-title">Transactions</h2>
            <p class="page-subtitle">Real startup ledger entries with suspicious activity monitoring.</p>
        </div>
    </header>

    <div class="transactions-metrics">
        <article class="card transactions-metric-card transactions-metric-card-primary">
            <p class="transactions-metric-label">Total Ledger Volume</p>
            <p class="transactions-metric-value">${{ number_format($totalVolume, 2) }}</p>
            <p class="transactions-metric-meta">Based on {{ $transactionCount }} listed transactions</p>
            <span class="transactions-metric-line"></span>
        </article>

        <div class="transactions-metrics-mini">
            <article class="card transactions-metric-card">
                <p class="transactions-metric-label">Outflow</p>
                <p class="transactions-metric-value">${{ number_format($outflow, 2) }}</p>
                <p class="transactions-metric-meta">{{ $txCollection->where('type', 'purchase')->count() }} purchase records</p>
            </article>

            <article class="card transactions-metric-card">
                <p class="transactions-metric-label">Inflow</p>
                <p class="transactions-metric-value">${{ number_format($inflow, 2) }}</p>
                <p class="transactions-metric-meta">{{ $txCollection->where('type', 'sale')->count() }} sale records</p>
            </article>

            <article class="card transactions-metric-card">
                <p class="transactions-metric-label">Liquidity Ratio</p>
                <p class="transactions-metric-value">
                    {{ $liquidityRatio === null ? 'N/A' : number_format($liquidityRatio, 2) }}
                </p>
                <p class="transactions-metric-meta">
                    {{ $liquidityRatio === null ? 'No purchase records on this page' : 'Inflow / Outflow' }}
                </p>
            </article>
        </div>
    </div>

    <article class="card transactions-table-card">
        <div class="transactions-table-head">
            <div>
                <h3 class="card-title">Transactions Ledger</h3>
                <p class="card-subtitle">Suspicious entries are highlighted in the status column.</p>
            </div>
            <p class="transactions-table-meta">
                Suspicious: {{ $suspiciousCount }} | Average Amount: ${{ number_format($averageAmount, 2) }}
            </p>
        </div>

        <div class="table-wrap">
            <table class="table transactions-table">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="is-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @php
                            $type = strtolower((string) $transaction->type);
                            $category = match ($type) {
                                'sale' => 'Inflow',
                                'purchase' => 'Outflow',
                                'transfer' => 'Transfer',
                                default => 'General',
                            };
                            $amountClass = match ($type) {
                                'sale' => 'is-income',
                                'purchase' => 'is-expense',
                                default => 'is-neutral',
                            };
                            $avatarLetter = strtoupper(substr((string) ($startup->name ?? 'T'), 0, 1));
                        @endphp
                        <tr class="transaction-row {{ $transaction->is_suspicious ? 'is-suspicious' : '' }}">
                            <td>
                                <div class="transaction-identity">
                                    <span class="transaction-avatar">{{ $avatarLetter }}</span>
                                    <div class="transaction-copy">
                                        <span class="transaction-id">#{{ $transaction->id }}</span>
                                        <span class="transaction-sub">{{ $transaction->description ?: 'Ledger entry' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="transaction-pill">{{ ucfirst($type) }}</span>
                            </td>
                            <td class="transaction-amount {{ $amountClass }}">
                                ${{ number_format((float) $transaction->amount, 2) }}
                            </td>
                            <td>
                                <span class="transaction-pill transaction-pill-muted">{{ $category }}</span>
                            </td>
                            <td>{{ optional($transaction->transaction_date)->format('Y-m-d') ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $transaction->is_suspicious ? 'badge-high' : 'badge-success' }}">
                                    {{ $transaction->is_suspicious ? 'Suspicious' : 'Normal' }}
                                </span>
                            </td>
                            <td>
                                <div class="transaction-actions">
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-secondary">View</a>
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
                            <td colspan="7" class="empty-state">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="transactions-table-footer">
            <p class="transactions-table-meta">
                @if(method_exists($transactions, 'total'))
                    Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions
                @else
                    Showing {{ $transactionCount }} transactions
                @endif
            </p>

            @if(method_exists($transactions, 'links'))
                <div class="pagination-wrap">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </article>
</section>
@endsection
