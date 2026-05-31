@extends('layouts.app')
@section('title') My Referrals @endsection

@section('content')

    @include('includes.header', ['pageTitle' => 'My Referral Dashboard'])

    {{-- ── Stats Row ──────────────────────────────────────────────────── --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Referrals</small>
                            <h4 class="mb-0">{{ $stats['total_referrals'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <small class="text-muted">Total Earned</small>
                            <h4 class="mb-0">${{ number_format($stats['total_earnings'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                        <div>
                            <small class="text-muted">This Month</small>
                            <h4 class="mb-0">${{ number_format($stats['this_month_earnings'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-info text-white rounded-circle me-3">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <small class="text-muted">Wallet Balance</small>
                            <h4 class="mb-0">${{ number_format($stats['wallet_balance'], 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Left column ────────────────────────────────────────── --}}
        <div class="col-lg-8">

            {{-- Referral Link Card --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-link-45deg me-2"></i>Your Referral Link</h5>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text"
                               id="referral-link"
                               class="form-control"
                               value="{{ $stats['referral_link'] }}"
                               readonly>
                        <button class="btn btn-primary" onclick="copyReferralLink()" id="copy-btn">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="https://wa.me/?text={{ urlencode('Join me! Use my referral link: ' . $stats['referral_link']) }}"
                           target="_blank" class="btn btn-sm btn-success">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode($stats['referral_link']) }}&text={{ urlencode('Join me and earn!') }}"
                           target="_blank" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-telegram"></i> Telegram
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($stats['referral_link']) }}"
                           target="_blank" class="btn btn-sm btn-primary">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <span class="badge bg-secondary align-self-center ms-auto">
                            Code: <strong>{{ $stats['referral_code'] }}</strong>
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── Earnings by Generation ──────────────────────── --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Earnings by Level</h5>
                </div>
                <div class="card-body">
                    @php
                        $genColors = [1 => 'primary', 2 => 'success', 3 => 'warning', 4 => 'info', 5 => 'secondary'];
                    @endphp

                    @forelse($generationSettings as $setting)
                        @php
                            $genEarning = $stats['earnings_by_generation'][$setting->generation] ?? null;
                            $total  = $genEarning ? $genEarning['total'] : 0;
                            $count  = $genEarning ? $genEarning['count'] : 0;
                            $color  = $genColors[$setting->generation] ?? 'secondary';
                        @endphp
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2
                                    {{ $setting->is_active ? '' : 'opacity-50' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge bg-{{ $color }} fs-6 px-3 py-2">
                                    L{{ $setting->generation }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $setting->label }}</div>
                                    <small class="text-muted">
                                        {{ $setting->commission_rate }}% commission
                                        @if(! $setting->is_active)
                                            &nbsp;<span class="badge bg-secondary bg-opacity-50 text-dark">Inactive</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">${{ number_format($total, 2) }}</div>
                                <small class="text-muted">{{ $count }} earning{{ $count !== 1 ? 's' : '' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No commission levels configured.</p>
                    @endforelse
                </div>
            </div>

            {{-- ── Recent Earnings Table ───────────────────────── --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recent Earnings</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>From</th>
                                <th>Level</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($earnings as $earning)
                            <tr>
                                <td>
                                    <small>{{ $earning->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $earning->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $earning->referred->name ?? '—' }}</strong>
                                </td>
                                <td>
                                    @php $color = $genColors[$earning->generation] ?? 'secondary'; @endphp
                                    <span class="badge bg-{{ $color }}">
                                        Level {{ $earning->generation }}
                                    </span>
                                </td>
                                <td>{{ $earning->commission_rate }}%</td>
                                <td><strong class="text-success">${{ number_format($earning->amount, 2) }}</strong></td>
                                <td>
                                    @if($earning->status === 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No earnings yet. Share your referral link to start earning!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($earnings->hasPages())
                <div class="card-footer border-0">
                    {{ $earnings->links() }}
                </div>
                @endif
            </div>

        </div>

        {{-- ── Right column ────────────────────────────────────────── --}}
        <div class="col-lg-4">

            {{-- How it works --}}
            <div class="card shadow border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-question-circle me-2"></i>How Multi-Level Works</h6>
                </div>
                <div class="card-body p-0">
                    @foreach($generationSettings->where('is_active', true) as $setting)
                    <div class="p-3 border-bottom">
                        <div class="d-flex gap-2">
                            @php
                                $icons = [1=>'bi-person-fill-add', 2=>'bi-person-fill', 3=>'bi-people-fill'];
                                $icon  = $icons[$setting->generation] ?? 'bi-people-fill';
                                $color = $genColors[$setting->generation] ?? 'secondary';
                            @endphp
                            <div class="text-{{ $color }} fs-5">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $setting->label }}</div>
                                <div class="text-muted small">
                                    @if($setting->generation === 1)
                                        Someone you directly refer buys a package
                                    @elseif($setting->generation === 2)
                                        Your referral's referral buys a package
                                    @else
                                        Level {{ $setting->generation }} member in your network buys
                                    @endif
                                </div>
                                <div class="text-{{ $color }} fw-bold small mt-1">
                                    → You earn {{ $setting->commission_rate }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Direct referrals list --}}
            <div class="card shadow border-0">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="mb-0"><i class="bi bi-people me-2"></i>Direct Referrals</h6>
                    <span class="badge bg-primary">{{ $referrals->total() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($referrals as $referral)
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:36px;height:36px;font-size:14px;font-weight:bold;">
                                {{ strtoupper(substr($referral->referred->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="small fw-semibold">{{ $referral->referred->name ?? 'Unknown' }}</div>
                                <div class="text-muted" style="font-size:11px;">
                                    Joined {{ $referral->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <span class="badge
                            {{ $referral->status === 'active'    ? 'bg-success' : '' }}
                            {{ $referral->status === 'pending'   ? 'bg-warning text-dark' : '' }}
                            {{ $referral->status === 'completed' ? 'bg-secondary' : '' }}">
                            {{ ucfirst($referral->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-people fs-1"></i>
                        <p class="mt-2 small">No referrals yet</p>
                    </div>
                    @endforelse
                </div>
                @if($referrals->hasPages())
                <div class="card-footer border-0">
                    {{ $referrals->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
function copyReferralLink() {
    const input = document.getElementById('referral-link');
    const btn   = document.getElementById('copy-btn');

    navigator.clipboard.writeText(input.value).then(() => {
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Copied!';
        btn.classList.replace('btn-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
            btn.classList.replace('btn-success', 'btn-primary');
        }, 2500);
    }).catch(() => {
        // Fallback for older browsers
        input.select();
        document.execCommand('copy');
    });
}
</script>
@endpush
