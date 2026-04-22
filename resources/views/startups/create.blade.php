@extends('layouts.app')

@section('page_title', 'Create Startup')

@section('content')
<section class="unified-user-page startups-page startup-create-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h2 class="page-title">Create Startup</h2>
            <p class="page-subtitle">Set up your startup profile to start tracking transactions and fraud risk.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if($errors->any())
        <div class="notice notice-error" data-auto-hide="true">
            <strong>Validation Error</strong>
            <ul class="list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card dashboard-panel">
        <div class="card-header">
            <div>
                <h3 class="card-title">Startup Information</h3>
                <p class="card-subtitle">Basic company profile and financial baseline.</p>
            </div>
        </div>

        <form action="{{ route('startup.store') }}" method="POST" class="form-grid">
            @csrf

            <div>
                <label for="name">Startup Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="input" required>
            </div>

            <div>
                <label for="activity_type">Activity Type</label>
                <input type="text" id="activity_type" name="activity_type" value="{{ old('activity_type') }}" class="input" required>
            </div>

            <div>
                <div class="grid-2">
                    <div>
                        <label for="initial_budget">Initial Budget</label>
                        <input type="number" id="initial_budget" name="initial_budget" value="{{ old('initial_budget') }}" class="input" step="0.01" required>
                    </div>
                    <div>
                        <label for="employees_count">Employees Count</label>
                        <input type="number" id="employees_count" name="employees_count" value="{{ old('employees_count') }}" class="input" required>
                    </div>
                </div>
            </div>

            <div>
                <div class="grid-2">
                    <div>
                        <label for="monthly_revenue">Monthly Revenue</label>
                        <input type="number" id="monthly_revenue" name="monthly_revenue" value="{{ old('monthly_revenue') }}" class="input" step="0.01" required>
                    </div>
                    <div>
                        <label for="monthly_expenses">Monthly Expenses</label>
                        <input type="number" id="monthly_expenses" name="monthly_expenses" value="{{ old('monthly_expenses') }}" class="input" step="0.01" required>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" data-loading-text="Saving Startup...">Save Startup</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
@endsection
