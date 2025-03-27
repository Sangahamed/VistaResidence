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
        $response = $next($request);

        // Définit les en-têtes pour empêcher la mise en cache
        $headers = [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Thu, 04 Jun 2000 00:00:00 GMT',
        ];

        // Ajoute un script JavaScript pour bloquer la navigation arrière
        $script = <<<SCRIPT
        <script>
            history.pushState(null, document.title, location.href);
            window.addEventListener('popstate', function (event) {
                history.pushState(null, document.title, location.href);
                alert('Vous ne pouvez pas revenir en arrière.');
            });
        </script>
        SCRIPT;

        // Ajoute le script à la réponse
        $content = $response->getContent();
        $content = str_replace('</body>', $script . '</body>', $content);
        $response->setContent($content);

        // Applique les en-têtes à la réponse
        return $response->withHeaders($headers);
    }
}