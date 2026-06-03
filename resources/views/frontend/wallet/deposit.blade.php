@extends('layouts.app')
@section('title', 'Deposit')

@section('css')
    <style>
        /* ═══════════════════════════════════════
       DEPOSIT PAGE — CYBERPUNK REDESIGN
       Matches wallet.blade.php design system
    ═══════════════════════════════════════ */

        /* ── PAGE GRID ── */
        .dep-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 1.5rem;
        }

        @media(max-width:1199px) {
            .dep-grid {
                grid-template-columns: 1fr 280px;
                gap: 1.25rem;
            }
        }

        @media(max-width:991px) {
            .dep-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── SHARED PANEL ── */
        .dep-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .dep-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .dep-panel:hover::before {
            transform: scaleX(1);
        }

        .dep-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            gap: 0.75rem;
        }

        .dep-panel-title {
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--cyan);
            text-transform: uppercase;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dep-panel-body {
            padding: 1.5rem;
        }

        /* ── BINANCE ADDRESS BOX ── */
        .dep-addr-box {
            background: linear-gradient(135deg, var(--surface2), rgba(0, 71, 255, 0.08));
            border: 1px solid var(--border-bright);
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .dep-addr-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        }

        .dep-addr-network {
            font-family: 'Orbitron', monospace;
            font-size: 0.55rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--cyan);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dep-addr-network span {
            background: rgba(0, 245, 255, 0.08);
            border: 1px solid rgba(0, 245, 255, 0.3);
            padding: 0.15rem 0.5rem;
            font-size: 0.48rem;
            letter-spacing: 2px;
        }

        .dep-addr-code {
            font-family: 'Orbitron', monospace;
            font-size: 0.72rem;
            color: #fff;
            letter-spacing: 1px;
            word-break: break-all;
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }

        .dep-copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.4rem 0.9rem;
            background: var(--surface2);
            border: 1px solid var(--border-bright);
            color: var(--text-dim);
            cursor: pointer;
            transition: all 0.25s;
            clip-path: polygon(5px 0, 100% 0, calc(100% - 5px) 100%, 0 100%);
        }

        .dep-copy-btn:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            background: rgba(0, 245, 255, 0.07);
            box-shadow: var(--glow-cyan);
        }

        .dep-copy-btn.copied {
            color: #34d399;
            border-color: #34d399;
            background: rgba(52, 211, 153, 0.07);
        }

        .dep-warn {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            background: rgba(251, 191, 36, 0.06);
            border: 1px solid rgba(251, 191, 36, 0.3);
            border-left: 3px solid var(--warning);
            padding: 0.85rem 1rem;
            font-size: 0.82rem;
            color: var(--warning);
        }

        .dep-warn i {
            font-size: 1rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        /* ── FORM STYLES ── */
        .dep-form-group {
            margin-bottom: 1.25rem;
        }

        .dep-form-label {
            font-family: 'Orbitron', monospace;
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 0.5rem;
            display: block;
        }

        .dep-form-label .req {
            color: var(--danger);
            margin-left: 2px;
        }

        .dep-input-wrap {
            position: relative;
        }

        .dep-input-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cyan);
            font-size: 0.95rem;
            pointer-events: none;
            z-index: 2;
        }

        .dep-input-icon img {
            display: block;
        }

        .dep-input {
            width: 100%;
            background: var(--surface2) !important;
            border: 1px solid var(--border) !important;
            color: #fff !important;
            border-radius: 0 !important;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.6rem 0.85rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            -webkit-appearance: none;
        }

        .dep-input.has-icon-left {
            padding-left: 2.5rem;
        }

        .dep-input.has-icon-right {
            padding-right: 3.5rem;
        }

        .dep-input::placeholder {
            color: var(--text-muted) !important;
            font-size: 0.88rem;
        }

        .dep-input:focus {
            border-color: var(--cyan) !important;
            box-shadow: 0 0 0 2px rgba(0, 245, 255, 0.1) !important;
        }

        .dep-input.is-invalid {
            border-color: var(--danger) !important;
        }

        .dep-input-suffix {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Orbitron', monospace;
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--cyan);
            pointer-events: none;
        }

        .dep-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .dep-hint i {
            font-size: 0.7rem;
        }

        .dep-error {
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.35rem;
            font-family: 'Orbitron', monospace;
            letter-spacing: 0.5px;
        }

        /* custom select */
        .dep-select-wrap {
            position: relative;
        }

        .dep-select-wrap::after {
            content: '▾';
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cyan);
            font-size: 0.8rem;
            pointer-events: none;
        }

        .dep-select {
            width: 100%;
            background: var(--surface2) !important;
            border: 1px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 0 !important;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.6rem 2.2rem 0.6rem 0.85rem;
            transition: border-color 0.2s;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .dep-select:focus {
            border-color: var(--cyan) !important;
            box-shadow: 0 0 0 2px rgba(0, 245, 255, 0.1) !important;
        }

        .dep-select option {
            background: var(--surface2);
            color: var(--text);
        }

        .dep-select.is-invalid {
            border-color: var(--danger) !important;
        }

        /* file upload */
        .dep-file-area {
            border: 1px dashed var(--border-bright);
            background: var(--surface2);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
            position: relative;
        }

        .dep-file-area:hover {
            border-color: var(--cyan);
            background: rgba(0, 245, 255, 0.04);
        }

        .dep-file-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .dep-file-icon {
            font-size: 1.75rem;
            color: var(--cyan);
            opacity: 0.6;
            margin-bottom: 0.5rem;
            display: block;
        }

        .dep-file-text {
            font-family: 'Orbitron', monospace;
            font-size: 0.58rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .dep-file-sub {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .dep-preview {
            margin-top: 1rem;
            border: 1px solid var(--border);
            overflow: hidden;
            position: relative;
        }

        .dep-preview img {
            display: block;
            width: 100%;
            max-height: 200px;
            object-fit: cover;
        }

        .dep-preview-label {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: rgba(0, 8, 16, 0.8);
            border: 1px solid var(--border-bright);
            font-family: 'Orbitron', monospace;
            font-size: 0.48rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--cyan);
            padding: 0.2rem 0.5rem;
        }

        /* form action buttons */
        .dep-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.75rem 1.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            clip-path: polygon(7px 0, 100% 0, calc(100% - 7px) 100%, 0 100%);
            width: 100%;
            justify-content: center;
            margin-bottom: 0.65rem;
        }

        .dep-btn.primary {
            background: linear-gradient(135deg, var(--cyan), var(--blue));
            color: var(--black);
        }

        .dep-btn.primary:hover {
            box-shadow: var(--glow-cyan-lg);
            transform: translateY(-2px);
            color: var(--black);
        }

        .dep-btn.outline {
            background: var(--surface2);
            border: 1px solid var(--border-bright) !important;
            color: var(--text-dim);
            clip-path: polygon(7px 0, 100% 0, calc(100% - 7px) 100%, 0 100%);
        }

        .dep-btn.outline:hover {
            border-color: var(--cyan) !important;
            color: var(--cyan);
        }

        /* ── RIGHT SIDEBAR ── */
        .dep-right {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* balance card */
        .dep-bal-card {
            background: linear-gradient(135deg, #000d2e, #010e24, #021d45);
            border: 1px solid var(--border);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
        }

        .dep-bal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
        }

        .dep-bal-card::after {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0, 71, 255, 0.18), transparent 65%);
            pointer-events: none;
        }

        .dep-bal-pre {
            font-family: 'Orbitron', monospace;
            font-size: 0.5rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--cyan);
            opacity: 0.8;
            margin-bottom: 0.4rem;
        }

        .dep-bal-val {
            font-family: 'Orbitron', monospace;
            font-size: 1.75rem;
            font-weight: 900;
            color: #fff;
            line-height: 1;
            text-shadow: 0 0 30px rgba(0, 245, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .dep-bal-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            position: relative;
            z-index: 1;
        }

        /* payment method cards */
        .dep-method {
            background: var(--surface);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .dep-method-head {
            padding: 0.85rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--cyan);
            text-transform: uppercase;
        }

        .dep-method-item {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .dep-method-item:last-child {
            border-bottom: none;
        }

        .dep-method-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.48rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 0.2rem 0.55rem;
            border: 1px solid;
            margin-bottom: 0.5rem;
            clip-path: polygon(3px 0, 100% 0, calc(100% - 3px) 100%, 0 100%);
        }

        .dep-method-badge.rec {
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.4);
            background: rgba(52, 211, 153, 0.07);
        }

        .dep-method-badge.p2p {
            color: #60a5fa;
            border-color: rgba(96, 165, 250, 0.4);
            background: rgba(96, 165, 250, 0.07);
        }

        .dep-method-name {
            font-family: 'Orbitron', monospace;
            font-size: 0.65rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .dep-method-meta {
            display: flex;
            gap: 0.85rem;
            margin-bottom: 0.65rem;
            flex-wrap: wrap;
        }

        .dep-method-tag {
            font-size: 0.72rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .dep-method-tag.green {
            color: #34d399;
        }

        .dep-method-tag.blue {
            color: #60a5fa;
        }

        .dep-steps {
            font-size: 0.78rem;
            color: var(--text-dim);
            padding-left: 1.25rem;
            margin: 0;
        }

        .dep-steps li {
            margin-bottom: 0.3rem;
            padding-left: 0.2rem;
        }

        .dep-steps li::marker {
            color: var(--cyan);
            font-size: 0.65rem;
        }

        /* instructions */
        .dep-instructions {
            background: var(--surface);
            border: 1px solid var(--border);
        }

        .dep-ins-head {
            padding: 0.85rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--cyan);
            text-transform: uppercase;
        }

        .dep-ins-body {
            padding: 1.1rem 1.25rem;
        }

        .dep-ins-steps {
            padding-left: 0;
            margin: 0;
            list-style: none;
        }

        .dep-ins-step {
            display: flex;
            gap: 0.85rem;
            align-items: flex-start;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--border);
        }

        .dep-ins-step:last-child {
            border-bottom: none;
        }

        .dep-ins-num {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', monospace;
            font-size: 0.55rem;
            font-weight: 700;
            color: var(--black);
            background: var(--cyan);
            clip-path: polygon(3px 0, 100% 0, calc(100% - 3px) 100%, 0 100%);
        }

        .dep-ins-text {
            font-size: 0.82rem;
            color: var(--text-dim);
            line-height: 1.5;
        }

        .dep-ins-text strong {
            color: var(--cyan);
        }

        /* ── PULSE ── */
        .pulse {
            width: 8px;
            height: 8px;
            background: var(--cyan);
            border-radius: 50%;
            display: inline-block;
            animation: blink 1.5s infinite;
            box-shadow: 0 0 6px var(--cyan);
            flex-shrink: 0;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .4
            }
        }

        /* ══════════════════════════════════
       RECENT DEPOSITS TABLE
    ══════════════════════════════════ */
        .dep-history {
            background: var(--surface);
            border: 1px solid var(--border);
        }

        .dep-history-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .dep-history-title {
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--cyan);
            text-transform: uppercase;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dep-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dep-table th {
            background: rgba(1, 21, 53, 0.8);
            color: var(--cyan);
            font-family: 'Orbitron', monospace;
            font-size: 0.52rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 0.8rem 1rem;
            white-space: nowrap;
            border-bottom: 1px solid var(--border-bright);
        }

        .dep-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
            color: var(--text);
            vertical-align: middle;
        }

        .dep-table tr:last-child td {
            border-bottom: none;
        }

        .dep-table tbody tr:hover td {
            background: rgba(0, 245, 255, 0.025);
        }

        .dt-date {
            font-family: 'Orbitron', monospace;
            font-size: 0.62rem;
            font-weight: 700;
            color: #fff;
        }

        .dt-time {
            font-size: 0.68rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .dt-ref {
            font-family: 'Orbitron', monospace;
            font-size: 0.58rem;
            letter-spacing: 1px;
            color: var(--text-muted);
            border: 1px solid var(--border);
            padding: 0.18rem 0.5rem;
            display: inline-block;
        }

        .dt-amount {
            font-family: 'Orbitron', monospace;
            font-size: 0.88rem;
            font-weight: 700;
            color: #34d399;
            white-space: nowrap;
        }

        .dt-method {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.22rem 0.6rem;
            border: 1px solid;
            clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
        }

        .dt-method.pay {
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.4);
            background: rgba(52, 211, 153, 0.06);
        }

        .dt-method.p2p {
            color: #60a5fa;
            border-color: rgba(96, 165, 250, 0.4);
            background: rgba(96, 165, 250, 0.06);
        }

        .dt-method.oth {
            color: var(--text-dim);
            border-color: var(--border);
            background: var(--surface2);
        }

        .dt-txid {
            font-family: 'Orbitron', monospace;
            font-size: 0.6rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .dt-status {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.22rem 0.6rem;
            border: 1px solid;
            clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
        }

        .dt-status.pending {
            color: var(--warning);
            border-color: rgba(251, 191, 36, 0.4);
            background: rgba(251, 191, 36, 0.06);
        }

        .dt-status.approved {
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.4);
            background: rgba(52, 211, 153, 0.06);
        }

        .dt-status.rejected {
            color: var(--danger);
            border-color: rgba(248, 113, 113, 0.4);
            background: rgba(248, 113, 113, 0.06);
        }

        .dt-view {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-family: 'Orbitron', monospace;
            font-size: 0.52rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.25rem 0.65rem;
            border: 1px solid var(--border);
            color: var(--text-dim);
            text-decoration: none;
            clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
            transition: all 0.2s;
        }

        .dt-view:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        .dep-table-empty {
            text-align: center;
            padding: 3rem 1rem;
        }

        .dep-table-empty i {
            font-size: 2.5rem;
            color: var(--text-muted);
            display: block;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }

        .dep-table-empty p {
            font-family: 'Orbitron', monospace;
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin: 0;
        }

        .dep-table-footer {
            background: var(--surface2);
            border-top: 1px solid var(--border);
            padding: 0.85rem 1.25rem;
        }

        /* RESPONSIVE */
        @media(max-width:575px) {
            .dep-panel-body {
                padding: 1rem;
            }

            .dep-bal-val {
                font-size: 1.4rem;
            }

            .dep-table th,
            .dep-table td {
                padding: 0.6rem 0.65rem;
                font-size: 0.78rem;
            }

            .dep-grid {
                gap: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'Deposit Money'])

    {{-- ALERTS --}}
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══ TWO-COLUMN GRID ══ --}}
    <div class="dep-grid">

        {{-- ════ LEFT: FORM ════ --}}
        <div>

            {{-- Binance Address --}}
            <div class="dep-panel" style="margin-bottom:1.25rem;">
                <div class="dep-panel-head">
                    <h2 class="dep-panel-title">
                        <i class="bi bi-wallet2"></i> Our Binance Account
                    </h2>
                </div>
                <div class="dep-panel-body">
                    <div class="dep-addr-box">
                        <div class="dep-addr-network">
                            <i class="bi bi-hdd-network-fill"></i>
                            Binance TRX
                            <span>torn(TRC20)</span>
                        </div>
                        <div class="dep-addr-code" id="walletAddress">
                            TFDdDrdohNe9Er4uEpr1evCFMFCckm9rba
                        </div>
                        <button class="dep-copy-btn" id="copyBtn" onclick="copyAddress()">
                            <i class="bi bi-clipboard" id="copyIcon"></i>
                            <span id="copyText">Copy Address</span>
                        </button>
                    </div>
                    <div class="dep-warn">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong
                                style="font-family:'Orbitron',monospace;font-size:0.6rem;letter-spacing:1px;">Warning:</strong>
                            Only send USDT (BEP20) to this address. Other tokens will not be accepted and cannot be
                            recovered.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deposit Form --}}
            <div class="dep-panel">
                <div class="dep-panel-head">
                    <h2 class="dep-panel-title">
                        <span class="pulse"></span> Submit Deposit Request
                    </h2>
                </div>
                <div class="dep-panel-body">
                    <form id="deposit-form" action="{{ route('wallet.deposit.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Amount --}}
                        <div class="dep-form-group">
                            <label class="dep-form-label">Deposit Amount (USDT) <span class="req">*</span></label>
                            <div class="dep-input-wrap">
                                <span class="dep-input-icon" style="top:50%;transform:translateY(-50%);">
                                    <img src="https://cryptologos.cc/logos/tether-usdt-logo.png" width="18"
                                        alt="USDT">
                                </span>
                                <input type="number" name="amount"
                                    class="dep-input has-icon-left has-icon-right @error('amount') is-invalid @enderror"
                                    placeholder="0.00" min="10" max="10000" step="0.01"
                                    value="{{ old('amount') }}" required>
                                <span class="dep-input-suffix">USDT</span>
                            </div>
                            @error('amount')
                                <div class="dep-error">{{ $message }}</div>
                            @enderror
                            <div class="dep-hint"><i class="bi bi-info-circle"></i> Minimum: 10 USDT &nbsp;|&nbsp; Maximum:
                                10,000 USDT</div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="dep-form-group">
                            <label class="dep-form-label">Payment Method <span class="req">*</span></label>
                            <div class="dep-select-wrap">
                                <select name="payment_method" id="paymentMethod"
                                    class="dep-select @error('payment_method') is-invalid @enderror" required>
                                    <option value="">Select payment method</option>
                                    <option value="binance_pay"
                                        {{ old('payment_method') == 'binance_pay' ? 'selected' : '' }}>
                                        Binance Pay — Recommended (FREE)
                                    </option>
                                    <option value="binance_p2p"
                                        {{ old('payment_method') == 'binance_p2p' ? 'selected' : '' }}>
                                        Binance P2P
                                    </option>
                                </select>
                            </div>
                            @error('payment_method')
                                <div class="dep-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Transaction ID --}}
                        <div class="dep-form-group">
                            <label class="dep-form-label" id="transactionLabel">
                                Transaction ID / Order ID <span class="req">*</span>
                            </label>
                            <div class="dep-input-wrap">
                                <span class="dep-input-icon"><i class="bi bi-hash"></i></span>
                                <input type="text" name="transaction_id"
                                    class="dep-input has-icon-left @error('transaction_id') is-invalid @enderror"
                                    placeholder="Enter your transaction or order ID" value="{{ old('transaction_id') }}"
                                    required>
                            </div>
                            @error('transaction_id')
                                <div class="dep-error">{{ $message }}</div>
                            @enderror
                            <div class="dep-hint" id="transactionHint">
                                <i class="bi bi-info-circle"></i>
                                For Binance Pay: Enter Transfer ID &nbsp;|&nbsp; For P2P: Enter Order Number
                            </div>
                        </div>

                        {{-- Screenshot --}}
                        <div class="dep-form-group">
                            <label class="dep-form-label">Payment Screenshot <span class="req">*</span></label>
                            <div class="dep-file-area" id="fileArea">
                                <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                                <i class="bi bi-cloud-arrow-up dep-file-icon"></i>
                                <div class="dep-file-text">Click or drag to upload screenshot</div>
                                <div class="dep-file-sub">PNG, JPG, WEBP — Max 5MB</div>
                            </div>
                            @error('payment_proof')
                                <div class="dep-error">{{ $message }}</div>
                            @enderror

                            <div class="dep-preview d-none" id="previewBox">
                                <span class="dep-preview-label">Preview</span>
                                <img id="previewImage" src="" alt="Payment proof">
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="button" class="dep-btn primary"
                            onclick="confirmFormSubmit('deposit-form', {
                                title: 'Confirm Deposit',
                                text: 'Do you want to submit this deposit request?',
                                confirmButtonText: 'Yes, deposit'
                            })">
                            <i class="bi bi-send-fill"></i> Submit Deposit Request
                        </button>
                        <a href="{{ route('wallet.index') }}" class="dep-btn outline">
                            <i class="bi bi-arrow-left"></i> Back to Wallet
                        </a>

                    </form>
                </div>
            </div>

        </div>{{-- /left --}}

        {{-- ════ RIGHT SIDEBAR ════ --}}
        <div class="dep-right">

            {{-- Balance --}}
            <div class="dep-bal-card">
                <div class="dep-bal-pre">Current Balance</div>
                <div class="dep-bal-val">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                <div class="dep-bal-sub">Available to use immediately</div>
            </div>

            {{-- Payment Methods --}}
            <div class="dep-method">
                <div class="dep-method-head">Payment Methods</div>

                <div class="dep-method-item">
                    <div class="dep-method-badge rec"><i class="bi bi-star-fill"></i> Recommended</div>
                    <div class="dep-method-name">Option 1 — Binance Pay</div>
                    <div class="dep-method-meta">
                        <span class="dep-method-tag green"><i class="bi bi-check-circle-fill"></i> FREE (0 Fees)</span>
                        <span class="dep-method-tag green"><i class="bi bi-lightning-charge-fill"></i> Instant</span>
                    </div>
                    <ol class="dep-steps">
                        <li>Open Binance App → Pay → Send</li>
                        <li>Enter our email / phone / ID</li>
                        <li>Select USDT &amp; enter amount</li>
                        <li>Copy the Transfer ID</li>
                        <li>Submit Transfer ID in the form</li>
                    </ol>
                </div>

                <div class="dep-method-item">
                    <div class="dep-method-badge p2p"><i class="bi bi-people-fill"></i> P2P</div>
                    <div class="dep-method-name">Option 2 — Binance P2P</div>
                    <div class="dep-method-meta">
                        <span class="dep-method-tag blue"><i class="bi bi-people-fill"></i> Peer-to-Peer</span>
                        <span class="dep-method-tag"><i class="bi bi-clock"></i> 15–30 min</span>
                    </div>
                    <ol class="dep-steps">
                        <li>Binance → P2P Trading → Buy USDT</li>
                        <li>Pay with local payment method</li>
                        <li>After purchase send to our address</li>
                        <li>Submit Order Number in the form</li>
                    </ol>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="dep-instructions">
                <div class="dep-ins-head">Step-by-Step Guide</div>
                <div class="dep-ins-body">
                    <ol class="dep-ins-steps">
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">1</span>
                            <span class="dep-ins-text">Choose your preferred payment method — <strong>Binance Pay</strong>
                                is recommended for zero fees.</span>
                        </li>
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">2</span>
                            <span class="dep-ins-text">Send USDT to our Binance address shown above.</span>
                        </li>
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">3</span>
                            <span class="dep-ins-text">Copy your <strong>Transaction ID</strong> or <strong>Order
                                    Number</strong> from Binance.</span>
                        </li>
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">4</span>
                            <span class="dep-ins-text">Take a screenshot of the completed payment confirmation.</span>
                        </li>
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">5</span>
                            <span class="dep-ins-text">Fill in the form and upload the screenshot.</span>
                        </li>
                        <li class="dep-ins-step">
                            <span class="dep-ins-num">6</span>
                            <span class="dep-ins-text">Wait for admin approval — usually within <strong>1–4
                                    hours</strong>.</span>
                        </li>
                    </ol>
                </div>
            </div>

        </div>{{-- /right --}}
    </div>{{-- /dep-grid --}}

    {{-- ══ RECENT DEPOSITS TABLE ══ --}}
    <div class="dep-history">
        <div class="dep-history-head">
            <h2 class="dep-history-title">
                <span class="pulse"></span> Recent Deposits
            </h2>
        </div>

        <div style="overflow-x:auto;">
            <table class="dep-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Transaction ID</th>
                        <th>Status</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $deposit)
                        <tr>
                            <td>
                                <div class="dt-date">{{ $deposit->created_at->format('d M Y') }}</div>
                                <div class="dt-time">{{ $deposit->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="dt-ref">{{ $deposit->reference_number }}</span>
                            </td>
                            <td>
                                <span class="dt-amount">{{ number_format($deposit->amount, 2) }} USDT</span>
                            </td>
                            <td>
                                @if ($deposit->payment_method === 'binance_pay')
                                    <span class="dt-method pay">
                                        <i class="bi bi-lightning-charge-fill"></i> Binance Pay
                                    </span>
                                @elseif($deposit->payment_method === 'binance_p2p')
                                    <span class="dt-method p2p">
                                        <i class="bi bi-people-fill"></i> P2P
                                    </span>
                                @else
                                    <span class="dt-method oth">{{ strtoupper($deposit->payment_method) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="dt-txid">{{ Str::limit($deposit->transaction_id, 16) }}</span>
                            </td>
                            <td>
                                @php $sc = strtolower($deposit->status); @endphp
                                <span class="dt-status {{ $sc }}">
                                    <i
                                        class="bi {{ $sc === 'pending' ? 'bi-hourglass-split' : ($sc === 'approved' ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
                                    {{ ucfirst($sc) }}
                                </span>
                            </td>
                            <td>
                                @if ($deposit->payment_proof)
                                    <a href="{{ asset('storage/' . $deposit->payment_proof) }}" target="_blank"
                                        class="dt-view">
                                        <i class="bi bi-image"></i> View
                                    </a>
                                @else
                                    <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="dep-table-empty">
                                    <i class="bi bi-inbox"></i>
                                    <p>No deposits yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($deposits->hasPages())
            <div class="dep-table-footer">
                {{ $deposits->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        // ── COPY ADDRESS ──
        function copyAddress() {
            const addr = document.getElementById('walletAddress').textContent.trim();
            navigator.clipboard.writeText(addr).then(() => {
                const btn = document.getElementById('copyBtn');
                const icon = document.getElementById('copyIcon');
                const text = document.getElementById('copyText');
                btn.classList.add('copied');
                icon.className = 'bi bi-check2';
                text.textContent = 'Copied!';
                setTimeout(() => {
                    btn.classList.remove('copied');
                    icon.className = 'bi bi-clipboard';
                    text.textContent = 'Copy Address';
                }, 2500);
            });
        }

        // ── PAYMENT METHOD LABEL SWAP ──
        document.getElementById('paymentMethod').addEventListener('change', function() {
            const label = document.getElementById('transactionLabel');
            const hint = document.getElementById('transactionHint');
            if (this.value === 'binance_pay') {
                label.innerHTML = 'Transfer ID <span class="req">*</span>';
                hint.innerHTML =
                    '<i class="bi bi-info-circle"></i> Enter the Transfer ID from your Binance Pay transaction';
            } else if (this.value === 'binance_p2p') {
                label.innerHTML = 'P2P Order Number <span class="req">*</span>';
                hint.innerHTML = '<i class="bi bi-info-circle"></i> Enter the Order Number from your P2P purchase';
            } else {
                label.innerHTML = 'Transaction ID / Order ID <span class="req">*</span>';
                hint.innerHTML =
                    '<i class="bi bi-info-circle"></i> For Binance Pay: Enter Transfer ID &nbsp;|&nbsp; For P2P: Enter Order Number';
            }
        });

        // ── FILE PREVIEW ──
        document.getElementById('payment_proof').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('previewBox').classList.remove('d-none');
            };
            reader.readAsDataURL(file);

            // Update file area label
            document.querySelector('.dep-file-text').textContent = file.name;
            document.querySelector('.dep-file-sub').textContent = (file.size / 1024).toFixed(1) + ' KB';
        });

        // ── DRAG & DROP HINT ──
        const fileArea = document.getElementById('fileArea');
        fileArea.addEventListener('dragover', e => {
            e.preventDefault();
            fileArea.style.borderColor = 'var(--cyan)';
        });
        fileArea.addEventListener('dragleave', () => {
            fileArea.style.borderColor = '';
        });
        fileArea.addEventListener('drop', e => {
            e.preventDefault();
            fileArea.style.borderColor = '';
        });
    </script>
@endpush
