<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TopTrade — Expert-Led Crypto Trading</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=IBM+Plex+Mono:wght@300;400;500&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
</head>
<body>

<!-- Live Ticker -->
<div class="ticker-wrap">
  <div class="ticker-track" id="ticker">
    <span class="tick-item"><span class="tick-sym">BTC/USDT</span><span class="tick-price">$67,842.30</span><span class="tick-up">▲ +2.14%</span></span>
    <span class="tick-item"><span class="tick-sym">ETH/USDT</span><span class="tick-price">$3,521.88</span><span class="tick-up">▲ +1.87%</span></span>
    <span class="tick-item"><span class="tick-sym">SOL/USDT</span><span class="tick-price">$182.44</span><span class="tick-dn">▼ -0.62%</span></span>
    <span class="tick-item"><span class="tick-sym">BNB/USDT</span><span class="tick-price">$598.10</span><span class="tick-up">▲ +0.91%</span></span>
    <span class="tick-item"><span class="tick-sym">XRP/USDT</span><span class="tick-price">$0.6231</span><span class="tick-dn">▼ -1.04%</span></span>
    <span class="tick-item"><span class="tick-sym">ADA/USDT</span><span class="tick-price">$0.4482</span><span class="tick-up">▲ +3.22%</span></span>
    <span class="tick-item"><span class="tick-sym">AVAX/USDT</span><span class="tick-price">$36.77</span><span class="tick-up">▲ +1.55%</span></span>
    <span class="tick-item"><span class="tick-sym">MATIC/USDT</span><span class="tick-price">$0.8814</span><span class="tick-dn">▼ -0.38%</span></span>
    <!-- duplicate for seamless loop -->
    <span class="tick-item"><span class="tick-sym">BTC/USDT</span><span class="tick-price">$67,842.30</span><span class="tick-up">▲ +2.14%</span></span>
    <span class="tick-item"><span class="tick-sym">ETH/USDT</span><span class="tick-price">$3,521.88</span><span class="tick-up">▲ +1.87%</span></span>
    <span class="tick-item"><span class="tick-sym">SOL/USDT</span><span class="tick-price">$182.44</span><span class="tick-dn">▼ -0.62%</span></span>
    <span class="tick-item"><span class="tick-sym">BNB/USDT</span><span class="tick-price">$598.10</span><span class="tick-up">▲ +0.91%</span></span>
    <span class="tick-item"><span class="tick-sym">XRP/USDT</span><span class="tick-price">$0.6231</span><span class="tick-dn">▼ -1.04%</span></span>
    <span class="tick-item"><span class="tick-sym">ADA/USDT</span><span class="tick-price">$0.4482</span><span class="tick-up">▲ +3.22%</span></span>
    <span class="tick-item"><span class="tick-sym">AVAX/USDT</span><span class="tick-price">$36.77</span><span class="tick-up">▲ +1.55%</span></span>
    <span class="tick-item"><span class="tick-sym">MATIC/USDT</span><span class="tick-price">$0.8814</span><span class="tick-dn">▼ -0.38%</span></span>
  </div>
</div>

