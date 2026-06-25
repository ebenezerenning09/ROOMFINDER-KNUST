<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title') — Admin
        @else
            Admin — RoomFinder
        @endif
    </title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=4" type="image/png">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex shrink-0 items-center transition hover:opacity-90">
                    <x-roomfinder-logo variant="trimmed" class="h-8 w-auto sm:h-9" />
                </a>
                <div class="hidden border-l border-slate-200 pl-3 sm:block">
                    <p class="text-sm font-semibold leading-tight text-slate-900">Admin</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <nav class="flex flex-wrap items-center gap-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-700' }}"
                >
                    Dashboard
                </a>
                <a
                    href="{{ route('admin.rooms.index') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.rooms.*') ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-700' }}"
                >
                    Rooms
                </a>
                <a
                    href="{{ route('admin.users.index') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-emerald-700' }}"
                >
                    Users
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('rooms.index') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-700">
                    View site
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-600 hover:border-slate-400 hover:bg-slate-50">
                        Log out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6">
        @hasSection('header')
            <div class="mb-6">
                @yield('header')
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
