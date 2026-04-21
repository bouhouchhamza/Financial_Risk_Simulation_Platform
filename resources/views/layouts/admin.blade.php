<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="admin-page">

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <div class="sidebar-nav">

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

        </div>
        <!-- MAIN -->
        <div class="main-content">

            <div class="topbar">
                <div class="topbar-title">
                    Admin Dashboard
                </div>

                <div class="topbar-actions">
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                @yield('content')
            </div>

        </div>

    </div>

</body>

</html>
