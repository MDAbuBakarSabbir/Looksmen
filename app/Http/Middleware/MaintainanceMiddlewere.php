<?php

namespace App\Http\Middleware;

use App\Models\GeneralWebSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintainanceMiddlewere
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $webinfo = GeneralWebSettings::all();
        $maintainancemode = $webinfo->keyBy('name')->map(function ($item) {
            return [
                'value' => $item->value,
                'status' => $item->status,
            ];
        })->toArray();
        $maintainanceStatus = $maintainancemode['maintainance']['status'] == '1';
        if ($maintainanceStatus) {
            return redirect(route('maintainance.mode'));
        }

        return $next($request);
    }
}
