@extends('layouts.app')
@section('title', 'Available Tasks')
@section('page-title', 'Tasks')

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="bi bi-lightning-charge-fill" style="color:var(--accent);font-size:1.2rem;"></i> Available Tasks</h1>
        <p>Complete tasks to earn rewards directly to your wallet</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('tasks.history') }}" class="cy-hbtn outline">
            <i class="bi bi-clock-history"></i> History
        </a>
    </div>
</div>

<div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--accent);"><i class="bi bi-cash-coin"></i></div>
        <div class="stat-card-lbl">Earned Today</div>
        <div class="stat-card-val" style="color:var(--accent);" id="stat-earned">${{ number_format($stats['total_earned_today'], 2) }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-up">Today</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--green);"><i class="bi bi-check-circle-fill"></i></div>
        <div class="stat-card-lbl">Completed</div>
        <div class="stat-card-val" style="color:var(--green);" id="stat-done">{{ $stats['tasks_completed_today'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Today</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--blue);"><i class="bi bi-list-check"></i></div>
        <div class="stat-card-lbl">Remaining</div>
        <div class="stat-card-val" style="color:var(--blue);" id="stat-remaining">{{ $stats['available_tasks'] }}</div>
        <div class="stat-card-sub"><span class="stat-card-badge badge-neu">Available</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color:var(--gold);"><i class="bi bi-box-seam-fill"></i></div>
        <div class="stat-card-lbl">Active Packages</div>
        <div class="stat-card-val" style="color:var(--gold);">{{ $stats['active_packages'] }}</div>
        <div class="stat-card-sub">
            @if($stats['active_packages'] == 0)
            <a href="{{ route('packages.index') }}" style="color:var(--accent);font-size:0.72rem;">Buy Package →</a>
            @else
            <span class="stat-card-badge badge-up">Running</span>
            @endif
        </div>
    </div>
</div>

<div class="tk-page-grid">

    {{-- TASK LIST --}}
    <div class="tk-list-col">
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><span class="pulse"></span> Task Queue</span>
                <span style="font-size:0.72rem;color:var(--muted);background:var(--card2);border:1px solid var(--border);padding:3px 10px;border-radius:99px;">
                    {{ count($tasks) }} tasks
                </span>
            </div>

            @forelse($tasks as $taskData)
            @php
                $isAd  = $taskData['task']->task_type === 'adsterra';
                $tid   = $taskData['task']->id;
                $upid  = $taskData['user_package_id'];

                // Parse ad code for inline injection
                $adCode = $taskData['task']->adsterra_ad_code ?? '';
                preg_match_all('/src=["\']([^"\']+)["\']/', $adCode, $srcM);
                $adSrcs = $srcM[1] ?? [];
                preg_match_all('/<script(?![^>]*src)[^>]*>(.*?)<\/script>/s', $adCode, $inlM);
                $adInlines = array_filter($inlM[1] ?? [], fn($s) => trim($s));
                preg_match_all('/<div[^>]*>.*?<\/div>/s', $adCode, $divM);
                $adDivs = $divM[0] ?? [];
                preg_match_all('/https?:\/\/[^\s<>"\']+/', strip_tags($adCode), $urlM);
                $adUrls = array_filter($urlM[0] ?? [], fn($u) => !str_contains($u, '.js'));
            @endphp

            {{-- TASK ROW --}}
            <div class="tk-item {{ $isAd ? 'type-ad' : 'type-std' }}" id="task-card-{{ $tid }}-{{ $upid }}">
                <div class="tk-item-ico {{ $isAd ? 'ad' : 'std' }}">
                    <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                </div>
                <div class="tk-item-info">
                    <div class="tk-item-title">{{ $taskData['task']->title }}</div>
                    <div class="tk-item-tags">
                        <span class="tk-tag pkg"><i class="bi bi-box-seam"></i> {{ $taskData['package']->name }}</span>
                        @if($isAd)
                        <span class="tk-tag ad"><i class="bi bi-megaphone-fill"></i> Ad Task</span>
                        @else
                        <span class="tk-tag auto"><i class="bi bi-lightning-charge-fill"></i> Auto</span>
                        @endif
                        <span class="tk-tag remain"><i class="bi bi-layers"></i> {{ $taskData['remaining_tasks'] }} left</span>
                    </div>
                    <p class="tk-item-desc">{{ Str::limit($taskData['task']->description, 100) }}</p>
                    <div class="tk-item-meta">
                        @if($taskData['task']->estimated_time)
                        <span class="tk-meta-pill"><i class="bi bi-clock"></i> {{ $taskData['task']->estimated_time }} mins</span>
                        @endif
                        @if($isAd)
                        <span class="tk-meta-pill"><i class="bi bi-eye"></i> Watch {{ $taskData['task']->effective_skip_delay }}s then skip</span>
                        @else
                        <span class="tk-meta-pill"><i class="bi bi-hourglass-split"></i> Stay {{ $taskData['task']->required_duration ?? 30 }}s</span>
                        @endif
                    </div>
                </div>
                <div class="tk-item-action">
                    <div>
                        <div class="tk-reward-num">${{ number_format($taskData['reward'], 2) }}</div>
                        <div class="tk-reward-sub">Per Task</div>
                    </div>
                    @if($isAd)
                    <button type="button" class="tk-start-btn ad adsterra-task-btn"
                        data-task-id="{{ $tid }}"
                        data-user-package-id="{{ $upid }}"
                        data-reward="{{ $taskData['reward'] }}"
                        data-skip-delay="{{ $taskData['task']->effective_skip_delay }}">
                        <i class="bi bi-megaphone-fill"></i>
                        <span class="tk-btn-label">
                            <span class="tk-btn-main">Watch & Earn</span>
                            <span class="tk-btn-hint">skip after {{ $taskData['task']->effective_skip_delay }}s</span>
                        </span>
                    </button>
                    @else
                    <button type="button" class="tk-start-btn std auto-task-btn"
                        data-task-id="{{ $tid }}"
                        data-user-package-id="{{ $upid }}"
                        data-task-url="{{ $taskData['task']->task_url }}"
                        data-reward="{{ $taskData['reward'] }}"
                        data-required-duration="{{ $taskData['task']->required_duration ?? 30 }}">
                        <i class="bi bi-play-circle-fill"></i>
                        <span class="tk-btn-label">
                            <span class="tk-btn-main">Start Task</span>
                            <span class="tk-btn-hint">auto in {{ $taskData['task']->required_duration ?? 30 }}s</span>
                        </span>
                    </button>
                    @endif
                    <div class="tk-timer" id="timer-box-{{ $tid }}-{{ $upid }}">
                        <div class="tk-timer-bar">
                            <div class="tk-timer-fill" id="progress-{{ $tid }}-{{ $upid }}"></div>
                        </div>
                        <span class="tk-timer-txt" id="timer-text-{{ $tid }}-{{ $upid }}">Waiting...</span>
                    </div>
                </div>
            </div>

            {{-- INLINE AD PANEL (hidden until Watch & Earn clicked) --}}
            @if($isAd)
            <div class="tk-ad-panel" id="ad-panel-{{ $tid }}-{{ $upid }}">
                <div class="tk-ad-panel-inner">
                    <div class="tk-ad-label">
                        <span class="pulse"></span> Advertisement — Watch for
                        <strong id="ad-countdown-{{ $tid }}-{{ $upid }}">{{ $taskData['task']->effective_skip_delay }}</strong>s
                    </div>

                    {{-- Ad content --}}
                    <div class="tk-ad-slot" id="ad-slot-{{ $tid }}-{{ $upid }}">
                        @if(!empty($adUrls))
                            {{-- Smartlink --}}
                            <a href="{{ reset($adUrls) }}" target="_blank" class="tk-ad-smartlink" id="smartlink-{{ $tid }}-{{ $upid }}">
                                🔗 Open Advertisement
                            </a>
                        @elseif(!empty($adDivs))
                            {{-- Banner with div --}}
                            @foreach($adDivs as $div)
                                {!! $div !!}
                            @endforeach
                        @else
                            {{-- Script-only (popunder/social bar) — show placeholder --}}
                            <div class="tk-ad-placeholder">
                                <i class="bi bi-megaphone-fill"></i>
                                <span>Ad is running in background</span>
                            </div>
                        @endif
                    </div>

                    {{-- Timer bar --}}
                    <div class="tk-ad-progress-wrap">
                        <div class="tk-ad-progress-bar" id="ad-progress-{{ $tid }}-{{ $upid }}"></div>
                    </div>

                    {{-- Claim button (hidden until timer done) --}}
                    <button type="button" class="tk-ad-claim-btn" id="claim-btn-{{ $tid }}-{{ $upid }}" style="display:none;">
                        <i class="bi bi-check-circle-fill"></i>
                        Claim ${{ number_format($taskData['reward'], 2) }}
                    </button>

                    {{-- Cancel --}}
                    <button type="button" class="tk-ad-cancel-btn" id="cancel-btn-{{ $tid }}-{{ $upid }}">
                        <i class="bi bi-x"></i> Cancel
                    </button>
                </div>

                {{-- Inject ad scripts when panel opens --}}
                @foreach($adInlines as $inline)
                <script id="ad-inline-{{ $tid }}-{{ $upid }}-{{ $loop->index }}">{{ $inline }}</script>
                @endforeach
                @foreach($adSrcs as $src)
                <script src="{{ $src }}" data-ad-task="{{ $tid }}"></script>
                @endforeach
            </div>
            @endif

            @empty
            <div class="tk-empty">
                <i class="bi bi-inbox"></i>
                <div class="tk-empty-t">No Tasks Available</div>
                <p class="tk-empty-s">
                    @if($stats['active_packages'] === 0)
                        No active packages. <a href="{{ route('packages.index') }}">Buy a package</a> to start earning.
                    @else
                        All tasks completed for today. Come back tomorrow!
                    @endif
                </p>
            </div>
            @endforelse

        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="tk-side-col">
        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-bar-chart-fill"></i> Today's Stats</span>
            </div>
            <div style="padding:4px 0;">
                @foreach([
                    ['icon'=>'bi-cash-coin','color'=>'var(--accent)','label'=>'Earned Today','id'=>'sb-earned','val'=>'$'.number_format($stats['total_earned_today'],2)],
                    ['icon'=>'bi-check-circle-fill','color'=>'var(--green)','label'=>'Tasks Done','id'=>'sb-done','val'=>$stats['tasks_completed_today']],
                    ['icon'=>'bi-list-check','color'=>'var(--blue)','label'=>'Remaining','id'=>'sb-remaining','val'=>$stats['available_tasks']],
                    ['icon'=>'bi-box-seam-fill','color'=>'var(--gold)','label'=>'Packages','id'=>'','val'=>$stats['active_packages']],
                ] as $row)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(0,0,0,0.2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:{{ $row['color'] }};font-size:0.9rem;">
                            <i class="bi {{ $row['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.78rem;color:var(--muted);">{{ $row['label'] }}</span>
                    </div>
                    <span style="font-family:'Syne',sans-serif;font-size:0.92rem;font-weight:800;color:{{ $row['color'] }}" {{ $row['id'] ? 'id='.$row['id'] : '' }}>{{ $row['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="s-card">
            <div class="s-card-head">
                <span class="s-card-title"><i class="bi bi-info-circle-fill"></i> How It Works</span>
            </div>
            <div style="padding:14px 20px;display:flex;flex-direction:column;gap:10px;">
                @foreach([
                    ['icon'=>'bi-cursor-fill','text'=>'Click Watch & Earn — ad appears below'],
                    ['icon'=>'bi-hourglass-split','text'=>'Wait for the timer to complete'],
                    ['icon'=>'bi-check-circle-fill','text'=>'Click Claim to get your reward'],
                    ['icon'=>'bi-wallet2','text'=>'Rewards instantly added to your wallet'],
                ] as $tip)
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:26px;height:26px;border-radius:7px;background:rgba(0,0,0,0.2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:0.78rem;flex-shrink:0;margin-top:1px;">
                        <i class="bi {{ $tip['icon'] }}"></i>
                    </div>
                    <span style="font-size:0.78rem;color:var(--muted);line-height:1.55;">{{ $tip['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <a href="{{ route('tasks.history') }}" class="cy-hbtn outline" style="width:100%;justify-content:center;">
            <i class="bi bi-clock-history"></i> View Task History
        </a>
    </div>
</div>

{{-- Success Modal --}}
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body" style="text-align:center;padding:40px 24px;">
                <i class="bi bi-check-circle-fill" style="font-size:3.5rem;color:var(--accent);display:block;margin-bottom:14px;"></i>
                <div style="font-family:'Syne',sans-serif;font-size:1.2rem;font-weight:800;margin-bottom:6px;">Task Completed!</div>
                <p style="color:var(--muted);font-size:0.85rem;margin-bottom:16px;">You've earned:</p>
                <div style="font-family:'Syne',sans-serif;font-size:2.8rem;font-weight:800;color:var(--accent);line-height:1;margin-bottom:4px;">$<span id="earnedAmount">0.00</span></div>
                <div style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--muted);margin-bottom:24px;">Added to wallet</div>
                <button type="button" class="cy-hbtn primary" style="width:100%;justify-content:center;" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise"></i> Continue Tasks
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
/* ── TASK PAGE GRID ── */
.tk-page-grid { display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start; }
.tk-side-col  { display:flex; flex-direction:column; gap:16px; }
@media(max-width:991px) {
    .tk-page-grid { grid-template-columns:1fr; }
    .tk-list-col  { order:0; }
    .tk-side-col  { order:1; }
}

/* ── TASK ITEM ── */
.tk-item {
    display:grid; grid-template-columns:48px 1fr auto;
    gap:16px; align-items:start;
    padding:18px 20px; border-bottom:1px solid var(--border);
    transition:background 0.2s; border-left:3px solid transparent;
}
.tk-item:last-child { border-bottom:none; }
.tk-item:hover { background:rgba(255,255,255,0.02); }
.tk-item.type-ad  { border-left-color:var(--blue); }
.tk-item.type-std { border-left-color:rgba(0,245,212,0.3); }
.tk-item.task-done { opacity:0.3; pointer-events:none; }
.tk-item-ico { width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;border:1px solid; }
.tk-item-ico.ad  { background:rgba(59,130,246,0.1);color:var(--blue);border-color:rgba(59,130,246,0.2); }
.tk-item-ico.std { background:rgba(0,0,0,0.2);color:var(--accent);border-color:rgba(255,255,255,0.1); }
.tk-item-title { font-family:'Syne',sans-serif;font-size:0.88rem;font-weight:700;margin-bottom:8px; }
.tk-item-tags  { display:flex;gap:5px;flex-wrap:wrap;margin-bottom:8px; }
.tk-tag { display:inline-flex;align-items:center;gap:4px;font-size:0.62rem;font-weight:600;padding:2px 8px;border-radius:99px;border:1px solid; }
.tk-tag.pkg    { color:var(--blue);  border-color:rgba(59,130,246,0.25); background:rgba(59,130,246,0.08); }
.tk-tag.ad     { color:var(--accent);border-color:rgba(0,245,212,0.25);  background:rgba(0,245,212,0.06); }
.tk-tag.auto   { color:var(--green); border-color:rgba(34,197,94,0.25);  background:rgba(34,197,94,0.06); }
.tk-tag.remain { color:var(--muted); border-color:var(--border2);         background:rgba(0,0,0,0.2); }
.tk-item-desc  { font-size:0.82rem;color:var(--muted);line-height:1.6;margin-bottom:8px; }
.tk-item-meta  { display:flex;gap:14px;flex-wrap:wrap; }
.tk-meta-pill  { display:flex;align-items:center;gap:4px;font-size:0.68rem;color:var(--muted); }
.tk-meta-pill i { color:var(--accent); }
.tk-item-action { display:flex;flex-direction:column;align-items:flex-end;gap:8px;min-width:150px; }
.tk-reward-num  { font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent);line-height:1;text-align:right; }
.tk-reward-sub  { font-size:0.62rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);text-align:right; }
.tk-start-btn { display:flex;align-items:center;justify-content:center;gap:7px;padding:10px 16px;border:none;cursor:pointer;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:700;width:100%;transition:all 0.25s; }
.tk-start-btn:hover { transform:translateY(-2px); }
.tk-start-btn:disabled { opacity:0.4;cursor:not-allowed;transform:none; }
.tk-start-btn.std { background:var(--accent);color:#000; }
.tk-start-btn.ad  { background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff; }
.tk-btn-label { display:flex;flex-direction:column;align-items:center;gap:2px; }
.tk-btn-main  { font-size:0.78rem;font-weight:700; }
.tk-btn-hint  { font-size:0.65rem;opacity:0.75;font-weight:400; }
.tk-timer { background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;display:none;width:100%; }
.tk-timer.on { display:block; }
.tk-timer-bar { background:rgba(0,0,0,0.3);border-radius:99px;height:3px;margin-bottom:5px;overflow:hidden; }
.tk-timer-fill { height:100%;background:linear-gradient(90deg,var(--accent2),var(--accent));border-radius:99px; }
.tk-timer-txt { font-size:0.65rem;color:var(--muted);text-align:center;display:block; }
.tk-empty { text-align:center;padding:48px 20px;color:var(--muted); }
.tk-empty i { font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.2; }
.tk-empty-t { font-family:'Syne',sans-serif;font-size:0.95rem;font-weight:700;margin-bottom:6px;color:var(--text); }
.tk-empty-s { font-size:0.82rem; }
.tk-empty-s a { color:var(--accent); }

/* ── INLINE AD PANEL ── */
.tk-ad-panel {
    display: none;
    border-left: 3px solid var(--blue);
    border-bottom: 1px solid var(--border);
    background: rgba(59,130,246,0.04);
    animation: slideDown 0.3s ease;
}
.tk-ad-panel.open { display: block; }
@keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

.tk-ad-panel-inner { padding: 16px 20px; }

.tk-ad-label {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.72rem; color: var(--muted);
    margin-bottom: 14px;
    text-transform: uppercase; letter-spacing: 0.06em;
}
.tk-ad-label strong { color: var(--accent); }

/* Ad slot */
.tk-ad-slot {
    background: var(--card2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 16px;
    min-height: 80px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
    overflow: hidden;
    text-align: center;
}
.tk-ad-slot iframe { max-width: 100% !important; }
.tk-ad-slot > * { max-width: 100%; }

.tk-ad-placeholder {
    display: flex; align-items: center; gap: 8px;
    color: var(--muted); font-size: 0.82rem;
}
.tk-ad-placeholder i { color: var(--blue); font-size: 1.2rem; }

.tk-ad-smartlink {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border-radius: 9px;
    background: var(--accent); color: #000;
    font-weight: 700; font-size: 0.85rem;
    text-decoration: none; transition: opacity 0.2s;
}
.tk-ad-smartlink:hover { opacity: 0.88; color: #000; }

/* Progress bar */
.tk-ad-progress-wrap {
    background: rgba(0,0,0,0.3);
    border-radius: 99px; height: 4px;
    overflow: hidden; margin-bottom: 12px;
}
.tk-ad-progress-bar {
    height: 100%; width: 0%;
    background: linear-gradient(90deg, var(--accent2), var(--accent));
    border-radius: 99px;
    transition: width 1s linear;
}

/* Claim button */
.tk-ad-claim-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 12px; border-radius: 9px;
    background: var(--accent); color: #000;
    border: none; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 700;
    margin-bottom: 8px; transition: opacity 0.2s;
}
.tk-ad-claim-btn:hover { opacity: 0.9; }

.tk-ad-cancel-btn {
    display: flex; align-items: center; justify-content: center; gap: 4px;
    width: 100%; padding: 7px; border-radius: 9px;
    background: transparent; border: 1px solid var(--border);
    color: var(--muted); font-size: 0.78rem; cursor: pointer;
    font-family: 'DM Sans', sans-serif; transition: all 0.2s;
}
.tk-ad-cancel-btn:hover { border-color: var(--red); color: var(--red); }

/* Mobile */
@media(max-width:600px) {
    .tk-item { grid-template-columns:40px 1fr; gap:10px; padding:14px; }
    .tk-item-action { grid-column:1/-1; flex-direction:row; flex-wrap:wrap; align-items:center; gap:8px; }
    .tk-reward-num { font-size:1.1rem; }
    .tk-start-btn  { flex:1; min-width:120px; }
    .tk-timer      { width:100%; }
    .tk-item-ico   { width:36px; height:36px; font-size:0.9rem; }
    .tk-item-title { font-size:0.82rem; }
    .tk-item-desc  { font-size:0.75rem; }
    .tk-tag        { font-size:0.58rem; }
    .tk-meta-pill  { font-size:0.62rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Move success modal to body
    const successModal = document.getElementById('successModal');
    if (successModal && successModal.parentElement !== document.body) {
        document.body.appendChild(successModal);
    }

    // ── AUTO TASK BUTTONS ──
    document.querySelectorAll('.auto-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const taskId   = this.dataset.taskId;
            const pkgId    = this.dataset.userPackageId;
            const url      = this.dataset.taskUrl;
            const reward   = this.dataset.reward;
            const duration = parseInt(this.dataset.requiredDuration);
            const self     = this;
            if (!url) { alert('Task URL missing.'); return; }
            const tab = window.open(url, '_blank');
            if (!tab) { alert('Popup blocked! Please allow popups for this site.'); return; }
            self.disabled = true;
            self.querySelector('.tk-btn-main').textContent = 'Processing...';
            self.querySelector('.tk-btn-hint').textContent = 'Please wait...';
            const timerBox = document.getElementById(`timer-box-${taskId}-${pkgId}`);
            if (timerBox) timerBox.classList.add('on');
            let elapsed = 0;
            const iv = setInterval(() => {
                elapsed++;
                const bar = document.getElementById(`progress-${taskId}-${pkgId}`);
                const txt = document.getElementById(`timer-text-${taskId}-${pkgId}`);
                if (bar) bar.style.width = Math.min((elapsed/duration)*100,100)+'%';
                if (txt) txt.textContent = Math.max(duration-elapsed,0)+'s remaining...';
                if (elapsed >= duration) { clearInterval(iv); submitTask(taskId,pkgId,elapsed,reward,self,false); }
            }, 1000);
        });
    });

    // ── ADSTERRA INLINE TASK BUTTONS ──
    document.querySelectorAll('.adsterra-task-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const taskId   = this.dataset.taskId;
            const pkgId    = this.dataset.userPackageId;
            const reward   = parseFloat(this.dataset.reward);
            const duration = parseInt(this.dataset.skipDelay);
            const panel    = document.getElementById(`ad-panel-${taskId}-${pkgId}`);
            const claimBtn = document.getElementById(`claim-btn-${taskId}-${pkgId}`);
            const cancelBtn= document.getElementById(`cancel-btn-${taskId}-${pkgId}`);
            const progBar  = document.getElementById(`ad-progress-${taskId}-${pkgId}`);
            const countdown= document.getElementById(`ad-countdown-${taskId}-${pkgId}`);

            if (!panel) return;

            // Show panel
            panel.classList.add('open');
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Disable button
            this.disabled = true;
            this.querySelector('.tk-btn-main').textContent = 'Watching...';
            this.querySelector('.tk-btn-hint').textContent = 'Ad is showing';

            // Timer
            let elapsed = 0;
            const iv = setInterval(() => {
                elapsed++;
                const pct = Math.min((elapsed / duration) * 100, 100);
                if (progBar)  progBar.style.width = pct + '%';
                if (countdown) countdown.textContent = Math.max(duration - elapsed, 0);
                if (elapsed >= duration) {
                    clearInterval(iv);
                    if (countdown) countdown.textContent = '✓';
                    if (claimBtn) claimBtn.style.display = 'flex';
                }
            }, 1000);

            // Claim
            if (claimBtn) {
                claimBtn.onclick = () => {
                    claimBtn.disabled = true;
                    claimBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
                    submitTask(taskId, pkgId, elapsed, reward, btn, true, panel);
                };
            }

            // Cancel
            if (cancelBtn) {
                cancelBtn.onclick = () => {
                    clearInterval(iv);
                    panel.classList.remove('open');
                    btn.disabled = false;
                    btn.querySelector('.tk-btn-main').textContent = 'Watch & Earn';
                    btn.querySelector('.tk-btn-hint').textContent = `skip after ${duration}s`;
                    if (progBar)  progBar.style.width = '0%';
                    if (countdown) countdown.textContent = duration;
                    if (claimBtn) claimBtn.style.display = 'none';
                };
            }
        });
    });

    // ── SUBMIT TASK ──
    function submitTask(taskId, userPackageId, duration, reward, btn, isAd, panel) {
        fetch('/tasks/auto-verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_package_id: parseInt(userPackageId),
                task_id: parseInt(taskId),
                duration: parseInt(duration)
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Mark done
                const card = document.getElementById(`task-card-${taskId}-${userPackageId}`);
                if (card)  card.classList.add('task-done');
                if (panel) panel.classList.remove('open');

                // Update stats
                const sEarned = document.getElementById('sb-earned');
                const sDone   = document.getElementById('sb-done');
                const sRem    = document.getElementById('sb-remaining');
                const hEarned = document.getElementById('stat-earned');
                const hDone   = document.getElementById('stat-done');
                const hRem    = document.getElementById('stat-remaining');
                if (sEarned) { const c = parseFloat(sEarned.textContent.replace('$','').replace(',',''))||0; sEarned.textContent = '$'+(c+parseFloat(reward)).toFixed(2); }
                if (hEarned) { const c = parseFloat(hEarned.textContent.replace('$','').replace(',',''))||0; hEarned.textContent = '$'+(c+parseFloat(reward)).toFixed(2); }
                if (sDone) sDone.textContent = (parseInt(sDone.textContent)||0)+1;
                if (hDone) hDone.textContent = (parseInt(hDone.textContent)||0)+1;
                if (sRem)  sRem.textContent  = Math.max((parseInt(sRem.textContent)||0)-1,0);
                if (hRem)  hRem.textContent  = Math.max((parseInt(hRem.textContent)||0)-1,0);

                // Show success modal
                document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
                new bootstrap.Modal(document.getElementById('successModal')).show();
            } else {
                alert('Task failed: ' + (data.message || 'Unknown error'));
                if (btn) {
                    btn.disabled = false;
                    btn.querySelector('.tk-btn-main').textContent = isAd ? 'Watch & Earn' : 'Start Task';
                    btn.querySelector('.tk-btn-hint').textContent = isAd ? 'Try again' : 'Try again';
                }
            }
        })
        .catch(e => {
            alert('Network error: ' + e.message);
            if (btn) {
                btn.disabled = false;
                btn.querySelector('.tk-btn-main').textContent = isAd ? 'Watch & Earn' : 'Start Task';
            }
        });
    }
});
</script>
@endpush
