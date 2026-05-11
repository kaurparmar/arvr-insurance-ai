<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply for {{ $plan->name }} - {{ config('app.name', 'LifeShield XR') }}</title>

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

        /* File Upload */
        .file-upload{border:2px dashed;border-radius:16px;padding:20px;text-align:center;transition:all .3s;cursor:pointer;}
        html:not(.dark) .file-upload{border-color:#d1d5db;background:#f9fafb;}
        .dark .file-upload{border-color:var(--border-w);background:rgba(255,255,255,0.02);}
        .file-upload:hover{border-color:var(--cyan);}
        .file-upload.dragover{border-color:var(--cyan);background:rgba(0,240,255,0.05);}

        /* Button */
        .btn-xr{background:var(--cyan);color:#020F14;padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;border:none;cursor:pointer;}
        .btn-xr:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,240,255,0.3);}

        .text-hi{color:#0F172A;}.dark .text-hi{color:#EEF2FF;}
        .text-sub{color:#64748B;}.dark .text-sub{color:var(--text-mid);}

        /* File item styling */
        .file-item-name{font-weight:600;color:#0f172a;}.dark .file-item-name{color:#eef2ff;}
        .file-item-size{color:#64748b;}.dark .file-item-size{color:#8892aa;}

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
            <span class="xr-chip mb-4"><span class="chip-dot"></span> {{ __('messages.Policy Application') }}</span>
            <h1 class="syne text-hi text-5xl md:text-6xl font-extrabold tracking-tighter mb-4">{{ $plan->name }}</h1>
            <p class="text-sub text-lg">{{ __('messages.Complete your application with all required documents for instant processing.') }}</p>
        </div>

        {{-- Progress Steps --}}
        <div class="flex justify-center mb-12">
            <div class="step active">
                <div class="step-circle">1</div>
                <span class="text-hi font-semibold">{{ __('messages.Personal Details') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step pending">
                <div class="step-circle">2</div>
                <span class="text-sub">{{ __('messages.Document Upload') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step pending">
                <div class="step-circle">3</div>
                <span class="text-sub">{{ __('messages.Review & Submit') }}</span>
            </div>
        </div>

        {{-- Plan Summary --}}
        <div class="xr-card p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="syne text-hi text-xl font-bold">{{ $plan->name }}</h3>
                    <p class="text-sub text-sm">{{ $plan->description }}</p>
                </div>
                <div class="text-right">
                    <div class="syne text-hi text-2xl font-bold">₹{{ number_format($plan->premium_amount) }}/month</div>
                    <div class="text-sub text-sm">₹{{ number_format($plan->coverage_amount) }} coverage</div>
                </div>
            </div>
        </div>

        {{-- Main Form --}}
        <div class="xr-card p-8">
            <form action="{{ route('policies.apply.store', $plan->_id ?? $plan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Personal Information --}}
                <div>
                    <h3 class="syne text-hi text-xl font-bold mb-6">{{ __('messages.Personal Information') }}</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Full Name</label>
                            <input name="full_name" type="text" class="xr-input" value="{{ auth()->user()->name }}" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Date of Birth</label>
                            <input name="date_of_birth" type="date" class="xr-input" max="{{ date('Y-m-d', strtotime('-18 years')) }}" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Gender</label>
                            <select name="gender" class="xr-input" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Phone Number</label>
                            <input name="phone" type="tel" class="xr-input" placeholder="+91 9876543210" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Email Address</label>
                            <input name="email" type="email" class="xr-input" value="{{ auth()->user()->email }}" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Residential Address</label>
                            <textarea name="address" rows="3" class="xr-input" placeholder="Complete residential address" required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Occupation</label>
                            <input name="occupation" type="text" class="xr-input" placeholder="Your profession" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Annual Income</label>
                            <input name="annual_income" type="number" class="xr-input" placeholder="₹ 500000" min="100000" required />
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact --}}
                <div>
                    <h3 class="syne text-hi text-xl font-bold mb-6">{{ __('messages.Emergency Contact') }}</h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Contact Name</label>
                            <input name="emergency_contact_name" type="text" class="xr-input" placeholder="Full name" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Phone Number</label>
                            <input name="emergency_contact_phone" type="tel" class="xr-input" placeholder="+91 9876543210" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Relationship</label>
                            <input name="emergency_contact_relationship" type="text" class="xr-input" placeholder="e.g., Spouse, Parent" required />
                        </div>
                    </div>
                </div>

                {{-- Document Uploads --}}
                <div>
                    <h3 class="syne text-hi text-xl font-bold mb-6">{{ __('messages.Required Documents') }}</h3>
                    <div class="grid md:grid-cols-2 gap-6">

                        {{-- Photo ID --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Photo ID (Aadhaar/Passport)</label>
                            <div class="file-upload" onclick="document.getElementById('photo_id').click()">
                                <div class="text-3xl mb-2">🆔</div>
                                <p class="text-sub mb-2">Upload government-issued photo ID</p>
                                <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB</p>
                            </div>
                            <input id="photo_id" name="photo_id" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required />
                            <div id="photo-id-file" class="mt-3"></div>
                        </div>

                        {{-- Address Proof --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Address Proof</label>
                            <div class="file-upload" onclick="document.getElementById('address_proof').click()">
                                <div class="text-3xl mb-2">🏠</div>
                                <p class="text-sub mb-2">Upload utility bill or rental agreement</p>
                                <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB</p>
                            </div>
                            <input id="address_proof" name="address_proof" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required />
                            <div id="address-proof-file" class="mt-3"></div>
                        </div>

                        {{-- Income Proof --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Income Proof</label>
                            <div class="file-upload" onclick="document.getElementById('income_proof').click()">
                                <div class="text-3xl mb-2">💰</div>
                                <p class="text-sub mb-2">Upload salary slip or bank statement</p>
                                <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB</p>
                            </div>
                            <input id="income_proof" name="income_proof" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required />
                            <div id="income-proof-file" class="mt-3"></div>
                        </div>

                        {{-- Medical Certificate --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Medical Certificate (Optional)</label>
                            <div class="file-upload" onclick="document.getElementById('medical_certificate').click()">
                                <div class="text-3xl mb-2">🏥</div>
                                <p class="text-sub mb-2">Upload medical fitness certificate</p>
                                <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB</p>
                            </div>
                            <input id="medical_certificate" name="medical_certificate" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                            <div id="medical-certificate-file" class="mt-3"></div>
                        </div>
                    </div>

                    {{-- Other Documents --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Additional Documents (Optional)</label>
                        <div class="file-upload" onclick="document.getElementById('other_documents').click()">
                            <div class="text-3xl mb-2">📄</div>
                            <p class="text-sub mb-2">Any other relevant documents</p>
                            <p class="text-xs text-sub">PDF, DOC, DOCX, JPG, PNG up to 5MB each</p>
                        </div>
                        <input id="other_documents" name="other_documents[]" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" />
                        <div id="other-documents-files" class="mt-3 space-y-2"></div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="btn-xr w-full syne text-sm uppercase tracking-wider">
                        Submit Application for Review
                    </button>
                    <p class="text-xs text-sub text-center mt-4">
                        By submitting this application, you confirm that all information provided is accurate and complete.
                        Processing typically takes 24-48 hours.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File upload handling
        function handleFileSelect(input, containerId, maxFiles = 1) {
            const container = document.getElementById(containerId);
            const files = Array.from(input.files);

            if (files.length > maxFiles) {
                alert(`Maximum ${maxFiles} file${maxFiles > 1 ? 's' : ''} allowed`);
                input.value = '';
                return;
            }

            container.innerHTML = '';
            files.forEach((file, index) => {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'flex items-center justify-between bg-slate-100 dark:bg-slate-800 rounded-lg p-3';
                fileDiv.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="text-sm">📎</span>
                        <span class="text-sm file-item-name">${file.name}</span>
                        <span class="text-xs file-item-size">(${formatFileSize(file.size)})</span>
                    </div>
                    <button type="button" onclick="removeFile('${containerId}', ${index})" class="text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(fileDiv);
            });
        }

        function removeFile(containerId, index) {
            const inputId = containerId.replace('-file', '').replace('-files', 's');
            const input = document.getElementById(inputId);
            const dt = new DataTransfer();
            const files = Array.from(input.files);

            files.splice(index, 1);
            files.forEach(file => dt.items.add(file));
            input.files = dt.files;

            handleFileSelect(input, containerId);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Event listeners
        document.getElementById('photo_id').addEventListener('change', function() {
            handleFileSelect(this, 'photo-id-file', 1);
        });

        document.getElementById('address_proof').addEventListener('change', function() {
            handleFileSelect(this, 'address-proof-file', 1);
        });

        document.getElementById('income_proof').addEventListener('change', function() {
            handleFileSelect(this, 'income-proof-file', 1);
        });

        document.getElementById('medical_certificate').addEventListener('change', function() {
            handleFileSelect(this, 'medical-certificate-file', 1);
        });

        document.getElementById('other_documents').addEventListener('change', function() {
            handleFileSelect(this, 'other-documents-files', 5);
        });

        // Drag and drop
        document.querySelectorAll('.file-upload').forEach(upload => {
            upload.addEventListener('dragover', (e) => {
                e.preventDefault();
                upload.classList.add('dragover');
            });

            upload.addEventListener('dragleave', () => {
                upload.classList.remove('dragover');
            });

            upload.addEventListener('drop', (e) => {
                e.preventDefault();
                upload.classList.remove('dragover');

                const input = upload.parentElement.querySelector('input[type="file"]');
                const files = e.dataTransfer.files;

                if (input.multiple) {
                    // For multiple files
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach(file => dt.items.add(file));
                    Array.from(files).forEach(file => dt.items.add(file));
                    input.files = dt.files;
                } else {
                    // For single file
                    input.files = files;
                }

                input.dispatchEvent(new Event('change'));
            });
        });
    </script>
</body>
</html>