<!-- Navbar -->
<nav class="tt-nav" style="top:36px;">
  <div class="tt-nav-inner">
    <a class="tt-brand" href="{{ route('welcome') }}">
      <img src="{{ asset('assets/images/logo.png') }}" alt="TopTrade" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <span style="display:none">Top<em>Trade</em></span>
    </a>
    <ul class="tt-nav-links">
      <li><a href="#how">How It Works</a></li>
      <li><a href="#plans">Plans</a></li>
      <li><a href="#sources">Revenue</a></li>
      <li><a href="#faq">FAQ</a></li>
    </ul>
    <div class="tt-nav-actions">
      @auth
        <a href="{{ route('dashboard') }}" class="tt-btn-solid">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="tt-btn-ghost">Sign In</a>
        <a href="{{ route('register') }}" class="tt-btn-solid">Open Account</a>
      @endauth
    </div>
    <button class="tt-hamburger d-lg-none" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="hero-grid"></div>
  <div class="hero-glow"></div>
  <div class="hero-container">
    <div class="hero-left">
      <div class="hero-eyebrow">Expert-Led Crypto Trading Platform</div>
      <h1 class="hero-title">
        Your Capital.<br>
        Our <em>Expert</em><br>
        Trading Team.
      </h1>
      <p class="hero-body">
        Deposit USDT, activate a trading plan, and let our professional analysts execute high-yield strategies on your behalf. Transparent performance, daily returns, no guesswork.
      </p>
      <div class="hero-ctas">
        @auth
          <a href="{{ route('dashboard') }}" class="btn-hero-primary">Open Dashboard →</a>
        @else
          <a href="{{ route('register') }}" class="btn-hero-primary">Open Free Account →</a>
          <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
        @endauth
      </div>

      <div style="display:flex;gap:3rem;margin-top:3rem;padding-top:2rem;border-top:1px solid var(--border)">
        <div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:1.5rem;font-weight:500;color:var(--gold)">14,800+</div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:0.08em;text-transform:uppercase;margin-top:4px">Active Traders</div>
        </div>
        <div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:1.5rem;font-weight:500;color:var(--gold)">$1.2M+</div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:0.08em;text-transform:uppercase;margin-top:4px">Profits Distributed</div>
        </div>
        <div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:1.5rem;font-weight:500;color:var(--gold)">99.9%</div>
          <div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:0.08em;text-transform:uppercase;margin-top:4px">Uptime</div>
        </div>
      </div>
    </div>

    <!-- Trading panel -->
    <div class="hero-panel">
      <div class="panel-header">
        <span class="panel-title">Portfolio · Live</span>
        <div class="panel-dots">
          <div class="panel-dot"></div>
          <div class="panel-dot"></div>
          <div class="panel-dot"></div>
        </div>
      </div>
      <div class="panel-body">
        <div class="asset-row">
          <div class="asset-info">
            <div class="asset-icon">BTC</div>
            <div><div class="asset-name">Bitcoin</div><div class="asset-sub">BTC/USDT · Spot</div></div>
          </div>
          <div class="asset-price">
            <div class="price-val">$67,842</div>
            <div class="price-chg up">▲ +2.14%</div>
          </div>
        </div>
        <div class="asset-row">
          <div class="asset-info">
            <div class="asset-icon">ETH</div>
            <div><div class="asset-name">Ethereum</div><div class="asset-sub">ETH/USDT · Spot</div></div>
          </div>
          <div class="asset-price">
            <div class="price-val">$3,521</div>
            <div class="price-chg up">▲ +1.87%</div>
          </div>
        </div>
        <div class="asset-row">
          <div class="asset-info">
            <div class="asset-icon">SOL</div>
            <div><div class="asset-name">Solana</div><div class="asset-sub">SOL/USDT · Spot</div></div>
          </div>
          <div class="asset-price">
            <div class="price-val">$182.44</div>
            <div class="price-chg dn">▼ -0.62%</div>
          </div>
        </div>
        <!-- Mini chart SVG -->
        <div class="panel-chart">
          <svg viewBox="0 0 320 80" preserveAspectRatio="none">
            <defs>
              <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#C9A84C" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="#C9A84C" stop-opacity="0"/>
              </linearGradient>
            </defs>
            <path d="M0,65 C20,60 40,55 60,50 C80,45 90,52 110,44 C130,36 140,40 160,30 C180,20 200,25 220,18 C240,11 260,15 280,8 C300,1 310,5 320,3" fill="none" stroke="#C9A84C" stroke-width="1.5"/>
            <path d="M0,65 C20,60 40,55 60,50 C80,45 90,52 110,44 C130,36 140,40 160,30 C180,20 200,25 220,18 C240,11 260,15 280,8 C300,1 310,5 320,3 L320,80 L0,80 Z" fill="url(#chartGrad)"/>
          </svg>
        </div>
      </div>
      <div class="panel-footer">
        <div class="pf-stat"><div class="pf-label">Today's Return</div><div class="pf-val up">+$248.60</div></div>
        <div class="pf-stat"><div class="pf-label">Total Balance</div><div class="pf-val">$12,842.30</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Trust Strip -->
<div class="trust-strip">
  <div class="trust-inner">
    <div class="trust-item"><i class="bi bi-shield-lock-fill"></i> Bank-Grade Security</div>
    <div class="trust-item"><i class="bi bi-people-fill"></i> Professional Trading Desk</div>
    <div class="trust-item"><i class="bi bi-currency-bitcoin"></i> USDT TRC20 Payments</div>
    <div class="trust-item"><i class="bi bi-graph-up-arrow"></i> Daily Profit Distributions</div>
    <div class="trust-item"><i class="bi bi-eye"></i> Full Trade Transparency</div>
  </div>
