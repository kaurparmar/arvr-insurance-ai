<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Contact Us - {{ config('app.name', 'LifeShield XR') }}</title>
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-bold text-slate-950 dark:text-white mb-6">Get in Touch</h1>
                <p class="text-xl text-slate-600 dark:text-slate-300">We'd love to hear from you. Send us a message today.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h2 class="text-2xl font-bold text-slate-950 dark:text-white mb-8">Contact Information</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="text-cyan-600 text-2xl">📍</div>
                            <div>
                                <h3 class="font-bold text-slate-950 dark:text-white mb-2">Address</h3>
                                <p class="text-slate-600 dark:text-slate-400">Mumbai, India</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-purple-600 text-2xl">📞</div>
                            <div>
                                <h3 class="font-bold text-slate-950 dark:text-white mb-2">Phone</h3>
                                <p class="text-slate-600 dark:text-slate-400">+91 1234 567 890</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-pink-600 text-2xl">✉️</div>
                            <div>
                                <h3 class="font-bold text-slate-950 dark:text-white mb-2">Email</h3>
                                <p class="text-slate-600 dark:text-slate-400">support@lifeshield-xr.com</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-green-600 text-2xl">🕐</div>
                            <div>
                                <h3 class="font-bold text-slate-950 dark:text-white mb-2">Business Hours</h3>
                                <p class="text-slate-600 dark:text-slate-400">Monday - Saturday: 9:00 AM - 6:00 PM</p>
                                <p class="text-slate-600 dark:text-slate-400">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-[32px] border border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 p-8 backdrop-blur-xl shadow-2xl">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-white mb-4">Live Stats</h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-cyan-600">{{ number_format($planCount) }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Active Plans</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600">{{ number_format($activeUsers) }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">Members</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-2xl ring-1 ring-slate-200/70 dark:ring-slate-700">
                    <div class="mb-6">
                        <label class="block text-slate-950 dark:text-white font-bold mb-2">Name</label>
                        <input type="text" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Your Name">
                    </div>
                    <div class="mb-6">
                        <label class="block text-slate-950 dark:text-white font-bold mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="your@email.com">
                    </div>
                    <div class="mb-6">
                        <label class="block text-slate-950 dark:text-white font-bold mb-2">Subject</label>
                        <input type="text" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Message Subject">
                    </div>
                    <div class="mb-6">
                        <label class="block text-slate-950 dark:text-white font-bold mb-2">Message</label>
                        <textarea rows="5" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-600" placeholder="Your message..."></textarea>
                    </div>
                    <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-cyan-600 to-purple-600 text-white rounded-lg font-bold hover:shadow-xl transition">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
