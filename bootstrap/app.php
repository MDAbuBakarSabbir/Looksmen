<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\BlockIpMiddleware::class);
        $middleware->append(\App\Http\Middleware\BlockUserMiddleware::class);
        $middleware->append(\App\Http\Middleware\AffiliateTrackingMiddleware::class);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            return route('dashboard');
        });

        $middleware->alias([
            'admin.permission' => \App\Http\Middleware\AdminPermission::class,
            'maintainance' => \App\Http\Middleware\MaintainanceMiddlewere::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/ssl/success',
            '/ssl/fail',
            '/ssl/cancel',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
