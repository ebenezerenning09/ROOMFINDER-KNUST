@extends('layouts.admin')

@section('title', 'Rooms')

@section('header')
    <h1 class="text-2xl font-bold text-slate-900">Rooms</h1>
    <p class="mt-1 text-sm text-slate-500">Manage hostel listings</p>
@endsection

@section('content')
    <div x-data="{
        loading: false,
        buildUrl() {
            const form = this.$refs.filterForm;
            const qs = new URLSearchParams(new FormData(form)).toString();
            return form.action + (qs ? ('?' + qs) : '');
        },
        apply() { this.goTo(this.buildUrl()); },
        async goTo(url) {
            this.loading = true;
            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const fresh = doc.getElementById('rooms-results');
                if (fresh) { document.getElementById('rooms-results').innerHTML = fresh.innerHTML; }
                window.history.replaceState({}, '', url);
            } finally {
                this.loading = false;
            }
        }
    }">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form x-ref="filterForm" method="GET" action="{{ route('admin.rooms.index') }}" @submit.prevent="apply()" class="flex flex-1 flex-wrap items-end gap-3">
            <div class="min-w-[10rem] flex-1">
                <label for="keyword" class="mb-1 block text-xs font-medium text-slate-600">Search</label>
                <input
                    type="search"
                    name="keyword"
                    id="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Title..."
                    autocomplete="off"
                    @input.debounce.450ms="apply()"
                    @search="apply()"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                >
            </div>
            <div class="min-w-[8rem]">
                <label for="location" class="mb-1 block text-xs font-medium text-slate-600">Location</label>
                <select name="location" id="location" @change="apply()" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[8rem]">
                <label for="room_type" class="mb-1 block text-xs font-medium text-slate-600">Type</label>
                <select name="room_type" id="room_type" @change="apply()" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    @foreach (\App\Models\Room::ROOM_TYPES as $type)
                        <option value="{{ $type }}" @selected(request('room_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[8rem]">
                <label for="status" class="mb-1 block text-xs font-medium text-slate-600">Status</label>
                <select name="status" id="status" @change="apply()" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">All</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </div>
            {{-- Filters apply automatically; button kept as a no-JS fallback. --}}
            <noscript>
                <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Filter</button>
            </noscript>
            @if (request()->hasAny(['keyword', 'location', 'room_type', 'status']))
                <a href="{{ route('admin.rooms.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.rooms.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
            Add room
        </a>
    </div>

    <div
        id="rooms-results"
        @click="const a = $event.target.closest('a'); if (a && a.closest('nav')) { $event.preventDefault(); goTo(a.href); }"
        :class="loading ? 'opacity-50 pointer-events-none transition' : 'transition'"
        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
    >
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
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.rooms.toggle-published', $room) }}" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    role="switch"
                                                    aria-checked="{{ $room->is_published ? 'true' : 'false' }}"
                                                    title="{{ $room->is_published ? 'Published — click to unpublish' : 'Draft — click to publish' }}"
                                                    class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 {{ $room->is_published ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                                >
                                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $room->is_published ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                                                </button>
                                            </form>
                                            <span class="text-xs font-medium {{ $room->is_published ? 'text-emerald-700' : 'text-slate-500' }}">
                                                {{ $room->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('admin.rooms.toggle-verified', $room) }}" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    role="switch"
                                                    aria-checked="{{ $room->is_verified ? 'true' : 'false' }}"
                                                    title="{{ $room->is_verified ? 'Verified — click to unverify' : 'Unverified — click to verify' }}"
                                                    class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-1 {{ $room->is_verified ? 'bg-amber-500' : 'bg-slate-300' }}"
                                                >
                                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $room->is_verified ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                                                </button>
                                            </form>
                                            <span class="text-xs font-medium {{ $room->is_verified ? 'text-amber-700' : 'text-slate-500' }}">
                                                {{ $room->is_verified ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </div>
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
    </div>
@endsection
