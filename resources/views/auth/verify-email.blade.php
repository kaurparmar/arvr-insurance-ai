<x-guest-layout>
    <div class="space-y-6 p-4">
        <!-- Page Title -->
        <div class="text-center pb-4 border-b" style="border-color: var(--cyan-dim);">
            <h2 style="color: var(--cyan);" class="text-2xl font-bold tracking-tight">Verify Your Email</h2>
            <p style="color: var(--text-mid);" class="text-sm mt-2">Confirm your email to activate your account</p>
        </div>

        <!-- Info Message -->
        <div style="background: var(--emerald-dim); border: 1px solid var(--emerald);" class="rounded-lg p-4">
            <p style="color: var(--text-mid);" class="text-sm">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>
        </div>

        <!-- Status Message -->
        @if (session('status') == 'verification-link-sent')
            <div style="background: var(--emerald-dim); border: 1px solid var(--emerald);" class="rounded-lg p-4">
                <p style="color: var(--emerald);" class="text-sm font-medium">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col gap-4">
            <!-- Resend Email Button -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    style="background: linear-gradient(135deg, var(--cyan), var(--emerald)); color: var(--bg-void);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:shadow-lg hover:shadow-cyan-400/30 transition duration-300">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    style="border: 1px solid var(--cyan-dim); color: var(--cyan); background: rgba(0,240,255,0.1);"
                    class="w-full px-4 py-2 rounded-lg font-semibold hover:bg-cyan-400/20 transition duration-300">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
</x-guest-layout>
