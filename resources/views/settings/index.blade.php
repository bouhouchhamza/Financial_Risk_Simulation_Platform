@extends('layouts.app')

@section('content')
<section class="unified-user-page settings-page">
    <div class="page-header">
        <div class="page-title-wrap">
            <h1 class="page-title">Settings</h1>
            <p class="page-subtitle">Manage account notifications and platform preferences.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('dashboard') }}" class="btn">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    <div class="grid-2">
        <div class="card dashboard-panel">
            <h2 class="card-title">Notification Preferences</h2>
            <form action="{{ route('settings.update') }}" method="POST" class="form-grid">
                @csrf
                <label class="setting-option">
                    <input type="checkbox" name="notify_high_risk" checked>
                    <span class="setting-option-copy">
                        <strong>Email alerts for high risk events</strong>
                        <small>Get immediate notifications when fraud severity reaches high.</small>
                    </span>
                </label>
                <label class="setting-option">
                    <input type="checkbox" name="notify_weekly_report" checked>
                    <span class="setting-option-copy">
                        <strong>Weekly summary report</strong>
                        <small>Receive a weekly digest of transactions, alerts, and simulation outcomes.</small>
                    </span>
                </label>
                <label class="setting-option">
                    <input type="checkbox" name="notify_login_events">
                    <span class="setting-option-copy">
                        <strong>Login activity notifications</strong>
                        <small>Get notified when account sign-in activity is detected.</small>
                    </span>
                </label>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                </div>
            </form>
        </div>

        <div class="card dashboard-panel">
            <h2 class="card-title">Platform Settings</h2>
            <p class="muted">Configure operational defaults for your fintech dashboard.</p>
            <div class="rule-item">
                <p><strong>Theme:</strong> Dark Fintech</p>
                <p><strong>Time Zone:</strong> Africa/Casablanca</p>
                <p><strong>Mode:</strong> Production-ready UI</p>
            </div>
        </div>
    </div>
</section>
@endsection
