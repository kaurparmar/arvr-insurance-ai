<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Create New Password</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">Choose a strong password for your account</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="p-4">
                <label for="email" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('Email Address') }}</label>
                <input id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $request->email) }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    readonly
                    style="background: rgba(139,92,246,0.05); border: 1px solid var(--violet-dim); color: var(--text-mid);"
                    class="w-full px-4 py-2 rounded-lg" />
            </div>

            <!-- Password -->
            <div class="p-4">
                <label for="password" style="color: var(--text-hi);" class="block text-sm font-medium mb-2">{{ __('New Password') }}</label>
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
            <div class="p-4">
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

            <!-- Submit Button -->
            <div class="pt-2 mb-2">
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
