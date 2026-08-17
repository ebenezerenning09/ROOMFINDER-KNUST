<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/favicon.ico', fn () => response()->file(public_path('favicon.png'), [
    'Content-Type' => 'image/png',
    'Cache-Control' => 'public, max-age=604800',
]));

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room:slug}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/sitemap.xml', function () {
    $rooms = \App\Models\Room::query()->published()->latest('updated_at')->get(['slug', 'updated_at']);

    return response()
        ->view('sitemap', ['rooms' => $rooms])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/llms.txt', function () {
    $rooms = \App\Models\Room::query()->published()->latest()->get();

    return response()
        ->view('llms', ['rooms' => $rooms])
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('llms');

Route::get('/dashboard', function () {
    if (auth()->user()?->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('rooms.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
