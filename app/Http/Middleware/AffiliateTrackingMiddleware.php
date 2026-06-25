<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AffiliateTrackingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $ref = $request->query('ref');
            // Save referral code in cookie for 30 days
            $response = $next($request);
            return $response->withCookie(cookie('referral_code', $ref, 60 * 24 * 30));
        }

        return $next($request);
    }
}
