<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $plan->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">{{ $plan->name }}</h1>
                    <p class="text-slate-600 dark:text-slate-300 mb-6">{{ $plan->description }}</p>

                    <div class="grid gap-4 sm:grid-cols-2 mb-8">
                        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 p-6 bg-slate-50 dark:bg-slate-900">
                            <p class="text-sm uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Coverage</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">₹{{ number_format($plan->coverage_amount) }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 p-6 bg-slate-50 dark:bg-slate-900">
                            <p class="text-sm uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Duration</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $plan->duration_years }} years</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Plan Features</h3>
                        <ul class="grid gap-3">
                            @foreach($plan->features ?? [] as $feature)
                                <li class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-4 text-slate-700 dark:text-slate-200">{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-3xl bg-gradient-to-br from-slate-950 to-slate-800 p-8 text-white shadow-xl">
                        <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Premium Estimate</p>
                        <p class="mt-4 text-5xl font-bold">₹{{ number_format($plan->premium_amount) }}</p>
                        <p class="mt-3 text-slate-300">A simple premium anchored to your coverage and term.</p>
                    </div>

                    <div class="rounded-3xl bg-white dark:bg-gray-900 border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Ready to secure your future?</h3>
                        @auth
                            <form action="{{ route('purchase.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="w-full rounded-3xl bg-blue-600 py-4 text-white font-semibold hover:bg-blue-500 transition">Buy this plan</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center rounded-3xl bg-blue-600 py-4 text-white font-semibold hover:bg-blue-500 transition">Login to purchase</a>
                        @endauth
                    </div>

                    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 p-6 shadow-sm">
                        <h4 class="text-base font-semibold text-slate-900 dark:text-white mb-3">Need help?</h4>
                        <p class="text-sm text-slate-600 dark:text-slate-300">Contact our support team for plan comparison, custom quotes, or onboarding help.</p>
                        <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500">Contact support →</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
