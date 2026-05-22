<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="label-premium">Email Address</label>
            <input id="email" 
                   class="premium-input block w-full" 
                   type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan email..." />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="label-premium">Secure Password</label>
            <input id="password" 
                   class="premium-input block w-full"
                   type="password" name="password" required placeholder="........" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <!-- Remember Me -->
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-md bg-slate-900 border-slate-700 text-blue-600 shadow-sm focus:ring-blue-500/50" name="remember">
                <span class="ms-3 text-muted-premium">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="link-premium" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="premium-btn w-full">
            {{ __('Masuk Dashboard') }}
        </button>
    </form>
</x-guest-layout>
