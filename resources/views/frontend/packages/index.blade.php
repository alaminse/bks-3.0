@extends('layouts.app')
@section('title', 'Buy Package')
@section('page-title', 'Packages')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-box-seam-fill" style="color:var(--accent);font-size:1.1rem;"></i> Investment Packages</h1>
        <p>Choose a package and start earning daily rewards</p>
    </div>
    <div class="page-header-actions">
        <div class="pk-wallet-strip">
            <div class="pk-wallet-item">
                <span class="pk-wallet-lbl">Balance</span>
                <span class="pk-wallet-val">${{ number_format($wallet->balance ?? 0, 2) }}</span>
            </div>
            <div class="pk-wallet-sep"></div>
            <div class="pk-wallet-item">
                <span class="pk-wallet-lbl">Available</span>
                <span class="pk-wallet-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</span>
            </div>
            <a href="{{ route('wallet.deposit') }}" class="cy-hbtn primary" style="margin-left:4px;">
                <i class="bi bi-plus-lg"></i> Add Funds
            </a>
        </div>
    </div>
</div>

{{-- ACTIVE PACKAGES BAND --}}
@if($userPackages->count() > 0)
<div class="s-card" style="margin-bottom:20px;">
    <div class="s-card-head">
        <span class="s-card-title">
            <span class="pk-pulse"></span> Running Packages
        </span>
        <a href="{{ route('packages.my') }}" class="cy-hbtn outline" style="font-size:0.72rem;padding:5px 12px;">View All →</a>
    </div>
    <div class="pk-active-scroll">
        @foreach($userPackages as $up)
        @php $pct = $up->daily_task_limit > 0 ? min(100, round(($up->today_task_count / $up->daily_task_limit) * 100)) : 0; @endphp
        <div class="pk-active-item">
            <div class="pk-active-ico"><i class="bi bi-box-seam-fill"></i></div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:0.85rem;margin-bottom:6px;">{{ $up->package->name }}</div>
                <div style="display:flex;gap:16px;margin-bottom:8px;">
                    <div>
                        <div style="font-size:0.65rem;color:var(--muted);">Tasks</div>
                        <div style="font-size:0.82rem;font-weight:700;">{{ $up->today_task_count }}/{{ $up->daily_task_limit }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.65rem;color:var(--muted);">Today</div>
                        <div style="font-size:0.82rem;font-weight:700;color:var(--accent);">${{ number_format($up->today_earning, 2) }}</div>
                    </div>
                </div>
                <div class="pk-prog-track">
                    <div class="pk-prog-fill" style="width:{{ $pct }}%"></div>
                </div>
                <div style="font-size:0.62rem;color:var(--muted);text-align:right;margin-top:2px;">{{ $pct }}% done</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- SECTION LABEL --}}
<div class="pk-sec-lbl">
    <span>Available Packages</span>
    <span style="font-size:0.72rem;color:var(--muted);font-weight:400;">{{ $packages->count() }} plans</span>
</div>

