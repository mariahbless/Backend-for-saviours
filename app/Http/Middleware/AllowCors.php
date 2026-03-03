<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowCors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If this is an OPTIONS request, respond immediately
        if ($request->getMethod() === "OPTIONS") {
            $response = response()->json([], 200);
        } else {
            $response = $next($request);
        }

        // Add CORS headers
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:58724'); // Flutter web origin
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        return $response;
    }
}