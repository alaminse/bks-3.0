@extends('layouts.app')
@section('title', $package->name . ' — Package Details')
@section('page-title', 'Package Details')

@section('content')

{{-- BREADCRUMB + HEADER --}}
<div class="page-header-bar">
    <div>
        <div style="font-size:0.72rem;color:var(--muted);margin-bottom:4px;">
            <a href="{{ route('packages.index') }}" style="color:var(--muted);text-decoration:none;">Packages</a>
            <i class="bi bi-chevron-right" style="font-size:0.6rem;margin:0 4px;"></i>
            {{ $package->name }}
        </div>
        <h1><i class="bi bi-box-seam-fill" style="color:var(--accent);font-size:1.1rem;"></i> {{ $package->name }}</h1>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('packages.index') }}" class="cy-hbtn outline">
            <i class="bi bi-arrow-left"></i> All Packages
        </a>
    </div>
</div>

{{-- 2 COL LAYOUT --}}
<div class="ps-layout">

    {{-- LEFT --}}
    <div>

        {{-- OVERVIEW --}}
        <div class="s-card ps-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-box-seam-fill"></i> Package Overview</span>
                @if($package->is_active)
                <span class="s-pill approved"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> Active</span>
                @else
                <span class="s-pill inactive">Inactive</span>
                @endif
            </div>

            {{-- Description --}}
            <div style="padding:16px 20px;font-size:0.88rem;color:var(--muted);line-height:1.65;border-bottom:1px solid var(--border);">
                {{ $package->description }}
            </div>

            {{-- 4 stat tiles --}}
            <div class="ps-stat-strip">
                <div class="ps-stat-tile">
                    <i class="bi bi-currency-dollar" style="font-size:1.2rem;color:var(--accent);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val">${{ number_format($package->price, 2) }}</div>
                    <div class="ps-tile-lbl">Package Price</div>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-list-check" style="font-size:1.2rem;color:var(--accent);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val">{{ $package->daily_tasks }}</div>
                    <div class="ps-tile-lbl">Tasks / Day</div>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-cash-coin" style="font-size:1.2rem;color:var(--accent);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val">${{ number_format($package->daily_earning, 2) }}</div>
                    <div class="ps-tile-lbl">Daily Earning</div>
                </div>
                <div class="ps-stat-tile" style="border-right:none;">
                    <i class="bi bi-infinity" style="font-size:1.2rem;color:var(--accent);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val" style="font-size:0.9rem;">{{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d' }}</div>
                    <div class="ps-tile-lbl">Validity</div>
                </div>
            </div>

            {{-- Earning breakdown --}}
            <div class="ps-earn-grid">
                <div class="ps-earn-row">
                    <span style="font-size:0.82rem;color:var(--muted);">Per task earning</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);">${{ number_format($package->per_task_earning, 2) }}</span>
                </div>
                <div class="ps-earn-row">
                    <span style="font-size:0.82rem;color:var(--muted);">Daily maximum</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--accent);">${{ number_format($package->daily_earning, 2) }}</span>
                </div>
                <div class="ps-earn-row">
                    <span style="font-size:0.82rem;color:var(--muted);">Monthly potential</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--blue);">${{ number_format($package->daily_earning * 30, 2) }}</span>
                </div>
                <div class="ps-earn-row">
                    <span style="font-size:0.82rem;color:var(--muted);">Total potential</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;color:var(--green);">${{ number_format($package->total_earning_potential, 2) }}</span>
                </div>
            </div>

            {{-- ROI Banner --}}
            <div class="ps-roi-banner">
                <span style="font-size:0.82rem;color:var(--muted);display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-graph-up-arrow" style="color:var(--accent);"></i> Return on Investment (ROI)
                </span>
                <span style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);">{{ number_format($package->roi_percentage, 1) }}%</span>
            </div>
        </div>

        {{-- FEATURES --}}
        @if($package->features)
        <div class="s-card ps-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-stars"></i> Package Features</span>
            </div>
            <div class="ps-feat-grid">
                @foreach(explode("\n", $package->features) as $feat)
                @if(trim($feat))
                <div class="ps-feat-row">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ trim($feat) }}
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- HOW IT WORKS --}}
        <div class="s-card ps-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-info-circle-fill"></i> How It Works</span>
            </div>
            <div class="ps-steps-grid">
                @foreach([
                    ['n'=>'01','title'=>'Purchase Package',  'desc'=>'Buy with your wallet balance — instant activation'],
                    ['n'=>'02','title'=>'Complete Tasks',    'desc'=>"Up to {$package->daily_tasks} tasks per day"],
                    ['n'=>'03','title'=>'Earn Daily',        'desc'=>'Up to $'.number_format($package->daily_earning,2).' per day'],
                    ['n'=>'04','title'=>'Withdraw Anytime',  'desc'=>'No lock-in — withdraw whenever you want'],
                ] as $s)
                <div class="ps-step">
                    <div class="ps-step-num">{{ $s['n'] }}</div>
                    <div>
                        <div style="font-weight:700;font-size:0.85rem;margin-bottom:3px;">{{ $s['title'] }}</div>
                        <div style="font-size:0.78rem;color:var(--muted);line-height:1.55;">{{ $s['desc'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- STATISTICS --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-bar-chart-fill"></i> Package Statistics</span>
            </div>
            <div class="ps-stat-strip">
                <div class="ps-stat-tile">
                    <i class="bi bi-people-fill" style="font-size:1.2rem;color:var(--accent);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val">{{ $package->total_subscribers }}</div>
                    <div class="ps-tile-lbl">Total Subscribers</div>
                </div>
                <div class="ps-stat-tile">
                    <i class="bi bi-check-circle-fill" style="font-size:1.2rem;color:var(--green);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val" style="color:var(--green);">{{ $package->active_subscribers }}</div>
                    <div class="ps-tile-lbl">Active Now</div>
                </div>
                <div class="ps-stat-tile" style="border-right:none;">
                    <i class="bi bi-star-fill" style="font-size:1.2rem;color:var(--gold);opacity:0.5;display:block;margin-bottom:6px;"></i>
                    <div class="ps-tile-val" style="color:var(--gold);">4.8</div>
                    <div class="ps-tile-lbl">User Rating</div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="ps-sidebar">

        {{-- WALLET --}}
        <div class="s-card">
            <div style="padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                <div>
                    <div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Your Wallet</div>
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);line-height:1;">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                    <div style="font-size:0.68rem;color:var(--muted);margin-top:3px;">Available: ${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                </div>
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(0,245,212,0.1);border:1px solid rgba(0,245,212,0.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:var(--accent);">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>

        {{-- PURCHASE --}}
        @php
            $canAfford = $wallet && $wallet->hasSufficientBalance($package->price);
            $shortage  = !$canAfford ? $package->price - ($wallet->available_balance ?? 0) : 0;
        @endphp
        <div class="s-card">
            {{-- Price --}}
            <div style="padding:20px;text-align:center;border-bottom:1px solid var(--border);">
                <div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:6px;">Package Price</div>
                <div style="font-family:'Syne',sans-serif;font-size:2.2rem;font-weight:800;color:var(--text);line-height:1;">${{ number_format($package->price, 2) }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:4px;">One-time payment</div>
            </div>

            @if($hasActivePackage)
            <div style="padding:14px 18px;">
                <div style="background:rgba(0,245,212,0.07);border:1px solid rgba(0,245,212,0.2);border-radius:10px;padding:12px 14px;display:flex;gap:10px;font-size:0.82rem;color:var(--muted);margin-bottom:14px;">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent);flex-shrink:0;margin-top:2px;"></i>
                    <span>You already have an active subscription to this package.</span>
                </div>
                <a href="{{ route('packages.my') }}" class="cy-hbtn outline w-full" style="justify-content:center;">
                    <i class="bi bi-box-seam"></i> View My Packages
                </a>
            </div>
            @elseif(!$canAfford)
            <div style="padding:14px 18px;">
                <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.2);border-left:3px solid var(--red);border-radius:10px;padding:12px 14px;display:flex;gap:10px;font-size:0.82rem;color:var(--muted);margin-bottom:14px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:var(--red);flex-shrink:0;margin-top:2px;"></i>
                    <span>You need <strong style="color:var(--text);">${{ number_format($shortage, 2) }}</strong> more to purchase this package.</span>
                </div>
                <a href="{{ route('wallet.deposit') }}" class="cy-hbtn primary w-full" style="justify-content:center;margin-bottom:8px;">
                    <i class="bi bi-plus-circle-fill"></i> Deposit Funds
                </a>
                <a href="{{ route('packages.index') }}" class="cy-hbtn outline w-full" style="justify-content:center;">
                    <i class="bi bi-arrow-left"></i> Back to Packages
                </a>
            </div>
            @else
            <div style="padding:14px 18px;">
                <form id="purchase-form-{{ $package->slug }}"
                    action="{{ route('packages.purchase', $package->slug) }}"
                    method="POST">
                    @csrf
                    <button type="button"
                        class="cy-hbtn primary w-full"
                        style="justify-content:center;padding:12px;font-size:0.9rem;margin-bottom:8px;"
                        onclick="confirmFormSubmit('purchase-form-{{ $package->slug }}', {
                            title: 'Confirm Purchase?',
                            text: 'Purchase {{ $package->name }} for \${{ number_format($package->price, 2) }}?',
                            confirmText: 'Yes, Purchase',
                            cancelText: 'Cancel'
                        })">
                        <i class="bi bi-cart-check-fill"></i> Purchase Now
                    </button>
                </form>
                <a href="{{ route('packages.index') }}" class="cy-hbtn outline w-full" style="justify-content:center;">
                    <i class="bi bi-arrow-left"></i> Back to Packages
                </a>
            </div>
            @endif

            {{-- Payment summary --}}
            <div style="border-top:1px solid var(--border);">
                <div class="ps-sum-row">
                    <span>Package price</span>
                    <span>${{ number_format($package->price, 2) }}</span>
                </div>
                <div class="ps-sum-row">
                    <span>Processing fee</span>
                    <span style="color:var(--accent);">Free</span>
                </div>
                <div class="ps-sum-row ps-sum-total">
                    <span>Total amount</span>
                    <span style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:800;color:var(--accent);">${{ number_format($package->price, 2) }}</span>
                </div>
            </div>

            {{-- What you get --}}
            <div style="border-top:1px solid var(--border);">
                <div style="padding:10px 18px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);display:flex;align-items:center;gap:6px;border-bottom:1px solid var(--border);">
                    <i class="bi bi-gift-fill" style="color:var(--accent);"></i> What You Get
                </div>
                @foreach([
                    $package->daily_tasks.' daily tasks',
                    'Up to $'.number_format($package->daily_earning,2).' per day',
                    ($package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d').' access',
                    'Total potential: $'.number_format($package->total_earning_potential,2),
                    'ROI: '.number_format($package->roi_percentage,1).'%',
                    'Instant activation',
                ] as $benefit)
                <div style="display:flex;align-items:center;gap:8px;padding:9px 18px;border-bottom:1px solid var(--border);font-size:0.82rem;color:var(--muted);">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent);font-size:0.72rem;flex-shrink:0;"></i>
                    {{ $benefit }}
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- FAQ --}}
<div class="s-card" style="margin-top:8px;">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-question-circle-fill"></i> Frequently Asked Questions</span>
    </div>
    <div class="accordion" id="faqAccordion">
        @foreach([
            ['id'=>'faq1','q'=>'How does this package work?',           'a'=>"After purchasing, complete up to {$package->daily_tasks} tasks per day and earn up to \${$package->daily_earning} daily. The package stays active for ".($package->duration_days === 0 ? 'unlimited time' : $package->duration_days.' days').".", 'open'=>true],
            ['id'=>'faq2','q'=>'Can I buy multiple packages at once?',  'a'=>'Yes! You can hold multiple different packages simultaneously. You can only have one active subscription of the same package at a time.', 'open'=>false],
            ['id'=>'faq3','q'=>'When can I withdraw my earnings?',      'a'=>'You can withdraw anytime after completing tasks — no lock-in period. KYC verification is required before your first withdrawal.', 'open'=>false],
            ['id'=>'faq4','q'=>'What happens when the package expires?','a'=>'When the package expires, task access stops. All your earnings are yours to keep and withdraw. You can immediately repurchase the same package or choose a different tier.', 'open'=>false],
        ] as $faq)
        <div class="accordion-item" style="background:transparent !important;border:none !important;border-bottom:1px solid var(--border) !important;border-radius:0 !important;margin:0 !important;">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $faq['open'] ? '' : 'collapsed' }}"
                    type="button" data-bs-toggle="collapse" data-bs-target="#{{ $faq['id'] }}"
                    style="background:var(--card) !important;color:var(--text) !important;font-family:'DM Sans',sans-serif !important;font-size:0.88rem !important;font-weight:600 !important;padding:14px 20px !important;border:none !important;box-shadow:none !important;">
                    {{ $faq['q'] }}
                </button>
            </h2>
            <div id="{{ $faq['id'] }}" class="accordion-collapse collapse {{ $faq['open'] ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body" style="background:var(--card2) !important;padding:14px 20px !important;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:0.85rem;line-height:1.65;">
                    {{ $faq['a'] }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── PACKAGE SHOW ── */
.ps-layout{display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start;margin-bottom:16px}
.ps-sidebar{display:flex;flex-direction:column;gap:12px}
.ps-mb{margin-bottom:12px}
.w-full{width:100%;justify-content:center}

/* Stat strip */
.ps-stat-strip{display:grid;grid-template-columns:repeat(4,1fr)}
.ps-stat-tile{padding:16px 12px;text-align:center;border-right:1px solid var(--border);transition:background 0.2s}
.ps-stat-tile:hover{background:var(--card2)}
.ps-tile-val{font-family:'Syne',sans-serif;font-size:1rem;font-weight:800;color:var(--accent);display:block;margin-bottom:4px}
.ps-tile-lbl{font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted)}

