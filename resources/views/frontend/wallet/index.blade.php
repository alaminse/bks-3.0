@extends('layouts.app')
@section('title', 'My Wallet')

@section('css')
<style>
/* ══════════════════════════════════════
   WALLET PAGE — CYBERPUNK REDESIGN
   Inspired by Payzen layout × your design system
══════════════════════════════════════ */

/* ── GRID LAYOUT ── */
.wlt-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:1199px){ .wlt-grid{ grid-template-columns:1fr 300px; gap:1.25rem; } }
@media(max-width:991px)  { .wlt-grid{ grid-template-columns:1fr; } }

/* ── LEFT COLUMN ── */
.wlt-left { display:flex; flex-direction:column; gap:1.25rem; }

/* ════════════════
   BALANCE HERO CARD
════════════════ */
.wlt-hero {
    background: linear-gradient(135deg, #000d2e 0%, #010e24 40%, #021d45 100%);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
    padding: 0;
}
.wlt-hero::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cyan), var(--blue), transparent);
}
.wlt-hero::after {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,71,255,0.18), transparent 65%);
    pointer-events: none;
}
.wlt-hero-orb {
    position: absolute;
    bottom: -60px; left: -60px;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(0,245,255,0.07), transparent 65%);
    pointer-events: none;
}

.wlt-hero-inner {
    display: flex;
    align-items: stretch;
    position: relative;
    z-index: 1;
}

/* left: balance */
.wlt-bal-side {
    flex: 1;
    padding: 2rem 1.75rem;
    border-right: 1px solid var(--border);
}
.wlt-bal-pre {
    font-family: 'Orbitron', monospace;
    font-size: 0.5rem;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--cyan);
    opacity: 0.8;
    margin-bottom: 0.4rem;
}
.wlt-bal-amount {
    font-family: 'Orbitron', monospace;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 900;
    color: #fff;
    text-shadow: 0 0 40px rgba(0,245,255,0.25);
    line-height: 1;
    margin-bottom: 0.4rem;
}
.wlt-bal-sub {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-family: 'Orbitron', monospace;
    letter-spacing: 1px;
}
.wlt-bal-actions {
    display: flex;
    gap: 0.65rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}
.wlt-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.55rem 1.15rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.6rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    text-decoration: none;
    border: 1px solid var(--border-bright);
    background: rgba(1,21,53,0.8);
    color: var(--text-dim);
    transition: all 0.25s;
    clip-path: polygon(7px 0, 100% 0, calc(100% - 7px) 100%, 0 100%);
    white-space: nowrap;
}
.wlt-action-btn:hover {
    color: var(--cyan);
    border-color: var(--cyan);
    background: rgba(0,245,255,0.07);
    box-shadow: var(--glow-cyan);
}
.wlt-action-btn.primary {
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black);
    border: none;
}
.wlt-action-btn.primary:hover {
    box-shadow: var(--glow-cyan-lg);
    transform: translateY(-2px);
    color: var(--black);
}

/* right: mini-stats column */
.wlt-mini-stats {
    display: flex;
    flex-direction: column;
    min-width: 220px;
}
.wlt-mini-item {
    flex: 1;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: background 0.2s;
}
.wlt-mini-item:last-child { border-bottom: none; }
.wlt-mini-item:hover { background: rgba(0,245,255,0.03); }

.wlt-mini-icon {
    width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    border: 1px solid;
    clip-path: polygon(4px 0,100% 0,100% calc(100% - 4px),calc(100% - 4px) 100%,0 100%,0 4px);
    flex-shrink: 0;
}
.wlt-mini-icon.avail  { color: #34d399; border-color: rgba(52,211,153,0.3);  background: rgba(52,211,153,0.08); }
.wlt-mini-icon.locked { color: var(--warning); border-color: rgba(251,191,36,0.3); background: rgba(251,191,36,0.08); }
.wlt-mini-icon.earned { color: var(--cyan);    border-color: rgba(0,245,255,0.3);  background: rgba(0,245,255,0.08); }

.wlt-mini-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.5rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 0.2rem;
}
.wlt-mini-value {
    font-family: 'Orbitron', monospace;
    font-size: 0.92rem;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}
.wlt-mini-item.avail-item  .wlt-mini-value { color: #34d399; }
.wlt-mini-item.locked-item .wlt-mini-value { color: var(--warning); }
.wlt-mini-item.earned-item .wlt-mini-value { color: var(--cyan); }

/* ════════════════
   SPENDING CHART CARD
════════════════ */
.wlt-chart-card {
    background: var(--surface);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}
.wlt-chart-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--blue), var(--cyan), transparent);
    transform: scaleX(0);
    transition: transform 0.5s;
}
.wlt-chart-card:hover::before { transform: scaleX(1); }

