<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::patch('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
        Route::patch('/rooms/{room}/toggle-published', [RoomController::class, 'togglePublished'])->name('rooms.toggle-published');
        Route::patch('/rooms/{room}/toggle-verified', [RoomController::class, 'toggleVerified'])->name('rooms.toggle-verified');

        Route::post('/rooms/{room}/images', [RoomImageController::class, 'store'])->name('rooms.images.store');
        Route::delete('/rooms/{room}/images/{roomImage}', [RoomImageController::class, 'destroy'])->name('rooms.images.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});
