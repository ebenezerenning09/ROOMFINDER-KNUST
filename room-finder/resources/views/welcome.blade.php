<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RoomFinder — Student Housing in Kumasi</title>
    <link rel="icon" href="{{ asset('favicon.png') }}?v=4" type="image/png">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin:0;">
    {{-- One photo only — real img tag, no CSS background tiling --}}
    <div style="position:fixed;inset:0;z-index:0;overflow:hidden;">
        <img
            src="{{ asset('images/knust-campus-hero.jpg') }}?v=5"
            alt="KNUST campus"
            fetchpriority="high"
            decoding="async"
            style="display:block;width:100%;height:100%;object-fit:cover;object-position:center;"
        >
    </div>
    <div style="position:fixed;inset:0;z-index:1;background:rgba(15,23,42,0.45);"></div>

    <div style="position:relative;z-index:2;min-height:100vh;display:flex;flex-direction:column;">
        <div class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-center sm:px-6">
            <p class="text-sm font-bold text-amber-950 sm:text-base">
                10% agent fee applies after we successfully help you find a room.
            </p>
            <p class="mt-0.5 text-xs text-amber-800 sm:text-sm">
                Charged only when your hostel deal is confirmed — not before.
            </p>
        </div>

        <main class="flex flex-1 items-center justify-center px-6 py-14 sm:px-10 sm:py-16">
            <div class="w-full max-w-md rounded-2xl bg-white p-10 shadow-2xl sm:p-14">
                <div class="text-center">
                    <x-roomfinder-logo variant="icon" class="mx-auto h-11 w-11 sm:h-12 sm:w-12" />
                    <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">RoomFinder</h1>
                    <p class="mt-1 text-sm font-medium text-emerald-700">Find Your Next Home</p>
                </div>

                <p class="mt-6 text-base leading-relaxed text-slate-600">
                    Find verified hostel rooms near KNUST. Browse freely — sign in to contact the RoomFinder admin.
                </p>

                <div class="mt-10 flex flex-col gap-4">
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg bg-emerald-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-emerald-700"
                    >
                        Log in
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg border-2 border-emerald-600 px-6 py-3 text-base font-semibold text-emerald-700 hover:bg-emerald-50"
                    >
                        Register
                    </a>
                    <a
                        href="{{ route('rooms.index') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg bg-slate-100 px-6 py-3 text-base font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Browse without an account
                    </a>
                </div>
            </div>
        </main>

        <footer class="bg-slate-900 px-4 py-5 text-center text-sm text-slate-200 sm:px-6">
            &copy; {{ date('Y') }} RoomFinder — Built for KNUST students
        </footer>
    </div>
</body>
</html>