</div>

<!-- How It Works -->
<section id="how" class="how-section section">
  <div class="container">
    <div class="sec-eyebrow">Protocol</div>
    <h2 class="sec-title">Three Steps to<br><em>Daily Returns</em></h2>
    <p class="sec-body">From deposit to profit in under 10 minutes. No trading knowledge required — our expert desk handles all execution.</p>
    <div class="steps-grid">
      <div class="step">
        <div class="step-num">01</div>
        <div class="step-title">Deposit & Fund</div>
        <p class="step-body">Top up your account with USDT via the TRC20 network through Binance. Deposits confirm within seconds. Zero platform fees, no minimum threshold.</p>
        <span class="step-tag">USDT · TRC20 · Zero Fees</span>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <div class="step-title">Select a Trading Plan</div>
        <p class="step-body">Choose the plan that matches your capital and return targets. Each plan activates a dedicated position size managed by our trading analysts — live from day one.</p>
        <span class="step-tag">One-Time · No Subscriptions</span>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <div class="step-title">Collect Daily Profits</div>
        <p class="step-body">Our team executes trades around the clock. Your daily profit share credits automatically to your balance. Withdraw directly to your USDT wallet any time.</p>
        <span class="step-tag">Withdraw Anytime · No Lock</span>
      </div>
    </div>
  </div>
</section>

<!-- Trading Plans -->
<section id="plans" class="plans-section section">
  <div class="container">
    <div class="sec-eyebrow">Trading Plans</div>
    <h2 class="sec-title">Choose Your <em>Allocation</em></h2>
    <p class="sec-body">Every plan is a one-time activation — no subscriptions, no renewals. Lock in your allocation and compound daily returns for the full duration.</p>
    <div class="plans-grid">
      @forelse($packages as $package)
      <div class="plan-card {{ $loop->iteration === 2 ? 'featured' : '' }}">
        @if($loop->iteration === 2)<div class="plan-badge">Most Popular</div>@endif
        <div class="plan-name">{{ $package->name }}</div>
        <div class="plan-desc">{{ $package->description }}</div>
        <div class="plan-price">${{ number_format($package->price, 2) }}</div>
        <div class="plan-price-note">One-time activation · No renewal</div>
        <div class="plan-divider"></div>
        <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>{{ $package->daily_tasks }} daily trading sessions allocated</span></div>
        <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>Daily ceiling: <strong>${{ number_format($package->daily_earning, 2) }}</strong></span></div>
        <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>Active for {{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days }} days</span></div>
        <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>Max payout: <strong>${{ number_format($package->total_earning_potential, 2) }}</strong></span></div>
        <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>ROI: <strong>{{ number_format($package->roi_percentage, 1) }}%</strong></span></div>
        @if($package->features)
          @foreach(explode("\n", $package->features) as $feature)
            @if(trim($feature))
              <div class="plan-feature"><i class="bi bi-check plan-feature-icon"></i> <span>{{ trim($feature) }}</span></div>
            @endif
          @endforeach
        @endif
        <div class="plan-actions">
          @auth
            <form id="purchase-form-{{ $package->slug }}" action="{{ route('packages.purchase', $package->slug) }}" method="POST">
              @csrf
              <button type="button" class="btn-plan btn-plan-primary" style="cursor:pointer;width:100%;font-family:'IBM Plex Mono',monospace;"
                onclick="confirmFormSubmit('purchase-form-{{ $package->slug }}',{title:'Confirm Activation?',text:'Activate {{ $package->name }} for ${{ number_format($package->price,2) }}?',confirmText:'Yes, Activate',cancelText:'Cancel'})">
                Activate Plan →
              </button>
            </form>
          @else
            <a href="{{ route('register') }}" class="btn-plan btn-plan-primary">Open Account to Activate →</a>
          @endauth
          <a href="{{ route('packages.details', $package->slug) }}" class="btn-plan btn-plan-outline">View Full Breakdown</a>
        </div>
      </div>
      @empty
      <div style="grid-column:1/-1;text-align:center;padding:4rem 0">
        <p style="color:var(--text-dim);font-family:'IBM Plex Mono',monospace;font-size:0.85rem">New plans launching soon — check back shortly.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Supported Networks -->
