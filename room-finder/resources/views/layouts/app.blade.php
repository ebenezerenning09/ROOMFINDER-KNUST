<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @hasSection('title')
            @yield('title') — RoomFinder
        @else
            RoomFinder — Student Housing in Kumasi
        @endif
    </title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=2" type="image/png" sizes="32x32">
    <link rel="icon" href="{{ asset('favicon.svg') }}?v=2" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=2">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <nav class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4">
            <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 text-base font-bold text-emerald-700 hover:text-emerald-800 sm:text-xl">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-50" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="h-6 w-6" fill="none">
                        <path d="M16 6 6 14.5V26h7v-7h6v7h7V14.5L16 6Z" fill="#059669"/>
                        <path d="M16 6 26 14.5h-4L16 9.5 10 14.5H6L16 6Z" fill="#10b981"/>
                        <rect x="14" y="19" width="4" height="7" rx="0.5" fill="#047857"/>
                    </svg>
                </span>
                RoomFinder
            </a>
            <span class="hidden text-sm text-slate-500 sm:inline">Student housing near KNUST</span>
        </div>
    </nav>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-5 sm:px-6 sm:py-8">
        @yield('content')
    </main>

    <footer class="mt-auto w-full border-t border-slate-200 bg-slate-100 px-4 py-8 text-center sm:px-6">
        <p class="text-sm font-semibold text-slate-700">RoomFinder</p>
        <p class="mt-1 text-sm text-slate-500">Built for KNUST students</p>
        <p class="mt-2">
            <a href="mailto:hello@roomfinder.com" class="text-sm text-slate-500 underline decoration-slate-300 underline-offset-2 hover:text-emerald-700 hover:decoration-emerald-300">
                hello@roomfinder.com
            </a>
        </p>
        <p class="mt-4 text-xs text-slate-500 sm:text-sm">
            &copy; {{ date('Y') }} RoomFinder — Kumasi
        </p>
    </footer>
</body>
</html>
