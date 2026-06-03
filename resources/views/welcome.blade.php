<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earn Wave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
</head>
<body>
<div class="grid-bg"></div>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="earn wave" onerror="this.style.display='none'">
        </a>
        <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="nav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item"><a class="nav-link" href="#how">Protocol</a></li>
                <li class="nav-item"><a class="nav-link" href="#packages">Tiers</a></li>
                <li class="nav-item"><a class="nav-link" href="#partners">Ecosystem</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">Support</a></li>
                @auth
                    <li class="nav-item ms-lg-2"><a class="btn-cyber-solid" href="{{ route('dashboard') }}">Dashboard</a></li>
                @else
                    <li class="nav-item ms-lg-2"><a class="btn-cyber" href="{{ route('login') }}"><span>Sign In</span></a></li>
                    <li class="nav-item ms-lg-1"><a class="btn-cyber-solid" href="{{ route('register') }}">Join Now</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="hero text-center">
    <div class="hero-orb orb1"></div>
    <div class="hero-orb orb2"></div>
    <div class="hero-orb orb3"></div>
    <div class="container position-relative" style="z-index:2">
        <div class="hero-label">⬡ Next-Gen Crypto Earning Protocol</div>
        <h1 class="hero-title">UNLOCK YOUR<br>EARNING<br><span class="cyan">POTENTIAL.</span></h1>
        <p class="hero-sub">Activate an investment tier, run daily micro-tasks, and stack crypto income automatically — powered by a blockchain-verified reward engine.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-lg-cyber">⚡ Open Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn-lg-cyber">⚡ Launch Your Wallet</a>
                <a href="{{ route('login') }}" class="btn-lg-ghost">Sign In</a>
            @endauth
        </div>
        <div class="stats-row">
            <div class="stat-item"><div class="stat-value">14,800+</div><div class="stat-label">Active Earners</div></div>
            <div class="stat-item"><div class="stat-value">$1.2M+</div><div class="stat-label">Rewards Paid</div></div>
            <div class="stat-item"><div class="stat-value">3.5M+</div><div class="stat-label">Tasks Completed</div></div>
            <div class="stat-item"><div class="stat-value">99.9%</div><div class="stat-label">Uptime</div></div>
        </div>
    </div>
</section>

<div class="trust-bar">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <div class="col-6 col-md-3"><div class="trust-item"><i class="bi bi-shield-lock-fill"></i> Military-Grade Security</div></div>
            <div class="col-6 col-md-3"><div class="trust-item"><i class="bi bi-currency-bitcoin"></i> On-Chain Payments</div></div>
            <div class="col-6 col-md-3"><div class="trust-item"><i class="bi bi-lightning-charge-fill"></i> Instant Rewards</div></div>
        </div>
    </div>
</div>

{{-- <section class="video-section">
    <video class="video-player" autoplay muted loop playsinline preload="auto">
        <source src="{{ asset('assets/video/home.mp4') }}" type="video/mp4">
    </video>
    <div class="video-overlay"></div>
</section> --}}

