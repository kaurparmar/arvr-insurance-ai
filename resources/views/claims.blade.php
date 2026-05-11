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
            {{-- Action Buttons --}}
            <div class="flex justify-center mb-12">
                <a href="{{ route('claims.create') }}" class="btn-xr px-12 py-4 syne text-lg uppercase tracking-wider">
                    🚀 File New Claim
                </a>
            </div>

            <div class="grid lg:grid-cols-2 gap-10 mb-16">

                {{-- Claims List --}}
                <div class="xr-card p-8">
                    <h2 class="syne text-hi text-2xl font-bold mb-8">Your Claims History</h2>
                    <div class="space-y-4">
                        @forelse($claims as $claim)
                            <a href="{{ route('claims.show', $claim->_id ?? $claim->id) }}" class="block p-5 rounded-2xl border dark:border-white/5 border-slate-100 bg-white/5 transition hover:border-cyan/30 hover:bg-cyan/5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="syne text-hi font-bold text-sm">#{{ substr($claim->_id ?? $claim->id, -8) }}</div>
                                    <span class="badge {{ $claim->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                        {{ $claim->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-xs text-sub mb-1">Policy: {{ $claim->policy->policy_number ?? 'N/A' }}</p>
                                        <p class="text-hi font-medium">₹{{ number_format($claim->claim_amount) }}</p>
                                        @if($claim->incident_date)
                                        <p class="text-xs text-sub">Incident: {{ $claim->incident_date?->format('M j, Y') ?? 'N/A' }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-sub">{{ $claim->submitted_at->diffForHumans() }}</p>
                                        <div class="text-xs text-cyan-500 mt-1">View Details →</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-12 text-center">
                                <div class="text-4xl mb-4">📡</div>
                                <p class="text-sub text-sm mb-4">No claims found in your records.</p>
                                <a href="{{ route('claims.create') }}" class="text-cyan-500 hover:text-cyan-400 text-sm underline">
                                    File your first claim →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="space-y-6">
                    <div class="xr-card p-8">
                        <h2 class="syne text-hi text-2xl font-bold mb-6">Claims Overview</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                <div class="text-2xl font-bold text-hi mb-1">{{ $claims->count() }}</div>
                                <div class="text-xs text-sub uppercase tracking-wider">Total Claims</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                <div class="text-2xl font-bold text-emerald-500 mb-1">{{ $claims->where('status', 'approved')->count() }}</div>
                                <div class="text-xs text-sub uppercase tracking-wider">Approved</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                <div class="text-2xl font-bold text-amber-500 mb-1">{{ $claims->where('status', 'pending')->count() }}</div>
                                <div class="text-xs text-sub uppercase tracking-wider">Pending</div>
                            </div>
                            <div class="text-center p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                                <div class="text-2xl font-bold text-hi mb-1">₹{{ number_format($claims->sum('claim_amount')) }}</div>
                                <div class="text-xs text-sub uppercase tracking-wider">Total Value</div>
                            </div>
                        </div>
                    </div>

                    {{-- Help Card --}}
                    <div class="xr-card p-8">
                        <h3 class="syne text-hi text-xl font-bold mb-4">Need Assistance?</h3>
                        <p class="text-sub text-sm mb-4">Our AI claims assistant is available 24/7 to help you with your claims process.</p>
                        <div class="space-y-2">
                            <a href="mailto:claims@liveshieldxr.com" class="block text-cyan-500 hover:text-cyan-400 text-sm">
                                📧 claims@liveshieldxr.com
                            </a>
                            <a href="tel:+1-800-CLAIMS" class="block text-cyan-500 hover:text-cyan-400 text-sm">
                                📞 1-800-CLAIMS (252467)
                            </a>
                            <a href="#" class="block text-cyan-500 hover:text-cyan-400 text-sm">
                                💬 Live Chat Support
                            </a>
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