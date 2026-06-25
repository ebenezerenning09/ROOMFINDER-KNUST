<x-guest-layout>
    <h2 class="mb-6 text-center text-xl font-bold text-slate-900">Log in to RoomFinder</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-700 hover:text-emerald-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="sm:ms-auto">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
