@extends('layouts.app')
@section('title', 'Available Tasks')

@section('css')
    <style>
        :root {
            --tk-cyan: #00f5ff;
            --tk-blue: #0047ff;
            --tk-blue2: #0ea5e9;
            --tk-surface: #050d1a;
            --tk-surface2: #071224;
            --tk-border: rgba(0, 245, 255, 0.10);
            --tk-border2: rgba(0, 245, 255, 0.22);
            --tk-text: #c8e8ff;
            --tk-muted: rgba(200, 232, 255, 0.40);
            --tk-glow-c: 0 0 18px rgba(0, 245, 255, 0.35);
            --tk-glow-b: 0 0 18px rgba(0, 71, 255, 0.4);
        }

        .tk-hero {
            background: var(--tk-surface);
            border: 1px solid var(--tk-border2);
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .tk-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--tk-cyan), var(--tk-blue), transparent);
        }
        .tk-hero-eyebrow { font-family: 'Orbitron', monospace; font-size: .48rem; letter-spacing: 4px; text-transform: uppercase; color: var(--tk-cyan); margin-bottom: .4rem; opacity: .7; }
        .tk-hero-title   { font-family: 'Orbitron', monospace; font-size: 1.1rem; font-weight: 900; letter-spacing: 1px; color: #fff; margin-bottom: .25rem; }
        .tk-hero-sub     { font-family: 'Rajdhani', sans-serif; font-size: .9rem; color: var(--tk-muted); }
        .tk-hero-right   { display: flex; align-items: center; gap: .65rem; flex-wrap: wrap; }
        .tk-hero-stat    { background: rgba(0,0,0,.5); border: 1px solid var(--tk-border2); padding: .65rem 1rem; text-align: center; min-width: 90px; position: relative; overflow: hidden; }
        .tk-hero-stat::before { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--tk-blue), transparent); }
        .tk-hs-val { font-family: 'Orbitron', monospace; font-size: .9rem; font-weight: 900; display: block; line-height: 1; }
        .tk-hs-lbl { font-family: 'Orbitron', monospace; font-size: .42rem; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-muted); display: block; margin-top: .3rem; }
        .tk-layout { display: grid; grid-template-columns: 1fr 300px; gap: 1.25rem; align-items: start; }
        @media(max-width: 1100px) { .tk-layout { grid-template-columns: 1fr; } }
        .tk-list-panel { background: var(--tk-surface); border: 1px solid var(--tk-border2); overflow: hidden; position: relative; }
        .tk-list-panel::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--tk-cyan), var(--tk-blue), transparent); }
        .tk-list-head { display: flex; align-items: center; justify-content: space-between; padding: .85rem 1.25rem; background: var(--tk-surface2); border-bottom: 1px solid var(--tk-border2); }
        .tk-list-title { font-family: 'Orbitron', monospace; font-size: .65rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-cyan); margin: 0; display: flex; align-items: center; gap: .5rem; }
        .tk-live-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--tk-cyan); box-shadow: 0 0 8px var(--tk-cyan); animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }
        .tk-list-count { font-family: 'Orbitron', monospace; font-size: .52rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--tk-muted); border: 1px solid var(--tk-border2); padding: .25rem .65rem; background: rgba(0,0,0,.4); }
        .tk-item { display: grid; grid-template-columns: 44px 1fr auto; gap: .85rem; align-items: start; padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--tk-border); transition: background .2s; border-left: 3px solid transparent; }
        .tk-item:last-child { border-bottom: none; }
        .tk-item:hover { background: rgba(0,245,255,.03); }
        .tk-item.type-ad  { border-left-color: var(--tk-blue); }
        .tk-item.type-std { border-left-color: rgba(0,245,255,.3); }
        .tk-item.task-done { opacity: .3; pointer-events: none; }
        .tk-item-ico { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
        .type-ad  .tk-item-ico { background: rgba(0,71,255,.15); color: var(--tk-blue2); border: 1px solid rgba(0,71,255,.3); }
        .type-std .tk-item-ico { background: rgba(0,245,255,.08); color: var(--tk-cyan); border: 1px solid rgba(0,245,255,.2); }
        .tk-item-title { font-family: 'Orbitron', monospace; font-size: .72rem; font-weight: 700; color: #fff; margin-bottom: .35rem; }
        .tk-item-tags  { display: flex; gap: .35rem; flex-wrap: wrap; margin-bottom: .5rem; }
        .tk-tag { display: inline-flex; align-items: center; gap: .25rem; font-family: 'Orbitron', monospace; font-size: .42rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: .18rem .5rem; border: 1px solid; white-space: nowrap; }
        .tk-tag.pkg    { color: var(--tk-blue2); border-color: rgba(14,165,233,.35); background: rgba(14,165,233,.08); }
        .tk-tag.ad     { color: var(--tk-cyan);  border-color: rgba(0,245,255,.3);   background: rgba(0,245,255,.05); }
        .tk-tag.auto   { color: #fff;            border-color: rgba(0,71,255,.4);    background: rgba(0,71,255,.1); }
        .tk-tag.remain { color: var(--tk-muted); border-color: var(--tk-border2);    background: rgba(0,0,0,.3); }
        .tk-item-desc { font-family: 'Rajdhani', sans-serif; font-size: .86rem; color: var(--tk-muted); line-height: 1.5; margin-bottom: .5rem; }
        .tk-item-meta { display: flex; gap: 1rem; flex-wrap: wrap; }
        .tk-meta-pill { display: flex; align-items: center; gap: .3rem; font-family: 'Orbitron', monospace; font-size: .46rem; letter-spacing: 1px; text-transform: uppercase; color: var(--tk-muted); }
        .tk-meta-pill i { color: var(--tk-cyan); font-size: .68rem; }
        .tk-item-action { display: flex; flex-direction: column; align-items: flex-end; gap: .6rem; min-width: 160px; }
        @media(max-width:600px) { .tk-item { grid-template-columns: 44px 1fr; } .tk-item-action { grid-column: 1/-1; align-items: stretch; } }
        .tk-reward-num { font-family: 'Orbitron', monospace; font-size: 1.3rem; font-weight: 900; color: var(--tk-cyan); text-shadow: 0 0 16px rgba(0,245,255,.4); line-height: 1; text-align: right; display: block; }
        .tk-reward-sub { font-family: 'Orbitron', monospace; font-size: .42rem; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-muted); text-align: right; display: block; margin-top: .2rem; }
        .tk-start-btn { display: flex; align-items: center; justify-content: center; gap: .45rem; font-family: 'Orbitron', monospace; font-size: .56rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; padding: .7rem 1rem; border: none; cursor: pointer; transition: all .25s; width: 100%; }
        .tk-start-btn:hover { transform: translateY(-2px); }
        .tk-start-btn:disabled { opacity: .4; cursor: not-allowed; transform: none; }
        .tk-start-btn.std { background: linear-gradient(135deg, var(--tk-cyan), #00b8c8); color: #000; }
        .tk-start-btn.std:hover:not(:disabled) { box-shadow: var(--tk-glow-c); }
        .tk-start-btn.ad  { background: linear-gradient(135deg, var(--tk-blue2), var(--tk-blue)); color: #fff; }
        .tk-start-btn.ad:hover:not(:disabled) { box-shadow: var(--tk-glow-b); }
        .tk-btn-label { display: flex; flex-direction: column; align-items: center; gap: .06rem; }
        .tk-btn-main  { font-size: .56rem; font-weight: 700; letter-spacing: 1.5px; }
        .tk-btn-hint  { font-family: 'Rajdhani', sans-serif; font-size: .7rem; font-weight: 400; letter-spacing: 0; text-transform: none; opacity: .75; }
        .tk-timer { background: rgba(0,0,0,.5); border: 1px solid var(--tk-border2); padding: .55rem .75rem; display: none; width: 100%; }
        .tk-timer.on { display: block; }
        .tk-timer-bar { background: rgba(0,0,0,.4); height: 3px; margin-bottom: .4rem; overflow: hidden; }
        .tk-timer-fill { height: 100%; width: 0%; background: linear-gradient(90deg, var(--tk-cyan), var(--tk-blue)); transition: width .9s linear; }
        .tk-timer-txt  { font-family: 'Orbitron', monospace; font-size: .48rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--tk-muted); text-align: center; display: block; }
        .tk-sidebar { display: flex; flex-direction: column; gap: 1rem; }
        .tk-side-card { background: var(--tk-surface); border: 1px solid var(--tk-border2); overflow: hidden; position: relative; }
        .tk-side-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--tk-cyan), var(--tk-blue), transparent); }
        .tk-side-head { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; background: var(--tk-surface2); border-bottom: 1px solid var(--tk-border2); }
        .tk-side-head-title { font-family: 'Orbitron', monospace; font-size: .55rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-text); }
        .tk-side-head i { font-size: .85rem; color: var(--tk-cyan); }
        .tk-side-stat { display: flex; align-items: center; justify-content: space-between; padding: .85rem 1rem; border-bottom: 1px solid var(--tk-border); }
        .tk-side-stat:last-child { border-bottom: none; }
        .tk-side-stat-left { display: flex; align-items: center; gap: .65rem; }
        .tk-side-ico { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; flex-shrink: 0; }
        .tk-side-stat-lbl { font-family: 'Orbitron', monospace; font-size: .5rem; letter-spacing: 1px; text-transform: uppercase; color: var(--tk-muted); }
        .tk-side-stat-val { font-family: 'Orbitron', monospace; font-size: .9rem; font-weight: 900; color: #fff; }
        .tk-tip-card { background: linear-gradient(135deg, rgba(0,71,255,.12), rgba(0,245,255,.05)); border: 1px solid var(--tk-border2); padding: 1rem; }
        .tk-tip-title { font-family: 'Orbitron', monospace; font-size: .58rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--tk-cyan); margin-bottom: .5rem; display: flex; align-items: center; gap: .4rem; }
        .tk-tip-list { list-style: none; padding: 0; margin: 0; }
        .tk-tip-list li { font-family: 'Rajdhani', sans-serif; font-size: .82rem; color: var(--tk-muted); padding: .28rem 0; display: flex; gap: .4rem; border-bottom: 1px solid rgba(0,245,255,.05); }
        .tk-tip-list li:last-child { border-bottom: none; }
        .tk-tip-list li::before { content: '//'; font-family: 'Orbitron', monospace; font-size: .44rem; color: var(--tk-cyan); opacity: .6; margin-top: .15rem; flex-shrink: 0; }
        .tk-hist-link { display: flex; align-items: center; justify-content: center; gap: .5rem; font-family: 'Orbitron', monospace; font-size: .55rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--tk-muted); text-decoration: none; border: 1px solid var(--tk-border2); padding: .7rem; transition: all .2s; background: var(--tk-surface2); }
        .tk-hist-link:hover { border-color: var(--tk-cyan); color: var(--tk-cyan); }
        .tk-empty { text-align: center; padding: 4rem 2rem; }
        .tk-empty i { font-size: 3rem; color: var(--tk-cyan); display: block; margin-bottom: 1rem; opacity: .15; }
        .tk-empty-t { font-family: 'Orbitron', monospace; font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-text); margin-bottom: .5rem; }
        .tk-empty-s { font-family: 'Rajdhani', sans-serif; font-size: .88rem; color: var(--tk-muted); }
        .tk-empty-s a { color: var(--tk-cyan); }

        /* ── CRITICAL: Fix modal z-index so buttons are clickable ── */
        /* The h-screen overflow wrapper in the layout clips modals */
        .modal { z-index: 999999 !important; }
        .modal-backdrop { z-index: 999998 !important; }
        .modal-dialog { z-index: 999999 !important; position: relative; }
        .modal-content { z-index: 999999 !important; position: relative !important; }

        /* AD MODAL */
        #adWaitModal .modal-content {
            background: var(--tk-surface) !important;
            border: 1px solid var(--tk-border2) !important;
            border-radius: 0 !important;
            border-top: 2px solid var(--tk-blue2) !important;
        }
        #adWaitModal .modal-header {
            background: var(--tk-surface2) !important;
            border-bottom: 1px solid var(--tk-border2) !important;
            padding: 1rem 1.5rem;
        }
        #adWaitModal .modal-body {
            padding: 2rem 1.5rem;
            background: var(--tk-surface);
        }
        .mah-title { font-family: 'Orbitron', monospace; font-size: .62rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--tk-blue2); display: flex; align-items: center; gap: .5rem; }
        .mad-icon { width: 70px; height: 70px; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: var(--tk-blue2); border: 1px solid rgba(14,165,233,.3); background: rgba(0,71,255,.1); border-radius: 50%; }
        .mad-title { font-family: 'Orbitron', monospace; font-size: .7rem; font-weight: 700; color: #fff; margin-bottom: .3rem; }
        .mad-sub   { font-family: 'Rajdhani', sans-serif; font-size: .88rem; color: var(--tk-muted); }
        .mad-countdown { font-family: 'Orbitron', monospace; font-size: 2.8rem; font-weight: 900; color: var(--tk-cyan); line-height: 1; display: block; margin-bottom: .25rem; }
        .mad-clbl  { font-family: 'Orbitron', monospace; font-size: .44rem; letter-spacing: 3px; text-transform: uppercase; color: var(--tk-muted); }
        .mad-prog-bg   { background: rgba(0,0,0,.4); border: 1px solid var(--tk-border2); height: 6px; margin: 1.25rem 0 1rem; overflow: hidden; }
        .mad-prog-fill { height: 100%; background: linear-gradient(90deg, var(--tk-blue), var(--tk-cyan)); transition: width .9s linear; width: 0%; }
        .mad-status    { font-family: 'Orbitron', monospace; font-size: .5rem; letter-spacing: 1.5px; text-transform: uppercase; color: var(--tk-muted); margin-bottom: 1rem; }

        #modal-skip-btn {
            display: none;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            font-family: 'Orbitron', monospace;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: .8rem 2rem;
            background: linear-gradient(135deg, var(--tk-cyan), #00b8c8);
            color: #000;
            border: none;
            cursor: pointer !important;
            transition: all .25s;
            margin: 0 auto .75rem;
            position: relative;
            z-index: 9999999;
            pointer-events: auto !important;
        }
        #modal-skip-btn:hover:not([disabled]) { box-shadow: var(--tk-glow-c); transform: translateY(-2px); }
        #modal-skip-btn[disabled] { opacity: .5; cursor: not-allowed !important; }

        #modal-cancel-btn {
            font-family: 'Orbitron', monospace;
            font-size: .5rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--tk-muted);
            background: none;
            border: none;
            cursor: pointer !important;
            padding: .4rem 1rem;
            transition: color .2s;
            display: block;
            margin: 0 auto;
            position: relative;
            z-index: 9999999;
            pointer-events: auto !important;
        }
        #modal-cancel-btn:hover { color: #f87171; }

        /* SUCCESS MODAL */
        #successModal .modal-content {
            background: var(--tk-surface) !important;
            border: 1px solid var(--tk-border2) !important;
            border-radius: 0 !important;
            border-top: 2px solid var(--tk-cyan) !important;
        }
        #successModal .modal-body { background: var(--tk-surface); padding: 2.5rem 1.5rem; }
        .suc-icon   { font-size: 3.5rem; color: var(--tk-cyan); filter: drop-shadow(0 0 18px rgba(0,245,255,.6)); display: block; margin-bottom: 1rem; }
        .suc-title  { font-family: 'Orbitron', monospace; font-size: 1rem; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; color: #fff; margin-bottom: .3rem; }
        .suc-sub    { font-family: 'Rajdhani', sans-serif; font-size: .9rem; color: var(--tk-muted); margin-bottom: 1rem; }
        .suc-amount { font-family: 'Orbitron', monospace; font-size: 2.4rem; font-weight: 900; color: var(--tk-cyan); line-height: 1; display: block; margin-bottom: .25rem; }
        .suc-unit   { font-family: 'Orbitron', monospace; font-size: .6rem; letter-spacing: 3px; text-transform: uppercase; color: rgba(0,245,255,.5); display: block; margin-bottom: 1.25rem; }
        .suc-btn    { display: inline-flex; align-items: center; gap: .6rem; font-family: 'Orbitron', monospace; font-size: .62rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; padding: .8rem 2rem; background: linear-gradient(135deg, var(--tk-cyan), var(--tk-blue)); color: #000; border: none; cursor: pointer; transition: all .25s; }
        .suc-btn:hover { transform: translateY(-2px); color: #000; }
    </style>
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'Available Tasks'])

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

    <div class="tk-hero">
        <div class="tk-hero-left">
            <div class="tk-hero-eyebrow">// Task Centre</div>
            <div class="tk-hero-title">Available Tasks</div>
            <div class="tk-hero-sub">Complete tasks to earn rewards directly to your wallet</div>
        </div>
        <div class="tk-hero-right">
            <div class="tk-hero-stat">
                <span class="tk-hs-val" style="color:var(--tk-cyan)">${{ number_format($stats['total_earned_today'], 2) }}</span>
                <span class="tk-hs-lbl">Earned Today</span>
            </div>
            <div class="tk-hero-stat">
                <span class="tk-hs-val" style="color:#60a5fa">{{ $stats['tasks_completed_today'] }}</span>
                <span class="tk-hs-lbl">Completed</span>
            </div>
            <div class="tk-hero-stat">
                <span class="tk-hs-val" style="color:var(--tk-blue2)">{{ $stats['available_tasks'] }}</span>
                <span class="tk-hs-lbl">Available</span>
            </div>
            <div class="tk-hero-stat">
                <span class="tk-hs-val" style="color:#fff">{{ $stats['active_packages'] }}</span>
                <span class="tk-hs-lbl">Packages</span>
            </div>
        </div>
    </div>

    <div class="tk-layout">
        <div class="tk-list-panel">
            <div class="tk-list-head">
                <h2 class="tk-list-title"><span class="tk-live-dot"></span> Task Queue</h2>
                <span class="tk-list-count">{{ count($tasks) }} tasks</span>
            </div>

            @forelse($tasks as $taskData)
                @php
                    $isAd = $taskData['task']->task_type === 'adsterra';
                    $tid  = $taskData['task']->id;
                    $upid = $taskData['user_package_id'];
                @endphp
                <div class="tk-item {{ $isAd ? 'type-ad' : 'type-std' }}" id="task-card-{{ $tid }}-{{ $upid }}">
                    <div class="tk-item-ico">
                        <i class="bi {{ $isAd ? 'bi-megaphone-fill' : 'bi-play-circle-fill' }}"></i>
                    </div>
                    <div class="tk-item-info">
                        <div class="tk-item-title">{{ $taskData['task']->title }}</div>
                        <div class="tk-item-tags">
                            <span class="tk-tag pkg"><i class="bi bi-box-seam"></i> {{ $taskData['package']->name }}</span>
                            @if ($isAd)
                                <span class="tk-tag ad"><i class="bi bi-megaphone-fill"></i> Ad Task</span>
                            @else
                                <span class="tk-tag auto"><i class="bi bi-lightning-charge-fill"></i> Auto</span>
                            @endif
                            <span class="tk-tag remain"><i class="bi bi-layers"></i> {{ $taskData['remaining_tasks'] }} left</span>
                        </div>
                        <p class="tk-item-desc">{{ $taskData['task']->description }}</p>
                        <div class="tk-item-meta">
                            @if ($taskData['task']->estimated_time)
                                <span class="tk-meta-pill"><i class="bi bi-clock"></i> {{ $taskData['task']->estimated_time }} mins</span>
                            @endif
                            @if ($isAd)
                                <span class="tk-meta-pill"><i class="bi bi-eye"></i> View {{ $taskData['task']->effective_skip_delay }}s then skip</span>
                            @else
                                <span class="tk-meta-pill"><i class="bi bi-hourglass-split"></i> Stay {{ $taskData['task']->required_duration ?? 30 }}s</span>
                            @endif
                        </div>
                    </div>
                    <div class="tk-item-action">
                        <span class="tk-reward-num">${{ number_format($taskData['reward'], 2) }}</span>
                        <span class="tk-reward-sub">Per Task</span>
                        @if ($isAd)
                            <button type="button" class="tk-start-btn ad adsterra-task-btn"
                                data-task-id="{{ $tid }}"
                                data-user-package-id="{{ $upid }}"
                                data-reward="{{ $taskData['reward'] }}"
                                data-skip-delay="{{ $taskData['task']->effective_skip_delay }}"
                                data-ad-url="{{ $taskData['task']->task_url ?? route('tasks.ad.view', $taskData['task']->slug) }}">
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
            @empty
                <div class="tk-empty">
                    <i class="bi bi-inbox"></i>
                    <div class="tk-empty-t">No Tasks Available</div>
                    <p class="tk-empty-s">
                        @if ($stats['active_packages'] === 0)
                            No active packages. <a href="{{ route('packages.index') }}">Buy a package</a> to start earning.
                        @else
                            All tasks completed for today. Come back tomorrow!
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <div class="tk-sidebar">
            <div class="tk-side-card">
                <div class="tk-side-head">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span class="tk-side-head-title">Today's Stats</span>
                </div>
                <div class="tk-side-stat">
                    <div class="tk-side-stat-left">
                        <div class="tk-side-ico" style="background:rgba(0,245,255,.08);color:var(--tk-cyan);border:1px solid rgba(0,245,255,.2)"><i class="bi bi-cash-coin"></i></div>
                        <span class="tk-side-stat-lbl">Earned Today</span>
                    </div>
                    <span class="tk-side-stat-val" style="color:var(--tk-cyan)" id="stat-earned">${{ number_format($stats['total_earned_today'], 2) }}</span>
                </div>
                <div class="tk-side-stat">
                    <div class="tk-side-stat-left">
                        <div class="tk-side-ico" style="background:rgba(14,165,233,.08);color:#60a5fa;border:1px solid rgba(14,165,233,.2)"><i class="bi bi-check-circle-fill"></i></div>
                        <span class="tk-side-stat-lbl">Tasks Done</span>
                    </div>
                    <span class="tk-side-stat-val" style="color:#60a5fa" id="stat-done">{{ $stats['tasks_completed_today'] }}</span>
                </div>
                <div class="tk-side-stat">
                    <div class="tk-side-stat-left">
                        <div class="tk-side-ico" style="background:rgba(0,71,255,.1);color:var(--tk-blue2);border:1px solid rgba(0,71,255,.25)"><i class="bi bi-list-check"></i></div>
                        <span class="tk-side-stat-lbl">Remaining</span>
                    </div>
                    <span class="tk-side-stat-val" style="color:var(--tk-blue2)" id="stat-remaining">{{ $stats['available_tasks'] }}</span>
                </div>
                <div class="tk-side-stat">
                    <div class="tk-side-stat-left">
                        <div class="tk-side-ico" style="background:rgba(255,255,255,.05);color:#fff;border:1px solid rgba(255,255,255,.1)"><i class="bi bi-box-seam-fill"></i></div>
                        <span class="tk-side-stat-lbl">Active Packages</span>
                    </div>
                    <span class="tk-side-stat-val">{{ $stats['active_packages'] }}</span>
                </div>
            </div>
            <div class="tk-tip-card">
                <div class="tk-tip-title"><i class="bi bi-lightning-charge-fill"></i> How It Works</div>
                <ul class="tk-tip-list">
                    <li>Click Start Task — a new tab opens</li>
                    <li>Stay on the page for the required time</li>
                    <li>Ad tasks: watch the ad then click Skip &amp; Claim</li>
                    <li>Rewards instantly added to your wallet</li>
                </ul>
            </div>
            <a href="{{ route('tasks.history') }}" class="tk-hist-link">
                <i class="bi bi-clock-history"></i> View Task History
            </a>
        </div>
    </div>

    {{-- MODALS: placed OUTSIDE .tk-layout to avoid overflow clipping --}}

    {{-- AD WAIT MODAL --}}
    <div class="modal fade" id="adWaitModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" style="z-index:999999!important">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="mah-title"><i class="bi bi-megaphone-fill"></i> Ad Viewing In Progress</div>
                </div>
                <div class="modal-body text-center">
                    <div class="mad-icon"><i class="bi bi-eye-fill"></i></div>
                    <div class="mad-title">Ad is open in a new tab</div>
                    <p class="mad-sub mb-3">Keep the ad tab open. Skip button appears after the timer.</p>
                    <div class="mad-prog-bg">
                        <div class="mad-prog-fill" id="modal-progress"></div>
                    </div>
                    <span class="mad-countdown" id="modal-countdown">--</span>
                    <span class="mad-clbl">seconds remaining</span>
                    <div class="mad-status" id="modal-status-text">Waiting for minimum view time...</div>
                    <button type="button" id="modal-skip-btn">
                        <i class="bi bi-check-circle-fill"></i>
                        Skip &amp; Claim $<span id="modal-reward-amt">0.00</span>
                    </button>
                    <button type="button" id="modal-cancel-btn">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SUCCESS MODAL --}}
    <div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" style="z-index:999999!important">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle-fill suc-icon"></i>
                    <div class="suc-title">Task Completed!</div>
                    <p class="suc-sub">You've earned:</p>
                    <span class="suc-amount">$<span id="earnedAmount">0.00</span></span>
                    <span class="suc-unit">Added to wallet</span>
                    <div class="mt-3">
                        <button type="button" class="suc-btn" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log('✅ DOMContentLoaded fired');
    console.log('✅ Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('✅ CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    console.log('✅ Auto task buttons found:', document.querySelectorAll('.auto-task-btn').length);
    console.log('✅ Ad task buttons found:', document.querySelectorAll('.adsterra-task-btn').length);

    // ── Move modals to <body> to escape any overflow:hidden parent ──
    var adModal  = document.getElementById('adWaitModal');
    var sucModal = document.getElementById('successModal');
    if (adModal  && adModal.parentElement  !== document.body) document.body.appendChild(adModal);
    if (sucModal && sucModal.parentElement !== document.body) document.body.appendChild(sucModal);
    console.log('✅ Modals moved to body');

    // ── Global state ──
    window.adState = {
        taskId:   null,
        pkgId:    null,
        reward:   0,
        duration: 0,
        elapsed:  0,
        interval: null,
        tabRef:   null,
        btn:      null,
    };

    var skipBtn   = document.getElementById('modal-skip-btn');
    var cancelBtn = document.getElementById('modal-cancel-btn');

    console.log('✅ skipBtn found:', !!skipBtn);
    console.log('✅ cancelBtn found:', !!cancelBtn);

    // ── Skip button ──
    skipBtn.addEventListener('click', function () {
        console.log('🖱️ SKIP CLICKED');
        console.log('adState:', JSON.stringify({
            taskId:  window.adState.taskId,
            pkgId:   window.adState.pkgId,
            elapsed: window.adState.elapsed,
            reward:  window.adState.reward,
        }));

        if (!window.adState.taskId || !window.adState.pkgId) {
            console.error('❌ adState.taskId or pkgId is null!');
            alert('Task data missing. Please refresh.');
            return;
        }

        clearInterval(window.adState.interval);
        skipBtn.disabled      = true;
        skipBtn.style.opacity = '0.5';

        if (window.adState.tabRef && !window.adState.tabRef.closed) {
            try { window.adState.tabRef.close(); } catch(e) {}
        }

        submitTask(
            window.adState.taskId,
            window.adState.pkgId,
            window.adState.elapsed,
            window.adState.reward,
            window.adState.btn,
            true
        );
    });

    // ── Cancel button ──
    cancelBtn.addEventListener('click', function () {
        console.log('🖱️ CANCEL CLICKED');
        clearInterval(window.adState.interval);
        var m = bootstrap.Modal.getInstance(document.getElementById('adWaitModal'));
        if (m) m.hide();
        if (window.adState.btn) {
            window.adState.btn.disabled = false;
            window.adState.btn.querySelector('.tk-btn-main').textContent = 'Watch & Earn';
            window.adState.btn.querySelector('.tk-btn-hint').textContent = 'Opens new tab';
        }
        if (window.adState.tabRef && !window.adState.tabRef.closed) {
            try { window.adState.tabRef.close(); } catch(e) {}
        }
        window.adState.taskId = null;
        window.adState.pkgId  = null;
        window.adState.btn    = null;
    });

    // ── Adsterra ad task buttons ──
    document.querySelectorAll('.adsterra-task-btn').forEach(function (btn) {
        console.log('🔗 Binding adsterra btn, task-id:', btn.dataset.taskId);
        btn.addEventListener('click', function () {
            console.group('🖱️ Adsterra button clicked');
            console.log('task-id:', this.dataset.taskId);
            console.log('user-package-id:', this.dataset.userPackageId);
            console.log('reward:', this.dataset.reward);
            console.log('skip-delay:', this.dataset.skipDelay);
            console.log('ad-url:', this.dataset.adUrl);
            console.groupEnd();

            window.adState.taskId   = this.dataset.taskId;
            window.adState.pkgId    = this.dataset.userPackageId;
            window.adState.reward   = parseFloat(this.dataset.reward);
            window.adState.duration = parseInt(this.dataset.skipDelay);
            window.adState.elapsed  = 0;
            window.adState.btn      = this;

            console.log('📌 adState set:', JSON.stringify({
                taskId: window.adState.taskId, pkgId: window.adState.pkgId,
                reward: window.adState.reward, duration: window.adState.duration,
            }));

            window.adState.tabRef = window.open(this.dataset.adUrl, '_blank');
            if (!window.adState.tabRef) {
                console.error('❌ Popup blocked!');
                alert('Popup blocked! Please allow popups for this site.');
                return;
            }

            this.disabled = true;
            this.querySelector('.tk-btn-main').textContent = 'Ad opened...';
            this.querySelector('.tk-btn-hint').textContent = 'Waiting...';

            document.getElementById('modal-reward-amt').textContent  = window.adState.reward.toFixed(2);
            document.getElementById('modal-countdown').textContent   = window.adState.duration;
            document.getElementById('modal-progress').style.width    = '0%';
            document.getElementById('modal-status-text').textContent = 'Waiting for minimum view time...';
            skipBtn.style.display  = 'none';
            skipBtn.disabled       = false;
            skipBtn.style.opacity  = '1';

            new bootstrap.Modal(document.getElementById('adWaitModal')).show();
            console.log('✅ Modal shown, timer starting for', window.adState.duration, 'seconds');

            clearInterval(window.adState.interval);
            window.adState.interval = setInterval(function () {
                window.adState.elapsed++;
                var rem = Math.max(window.adState.duration - window.adState.elapsed, 0);
                var pct = Math.min((window.adState.elapsed / window.adState.duration) * 100, 100);
                document.getElementById('modal-countdown').textContent = rem;
                document.getElementById('modal-progress').style.width  = pct + '%';

                if (window.adState.elapsed >= window.adState.duration) {
                    clearInterval(window.adState.interval);
                    skipBtn.style.display = 'inline-flex';
                    document.getElementById('modal-countdown').textContent   = '✓';
                    document.getElementById('modal-status-text').textContent = 'Time complete — click Skip & Claim!';
                    console.log('✅ Timer complete — skip button now visible, click it!');
                }
            }, 1000);
        });
    });

    // ── Standard auto-complete task buttons ──
    document.querySelectorAll('.auto-task-btn').forEach(function (btn) {
        console.log('🔗 Binding auto btn, task-id:', btn.dataset.taskId);
        btn.addEventListener('click', function () {
            console.group('🖱️ Auto task button clicked');
            console.log('task-id:', this.dataset.taskId);
            console.log('user-package-id:', this.dataset.userPackageId);
            console.log('task-url:', this.dataset.taskUrl);
            console.log('reward:', this.dataset.reward);
            console.log('required-duration:', this.dataset.requiredDuration);
            console.groupEnd();

            var taskId   = this.dataset.taskId;
            var pkgId    = this.dataset.userPackageId;
            var url      = this.dataset.taskUrl;
            var reward   = this.dataset.reward;
            var duration = parseInt(this.dataset.requiredDuration);
            var self     = this;

            if (!url) {
                console.error('❌ No task URL!');
                alert('Task URL missing. Please contact support.');
                return;
            }

            var tab = window.open(url, '_blank');
            if (!tab) {
                console.error('❌ Popup blocked!');
                alert('Popup blocked! Please allow popups for this site.');
                return;
            }

            console.log('✅ Task tab opened, duration:', duration, 's');

            self.disabled = true;
            self.querySelector('.tk-btn-main').textContent = 'Processing...';
            self.querySelector('.tk-btn-hint').textContent = 'Please wait...';

            var timerBox = document.getElementById('timer-box-' + taskId + '-' + pkgId);
            if (timerBox) timerBox.classList.add('on');

            var elapsed = 0;
            var iv = setInterval(function () {
                elapsed++;
                var bar = document.getElementById('progress-' + taskId + '-' + pkgId);
                var txt = document.getElementById('timer-text-' + taskId + '-' + pkgId);
                if (bar) bar.style.width = Math.min((elapsed / duration) * 100, 100) + '%';
                if (txt) txt.textContent = Math.max(duration - elapsed, 0) + 's remaining...';
                if (elapsed >= duration) {
                    clearInterval(iv);
                    console.log('✅ Timer complete, submitting...');
                    submitTask(taskId, pkgId, elapsed, reward, self, false);
                }
            }, 1000);
        });
    });

    // ── Submit task to server ──
    function submitTask(taskId, userPackageId, duration, reward, btn, isAd) {
        console.group('🚀 submitTask');
        console.log('taskId:', taskId, 'pkgId:', userPackageId, 'duration:', duration, 'reward:', reward, 'isAd:', isAd);
        console.groupEnd();

        var payload = {
            user_package_id: parseInt(userPackageId),
            task_id:         parseInt(taskId),
            duration:        parseInt(duration)
        };
        console.log('📤 Payload:', JSON.stringify(payload));

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            console.error('❌ No CSRF token!');
            alert('CSRF token missing. Please refresh.');
            return;
        }

        fetch('/tasks/auto-verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(function (r) {
            console.log('📥 HTTP Status:', r.status, r.statusText);
            return r.json();
        })
        .then(function (data) {
            console.group('📦 Response');
            console.log('success:', data.success);
            console.log('message:', data.message);
            console.log('data:', data);
            console.groupEnd();

            if (isAd) {
                var m = bootstrap.Modal.getInstance(document.getElementById('adWaitModal'));
                if (m) m.hide();
            }

            if (data.success) {
                console.log('🎉 Task completed! Reward:', data.reward);

                var card = document.getElementById('task-card-' + taskId + '-' + userPackageId);
                if (card) card.classList.add('task-done');

                var statEarned    = document.getElementById('stat-earned');
                var statDone      = document.getElementById('stat-done');
                var statRemaining = document.getElementById('stat-remaining');

                if (statEarned) {
                    var cur = parseFloat(statEarned.textContent.replace('$','').replace(',','')) || 0;
                    statEarned.textContent = '$' + (cur + parseFloat(reward)).toFixed(2);
                }
                if (statDone)      statDone.textContent      = (parseInt(statDone.textContent) || 0) + 1;
                if (statRemaining) statRemaining.textContent = Math.max((parseInt(statRemaining.textContent) || 0) - 1, 0);

                document.getElementById('earnedAmount').textContent = data.reward || parseFloat(reward).toFixed(2);
                new bootstrap.Modal(document.getElementById('successModal')).show();

            } else {
                console.warn('❌ Failed:', data.message);
                if (data.errors) console.warn('Errors:', data.errors);
                alert('Task failed: ' + data.message);

                if (btn) {
                    btn.disabled = false;
                    if (isAd) {
                        btn.querySelector('.tk-btn-main').textContent = 'Watch & Earn';
                        btn.querySelector('.tk-btn-hint').textContent = 'Opens new tab';
                    } else {
                        btn.querySelector('.tk-btn-main').textContent = 'Start Task';
                        btn.querySelector('.tk-btn-hint').textContent = 'auto in ' + duration + 's';
                    }
                }
                if (!isAd) {
                    var tb = document.getElementById('timer-box-' + taskId + '-' + userPackageId);
                    if (tb) tb.classList.remove('on');
                }
            }
        })
        .catch(function (e) {
            console.error('💥 Fetch error:', e);
            alert('Network error: ' + e.message);
            if (btn) {
                btn.disabled = false;
                btn.querySelector('.tk-btn-main').textContent = isAd ? 'Watch & Earn' : 'Start Task';
                btn.querySelector('.tk-btn-hint').textContent = isAd ? 'Opens new tab' : 'Try again';
            }
        });
    }

}); // end DOMContentLoaded
</script>
@endpush
