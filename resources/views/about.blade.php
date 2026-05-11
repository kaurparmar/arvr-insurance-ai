<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>About Us — {{ config('app.name', 'LifeShield XR') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        (function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --cyan:#00F0FF; --violet:#8B5CF6; --rose:#FF3B6B; --emerald:#00E676; --amber:#FFB700;
            --bg-void:#03060F; --bg-deep:#060C1A; --bg-panel:rgba(8,14,30,.92);
            --text-mid:#8892AA; --border:rgba(0,240,255,.1); --border-w:rgba(255,255,255,.07);
        }
        /* Light mode overrides */
        .light-bg   { background: #F8FAFF; }
        .light-card { background: #FFFFFF; border-color: rgba(0,0,0,.08); }
        .light-text { color: #0F172A; }
        .light-sub  { color: #475569; }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            transition: background .3s, color .3s;
        }

        /* ── Dark mode body ── */
        body.dark-mode, .dark body {
            background: var(--bg-void);
            color: #EEF2FF;
        }
        /* ── Light mode body ── */
        body:not(.dark-mode), html:not(.dark) body {
            background: #F0F4FF;
            color: #0F172A;
        }

        /* Tailwind dark: prefix targets */
        .dark body { background: var(--bg-void); color: #EEF2FF; }

        /* Scanlines only dark */
        .vr-scanlines {
            display: none;
            position: fixed; inset: 0; pointer-events: none; z-index: 1;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.025) 2px, rgba(0,0,0,.025) 4px);
        }
        .dark .vr-scanlines { display: block; }

        /* ── Particle canvas ── */
        #about-particles {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            opacity: 0; transition: opacity .5s;
        }
        .dark #about-particles { opacity: 1; }

        /* ── Glows ── */
        .glow-cyan   { position: absolute; border-radius: 50%; filter: blur(100px); background: rgba(0,240,255,.07); pointer-events: none; }
        .glow-violet { position: absolute; border-radius: 50%; filter: blur(120px); background: rgba(139,92,246,.06); pointer-events: none; }
        .glow-rose   { position: absolute; border-radius: 50%; filter: blur(100px); background: rgba(255,59,107,.05); pointer-events: none; }
        .dark .glow-cyan   { background: rgba(0,240,255,.09); }
        .dark .glow-violet { background: rgba(139,92,246,.08); }
        .dark .glow-rose   { background: rgba(255,59,107,.07); }

        /* ── Chip badge ── */
        .xr-chip {
            display: inline-flex; align-items: center; gap: 8px;
            border-radius: 100px; padding: 5px 16px;
            font-size: 10px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        }
        .chip-cyan {
            background: rgba(0,240,255,.1); border: 1px solid rgba(0,240,255,.25); color: var(--cyan);
        }
        .dark .chip-cyan { background: rgba(0,240,255,.08); }

        .chip-dot { width: 6px; height: 6px; background: var(--cyan); border-radius: 50%; animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ── Cards ── */
        .xr-card {
            border-radius: 24px;
            transition: transform .3s, border-color .3s, box-shadow .3s;
            position: relative; overflow: hidden;
        }
        /* Light card */
        html:not(.dark) .xr-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }
        /* Dark card */
        .dark .xr-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-w);
        }
        .xr-card:hover {
            transform: translateY(-6px);
            border-color: rgba(0,240,255,.3);
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .xr-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0; transition: opacity .3s;
        }
        .xr-card:hover::before { opacity: 1; }

        /* ── Gradient card border ── */
        .grad-border {
            padding: 1px;
            background: linear-gradient(135deg, rgba(0,240,255,.4), rgba(139,92,246,.4), rgba(255,59,107,.3));
            border-radius: 24px;
        }
        .grad-border-inner {
            border-radius: 23px;
            padding: 40px;
            height: 100%;
        }
        html:not(.dark) .grad-border-inner { background: #fff; }
        .dark .grad-border-inner { background: #07111F; }

        /* ── Syne headings ── */
        .syne { font-family: 'Syne', sans-serif; }

        /* ── Gradient text ── */
        .grad-text {
            background: linear-gradient(135deg, var(--cyan) 0%, var(--violet) 50%, var(--rose) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; background-size: 200%;
            animation: textShine 5s linear infinite;
        }
        @keyframes textShine { to { background-position: 200% center; } }

        .grad-text-cv {
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .grad-text-vr {
            background: linear-gradient(135deg, var(--violet), var(--rose));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .grad-text-eg {
            background: linear-gradient(135deg, var(--emerald), var(--cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* ── Narration box ── */
        .narration-box {
            border-radius: 14px; padding: 20px 24px;
            border-left: 3px solid var(--cyan);
            font-style: italic; font-size: 15px; line-height: 1.7;
        }
        html:not(.dark) .narration-box { background: #F0F7FF; color: #475569; border-color: var(--cyan); }
        .dark .narration-box { background: rgba(255,255,255,.03); color: var(--text-mid); }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 20px; padding: 28px 20px; text-align: center;
            transition: transform .3s;
        }
        html:not(.dark) .stat-card { background: #fff; border: 1px solid rgba(0,0,0,.06); box-shadow: 0 2px 16px rgba(0,0,0,.05); }
        .dark .stat-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        .stat-card:hover { transform: translateY(-4px); }

        /* ── Icon circle ── */
        .icon-circle {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 20px;
        }

        /* ── Vision list ── */
        .vision-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid;
            transition: color .2s;
        }
        html:not(.dark) .vision-item { border-color: rgba(0,0,0,.06); color: #475569; }
        .dark .vision-item { border-color: var(--border-w); color: var(--text-mid); }
        .vision-item:last-child { border-bottom: none; }
        .vision-item:hover { color: #0F172A; }
        .dark .vision-item:hover { color: #EEF2FF; }
        .vision-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--cyan); margin-top: 6px; flex-shrink: 0; transition: transform .2s; }
        .vision-item:hover .vision-dot { transform: scale(1.5); }

        /* ── Timeline ── */
        .timeline-item {
            display: flex; gap: 24px; padding-bottom: 40px;
            position: relative;
        }
        .timeline-item::before {
            content: ''; position: absolute; left: 19px; top: 40px; bottom: 0; width: 1px;
        }
        html:not(.dark) .timeline-item::before { background: rgba(0,0,0,.08); }
        .dark .timeline-item::before { background: var(--border-w); }
        .timeline-item:last-child::before { display: none; }
        .t-dot {
            width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; font-size: 16px; flex-shrink: 0; border: 2px solid;
        }
        html:not(.dark) .t-dot { background: #F0F4FF; border-color: rgba(0,0,0,.1); }
        .dark .t-dot { background: var(--bg-deep); border-color: var(--border); }
        .t-dot.cyan-dot { border-color: var(--cyan); }
        .t-dot.violet-dot { border-color: var(--violet); }
        .t-dot.rose-dot { border-color: var(--rose); }

        /* ── CTA section ── */
        .cta-wrap {
            border-radius: 40px; padding: 1px;
            background: linear-gradient(135deg, rgba(0,240,255,.3), rgba(139,92,246,.3), rgba(255,59,107,.2));
        }
        .cta-inner {
            border-radius: 39px; padding: 72px 40px; text-align: center;
        }
        html:not(.dark) .cta-inner { background: linear-gradient(135deg, #EEF4FF, #F5EEFF); }
        .dark .cta-inner { background: linear-gradient(135deg, #040C18, #080512); }

        /* ── Buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--cyan); color: #020F14;
            font-family: 'Syne', sans-serif; font-weight: 700;
            padding: 14px 32px; border-radius: 100px; border: none; cursor: pointer;
            font-size: 15px; transition: all .2s; text-decoration: none;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,240,255,.3); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: #0F172A;
            font-family: 'DM Sans', sans-serif; font-weight: 500;
            padding: 14px 32px; border-radius: 100px; cursor: pointer;
            font-size: 15px; transition: all .2s; text-decoration: none;
            border: 1px solid rgba(0,0,0,.15);
        }
        .dark .btn-outline { color: #EEF2FF; border-color: rgba(255,255,255,.12); }
        .btn-outline:hover { border-color: var(--cyan); color: var(--cyan); transform: translateY(-2px); }

        /* ── Team card ── */
        .team-card {
            border-radius: 24px; padding: 32px 24px; text-align: center;
            transition: transform .3s, box-shadow .3s;
        }
        html:not(.dark) .team-card { background: #fff; border: 1px solid rgba(0,0,0,.07); box-shadow: 0 4px 20px rgba(0,0,0,.06); }
        .dark .team-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        .team-card:hover { transform: translateY(-8px); box-shadow: 0 24px 60px rgba(0,0,0,.2); }

        .team-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 700;
        }

        /* ── Section layouts ── */
        .section { max-width: 1100px; margin: 0 auto; padding: 80px 32px; }
        .section-sm { max-width: 1100px; margin: 0 auto; padding: 0 32px 80px; }

        /* ── Scroll indicator ── */
        @keyframes bounce { 0%,100%{transform:translateY(0)}50%{transform:translateY(8px)} }
        .scroll-dot { animation: bounce 2s infinite; }

        /* ── Fade up animations ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { opacity: 0; animation: fadeUp .6s forwards; }
        .fade-up-1 { animation-delay: .1s; }
        .fade-up-2 { animation-delay: .25s; }
        .fade-up-3 { animation-delay: .4s; }
        .fade-up-4 { animation-delay: .55s; }

        /* ── Muted text ── */
        .text-muted { color: #475569; }
        .dark .text-muted { color: var(--text-mid); }
        .text-hi { color: #0F172A; }
        .dark .text-hi { color: #EEF2FF; }
        .text-sub { color: #64748B; }
        .dark .text-sub { color: #8892AA; }

        @media(max-width:768px){
            .grid-2,.grid-3,.grid-4,.team-grid,.stat-grid { grid-template-columns: 1fr !important; }
            .section { padding: 60px 20px; }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#03060F] text-slate-900 dark:text-[#EEF2FF]">

    <div class="vr-scanlines"></div>
    <canvas id="about-particles"></canvas>

    <x-navbar :is-authenticated="auth()->check()" />

    <main class="relative" style="padding-top:60px">

        {{-- Background glows --}}
        <div class="glow-cyan" style="width:600px;height:600px;top:-200px;left:-150px;opacity:.6"></div>
        <div class="glow-violet" style="width:700px;height:700px;top:200px;right:-200px;opacity:.5"></div>

        {{-- ═══ HERO ═══ --}}
        <section class="relative min-h-[90vh] flex items-center justify-center text-center overflow-hidden">
            <div class="glow-rose" style="width:400px;height:400px;bottom:0;left:50%;transform:translateX(-50%);opacity:.4"></div>

            <div class="section" style="padding-top:80px;padding-bottom:80px">
                <div class="fade-up fade-up-1">
                    <span class="xr-chip chip-cyan">
                        <span class="chip-dot"></span>
                        India's First XR Insurance Experience
                    </span>
                </div>

                <h1 class="syne fade-up fade-up-2" style="font-size:clamp(52px,8vw,96px);font-weight:800;letter-spacing:-3px;line-height:1.02;margin:28px 0 24px;">
                    About <span class="grad-text">LifeShield XR</span>
                </h1>

                <p class="fade-up fade-up-3 text-sub" style="font-size:clamp(16px,2vw,20px);max-width:600px;margin:0 auto 48px;line-height:1.7;font-weight:300;">
                    We are bridging the gap between <strong class="text-hi">complex policy data</strong> and <strong class="text-hi">human understanding</strong> through spatial computing and immersive reality.
                </p>

                <div class="fade-up fade-up-4" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
                    <a href="{{ route('login') }}" class="btn-primary">Get Started →</a>
                    <a href="#mission" class="btn-outline">Our Story ↓</a>
                </div>

                {{-- Scroll indicator --}}
                <div class="scroll-dot" style="margin-top:64px;opacity:.3">
                    <div style="width:2px;height:48px;background:linear-gradient(180deg,currentColor,transparent);margin:0 auto"></div>
                </div>
            </div>
        </section>

        {{-- ═══ MISSION + VISION ═══ --}}
        <section id="mission" class="section">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px" class="grid-2">

                {{-- Mission --}}
                <div class="xr-card" style="padding:40px">
                    <div class="icon-circle" style="background:rgba(0,240,255,.08);border:1px solid rgba(0,240,255,.2)">🚀</div>
                    <h2 class="syne text-hi" style="font-size:28px;font-weight:700;margin-bottom:16px;letter-spacing:-.5px">Our Mission</h2>
                    <p class="text-sub" style="font-size:15px;line-height:1.75">
                        LifeShield XR combines <span style="color:var(--cyan)">spatial immersion</span> with intelligent systems to create a futuristic experience where users visualize risks and policies in real-time AR/VR environments.
                    </p>
                    <div class="narration-box" style="margin-top:24px">
                        Making life insurance understandable, accessible, and genuinely human — one immersive experience at a time.
                    </div>
                </div>

                {{-- Vision --}}
                <div class="grad-border">
                    <div class="grad-border-inner">
                        <h2 class="syne text-hi" style="font-size:28px;font-weight:700;margin-bottom:28px;letter-spacing:-.5px">Our Vision</h2>
                        <div>
                            @foreach([
                                ['🌐','Democratizing XR for the insurance industry','Building spatial tools that every family can access, not just enterprise clients.'],
                                ['🧠','Building empathy through immersive simulations','When people see the financial impact, they understand why protection matters.'],
                                ['🔗','Setting the global standard for Digital Twins in fintech','Real-time policy mirrors that update as your life changes.'],
                            ] as $v)
                            <div class="vision-item">
                                <span style="font-size:20px;flex-shrink:0">{{ $v[0] }}</span>
                                <div>
                                    <div style="font-weight:600;font-size:14px;margin-bottom:4px" class="text-hi">{{ $v[1] }}</div>
                                    <div class="text-sub" style="font-size:13px;line-height:1.55">{{ $v[2] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ═══ STATS ═══ --}}
        <section class="section-sm">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px" class="stat-grid">
                @foreach([
                    ['100K+','Users Protected','var(--cyan)'],
                    ['50+','XR Innovators','var(--violet)'],
                    ['24/7','AI Support','var(--rose)'],
                    ['99%','Accuracy Rate','var(--emerald)'],
                ] as $s)
                <div class="stat-card">
                    <div class="syne" style="font-size:clamp(28px,4vw,44px);font-weight:800;color:{{ $s[2] }};line-height:1">{{ $s[0] }}</div>
                    <div class="text-sub" style="font-size:11px;letter-spacing:2px;text-transform:uppercase;margin-top:8px;font-weight:600">{{ $s[1] }}</div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- {{-- ═══ TEAM ═══ --}}
        <section class="section">
            <div style="margin-bottom:56px">
                <span class="xr-chip chip-cyan" style="margin-bottom:16px;display:inline-flex">
                    <span class="chip-dot"></span> The Team
                </span>
                <h2 class="syne text-hi" style="font-size:clamp(32px,4vw,48px);font-weight:700;letter-spacing:-1px;margin-top:16px">Built by 3. Powered by Vision.</h2>
                <p class="text-sub" style="margin-top:12px;font-size:16px;max-width:480px;line-height:1.65">A group project at the intersection of technology, design, and financial innovation.</p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px" class="team-grid">
                @foreach([
                    ['AK','Armin','Backend / Laravel','Handles MVC architecture, Eloquent ORM, database migrations, seeding, and REST API design.',['linear-gradient(135deg,#00F0FF,#8B5CF6)','#fff']],
                    ['PS','Priya Sharma','Frontend / Blade','Masters Blade templating, template inheritance, form validation, CSRF, sessions, and localization.',['linear-gradient(135deg,#FF3B6B,#8B5CF6)','#fff']],
                    ['RV','Rahul Verma','Full Stack / AR','AR/VR integration, email system, MongoDB setup, Query Builder, and deployment pipeline.',['linear-gradient(135deg,#FFB700,#FF3B6B)','#fff']],
                ] as [$init,$name,$role,$desc,$av])
                <div class="team-card">
                    <div class="team-avatar" style="background:{{ $av[0] }};color:{{ $av[1] }}">{{ $init }}</div>
                    <h3 class="syne text-hi" style="font-size:18px;font-weight:700;margin-bottom:4px">{{ $name }}</h3>
                    <div style="font-size:11px;color:var(--cyan);letter-spacing:1.5px;text-transform:uppercase;margin-bottom:12px;font-weight:600">{{ $role }}</div>
                    <p class="text-sub" style="font-size:13px;line-height:1.65">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </section> -->

        {{-- ═══ CURRICULUM ═══ --}}
        <!-- <section class="section" style="padding-top:0">
            <span class="xr-chip chip-cyan" style="display:inline-flex;margin-bottom:20px">
                <span class="chip-dot"></span> Laravel Curriculum
            </span>
            <h2 class="syne text-hi" style="font-size:clamp(28px,4vw,44px);font-weight:700;letter-spacing:-1px;margin-bottom:8px">All 6 Units Implemented</h2>
            <p class="text-sub" style="font-size:15px;margin-bottom:40px">Every Laravel concept from the syllabus is demonstrated within this live application.</p>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px" class="grid-3">
                @foreach([
                    ['I','MVC + Laravel Setup','Composer install, Laravel 11 scaffold, Artisan commands for migrations, seeders, and resource controllers.','var(--cyan)'],
                    ['II','Routing & Responses','Named routes, resource routes, route parameters, JSON responses, redirections to named routes and controller actions.','var(--violet)'],
                    ['III','Controllers & Blade','RESTful Resource Controllers, Blade @extends/@section, template inheritance, route groups, URL generation.','var(--rose)'],
                    ['IV','Request Data & Emails','Old input handling, file uploads for KYC, cookies, Laravel Mail for policy issuance and welcome emails.','var(--amber)'],
                    ['V','Forms & Validation','CSRF tokens, @method spoofing, custom validation rules for age/health data, error messages, form repopulation.','var(--emerald)'],
                    ['VI','Database & ORM','Eloquent models, migrations, CRUD via Query Builder and Eloquent ORM, MongoDB for AR data, REST APIs.','var(--cyan)'],
                ] as [$num,$title,$desc,$col])
                <div class="xr-card" style="padding:24px">
                    <div style="font-size:10px;letter-spacing:2px;color:{{ $col }};font-weight:700;text-transform:uppercase;margin-bottom:8px">UNIT {{ $num }}</div>
                    <h3 class="syne text-hi" style="font-size:16px;font-weight:600;margin-bottom:10px">{{ $title }}</h3>
                    <p class="text-sub" style="font-size:13px;line-height:1.6">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </section> -->

        {{-- ═══ JOURNEY / TIMELINE ═══ --}}
        <section class="section" style="padding-top:0">
            <span class="xr-chip chip-cyan" style="display:inline-flex;margin-bottom:20px">
                <span class="chip-dot"></span> Our Journey
            </span>
            <h2 class="syne text-hi" style="font-size:clamp(28px,4vw,44px);font-weight:700;letter-spacing:-1px;margin-bottom:40px">From Idea to Reality</h2>

            <div style="max-width:600px">
                @foreach([
                    ['cyan-dot','🧪','Concept & Research','Identified the gap — 400M uninsured Indians due to policy complexity. AR as the solution emerged.'],
                    ['violet-dot','🏗️','Build Phase','Laravel MVC backend scaffolded. Database designed. AR simulation engine built from scratch in Canvas 2D.'],
                    ['rose-dot','🚀','Launch','LifeShield XR goes live. Real policies, real database, real immersive AR/VR simulation.'],
                ] as [$dc,$ic,$ti,$de])
                <div class="timeline-item">
                    <div class="t-dot {{ $dc }}">{{ $ic }}</div>
                    <div style="padding-top:6px">
                        <div class="syne text-hi" style="font-weight:600;font-size:16px;margin-bottom:6px">{{ $ti }}</div>
                        <div class="text-sub" style="font-size:14px;line-height:1.6">{{ $de }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ═══ CTA ═══ --}}
        <section class="section" style="padding-top:0">
            <div class="cta-wrap">
                <div class="cta-inner">
                    <span class="xr-chip chip-cyan" style="display:inline-flex;margin-bottom:24px">
                        <span class="chip-dot"></span> Ready?
                    </span>
                    <h2 class="syne text-hi" style="font-size:clamp(36px,5vw,64px);font-weight:800;letter-spacing:-2px;line-height:1.05;margin-bottom:16px">
                        Ready to see<br><span class="grad-text">The Future?</span>
                    </h2>
                    <p class="text-sub" style="font-size:16px;margin-bottom:36px;max-width:400px;margin-left:auto;margin-right:auto;line-height:1.65">
                        Experience India's first AR-powered insurance platform. It only takes 60 seconds.
                    </p>
                    <a href="{{ route('login') }}" class="btn-primary" style="font-size:17px;padding:16px 40px">
                        Start Your Journey →
                    </a>
                </div>
            </div>
        </section>

    </main>

    <footer style="text-align:center;padding:28px;font-size:13px;border-top:1px solid" class="text-sub" style="border-color:rgba(255,255,255,.06)">
        <span class="dark:border-white/5 border-slate-200">© {{ date('Y') }} LifeShield XR. All rights reserved.</span>
    </footer>

    <script>
    (function(){
        const canvas=document.getElementById('about-particles');
        const ctx=canvas.getContext('2d');
        let W,H;
        const isDark=()=>document.documentElement.classList.contains('dark');
        const resize=()=>{W=canvas.width=window.innerWidth;H=canvas.height=window.innerHeight;};
        window.addEventListener('resize',resize);resize();
        class P{constructor(){this.reset();}reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.r=Math.random()*1.4+.3;this.vx=(Math.random()-.5)*.2;this.vy=(Math.random()-.5)*.2;this.a=Math.random()*.35+.08;this.c=Math.random()>.6?'#00F0FF':Math.random()>.5?'#8B5CF6':'#ffffff';}update(){this.x+=this.vx;this.y+=this.vy;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset();}draw(){ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);ctx.fillStyle=this.c;ctx.globalAlpha=this.a;ctx.fill();}}
        const ps=Array.from({length:70},()=>new P());
        const loop=()=>{if(!isDark()){ctx.clearRect(0,0,W,H);requestAnimationFrame(loop);return;}ctx.clearRect(0,0,W,H);ctx.globalAlpha=1;for(let i=0;i<ps.length;i++)for(let j=i+1;j<ps.length;j++){const dx=ps[i].x-ps[j].x,dy=ps[i].y-ps[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<90){ctx.beginPath();ctx.moveTo(ps[i].x,ps[i].y);ctx.lineTo(ps[j].x,ps[j].y);ctx.strokeStyle='rgba(0,240,255,'+(0.03*(1-d/90))+')';ctx.lineWidth=.5;ctx.stroke();}}ps.forEach(p=>{p.update();p.draw();});requestAnimationFrame(loop);};loop();
    })();
    </script>

</body>
</html>