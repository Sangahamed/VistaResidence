<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class EnsureTwoFactorVerifiedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        if ($user) {
            // Récupérer les informations de l'appareil actuel
            $agent = new Agent();
            $currentDeviceInfo = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'device_type' => $agent->device(),
                'device_os' => $agent->platform(),
                'device_browser' => $agent->browser(),
                'device_resolution' => $request->header('sec-ch-ua-platform'),
                'device_language' => $request->getPreferredLanguage(),
            ];

            // Vérifier si c'est un nouvel appareil
            $isNewDevice = $user->last_login_ip !== $currentDeviceInfo['ip'] ||
                          $user->last_login_agent_user !== $currentDeviceInfo['user_agent'] ||
                          $user->device_type !== $currentDeviceInfo['device_type'] ||
                          $user->device_os !== $currentDeviceInfo['device_os'] ||
                          $user->device_browser !== $currentDeviceInfo['device_browser'] ||
                          $user->device_resolution !== $currentDeviceInfo['device_resolution'] ||
                          $user->device_language !== $currentDeviceInfo['device_language'];

            // Rediriger vers la page 2FA uniquement si c'est un nouvel appareil ET qu'un code 2FA est actif
            if ($isNewDevice && $user->two_factor_code && now()->lessThan($user->two_factor_expires_at)) {
                return redirect()->route('admin.two-factor.show');
            }
        }

        return $next($request);
    }
}