{{-- PACKAGES LIST --}}
@if($packages->count())
<div class="pk-list">
    @foreach($packages as $package)
    @php
        $hasActive = $userPackages->where('package_id', $package->id)->count() > 0;
        $canAfford = $wallet && $wallet->hasSufficientBalance($package->price);
        $isPopular = $loop->iteration === 2;
    @endphp

    <div class="pk-card {{ $isPopular ? 'pk-popular' : '' }}">
        @if($isPopular)
        <div class="pk-popular-badge">★ Most Popular</div>
        @endif

        {{-- LEFT: Price --}}
        <div class="pk-card-price">
            <div style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:6px;">{{ $package->name }}</div>
            <div class="pk-price-big">${{ number_format($package->price, 2) }}</div>
            <div style="font-size:0.68rem;color:var(--muted);margin-top:4px;">one-time</div>
        </div>

        {{-- MIDDLE: Info --}}
        <div class="pk-card-info">
            <div style="margin-bottom:10px;">
                <div style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:800;margin-bottom:4px;">{{ $package->name }} Package</div>
                @if($package->description)
                <div style="font-size:0.82rem;color:var(--muted);line-height:1.55;">{{ $package->description }}</div>
                @endif
            </div>

            <div class="pk-pills">
                <span class="pk-pill cyan"><i class="bi bi-list-check"></i> {{ $package->daily_tasks }} tasks/day</span>
                <span class="pk-pill green"><i class="bi bi-cash-coin"></i> ${{ number_format($package->daily_earning, 2) }}/day</span>
                <span class="pk-pill gold"><i class="bi bi-graph-up-arrow"></i> {{ number_format($package->roi_percentage, 1) }}% ROI</span>
                <span class="pk-pill blue"><i class="bi bi-infinity"></i> {{ $package->duration_days === 0 ? 'Unlimited' : $package->duration_days.'d' }}</span>
            </div>

            <div style="margin-top:10px;">
                <button class="pk-feat-toggle" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#pf-{{ $package->id }}"
                    aria-expanded="false">
                    <i class="bi bi-list-ul"></i> View features
                    <i class="bi bi-chevron-down pk-chevron"></i>
                </button>
                <div class="collapse" id="pf-{{ $package->id }}">
                    <div class="pk-feat-grid">
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> {{ $package->daily_tasks }} tasks per day</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> ${{ number_format($package->per_task_earning, 2) }} per task</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> ${{ number_format($package->daily_earning, 2) }} daily max</div>
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> ${{ number_format($package->total_earning_potential, 2) }} total potential</div>
                        @if($package->features)
                        @foreach(explode("\n", $package->features) as $feat)
                        @if(trim($feat))
                        <div class="pk-feat-row"><i class="bi bi-check-circle-fill"></i> {{ trim($feat) }}</div>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Action --}}
        <div class="pk-card-action">
            @if($hasActive)
            <div class="pk-state active"><i class="bi bi-check-lg"></i> Active</div>
            <a href="{{ route('packages.my') }}" style="font-size:0.72rem;color:var(--muted);text-align:center;display:block;margin-top:6px;">My Packages →</a>
            @elseif(!$canAfford)
            <div class="pk-state low"><i class="bi bi-wallet2"></i> Low Balance</div>
            <a href="{{ route('wallet.deposit') }}" class="cy-hbtn primary w-full" style="margin-top:8px;justify-content:center;font-size:0.75rem;">
                <i class="bi bi-plus-circle"></i> Add Funds
            </a>
            @else
            <form id="pkf-{{ $package->slug }}"
                action="{{ route('packages.purchase', $package->slug) }}"
                method="POST">
                @csrf
                <button type="button" class="pk-buy-btn"
                    onclick="confirmFormSubmit('pkf-{{ $package->slug }}', {
                        title: 'Confirm Purchase',
                        text: 'Purchase {{ $package->name }} for \${{ number_format($package->price, 2) }}?',
                        confirmText: 'Purchase Now',
                        cancelText: 'Cancel'
                    })">
                    <i class="bi bi-cart-check-fill"></i> Buy Now
                </button>
            </form>
            @endif
            <a href="{{ route('packages.show', $package->slug) }}" class="pk-detail-link">
                <i class="bi bi-info-circle"></i> Full Details
            </a>
        </div>

    </div>
    @endforeach
</div>
@else
<div class="empty-state">
    <i class="bi bi-inbox"></i>
    <p>No packages available at this time</p>
</div>
@endif

