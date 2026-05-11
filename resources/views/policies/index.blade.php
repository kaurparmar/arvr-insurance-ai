<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Policies</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-6">
                    @if($policies->isEmpty())
                        <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-10 text-center">
                            <h3 class="text-2xl font-semibold text-slate-900 dark:text-white mb-3">No active policies yet</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-6">Browse our insurance plans and purchase coverage with just one click.</p>
                            <a href="{{ route('plans.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full bg-blue-600 text-white shadow-lg shadow-blue-500/20 hover:bg-blue-500 transition">Explore Plans</a>
                        </div>
                    @else
                        <div class="grid gap-6 lg:grid-cols-2">
                            @foreach($policies as $policy)
                                <div class="rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg transition">
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div>
                                            <p class="text-sm uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Policy Number</p>
                                            <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $policy->policy_number }}</h3>
                                        </div>
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-800 px-3 py-1 text-xs font-semibold dark:bg-emerald-900/20 dark:text-emerald-200">{{ ucfirst($policy->status) }}</span>
                                    </div>

                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Plan</p>
                                    <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ $policy->plan?->title ?? 'Policy Plan' }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">Coverage: ₹{{ number_format($policy->plan?->coverage_amount ?? 0) }}</p>

                                    <div class="grid grid-cols-2 gap-4 text-sm text-slate-600 dark:text-slate-400">
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">Premium</p>
                                            <p>₹{{ number_format($policy->premium_paid) }}</p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">Valid Until</p>
                                            <p>{{ optional($policy->end_date)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
