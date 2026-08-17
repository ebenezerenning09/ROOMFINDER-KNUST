<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    public function store(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        $nextSortOrder = (int) $room->images()->max('sort_order') + 1;

        foreach ($request->file('images', []) as $image) {
            $path = $image->store("rooms/{$room->id}", 'public');

            RoomImage::query()->create([
                'room_id' => $room->id,
                'path' => 'storage/'.$path,
                'sort_order' => $nextSortOrder++,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy(Room $room, RoomImage $roomImage): RedirectResponse
    {
        if ($roomImage->room_id !== $room->id) {
            abort(404);
        }

        if (str_starts_with($roomImage->path, 'storage/')) {
            $storagePath = substr($roomImage->path, strlen('storage/'));

            if ($storagePath !== '' && Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
        }

        $roomImage->delete();

        return back()->with('success', 'Image removed successfully.');
    }
}
