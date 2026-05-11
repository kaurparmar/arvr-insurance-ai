{{-- ============================================================
     vr.blade.php — LifeShield XR: Accident Simulation
     AR/VR Life Insurance Experience for ARLife∞
     Laravel Blade Template | Full Cinematic Scene Flow
     ============================================================ --}}
@props(['isAuthenticated' => auth()->check()])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LifeShield XR — Accident Simulation | ARLife∞</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/vr.css">
</head>

<style>
    [x-cloak] { display: none !important; }
    .overflow-hidden { overflow: hidden; }

    /* ── Hamburger ─────────────────────────────────────────── */
    .xr-hamburger {
        width: 36px; height: 36px;
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 5px;
        cursor: pointer; transition: all 0.2s;
        padding: 0;
        flex-shrink: 0;
        position: relative;
        z-index: 51;
    }
    .xr-hamburger:hover {
        border-color: rgba(0,240,255,0.35);
        background: rgba(0,240,255,0.06);
    }
    .xr-hamburger .bar {
        width: 16px; height: 1.5px;
        background: rgba(136,146,170,0.8);
        border-radius: 2px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
        display: block;
    }
    .xr-hamburger.open .bar:nth-child(1) {
        transform: translateY(6.5px) rotate(45deg);
        background: #00F0FF;
    }
    .xr-hamburger.open .bar:nth-child(2) {
        opacity: 0;
        transform: scaleX(0);
    }
    .xr-hamburger.open .bar:nth-child(3) {
        transform: translateY(-6.5px) rotate(-45deg);
        background: #00F0FF;
    }
    .xr-hamburger.open {
        border-color: rgba(0,240,255,0.4);
        background: rgba(0,240,255,0.08);
    }

    /* ── Mobile overlay ────────────────────────────────────── */
    .xr-mob-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.65);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 9998;
    }

    /* ── Mobile drawer ─────────────────────────────────────── */
    .xr-mob-drawer {
        position: fixed; right: 0; top: 0;
        height: 100dvh; width: min(300px, 82vw);
        background: rgba(5,10,22,0.99);
        border-left: 1px solid rgba(0,240,255,0.12);
        z-index: 9999;
        display: flex; flex-direction: column;
        backdrop-filter: blur(32px);
        -webkit-backdrop-filter: blur(32px);
        box-shadow: -20px 0 80px rgba(0,0,0,0.85);
    }
    .xr-mob-drawer::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,240,255,0.6), rgba(139,92,246,0.6), transparent);
    }

    .xr-mob-inner {
        display: flex; flex-direction: column;
        height: 100%;
        padding: 72px 20px 28px;
        overflow-y: auto;
        position: relative;
    }

    /* Close ✕ button */
    .xr-mob-close {
        position: absolute; top: 18px; right: 18px;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(136,146,170,0.7);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 15px;
        transition: all 0.2s; line-height: 1;
    }
    .xr-mob-close:hover {
        background: rgba(255,59,107,0.08);
        border-color: rgba(255,59,107,0.3);
        color: #FF3B6B;
    }

    .xr-mob-section-label {
        font-size: 9px; letter-spacing: 2.5px; text-transform: uppercase;
        color: rgba(136,146,170,0.45);
        padding: 0 12px; margin-bottom: 6px;
        font-family: 'DM Sans', sans-serif;
    }

    .xr-mob-link {
        display: flex; align-items: center; gap: 12px;
        padding: 11px 14px; border-radius: 11px;
        font-size: 14px; font-weight: 600;
        color: rgba(136,146,170,0.85);
        text-decoration: none;
        transition: all 0.2s;
        font-family: 'DM Sans', sans-serif;
        position: relative;
    }
    .xr-mob-link:hover { color: #EEF2FF; background: rgba(255,255,255,0.04); }
    .xr-mob-link.active { color: #00F0FF; background: rgba(0,240,255,0.07); }
    .xr-mob-link .mob-icon { font-size: 15px; width: 22px; text-align: center; }

    .xr-mob-link.ar-highlight {
        color: #A78BFA;
        background: rgba(139,92,246,0.07);
        border: 1px solid rgba(139,92,246,0.15);
    }
    .xr-mob-link.ar-highlight:hover {
        background: rgba(139,92,246,0.12);
        border-color: rgba(139,92,246,0.3);
    }

    .xr-mob-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 14px 0; }
    .xr-mob-auth { margin-top: auto; }

    .xr-mob-user-card {
        display: flex; align-items: center; gap: 12px;
        padding: 14px; border-radius: 12px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        margin-bottom: 12px;
    }
    .xr-mob-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, #00F0FF, #8B5CF6);
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: 700; color: #020F14;
        font-family: 'Syne', sans-serif; flex-shrink: 0;
    }
    .xr-mob-user-info { overflow: hidden; }
    .xr-mob-user-name { font-size: 14px; font-weight: 600; color: #EEF2FF; }
    .xr-mob-user-tag  { font-size: 10px; color: rgba(0,240,255,0.6); letter-spacing: 1px; text-transform: uppercase; }

    .xr-mob-btn-login {
        display: block; width: 100%; padding: 12px;
        border-radius: 10px; text-align: center;
        font-size: 14px; font-weight: 700;
        border: 1px solid rgba(0,240,255,0.3);
        color: #00F0FF; text-decoration: none;
        transition: all 0.2s; margin-bottom: 10px;
        font-family: 'DM Sans', sans-serif;
        background: rgba(0,240,255,0.06);
    }
    .xr-mob-btn-login:hover { background: rgba(0,240,255,0.12); border-color: rgba(0,240,255,0.5); }

    .xr-mob-btn-register {
        display: block; width: 100%; padding: 12px;
        border-radius: 10px; text-align: center;
        font-size: 14px; font-weight: 700;
        background: linear-gradient(135deg, #00F0FF, #8B5CF6);
        color: #020F14; text-decoration: none;
        transition: all 0.2s; margin-bottom: 10px;
        font-family: 'DM Sans', sans-serif; border: none;
        box-shadow: 0 0 20px rgba(0,240,255,0.2);
    }
    .xr-mob-btn-register:hover { box-shadow: 0 0 32px rgba(0,240,255,0.4); }

    .xr-mob-logout-btn {
        width: 100%; padding: 11px;
        border-radius: 10px; text-align: center;
        font-size: 13px; font-weight: 700;
        background: rgba(255,59,107,0.08);
        border: 1px solid rgba(255,59,107,0.2);
        color: #FF3B6B; cursor: pointer;
        transition: all 0.2s; font-family: 'DM Sans', sans-serif;
    }
    .xr-mob-logout-btn:hover { background: rgba(255,59,107,0.15); border-color: rgba(255,59,107,0.4); }
</style>

<body>

<!-- Custom cursor -->
<div id="cursor-dot"></div>
<div id="cursor-ring"></div>

<!-- VR visual overlays -->
<div class="vr-scanlines"></div>
<div class="vr-vignette"></div>

<!-- Flash transition overlay -->
<div id="flash-overlay"></div>

<!-- Particle canvas -->
<canvas id="particle-canvas"></canvas>

<!-- Progress bar -->
<div id="xr-progress">
    <div id="xr-progress-fill"></div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ALPINE ROOT
     The hamburger button and the drawer MUST both live inside
     this single x-data div so they share the same `open` var.
     ══════════════════════════════════════════════════════════ --}}
