<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Claims - {{ config('app.name', 'LifeShield XR') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white dark:bg-slate-900">
        <x-navbar :is-authenticated="auth()->check()" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">Easy Claims Process</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">File and track your insurance claims with complete transparency</p>
                </div>

                @auth
                    <div class="grid md:grid-cols-2 gap-8 mb-16">
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">File a New Claim</h2>
                            <form>
                                <div class="mb-6">
                                    <label class="block text-gray-900 dark:text-white font-bold mb-2">Claim Type</label>
                                    <select class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg">
                                        <option>Select Claim Type</option>
                                        <option>Death Benefit</option>
                                        <option>Maturity Benefit</option>
                                        <option>Disability Claim</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label class="block text-gray-900 dark:text-white font-bold mb-2">Policy Number</label>
                                    <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg" placeholder="Your Policy Number">
                                </div>
                                <div class="mb-6">
                                    <label class="block text-gray-900 dark:text-white font-bold mb-2">Description</label>
                                    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg" placeholder="Describe your claim..."></textarea>
                                </div>
                                <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold hover:shadow-lg transition-all">
                                    Submit Claim
                                </button>
                            </form>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Claims Status</h2>
                            <div class="space-y-4">
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-lg border-l-4 border-blue-600">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-bold text-gray-900 dark:text-white">Policy #12345</h3>
                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-bold">In Progress</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">Filed on: May 10, 2026</p>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 shadow-lg border-l-4 border-green-600">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-bold text-gray-900 dark:text-white">Policy #12346</h3>
                                        <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-bold">Approved</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400">Approved on: May 5, 2026</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-12 text-center shadow-lg mb-16">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Please log in to file a claim</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-8">Sign in to your account to access your claims and file new claims.</p>
                        <a href="/login" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold hover:shadow-lg transition-all">
                            Sign In
                        </a>
                    </div>
                @endauth

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-4">1</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Submit Claim</h3>
                        <p class="text-gray-600 dark:text-gray-400">Fill out the claim form with all required details</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-purple-600 mb-4">2</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Verification</h3>
                        <p class="text-gray-600 dark:text-gray-400">Our team will verify your claim details</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-pink-600 mb-4">3</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Settlement</h3>
                        <p class="text-gray-600 dark:text-gray-400">Receive your claim settlement quickly</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
