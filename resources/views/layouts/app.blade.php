<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title'): RoomFinder
        @else
            RoomFinder: Student Housing in Kumasi
        @endif
    </title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=4" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=4">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-slate-50 text-slate-900 antialiased">
    <nav class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6 sm:py-4">
            <a href="{{ route('rooms.index') }}" class="inline-flex shrink-0 items-center transition hover:opacity-90">
                <x-roomfinder-logo variant="trimmed" class="h-8 w-auto sm:h-9" />
            </a>

            <div class="flex items-center gap-3 sm:gap-4">
                @auth
                    <span class="hidden text-sm font-medium text-slate-600 sm:inline">{{ Auth::user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-slate-600 hover:text-emerald-700">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-emerald-700">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Register</a>
                @endauth

                <a
                    href="https://www.knust.edu.gh/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex shrink-0 items-center"
                    title="Kwame Nkrumah University of Science and Technology"
                >
                    <img
                        src="{{ asset('images/knust-logo.png') }}"
                        alt="KNUST logo"
                        class="h-10 w-auto object-contain sm:h-12"
                        width="48"
                        height="48"
                    >
                </a>
            </div>
        </div>
    </nav>

    <div class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-center sm:px-6">
        <p class="text-sm font-bold text-amber-950 sm:text-base">
            10% agent fee applies after we successfully help you find a room.
        </p>
        <p class="mt-0.5 text-xs text-amber-800 sm:text-sm">
            Charged only when your hostel deal is confirmed, not before.
        </p>
    </div>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-5 sm:px-6 sm:py-8">
        @yield('content')
    </main>

    <footer class="mt-auto w-full border-t border-slate-200 bg-slate-100 px-4 py-8 text-center sm:px-6">
        <p class="text-sm font-semibold text-slate-700">RoomFinder</p>
        <p class="mt-1 text-sm text-slate-500">Built for KNUST students</p>
        <p class="mt-3 text-sm font-bold text-slate-800">
            10% agent fee, only after a successful room placement.
        </p>
        <p class="mt-2">
            <a href="mailto:hello@elitealfaventures.com" class="text-sm text-slate-500 underline decoration-slate-300 underline-offset-2 hover:text-emerald-700 hover:decoration-emerald-300">
                hello@elitealfaventures.com
            </a>
        </p>
        <p class="mt-4 text-xs text-slate-500 sm:text-sm">
            &copy; {{ date('Y') }} RoomFinder, Kumasi
        </p>
    </footer>
</body>
</html>
