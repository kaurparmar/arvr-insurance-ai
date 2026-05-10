<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Create Your Account</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">Join LifeShield XR and protect your future</p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div style="background: var(--rose-dim); border: 1px solid var(--rose);" class="rounded-lg p-4">
                <p style="color: var(--rose);" class="text-sm font-medium mb-2">{{ __('Validation Error') }}</p>
                <ul style="color: var(--rose);" class="text-xs space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Full Name') }}</label>
                <input id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('name'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Email Address') }}</label>
                <input id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="username"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('email'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Password') }}</label>
                <input id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('password'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('password_confirmation'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <!-- Terms -->
            <div class="flex items-start space-x-2">
                <input type="checkbox" name="terms" id="terms" required style="accent-color: var(--cyan); margin-top: 6px;" class="mr-2" />
                <label for="terms" style="color: var(--text-mid);" class="text-sm">
                    {{ __('I agree to the Terms of Service and Privacy Policy') }}
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 mb-2">
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300">
                    {{ __('Create Account') }}
                </button>
            </div>

            <!-- Login Link -->
            <p style="color: var(--text-mid);" class="text-center text-sm">
                {{ __('Already have an account?') }} 
                <a href="{{ route('login') }}" style="color: var(--cyan);" class="font-medium hover:opacity-80 transition">
                    {{ __('Sign in') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
