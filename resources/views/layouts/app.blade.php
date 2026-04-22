<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PFF') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="user-page">
    @php
    $startupId = auth()->user()?->startup?->id;
    $fraudDetectionUrl = $startupId ? route('fraud-detection.show', $startupId) : route('startup.create');
    $userName = auth()->user()?->name ?? 'User';
    $avatarLetter = strtoupper(substr($userName, 0, 1));
    @endphp

    <div class="app-shell admin-layout user-layout">
        <aside class="sidebar user-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('dashboard') }}" class="brand sidebar-brand-link">
                    <span class="brand-logo sidebar-brand-mark">F</span>
                    <span class="brand-copy sidebar-brand-text">
                        <strong class="brand-title">FinFlow</strong>
                        <small class="brand-subtitle">Fraud Monitor</small>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav sidebar-menu">

                <div class="sidebar-group-title">OVERVIEW</div>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">DB</span>
                    <span class="sidebar-link-label">Dashboard</span>
                </a>

                <div class="sidebar-group-title">OPERATIONS</div>
                <a href="{{ route('startups.index') }}" class="sidebar-link {{ request()->routeIs('startup.*') || request()->routeIs('startups.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">ST</span>
                    <span class="sidebar-link-label">My Startup</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="sidebar-link {{ request()->routeIs('transactions.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">TX</span>
                    <span class="sidebar-link-label">Transactions</span>
                </a>

                <a href="{{ route('simulations.index') }}" class="sidebar-link {{ request()->routeIs('simulations.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">SM</span>
                    <span class="sidebar-link-label">Simulations</span>
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

                <div class="sidebar-group-title">ACCOUNT</div>
                <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">SE</span>
                    <span class="sidebar-link-label">Settings</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">PR</span>
                    <span class="sidebar-link-label">Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
                    @csrf
                    <button type="submit" class="sidebar-link sidebar-logout-btn">
                        <span class="sidebar-link-icon">LO</span>
                        <span class="sidebar-link-label">Logout</span>
                    </button>
                </form>

            </nav>

            <div class="sidebar-footer sidebar-utility">
                <a href="{{ route('simulations.index') }}" class="sidebar-cta">Run Simulation</a>
                <p class="sidebar-footer-label sidebar-utility-note">Signed in as</p>
                <p class="sidebar-footer-name">{{ $userName }}</p>
            </div>
        </aside>

        <div class="app-main main-content">
            <header class="topbar user-topbar">
                <div class="topbar-left">
                    <div class="topbar-heading">
                        <span class="topbar-kicker">User Workspace</span>
                        <h1 class="topbar-title">@yield('page_title', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="topbar-actions">
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" data-loading-text="Signing out...">Logout</button>
                    </form>
                    <div class="topbar-user-chip">
                        <span class="avatar">{{ $avatarLetter }}</span>
                        <span class="topbar-user-meta">
                            <strong>{{ $userName }}</strong>
                            <span>User</span>
                        </span>
                    </div>
                </div>
            </header>

            <main class="content content-wrapper">
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
</body>

</html>