<div style="background:var(--ink-2);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:1.5rem 2rem;text-align:center">
  <div style="max-width:1200px;margin:0 auto">
    <div style="font-family:'IBM Plex Mono',monospace;font-size:0.6rem;letter-spacing:0.15em;text-transform:uppercase;color:var(--text-dim);margin-bottom:1rem">Supported Networks</div>
    <div style="display:flex;justify-content:center;gap:1rem;flex-wrap:wrap">
      <span style="font-family:'IBM Plex Mono',monospace;font-size:0.68rem;color:var(--gold);border:1px solid var(--border-mid);padding:0.4rem 1.1rem;letter-spacing:0.06em">USDT · TRC20</span>
      <span style="font-family:'IBM Plex Mono',monospace;font-size:0.68rem;color:var(--gold);border:1px solid var(--border-mid);padding:0.4rem 1.1rem;letter-spacing:0.06em">Binance Pay</span>
      <span style="font-family:'IBM Plex Mono',monospace;font-size:0.68rem;color:var(--gold);border:1px solid var(--border-mid);padding:0.4rem 1.1rem;letter-spacing:0.06em">TRON Network</span>
      <span style="font-family:'IBM Plex Mono',monospace;font-size:0.68rem;color:var(--gold);border:1px solid var(--border-mid);padding:0.4rem 1.1rem;letter-spacing:0.06em">Zero Deposit Fees</span>
    </div>
  </div>
</div>

<!-- Platform Features -->
<section class="features-section section">
  <div class="container">
    <div class="sec-eyebrow">Platform</div>
    <h2 class="sec-title">Built for <em>Serious Capital</em></h2>
    <div class="features-grid">
      <div class="feature-card"><i class="bi bi-lightning-charge-fill feature-icon"></i><div class="feature-title">Instant Withdrawals</div><p class="feature-body">Withdraw profits to your USDT wallet with no delays, no lock periods, no minimum waiting time.</p></div>
      <div class="feature-card"><i class="bi bi-graph-up-arrow feature-icon"></i><div class="feature-title">Stackable Plans</div><p class="feature-body">Activate multiple plans simultaneously to compound your total allocated capital and returns.</p></div>
      <div class="feature-card"><i class="bi bi-shield-check feature-icon"></i><div class="feature-title">Verified Security</div><p class="feature-body">Multi-layer transaction verification and manual audits protect every deposit and withdrawal.</p></div>
      <div class="feature-card"><i class="bi bi-phone feature-icon"></i><div class="feature-title">Mobile First</div><p class="feature-body">Fully optimized for smartphones — track your portfolio and withdraw earnings from anywhere.</p></div>
      <div class="feature-card"><i class="bi bi-people-fill feature-icon"></i><div class="feature-title">Referral Rewards</div><p class="feature-body">Earn bonus returns for every referred user who activates a plan — unlimited referral potential.</p></div>
      <div class="feature-card"><i class="bi bi-eye feature-icon"></i><div class="feature-title">Live Ledger</div><p class="feature-body">Every trade profit and withdrawal logged in your personal dashboard in real time.</p></div>
      <div class="feature-card"><i class="bi bi-arrow-repeat feature-icon"></i><div class="feature-title">24/7 Trading</div><p class="feature-body">Our desk operates around the clock — your capital is always positioned in active markets.</p></div>
      <div class="feature-card"><i class="bi bi-globe feature-icon"></i><div class="feature-title">Global Access</div><p class="feature-body">Available worldwide with multilingual support and crypto-native payment infrastructure.</p></div>
    </div>
  </div>
</section>

