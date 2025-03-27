<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Passe la requête au prochain middleware ou au contrôleur
        $response = $next($request);

        // Définit les en-têtes pour empêcher la mise en cache
        $headers = [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate', // Empêche la mise en cache
            'Pragma'        => 'no-cache', // Compatibilité avec HTTP/1.0
            'Expires'       => 'Thu, 04 Jun 2000 00:00:00 GMT', // Date dans le passé pour invalider le cache
            'Vary'          => 'User-Agent', // Indique que la réponse varie en fonction de l'User-Agent
        ];

        // Applique les en-têtes à la réponse
        return $response->withHeaders($headers);
    }
}