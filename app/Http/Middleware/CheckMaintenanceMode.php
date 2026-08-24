<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Setting::get('system.maintenance_mode', false)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'System is under maintenance. Please try again later.',
            ], 503);
        }

        abort(503, 'System is under maintenance. Please try again later.');
    }
}
