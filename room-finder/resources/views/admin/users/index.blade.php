@extends('layouts.admin')

@section('title', 'Users')

@section('header')
    <h1 class="text-2xl font-bold text-slate-900">Users</h1>
    <p class="mt-1 text-sm text-slate-500">Registered accounts on RoomFinder</p>
@endsection

@section('content')
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($users->isEmpty())
            <p class="px-5 py-12 text-center text-sm text-slate-500">No users registered yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Name</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Email</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Joined</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-600">Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-900">
                                    {{ $user->name }}
                                    @if ($user->is(Auth::user()))
                                        <span class="ml-1 text-xs font-normal text-slate-500">(you)</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $user->email }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_admin" value="0">
                                        <label class="inline-flex cursor-pointer items-center gap-2">
                                            <input
                                                type="checkbox"
                                                name="is_admin"
                                                value="1"
                                                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                                @checked($user->is_admin)
                                                onchange="this.form.submit()"
                                                @disabled($user->is(Auth::user()))
                                            >
                                            <span class="text-sm text-slate-600">
                                                {{ $user->is_admin ? 'Admin' : 'User' }}
                                            </span>
                                        </label>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
