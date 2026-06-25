<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $rooms = Room::query()
            ->published()
            ->with('images')
            ->when($request->filled('keyword'), fn ($query) => $query->where('title', 'like', '%'.$request->string('keyword').'%'))
            ->when($request->filled('location'), fn ($query) => $query->where('location', $request->string('location')))
            ->when($request->filled('room_type'), fn ($query) => $query->where('room_type', $request->string('room_type')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $locations = Room::query()
            ->published()
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('rooms.index', [
            'rooms' => $rooms,
            'locations' => $locations,
        ]);
    }

    public function show(Room $room): View
    {
        if (! $room->is_published) {
            abort(404);
        }

        $room->load('images');

        return view('rooms.show', compact('room'));
    }
}
