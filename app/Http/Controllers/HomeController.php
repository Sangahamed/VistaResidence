<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\SavedSearch;
use App\Models\UserLocation;
use App\Services\{
    PropertySearchService,
    PropertyViewService,
    GeoLocationService,
    PropertyRecommendationService,
    RecommendationService
};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\EnhancedGeoLocationService;
use Illuminate\Support\Facades\Cache;



class HomeController extends Controller
{

    public function __construct(
        protected PropertySearchService $searchService,
        protected PropertyViewService $viewService,
        protected GeoLocationService $geoService,
        protected RecommendationService $recommendationService
    ) {}

    public function index(Request $request)
    {
        $user = auth()->user();
       $position = $this->getUserPosition($request);

       // 2. Chargement initial rapide
    $properties = $this->getInitialProperties($request, $position);
    
    // 3. Cache pour les listes déroulantes
    $propertyTypes = Cache::remember('property_types', 3600, function() {
        return Property::distinct()->pluck('type');
    });
    
    $propertyCities = Cache::remember('property_cities', 3600, function() {
        return Property::distinct()->pluck('city');
    });

            // Récupérer le feed personnalisé
          $properties = $this->recommendationService->getTikTokStyleFeed($user);


        return view('front.pages.index', [
            'properties' => $properties,
            'propertyTypes' => Property::distinct()->pluck('type'),
            'propertycity' => Property::distinct()->pluck('city'),
            'isSearching' => $request->filled(['search', 'type', 'city', 'price_min', 'price_max'])
        ]);
}

protected function getInitialProperties(Request $request, ?array $position)
{
    // 1. Si recherche active, prioriser les résultats
    if ($request->filled(['search', 'type', 'city', 'price_min', 'price_max'])) {
        return $this->searchService->search(
            $request->all(),
            $position
        )->paginate(20);
    }

    // 2. Sinon, recommandations personnalisées
    return $this->recommendationService->getTikTokStyleFeed(
        auth()->user(),
        $position,
        20
    );
}

    protected function getUserPosition(Request $request): ?array
    {
        // Priorité 1 : Géolocalisation manuelle
        if ($request->has(['lat', 'lng'])) {
            return [
                'lat' => $request->lat,
                'lng' => $request->lng,
                'source' => 'manual'
            ];
        }

        // Priorité 2 : Géolocalisation navigateur
        if ($request->header('X-Geo-Location')) {
            $geo = json_decode($request->header('X-Geo-Location'), true);
            return [
                'lat' => $geo['latitude'],
                'lng' => $geo['longitude'],
                'source' => 'browser'
            ];
        }

        // Nouveau service de géolocalisation
        $geoService = new EnhancedGeoLocationService();
        return $geoService->getLocationFromIP();
    }

    protected function getIpLocation(): ?array
{
    try {
        $response = Http::get('https://ipapi.co/json/');
        if ($response->successful()) {
            $data = $response->json();
            return [
                'lat' => $data['latitude'] ?? null,
                'lng' => $data['longitude'] ?? null,
                'accuracy' => $geo['accuracy'] ?? 10000,
                'source' => 'ip'
            ];
        }
    } catch (\Exception $e) {
        Log::error("Échec récupération position IP : " . $e->getMessage());
    }

    return null;
}
   

public function storePosition(Request $request)
{
    $user = auth()->user();

    // Vérifier si la position a changé de manière significative
    $currentPosition = session('user_position');
    if ($currentPosition &&
        abs($currentPosition['lat'] - $request->lat) < 0.01 &&
        abs($currentPosition['lng'] - $request->lng) < 0.01) {
        return response()->json(['status' => 'unchanged']);
    }

    // Mettre à jour la session
    session(['user_position' => ['lat' => $request->lat, 'lng' => $request->lng]]);

    return response()->json(['status' => 'updated']);
}

    protected function normalizeSearchParams(Request $request): array
    {
        return $request->validate([
            'search' => 'nullable|string',
            'type' => 'nullable|string',
            'city' => 'nullable|string',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'radius' => 'nullable|numeric|max:50'
        ]);
    }

    protected function getContextualResults(Request $request, array $params)
    {
        // Priorité 1 : Géolocalisation explicite
        if ($request->has(['lat', 'lng'])) {
            return $this->recommendationService->getNearbyProperties(
                $params['lat'],
                $params['lng'],
                $params['radius'] ?? 10
            );
        }

        // Priorité 2 : Localisation utilisateur enregistrée
        if (auth()->check() && $location = auth()->user()->location) {
            return $this->recommendationService->getNearbyProperties(
                $location->lat,
                $location->lng
            );
        }

        // Priorité 3 : Recommandations basées sur l'historique
        return auth()->check() 
            ? $this->recommendationService->getPersonalizedRecommendations(auth()->user())
            : collect();
    }

    protected function mergeResults($mainResults, $contextualResults)
    {
        if ($contextualResults->isEmpty()) {
            return $mainResults;
        }

        return $mainResults->whereIn('id', $contextualResults->pluck('id'))
            ->union(
                $mainResults->whereNotIn('id', $contextualResults->pluck('id'))
            );
    }

    protected function getSavedSearches()
    {
        return auth()->check()
            ? auth()->user()->savedSearches()->latest()->limit(5)->get()
            : SavedSearch::forSession(session()->getId())->latest()->limit(5)->get();
    }

   // HomeController.php - Méthode detail
public function detail(Property $property)
{
    // Enregistrer la vue
    $this->viewService->recordPropertyView(
        $property->id,
        auth()->id(),
        session()->getId()
    );

    $data = [
        'pageTitle' => $property->title,
        'property' => $property->load(['owner', 'features']),
        'similar' => $this->recommendationService->getSimilarProperties($property, 6),
        'PropertiesCount' => Property::where('owner_id', $property->owner_id)->count(),
        'trendingProperties' => $this->recommendationService->getTrendingProperties(4),
    ];

    return view('front.pages.detail', $data);
}
}