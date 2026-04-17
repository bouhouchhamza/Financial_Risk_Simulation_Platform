<nav class="nav">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
    <a class="nav-link {{ request()->routeIs('startups.*') || request()->routeIs('startup.*') ? 'active' : '' }}" href="{{ route('startups.index') }}">Startups</a>
    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">Transactions</a>
    @php
        $startupId = auth()->user()?->startup?->id;
    @endphp
    <a class="nav-link {{ request()->routeIs('fraud.*') || request()->routeIs('fraud-detection.*') ? 'active' : '' }}"
       href="{{ $startupId ? route('fraud-detection.show', $startupId) : route('startup.create') }}">
       Fraud Detection
    </a>
    <a class="nav-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}" href="{{ route('alerts.index') }}">Alerts</a>
    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Reports</a>
    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">Settings</a>
</nav>
