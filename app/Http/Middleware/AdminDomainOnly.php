<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminDomainOnly
{
    /**
     * Handle an incoming request.
     * Admin routes are only accessible from the configured admin domain.
     * Domain is read from DB first (admin-configurable), then falls back to .env config.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Always allow on local development environment
        if (app()->environment('local')) {
            return $next($request);
        }

        // ✅ Always allow localhost / 127.0.0.1 access (dev server, artisan serve)
        $localHosts = ['localhost', '127.0.0.1', '::1'];
        if (in_array($request->getHost(), $localHosts)) {
            return $next($request);
        }

        // Try to get admin domain from DB (admin-configurable via UI)
        $adminDomain = $this->getAdminDomain();

        // If no admin domain is configured, allow everything
        if (empty($adminDomain)) {
            return $next($request);
        }

        $currentHost = $request->getHost();

        // If request is not from the admin domain, redirect to admin domain
        if ($currentHost !== $adminDomain) {
            $scheme = $request->secure() ? 'https' : 'http';
            $redirectUrl = $scheme . '://' . $adminDomain . $request->getRequestUri();
            return redirect($redirectUrl, 302);
        }

        return $next($request);
    }

    /**
     * Resolve the admin domain.
     * Priority: DB setting → .env config → empty (allow all).
     */
    private function getAdminDomain(): string
    {
        try {
            // DB setting takes priority (set via admin UI)
            $dbSetting = DB::table('general_web_settings')
                ->where('name', 'admin_domain')
                ->where('status', 1)
                ->value('value');

            if (!empty($dbSetting)) {
                return trim($dbSetting);
            }
        } catch (\Exception $e) {
            // DB might not be available (e.g., during migration) — fall through
        }

        // Fall back to config / .env
        return config('app.admin_domain', '');
    }
}
