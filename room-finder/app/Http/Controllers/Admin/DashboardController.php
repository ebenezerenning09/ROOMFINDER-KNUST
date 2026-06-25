<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_rooms' => Room::query()->count(),
            'published_rooms' => Room::query()->published()->count(),
            'verified_rooms' => Room::query()->verified()->count(),
            'total_users' => User::query()->count(),
        ];

        $recentRooms = Room::query()
            ->with('images')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRooms'));
    }
}
