<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin', 'admin/*') && ! $request->is('admin/login')) {
                return route('admin.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // On failed login, keep the password field populated (Laravel strips it by default).
        // Scoped to the login routes only, so every other form keeps the secure default.
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->isMethod('post') && ($request->is('login') || $request->is('admin/login')) && ! $request->expectsJson()) {
                return redirect()->back()
                    ->withInput($request->input())
                    ->withErrors($e->errors(), $e->errorBag);
            }
        });
    })->create();
