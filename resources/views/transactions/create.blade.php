@extends('layouts.app')

@section('page_title', 'Create Transaction')

@section('content')
<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Create Transaction</h2>
        <p class="page-subtitle">Record a new financial event for your startup.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

@if($errors->any())
    <div class="notice notice-error">
        <strong>Please fix the following errors:</strong>
        <ul class="list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Transaction Details</h3>
            <p class="card-subtitle">Type, amount, date, and optional description.</p>
        </div>
    </div>

    <form action="{{ route('transactions.store') }}" method="POST" class="form-grid">
        @csrf

        <div>
            <label for="type">Type</label>
            <select id="type" name="type" class="select" required>
                <option value="">Select Type</option>
                <option value="sale" @selected(old('type') === 'sale')>Sale</option>
                <option value="purchase" @selected(old('type') === 'purchase')>Purchase</option>
                <option value="transfer" @selected(old('type') === 'transfer')>Transfer</option>
            </select>
        </div>

        <div>
            <label for="amount">Amount</label>
            <input
                id="amount"
                type="number"
                name="amount"
                class="input"
                step="0.01"
                min="0.01"
                value="{{ old('amount') }}"
                required
            >
        </div>

        <div>
            <label for="transaction_date">Transaction Date</label>
            <input
                id="transaction_date"
                type="date"
                name="transaction_date"
                class="input"
                value="{{ old('transaction_date', now()->toDateString()) }}"
                required
            >
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" class="input">{{ old('description') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" data-loading-text="Saving...">Save Transaction</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
