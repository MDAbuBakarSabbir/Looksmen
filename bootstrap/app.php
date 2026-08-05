<?php

use App\Http\Middleware\AdminPermission;
use App\Http\Middleware\AffiliateTrackingMiddleware;
use App\Http\Middleware\BlockIpMiddleware;
use App\Http\Middleware\BlockUserMiddleware;
use App\Http\Middleware\EnsureCustomerEmailIsVerified;
use App\Http\Middleware\MaintainanceMiddlewere;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(BlockIpMiddleware::class);
        $middleware->append(BlockUserMiddleware::class);
        $middleware->append(AffiliateTrackingMiddleware::class);

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
            'admin.permission' => AdminPermission::class,
            'maintainance' => MaintainanceMiddlewere::class,
            'verified' => EnsureCustomerEmailIsVerified::class,
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
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Resource Not Found.'], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'webhook/whatsapp',
            'api/webhook/whatsapp',
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'webhook/whatsapp',
            'webhook/messenger',
        ]);
    })

    ->create();
