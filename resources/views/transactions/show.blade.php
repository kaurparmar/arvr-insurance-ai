<x-app-layout>
    <style>
        :root {
            --cyan: #00F0FF;
            --violet: #8B5CF6;
            --bg-void: #03060F;
            --bg-panel: rgba(8, 14, 30, 0.92);
            --text-mid: #8892AA;
            --border-w: rgba(255, 255, 255, 0.07);
        }

        .min-h-screen {
            background-color: #F0F4FF;
        }
        .dark .min-h-screen {
            background-color: var(--bg-void);
        }

        .syne { font-family: 'Syne', sans-serif; }

        .xr-card {
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .xr-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-w);
            backdrop-filter: blur(12px);
        }

        .data-label {
            font-[900] text-[10px] uppercase tracking-[0.2em] text-sub mb-1;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-success { background: rgba(0, 230, 118, 0.1); color: #00E676; border: 1px solid rgba(0, 230, 118, 0.2); }
    </style>

    <div class="py-12 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            
            {{-- Breadcrumb / Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-sub hover:text-cyan-500 transition-colors text-xs font-bold uppercase tracking-widest flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        Back to Terminal
                    </a>
                    <h1 class="syne text-hi text-4xl font-extrabold tracking-tighter">Transaction Detail</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="status-badge status-success">Verified by XR-Node</span>
                    <button onclick="window.print()" class="p-3 rounded-xl bg-slate-200 dark:bg-white/5 text-hi hover:bg-cyan-500/20 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </button>
                </div>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                {{-- Primary Receipt --}}
                <div class="md:col-span-2 space-y-8">
                    <div class="xr-card p-8 md:p-12">
                        <div class="flex justify-between items-start mb-12">
                            <div class="w-16 h-16 bg-cyan-500 rounded-2xl flex items-center justify-center text-slate-950">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div class="text-right">
                                <p class="syne text-3xl font-black text-hi tracking-tighter">₹{{ number_format($transaction->amount ?? 0) }}</p>
                                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Successful Deployment</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-y-8 gap-x-4">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sub">Policy Holder</p>
                                <p class="text-hi font-bold">{{ auth()->user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sub">Plan Specification</p>
                                <p class="text-hi font-bold">{{ $transaction->plan_name ?? 'Neural Standard' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sub">Transaction Hash</p>
                                <p class="text-hi font-mono text-xs">{{ $transaction->transaction_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sub">Payment Timestamp</p>
                                <p class="text-hi font-bold">
                                    {{ optional($transaction->payment_date)->format('M d, Y • H:i') ?? 'Pending Sync' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t border-dashed border-slate-200 dark:border-white/10">
                            <p class="text-xs text-sub leading-relaxed italic">
                                This transaction serves as a binding digital agreement for insurance coverage. 
                                The premium has been processed through the secure XR payment gateway.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="md:col-span-1 space-y-6">
                    <div class="xr-card p-6 bg-gradient-to-br from-violet-500/10 to-transparent">
                        <h4 class="syne text-hi font-bold mb-4">Coverage Meta</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between text-xs">
                                <span class="text-sub font-bold uppercase tracking-wider">Status</span>
                                <span class="text-emerald-500 font-black tracking-widest uppercase">Active</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-sub font-bold uppercase tracking-wider">Gateway</span>
                                <span class="text-hi font-bold">Stripe/XR-Link</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-sub font-bold uppercase tracking-wider">Currency</span>
                                <span class="text-hi font-bold">INR</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-3xl border border-dashed border-slate-300 dark:border-white/10 text-center">
                        <p class="text-[10px] font-bold text-sub uppercase tracking-widest mb-4">Need Assistance?</p>
                        <a href="{{ route('contact') }}" class="text-xs font-black text-cyan-500 uppercase tracking-widest hover:text-hi transition-colors">
                            Contact Support Node
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>