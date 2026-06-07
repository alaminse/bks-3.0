@extends('layouts.app')
@section('title', 'My Packages')
@section('page-title', 'My Packages')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-collection-fill" style="color:var(--accent);font-size:1.1rem;"></i> My Packages</h1>
        <p>Track your active packages and earnings</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('packages.index') }}" class="cy-hbtn primary">
            <i class="bi bi-plus-circle-fill"></i> Buy New Package
        </a>
    </div>
</div>

{{-- ACTIVE PACKAGES --}}
<div class="mp-sec-lbl">
    Active Packages
    <span class="mp-sec-count">{{ $activePackages->count() }}</span>
</div>

@if($activePackages->count() > 0)
<div class="mp-grid">
    @foreach($activePackages as $i => $up)
    @php
        $taskPct = $up->daily_task_limit > 0 ? min(100, ($up->today_task_count / $up->daily_task_limit) * 100) : 0;
        $earnPct = $up->progress_percentage ?? 0;
    @endphp
    <div class="mp-card">

        {{-- Card Header --}}
        <div class="mp-card-head">
            <div>
                <div class="mp-card-name">{{ $up->package->name }}</div>
                <div class="mp-card-desc">{{ $up->package->description }}</div>
            </div>
            <span class="s-pill approved"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> Active</span>
        </div>

        {{-- Big earned --}}
        <div class="mp-earned-block">
            <div>
                <div class="mp-earned-lbl">Total Earned</div>
                <div class="mp-earned-val">${{ number_format($up->total_earning, 2) }}</div>
                <div class="mp-earned-of">of ${{ number_format($up->package->total_earning_potential, 2) }}</div>
            </div>
            <div class="mp-roi-chip {{ $up->roi_achieved > 0 ? 'pos' : 'neg' }}">
                {{ number_format($up->roi_achieved, 1) }}% ROI
            </div>
        </div>

        {{-- Earn progress --}}
        <div style="padding:0 18px 14px;">
            <div style="display:flex;justify-content:space-between;font-size:0.68rem;color:var(--muted);margin-bottom:5px;">
                <span>Earnings progress</span>
                <span style="color:var(--accent);">{{ number_format($earnPct, 1) }}%</span>
            </div>
            <div class="mp-prog-track">
                <div class="mp-prog-fill" style="width:{{ min($earnPct, 100) }}%"></div>
            </div>
        </div>

        {{-- 4 data cells --}}
        <div class="mp-data-grid">
            <div class="mp-data-cell">
                <div class="mp-data-lbl">Daily Tasks</div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-family:'Syne',sans-serif;font-size:0.82rem;font-weight:700;">{{ $up->today_task_count }}/{{ $up->daily_task_limit }}</span>
                    <div class="mp-prog-track" style="flex:1;height:3px;">
                        <div class="mp-prog-fill" style="width:{{ min($taskPct, 100) }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mp-data-cell">
                <div class="mp-data-lbl">Today Earned</div>
                <div class="mp-data-val" style="color:var(--accent);">${{ number_format($up->today_earning, 2) }}</div>
            </div>
            <div class="mp-data-cell">
                <div class="mp-data-lbl">Days Running</div>
                <div class="mp-data-val">{{ $up->days_remaining }} <span style="font-size:0.65rem;color:var(--muted);">days</span></div>
            </div>
            <div class="mp-data-cell">
                <div class="mp-data-lbl">Valid Until</div>
                <div class="mp-data-val" style="color:var(--green);font-size:0.72rem;">Unlimited</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mp-card-footer">
            <div style="font-size:0.72rem;color:var(--muted);">
                <i class="bi bi-calendar3" style="color:var(--accent);"></i>
                {{ $up->created_at->format('d M Y') }}
            </div>
            <a href="{{ route('tasks.index') }}" class="cy-hbtn primary" style="font-size:0.78rem;padding:7px 14px;">
                <i class="bi bi-play-fill"></i> Start Tasks
            </a>
        </div>

    </div>
    @endforeach
</div>
@else
<div class="empty-state" style="margin-bottom:20px;">
    <i class="bi bi-box-seam"></i>
    <p>No active packages</p>
    <div style="margin-top:12px;">
        <a href="{{ route('packages.index') }}" class="cy-hbtn primary">
            <i class="bi bi-cart-check"></i> Browse Packages
        </a>
    </div>
