@extends('layouts.app')

@section('page_title', 'Startups')

@section('content')
<div class="page-header">
    <div class="page-title-wrap">
        <h2 class="page-title">Startup</h2>
        <p class="page-subtitle">Manage your startup profile and financial baseline.</p>
    </div>
    <div class="page-actions">
        @if($startup)
            <a href="{{ route('startup.show') }}" class="btn btn-primary">Open Profile</a>
        @else
            <a href="{{ route('startup.create') }}" class="btn btn-primary">Create Startup</a>
        @endif
    </div>
</div>

@if(!$startup)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">No Startup Found</h3>
        </div>
        <p class="empty-state">You can create one startup account to unlock transaction and fraud features.</p>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">{{ $startup->name }}</h3>
                <p class="card-subtitle">{{ $startup->activity_type }}</p>
            </div>
        </div>
        <div class="key-value-grid">
            <div class="kv-item">
                <p class="kv-label">Initial Budget</p>
                <p class="kv-value">${{ number_format((float) $startup->initial_budget, 2) }}</p>
            </div>
            <div class="kv-item">
                <p class="kv-label">Monthly Revenue</p>
                <p class="kv-value">${{ number_format((float) $startup->monthly_revenue, 2) }}</p>
            </div>
            <div class="kv-item">
                <p class="kv-label">Monthly Expenses</p>
                <p class="kv-value">${{ number_format((float) $startup->monthly_expenses, 2) }}</p>
            </div>
            <div class="kv-item">
                <p class="kv-label">Employees</p>
                <p class="kv-value">{{ $startup->employees_count }}</p>
            </div>
        </div>
    </div>
@endif
@endsection
