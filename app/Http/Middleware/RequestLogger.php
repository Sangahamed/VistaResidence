<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\User;

class RequestLogger
{
    public function handle($request, Closure $next)
    {
        Log::debug('Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'session' => $request->session()->all()
        ]);

        return $next($request);
    }
}