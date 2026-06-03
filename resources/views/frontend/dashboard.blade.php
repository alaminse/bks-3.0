@extends('layouts.app')
@section('title', 'Dashboard')

@section('css')

@endsection

@section('content')

@include('includes.header', ['pageTitle' => 'Dashboard'])

{{-- WELCOME --}}
<div class="welcome-bar">
    <div class="wb-left">
        <div class="wb-greeting"><span class="pulse"></span>Welcome back, {{ Auth::user()->name }}</div>
        <div class="wb-sub">{{ now()->format('l, d M Y') }} — here's your earning summary</div>
    </div>
    <div class="wb-right">
        <a href="{{ route('wallet.transactions') }}" class="wb-btn outline"><i class="bi bi-download"></i> Export</a>
        <a href="{{ route('tasks.index') }}" class="wb-btn primary"><i class="bi bi-lightning-charge-fill"></i> Run Tasks</a>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="dash-grid">

    {{-- ════ LEFT COLUMN ════ --}}
    <div class="dash-left">

        {{-- INCOME / OUTCOME ROW --}}
        <div class="io-row">
            <div class="io-card income">
                <div class="io-head">
                    <div class="io-icon"><i class="bi bi-arrow-down-left"></i></div>
                    <div class="io-label">Income</div>
                </div>
                <div class="io-amount">+${{ number_format($totalEarnings, 2) }}</div>
                <span class="io-badge"><i class="bi bi-arrow-up"></i> All Time</span>
            </div>
            <div class="io-card outcome">
                <div class="io-head">
                    <div class="io-icon"><i class="bi bi-arrow-up-right"></i></div>
                    <div class="io-label">Outcome</div>
                </div>
                <div class="io-amount">-${{ number_format($walletStats['pending'] ?? 0, 2) }}</div>
                <span class="io-badge"><i class="bi bi-arrow-down"></i> Pending</span>
            </div>
        </div>

        {{-- CHART CARD --}}
        <div class="chart-card">
            <div class="cc-head">
                <div>
                    <div class="cc-pre">// {{ now()->format('F Y') }}</div>
                    <div class="cc-title">Earnings Overview</div>
                </div>
                <div class="cc-bal">
                    <div class="cc-bal-lbl">Your Balance</div>
                    <div class="cc-bal-val">${{ number_format($walletStats['balance'], 2) }}</div>
                    <span class="cc-badge">↑ {{ $todayStats['tasks_completed'] }} tasks today</span>
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="earningsChart"></canvas>
            </div>
            <div class="chart-stats">
                <div class="cs-item">
                    <div class="cs-lbl">Today Earned</div>
                    <div class="cs-val">${{ number_format($todayStats['earned'], 2) }}</div>
                </div>
                <div class="cs-item">
                    <div class="cs-lbl">Tasks Done</div>
                    <div class="cs-val">{{ $todayStats['tasks_completed'] }} / {{ $todayStats['tasks_total'] ?? '—' }}</div>
                </div>
                <div class="cs-item">
                    <div class="cs-lbl">Available</div>
                    <div class="cs-val">${{ number_format($walletStats['available'], 2) }}</div>
                </div>
            </div>
        </div>

        {{-- RECENT ACTIVITIES TABLE --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-activity"></i> Recent Activities</span>
                <a href="{{ route('tasks.history') }}" class="s-view-all">View All →</a>
            </div>
            <div class="table-responsive">
                <table class="act-table">
                    <thead>
                        <tr><th>Task</th><th>Package</th><th>Reward</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                        <tr>
                            <td><span class="t-task">{{ $activity->task->title }}</span></td>
                            <td><span class="s-pill info">{{ $activity->userPackage->package->name }}</span></td>
                            <td><span class="t-reward">+${{ number_format($activity->reward_amount, 2) }}</span></td>
                            <td><span class="s-pill {{ $activity->status }}">{{ ucfirst($activity->status) }}</span></td>
                            <td style="font-size:.72rem;color:var(--text-muted)">{{ $activity->submitted_at->format('M d, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state"><i class="bi bi-inbox"></i><p>No activities yet</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- end dash-left --}}

    {{-- ════ RIGHT SIDEBAR ════ --}}
    <div class="dash-right">

        {{-- WALLET CARD (like "My Cards" in image) --}}
        <div class="wallet-card">
            <div class="wc-head">
                <h3 class="wc-title"><i class="bi bi-wallet2"></i> My Wallet</h3>
            </div>
            <div class="cy-card">
                <div class="ccy-inner">
                    <div class="ccy-top">
                        <div class="ccy-logo">Top Trade</div>
                        <div class="ccy-dots">·· USDT</div>
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
                <a href="{{ route('wallet.deposit') }}"  class="wca-btn"><i class="bi bi-plus-circle"></i> Deposit</a>
                <a href="{{ route('withdraw.index') }}"  class="wca-btn"><i class="bi bi-send"></i> Withdraw</a>
                <a href="{{ route('wallet.index') }}"    class="wca-btn"><i class="bi bi-eye"></i> Details</a>
            </div>
        </div>

        {{-- DAILY PROGRESS --}}
        @php
            $total = $todayStats['tasks_total'] ?? 0;
            $done  = $todayStats['tasks_completed'] ?? 0;
            $pct   = $total > 0 ? min(100, round(($done / $total) * 100)) : 0;
        @endphp
        <div class="prog-card">
            <div class="prog-pre">// Daily Mission</div>
            <div class="prog-title">Task Progress</div>
            <div class="prog-row">
                <span class="prog-label">Completed</span>
                <span class="prog-nums">{{ $done }} / {{ $total ?: '—' }}</span>
            </div>
            <div class="prog-bar-wrap">
                <div class="prog-bar-fill" style="width:{{ $pct }}%"></div>
            </div>
            <div class="prog-sub">
                @if($pct >= 100) <span style="color:var(--cyan)">✓ All tasks complete!</span>
                @elseif($pct > 0) {{ $pct }}% done — keep going
                @else No tasks completed yet today @endif
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="qa-card">
            <div class="qa-head">
                <h3 class="qa-title"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</h3>
            </div>
            <div class="qa-grid">
                <a href="{{ route('tasks.index') }}"     class="qa-btn primary"><i class="bi bi-list-check"></i> Tasks</a>
                <a href="{{ route('packages.index') }}"  class="qa-btn"><i class="bi bi-box-seam"></i> Packages</a>
                <a href="{{ route('referrals.index') }}" class="qa-btn"><i class="bi bi-people"></i> Referrals</a>
                <a href="{{ route('profile.index') }}"   class="qa-btn"><i class="bi bi-person"></i> Account</a>
            </div>
        </div>

        {{-- RECENT TRANSACTIONS --}}
        <div class="txn-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-wallet2"></i> Transactions</span>
                <a href="{{ route('wallet.transactions') }}" class="s-view-all">All →</a>
            </div>
            @forelse($recentTransactions as $transaction)
            <div class="txn-row">
                <div class="d-flex align-items-center gap-2">
                    <div class="txn-ico {{ $transaction->direction === 'credit' ? 'cr' : 'db' }}">
                        @if($transaction->type === 'task')         <i class="bi bi-check-circle"></i>
                        @elseif($transaction->type === 'deposit')  <i class="bi bi-arrow-down-circle"></i>
                        @elseif($transaction->type === 'withdraw') <i class="bi bi-arrow-up-circle"></i>
                        @else                                      <i class="bi bi-box-seam"></i>
                        @endif
                    </div>
                    <div>
                        <div class="txn-type">{{ ucfirst($transaction->type) }}</div>
                        <div class="txn-date">{{ $transaction->created_at->format('M d, h:i A') }}</div>
                    </div>
                </div>
                <div class="txn-amt {{ $transaction->direction === 'credit' ? 'cr' : 'db' }}">
                    {{ $transaction->direction === 'credit' ? '+' : '−' }}${{ number_format($transaction->amount, 2) }}
                </div>
            </div>
            @empty
            <div class="empty-state"><i class="bi bi-inbox"></i><p>No transactions yet</p></div>
            @endforelse
        </div>

    </div>{{-- end dash-right --}}

</div>{{-- end dash-grid --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function(){
    // EARNINGS CHART
    const ctx = document.getElementById('earningsChart');
    if(ctx){
        const labels = {!! json_encode($chartLabels ?? ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']) !!};
        const data   = {!! json_encode($chartData   ?? [0,0,0,0,0,0,0]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets:[{
                    data,
                    borderColor: 'rgba(0,245,255,0.8)',
                    backgroundColor: function(ctx){
                        const g = ctx.chart.ctx.createLinearGradient(0,0,0,160);
                        g.addColorStop(0,'rgba(0,245,255,0.18)');
                        g.addColorStop(1,'rgba(0,245,255,0)');
                        return g;
                    },
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'var(--cyan)',
                    pointBorderColor: '#000810',
                    pointBorderWidth: 2,
                    tension: 0.45,
                    fill: true,
                }]
            },
            options:{
                responsive: true,
                maintainAspectRatio: false,
                plugins:{ legend:{ display:false }, tooltip:{
                    backgroundColor:'#011535', borderColor:'rgba(0,245,255,.3)', borderWidth:1,
                    titleFont:{ family:'Orbitron', size:10 }, bodyFont:{ family:'Orbitron', size:11 },
                    titleColor:'rgba(0,245,255,.7)', bodyColor:'#fff',
                    callbacks:{ label: ctx => ' $' + ctx.parsed.y.toFixed(2) }
                }},
                scales:{
                    x:{ grid:{ color:'rgba(0,245,255,0.04)' }, ticks:{ color:'rgba(255,255,255,.3)', font:{ family:'Orbitron', size:9 } } },
                    y:{ grid:{ color:'rgba(0,245,255,0.04)' }, ticks:{ color:'rgba(255,255,255,.3)', font:{ family:'Orbitron', size:9 }, callback: v => '$'+v } }
                }
            }
        });
    }

    // PROGRESS BAR ANIMATE
    setTimeout(()=>{
        $('.prog-bar-fill').each(function(){
            const w = $(this).css('width');
            $(this).css('width','0').animate({width:w},1000);
        });
    }, 300);
});
</script>
@endpush
