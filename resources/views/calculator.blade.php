<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Calculator - {{ config('app.name', 'LifeShield XR') }}</title>
    <script>
        (function () {
            const theme = localStorage.theme;
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (theme === 'dark' || (!theme && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-950 dark:bg-slate-950 dark:text-slate-100">
    <x-navbar :is-authenticated="auth()->check()" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-bold text-slate-950 dark:text-white mb-6">Premium Calculator</h1>
                <p class="text-xl text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">Calculate your life insurance premium based on your personal details and coverage needs.</p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-[32px] p-8 shadow-2xl ring-1 ring-slate-200/70 dark:ring-slate-700">
                <form method="POST" action="{{ route('calculate') }}" class="space-y-8">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Age</label>
                            <input type="number" name="age" min="18" max="80" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition" placeholder="Enter your age" required />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Annual Salary (₹)</label>
                            <input type="number" name="salary" min="100000" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition" placeholder="Enter annual salary" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Number of Dependents</label>
                        <input type="number" name="dependents" min="0" max="10" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition" placeholder="Enter number of dependents" required />
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-violet-600 px-8 py-4 text-white font-semibold text-lg shadow-xl shadow-cyan-500/20 hover:from-cyan-400 hover:to-violet-500 transition transform hover:scale-105">
                        Calculate Premium
                    </button>
                </form>

                @if(isset($premium))
                    <div class="mt-12 p-6 bg-gradient-to-r from-emerald-50 to-cyan-50 dark:from-emerald-900/20 dark:to-cyan-900/20 rounded-2xl border border-emerald-200 dark:border-emerald-800">
                        <div class="text-center">
                            <div class="text-6xl mb-4">💰</div>
                            <h3 class="text-2xl font-bold text-slate-950 dark:text-white mb-2">Your Estimated Premium</h3>
                            <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">₹{{ number_format($premium, 2) }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-2">per year</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>