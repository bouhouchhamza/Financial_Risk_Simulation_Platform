@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Simulations</h1>
    <div class="page-actions">
        <a href="{{ route('dashboard') }}" class="btn">Back</a>
    </div>
</div>

@if(session('success'))
    <div class="notice notice-success">{{ session('success') }}</div>
@endif

@if(!$startup)
    <div class="card">
        <p class="empty-state">Create a startup before running simulations.</p>
    </div>
@else
    <div class="grid-2">
        <div class="card">
            <h2 class="card-title">Run New Simulation</h2>
            <form action="{{ route('simulations.store') }}" method="POST" class="form-grid">
                @csrf
                <div>
                    <label for="duration">Duration</label>
                    <select id="duration" name="duration" class="select" required>
                        <option value="6_month">6 Months</option>
                        <option value="12_month">12 Months</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Run Simulation</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 class="card-title">Recent Simulations</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Duration</th>
                            <th>Cashflow</th>
                            <th>Risk</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSimulations as $simulation)
                            <tr>
                                <td>#{{ $simulation->id }}</td>
                                <td>{{ str_replace('_', ' ', $simulation->duration) }}</td>
                                <td>${{ number_format((float) $simulation->final_cashflow, 2) }}</td>
                                <td>
                                    @php
                                        $risk = strtolower((string) $simulation->risk_level);
                                        $riskClass = match ($risk) {
                                            'high' => 'badge-high',
                                            'medium' => 'badge-medium',
                                            default => 'badge-low',
                                        };
                                    @endphp
                                    <span class="badge {{ $riskClass }}">{{ strtoupper($simulation->risk_level) }}</span>
                                </td>
                                <td><a class="btn" href="{{ route('simulations.show', $simulation->id) }}">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">No simulations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
@endsection
