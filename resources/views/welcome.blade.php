<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopTrade — Trade. Earn. Grow.</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toptrade.css') }}">
</head>

<body>
    <div class="grid-bg"></div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="{{ route('welcome') }}" class="navbar-brand">
            <div class="logo-mark">T</div>
            <span class="nav-text">TopTrade</span>
        </a>
        <div class="nav-links">
            <a href="#how" class="nav-link">How It Works</a>
            <a href="#packages" class="nav-link">Packages</a>
            <a href="#earn" class="nav-link">Earn Ads</a>
            <a href="#trading" class="nav-link">Trading</a>
            <a href="#faq" class="nav-link">FAQ</a>
        </div>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
            @endauth
        </div>
    </nav>

    <!-- LIVE TICKER -->
    <div class="ticker">
        <div
            style="position:absolute;left:0;top:0;bottom:0;width:80px;background:linear-gradient(90deg,var(--dark) 60%,transparent);z-index:2;display:flex;align-items:center;padding-left:12px;gap:6px;">
            <div class="live-dot"></div>
            <span style="font-size:0.6rem;font-weight:700;color:var(--muted);">LIVE</span>
        </div>
        <div
            style="position:absolute;right:0;top:0;bottom:0;width:48px;background:linear-gradient(270deg,var(--dark) 60%,transparent);z-index:2;">
        </div>
        <div class="ticker-track" id="tickerTrack">
            @php
                $coins = [
                    ['sym' => 'BTC/USD', 'price' => '67,432', 'chg' => '+2.4%', 'up' => true],
                    ['sym' => 'ETH/USD', 'price' => '3,821', 'chg' => '+1.8%', 'up' => true],
                    ['sym' => 'BNB/USD', 'price' => '412', 'chg' => '-0.5%', 'up' => false],
                    ['sym' => 'SOL/USD', 'price' => '178', 'chg' => '+3.2%', 'up' => true],
                    ['sym' => 'XRP/USD', 'price' => '0.72', 'chg' => '-1.1%', 'up' => false],
                    ['sym' => 'GOLD/USD', 'price' => '2,312', 'chg' => '+0.4%', 'up' => true],
                    ['sym' => 'EUR/USD', 'price' => '1.0842', 'chg' => '+0.1%', 'up' => true],
                    ['sym' => 'OIL/USD', 'price' => '82.4', 'chg' => '-0.8%', 'up' => false],
                ];
            @endphp
            @foreach (array_merge($coins, $coins) as $c)
                <div class="ticker-item">
                    <span class="ticker-sym">{{ $c['sym'] }}</span>
                    <span class="ticker-price">${{ $c['price'] }}</span>
                    <span class="{{ $c['up'] ? 'up' : 'down' }}">{{ $c['chg'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">

        <!-- HERO -->
        <section class="hero">
            <div style="position:absolute;top:10%;left:10%;width:500px;height:500px;background:radial-gradient(circle,rgba(0,245,212,0.08) 0%,transparent 70%);"
                class="orb"></div>
            <div style="position:absolute;bottom:10%;right:10%;width:400px;height:400px;background:radial-gradient(circle,rgba(99,102,241,0.08) 0%,transparent 70%);"
                class="orb"></div>

            <div class="hero-inner fade-up">
                <div class="hero-tag">
                    <div class="live-dot"></div>
                    Multi-Market Investment Platform — Live 24/7
                </div>
                <h1>
                    TRADE ALL<br>
                    MARKETS.<br>
                    <span class="accent">EARN DAILY.</span>
                </h1>
                <p class="hero-sub">
                    Invest in professionally-managed crypto, forex, and commodities portfolios. Watch daily ads to boost
                    your earnings. Withdraw anytime.
                </p>
                <div class="hero-cta">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-lg btn-cyan">
                            <i class="bi bi-lightning-charge-fill"></i> Open Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-lg btn-cyan">
                            <i class="bi bi-lightning-charge-fill"></i> Start Earning Free
                        </a>
                        <a href="{{ route('login') }}" class="btn-lg btn-outline">Sign In</a>
                    @endauth
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-val">$4.2M+</div>
                        <div class="hero-stat-lbl">Total Invested</div>
                    </div>
                    <div>
                        <div class="hero-stat-val">14,800+</div>
                        <div class="hero-stat-lbl">Active Investors</div>
                    </div>
                    <div>
                        <div class="hero-stat-val">$1.8M+</div>
                        <div class="hero-stat-lbl">Profits Paid</div>
                    </div>
                    <div>
                        <div class="hero-stat-val">98.6%</div>
                        <div class="hero-stat-lbl">Success Rate</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MARKET STRIP -->
        <div class="market-strip">
            <div class="container">
                <div class="market-strip-inner">
                    <div class="market-cell">
                        <div class="market-cell-lbl">Total Deposited</div>
                        <div class="market-cell-val" style="color:var(--cyan);">$4.2M</div>
                        <div class="market-cell-sub">↑ 12% this month</div>
                    </div>
                    <div class="market-cell">
                        <div class="market-cell-lbl">Profits Distributed</div>
                        <div class="market-cell-val" style="color:var(--green);">$1.8M</div>
                        <div class="market-cell-sub">↑ 8% this week</div>
                    </div>
                    <div class="market-cell">
                        <div class="market-cell-lbl">Active Investors</div>
                        <div class="market-cell-val" style="color:var(--text);">14,800</div>
                        <div class="market-cell-sub">+240 this week</div>
                    </div>
                    <div class="market-cell">
                        <div class="market-cell-lbl">Markets Covered</div>
                        <div class="market-cell-val" style="color:var(--gold);">40+</div>
                        <div class="market-cell-sub">Crypto · Forex · Commodities</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOW IT WORKS -->
        <section class="sec-pad" id="how">
            <div class="container">
                <div style="margin-bottom:52px;">
                    <div class="sec-tag">How It Works</div>
                    <h2 class="sec-title">Three steps to daily income</h2>
                    <p class="sec-sub">Your money works 24/7 in global markets while you earn extra through daily ads.
                        Simple, transparent, powerful.</p>
                    <div class="sec-line"></div>
                </div>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-num">01</div>
                        <div class="step-icon"><i class="bi bi-wallet2"></i></div>
                        <div class="step-title">Fund Your Account</div>
                        <p class="step-desc">Deposit USDT via TRC20. Funds hit your dashboard within minutes. Zero
                            deposit fees. No minimums beyond your chosen package.</p>
                        <span class="step-tag">⚡ USDT TRC20 · Binance · Instant</span>
                    </div>
                    <div class="step-card">
                        <div class="step-num">02</div>
                        <div class="step-icon"><i class="bi bi-box-seam"></i></div>
                        <div class="step-title">Activate a Package</div>
                        <p class="step-desc">Choose your investment tier. Your capital is pooled with our traders who
                            execute across crypto, forex, and commodities — daily profits credited automatically.</p>
                        <span class="step-tag">📈 Crypto · Forex · Commodities</span>
                    </div>
                    <div class="step-card">
                        <div class="step-num">03</div>
                        <div class="step-icon"><i class="bi bi-play-circle"></i></div>
                        <div class="step-title">Watch Ads, Earn Bonus</div>
                        <p class="step-desc">Log in daily and watch a set of short ads from our Adastra ad network.
                            Each viewed ad adds bonus USDT directly to your balance on top of trading profits.</p>
                        <span class="step-tag">💰 Ads + Trading = Max Earnings</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- PACKAGES -->
        <section class="sec-pad" id="packages" style="background:var(--dark);">
            <div class="container">
                <div style="margin-bottom:52px;">
                    <div class="sec-tag">Investment Packages</div>
                    <h2 class="sec-title">Choose your tier</h2>
                    <p class="sec-sub">Every package earns through live trading. Bigger packages = more daily tasks =
                        more ad bonuses on top.</p>
                    <div class="sec-line"></div>
                </div>
                <div class="packages-grid">
                    @forelse($packages as $package)
                        <div class="pkg-card {{ $loop->iteration === 2 ? 'featured' : '' }}">
                            <div class="pkg-tier">{{ $package->name }}</div>
                            <div class="pkg-price">${{ number_format($package->price, 2) }}</div>
                            <div class="pkg-price-lbl">One-time investment · No renewal</div>
                            <div class="pkg-earn-box">
                                <span class="pkg-earn-lbl">Daily Trading Profit</span>
                                <span class="pkg-earn-val">${{ number_format($package->daily_earning, 2) }}/day</span>
                            </div>
                            <ul class="pkg-features">
                                <li><i class="bi bi-check2-circle"></i> {{ $package->daily_tasks }} daily ad tasks
                                </li>
                                <li><i class="bi bi-check2-circle"></i>
                                    {{ $package->duration_days == 0 ? 'Unlimited' : $package->duration_days . ' day' }}
                                    duration</li>
                                <li><i class="bi bi-check2-circle"></i> Max payout:
                                    ${{ number_format($package->total_earning_potential, 2) }}</li>
                                <li><i class="bi bi-check2-circle"></i> ROI:
                                    {{ number_format($package->roi_percentage, 1) }}%</li>
                                <li><i class="bi bi-check2-circle"></i> Withdraw anytime</li>
                                @if ($package->features)
                                    @foreach (array_slice(explode("\n", $package->features), 0, 2) as $f)
                                        @if (trim($f))
                                            <li><i class="bi bi-check2-circle"></i> {{ trim($f) }}</li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                            @auth
                                <form action="{{ route('packages.purchase', $package->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="pkg-btn {{ $loop->iteration === 2 ? 'pkg-btn-solid' : 'pkg-btn-outline' }}">
                                        ⚡ Activate Package
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('register') }}"
                                    class="pkg-btn {{ $loop->iteration === 2 ? 'pkg-btn-solid' : 'pkg-btn-outline' }}">
                                    Get Started
                                </a>
                            @endauth
                        </div>
                    @empty
                        <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--muted);">
                            <i class="bi bi-inbox" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                            Packages launching soon.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- ADS EARN SECTION -->
        <section class="sec-pad" id="earn">
            <div class="container">
                <div class="ads-grid">
                    <!-- Phone mockup -->
                    <div>
                        <div class="ads-phone">
                            <div class="ads-phone-bar">
                                <span>9:41</span>
                                <span>Daily Tasks</span>
                                <span><i class="bi bi-battery-full"></i></span>
                            </div>
                            <div style="font-size:0.72rem;color:var(--muted);margin-bottom:12px;">Watch & Earn — 5
                                tasks remaining</div>

                            <div class="ad-item">
                                <div class="ad-thumb">📱</div>
                                <div style="flex:1">
                                    <div class="ad-title">Binance App — Watch 15s</div>
                                    <div class="ad-sub">Crypto Trading Platform</div>
                                </div>
                                <div class="ad-reward">+$0.42</div>
                            </div>
                            <div class="ad-item">
                                <div class="ad-thumb" style="background:linear-gradient(135deg,#f59e0b,#ef4444);">🛒
                                </div>
                                <div style="flex:1">
                                    <div class="ad-title">Amazon Deal Alert</div>
                                    <div class="ad-sub">E-commerce</div>
                                </div>
                                <div class="ad-reward">+$0.38</div>
                            </div>
                            <div class="ad-item">
                                <div class="ad-thumb" style="background:linear-gradient(135deg,#22c55e,#14b8a6);">▶️
                                </div>
                                <div style="flex:1">
                                    <div class="ad-title">YouTube Premium</div>
                                    <div class="ad-sub">Video Platform</div>
                                </div>
                                <div class="ad-reward">+$0.55</div>
                            </div>

                            <div
                                style="background:rgba(0,245,212,0.08);border:1px solid rgba(0,245,212,0.15);border-radius:10px;padding:12px;margin-top:4px;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:0.75rem;color:var(--muted);">Today's Bonus Earned</span>
                                <span
                                    style="font-weight:800;color:var(--cyan);font-family:'Syne',sans-serif;">$2.74</span>
                            </div>
                        </div>
                    </div>
                    <!-- Text -->
                    <div>
                        <div class="sec-tag">Adastra Ad Network</div>
                        <h2 class="sec-title">Watch ads.<br>Earn real money.</h2>
                        <p class="sec-sub" style="margin-bottom:32px;">Every day, a fresh queue of short ads from our
                            Adastra network awaits you. Each view pays USDT directly to your balance — on top of your
                            trading profits.</p>
                        <div class="earn-steps">
                            <div class="earn-step">
                                <div class="earn-step-num">1</div>
                                <div>
                                    <div class="earn-step-title">Log in daily</div>
                                    <div class="earn-step-desc">Your task queue refreshes every 24 hours. Number of
                                        tasks depends on your active package tier.</div>
                                </div>
                            </div>
                            <div class="earn-step">
                                <div class="earn-step-num">2</div>
                                <div>
                                    <div class="earn-step-title">Watch short ads (10–30s)</div>
                                    <div class="earn-step-desc">Ads sourced from Adastra network — global brands across
                                        e-commerce, tech, and finance.</div>
                                </div>
                            </div>
                            <div class="earn-step">
                                <div class="earn-step-num">3</div>
                                <div>
                                    <div class="earn-step-title">Reward credited instantly</div>
                                    <div class="earn-step-desc">USDT hits your balance the moment the ad completes. No
                                        waiting, no verification delays.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TRADING SECTION -->
        <section class="sec-pad" id="trading" style="background:var(--dark);">
            <div class="container">
                <div class="trading-grid">
                    <div>
                        <div class="sec-tag">Live Trading</div>
                        <h2 class="sec-title">Your money trades<br>every market.</h2>
                        <p class="sec-sub" style="margin-bottom:32px;">Our professional traders operate across crypto,
                            forex, commodities, and indices — 24 hours a day, 5 days a week. Your investment earns from
                            all of it.</p>
                        <div style="display:flex;gap:16px;flex-wrap:wrap;">
                            @foreach (['BTC/ETH/SOL', 'EUR/USD', 'Gold/Silver', 'Oil/Gas', 'S&P 500 Index'] as $m)
                                <span
                                    style="background:rgba(255,255,255,0.05);border:1px solid var(--border2);border-radius:8px;padding:6px 14px;font-size:0.78rem;color:var(--muted);">{{ $m }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="trading-cards">
                        @foreach ([['pair' => 'BTC/USD', 'type' => 'Crypto Long', 'pct' => '+3.4%', 'fill' => 62, 'up' => true], ['pair' => 'EUR/USD', 'type' => 'Forex Trade', 'pct' => '+0.8%', 'fill' => 45, 'up' => true], ['pair' => 'GOLD/USD', 'type' => 'Commodity', 'pct' => '+1.2%', 'fill' => 55, 'up' => true], ['pair' => 'SOL/USD', 'type' => 'Crypto Long', 'pct' => '-0.5%', 'fill' => 28, 'up' => false]] as $t)
                            <div class="trade-card">
                                <div>
                                    <div class="trade-pair">{{ $t['pair'] }}</div>
                                    <div class="trade-type">{{ $t['type'] }}</div>
                                </div>
                                <div class="trade-right">
                                    <div class="trade-pct"
                                        style="color:{{ $t['up'] ? 'var(--green)' : 'var(--red)' }};">
                                        {{ $t['pct'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="sec-pad">
            <div class="container">
                <div style="margin-bottom:52px;">
                    <div class="sec-tag">Platform Features</div>
                    <h2 class="sec-title">Built for serious earners</h2>
                    <div class="sec-line"></div>
                </div>
                <div class="features-grid">
                    @foreach ([
        ['icon' => 'bi-shield-lock-fill', 'title' => 'Secure Deposits', 'desc' => 'Every deposit manually verified. SSL-encrypted. Your funds protected at every step.'],
        ['icon' => 'bi-lightning-charge-fill', 'title' => 'Instant Withdrawals', 'desc' => 'Request anytime. Our team processes USDT withdrawals within 1–6 hours.'],
        ['icon' => 'bi-graph-up-arrow', 'title' => 'Multi-Market Trading', 'desc' => '40+ markets including crypto, forex, gold, oil, and global stock indices.'],
        ['icon' => 'bi-phone', 'title' => 'Mobile Optimized', 'desc' => 'Full dashboard, ad tasks, and withdrawals work perfectly on any smartphone.'],
        ['icon' => 'bi-people-fill', 'title' => 'Referral Rewards', 'desc' => 'Earn commission on every user you refer who activates a package.'],
        ['icon' => 'bi-play-circle', 'title' => 'Adastra Ad Network', 'desc' => 'Earn real USDT by watching short ads every day — separate from trading profits.'],
        ['icon' => 'bi-arrow-repeat', 'title' => 'Daily Task Reset', 'desc' => 'Ad tasks refresh every 24 hours — a new earning window opens every day.'],
        ['icon' => 'bi-headset', 'title' => 'Live Support', 'desc' => 'Real agents via Telegram and live chat — response within 2 hours.'],
    ] as $f)
                        <div class="feat-card">
                            <div class="feat-icon"><i class="bi {{ $f['icon'] }}"></i></div>
                            <div class="feat-title">{{ $f['title'] }}</div>
                            <p class="feat-desc">{{ $f['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="sec-pad" style="background:var(--dark);">
            <div class="container">
                <div style="margin-bottom:52px;text-align:center;">
                    <div class="sec-tag">Investor Reports</div>
                    <h2 class="sec-title">Real results from real people</h2>
                    <div class="sec-line" style="margin:16px auto 0;"></div>
                </div>
                <div class="testi-grid">
                    @foreach ([['init' => 'AR', 'name' => 'A. Rahman', 'role' => 'Pro Package · Malaysia', 'quote' => 'The combination of trading profits AND daily ad earnings is what makes TopTrade different. I pull in more per day than any single platform I tried before.'], ['init' => 'FO', 'name' => 'F. Okonkwo', 'role' => 'Starter Package · Nigeria', 'quote' => 'Simple to understand: deposit, activate, watch a few ads each morning, collect daily profits. My withdrawal hit in under 3 hours. No games.'], ['init' => 'PN', 'name' => 'P. Nguyen', 'role' => 'Elite Package · Vietnam', 'quote' => 'I referred 14 people in month one. Referral bonuses plus my own trading returns covered the full investment in 6 weeks. Completely legit.']] as $t)
                        <div class="testi-card">
                            <div class="testi-stars">★★★★★</div>
                            <p class="testi-quote">"{{ $t['quote'] }}"</p>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div class="testi-avatar">{{ $t['init'] }}</div>
                                <div>
                                    <div class="testi-name">{{ $t['name'] }}</div>
                                    <div class="testi-role">{{ $t['role'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="sec-pad" id="faq">
            <div class="container">
                <div style="margin-bottom:52px;">
                    <div class="sec-tag">FAQ</div>
                    <h2 class="sec-title">Common questions</h2>
                    <div class="sec-line"></div>
                </div>
                <div style="max-width:720px;">
                    @foreach ([
        ['q' => 'How are trading profits generated?', 'a' => 'Your investment is pooled with our professional trading fund which operates across crypto (BTC, ETH, SOL), forex (EUR/USD, GBP/USD), commodities (Gold, Oil), and indices. Our traders execute live positions 24/7 and your proportional share of profits credits daily.'],
        ['q' => 'What is the Adastra ad network?', 'a' => 'Adastra is our integrated ad platform that connects global brands with our user base. When you watch a short ad (10–30 seconds), the advertiser pays a fee and we pass the majority of that revenue directly to your balance as USDT. It is a genuine additional income stream completely separate from trading profits.'],
        ['q' => 'How fast are withdrawals processed?', 'a' => 'Withdrawal requests are reviewed by our finance team and typically processed within 1–6 hours. Funds go directly to your USDT TRC20 wallet. Zero withdrawal fees.'],
        ['q' => 'Can I withdraw anytime?', 'a' => 'Yes. There is no lock period on your investment. You can request a withdrawal at any time and receive your available balance within hours. Only pending trading positions are temporarily locked.'],
        ['q' => 'Is there a referral program?', 'a' => 'Every account gets a unique referral link from day one. When someone you refer activates any package, you earn a referral commission instantly credited to your balance. There is no cap on referrals.'],
    ] as $i => $faq)
                        <div class="faq-item" id="faq{{ $i }}">
                            <div class="faq-q" onclick="toggleFaq({{ $i }})">
                                <span>{{ $faq['q'] }}</span>
                                <i class="bi bi-plus-lg"></i>
                            </div>
                            <div class="faq-a">{{ $faq['a'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
            <div class="cta-inner">
                <div class="cta-badge">
                    <div class="live-dot" style="background:var(--green);"></div>
                    Platform is Live — Join 14,800+ Investors
                </div>
                <h2>Ready to start<br><span style="color:var(--cyan);">earning today?</span></h2>
                <p>Create your free account in under 60 seconds. Activate a package, watch daily ads, and receive your
                    first profits tomorrow.</p>
                <div class="cta-btns">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-lg btn-cyan"><i
                                class="bi bi-lightning-charge-fill"></i> Open Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-lg btn-cyan"><i
                                class="bi bi-lightning-charge-fill"></i> Create Free Account</a>
                        <a href="{{ route('login') }}" class="btn-lg btn-outline">Sign In</a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="container">
                <div class="footer-grid">
                    <div>
                        <div class="navbar-brand" style="margin-bottom:0;">
                            <div class="logo-mark">T</div>
                            <span>TopTrade</span>
                        </div>
                        <p class="footer-brand-desc">A professionally-managed multi-market investment platform. Your
                            capital works in crypto, forex, and commodities — 24/7.</p>
                    </div>
                    <div>
                        <div class="footer-col-title">Platform</div>
                        <a href="#how" class="footer-link">How It Works</a>
                        <a href="#packages" class="footer-link">Packages</a>
                        <a href="#earn" class="footer-link">Earn with Ads</a>
                        <a href="#trading" class="footer-link">Trading Markets</a>
                        <a href="#faq" class="footer-link">FAQ</a>
                    </div>
                    <div>
                        <div class="footer-col-title">Account</div>
                        <a href="{{ route('login') }}" class="footer-link">Sign In</a>
                        <a href="{{ route('register') }}" class="footer-link">Create Account</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                        @endauth
                    </div>
                </div>
                <div class="footer-bottom">
                    <span>© {{ date('Y') }} TopTrade. All Rights Reserved.</span>
                    <span>Multi-Market Investment · Adastra Ads · Daily Earnings</span>
                </div>
            </div>
        </footer>

        <!-- DISCLAIMER -->
        <div class="disclaimer">
            ⚠ Investment Disclaimer: TopTrade does not guarantee fixed returns. Trading involves risk — only invest what
            you can afford to lose. Past performance does not guarantee future results.
        </div>

    </div><!-- /page-content -->

    <!-- MOBILE BOTTOM NAV -->
    <nav class="mobile-nav">
        <div class="mobile-nav-inner">
            <a href="{{ route('welcome') }}" class="mob-nav-btn active">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="#packages" class="mob-nav-btn">
                <i class="bi bi-box-seam"></i>
                <span>Packages</span>
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="mob-nav-btn cta-mob">
                    <div class="mob-icon-wrap"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('register') }}" class="mob-nav-btn cta-mob">
                    <div class="mob-icon-wrap"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Join Now</span>
                </a>
            @endauth
            <a href="#earn" class="mob-nav-btn">
                <i class="bi bi-play-circle"></i>
                <span>Earn Ads</span>
            </a>
            <a href="{{ route('login') }}" class="mob-nav-btn">
                <i class="bi bi-person"></i>
                <span>Account</span>
            </a>
        </div>
    </nav>


    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script>
        function toggleFaq(i) {
            const item = document.getElementById('faq' + i);
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        }

        // Live ticker from CoinGecko
        async function fetchTicker() {
            try {
                const res = await fetch(
                    'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,binancecoin,solana,ripple&vs_currencies=usd&include_24hr_change=true'
                    );
                const d = await res.json();
                const map = {
                    bitcoin: 'BTC/USD',
                    ethereum: 'ETH/USD',
                    binancecoin: 'BNB/USD',
                    solana: 'SOL/USD',
                    ripple: 'XRP/USD'
                };
                const items = Object.entries(d).map(([id, v]) => {
                    const sym = map[id];
                    const up = v.usd_24h_change >= 0;
                    const price = v.usd >= 1000 ? v.usd.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) : v.usd.toFixed(4);
                    const chg = (up ? '+' : '') + v.usd_24h_change.toFixed(2) + '%';
                    return `<div class="ticker-item"><span class="ticker-sym">${sym}</span><span class="ticker-price">$${price}</span><span class="${up?'up':'down'}">${chg}</span></div>`;
                });
                const html = [...items, ...items].join('');
                document.getElementById('tickerTrack').innerHTML = html;
            } catch (e) {}
        }
        fetchTicker();
        setInterval(fetchTicker, 30000);

        // Smooth scroll for hash links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const id = a.getAttribute('href').slice(1);
                const el = document.getElementById(id);
                if (el) {
                    e.preventDefault();
                    el.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>
