<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsWhitelist
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = [
            'https://tirtheasy.com',
            'http://65.2.23.163:3000',
            'http://localhost:3000',
        ];

        $origin = $request->headers->get('Origin');

        if ($origin && in_array($origin, $allowedOrigins, true)) {
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true',
            ];

            if ($request->getMethod() === 'OPTIONS') {
                return response()->json('OK', 200, $headers);
            }

            $response = $next($request);
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }

        // Block requests from non-whitelisted origins
        return response()->json(['error' => 'Forbidden - Origin not allowed'], 403);
    }
}