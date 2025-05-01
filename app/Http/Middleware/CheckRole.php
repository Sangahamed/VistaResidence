<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur a l'un des rôles requis
        foreach ($roles as $role) {
            // Méthodes de vérification de rôle
            $method = 'is' . str_replace('_', '', ucwords($role, '_'));
            
            if (method_exists($request->user(), $method) && $request->user()->$method()) {
                return $next($request);
            }
            
            // Vérifier également dans la table des rôles
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // Si l'utilisateur n'a aucun des rôles requis
        abort(403, 'Accès non autorisé.');
    }
}