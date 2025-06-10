<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Bepsvpt\SecureHeaders\SecureHeaders;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Appliquer les Secure Headers via la config
        app(SecureHeaders::class)->headers($response)->send();

        return $response;
    }
}
