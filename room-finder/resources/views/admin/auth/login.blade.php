@extends('layouts.admin-guest')

@section('content')
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@roomfinder.test"
                class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
            >
            @if ($errors->has('email'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
            >
            @if ($errors->has('password'))
                <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                >
                <span class="text-sm text-slate-600">Remember me</span>
            </label>
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
        >
            Sign in to admin
        </button>
    </form>
@endsection