.wlt-chart-head {
    display: flex; align-items: flex-start; justify-content: space-between;
    padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border);
    background: var(--surface2);
    flex-wrap: wrap; gap: 0.75rem;
}
.wlt-chart-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase; margin: 0;
    display: flex; align-items: center; gap: 0.5rem;
}
.wlt-chart-filters {
    display: flex; gap: 0.4rem;
}
.wlt-filter-btn {
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; font-weight: 700;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 0.25rem 0.65rem;
    border: 1px solid var(--border);
    background: transparent; color: var(--text-muted);
    cursor: pointer; transition: all 0.2s;
    clip-path: polygon(3px 0, 100% 0, calc(100% - 3px) 100%, 0 100%);
}
.wlt-filter-btn.active,
.wlt-filter-btn:hover {
    border-color: var(--cyan); color: var(--cyan);
    background: rgba(0,245,255,0.06);
}

.wlt-chart-body { padding: 1.25rem; }
.wlt-chart-wrap { position: relative; height: 160px; }

.wlt-chart-stats {
    display: grid; grid-template-columns: repeat(3,1fr);
    border-top: 1px solid var(--border);
    margin-top: 1rem;
}
.wcs-item {
    padding: 0.85rem 1rem;
    border-right: 1px solid var(--border);
}
.wcs-item:last-child { border-right: none; }
.wcs-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.48rem; letter-spacing: 2.5px;
    text-transform: uppercase; color: var(--text-muted);
    margin-bottom: 0.3rem;
}
.wcs-val {
    font-family: 'Orbitron', monospace;
    font-size: 0.88rem; font-weight: 700; color: #fff;
}
.wcs-val.up   { color: #34d399; }
.wcs-val.down { color: var(--danger); }

/* ════════════════
   QUICK ACTIONS ROW
════════════════ */
.wlt-qa {
    background: var(--surface);
    border: 1px solid var(--border);
}
.wlt-qa-head {
    padding: 0.85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.wlt-qa-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase; margin: 0;
}
.wlt-qa-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    border-top: none;
}
@media(max-width:575px){ .wlt-qa-grid{ grid-template-columns:repeat(3,1fr); } }

.wlt-qa-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 0.45rem;
    padding: 1.1rem 0.5rem;
    text-decoration: none;
    border-right: 1px solid var(--border);
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; letter-spacing: 1px;
    text-transform: uppercase; color: var(--text-muted);
    transition: all 0.25s;
    background: transparent;
    cursor: pointer; position: relative; overflow: hidden;
}
.wlt-qa-item:last-child { border-right: none; }
.wlt-qa-item::before {
    content: '';
    position: absolute; bottom: 0; left: 50%; right: 50%;
    height: 2px; background: var(--cyan);
    transition: all 0.3s;
}
.wlt-qa-item:hover::before { left: 0; right: 0; }
.wlt-qa-item:hover { color: var(--cyan); background: rgba(0,245,255,0.04); }

.wlt-qa-icon {
    width: 38px; height: 38px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    border: 1px solid var(--border);
    transition: all 0.25s;
    clip-path: polygon(5px 0,100% 0,100% calc(100% - 5px),calc(100% - 5px) 100%,0 100%,0 5px);
    background: var(--surface2);
    color: var(--text-muted);
}
.wlt-qa-item:hover .wlt-qa-icon {
    border-color: var(--cyan); color: var(--cyan);
    background: rgba(0,245,255,0.08);
    filter: drop-shadow(0 0 6px rgba(0,245,255,0.4));
}
.wlt-qa-item.highlight .wlt-qa-icon {
    background: linear-gradient(135deg, var(--cyan), var(--blue));
    color: var(--black); border: none;
}
.wlt-qa-item.highlight { color: var(--cyan); }

/* ════════════════
   TRANSACTIONS TABLE
════════════════ */
.wlt-txn {
    background: var(--surface);
    border: 1px solid var(--border);
}
.wlt-txn-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.wlt-txn-htitle {
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase; margin: 0;
    display: flex; align-items: center; gap: 0.5rem;
}
.wlt-txn-viewall {
    font-family: 'Orbitron', monospace;
    font-size: 0.55rem; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--text-dim);
    text-decoration: none;
    border: 1px solid var(--border);
    padding: 0.28rem 0.75rem;
    transition: all 0.2s;
}
.wlt-txn-viewall:hover { color: var(--cyan); border-color: var(--cyan); }

.wlt-table { width: 100%; border-collapse: collapse; }
.wlt-table th {
    background: rgba(1,21,53,0.9);
    color: var(--cyan);
    font-family: 'Orbitron', monospace;
    font-size: 0.56rem; letter-spacing: 2px;
    text-transform: uppercase;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-bright);
    white-space: nowrap;
}
.wlt-table td {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid var(--border);
    font-size: 0.88rem; color: var(--text);
    vertical-align: middle;
}
.wlt-table tr:last-child td { border-bottom: none; }
.wlt-table tr:hover td { background: rgba(0,245,255,0.03); }

