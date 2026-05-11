<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Application Submitted - {{ config('app.name', 'LifeShield XR') }}</title>

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

        /* Button */
        .btn-xr{background:var(--cyan);color:#020F14;padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;border:none;cursor:pointer;}
        .btn-xr:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,240,255,0.3);}

        .btn-secondary{background:rgba(255,255,255,0.1);color:#fff;border:1px solid var(--border-w);padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;}
        .btn-secondary:hover{background:rgba(255,255,255,0.2);}

        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}

        /* Success Animation */
        .success-icon{margin:0 auto 24px;width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,var(--emerald),var(--cyan));display:flex;align-items:center;justify-content:center;animation:successPulse 2s infinite;}
        @keyframes successPulse{0%,100%{transform:scale(1);box-shadow:0 0 0 0 rgba(0,230,118,0.4);}50%{transform:scale(1.05);box-shadow:0 0 0 20px rgba(0,230,118,0);}}

        /* Timeline */
        .timeline{position:relative;padding-left:40px;}
        .timeline::before{content:'';position:absolute;left:15px;top:0;bottom:0;width:2px;background:var(--border-w);}
        .timeline-item{position:relative;margin-bottom:24px;}
        .timeline-item::before{content:'';position:absolute;left:-25px;top:8px;width:12px;height:12px;border-radius:50%;background:var(--cyan);border:2px solid var(--bg-void);}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:400px;height:400px;top:-100px;right:-100px;background:rgba(0,230,118,.06)"></div>

    <x-navbar :is-authenticated="auth()->check()" />

    <div class="max-w-4xl mx-auto px-6 py-20 relative" style="z-index:10">

        {{-- Success Header --}}
        <div class="text-center mb-16">
            <div class="success-icon">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="xr-chip mb-4"><span class="chip-dot"></span> Application Submitted</span>
            <h1 class="syne text-hi text-5xl md:text-6xl font-extrabold tracking-tighter mb-4">Application Received!</h1>
            <p class="text-sub text-lg">Your policy application has been successfully submitted and is now under review.</p>
        </div>

        {{-- Application Details --}}
        <div class="grid lg:grid-cols-2 gap-8 mb-12">

            {{-- Policy Summary --}}
            <div class="xr-card p-6">
                <h3 class="syne text-hi text-xl font-bold mb-4">Policy Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sub">Policy Number:</span>
                        <span class="text-hi font-semibold">{{ $policy->policy_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sub">Plan:</span>
                        <span class="text-hi font-semibold">{{ $policy->plan->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sub">Coverage Amount:</span>
                        <span class="text-hi font-semibold">₹{{ number_format($policy->coverage_amount) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sub">Monthly Premium:</span>
                        <span class="text-hi font-semibold">₹{{ number_format($policy->premium_paid) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sub">Application Date:</span>
                        <span class="text-hi font-semibold">{{ $policy->application_date ? $policy->application_date->format('M j, Y') : now()->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Processing Timeline --}}
            <div class="xr-card p-6">
                <h3 class="syne text-hi text-xl font-bold mb-4">Processing Timeline</h3>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="text-hi font-semibold text-sm">Application Submitted</div>
                        <div class="text-sub text-xs">{{ $policy->application_date ? $policy->application_date->format('M j, Y g:i A') : now()->format('M j, Y g:i A') }}</div>
                    </div>
                    <div class="timeline-item">
                        <div class="text-hi font-semibold text-sm">Document Verification</div>
                        <div class="text-sub text-xs">24-48 hours</div>
                    </div>
                    <div class="timeline-item">
                        <div class="text-hi font-semibold text-sm">Medical Underwriting</div>
                        <div class="text-sub text-xs">2-3 business days</div>
                    </div>
                    <div class="timeline-item">
                        <div class="text-hi font-semibold text-sm">Policy Activation</div>
                        <div class="text-sub text-xs">Upon approval</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next Steps --}}
        <div class="xr-card p-8 mb-8">
            <h3 class="syne text-hi text-xl font-bold mb-6">What Happens Next?</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">📋</span>
                    </div>
                    <h4 class="font-semibold text-hi mb-2">Document Review</h4>
                    <p class="text-sub text-sm">Our team will verify all submitted documents for authenticity and completeness.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-violet-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">🏥</span>
                    </div>
                    <h4 class="font-semibold text-hi mb-2">Medical Assessment</h4>
                    <p class="text-sub text-sm">Medical certificates and health declarations will be reviewed by our underwriting team.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white text-2xl">✅</span>
                    </div>
                    <h4 class="font-semibold text-hi mb-2">Policy Activation</h4>
                    <p class="text-sub text-sm">Once approved, your policy will be activated and coverage will begin immediately.</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
 
            <a href="{{ route('transactions.create', $policy->_id ?? $policy->id) }}" class="btn-xr px-8">
                Proceed to Payment
            </a>
            <a href="{{ route('dashboard') }}" class="btn-secondary px-8">
                Back to Dashboard
            </a>
        </div>

        {{-- Contact Information --}}
        <div class="text-center mt-12">
            <p class="text-sub text-sm mb-4">
                Questions about your application?
                <a href="mailto:applications@liveshieldxr.com" class="text-cyan-500 hover:text-cyan-400">Contact our team</a>
            </p>
            <p class="text-sub text-xs">
                Processing typically takes 24-48 hours. You'll receive email updates at every step.
            </p>
        </div>
    </div>
</body>
</html>