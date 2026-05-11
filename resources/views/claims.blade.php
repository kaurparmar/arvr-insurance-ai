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
                    <p class="text-xl text-gray-600 dark:text-gray-300">File and track your insurance claims with complete transparency.</p>
                </div>

                @auth
                    <div class="grid lg:grid-cols-2 gap-8 mb-16">
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-2xl ring-1 ring-slate-200/70 dark:ring-slate-700">
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-6">File a New Claim</h2>
                            <form action="{{ route('claims.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Select Policy</label>
                                    <select name="policy_id" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Choose a policy</option>
                                        @foreach($policies as $policy)
                                            <option value="{{ $policy->_id ?? $policy->id }}">{{ $policy->policy_number }} — {{ $policy->plan->name ?? 'Plan' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Claim Reason</label>
                                    <textarea name="claim_reason" rows="4" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe your claim in detail..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Claim Amount</label>
                                    <input name="claim_amount" type="number" step="0.01" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter claim amount" />
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 text-white font-semibold hover:shadow-xl transition">Submit claim</button>
                            </form>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-2xl ring-1 ring-slate-200/70 dark:ring-slate-700">
                                <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-5">Claims status</h2>
                                <div class="space-y-4">
                                    @forelse($claims as $claim)
                                        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 p-5">
                                            <div class="flex items-center justify-between gap-4 mb-3">
                                                <div>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400">Policy</p>
                                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $claim->policy->policy_number ?? 'Unknown' }}</p>
                                                </div>
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $claim->status === 'approved' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($claim->status === 'pending' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200') }}">{{ ucfirst($claim->status) }}</span>
                                            </div>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Submitted on {{ $claim->submitted_at?->format('F d, Y') ?? '—' }}</p>
                                            <p class="mt-3 text-slate-600 dark:text-slate-300">{{ Str::limit($claim->claim_reason, 120) }}</p>
                                        </div>
                                    @empty
                                        <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center">
                                            <p class="text-slate-700 dark:text-slate-300 font-semibold mb-2">No claims have been submitted yet.</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Use the form to file your first insurance claim.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center shadow-2xl ring-1 ring-slate-200/70 dark:ring-slate-700 mb-16">
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-4">Please log in to file a claim</h2>
                        <p class="text-slate-500 dark:text-slate-400 mb-8">Sign in to access your account, manage policies, and file claims safely.</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-3 text-sm font-semibold text-white hover:shadow-xl transition">Sign in</a>
                    </div>
                @endauth

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg ring-1 ring-slate-200/70 dark:ring-slate-700">
                        <div class="text-4xl font-bold text-blue-600 mb-4">1</div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Submit claim</h3>
                        <p class="text-slate-500 dark:text-slate-400">Complete the claim form with your policy details and supporting information.</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg ring-1 ring-slate-200/70 dark:ring-slate-700">
                        <div class="text-4xl font-bold text-purple-600 mb-4">2</div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Verification</h3>
                        <p class="text-slate-500 dark:text-slate-400">Our claims team reviews your submission and keeps you updated.</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg ring-1 ring-slate-200/70 dark:ring-slate-700">
                        <div class="text-4xl font-bold text-pink-600 mb-4">3</div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Settlement</h3>
                        <p class="text-slate-500 dark:text-slate-400">Receive the payout when your claim is approved and processed.</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
