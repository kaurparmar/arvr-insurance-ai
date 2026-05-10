<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Contact Us - {{ config('app.name', 'LifeShield XR') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white dark:bg-slate-900">
        <x-navbar :is-authenticated="auth()->check()" />

        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="text-center mb-16">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 dark:text-white mb-6">Get in Touch</h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300">We'd love to hear from you. Send us a message today.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Contact Information</h2>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="text-blue-600 text-2xl">📍</div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Address</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Mumbai, India</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="text-purple-600 text-2xl">📞</div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Phone</h3>
                                    <p class="text-gray-600 dark:text-gray-400">+91 1234 567 890</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="text-pink-600 text-2xl">✉️</div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Email</h3>
                                    <p class="text-gray-600 dark:text-gray-400">support@lifeshield-xr.com</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="text-green-600 text-2xl">🕐</div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Business Hours</h3>
                                    <p class="text-gray-600 dark:text-gray-400">Monday - Saturday: 9:00 AM - 6:00 PM</p>
                                    <p class="text-gray-600 dark:text-gray-400">Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form class="bg-white dark:bg-slate-800 rounded-lg p-8 shadow-lg">
                        <div class="mb-6">
                            <label class="block text-gray-900 dark:text-white font-bold mb-2">Name</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Your Name">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-900 dark:text-white font-bold mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="your@email.com">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-900 dark:text-white font-bold mb-2">Subject</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Message Subject">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-900 dark:text-white font-bold mb-2">Message</label>
                            <textarea rows="5" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 dark:bg-slate-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Your message..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-bold hover:shadow-lg transition-all">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
