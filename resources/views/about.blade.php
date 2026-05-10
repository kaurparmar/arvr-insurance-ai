<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>About Us - {{ config('app.name', 'LifeShield XR') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white dark:bg-slate-900">
        <x-navbar :is-authenticated="auth()->check()" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">About LifeShield XR</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">Revolutionizing life insurance through immersive reality technology</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12 mb-16">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Our Mission</h2>
                        <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-4">
                            At LifeShield XR, we believe life insurance should be simple, transparent, and engaging. We're pioneering India's first AR/VR-powered insurance platform to help you visualize your future, understand your coverage, and make confident decisions about protecting your family.
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                            Our innovative technology transforms complex insurance concepts into immersive 3D experiences that anyone can understand and interact with.
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg p-8 text-white">
                        <h2 class="text-3xl font-bold mb-6">Our Vision</h2>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="text-2xl mt-1">✓</span>
                                <span>Make insurance accessible to everyone through immersive technology</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl mt-1">✓</span>
                                <span>Empower families to make informed decisions about their future</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl mt-1">✓</span>
                                <span>Transform the insurance industry with innovative solutions</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl mt-1">✓</span>
                                <span>Build trust through transparency and cutting-edge technology</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-blue-600 mb-4">100K+</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Satisfied Customers</h3>
                        <p class="text-gray-600 dark:text-gray-400">Families across India trust us with their insurance needs</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-purple-600 mb-4">50+</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Expert Team</h3>
                        <p class="text-gray-600 dark:text-gray-400">Dedicated professionals committed to your success</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="text-4xl font-bold text-pink-600 mb-4">24/7</div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Customer Support</h3>
                        <p class="text-gray-600 dark:text-gray-400">Always here to help with your questions and concerns</p>
                    </div>
                </div>

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Ready to experience the future of insurance?</h2>
                    <a href="/login" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold hover:shadow-lg transition-all">
                        Get Started Today
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
