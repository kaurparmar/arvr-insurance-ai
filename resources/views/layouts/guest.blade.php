<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LifeShield XR') }} — AR/VR Life Insurance</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script>
            (function () {
                const theme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (theme === 'dark' || (!theme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --bg-void: #F8FAFF;
                --bg-deep: #E5E7EB;
                --bg-panel: rgba(255,255,255,0.92);
                --cyan: #00B8FF;
                --cyan-dim: rgba(0,184,255,0.15);
                --cyan-glow: rgba(0,184,255,0.06);
                --violet: #8B5CF6;
                --violet-dim: rgba(139,92,246,0.15);
                --rose: #E11D48;
                --rose-dim: rgba(225,29,72,0.15);
                --emerald: #10B981;
                --emerald-dim: rgba(16,185,129,0.12);
                --text-hi: #111827;
                --text-mid: #475569;
                --border-color: rgba(15,23,42,0.08);
            }

            .dark {
                --bg-void: #03060F;
                --bg-deep: #060C1A;
                --bg-panel: rgba(8,14,30,0.92);
                --cyan: #00F0FF;
                --cyan-dim: rgba(0,240,255,0.15);
                --cyan-glow: rgba(0,240,255,0.06);
                --rose: #FF3B6B;
                --rose-dim: rgba(255,59,107,0.15);
                --emerald: #00E676;
                --emerald-dim: rgba(0,230,118,0.12);
                --text-hi: #EEF2FF;
                --text-mid: #8892AA;
                --border-color: rgba(0,240,255,0.2);
            }

            body {
                background: linear-gradient(135deg, var(--bg-void) 0%, #F8FAFF 100%);
                color: var(--text-hi);
                font-family: 'DM Sans', sans-serif;
            }

            .dark body {
                background: linear-gradient(135deg, var(--bg-void) 0%, #0A0E1E 100%);
            }

            .auth-container {
                position: relative;
                overflow: hidden;
                min-height: 100vh;
            }
            
            .auth-background {
                position: absolute;
                inset: 0;
                overflow: hidden;
                pointer-events: none;
            }
            
            .bg-glow {
                position: absolute;
                border-radius: 50%;
                filter: blur(120px);
                opacity: 0.3;
            }
            
            .bg-glow-cyan {
                width: 500px;
                height: 500px;
                background: var(--cyan);
                top: -100px;
                left: -100px;
                animation: float 20s ease-in-out infinite;
            }
            
            .bg-glow-violet {
                width: 500px;
                height: 500px;
                background: var(--violet);
                bottom: -100px;
                right: -100px;
                animation: float 25s ease-in-out infinite 2s;
            }
            
            .grid-overlay {
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
                background-size: 40px 40px;
                opacity: 0.5;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(30px); }
            }
            
            .nav-bar {
                position: relative;
                z-index: 20;
                border-bottom: 1px solid var(--cyan-dim);
                backdrop-filter: blur(20px);
                background: rgba(255,255,255,0.7);
            }

            .dark .nav-bar {
                background: rgba(3,6,15,0.7);
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <!-- Animated background -->
            <div class="auth-background">
                <div class="bg-glow bg-glow-cyan"></div>
                <div class="bg-glow bg-glow-violet"></div>
                <div class="grid-overlay"></div>
            </div>

            <!-- Navigation -->
            <nav class="nav-bar">
                <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between relative z-20 mb-4 pt-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                            <span style="color: var(--text-hi);">Life</span>
                            <span style="color: var(--cyan);">Shield XR</span>
                        </h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            style="border: 1px solid rgba(0,240,255,0.3); background: rgba(0,240,255,0.1); color: rgba(0,240,255,0.8);"
                            class="px-5 py-2 rounded-full transition duration-300 hover:bg-cyan-400/20">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            style="background: var(--cyan); color: var(--bg-void);"
                            class="px-5 py-2 rounded-full font-semibold hover:scale-105 transition duration-300 shadow-lg shadow-cyan-400/30">
                            Register
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <div class="relative z-10 min-h-[calc(100vh-80px)] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-6">
                <div class="w-full sm:max-w-md mt-6">
                    <div style="background: var(--bg-panel); border: 1px solid var(--cyan-dim);" class="rounded-md shadow-2xl overflow-hidden backdrop-blur-xl">
                        <div class="px-8 ">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