/* type badge */
.wt-type {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase;
    padding: 0.22rem 0.6rem;
    border: 1px solid; background: transparent;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.wt-type.deposit  { color: #34d399; border-color: rgba(52,211,153,0.4); background: rgba(52,211,153,0.07); }
.wt-type.withdraw { color: var(--danger); border-color: rgba(248,113,113,0.4); background: rgba(248,113,113,0.07); }
.wt-type.task     { color: var(--cyan);    border-color: rgba(0,245,255,0.4);  background: rgba(0,245,255,0.07); }
.wt-type.package  { color: #60a5fa;        border-color: rgba(96,165,250,0.4); background: rgba(96,165,250,0.07); }
.wt-type.referral { color: var(--warning); border-color: rgba(251,191,36,0.4); background: rgba(251,191,36,0.07); }
.wt-type.adjustment { color: var(--text-dim); border-color: var(--border); background: var(--surface2); }

/* amount */
.wt-amount {
    font-family: 'Orbitron', monospace;
    font-size: 0.88rem; font-weight: 700;
    white-space: nowrap;
}
.wt-amount.cr { color: #34d399; }
.wt-amount.db { color: var(--danger); }

/* balance after */
.wt-bal {
    font-family: 'Orbitron', monospace;
    font-size: 0.78rem; color: var(--text-dim);
}

/* status pill */
.wt-status {
    display: inline-flex; align-items: center; gap: 0.3rem;
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase;
    padding: 0.22rem 0.6rem;
    border: 1px solid; background: transparent;
    clip-path: polygon(4px 0, 100% 0, calc(100% - 4px) 100%, 0 100%);
}
.wt-status.completed { color: var(--cyan);    border-color: rgba(0,245,255,0.4);  background: rgba(0,245,255,0.07); }
.wt-status.pending   { color: var(--warning); border-color: rgba(251,191,36,0.4); background: rgba(251,191,36,0.07); }
.wt-status.rejected,
.wt-status.failed    { color: var(--danger);  border-color: rgba(248,113,113,0.4);background: rgba(248,113,113,0.07); }

/* date */
.wt-date { font-size: 0.8rem; color: var(--text-dim); font-weight: 600; }
.wt-time { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

/* desc */
.wt-desc { font-size: 0.84rem; color: var(--text); font-weight: 500; }
.wt-ref  { font-size: 0.7rem;  color: var(--text-muted); margin-top: 0.1rem;
           font-family: 'Orbitron', monospace; font-size: 0.58rem; letter-spacing: 0.5px; }

/* ════════════════
   EMPTY STATE
════════════════ */
.wlt-empty {
    text-align: center; padding: 3rem 1rem; color: var(--text-muted);
}
.wlt-empty i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }
.wlt-empty p { font-family: 'Orbitron', monospace; font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; margin: 0; }

/* ════════════════
   RIGHT SIDEBAR
════════════════ */
.wlt-right { display: flex; flex-direction: column; gap: 1.25rem; }

/* CYBER CARD (like the visa card) */
.wlt-card-widget {
    background: var(--surface);
    border: 1px solid var(--border);
    overflow: hidden;
}
.wlt-cw-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.wlt-cw-title {
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase; margin: 0;
}
.wlt-cw-body { padding: 1.25rem; }

/* the actual cyber card */
.cyber-card {
    background: linear-gradient(135deg, #0047ff 0%, #001a6e 50%, #000810 100%);
    border: 1px solid rgba(0,245,255,0.2);
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
    clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
}
.cyber-card::before {
    content: '';
    position: absolute; top: -50px; right: -50px;
    width: 180px; height: 180px;
    background: radial-gradient(circle, rgba(0,245,255,0.18), transparent 65%);
    pointer-events: none;
}
.cyber-card::after {
    content: '';
    position: absolute; bottom: -40px; left: -40px;
    width: 160px; height: 160px;
    background: radial-gradient(circle, rgba(0,71,255,0.25), transparent 65%);
    pointer-events: none;
}
/* scanline on card */
.cyber-card-scan {
    position: absolute; inset: 0;
    background: repeating-linear-gradient(0deg, transparent, transparent 3px, rgba(0,245,255,0.015) 3px, rgba(0,245,255,0.015) 4px);
    pointer-events: none;
}

.cc-inner { position: relative; z-index: 1; }
.cc-top {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem;
}
.cc-brand {
    font-family: 'Orbitron', monospace;
    font-size: 0.62rem; font-weight: 900;
    letter-spacing: 3px; color: rgba(255,255,255,0.65);
    text-transform: uppercase;
}
.cc-chip {
    width: 28px; height: 22px;
    background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 3px;
}
.cc-bal-label {
    font-family: 'Orbitron', monospace;
    font-size: 0.48rem; letter-spacing: 3px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    margin-bottom: 0.3rem;
}
.cc-bal-val {
    font-family: 'Orbitron', monospace;
    font-size: 1.4rem; font-weight: 900;
    color: #fff;
    text-shadow: 0 0 20px rgba(0,245,255,0.3);
    line-height: 1;
}
.cc-bottom {
    display: flex; align-items: flex-end;
    justify-content: space-between;
    margin-top: 1.25rem;
}
.cc-user {
    font-family: 'Orbitron', monospace;
    font-size: 0.58rem; color: rgba(255,255,255,0.55);
    letter-spacing: 1px; text-transform: uppercase;
}
.cc-avail { text-align: right; }
.cc-avail-lbl {
    font-family: 'Orbitron', monospace;
    font-size: 0.45rem; letter-spacing: 2px;
    text-transform: uppercase; color: rgba(255,255,255,0.4);
}
.cc-avail-val {
    font-family: 'Orbitron', monospace;
    font-size: 0.78rem; font-weight: 700;
    color: var(--cyan);
    text-shadow: var(--glow-cyan);
}

/* card detail rows */
.cc-details { border-top: 1px solid var(--border); }
.cc-detail-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.65rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.82rem;
}
.cc-detail-row:last-child { border-bottom: none; }
.cc-dk { color: var(--text-muted); font-size: 0.75rem; }
.cc-dv {
    font-family: 'Orbitron', monospace;
    font-size: 0.72rem; font-weight: 700; color: #fff;
    letter-spacing: 1px;
}

/* ── QUICK LINKS SIDEBAR ── */
.wlt-ql {
    background: var(--surface);
    border: 1px solid var(--border);
}
.wlt-ql-head {
    padding: 0.85rem 1.25rem;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
    font-family: 'Orbitron', monospace;
    font-size: 0.7rem; font-weight: 700;
    letter-spacing: 2px; color: var(--cyan);
    text-transform: uppercase;
}
.wlt-ql-grid {
    display: grid; grid-template-columns: 1fr 1fr;
}
.wlt-ql-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 0.45rem;
    padding: 1.1rem 0.5rem;
    text-decoration: none;
    border-right: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    font-family: 'Orbitron', monospace;
    font-size: 0.52rem; letter-spacing: 1px;
    text-transform: uppercase; color: var(--text-muted);
    transition: all 0.25s; position: relative; overflow: hidden;
}
.wlt-ql-item:nth-child(2n) { border-right: none; }
.wlt-ql-item:nth-child(3),
.wlt-ql-item:nth-child(4) { border-bottom: none; }
.wlt-ql-item::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(0,245,255,0.06), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.wlt-ql-item:hover::before { opacity: 1; }
.wlt-ql-item:hover { color: var(--cyan); }
.wlt-ql-icon {
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem; border: 1px solid var(--border);
    background: var(--surface2); color: var(--text-muted);
    transition: all 0.25s;
    clip-path: polygon(5px 0,100% 0,100% calc(100% - 5px),calc(100% - 5px) 100%,0 100%,0 5px);
}
.wlt-ql-item:hover .wlt-ql-icon {
    border-color: var(--cyan); color: var(--cyan);
    background: rgba(0,245,255,0.08);
    filter: drop-shadow(0 0 5px rgba(0,245,255,0.4));
}
.wlt-ql-item.ql-deposit .wlt-ql-icon { color: #34d399; border-color: rgba(52,211,153,0.3); background: rgba(52,211,153,0.07); }
.wlt-ql-item.ql-withdraw .wlt-ql-icon { color: var(--danger); border-color: rgba(248,113,113,0.3); background: rgba(248,113,113,0.07); }
.wlt-ql-item.ql-package .wlt-ql-icon { color: #60a5fa; border-color: rgba(96,165,250,0.3); background: rgba(96,165,250,0.07); }
.wlt-ql-item.ql-history .wlt-ql-icon { color: var(--cyan); border-color: rgba(0,245,255,0.3); background: rgba(0,245,255,0.07); }

/* PULSE */
.pulse { width: 8px; height: 8px; background: var(--cyan); border-radius: 50%;
         display: inline-block; animation: blink 1.5s infinite;
         box-shadow: 0 0 6px var(--cyan); flex-shrink: 0; }
@keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

/* ═══════════════════════════════════════════
   RESPONSIVE — TABLET ≤991px
═══════════════════════════════════════════ */
@media(max-width:991px){
    /* Right sidebar becomes a two-col flex row */
    .wlt-right { display: flex; flex-direction: row; flex-wrap: wrap; gap: 1rem; }
    .wlt-card-widget,
    .wlt-txn  { flex: 1 1 calc(50% - 0.5rem); min-width: 260px; }
    .wlt-ql   { flex: 1 1 100%; }
}

/* ═══════════════════════════════════════════
   RESPONSIVE — MOBILE ≤767px
═══════════════════════════════════════════ */
@media(max-width:767px){
    /* Hero: stack balance + mini-stats */
    .wlt-hero-inner    { flex-direction: column; }
    .wlt-bal-side      { border-right: none; border-bottom: 1px solid var(--border); padding: 1.5rem 1.25rem; }
    /* Mini stats go side-by-side */
    .wlt-mini-stats    { flex-direction: row; flex-wrap: wrap; min-width: unset; }
    .wlt-mini-item     { flex: 1 1 33%; border-right: 1px solid var(--border); border-bottom: none; min-width: 100px; padding: 0.85rem 1rem; }
    .wlt-mini-item:last-child { border-right: none; }
    /* Action buttons wrap */
    .wlt-bal-actions   { flex-wrap: wrap; gap: 0.5rem; }
    .wlt-action-btn    { font-size: 0.54rem; padding: 0.45rem 0.85rem; }
    /* Right col: single column */
    .wlt-right         { flex-direction: column; }
    .wlt-card-widget,
    .wlt-txn,
    .wlt-ql            { flex: 1 1 100%; min-width: unset; }
    /* Quick actions: 3 cols */
    .wlt-qa-grid       { grid-template-columns: repeat(3, 1fr); }
    /* Chart stats: 2 cols */
    .wlt-chart-stats   { grid-template-columns: 1fr 1fr; }
    .wcs-item:last-child { grid-column: 1/-1; border-right: none; border-top: 1px solid var(--border); }
    /* Table: hide balance-after column */
    .wlt-table th:nth-child(5),
    .wlt-table td:nth-child(5) { display: none; }
}

/* ═══════════════════════════════════════════
   RESPONSIVE — SMALL MOBILE ≤575px
═══════════════════════════════════════════ */
@media(max-width:575px){
    /* Balance hero */
    .wlt-bal-amount    { font-size: 1.5rem; }
    .wlt-bal-pre       { font-size: 0.45rem; letter-spacing: 3px; }
    .wlt-bal-side      { padding: 1.1rem 1rem; }
    /* Action buttons: shrink */
    .wlt-action-btn    { font-size: 0.5rem; padding: 0.4rem 0.7rem; gap: 0.3rem; }
    /* Mini stats: 2-col */
    .wlt-mini-item     { flex: 1 1 50%; }
    .wlt-mini-item:nth-child(2) { border-right: none; border-bottom: 1px solid var(--border); }
    .wlt-mini-item:nth-child(3) { flex: 1 1 100%; border-right: none; }
    .wlt-mini-value    { font-size: 0.82rem; }
    .wlt-mini-label    { font-size: 0.44rem; }
    /* Quick actions */
    .wlt-qa-grid       { grid-template-columns: repeat(3, 1fr); }
    .wlt-qa-item       { padding: 0.7rem 0.2rem; font-size: 0.44rem; letter-spacing: 0.5px; }
    .wlt-qa-icon       { width: 30px; height: 30px; font-size: 0.8rem; }
    /* Chart */
    .wlt-chart-wrap    { height: 120px; }
    .wlt-chart-head    { flex-direction: column; gap: 0.5rem; align-items: flex-start; }
    .wlt-chart-title   { font-size: 0.75rem; }
    .wlt-chart-stats   { grid-template-columns: 1fr; }
    .wcs-item          { border-right: none; border-bottom: 1px solid var(--border); }
    .wcs-item:last-child { border-bottom: none; border-top: none; }
    /* Transaction table */
    .wlt-table                  { font-size: 0.7rem; }
    .wlt-table th,
    .wlt-table td               { padding: 0.45rem 0.4rem; }
    .wlt-table th:nth-child(4),
    .wlt-table td:nth-child(4),
    .wlt-table th:nth-child(5),
    .wlt-table td:nth-child(5)  { display: none; }
    /* Badges */
    .wt-type   { font-size: 0.44rem; padding: 0.15rem 0.4rem; letter-spacing: 0.5px; }
    .wt-status { font-size: 0.44rem; padding: 0.15rem 0.4rem; }
    .wt-amount { font-size: 0.72rem; }
    /* Cyber card */
    .cyber-card        { padding: 1rem; margin: 0.75rem; }
    .cc-bal-val        { font-size: 1.1rem; }
    .cc-bal-lbl        { font-size: 0.44rem; }
    /* Quick links */
    .wlt-ql-grid       { grid-template-columns: 1fr 1fr; }
    .wlt-ql-item       { padding: 0.85rem 0.25rem; font-size: 0.46rem; }
    .wlt-cw-body       { padding: 0.75rem; }
    /* Txn rows in sidebar */
    .txn-ico           { width: 28px; height: 28px; font-size: 0.75rem; }
    .txn-type          { font-size: 0.55rem; }
    .txn-amt           { font-size: 0.72rem; }
}
</style>
@endsection

@section('content')
    @include('includes.header', ['pageTitle' => 'My Wallet'])

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

    {{-- ══ MAIN GRID ══ --}}
    <div class="wlt-grid">

        {{-- ════ LEFT COLUMN ════ --}}
        <div class="wlt-left">

            {{-- ── 1. BALANCE HERO ── --}}
            <div class="wlt-hero">
                <div class="wlt-hero-orb"></div>
                <div class="wlt-hero-inner">

                    {{-- Balance --}}
                    <div class="wlt-bal-side">
                        <div class="wlt-bal-pre">Total Balance</div>
                        <div class="wlt-bal-amount">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                        <div class="wlt-bal-sub">Current wallet balance</div>
                        <div class="wlt-bal-actions">
                            <a href="{{ route('wallet.deposit') }}" class="wlt-action-btn primary">
                                <i class="bi bi-plus-circle-fill"></i> Deposit
                            </a>
                            <a href="{{ route('withdraw.index') }}" class="wlt-action-btn">
                                <i class="bi bi-send-fill"></i> Withdraw
                            </a>
                            <a href="{{ route('wallet.transactions') }}" class="wlt-action-btn">
                                <i class="bi bi-clock-history"></i> History
                            </a>
                        </div>
                    </div>

                    {{-- Mini stats --}}
                    <div class="wlt-mini-stats">
                        <div class="wlt-mini-item avail-item">
                            <div class="wlt-mini-icon avail"><i class="bi bi-cash-coin"></i></div>
                            <div>
                                <div class="wlt-mini-label">Available</div>
                                <div class="wlt-mini-value">${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="wlt-mini-item locked-item">
                            <div class="wlt-mini-icon locked"><i class="bi bi-lock-fill"></i></div>
                            <div>
                                <div class="wlt-mini-label">Locked</div>
                                <div class="wlt-mini-value">${{ number_format($wallet->locked_balance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="wlt-mini-item earned-item">
                            <div class="wlt-mini-icon earned"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="wlt-mini-label">Total Earned</div>
                                <div class="wlt-mini-value">${{ number_format($wallet->total_earned ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── 2. SPENDING CHART ── --}}
            <div class="wlt-chart-card">
                <div class="wlt-chart-head">
                    <h2 class="wlt-chart-title">
                        <span class="pulse"></span> Spending Overview
                    </h2>
                    <div class="wlt-chart-filters">
                        <button class="wlt-filter-btn active" onclick="setFilter(this,'7d')">7D</button>
                        <button class="wlt-filter-btn" onclick="setFilter(this,'30d')">30D</button>
                        <button class="wlt-filter-btn" onclick="setFilter(this,'90d')">90D</button>
                    </div>
                </div>
                <div class="wlt-chart-body">
                    <div class="wlt-chart-wrap">
                        <canvas id="walletChart"></canvas>
                    </div>
                    <div class="wlt-chart-stats">
                        <div class="wcs-item">
                            <div class="wcs-label">Deposits</div>
                            <div class="wcs-val up">${{ number_format($wallet->total_deposits ?? 0, 2) }}</div>
                        </div>
                        <div class="wcs-item">
                            <div class="wcs-label">Withdrawals</div>
                            <div class="wcs-val down">${{ number_format($wallet->total_withdrawals ?? 0, 2) }}</div>
                        </div>
                        <div class="wcs-item">
                            <div class="wcs-label">Task Earnings</div>
                            <div class="wcs-val">${{ number_format($wallet->task_earnings ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── 3. QUICK ACTIONS BAR ── --}}
            <div class="wlt-qa">
                <div class="wlt-qa-head">
                    <div class="wlt-qa-title">Quick Actions</div>
                </div>
                <div class="wlt-qa-grid">
                    <a href="{{ route('wallet.deposit') }}" class="wlt-qa-item highlight">
                        <div class="wlt-qa-icon"><i class="bi bi-plus-circle-fill"></i></div>
                        Deposit
                    </a>
                    <a href="{{ route('withdraw.index') }}" class="wlt-qa-item">
                        <div class="wlt-qa-icon"><i class="bi bi-send-fill"></i></div>
                        Withdraw
                    </a>
                    <a href="{{ route('packages.index') }}" class="wlt-qa-item">
                        <div class="wlt-qa-icon"><i class="bi bi-box-seam-fill"></i></div>
                        Packages
                    </a>
                    <a href="{{ route('wallet.transactions') }}" class="wlt-qa-item">
                        <div class="wlt-qa-icon"><i class="bi bi-clock-history"></i></div>
                        History
                    </a>
                    <a href="#" class="wlt-qa-item">
                        <div class="wlt-qa-icon"><i class="bi bi-three-dots"></i></div>
                        More
                    </a>
                </div>
            </div>

            {{-- ── 4. TRANSACTIONS TABLE ── --}}
            <div class="wlt-txn">
                <div class="wlt-txn-header">
                    <h2 class="wlt-txn-htitle">
                        <span class="pulse"></span> Recent Transactions
                    </h2>
                    <a href="{{ route('wallet.transactions') }}" class="wlt-txn-viewall">View All</a>
                </div>

                <div style="overflow-x:auto;">
                    <table class="wlt-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th style="text-align:right">Amount</th>
                                <th style="text-align:right">Balance After</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                @php
                                    $dir = $txn->direction ?? ($txn->type === 'deposit' ? 'credit' : 'debit');
                                    $typeMap = [
                                        'deposit'    => ['icon' => 'bi-arrow-down-circle-fill', 'label' => 'Deposit'],
                                        'task'       => ['icon' => 'bi-check-circle-fill',      'label' => 'Task'],
                                        'package'    => ['icon' => 'bi-box-seam-fill',           'label' => 'Package'],
                                        'withdraw'   => ['icon' => 'bi-arrow-up-circle-fill',    'label' => 'Withdraw'],
                                        'referral'   => ['icon' => 'bi-people-fill',             'label' => 'Referral'],
                                        'adjustment' => ['icon' => 'bi-gear-fill',               'label' => 'Adjust'],
                                    ];
                                    $tm = $typeMap[$txn->type] ?? ['icon' => 'bi-circle-fill', 'label' => ucfirst($txn->type)];
                                    $sc = strtolower($txn->status ?? 'completed');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="wt-date">{{ $txn->created_at->format('d M Y') }}</div>
                                        <div class="wt-time">{{ $txn->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="wt-type {{ $txn->type }}">
                                            <i class="bi {{ $tm['icon'] }}"></i>{{ $tm['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="wt-desc">{{ Str::limit($txn->description ?? '—', 55) }}</div>
                                        @if ($txn->reference_id)
                                            <div class="wt-ref">{{ $txn->reference_type }} #{{ $txn->reference_id }}</div>
                                        @endif
                                    </td>
                                    <td style="text-align:right">
                                        <div class="wt-amount {{ $dir === 'credit' ? 'cr' : 'db' }}">
                                            {{ $dir === 'credit' ? '+' : '−' }}${{ number_format($txn->amount, 2) }}
                                        </div>
                                    </td>
                                    <td style="text-align:right">
                                        <div class="wt-bal">${{ number_format($txn->balance_after ?? 0, 2) }}</div>
                                    </td>
                                    <td>
                                        <span class="wt-status {{ $sc }}">
                                            <i class="bi {{ $sc === 'pending' ? 'bi-hourglass-split' : ($sc === 'rejected' || $sc === 'failed' ? 'bi-x-circle-fill' : 'bi-check-circle-fill') }}"></i>
                                            {{ ucfirst($sc) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="wlt-empty">
                                            <i class="bi bi-journal-x"></i>
                                            <p>No transactions yet</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- /wlt-left --}}

        {{-- ════ RIGHT SIDEBAR ════ --}}
        <div class="wlt-right">

            {{-- ── CYBER WALLET CARD ── --}}
            <div class="wlt-card-widget">
                <div class="wlt-cw-head">
                    <span class="wlt-cw-title">My Wallet</span>
                    <i class="bi bi-three-dots" style="color:var(--text-muted);cursor:pointer;font-size:.9rem;"></i>
                </div>
                <div class="wlt-cw-body">
                    <div class="cyber-card">
                        <div class="cyber-card-scan"></div>
                        <div class="cc-inner">
                            <div class="cc-top">
                                <div class="cc-brand">PayFlex</div>
                                <div class="cc-chip"></div>
                            </div>
                            <div class="cc-bal-label">Current Balance</div>
                            <div class="cc-bal-val">${{ number_format($wallet->balance ?? 0, 2) }}</div>
                            <div class="cc-bottom">
                                <div class="cc-user">{{ auth()->user()->name ?? 'User' }}</div>
                                <div class="cc-avail">
                                    <div class="cc-avail-lbl">Available</div>
                                    <div class="cc-avail-val">${{ number_format($wallet->available_balance ?? 0, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cc-details">
                        <div class="cc-detail-row">
                            <span class="cc-dk">Account ID</span>
                            <span class="cc-dv">•••• {{ str_pad(auth()->id() ?? '0', 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="cc-detail-row">
                            <span class="cc-dk">Status</span>
                            <span class="cc-dv" style="color:var(--cyan);">● Active</span>
                        </div>
                        <div class="cc-detail-row">
                            <span class="cc-dk">Locked Funds</span>
                            <span class="cc-dv" style="color:var(--warning);">${{ number_format($wallet->locked_balance ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── QUICK LINKS ── --}}
            <div class="wlt-ql">
                <div class="wlt-ql-head">Quick Links</div>
                <div class="wlt-ql-grid">
                    <a href="{{ route('wallet.deposit') }}" class="wlt-ql-item ql-deposit">
                        <div class="wlt-ql-icon"><i class="bi bi-plus-circle-fill"></i></div>
                        Deposit
                    </a>
                    <a href="{{ route('withdraw.index') }}" class="wlt-ql-item ql-withdraw">
                        <div class="wlt-ql-icon"><i class="bi bi-send-fill"></i></div>
                        Withdraw
                    </a>
                    <a href="{{ route('packages.index') }}" class="wlt-ql-item ql-package">
                        <div class="wlt-ql-icon"><i class="bi bi-box-seam-fill"></i></div>
                        Buy Package
                    </a>
                    <a href="{{ route('wallet.transactions') }}" class="wlt-ql-item ql-history">
                        <div class="wlt-ql-icon"><i class="bi bi-clock-history"></i></div>
                        History
                    </a>
                </div>
            </div>

            {{-- ── RECENT (compact) ── --}}
            <div class="wlt-txn">
                <div class="wlt-txn-header">
                    <h2 class="wlt-txn-htitle" style="font-size:.65rem;">
                        <span class="pulse"></span> Latest Activity
                    </h2>
                    <a href="{{ route('wallet.transactions') }}" class="wlt-txn-viewall">All</a>
                </div>
                @forelse($transactions->take(5) as $txn)
                    @php
                        $dir = $txn->direction ?? ($txn->type === 'deposit' ? 'credit' : 'debit');
                        $typeMap = [
                            'deposit'    => ['icon' => 'bi-arrow-down-circle-fill', 'label' => 'Deposit'],
                            'task'       => ['icon' => 'bi-check-circle-fill',      'label' => 'Task'],
                            'package'    => ['icon' => 'bi-box-seam-fill',           'label' => 'Package'],
                            'withdraw'   => ['icon' => 'bi-arrow-up-circle-fill',    'label' => 'Withdraw'],
                            'referral'   => ['icon' => 'bi-people-fill',             'label' => 'Referral'],
                            'adjustment' => ['icon' => 'bi-gear-fill',               'label' => 'Adjust'],
                        ];
                        $tm = $typeMap[$txn->type] ?? ['icon' => 'bi-circle-fill', 'label' => ucfirst($txn->type)];
                    @endphp
                    <div class="txn-row">
                        <div class="txn-ico {{ $dir }}">
                            <i class="bi {{ $tm['icon'] }}"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="txn-type">{{ $tm['label'] }}</div>
                            <div class="txn-date">{{ $txn->created_at->format('d M, h:i A') }}</div>
                        </div>
                        <div class="txn-amt {{ $dir }}">
                            {{ $dir === 'credit' ? '+' : '−' }}${{ number_format($txn->amount, 2) }}
                        </div>
                    </div>
                @empty
                    <div class="wlt-empty" style="padding:1.5rem;">
                        <i class="bi bi-journal-x"></i>
                        <p>No activity yet</p>
                    </div>
                @endforelse
            </div>

        </div>{{-- /wlt-right --}}
    </div>{{-- /wlt-grid --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── SPENDING CHART ──
const ctx = document.getElementById('walletChart');
if (ctx) {
    const labels = @json(collect($chartData ?? [])->pluck('label')->toArray());
    const income = @json(collect($chartData ?? [])->pluck('income')->toArray());
    const spend  = @json(collect($chartData ?? [])->pluck('spend')->toArray());

    const gradient1 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 160);
    gradient1.addColorStop(0, 'rgba(0,245,255,0.35)');
    gradient1.addColorStop(1, 'rgba(0,245,255,0)');

    const gradient2 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 160);
    gradient2.addColorStop(0, 'rgba(248,113,113,0.25)');
    gradient2.addColorStop(1, 'rgba(248,113,113,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            datasets: [
                {
                    label: 'Income',
                    data: income.length ? income : [120,200,180,250,310,270,400],
                    borderColor: '#00f5ff',
                    backgroundColor: gradient1,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#00f5ff',
                    pointBorderColor: '#000810',
                    pointBorderWidth: 2,
                    tension: 0.45, fill: true,
                },
                {
                    label: 'Spend',
                    data: spend.length ? spend : [80,120,100,160,200,140,180],
                    borderColor: '#f87171',
                    backgroundColor: gradient2,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#f87171',
                    pointBorderColor: '#000810',
                    pointBorderWidth: 2,
                    tension: 0.45, fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#011535',
                    borderColor: 'rgba(0,245,255,0.3)',
                    borderWidth: 1,
                    titleColor: '#00f5ff',
                    bodyColor: '#c8e8ff',
                    titleFont: { family: 'Orbitron', size: 10 },
                    bodyFont: { family: 'Rajdhani', size: 12 },
                    callbacks: {
                        label: ctx => ' $' + ctx.parsed.y.toFixed(2)
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,245,255,0.05)', drawBorder: false },
                    ticks: { color: '#3a5a7a', font: { family: 'Orbitron', size: 9 } }
                },
                y: {
                    grid: { color: 'rgba(0,245,255,0.05)', drawBorder: false },
                    ticks: { color: '#3a5a7a', font: { family: 'Orbitron', size: 9 },
                             callback: v => '$' + v }
                }
            }
        }
    });
}

// ── FILTER BUTTONS ──
function setFilter(btn, range) {
    document.querySelectorAll('.wlt-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // Emit a custom event or handle AJAX reload here
}
</script>
@endpush
