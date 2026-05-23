@extends('layouts.admin')

@section('content')
<div class="max-w-[1100px] mx-auto px-6">
    
    {{-- Decorative Layout Ambient Glow elements --}}
    <div class="absolute top-0 left-10 pointer-events-none filter blur-[120px] rounded-full opacity-40 dark:opacity-60 bg-cyan-400 dark:bg-[#00F0FF]" style="width:300px; height:300px; z-index:-1;"></div>

    <div class="mb-10">
        <span class="xr-chip chip-cyan mb-3">
            <span class="chip-dot"></span> {{ __('messages.xr_admin_tag') }}
        </span>
        <h1 class="syne text-slate-900 dark:text-[#EEF2FF]" style="font-size: clamp(32px, 4vw, 48px); font-weight: 800; letter-spacing: -1.5px;">
            {{ __('messages.admin_title') }}
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl border border-[#00E676]/30 bg-[#00E676]/10 text-[#00E676] text-sm font-medium">
            🧬 System Notification: {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl border border-[#FF3B6B]/30 bg-[#FF3B6B]/10 text-[#FF3B6B] text-sm font-medium">
            ⚠️ Exception Warning: {{ session('error') }}
        </div>
    @endif

    @if($flaggedClaims->isEmpty())
        <div class="xr-card p-16 text-center text-slate-400 dark:text-[#8892AA]">
            <span class="text-4xl block mb-4">🌌</span>
            <p class="font-medium text-base">{{ __('messages.no_claims') }}</p>
        </div>
    @else
        <div class="space-y-8">
            @foreach($flaggedClaims as $claim)
                <div class="xr-card p-8">
                    
                    {{-- Header Row --}}
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
                        <div>
                            <span class="text-[10px] tracking-widest uppercase font-bold border border-[#FFB700]/30 bg-[#FFB700]/10 text-[#FFB700] px-3 py-1 rounded-md">
                                Core Engine Phase: {{ $claim->status }}
                            </span>
                            <h2 class="syne text-xl font-bold text-slate-900 dark:text-[#EEF2FF] mt-3">Node Tracking Key: {{ $claim->_id }}</h2>
                            <p class="text-xs text-slate-400 dark:text-[#8892AA] mt-1">Requester Node Pointer: <span class="font-mono text-slate-600 dark:text-cyan-400">{{ $claim->user_id }}</span> | Policy Object ID: <span class="font-mono">{{ $claim->policy_id }}</span></p>
                        </div>
                        <div class="md:text-right">
                            <span class="syne text-3xl font-extrabold text-slate-900 dark:text-[#EEF2FF] tracking-tight">${{ number_format($claim->claim_amount, 2) }}</span>
                            <p class="text-xs text-slate-400 dark:text-[#8892AA] mt-1 uppercase tracking-wider">Incident Epoch: {{ $claim->incident_date }}</p>
                        </div>
                    </div>

                    {{-- Split Analysis Interface Columns --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
                        
                        {{-- Left Column: User Input --}}
                        <div class="narration-box">
                            <h3 class="syne text-xs font-bold text-slate-400 dark:text-[#8892AA] uppercase tracking-widest mb-3" style="font-style: normal;">
                                📁 {{ __('messages.user_statement') }}
                            </h3>
                            <p class="leading-relaxed font-normal">"{{ $claim->claim_reason }}"</p>
                            <div class="text-xs text-slate-400 dark:text-[#8892AA] mt-4 pt-3 border-t border-slate-200/40 dark:border-white/5" style="font-style: normal;">
                                📍 Spatial Coordinates: <strong class="text-slate-800 dark:text-[#EEF2FF] font-medium">{{ $claim->incident_location }}</strong>
                            </div>
                        </div>

                        {{-- Right Column: Orchestration Telemetry --}}
                        <div class="p-6 rounded-xl border border-slate-200/60 dark:border-white/5 bg-slate-100/50 dark:bg-white/[0.01]">
                            <h3 class="syne text-xs font-bold text-[#FF3B6B] uppercase tracking-widest mb-3">
                                {{ __('messages.ai_assessment') }}
                            </h3>
                            <div class="mb-3 text-sm">
                                <span class="text-slate-400 dark:text-[#8892AA]">{{ __('messages.risk_level') }}</span> 
                                <span class="font-mono font-bold text-[#FF3B6B] px-2 py-0.5 bg-[#FF3B6B]/10 rounded border border-[#FF3B6B]/20 ml-1">
                                    {{ strtoupper($claim->ai_recommendation ?? 'FLAG') }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-[#8892AA] leading-relaxed">
                                <strong class="text-slate-800 dark:text-[#EEF2FF] font-medium block mb-1">{{ __('messages.reasoning_matrix') }}</strong> 
                                {{ $claim->ai_reasoning ?? 'Telemetry evaluation streams active. Neural network logs missing explicit structural error payloads.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Admin Decision Action Tray --}}
                    <div class="mt-6 pt-6 border-t border-slate-200/60 dark:border-white/5">
                        <form action="{{ route('admin.claims.resolve', $claim->_id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end justify-end">
                            @csrf
                            <div class="w-full md:flex-1">
                                <label class="block syne text-xs font-bold text-slate-400 dark:text-[#8892AA] uppercase tracking-wider mb-2">
                                    {{ __('messages.adjuster_note') }}
                                </label>
                                <input type="text" name="admin_note" required placeholder="Type operational consensus reasons to overwrite or bypass AI vector..." 
                                       class="w-full px-4 py-3 bg-white dark:bg-[#060C1A] text-slate-900 dark:text-[#EEF2FF] border border-slate-200 dark:border-white/10 rounded-xl text-sm focus:outline-none focus:border-[#00F0FF] focus:ring-1 focus:ring-[#00F0FF] transition-colors">
                            </div>
                            <div class="flex gap-3 w-full md:w-auto">
                                <button type="submit" name="status" value="Approved" 
                                        class="flex-1 md:flex-none px-6 py-3 bg-[#00E676] text-[#020F14] font-bold rounded-full text-sm font-sans transition-all transform hover:-translate-y-0.5 shadow-lg shadow-[#00E676]/20 hover:shadow-[#00E676]/40 cursor-pointer">
                                    {{ __('messages.approve') }}
                                </button>
                                <button type="submit" name="status" value="Rejected" 
                                        class="flex-1 md:flex-none px-6 py-3 bg-transparent text-[#FF3B6B] border border-[#FF3B6B]/40 hover:border-[#FF3B6B] font-medium rounded-full text-sm font-sans transition-all transform hover:-translate-y-0.5 cursor-pointer">
                                    {{ __('messages.reject') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
