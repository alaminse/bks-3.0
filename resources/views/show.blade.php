<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $package->name }} — TopTrade</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toptrade.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="{{ route('welcome') }}" class="nav-brand">
            <div class="nav-logo">T</div>
            <span class="nav-text">TopTrade</span>
        </a>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
            @endauth
        </div>
    </nav>

    <div class="page">

        <!-- HERO -->
        <div class="pkg-hero">
            <div style="max-width:1200px;margin:0 auto;">
                <div class="breadcrumb">
                    <a href="{{ route('welcome') }}">Home</a>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                    <a href="{{ route('welcome') }}#packages">Packages</a>
                    <i class="bi bi-chevron-right" style="font-size:10px;"></i>
                    <span>{{ $package->name }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <h1 class="pkg-hero-title">
                        <span>{{ $package->name }}</span> Package
                    </h1>
                    @if ($package->is_active)
                        <span class="status-badge status-active">
                            <span class="status-dot"></span> Active
                        </span>
                    @endif
                </div>
                <p style="color:var(--muted);font-size:0.9rem;margin-top:8px;">{{ $package->description }}</p>
            </div>
        </div>

        <!-- STATS BAR -->
        <div class="stats-bar">
            <div class="stats-bar-cell">
                <i class="bi bi-currency-dollar stats-bar-icon" style="color:var(--cyan);"></i>
                <span class="stats-bar-val" style="color:var(--cyan);">${{ number_format($package->price, 2) }}</span>
                <span class="stats-bar-lbl">Package Price</span>
            </div>
            <div class="stats-bar-cell">
                <i class="bi bi-list-check stats-bar-icon" style="color:var(--green);"></i>
                <span class="stats-bar-val" style="color:var(--green);">{{ $package->daily_tasks }}</span>
                <span class="stats-bar-lbl">Daily Tasks</span>
            </div>
            <div class="stats-bar-cell">
                <i class="bi bi-cash-coin stats-bar-icon" style="color:var(--gold);"></i>
                <span class="stats-bar-val"
                    style="color:var(--gold);">${{ number_format($package->daily_earning, 2) }}</span>
                <span class="stats-bar-lbl">Daily Earning</span>
            </div>
            <div class="stats-bar-cell">
                <i class="bi bi-calendar-check stats-bar-icon" style="color:var(--blue);"></i>
                <span class="stats-bar-val"
                    style="color:var(--blue);">{{ $package->duration_days == 0 ? '∞' : $package->duration_days }}</span>
                <span class="stats-bar-lbl">Days Valid</span>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="main-grid">

            <!-- LEFT -->
            <div>

                <!-- Earning Potential -->
                <div class="panel">
                    <div class="panel-head">
                        <i class="bi bi-graph-up-arrow" style="color:var(--cyan);"></i>
                        <span class="panel-title">Earning Potential</span>
                    </div>
                    <div class="panel-body">
                        <div class="earn-grid">
                            <div>
                                <div class="earn-row">
                                    <span class="earn-key">Per Task Earning</span>
                                    <span
                                        class="earn-val c-green">${{ number_format($package->per_task_earning, 2) }}</span>
                                </div>
                                <div class="earn-row">
                                    <span class="earn-key">Daily Maximum</span>
                                    <span
                                        class="earn-val c-cyan">${{ number_format($package->daily_earning, 2) }}</span>
                                </div>
                                <div class="earn-row">
                                    <span class="earn-key">Monthly Potential</span>
                                    <span
                                        class="earn-val c-blue">${{ number_format($package->daily_earning * 30, 2) }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="earn-row">
                                    <span class="earn-key">Total Potential</span>
                                    <span
                                        class="earn-val c-gold">${{ number_format($package->total_earning_potential, 2) }}</span>
                                </div>
                                <div class="earn-row">
                                    <span class="earn-key">Investment</span>
                                    <span class="earn-val c-red">−${{ number_format($package->price, 2) }}</span>
                                </div>
                                <div class="earn-row">
                                    <span class="earn-key">Net Profit</span>
                                    <span
                                        class="earn-val c-green">${{ number_format($package->total_earning_potential - $package->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="roi-box">
                            <div class="roi-label">
                                <i class="bi bi-graph-up"></i>
                                Return on Investment (ROI)
                            </div>
                            <div class="roi-val">{{ number_format($package->roi_percentage, 1) }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                @if ($package->features)
                    <div class="panel">
                        <div class="panel-head">
                            <i class="bi bi-star-fill" style="color:var(--cyan);"></i>
                            <span class="panel-title">Package Features</span>
                        </div>
                        <div class="panel-body">
                            <div class="features-list">
                                @foreach (explode("\n", $package->features) as $f)
                                    @if (trim($f))
                                        <div class="feature-item">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>{{ trim($f) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- How It Works -->
                <div class="panel">
                    <div class="panel-head">
                        <i class="bi bi-lightning-charge-fill" style="color:var(--cyan);"></i>
                        <span class="panel-title">How It Works</span>
                    </div>
                    <div class="panel-body">
                        <div class="steps-list">
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div>
                                    <div class="step-title">Purchase Package</div>
                                    <div class="step-desc">Activate with your wallet balance instantly.</div>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div>
                                    <div class="step-title">Watch Daily Ads</div>
                                    <div class="step-desc">Complete {{ $package->daily_tasks }} Adastra ad tasks per
                                        day.</div>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div>
                                    <div class="step-title">Trading Profits</div>
                                    <div class="step-desc">Earn from live multi-market trading automatically.</div>
                                </div>
                            </div>
                            <div class="step-item">
                                <div class="step-num">4</div>
                                <div>
                                    <div class="step-title">Withdraw Anytime</div>
                                    <div class="step-desc">Send to your USDT wallet — processed in hours.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Stats -->
                <div class="panel">
                    <div class="panel-head">
                        <i class="bi bi-bar-chart-fill" style="color:var(--cyan);"></i>
                        <span class="panel-title">Package Statistics</span>
                    </div>
                    <div class="pkg-stats-grid">
                        <div class="pkg-stat-cell">
                            <i class="bi bi-people-fill pkg-stat-icon2" style="color:var(--cyan);"></i>
                            <span class="pkg-stat-val2">{{ $package->total_subscribers ?? 0 }}</span>
                            <span class="pkg-stat-lbl2">Total Investors</span>
                        </div>
                        <div class="pkg-stat-cell">
                            <i class="bi bi-check-circle-fill pkg-stat-icon2" style="color:var(--green);"></i>
                            <span class="pkg-stat-val2">{{ $package->active_subscribers ?? 0 }}</span>
                            <span class="pkg-stat-lbl2">Active Now</span>
                        </div>
                        <div class="pkg-stat-cell">
                            <i class="bi bi-star-fill pkg-stat-icon2" style="color:var(--gold);"></i>
                            <span class="pkg-stat-val2">4.8/5</span>
                            <span class="pkg-stat-lbl2">User Rating</span>
                        </div>
                    </div>
                </div>

            </div><!-- /left -->

            <!-- RIGHT SIDEBAR -->
            <div>
                <div style="position:sticky;top:80px;">

                    <!-- Price Card -->
                    <div class="price-card">
                        <div class="price-card-lbl">One-time Investment</div>
                        <div class="price-card-val">${{ number_format($package->price, 2) }}</div>
                        <div class="price-card-sub">USDT · Instant Activation · Zero Fees</div>

                        @auth
                            <form action="{{ route('packages.purchase', $package->slug) }}" method="POST">
                                @csrf
                                <button type="submit" class="buy-btn buy-btn-solid">
                                    <i class="bi bi-cart-check-fill"></i> Activate Package
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="buy-btn buy-btn-solid" style="display:flex;">
                                <i class="bi bi-box-arrow-in-right"></i> Login to Purchase
                            </a>
                        @endauth

                        <a href="{{ route('welcome') }}#packages" class="buy-btn buy-btn-outline"
                            style="display:flex;">
                            <i class="bi bi-arrow-left"></i> All Packages
                        </a>
                    </div>

                    <!-- Summary -->
                    <div class="panel">
                        <div class="panel-head">
                            <i class="bi bi-receipt" style="color:var(--cyan);"></i>
                            <span class="panel-title">Payment Summary</span>
                        </div>
                        <div class="panel-body" style="padding-top:14px;padding-bottom:14px;">
                            <div class="summary-row">
                                <span class="summary-key">Package Price</span>
                                <span style="font-weight:700;">${{ number_format($package->price, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-key">Processing Fee</span>
                                <span style="font-weight:700;color:var(--green);">$0.00 FREE</span>
                            </div>
                            <div class="summary-total">
                                <span class="summary-total-key">Total</span>
                                <span class="summary-total-val">${{ number_format($package->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- What You Get -->
                    <div class="panel">
                        <div class="panel-head">
                            <i class="bi bi-gift-fill" style="color:var(--cyan);"></i>
                            <span class="panel-title">What You Get</span>
                        </div>
                        <div class="panel-body" style="padding-top:14px;padding-bottom:14px;">
                            <div class="get-item"><i class="bi bi-check2-circle"></i> {{ $package->daily_tasks }}
                                daily ad tasks</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> Up to
                                ${{ number_format($package->daily_earning, 2) }}/day earning</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i>
                                {{ $package->duration_days == 0 ? 'Unlimited' : $package->duration_days . ' days' }}
                                access</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> Total potential:
                                ${{ number_format($package->total_earning_potential, 2) }}</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> ROI:
                                {{ number_format($package->roi_percentage, 1) }}%</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> Trading profits daily</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> Adastra ad bonuses</div>
                            <div class="get-item"><i class="bi bi-check2-circle"></i> Withdraw anytime</div>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- /main-grid -->

        <!-- FAQ -->
        <div class="faq-section">
            <h2 class="faq-title">
                <i class="bi bi-question-circle-fill"></i>
                Frequently Asked Questions
            </h2>
            @foreach ([
        ['q' => 'How does this package work?', 'a' => 'After purchasing, you complete up to ' . $package->daily_tasks . ' daily ad tasks via the Adastra network. Your investment also earns from live multi-market trading. Both streams credit daily to your balance.'],
        ['q' => 'Can I buy multiple packages?', 'a' => 'Yes! You can hold multiple different packages simultaneously. Each one adds its own daily task quota and earning ceiling. You cannot hold two of the same package at once.'],
        ['q' => 'When can I withdraw?', 'a' => 'Withdraw anytime — no lock-in period. Funds go directly to your USDT TRC20 wallet, processed within 1–6 hours. Zero withdrawal fees.'],
        ['q' => 'What happens after ' . $package->duration_days . ' days?', 'a' => 'The package expires and tasks stop generating rewards. Your accumulated balance remains yours. You can re-activate the same package or upgrade to a higher tier.'],
        ['q' => 'What is the Adastra ad network?', 'a' => 'Adastra is our integrated ad platform. Advertisers pay per view and we pass the revenue directly to your balance as USDT — completely separate from and on top of your trading profits.'],
    ] as $i => $faq)
                <div class="faq-item" id="fq{{ $i }}">
                    <div class="faq-q" onclick="tf({{ $i }})">
                        <span>{{ $faq['q'] }}</span>
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <div class="faq-a">{{ $faq['a'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- FOOTER -->
        <footer>
            <p class="footer-text">© {{ date('Y') }} TopTrade. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="{{ route('welcome') }}#how">How It Works</a>
                <a href="{{ route('welcome') }}#packages">All Packages</a>
                <a href="{{ route('welcome') }}#faq">FAQ</a>
                <a href="{{ route('login') }}">Sign In</a>
            </div>
        </footer>

    </div><!-- /page -->

    <!-- MOBILE BOTTOM NAV -->
    <nav class="mobile-nav">
        <div class="mobile-nav-inner">
            <a href="{{ route('welcome') }}" class="mob-btn">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('welcome') }}#packages" class="mob-btn active">
                <i class="bi bi-box-seam"></i>
                <span>Packages</span>
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="mob-btn mob-cta">
                    <div class="mob-cta-wrap"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('register') }}" class="mob-btn mob-cta">
                    <div class="mob-cta-wrap"><i class="bi bi-lightning-charge-fill"></i></div>
                    <span>Join Now</span>
                </a>
            @endauth
            <a href="#" class="mob-btn">
                <i class="bi bi-play-circle"></i>
                <span>Earn Ads</span>
            </a>
            <a href="{{ route('login') }}" class="mob-btn">
                <i class="bi bi-person"></i>
                <span>Account</span>
            </a>
        </div>
    </nav>

    <script>
        function tf(i) {
            const item = document.getElementById('fq' + i);
            const open = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('open'));
            if (!open) item.classList.add('open');
        }
    </script>
</body>

</html>