<section class="video-section position-relative overflow-hidden" style="background:var(--black);padding:80px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <!-- Background glow orbs -->
    <div style="position:absolute;width:400px;height:400px;background:radial-gradient(circle,rgba(0,71,255,0.2),transparent 70%);top:-100px;left:-100px;border-radius:50%;filter:blur(60px);pointer-events:none"></div>
    <div style="position:absolute;width:300px;height:300px;background:radial-gradient(circle,rgba(0,245,255,0.15),transparent 70%);bottom:-80px;right:-80px;border-radius:50%;filter:blur(60px);pointer-events:none"></div>

    <div class="container position-relative" style="z-index:2">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <div class="sec-label">// Platform Overview</div>
            <h2 class="sec-title">Your Earning Dashboard, Explained</h2>
            <div class="sec-line mx-auto"></div>
            <p style="color:var(--text-dim);max-width:520px;margin:1.2rem auto 0;font-size:0.95rem">Everything you need to earn, track, and withdraw — built into one clean interface. Here is exactly what you get access to from day one.</p>
        </div>

        <!-- 3 Feature Highlight Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div style="background:var(--surface);border:1px solid var(--border);padding:2rem;height:100%;position:relative;overflow:hidden;transition:all 0.3s" onmouseover="this.style.borderColor='var(--cyan)';this.style.boxShadow='var(--glow-cyan)'" onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    <div style="font-size:2.5rem;margin-bottom:1rem">⚡</div>
                    <div style="font-family:'Orbitron',monospace;font-size:0.78rem;font-weight:700;color:var(--cyan);letter-spacing:2px;text-transform:uppercase;margin-bottom:0.75rem">Real-Time Balance</div>
                    <p style="color:var(--text-dim);font-size:0.9rem;line-height:1.6;margin:0">Your wallet balance updates the moment a task is verified. No 24-hour delay, no batching — every completed task reflects instantly on your dashboard.</p>
                    <div style="margin-top:1.25rem;display:flex;align-items:center;gap:0.5rem">
                        <div style="width:8px;height:8px;background:var(--cyan);border-radius:50%;animation:pulse-border 1.5s infinite"></div>
                        <span style="font-family:'Orbitron',monospace;font-size:0.6rem;color:var(--cyan);letter-spacing:2px">LIVE TRACKING</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:var(--surface);border:1px solid var(--border);padding:2rem;height:100%;position:relative;overflow:hidden;transition:all 0.3s" onmouseover="this.style.borderColor='var(--cyan)';this.style.boxShadow='var(--glow-cyan)'" onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    <div style="font-size:2.5rem;margin-bottom:1rem">📋</div>
                    <div style="font-family:'Orbitron',monospace;font-size:0.78rem;font-weight:700;color:var(--cyan);letter-spacing:2px;text-transform:uppercase;margin-bottom:0.75rem">Daily Task Queue</div>
                    <p style="color:var(--text-dim);font-size:0.9rem;line-height:1.6;margin:0">Log in and your task queue is ready. Each task shows the reward amount upfront before you start. Complete at your own pace — tasks stay available for the full 24-hour window.</p>
                    <div style="margin-top:1.25rem;display:flex;align-items:center;gap:0.5rem">
                        <div style="width:8px;height:8px;background:#fbbf24;border-radius:50%"></div>
                        <span style="font-family:'Orbitron',monospace;font-size:0.6rem;color:#fbbf24;letter-spacing:2px">RESETS EVERY 24H</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div style="background:var(--surface);border:1px solid var(--border);padding:2rem;height:100%;position:relative;overflow:hidden;transition:all 0.3s" onmouseover="this.style.borderColor='var(--cyan)';this.style.boxShadow='var(--glow-cyan)'" onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    <div style="font-size:2.5rem;margin-bottom:1rem">💸</div>
                    <div style="font-family:'Orbitron',monospace;font-size:0.78rem;font-weight:700;color:var(--cyan);letter-spacing:2px;text-transform:uppercase;margin-bottom:0.75rem">One-Tap Withdrawal</div>
                    <p style="color:var(--text-dim);font-size:0.9rem;line-height:1.6;margin:0">Hit withdraw, enter your USDT TRC20 address, and confirm. Our team processes it within hours. No forms, no waiting days, no hidden steps between you and your money.</p>
                    <div style="margin-top:1.25rem;display:flex;align-items:center;gap:0.5rem">
                        <div style="width:8px;height:8px;background:#4ade80;border-radius:50%"></div>
                        <span style="font-family:'Orbitron',monospace;font-size:0.6rem;color:#4ade80;letter-spacing:2px">PROCESSED IN HOURS</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Strip -->
        <div style="background:var(--surface2);border:1px solid var(--border);padding:1.5rem 2rem;display:flex;justify-content:space-around;flex-wrap:wrap;gap:1rem">
            <div style="text-align:center">
                <div style="font-family:'Orbitron',monospace;font-size:1.4rem;font-weight:900;color:var(--cyan);text-shadow:var(--glow-cyan)">~8 min</div>
                <div style="font-size:0.72rem;color:var(--text-dim);letter-spacing:1px;text-transform:uppercase;margin-top:0.25rem">Avg. Daily Task Time</div>
            </div>
            <div style="width:1px;background:var(--border);flex-shrink:0"></div>
            <div style="text-align:center">
                <div style="font-family:'Orbitron',monospace;font-size:1.4rem;font-weight:900;color:var(--cyan);text-shadow:var(--glow-cyan)">1–6 hrs</div>
                <div style="font-size:0.72rem;color:var(--text-dim);letter-spacing:1px;text-transform:uppercase;margin-top:0.25rem">Withdrawal Processing</div>
            </div>
            <div style="width:1px;background:var(--border);flex-shrink:0"></div>
            <div style="text-align:center">
                <div style="font-family:'Orbitron',monospace;font-size:1.4rem;font-weight:900;color:var(--cyan);text-shadow:var(--glow-cyan)">$0</div>
                <div style="font-size:0.72rem;color:var(--text-dim);letter-spacing:1px;text-transform:uppercase;margin-top:0.25rem">Platform Fees</div>
            </div>
            <div style="width:1px;background:var(--border);flex-shrink:0"></div>
            <div style="text-align:center">
                <div style="font-family:'Orbitron',monospace;font-size:1.4rem;font-weight:900;color:var(--cyan);text-shadow:var(--glow-cyan)">Always</div>
                <div style="font-size:0.72rem;color:var(--text-dim);letter-spacing:1px;text-transform:uppercase;margin-top:0.25rem">Platform Availability</div>
            </div>
            <div style="width:1px;background:var(--border);flex-shrink:0"></div>
            <div style="text-align:center">
                <div style="font-family:'Orbitron',monospace;font-size:1.4rem;font-weight:900;color:var(--cyan);text-shadow:var(--glow-cyan)">TRC20</div>
                <div style="font-size:0.72rem;color:var(--text-dim);letter-spacing:1px;text-transform:uppercase;margin-top:0.25rem">Only Network Used</div>
            </div>
        </div>

    </div>
