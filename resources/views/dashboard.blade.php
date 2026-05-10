<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
                <h1 class="text-4xl font-bold mb-2">Welcome, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100">Manage your life insurance policies and claims in one place</p>
            </div>

            <!-- Account Information -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Account Information</h3>
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Full Name</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Email Address</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Member Since</p>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                        </div>
                        <div class="pb-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Account Status</p>
                            <p class="text-gray-900 dark:text-white font-semibold"><span class="text-green-600 font-bold">✓ Active</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Active Policies</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">3</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Coverage</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">₹50,00,000</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Monthly Premium</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">₹5,450</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Policies -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Active Policies</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Policy Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Plan Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Coverage Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">LSX-2024-001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">Term Life 20 Years</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">₹20,00,000</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Active</span></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">LSX-2024-002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">Endowment Plan</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">₹15,00,000</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Active</span></td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">LSX-2024-003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">Money Back Plan</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">₹15,00,000</td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid md:grid-cols-4 gap-6">
                <a href="/plans" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <div class="text-3xl mb-3">📋</div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Browse Plans</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Explore our insurance plans</p>
                </a>
                <a href="/claims" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <div class="text-3xl mb-3">📝</div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">File a Claim</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Submit your insurance claim</p>
                </a>
                <a href="/vr" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <div class="text-3xl mb-3">👓</div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">AR/VR Demo</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Experience in 3D</p>
                </a>
                <a href="/profile" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-md transition-all">
                    <div class="text-3xl mb-3">⚙️</div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Settings</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Manage your account</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
