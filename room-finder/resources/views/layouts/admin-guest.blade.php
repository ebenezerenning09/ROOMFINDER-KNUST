<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — RoomFinder</title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=4" type="image/png">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <div class="flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-6">
        <div class="mb-8 text-center">
            <a href="{{ route('rooms.index') }}" class="inline-flex transition hover:opacity-90">
                <x-roomfinder-logo variant="full" class="justify-center" />
            </a>
            <p class="mt-4 text-sm font-semibold text-slate-700">Admin sign in</p>
            <p class="mt-1 text-sm text-slate-500">Manage listings and users</p>
        </div>

        <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('rooms.index') }}" class="font-medium text-emerald-700 hover:text-emerald-800">Back to public site</a>
        </p>
    </div>
</body>
</html>
