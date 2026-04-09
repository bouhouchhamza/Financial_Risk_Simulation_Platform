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
    <style>
        :root{
            --bg-deep:#041424;--bg-card-1:#0C1D2D;--bg-card-2:#102131;--bg-card-3:#1B2B3C;
            --accent-blue:#ADC6FF;--accent-green:#4AE176;--text-primary:#D3E4FA;--text-muted:#C2C6D6;
            --btn-gradient:linear-gradient(90deg,#4D8EFF 0%,#ADC6FF 100%);
            --border:rgba(173,198,255,.22);--shadow:0 16px 36px rgba(0,0,0,.34);--max:1180px;
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0;scroll-behavior:smooth}
        body{font-family:"Inter",sans-serif;background:var(--bg-deep);color:var(--text-primary);line-height:1.5;overflow-x:hidden}
        a{text-decoration:none;color:inherit}
        .container{max-width:var(--max);margin:0 auto;padding:0 24px}
        .section{padding:100px 0}

        .nav{position:fixed;top:0;left:0;width:100%;z-index:1000;background:rgba(4,20,36,.88);border-bottom:1px solid rgba(173,198,255,.14);backdrop-filter:blur(10px)}
        .nav-inner{min-height:80px;display:flex;align-items:center;justify-content:space-between;gap:20px}
        .brand{font:700 1.35rem/1 "Manrope",sans-serif;color:var(--accent-blue);letter-spacing:.03em}
        .menu{display:flex;align-items:center;gap:26px}
        .links{display:flex;list-style:none;margin:0;padding:0;gap:20px}
        .link{position:relative;color:var(--text-muted);font-size:.95rem;font-weight:500;transition:color .2s}
        .link:hover{color:var(--text-primary)}
        .link.active{color:var(--accent-blue)}
        .link.active::after{content:"";position:absolute;left:0;right:0;bottom:-10px;height:2px;background:var(--accent-blue);border-radius:99px}
        .nav-actions{display:flex;gap:10px}
        .btn{display:inline-flex;align-items:center;justify-content:center;padding:11px 20px;border-radius:999px;border:1px solid transparent;font-size:.92rem;font-weight:600;cursor:pointer;transition:transform .2s,box-shadow .2s,border-color .2s}
        .btn:hover{transform:scale(1.02)}
        .btn-primary{background:var(--btn-gradient);color:#041424;box-shadow:0 10px 24px rgba(77,142,255,.35)}
        .btn-dark{background:rgba(16,33,49,.95);border-color:var(--border);color:var(--text-primary)}
        .btn-dark:hover{box-shadow:0 8px 18px rgba(173,198,255,.12)}
        .hamburger{display:none;width:42px;height:42px;border-radius:10px;border:1px solid var(--border);background:rgba(12,29,45,.9);align-items:center;justify-content:center;flex-direction:column;gap:4px;cursor:pointer}
        .hamburger span{display:block;width:20px;height:2px;background:var(--text-primary)}

        .hero{position:relative;overflow:hidden;padding:170px 0 110px}
        .hero-content{position:relative;z-index:2;max-width:920px}
        .badge{display:inline-flex;align-items:center;gap:10px;padding:8px 16px;border-radius:999px;border:1px solid rgba(74,225,118,.45);background:rgba(74,225,118,.12);color:#c8ffd7;font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em}
        .dot{width:8px;height:8px;border-radius:50%;background:var(--accent-green);box-shadow:0 0 0 0 rgba(74,225,118,.7);animation:pulse 1.8s infinite}
        .hero h1{margin:24px 0 0;font:800 96px/.95 "Manrope",sans-serif;letter-spacing:-.03em;color:#eef5ff}
        .grad{background:var(--btn-gradient);-webkit-background-clip:text;background-clip:text;color:transparent}
        .hero p{margin:24px 0 0;max-width:690px;color:var(--text-muted);font-size:1.12rem}
        .hero-cta{display:flex;flex-wrap:wrap;gap:14px;margin-top:34px}
        .glow{position:absolute;border-radius:50%;filter:blur(92px);pointer-events:none}
        .glow.blue{top:-170px;right:-160px;width:420px;height:420px;background:rgba(77,142,255,.36)}
        .glow.green{bottom:-190px;left:-160px;width:400px;height:400px;background:rgba(74,225,118,.28)}

        .label{color:var(--accent-blue);font-size:.78rem;letter-spacing:.12em;text-transform:uppercase;font-weight:600}
        .title{margin:12px 0 34px;font:800 52px/1.05 "Manrope",sans-serif;letter-spacing:-.02em}

        .grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:16px}
        .card{border:1px solid var(--border);border-radius:18px;padding:24px;box-shadow:var(--shadow);transition:transform .24s,box-shadow .24s,border-color .24s}
        .card:hover{transform:translateY(-4px);box-shadow:0 16px 30px rgba(77,142,255,.15);border-color:rgba(173,198,255,.35)}
        .card h3{margin:0;font:700 1.5rem/1.15 "Manrope",sans-serif}
        .card p{margin:12px 0 0;color:var(--text-muted);font-size:.95rem}
        .a{grid-column:1/9;min-height:290px;background:linear-gradient(145deg,#0C1D2D,#102131)}
        .b{grid-column:9/13;min-height:140px;background:linear-gradient(145deg,#102131,#12283a)}
        .c{grid-column:9/13;min-height:140px;background:linear-gradient(145deg,#0D1C2B,#112638)}
        .d{grid-column:1/9;min-height:220px;background:linear-gradient(145deg,#102131,#1B2B3C)}
        .mini{margin-top:18px;border:1px solid rgba(173,198,255,.18);border-radius:14px;background:rgba(4,20,36,.46);padding:12px}
        .icon{width:42px;height:42px;border-radius:12px;border:1px solid rgba(74,225,118,.4);background:rgba(74,225,118,.16);color:#92ffb0;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px}
        .stack{display:grid;gap:12px;margin-top:18px}
        .plabel{font-size:.79rem;color:var(--text-muted);font-weight:600;letter-spacing:.08em;text-transform:uppercase}
        .bar{height:9px;border-radius:999px;background:rgba(173,198,255,.18);overflow:hidden}
        .fill{height:100%}
        .fill-blue{width:82%;background:var(--btn-gradient)}
        .fill-green{width:64%;background:linear-gradient(90deg,#2dbf57 0%,#4AE176 100%)}
        .tags{margin-top:22px;display:flex;flex-wrap:wrap;gap:10px}
        .tag{padding:7px 12px;border-radius:999px;border:1px solid var(--border);font-size:.75rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--accent-blue);background:rgba(173,198,255,.1)}

        .command{background:#0C1D2D}
        .center-title{text-align:center;margin:0 0 34px;font:800 52px/1.05 "Manrope",sans-serif;letter-spacing:-.02em}
        .glass{border:1px solid rgba(173,198,255,.2);background:linear-gradient(135deg,rgba(27,43,60,.78),rgba(12,29,45,.65));border-radius:22px;box-shadow:0 16px 44px rgba(0,0,0,.35);backdrop-filter:blur(10px)}
        .shell{overflow:hidden}
        .layout{display:grid;grid-template-columns:240px 1fr}
        .side{padding:22px 18px;border-right:1px solid rgba(173,198,255,.16);background:rgba(4,20,36,.52)}
        .side-brand{font:700 1rem/1 "Manrope",sans-serif;color:var(--accent-blue);margin-bottom:22px}
        .side-nav{display:grid;gap:8px}
        .item{padding:10px 12px;border-radius:10px;border:1px solid transparent;color:var(--text-muted);font-size:.87rem}
        .item.active{border-color:rgba(173,198,255,.36);background:rgba(173,198,255,.1);color:var(--text-primary)}
        .main{padding:24px;display:grid;gap:18px}
        .metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
        .mcard{padding:14px;border-radius:14px;border:1px solid rgba(173,198,255,.2);background:rgba(4,20,36,.46)}
        .mlabel{font-size:.77rem;color:var(--text-muted);font-weight:600;text-transform:uppercase;letter-spacing:.08em}
        .mval{margin-top:8px;font-size:1.5rem;font-weight:700;color:#eff5ff}
        .chart{padding:14px;border-radius:14px;border:1px solid rgba(173,198,255,.2);background:rgba(4,20,36,.4)}
        .chart p{margin:0 0 10px;color:var(--text-muted);font-size:.86rem}

        .test-wrap{display:grid;grid-template-columns:1.05fr 1fr;gap:30px;align-items:start}
        .test-wrap h2{margin:0 0 24px;max-width:580px;font:800 52px/1.06 "Manrope",sans-serif;letter-spacing:-.02em}
        .checks{list-style:none;padding:0;margin:0;display:grid;gap:14px}
        .checks li{display:flex;gap:10px;color:var(--text-muted);font-size:1rem}
        .check{width:18px;height:18px;flex-shrink:0;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-top:1px;background:rgba(74,225,118,.2);color:var(--accent-green);font-size:.75rem;font-weight:700}
        .quotes{display:grid;gap:14px}
        .q{padding:20px;border-radius:16px;border:1px solid rgba(173,198,255,.2);background:var(--bg-card-1);box-shadow:var(--shadow);transition:transform .24s,box-shadow .24s}
        .q:hover{transform:translateY(-4px);box-shadow:0 14px 30px rgba(77,142,255,.15)}
        .q.accent{border-left:4px solid var(--accent-blue)}
        .q.dim{opacity:.6}
        .q p{margin:0;color:#e6efff}
        .author{margin-top:14px;color:var(--text-muted);font-size:.86rem;font-weight:600}

        .cta{padding-top:30px;padding-bottom:80px}
        .cta-box{max-width:920px;margin:0 auto;text-align:center;padding:50px 30px}
        .cta-box h2{margin:0;font:800 60px/1.02 "Manrope",sans-serif;letter-spacing:-.02em}
        .cta-box p{margin:16px auto 0;max-width:640px;color:var(--text-muted);font-size:1.05rem}
        .cta-action{margin-top:28px}
        .btn-lg{padding:14px 34px;font-size:1rem}
        .fine{margin-top:14px;color:var(--text-muted);font-size:.86rem}

        footer{border-top:1px solid rgba(173,198,255,.14);padding:24px 0 34px}
        .foot{display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap}
        .foot-brand{display:grid;gap:4px}
        .foot-brand strong{font:700 1.08rem/1 "Manrope",sans-serif;color:var(--accent-blue)}
        .foot-brand span{color:var(--text-muted);font-size:.88rem}
        .foot-nav{display:flex;gap:18px;flex-wrap:wrap}
        .foot-nav a{color:var(--text-muted);font-size:.9rem;transition:color .2s}
        .foot-nav a:hover{color:var(--accent-blue)}

        .load{opacity:0;transform:translateY(22px);animation:fade .8s cubic-bezier(.2,.65,.2,1) forwards}
        .d1{animation-delay:.05s}.d2{animation-delay:.12s}.d3{animation-delay:.19s}.d4{animation-delay:.26s}
        .reveal{opacity:0;transform:translateY(26px);transition:opacity .7s,transform .7s}
        .reveal.show{opacity:1;transform:translateY(0)}
        @keyframes fade{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(74,225,118,.7)}70%{box-shadow:0 0 0 9px rgba(74,225,118,0)}100%{box-shadow:0 0 0 0 rgba(74,225,118,0)}}

        @media (max-width:992px){
            .hero h1{font-size:74px}
            .title,.center-title,.test-wrap h2{font-size:44px}
            .layout{grid-template-columns:210px 1fr}
        }
        @media (max-width:768px){
            .section{padding:76px 0}.container{padding:0 18px}
            .nav-inner{min-height:72px}.hamburger{display:inline-flex}
            .menu{display:none;position:absolute;top:calc(100% + 1px);left:0;width:100%;padding:14px 18px 18px;border-bottom:1px solid rgba(173,198,255,.16);background:rgba(4,20,36,.98);flex-direction:column;align-items:stretch;gap:14px}
            .nav.open .menu{display:flex}.links{flex-direction:column;align-items:flex-start;gap:14px}
            .nav-actions{width:100%}.nav-actions .btn{flex:1}
            .hero{padding-top:130px;padding-bottom:80px}.hero h1{font-size:52px;line-height:1.02}.hero p{font-size:1rem}
            .glow.blue{width:280px;height:280px;top:-120px;right:-120px}.glow.green{width:260px;height:260px;bottom:-140px;left:-100px}
            .title,.center-title,.test-wrap h2{font-size:36px}
            .grid{grid-template-columns:1fr}.a,.b,.c,.d{grid-column:auto;min-height:auto}
            .layout{grid-template-columns:1fr}.side{border-right:none;border-bottom:1px solid rgba(173,198,255,.16)}
            .metrics{grid-template-columns:1fr}.test-wrap{grid-template-columns:1fr}
            .cta-box{padding:38px 20px}.cta-box h2{font-size:44px}
        }
        @media (max-width:480px){
            .hero h1{font-size:42px}.title,.center-title,.test-wrap h2{font-size:32px}.cta-box h2{font-size:36px}
        }
    </style>
</head>
<body>
    <header class="nav load d1" id="top">
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
            <div class="badge load d2"><span class="dot"></span><span>Advanced Fintech Platform</span></div>
            <h1 class="load d3">Simulate. Detect. <span class="grad">Decide.</span></h1>
            <p class="load d4">SENTINEL gives modern fintech teams one environment to run simulations, detect anomalies in real time, and turn risk signals into confident decisions.</p>
            <div class="hero-cta load d4">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                <a href="#docs" class="btn btn-dark">See Platform Tour</a>
            </div>
        </div>
    </section>

    <section class="section" id="features">
        <div class="container">
            <p class="label reveal">Features</p>
            <h2 class="title reveal">Precision Engineering</h2>
            <div class="grid">
                <article class="card a reveal">
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
                <article class="card b reveal">
                    <span class="icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z"/><path d="M9 12l2 2 4-4"/></svg>
                    </span>
                    <h3>Fraud Detection</h3>
                    <p>Adaptive rule intelligence with explainable match details for each event.</p>
                </article>
                <article class="card c reveal">
                    <h3>Analytics</h3>
                    <p>Fast visibility into transaction quality and operational risk ratios.</p>
                    <div class="stack">
                        <div><div class="plabel">Signal Confidence</div><div class="bar"><div class="fill fill-blue"></div></div></div>
                        <div><div class="plabel">Resolution Rate</div><div class="bar"><div class="fill fill-green"></div></div></div>
                    </div>
                </article>
                <article class="card d reveal">
                    <h3>Digital Sovereignty</h3>
                    <p>Deploy under your own controls with strict governance, data residency, and isolated infrastructure options.</p>
                    <div class="tags"><span class="tag">On-Premise</span><span class="tag">Air-Gapped</span></div>
                </article>
            </div>
        </div>
    </section>

    <section class="section command" id="docs">
        <div class="container">
            <h2 class="center-title reveal">The Command Center</h2>
            <div class="glass shell reveal">
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
            <div class="reveal">
                <h2>Trusted by the visionaries of fintech.</h2>
                <ul class="checks">
                    <li><span class="check">✓</span><span>Enterprise-grade monitoring without operational drag.</span></li>
                    <li><span class="check">✓</span><span>Fully explainable risk decisions for audit-ready workflows.</span></li>
                    <li><span class="check">✓</span><span>Rapid rollout across security, compliance, and fraud teams.</span></li>
                </ul>
            </div>
            <div class="quotes reveal">
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
            <div class="glass cta-box reveal">
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

    <script>
        (function () {
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
        })();
    </script>
</body>
</html>
