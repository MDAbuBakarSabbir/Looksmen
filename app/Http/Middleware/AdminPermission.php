<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth()->guard('admin')->user();

        $permissions = array_map('trim', explode(',', $permission));
        $hasPermission = false;

        if ($user) {
            foreach ($permissions as $p) {
                if ($user->hasPermission($p)) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        if (!$user || !$hasPermission) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this action.'
                ], 403);
            }
            abort(403, 'You do not have permission to access this operation.');
        }

        return $next($request);
    }
}
