<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\PropertySearchService;

class TransferGuestSearches
{
    protected $searchService;

    public function __construct(PropertySearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check() && !session()->has('searches_transferred')) {
            $this->searchService->transferSessionSearches(auth()->user());
            session()->put('searches_transferred', true);
        }

        return $response;
    }
}