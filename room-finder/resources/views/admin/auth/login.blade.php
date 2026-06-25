@extends('layouts.admin-guest')

@section('content')
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-500/40 bg-red-950/50 px-4 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-300">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@roomfinder.test"
                class="admin-login-input block w-full rounded-lg px-3 py-2.5 text-sm"
            >
            @if ($errors->has('email'))
                <p class="mt-2 text-sm text-red-400">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-300">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                class="admin-login-input block w-full rounded-lg px-3 py-2.5 text-sm"
            >
            @if ($errors->has('password'))
                <p class="mt-2 text-sm text-red-400">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-slate-500 bg-slate-700 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-800"
                >
                <span class="text-sm text-slate-400">Remember me</span>
            </label>
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-800"
        >
            Sign in to admin
        </button>
    </form>
@endsection
