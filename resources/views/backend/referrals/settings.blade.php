@extends('layouts.backend')

@section('title')
    Referral Commission Settings
@endsection

@section('content')
    <div class="container-fluid">

        @include('includes.header', [
            'pageTitle' => 'Referral Commission Settings',
        ])

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Stats Row ─────────────────────────────────────────────── --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                <i class="bi bi-people-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Total Referrals</h6>
                                <h3 class="mb-0">{{ number_format($stats['total_referrals']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                                <i class="bi bi-person-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Active Referrals</h6>
                                <h3 class="mb-0">{{ number_format($stats['active_referrals']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">Total Commissions Paid</h6>
                                <h3 class="mb-0">${{ number_format($stats['total_commissions'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                                <i class="bi bi-calendar-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small">This Month</h6>
                                <h3 class="mb-0">${{ number_format($stats['this_month'], 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- ── Commission Settings Form ──────────────────────────── --}}
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Generation Commission Rates</h5>
                            <p class="text-muted small mb-0">Set commission % for each referral generation level</p>
                        </div>
                        {{-- Add Level form — standalone, NOT nested --}}
                        <form method="POST" action="{{ route('backend.referrals.settings.add-generation') }}"
                            class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Add Level
                            </button>
                        </form>
                    </div>

                    <div class="card-body">

                        {{-- ✅ MAIN SAVE FORM — no nested forms inside --}}
                        <form method="POST" action="{{ route('backend.referrals.settings.update') }}" id="settings-form">
                            @csrf

                            {{-- Info box --}}
                            <div class="alert alert-info d-flex gap-2 align-items-start mb-4">
                                <i class="bi bi-info-circle-fill mt-1"></i>
                                <div>
                                    <strong>How multi-generation referral works:</strong><br>
                                    <small>
                                        User A refers User B → User B refers User C → User C buys package ($100)<br>
                                        <strong>User A</strong> earns Level 2 commission &nbsp;|&nbsp;
                                        <strong>User B</strong> earns Level 1 commission
                                    </small>
                                </div>
                            </div>

                            {{-- Generation rows --}}
                            <div id="generation-rows">
                                @foreach ($settings as $setting)
                                    <div class="card border mb-3 generation-row" data-gen="{{ $setting->generation }}">
                                        <div class="card-body py-3">

                                            <input type="hidden" name="generations[{{ $loop->index }}][generation]"
                                                value="{{ $setting->generation }}">

                                            <div class="row g-3 align-items-center">

                                                {{-- Level badge --}}
                                                <div class="col-auto">
                                                    <div
                                                        class="badge fs-6 px-3 py-2
                                                {{ $setting->generation === 1 ? 'bg-primary' : ($setting->generation === 2 ? 'bg-success' : 'bg-secondary') }}">
                                                        Level {{ $setting->generation }}
                                                    </div>
                                                </div>

                                                {{-- Label --}}
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted mb-1">Label</label>
                                                    <input type="text" name="generations[{{ $loop->index }}][label]"
                                                        class="form-control form-control-sm" value="{{ $setting->label }}"
                                                        placeholder="e.g. Direct Referral">
                                                </div>

                                                {{-- Commission Rate --}}
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted mb-1">Commission Rate
                                                        (%)</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number"
                                                            name="generations[{{ $loop->index }}][commission_rate]"
                                                            class="form-control commission-input"
                                                            value="{{ $setting->commission_rate }}" min="0"
                                                            max="100" step="0.01" placeholder="0.00">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>

                                                {{-- Active toggle --}}
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted mb-1">Status</label>
                                                    <div class="form-check form-switch mt-1">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="generations[{{ $loop->index }}][is_active]"
                                                            value="1" id="active_{{ $setting->generation }}"
                                                            {{ $setting->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label small"
                                                            for="active_{{ $setting->generation }}">
                                                            {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Preview --}}
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted mb-1">On $100
                                                        purchase</label>
                                                    <div class="fw-bold text-success preview-amount">
                                                        ${{ number_format($setting->commission_rate, 2) }}
                                                    </div>
                                                </div>

                                                {{-- ✅ Delete button — NO nested form, uses JS --}}
                                                <div class="col-auto">
                                                    @if ($setting->generation === $settings->max('generation') && $setting->generation > 1)
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteGeneration({{ $setting->id }}, {{ $setting->generation }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>

                                            {{-- Description --}}
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    @if ($setting->generation === 1)
                                                        <i class="bi bi-arrow-right text-primary"></i>
                                                        <strong>Direct referral:</strong> You referred this person directly
                                                    @elseif($setting->generation === 2)
                                                        <i class="bi bi-arrow-right text-success"></i>
                                                        <strong>2nd generation:</strong> Your referral referred this person
                                                    @elseif($setting->generation === 3)
                                                        <i class="bi bi-arrow-right text-warning"></i>
                                                        <strong>3rd generation:</strong> Your referral's referral referred
                                                        this person
                                                    @else
                                                        <i class="bi bi-arrow-right"></i>
                                                        <strong>Level {{ $setting->generation }}:</strong>
                                                        {{ $setting->generation }} levels deep in your network
                                                    @endif
                                                </small>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total preview --}}
                            <div class="alert alert-secondary">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <i class="bi bi-calculator me-2"></i>
                                        Total commission on a <strong>$100</strong> package purchase:
                                    </span>
                                    <strong id="total-preview">
                                        ${{ number_format($settings->where('is_active', true)->sum('commission_rate'), 2) }}
                                    </strong>
                                </div>
                            </div>

                            {{-- ✅ Save button — type="submit" inside the form, no JS needed --}}
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Commission Settings
                            </button>

                        </form>
                        {{-- ✅ End of main save form --}}

                    </div>
                </div>
            </div>

            {{-- ── Earnings by Generation ────────────────────────────── --}}
            <div class="col-lg-4">
                <div class="card shadow border-0 mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Earnings by Generation</h6>
                    </div>
                    <div class="card-body">
                        @forelse($earningsByGen as $gen)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-semibold">Level {{ $gen->generation }}</span>
                                    <span class="small text-success">${{ number_format($gen->total, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $maxTotal = $earningsByGen->max('total') ?: 1;
                                        $pct = ($gen->total / $maxTotal) * 100;
                                        $colors = ['primary', 'success', 'warning', 'info', 'secondary'];
                                        $color = $colors[$gen->generation - 1] ?? 'secondary';
                                    @endphp
                                    <div class="progress-bar bg-{{ $color }}"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                                <small class="text-muted">{{ $gen->count }} transactions</small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-bar-chart fs-1"></i>
                                <p class="mt-2">No earnings yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick reference table --}}
                <div class="card shadow border-0">
                    <div class="card-header">
                        <h6 class="mb-0">Current Active Rates</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Level</th>
                                    <th>Label</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settings as $s)
                                    <tr>
                                        <td><span class="badge bg-secondary">L{{ $s->generation }}</span></td>
                                        <td><small>{{ $s->label }}</small></td>
                                        <td><strong>{{ $s->commission_rate }}%</strong></td>
                                        <td>
                                            @if ($s->is_active)
                                                <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Off</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ✅ DELETE FORM — completely outside all other forms --}}
        <form id="delete-generation-form" method="POST" action="" class="d-none">
            @csrf
            @method('DELETE')
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        // ── Delete generation ──────────────────────────────────────────────────
        function deleteGeneration(id, level) {
            if (!confirm('Are you sure you want to delete Level ' + level + '?')) return;

            const form = document.getElementById('delete-generation-form');
            form.action = '{{ route('backend.referrals.settings') }}/' + id;
            form.submit();
        }

        // ── Live preview ───────────────────────────────────────────────────────
        document.querySelectorAll('.commission-input').forEach(input => {
            input.addEventListener('input', updatePreviews);
        });

        document.querySelectorAll('.form-check-input').forEach(cb => {
            cb.addEventListener('change', function() {
                const label = this.closest('.form-check').querySelector('.form-check-label');
                if (label) label.textContent = this.checked ? 'Active' : 'Inactive';
                updatePreviews();
            });
        });

        function updatePreviews() {
            let total = 0;

            document.querySelectorAll('.generation-row').forEach(row => {
                const input = row.querySelector('.commission-input');
                const checkbox = row.querySelector('.form-check-input');
                const preview = row.querySelector('.preview-amount');
                const rate = parseFloat(input?.value) || 0;
                const active = checkbox?.checked ?? false;

                if (preview) {
                    preview.textContent = '$' + rate.toFixed(2);
                    preview.className = 'fw-bold preview-amount ' + (active && rate > 0 ? 'text-success' :
                        'text-muted');
                }

                if (active) total += rate;
            });

            const totalEl = document.getElementById('total-preview');
            if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
        }
    </script>
@endpush
