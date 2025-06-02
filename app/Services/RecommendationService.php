<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\UserPreference;
use App\Models\SavedSearch;
use App\Models\Favorite;
use App\Models\UserLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class RecommendationService
{
    protected EnhancedGeoLocationService $geoService;
    
    public function __construct(EnhancedGeoLocationService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Génère un feed personnalisé style TikTok avec algorithme avancé
     */
    public function getTikTokStyleFeed(User $user = null, array $position = null, int $limit = 20): EloquentCollection
    {
        $position = $position ?? $this->getUserPosition($user);
        $feed = collect();
        $excludedIds = collect();

        // 1. Propriétés proches géographiquement (35%)
        $nearbyCount = (int)($limit * 0.35);
        $nearby = $this->getNearbyProperties(
            $position['lat'] ?? 5.3543,
            $position['lng'] ?? -4.0016,
            $this->calculateDynamicRadius($position),
            $nearbyCount
        )->each(fn($p) => $p->recommendation_source = 'nearby');
        
        $feed = $feed->merge($nearby);
        $excludedIds = $excludedIds->merge($nearby->pluck('id'));

        // 2. Propriétés virales/populaires (25%)
        $viralCount = (int)($limit * 0.25);
        $viral = $this->getViralProperties($viralCount, $excludedIds->toArray())
            ->each(fn($p) => $p->recommendation_source = 'viral');
        
        $feed = $feed->merge($viral);
        $excludedIds = $excludedIds->merge($viral->pluck('id'));

        // 3. Basé sur l'historique utilisateur (20%)
        if ($user) {
            $interactionCount = (int)($limit * 0.20);
            $interactions = $this->getUserInteractionBasedProperties($user, $interactionCount, $excludedIds->toArray());
            $feed = $feed->merge($interactions);
            $excludedIds = $excludedIds->merge($interactions->pluck('id'));
        }

        // 4. Basé sur les recherches sauvegardées (15%)
        if ($user) {
            $searchCount = (int)($limit * 0.15);
            $searchBased = $this->getSearchBasedRecommendations($user, $searchCount, $excludedIds->toArray());
            $feed = $feed->merge($searchBased);
            $excludedIds = $excludedIds->merge($searchBased->pluck('id'));
        }

        // 5. Recommandations collaboratives (10%)
        if ($user) {
            $collaborativeCount = (int)($limit * 0.10);
            $collaborative = $this->getCollaborativeRecommendations($user, $collaborativeCount, $excludedIds->toArray());
            $feed = $feed->merge($collaborative);
            $excludedIds = $excludedIds->merge($collaborative->pluck('id'));
        }

        // 6. Compléter avec des propriétés de qualité si nécessaire
        $remaining = $limit - $feed->count();
        if ($remaining > 0) {
            $fallback = $this->getFallbackProperties($remaining, $excludedIds->toArray(), $position);
            $feed = $feed->merge($fallback);
        }

        // Appliquer l'algorithme de diversification et de scoring
        $finalFeed = $this->applyDiversificationAndScoring($feed, $user, $position);

        return new EloquentCollection($finalFeed->take($limit)->values()->all());
    }

    /**
     * Obtient les propriétés proches avec calcul de distance optimisé
     */
    public function getNearbyProperties(float $lat, float $lng, int $radius = 15, int $limit = 20): Collection
    {
        $cacheKey = "nearby_properties_{$lat}_{$lng}_{$radius}_{$limit}";
        
        return Cache::remember($cacheKey, 300, function() use ($lat, $lng, $radius, $limit) {
            return Property::selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('availability_status', '!=', 'inactive')
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->orderBy('viral_score', 'desc')
            ->limit($limit)
            ->get();
        });
    }

    /**
     * Obtient les propriétés virales/populaires
     */
    protected function getViralProperties(int $limit, array $excludedIds = []): Collection
    {
        $cacheKey = "viral_properties_{$limit}_" . md5(implode(',', $excludedIds));
        
        return Cache::remember($cacheKey, 600, function() use ($limit, $excludedIds) {
            return Property::where('availability_status', '!=', 'inactive')
                ->whereNotIn('id', $excludedIds)
                ->orderByDesc('viral_score')
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->each(fn($p) => $p->recommendation_source = 'viral');
        });
    }

    /**
     * Recommandations basées sur les interactions utilisateur
     */
    protected function getUserInteractionBasedProperties(User $user, int $limit, array $excludedIds = []): Collection
    {
        $interactions = collect();
        
        // Propriétés similaires aux vues récentes (60% du quota)
        $viewedSimilar = $this->getSimilarToViewedProperties($user, (int)($limit * 0.6), $excludedIds);
        $interactions = $interactions->merge($viewedSimilar);
        
        // Propriétés similaires aux favoris (40% du quota)
        $favoriteSimilar = $this->getSimilarToFavoriteProperties($user, (int)($limit * 0.4), $excludedIds);
        $interactions = $interactions->merge($favoriteSimilar);
        
        return $interactions->take($limit);
    }

    /**
     * Propriétés similaires à celles vues récemment
     */
    protected function getSimilarToViewedProperties(User $user, int $limit, array $excludedIds = []): Collection
    {
        $recentlyViewed = PropertyView::where('user_id', $user->id)
            ->where('last_viewed_at', '>=', now()->subDays(30))
            ->orderBy('view_count', 'desc')
            ->orderBy('last_viewed_at', 'desc')
            ->limit(5)
            ->with('property')
            ->get()
            ->pluck('property')
            ->filter();

        if ($recentlyViewed->isEmpty()) {
            return collect();
        }

        $similarProperties = collect();
        
        foreach ($recentlyViewed as $property) {
            $similar = $this->getSimilarProperties($property, 3)
                ->whereNotIn('id', $excludedIds)
                ->each(fn($p) => $p->recommendation_source = 'viewed_similar');
            
            $similarProperties = $similarProperties->merge($similar);
        }

        return $similarProperties->unique('id')->take($limit);
    }

    /**
     * Propriétés similaires aux favoris
     */
    protected function getSimilarToFavoriteProperties(User $user, int $limit, array $excludedIds = []): Collection
    {
        $favorites = Favorite::where('user_id', $user->id)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->pluck('property')
            ->filter();

        if ($favorites->isEmpty()) {
            return collect();
        }

        $similarProperties = collect();
        
        foreach ($favorites as $property) {
            $similar = $this->getSimilarProperties($property, 4)
                ->whereNotIn('id', $excludedIds)
                ->each(fn($p) => $p->recommendation_source = 'favorite_similar');
            
            $similarProperties = $similarProperties->merge($similar);
        }

        return $similarProperties->unique('id')->take($limit);
    }

    /**
     * Recommandations basées sur les recherches sauvegardées
     */
    public function getSearchBasedRecommendations(User $user, int $limit = 10, array $excludedIds = []): Collection
    {
        $searches = SavedSearch::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        if ($searches->isEmpty()) {
            return collect();
        }

        $recommendations = collect();

        foreach ($searches as $search) {
            if (is_array($search->criteria)) {
    $criteria = $search->criteria;
} else {
    $criteria = json_decode($search->criteria, true);
}
            
            if (!is_array($criteria)) continue;

            $query = Property::where('availability_status', '!=', 'inactive')
                ->whereNotIn('id', $excludedIds);

            foreach ($criteria as $key => $value) {
                if (empty($value)) continue;

                switch ($key) {
                    case 'type':
                        $query->where('type', $value);
                        break;
                    case 'city':
                        $query->where('city', 'like', "%{$value}%");
                        break;
                    case 'price_min':
                        $query->where('price', '>=', $value);
                        break;
                    case 'price_max':
                        $query->where('price', '<=', $value);
                        break;
                    case 'bedrooms':
                        $query->where('bedrooms', '>=', $value);
                        break;
                    case 'bathrooms':
                        $query->where('bathrooms', '>=', $value);
                        break;
                }
            }

            $searchResults = $query->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->each(fn($p) => $p->recommendation_source = 'saved_search');

            $recommendations = $recommendations->merge($searchResults);
        }

        return $recommendations->unique('id')->take($limit);
    }

    /**
     * Recommandations collaboratives
     */
    protected function getCollaborativeRecommendations(User $user, int $limit, array $excludedIds = []): Collection
    {
        // Trouver des utilisateurs avec des goûts similaires
        $similarUsers = $this->findSimilarUsers($user);
        
        if ($similarUsers->isEmpty()) {
            return collect();
        }

        // Obtenir les propriétés qu'ils ont aimées
        $recommendations = Favorite::whereIn('user_id', $similarUsers->pluck('id'))
            ->whereHas('property', function($q) use ($excludedIds) {
                $q->where('availability_status', '!=', 'inactive')
                  ->whereNotIn('id', $excludedIds);
            })
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit($limit * 2)
            ->get()
            ->pluck('property')
            ->filter()
            ->unique('id')
            ->take($limit)
            ->each(fn($p) => $p->recommendation_source = 'collaborative');

        return $recommendations;
    }

    /**
     * Trouve des utilisateurs avec des goûts similaires
     */
    protected function findSimilarUsers(User $user): Collection
    {
        $userFavorites = Favorite::where('user_id', $user->id)->pluck('property_id');
        $userViews = PropertyView::where('user_id', $user->id)->pluck('property_id');
        
        $commonInterests = $userFavorites->merge($userViews)->unique();
        
        if ($commonInterests->isEmpty()) {
            return collect();
        }

        return User::whereHas('favorites', function($q) use ($commonInterests) {
                $q->whereIn('property_id', $commonInterests);
            })
            ->where('id', '!=', $user->id)
            ->withCount(['favorites as common_favorites' => function($q) use ($commonInterests) {
                $q->whereIn('property_id', $commonInterests);
            }])
            ->having('common_favorites', '>=', 2)
            ->orderBy('common_favorites', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Propriétés de fallback de qualité
     */
    protected function getFallbackProperties(int $limit, array $excludedIds, array $position): Collection
    {
        return Property::where('availability_status', '!=', 'inactive')
            ->whereNotIn('id', $excludedIds)
            ->where(function($q) {
                $q->where('is_featured', true)
                  ->orWhere('viral_score', '>', 0)
                  ->orWhere('created_at', '>=', now()->subDays(7));
            })
            ->orderByDesc('viral_score')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->each(fn($p) => $p->recommendation_source = 'quality');
    }

    /**
     * Applique la diversification et le scoring
     */
    protected function applyDiversificationAndScoring(Collection $feed, ?User $user, array $position): Collection
    {
        return $feed->map(function($property) use ($user, $position) {
            // Calculer un score de pertinence
            $score = $this->calculateRelevanceScore($property, $user, $position);
            $property->relevance_score = $score;
            return $property;
        })
        ->groupBy('type') // Diversifier par type
        ->flatMap(function($group) {
            return $group->sortByDesc('relevance_score')->take(3); // Max 3 par type
        })
        ->sortByDesc('relevance_score')
        ->values();
    }

    /**
     * Calcule un score de pertinence pour une propriété
     */
    protected function calculateRelevanceScore(Property $property, ?User $user, array $position): float
    {
        $score = 0;

        // Score basé sur la popularité
        $score += ($property->viral_score ?? 0) * 0.3;

        // Score basé sur la fraîcheur
        $daysSinceCreated = $property->created_at->diffInDays(now());
        $freshnessScore = max(0, 30 - $daysSinceCreated) / 30;
        $score += $freshnessScore * 0.2;

        // Score basé sur la distance (si position disponible)
        if (isset($property->distance)) {
            $distanceScore = max(0, 50 - $property->distance) / 50;
            $score += $distanceScore * 0.3;
        }

        // Score basé sur les préférences utilisateur
        if ($user) {
            $preferencesScore = $this->calculateUserPreferencesScore($property, $user);
            $score += $preferencesScore * 0.2;
        }

        return $score;
    }

    /**
     * Calcule le score basé sur les préférences utilisateur
     */
    protected function calculateUserPreferencesScore(Property $property, User $user): float
    {
        $preferences = UserPreference::where('user_id', $user->id)->first();
        
        if (!$preferences) {
            return 0;
        }

        $score = 0;

        // Type préféré
        if ($preferences->preferred_property_types && in_array($property->type, $preferences->preferred_property_types)) {
            $score += 0.3;
        }

        // Localisation préférée
        if ($preferences->preferred_locations && in_array($property->city, $preferences->preferred_locations)) {
            $score += 0.3;
        }

        // Fourchette de prix
        if ($preferences->min_price && $preferences->max_price) {
            if ($property->price >= $preferences->min_price && $property->price <= $preferences->max_price) {
                $score += 0.4;
            }
        }

        return $score;
    }

    /**
     * Obtient des propriétés similaires à une propriété donnée
     */
    public function getSimilarProperties(Property $property, int $limit = 6): Collection
    {
        $cacheKey = "similar_properties_{$property->id}_{$limit}";
        
        return Cache::remember($cacheKey, 1800, function() use ($property, $limit) {
            return Property::where('id', '!=', $property->id)
                ->where('availability_status', '!=', 'inactive')
                ->where(function($query) use ($property) {
                    $query->where('type', $property->type)
                        ->orWhere('city', $property->city)
                        ->orWhereBetween('price', [$property->price * 0.7, $property->price * 1.3]);
                    
                    if ($property->bedrooms) {
                        $query->orWhereBetween('bedrooms', [
                            max(1, $property->bedrooms - 1), 
                            $property->bedrooms + 1
                        ]);
                    }
                })
                ->orderByDesc('viral_score')
                ->orderBy(DB::raw('RAND()'))
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Calcule un rayon dynamique basé sur la densité de propriétés
     */
    protected function calculateDynamicRadius(array $position): int
    {
        $baseRadius = 10; // km
        
        // Compter les propriétés dans un rayon de base
        $count = Property::selectRaw("
            COUNT(*) as count
        ")
        ->whereRaw("
            (6371 * acos(
                cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude))
            )) <= ?
        ", [$position['lat'], $position['lng'], $position['lat'], $baseRadius])
        ->value('count');

        // Ajuster le rayon selon la densité
        if ($count < 10) {
            return 25; // Élargir si peu de propriétés
        } elseif ($count > 100) {
            return 5;  // Réduire si beaucoup de propriétés
        }
        
        return $baseRadius;
    }

    /**
     * Obtient la position de l'utilisateur
     */
    protected function getUserPosition(?User $user): array
    {
        // Priorité 1: Session
        if ($position = session('user_position')) {
            return $position;
        }

        // Priorité 2: Base de données utilisateur
        if ($user && $userLocation = UserLocation::where('user_id', $user->id)->latest()->first()) {
            return [
                'lat' => $userLocation->latitude,
                'lng' => $userLocation->longitude,
                'city' => $userLocation->city,
                'country' => $userLocation->country,
                'source' => 'database'
            ];
        }

        // Priorité 3: Géolocalisation IP
        $position = $this->geoService->getLocationFromIP();
        session(['user_position' => $position]);
        
        return $position;
    }

    /**
     * Met à jour les scores viraux
     */
    public function updateViralScores(): void
    {
        Property::query()->update([
            'viral_score' => DB::raw('
                (COALESCE(views_count, 0) * 0.4) + 
                (COALESCE(favorites_count, 0) * 0.6) +
                (CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 10 ELSE 0 END)
            ')
        ]);
    }

    /**
     * Met à jour les préférences utilisateur basées sur le comportement
     */
    public function updateUserPreferencesFromBehavior(User $user): void
    {
        $viewedProperties = Property::select('properties.*')
            ->join('property_views', 'properties.id', '=', 'property_views.property_id')
            ->where('property_views.user_id', $user->id)
            ->where('property_views.last_viewed_at', '>=', now()->subDays(30))
            ->get();
            
        $favoritedProperties = Property::select('properties.*')
            ->join('favorites', 'properties.id', '=', 'favorites.property_id')
            ->where('favorites.user_id', $user->id)
            ->get();
            
        $allProperties = $viewedProperties->merge($favoritedProperties);
        
        if ($allProperties->isEmpty()) {
            return;
        }
        
        // Analyser les patterns
        $typePreferences = $allProperties->groupBy('type')
            ->map->count()
            ->sortDesc()
            ->take(3)
            ->keys()
            ->toArray();
            
        $cityPreferences = $allProperties->groupBy('city')
            ->map->count()
            ->sortDesc()
            ->take(3)
            ->keys()
            ->toArray();
            
        $prices = $allProperties->pluck('price')->filter();
        $minPrice = $prices->isNotEmpty() ? $prices->min() * 0.8 : null;
        $maxPrice = $prices->isNotEmpty() ? $prices->max() * 1.2 : null;
        
        // Sauvegarder les préférences
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_property_types' => $typePreferences,
                'preferred_locations' => $cityPreferences,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'updated_at' => now()
            ]
        );
    }

    /**
     * Obtient les propriétés tendance
     */
    public function getTrendingProperties(int $limit = 8): Collection
    {
        $cacheKey = "trending_properties_{$limit}";
        
        return Cache::remember($cacheKey, 900, function() use ($limit) {
            $lastWeek = Carbon::now()->subWeek();
            
            return Property::select('properties.*')
                ->join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.last_viewed_at', '>=', $lastWeek)
                ->where('properties.availability_status', '!=', 'inactive')
                ->groupBy('properties.id')
                ->orderByRaw('SUM(property_views.view_count) DESC')
                ->orderByDesc('properties.viral_score')
                ->limit($limit)
                ->get();
        });
    }
}