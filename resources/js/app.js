import Chart from 'chart.js/auto';
window.Chart = Chart;

(() => {
    const byId = (id) => document.getElementById(id);
    const all = (selector, root = document) => Array.from(root.querySelectorAll(selector));

    function parseJSON(value, fallback) {
        if (value === undefined || value === null || value === '') {
            return fallback;
        }

        try {
            const parsed = JSON.parse(value);
            return parsed ?? fallback;
        } catch (_) {
            return fallback;
        }
    }

    function parseNumber(value, fallback = 0) {
        if (value === undefined || value === null || value === '') {
            return fallback;
        }

        const parsed = Number(value);
        return Number.isFinite(parsed) ? parsed : fallback;
    }

    function initAutoHideNotices() {
        all('.notice[data-auto-hide="true"]').forEach((notice) => {
            window.setTimeout(() => {
                notice.classList.add('is-hiding');
                window.setTimeout(() => notice.remove(), 260);
            }, 4500);
        });
    }

    function initLoadingButtons() {
        all('form').forEach((form) => {
            form.addEventListener('submit', () => {
                const submit = form.querySelector('button[type="submit"], input[type="submit"]');

                if (!submit || submit.disabled) {
                    return;
                }

                const loadingText = submit.dataset.loadingText || 'Please wait...';

                if (submit.tagName === 'BUTTON') {
                    submit.dataset.originalText = submit.innerHTML;
                    submit.innerHTML = loadingText;
                } else {
                    submit.dataset.originalText = submit.value;
                    submit.value = loadingText;
                }

                submit.classList.add('is-loading');
                submit.disabled = true;
            });
        });
    }

    function initLoadingLinks() {
        all('[data-loading-link]').forEach((link) => {
            link.addEventListener('click', () => {
                link.classList.add('is-loading');

                if (link.dataset.loadingText) {
                    link.dataset.originalText = link.textContent;
                    link.textContent = link.dataset.loadingText;
                }
            });
        });
    }

    function setChartDefaults() {
        if (!window.Chart) {
            return;
        }

        window.Chart.defaults.color = '#94a3b8';
        window.Chart.defaults.borderColor = '#1e293b';
        window.Chart.defaults.font.family = "'Inter', 'Manrope', 'Segoe UI', sans-serif";
    }

    function initRevenueExpensesChart() {
        const canvas = byId('revenueExpensesChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const hasRevenue = canvas.dataset.revenue !== undefined && canvas.dataset.revenue !== '';
        const hasExpenses = canvas.dataset.expenses !== undefined && canvas.dataset.expenses !== '';

        if (!Array.isArray(labels) || labels.length === 0) {
            return;
        }

        if (!hasRevenue && !hasExpenses) {
            return;
        }

        const revenue = parseNumber(canvas.dataset.revenue, 0);
        const expenses = parseNumber(canvas.dataset.expenses, 0);

        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Amount',
                        data: [revenue, expenses],
                        backgroundColor: ['#6366f1', '#ef4444'],
                        borderRadius: 10,
                        maxBarThickness: 64,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback(value) {
                                return '$' + Number(value).toLocaleString();
                            },
                        },
                    },
                },
            },
        });
    }

    function initTransactionsTrendChart() {
        const canvas = byId('transactionsOverTimeChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);

        if (!Array.isArray(labels) || !Array.isArray(values)) {
            return;
        }

        if (labels.length === 0 || values.length === 0) {
            return;
        }

        const parsedValues = values.map((value) => parseNumber(value, 0));

        new window.Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Transactions',
                        data: parsedValues,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e2e8f0',
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    function initUserMainPerformanceChart() {
        const canvas = byId('userMainPerformanceChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const dailyLabels = parseJSON(canvas.dataset.dailyLabels, []);
        const dailyValues = parseJSON(canvas.dataset.dailyValues, []);
        const weeklyLabels = parseJSON(canvas.dataset.weeklyLabels, []);
        const weeklyValues = parseJSON(canvas.dataset.weeklyValues, []);

        if (!Array.isArray(dailyLabels) || !Array.isArray(dailyValues)) {
            return;
        }

        if (dailyLabels.length === 0 || dailyValues.length === 0) {
            return;
        }

        const parsedDailyValues = dailyValues.map((value) => parseNumber(value, 0));
        const parsedWeeklyValues = Array.isArray(weeklyValues)
            ? weeklyValues.map((value) => parseNumber(value, 0))
            : [];

        let chartInstance = null;

        const buildChart = (mode) => {
            const isWeekly = mode === 'weekly' && Array.isArray(weeklyLabels) && weeklyLabels.length > 0 && parsedWeeklyValues.length > 0;
            const labels = isWeekly ? weeklyLabels : dailyLabels;
            const values = isWeekly ? parsedWeeklyValues : parsedDailyValues;

            if (!labels.length || !values.length) {
                return;
            }

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: isWeekly ? 'Weekly Activity' : 'Daily Activity',
                        data: values,
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
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(194,198,214,0.12)',
                            },
                            ticks: {
                                color: '#C2C6D6',
                                precision: 0,
                            },
                        },
                        x: {
                            grid: {
                                color: 'rgba(194,198,214,0.08)',
                            },
                            ticks: {
                                color: '#C2C6D6',
                            },
                        },
                    },
                },
            });
        };

        buildChart('daily');

        const toggleContainer = document.querySelector('[data-user-main-chart-toggle]');

        if (!toggleContainer) {
            return;
        }

        const toggleButtons = toggleContainer.querySelectorAll('.chart-toggle-btn');

        toggleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const mode = button.dataset.mode === 'weekly' ? 'weekly' : 'daily';

                toggleButtons.forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
                buildChart(mode);
            });
        });
    }

    function initUserRiskDonutChart() {
        const canvas = byId('userRiskDonutChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);

        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) {
            return;
        }

        const chartValues = values.map((value) => Math.max(0, parseNumber(value, 0)));

        if (chartValues.every((value) => value === 0)) {
            return;
        }

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
            options: {
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    function initUserMonthlyPerformanceChart() {
        const canvas = byId('userMonthlyPerformanceChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);

        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) {
            return;
        }

        const chartValues = values.map((value) => parseNumber(value, 0));

        new window.Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Transactions',
                    data: chartValues,
                    backgroundColor: '#4D8EFF',
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 44,
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(194,198,214,0.12)',
                        },
                        ticks: {
                            color: '#C2C6D6',
                            precision: 0,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#C2C6D6',
                        },
                    },
                },
            },
        });
    }

    function initUserAllocationChart() {
        const canvas = byId('userAllocationChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);

        if (!Array.isArray(labels) || !Array.isArray(values) || labels.length === 0 || values.length === 0) {
            return;
        }

        const chartValues = values.map((value) => Math.max(0, parseNumber(value, 0)));

        if (chartValues.every((value) => value === 0)) {
            return;
        }

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
            options: {
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });
    }

    function initSimulationProjectionChart() {
        const canvas = byId('simulationProjectionChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const optimistic = parseJSON(canvas.dataset.optimistic, []);
        const conservative = parseJSON(canvas.dataset.conservative, []);

        if (!Array.isArray(labels) || !Array.isArray(optimistic) || !Array.isArray(conservative)) {
            return;
        }

        if (labels.length === 0 || optimistic.length === 0 || conservative.length === 0) {
            return;
        }

        const optimisticValues = optimistic.map((value) => parseNumber(value, 0));
        const conservativeValues = conservative.map((value) => parseNumber(value, 0));

        new window.Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Optimistic',
                        data: optimisticValues,
                        borderColor: '#4d8eff',
                        backgroundColor: 'rgba(77, 142, 255, 0.2)',
                        fill: false,
                        tension: 0.32,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        borderWidth: 2,
                    },
                    {
                        label: 'Conservative',
                        data: conservativeValues,
                        borderColor: '#f87171',
                        backgroundColor: 'rgba(248, 113, 113, 0.16)',
                        fill: false,
                        tension: 0.32,
                        pointRadius: 2,
                        pointHoverRadius: 4,
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148,163,184,0.16)',
                        },
                        ticks: {
                            color: '#9fb2d0',
                            callback(value) {
                                return '$' + Number(value).toLocaleString();
                            },
                        },
                    },
                    x: {
                        grid: {
                            color: 'rgba(148,163,184,0.08)',
                        },
                        ticks: {
                            color: '#9fb2d0',
                        },
                    },
                },
            },
        });
    }

    function initSimulationModeSwitch() {
        const switcher = document.querySelector('[data-mode-switch]');

        if (!switcher) {
            return;
        }

        const buttons = switcher.querySelectorAll('.mode-btn');

        buttons.forEach((button) => {
            button.addEventListener('click', () => {
                buttons.forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
            });
        });
    }

    function initCharts() {
        setChartDefaults();
        initRevenueExpensesChart();
        initTransactionsTrendChart();
        initUserMainPerformanceChart();
        initUserRiskDonutChart();
        initUserMonthlyPerformanceChart();
        initUserAllocationChart();
        initSimulationProjectionChart();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!document.querySelector('.app-shell')) {
            return;
        }

        initAutoHideNotices();
        initLoadingButtons();
        initLoadingLinks();
        initSimulationModeSwitch();
        initCharts();
    });
})();

