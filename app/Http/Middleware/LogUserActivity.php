<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Log des pages consultées (sauf les assets, api, etc.)
        if (!$this->shouldSkip($request) && Auth::check()) {
            ActivityLogger::log(
                'page_viewed',
                null,
                'Page consultée: ' . $request->fullUrl(),
                ['route' => $request->route()->getName()]
            );
        }
        
        return $response;
    }
    
    private function shouldSkip(Request $request)
    {
        $skipPaths = [
            'api/*',
            '_debugbar/*',
            'livewire/*',
            '*/js/*',
            '*/css/*',
            '*/images/*',
            '*/fonts/*',
        ];
        
        foreach ($skipPaths as $path) {
            if ($request->is($path)) {
                return true;
            }
        }
        
        return false;
    }
}