{{-- HOW IT WORKS --}}
<div class="s-card" style="margin-top:24px;">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-info-circle-fill"></i> How It Works</span>
    </div>
    <div class="pk-hiw-grid">
        @foreach([
            ['icon'=>'bi-cart-check',    'num'=>'01', 'title'=>'Choose Package',   'desc'=>'Select the tier that matches your earning goals and budget'],
            ['icon'=>'bi-wallet2',       'num'=>'02', 'title'=>'Make Payment',     'desc'=>'Pay instantly from your wallet balance — zero fees'],
            ['icon'=>'bi-list-check',    'num'=>'03', 'title'=>'Complete Tasks',   'desc'=>'Do your daily task quota every 24 hours without fail'],
            ['icon'=>'bi-cash-coin',     'num'=>'04', 'title'=>'Earn & Withdraw',  'desc'=>'Watch your balance grow — withdraw anytime you want'],
        ] as $s)
        <div class="pk-hiw-step">
            <div class="pk-hiw-num">{{ $s['num'] }}</div>
            <i class="bi {{ $s['icon'] }}" style="font-size:1.5rem;color:var(--accent);opacity:0.5;margin-bottom:8px;display:block;"></i>
            <div style="font-weight:700;font-size:0.85rem;margin-bottom:4px;">{{ $s['title'] }}</div>
            <div style="font-size:0.78rem;color:var(--muted);line-height:1.55;">{{ $s['desc'] }}</div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── PACKAGES INDEX ── */
.pk-wallet-strip {
    display:flex;align-items:center;gap:12px;
    background:var(--card);border:1px solid var(--border);border-radius:10px;padding:8px 14px;
}
.pk-wallet-item{text-align:center}
.pk-wallet-lbl{font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);display:block;margin-bottom:2px}
.pk-wallet-val{font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:800;color:var(--accent);display:block}
.pk-wallet-sep{width:1px;height:28px;background:var(--border);flex-shrink:0}
.pk-pulse{width:8px;height:8px;border-radius:50%;background:var(--accent);box-shadow:0 0 6px var(--accent);animation:pulse-blink 1.5s infinite;display:inline-block;margin-right:6px}
@keyframes pulse-blink{0%,100%{opacity:1}50%{opacity:.25}}

/* Active scroll */
.pk-active-scroll{display:flex;gap:0;overflow-x:auto;scrollbar-width:none}
.pk-active-scroll::-webkit-scrollbar{display:none}
.pk-active-item{display:flex;align-items:flex-start;gap:12px;padding:16px 20px;border-right:1px solid var(--border);min-width:260px;flex-shrink:0}
.pk-active-item:last-child{border-right:none}
.pk-active-ico{width:36px;height:36px;border-radius:10px;background:rgba(0,245,212,0.1);border:1px solid rgba(0,245,212,0.2);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.pk-prog-track{background:var(--card2);border:1px solid var(--border);height:4px;border-radius:99px;overflow:hidden}
.pk-prog-fill{height:100%;background:var(--accent);border-radius:99px;transition:width 0.8s}

/* Section label */
.pk-sec-lbl{display:flex;align-items:center;justify-content:space-between;font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:700;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--border)}

/* Package list */
.pk-list{display:flex;flex-direction:column;gap:10px;margin-bottom:10px}

/* Package card */
.pk-card{
    background:var(--card);border:1px solid var(--border);border-radius:14px;
    display:grid;grid-template-columns:160px 1fr 180px;
    overflow:hidden;position:relative;
    transition:border-color 0.2s,transform 0.2s;
}
.pk-card:hover{border-color:var(--border2);transform:translateY(-2px)}
.pk-popular{border-color:rgba(0,245,212,0.3) !important}
.pk-popular-badge{
    position:absolute;top:14px;right:-22px;
    background:var(--accent);color:#000;
    font-size:0.6rem;font-weight:700;padding:3px 26px;
    transform:rotate(45deg);z-index:10;
}

/* Left price col */
.pk-card-price{
    background:var(--card2);border-right:1px solid var(--border);
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:20px 14px;text-align:center;
}
.pk-price-big{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;color:var(--text);line-height:1}

/* Middle info */
.pk-card-info{padding:18px 20px;display:flex;flex-direction:column;justify-content:center;gap:0}