</section>

<section id="how" class="how-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Activation Protocol</div>
            <h2 class="sec-title">Three Steps to Earning</h2>
            <div class="sec-line mx-auto"></div>
            <p style="color:var(--text-dim);max-width:500px;margin:1.2rem auto 0;font-size:0.95rem">From zero to daily income in under 10 minutes. No trading experience required — just a wallet and a goal.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="step-card"><div class="step-num">01</div><div class="step-icon"><i class="bi bi-wallet2"></i></div><div class="step-title">Fund Your Wallet</div><p class="step-desc">Top up via USDT on the TRC20 network through Binance. Deposits confirm in seconds with zero platform fees and no minimums.</p><span class="step-tag">⚡ TRC20 · USDT · Zero Fees</span></div></div>
            <div class="col-md-4"><div class="step-card"><div class="step-num">02</div><div class="step-icon"><i class="bi bi-box-seam"></i></div><div class="step-title">Activate an Earning Tier</div><p class="step-desc">Pick the tier that fits your budget. Each one unlocks a fixed number of daily micro-tasks and a guaranteed earning ceiling — active from day one.</p><span class="step-tag">📦 One-Time · No Subscriptions</span></div></div>
            <div class="col-md-4"><div class="step-card"><div class="step-num">03</div><div class="step-icon"><i class="bi bi-check2-circle"></i></div><div class="step-title">Complete Tasks, Stack Rewards</div><p class="step-desc">Log in daily, complete your assigned tasks in minutes, and watch your balance grow. Withdraw directly to your crypto wallet anytime.</p><span class="step-tag">💰 Withdraw Anytime · No Lock</span></div></div>
        </div>
    </div>
</section>

