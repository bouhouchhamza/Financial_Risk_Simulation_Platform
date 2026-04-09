<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PFF') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @php
        $startupId = auth()->user()?->startup?->id;
        $fraudDetectionUrl = $startupId ? route('fraud.show', $startupId) : route('startup.create');
        $userName = auth()->user()?->name ?? 'User';
        $avatarLetter = strtoupper(substr($userName, 0, 1));
    @endphp

    <div class="app-shell">
        <aside class="sidebar">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-logo">F</span>
                <span class="brand-copy">
                    <span class="brand-title">FinFlow</span>
                    <span class="brand-subtitle">Fraud Monitor</span>
                </span>
            </a>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">DB</span>
                    <span class="sidebar-link-label">Dashboard</span>
                </a>
                <a href="{{ route('startups.index') }}" class="sidebar-link {{ request()->routeIs('startup.*') || request()->routeIs('startups.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">ST</span>
                    <span class="sidebar-link-label">Startups</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="sidebar-link {{ request()->routeIs('transactions.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">TX</span>
                    <span class="sidebar-link-label">Transactions</span>
                </a>
                <a href="{{ $fraudDetectionUrl }}" class="sidebar-link {{ request()->routeIs('fraud.*') || request()->routeIs('fraud-detection.*') || request()->routeIs('analysis.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">FD</span>
                    <span class="sidebar-link-label">Fraud Detection</span>
                </a>
                <a href="{{ route('alerts.index') }}" class="sidebar-link {{ request()->routeIs('alerts.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">AL</span>
                    <span class="sidebar-link-label">Alerts</span>
                </a>
                <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">RP</span>
                    <span class="sidebar-link-label">Reports</span>
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">SE</span>
                    <span class="sidebar-link-label">Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <p class="sidebar-footer-label">Signed in as</p>
                <p class="sidebar-footer-name">{{ $userName }}</p>
            </div>
        </aside>

        <div class="app-main">
            <header class="topbar">
                <h1 class="topbar-title">@yield('page_title', 'Dashboard')</h1>
                <div class="topbar-user">
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" data-loading-text="Signing out...">Logout</button>
                    </form>
                    <span class="avatar">{{ $avatarLetter }}</span>
                </div>
            </header>

            <main class="content">
                @if(session('success'))
                    <div class="notice notice-success" data-auto-hide="true">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="notice notice-error" data-auto-hide="true">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
