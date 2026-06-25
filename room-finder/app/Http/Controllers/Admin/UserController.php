<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user) && ! $request->boolean('is_admin')) {
            return back()->with('error', 'You cannot remove your own admin access.');
        }

        $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);

        $user->update([
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return back()->with('success', 'User updated successfully.');
    }
}