<div
    x-data="{ open: false }"
    x-init="
        $watch('open', v => {
            v
                ? document.body.classList.add('overflow-hidden')
                : document.body.classList.remove('overflow-hidden');
        });
    "
    @keydown.escape.window="open = false">

    {{-- ── NAVIGATION HUD ──────────────────────────────────── --}}
    {{--
        IMPORTANT: #xr-nav must NOT have transform/filter/will-change
        CSS properties — they create a new stacking context and trap
        the drawer (z-index:95) beneath the nav (z-index:50).
        Set only position + z-index here.
    --}}
    <nav id="xr-nav" style="position:relative; z-index:50;">
        <div class="nav-logo">Life<span>Shield</span> XR</div>
        <div class="nav-badge">XR Simulation Live</div>
        <div class="nav-scene-indicator">
            Scene <span id="current-scene-num">1</span> of 7
        </div>

        {{-- Hamburger — INSIDE Alpine root so @click has scope --}}
        <button
            @click="open = !open"
            :class="open ? 'open' : ''"
            class="xr-hamburger"
            type="button"
            aria-label="Toggle menu"
            :aria-expanded="open.toString()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </nav>

    {{-- ══════════════════════════════════════════════════════
         MOBILE DRAWER
         Also INSIDE Alpine root — shares the same `open` state.
         No md:hidden here — Alpine x-show handles visibility.
         ══════════════════════════════════════════════════════ --}}
    <div x-cloak>

        {{-- Backdrop overlay --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="xr-mob-overlay">
        </div>

        {{-- Drawer panel — slide + fade in from right --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-220 transform"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="xr-mob-drawer">

            <div class="xr-mob-inner">

                {{-- Explicit close ✕ button --}}
                <button
                    @click="open = false"
                    class="xr-mob-close"
                    type="button"
                    aria-label="Close menu">✕</button>

                <!-- Navigation links -->
                <div class="xr-mob-section-label">Navigation</div>

                <a href="{{ route('home') }}"        @click="open = false" class="xr-mob-link">
                    <span class="mob-icon">⌂</span> Home
                </a>
                <a href="{{ route('about') }}"       @click="open = false" class="xr-mob-link">
                    <span class="mob-icon">◇</span> About
                </a>
                <a href="{{ route('plans.index') }}" @click="open = false" class="xr-mob-link">
                    <span class="mob-icon">☰</span> Plans
                </a>
                <a href="{{ route('vr') }}"          @click="open = false" class="xr-mob-link ar-highlight">
                    <span class="mob-icon">◈</span> AR Demo
                    <span style="margin-left:auto;font-size:9px;letter-spacing:1.5px;color:rgba(139,92,246,0.7);text-transform:uppercase;">Live</span>
                </a>
                <a href="{{ route('contact') }}"     @click="open = false" class="xr-mob-link">
                    <span class="mob-icon">✉</span> Contact
                </a>

                <div class="xr-mob-divider"></div>

                <!-- Auth section -->
                <div class="xr-mob-auth">

                    @if($isAuthenticated)
                        <div class="xr-mob-user-card">
                            <div class="xr-mob-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="xr-mob-user-info">
                                <div class="xr-mob-user-name">{{ Auth::user()->name }}</div>
                                <div class="xr-mob-user-tag">● Policy Active</div>
                            </div>
                        </div>

                        <div class="xr-mob-section-label" style="margin-top:4px;">Account</div>
                        <a href="{{ route('dashboard') }}"    @click="open = false" class="xr-mob-link">
                            <span class="mob-icon">⬡</span> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" @click="open = false" class="xr-mob-link">
                            <span class="mob-icon">◎</span> My Profile
                        </a>

                        <div class="xr-mob-divider"></div>

                        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="xr-mob-logout-btn">⏻ &nbsp;Sign Out</button>
                        </form>

                    @else
                        <div class="xr-mob-section-label">Get Started</div>
                        <a href="{{ route('login') }}"    class="xr-mob-btn-login">Login</a>
                        <a href="{{ route('register') }}" class="xr-mob-btn-register">Create Account →</a>
                    @endif

                </div>{{-- /.xr-mob-auth --}}
            </div>{{-- /.xr-mob-inner --}}
        </div>{{-- /.xr-mob-drawer --}}
    </div>{{-- /x-cloak --}}

</div>{{-- /Alpine root — ends here, BEFORE scene-wrapper --}}


{{-- ═══════════════════════════════════════════════════════════
     SCENE WRAPPER (outside Alpine root — no Alpine needed here)
     ══════════════════════════════════════════════════════════ --}}
<div id="scene-wrapper">

    {{-- ── SCENE 1: WELCOME / PEACEFUL HOME ──────────────── --}}
    <div class="xr-scene active" id="scene-1">
        <div class="scene-bg s1-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-cyan">Scene 01 &mdash; Introduction</span>

            <h1 class="scene-title">
                Welcome to<br><span class="grad-cyan">LifeShield XR</span>
            </h1>

            <div class="home-scene-3d">
                <div class="orbit-ring" style="width:320px;height:320px;top:50%;left:50%;margin-top:-160px;margin-left:-160px;animation-duration:12s;"></div>
                <div class="ar-float ar-float-1">📍 Your Home</div>
                <div class="ar-float ar-float-2" style="color:var(--emerald)">✓ Family Protected</div>
                <div class="floor-plane"></div>
                <div class="tv-set">
                    <div class="tv-screen"><div class="tv-scanline"></div></div>
                    <div class="tv-stand"></div>
                </div>
                <div class="sofa">
                    <div class="sofa-arm left"></div>
                    <div class="sofa-arm right"></div>
                    <div class="sofa-back"></div>
                    <div class="sofa-base"></div>
                </div>
                <div class="person p-dad">
                    <div class="person-head"></div>
                    <div class="person-body" style="width:28px;height:36px;background:linear-gradient(180deg,#1E3A5F,#162D4A);border-radius:4px 4px 0 0;"></div>
                </div>
                <div class="person p-mom">
                    <div class="person-head" style="width:20px;height:20px;background:#4A1E5F;border:2px solid var(--violet);border-radius:50%;"></div>
                    <div class="person-body" style="width:26px;height:34px;background:linear-gradient(180deg,#4A1E5F,#381550);border-radius:4px 4px 0 0;"></div>
                </div>
                <div class="person p-kid">
                    <div class="person-head" style="width:16px;height:16px;background:#1E4A3A;border:2px solid var(--emerald);border-radius:50%;"></div>
                    <div class="person-body" style="width:20px;height:28px;background:linear-gradient(180deg,#1E4A3A,#143828);border-radius:4px 4px 0 0;"></div>
                </div>
            </div>

            <p class="scene-subtitle">
                This immersive simulation helps you understand the real financial impact of unexpected life events — and how <strong style="color:var(--cyan)">LifeShield XR</strong> protects everything you've built.
            </p>

            <div class="narration-box">
                Welcome to LifeShield XR. This simulation helps you understand the financial impact of unexpected life events — and how insurance transforms uncertainty into security.
            </div>
        </div>
    </div>

    {{-- ── SCENE 2: CINEMATIC ACCIDENT ANIMATION ──────────── --}}
    <div class="xr-scene" id="scene-2">
        <div class="scene-bg s2-bg"></div>

        <canvas id="crash-canvas"></canvas>

        <div id="crash-hud">
            <div id="crash-chip" class="scene-chip chip-rose" style="opacity:0">Scene 02 &mdash; Incident</div>

            <div id="ar-speed-hud">
                <div class="ar-hud-panel" id="hud-speed">
                    <div class="hud-label">VEHICLE SPEED</div>
                    <div class="hud-value" id="speed-val">87 <span>km/h</span></div>
                </div>
                <div class="ar-hud-panel" id="hud-impact" style="opacity:0">
                    <div class="hud-label">⚠ IMPACT FORCE</div>
                    <div class="hud-value" style="color:var(--rose)">CRITICAL</div>
                </div>
                <div class="ar-hud-panel" id="hud-sos" style="opacity:0;border-color:rgba(255,59,107,0.6)">
                    <div class="hud-label" style="color:var(--rose)">🚨 SOS SIGNAL</div>
                    <div class="hud-value" style="color:var(--rose);font-size:14px">EMERGENCY DISPATCHED</div>
                </div>
            </div>

            <div id="crash-title-wrap" style="opacity:0;pointer-events:none">
                <div class="crash-incident-label">INCIDENT RECORDED — 11:47 PM</div>
                <div class="crash-main-title">ACCIDENT<br><span>OCCURRED</span></div>
                <div class="crash-sub">National Highway 44 · Critical Severity · ICU Response</div>
            </div>
        </div>

        <div id="crash-stats-row" style="opacity:0">
            <div class="cstat">
                <div class="cstat-label">Response Time</div>
                <div class="cstat-val" style="color:var(--rose)">8 min</div>
            </div>
            <div class="cstat-div"></div>
            <div class="cstat">
                <div class="cstat-label">Severity</div>
                <div class="cstat-val" style="color:var(--amber)">Critical</div>
            </div>
            <div class="cstat-div"></div>
            <div class="cstat">
                <div class="cstat-label">Admission</div>
                <div class="cstat-val" style="color:var(--cyan)">ICU</div>
            </div>
        </div>

        <div class="narration-box" id="crash-narration" style="border-left-color:var(--rose);opacity:0;position:relative;z-index:20;margin:0 auto;max-width:600px;width:90%">
            In real life, accidents can happen anytime — a split second changes everything. This is the financial reality your family faces without protection.
        </div>
    </div>

    {{-- ── SCENE 3: EXPENSES RISING ───────────────────────── --}}
    <div class="xr-scene" id="scene-3">
        <div class="scene-bg s3-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-amber">Scene 03 &mdash; Financial Impact</span>

            <h2 class="scene-title" style="font-size:clamp(26px,4vw,48px)">
                Medical Bills Are <span class="grad-rose">Rising Fast</span>
            </h2>

            <div class="bills-container">
                <div class="bill-card">
                    <div class="bill-label">
                        <div class="bill-icon bi-amber">🏥</div>
                        <div>
                            <div class="bill-name">Ambulance + Emergency Admission</div>
                            <div class="bill-sub">Apollo Hospital, ICU — Day 1</div>
                        </div>
                    </div>
                    <div class="bill-amount amber">₹<span class="counter" data-target="50000">0</span></div>
                </div>
                <div class="bill-card">
                    <div class="bill-label">
                        <div class="bill-icon bi-rose">🔬</div>
                        <div>
                            <div class="bill-name">Surgery + Operation Theatre</div>
                            <div class="bill-sub">Spinal surgery, 6-hour procedure</div>
                        </div>
                    </div>
                    <div class="bill-amount rose">₹<span class="counter" data-target="200000">0</span></div>
                </div>
                <div class="bill-card">
                    <div class="bill-label">
                        <div class="bill-icon bi-violet">💊</div>
                        <div>
                            <div class="bill-name">ICU Care + Medicines</div>
                            <div class="bill-sub">7-day intensive care unit stay</div>
                        </div>
                    </div>
                    <div class="bill-amount" style="color:var(--violet)">₹<span class="counter" data-target="150000">0</span></div>
                </div>
                <div class="bill-card" style="background:rgba(0,240,255,0.04)">
                    <div class="bill-label">
                        <div class="bill-icon bi-cyan">📋</div>
                        <div>
                            <div class="bill-name" style="font-size:16px;font-weight:700">Total Medical Expenses</div>
                            <div class="bill-sub">As of Day 7 — costs still accruing</div>
                        </div>
                    </div>
                    <div class="bill-amount total">₹<span class="counter" data-target="500000">0</span></div>
                </div>
            </div>

            <div class="narration-box" style="border-left-color:var(--amber)">
                Medical expenses escalate rapidly — ambulance, surgery, ICU, medicines, rehabilitation. Within one week, bills exceed ₹5,00,000 and continue rising.
            </div>
        </div>
    </div>

    {{-- ── SCENE 4: WITHOUT INSURANCE ─────────────────────── --}}
    <div class="xr-scene" id="scene-4">
        <div class="scene-bg s4-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-rose">Scene 04 &mdash; No Coverage</span>

            <h2 class="scene-title">
                <span class="grad-rose">Without Insurance</span><br>The Family Breaks
            </h2>

            <div class="crisis-banner">
                <div style="font-size:12px;color:var(--rose);letter-spacing:2px;text-transform:uppercase;margin-bottom:8px;">Total Burden on Family</div>
                <div class="crisis-number">₹5,00,000</div>
                <div style="font-size:14px;color:var(--text-mid);margin-top:6px;">Drawn entirely from personal savings</div>
            </div>

            <div class="worry-grid">
                <div class="worry-card"><div class="worry-icon">🏦</div><div class="worry-label">Savings Wiped</div><div class="worry-val">Entire emergency fund depleted in 7 days</div></div>
                <div class="worry-card"><div class="worry-icon">💸</div><div class="worry-label">Loans Taken</div><div class="worry-val">₹2L personal loan @ 24% interest</div></div>
                <div class="worry-card"><div class="worry-icon">🏠</div><div class="worry-label">Asset Risk</div><div class="worry-val">Home mortgage at risk of default</div></div>
                <div class="worry-card"><div class="worry-icon">🎓</div><div class="worry-label">Child's Future</div><div class="worry-val">Education fund diverted to medical bills</div></div>
                <div class="worry-card"><div class="worry-icon">😰</div><div class="worry-label">Mental Health</div><div class="worry-val">Chronic stress, family conflict, anxiety</div></div>
                <div class="worry-card"><div class="worry-icon">⏰</div><div class="worry-label">Recovery Time</div><div class="worry-val">5–7 years to rebuild financial stability</div></div>
            </div>

            <div class="narration-box" style="border-left-color:var(--rose)">
                Without insurance, the entire ₹5,00,000 burden falls on personal savings. Emergency loans, asset liquidation, and years of financial setback — all from a single unexpected event.
            </div>
        </div>
    </div>

    {{-- ── SCENE 5: WITH INSURANCE TRANSFORMATION ─────────── --}}
    <div class="xr-scene" id="scene-5">
        <div class="scene-bg s5-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-green">Scene 05 &mdash; Transformation</span>

            <div class="transform-state-before" id="before-insurance">
                <h2 class="scene-title" style="text-align:center">Now Watch What<br><span class="grad-green">Insurance Does</span></h2>
                <p class="scene-subtitle" style="opacity:1">Tap the button below to activate your LifeShield XR policy and see how it transforms the financial reality.</p>
                <div class="apply-btn-wrap">
                    <button class="apply-btn" onclick="applyInsurance()">🛡️ Apply LifeShield XR Insurance</button>
                </div>
            </div>

            <div class="transform-state-after" id="after-insurance" style="width:100%;display:flex;flex-direction:column;align-items:center;gap:24px;">
                <span class="scene-chip chip-green" style="opacity:1;transform:none">✓ Insurance Activated</span>
                <h2 class="scene-title" style="text-align:center;opacity:1;transform:none"><span class="grad-green">₹4,00,000 Covered.</span><br>Peace Restored.</h2>

                <div class="transform-panel">
                    <div class="coverage-row" style="opacity:1;transform:none">
                        <div class="cr-label"><span style="font-size:18px">📋</span> Total Medical Bill</div>
                        <div class="cr-val" style="color:var(--text-mid)">₹5,00,000</div>
                    </div>
                    <div class="coverage-row" style="opacity:1;transform:none">
                        <div class="cr-label"><span style="font-size:18px">🛡️</span> Covered by LifeShield XR</div>
                        <div class="cr-val green">– ₹4,00,000</div>
                    </div>
                    <div class="coverage-row" style="opacity:1;transform:none;background:rgba(0,240,255,0.04)">
                        <div class="cr-label"><span style="font-size:18px">💳</span> Your Payable Amount</div>
                        <div class="cr-val cyan">₹1,00,000</div>
                    </div>
                </div>

                <div class="coverage-bar-wrap" style="opacity:1">
                    <div class="coverage-bar-label">
                        <span>Insurance covers</span>
                        <span style="color:var(--emerald);font-weight:600">80% of total cost</span>
                    </div>
                    <div class="coverage-bar-track">
                        <div class="coverage-bar-fill" id="coverage-fill"></div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;width:100%;max-width:560px;">
                    <div style="background:var(--emerald-dim);border:1px solid rgba(0,230,118,0.2);border-radius:var(--r-md);padding:14px;text-align:center;">
                        <div style="font-size:11px;color:var(--text-mid);margin-bottom:4px">Savings Safe</div>
                        <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:var(--emerald)">100%</div>
                    </div>
                    <div style="background:var(--cyan-dim);border:1px solid rgba(0,240,255,0.2);border-radius:var(--r-md);padding:14px;text-align:center;">
                        <div style="font-size:11px;color:var(--text-mid);margin-bottom:4px">Claim Settled</div>
                        <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:var(--cyan)">47 sec</div>
                    </div>
                    <div style="background:var(--violet-dim);border:1px solid rgba(139,92,246,0.2);border-radius:var(--r-md);padding:14px;text-align:center;">
                        <div style="font-size:11px;color:var(--text-mid);margin-bottom:4px">Stress Level</div>
                        <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:var(--violet)">Minimal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SCENE 6: RESOLUTION ─────────────────────────────── --}}
    <div class="xr-scene" id="scene-6">
        <div class="scene-bg s6-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-green">Scene 06 &mdash; Resolution</span>

            <h2 class="scene-title">Family Secure.<br><span class="grad-green">Future Protected.</span></h2>

            <div class="resolution-visual">
                <div class="orbit-ring" style="width:200px;height:200px;top:50%;left:50%;margin-top:-100px;margin-left:-100px;">
                    <div class="orbit-dot"></div>
                </div>
                <div class="orbit-ring" style="width:260px;height:260px;top:50%;left:50%;margin-top:-130px;margin-left:-130px;animation-duration:14s;animation-direction:reverse;">
                    <div class="orbit-dot" style="background:var(--cyan);box-shadow:0 0 8px var(--cyan)"></div>
                </div>
                <div class="shield-main">
                    <svg class="shield-svg" viewBox="0 0 120 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M60 8 L108 28 L108 72 C108 102 60 132 60 132 C60 132 12 102 12 72 L12 28 Z" fill="rgba(0,230,118,0.08)" stroke="url(#shield-grad)" stroke-width="2"/>
                        <defs>
                            <linearGradient id="shield-grad" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#00E676"/><stop offset="100%" stop-color="#00F0FF"/>
                            </linearGradient>
                        </defs>
                        <text x="60" y="82" text-anchor="middle" font-size="42" fill="#00E676">✓</text>
                    </svg>
                </div>
                <div class="checkmark-circle">✓</div>
                <div class="resolved-family">
                    <div class="rf-person">
                        <div class="rf-head" style="width:22px;height:22px;background:#1E3A5F;"></div>
                        <div class="rf-body" style="width:28px;height:36px;background:linear-gradient(180deg,#1E3A5F,#162D4A);border-radius:4px 4px 0 0;"></div>
                    </div>
                    <div class="rf-person">
                        <div class="rf-head" style="width:20px;height:20px;background:#4A1E5F;border-color:var(--violet);"></div>
                        <div class="rf-body" style="width:26px;height:34px;background:linear-gradient(180deg,#4A1E5F,#381550);border-radius:4px 4px 0 0;"></div>
                    </div>
                    <div class="rf-person">
                        <div class="rf-head" style="width:16px;height:16px;background:#1E4A3A;"></div>
                        <div class="rf-body" style="width:20px;height:28px;background:linear-gradient(180deg,#1E4A3A,#143828);border-radius:4px 4px 0 0;"></div>
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;align-items:center;gap:8px;opacity:0;animation:fadeUp 0.6s 0.6s forwards;">
                <div style="font-family:'Syne',sans-serif;font-size:clamp(22px,4vw,40px);font-weight:700;color:var(--emerald)">Secure Your Future Today</div>
                <div style="font-size:16px;color:var(--text-mid);max-width:480px;text-align:center;line-height:1.6">Insurance transforms uncertainty into security. One small monthly premium — infinite peace of mind.</div>
            </div>

            <div style="display:flex;gap:20px;flex-wrap:wrap;justify-content:center;opacity:0;animation:fadeUp 0.6s 1s forwards;">
                <div style="text-align:center">
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--cyan)">₹1,299</div>
                    <div style="font-size:12px;color:var(--text-mid)">monthly premium</div>
                </div>
                <div style="width:1px;background:var(--border-w)"></div>
                <div style="text-align:center">
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--emerald)">₹1 Cr</div>
                    <div style="font-size:12px;color:var(--text-mid)">sum assured</div>
                </div>
                <div style="width:1px;background:var(--border-w)"></div>
                <div style="text-align:center">
                    <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:700;color:var(--violet)">30 yrs</div>
                    <div style="font-size:12px;color:var(--text-mid)">coverage term</div>
                </div>
            </div>

            <div class="narration-box" style="border-left-color:var(--emerald)">
                Insurance transforms uncertainty into security. With LifeShield XR, your family's future is protected — no matter what happens. A single premium, infinite protection.
            </div>
        </div>
    </div>

    {{-- ── SCENE 7: CALL TO ACTION ─────────────────────────── --}}
    <div class="xr-scene" id="scene-7">
        <div class="scene-bg s7-bg"></div>
        <div class="scene-content">
            <span class="scene-chip chip-violet">Scene 07 &mdash; Take Action</span>

            <h2 class="scene-title">Your Protection<br><span class="grad-violet">Starts Now</span></h2>
            <p class="scene-subtitle">Choose how you want to continue. Plans tailored for every life, every budget.</p>

            <div class="cta-grid">
                <div class="cta-card">
                    <span class="cta-emoji">📋</span>
                    <div class="cta-title">View Plans</div>
                    <div class="cta-desc">Explore all coverage options — from Starter Shield to Wealth Guardian. AR-powered comparison included.</div>
                    <button class="cta-btn cta-btn-cyan" onclick="window.location.href='{{ route('plans.index') }}'">Explore Plans →</button>
                </div>
                <div class="cta-card">
                    <span class="cta-emoji">🧮</span>
                    <div class="cta-title">Calculate Premium</div>
                    <div class="cta-desc">Enter your age, income, and lifestyle. Get an instant premium quote powered by AI underwriting.</div>
                    <button class="cta-btn cta-btn-violet" onclick="window.location.href='{{ route('calculator') }}'">Get My Quote →</button>
                </div>
                <div class="cta-card">
                    <span class="cta-emoji">🛡️</span>
                    <div class="cta-title">Buy Policy Now</div>
                    <div class="cta-desc">Instant issuance. No medical tests up to ₹1 Cr. Policy in your inbox in under 60 seconds.</div>
                   <button class="cta-btn cta-btn-green" onclick="window.location.href='{{ route('policies.index') }}'">
    Buy Instantly →
</button>
</div>
            </div>

            <div style="display:flex;gap:32px;flex-wrap:wrap;justify-content:center;margin-top:8px;opacity:0;animation:fadeUp 0.6s 1.2s forwards">
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-mid)"><span style="color:var(--emerald)">✓</span> IRDAI Registered</div>
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-mid)"><span style="color:var(--emerald)">✓</span> 98.7% Claim Settlement</div>
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-mid)"><span style="color:var(--emerald)">✓</span> 2.4M+ Policyholders</div>
                <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-mid)"><span style="color:var(--emerald)">✓</span> Blockchain Secured</div>
            </div>

            <div class="narration-box" style="border-left-color:var(--violet)">
                Explore plans tailored to your needs, calculate your exact premium, or buy instantly — protection in under 60 seconds. Your family deserves LifeShield XR.
            </div>
        </div>
    </div>

</div>{{-- /#scene-wrapper --}}

<!-- ─── SCENE CONTROLS ──────────────────────────────────────── -->
<div id="scene-controls">
    <button class="ctrl-btn" id="btn-prev" onclick="changeScene(-1)" disabled>← Prev</button>
    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
        <div class="scene-dots" id="scene-dots"></div>
        <div id="scene-title-hud">Welcome</div>
    </div>
    <button class="ctrl-btn primary" id="btn-next" onclick="changeScene(1)">Next →</button>
</div>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="/js/vr.js"></script>

</body>
</html>