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
    <style>
        .admin-login-input {
            background-color: #1e293b;
            border: 1px solid #475569;
            color: #f8fafc;
        }
        .admin-login-input::placeholder {
            color: #94a3b8;
        }
        .admin-login-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }
        .admin-login-input:-webkit-autofill,
        .admin-login-input:-webkit-autofill:hover,
        .admin-login-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #f8fafc;
            -webkit-box-shadow: 0 0 0 1000px #1e293b inset;
            box-shadow: 0 0 0 1000px #1e293b inset;
            border: 1px solid #475569;
        }
    </style>
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-4 py-10 text-slate-100 antialiased">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex justify-center">
                <x-roomfinder-logo variant="icon" class="h-11 w-11 sm:h-12 sm:w-12" />
            </div>
            <h1 class="sr-only">RoomFinder Admin</h1>
            <p class="text-lg font-semibold text-white" aria-hidden="true">Admin</p>
            <p class="mt-1 text-sm text-slate-400">Sign in to manage listings and users</p>
        </div>

        <div class="rounded-2xl border border-slate-700/80 bg-slate-800/90 p-6 shadow-2xl backdrop-blur sm:p-8">
            @yield('content')
        </div>

        <p class="mt-6 text-center text-sm text-slate-500">
            <a href="{{ route('rooms.index') }}" class="text-slate-400 transition hover:text-emerald-400">Back to public site</a>
        </p>
    </div>
</body>
</html>
