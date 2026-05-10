<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b border-r-4" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Welcome Back</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">Access your LifeShield XR account</p>
        </div>

        <!-- Session Status -->
        @if ($errors->any())
            <div style="background: var(--rose-dim); border: 1px solid var(--rose);" class="rounded-lg p-4">
                <p style="color: var(--rose);" class="text-sm font-medium">{{ __('Login Failed') }}</p>
                <p style="color: var(--rose);" class="text-xs mt-1">{{ __('The credentials provided were incorrect.') }}</p>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4 ml-4 mr-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Email Address') }}</label>
                <input id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition mb-4" />
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
                    autocomplete="current-password"
                    style="background: rgba(139,92,246,0.1); border: 1px solid var(--violet-dim); color: var(--text-hi);"
                    class="w-full px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 transition" />
                @if ($errors->has('password'))
                    <p style="color: var(--rose);" class="text-xs mt-2">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between ">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" style="accent-color: var(--cyan);" class="rounded mr-2" />
                    <span style="color: var(--text-mid);" class="text-sm">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: var(--cyan);" class="text-sm hover:opacity-80 transition">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300">
                    {{ __('Sign In') }}
                </button>
            </div>

            <!-- Register Link -->
            <p style="color: var(--text-mid);" class="text-center text-sm pt-4">
                {{ __('Don\'t have an account?') }} 
                <a href="{{ route('register') }}" style="color: var(--cyan);" class="font-medium hover:opacity-80 transition">
                    {{ __('Register now') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>