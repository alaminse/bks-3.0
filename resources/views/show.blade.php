<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $package->name }} — Package Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">

    <style>
    /* ═══════════════════════════════════════════════════
       PACKAGE DETAIL PAGE — CYBERPUNK
       Extends landing.css design system
    ═══════════════════════════════════════════════════ */

    /* ── PAGE BG ── */
    .pkg-page {
        background: var(--bg, #000814);
        min-height: 100vh;
        padding-bottom: 5rem;
    }

    /* ── BREADCRUMB HERO ── */
    .pkg-hero {
        background: linear-gradient(180deg, rgba(0,71,255,0.08) 0%, transparent 100%);
        border-bottom: 1px solid rgba(0,245,255,0.1);
        padding: 2.5rem 0 1.5rem;
        margin-bottom: 2.5rem;
        position: relative; overflow: hidden;
    }
    .pkg-hero::before {
        content: '';
        position: absolute; top:0; left:0; right:0; height:2px;
        background: linear-gradient(90deg, transparent, var(--primary,#00f5ff), transparent);
    }
    .pkg-hero-inner { position: relative; z-index: 1; }
    .pkg-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem;
        font-family: 'Orbitron', monospace;
        font-size: 0.52rem; letter-spacing: 2px; text-transform: uppercase;
        color: var(--text-muted, #4b5563); margin-bottom: 1rem;
    }
    .pkg-breadcrumb a { color: var(--primary, #00f5ff); text-decoration: none; transition: opacity .2s; }
    .pkg-breadcrumb a:hover { opacity: 0.7; }
    .pkg-breadcrumb i { font-size: 0.45rem; }
    .pkg-hero-title {
        font-family: 'Orbitron', monospace;
        font-size: clamp(1.4rem, 3.5vw, 2rem);
        font-weight: 900; color: #fff;
        letter-spacing: 2px; margin: 0 0 0.5rem;
    }
    .pkg-hero-title span { color: var(--primary, #00f5ff); }
    .pkg-status-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-family: 'Orbitron', monospace;
        font-size: 0.5rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 0.25rem 0.7rem; border: 1px solid;
        clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
    }
    .pkg-status-badge.active   { color: #34d399; border-color: rgba(52,211,153,0.4); background: rgba(52,211,153,0.07); }
    .pkg-status-badge.inactive { color: #6b7280; border-color: rgba(107,114,128,0.4); background: rgba(107,114,128,0.07); }
    .pkg-status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; animation: blink 1.8s infinite; }
    @keyframes blink { 0%,100%{opacity:1}50%{opacity:.3} }

    /* ── LAYOUT ── */
    .pkg-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem;
        align-items: start;
    }
    @media(max-width:1199px){ .pkg-grid { grid-template-columns: 1fr 290px; } }
    @media(max-width:991px)  { .pkg-grid { grid-template-columns: 1fr; } }

    /* ── SHARED PANEL ── */
    .pkg-panel {
        background: rgba(2,6,23,0.85);
        border: 1px solid rgba(255,255,255,0.08);
        position: relative; overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .pkg-panel::before {
        content: '';
        position: absolute; top:0; left:0; right:0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,245,255,0.25), transparent);
    }
    .pkg-panel-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1.4rem;
        background: rgba(255,255,255,0.03);
        border-bottom: 1px solid rgba(255,255,255,0.06);
        flex-wrap: wrap; gap: 0.5rem;
    }
    .pkg-panel-title {
        font-family: 'Orbitron', monospace;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 2px;
        text-transform: uppercase; color: var(--primary, #00f5ff);
        margin: 0; display: flex; align-items: center; gap: 0.5rem;
    }
    .pkg-panel-body { padding: 1.5rem 1.4rem; }

    /* ── DESCRIPTION ── */
    .pkg-desc {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.05rem; color: rgba(255,255,255,0.65); line-height: 1.7;
        margin: 0;
    }

    /* ── STAT GRID ── */
    .pkg-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 1.25rem;
    }
    @media(max-width:575px){ .pkg-stats { grid-template-columns: 1fr 1fr; } }

    .pkg-stat {
        background: rgba(2,6,23,0.9);
        padding: 1.5rem 1rem;
        text-align: center;
        position: relative; overflow: hidden;
        transition: background .25s;
    }
    .pkg-stat:hover { background: rgba(0,245,255,0.04); }
    .pkg-stat::before {
        content: '';
        position: absolute; top:0; left:0; right:0; height:2px;
        background: var(--sc, rgba(0,245,255,0.4));
        transform: scaleX(0); transition: transform .4s;
    }
    .pkg-stat:hover::before { transform: scaleX(1); }
    .pkg-stat-icon {
        font-size: 1.5rem; margin-bottom: 0.5rem; display: block;
        filter: drop-shadow(0 0 6px currentColor);
    }
    .pkg-stat-val {
        font-family: 'Orbitron', monospace;
        font-size: 1.3rem; font-weight: 900; color: #fff; line-height: 1;
        margin-bottom: 0.3rem; display: block;
    }
    .pkg-stat-lbl {
        font-family: 'Orbitron', monospace;
        font-size: 0.46rem; letter-spacing: 2px; text-transform: uppercase;
        color: #4b5563;
    }

    /* ── EARNINGS TABLE ── */
    .pkg-earn-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    @media(max-width:575px){ .pkg-earn-grid { grid-template-columns: 1fr; } }

    .pkg-earn-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-family: 'Rajdhani', sans-serif; font-size: 0.9rem;
    }
    .pkg-earn-row:last-child { border-bottom: none; }
    .pkg-earn-key { color: #6b7280; }
    .pkg-earn-val { font-weight: 700; color: #fff; font-size: 0.92rem; }
    .pkg-earn-val.green  { color: #34d399; }
    .pkg-earn-val.cyan   { color: var(--primary, #00f5ff); }
    .pkg-earn-val.blue   { color: #60a5fa; }
    .pkg-earn-val.red    { color: #f87171; }

    /* ROI box */
    .pkg-roi {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem;
        background: rgba(52,211,153,0.06);
        border: 1px solid rgba(52,211,153,0.25);
        border-left: 3px solid #34d399;
        margin-top: 1rem;
    }
    .pkg-roi-label {
        font-family: 'Orbitron', monospace;
        font-size: 0.58rem; letter-spacing: 1.5px; text-transform: uppercase;
        color: #34d399; display: flex; align-items: center; gap: 0.5rem;
    }
    .pkg-roi-val {
        font-family: 'Orbitron', monospace;
        font-size: 1.6rem; font-weight: 900; color: #34d399;
        text-shadow: 0 0 20px rgba(52,211,153,0.4);
    }

    /* ── FEATURES ── */
    .pkg-features { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem 1rem; }
    @media(max-width:575px){ .pkg-features { grid-template-columns: 1fr; } }

    .pkg-feature-item {
        display: flex; align-items: flex-start; gap: 0.6rem;
        font-family: 'Rajdhani', sans-serif; font-size: 0.92rem; color: rgba(255,255,255,0.7);
    }
    .pkg-feature-item i { color: #34d399; font-size: 0.85rem; margin-top: 0.15rem; flex-shrink: 0; }

    /* ── HOW IT WORKS ── */
    .pkg-steps { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media(max-width:575px){ .pkg-steps { grid-template-columns: 1fr; } }

    .pkg-step {
        display: flex; gap: 0.85rem; align-items: flex-start;
        padding: 1rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.06);
        transition: border-color .25s, background .25s;
    }
    .pkg-step:hover { border-color: rgba(0,245,255,0.2); background: rgba(0,245,255,0.03); }
    .pkg-step-num {
        font-family: 'Orbitron', monospace;
        font-size: 0.7rem; font-weight: 900; color: var(--primary, #00f5ff);
        width: 28px; height: 28px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(0,245,255,0.3);
        background: rgba(0,245,255,0.07);
        clip-path: polygon(4px 0,100% 0,calc(100% - 4px) 100%,0 100%);
    }
    .pkg-step-body { flex: 1; min-width: 0; }
    .pkg-step-title {
        font-family: 'Orbitron', monospace;
        font-size: 0.62rem; font-weight: 700; letter-spacing: 1px;
        color: #fff; margin-bottom: 0.25rem;
    }
    .pkg-step-desc { font-family: 'Rajdhani', sans-serif; font-size: 0.85rem; color: #6b7280; margin: 0; }

    /* ── PACKAGE STATS (subscribers) ── */
    .pkg-subs { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: rgba(255,255,255,0.06); }
    .pkg-sub-item {
        background: rgba(2,6,23,0.9); padding: 1.25rem 1rem; text-align: center;
        transition: background .2s;
    }
    .pkg-sub-item:hover { background: rgba(0,245,255,0.03); }
    .pkg-sub-icon { font-size: 1.6rem; display: block; margin-bottom: 0.5rem; }
    .pkg-sub-val {
        font-family: 'Orbitron', monospace; font-size: 1.1rem; font-weight: 900;
        color: #fff; margin-bottom: 0.25rem; display: block;
    }
    .pkg-sub-lbl { font-family: 'Orbitron', monospace; font-size: 0.46rem; letter-spacing: 2px; text-transform: uppercase; color: #4b5563; }

    /* ── RIGHT SIDEBAR ── */

    /* price card */
    .pkg-price-card {
        background: linear-gradient(135deg, #000d2e, #010e24, #011a3e);
        border: 1px solid rgba(0,245,255,0.15);
        position: relative; overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .pkg-price-card::before {
        content: '';
        position: absolute; top:0; left:0; right:0; height:2px;
        background: linear-gradient(90deg, transparent, var(--primary,#00f5ff), transparent);
    }
    .pkg-price-card::after {
        content: '';
        position: absolute; top:-80px; right:-80px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(0,71,255,0.15), transparent 65%);
        pointer-events: none;
    }
    .pkg-price-inner { padding: 1.75rem 1.5rem; position: relative; z-index: 1; }
    .pkg-price-lbl {
        font-family: 'Orbitron', monospace; font-size: 0.5rem; letter-spacing: 4px;
        text-transform: uppercase; color: var(--primary, #00f5ff); opacity: 0.8;
        display: block; margin-bottom: 0.35rem;
    }
    .pkg-price-val {
        font-family: 'Orbitron', monospace; font-size: 2.4rem; font-weight: 900;
        color: #fff; line-height: 1;
        text-shadow: 0 0 30px rgba(0,245,255,0.2);
        display: block; margin-bottom: 0.3rem;
    }
    .pkg-price-sub { font-family: 'Rajdhani', sans-serif; font-size: 0.85rem; color: #4b5563; }

    /* buy button */
    .pkg-buy-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        width: 100%;
        font-family: 'Orbitron', monospace;
        font-size: 0.68rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 0.9rem 1.5rem;
        background: linear-gradient(135deg, var(--primary,#00f5ff), #0047ff);
        color: #000; border: none; cursor: pointer;
        clip-path: polygon(8px 0, 100% 0, calc(100% - 8px) 100%, 0 100%);
        transition: all 0.3s; text-decoration: none;
        margin-top: 1.25rem; margin-bottom: 0.75rem;
    }
    .pkg-buy-btn:hover {
        box-shadow: 0 0 30px rgba(0,245,255,0.4), 0 0 60px rgba(0,245,255,0.15);
        transform: translateY(-2px); color: #000;
    }
    .pkg-buy-btn.login { background: linear-gradient(135deg, #60a5fa, #0047ff); }

    .pkg-back-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        width: 100%;
        font-family: 'Orbitron', monospace;
        font-size: 0.58rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
        padding: 0.6rem 1rem;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.1);
        color: #6b7280; text-decoration: none;
        clip-path: polygon(5px 0, 100% 0, calc(100% - 5px) 100%, 0 100%);
        transition: all 0.2s;
    }
    .pkg-back-btn:hover { border-color: rgba(0,245,255,0.3); color: var(--primary, #00f5ff); }

    /* payment summary */
    .pkg-summary-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-family: 'Rajdhani', sans-serif; font-size: 0.9rem;
    }
    .pkg-summary-row:last-child { border-bottom: none; }
    .pkg-summary-key { color: #6b7280; }
    .pkg-summary-val { color: #fff; font-weight: 700; }
    .pkg-summary-total-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 0 0;
        margin-top: 0.25rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    .pkg-summary-total-key {
        font-family: 'Orbitron', monospace; font-size: 0.6rem; font-weight: 700;
        letter-spacing: 1px; text-transform: uppercase; color: #fff;
    }
    .pkg-summary-total-val {
        font-family: 'Orbitron', monospace; font-size: 1rem; font-weight: 900;
        color: var(--primary, #00f5ff);
        text-shadow: 0 0 12px rgba(0,245,255,0.3);
    }

    /* what you get */
    .pkg-get-item {
        display: flex; align-items: flex-start; gap: 0.6rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-family: 'Rajdhani', sans-serif; font-size: 0.88rem; color: rgba(255,255,255,0.75);
    }
    .pkg-get-item:last-child { border-bottom: none; }
    .pkg-get-item i { color: #34d399; font-size: 0.85rem; margin-top: 0.12rem; flex-shrink: 0; }

    /* ── FAQ SECTION ── */
    .pkg-faq { margin-top: 2.5rem; }
    .pkg-faq-title {
        font-family: 'Orbitron', monospace;
        font-size: clamp(1rem, 2.5vw, 1.3rem); font-weight: 900;
        color: #fff; letter-spacing: 2px; margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.65rem;
    }
    .pkg-faq-title i { color: var(--primary, #00f5ff); }

    .accordion-item {
        background: rgba(2,6,23,0.85) !important;
        border: 1px solid rgba(255,255,255,0.07) !important;
        border-radius: 0 !important;
        margin-bottom: 4px;
        overflow: hidden;
        position: relative;
    }
    .accordion-item::before {
        content: '';
        position: absolute; top:0; left:0; bottom:0; width:2px;
        background: linear-gradient(to bottom, transparent, rgba(0,245,255,0.3), transparent);
        opacity: 0; transition: opacity .3s;
    }
    .accordion-item:hover::before,
    .accordion-item:has(.accordion-button:not(.collapsed))::before { opacity: 1; }

    .accordion-button {
        font-family: 'Rajdhani', sans-serif !important;
        font-size: 0.95rem !important; font-weight: 600 !important;
        background: rgba(2,6,23,0.85) !important;
        color: rgba(255,255,255,0.8) !important;
        padding: 1rem 1.25rem !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }
    .accordion-button::after {
        filter: invert(1) sepia(1) saturate(3) hue-rotate(160deg) !important;
    }
    .accordion-button:not(.collapsed) {
        background: rgba(0,245,255,0.04) !important;
        color: var(--primary, #00f5ff) !important;
        border-bottom: 1px solid rgba(0,245,255,0.15) !important;
    }
    .accordion-body {
        background: rgba(1,6,20,0.9) !important;
        color: rgba(255,255,255,0.55) !important;
        font-family: 'Rajdhani', sans-serif !important;
        font-size: 0.92rem !important; line-height: 1.7 !important;
        padding: 1rem 1.25rem 1.25rem !important;
    }

    /* ── FOOTER ── */
    .pkg-footer {
        background: rgba(0,0,0,0.6);
        border-top: 1px solid rgba(255,255,255,0.06);
        padding: 2rem 0;
        margin-top: 4rem;
        text-align: center;
    }
    .pkg-footer-text { font-family: 'Rajdhani', sans-serif; font-size: 0.88rem; color: #4b5563; margin-bottom: 0.75rem; }
    .pkg-footer-links { display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap; }
    .pkg-footer-links a {
        font-family: 'Orbitron', monospace; font-size: 0.5rem; letter-spacing: 2px;
        text-transform: uppercase; color: #4b5563; text-decoration: none; transition: color .2s;
    }
    .pkg-footer-links a:hover { color: var(--primary, #00f5ff); }

    /* ── RESPONSIVE ── */
    @media(max-width:767px){
        .pkg-panel-body { padding: 1.1rem; }
        .pkg-price-inner { padding: 1.25rem 1.1rem; }
        .pkg-price-val { font-size: 1.8rem; }
    }
    @media(max-width:575px){
        .pkg-subs { grid-template-columns: repeat(3,1fr); }
        .pkg-hero { padding: 1.75rem 0 1rem; margin-bottom: 1.5rem; }
    }
    </style>
</head>

<body class="pkg-page">

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" onerror="this.style.display='none'">
            </a>
            <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="nav" class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#how">Protocol</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#packages">Tiers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#partners">Ecosystem</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#faq">Support</a></li>
                    @auth
                        <li class="nav-item ms-lg-2">
                            <a class="btn-cyber-solid" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn-cyber" href="{{ route('login') }}"><span>Sign In</span></a>
                        </li>
                        <li class="nav-item ms-lg-1">
                            <a class="btn-cyber-solid" href="{{ route('register') }}">Join Now</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div style="height:80px;"></div>

    {{-- ══ HERO STRIP ══ --}}
    <div class="pkg-hero">
        <div class="container pkg-hero-inner">
            <div class="pkg-breadcrumb">
                <a href="{{ route('welcome') }}">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('welcome') }}#packages">Packages</a>
                <i class="bi bi-chevron-right"></i>
                <span style="color:rgba(255,255,255,0.4);">{{ $package->name }}</span>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-3">
                <h1 class="pkg-hero-title">
                    <span>{{ $package->name }}</span>
                </h1>
                <span class="pkg-status-badge {{ $package->is_active ? 'active' : 'inactive' }}">
                    <span class="pkg-status-dot"></span>
                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ══ KEY STATS BAR ══ --}}
    <div class="container mb-4">
        <div class="pkg-stats">
            <div class="pkg-stat" style="--sc: rgba(0,245,255,0.5);">
                <i class="bi bi-currency-dollar pkg-stat-icon" style="color: var(--primary, #00f5ff);"></i>
                <span class="pkg-stat-val">${{ number_format($package->price, 2) }}</span>
                <span class="pkg-stat-lbl">Package Price</span>
            </div>
            <div class="pkg-stat" style="--sc: rgba(52,211,153,0.5);">
                <i class="bi bi-list-check pkg-stat-icon" style="color: #34d399;"></i>
                <span class="pkg-stat-val">{{ $package->daily_tasks }}</span>
                <span class="pkg-stat-lbl">Tasks / Day</span>
            </div>
            <div class="pkg-stat" style="--sc: rgba(251,191,36,0.5);">
                <i class="bi bi-cash-coin pkg-stat-icon" style="color: #fbbf24;"></i>
                <span class="pkg-stat-val">${{ number_format($package->daily_earning, 2) }}</span>
                <span class="pkg-stat-lbl">Daily Earning</span>
            </div>
            <div class="pkg-stat" style="--sc: rgba(96,165,250,0.5);">
                <i class="bi bi-calendar-check pkg-stat-icon" style="color: #60a5fa;"></i>
                <span class="pkg-stat-val">{{ $package->duration_days === 0 ? 'Unlimited' : '' }}</span>
                <span class="pkg-stat-lbl">Days Validity</span>
            </div>
        </div>
    </div>

    {{-- ══ MAIN CONTENT ══ --}}
    <div class="container">
        <div class="pkg-grid">

            {{-- ════ LEFT COLUMN ════ --}}
            <div>

                {{-- Description --}}
                <div class="pkg-panel">
                    <div class="pkg-panel-head">
                        <h2 class="pkg-panel-title"><i class="bi bi-info-circle-fill"></i> Package Overview</h2>
                    </div>
                    <div class="pkg-panel-body">
                        <p class="pkg-desc">{{ $package->description }}</p>
                    </div>
                </div>

                {{-- Earning Potential --}}
                <div class="pkg-panel">
                    <div class="pkg-panel-head">
                        <h2 class="pkg-panel-title"><i class="bi bi-graph-up-arrow"></i> Earning Potential</h2>
                    </div>
                    <div class="pkg-panel-body">
                        <div class="pkg-earn-grid">
                            <div>
                                <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Per Task Earning</span>
                                    <span class="pkg-earn-val green">${{ number_format($package->per_task_earning, 2) }}</span>
                                </div>
                                <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Daily Maximum</span>
                                    <span class="pkg-earn-val cyan">${{ number_format($package->daily_earning, 2) }}</span>
                                </div>
                                <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Monthly Potential</span>
                                    <span class="pkg-earn-val blue">${{ number_format($package->daily_earning * 30, 2) }}</span>
                                </div>
                            </div>
                            <div>
                                {{-- <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Total ({{ $package->duration_days }}d)</span>
                                    <span class="pkg-earn-val green">${{ number_format($package->total_earning_potential, 2) }}</span>
                                </div> --}}
                                <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Investment</span>
                                    <span class="pkg-earn-val red">−${{ number_format($package->price, 2) }}</span>
                                </div>
                                {{-- <div class="pkg-earn-row">
                                    <span class="pkg-earn-key">Net Profit</span>
                                    <span class="pkg-earn-val green">${{ number_format($package->total_earning_potential - $package->price, 2) }}</span>
                                </div> --}}
                            </div>
                        </div>
                        <div class="pkg-roi">
                            <div class="pkg-roi-label">
                                <i class="bi bi-graph-up"></i> Return on Investment (ROI)
                            </div>
                            <div class="pkg-roi-val">{{ number_format($package->roi_percentage, 1) }}%</div>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                @if($package->features)
                <div class="pkg-panel">
                    <div class="pkg-panel-head">
                        <h2 class="pkg-panel-title"><i class="bi bi-star-fill"></i> Package Features</h2>
                    </div>
                    <div class="pkg-panel-body">
                        <div class="pkg-features">
                            @foreach(explode("\n", $package->features) as $feature)
                                @if(trim($feature))
                                    <div class="pkg-feature-item">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>{{ trim($feature) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- How it Works --}}
                <div class="pkg-panel">
                    <div class="pkg-panel-head">
                        <h2 class="pkg-panel-title"><i class="bi bi-lightning-charge-fill"></i> How It Works</h2>
                    </div>
                    <div class="pkg-panel-body">
                        <div class="pkg-steps">
                            <div class="pkg-step">
                                <div class="pkg-step-num">1</div>
                                <div class="pkg-step-body">
                                    <div class="pkg-step-title">Purchase Package</div>
                                    <p class="pkg-step-desc">Buy this package with your wallet balance</p>
                                </div>
                            </div>
                            <div class="pkg-step">
                                <div class="pkg-step-num">2</div>
                                <div class="pkg-step-body">
                                    <div class="pkg-step-title">Complete Daily Tasks</div>
                                    <p class="pkg-step-desc">Do up to {{ $package->daily_tasks }} tasks per day</p>
                                </div>
                            </div>
                            <div class="pkg-step">
                                <div class="pkg-step-num">3</div>
                                <div class="pkg-step-body">
                                    <div class="pkg-step-title">Earn Daily</div>
                                    <p class="pkg-step-desc">Earn up to ${{ number_format($package->daily_earning, 2) }} per day</p>
                                </div>
                            </div>
                            <div class="pkg-step">
                                <div class="pkg-step-num">4</div>
                                <div class="pkg-step-body">
                                    <div class="pkg-step-title">Withdraw Anytime</div>
                                    <p class="pkg-step-desc">Withdraw earnings whenever you want</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Package Stats --}}
                <div class="pkg-panel">
                    <div class="pkg-panel-head">
                        <h2 class="pkg-panel-title"><i class="bi bi-bar-chart-fill"></i> Package Statistics</h2>
                    </div>
                    <div class="pkg-subs">
                        <div class="pkg-sub-item">
                            <i class="bi bi-people-fill pkg-sub-icon" style="color:var(--primary,#00f5ff);"></i>
                            <span class="pkg-sub-val">{{ $package->total_subscribers ?? 0 }}</span>
                            <span class="pkg-sub-lbl">Total Subscribers</span>
                        </div>
                        <div class="pkg-sub-item">
                            <i class="bi bi-check-circle-fill pkg-sub-icon" style="color:#34d399;"></i>
                            <span class="pkg-sub-val">{{ $package->active_subscribers ?? 0 }}</span>
                            <span class="pkg-sub-lbl">Active Now</span>
                        </div>
                        <div class="pkg-sub-item">
                            <i class="bi bi-star-fill pkg-sub-icon" style="color:#fbbf24;"></i>
                            <span class="pkg-sub-val">4.8/5</span>
                            <span class="pkg-sub-lbl">User Rating</span>
                        </div>
                    </div>
                </div>

            </div>{{-- /left --}}

            {{-- ════ RIGHT SIDEBAR ════ --}}
            <div>

                {{-- Price + Buy (sticky) --}}
                <div class="sticky-top" style="top:96px;">

                    <div class="pkg-price-card">
                        <div class="pkg-price-inner">
                            <span class="pkg-price-lbl">One-time Investment</span>
                            <span class="pkg-price-val">${{ number_format($package->price, 2) }}</span>
                            <span class="pkg-price-sub">USDT · Instant Activation</span>

                            @auth
                                <form id="purchase-form-{{ $package->slug }}"
                                    action="{{ route('packages.purchase', $package->slug) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="pkg-buy-btn">
                                        <i class="bi bi-cart-check-fill"></i> Purchase Now
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="pkg-buy-btn login">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Purchase
                                </a>
                            @endauth

                            <a href="{{ route('welcome') }}#packages" class="pkg-back-btn">
                                <i class="bi bi-arrow-left"></i> All Packages
                            </a>
                        </div>
                    </div>

                    {{-- Payment Summary --}}
                    <div class="pkg-panel">
                        <div class="pkg-panel-head">
                            <h3 class="pkg-panel-title"><i class="bi bi-receipt"></i> Payment Summary</h3>
                        </div>
                        <div class="pkg-panel-body" style="padding-top:1rem;padding-bottom:1rem;">
                            <div class="pkg-summary-row">
                                <span class="pkg-summary-key">Package Price</span>
                                <span class="pkg-summary-val">${{ number_format($package->price, 2) }}</span>
                            </div>
                            <div class="pkg-summary-row">
                                <span class="pkg-summary-key">Processing Fee</span>
                                <span class="pkg-summary-val" style="color:#34d399;">$0.00 — FREE</span>
                            </div>
                            <div class="pkg-summary-total-row">
                                <span class="pkg-summary-total-key">Total</span>
                                <span class="pkg-summary-total-val">${{ number_format($package->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- What You Get --}}
                    <div class="pkg-panel">
                        <div class="pkg-panel-head">
                            <h3 class="pkg-panel-title"><i class="bi bi-gift-fill"></i> What You Get</h3>
                        </div>
                        <div class="pkg-panel-body" style="padding-top:1rem;padding-bottom:1rem;">
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>{{ $package->daily_tasks }} daily tasks</span>
                            </div>
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>Up to ${{ number_format($package->daily_earning, 2) }}/day earning</span>
                            </div>
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>{{ $package->duration_days === 0 ? 'Unlimited' : '' }} days access</span>
                            </div>
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>Total potential: ${{ number_format($package->total_earning_potential, 2) }}</span>
                            </div>
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>ROI: {{ number_format($package->roi_percentage, 1) }}%</span>
                            </div>
                            <div class="pkg-get-item">
                                <i class="bi bi-check2-circle"></i>
                                <span>Instant activation after purchase</span>
                            </div>
                        </div>
                    </div>

                </div>{{-- /sticky --}}
            </div>{{-- /right --}}
        </div>{{-- /grid --}}

        {{-- ══ FAQ ══ --}}
        <div class="pkg-faq">
            <h2 class="pkg-faq-title">
                <i class="bi bi-question-circle-fill"></i> Frequently Asked Questions
            </h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How does this package work?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            After purchasing, you can complete up to {{ $package->daily_tasks }} tasks per day.
                            Each completed task earns you money, up to ${{ number_format($package->daily_earning, 2) }} per day.
                            The package stays active for {{ $package->duration_days === 0 ? 'Unlimited' : '' }} days.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Can I buy multiple packages at once?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! You can purchase multiple different packages simultaneously. You can only have one active subscription of the same package at a time — once it expires you can re-purchase it.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            When can I withdraw my earnings?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            You can withdraw anytime after completing tasks — there's no lock-in period. KYC verification is required before your first withdrawal.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            What happens after {{ $package->duration_days === 0 ? 'Unlimited' : '' }} days?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Your package expires after {{ $package->duration_days === 0 ? 'Unlimited' : '' }} days. You can repurchase the same package or choose a different one. All accumulated earnings remain yours to withdraw.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /container --}}

    {{-- ══ FOOTER ══ --}}
    <footer class="pkg-footer">
        <div class="container">
            <p class="pkg-footer-text">© {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
            <div class="pkg-footer-links">
                <a href="{{ route('welcome') }}#faq">FAQ</a>
                <a href="{{ route('welcome') }}#contact">Contact</a>
                <a href="#">Terms</a>
                <a href="#">Privacy</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
</body>
</html>
