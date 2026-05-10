<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Confirm Password</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">This is a secure area. Please confirm your password</p>
        </div>

        <!-- Info Message -->
        <div style="background: var(--violet-dim); border: 1px solid var(--violet);" class="rounded-lg p-4">
            <p style="color: var(--text-mid);" class="text-sm">
                {{ __('This is a secure area of LifeShield XR. Please confirm your password before continuing.') }}
            </p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

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

            <!-- Security Features -->
            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                <div style="background: var(--emerald-dim); border: 1px solid var(--emerald);" class="rounded p-3">
                    <div style="color: var(--emerald);">🔒</div>
                    <p style="color: var(--text-mid);">Encrypted</p>
                </div>
                <div style="background: var(--cyan-dim); border: 1px solid var(--cyan);" class="rounded p-3">
                    <div style="color: var(--cyan);">⚡</div>
                    <p style="color: var(--text-mid);">Secure XR</p>
                </div>
                <div style="background: var(--violet-dim); border: 1px solid var(--violet);" class="rounded p-3">
                    <div style="color: var(--violet);">🛡️</div>
                    <p style="color: var(--text-mid);">Protected</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300 flex items-center justify-center gap-2">
                    {{ __('Confirm Access') }}
                    <span>→</span>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

