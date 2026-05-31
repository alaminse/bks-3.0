<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $package->name }} — TopTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=IBM+Plex+Mono:wght@300;400;500&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">

    <style>
    /* ════════════════════════════════════════════
       TOPTRADE — PLAN DETAIL PAGE
       Palette: Charcoal + Antique Gold
       Fonts: DM Serif + IBM Plex Mono + Syne
    ════════════════════════════════════════════ */

    :root {
        --gold:        #C9A84C;
        --gold-light:  #E8C97A;
        --gold-dim:    #8A6F2E;
        --gold-faint:  rgba(201,168,76,0.08);
        --gold-mist:   rgba(201,168,76,0.14);
        --ink:         #0C0C0E;
        --ink-2:       #131316;
        --ink-3:       #1A1A1F;
        --ink-4:       #222228;
        --border:      rgba(255,255,255,0.07);
        --border-mid:  rgba(255,255,255,0.11);
        --border-gold: rgba(201,168,76,0.22);
        --border-gold-b: rgba(201,168,76,0.45);
        --text:        #D4D4DC;
        --text-dim:    #7A7A88;
        --text-faint:  #3E3E48;
        --green:       #4CAF82;
        --green-bg:    rgba(76,175,130,0.08);
        --green-bdr:   rgba(76,175,130,0.25);
        --red:         #D95F5F;
        --red-bg:      rgba(217,95,95,0.08);
        --red-bdr:     rgba(217,95,95,0.25);
        --blue:        #5B8EF0;
        --blue-bg:     rgba(91,142,240,0.08);
        --amber:       #E8A24A;
        --font-serif:  'DM Serif Display', serif;
        --font-mono:   'IBM Plex Mono', monospace;
        --font-sans:   'Syne', sans-serif;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: var(--ink);
        color: var(--text);
        font-family: var(--font-sans);
        font-size: 15px;
        line-height: 1.6;
        overflow-x: hidden;
        min-height: 100vh;
    }

    /* Subtle grain */
    body::before {
        content: '';
        position: fixed; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
        pointer-events: none; z-index: 9000; opacity: 0.5;
    }

    a { color: var(--gold); text-decoration: none; transition: color 0.2s; }
    a:hover { color: var(--gold-light); }

    /* ── NAVBAR ── */
    .tt-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        background: rgba(12,12,14,0.95);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border);
        height: 64px;
        display: flex; align-items: center;
    }
    .tt-nav-inner {
        max-width: 1240px; margin: 0 auto; padding: 0 1.5rem;
        width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
    }
    .tt-brand {
        font-family: var(--font-serif);
        font-size: 1.25rem; color: #fff; text-decoration: none; letter-spacing: 0.01em;
        white-space: nowrap;
    }
    .tt-brand em { color: var(--gold); font-style: normal; }
    .tt-brand img { max-height: 30px; }
    .tt-nav-links { display: flex; align-items: center; gap: 2rem; list-style: none; }
    .tt-nav-links a {
        color: var(--text-dim); text-decoration: none;
        font-family: var(--font-mono); font-size: 0.68rem; font-weight: 400;
        letter-spacing: 0.1em; text-transform: uppercase; transition: color 0.2s;
    }
    .tt-nav-links a:hover { color: var(--gold); }
    .tt-nav-actions { display: flex; align-items: center; gap: 0.65rem; }
    .tt-btn-ghost {
        font-family: var(--font-mono); font-size: 0.68rem; font-weight: 400;
        letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--text-dim); border: 1px solid var(--border-mid);
        background: transparent; padding: 0.45rem 1rem; text-decoration: none; transition: all 0.2s;
    }
    .tt-btn-ghost:hover { color: var(--gold); border-color: var(--border-gold); }
    .tt-btn-solid {
        font-family: var(--font-mono); font-size: 0.68rem; font-weight: 500;
        letter-spacing: 0.08em; text-transform: uppercase;
        color: var(--ink); background: var(--gold); border: 1px solid var(--gold);
        padding: 0.45rem 1rem; text-decoration: none; transition: all 0.2s;
    }
    .tt-btn-solid:hover { background: var(--gold-light); border-color: var(--gold-light); color: var(--ink); }
    .tt-hamburger { display: none; background: none; border: 1px solid var(--border-mid); padding: 6px 8px; cursor: pointer; }
    .tt-hamburger span { display: block; width: 18px; height: 1.5px; background: var(--text-dim); margin: 4px 0; transition: all 0.3s; }

    .nav-spacer { height: 64px; }

    /* ── BREADCRUMB HEADER ── */
    .plan-header {
        background: var(--ink-2);
        border-bottom: 1px solid var(--border);
        padding: 2.25rem 0 1.75rem;
        position: relative; overflow: hidden;
    }
    .plan-header::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
    }
    .plan-header::after {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 240px; height: 240px;
        background: radial-gradient(circle, rgba(201,168,76,0.05), transparent 65%);
        pointer-events: none;
    }
    .plan-header-inner { position: relative; z-index: 1; }

    .tt-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem;
        font-family: var(--font-mono); font-size: 0.6rem; letter-spacing: 0.12em;
        text-transform: uppercase; color: var(--text-faint); margin-bottom: 1.1rem;
    }
    .tt-breadcrumb a { color: var(--text-dim); transition: color 0.2s; }
    .tt-breadcrumb a:hover { color: var(--gold); }
    .tt-breadcrumb i { font-size: 0.5rem; }

    .plan-title-row { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .plan-title {
        font-family: var(--font-serif);
        font-size: clamp(1.5rem, 3.5vw, 2.2rem);
        font-weight: 400; color: #fff; letter-spacing: 0; margin: 0;
    }
    .plan-status {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-family: var(--font-mono); font-size: 0.58rem; font-weight: 400;
        letter-spacing: 0.1em; text-transform: uppercase;
        padding: 0.25rem 0.7rem; border: 1px solid;
    }
    .plan-status.active   { color: var(--green); border-color: var(--green-bdr); background: var(--green-bg); }
    .plan-status.inactive { color: var(--text-faint); border-color: var(--border); background: transparent; }
    .plan-status-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

    /* ── KEY METRICS BAR ── */
    .metrics-bar {
        display: grid; grid-template-columns: repeat(4, 1fr);
        border: 1px solid var(--border);
        background: var(--border);
        gap: 1px; margin: 2rem 0;
    }
    @media(max-width:575px) { .metrics-bar { grid-template-columns: 1fr 1fr; } }

    .metric-cell {
        background: var(--ink-2);
        padding: 1.5rem 1.25rem;
        display: flex; align-items: center; gap: 1rem;
        transition: background 0.2s;
    }
    .metric-cell:hover { background: var(--ink-3); }
    .metric-icon {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem; flex-shrink: 0; border: 1px solid;
    }
    .metric-icon.gold   { color: var(--gold); border-color: var(--border-gold); background: var(--gold-faint); }
    .metric-icon.green  { color: var(--green); border-color: var(--green-bdr); background: var(--green-bg); }
    .metric-icon.amber  { color: var(--amber); border-color: rgba(232,162,74,0.3); background: rgba(232,162,74,0.08); }
    .metric-icon.blue   { color: var(--blue); border-color: rgba(91,142,240,0.3); background: var(--blue-bg); }
    .metric-lbl {
        font-family: var(--font-mono); font-size: 0.58rem; letter-spacing: 0.1em;
        text-transform: uppercase; color: var(--text-faint); margin-bottom: 0.2rem;
    }
    .metric-val {
        font-family: var(--font-mono); font-size: 1.15rem; font-weight: 500; color: #fff; line-height: 1;
    }

    /* ── MAIN GRID ── */
    .plan-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 1.5rem; align-items: start;
        padding: 2rem 0 5rem;
    }
    @media(max-width:1199px) { .plan-grid { grid-template-columns: 1fr 290px; } }
    @media(max-width:991px)  { .plan-grid { grid-template-columns: 1fr; } }

    /* ── PANELS ── */
    .plan-panel {
        background: var(--ink-2);
        border: 1px solid var(--border);
        margin-bottom: 1.25rem;
        position: relative; overflow: hidden;
    }
    .plan-panel::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .plan-panel:hover::before { opacity: 1; }

    .panel-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 1.4rem;
        background: var(--ink-3); border-bottom: 1px solid var(--border);
        flex-wrap: wrap; gap: 0.5rem;
    }
    .panel-title {
        font-family: var(--font-mono); font-size: 0.68rem; font-weight: 400;
        letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold);
        margin: 0; display: flex; align-items: center; gap: 0.5rem;
    }
    .panel-body { padding: 1.5rem 1.4rem; }

    /* ── DESCRIPTION ── */
    .plan-desc {
        font-family: var(--font-sans);
        font-size: 1rem; color: var(--text-dim); line-height: 1.8; margin: 0;
    }

    /* ── EARNING GRID ── */
    .earn-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
    }
    @media(max-width:575px) { .earn-grid { grid-template-columns: 1fr; } }

    .earn-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.58rem 0; border-bottom: 1px solid var(--border);
        font-size: 0.88rem;
    }
    .earn-row:last-child { border-bottom: none; }
    .earn-key { color: var(--text-dim); }
    .earn-val { font-family: var(--font-mono); font-size: 0.88rem; font-weight: 500; color: #fff; }
    .earn-val.green { color: var(--green); }
    .earn-val.gold  { color: var(--gold); }
    .earn-val.blue  { color: var(--blue); }
    .earn-val.red   { color: var(--red); }

    /* ROI callout */
    .roi-callout {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.1rem 1.25rem; margin-top: 1.25rem;
        background: var(--green-bg);
        border: 1px solid var(--green-bdr); border-left: 3px solid var(--green);
    }
    .roi-label {
        font-family: var(--font-mono); font-size: 0.62rem; letter-spacing: 0.12em;
        text-transform: uppercase; color: var(--green);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .roi-val {
        font-family: var(--font-serif); font-size: 1.8rem; font-weight: 400; color: var(--green);
        font-style: italic;
    }

    /* ── FEATURES ── */
    .features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.55rem 1.25rem; }
    @media(max-width:575px) { .features-grid { grid-template-columns: 1fr; } }
    .feature-item {
        display: flex; align-items: flex-start; gap: 0.6rem;
        font-size: 0.9rem; color: var(--text-dim); padding: 0.25rem 0;
    }
    .feature-item i { color: var(--green); font-size: 0.8rem; margin-top: 0.2rem; flex-shrink: 0; }

    /* ── HOW IT WORKS ── */
    .steps-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media(max-width:575px) { .steps-grid { grid-template-columns: 1fr; } }
    .step-item {
        display: flex; gap: 1rem; align-items: flex-start;
        padding: 1.1rem; background: var(--ink-3); border: 1px solid var(--border);
        transition: border-color 0.2s;
    }
    .step-item:hover { border-color: var(--border-gold); }
    .step-num {
        font-family: var(--font-serif); font-size: 1.4rem; font-weight: 400; color: var(--gold-dim);
        line-height: 1; flex-shrink: 0; width: 28px; text-align: center; font-style: italic;
    }
    .step-title { font-size: 0.88rem; font-weight: 700; color: #fff; margin-bottom: 0.2rem; }
    .step-desc { font-size: 0.8rem; color: var(--text-dim); margin: 0; }

    /* ── STATS TABLE ── */
    .stats-cells { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border); }
    .stat-cell {
        background: var(--ink-2); padding: 1.4rem 1rem; text-align: center;
        transition: background 0.2s;
    }
    .stat-cell:hover { background: var(--ink-3); }
    .stat-cell-icon { font-size: 1.4rem; display: block; margin-bottom: 0.5rem; }
    .stat-cell-val {
        font-family: var(--font-mono); font-size: 1.1rem; font-weight: 500; color: #fff;
        display: block; margin-bottom: 0.25rem;
    }
    .stat-cell-lbl {
        font-family: var(--font-mono); font-size: 0.55rem; letter-spacing: 0.12em;
        text-transform: uppercase; color: var(--text-faint);
    }

    /* ── RIGHT SIDEBAR ── */

    /* price card */
    .price-card {
        background: linear-gradient(135deg, #12100A, #1C180A, #0C0C0E);
        border: 1px solid var(--border-gold);
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
    }
    .price-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
    }
    .price-card::after {
        content: '';
        position: absolute; top: -60px; right: -60px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(201,168,76,0.08), transparent 65%);
        pointer-events: none;
    }
    .price-card-inner { padding: 1.75rem 1.5rem; position: relative; z-index: 1; }

    .price-eyebrow {
        font-family: var(--font-mono); font-size: 0.58rem; letter-spacing: 0.16em;
        text-transform: uppercase; color: var(--gold-dim); display: block; margin-bottom: 0.35rem;
    }
    .price-amount {
        font-family: var(--font-mono); font-size: 2.5rem; font-weight: 500;
        color: #fff; line-height: 1; display: block; margin-bottom: 0.3rem;
    }
    .price-note { font-size: 0.82rem; color: var(--text-dim); font-family: var(--font-sans); }

    .buy-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        width: 100%;
        font-family: var(--font-mono); font-size: 0.72rem; font-weight: 500;
        letter-spacing: 0.1em; text-transform: uppercase;
        padding: 0.9rem 1.5rem;
        background: var(--gold); color: var(--ink);
        border: none; cursor: pointer; text-decoration: none;
        transition: all 0.2s; margin-top: 1.5rem; margin-bottom: 0.65rem;
    }
    .buy-btn:hover { background: var(--gold-light); color: var(--ink); transform: translateY(-1px); }
    .buy-btn.login-btn { background: var(--blue-bg); color: var(--blue); border: 1px solid rgba(91,142,240,0.3); }
    .buy-btn.login-btn:hover { background: rgba(91,142,240,0.15); }

    .back-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.45rem;
        width: 100%;
        font-family: var(--font-mono); font-size: 0.62rem; font-weight: 400;
        letter-spacing: 0.08em; text-transform: uppercase;
        padding: 0.55rem 1rem;
        background: transparent; border: 1px solid var(--border-mid);
        color: var(--text-dim); text-decoration: none; transition: all 0.2s;
    }
    .back-btn:hover { border-color: var(--border-gold); color: var(--gold); }

    /* sidebar panels */
    .side-panel { background: var(--ink-2); border: 1px solid var(--border); margin-bottom: 1.25rem; }
    .side-panel-head { padding: 0.82rem 1.25rem; background: var(--ink-3); border-bottom: 1px solid var(--border); }
    .side-panel-title { font-family: var(--font-mono); font-size: 0.68rem; font-weight: 400; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold); margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .side-panel-body { padding: 1.1rem 1.25rem; }

    /* summary rows */
    .summary-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.58rem 0; border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-key { color: var(--text-dim); }
    .summary-val { font-family: var(--font-mono); font-size: 0.82rem; font-weight: 500; color: #fff; }
    .summary-free { color: var(--green) !important; }
    .summary-total {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.82rem 0 0; margin-top: 0.25rem; border-top: 1px solid var(--border-mid);
    }
    .summary-total-key { font-family: var(--font-mono); font-size: 0.65rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; }
    .summary-total-val { font-family: var(--font-mono); font-size: 1.1rem; font-weight: 500; color: var(--gold); }

    /* what you get */
    .get-item {
        display: flex; align-items: flex-start; gap: 0.6rem;
        padding: 0.5rem 0; border-bottom: 1px solid var(--border);
        font-size: 0.875rem; color: var(--text-dim);
    }
    .get-item:last-child { border-bottom: none; }
    .get-item i { color: var(--green); font-size: 0.8rem; margin-top: 0.2rem; flex-shrink: 0; }

    /* ── FAQ ── */
    .faq-section { padding: 2.5rem 0 5rem; }
    .faq-heading {
        font-family: var(--font-serif); font-size: clamp(1.2rem, 2.5vw, 1.8rem); font-weight: 400;
        color: #fff; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.75rem;
    }
    .faq-heading-line { flex: 1; height: 1px; background: var(--border); }

    .faq-item { border-bottom: 1px solid var(--border); }
    .faq-question {
        width: 100%; text-align: left; background: none; border: none; cursor: pointer;
        padding: 1.25rem 0; display: flex; align-items: center; justify-content: space-between;
        font-family: var(--font-sans); font-size: 0.95rem; font-weight: 600; color: #fff;
        gap: 1.5rem; transition: color 0.2s;
    }
    .faq-question:hover { color: var(--gold); }
    .faq-icon { color: var(--gold-dim); font-size: 1.1rem; flex-shrink: 0; transition: transform 0.3s; }
    .faq-question.open .faq-icon { transform: rotate(45deg); color: var(--gold); }
    .faq-answer {
        font-size: 0.9rem; color: var(--text-dim); line-height: 1.8;
        max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s;
    }
    .faq-answer.open { max-height: 300px; padding-bottom: 1.25rem; }

    /* ── FOOTER ── */
    .plan-footer {
        background: var(--ink-2);
        border-top: 1px solid var(--border);
        padding: 1.75rem 0; text-align: center;
    }
    .footer-brand { font-family: var(--font-serif); font-size: 1.1rem; color: #fff; margin-bottom: 0.5rem; }
    .footer-brand em { color: var(--gold); font-style: normal; }
    .footer-copy { font-family: var(--font-mono); font-size: 0.62rem; color: var(--text-faint); letter-spacing: 0.06em; margin-bottom: 0.85rem; }
    .footer-links { display: flex; justify-content: center; gap: 1.75rem; flex-wrap: wrap; }
    .footer-links a {
        font-family: var(--font-mono); font-size: 0.6rem; letter-spacing: 0.1em;
        text-transform: uppercase; color: var(--text-faint); text-decoration: none; transition: color 0.2s;
    }
    .footer-links a:hover { color: var(--gold); }

    /* ── CONTAINER ── */
    .tt-container { max-width: 1240px; margin: 0 auto; padding: 0 1.5rem; }

    /* ── RESPONSIVE ── */
    @media(max-width:991px) {
        .tt-nav-links { display: none; }
        .tt-hamburger { display: block; }
    }
    @media(max-width:767px) {
        .panel-body { padding: 1.1rem; }
        .price-card-inner { padding: 1.25rem 1.1rem; }
        .price-amount { font-size: 1.9rem; }
        .metric-cell { padding: 1.1rem 0.85rem; gap: 0.65rem; }
    }
    @media(max-width:575px) {
        .plan-title { font-size: 1.5rem; }
        .faq-heading { font-size: 1.2rem; }
        .stats-cells { grid-template-columns: 1fr 1fr 1fr; }
    }
    </style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="tt-nav">
    <div class="tt-nav-inner">
        <a class="tt-brand" href="{{ route('welcome') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="TopTrade" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
            <span style="display:none">Top<em>Trade</em></span>
        </a>
        <ul class="tt-nav-links">
            <li><a href="{{ route('welcome') }}#how">How It Works</a></li>
            <li><a href="{{ route('welcome') }}#plans">Plans</a></li>
            <li><a href="{{ route('welcome') }}#sources">Revenue</a></li>
            <li><a href="{{ route('welcome') }}#faq">FAQ</a></li>
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
<div class="nav-spacer"></div>

<!-- ── PLAN HEADER ── -->
<div class="plan-header">
    <div class="tt-container plan-header-inner">
        <div class="tt-breadcrumb">
            <a href="{{ route('welcome') }}">Home</a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('welcome') }}#plans">Trading Plans</a>
            <i class="bi bi-chevron-right"></i>
            <span style="color:var(--text-faint)">{{ $package->name }}</span>
        </div>
        <div class="plan-title-row">
            <h1 class="plan-title">{{ $package->name }}</h1>
            <span class="plan-status {{ $package->is_active ? 'active' : 'inactive' }}">
                <span class="plan-status-dot"></span>
                {{ $package->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>

<!-- ── METRICS BAR ── -->
<div class="tt-container">
    <div class="metrics-bar">
        <div class="metric-cell">
            <div class="metric-icon gold"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="metric-lbl">Plan Price</div>
                <div class="metric-val">${{ number_format($package->price, 2) }}</div>
            </div>
        </div>
        <div class="metric-cell">
            <div class="metric-icon green"><i class="bi bi-list-check"></i></div>
            <div>
                <div class="metric-lbl">Sessions / Day</div>
                <div class="metric-val">{{ $package->daily_tasks }}</div>
            </div>
        </div>
        <div class="metric-cell">
            <div class="metric-icon amber"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="metric-lbl">Daily Return</div>
                <div class="metric-val">${{ number_format($package->daily_earning, 2) }}</div>
            </div>
        </div>
        <div class="metric-cell">
            <div class="metric-icon blue"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="metric-lbl">Duration</div>
                <div class="metric-val">{{ $package->duration_days === 0 ? '∞ Unlimited' : $package->duration_days . 'd' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- ── MAIN CONTENT ── -->
<div class="tt-container">
    <div class="plan-grid">

        <!-- ════ LEFT ════ -->
        <div>

            <!-- Overview -->
            <div class="plan-panel">
                <div class="panel-head">
                    <h2 class="panel-title"><i class="bi bi-info-circle"></i> Plan Overview</h2>
                </div>
                <div class="panel-body">
                    <p class="plan-desc">{{ $package->description }}</p>
                </div>
            </div>

            <!-- Earning Potential -->
            <div class="plan-panel">
                <div class="panel-head">
                    <h2 class="panel-title"><i class="bi bi-graph-up-arrow"></i> Return Breakdown</h2>
                </div>
                <div class="panel-body">
                    <div class="earn-grid">
                        <div>
                            <div class="earn-row">
                                <span class="earn-key">Per-session return</span>
                                <span class="earn-val green">${{ number_format($package->per_task_earning, 2) }}</span>
                            </div>
                            <div class="earn-row">
                                <span class="earn-key">Daily ceiling</span>
                                <span class="earn-val gold">${{ number_format($package->daily_earning, 2) }}</span>
                            </div>
                            <div class="earn-row">
                                <span class="earn-key">30-day potential</span>
                                <span class="earn-val blue">${{ number_format($package->daily_earning * 30, 2) }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="earn-row">
                                <span class="earn-key">Total potential</span>
                                <span class="earn-val green">${{ number_format($package->total_earning_potential, 2) }}</span>
                            </div>
                            <div class="earn-row">
                                <span class="earn-key">Activation cost</span>
                                <span class="earn-val red">−${{ number_format($package->price, 2) }}</span>
                            </div>
                            <div class="earn-row">
                                <span class="earn-key">Net profit</span>
                                <span class="earn-val green">${{ number_format($package->total_earning_potential - $package->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="roi-callout">
                        <div class="roi-label">
                            <i class="bi bi-graph-up"></i> Total Return on Investment
                        </div>
                        <div class="roi-val">{{ number_format($package->roi_percentage, 1) }}%</div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            @if($package->features)
            <div class="plan-panel">
                <div class="panel-head">
                    <h2 class="panel-title"><i class="bi bi-star"></i> Plan Features</h2>
                </div>
                <div class="panel-body">
                    <div class="features-grid">
                        @foreach(explode("\n", $package->features) as $feature)
                            @if(trim($feature))
                                <div class="feature-item">
                                    <i class="bi bi-check2-circle"></i>
                                    <span>{{ trim($feature) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- How It Works -->
            <div class="plan-panel">
                <div class="panel-head">
                    <h2 class="panel-title"><i class="bi bi-lightning-charge"></i> How It Works</h2>
                </div>
                <div class="panel-body">
                    <div class="steps-grid">
                        <div class="step-item">
                            <div class="step-num">1</div>
                            <div>
                                <div class="step-title">Activate Plan</div>
                                <p class="step-desc">Fund your account and activate this trading plan with one click</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-num">2</div>
                            <div>
                                <div class="step-title">Expert Desk Trades</div>
                                <p class="step-desc">Our analysts run up to {{ $package->daily_tasks }} daily sessions on your allocation</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-num">3</div>
                            <div>
                                <div class="step-title">Profits Credit Daily</div>
                                <p class="step-desc">Up to ${{ number_format($package->daily_earning, 2) }} credited to your balance each day</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-num">4</div>
                            <div>
                                <div class="step-title">Withdraw Anytime</div>
                                <p class="step-desc">Move profits to your USDT TRC20 wallet with no lock-in</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="plan-panel">
                <div class="panel-head">
                    <h2 class="panel-title"><i class="bi bi-bar-chart"></i> Plan Statistics</h2>
                </div>
                <div class="stats-cells">
                    <div class="stat-cell">
                        <span class="stat-cell-icon" style="color:var(--gold)">👥</span>
                        <span class="stat-cell-val">{{ $package->total_subscribers ?? 0 }}</span>
                        <span class="stat-cell-lbl">Total Investors</span>
                    </div>
                    <div class="stat-cell">
                        <span class="stat-cell-icon" style="color:var(--green)">✓</span>
                        <span class="stat-cell-val">{{ $package->active_subscribers ?? 0 }}</span>
                        <span class="stat-cell-lbl">Active Now</span>
                    </div>
                    <div class="stat-cell">
                        <span class="stat-cell-icon" style="color:var(--amber)">★</span>
                        <span class="stat-cell-val">4.8</span>
                        <span class="stat-cell-lbl">Avg. Rating</span>
                    </div>
                </div>
            </div>

        </div><!-- /left -->

        <!-- ════ RIGHT SIDEBAR ════ -->
        <div>
            <div class="sticky-top" style="top:80px;">

                <!-- Price Card -->
                <div class="price-card">
                    <div class="price-card-inner">
                        <span class="price-eyebrow">One-Time Activation · No Renewal</span>
                        <span class="price-amount">${{ number_format($package->price, 2) }}</span>
                        <span class="price-note">USDT · TRC20 · Instant start</span>

                        @auth
                            <form id="purchase-form-{{ $package->slug }}"
                                  action="{{ route('packages.purchase', $package->slug) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="buy-btn">
                                    <i class="bi bi-lightning-charge-fill"></i> Activate Plan
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="buy-btn login-btn">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In to Activate
                            </a>
                        @endauth

                        <a href="{{ route('welcome') }}#plans" class="back-btn">
                            <i class="bi bi-arrow-left"></i> View All Plans
                        </a>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="side-panel">
                    <div class="side-panel-head">
                        <h3 class="side-panel-title"><i class="bi bi-receipt"></i> Payment Summary</h3>
                    </div>
                    <div class="side-panel-body">
                        <div class="summary-row">
                            <span class="summary-key">Plan price</span>
                            <span class="summary-val">${{ number_format($package->price, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-key">Processing fee</span>
                            <span class="summary-val summary-free">$0.00 — Free</span>
                        </div>
                        <div class="summary-total">
                            <span class="summary-total-key">Total Due</span>
                            <span class="summary-total-val">${{ number_format($package->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- What You Get -->
                <div class="side-panel">
                    <div class="side-panel-head">
                        <h3 class="side-panel-title"><i class="bi bi-gift"></i> What's Included</h3>
                    </div>
                    <div class="side-panel-body">
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>{{ $package->daily_tasks }} daily trading sessions</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>Up to ${{ number_format($package->daily_earning, 2) }} daily return</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>{{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days . '-day' }} plan duration</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>Total potential: ${{ number_format($package->total_earning_potential, 2) }}</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>{{ number_format($package->roi_percentage, 1) }}% ROI</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>Instant activation after payment</span></div>
                        <div class="get-item"><i class="bi bi-check2-circle"></i><span>Zero fees on withdrawals</span></div>
                    </div>
                </div>

                <!-- Trust indicators -->
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;padding:0.25rem 0">
                    <span style="font-family:var(--font-mono);font-size:0.58rem;letter-spacing:0.08em;color:var(--text-faint);border:1px solid var(--border);padding:0.28rem 0.65rem">USDT TRC20</span>
                    <span style="font-family:var(--font-mono);font-size:0.58rem;letter-spacing:0.08em;color:var(--text-faint);border:1px solid var(--border);padding:0.28rem 0.65rem">Zero Fees</span>
                    <span style="font-family:var(--font-mono);font-size:0.58rem;letter-spacing:0.08em;color:var(--text-faint);border:1px solid var(--border);padding:0.28rem 0.65rem">Secure</span>
                </div>

            </div>
        </div><!-- /right -->

    </div><!-- /grid -->

    <!-- ── FAQ ── -->
    <div class="faq-section">
        <h2 class="faq-heading">
            Frequently Asked Questions
            <span class="faq-heading-line"></span>
        </h2>
        <div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">How does this plan work? <i class="bi bi-plus faq-icon"></i></button>
                <div class="faq-answer">After activating, our trading desk runs up to {{ $package->daily_tasks }} sessions per day on your allocated capital. Each session credits your balance automatically — no action required from you. The plan stays active for {{ $package->duration_days === 0 ? 'an unlimited period' : $package->duration_days . ' days' }}.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">Can I activate multiple plans at once? <i class="bi bi-plus faq-icon"></i></button>
                <div class="faq-answer">Yes. You can run multiple different plans simultaneously — each adds its own daily session pool and earning ceiling. You cannot hold two identical plans at once, but combining different plans is fully supported.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">How fast are withdrawals processed? <i class="bi bi-plus faq-icon"></i></button>
                <div class="faq-answer">Withdrawal requests are reviewed by our finance team and typically complete within 1–6 hours. Funds transfer directly to your USDT TRC20 wallet. TopTrade charges zero withdrawal fees.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">What happens when the plan expires? <i class="bi bi-plus faq-icon"></i></button>
                <div class="faq-answer">When the plan duration ends, daily profit credits for that plan stop. Your accumulated balance remains safely in your wallet and can be withdrawn anytime. You can re-activate the same plan or upgrade to a higher tier.</div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">Is there a referral program? <i class="bi bi-plus faq-icon"></i></button>
                <div class="faq-answer">Every registered account gets a unique referral link from day one. When someone signs up through your link and activates any plan, you earn a referral commission instantly. There is no cap on referrals.</div>
            </div>
        </div>
    </div>

</div><!-- /container -->

<!-- ── FOOTER ── -->
<footer class="plan-footer">
    <div class="tt-container">
        <div class="footer-brand">Top<em>Trade</em></div>
        <div class="footer-copy">© {{ date('Y') }} TopTrade — All Rights Reserved.</div>
        <div class="footer-links">
            <a href="{{ route('welcome') }}#how">How It Works</a>
            <a href="{{ route('welcome') }}#plans">Plans</a>
            <a href="{{ route('welcome') }}#faq">FAQ</a>
            <a href="{{ route('welcome') }}#contact">Contact</a>
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('.faq-icon');
    const isOpen = answer.classList.contains('open');
    document.querySelectorAll('.faq-answer.open').forEach(a => {
        a.classList.remove('open');
        a.previousElementSibling.classList.remove('open');
        a.previousElementSibling.querySelector('.faq-icon').classList.replace('bi-dash','bi-plus');
    });
    if (!isOpen) {
        answer.classList.add('open');
        btn.classList.add('open');
        icon.classList.replace('bi-plus','bi-dash');
    }
}
</script>
</body>
</html>
