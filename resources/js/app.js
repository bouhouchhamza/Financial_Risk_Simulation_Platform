(() => {
    const byId = (id) => document.getElementById(id);
    const all = (selector, root = document) => Array.from(root.querySelectorAll(selector));

    function parseJSON(value, fallback) {
        try {
            return JSON.parse(value);
        } catch (_) {
            return fallback;
        }
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
        if (typeof Chart === 'undefined') {
            return;
        }

        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#1e293b';
        Chart.defaults.font.family = "'Inter', 'Manrope', 'Segoe UI', sans-serif";
    }

    function initRevenueExpensesChart() {
        const canvas = byId('revenueExpensesChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const revenue = Number(canvas.dataset.revenue || 0);
        const expenses = Number(canvas.dataset.expenses || 0);

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['Revenue', 'Expenses'],
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

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const labels = parseJSON(canvas.dataset.labels || '[]', []);
        const values = parseJSON(canvas.dataset.values || '[]', []);

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Transactions',
                        data: values,
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

    function initCharts() {
        setChartDefaults();
        initRevenueExpensesChart();
        initTransactionsTrendChart();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!document.querySelector('.app-shell')) {
            return;
        }

        initAutoHideNotices();
        initLoadingButtons();
        initLoadingLinks();
        initCharts();
    });
})();

(() => {
    function initWelcomeLandingPage() {
        if (!document.body.classList.contains('welcome-page')) {
            return;
        }

        const nav = document.querySelector(".nav");
                    const toggle = document.getElementById("toggle");
                    const links = document.querySelectorAll(".link");
                    const revealItems = document.querySelectorAll(".reveal");

                    if (toggle && nav) {
                        toggle.addEventListener("click", function () {
                            const open = nav.classList.toggle("open");
                            toggle.setAttribute("aria-expanded", open ? "true" : "false");
                        });
                    }

                    links.forEach(function (link) {
                        link.addEventListener("click", function () {
                            if (nav && nav.classList.contains("open")) {
                                nav.classList.remove("open");
                                if (toggle) {
                                    toggle.setAttribute("aria-expanded", "false");
                                }
                            }
                        });
                    });

                    if ("IntersectionObserver" in window) {
                        const observer = new IntersectionObserver(function (entries, obs) {
                            entries.forEach(function (entry) {
                                if (entry.isIntersecting) {
                                    entry.target.classList.add("show");
                                    obs.unobserve(entry.target);
                                }
                            });
                        }, { threshold: 0.16, rootMargin: "0px 0px -10% 0px" });

                        revealItems.forEach(function (item) {
                            observer.observe(item);
                        });
                    } else {
                        revealItems.forEach(function (item) {
                            item.classList.add("show");
                        });
                    }
    }

    function initAdminActivityChart() {
        const canvas = document.getElementById('activityChart');

        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const ctx = canvas.getContext('2d');

        if (!ctx) {
            return;
        }

        let chartData = [];

        try {
            chartData = JSON.parse(canvas.dataset.chartData || '[]');
        } catch (_) {
            chartData = [];
        }

        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(77, 142, 255, 0.95)');
        gradient.addColorStop(1, 'rgba(77, 142, 255, 0.20)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['6d ago', '5d ago', '4d ago', '3d ago', '2d ago', 'Yesterday', 'Today'],
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
        initAdminActivityChart();
    });
})();
