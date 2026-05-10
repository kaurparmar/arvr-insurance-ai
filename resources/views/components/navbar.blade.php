@props(['isAuthenticated' => auth()->check()])

<nav x-data="{ open: false }" class="bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700 sticky top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo/Brand -->
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">XR</span>
                    </div>
                    <span class="hidden sm:inline font-bold text-lg bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">LifeShield</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                @if($isAuthenticated)
                    <!-- Authenticated Desktop Nav -->
                    <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Home</a>
                    <a href="/about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">About</a>
                    <a href="/plans" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Policies</a>
                    <a href="/vr" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">AR Demo</a>
                    <a href="/claims" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Claims</a>
                    <a href="/contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Contact</a>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700 transition">
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-gray-200 dark:border-slate-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="/dashboard" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 text-sm">Dashboard</a>
                            <a href="/profile" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 text-sm">Profile</a>
                            <form action="/logout" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 text-sm">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest Desktop Nav -->
                    <a href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Home</a>
                    <a href="/about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">About</a>
                    <a href="/plans" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">Policies</a>
                    
                    <a href="/login" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Login</a>
                    <a href="/register" class="px-4 py-2 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition font-medium">Register</a>
                @endif
            </div>

            <!-- Mobile Hamburger Menu Button -->
            <button @click="open = !open" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition class="md:hidden bg-gray-50 dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700">
            <div class="px-4 py-4 space-y-2">
                @if($isAuthenticated)
                    <!-- Authenticated Mobile Nav -->
                    <a href="/" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Home</a>
                    <a href="/about" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">About</a>
                    <a href="/plans" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Policies</a>
                    <a href="/vr" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">AR Demo</a>
                    <a href="/claims" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Claims</a>
                    <a href="/contact" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Contact</a>
                    <hr class="my-2 border-gray-200 dark:border-slate-700">
                    <a href="/dashboard" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Dashboard</a>
                    <a href="/profile" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Profile</a>
                    <form action="/logout" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Logout</button>
                    </form>
                @else
                    <!-- Guest Mobile Nav -->
                    <a href="/" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Home</a>
                    <a href="/about" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">About</a>
                    <a href="/plans" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition font-medium">Policies</a>
                    <hr class="my-2 border-gray-200 dark:border-slate-700">
                    <a href="/login" class="block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-center">Login</a>
                    <a href="/register" class="block px-4 py-2 border-2 border-blue-600 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-700 transition font-medium text-center">Register</a>
                @endif
            </div>
        </div>
    </div>
</nav>
