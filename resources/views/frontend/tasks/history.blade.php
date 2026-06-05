@extends('layouts.app')
@section('title', 'Task History')
@section('page-title', 'Task History')

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-clock-history" style="color:var(--accent);font-size:1.2rem;"></i> Task History</h1>
        <p>All your task submissions and their status</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('tasks.index') }}" class="cy-hbtn primary">
            <i class="bi bi-lightning-charge-fill"></i> Available Tasks
        </a>
    </div>
</div>

{{-- SUMMARY STATS --}}
<div class="stats-row" style="margin-bottom:24px;">
    @php
        $allSubs = $submissions->getCollection();
    @endphp
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-card-lbl">Pending Review</div>
        <div class="stat-card-val" style="color:var(--gold);">{{ $allSubs->where('status','pending')->count() }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Awaiting</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-card-lbl">Approved</div>
        <div class="stat-card-val" style="color:var(--green);">{{ $allSubs->where('status','approved')->count() }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">Credited</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--red);"><i class="bi bi-x-circle-fill"></i></div>
        <div class="stat-card-lbl">Rejected</div>
        <div class="stat-card-val" style="color:var(--red);">{{ $allSubs->where('status','rejected')->count() }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-down">Declined</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-card-lbl">Total Earned</div>
        <div class="stat-card-val" style="color:var(--accent);">${{ number_format($allSubs->where('status','approved')->sum('reward_amount'), 2) }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">All Time</span></div>
    </div>
</div>

{{-- TABLE --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title"><i class="bi bi-table"></i> My Task Submissions</span>
        @if($submissions->total() > 0)
        <span style="font-size:0.72rem;color:var(--muted);">{{ $submissions->total() }} total</span>
        @endif
    </div>
    <div style="overflow-x:auto;">
        <table class="act-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task</th>
                    <th>Package</th>
                    <th>Reward</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.82rem;">{{ $sub->submitted_at->format('d M Y') }}</div>
                        <div style="font-size:0.68rem;color:var(--muted);">{{ $sub->submitted_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="t-task">{{ Str::limit($sub->task->title, 35) }}</div>
                        <span class="s-pill info" style="font-size:0.6rem;margin-top:4px;">
                            {{ ucfirst($sub->task->task_type) }}
                        </span>
                    </td>
                    <td>
                        <span class="s-pill info">{{ $sub->userPackage->package->name }}</span>
                    </td>
                    <td>
                        <span class="t-reward">+${{ number_format($sub->reward_amount, 2) }}</span>
                    </td>
                    <td>
                        @if($sub->hasProof())
                        <a href="{{ $sub->proof_url }}" target="_blank" class="cy-hbtn outline" style="padding:4px 10px;font-size:0.72rem;">
                            <i class="bi bi-image"></i> View
                        </a>
                        @else
                        <span style="color:var(--muted);font-size:0.78rem;">—</span>
                        @endif

                        @if($sub->proof_text)
                        <button class="cy-hbtn outline" style="padding:4px 10px;font-size:0.72rem;margin-left:4px;" data-bs-toggle="tooltip" title="{{ $sub->proof_text }}">
                            <i class="bi bi-chat-text"></i>
                        </button>
                        @endif
                    </td>
                    <td>
                        <span class="s-pill {{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                        <div style="font-size:0.65rem;color:var(--muted);margin-top:4px;">{{ $sub->time_elapsed ?? $sub->submitted_at->diffForHumans() }}</div>
                    </td>
                    <td>
                        @if($sub->status === 'approved')
                        <span style="display:flex;align-items:center;gap:4px;font-size:0.72rem;color:var(--green);">
                            <i class="bi bi-check-circle-fill"></i> Credited
                        </span>
                        @elseif($sub->status === 'rejected')
                        <button class="cy-hbtn outline" style="padding:4px 10px;font-size:0.72rem;color:var(--red);border-color:rgba(239,68,68,0.25);"
                            data-bs-toggle="modal" data-bs-target="#reasonModal{{ $sub->id }}">
                            <i class="bi bi-info-circle"></i> Reason
                        </button>

                        {{-- Rejection Modal --}}
                        <div class="modal fade" id="reasonModal{{ $sub->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="color:var(--red)!important;">
                                            <i class="bi bi-x-circle-fill" style="color:var(--red);"></i>
                                            Rejection Reason
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div style="display:flex;flex-direction:column;gap:10px;">
                                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.82rem;">
                                                <span style="color:var(--muted);">Task</span>
                                                <span style="font-weight:600;">{{ $sub->task->title }}</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.82rem;">
                                                <span style="color:var(--muted);">Submitted</span>
                                                <span>{{ $sub->submitted_at->format('d M Y h:i A') }}</span>
                                            </div>
                                            <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:9px;padding:12px 14px;margin-top:4px;font-size:0.85rem;color:var(--text);">
                                                {{ $sub->rejection_reason ?? 'No reason provided.' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cy-hbtn outline" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('tasks.index') }}" class="cy-hbtn primary">Try Again</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <span style="display:flex;align-items:center;gap:4px;font-size:0.72rem;color:var(--muted);">
                            <i class="bi bi-clock"></i> Under review
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding:48px 20px;">
                            <i class="bi bi-inbox"></i>
                            <p style="margin-bottom:14px;">No task history yet.</p>
                            <a href="{{ route('tasks.index') }}" class="cy-hbtn primary">
                                <i class="bi bi-lightning-charge-fill"></i> Start Earning
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($submissions->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:0.72rem;color:var(--muted);">
            Showing {{ $submissions->firstItem() }}–{{ $submissions->lastItem() }} of {{ $submissions->total() }}
        </div>
        {{ $submissions->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Init Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
</script>
@endpush
