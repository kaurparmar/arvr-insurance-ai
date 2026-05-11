<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>File New Claim - {{ config('app.name', 'LifeShield XR') }}</title>

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
        .file-upload{border:2px dashed;border-radius:16px;padding:24px;text-align:center;transition:all .3s;cursor:pointer;}
        html:not(.dark) .file-upload{border-color:#d1d5db;background:#f9fafb;}
        .dark .file-upload{border-color:var(--border-w);background:rgba(255,255,255,0.02);}
        .file-upload:hover{border-color:var(--cyan);}
        .file-upload.dragover{border-color:var(--cyan);background:rgba(0,240,255,0.05);}

        /* Button */
        .btn-xr{background:var(--cyan);color:#020F14;padding:14px 24px;border-radius:16px;font-weight:700;text-align:center;transition:all .2s;border:none;cursor:pointer;}
        .btn-xr:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,240,255,0.3);}

        .badge{padding:4px 12px;border-radius:100px;font-size:10px;font-weight:700;text-transform:uppercase;}
        .badge-pending{background:rgba(255,183,0,0.1);color:var(--amber);}
        .badge-approved{background:rgba(0,230,118,0.1);color:var(--emerald);}

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
            <span class="xr-chip mb-4"><span class="chip-dot"></span> {{ __('messages.Claims Processing') }}</span>
            <h1 class="syne text-hi text-5xl md:text-6xl font-extrabold tracking-tighter mb-4">{{ __('messages.File Insurance Claim') }}</h1>
            <p class="text-sub text-lg">{{ __('messages.Submit your claim with all necessary documentation for quick processing.') }}</p>
        </div>

        {{-- Progress Steps --}}
        <div class="flex justify-center mb-12">
            <div class="step active">
                <div class="step-circle">1</div>
                <span class="text-hi font-semibold">{{ __('messages.Claim Details') }}</span>
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

        {{-- Main Form --}}
        <div class="xr-card p-8">
            <form action="{{ route('claims.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Policy Selection --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Select Policy</label>
                    <select name="policy_id" class="xr-input" required>
                        <option value="">Choose a policy</option>
                        @foreach($policies as $policy)
                            <option value="{{ $policy->_id ?? $policy->id }}">
                                {{ $policy->policy_number }} — {{ $policy->plan->name ?? 'Plan' }} (₹{{ number_format($policy->plan->coverage_amount ?? 0) }} coverage)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Incident Details --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Incident Date</label>
                        <input name="incident_date" type="date" class="xr-input" max="{{ date('Y-m-d') }}" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Incident Location</label>
                        <input name="incident_location" type="text" class="xr-input" placeholder="City, State/Country" required />
                    </div>
                </div>

                {{-- Claim Amount --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Claim Amount (₹)</label>
                    <input name="claim_amount" type="number" step="0.01" min="100" class="xr-input" placeholder="Enter claimed amount" required />
                </div>

                {{-- Claim Description --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Detailed Description</label>
                    <textarea name="claim_reason" rows="6" class="xr-input" placeholder="Provide a comprehensive description of the incident, including what happened, when it occurred, and any relevant details..." required></textarea>
                </div>

                {{-- Witnesses --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Witness Information (Optional)</label>
                    <textarea name="witnesses" rows="3" class="xr-input" placeholder="Names, contact information, and statements from any witnesses"></textarea>
                </div>

                {{-- Document Uploads --}}
                <div class="space-y-6">
                    <h3 class="syne text-hi text-xl font-bold">Required Documentation</h3>

                    {{-- Medical Reports --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Medical Reports (PDF, Images)</label>
                        <div class="file-upload" onclick="document.getElementById('medical_reports').click()">
                            <div class="text-4xl mb-2">🏥</div>
                            <p class="text-sub mb-2">Click to upload medical reports, bills, or prescriptions</p>
                            <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB each</p>
                        </div>
                        <input id="medical_reports" name="medical_reports[]" type="file" multiple accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                        <div id="medical-files" class="mt-3 space-y-2"></div>
                    </div>

                    {{-- Police Report --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Police Report (If Applicable)</label>
                        <div class="file-upload" onclick="document.getElementById('police_report').click()">
                            <div class="text-4xl mb-2">🚔</div>
                            <p class="text-sub mb-2">Upload police report or FIR copy</p>
                            <p class="text-xs text-sub">PDF, JPG, PNG up to 5MB</p>
                        </div>
                        <input id="police_report" name="police_report" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" />
                        <div id="police-file" class="mt-3"></div>
                    </div>

                    {{-- Damage Photos --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Damage Photographs</label>
                        <div class="file-upload" onclick="document.getElementById('damage_photos').click()">
                            <div class="text-4xl mb-2">📸</div>
                            <p class="text-sub mb-2">Upload photos of the damage or incident</p>
                            <p class="text-xs text-sub">JPG, PNG up to 5MB each</p>
                        </div>
                        <input id="damage_photos" name="damage_photos[]" type="file" multiple accept=".jpg,.jpeg,.png" class="hidden" />
                        <div id="damage-files" class="mt-3 space-y-2"></div>
                    </div>

                    {{-- Other Documents --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-sub mb-3">Additional Documents (Optional)</label>
                        <div class="file-upload" onclick="document.getElementById('other_documents').click()">
                            <div class="text-4xl mb-2">📄</div>
                            <p class="text-sub mb-2">Any other relevant documents</p>
                            <p class="text-xs text-sub">PDF, DOC, DOCX, JPG, PNG up to 5MB each</p>
                        </div>
                        <input id="other_documents" name="other_documents[]" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" />
                        <div id="other-files" class="mt-3 space-y-2"></div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="btn-xr w-full syne text-sm uppercase tracking-wider">
                        {{ __('messages.Submit Claim for Processing') }}
                    </button>
                    <p class="text-xs text-sub text-center mt-4">
                        {{ __('messages.By submitting this claim, you confirm that all information provided is accurate and complete.') }}
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File upload handling
        function handleFileSelect(input, containerId, maxFiles = 5) {
            const container = document.getElementById(containerId);
            const files = Array.from(input.files);

            if (files.length > maxFiles) {
                alert(`Maximum ${maxFiles} files allowed`);
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
            const input = document.getElementById(containerId.replace('-files', ''));
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
        document.getElementById('medical_reports').addEventListener('change', function() {
            handleFileSelect(this, 'medical-files', 5);
        });

        document.getElementById('police_report').addEventListener('change', function() {
            handleFileSelect(this, 'police-file', 1);
        });

        document.getElementById('damage_photos').addEventListener('change', function() {
            handleFileSelect(this, 'damage-files', 10);
        });

        document.getElementById('other_documents').addEventListener('change', function() {
            handleFileSelect(this, 'other-files', 5);
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