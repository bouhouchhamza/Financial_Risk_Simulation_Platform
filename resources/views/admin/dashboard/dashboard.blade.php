@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
    <div class="dashboard-hero">
        <div>
            <span class="hero-badge">SYSTEM STATUS OVERVIEW</span>
            <h1 class="hero-title">Administrative<br>Control Center</h1>
        </div>
        <p class="hero-text">
            Global oversight of the fintech system, alerts, and recent platform activity.
        </p>
    </div>

    <div class="dashboard-stats">
        <div class="dashboard-stat-card">
            <div class="stat-top">
                <span class="stat-caption">TOTAL REGISTERED USERS</span>
                <span class="stat-icon">👥</span>
            </div>
            <div class="stat-number">{{ number_format($totalUsers) }}</div>
            <div class="stat-trend positive">+ Live system data</div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-top">
                <span class="stat-caption">TOTAL TRANSACTIONS</span>
                <span class="stat-icon">💳</span>
            </div>
            <div class="stat-number">{{ number_format($totalTxns) }}</div>
            <div class="stat-trend positive">Across {{ number_format($totalStartups) }} startups</div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-top">
                <span class="stat-caption">UNRESOLVED ALERTS</span>
                <span class="stat-icon">🔔</span>
            </div>
            <div class="stat-number">{{ $totalAlerts }}</div>
            <div class="stat-trend {{ $totalAlerts > 0 ? 'negative' : 'positive' }}">
                {{ $totalAlerts > 0 ? 'Requires immediate review' : 'System currently stable' }}
            </div>
        </div>
    </div>

    <div class="dashboard-panel chart-panel">
        <div class="panel-header">
            <div>
                <h3>Global Activity Summary</h3>
                <p>Weekly onboarding and platform activity snapshot</p>
            </div>
        </div>

        <div class="chart-box">
            <canvas id="activityChart" data-chart-data='@json($chartData)'></canvas>
        </div>
    </div>

    <div class="dashboard-panel table-panel">
        <div class="panel-header">
            <div>
                <h3>Verified System Users</h3>
                <p>Recently registered and active user accounts</p>
            </div>
            <button type="button" class="filter-btn">Filter Registry</button>
        </div>

        <div class="table-wrap">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>User Identity</th>
                        <th>Assigned Role</th>
                        <th>Last Activity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleName = method_exists($user, 'isAdmin') && $user->isAdmin() ? 'Administrator' : 'User Account';
                                @endphp
                                <span class="role-badge">{{ $roleName }}</span>
                            </td>
                            <td class="muted-text">2 mins ago</td>
                            <td><span class="status-dot active">● Active</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-table">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
