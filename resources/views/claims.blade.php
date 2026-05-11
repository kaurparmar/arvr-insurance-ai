<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Claims — {{ config('app.name', 'LifeShield XR') }}</title>
    
    {{-- High-tech Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    {{-- Theme Script --}}
    <script>(function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();</script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root{--cyan:#00F0FF;--violet:#8B5CF6;--rose:#FF3B6B;--emerald:#00E676;--amber:#FFB700;--bg-void:#03060F;--bg-deep:#060C1A;--bg-panel:rgba(8,14,30,.92);--text-mid:#8892AA;--border:rgba(0,240,255,.1);--border-w:rgba(255,255,255,.07);}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;}
        html:not(.dark) body{background:#F0F4FF;color:#0F172A;}
        .dark body{background:var(--bg-void);color:#EEF2FF;}
        .syne{font-family:'Syne',sans-serif;}

        /* Global Overlays */
        .vr-scanlines{display:none;position:fixed;inset:0;pointer-events:none;z-index:1;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.025) 2px,rgba(0,0,0,.025) 4px);}
        .dark .vr-scanlines{display:block;}
        .glow{position:fixed;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0;opacity:0.4;}

        /* XR Components */
        .xr-card{border-radius:24px;position:relative;overflow:hidden;transition:all .3s;}
        html:not(.dark) .xr-card{background:#fff;border:1px solid rgba(0,0,0,.07);box-shadow:0 4px 20px rgba(0,0,0,.06);}
        .dark .xr-card{background:var(--bg-panel);border:1px solid var(--border-w);backdrop-filter:blur(10px);}
        
        .xr-chip{display:inline-flex;align-items:center;gap:8px;border-radius:100px;padding:4px 14px;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;background:rgba(0,240,255,.08);border:1px solid rgba(0,240,255,.2);color:var(--cyan);}
        .chip-dot{width:6px;height:6px;background:var(--cyan);border-radius:50%;animation:blink 1.5s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}

        /* Inputs */
        .xr-input{width:100%;border-radius:16px;padding:12px 16px;transition:all .2s;font-size:14px;}
        html:not(.dark) .xr-input{background:#f8fafc;border:1px solid #e2e8f0;color:#0f172a;}
        .dark .xr-input{background:rgba(255,255,255,0.03);border:1px solid var(--border-w);color:#fff;}
        .xr-input:focus{outline:none;border-color:var(--cyan);box-shadow:0 0 0 4px rgba(0,240,255,0.1);}

        /* Button */
        .btn-xr{background:var(--cyan);color:#020F14;padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;border:none;cursor:pointer;}
        .btn-xr:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,240,255,0.3);}

        .badge{padding:4px 12px;border-radius:100px;font-size:10px;font-weight:700;text-transform:uppercase;}
        .badge-pending{background:rgba(255,183,0,0.1);color:var(--amber);}
        .badge-approved{background:rgba(0,230,118,0.1);color:var(--emerald);}

        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:400px;height:400px;top:-100px;right:-100px;background:rgba(139,92,246,.06)"></div>

    <x-navbar :is-authenticated="auth()->check()" />

    <div class="max-w-6xl mx-auto px-6 py-20 relative" style="z-index:10">
        
        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="xr-chip mb-4"><span class="chip-dot"></span> Claims Portal</span>
            <h1 class="syne text-hi text-5xl md:text-6xl font-extrabold tracking-tighter mb-4">Easy Claims Process</h1>
            <p class="text-sub text-lg">File and track your insurance claims with complete transparency.</p>
        </div>

        @auth
            <div class="grid lg:grid-cols-2 gap-10 mb-16">
                
                {{-- Form Card --}}
                <div class="xr-card p-8">
                    <h2 class="syne text-hi text-2xl font-bold mb-8">File a New Claim</h2>
                    <form action="{{ route('claims.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Select Policy</label>
                            <select name="policy_id" class="xr-input">
                                <option value="">Choose a policy</option>
                                @foreach($policies as $policy)
                                    <option value="{{ $policy->_id ?? $policy->id }}">{{ $policy->policy_number }} — {{ $policy->plan->name ?? 'Plan' }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Claim Amount</label>
                                <input name="claim_amount" type="number" step="0.01" class="xr-input" placeholder="₹ 0.00" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Date of Incident</label>
                                <input name="incident_date" type="date" class="xr-input" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Claim Reason</label>
                            <textarea name="claim_reason" rows="4" class="xr-input" placeholder="Describe the event for the claims officer..."></textarea>
                        </div>

                        <button type="submit" class="btn-xr w-full syne text-sm uppercase tracking-wider">Submit Claim Authorization</button>
                    </form>
                </div>

                {{-- Status Feed --}}
                <div class="space-y-6">
                    <div class="xr-card p-8">
                        <h2 class="syne text-hi text-2xl font-bold mb-6">Recent Activity</h2>
                        <div class="space-y-4">
                            @forelse($claims as $claim)
                                <div class="p-5 rounded-2xl border dark:border-white/5 border-slate-100 bg-white/5 transition hover:border-cyan/30">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="syne text-hi font-bold text-sm">#{{ substr($claim->id, -8) }}</div>
                                        <span class="badge {{ $claim->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                            {{ $claim->status }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-xs text-sub mb-1">Policy: {{ $claim->policy->policy_number ?? 'N/A' }}</p>
                                            <p class="text-hi font-medium">₹{{ number_format($claim->claim_amount) }}</p>
                                        </div>
                                        <p class="text-[10px] text-sub">{{ $claim->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="text-4xl mb-4">📡</div>
                                    <p class="text-sub text-sm">No active claim signals found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Guest State --}}
            <div class="xr-card p-16 text-center max-w-2xl mx-auto mb-20">
                <h2 class="syne text-hi text-3xl font-bold mb-4">Access Restricted</h2>
                <p class="text-sub mb-10">Neural link required. Please sign in to access the LifeShield XR claims database.</p>
                <a href="{{ route('login') }}" class="btn-xr px-10">Authenticate Identity</a>
            </div>
        @endauth

        {{-- Footer Steps --}}
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['01', 'Submit Data', 'var(--cyan)'],
                ['02', 'Neural Review', 'var(--violet)'],
                ['03', 'Instant Payout', 'var(--emerald)']
            ] as [$num, $title, $color])
            <div class="xr-card p-8 group">
                <div class="syne text-4xl font-black mb-4 opacity-20 group-hover:opacity-100 transition-opacity" style="color:{{ $color }}">{{ $num }}</div>
                <h3 class="syne text-hi text-xl font-bold mb-2">{{ $title }}</h3>
                <p class="text-sub text-sm">Encrypted processing ensuring your data reaches our officers securely.</p>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>