<x-app-layout>
    <style>
        /* CSS Variable Injection & Body Setup */
        :root {
            --cyan: #00F0FF;
            --violet: #8B5CF6;
            --rose: #FF3B6B;
            --emerald: #00E676;
            --amber: #FFB700;
            --bg-void: #03060F;
            --bg-deep: #060C1A;
            --bg-panel: rgba(8, 14, 30, 0.92);
            --text-mid: #8892AA;
            --border-w: rgba(255, 255, 255, 0.07);
        }

        /* Force background to match Dashboard theme */
        .min-h-screen {
            background-color: #F0F4FF; /* Light mode */
            transition: background-color 0.3s;
        }
        .dark .min-h-screen {
            background-color: var(--bg-void);
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(0, 240, 255, 0.05) 0%, transparent 50%);
        }

        .syne { font-family: 'Syne', sans-serif; }
        
        .vr-scanlines {
            position: fixed; inset: 0; pointer-events: none; z-index: 1;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0, 0, 0, 0.02) 2px, rgba(0, 0, 0, 0.02) 4px);
            display: none;
        }
        .dark .vr-scanlines { display: block; opacity: 0.4; }
        
        .xr-card {
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        html:not(.dark) .xr-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .dark .xr-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-w);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        .xr-card:hover {
            border-color: rgba(0, 240, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .xr-chip {
            display: inline-flex; align-items:center; gap:8px; border-radius:100px; padding:6px 16px;
            font-size:10px; font-weight:800; letter-spacing:2px; text-transform:uppercase;
            background: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0, 240, 255, 0.2); color: var(--cyan);
        }
        
        .btn-xr {
            background: var(--cyan); color: #020F14; padding: 18px 24px; border-radius: 18px;
            font-weight: 800; text-align: center; transition: all 0.3s; border: none;
            cursor: pointer; display: block; width: 100%; text-transform: uppercase;
            letter-spacing: 1.5px; font-size: 13px; font-family: 'Syne', sans-serif;
        }
        .btn-xr:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.4);
            filter: brightness(1.1);
        }

        .text-hi { color: #0F172A; }
        .dark .text-hi { color: #EEF2FF; }
        .text-sub { color: #64748B; }
        .dark .text-sub { color: var(--text-mid); }

        /* Feature Item Highlight */
        .feature-item {
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .feature-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>

    {{-- Background Elements --}}
    <div class="vr-scanlines"></div>
    
    <div class="py-12 relative z-10 min-h-screen">
        {{-- Decorative Blur --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            {{-- Header/Breadcrumb --}}
            <div class="mb-10 flex items-center gap-4">
                <span class="xr-chip">
                    <span class="w-2 h-2 bg-cyan-400 rounded-full animate-ping"></span> 
                    Active Deployment Protocol
                </span>
                <div class="h-[1px] flex-grow bg-gradient-to-r from-cyan-500/50 to-transparent"></div>
            </div>

            <div class="grid gap-10 lg:grid-cols-[1.3fr_0.7fr]">
                
                {{-- Left: Plan Intelligence --}}
                <div class="xr-card p-8 lg:p-14">
                    <div class="mb-10">
                        <h1 class="syne text-hi text-5xl lg:text-6xl font-extrabold tracking-tighter mb-6 leading-none">
                            {{ $plan->name }}
                        </h1>
                        <p class="text-sub text-xl leading-relaxed max-w-2xl">
                            {{ $plan->description }}
                        </p>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="grid gap-6 sm:grid-cols-2 mb-12">
                        <div class="xr-card feature-item p-8">
                            <p class="text-[10px] uppercase tracking-[0.4em] text-cyan-500 font-black mb-3">Coverage Limit</p>
                            <p class="syne text-4xl font-bold text-hi">₹{{ number_format($plan->coverage_amount) }}</p>
                            <div class="mt-4 h-1 w-full bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-cyan-400 w-3/4"></div>
                            </div>
                        </div>
                        <div class="xr-card feature-item p-8">
                            <p class="text-[10px] uppercase tracking-[0.4em] text-violet-500 font-black mb-3">Timeframe</p>
                            <p class="syne text-4xl font-bold text-hi">{{ $plan->duration_years }} <span class="text-lg opacity-50">Years</span></p>
                            <div class="mt-4 h-1 w-full bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-violet-400 w-1/2"></div>
                            </div>
                        </div>
                    </div>

                    {{-- System Features --}}
                    <div class="space-y-6">
                        <h3 class="syne text-sm font-black text-hi uppercase tracking-[0.3em] flex items-center gap-3">
                            <span class="w-8 h-[1px] bg-cyan-500"></span> System Manifest
                        </h3>
                        <div class="grid gap-4">
                            @foreach($plan->features ?? [] as $feature)
                                <div class="feature-item rounded-2xl p-6 flex items-start gap-5 transition-all hover:translate-x-2">
                                    <div class="mt-1 w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div>
                                        <span class="text-hi font-bold text-lg block mb-1">Standard Protocol</span>
                                        <span class="text-sub">{{ $feature }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right: Checkout Module --}}
                <aside class="space-y-8">
                    {{-- Pricing Terminal --}}
                    {{-- Pricing Terminal --}}
<div class="xr-card p-1 bg-gradient-to-br from-cyan-500/20 via-transparent to-violet-500/20">
    <div class="bg-slate-950 rounded-[22px] p-10 text-white relative overflow-hidden">
        {{-- Geometric Decoration --}}
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <svg class="w-20 h-20" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="10 5"/></svg>
        </div>

        <p class="text-[10px] uppercase tracking-[0.4em] text-cyan-400 font-black mb-6">Neural Premium Output</p>
        <div class="flex items-baseline gap-3 mb-8">
            <span class="syne text-7xl font-extrabold tracking-tighter">₹{{ number_format($plan->premium_amount) }}</span>
            <span class="text-slate-500 font-bold uppercase tracking-widest text-xs">/ Cycle</span>
        </div>
        
        @auth
            @php
                // Check if user has an application for this specific plan
                $existingApplication = auth()->user()->policyApplications()
                    ->where('plan_id', $plan->_id ?? $plan->id)
                    ->latest()
                    ->first();
            @endphp

            @if(!$existingApplication)
                {{-- Case 1: New User / No Application --}}
                <a href="{{ route('policies.apply', $plan->_id ?? $plan->id) }}" class="btn-xr">
                    Apply for Coverage ⚡
                </a>
            @elseif($existingApplication->status === 'pending')
                {{-- Case 2: Waiting for Admin Approval --}}
                <div class="text-center p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                    <p class="text-amber-500 font-black text-[10px] uppercase tracking-widest mb-2 animate-pulse">
                        Verification in Progress
                    </p>
                    <p class="text-slate-400 text-xs italic">
                        Please wait for system approval before initiating payment.
                    </p>
                </div>
            @elseif($existingApplication->status === 'approved')
                {{-- Case 3: Approved - Proceed to Payment --}}
                <a href="{{ route('policies.payment', $existingApplication->id) }}" class="btn-xr bg-emerald-500 hover:shadow-emerald-500/40">
                    Initialize Payment 💳
                </a>
                <p class="text-center text-[10px] text-emerald-500 font-bold mt-4 uppercase tracking-widest">
                    Status: Authorization Granted
                </p>
            @elseif($existingApplication->status === 'active')
                {{-- Case 4: Already Active --}}
                <div class="btn-xr bg-slate-800 cursor-default opacity-80 text-slate-400">
                    Protocol Active ✅
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-xr">Initialize Link</a>
        @endauth
    </div>
</div>

                    {{-- Helper Card --}}
                    <div class="xr-card p-8 border-l-4 border-l-cyan-500">
                        <h4 class="syne text-hi font-bold text-xl mb-4">Neural Support</h4>
                        <p class="text-sub text-sm leading-relaxed mb-8">Our AI-assisted agents are standing by to clarify coverage synchronization or onboarding errors.</p>
                        <a href="{{ route('contact') }}" class="group flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em] text-hi">
                            Request Uplink 
                            <span class="group-hover:translate-x-2 transition-transform text-cyan-500">——→</span>
                        </a>
                    </div>

                    {{-- Status Card --}}
                    <div class="p-6 rounded-3xl border border-dashed border-slate-300 dark:border-white/10">
                        <div class="flex items-center gap-3 text-[10px] font-bold text-sub uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                            All Systems Operational
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>