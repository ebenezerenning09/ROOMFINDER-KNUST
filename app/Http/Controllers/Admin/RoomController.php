<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomRequest;
use App\Http\Requests\Admin\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $rooms = Room::query()
            ->with('images')
            ->when($request->filled('keyword'), fn ($query) => $query->where('title', 'like', '%'.$request->string('keyword').'%'))
            ->when($request->filled('location'), fn ($query) => $query->where('location', $request->string('location')))
            ->when($request->filled('room_type'), fn ($query) => $query->where('room_type', $request->string('room_type')))
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->string('status') === 'published') {
                    $query->published();
                } elseif ($request->string('status') === 'draft') {
                    $query->unpublished();
                }
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $locations = Room::query()
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('admin.rooms.index', compact('rooms', 'locations'));
    }

    public function create(): View
    {
        return view('admin.rooms.form', [
            'room' => new Room,
        ]);
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $room = Room::query()->create($request->safe()->except(['images']));

        $this->storeUploadedImages($room, $request->file('images', []));

        return redirect()
            ->route('admin.rooms.edit', $room)
            ->with('success', 'Room created successfully.');
    }

    public function edit(Room $room): View
    {
        $room->load('images');

        return view('admin.rooms.form', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->safe()->except(['images']));

        $this->storeUploadedImages($room, $request->file('images', []));

        return redirect()
            ->route('admin.rooms.edit', $room)
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $this->deleteRoomImagesFromDisk($room);

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function togglePublished(Room $room): RedirectResponse
    {
        $room->update(['is_published' => ! $room->is_published]);

        return back()->with('success', 'Publish status updated.');
    }

    public function toggleVerified(Room $room): RedirectResponse
    {
        $room->update(['is_verified' => ! $room->is_verified]);

        return back()->with('success', 'Verified status updated.');
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>|null  $images
     */
    protected function storeUploadedImages(Room $room, ?array $images): void
    {
        if (empty($images)) {
            return;
        }

        $nextSortOrder = (int) $room->images()->max('sort_order') + 1;

        foreach ($images as $image) {
            $path = $image->store("rooms/{$room->id}", 'public');

            RoomImage::query()->create([
                'room_id' => $room->id,
                'path' => 'storage/'.$path,
                'sort_order' => $nextSortOrder++,
            ]);
        }
    }

    protected function deleteRoomImagesFromDisk(Room $room): void
    {
        $room->load('images');

        foreach ($room->images as $image) {
            $this->deleteImageFile($image->path);
        }
    }

    protected function deleteImageFile(string $path): void
    {
        if (! str_starts_with($path, 'storage/')) {
            return;
        }

        $storagePath = substr($path, strlen('storage/'));

        if ($storagePath !== '' && Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }
}
