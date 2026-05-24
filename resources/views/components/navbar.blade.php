@props([
    'isAuthenticated' => auth()->check(),
    'isAdmin' => auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()))
])

<style>
    [x-cloak] { display: none !important; }
    .overflow-hidden { overflow: hidden; }

    /* ── Navbar base ──────────────────────────────────────── */
    .xr-nav {
        position: sticky;
        top: 0;
        z-index: 50;
        height: 64px;
        display: flex;
        align-items: center;
        background: rgba(3, 6, 15, 0.82);
        border-bottom: 1px solid rgba(0, 240, 255, 0.1);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        box-shadow: 0 1px 0 rgba(0,240,255,0.06), 0 8px 32px rgba(0,0,0,0.5);
    }

    /* Animated top-border glow line */
    .xr-nav::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg,
            transparent 0%,
            rgba(139,92,246,0.6) 20%,
            rgba(0,240,255,0.9) 50%,
            rgba(139,92,246,0.6) 80%,
            transparent 100%);
        background-size: 200% 100%;
        animation: navGlow 4s linear infinite;
    }
    @keyframes navGlow {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Subtle scan-line texture */
    .xr-nav::after {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            0deg, transparent, transparent 2px,
            rgba(0,240,255,0.012) 2px, rgba(0,240,255,0.012) 4px
        );
        pointer-events: none;
    }

    /* ── Logo ──────────────────────────────────────────────── */
    .xr-logo-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        position: relative;
    }
    .xr-logo-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(0,240,255,0.15), rgba(139,92,246,0.2));
        border: 1px solid rgba(0,240,255,0.3);
        display: flex; align-items: center; justify-content: center;
        position: relative;
        box-shadow: 0 0 16px rgba(0,240,255,0.15), inset 0 1px 0 rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }
    .xr-logo-icon:hover {
        box-shadow: 0 0 24px rgba(0,240,255,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
        border-color: rgba(0,240,255,0.5);
    }
    .xr-logo-icon span {
        font-family: 'Syne', sans-serif;
        font-weight: 800; font-size: 13px;
        background: linear-gradient(135deg, #00F0FF, #8B5CF6);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.5px;
    }
    /* Pulse ring on logo */
    .xr-logo-icon::after {
        content: '';
        position: absolute; inset: -3px;
        border-radius: 13px;
        border: 1px solid rgba(0,240,255,0.2);
        animation: logoPulse 3s ease-in-out infinite;
    }
    @keyframes logoPulse {
        0%,100% { opacity: 0.4; transform: scale(1); }
        50%      { opacity: 1;   transform: scale(1.06); }
    }

    .xr-logo-text {
        display: flex; flex-direction: column; line-height: 1;
    }
    .xr-logo-name {
        font-family: 'Syne', sans-serif;
        font-weight: 800; font-size: 16px; letter-spacing: -0.5px;
        color: #EEF2FF;
    }
    .xr-logo-name .cyan { color: #00F0FF; }
    .xr-logo-sub {
        font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
        color: rgba(136,146,170,0.7);
        font-family: 'DM Sans', sans-serif;
        margin-top: 1px;
    }

    /* ── Desktop nav links ─────────────────────────────────── */
    /* FIX: moved .xr-mob-close out of desktop nav section — it belongs to mobile drawer */
    .xr-mob-close {
        position: absolute; top: 18px; right: 18px;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(136,146,170,0.7);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 15px;
        transition: all 0.2s; line-height: 1;
        z-index: 10;
    }
    .xr-mob-close:hover {
        background: rgba(255,59,107,0.08);
        border-color: rgba(255,59,107,0.3);
        color: #FF3B6B;
    }
    .xr-nav-link {
        position: relative;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px; font-weight: 500;
        color: rgba(136,146,170,0.85);
        text-decoration: none;
        transition: color 0.2s, background 0.2s;
        font-family: 'DM Sans', sans-serif;
        letter-spacing: 0.1px;
        white-space: nowrap;
    }
    .xr-nav-link:hover {
        color: #EEF2FF;
        background: rgba(255,255,255,0.04);
    }
    .xr-nav-link.active {
        color: #00F0FF;
        background: rgba(0,240,255,0.06);
    }
    /* Active underline glow */
    .xr-nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 14px; right: 14px;
        height: 1.5px;
        background: #00F0FF;
        border-radius: 1px;
        box-shadow: 0 0 6px #00F0FF;
    }
    /* Hover indicator */
    .xr-nav-link::before {
        content: '';
        position: absolute;
        bottom: -1px; left: 50%; right: 50%;
        height: 1.5px;
        background: rgba(0,240,255,0.4);
        border-radius: 1px;
        transition: left 0.2s ease, right 0.2s ease;
    }
    .xr-nav-link:hover:not(.active)::before {
        left: 14px; right: 14px;
    }

    /* AR Demo link — special highlight */
    .xr-nav-link.ar-demo {
        color: #8B5CF6;
        border: 1px solid rgba(139,92,246,0.2);
        background: rgba(139,92,246,0.06);
    }
    .xr-nav-link.ar-demo:hover {
        background: rgba(139,92,246,0.12);
        border-color: rgba(139,92,246,0.4);
        color: #A78BFA;
        box-shadow: 0 0 16px rgba(139,92,246,0.15);
    }

    /* ── Theme toggle ──────────────────────────────────────── */
    .xr-theme-btn {
        width: 36px; height: 36px;
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.25s;
        color: rgba(136,146,170,0.8);
        flex-shrink: 0;
    }
    .xr-theme-btn:hover {
        border-color: rgba(0,240,255,0.35);
        background: rgba(0,240,255,0.08);
        color: #00F0FF;
        box-shadow: 0 0 12px rgba(0,240,255,0.12);
    }

    /* ── Auth buttons ──────────────────────────────────────── */
    .xr-btn-login {
        padding: 7px 18px;
        border-radius: 9px;
        font-size: 13px; font-weight: 600;
        background: transparent;
        border: 1px solid rgba(0,240,255,0.25);
        color: #00F0FF;
        cursor: pointer; transition: all 0.22s;
        text-decoration: none;
        font-family: 'DM Sans', sans-serif;
        white-space: nowrap;
    }
    .xr-btn-login:hover {
        background: rgba(0,240,255,0.08);
        border-color: rgba(0,240,255,0.5);
        box-shadow: 0 0 16px rgba(0,240,255,0.15);
    }

    .xr-btn-register {
        padding: 7px 18px;
        border-radius: 9px;
        font-size: 13px; font-weight: 700;
        background: linear-gradient(135deg, rgba(0,240,255,0.9), rgba(139,92,246,0.9));
        border: none;
        color: #020F14;
        cursor: pointer; transition: all 0.22s;
        text-decoration: none;
        font-family: 'DM Sans', sans-serif;
        box-shadow: 0 0 16px rgba(0,240,255,0.2);
        white-space: nowrap;
    }
    .xr-btn-register:hover {
        box-shadow: 0 0 28px rgba(0,240,255,0.4);
        transform: scale(1.03);
    }

    /* ── User avatar pill ─────────────────────────────────── */
    .xr-user-pill {
        display: flex; align-items: center; gap: 8px;
        padding: 5px 14px 5px 6px;
        border-radius: 100px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.09);
        cursor: pointer; transition: all 0.22s;
        position: relative;
        font-family: 'DM Sans', sans-serif;
    }
    .xr-user-pill:hover {
        border-color: rgba(0,240,255,0.25);
        background: rgba(0,240,255,0.06);
    }
    .xr-avatar {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, #00F0FF, #8B5CF6);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #020F14;
        flex-shrink: 0;
        font-family: 'Syne', sans-serif;
    }
    .xr-user-name {
        font-size: 13px; font-weight: 500; color: #EEF2FF;
        max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .xr-caret {
        width: 14px; height: 14px; color: rgba(136,146,170,0.5);
        transition: transform 0.2s;
    }
    .xr-user-pill.open .xr-caret { transform: rotate(180deg); }

    /* Dropdown */
    .xr-dropdown {
        position: absolute; top: calc(100% + 10px); right: 0;
        min-width: 220px;
        background: rgba(6,12,26,0.97);
        border: 1px solid rgba(0,240,255,0.12);
        border-radius: 14px;
        padding: 8px;
        box-shadow: 0 16px 48px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.03);
        backdrop-filter: blur(20px);
        z-index: 200;
        opacity: 0; transform: translateY(-8px) scale(0.97);
        pointer-events: none;
        transition: opacity 0.2s, transform 0.2s;
    }
    .xr-dropdown.open {
        opacity: 1; transform: translateY(0) scale(1);
        pointer-events: all;
    }
    .xr-dd-header {
        padding: 8px 12px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 6px;
    }
    .xr-dd-label { font-size: 9px; letter-spacing: 2px; text-transform: uppercase; color: rgba(136,146,170,0.5); margin-bottom: 2px; }
    .xr-dd-name  { font-size: 14px; font-weight: 600; color: #EEF2FF; }

    /* Admin Specialty Dropdown Link Style */
    .xr-dd-link.admin-glow {
        color: #00F0FF;
        background: rgba(0, 240, 255, 0.05);
        border: 1px solid rgba(0, 240, 255, 0.2);
        font-weight: 600;
    }
    .xr-dd-link.admin-glow:hover {
        background: rgba(0, 240, 255, 0.12);
        border-color: rgba(0, 240, 255, 0.4);
        box-shadow: 0 0 12px rgba(0, 240, 255, 0.15);
    }

    .xr-dd-link {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 9px;
        font-size: 13px; color: rgba(136,146,170,0.85);
        text-decoration: none;
        transition: all 0.18s;
        cursor: pointer;
    }
    .xr-dd-link:hover { background: rgba(255,255,255,0.05); color: #EEF2FF; }
    .xr-dd-link .dd-icon { font-size: 14px; width: 20px; text-align: center; opacity: 0.7; }
    .xr-dd-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 6px 0; }

    .xr-dd-item-container {
        padding: 4px 6px;
    }

    .xr-dd-logout {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 12px; border-radius: 9px;
        font-size: 13px; color: rgba(255,59,107,0.85);
        cursor: pointer; transition: all 0.18s;
        width: 100%; background: none; border: none;
        font-family: 'DM Sans', sans-serif; text-align: left;
    }
    .xr-dd-logout:hover { background: rgba(255,59,107,0.08); color: #FF3B6B; }

    /* ── Hamburger ─────────────────────────────────────────── */
    .xr-hamburger {
        width: 36px; height: 36px;
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.04);
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 4.5px;
        cursor: pointer; transition: all 0.2s;
        padding: 0;
    }
    @media (min-width: 768px) {
        .xr-hamburger { display: none; }
    }

    .xr-hamburger:hover {
        border-color: rgba(0,240,255,0.35);
        background: rgba(0,240,255,0.06);
    }
    .xr-hamburger .bar {
        width: 16px; height: 1.5px;
        background: rgba(136,146,170,0.8);
        border-radius: 2px;
        transition: all 0.28s cubic-bezier(0.4,0,0.2,1);
        transform-origin: center;
    }
    .xr-hamburger.open .bar:nth-child(1) { transform: translateY(6px) rotate(45deg); background: #00F0FF; }
    .xr-hamburger.open .bar:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .xr-hamburger.open .bar:nth-child(3) { transform: translateY(-6px) rotate(-45deg); background: #00F0FF; }
    .xr-hamburger.open { border-color: rgba(0,240,255,0.4); background: rgba(0,240,255,0.08); }

    /* ── Mobile drawer ─────────────────────────────────────── */
    .xr-mob-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 88;
    }
    .xr-mob-drawer {
        position: fixed; right: 0; top: 0;
        height: 100dvh; width: min(300px, 80vw);
        background: rgba(6,12,26,0.99);
        border-left: 1px solid rgba(0,240,255,0.1);
        z-index: 90;
        display: flex; flex-direction: column;
        backdrop-filter: blur(32px);
        box-shadow: -16px 0 64px rgba(0,0,0,0.8);
        /* FIX: position:relative so the close button can absolute-position inside */
        position: fixed;
    }
    /* Drawer top glow */
    .xr-mob-drawer::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,240,255,0.5), rgba(139,92,246,0.5), transparent);
    }

    .xr-mob-inner {
        display: flex; flex-direction: column;
        height: 100%; padding-top: 64px;
        padding-left: 20px; padding-right: 20px;
        padding-bottom: 28px;
        overflow-y: auto;
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
    .xr-mob-link:hover {
        color: #EEF2FF;
        background: rgba(255,255,255,0.04);
    }
    .xr-mob-link.active {
        color: #00F0FF;
        background: rgba(0,240,255,0.07);
    }
    .xr-mob-link .mob-icon { font-size: 15px; width: 22px; text-align: center; }

    /* Mobile Admin Highlight style */
    .xr-mob-link.admin-highlight {
        color: #00F0FF;
        background: rgba(0, 240, 255, 0.05);
        border: 1px solid rgba(0, 240, 255, 0.15);
    }
    .xr-mob-link.admin-highlight:hover {
        background: rgba(0, 240, 255, 0.12);
        border-color: rgba(0, 240, 255, 0.35);
    }

    .xr-mob-link.ar-highlight {
        color: #A78BFA;
        background: rgba(139,92,246,0.07);
        border: 1px solid rgba(139,92,246,0.15);
    }
    .xr-mob-link.ar-highlight:hover {
        background: rgba(139,92,246,0.12);
        border-color: rgba(139,92,246,0.3);
    }

    .xr-mob-divider {
        height: 1px; background: rgba(255,255,255,0.06);
        margin: 14px 0;
    }

    /* Auth in mobile */
    .xr-mob-auth { margin-top: auto; }
    .xr-mob-user-card {
        display: flex; align-items: center; gap: 12px;
        padding: 14px;
        border-radius: 12px;
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

    /* ── Language Switcher Custom Layout ───────────────────── */
    .cyber-lang-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        padding: 8px 12px;
        background: rgba(0, 240, 255, 0.04);
        border: 1px solid rgba(0, 240, 255, 0.2);
        border-radius: 8px;
        transition: all 0.25s ease;
        width: 100%;
    }
    .cyber-lang-wrapper:hover {
        border-color: rgba(0, 240, 255, 0.45);
        background: rgba(0, 240, 255, 0.08);
        box-shadow: 0 0 12px rgba(0, 240, 255, 0.15);
    }
    .lang-icon {
        margin-right: 8px;
        font-size: 12px;
        color: rgba(136,146,170,0.6);
        white-space: nowrap;
    }
    .cyber-select {
        appearance: none;
        -webkit-appearance: none;
        background: transparent;
        border: none;
        color: #00F0FF;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 12px;
        width: 100%;
        cursor: pointer;
        outline: none;
        letter-spacing: 0.5px;
        padding-right: 20px;
    }
    .cyber-lang-wrapper::after {
        content: '▼';
        font-size: 8px;
        color: #00F0FF;
        position: absolute;
        right: 12px;
        pointer-events: none;
        opacity: 0.7;
    }
    .cyber-select option {
        background: #060c1a;
        color: #eef2ff;
        padding: 8px;
    }
    .xr-lang-badge {
        font-size: 9px;
        color: #00F0FF;
        font-weight: 700;
        background: rgba(0, 240, 255, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid rgba(0, 240, 255, 0.2);
        margin-left: auto;
    }
</style>

{{--
    FIX: x-data used backtick template literals inside Blade {{ }} which breaks Blade parsing.
    Changed to a plain JS object string with single-quoted keys.
--}}
<div x-data="{
    open: false,
    dropOpen: false,
    darkMode: true,
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', this.darkMode);
    }
}"
x-init="
    $watch('open', v => v ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden'));
    darkMode = localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
    document.documentElement.classList.toggle('dark', darkMode);
"
@keydown.escape.window="open = false; dropOpen = false">

    <!-- ══════════════════════════════════════════════════════
         TOP NAV BAR
         ══════════════════════════════════════════════════════ -->
    <nav class="xr-nav">
        {{-- FIX: changed justify-content:between (invalid) to justify-content:space-between --}}
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;width:100%;display:flex;align-items:center;justify-content:space-between;gap:16px;position:relative;z-index:1;">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="xr-logo-wrap">
                <div class="xr-logo-icon">
                    <span>XR</span>
                </div>
                <div class="xr-logo-text hidden sm:block">
                    <div class="xr-logo-name">Life<span class="cyan">Shield</span></div>
                    <div class="xr-logo-sub">AR∞ Insurance</div>
                </div>
            </a>

            <!-- Desktop links -->
            <div class="hidden md:flex items-center gap-1" style="flex:1;justify-content:center;">
                <a href="{{ route('home') }}" class="xr-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="xr-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                <a href="{{ route('plans.index') }}" class="xr-nav-link {{ request()->routeIs('plans.index') ? 'active' : '' }}">Plans</a>
                <a href="{{ route('vr') }}" class="xr-nav-link ar-demo {{ request()->routeIs('vr') ? 'active' : '' }}">
                    <span style="margin-right:4px">◈</span> AR Demo
                </a>
                <a href="{{ route('contact') }}" class="xr-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </div>

            <!-- Right side controls panel -->
            <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">

                <!-- Theme toggle -->
                <button @click="toggleTheme()" class="xr-theme-btn" type="button" :title="darkMode ? 'Light mode' : 'Dark mode'">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM15.485 4.515a.75.75 0 011.06 0l1.061 1.061a.75.75 0 11-1.06 1.06l-1.06-1.06a.75.75 0 010-1.061zM2.454 4.515a.75.75 0 010 1.061l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.061a.75.75 0 011.06 0zM10 15a3 3 0 100-6 3 3 0 000 6zM10 16.75a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5a.75.75 0 01.75-.75zM5.25 14.525a.75.75 0 011.06-1.06l1.061 1.06a.75.75 0 11-1.06 1.06L5.25 14.525zM3.5 10a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 013.5 10zM13.19 14.525a.75.75 0 011.06 1.06l-1.06 1.061a.75.75 0 11-1.061-1.06l1.061-1.061z"/>
                    </svg>
                    <svg x-cloak x-show="darkMode" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.293 13.293A8 8 0 116.707 2.707a8.001 8.001 0 0010.586 10.586z"/>
                    </svg>
                </button>

                @if($isAuthenticated)
                    {{-- ── Authenticated: user pill + dropdown ── --}}
                    <div style="position:relative;" @click.outside="dropOpen = false">
                        <button
                            @click="dropOpen = !dropOpen"
                            :class="dropOpen ? 'open' : ''"
                            class="xr-user-pill hidden md:flex"
                            type="button">
                            <div class="xr-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span class="xr-user-name">{{ Auth::user()->name }}</span>
                            <svg class="xr-caret" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 6l4 4 4-4"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu Options -->
                        <div :class="dropOpen ? 'open' : ''" class="xr-dropdown">
                            <div class="xr-dd-header">
                                <div class="xr-dd-label">Signed in as</div>
                                <div class="xr-dd-name">{{ Auth::user()->name }}</div>
                            </div>

                            {{-- Integrated Desktop Admin Link --}}
                            @if($isAdmin)
                                <a href="{{ route('dashboard') }}" class="xr-dd-link admin-glow">
                                    <span class="dd-icon" style="opacity:1">⚡</span> Admin Panel
                                </a>
                                <div class="xr-dd-divider"></div>
                            @endif

                            <a href="{{ route('dashboard') }}" class="xr-dd-link">
                                <span class="dd-icon">⬡</span> Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="xr-dd-link">
                                <span class="dd-icon">◎</span> Profile
                            </a>

                            <div class="xr-dd-divider"></div>

                            <!-- Integrated Language Switcher Area -->
                            <div class="xr-dd-item-container">
                                <div class="cyber-lang-wrapper">
                                    <span class="lang-icon">LANG:</span>
                                    <select class="cyber-select" onchange="window.location.href=this.value">
                                        <option value="" disabled {{ !session()->has('locale') ? 'selected' : '' }}>Select Language</option>
                                        <option value="/lang/en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN - English</option>
                                        <option value="/lang/pa" {{ app()->getLocale() == 'pa' ? 'selected' : '' }}>PA - ਪੰਜਾਬੀ</option>
                                        <option value="/lang/hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>HI - हिन्दी</option>
                                    </select>
                                    <span class="xr-lang-badge">{{ strtoupper(app()->getLocale()) }}</span>
                                </div>
                            </div>

                            <div class="xr-dd-divider"></div>

                            <form action="{{ route('logout') }}" method="POST" style="margin:0">
                                @csrf
                                <button type="submit" class="xr-dd-logout">
                                    <span class="dd-icon">⏻</span> Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- ── Guest: Login + Register ── --}}
                    <a href="{{ route('login') }}"    class="xr-btn-login    hidden md:inline-flex">Login</a>
                    <a href="{{ route('register') }}" class="xr-btn-register hidden md:inline-flex">Get Started</a>
                @endif

                <!-- Hamburger (mobile) -->
                <button
                    @click="open = !open"
                    :class="open ? 'open' : ''"
                    class="xr-hamburger md:hidden"
                    type="button"
                    aria-label="Menu">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </button>
            </div>
        </div>
    </nav>


    <!-- ══════════════════════════════════════════════════════
         MOBILE DRAWER
         ══════════════════════════════════════════════════════ -->
    <div x-cloak class="md:hidden">

        <!-- Overlay background fade -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="open = false"
            class="xr-mob-overlay">
        </div>

        <!-- Drawer panel container sliding -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-280 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="xr-mob-drawer">

            {{--
                FIX: Close button moved OUTSIDE xr-mob-inner so it stays fixed at top-right
                of the drawer panel even when the inner content is scrolled.
            --}}
            <button
                @click="open = false"
                class="xr-mob-close"
                type="button"
                aria-label="Close menu">✕</button>

            <div class="xr-mob-inner">

                <!-- Nav section -->
                <div class="xr-mob-section-label">Navigation</div>

                <a href="{{ route('home') }}"        @click="open=false" class="xr-mob-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <span class="mob-icon">⌂</span> Home
                </a>
                <a href="{{ route('about') }}"       @click="open=false" class="xr-mob-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <span class="mob-icon">◇</span> About
                </a>
                <a href="{{ route('plans.index') }}" @click="open=false" class="xr-mob-link {{ request()->routeIs('plans.index') ? 'active' : '' }}">
                    <span class="mob-icon">☰</span> Plans
                </a>
                <a href="{{ route('vr') }}"          @click="open=false" class="xr-mob-link ar-highlight {{ request()->routeIs('vr') ? 'active' : '' }}">
                    <span class="mob-icon">◈</span> AR Demo
                    <span style="margin-left:auto;font-size:9px;letter-spacing:1.5px;color:rgba(139,92,246,0.7);text-transform:uppercase">Live</span>
                </a>
                <a href="{{ route('contact') }}"     @click="open=false" class="xr-mob-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <span class="mob-icon">✉</span> Contact
                </a>

                <div class="xr-mob-divider"></div>

                <!-- Shared Mobile Preferences Selector -->
                <div class="xr-mob-section-label">Preferences</div>
                <div style="padding: 4px 12px 14px;">
                    <div class="cyber-lang-wrapper">
                        <span class="lang-icon">LANG:</span>
                        <select class="cyber-select" onchange="window.location.href=this.value">
                            <option value="" disabled {{ !session()->has('locale') ? 'selected' : '' }}>Select Language</option>
                            <option value="/lang/en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN - English</option>
                            <option value="/lang/pa" {{ app()->getLocale() == 'pa' ? 'selected' : '' }}>PA - ਪੰਜਾਬੀ</option>
                            <option value="/lang/hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>HI - हिन्दी</option>
                        </select>
                        <span class="xr-lang-badge">{{ strtoupper(app()->getLocale()) }}</span>
                    </div>
                </div>

                <div class="xr-mob-divider"></div>

                <!-- Auth section area -->
                <div class="xr-mob-auth">

                    @if($isAuthenticated)
                        <!-- User Identification Card Details Layout -->
                        <div class="xr-mob-user-card">
                            <div class="xr-mob-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="xr-mob-user-info">
                                <div class="xr-mob-user-name">{{ Auth::user()->name }}</div>
                                <div class="xr-mob-user-tag">{{ $isAdmin ? '⚡ System Admin' : '● Policy Active' }}</div>
                            </div>
                        </div>

                        <!-- Account Navigation Options Area -->
                        <div class="xr-mob-section-label" style="margin-top:4px;">Account</div>

                        {{-- Integrated Mobile Admin Link --}}
                        @if($isAdmin)
                            <a href="{{ route('dashboard') }}" @click="open=false" class="xr-mob-link admin-highlight">
                                <span class="mob-icon">⚡</span> Admin Panel
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}"   @click="open=false" class="xr-mob-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="mob-icon">⬡</span> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" @click="open=false" class="xr-mob-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <span class="mob-icon">◎</span> My Profile
                        </a>

                        <div class="xr-mob-divider"></div>

                        <form action="{{ route('logout') }}" method="POST" style="margin:0">
                            @csrf
                            <button type="submit" class="xr-mob-logout-btn">⏻ &nbsp;Sign Out</button>
                        </form>

                    @else
                        <div class="xr-mob-section-label">Get Started</div>
                        <a href="{{ route('login') }}"    class="xr-mob-btn-login">Login</a>
                        <a href="{{ route('register') }}" class="xr-mob-btn-register">Create Account →</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>