<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ActivityLog;
use Toastr;

class ThrottleLogins
{
    public function handle($request, Closure $next)
    {
        // Récupérer le guard actuel
        $guard = Auth::getDefaultDriver();

        // Appliquer le throttling uniquement pour les guards 'web' et 'admins'
        if (in_array($guard, ['web', 'admins'])) {
            $executed = RateLimiter::attempt(
                'login-attempts:' . $guard . ':' . $request->ip(),
                $perMinute = 5,
                function() {}
            );

            if (!$executed) {
                ActivityLog::create([
                    'ip_address' => $request->ip(),
                    'action' => 'BRUTE_FORCE_BLOCKED',
                    'details' => 'Trop de tentatives de connexion'
                ]);

                Session::flash('fail', 'Trop de tentatives. Réessayez dans 1 minute.');

                return redirect()->back();
            }
        }

        return $next($request);
    }
}
