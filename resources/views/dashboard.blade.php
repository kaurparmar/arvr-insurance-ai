<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — {{ config('app.name', 'LifeShield XR') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>(function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--cyan:#00F0FF;--violet:#8B5CF6;--rose:#FF3B6B;--emerald:#00E676;--amber:#FFB700;--bg-void:#03060F;--bg-deep:#060C1A;--bg-panel:rgba(8,14,30,.92);--text-mid:#8892AA;--border:rgba(0,240,255,.1);--border-w:rgba(255,255,255,.07);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;transition:background .3s,color .3s;}
        html:not(.dark) body{background:#F0F4FF;color:#0F172A;}
        .dark body{background:var(--bg-void);color:#EEF2FF;}
        .syne{font-family:'Syne',sans-serif;}
        /* Scanlines dark only */
        .vr-scanlines{display:none;position:fixed;inset:0;pointer-events:none;z-index:1;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.025) 2px,rgba(0,0,0,.025) 4px);}
        .dark .vr-scanlines{display:block;}
        /* Glows */
        .glow{position:fixed;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0;opacity:0;transition:opacity .5s;}
        .dark .glow{opacity:1;}
        /* Cards */
        .xr-card{border-radius:20px;transition:transform .25s,border-color .25s,box-shadow .25s;position:relative;overflow:hidden;}
        html:not(.dark) .xr-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 4px 20px rgba(0,0,0,.06);}
        .dark .xr-card{background:var(--bg-panel);border:1px solid var(--border-w);}
        /* FIX: Unified hover state for both themes — was missing box-shadow on light mode */
        .xr-card:hover{transform:translateY(-3px);border-color:rgba(0,240,255,.25);box-shadow:0 12px 40px rgba(0,0,0,.12);}
        .xr-card::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--cyan),transparent);opacity:0;transition:opacity .3s;}
        .xr-card:hover::before{opacity:1;}
        /* Metric cards */
        .metric-card{border-radius:20px;padding:24px;}
        html:not(.dark) .metric-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 2px 12px rgba(0,0,0,.05);}
        .dark .metric-card{background:var(--bg-panel);border:1px solid var(--border-w);}
        /* Badge */
        .badge{display:inline-flex;align-items:center;padding:3px 12px;border-radius:100px;font-size:11px;font-weight:700;}
        .badge-green{background:rgba(0,230,118,.1);color:var(--emerald);}
        .badge-yellow{background:rgba(255,183,0,.1);color:var(--amber);}
        .badge-blue{background:rgba(0,240,255,.1);color:var(--cyan);}
        .badge-red{background:rgba(255,59,107,.1);color:var(--rose);}
        /* Quick action cards */
        .qa-card{border-radius:18px;padding:20px 16px;text-align:center;cursor:pointer;transition:all .25s;text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:10px;}
        html:not(.dark) .qa-card{background:#fff;border:1px solid rgba(0,0,0,.07);}
        .dark .qa-card{background:var(--bg-panel);border:1px solid var(--border-w);}
        .qa-card:hover{transform:translateY(-4px);}
        .qa-icon{width:48px;height:48px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto;}
        /* Table */
        table{width:100%;border-collapse:collapse;}
        thead th{padding:12px 20px;font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;text-align:left;}
        html:not(.dark) thead th{color:#64748B;background:rgba(0,0,0,.02);border-bottom:1px solid rgba(0,0,0,.06);}
        .dark thead th{color:var(--text-mid);background:rgba(255,255,255,.02);border-bottom:1px solid var(--border-w);}
        tbody td{padding:16px 20px;font-size:13px;}
        html:not(.dark) tbody td{border-bottom:1px solid rgba(0,0,0,.05);}
        .dark tbody td{border-bottom:1px solid rgba(255,255,255,.04);}
        tbody tr:last-child td{border-bottom:none;}
        html:not(.dark) tbody tr:hover td{background:rgba(0,240,255,.03);}
        .dark tbody tr:hover td{background:rgba(0,240,255,.03);}
        /* Chip */
        .xr-chip{display:inline-flex;align-items:center;gap:8px;border-radius:100px;padding:4px 14px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;background:rgba(0,240,255,.08);border:1px solid rgba(0,240,255,.2);color:var(--cyan);}
        .chip-dot{width:6px;height:6px;background:var(--cyan);border-radius:50%;animation:blink 1.5s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
        /* Colors */
        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}
        /* Sidebar */
        .sidebar{border-radius:20px;padding:24px;}
        html:not(.dark) .sidebar{background:#fff;border:1px solid rgba(0,0,0,.07);}
        .dark .sidebar{background:var(--bg-panel);border:1px solid var(--border-w);}
        .sidebar-item{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;font-size:14px;cursor:pointer;transition:all .2s;text-decoration:none;}
        html:not(.dark) .sidebar-item{color:#475569;}
        .dark .sidebar-item{color:var(--text-mid);}
        html:not(.dark) .sidebar-item:hover{background:rgba(0,240,255,.06);color:#0F172A;}
        .dark .sidebar-item:hover{background:rgba(0,240,255,.06);color:#EEF2FF;}
        .sidebar-item.active{background:rgba(0,240,255,.08);color:var(--cyan);}
        /* Avatar */
        .dash-avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--violet),var(--cyan));display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#fff;}
        /* Btn */
        .btn-sm{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:100px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;border:none;}
        .btn-cyan{background:var(--cyan);color:#020F14;}
        .btn-cyan:hover{transform:scale(1.04);}
        /* FIX: Added display:inline-flex and align-items:center so <a> and <button> both render consistently */
        .btn-outline-sm{display:inline-flex;align-items:center;background:transparent;font-size:13px;font-weight:600;padding:8px 18px;border-radius:100px;cursor:pointer;transition:all .2s;text-decoration:none;}
        html:not(.dark) .btn-outline-sm{border:1px solid rgba(0,0,0,.12);color:#475569;}
        .dark .btn-outline-sm{border:1px solid var(--border-w);color:var(--text-mid);}
        .btn-outline-sm:hover{border-color:var(--cyan);color:var(--cyan);}
        /* Progress bar */
        .prog-track{height:6px;border-radius:100px;background:rgba(255,255,255,.06);overflow:hidden;}
        html:not(.dark) .prog-track{background:rgba(0,0,0,.06);}
        .prog-fill{height:100%;border-radius:100px;}
        /* Layout */
        .page-wrap{max-width:1200px;margin:0 auto;padding:80px 28px 60px;}
        @media(max-width:900px){.dash-layout{grid-template-columns:1fr!important;}.metric-row{grid-template-columns:1fr 1fr!important;}.qa-row{grid-template-columns:1fr 1fr!important;}}
        @media(max-width:600px){.metric-row,.qa-row{grid-template-columns:1fr!important;}}
        @media(max-width:768px){.admin-panel-grid{grid-template-columns:1fr!important;}}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{opacity:0;animation:fadeUp .5s forwards;}
        .d1{animation-delay:.05s}.d2{animation-delay:.15s}.d3{animation-delay:.25s}.d4{animation-delay:.35s}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:500px;height:500px;top:-100px;left:-100px;background:rgba(0,240,255,.05)"></div>
    <div class="glow" style="width:600px;height:600px;bottom:0;right:-150px;background:rgba(139,92,246,.04)"></div>

    {{-- FIX: Pass is-admin prop so admin navbar styling works on this page --}}
    <x-navbar
        :is-authenticated="auth()->check()"
        :is-admin="auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin()))"
    />

    <div class="page-wrap" style="position:relative;z-index:10">

        @if(auth()->user()->isAdmin())
            {{-- ══════════════════════════════════════════════════════
                 ADMIN CONTROL TERMINAL VIEW
                 ══════════════════════════════════════════════════════ --}}

            {{-- Admin Header --}}
            <div class="fade-up d1" style="margin-bottom:40px">
                <span class="xr-chip" style="border-color:var(--rose);color:var(--rose);"><span class="chip-dot" style="background:var(--rose)"></span> HQ Terminal</span>
                <h1 class="syne text-hi" style="font-size:clamp(28px,4vw,48px);font-weight:800;letter-spacing:-1.5px;margin-top:16px;margin-bottom:8px">
                    System Control, <span style="color:var(--rose)">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-sub" style="font-size:15px">Root access enabled. Processing systemic registrations, claims approvals, and architectural configs.</p>
            </div>

            {{-- Admin Management Metric Cards --}}
            <div class="metric-row fade-up d2" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px">
                <div class="metric-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;font-weight:600" class="text-sub">System-wide Users</span>
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,240,255,.08);display:flex;align-items:center;justify-content:center;font-size:16px">👥</div>
                    </div>
                    <div class="syne" style="font-size:clamp(22px,3vw,32px);font-weight:700;color:var(--cyan);line-height:1">Active Node</div>
                </div>

                <div class="metric-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;font-weight:600" class="text-sub">Global Coverage Managed</span>
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(139,92,246,.08);display:flex;align-items:center;justify-content:center;font-size:16px">🛡️</div>
                    </div>
                    <div class="syne" style="font-size:clamp(22px,3vw,32px);font-weight:700;color:var(--violet);line-height:1">Protected</div>
                </div>

                <div class="metric-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;font-weight:600" class="text-sub">Claims Queue Status</span>
                        <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,59,107,.08);display:flex;align-items:center;justify-content:center;font-size:16px">⚡</div>
                    </div>
                    <div class="syne" style="font-size:clamp(22px,3vw,32px);font-weight:700;color:var(--rose);line-height:1">0 Actionable</div>
                </div>
            </div>

            {{-- Admin Control Hub Split Layout --}}
            <div class="admin-panel-grid fade-up d3" style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

                {{-- Quick Utility Matrix --}}
                <div class="xr-card" style="padding:24px">
                    <div style="margin-bottom:20px">
                        <h3 class="syne text-hi" style="font-weight:600;font-size:18px;">Administrative Tools Matrix</h3>
                        <p class="text-sub" style="font-size:13px;margin-top:2px;">Select an operations segment below to alter platform configurations.</p>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px" class="qa-row">
                        <a href="#" class="qa-card" style="border-color:var(--cyan)33;padding:28px 16px;">
                            <div class="qa-icon" style="background:rgba(0,240,255,.08);color:var(--cyan)">🛠️</div>
                            <span class="text-hi" style="font-size:14px;font-weight:600">Policy Systems Manager</span>
                        </a>

                        <a href="#" class="qa-card" style="border-color:var(--rose)33;padding:28px 16px;">
                            <div class="qa-icon" style="background:rgba(255,59,107,.08);color:var(--rose)">⚖️</div>
                            <span class="text-hi" style="font-size:14px;font-weight:600">Claims Resolution Engine</span>
                        </a>

                        <a href="#" class="qa-card" style="border-color:var(--violet)33;padding:28px 16px;">
                            <div class="qa-icon" style="background:rgba(139,92,246,.08);color:var(--violet)">👥</div>
                            <span class="text-hi" style="font-size:14px;font-weight:600">Identity Directory</span>
                        </a>

                        <a href="#" class="qa-card" style="border-color:var(--emerald)33;padding:28px 16px;">
                            <div class="qa-icon" style="background:rgba(0,230,118,.08);color:var(--emerald)">📡</div>
                            <span class="text-hi" style="font-size:14px;font-weight:600">AR & XR Module Logs</span>
                        </a>
                    </div>
                </div>

                {{-- Admin identity and shortcut stack --}}
                <div style="display:flex;flex-direction:column;gap:20px;">
                    <div class="xr-card" style="padding:24px">
                        <div style="text-align:center;padding-bottom:12px">
                            <div class="dash-avatar" style="margin:0 auto 12px;background:linear-gradient(135deg,var(--rose),var(--violet))">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                            <div class="syne text-hi" style="font-weight:600;font-size:16px">{{ auth()->user()->name }}</div>
                            <div style="margin-top:8px"><span class="badge badge-red" style="letter-spacing:1px;text-transform:uppercase;">Root Administrator</span></div>
                        </div>

                        <div style="margin-top:20px;border-top:1px solid" class="dark:border-white/5 border-slate-100">
                            <form method="POST" action="{{ route('logout') }}" style="margin-top:16px">
                                @csrf
                                <button type="submit" class="sidebar-item" style="width:100%;border:none;background:rgba(255,59,107,.06);border:1px solid rgba(255,59,107,0.15);border-radius:12px;justify-content:center;padding:12px;cursor:pointer;color:var(--rose)">
                                    <span>🚪</span><span style="font-weight:600">Secure Exit Terminal</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        @else
            {{-- ══════════════════════════════════════════════════════
                 STANDARD CONSUMER DASHBOARD VIEW
                 ══════════════════════════════════════════════════════ --}}

            {{-- Header --}}
            <div class="fade-up d1" style="margin-bottom:40px">
                <span class="xr-chip"><span class="chip-dot"></span> Dashboard</span>
                <h1 class="syne text-hi" style="font-size:clamp(28px,4vw,48px);font-weight:800;letter-spacing:-1.5px;margin-top:16px;margin-bottom:8px">
                    Welcome back, <span style="color:var(--cyan)">{{ auth()->user()->name }}</span>
                </h1>
                <p class="text-sub" style="font-size:15px">Your insurance command center. All policies, claims, and AR tools in one place.</p>
            </div>

            {{-- Metrics row --}}
            {{--
                FIX: Replaced var_export($activePoliciesCount, true) with the variable directly.
                var_export outputs PHP boolean strings ('true'/'false') for 0/1 values, which
                would display literally in the UI instead of the number.
            --}}
            <div class="metric-row fade-up d2" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px">
                @foreach([
                    [$activePoliciesCount,'Active Policies','var(--cyan)','🛡️','rgba(0,240,255,.08)'],
                    ['₹'.number_format($coverageTotal),'Total Coverage','var(--violet)','💰','rgba(139,92,246,.08)'],
                    ['₹'.number_format($monthlyPremiumTotal),'Monthly Premium','var(--emerald)','📋','rgba(0,230,118,.08)'],
                    ['0','Claims Filed','var(--rose)','⚡','rgba(255,59,107,.08)'],
                ] as [$val,$lbl,$col,$icon,$bg])
                <div class="metric-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1.5px;font-weight:600" class="text-sub">{{ $lbl }}</span>
                        <div style="width:36px;height:36px;border-radius:10px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;font-size:16px">{{ $icon }}</div>
                    </div>
                    <div class="syne" style="font-size:clamp(22px,3vw,32px);font-weight:700;color:{{ $col }};line-height:1">{{ $val }}</div>
                </div>
                @endforeach
            </div>

            {{-- Main layout --}}
            <div class="dash-layout fade-up d3" style="display:grid;grid-template-columns:220px 1fr;gap:20px;margin-bottom:28px;align-items:start">

                {{-- Sidebar --}}
                <div class="sidebar">
                    <div style="text-align:center;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid" class="dark:border-white/5 border-slate-100">
                        <div class="dash-avatar" style="margin:0 auto 12px">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
                        <div class="syne text-hi" style="font-weight:600;font-size:15px">{{ auth()->user()->name }}</div>
                        <div class="text-sub" style="font-size:11px;margin-top:3px">Policyholder since {{ auth()->user()->created_at->format('Y') }}</div>
                        <div style="margin-top:10px"><span class="badge badge-green">Active</span></div>
                    </div>
                    <nav>
                        @foreach([
                            ['📊','Overview','#','active'],
                            ['📄','My Policies',route('plans.index'),''],
                            ['⚡','File Claim',route('claims'),''],
                            ['🥽','AR Demo',route('vr'),''],
                            ['👤','Profile',route('profile.edit'),''],
                        ] as [$ic,$lb,$hr,$ac])
                        <a href="{{ $hr }}" class="sidebar-item {{ $ac }}">
                            <span>{{ $ic }}</span><span>{{ $lb }}</span>
                        </a>
                        @endforeach
                        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
                            @csrf
                            <button type="submit" class="sidebar-item" style="width:100%;border:none;background:transparent;cursor:pointer;color:var(--rose)">
                                <span>🚪</span><span>Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>

                {{-- Policies table --}}
                <div class="xr-card">
                    <div style="padding:20px 24px;border-bottom:1px solid;display:flex;align-items:center;justify-content:space-between" class="dark:border-white/5 border-slate-100">
                        <div class="syne text-hi" style="font-weight:600;font-size:17px">My Active Policies</div>
                        <a href="{{ route('plans.index') }}" class="btn-outline-sm">+ Add Policy</a>
                    </div>
                    @if($policies->isEmpty())
                    <div style="padding:48px;text-align:center">
                        <div style="font-size:48px;margin-bottom:16px">📋</div>
                        <div class="syne text-hi" style="font-weight:600;margin-bottom:8px">No policies yet</div>
                        <p class="text-sub" style="font-size:13px;margin-bottom:20px">Browse plans to secure your coverage.</p>
                        <a href="{{ route('plans.index') }}" class="btn-sm btn-cyan">Browse Plans →</a>
                    </div>
                    @else
                    <div style="overflow-x:auto">
                        <table>
                            <thead><tr>
                                <th>Policy</th><th>Plan</th><th>Coverage</th><th>Premium</th><th>Status</th><th>Action</th>
                            </tr></thead>
                            <tbody>
                            @foreach($policies as $policy)
                            <tr>
                                <td><div class="syne text-hi" style="font-weight:600;font-size:13px">{{ $policy->policy_number }}</div></td>
                                <td class="text-sub">{{ $policy->plan->name ?? 'Plan' }}</td>
                                <td class="text-sub">₹{{ number_format($policy->plan->coverage_amount ?? 0) }}</td>
                                <td class="text-sub">₹{{ number_format($policy->premium_paid) }}</td>
                                <td><span class="badge {{ $policy->status==='active'?'badge-green':'badge-yellow' }}">{{ ucfirst($policy->status) }}</span></td>
                                {{--
                                    FIX: Added a safe fallback href for policy statuses that don't match the
                                    three known states (e.g. 'cancelled', 'expired'), preventing a blank href.
                                --}}
                                <td>
                                    @if($policy->status === 'active')
                                        <a href="{{ route('transactions.success', $policy->_id) }}" style="color:var(--cyan);font-size:12px;font-weight:600">View →</a>
                                    @elseif($policy->status === 'pending_approval')
                                        <a href="{{ route('policies.application.success', $policy->_id) }}" style="color:var(--cyan);font-size:12px;font-weight:600">View →</a>
                                    @elseif($policy->status === 'approved')
                                        <a href="{{ route('transactions.create', $policy->_id) }}" style="color:var(--cyan);font-size:12px;font-weight:600">View →</a>
                                    @else
                                        <span style="color:var(--text-mid);font-size:12px">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

            </div>

            {{-- Account summary + Quick actions --}}
            <div style="display:grid;grid-template-columns:220px 1fr;gap:20px;margin-bottom:28px" class="dash-layout fade-up d4">

                {{-- Account info --}}
                <div class="xr-card" style="padding:24px">
                    <div class="syne text-hi" style="font-weight:600;font-size:16px;margin-bottom:20px">Account Info</div>
                    @foreach([
                        ['Email',auth()->user()->email],
                        ['Member Since',auth()->user()->created_at->format('M d, Y')],
                        ['Account Type','Premium'],
                    ] as [$k,$v])
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid" class="dark:border-white/5 border-slate-100">
                        <span class="text-sub" style="font-size:12px">{{ $k }}</span>
                        <span class="text-hi" style="font-size:12px;font-weight:600;max-width:130px;text-align:right;word-break:break-all">{{ $v }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Quick actions --}}
                <div class="xr-card" style="padding:24px">
                    <div class="syne text-hi" style="font-weight:600;font-size:16px;margin-bottom:20px">Quick Actions</div>
                    <div class="qa-row" style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
                        @foreach([
                            [route('ai.nexus'),'🤖','Nexus AI','rgba(0,240,255,.08)','var(--cyan)'],
                            [route('plans.index'),'📋','Browse Plans','rgba(0,240,255,.08)','var(--cyan)'],
                            [route('claims'),'⚡','File Claim','rgba(139,92,246,.08)','var(--violet)'],
                            [route('vr'),'🥽','AR Demo','rgba(0,230,118,.08)','var(--emerald)'],
                            [route('calculator'),'🧮','Calculator','rgba(255,183,0,.08)','var(--amber)'],
                        ] as [$href,$icon,$label,$bg,$col])
                        <a href="{{ $href }}" class="qa-card" style="border-color:{{ $col }}22">
                            <div class="qa-icon" style="background:{{ $bg }}">{{ $icon }}</div>
                            <span class="text-hi" style="font-size:13px;font-weight:600">{{ $label }}</span>
                        </a>
                        @endforeach
                    </div>

                    {{-- Premium next due --}}
                    <div style="margin-top:24px;padding:16px;border-radius:14px;background:linear-gradient(135deg,rgba(0,240,255,.05),rgba(139,92,246,.05));border:1px solid rgba(0,240,255,.12)">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                            <span class="text-sub" style="font-size:12px;text-transform:uppercase;letter-spacing:1px;font-weight:600">Next Premium Due</span>
                            <span class="badge badge-yellow">15 Jan 2025</span>
                        </div>
                        <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:10px">
                            <span class="syne" style="font-size:28px;font-weight:700;color:var(--amber)">₹1,299</span>
                            <span class="text-sub" style="font-size:12px">Family Fortress Plan</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="width:70%;background:linear-gradient(90deg,var(--emerald),var(--cyan))"></div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:6px">
                            <span class="text-sub" style="font-size:11px">12 months paid</span>
                            <span class="text-sub" style="font-size:11px">18 months left</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- VR Banner --}}
            <div style="border-radius:20px;padding:32px 40px;background:linear-gradient(135deg,rgba(0,240,255,.05),rgba(139,92,246,.06));border:1px solid rgba(0,240,255,.15);display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap">
                <div>
                    <div style="font-size:24px;margin-bottom:10px">🥽</div>
                    <div class="syne text-hi" style="font-size:22px;font-weight:700;margin-bottom:6px">Experience Your Policy in AR</div>
                    <p class="text-sub" style="font-size:14px;max-width:480px">Launch our cinematic accident simulation to see exactly what LifeShield XR covers — and why it matters.</p>
                </div>
                <a href="{{ route('vr') }}" class="btn-sm btn-cyan" style="padding:12px 28px;font-size:15px;white-space:nowrap">Launch AR Demo →</a>
            </div>
        @endif

    </div>
</body>
</html>