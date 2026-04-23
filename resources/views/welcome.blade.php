<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SENTINEL fintech SaaS platform. Simulate. Detect. Decide.">
    <title>SENTINEL | Simulate. Detect. Decide.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="welcome-page">
    <header class="nav" id="top">
        <div class="container nav-inner">
            <a class="brand" href="#top">SENTINEL</a>
            <button id="toggle" class="hamburger" aria-label="Toggle navigation" aria-expanded="false" aria-controls="menu">
                <span></span><span></span><span></span>
            </button>
            <nav class="menu" id="menu">
                <ul class="links">
                    <li><a class="link active" href="#features">Features</a></li>
                    <li><a class="link" href="#pricing">Pricing</a></li>
                    <li><a class="link" href="#docs">Docs</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="{{ route('login') }}" class="btn btn-dark">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero">
        <span class="glow blue"></span>
        <span class="glow green"></span>
        <div class="container hero-content">
            <div class="badge"><span class="dot"></span><span>Advanced Fintech Platform</span></div>
            <h1>Simulate. Detect. <span class="grad">Decide.</span></h1>
            <p>SENTINEL gives modern fintech teams one environment to run simulations, detect anomalies in real time, and turn risk signals into confident decisions.</p>
            <div class="hero-cta">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                <a href="#docs" class="btn btn-dark">See Platform Tour</a>
            </div>
        </div>
    </section>

    <section class="section" id="features">
        <div class="container">
            <p class="label">Features</p>
            <h2 class="title">Precision Engineering</h2>
            <div class="grid">
                <article class="card a">
                    <h3>Risk Simulation</h3>
                    <p>Run deterministic and stress-test scenarios to anticipate downstream exposure before it happens.</p>
                    <div class="mini">
                        <svg viewBox="0 0 520 180" width="100%" height="170" aria-label="Risk simulation chart">
                            <defs><linearGradient id="lineBlue" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#4D8EFF"/><stop offset="100%" stop-color="#ADC6FF"/></linearGradient></defs>
                            <path d="M8 146 C80 134,136 46,214 70 C268 86,320 138,392 98 C446 68,474 40,512 28" fill="none" stroke="url(#lineBlue)" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="214" cy="70" r="5" fill="#ADC6FF"/><circle cx="392" cy="98" r="5" fill="#4D8EFF"/><circle cx="512" cy="28" r="6" fill="#4AE176"/>
                        </svg>
                    </div>
                </article>
                <article class="card b">
                    <span class="icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z"/><path d="M9 12l2 2 4-4"/></svg>
                    </span>
                    <h3>Fraud Detection</h3>
                    <p>Adaptive rule intelligence with explainable match details for each event.</p>
                </article>
                <article class="card c">
                    <h3>Analytics</h3>
                    <p>Fast visibility into transaction quality and operational risk ratios.</p>
                    <div class="stack">
                        <div><div class="plabel">Signal Confidence</div><div class="bar"><div class="fill fill-blue"></div></div></div>
                        <div><div class="plabel">Resolution Rate</div><div class="bar"><div class="fill fill-green"></div></div></div>
                    </div>
                </article>
                <article class="card d">
                    <h3>Digital Sovereignty</h3>
                    <p>Deploy under your own controls with strict governance, data residency, and isolated infrastructure options.</p>
                    <div class="tags"><span class="tag">On-Premise</span><span class="tag">Air-Gapped</span></div>
                </article>
            </div>
        </div>
    </section>

    <section class="section command" id="docs">
        <div class="container">
            <h2 class="center-title">The Command Center</h2>
            <div class="glass shell">
                <div class="layout">
                    <aside class="side">
                        <div class="side-brand">SENTINEL Console</div>
                        <div class="side-nav">
                            <div class="item active">Overview</div><div class="item">Simulations</div><div class="item">Detections</div><div class="item">Alerts</div><div class="item">Reports</div>
                        </div>
                    </aside>
                    <div class="main">
                        <div class="metrics">
                            <div class="mcard"><div class="mlabel">Transactions</div><div class="mval">148,290</div></div>
                            <div class="mcard"><div class="mlabel">Risk Score</div><div class="mval">21.3</div></div>
                            <div class="mcard"><div class="mlabel">Open Alerts</div><div class="mval">37</div></div>
                        </div>
                        <div class="chart">
                            <p>Transaction Volatility</p>
                            <svg viewBox="0 0 740 260" width="100%" height="220" aria-label="Area chart">
                                <defs><linearGradient id="areaFill" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#4D8EFF" stop-opacity="0.5"/><stop offset="100%" stop-color="#4D8EFF" stop-opacity="0.06"/></linearGradient></defs>
                                <path d="M0 210 C66 180,130 120,194 132 C260 144,314 228,388 176 C448 136,512 72,574 98 C640 126,686 72,740 42" fill="none" stroke="#ADC6FF" stroke-width="4" stroke-linecap="round"/>
                                <path d="M0 260 L0 210 C66 180,130 120,194 132 C260 144,314 228,388 176 C448 136,512 72,574 98 C640 126,686 72,740 42 L740 260 Z" fill="url(#areaFill)"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="pricing">
        <div class="container test-wrap">
            <div>
                <h2>Trusted by the visionaries of fintech.</h2>
                <ul class="checks">
                    <li><span class="check">✓</span><span>Enterprise-grade monitoring without operational drag.</span></li>
                    <li><span class="check">✓</span><span>Fully explainable risk decisions for audit-ready workflows.</span></li>
                    <li><span class="check">✓</span><span>Rapid rollout across security, compliance, and fraud teams.</span></li>
                </ul>
            </div>
            <div class="quotes">
                <article class="q accent">
                    <p>"SENTINEL replaced three disconnected tools and gave us one source of truth for risk decisions in less than two weeks."</p>
                    <div class="author">Amina H., COO at FluxPay</div>
                </article>
                <article class="q dim">
                    <p>"The simulation engine helped us discover edge-case fraud paths before launch. That changed our release confidence."</p>
                    <div class="author">Rayan M., Head of Risk at NovaLedger</div>
                </article>
            </div>
        </div>
    </section>

    <section class="section cta">
        <div class="container">
            <div class="glass cta-box">
                <h2>Secure your future.</h2>
                <p>Deploy SENTINEL in minutes and give your fintech stack proactive risk intelligence from day one.</p>
                <div class="cta-action"><a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Free</a></div>
                <div class="fine">No credit card required.</div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container foot">
            <div class="foot-brand"><strong>SENTINEL</strong><span>Simulate. Detect. Decide.</span></div>
            <nav class="foot-nav"><a href="#">Privacy Policy</a><a href="#">Terms</a><a href="#">Blog</a><a href="#docs">Docs</a></nav>
        </div>
    </footer>

</body>
</html>