/* Earn grid */
.ps-earn-grid{display:grid;grid-template-columns:1fr 1fr}
.ps-earn-row{display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-right:1px solid var(--border);border-bottom:1px solid var(--border)}
.ps-earn-row:nth-child(2n){border-right:none}
.ps-earn-row:nth-last-child(-n+2){border-bottom:none}

/* ROI Banner */
.ps-roi-banner{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:rgba(0,245,212,0.04);border-top:1px solid rgba(0,245,212,0.15)}

/* Features */
.ps-feat-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:16px 18px}
.ps-feat-row{display:flex;align-items:flex-start;gap:8px;background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:10px 12px;font-size:0.82rem;color:var(--muted)}
.ps-feat-row i{color:var(--accent);font-size:0.72rem;flex-shrink:0;margin-top:3px}

/* Steps */
.ps-steps-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:16px 18px}
.ps-step{display:flex;align-items:flex-start;gap:10px;background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:12px}
.ps-step-num{width:28px;height:28px;border-radius:8px;background:var(--accent);color:#000;display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:0.68rem;font-weight:800;flex-shrink:0}

/* Payment summary */
.ps-sum-row{display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid var(--border);font-size:0.82rem;color:var(--muted)}
.ps-sum-total{background:rgba(0,245,212,0.04);border-bottom:none}

/* RESPONSIVE */
@media(max-width:1024px){.ps-layout{grid-template-columns:1fr}}
@media(max-width:768px){
    .ps-stat-strip{grid-template-columns:1fr 1fr}
    .ps-stat-tile:nth-child(2n){border-right:none}
    .ps-stat-tile:nth-child(-n+2){border-bottom:1px solid var(--border)}
    .ps-stat-tile:last-child{border-right:none !important}
    .ps-earn-grid{grid-template-columns:1fr}
    .ps-earn-row{border-right:none}
    .ps-earn-row:last-child{border-bottom:none}
    .ps-feat-grid{grid-template-columns:1fr}
    .ps-steps-grid{grid-template-columns:1fr}
}
@media(max-width:480px){
    .ps-stat-strip{grid-template-columns:1fr 1fr}
}
</style>
@endpush
