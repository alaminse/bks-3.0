@extends('layouts.app')
@section('title', 'Invest in '.$company->name)
@section('page-title', 'Invest')

@section('content')

<div class="page-header-bar">
    <div>
        <div style="font-size:0.72rem;color:var(--muted);margin-bottom:4px;">
            <a href="{{ route('companies.index') }}" style="color:var(--muted);text-decoration:none;">Companies</a>
            <i class="bi bi-chevron-right" style="font-size:0.6rem;margin:0 4px;"></i>
            <a href="{{ route('companies.show', $company->id) }}" style="color:var(--muted);text-decoration:none;">{{ $company->name }}</a>
            <i class="bi bi-chevron-right" style="font-size:0.6rem;margin:0 4px;"></i> Invest
        </div>
        <h1><i class="bi bi-cash-stack" style="color:var(--accent);font-size:1.1rem;"></i> Invest in {{ $company->name }}</h1>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('companies.show', $company->id) }}" class="cy-hbtn outline">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="ci-layout">

    {{-- LEFT: FORM --}}
    <div>

        {{-- Company Info --}}
        <div class="s-card ci-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-building-fill"></i> {{ $company->name }}</span>
                <span class="s-pill approved">Available</span>
            </div>
            <div style="padding:16px 20px;display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;">
                @if($company->logo)
                <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}"
                    style="width:64px;height:64px;border-radius:10px;object-fit:cover;border:1px solid var(--border);flex-shrink:0;">
                @else
                <div style="width:64px;height:64px;border-radius:10px;background:var(--card2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:var(--muted);flex-shrink:0;">
                    <i class="bi bi-building"></i>
                </div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.85rem;color:var(--muted);margin-bottom:10px;">{{ $company->description }}</div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <div style="background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;text-align:center;">
                            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:2px;">Share Price</div>
                            <div style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent);">${{ number_format($company->share_price, 2) }}</div>
                        </div>
                        <div style="background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;text-align:center;">
                            <div style="font-size:0.6rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:2px;">Available</div>
                            <div style="font-family:'Syne',sans-serif;font-weight:800;color:var(--green);">{{ number_format($company->available_shares, 2) }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Investment Form --}}
        <div class="s-card ci-mb">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-calculator"></i> Investment Form</span>
            </div>
            <div style="padding:20px;">
                <form action="{{ route('companies.process-investment', $company->id) }}" method="POST" id="investmentForm">
                    @csrf

                    <div class="ci-field">
                        <label class="ci-label">Share Percentage (%) <span style="color:var(--red);">*</span></label>
                        <input type="text" name="share_percentage" id="share_percentage"
                            class="pf-input"
                            value="{{ old('share_percentage') }}"
                            placeholder="e.g. 0.1, 0.5, 1, 1.5, 2"
                            required>
                        <div style="font-size:0.72rem;color:var(--muted);margin-top:5px;">
                            Format: 0.1%, 0.2%...0.9%, 1%, 1.1%...etc.
                        </div>
                        <div id="percentageError" style="display:none;font-size:0.72rem;color:var(--red);margin-top:4px;"></div>
                    </div>

                    <div class="ci-field">
                        <label class="ci-label">Investment Amount ($) <span style="color:var(--red);">*</span></label>
                        <input type="number" name="invested_amount" id="invested_amount"
                            class="pf-input" step="0.01"
                            max="{{ min($user->balance, $company->available_shares * $company->share_price) }}"
                            readonly required
                            style="background:var(--card2) !important;color:var(--muted) !important;cursor:not-allowed;">
                        <div style="font-size:0.72rem;color:var(--muted);margin-top:5px;">
                            Auto-calculated from share percentage × share price
                        </div>
                    </div>

                    {{-- Summary box --}}
                    <div id="summaryBox" style="display:none;background:rgba(0,245,212,0.05);border:1px solid rgba(0,245,212,0.2);border-radius:10px;padding:16px;margin-bottom:16px;">
                        <div style="font-family:'Syne',sans-serif;font-size:0.82rem;font-weight:700;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-calculator" style="color:var(--accent);"></i> Investment Summary
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
                            <div style="background:rgba(0,0,0,0.2);border-radius:8px;padding:10px 12px;">
                                <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin-bottom:3px;">Amount</div>
                                <div id="dispAmount" style="font-family:'Syne',sans-serif;font-weight:800;color:var(--accent);">$0.00</div>
                            </div>
                            <div style="background:rgba(0,0,0,0.2);border-radius:8px;padding:10px 12px;">
                                <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin-bottom:3px;">Shares</div>
                                <div id="dispShares" style="font-family:'Syne',sans-serif;font-weight:800;color:var(--green);">0.00</div>
                            </div>
                            <div style="background:rgba(0,0,0,0.2);border-radius:8px;padding:10px 12px;">
                                <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin-bottom:3px;">Ownership</div>
                                <div id="dispPct" style="font-family:'Syne',sans-serif;font-weight:800;color:var(--blue);">0.00%</div>
                            </div>
                            <div style="background:rgba(0,0,0,0.2);border-radius:8px;padding:10px 12px;">
                                <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin-bottom:3px;">Balance After</div>
                                <div id="dispBal" style="font-family:'Syne',sans-serif;font-weight:800;">$0.00</div>
                            </div>
                        </div>
                        <div id="warningBox"></div>
                    </div>

                    {{-- Terms --}}
                    <div style="display:flex;align-items:flex-start;gap:10px;background:rgba(0,0,0,0.15);border:1px solid var(--border);border-radius:9px;padding:12px 14px;margin-bottom:16px;">
                        <input type="checkbox" id="agreeTerms" required
                            style="width:16px;height:16px;accent-color:var(--accent);flex-shrink:0;margin-top:2px;cursor:pointer;">
                        <label for="agreeTerms" style="font-size:0.82rem;color:var(--muted);cursor:pointer;line-height:1.55;">
                            I understand this investment is subject to market risks. I agree to the
                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="color:var(--accent);">terms and conditions</a>.
                        </label>
                    </div>

                    <div style="display:flex;gap:8px;">
                        <button type="submit" class="cy-hbtn primary" id="submitBtn" disabled style="flex:1;justify-content:center;padding:12px;font-size:0.88rem;">
                            <i class="bi bi-check-circle-fill"></i> Confirm Investment
                        </button>
                        <a href="{{ route('companies.show', $company->id) }}" class="cy-hbtn outline" style="padding:12px 18px;">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT: Tips --}}
    <div>

        {{-- Balance --}}
        <div class="s-card ci-mb">
            <div style="padding:16px 18px;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);margin-bottom:4px;">Your Balance</div>
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);">${{ number_format($user->balance, 2) }}</div>
                </div>
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,245,212,0.1);border:1px solid rgba(0,245,212,0.2);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:1.1rem;">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>

        {{-- Investment Tips --}}
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-lightbulb-fill"></i> Investment Tips</span>
            </div>
            <div style="padding:4px 0;">
                @foreach([
                    ['icon'=>'bi-shield-check',   'color'=>'var(--green)',  'tip'=>'Only invest what you can afford to lose'],
                    ['icon'=>'bi-pie-chart-fill',  'color'=>'var(--blue)',   'tip'=>'Diversify across multiple companies'],
                    ['icon'=>'bi-search',          'color'=>'var(--accent)', 'tip'=>"Research the company's performance"],
                    ['icon'=>'bi-eye-fill',         'color'=>'var(--gold)',   'tip'=>'Monitor your investments regularly'],
                    ['icon'=>'bi-cash-coin',        'color'=>'var(--green)',  'tip'=>'Profits distributed by ownership %'],
                ] as $tip)
                <div style="display:flex;align-items:flex-start;gap:10px;padding:11px 18px;border-bottom:1px solid var(--border);">
                    <i class="bi {{ $tip['icon'] }}" style="color:{{ $tip['color'] }};font-size:0.88rem;flex-shrink:0;margin-top:2px;"></i>
                    <span style="font-size:0.82rem;color:var(--muted);">{{ $tip['tip'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- Terms Modal --}}
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:0.95rem !important;font-weight:700 !important;">
                    <i class="bi bi-file-text-fill" style="color:var(--accent);margin-right:6px;"></i> Terms & Conditions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="font-size:0.875rem;color:var(--muted);line-height:1.7;">
                <div style="margin-bottom:14px;">
                    <div style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:8px;">Investment Terms</div>
                    <ol style="padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                        <li>All investments are subject to market risks and company performance.</li>
                        <li>Share prices may fluctuate based on company valuation changes.</li>
                        <li>Profits will be distributed proportionally to your ownership percentage.</li>
                        <li>Investment amounts will be deducted from your balance immediately.</li>
                    </ol>
                </div>
                <div style="margin-bottom:14px;">
                    <div style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:8px;">Partner Rights</div>
                    <ol style="padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                        <li>Partners receive profit distributions based on ownership percentage.</li>
                        <li>Partners can view company performance and statistics.</li>
                        <li>Shares cannot be transferred without company approval.</li>
                    </ol>
                </div>
                <div>
                    <div style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:8px;">Risks</div>
                    <ol style="padding-left:16px;display:flex;flex-direction:column;gap:4px;">
                        <li>Investment values may decrease based on company performance.</li>
                        <li>There is no guarantee of profit distribution.</li>
                        <li>Shares may not be immediately liquidated.</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="cy-hbtn outline" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.ci-layout { display: grid; grid-template-columns: 1fr 280px; gap: 16px; align-items: start; }
.ci-mb { margin-bottom: 12px; }
.ci-field { margin-bottom: 16px; }
.ci-label { font-size: 0.75rem; font-weight: 600; color: var(--muted); display: block; margin-bottom: 6px; }
@media(max-width: 900px) { .ci-layout { grid-template-columns: 1fr; } }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const shareInput   = document.getElementById('share_percentage');
    const amountInput  = document.getElementById('invested_amount');
    const summaryBox   = document.getElementById('summaryBox');
    const warningBox   = document.getElementById('warningBox');
    const pctError     = document.getElementById('percentageError');
    const agreeTerms   = document.getElementById('agreeTerms');
    const submitBtn    = document.getElementById('submitBtn');

    const sharePrice     = {{ $company->share_price }};
    const availShares    = {{ $company->available_shares }};
    const userBalance    = {{ $user->wallet_balance }};
    const totalInvested  = {{ $company->partnerShares->where('status','active')->sum('invested_amount') }};

    function isValid(v) {
        v = v.toString().replace('%','').trim();
        const n = parseFloat(v);
        if (isNaN(n) || n <= 0) return false;
        if (n >= 0.1 && n < 1) return /^0\.[1-9]$/.test(v);
        if (n >= 1) {
            if (Number.isInteger(n)) return true;
            const p = v.split('.');
            return p.length === 2 && p[1].length === 1 && /^[1-9]$/.test(p[1]);
        }
        return false;
    }

    function update() {
        const raw = shareInput.value.replace('%','').trim();
        pctError.style.display = 'none';
        shareInput.classList.remove('pf-invalid');

        if (!raw) {
            summaryBox.style.display = 'none';
            amountInput.value = '';
            submitBtn.disabled = true;
            return;
        }

        if (!isValid(raw)) {
            pctError.style.display = 'block';
            pctError.textContent = 'Invalid format! Use: 0.1, 0.5, 1, 1.5, 2, etc.';
            shareInput.classList.add('pf-invalid');
            summaryBox.style.display = 'none';
            amountInput.value = '';
            submitBtn.disabled = true;
            return;
        }

        const pct    = parseFloat(raw);
        const amount = pct * sharePrice;
        const shares = amount / sharePrice;
        const newTotal  = totalInvested + amount;
        const ownership = (amount / newTotal) * 100;
        const balAfter  = userBalance - amount;

        amountInput.value = amount.toFixed(2);
        summaryBox.style.display = 'block';

        const fmt = (n) => n.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        document.getElementById('dispAmount').textContent = '$' + fmt(amount);
        document.getElementById('dispShares').textContent = fmt(shares);
        document.getElementById('dispPct').textContent    = fmt(ownership) + '%';
        document.getElementById('dispBal').textContent    = '$' + fmt(balAfter);

        let warn = '';
        if (amount > userBalance) {
            warn = `<div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;padding:10px 12px;font-size:0.78rem;color:var(--red);display:flex;gap:6px;"><i class="bi bi-exclamation-triangle-fill"></i> Insufficient balance! You need $${fmt(amount - userBalance)} more.</div>`;
        } else if (shares > availShares) {
            warn = `<div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:8px;padding:10px 12px;font-size:0.78rem;color:var(--red);display:flex;gap:6px;"><i class="bi bi-exclamation-triangle-fill"></i> Not enough shares available!</div>`;
        } else {
            warn = `<div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:8px;padding:10px 12px;font-size:0.78rem;color:var(--green);display:flex;gap:6px;"><i class="bi bi-check-circle-fill"></i> Investment amount is valid!</div>`;
        }
        warningBox.innerHTML = warn;

        submitBtn.disabled = !(amount > 0 && amount <= userBalance && shares <= availShares && agreeTerms.checked);
    }

    shareInput.addEventListener('input', update);
    agreeTerms.addEventListener('change', update);

    document.getElementById('investmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const amount  = parseFloat(amountInput.value);
        const shares  = amount / sharePrice;
        const raw     = shareInput.value.replace('%','').trim();
        if (isNaN(amount) || amount <= 0 || !isValid(raw)) {
            Swal.fire('Error', 'Please enter a valid share percentage.', 'error');
            return;
        }
        const fmt = (n) => n.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        const self = this;
        Swal.fire({
            title: 'Confirm Investment',
            html: `<div style="text-align:left;font-size:0.88rem;display:flex;flex-direction:column;gap:6px;">
                <div>Amount: <strong>$${fmt(amount)}</strong></div>
                <div>Percentage: <strong>${raw}%</strong></div>
                <div>Shares: <strong>${fmt(shares)}</strong></div>
                <div>Balance after: <strong>$${fmt(userBalance - amount)}</strong></div>
            </div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Invest',
            cancelButtonText: 'Cancel',
        }).then(r => { if (r.isConfirmed) self.submit(); });
    });
});
</script>
@endpush
