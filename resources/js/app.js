import Chart from 'chart.js/auto';
window.Chart = Chart;

(() => {
    /* ── Helpers ─────────────────────────────────────────────── */
    const byId = (id) => document.getElementById(id);

    function parseJSON(value, fallback) {
        if (value === undefined || value === null || value === '') return fallback;
        try { const p = JSON.parse(value); return p ?? fallback; }
        catch (_) { return fallback; }
    }

    function parseNumber(value, fallback = 0) {
        if (value === undefined || value === null || value === '') return fallback;
        const p = Number(value);
        return Number.isFinite(p) ? p : fallback;
    }

    /* ── Chart defaults ──────────────────────────────────────── */
    function setChartDefaults() {
        if (!window.Chart) return;
        window.Chart.defaults.color = '#94a3b8';
        window.Chart.defaults.borderColor = '#1e293b';
        window.Chart.defaults.font.family = "'Inter', 'Manrope', 'Segoe UI', sans-serif";
    }

    /* ── Revenue / Expenses bar chart ────────────────────────── */
    function initRevenueExpensesChart() {
        const canvas = byId('revenueExpensesChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const hasRevenue = canvas.dataset.revenue !== undefined && canvas.dataset.revenue !== '';
        const hasExpenses = canvas.dataset.expenses !== undefined && canvas.dataset.expenses !== '';
        if (!Array.isArray(labels) || labels.length === 0) return;
        if (!hasRevenue && !hasExpenses) return;

        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Amount',
                    data: [parseNumber(canvas.dataset.revenue, 0), parseNumber(canvas.dataset.expenses, 0)],
                    backgroundColor: ['#6366f1', '#ef4444'],
                    borderRadius: 10,
                    maxBarThickness: 64,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback(v) { return '$' + Number(v).toLocaleString(); } },
                    },
                },
            },
        });
    }

    /* ── Transactions trend line chart ────────────────────────── */
    function initTransactionsTrendChart() {
        const canvas = byId('transactionsOverTimeChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);
        if (!Array.isArray(labels) || !Array.isArray(values)) return;
        if (labels.length === 0 || values.length === 0) return;

        new window.Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Transactions',
                    data: values.map((v) => parseNumber(v, 0)),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#e2e8f0' } } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    }

    /* ── User main performance chart (daily / weekly toggle) ── */
    function initUserMainPerformanceChart() {
        const canvas = byId('userMainPerformanceChart');
        if (!canvas || !window.Chart) return;

        const dailyLabels = parseJSON(canvas.dataset.dailyLabels, []);
        const dailyValues = parseJSON(canvas.dataset.dailyValues, []);
        const weeklyLabels = parseJSON(canvas.dataset.weeklyLabels, []);
        const weeklyValues = parseJSON(canvas.dataset.weeklyValues, []);
        if (!Array.isArray(dailyLabels) || !Array.isArray(dailyValues)) return;
        if (dailyLabels.length === 0 || dailyValues.length === 0) return;

        const parsedDaily = dailyValues.map((v) => parseNumber(v, 0));
        const parsedWeekly = Array.isArray(weeklyValues) ? weeklyValues.map((v) => parseNumber(v, 0)) : [];

        let chartInstance = null;

        const buildChart = (mode) => {
            const isWeekly = mode === 'weekly' && Array.isArray(weeklyLabels) && weeklyLabels.length > 0 && parsedWeekly.length > 0;
            const lbl = isWeekly ? weeklyLabels : dailyLabels;
            const vals = isWeekly ? parsedWeekly : parsedDaily;
            if (!lbl.length || !vals.length) return;
            if (chartInstance) chartInstance.destroy();

            chartInstance = new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels: lbl,
                    datasets: [{
                        label: isWeekly ? 'Weekly Activity' : 'Daily Activity',
                        data: vals,
                        borderColor: '#4D8EFF',
                        backgroundColor: 'rgba(77, 142, 255, 0.22)',
                        fill: true,
                        tension: 0.34,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        borderWidth: 2,
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(194,198,214,0.12)' }, ticks: { color: '#C2C6D6', precision: 0 } },
                        x: { grid: { color: 'rgba(194,198,214,0.08)' }, ticks: { color: '#C2C6D6' } },
                    },
                },
            });
        };

        buildChart('daily');

        const toggleContainer = document.querySelector('[data-user-main-chart-toggle]');
        if (!toggleContainer) return;
        toggleContainer.querySelectorAll('.chart-toggle-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                toggleContainer.querySelectorAll('.chart-toggle-btn').forEach((b) => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                buildChart(btn.dataset.mode === 'weekly' ? 'weekly' : 'daily');
            });
        });
    }

    /* ── User risk donut chart ────────────────────────────────── */
    function initUserRiskDonutChart() {
        const canvas = byId('userRiskDonutChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);
        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) return;

        const chartValues = values.map((v) => Math.max(0, parseNumber(v, 0)));
        if (chartValues.every((v) => v === 0)) return;

        new window.Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: chartValues,
                    backgroundColor: ['#f87171', '#4AE176'],
                    borderColor: ['#0C1D2D', '#0C1D2D'],
                    borderWidth: 2,
                    hoverOffset: 2,
                }],
            },
            options: { maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false } } },
        });
    }

    /* ── User monthly performance bar chart ───────────────────── */
    function initUserMonthlyPerformanceChart() {
        const canvas = byId('userMonthlyPerformanceChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);
        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) return;

        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Transactions',
                    data: values.map((v) => parseNumber(v, 0)),
                    backgroundColor: '#4D8EFF',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 44,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(194,198,214,0.12)' }, ticks: { color: '#C2C6D6', precision: 0 } },
                    x: { grid: { display: false }, ticks: { color: '#C2C6D6' } },
                },
            },
        });
    }

    /* ── User allocation donut chart ──────────────────────────── */
    function initUserAllocationChart() {
        const canvas = byId('userAllocationChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);
        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) return;

        const chartValues = values.map((v) => Math.max(0, parseNumber(v, 0)));
        if (chartValues.every((v) => v === 0)) return;

        new window.Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: chartValues,
                    backgroundColor: ['#4D8EFF', '#f87171'],
                    borderColor: ['#0C1D2D', '#0C1D2D'],
                    borderWidth: 2,
                    hoverOffset: 2,
                }],
            },
            options: { maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false } } },
        });
    }

    function initSimulationProjectionChart() {
        const canvas = byId('simulationProjectionChart');
        if (!canvas || !window.Chart) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const optimistic = parseJSON(canvas.dataset.optimistic, []);
        const conservative = parseJSON(canvas.dataset.conservative, []);
        if (!Array.isArray(labels) || !Array.isArray(optimistic) || !Array.isArray(conservative)) return;
        if (labels.length === 0 || optimistic.length === 0 || conservative.length === 0) return;

        new window.Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Optimistic',
                        data: optimistic.map((v) => parseNumber(v, 0)),
                        borderColor: '#4d8eff',
                        backgroundColor: 'rgba(77, 142, 255, 0.2)',
                        fill: false, tension: 0.32, pointRadius: 2, pointHoverRadius: 4, borderWidth: 2,
                    },
                    {
                        label: 'Conservative',
                        data: conservative.map((v) => parseNumber(v, 0)),
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.16)',
                        fill: false, tension: 0.32, pointRadius: 2, pointHoverRadius: 4, borderWidth: 2,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148,163,184,0.16)' },
                        ticks: { color: '#9fb2d0', callback(v) { return '$' + Number(v).toLocaleString(); } },
                    },
                    x: { grid: { color: 'rgba(148,163,184,0.08)' }, ticks: { color: '#9fb2d0' } },
                },
            },
        });
    }

    /* ── Admin activity bar chart ─────────────────────────────── */
    function initAdminActivityChart() {
        const canvas = byId('activityChart');
        if (!canvas || !window.Chart) return;

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);
        if (!Array.isArray(labels) || !Array.isArray(values)) return;
        if (labels.length === 0 || values.length === 0) return;

        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(77, 142, 255, 0.95)');
        gradient.addColorStop(1, 'rgba(77, 142, 255, 0.20)');

        new window.Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Activity',
                    data: values.map((v) => parseNumber(v, 0)),
                    backgroundColor: gradient,
                    borderRadius: 10,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a8c6' } },
                    x: { grid: { display: false }, ticks: { color: '#94a8c6' } },
                },
            },
        });
    }

    /* ── Simulation mode switch (UI toggle, no chart) ─────────── */
    function initSimulationModeSwitch() {
        const switcher = document.querySelector('[data-mode-switch]');
        if (!switcher) return;
        const buttons = switcher.querySelectorAll('.mode-btn');
        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                buttons.forEach((b) => b.classList.remove('is-active'));
                btn.classList.add('is-active');
            });
        });
    }

    /* ── Welcome page burger menu ─────────────────────────────── */
    function initWelcomeBurgerMenu() {
        if (!document.body.classList.contains('welcome-page')) return;

        const nav = document.querySelector('.nav');
        const toggle = byId('toggle');
        const links = document.querySelectorAll('.link');

        if (toggle && nav) {
            toggle.addEventListener('click', () => {
                const open = nav.classList.toggle('open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        links.forEach((link) => {
            link.addEventListener('click', () => {
                if (nav && nav.classList.contains('open')) {
                    nav.classList.remove('open');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }

    /* ── Admin sidebar toggle (mobile) ────────────────────────── */
    function initAdminSidebarToggle() {
        if (!document.body.classList.contains('admin-page')) return;

        const toggleBtn = document.querySelector('[data-admin-menu-toggle]');
        const sidebar = byId('adminSidebar');
        if (!toggleBtn || !sidebar) return;

        const closeSidebar = () => {
            document.body.classList.remove('sidebar-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
        };

        toggleBtn.addEventListener('click', () => {
            const isOpen = document.body.classList.toggle('sidebar-open');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        sidebar.querySelectorAll('.sidebar-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1024) closeSidebar();
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) closeSidebar();
        });
    }

    /* ── User sidebar burger menu (mobile) ────────────────────── */
    function initUserSidebarToggle() {
        if (!document.body.classList.contains('user-page')) return;

        const sidebar = document.querySelector('.user-sidebar');
        const toggleBtn = document.querySelector('[data-user-menu-toggle]');
        if (!sidebar || !toggleBtn) return;

        const closeSidebar = () => {
            document.body.classList.remove('user-sidebar-open');
            toggleBtn.setAttribute('aria-expanded', 'false');
        };

        toggleBtn.addEventListener('click', () => {
            const isOpen = document.body.classList.toggle('user-sidebar-open');
            toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        sidebar.querySelectorAll('.sidebar-link').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 1080) closeSidebar();
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1080) closeSidebar();
        });
    }

    /* ── Init ─────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', () => {
        // Charts
        setChartDefaults();
        initRevenueExpensesChart();
        initTransactionsTrendChart();
        initUserMainPerformanceChart();
        initUserRiskDonutChart();
        initUserMonthlyPerformanceChart();
        initUserAllocationChart();
        initSimulationProjectionChart();
        initAdminActivityChart();
        initSimulationModeSwitch();

        // Burger menus
        initWelcomeBurgerMenu();
        initAdminSidebarToggle();
        initUserSidebarToggle();
    });
})();
