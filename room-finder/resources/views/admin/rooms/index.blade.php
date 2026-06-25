@extends('layouts.admin')

@section('title', 'Rooms')

@section('header')
    <h1 class="text-2xl font-bold text-slate-900">Rooms</h1>
    <p class="mt-1 text-sm text-slate-500">Manage hostel listings</p>
@endsection

@section('content')
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.rooms.index') }}" class="flex flex-1 flex-wrap items-end gap-3">
            <div class="min-w-[10rem] flex-1">
                <label for="keyword" class="mb-1 block text-xs font-medium text-slate-600">Search</label>
                <input
                    type="search"
                    name="keyword"
                    id="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Title..."
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                >
            </div>
            <div class="min-w-[8rem]">
                <label for="location" class="mb-1 block text-xs font-medium text-slate-600">Location</label>
                <select name="location" id="location" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[8rem]">
                <label for="room_type" class="mb-1 block text-xs font-medium text-slate-600">Type</label>
                <select name="room_type" id="room_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    @foreach (\App\Models\Room::ROOM_TYPES as $type)
                        <option value="{{ $type }}" @selected(request('room_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[8rem]">
                <label for="status" class="mb-1 block text-xs font-medium text-slate-600">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Filter</button>
            @if (request()->hasAny(['keyword', 'location', 'room_type', 'status']))
                <a href="{{ route('admin.rooms.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.rooms.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Add room
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($rooms->isEmpty())
            <p class="px-5 py-12 text-center text-sm text-slate-500">No rooms match your filters.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Listing</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Location</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Type</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Availability</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Price</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Status</th>
                            <th class="px-5 py-3 text-right font-medium text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rooms as $room)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ $room->imageUrl() }}"
                                            alt=""
                                            class="h-10 w-14 shrink-0 rounded object-cover"
                                            onerror="this.onerror=null; this.src='{{ $room->fallbackImageUrl() }}';"
                                        >
                                        <span class="font-medium text-slate-900">{{ $room->title }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $room->location }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ \App\Models\Room::roomTypeBadgeClass($room->room_type) }}">
                                        {{ $room->room_type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ \App\Models\Room::availabilityBadgeClass($room->isFull()) }}">
                                        {{ $room->availabilityLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-600">GHS {{ number_format((float) $room->price, 2) }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <form method="POST" action="{{ route('admin.rooms.toggle-published', $room) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $room->is_published ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                                {{ $room->is_published ? 'Published' : 'Draft' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.rooms.toggle-verified', $room) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $room->is_verified ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                                {{ $room->is_verified ? 'Verified' : 'Unverified' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        @if ($room->is_published)
                                            <a href="{{ route('rooms.show', $room) }}" target="_blank" rel="noopener" class="text-slate-500 hover:text-slate-700" title="View on site">View</a>
                                        @endif
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="font-medium text-emerald-700 hover:text-emerald-800">Edit</a>
                                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" class="inline" onsubmit="return confirm('Delete this room? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
@endsection
