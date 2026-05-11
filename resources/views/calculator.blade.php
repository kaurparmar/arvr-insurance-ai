<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Calculator - {{ config('app.name', 'LifeShield XR') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <script>
        (function(){const t=localStorage.theme,d=window.matchMedia('(prefers-color-scheme: dark)').matches;
        if(t==='dark'||(!t&&d)){document.documentElement.classList.add('dark');}else{document.documentElement.classList.remove('dark');}})();
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --cyan:#00F0FF; --violet:#8B5CF6; --rose:#FF3B6B; --emerald:#00E676;
            --bg-void:#03060F; --bg-deep:#060C1A; --bg-panel:rgba(8,14,30,.92);
            --text-mid:#8892AA; --border-w:rgba(255,255,255,.07);
        }
        .syne { font-family: 'Syne', sans-serif; }
        body { font-family: 'DM Sans', sans-serif; transition: background .3s, color .3s; }
        
        .dark body { background: var(--bg-void); color: #EEF2FF; }
        
        .vr-scanlines {
            display: none; position: fixed; inset: 0; pointer-events: none; z-index: 1;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.025) 2px, rgba(0,0,0,.025) 4px);
        }
        .dark .vr-scanlines { display: block; }

        .glow-cyan { position: absolute; border-radius: 50%; filter: blur(100px); background: rgba(0,240,255,.07); pointer-events: none; }
        .glow-violet { position: absolute; border-radius: 50%; filter: blur(120px); background: rgba(139,92,246,.06); pointer-events: none; }
        
        .xr-card {
            border-radius: 24px; position: relative; overflow: hidden; transition: all .3s;
        }
        .dark .xr-card { background: var(--bg-panel); border: 1px solid var(--border-w); }
        html:not(.dark) .xr-card { background: #fff; border: 1px solid rgba(0,0,0,.07); box-shadow: 0 4px 24px rgba(0,0,0,.06); }

        .grad-text {
            background: linear-gradient(135deg, var(--cyan) 0%, var(--violet) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        
        .xr-input {
            width: 100%; padding: 14px 20px; border-radius: 16px; border: 1px solid rgba(0,240,255,.1);
            background: rgba(255,255,255,0.03); color: inherit; transition: all .2s;
        }
        .xr-input:focus { border-color: var(--cyan); outline: none; box-shadow: 0 0 15px rgba(0,240,255,0.1); }
        
        .btn-primary {
            background: var(--cyan); color: #020F14; font-family: 'Syne', sans-serif; 
            font-weight: 700; padding: 16px 32px; border-radius: 100px; border: none;
            cursor: pointer; transition: all .2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,240,255,.3); }

        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { opacity: 0; animation: fadeUp .6s forwards; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="vr-scanlines"></div>

    <x-navbar :is-authenticated="auth()->check()" />

    <div class="relative min-h-screen overflow-hidden flex flex-col items-center justify-center py-20 px-4">
        <!-- Glow effects -->
        <div class="glow-cyan" style="width:600px;height:600px;top:-100px;right:-100px;opacity:.5"></div>
        <div class="glow-violet" style="width:500px;height:500px;bottom:-100px;left:-100px;opacity:.4"></div>

        <!-- Header Section -->
        <div class="text-center mb-12 fade-up relative z-10">
            <!-- Updated to ensure white text in dark mode -->
            <h1 class="syne text-5xl md:text-6xl font-bold  mb-6">
                Premium <span class="grad-text">Calculator</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-[#8892AA] max-w-xl mx-auto">
                Analyze your risk profile and generate an instant coverage estimate using our XR-Shield engine.
            </p>
        </div>

        <!-- Card Container -->
        <div class="xr-card p-8 md:p-12 fade-up md:max-w-2xl relative z-10 w-1/2 mt-4" style="animation-delay: 0.2s">
            <form method="POST" action="{{ route('calculate') }}" class="space-y-6 mb-4 ">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-[#8892AA] mb-3">User Age</label>
                        <input type="number" name="age" min="18" max="80" 
                            class="xr-input dark:bg-slate-900/50 dark:text-white placeholder-slate-400" 
                            placeholder="e.g. 25" required />
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-[#8892AA] mb-3">Annual Salary (₹)</label>
                        <input type="number" name="salary" min="100000" 
                            class="xr-input dark:bg-slate-900/50 dark:text-white placeholder-slate-400" 
                            placeholder="Enter amount" required />
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-[#8892AA] mb-3">Dependents</label>
                    <input type="number" name="dependents" min="0" max="10" 
                        class="xr-input dark:bg-slate-900/50 dark:text-white placeholder-slate-400" 
                        placeholder="Number of family members" required />
                </div>

                <button type="submit" class="btn-primary w-full mb-6">
                    Initialize Calculation →
                </button>
            </form>

            @if(isset($premium))
                <!-- Updated display box with high contrast colors -->
                <div class="mt-10 p-8 rounded-2xl border border-[#00E676]/30 bg-[#00E676]/5 fade-up">
                    <div class="flex flex-col items-center text-center">
                        <span class="text-sm uppercase tracking-widest text-[#00E676] font-bold mb-3">Estimated Annual Premium</span>
                        <h3 class="syne text-4xl md:text-5xl font-extrabold  leading-tight">
                            ₹{{ number_format($premium, 2) }}
                        </h3>
                        <p class="text-slate-500 dark:text-[#8892AA] mt-4 text-sm italic">Calculated based on current LifeShield XR risk parameters.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>