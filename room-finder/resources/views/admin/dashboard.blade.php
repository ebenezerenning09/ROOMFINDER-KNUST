@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
    <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
    <p class="mt-1 text-sm text-slate-500">Overview of your RoomFinder listings</p>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total rooms</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['total_rooms'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Published</p>
            <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $stats['published_rooms'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Verified</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['verified_rooms'] }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Registered users</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['total_users'] }}</p>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">Recent listings</h2>
            <a href="{{ route('admin.rooms.create') }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-emerald-700">
                Add room
            </a>
        </div>

        @if ($recentRooms->isEmpty())
            <p class="px-5 py-8 text-center text-sm text-slate-500">No rooms yet. Create your first listing.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Title</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Location</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Price</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Availability</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Status</th>
                            <th class="px-5 py-3 text-right font-medium text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentRooms as $room)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-900">{{ $room->title }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $room->location }}</td>
                                <td class="px-5 py-3 text-slate-600">GHS {{ number_format((float) $room->price, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ \App\Models\Room::availabilityBadgeClass($room->isFull()) }}">
                                        {{ $room->availabilityLabel() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap gap-1.5">
                                        @if ($room->is_published)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800">Published</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">Draft</span>
                                        @endif
                                        @if ($room->is_verified)
                                            <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">Verified</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="font-medium text-emerald-700 hover:text-emerald-800">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