(() => {
    function parseJSON(value, fallback) {
        if (value === undefined || value === null || value === '') {
            return fallback;
        }

        try {
            const parsed = JSON.parse(value);
            return parsed ?? fallback;
        } catch (_) {
            return fallback;
        }
    }

    function parseNumber(value, fallback = 0) {
        if (value === undefined || value === null || value === '') {
            return fallback;
        }

        const parsed = Number(value);
        return Number.isFinite(parsed) ? parsed : fallback;
    }

    function initWelcomeLandingPage() {
        if (!document.body.classList.contains('welcome-page')) {
            return;
        }

        const nav = document.querySelector('.nav');
        const toggle = document.getElementById('toggle');
        const links = document.querySelectorAll('.link');
        const revealItems = document.querySelectorAll('.reveal');

        if (toggle && nav) {
            toggle.addEventListener('click', function () {
                const open = nav.classList.toggle('open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        links.forEach(function (link) {
            link.addEventListener('click', function () {
                if (nav && nav.classList.contains('open')) {
                    nav.classList.remove('open');
                    if (toggle) {
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.16, rootMargin: '0px 0px -10% 0px' });

            revealItems.forEach(function (item) {
                observer.observe(item);
            });
        } else {
            revealItems.forEach(function (item) {
                item.classList.add('show');
            });
        }
    }

    function initAdminSidebarToggle() {
        if (!document.body.classList.contains('admin-page')) {
            return;
        }

        const toggleBtn = document.querySelector('[data-admin-menu-toggle]');
        const sidebar = document.getElementById('adminSidebar');

        if (!toggleBtn || !sidebar) {
            return;
        }

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
                if (window.innerWidth <= 1024) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024) {
                closeSidebar();
            }
        });
    }

    function initAdminActivityChart() {
        const canvas = document.getElementById('activityChart');

        if (!canvas || !window.Chart) {
            return;
        }

        const ctx = canvas.getContext('2d');

        if (!ctx) {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels, []);
        const values = parseJSON(canvas.dataset.values, []);

        if (!Array.isArray(labels) || !Array.isArray(values)) {
            return;
        }

        if (labels.length === 0 || values.length === 0) {
            return;
        }

        const chartData = values.map((value) => parseNumber(value, 0));

        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(77, 142, 255, 0.95)');
        gradient.addColorStop(1, 'rgba(77, 142, 255, 0.20)');

        new window.Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Activity',
                    data: chartData,
                    backgroundColor: gradient,
                    borderRadius: 10,
                    borderSkipped: false,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,0.05)',
                        },
                        ticks: {
                            color: '#94a8c6',
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#94a8c6',
                        },
                    },
                },
            },
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initWelcomeLandingPage();
        initAdminSidebarToggle();
        initAdminActivityChart();
    });
})();
