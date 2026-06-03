@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- WELCOME BAR --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <span class="pulse"></span>
            <h2 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;margin:0;">
                Welcome back, {{ Auth::user()->name }} 👋
            </h2>
        </div>
        <p style="color:var(--muted);font-size:0.82rem;margin:0;">
            {{ now()->format('l, d M Y') }} — here's your earning summary
        </p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('wallet.transactions') }}" class="cy-hbtn outline">
            <i class="bi bi-download"></i> Export
        </a>
        <a href="{{ route('tasks.index') }}" class="cy-hbtn primary">
            <i class="bi bi-lightning-charge-fill"></i> Run Tasks
        </a>
    </div>
</div>

{{-- STATS ROW --}}
<div class="stats-row" style="margin-bottom:24px;">

    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="stat-card-lbl">Wallet Balance</div>
        <div class="stat-card-val">${{ number_format($walletStats['balance'], 2) }}</div>
        <div class="stat-card-sub">
            <span class="stat-card-badge badge-up">
                <i class="bi bi-arrow-up"></i> Available
            </span>
            ${{ number_format($walletStats['available'], 2) }}
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="stat-card-lbl">Total Earnings</div>
        <div class="stat-card-val" style="color:var(--green);">${{ number_format($totalEarnings, 2) }}</div>
        <div class="stat-card-sub">
            <span class="stat-card-badge badge-up">
                <i class="bi bi-check2-circle"></i> All time
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="stat-card-lbl">Today Earned</div>
        <div class="stat-card-val" style="color:var(--gold);">${{ number_format($todayStats['earned'], 2) }}</div>
        <div class="stat-card-sub">
            <span class="stat-card-badge badge-neu">
                {{ $todayStats['tasks_completed'] }} tasks done
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-card-lbl">Active Packages</div>
        <div class="stat-card-val" style="color:var(--blue);">{{ $activePackages->count() }}</div>
        <div class="stat-card-sub">
            @if($activePackages->count() > 0)
            <span class="stat-card-badge badge-up">Running</span>
            @else
            <a href="{{ route('packages.index') }}" style="color:var(--accent);font-size:0.75rem;">Buy Package →</a>
            @endif
        </div>
    </div>

</div>

