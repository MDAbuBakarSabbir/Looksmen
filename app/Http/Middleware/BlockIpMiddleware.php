<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        // Safely check if blocked_ips table exists first to avoid crash during migrations
        if (Schema::hasTable('blocked_ips')) {
            $isBlocked = DB::table('blocked_ips')->where('ip_address', $ip)->exists();

            if ($isBlocked) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your IP address has been blocked by the administrator.'
                    ], 403);
                }
                abort(403, 'Your IP address has been blocked by the administrator.');
            }
        }

        return $next($request);
    }
}
