<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="admin-page">
    @php
        $adminName = auth()->user()->name ?? 'Admin';
        $adminInitial = strtoupper(substr($adminName, 0, 1));
    @endphp

    <div class="admin-layout">
        <aside class="sidebar-nav admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-link">
                    <span class="sidebar-brand-mark">PF</span>
                    <span class="sidebar-brand-text">
                        <strong>Platform Finance</strong>
                        <small>Admin Control</small>
                    </span>
                </a>
            </div>

            <nav class="sidebar-menu">
                <div class="sidebar-group-title">OVERVIEW</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">DB</span>
                    <span class="sidebar-link-label">Dashboard</span>
                </a>

                <div class="sidebar-group-title">MANAGEMENT</div>

                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">US</span>
                    <span class="sidebar-link-label">Users</span>
                </a>

                <a href="{{ route('admin.startups.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.startups.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">ST</span>
                    <span class="sidebar-link-label">Startups</span>
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">TX</span>
                    <span class="sidebar-link-label">Transactions</span>
                </a>

                <a href="{{ route('admin.alerts.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.alerts.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">AL</span>
                    <span class="sidebar-link-label">Alerts</span>
                </a>

                <a href="{{ route('admin.fraud_rules.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.fraud_rules.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-icon">FR</span>
                    <span class="sidebar-link-label">Fraud Rules</span>
                </a>

                <div class="sidebar-group-title">ACCOUNT</div>

                <a href="{{ route('profile.edit') }}"
                    class="sidebar-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
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

            <div class="sidebar-utility">
                <a href="{{ route('admin.alerts.index') }}" class="sidebar-cta">Review Alerts</a>
                <p class="sidebar-utility-note">Security monitoring is active</p>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar admin-topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="admin-menu-toggle" data-admin-menu-toggle
                        aria-expanded="false" aria-controls="adminSidebar">
                        <span></span><span></span><span></span>
                    </button>

                    <label class="topbar-search">
                        <span class="search-icon">SR</span>
                        <input type="search" placeholder="Search users, startups, alerts" aria-label="Search">
                    </label>
                </div>

                <div class="topbar-actions">
                    <button type="button" class="topbar-icon-btn" aria-label="Notifications">NT</button>
                    <button type="button" class="topbar-icon-btn" aria-label="System health">SY</button>

                    <div class="topbar-user-chip">
                        <div class="avatar">{{ $adminInitial }}</div>
                        <div class="topbar-user-meta">
                            <strong>{{ $adminName }}</strong>
                            <span>Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                @yield('content')
            </div>

            <footer class="admin-footer">
                <p>Platform Finance Firewall</p>
                <div class="admin-footer-links">
                    <a href="{{ route('admin.dashboard') }}">Overview</a>
                    <a href="{{ route('admin.alerts.index') }}">Alerts</a>
                    <a href="{{ route('profile.edit') }}">Profile</a>
                </div>
            </footer>
        </main>
    </div>

</body>

</html>
