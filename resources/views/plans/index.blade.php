<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Insurance Plans — {{ config('app.name','LifeShield XR') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>(function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();</script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        :root{--cyan:#00F0FF;--violet:#8B5CF6;--rose:#FF3B6B;--emerald:#00E676;--amber:#FFB700;--bg-void:#03060F;--bg-panel:rgba(8,14,30,.92);--text-mid:#8892AA;--border-w:rgba(255,255,255,.07);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;transition:background .3s;}
        html:not(.dark) body{background:#F0F4FF;color:#0F172A;}
        .dark body{background:var(--bg-void);color:#EEF2FF;}
        .syne{font-family:'Syne',sans-serif;}
        .vr-scanlines{display:none;position:fixed;inset:0;pointer-events:none;z-index:1;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.025) 2px,rgba(0,0,0,.025) 4px);}
        .dark .vr-scanlines{display:block;}
        .glow{position:fixed;border-radius:50%;filter:blur(130px);pointer-events:none;z-index:0;opacity:0;transition:opacity .5s;}
        .dark .glow{opacity:1;}
        .xr-chip{display:inline-flex;align-items:center;gap:8px;border-radius:100px;padding:5px 16px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;background:rgba(0,240,255,.08);border:1px solid rgba(0,240,255,.2);color:var(--cyan);}
        .chip-dot{width:6px;height:6px;background:var(--cyan);border-radius:50%;animation:blink 1.5s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}
        /* Plan card */
        .plan-card{border-radius:24px;padding:32px;position:relative;overflow:hidden;transition:all .3s;}
        html:not(.dark) .plan-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 4px 24px rgba(0,0,0,.06);}
        .dark .plan-card{background:var(--bg-panel);border:1px solid var(--border-w);}
        .plan-card:hover{transform:translateY(-8px);border-color:rgba(0,240,255,.3)!important;box-shadow:0 24px 60px rgba(0,0,0,.2);}
        .plan-card::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--cyan),transparent);opacity:0;transition:opacity .3s;}
        .plan-card:hover::before{opacity:1;}
        .plan-card.featured{border-color:var(--cyan)!important;background:rgba(0,240,255,.03)!important;}
        .dark .plan-card.featured{background:rgba(0,240,255,.04)!important;}
        .featured-badge{position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:var(--cyan);color:#020F14;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:5px 18px;border-radius:0 0 10px 10px;}
        /* Feature rows */
        .feat-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid;}
        html:not(.dark) .feat-row{border-color:rgba(0,0,0,.05);}
        .dark .feat-row{border-color:rgba(255,255,255,.04);}
        .feat-row:last-child{border-bottom:none;}
        .feat-check{width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;}
        .check-green{background:rgba(0,230,118,.1);color:var(--emerald);}
        /* Price */
        .price-amount{font-family:'Syne',sans-serif;font-size:42px;font-weight:800;line-height:1;}
        /* Buttons */
        .btn-plan{width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px 24px;border-radius:100px;font-size:14px;font-weight:700;cursor:pointer;border:none;transition:all .2s;text-decoration:none;font-family:'DM Sans',sans-serif;}
        .btn-plan.primary{background:var(--cyan);color:#020F14;}
        .btn-plan.primary:hover{transform:scale(1.02);box-shadow:0 8px 30px rgba(0,240,255,.3);}
        html:not(.dark) .btn-plan.outline{background:transparent;border:1px solid rgba(0,0,0,.12);color:#0F172A;}
        .dark .btn-plan.outline{background:transparent;border:1px solid var(--border-w);color:#EEF2FF;}
        .btn-plan.outline:hover{border-color:var(--cyan);color:var(--cyan);}
        /* Filter tabs */
        .filter-tab{padding:8px 22px;border-radius:100px;font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;border:none;font-family:'DM Sans',sans-serif;}
        html:not(.dark) .filter-tab{background:#fff;border:1px solid rgba(0,0,0,.1);color:#475569;}
        .dark .filter-tab{background:var(--bg-panel);border:1px solid var(--border-w);color:var(--text-mid);}
        .filter-tab.active,.filter-tab:hover{background:var(--cyan);color:#020F14;border-color:var(--cyan);}
        /* Comparison table */
        .comp-table{width:100%;border-collapse:collapse;border-radius:16px;overflow:hidden;}
        .comp-table th,.comp-table td{padding:14px 20px;text-align:center;font-size:13px;border-bottom:1px solid;}
        html:not(.dark) .comp-table th,.comp-table td{border-color:rgba(0,0,0,.06);}
        .dark .comp-table th,.comp-table td{border-color:rgba(255,255,255,.04);}
        .comp-table th{font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:1px;}
        html:not(.dark) .comp-table th{background:rgba(0,0,0,.03);color:#475569;}
        .dark .comp-table th{background:rgba(255,255,255,.03);color:var(--text-mid);}
        .comp-table td:first-child{text-align:left;font-weight:500;}
        html:not(.dark) .comp-table tbody tr:hover td{background:rgba(0,240,255,.03);}
        .dark .comp-table tbody tr:hover td{background:rgba(0,240,255,.03);}
        /* Layout */
        .page-wrap{max-width:1100px;margin:0 auto;padding:100px 28px 80px;position:relative;z-index:10;}
        .plans-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
        @media(max-width:900px){.plans-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:600px){.plans-grid{grid-template-columns:1fr;}}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{opacity:0;animation:fadeUp .6s forwards;}
        .d1{animation-delay:.1s}.d2{animation-delay:.25s}.d3{animation-delay:.4s}.d4{animation-delay:.55s}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:600px;height:600px;top:-150px;left:-150px;background:rgba(0,240,255,.05)"></div>
    <div class="glow" style="width:500px;height:500px;bottom:0;right:-100px;background:rgba(139,92,246,.05)"></div>
    <x-navbar :is-authenticated="auth()->check()" />

    <div class="page-wrap">

        {{-- Header --}}
        <div class="fade-up d1" style="text-align:center;margin-bottom:56px">
            <span class="xr-chip"><span class="chip-dot"></span> Insurance Plans</span>
            <h1 class="syne text-hi" style="font-size:clamp(36px,5vw,64px);font-weight:800;letter-spacing:-2px;margin:20px 0 16px">
                Choose Your <span style="color:var(--cyan)">Protection</span>
            </h1>
            <p class="text-sub" style="font-size:17px;max-width:520px;margin:0 auto;line-height:1.7">
                All plans include AR-powered onboarding, instant issuance, and VR-based claims processing. No paperwork.
            </p>
        </div>

        {{-- Filter tabs --}}
        <div class="fade-up d2" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-bottom:48px">
            @foreach(['All Plans','Term Insurance','Whole Life','ULIP','Child Plans'] as $tab)
            <button class="filter-tab {{ $loop->first ? 'active' : '' }}">{{ $tab }}</button>
            @endforeach
        </div>

        {{-- Plans grid --}}
        <div class="plans-grid fade-up d3">
            @forelse($plans as $plan)
            @php
                $colors = ['var(--cyan)','var(--violet)','var(--rose)','var(--amber)','var(--emerald)'];
                $col = $colors[$loop->index % count($colors)];
                $featured = $loop->index === 1;
                $featureItems = array_slice($plan->features ?? ['AR onboarding included','Instant policy issuance','Tax benefit u/s 80C','24/7 support'], 0, 5);
            @endphp
            <div class="plan-card {{ $featured ? 'featured' : '' }}">
                @if($featured)<div class="featured-badge">Most Popular</div>@endif
                <div style="margin-bottom:24px;padding-top:{{ $featured ? '12px' : '0' }}">
                    <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;font-weight:700;color:{{ $col }};margin-bottom:8px">
                        {{ $plan->duration_years }} Year Term
                    </div>
                    <h3 class="syne text-hi" style="font-size:22px;font-weight:700;margin-bottom:12px">{{ $plan->name }}</h3>
                    <p class="text-sub" style="font-size:13px;line-height:1.6">{{ Str::limit($plan->description,100) }}</p>
                </div>

                <div style="margin-bottom:24px">
                    <div class="price-amount" style="color:{{ $col }}">
                        ₹{{ number_format($plan->premium_amount) }}
                    </div>
                    <div class="text-sub" style="font-size:12px;margin-top:4px">per year &bull; ₹{{ number_format($plan->coverage_amount) }} coverage</div>
                </div>

                <div style="margin-bottom:28px">
                    @foreach($featureItems as $feat)
                    <div class="feat-row">
                        <div class="feat-check check-green">✓</div>
                        <span class="text-sub" style="font-size:13px">{{ $feat }}</span>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('plans.show', $plan) }}" class="btn-plan {{ $featured ? 'primary' : 'outline' }}">
                    {{ $featured ? 'Select Plan →' : 'View Details →' }}
                </a>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:80px 24px">
                <div style="font-size:56px;margin-bottom:20px">📋</div>
                <div class="syne text-hi" style="font-size:24px;font-weight:700;margin-bottom:8px">No plans available</div>
                <p class="text-sub" style="font-size:15px">Check back later for new insurance plans.</p>
            </div>
            @endforelse
        </div>

        {{-- Comparison table --}}
        @if($plans->count() > 0)
        <div class="fade-up d4" style="margin-top:72px">
            <div style="text-align:center;margin-bottom:36px">
                <span class="xr-chip"><span class="chip-dot"></span> Compare</span>
                <h2 class="syne text-hi" style="font-size:clamp(24px,3vw,36px);font-weight:700;letter-spacing:-1px;margin-top:16px">Plan Comparison</h2>
            </div>
            <div style="border-radius:20px;overflow:hidden;border:1px solid rgba(0,240,255,.1)">
                <table class="comp-table">
                    <thead><tr>
                        <th style="text-align:left">Feature</th>
                        @foreach($plans->take(3) as $p)<th>{{ $p->name }}</th>@endforeach
                    </tr></thead>
                    <tbody>
                    @foreach([
                        ['Coverage',fn($p)=>'₹'.number_format($p->coverage_amount)],
                        ['Term',fn($p)=>$p->duration_years.' years'],
                        ['Annual Premium',fn($p)=>'₹'.number_format($p->premium_amount)],
                        ['AR Experience','✓'],
                        ['Instant Issuance','✓'],
                        ['Tax Benefit','✓'],
                    ] as $row)
                    <tr>
                        <td class="text-hi" style="font-weight:500;font-size:13px">{{ $row[0] }}</td>
                        @foreach($plans->take(3) as $p)
                        <td>
                            @if(is_callable($row[1]))
                                <span style="color:var(--cyan);font-weight:600">{{ ($row[1])($p) }}</span>
                            @else
                                <span style="color:var(--emerald);font-size:16px">{{ $row[1] }}</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Trust bar --}}
        <div style="margin-top:56px;padding:28px 32px;border-radius:20px;background:linear-gradient(135deg,rgba(0,240,255,.04),rgba(139,92,246,.04));border:1px solid rgba(0,240,255,.1);display:flex;gap:32px;flex-wrap:wrap;justify-content:center;align-items:center">
            @foreach(['✓ IRDAI Registered','✓ 98.7% Claim Settlement','✓ 2.4M+ Policyholders','✓ Blockchain Secured','✓ Instant Issuance'] as $t)
            <div style="font-size:13px;color:var(--emerald);font-weight:600">{{ $t }}</div>
            @endforeach
        </div>

    </div>
</body>
</html>