<section id="packages" class="packages-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Earning Tiers</div>
            <h2 class="sec-title">Choose Your Tier</h2>
            <div class="sec-line mx-auto"></div>
            <p style="color:var(--text-dim);max-width:500px;margin:1.2rem auto 0;font-size:0.95rem">Every tier is a one-time activation — no subscriptions, no renewals. Just compounding daily rewards for the full duration.</p>
        </div>
        <div class="row g-4">
            @forelse($packages as $package)
            <div class="col-lg-4 col-md-6">
                <div class="pkg-card {{ $loop->iteration === 2 ? 'pkg-popular' : '' }}">
                    @if($loop->iteration === 2)<div class="pkg-ribbon">★ MOST POPULAR</div>@endif
                    <div class="pkg-header">
                        <div class="pkg-icon"><i class="bi bi-box-seam"></i></div>
                        <div class="pkg-name">{{ $package->name }}</div>
                        <div class="pkg-desc">{{ $package->description }}</div>
                    </div>
                    <div class="pkg-price-wrap">
                        <div class="pkg-price">${{ number_format($package->price, 2) }}</div>
                        <div class="pkg-price-label">One-time activation · No renewal</div>
                    </div>
                    <div class="pkg-features">
                        <div class="pkg-feature"><i class="bi bi-chevron-right"></i><span><strong>{{ $package->daily_tasks }}</strong> micro-tasks unlocked daily</span></div>
                        <div class="pkg-feature"><i class="bi bi-chevron-right"></i><span>Daily ceiling: <strong class="val">${{ number_format($package->daily_earning, 2) }}</strong></span></div>
                        <div class="pkg-feature"><i class="bi bi-chevron-right"></i><span>Active for <strong>{{ $package->duration_days === 0 ? 'Unlimited' : '' }}</strong> days</span></div>
                        <div class="pkg-feature"><i class="bi bi-chevron-right"></i><span>Max payout: <strong class="val-warn">${{ number_format($package->total_earning_potential, 2) }}</strong></span></div>
                        <div class="pkg-feature"><i class="bi bi-chevron-right"></i><span>ROI: <strong class="val">{{ number_format($package->roi_percentage, 1) }}%</strong></span></div>
                        @if($package->features)
                            @foreach(explode("\n", $package->features) as $feature)
                                @if(trim($feature))
                                    <div class="pkg-feature">
                                        <i class="bi bi-chevron-right"></i>
                                        <span>{{ trim($feature) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="pkg-actions">
                        @auth
                        <form id="purchase-form-{{ $package->slug }}" action="{{ route('packages.purchase', $package->slug) }}" method="POST">
                            @csrf
                            <button type="button" class="btn-lg-cyber" style="width:100%;text-align:center;cursor:pointer;margin-bottom:0.75rem"
                                onclick="confirmFormSubmit('purchase-form-{{ $package->slug }}',{title:'Confirm Activation?',text:'Activate {{ $package->name }} for ${{ number_format($package->price,2) }}?',confirmText:'Yes, Activate',cancelText:'Cancel'})">
                                ⚡ Activate Tier
                            </button>
                        </form>
                        @else
                        <a href="{{ route('register') }}" class="btn-lg-cyber" style="display:block;text-align:center;margin-bottom:0.75rem">⚡ Create Account to Activate</a>
                        @endauth
                        <a href="{{ route('packages.details', $package->slug) }}" class="btn-details"><i class="bi bi-info-circle me-1"></i> Full Tier Breakdown</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:var(--text-dim);display:block;margin-bottom:1rem"></i>
                <p style="color:var(--text-dim)">New tiers launching soon — check back shortly.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="supported-section">
    <div class="container text-center">
        <div class="sec-label mb-3">// Supported Networks</div>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <span class="crypto-badge">USDT · TRC20</span>
            <span class="crypto-badge">Binance Pay</span>
            <span class="crypto-badge">TRON Network</span>
            <span class="crypto-badge">Zero Deposit Fees</span>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Platform Capabilities</div>
            <h2 class="sec-title">Built for Earners</h2>
            <div class="sec-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-lightning-charge-fill feature-icon"></i><div class="feature-title">Instant Payouts</div><p class="feature-desc">Withdraw to your USDT wallet with no delays, no lock periods, no minimum waiting time.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-graph-up-arrow feature-icon"></i><div class="feature-title">Stackable Tiers</div><p class="feature-desc">Activate multiple tiers simultaneously to multiply your daily task allocation and earnings.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-shield-check feature-icon"></i><div class="feature-title">Verified Security</div><p class="feature-desc">Multi-layer transaction verification and manual audits protect every deposit and withdrawal.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-phone feature-icon"></i><div class="feature-title">Mobile First</div><p class="feature-desc">Fully optimized for smartphones — complete tasks and track earnings anywhere, anytime.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-people-fill feature-icon"></i><div class="feature-title">Referral Engine</div><p class="feature-desc">Earn bonus rewards for every referred user who activates a tier — unlimited referral potential.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-eye feature-icon"></i><div class="feature-title">Live Ledger</div><p class="feature-desc">Every task reward and withdrawal is logged in your personal earnings dashboard in real time.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-arrow-repeat feature-icon"></i><div class="feature-title">Daily Reset</div><p class="feature-desc">Task quotas refresh every 24 hours — a fresh earning window opens every single day.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="feature-card"><i class="bi bi-globe feature-icon"></i><div class="feature-title">Global Access</div><p class="feature-desc">Available worldwide with multilingual support and crypto-native payment infrastructure.</p></div></div>
        </div>
    </div>
</section>

<section id="partners" class="partners-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Revenue Sources</div>
            <h2 class="sec-title">What Powers Your Rewards</h2>
            <div class="sec-line mx-auto"></div>
            <p style="color:var(--text-dim);max-width:560px;margin:1.2rem auto 0;font-size:0.95rem">Earn Wave generates real revenue through diversified global industry exposure — keeping your daily rewards backed by genuine market activity, not speculation.</p>
        </div>
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">🛍️</span>
                    <div class="partner-title">Affiliate Sales</div>
                    <p class="partner-desc">When you complete product tasks, we earn a commission from the merchant. That commission is what funds the reward for that task — direct 1-to-1 revenue.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">▶️</span>
                    <div class="partner-title">Video Ad Spend</div>
                    <p class="partner-desc">Advertisers pay CPV (cost-per-view) rates to platforms like ours. Your verified video views generate ad revenue that is pooled and paid out daily.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">⭐</span>
                    <div class="partner-title">Review Contracts</div>
                    <p class="partner-desc">App developers and e-commerce brands pay fixed rates per verified review. We collect these fees and distribute them to the users who completed the review tasks.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">📊</span>
                    <div class="partner-title">Survey Panel Revenue</div>
                    <p class="partner-desc">Market research companies pay $0.50–$5 per completed survey response. We aggregate responses across our user base and pass the majority of that fee back to earners.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">📱</span>
                    <div class="partner-title">Social Engagement Deals</div>
                    <p class="partner-desc">Brands run paid engagement campaigns through us — paying per follow, like, or share on their content. These are negotiated monthly contracts with fixed budgets.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">🔗</span>
                    <div class="partner-title">Exchange Referral Fees</div>
                    <p class="partner-desc">Crypto exchanges pay us CPA (cost-per-acquisition) fees when referred users register and trade. These referral payouts are among our highest per-task revenue sources.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">🎯</span>
                    <div class="partner-title">CPC Ad Network</div>
                    <p class="partner-desc">We run a cost-per-click ad network where businesses bid for verified traffic. Click revenue from your daily ad tasks is tracked, pooled, and distributed transparently.</p>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-3">
                <div class="partner-card">
                    <span class="partner-emoji">🤝</span>
                    <div class="partner-title">Brand Retainer Contracts</div>
                    <p class="partner-desc">Select brands pay us monthly retainer fees for guaranteed exposure volume. These fixed contracts provide a stable baseline revenue that keeps the reward pool funded regardless of daily ad fluctuations.</p>
                </div>
            </div>
            {{-- <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">🥤</span><div class="partner-title">Beverage Distribution</div><p class="partner-desc">Branded supply chains operating across 12+ regional markets globally.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">🚗</span><div class="partner-title">Auto & Mobility</div><p class="partner-desc">Vehicle logistics networks and international fleet management operations.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">🍽️</span><div class="partner-title">Food & Catering</div><p class="partner-desc">Institutional contracts supporting large-scale corporate and event catering.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">🏨</span><div class="partner-title">Hospitality & Tourism</div><p class="partner-desc">Hotel groups and travel platforms generating high-margin service revenue.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">🛒</span><div class="partner-title">Retail & FMCG</div><p class="partner-desc">High-volume consumer goods with daily turnover driving consistent cash flow.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">⚡</span><div class="partner-title">Energy & Utilities</div><p class="partner-desc">Infrastructure-grade energy contracts delivering stable long-term returns.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">💻</span><div class="partner-title">Tech & SaaS</div><p class="partner-desc">Recurring SaaS licenses and digital product royalties from tech partners.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="partner-card"><span class="partner-emoji">📦</span><div class="partner-title">Logistics & Supply Chain</div><p class="partner-desc">Cross-border networks generating volume-based commissions every day.</p></div></div> --}}
        </div>
    </div>
</section>

<section class="why-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Competitive Edge</div>
            <h2 class="sec-title">Why Earn Wave Wins</h2>
            <div class="sec-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-ban"></i></div><div class="why-card-title">Zero Hidden Fees</div><p class="why-card-desc">No platform tax, no withdrawal penalties, no surprises. What you earn is what you keep.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-eye"></i></div><div class="why-card-title">Full Transparency</div><p class="why-card-desc">Every task, reward, and payout logged with timestamps in your personal earnings ledger.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-arrows-angle-expand"></i></div><div class="why-card-title">Scale at Will</div><p class="why-card-desc">Stack multiple tiers to multiply daily task volume and compound your income over time.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-robot"></i></div><div class="why-card-title">Automated Engine</div><p class="why-card-desc">Tasks refresh and rewards credit automatically — the system works even while you sleep.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-currency-exchange"></i></div><div class="why-card-title">Fast Withdrawals</div><p class="why-card-desc">Crypto withdrawals processed within hours, directly to your TRC20 wallet address.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-phone-flip"></i></div><div class="why-card-title">Mobile Optimized</div><p class="why-card-desc">Manage your full earning operation from any smartphone — seamless on every screen size.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-person-check"></i></div><div class="why-card-title">Verified Users Only</div><p class="why-card-desc">Light KYC keeps the platform fraud-free and every active earner's rewards protected.</p></div></div>
            <div class="col-6 col-md-4 col-lg-3"><div class="why-card"><div class="why-card-icon"><i class="bi bi-headset"></i></div><div class="why-card-title">Live Support</div><p class="why-card-desc">Real human agents available around the clock via live chat, Telegram, and ticket system.</p></div></div>
        </div>
    </div>
</section>

<section class="launch-section">
    {{-- <img src="{{ asset('assets/images/launching.png') }}" class="launch-bg" alt=""> --}}
    <div class="launch-overlay"></div>
    <div class="launch-content">
        <div class="container text-center">
            <div class="launch-badge">✓ Live Since 05 March 2026</div>
            <h2 class="hero-title" style="font-size:clamp(1.8rem,5vw,3rem);margin-bottom:1rem">The Future of<br><span class="cyan">Task Earning</span> Is Here.</h2>
            <p class="hero-sub" style="max-width:560px;margin:0 auto 2rem">Thousands of earners worldwide have already activated their tiers. Every day you wait is income left on the table.</p>
            <div class="row g-3 justify-content-center mb-4" style="max-width:680px;margin:0 auto 2rem">
                <div class="col-4"><div style="background:rgba(0,245,255,0.06);border:1px solid var(--border);padding:1rem"><i class="bi bi-check2-circle" style="color:var(--cyan);font-size:1.5rem;display:block;margin-bottom:0.5rem"></i><div style="font-family:'Orbitron',monospace;font-size:0.6rem;color:#fff;letter-spacing:1px">Daily Tasks</div><div style="font-size:0.75rem;color:var(--text-dim)">Complete & Earn</div></div></div>
                <div class="col-4"><div style="background:rgba(0,245,255,0.06);border:1px solid var(--border);padding:1rem"><i class="bi bi-box-seam" style="color:var(--cyan);font-size:1.5rem;display:block;margin-bottom:0.5rem"></i><div style="font-family:'Orbitron',monospace;font-size:0.6rem;color:#fff;letter-spacing:1px">Earning Tiers</div><div style="font-size:0.75rem;color:var(--text-dim)">Stack Multiple</div></div></div>
                <div class="col-4"><div style="background:rgba(0,245,255,0.06);border:1px solid var(--border);padding:1rem"><i class="bi bi-wallet2" style="color:var(--cyan);font-size:1.5rem;display:block;margin-bottom:0.5rem"></i><div style="font-family:'Orbitron',monospace;font-size:0.6rem;color:#fff;letter-spacing:1px">Crypto Wallet</div><div style="font-size:0.75rem;color:var(--text-dim)">Withdraw Freely</div></div></div>
            </div>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-lg-cyber">⚡ Open Dashboard</a>
                    <a href="{{ route('packages.index') }}" class="btn-lg-ghost">Browse Tiers</a>
                @else
                    <a href="{{ route('register') }}" class="btn-lg-cyber">⚡ Get Started Free</a>
                    <a href="{{ route('login') }}" class="btn-lg-ghost">Sign In</a>
                @endauth
            </div>
        </div>
    </div>
</section>

<section class="testimonials-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Earner Reports</div>
            <h2 class="sec-title">Real Results, Real People</h2>
            <div class="sec-line mx-auto"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"I was skeptical at first — but after my first withdrawal hit my Binance wallet in under two hours, I was sold. Now running three tiers simultaneously and earning every day."</p><div class="testi-name">A. RAHMAN</div><div class="testi-role">Tier 3 Active Earner · Malaysia</div></div></div>
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"The dashboard is clean, the tasks are simple, and the payouts are always on time. I complete my daily quota in under 15 minutes every morning before work."</p><div class="testi-name">F. OKONKWO</div><div class="testi-role">Tier 2 Active Earner · Nigeria</div></div></div>
            <div class="col-md-4"><div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"I referred 14 people in my first month. The referral bonuses alone covered my initial investment. Completely legitimate passive income — I wish I'd joined sooner."</p><div class="testi-name">P. NGUYEN</div><div class="testi-role">Tier 1 Active Earner · Vietnam</div></div></div>
        </div>
    </div>
</section>

<section id="faq" class="faq-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="sec-label">// Knowledge Base</div>
            <h2 class="sec-title">Common Questions</h2>
            <div class="sec-line mx-auto"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAcc">
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#f1">How are my daily rewards actually generated?</button></h2><div id="f1" class="accordion-collapse collapse show"><div class="accordion-body">Your rewards are funded by Earn Wave diversified revenue ecosystem — a blend of brand partnerships, affiliate commissions, and collaborations across retail, logistics, tech, and hospitality. Completing daily tasks drives platform engagement metrics that generate this revenue, and your proportional share credits automatically each day.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f2">Can I activate multiple tiers at the same time?</button></h2><div id="f2" class="accordion-collapse collapse"><div class="accordion-body">Yes — you can run multiple different tiers simultaneously. Each tier adds its own daily task pool and earning ceiling. You cannot hold two identical tiers at once, but combining different tiers is fully supported and a popular strategy among top earners on the platform.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f3">How fast are withdrawals processed?</button></h2><div id="f3" class="accordion-collapse collapse"><div class="accordion-body">Withdrawal requests are reviewed by our finance team and typically complete within 1–6 hours. Funds transfer directly to your USDT TRC20 wallet address. There is a minimum withdrawal threshold shown in your dashboard, and Earn Wave charges zero withdrawal fees.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f4">Is there a referral or affiliate program?</button></h2><div id="f4" class="accordion-collapse collapse"><div class="accordion-body">Every registered account receives a unique referral link from day one. When someone signs up through your link and activates any tier, you earn a referral commission credited instantly to your balance. There is no cap on referrals — many users have built significant secondary income through referrals alone.</div></div></div>
                    <div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f5">What happens when my tier's duration ends?</button></h2><div id="f5" class="accordion-collapse collapse"><div class="accordion-body">When a tier's validity period expires, daily tasks for that tier stop generating new rewards. Your accumulated balance remains safely in your wallet and can be withdrawn anytime. You can then re-activate the same tier or upgrade to a higher one to continue earning.</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact-section">
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center mb-5">
                    <div class="sec-label">// Open Comms Channel</div>
                    <h2 class="sec-title">Talk to Our Team</h2>
                    <div class="sec-line mx-auto"></div>
                    <p style="color:var(--text-dim);max-width:420px;margin:1.2rem auto 0;font-size:0.95rem">Have a pre-investment question or need account support? We respond to every message within 2 hours.</p>
                </div>
                <div class="contact-form mb-5">
                    <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="mb-4"><label class="form-label">Your Name</label><div class="input-group"><span class="input-group-text"><i class="bi bi-person"></i></span><input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required></div></div>
                        <div class="mb-4"><label class="form-label">Email Address</label><div class="input-group"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input type="email" name="email" class="form-control" placeholder="you@example.com" required></div></div>
                        <div class="mb-4"><label class="form-label">Topic</label><div class="input-group"><span class="input-group-text"><i class="bi bi-tag"></i></span><input type="text" name="subject" class="form-control" placeholder="e.g. Withdrawal issue, Tier question"></div></div>
                        <div class="mb-4"><label class="form-label">Message</label><textarea name="message" rows="4" class="form-control" placeholder="Describe your question in detail..." required></textarea></div>
                        <button type="submit" class="btn-lg-cyber" style="width:100%;text-align:center;cursor:pointer">
                            <span class="btn-text">⚡ Send Message</span>
                            <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-2"></span> Transmitting...</span>
                        </button>
                    </form>
                </div>
                <div class="row g-4 mb-5">
                    <div class="col-4"><div style="display:flex;flex-direction:column;align-items:center;text-align:center;gap:0.6rem"><div class="contact-icon"><i class="bi bi-telegram"></i></div><div class="contact-label">Telegram</div><div class="contact-val">@EarnWaveSupport</div></div></div>
                    <div class="col-4"><div style="display:flex;flex-direction:column;align-items:center;text-align:center;gap:0.6rem"><div class="contact-icon"><i class="bi bi-envelope-fill"></i></div><div class="contact-label">Email</div><div class="contact-val">support@EarnWave.io</div></div></div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="social-link"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-discord"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container position-relative" style="z-index:2">
        <div class="sec-label mb-3">// Your Move</div>
        <h2 class="sec-title" style="font-size:clamp(1.6rem,4vw,2.4rem);margin-bottom:1rem">Every Day Without a Tier<br>Is Income You're Missing.</h2>
        <p style="color:var(--text-dim);margin-bottom:2.5rem;max-width:480px;margin-left:auto;margin-right:auto;font-size:0.95rem">Join 14,800+ earners who wake up to daily crypto rewards. Activate your first tier in less than 3 minutes.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn-lg-cyber">⚡ Create Free Account</a>
            <a href="#packages" class="btn-lg-ghost">Explore Tiers</a>
        </div>
    </div>
</section>

<div class="disclaimer">
    <div class="container"><p>⚠ Disclaimer: Earn Wave does not guarantee fixed returns. Daily earnings vary based on task completion rate, tier level, and platform performance. Crypto investments carry risk — only invest what you can afford to lose.</p></div>
</div>

<footer>
    <p style="margin-bottom:0.5rem">
        <a href="#how" style="color:var(--text-dim);text-decoration:none;margin:0 0.75rem;font-size:0.78rem;font-family:'Orbitron',monospace;letter-spacing:1px">Protocol</a>
        <a href="#packages" style="color:var(--text-dim);text-decoration:none;margin:0 0.75rem;font-size:0.78rem;font-family:'Orbitron',monospace;letter-spacing:1px">Tiers</a>
        <a href="#partners" style="color:var(--text-dim);text-decoration:none;margin:0 0.75rem;font-size:0.78rem;font-family:'Orbitron',monospace;letter-spacing:1px">Ecosystem</a>
        <a href="#faq" style="color:var(--text-dim);text-decoration:none;margin:0 0.75rem;font-size:0.78rem;font-family:'Orbitron',monospace;letter-spacing:1px">Support</a>
    </p>
    <p>© {{ date('Y') }} Earn Wave — All Rights Reserved. | Built on Trust & Transparency.</p>
</footer>

<div class="mobile-cta d-lg-none">
    <a href="{{ route('register') }}" class="btn-lg-cyber">⚡ Launch Your Wallet — Free</a>
</div>

<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center py-4">
                <div id="modalIcon" class="mb-3"></div>
                <h5 id="modalTitle" class="mb-2" style="font-family:'Orbitron',monospace;font-size:0.9rem;letter-spacing:1px"></h5>
                <p id="modalMessage" class="mb-0" style="color:var(--text-dim)"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn-cyber" data-bs-dismiss="modal"><span>Close</span></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script>
document.getElementById('contactForm').addEventListener('submit',function(e){
    e.preventDefault();
    const form=this,btn=form.querySelector('button[type="submit"]'),
    t=btn.querySelector('.btn-text'),l=btn.querySelector('.btn-loading');
    btn.disabled=true;t.classList.add('d-none');l.classList.remove('d-none');
    fetch(form.action,{method:'POST',body:new FormData(form),headers:{'X-Requested-With':'XMLHttpRequest'}})
    .then(r=>r.json()).then(data=>{
        btn.disabled=false;t.classList.remove('d-none');l.classList.add('d-none');
        showModal(data.success?'success':'error',data.success?'Transmission Sent!':'Error',data.message||'Something went wrong.');
        if(data.success)form.reset();
    }).catch(()=>{
        btn.disabled=false;t.classList.remove('d-none');l.classList.add('d-none');
        showModal('error','Connection Failed','Unable to reach the server. Please try again.');
    });
});
function showModal(type,title,message){
    const modal=new bootstrap.Modal(document.getElementById('contactModal'));
    document.getElementById('modalIcon').innerHTML=type==='success'
        ?'<i class="bi bi-check-circle-fill" style="font-size:3rem;color:var(--cyan);text-shadow:var(--glow-cyan)"></i>'
        :'<i class="bi bi-exclamation-circle-fill" style="font-size:3rem;color:#f87171"></i>';
    const t=document.getElementById('modalTitle');
    t.textContent=title;t.style.color=type==='success'?'var(--cyan)':'#f87171';
    document.getElementById('modalMessage').textContent=message;
    modal.show();
}
</script>
</body>
</html>
