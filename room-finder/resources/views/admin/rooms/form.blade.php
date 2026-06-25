@extends('layouts.admin')

@section('title', $room->exists ? 'Edit Room' : 'Add Room')

@section('header')
    <h1 class="text-2xl font-bold text-slate-900">{{ $room->exists ? 'Edit room' : 'Add room' }}</h1>
    <p class="mt-1 text-sm text-slate-500">{{ $room->exists ? $room->title : 'Create a new hostel listing' }}</p>
@endsection

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.rooms.index') }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            &larr; Back to rooms
        </a>
        @if ($room->exists && $room->is_published)
            <span class="mx-2 text-slate-300">|</span>
            <a href="{{ route('rooms.show', $room) }}" target="_blank" rel="noopener" class="text-sm font-medium text-slate-600 hover:text-emerald-700">
                View on site
            </a>
        @endif
    </div>

    <form
        method="POST"
        action="{{ $room->exists ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @if ($room->exists)
            @method('PATCH')
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">Listing details</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-input-label for="title" value="Title" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $room->title)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    >{{ old('description', $room->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="price" value="Price (GHS)" />
                    <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $room->price)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                <div>
                    <x-input-label for="bedrooms" value="Bedrooms" />
                    <x-text-input id="bedrooms" name="bedrooms" type="number" min="1" max="20" class="mt-1 block w-full" :value="old('bedrooms', $room->bedrooms ?? 1)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('bedrooms')" />
                </div>

                <div>
                    <x-input-label for="location" value="Location" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $room->location)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>

                <div>
                    <x-input-label for="room_type" value="Room type" />
                    <select
                        id="room_type"
                        name="room_type"
                        required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    >
                        @foreach (\App\Models\Room::ROOM_TYPES as $type)
                            <option value="{{ $type }}" @selected(old('room_type', $room->room_type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('room_type')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="whatsapp" value="WhatsApp number" />
                    <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('whatsapp', $room->whatsapp)" placeholder="e.g. 0501234501" />
                    <p class="mt-1 text-xs text-slate-500">Used for the contact button on the listing page.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
                </div>

                <div class="sm:col-span-2 flex flex-wrap gap-6">
                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_published"
                            value="1"
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                            @checked(old('is_published', $room->exists ? $room->is_published : true))
                        >
                        <span class="text-sm font-medium text-slate-700">Published (visible on site)</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_verified"
                            value="1"
                            class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                            @checked(old('is_verified', $room->is_verified))
                        >
                        <span class="text-sm font-medium text-slate-700">Verified listing</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h2 class="mb-4 text-base font-semibold text-slate-900">Photos</h2>

            @if ($room->exists && $room->images->isNotEmpty())
                <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                    @foreach ($room->images as $image)
                        <div class="group relative overflow-hidden rounded-lg border border-slate-200">
                            <img
                                src="{{ $image->url() }}"
                                alt=""
                                class="aspect-[4/3] w-full object-cover"
                                onerror="this.onerror=null; this.src='{{ $room->fallbackImageUrl() }}';"
                            >
                            <form
                                method="POST"
                                action="{{ route('admin.rooms.images.destroy', [$room, $image]) }}"
                                class="absolute right-2 top-2"
                                onsubmit="return confirm('Remove this image?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="rounded-md bg-red-600 px-2 py-1 text-xs font-medium text-white opacity-90 hover:opacity-100"
                                >
                                    Remove
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

            <div>
                <x-input-label for="images" value="Upload images" />
                <input
                    type="file"
                    id="images"
                    name="images[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    class="mt-1 block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100"
                >
                <p class="mt-1 text-xs text-slate-500">JPEG, PNG, or WebP. Max 5 MB each.</p>
                <x-input-error class="mt-2" :messages="$errors->get('images')" />
                <x-input-error class="mt-2" :messages="$errors->get('images.*')" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-primary-button>{{ $room->exists ? 'Save changes' : 'Create room' }}</x-primary-button>
            <a href="{{ route('admin.rooms.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">Cancel</a>
        </div>
    </form>
@endsection
