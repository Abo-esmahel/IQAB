<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! $request->user()->isActive()) {
            auth()->logout();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account has been suspended.'], 401);
            }

            return redirect()->route('login')->with('error', 'Your account has been suspended.');
        }

        return $next($request);
    }
}