<!-- Revenue Sources -->
<section id="sources" class="revenue-section section">
  <div class="container">
    <div class="sec-eyebrow">Revenue Sources</div>
    <h2 class="sec-title">What Funds <em>Your Returns</em></h2>
    <p class="sec-body">Our trading desk generates revenue through diversified market exposure — keeping your daily returns backed by real market activity, not speculation.</p>
    <div class="revenue-grid">
      <div class="rev-card"><span class="rev-icon">📈</span><div class="rev-title">Spot Trading</div><p class="rev-body">Our analysts execute high-probability spot positions across major pairs, capturing intraday price movements with disciplined risk management.</p></div>
      <div class="rev-card"><span class="rev-icon">⚡</span><div class="rev-title">Arbitrage Desks</div><p class="rev-body">Cross-exchange price differentials are captured in milliseconds by our automated arb systems — consistent, low-risk returns.</p></div>
      <div class="rev-card"><span class="rev-icon">🔗</span><div class="rev-title">Affiliate Commissions</div><p class="rev-body">Exchange referral CPA fees and platform affiliate programs provide stable baseline revenue independent of market conditions.</p></div>
      <div class="rev-card"><span class="rev-icon">🛍️</span><div class="rev-title">Affiliate Sales</div><p class="rev-body">Product and merchant affiliate commissions generate direct per-task revenue shared proportionally with active plan holders.</p></div>
      <div class="rev-card"><span class="rev-icon">▶️</span><div class="rev-title">Ad Network Revenue</div><p class="rev-body">CPV and CPC ad inventory from our verified user base generates pooled revenue distributed daily to plan holders.</p></div>
      <div class="rev-card"><span class="rev-icon">📊</span><div class="rev-title">Survey Panels</div><p class="rev-body">Market research contracts pay $0.50–$5 per verified response. We aggregate and pass the majority back to active earners.</p></div>
      <div class="rev-card"><span class="rev-icon">⭐</span><div class="rev-title">Review Contracts</div><p class="rev-body">Fixed-rate per-review contracts with app developers and e-commerce brands, distributed to users completing review tasks.</p></div>
      <div class="rev-card"><span class="rev-icon">🤝</span><div class="rev-title">Brand Retainers</div><p class="rev-body">Monthly brand retainer contracts for guaranteed exposure volume provide stable baseline revenue regardless of daily ad fluctuations.</p></div>
    </div>
  </div>
</section>

<!-- Why -->
<section class="why-section section">
  <div class="container">
    <div class="sec-eyebrow">Competitive Edge</div>
    <h2 class="sec-title">Why Traders Choose <em>TopTrade</em></h2>
    <div class="why-grid">
      <div class="why-card"><div class="why-icon"><i class="bi bi-ban"></i></div><div class="why-title">Zero Hidden Fees</div><p class="why-body">No platform tax, no withdrawal penalties. What you earn is exactly what you keep.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-eye"></i></div><div class="why-title">Full Transparency</div><p class="why-body">Every trade, profit, and payout logged with timestamps in your personal ledger.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-arrows-angle-expand"></i></div><div class="why-title">Scale at Will</div><p class="why-body">Stack multiple plans to multiply your allocated capital and compound returns.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-robot"></i></div><div class="why-title">Automated Engine</div><p class="why-body">Profits credit automatically — the system works even while you sleep.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-currency-exchange"></i></div><div class="why-title">Fast Withdrawals</div><p class="why-body">Crypto withdrawals processed within hours, directly to your TRC20 wallet.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-phone-flip"></i></div><div class="why-title">Mobile Optimized</div><p class="why-body">Manage your portfolio from any device — seamless on every screen size.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-person-check"></i></div><div class="why-title">Verified Users Only</div><p class="why-body">Light KYC keeps the platform fraud-free and every trader's capital protected.</p></div>
      <div class="why-card"><div class="why-icon"><i class="bi bi-headset"></i></div><div class="why-title">Live Support</div><p class="why-body">Real agents available around the clock via live chat, Telegram, and tickets.</p></div>
    </div>
  </div>
</section>

