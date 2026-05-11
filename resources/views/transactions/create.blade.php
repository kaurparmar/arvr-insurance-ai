<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Payment - {{ config('app.name', 'LifeShield XR') }}</title>

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
        html:not(.dark) .xr-input::placeholder{color:#94a3b8;opacity:0.7;}
        .dark .xr-input{background:rgba(255,255,255,0.03);border:1px solid var(--border-w);color:#fff;}
        .dark .xr-input::placeholder{color:#cbd5e1;opacity:0.6;}
        .xr-input:focus{outline:none;border-color:var(--cyan);box-shadow:0 0 0 4px rgba(0,240,255,0.1);}

        /* Button */
        .btn-xr{background:var(--cyan);color:#020F14;padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;border:none;cursor:pointer;}
        .btn-xr:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,240,255,0.3);}

        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}

        /* Payment Methods */
        .payment-method{border-radius:16px;padding:16px;text-align:center;transition:all .2s;cursor:pointer;border:2px solid transparent;}
        html:not(.dark) .payment-method{background:#f8fafc;border-color:#e2e8f0;}
        .dark .payment-method{background:rgba(255,255,255,0.02);border-color:var(--border-w);}
        .payment-method:hover{border-color:var(--cyan);}
        .payment-method.selected{border-color:var(--cyan);background:rgba(0,240,255,0.05);}

        /* Progress Steps */
        .step{display:flex;align-items:center;gap:12px;margin-bottom:24px;}
        .step-circle{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;border:2px solid;}
        .step.active .step-circle{background:var(--cyan);border-color:var(--cyan);color:#020F14;}
        .step.completed .step-circle{background:var(--emerald);border-color:var(--emerald);color:#fff;}
        .step.pending .step-circle{border-color:var(--text-mid);color:var(--text-mid);}
        .step-line{height:2px;width:60px;background:var(--text-mid);margin:0 12px;}
        .step.completed + .step-line{background:var(--emerald);}
    </style>
</head>
<body>
    <div class="vr-scanlines"></div>
    <div class="glow" style="width:400px;height:400px;top:-100px;right:-100px;background:rgba(139,92,246,.06)"></div>

    <x-navbar :is-authenticated="auth()->check()" />

    <div class="max-w-4xl mx-auto px-6 py-20 relative" style="z-index:10">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="xr-chip mb-4"><span class="chip-dot"></span> Payment Processing</span>
            <h1 class="syne text-hi text-5xl md:text-6xl font-extrabold tracking-tighter mb-4">Complete Your Payment</h1>
            <p class="text-sub text-lg">Secure payment processing for your LifeShield XR policy.</p>
        </div>

        {{-- Progress Steps --}}
        <div class="flex justify-center mb-12">
            <div class="step completed">
                <div class="step-circle">✓</div>
                <span class="text-hi font-semibold">Application</span>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-circle">✓</div>
                <span class="text-sub">Approval</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-circle">3</div>
                <span class="text-sub">Payment</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">

            {{-- Payment Form --}}
            <div class="xr-card p-8">
                <form action="{{ route('transactions.store', $policy->_id ?? $policy->id) }}" method="POST" id="paymentForm">
                    @csrf

                    {{-- Payment Method Selection --}}
                    <div class="mb-8">
                        <h3 class="syne text-hi text-xl font-bold mb-6">Select Payment Method</h3>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="payment-method selected" data-method="credit_card">
                                <div class="text-3xl mb-2">💳</div>
                                <p class="text-xs text-sub">Credit Card</p>
                                <input type="radio" name="payment_method" value="credit_card" checked class="hidden">
                            </div>
                            <div class="payment-method" data-method="debit_card">
                                <div class="text-3xl mb-2">💳</div>
                                <p class="text-xs text-sub">Debit Card</p>
                                <input type="radio" name="payment_method" value="debit_card" class="hidden">
                            </div>
                            <div class="payment-method" data-method="upi">
                                <div class="text-3xl mb-2">📱</div>
                                <p class="text-xs text-sub">UPI</p>
                                <input type="radio" name="payment_method" value="upi" class="hidden">
                            </div>
                            <div class="payment-method" data-method="net_banking">
                                <div class="text-3xl mb-2">🏦</div>
                                <p class="text-xs text-sub">Net Banking</p>
                                <input type="radio" name="payment_method" value="net_banking" class="hidden">
                            </div>
                        </div>
                    </div>

                    {{-- Credit/Debit Card Fields --}}
                    <div id="cardFields" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Card Number</label>
                                <input name="card_number" type="text" class="xr-input" placeholder="1234 5678 9012 3456" maxlength="16" required />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Expiry Month</label>
                                <select name="expiry_month" class="xr-input" required>
                                    <option value="">MM</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Expiry Year</label>
                                <select name="expiry_year" class="xr-input" required>
                                    <option value="">YYYY</option>
                                    @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">CVV</label>
                                <input name="cvv" type="text" class="xr-input" placeholder="123" maxlength="3" required />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Card Holder Name</label>
                                <input name="card_holder_name" type="text" class="xr-input" placeholder="John Doe" required />
                            </div>
                        </div>
                    </div>

                    {{-- UPI Fields --}}
                    <div id="upiFields" class="space-y-6 hidden">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">UPI ID</label>
                            <input name="upi_id" type="text" class="xr-input" placeholder="yourname@upi" />
                        </div>
                    </div>

                    {{-- Net Banking Fields --}}
                    <div id="bankingFields" class="space-y-6 hidden">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Bank Name</label>
                            <select name="bank_name" class="xr-input">
                                <option value="">Select Bank</option>
                                <option value="SBI">State Bank of India</option>
                                <option value="HDFC">HDFC Bank</option>
                                <option value="ICICI">ICICI Bank</option>
                                <option value="Axis">Axis Bank</option>
                                <option value="PNB">Punjab National Bank</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="btn-xr w-full syne text-sm uppercase tracking-wider" id="payButton">
                            Process Payment ₹{{ number_format($policy->premium_paid) }}
                        </button>
                        <p class="text-xs text-sub text-center mt-4">
                            Your payment is secured with 256-bit SSL encryption.
                        </p>
                    </div>
                </form>
            </div>

            {{-- Order Summary --}}
            <div class="space-y-6">
                <div class="xr-card p-6">
                    <h3 class="syne text-hi text-xl font-bold mb-6">Order Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sub">Policy:</span>
                            <span class="text-hi font-semibold">{{ $policy->plan->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sub">Coverage:</span>
                            <span class="text-hi font-semibold">₹{{ number_format($policy->coverage_amount) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sub">Duration:</span>
                            <span class="text-hi font-semibold">1 Year</span>
                        </div>
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                            <div class="flex justify-between text-lg">
                                <span class="text-hi font-bold">Total:</span>
                                <span class="text-hi font-bold">₹{{ number_format($policy->premium_paid) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security Info --}}
                <div class="xr-card p-6">
                    <h4 class="syne text-hi text-lg font-bold mb-4">🔒 Secure Payment</h4>
                    <ul class="text-sm text-sub space-y-2">
                        <li>• 256-bit SSL encryption</li>
                        <li>• PCI DSS compliant</li>
                        <li>• No card details stored</li>
                        <li>• Secure payment gateway</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Remove selected class from all
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                // Add selected class to clicked
                this.classList.add('selected');

                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;

                // Show/hide relevant fields
                showPaymentFields(radio.value);
            });
        });

        function showPaymentFields(method) {
            // Hide all field groups
            document.getElementById('cardFields').classList.add('hidden');
            document.getElementById('upiFields').classList.add('hidden');
            document.getElementById('bankingFields').classList.add('hidden');

            // Remove required attributes
            document.querySelectorAll('#cardFields input, #cardFields select').forEach(el => el.required = false);
            document.querySelectorAll('#upiFields input, #upiFields select').forEach(el => el.required = false);
            document.querySelectorAll('#bankingFields input, #bankingFields select').forEach(el => el.required = false);

            // Show relevant fields and make required
            if (method === 'credit_card' || method === 'debit_card') {
                document.getElementById('cardFields').classList.remove('hidden');
                document.querySelectorAll('#cardFields input, #cardFields select').forEach(el => el.required = true);
            } else if (method === 'upi') {
                document.getElementById('upiFields').classList.remove('hidden');
                document.querySelectorAll('#upiFields input').forEach(el => el.required = true);
            } else if (method === 'net_banking') {
                document.getElementById('bankingFields').classList.remove('hidden');
                document.querySelectorAll('#bankingFields select').forEach(el => el.required = true);
            }
        }

        // Form submission with loading state
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const button = document.getElementById('payButton');
            button.textContent = 'Processing...';
            button.disabled = true;
            button.style.opacity = '0.7';
        });

        // Initialize with credit card selected
        showPaymentFields('credit_card');
    </script>
</body>
</html>