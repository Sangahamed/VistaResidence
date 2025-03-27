<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifie si l'utilisateur a un des rôles autorisés
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Redirection en fonction du rôle actuel
        if ($user->hasRole('admin_entreprise')) {
            return redirect()->route('entreprise.dashboard');
        } elseif ($user->hasRole('particulier')) {
            return redirect()->route('proprietaire.dashboard');
        }

        // Par défaut pour les clients
        return redirect()->route('dashboard');
    }
}