<!-- Launch Banner -->
<div style="background:var(--ink-3);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:80px 0">
  <div style="max-width:1200px;margin:0 auto;padding:0 2rem;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center">
    <div>
      <div style="font-family:'IBM Plex Mono',monospace;font-size:0.62rem;color:var(--gold);letter-spacing:0.15em;text-transform:uppercase;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem"><span style="display:inline-block;width:18px;height:1px;background:var(--gold)"></span>Live Since 05 March 2026</div>
      <h2 style="font-family:'DM Serif Display',serif;font-size:clamp(1.8rem,3.5vw,2.8rem);color:#fff;line-height:1.15;margin-bottom:1rem">The Fastest-Growing<br>Crypto Trading <em style="color:var(--gold)">Community.</em></h2>
      <p style="font-size:0.95rem;color:var(--text-dim);line-height:1.8;margin-bottom:2rem">Thousands of traders worldwide have already activated their plans. Every day you wait is compounded income left on the table.</p>
      <div style="display:flex;gap:1rem;flex-wrap:wrap">
        @auth
          <a href="{{ route('dashboard') }}" class="btn-hero-primary">Open Dashboard →</a>
          <a href="{{ route('packages.index') }}" class="btn-hero-ghost">Browse Plans</a>
        @else
          <a href="{{ route('register') }}" class="btn-hero-primary">Get Started Free →</a>
          <a href="{{ route('login') }}" class="btn-hero-ghost">Sign In</a>
        @endauth
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border)">
      <div style="background:var(--ink-2);padding:2rem;text-align:center"><i class="bi bi-check2-circle" style="color:var(--gold);font-size:1.75rem;display:block;margin-bottom:0.75rem"></i><div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:#fff;letter-spacing:0.08em;text-transform:uppercase">Daily Returns</div><div style="font-size:0.75rem;color:var(--text-dim);margin-top:0.25rem">Expert-executed</div></div>
      <div style="background:var(--ink-2);padding:2rem;text-align:center"><i class="bi bi-box-seam" style="color:var(--gold);font-size:1.75rem;display:block;margin-bottom:0.75rem"></i><div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:#fff;letter-spacing:0.08em;text-transform:uppercase">Trading Plans</div><div style="font-size:0.75rem;color:var(--text-dim);margin-top:0.25rem">Stack multiple</div></div>
      <div style="background:var(--ink-2);padding:2rem;text-align:center"><i class="bi bi-wallet2" style="color:var(--gold);font-size:1.75rem;display:block;margin-bottom:0.75rem"></i><div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:#fff;letter-spacing:0.08em;text-transform:uppercase">Crypto Wallet</div><div style="font-size:0.75rem;color:var(--text-dim);margin-top:0.25rem">Withdraw freely</div></div>
      <div style="background:var(--ink-2);padding:2rem;text-align:center"><i class="bi bi-people" style="color:var(--gold);font-size:1.75rem;display:block;margin-bottom:0.75rem"></i><div style="font-family:'IBM Plex Mono',monospace;font-size:0.65rem;color:#fff;letter-spacing:0.08em;text-transform:uppercase">Expert Desk</div><div style="font-size:0.75rem;color:var(--text-dim);margin-top:0.25rem">24/7 active</div></div>
    </div>
  </div>
</div>

