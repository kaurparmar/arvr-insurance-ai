<x-app-layout>
    <style>
        :root {
            --cyan: #00F0FF;
            --emerald: #00E676;
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
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 240, 255, 0.03) 0%, transparent 100%);
        }

        .syne { font-family: 'Syne', sans-serif; }

        .xr-card {
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .dark .xr-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-w);
            backdrop-filter: blur(12px);
        }

        /* Success Animation Ring */
        .success-ring {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 230, 118, 0.1);
            border: 2px solid var(--emerald);
            box-shadow: 0 0 20px rgba(0, 230, 118, 0.2);
            margin: 0 auto 24px;
        }

        .receipt-line {
            border-top: 1px dashed rgba(136, 146, 170, 0.2);
            margin: 1.5rem 0;
        }

        .text-hi { color: #0F172A; }
        .dark .text-hi { color: #EEF2FF; }
        .text-sub { color: #64748B; }
        .dark .text-sub { color: var(--text-mid); }
        
        .btn-xr {
            background: var(--cyan);
            color: #020F14;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 800;
            text-align: center;
            transition: all 0.3s;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
        }
    </style>

    <div class="py-12 min-h-screen relative overflow-hidden">
        {{-- Scanline Overlay --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.03] dark:opacity-10 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,118,0.06))] bg-[length:100%_4px,3px_100%]"></div>

        <div class="max-w-2xl mx-auto px-6 relative z-10">
            
            <div class="xr-card p-10 text-center">
                {{-- Status Icon --}}
                <div class="success-ring animate-bounce">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="syne text-hi text-3xl font-extrabold mb-2 uppercase tracking-tighter">Transaction Verified</h1>
                <p class="text-sub mb-8">Neural link established. Policy coverage is now active.</p>

                {{-- Receipt Module --}}
                <div class="bg-slate-50 dark:bg-white/5 rounded-2xl p-6 text-left border border-slate-100 dark:border-white/5">
                    <div class="flex justify-between mb-4">
                        <span class="text-[10px] uppercase tracking-widest text-sub font-bold">Transaction ID</span>
                        <span class="text-xs font-mono text-hi uppercase">{{ $transaction->transaction_id ?? 'TXN-PENDING' }}</span>
                    </div>

                    <div class="flex justify-between mb-4">
                        <span class="text-[10px] uppercase tracking-widest text-sub font-bold">Payment Date</span>
                        {{-- Safe Null Check & Fallback Formatting --}}
                        <span class="text-xs font-bold text-hi">
                            @if(isset($transaction->payment_date) && $transaction->payment_date)
                                {{ $transaction->payment_date->format('M j, Y — H:i') }}
                            @else
                                {{ now()->format('M j, Y — H:i') }} <span class="opacity-50">(Synchronizing...)</span>
                            @endif
                        </span>
                    </div>

                    <div class="receipt-line"></div>

                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-[10px] uppercase tracking-widest text-cyan-500 font-black">Plan Deployed</span>
                            <p class="syne text-lg font-bold text-hi">{{ $transaction->plan_name ?? 'Standard Protocol' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase tracking-widest text-sub font-bold">Amount Paid</span>
                            <p class="syne text-xl font-bold text-emerald-500">₹{{ number_format($transaction->amount ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-10 grid grid-cols-2 gap-4">
                    <a href="{{ route('dashboard') }}" class="btn-xr">
                        Go to Dashboard
                    </a>
                    <a href="#" onclick="window.print()" class="bg-slate-200 dark:bg-white/10 text-hi py-3.5 px-6 rounded-xl font-bold text-xs uppercase tracking-widest hover:opacity-80 transition-all">
                        Download PDF
                    </a>
                </div>

                <p class="mt-8 text-[10px] text-sub uppercase tracking-[0.3em] font-bold">
                    LifeShield XR // Secure Deployment Module 04-A
                </p>
            </div>

            {{-- Support Link --}}
            <div class="mt-6 text-center">
                <p class="text-sub text-sm">
                    Encountering synchronization errors? 
                    <a href="{{ route('contact') }}" class="text-cyan-500 font-bold hover:underline">Open a Neural Ticket</a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>