/* Pills */
.pk-pills{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:0}
.pk-pill{display:inline-flex;align-items:center;gap:4px;font-size:0.68rem;font-weight:600;padding:3px 9px;border-radius:99px;border:1px solid;white-space:nowrap}
.pk-pill.cyan{color:var(--accent);border-color:rgba(0,245,212,0.3);background:rgba(0,245,212,0.07)}
.pk-pill.green{color:var(--green);border-color:rgba(34,197,94,0.3);background:rgba(34,197,94,0.07)}
.pk-pill.gold{color:var(--gold);border-color:rgba(245,158,11,0.3);background:rgba(245,158,11,0.07)}
.pk-pill.blue{color:var(--blue);border-color:rgba(59,130,246,0.3);background:rgba(59,130,246,0.07)}

/* Feature toggle */
.pk-feat-toggle{display:inline-flex;align-items:center;gap:6px;background:none;border:1px solid var(--border);border-radius:7px;padding:5px 10px;color:var(--muted);cursor:pointer;font-size:0.75rem;margin-top:10px;transition:all 0.2s}
.pk-feat-toggle:hover{border-color:var(--accent);color:var(--accent)}
.pk-chevron{font-size:0.6rem;transition:transform 0.2s}
.pk-feat-toggle[aria-expanded="true"] .pk-chevron{transform:rotate(180deg)}
.pk-feat-grid{display:grid;grid-template-columns:1fr 1fr;gap:4px;margin-top:8px}
.pk-feat-row{display:flex;align-items:flex-start;gap:5px;font-size:0.78rem;color:var(--muted)}
.pk-feat-row i{color:var(--accent);font-size:0.68rem;margin-top:2px;flex-shrink:0}

/* Right action */
.pk-card-action{border-left:1px solid var(--border);padding:18px;display:flex;flex-direction:column;align-items:stretch;justify-content:center;gap:8px}
.pk-state{display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border-radius:9px;font-size:0.78rem;font-weight:700;border:1px solid}
.pk-state.active{background:rgba(0,245,212,0.08);color:var(--accent);border-color:rgba(0,245,212,0.25)}
.pk-state.low{background:rgba(239,68,68,0.08);color:var(--red);border-color:rgba(239,68,68,0.25)}
.pk-buy-btn{width:100%;padding:11px;background:var(--accent);color:#000;border:none;border-radius:9px;font-size:0.82rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:opacity 0.2s}
.pk-buy-btn:hover{opacity:0.9}
.pk-detail-link{font-size:0.72rem;color:var(--muted);text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:4px;transition:color 0.2s}
.pk-detail-link:hover{color:var(--accent)}
.w-full{width:100%}

/* How it works */
.pk-hiw-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.pk-hiw-step{padding:20px 18px;text-align:center;border-right:1px solid var(--border)}
.pk-hiw-step:last-child{border-right:none}
.pk-hiw-num{width:32px;height:32px;border-radius:50%;background:var(--card2);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:0.72rem;font-weight:800;color:var(--accent);margin:0 auto 10px}

/* RESPONSIVE */
@media(max-width:900px){
    .pk-card{grid-template-columns:1fr}
    .pk-card-price{border-right:none;border-bottom:1px solid var(--border);flex-direction:row;justify-content:flex-start;gap:16px;padding:14px 18px;align-items:center}
    .pk-card-action{border-left:none;border-top:1px solid var(--border);flex-direction:row;flex-wrap:wrap}
    .pk-buy-btn{flex:1}
    .pk-hiw-grid{grid-template-columns:1fr 1fr}
    .pk-hiw-step:nth-child(2){border-right:none}
    .pk-hiw-step:nth-child(-n+2){border-bottom:1px solid var(--border)}
    .page-header-bar{flex-direction:column;align-items:flex-start}
    .pk-wallet-strip{width:100%}
}
@media(max-width:600px){
    .pk-feat-grid{grid-template-columns:1fr}
    .pk-hiw-grid{grid-template-columns:1fr 1fr}
    .pk-wallet-strip{flex-wrap:wrap;gap:8px}
}
@media(max-width:480px){
    .pk-price-big{font-size:1.4rem}
    .pk-hiw-grid{grid-template-columns:1fr}
    .pk-hiw-step{border-right:none;border-bottom:1px solid var(--border)}
    .pk-hiw-step:last-child{border-bottom:none}
}
</style>
@endpush