<!-- Testimonials -->
<section class="testi-section section">
  <div class="container">
    <div class="sec-eyebrow">Trader Reports</div>
    <h2 class="sec-title">Real Results, <em>Real Traders</em></h2>
    <div class="testi-grid">
      <div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"I was skeptical at first — but after my first withdrawal hit my Binance wallet in under two hours, I was sold. Now running three plans simultaneously and earning every day."</p><div class="testi-name">A. Rahman</div><div class="testi-role">Plan III Active Trader · Malaysia</div></div>
      <div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"The dashboard is clean, the returns are consistent, and the payouts are always on time. Our expert team handles everything — I just watch the balance grow."</p><div class="testi-name">F. Okonkwo</div><div class="testi-role">Plan II Active Trader · Nigeria</div></div>
      <div class="testi-card"><div class="testi-stars">★★★★★</div><p class="testi-quote">"I referred 14 people in my first month. The referral bonuses alone covered my initial investment. Completely legitimate passive income — I wish I'd joined sooner."</p><div class="testi-name">P. Nguyen</div><div class="testi-role">Plan I Active Trader · Vietnam</div></div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq" class="faq-section section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:5rem;align-items:start">
      <div>
        <div class="sec-eyebrow">Support</div>
        <h2 class="sec-title">Common<br><em>Questions</em></h2>
        <p class="sec-body" style="margin-top:1rem">Have more questions? Our team responds to every message within 2 hours.</p>
        <a href="#contact" style="display:inline-flex;align-items:center;gap:0.5rem;font-family:'IBM Plex Mono',monospace;font-size:0.72rem;color:var(--gold);text-decoration:none;margin-top:1.5rem;letter-spacing:0.05em">Contact Support <i class="bi bi-arrow-right"></i></a>
      </div>
      <div class="faq-list">
        <div class="faq-item">
          <button class="faq-question" onclick="toggleFaq(this)">How are my daily returns generated? <i class="bi bi-plus faq-icon"></i></button>
          <div class="faq-answer">Your returns are funded by our trading desk's daily activity — a blend of spot trading, arbitrage, affiliate commissions, and ad network revenue. Our analysts execute positions on your behalf and your proportional profit share credits automatically each day.</div>
        </div>
        <div class="faq-item">
          <button class="faq-question" onclick="toggleFaq(this)">Can I activate multiple plans simultaneously? <i class="bi bi-plus faq-icon"></i></button>
          <div class="faq-answer">Yes — you can run multiple different plans simultaneously. Each plan adds its own allocated capital and daily ceiling. You cannot hold two identical plans at once, but combining different plans is fully supported and is a popular strategy among top traders on the platform.</div>
        </div>
        <div class="faq-item">
          <button class="faq-question" onclick="toggleFaq(this)">How fast are withdrawals processed? <i class="bi bi-plus faq-icon"></i></button>
          <div class="faq-answer">Withdrawal requests are reviewed by our finance team and typically complete within 1–6 hours. Funds transfer directly to your USDT TRC20 wallet. There is a minimum withdrawal threshold shown in your dashboard, and TopTrade charges zero withdrawal fees.</div>
        </div>
        <div class="faq-item">
          <button class="faq-question" onclick="toggleFaq(this)">Is there a referral or affiliate program? <i class="bi bi-plus faq-icon"></i></button>
          <div class="faq-answer">Every registered account receives a unique referral link from day one. When someone signs up through your link and activates any plan, you earn a referral commission credited instantly to your balance. There is no cap on referrals.</div>
        </div>
        <div class="faq-item">
          <button class="faq-question" onclick="toggleFaq(this)">What happens when my plan's duration ends? <i class="bi bi-plus faq-icon"></i></button>
          <div class="faq-answer">When a plan's validity period expires, daily profit distributions for that plan stop. Your accumulated balance remains safely in your wallet and can be withdrawn anytime. You can then re-activate the same plan or upgrade to a higher one to continue earning.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact -->
<section id="contact" class="contact-section section">
  <div class="container">
    <div class="contact-layout">
      <div>
        <div class="sec-eyebrow">Get in Touch</div>
        <h2 class="sec-title">Talk to<br><em>Our Team</em></h2>
        <p class="sec-body" style="margin-bottom:2rem">Have a pre-investment question or need account support? We respond to every message within 2 hours.</p>
        <div class="contact-info-item">
          <i class="bi bi-telegram ci-icon"></i>
          <div><div class="ci-label">Telegram</div><div class="ci-val">@TopTradeSupport</div></div>
        </div>
        <div class="contact-info-item">
          <i class="bi bi-envelope-fill ci-icon"></i>
          <div><div class="ci-label">Email</div><div class="ci-val"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="b5c6c0c5c5dac7c1f5f0d4c7dbe2d4c3d09bdcda">[email&#160;protected]</a></div></div>
        </div>
        <div style="display:flex;gap:0.75rem;margin-top:2rem">
          <a href="#" style="width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);color:var(--text-dim);text-decoration:none;transition:all 0.2s;font-size:0.9rem" onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-dim)'"><i class="bi bi-telegram"></i></a>
          <a href="#" style="width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);color:var(--text-dim);text-decoration:none;transition:all 0.2s;font-size:0.9rem" onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-dim)'"><i class="bi bi-twitter-x"></i></a>
          <a href="#" style="width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);color:var(--text-dim);text-decoration:none;transition:all 0.2s;font-size:0.9rem" onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-dim)'"><i class="bi bi-instagram"></i></a>
          <a href="#" style="width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--border);color:var(--text-dim);text-decoration:none;transition:all 0.2s;font-size:0.9rem" onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-dim)'"><i class="bi bi-discord"></i></a>
        </div>
      </div>
      <div class="contact-form-wrap">
        <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
          @csrf
          <label class="form-label">Your Name</label>
          <input type="text" name="name" class="form-input" placeholder="e.g. John Doe" required>
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-input" placeholder="you@example.com" required>
          <label class="form-label">Topic</label>
          <input type="text" name="subject" class="form-input" placeholder="e.g. Withdrawal issue, Plan question">
          <label class="form-label">Message</label>
          <textarea name="message" class="form-textarea" placeholder="Describe your question in detail..." required></textarea>
          <button type="submit" class="btn-hero-primary" style="width:100%;text-align:center;border:none;cursor:pointer">
            <span class="btn-text">Send Message →</span>
            <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-2" style="border-color:var(--ink);border-right-color:transparent"></span>Sending...</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container" style="position:relative;z-index:2">
    <div class="sec-eyebrow" style="justify-content:center">Your Move</div>
    <h2 class="cta-title">Every Day Without a Plan<br>Is <em>Compounded Income</em> Missed.</h2>
    <p class="cta-body">Join 14,800+ traders who receive daily crypto returns managed by our expert desk. Activate your first plan in less than 3 minutes.</p>
    <div class="cta-buttons">
      <a href="{{ route('register') }}" class="btn-hero-primary">Open Free Account →</a>
      <a href="#plans" class="btn-hero-ghost">Explore Plans</a>
    </div>
  </div>
