@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1 class="page-title">Admin Dashboard</h1>
    <div class="page-actions">
        <a href="{{ route('dashboard') }}" class="btn">User Dashboard</a>
    </div>
</div>

<div class="grid">
    <div class="card">
        <p class="stat-label">Total Users</p>
        <p class="stat-value">{{ $totalUsers }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Total Startups</p>
        <p class="stat-value">{{ $totalStartups }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Total Transactions</p>
        <p class="stat-value">{{ $totalTxns }}</p>
    </div>
    <div class="card">
        <p class="stat-label">Total Alerts</p>
        <p class="stat-value">{{ $totalAlerts }}</p>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Latest Users</h2>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty-state">No users available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
