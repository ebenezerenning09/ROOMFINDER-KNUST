<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->guest(route('admin.login'));
        }

        if (! $request->user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}
