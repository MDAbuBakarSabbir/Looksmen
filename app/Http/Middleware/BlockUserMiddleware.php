<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BlockUserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If logged in under the 'web' (customer) guard and user is blocked
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->is_blocked) {
                // Log them out
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your account has been blocked by the administrator.'
                    ], 403);
                }

                return redirect()->route('login')->with('error', 'Your account has been blocked by the administrator.');
            }
        }

        return $next($request);
    }
}
