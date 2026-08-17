@extends('layouts.app')

@section('title', 'Browse Rooms')
@section('meta_description', 'Browse verified student hostel rooms around KNUST, Kumasi. Filter by location, room type and price. 1in1, 2in1, 3in1, 4in1 and homestay listings priced per academic year.')

@push('head')
    @php
        $ldList = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Student hostels near KNUST, Kumasi',
            'itemListElement' => $rooms->map(fn ($room, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => route('rooms.show', $room),
                'name' => $room->title,
            ])->values()->all(),
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($ldList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl font-bold text-slate-900 sm:text-2xl md:text-3xl">Find your hostel</h1>
        <p class="mt-1 text-sm text-slate-600 sm:text-base">Browse student hostels around KNUST, Kumasi.</p>
    </div>

    <form method="GET" action="{{ route('rooms.index') }}" class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:mb-8 sm:p-5">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-3 lg:grid-cols-4">
            <div class="sm:col-span-2 lg:col-span-1">
                <label for="keyword" class="mb-1.5 block text-sm font-medium text-slate-700">Keyword</label>
                <input
                    type="search"
                    name="keyword"
                    id="keyword"
                    value="{{ old('keyword', request('keyword')) }}"
                    placeholder="Search by title..."
                    class="w-full min-h-11 rounded-lg border border-slate-300 px-3 py-3 text-base shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:min-h-0 sm:py-2 sm:text-sm"
                >
            </div>

            <div class="grid grid-cols-2 gap-3 sm:contents">
                <div>
                    <label for="location" class="mb-1.5 block text-sm font-medium text-slate-700">Location</label>
                    <select
                        name="location"
                        id="location"
                        class="w-full min-h-11 rounded-lg border border-slate-300 px-3 py-3 text-base shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:min-h-0 sm:py-2 sm:text-sm"
                    >
                        <option value="">All locations</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location }}" @selected(old('location', request('location')) === $location)>{{ $location }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="room_type" class="mb-1.5 block text-sm font-medium text-slate-700">Room type</label>
                    <select
                        name="room_type"
                        id="room_type"
                        class="w-full min-h-11 rounded-lg border border-slate-300 px-3 py-3 text-base shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 sm:min-h-0 sm:py-2 sm:text-sm"
                    >
                        <option value="">All types</option>
                        @foreach (\App\Models\Room::ROOM_TYPES as $type)
                            <option value="{{ $type }}" @selected(old('room_type', request('room_type')) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:col-span-2 sm:flex-row sm:items-end lg:col-span-1">
                <button
                    type="submit"
                    class="min-h-11 w-full touch-manipulation rounded-lg bg-emerald-600 px-4 py-3 text-base font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:bg-emerald-800 sm:min-h-0 sm:flex-1 sm:py-2 sm:text-sm"
                >
                    Search
                </button>
                @if (request()->hasAny(['keyword', 'location', 'room_type']))
                    <a
                        href="{{ route('rooms.index') }}"
                        class="inline-flex min-h-11 w-full touch-manipulation items-center justify-center rounded-lg border border-slate-300 px-4 py-3 text-base font-medium text-slate-700 transition hover:bg-slate-50 active:bg-slate-100 sm:min-h-0 sm:w-auto sm:py-2 sm:text-sm"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    @if ($rooms->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-4 py-12 text-center sm:px-6 sm:py-16">
            <p class="text-base font-medium text-slate-700 sm:text-lg">No hostels found</p>
            <p class="mt-1 text-sm text-slate-500">Try adjusting your filters or search term.</p>
        </div>
    @else
        <p class="mb-4 text-sm text-slate-600">
            {{ $rooms->total() }} {{ Str::plural('hostel', $rooms->total()) }} found
        </p>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
            @foreach ($rooms as $room)
                <a
                    href="{{ route('rooms.show', $room) }}"
                    class="group flex touch-manipulation flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:border-emerald-300 hover:shadow-md active:border-emerald-400 active:bg-slate-50"
                >
                    <img
                        src="{{ $room->imageUrl() }}"
                        alt="{{ $room->title }}"
                        class="h-48 w-full shrink-0 object-cover"
                        loading="lazy"
                        referrerpolicy="no-referrer"
                        onerror="this.onerror=null; this.src='{{ $room->fallbackImageUrl() }}';"
                    >

                    <div class="flex min-w-0 flex-1 flex-col p-3 sm:p-4">
                        <div class="mb-1.5 flex flex-wrap items-center gap-1.5 sm:mb-2 sm:gap-2">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium sm:px-2.5 {{ \App\Models\Room::roomTypeBadgeClass($room->room_type) }}">
                                {{ $room->room_type }}
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium sm:px-2.5 {{ \App\Models\Room::availabilityBadgeClass($room->isFull()) }}">
                                {{ $room->availabilityLabel() }}
                            </span>
                            <span class="truncate text-xs text-slate-500">{{ $room->location }}</span>
                        </div>

                        <h2 class="line-clamp-2 text-sm font-semibold leading-snug text-slate-900 group-hover:text-emerald-700 sm:text-base">
                            {{ $room->title }}
                        </h2>

                        <p class="mt-1 text-base font-bold leading-snug text-green-700 sm:text-lg">
                            GHS {{ number_format($room->price, 2) }} / academic year
                        </p>

                        @if ($room->images->count() > 1)
                            <p class="mt-1 text-xs text-slate-500">{{ $room->images->count() }} photos</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6 sm:mt-8">
            {{ $rooms->links() }}
        </div>
    @endif
@endsection
