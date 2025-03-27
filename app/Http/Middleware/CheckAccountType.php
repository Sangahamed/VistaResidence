<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAccountType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        switch ($type) {
            case 'proprietaire':
                if (!$user->hasRole('particulier')) {
                    return redirect()->route('dashboard')->with('error', 'Accès réservé aux propriétaires');
                }
                break;
                
            case 'entreprise':
                if (!$user->hasRole('admin_entreprise')) {
                    return redirect()->route('dashboard')->with('error', 'Accès réservé aux entreprises');
                }
                break;
        }

        return $next($request);
    }
}