</section>

<div class="disclaimer">
  <div class="container"><p>⚠ Disclaimer: TopTrade does not guarantee fixed returns. Daily earnings vary based on trading conditions, plan level, and platform performance. Crypto investments carry risk — only invest what you can afford to lose.</p></div>
</div>

<footer class="plan-footer">
  <div class="tt-container">
    <div class="footer-brand">Top<em>Trade</em></div>
    <div class="footer-copy">© {{ date('Y') }} TopTrade — All Rights Reserved.</div>
    <div class="footer-links">
      <a href="#how">How It Works</a>
      <a href="#plans">Plans</a>
      <a href="#sources">Revenue</a>
      <a href="#faq">FAQ</a>
      <a href="#contact">Contact</a>
      <a href="#">Terms</a>
      <a href="#">Privacy</a>
    </div>
  </div>
</footer>

<div class="mobile-cta d-lg-none">
  <a href="{{ route('register') }}" class="btn-hero-primary" style="display:block;text-align:center">Open Free Account →</a>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body text-center py-4">
        <div id="modalIcon" class="mb-3"></div>
        <h5 id="modalTitle" class="mb-2" style="font-family:'IBM Plex Mono',monospace;font-size:0.85rem;letter-spacing:0.05em"></h5>
        <p id="modalMessage" class="mb-0" style="color:var(--text-dim)"></p>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn-ghost" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script>
// FAQ toggle
function toggleFaq(btn) {
  const answer = btn.nextElementSibling;
  const icon = btn.querySelector('.faq-icon');
  const isOpen = answer.classList.contains('open');
  document.querySelectorAll('.faq-answer.open').forEach(a => { a.classList.remove('open'); a.previousElementSibling.classList.remove('open'); a.previousElementSibling.querySelector('.faq-icon').classList.replace('bi-dash','bi-plus'); });
  if (!isOpen) { answer.classList.add('open'); btn.classList.add('open'); icon.classList.replace('bi-plus','bi-dash'); }
}

// Contact form
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this, btn = form.querySelector('button[type="submit"]'),
    t = btn.querySelector('.btn-text'), l = btn.querySelector('.btn-loading');
  btn.disabled = true; t.classList.add('d-none'); l.classList.remove('d-none');
  fetch(form.action, { method:'POST', body: new FormData(form), headers:{'X-Requested-With':'XMLHttpRequest'} })
    .then(r => r.json()).then(data => {
      btn.disabled = false; t.classList.remove('d-none'); l.classList.add('d-none');
      showModal(data.success ? 'success' : 'error', data.success ? 'Message Sent' : 'Error', data.message || 'Something went wrong.');
      if (data.success) form.reset();
    }).catch(() => {
      btn.disabled = false; t.classList.remove('d-none'); l.classList.add('d-none');
      showModal('error', 'Connection Failed', 'Unable to reach the server. Please try again.');
    });
});
function showModal(type, title, message) {
  const modal = new bootstrap.Modal(document.getElementById('contactModal'));
  document.getElementById('modalIcon').innerHTML = type === 'success'
    ? '<i class="bi bi-check-circle-fill" style="font-size:2.5rem;color:var(--gold)"></i>'
    : '<i class="bi bi-exclamation-circle-fill" style="font-size:2.5rem;color:var(--red)"></i>';
  const t = document.g
