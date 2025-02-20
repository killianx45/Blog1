<?php

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Access Denied'], 403);
        }

        return $next($request);
    }
}
