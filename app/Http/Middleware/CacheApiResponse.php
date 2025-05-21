<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheApiResponse
{
    public function handle(Request $request, Closure $next, $ttl = '300')
    {
        $key = 'api:'.$request->fingerprint();
        
        return cache()->remember($key, now()->addSeconds($ttl), function () use ($next, $request) {
            return $next($request);
        });
    }
}