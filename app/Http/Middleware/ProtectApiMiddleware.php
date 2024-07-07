<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ProtectApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('API-Key');
        $expectedApiKey = env('API_KEY');
        $host = $request->getHost();

        // List of allowed domains or subdomains
        $allowedDomains = [
            "demo.elalameinfestival.com",
            "elalameinfestival.com",
        ];

        if (!in_array($host, $allowedDomains)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
