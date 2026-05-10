<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Reset Your Password</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">We'll help you recover access to your account</p>
        </div>

        <!-- Status Message -->
        @if (session('status'))
            <div style="background: var(--emerald-dim); border: 1px solid var(--emerald);" class="rounded-lg p-4">
                <p style="color: var(--emerald);" class="text-sm">{{ session('status') }}</p>
            </div>
        @endif

        <!-- Info Message -->
        <div style="background: var(--violet-dim); border: 1px solid var(--violet);" class="rounded-lg p-4">
            <p style="color: var(--text-mid);" class="text-sm">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Email Address') }}</label>
                <input id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('email'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2 mb-2">
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300">
                    {{ __('Send Reset Link') }}
                </button>
            </div>

            <!-- Back to Login -->
            <p style="color: var(--text-mid);" class="text-center text-sm">
                <a href="{{ route('login') }}" style="color: var(--cyan);" class="font-medium hover:opacity-80 transition">
                    {{ __('Back to Login') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