{{-- MAIN GRID --}}
<div class="dash-grid">

    {{-- ════ LEFT ════ --}}
    <div class="dash-left">

        {{-- INCOME / OUTCOME --}}
        <div class="io-row">
            <div class="io-card income">
                <div class="io-head">
                    <div class="io-icon"><i class="bi bi-arrow-down-left"></i></div>
                    <div class="io-label">Total Income</div>
                </div>
                <div class="io-amount">+${{ number_format($totalEarnings, 2) }}</div>
                <span class="s-pill approved" style="font-size:0.65rem;">All Time</span>
            </div>
            <div class="io-card outcome">
                <div class="io-head">
                    <div class="io-icon"><i class="bi bi-arrow-up-right"></i></div>
                    <div class="io-label">Locked / Pending</div>
                </div>
                <div class="io-amount">-${{ number_format($walletStats['locked'] ?? 0, 2) }}</div>
                <span class="s-pill pending" style="font-size:0.65rem;">Pending</span>
            </div>
        </div>

        {{-- CHART --}}
        <div class="s-card" style="margin-bottom:20px;">
            <div class="s-card-head">
                <span class="s-card-title">
                    <i class="bi bi-bar-chart-line"></i> Earnings — Last 7 Days
                </span>
                <span style="font-size:0.72rem;color:var(--muted);">{{ now()->format('M Y') }}</span>
            </div>
            <div style="padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
                    <div>
                        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:3px;">Balance</div>
                        <div style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;color:var(--accent);">
                            ${{ number_format($walletStats['balance'], 2) }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:0.72rem;color:var(--muted);margin-bottom:4px;">Today</div>
                        <div style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;color:var(--green);">
                            +${{ number_format($todayStats['earned'], 2) }}
                        </div>
                        <span class="s-pill approved" style="font-size:0.6rem;">{{ $todayStats['tasks_completed'] }} tasks</span>
                    </div>
                </div>
                <div style="position:relative;height:180px;">
                    <canvas id="earningsChart"></canvas>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--border);margin-top:16px;">
                    <div style="padding:12px 16px;border-right:1px solid var(--border);">
                        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Today</div>
                        <div style="font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:700;">${{ number_format($todayStats['earned'], 2) }}</div>
                    </div>
                    <div style="padding:12px 16px;border-right:1px solid var(--border);">
                        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Tasks</div>
                        <div style="font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:700;">{{ $todayStats['tasks_completed'] }}</div>
                    </div>
                    <div style="padding:12px 16px;">
                        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Available</div>
                        <div style="font-family:'Syne',sans-serif;font-size:0.9rem;font-weight:700;">${{ number_format($walletStats['available'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTIVE PACKAGES --}}
        @if($activePackages->count() > 0)
        <div class="s-card" style="margin-bottom:20px;">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-box-seam"></i> Active Packages</span>
                <a href="{{ route('packages.my') }}" class="s-view-all">View All →</a>
            </div>
            <div style="padding:12px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;">
                    @foreach($activePackages as $up)
                    <div style="background:var(--card2);border:1px solid var(--border);border-radius:12px;padding:16px;position:relative;overflow:hidden;">
                        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--accent),transparent);"></div>
                        <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:4px;">{{ $up->package->name }}</div>
                        <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;color:var(--accent);margin-bottom:8px;">
                            ${{ number_format($up->daily_earning_limit, 2) }}<span style="font-size:0.65rem;font-weight:400;color:var(--muted);">/day</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:var(--muted);margin-bottom:10px;">
                            <span>{{ $up->daily_task_limit }} tasks/day</span>
                            <span style="color:{{ $up->valid_until && $up->valid_until->diffInDays() < 7 ? 'var(--red)' : 'var(--muted)' }}">
                                @if($up->valid_until)
                                    Expires {{ $up->valid_until->diffForHumans() }}
                                @else
                                    Unlimited
                                @endif
                            </span>
                        </div>
                        <div style="height:4px;background:var(--border);border-radius:99px;overflow:hidden;">
                            @php $epct = $up->daily_earning_limit > 0 ? min(100, ($up->total_earning / ($up->daily_earning_limit * 30)) * 100) : 0; @endphp
                            <div style="height:100%;width:{{ $epct }}%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- RECENT ACTIVITIES --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-activity"></i> Recent Activities</span>
                <a href="{{ route('tasks.history') }}" class="s-view-all">View All →</a>
            </div>
            <div class="table-responsive">
                <table class="act-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Package</th>
                            <th>Reward</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $act)
                        <tr>
                            <td>
                                <span class="t-task">{{ Str::limit($act->task->title, 30) }}</span>
                            </td>
                            <td>
                                <span class="s-pill info">{{ $act->userPackage->package->name }}</span>
                            </td>
                            <td>
                                <span class="t-reward">+${{ number_format($act->reward_amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="s-pill {{ $act->status }}">{{ ucfirst($act->status) }}</span>
                            </td>
                            <td style="font-size:0.72rem;color:var(--muted);">
                                {{ $act->submitted_at->format('M d, h:i A') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No activities yet. <a href="{{ route('tasks.index') }}" style="color:var(--accent);">Start a task →</a></p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /left --}}

    {{-- ════ RIGHT ════ --}}
    <div class="dash-right">

        {{-- WALLET CARD --}}
        <div class="wallet-card">
            <div class="wc-head">
                <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.88rem;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-wallet2" style="color:var(--accent);"></i> My Wallet
                </span>
            </div>
            <div class="cy-card">
                <div class="ccy-inner">
                    <div class="ccy-top">
                        <span class="ccy-logo">TopTrade</span>
                        <span class="ccy-dots">·· USDT</span>
                    </div>
                    <div class="ccy-bal-lbl">Balance</div>
                    <div class="ccy-bal-val">${{ number_format($walletStats['balance'], 2) }}</div>
                    <div class="ccy-bottom">
                        <div class="ccy-user">{{ Auth::user()->name }}</div>
                        <div class="ccy-avail">
                            <div class="ccy-avail-lbl">Available</div>
                            <div class="ccy-avail-val">${{ number_format($walletStats['available'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wc-actions">
                <a href="{{ route('wallet.deposit') }}"  class="wca-btn"><i class="bi bi-plus-circle"></i><span>Deposit</span></a>
                <a href="{{ route('withdraw.index') }}"  class="wca-btn"><i class="bi bi-send"></i><span>Withdraw</span></a>
                <a href="{{ route('wallet.index') }}"    class="wca-btn"><i class="bi bi-eye"></i><span>Details</span></a>
            </div>
        </div>

        {{-- DAILY PROGRESS --}}
        @php
            $total = $todayStats['tasks_total'] ?? ($activePackages->sum('daily_task_limit') ?: 0);
            $done  = $todayStats['tasks_completed'] ?? 0;
            $pct   = $total > 0 ? min(100, round(($done / $total) * 100)) : 0;
        @endphp
        <div class="s-card" style="margin-bottom:20px;">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-clipboard-check"></i> Daily Tasks</span>
                <a href="{{ route('tasks.index') }}" class="s-view-all">Go →</a>
            </div>
            <div style="padding:16px 20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="font-size:0.82rem;color:var(--muted);">Progress</span>
                    <span style="font-family:'Syne',sans-serif;font-size:0.85rem;font-weight:700;color:var(--accent);">
                        {{ $done }} / {{ $total ?: '—' }}
                    </span>
                </div>
                <div style="background:var(--card2);border-radius:99px;height:8px;overflow:hidden;margin-bottom:8px;">
                    <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px;transition:width 1s;"></div>
                </div>
                <div style="font-size:0.72rem;color:var(--muted);">
                    @if($pct >= 100)
                        <span style="color:var(--green);">✓ All tasks complete for today!</span>
                    @elseif($pct > 0)
                        {{ $pct }}% complete — keep going!
                    @else
                        No tasks completed yet today
                    @endif
                </div>
                @if($done < $total && $total > 0)
                <a href="{{ route('tasks.index') }}" class="cy-hbtn primary" style="width:100%;justify-content:center;margin-top:12px;">
                    <i class="bi bi-lightning-charge-fill"></i> Complete Tasks
                </a>
                @endif
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="s-card" style="margin-bottom:20px;">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-grid"></i> Quick Actions</span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:14px;">
                <a href="{{ route('tasks.index') }}" class="qa-btn primary">
                    <i class="bi bi-list-check"></i> Tasks
                </a>
                <a href="{{ route('packages.index') }}" class="qa-btn">
                    <i class="bi bi-box-seam"></i> Packages
                </a>
                <a href="{{ route('referrals.index') }}" class="qa-btn">
                    <i class="bi bi-people"></i> Referrals
                </a>
                <a href="{{ route('profile.index') }}" class="qa-btn">
                    <i class="bi bi-person"></i> Profile
                </a>
            </div>
        </div>

        {{-- RECENT TRANSACTIONS --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-arrow-left-right"></i> Transactions</span>
                <a href="{{ route('wallet.transactions') }}" class="s-view-all">All →</a>
            </div>
            @forelse($recentTransactions as $txn)
            <div class="txn-row">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="txn-ico {{ $txn->direction === 'credit' ? 'cr' : 'db' }}">
                        @if($txn->type === 'task')         <i class="bi bi-check-circle"></i>
                        @elseif($txn->type === 'deposit')  <i class="bi bi-arrow-down-circle"></i>
                        @elseif($txn->type === 'withdraw') <i class="bi bi-arrow-up-circle"></i>
                        @elseif($txn->type === 'referral') <i class="bi bi-people"></i>
                        @else                              <i class="bi bi-box-seam"></i>
                        @endif
                    </div>
                    <div>
                        <div class="txn-type">{{ ucfirst($txn->type) }}</div>
                        <div class="txn-date">{{ $txn->created_at->format('M d, h:i A') }}</div>
                    </div>
                </div>
                <div class="txn-amt {{ $txn->direction === 'credit' ? 'cr' : 'db' }}">
                    {{ $txn->direction === 'credit' ? '+' : '−' }}${{ number_format($txn->amount, 2) }}
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:28px;">
                <i class="bi bi-inbox"></i>
                <p>No transactions yet</p>
            </div>
            @endforelse
        </div>

    </div>{{-- /right --}}

</div>{{-- /dash-grid --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
    const ctx = document.getElementById('earningsChart');
    if (!ctx) return;

    const labels = {!! json_encode($chartLabels->values() ?? collect(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'])) !!};
    const data   = {!! json_encode($chartData->values()   ?? collect([0,0,0,0,0,0,0])) !!};

    const accent = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#00f5d4';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderColor: accent,
                backgroundColor: function(ctx) {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 160);
                    g.addColorStop(0, accent + '30');
                    g.addColorStop(1, accent + '00');
                    return g;
                },
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: accent,
                pointBorderColor: '#111119',
                pointBorderWidth: 2,
                tension: 0.45,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#16161f',
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1,
                    titleColor: '#6b6b80',
                    bodyColor: '#e8e8f0',
                    bodyFont: { family: 'DM Sans', size: 13, weight: 700 },
                    callbacks: { label: ctx => ' $' + parseFloat(ctx.parsed.y).toFixed(2) }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { color: 'rgba(255,255,255,0.3)', font: { family: 'DM Sans', size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: {
                        color: 'rgba(255,255,255,0.3)',
                        font: { family: 'DM Sans', size: 11 },
                        callback: v => '$' + v
                    }
                }
            }
        }
    });
})();
</script>
@endpush
