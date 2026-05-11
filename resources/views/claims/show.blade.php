<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Claim #{{ $claim->_id ?? $claim->id }} - {{ config('app.name', 'LifeShield XR') }}</title>

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

        .status-badge{display:inline-flex;align-items:center;gap:6px;border-radius:100px;padding:6px 16px;font-size:12px;font-weight:700;text-transform:uppercase;}
        .status-pending{background:rgba(255,183,0,0.1);color:var(--amber);border:1px solid rgba(255,183,0,0.2);}
        .status-approved{background:rgba(0,230,118,0.1);color:var(--emerald);border:1px solid rgba(0,230,118,0.2);}
        .status-rejected{background:rgba(255,59,107,0.1);color:var(--rose);border:1px solid rgba(255,59,107,0.2);}
        .status-processing{background:rgba(139,92,246,0.1);color:var(--violet);border:1px solid rgba(139,92,246,0.2);}

        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}

        /* Document Grid */
        .doc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;}
        .doc-item{border-radius:16px;padding:16px;text-align:center;transition:all .2s;cursor:pointer;}
        html:not(.dark) .doc-item{background:#f8fafc;border:1px solid #e2e8f0;}
        .dark .doc-item{background:rgba(255,255,255,0.02);border:1px solid var(--border-w);}
        .doc-item:hover{transform:translateY(-2px);border-color:var(--cyan);}

        /* Timeline */
        .timeline{position:relative;padding-left:40px;}
        .timeline::before{content:'';position:absolute;left:15px;top:0;bottom:0;width:2px;background:var(--border-w);}
        .timeline-item{position:relative;margin-bottom:24px;}
        .timeline-item::before{content:'';position:absolute;left:-25px;top:8px;width:12px;height:12px;border-radius:50%;background:var(--cyan);border:2px solid var(--bg-void);}
        .timeline-content{background:var(--bg-panel);border-radius:16px;padding:16px;border:1px solid var(--border-w);}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:400px;height:400px;top:-100px;right:-100px;background:rgba(139,92,246,.06)"></div>

    <x-navbar :is-authenticated="auth()->check()" />

    <div class="max-w-6xl mx-auto px-6 py-20 relative" style="z-index:10">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="xr-chip mb-4"><span class="chip-dot"></span> {{ __('messages.Claim Details') }}</span>
                <h1 class="syne text-hi text-4xl md:text-5xl font-extrabold tracking-tighter mb-2">
                    {{ __('messages.Claim') }} #{{ substr($claim->_id ?? $claim->id, -8) }}
                </h1>
                <p class="text-sub text-lg">{{ __('messages.Filed on') }} {{ $claim->submitted_at->format('M j, Y') }}</p>
            </div>
            <div class="status-badge status-{{ $claim->status }}">
                <span>{{ $claim->getStatusIconAttribute() }}</span>
                {{ ucfirst($claim->status) }}
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            {{-- Main Details --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Policy Information --}}
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Policy Information') }}</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Policy Number</p>
                            <p class="text-hi font-semibold">{{ $claim->policy->policy_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Plan</p>
                            <p class="text-hi font-semibold">{{ $claim->policy->plan->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Coverage Amount</p>
                            <p class="text-hi font-semibold">₹{{ number_format($claim->policy->plan->coverage_amount) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Claim Amount</p>
                            <p class="text-hi font-semibold text-{{ $claim->claim_amount > $claim->policy->plan->coverage_amount ? 'rose' : 'emerald' }}-500">
                                ₹{{ number_format($claim->claim_amount) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Incident Details --}}
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Incident Details') }}</h3>
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Incident Date</p>
                                <p class="text-hi font-semibold">{{ $claim->incident_date?->format('M j, Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Location</p>
                                <p class="text-hi font-semibold">{{ $claim->incident_location }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-2">Description</p>
                            <p class="text-hi leading-relaxed">{{ $claim->claim_reason }}</p>
                        </div>
                        @if($claim->witnesses)
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-sub mb-2">Witness Information</p>
                            <p class="text-hi leading-relaxed">{{ $claim->witnesses }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Documents --}}
                @if($claim->medical_reports || $claim->police_report || $claim->damage_photos || $claim->other_documents)
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Submitted Documents') }}</h3>

                    @if($claim->medical_reports)
                    <div class="mb-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-sub mb-3">Medical Reports</p>
                        <div class="doc-grid">
                            @foreach($claim->medical_reports as $doc)
                            <a href="{{ Storage::url($doc) }}" target="_blank" class="doc-item">
                                <div class="text-2xl mb-2">🏥</div>
                                <p class="text-xs text-sub">{{ basename($doc) }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($claim->police_report)
                    <div class="mb-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-sub mb-3">Police Report</p>
                        <a href="{{ Storage::url($claim->police_report) }}" target="_blank" class="doc-item">
                            <div class="text-2xl mb-2">🚔</div>
                            <p class="text-xs text-sub">{{ basename($claim->police_report) }}</p>
                        </a>
                    </div>
                    @endif

                    @if($claim->damage_photos)
                    <div class="mb-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-sub mb-3">Damage Photos</p>
                        <div class="doc-grid">
                            @foreach($claim->damage_photos as $photo)
                            <a href="{{ Storage::url($photo) }}" target="_blank" class="doc-item">
                                <div class="text-2xl mb-2">📸</div>
                                <p class="text-xs text-sub">{{ basename($photo) }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($claim->other_documents)
                    <div class="mb-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-sub mb-3">Other Documents</p>
                        <div class="doc-grid">
                            @foreach($claim->other_documents as $doc)
                            <a href="{{ Storage::url($doc) }}" target="_blank" class="doc-item">
                                <div class="text-2xl mb-2">📄</div>
                                <p class="text-xs text-sub">{{ basename($doc) }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8">

                {{-- Status Timeline --}}
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Claim Timeline') }}</h3>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">Submitted</p>
                                <p class="text-hi text-sm">{{ $claim->submitted_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>

                        @if($claim->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <p class="text-xs font-bold uppercase tracking-widest text-sub mb-1">
                                    @if($claim->status === 'approved') Approved
                                    @elseif($claim->status === 'rejected') Rejected
                                    @else Processing
                                    @endif
                                </p>
                                <p class="text-hi text-sm">{{ $claim->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Quick Actions') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('claims') }}" class="block w-full text-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-hi px-4 py-3 rounded-lg transition-colors">
                            ← {{ __('messages.Back to Claims') }}
                        </a>
                        @if($claim->status === 'pending')
                        <button onclick="printClaim()" class="block w-full text-center bg-cyan-500 hover:bg-cyan-600 text-cyan-900 dark:text-cyan-950 px-4 py-3 rounded-lg transition-colors font-semibold">
                            📄 {{ __('messages.Print Claim') }}
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Contact Support --}}
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-4">{{ __('messages.Need Help?') }}</h3>
                    <p class="text-sub text-sm mb-4">Contact our claims support team for assistance with your claim.</p>
                    <div class="space-y-2">
                        <a href="mailto:claims@liveshieldxr.com" class="block text-cyan-500 hover:text-cyan-400 text-sm">
                            📧 claims@liveshieldxr.com
                        </a>
                        <a href="tel:+1-800-CLAIMS" class="block text-cyan-500 hover:text-cyan-400 text-sm">
                            📞 1-800-CLAIMS (252467)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printClaim() {
            window.print();
        }
    </script>
</body>
</html>