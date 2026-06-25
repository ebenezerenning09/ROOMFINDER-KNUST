<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'RoomFinder') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=4" type="image/png">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <div class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-center sm:px-6">
        <p class="text-sm font-bold text-amber-950 sm:text-base">
            10% agent fee applies after we successfully help you find a room.
        </p>
    </div>

    <div class="flex flex-1 flex-col items-center justify-center px-4 py-10 sm:px-6">
        <a href="{{ route('home') }}" class="mb-6 inline-flex transition hover:opacity-90">
            <x-roomfinder-logo variant="trimmed" class="h-9 w-auto" />
        </a>

        <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('rooms.index') }}" class="font-medium text-emerald-700 hover:text-emerald-800">Browse listings without an account</a>
        </p>
    </div>
</body>
</html>
