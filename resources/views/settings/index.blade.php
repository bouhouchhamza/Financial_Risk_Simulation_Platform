@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <div class="page-actions">
        <a href="{{ route('dashboard') }}" class="btn">Back</a>
    </div>
</div>

@if(session('success'))
    <div class="notice notice-success">{{ session('success') }}</div>
@endif

<div class="grid-2">
    <div class="card">
        <h2 class="card-title">Notification Preferences</h2>
        <form action="{{ route('settings.update') }}" method="POST" class="form-grid">
            @csrf
            <label>
                <input type="checkbox" name="notify_high_risk" checked>
                Email alerts for high risk events
            </label>
            <label>
                <input type="checkbox" name="notify_weekly_report" checked>
                Weekly summary report
            </label>
            <label>
                <input type="checkbox" name="notify_login_events">
                Login activity notifications
            </label>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 class="card-title">Platform Settings</h2>
        <p class="muted">Configure operational defaults for your fintech dashboard.</p>
        <div class="rule-item">
            <p><strong>Theme:</strong> Dark Fintech</p>
            <p><strong>Time Zone:</strong> Africa/Casablanca</p>
            <p><strong>Mode:</strong> Production-ready UI</p>
        </div>
    </div>
</div>
@endsection
