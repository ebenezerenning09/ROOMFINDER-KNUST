<?php

use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/rooms');

Route::get('/favicon.ico', fn () => response()->file(public_path('favicon.png'), [
    'Content-Type' => 'image/png',
    'Cache-Control' => 'public, max-age=604800',
]));

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room:id}', [RoomController::class, 'show'])->name('rooms.show');