</div>
@endif

{{-- HISTORY --}}
@if($expiredPackages->count() > 0)
<div class="mp-sec-lbl" style="margin-top:8px;">
    Package History
    <span class="mp-sec-count">{{ $expiredPackages->count() }}</span>
</div>

<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-clock-history"></i> Past Packages</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Purchased</th>
                    <th>Price Paid</th>
                    <th>Total Earned</th>
                    <th>ROI</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiredPackages as $up)
                <tr>
                    <td style="font-weight:700;">{{ $up->package->name }}</td>
                    <td style="font-size:0.78rem;color:var(--muted);">{{ $up->created_at->format('d M Y') }}</td>
                    <td>${{ number_format($up->purchase_price, 2) }}</td>
                    <td><span class="t-reward">${{ number_format($up->total_earning, 2) }}</span></td>
                    <td>
                        <span class="s-pill {{ $up->roi_achieved > 0 ? 'approved' : 'rejected' }}">
                            {{ number_format($up->roi_achieved, 1) }}%
                        </span>
                    </td>
                    <td>
                        @if($up->status === 'completed')
                        <span class="s-pill approved"><i class="bi bi-check-all"></i> Completed</span>
                        @else
                        <span class="s-pill inactive"><i class="bi bi-clock"></i> Expired</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<style>
/* ── MY PACKAGES ── */
.mp-sec-lbl{display:flex;align-items:center;gap:10px;font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:700;margin-bottom:14px}
.mp-sec-count{background:var(--card2);border:1px solid var(--border);border-radius:99px;padding:2px 10px;font-size:0.72rem;color:var(--muted);font-weight:400}

.mp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:12px;margin-bottom:20px}

.mp-card{background:var(--card);border:1px solid var(--border);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;transition:border-color 0.2s,transform 0.2s}
.mp-card:hover{border-color:rgba(0,245,212,0.3);transform:translateY(-2px)}

.mp-card-head{padding:16px 18px;border-bottom:1px solid var(--border);display:flex;align-items:flex-start;justify-content:space-between;gap:10px}
.mp-card-name{font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:800;margin-bottom:3px}
.mp-card-desc{font-size:0.75rem;color:var(--muted)}

.mp-earned-block{padding:18px 18px 10px;display:flex;align-items:flex-end;justify-content:space-between;gap:10px}
.mp-earned-lbl{font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px}
.mp-earned-val{font-family:'Syne',sans-serif;font-size:1.8rem;font-weight:800;color:var(--accent);line-height:1}
.mp-earned-of{font-size:0.68rem;color:var(--muted);margin-top:3px}
.mp-roi-chip{font-family:'Syne',sans-serif;font-size:0.78rem;font-weight:800;padding:5px 10px;border-radius:8px;border:1px solid;white-space:nowrap;flex-shrink:0}
.mp-roi-chip.pos{color:var(--accent);border-color:rgba(0,245,212,0.3);background:rgba(0,245,212,0.08)}
.mp-roi-chip.neg{color:var(--red);border-color:rgba(239,68,68,0.3);background:rgba(239,68,68,0.08)}

.mp-prog-track{background:var(--card2);border:1px solid var(--border);height:5px;border-radius:99px;overflow:hidden}
.mp-prog-fill{height:100%;background:var(--accent);border-radius:99px;transition:width 0.8s}

.mp-data-grid{display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)}
.mp-data-cell{padding:12px 18px;border-right:1px solid var(--border);border-bottom:1px solid var(--border)}
.mp-data-cell:nth-child(2n){border-right:none}
.mp-data-cell:nth-last-child(-n+2){border-bottom:none}
.mp-data-lbl{font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px}
.mp-data-val{font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:700}

.mp-card-footer{margin-top:auto;padding:12px 18px;border-top:1px solid var(--border);background:rgba(0,0,0,0.1);display:flex;align-items:center;justify-content:space-between;gap:10px}

@media(max-width:768px){
    .mp-grid{grid-template-columns:1fr}
    .mp-earned-val{font-size:1.5rem}
}
@media(max-width:480px){
    .mp-card-head{flex-direction:column}
    .mp-data-grid{grid-template-columns:1fr 1fr}
}
</style>
@endpush
