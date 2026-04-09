@extends('layouts.app')

@section('content')
@php
    $results = $simulation->simulationResults()->orderBy('month_number')->get();
@endphp

<div class="page-header">
    <h1 class="page-title">Simulation #{{ $simulation->id }}</h1>
    <div class="page-actions">
        <a href="{{ route('simulations.index') }}" class="btn">Back to Simulations</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <p class="stat-label">Duration</p>
        <p class="stat-value">{{ str_replace('_', ' ', $simulation->duration) }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Total Revenue</p>
        <p class="stat-value">${{ number_format((float) $simulation->total_revenue, 2) }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Total Expenses</p>
        <p class="stat-value">${{ number_format((float) $simulation->total_expenses, 2) }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Final Cashflow</p>
        <p class="stat-value">${{ number_format((float) $simulation->final_cashflow, 2) }}</p>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Monthly Results</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Revenue</th>
                    <th>Expenses</th>
                    <th>Cashflow</th>
                    <th>Critical</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $result)
                    <tr class="{{ $result->is_critical ? 'row-suspicious' : '' }}">
                        <td>{{ $result->month_number }}</td>
                        <td>${{ number_format((float) $result->revenue, 2) }}</td>
                        <td>${{ number_format((float) $result->expenses, 2) }}</td>
                        <td>${{ number_format((float) $result->cashflow, 2) }}</td>
                        <td>
                            <span class="badge {{ $result->is_critical ? 'badge-high' : 'badge-low' }}">
                                {{ $result->is_critical ? 'Yes' : 'No' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No monthly results found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
