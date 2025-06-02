<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\RecommendationService;
use App\Services\EnhancedGeoLocationService;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PropertiesFeed extends Component
{
    public $search = '';
    public $type = '';
    public $city = '';
    public $priceMin = '';
    public $priceMax = '';
    public $loadedProperties = [];
    public $page = 1;
    public $perPage = 20;
    public $hasMorePages = true;
    public $isLoading = false;
    public $userPosition = null;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'city' => ['except' => ''],
        'priceMin' => ['except' => ''],
        'priceMax' => ['except' => ''],
    ];

    protected $listeners = ['loadMore', 'filtersUpdated', 'refreshLocation', 'favoriteUpdated'];

    // Corriger la signature de la méthode pour correspondre à l'événement envoyé
    public function favoriteUpdated($propertyId)
    {
        try {
            // Recharger la propriété depuis la base de données avec ses relations
            $updatedProperty = Property::with(['favorites' => function($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                }
            }])->find($propertyId);

            if ($updatedProperty) {
                // Mettre à jour dans loadedProperties
                foreach ($this->loadedProperties as $key => $property) {
                    if ($property->id == $propertyId) {
                        // Préserver les propriétés calculées existantes
                        if (isset($property->recommendation_source)) {
                            $updatedProperty->recommendation_source = $property->recommendation_source;
                        }
                        if (isset($property->distance)) {
                            $updatedProperty->distance = $property->distance;
                        }
                        if (isset($property->tiktok_score)) {
                            $updatedProperty->tiktok_score = $property->tiktok_score;
                        }
                        
                        $this->loadedProperties[$key] = $updatedProperty;
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des favoris: ' . $e->getMessage());
        }
    }

    public function mount()
    {
        // Initialize filters from request
        $this->search = request('search', '');
        $this->type = request('type', '');
        $this->city = request('city', '');
        $this->priceMin = request('price_min', '');
        $this->priceMax = request('price_max', '');
        
        // Forcer la détection de la position
        $this->refreshUserPosition();
        
        // Load initial properties - GARDER COMME COLLECTION D'OBJETS
        $this->loadedProperties = $this->getProperties()->all();
    }

    public function refreshLocation()
    {
        // Vider le cache de position et recharger
        session()->forget('user_position');
        $this->refreshUserPosition();
        $this->resetPagination();
        $this->loadedProperties = $this->getProperties()->all();
    }

    protected function refreshUserPosition()
    {
        try {
            $geoService = app(EnhancedGeoLocationService::class);
            $this->userPosition = $geoService->getLocationFromIP();
            session(['user_position' => $this->userPosition]);
            
            Log::info('User position updated: ', $this->userPosition);
        } catch (\Exception $e) {
            Log::error('Error refreshing user position: ' . $e->getMessage());
            $this->userPosition = [
                'lat' => 5.3600,
                'lng' => -4.0083,
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'source' => 'default'
            ];
        }
    }

    public function loadMore()
    {
        if ($this->hasMorePages && !$this->isLoading) {
            $this->isLoading = true;
            $this->page++;
            
            $newProperties = $this->getProperties();
            
            if ($newProperties->count() > 0) {
                // Merger les collections d'objets Property
                $this->loadedProperties = array_merge($this->loadedProperties, $newProperties->all());
            } else {
                $this->hasMorePages = false;
            }
            
            $this->isLoading = false;
        }
    }

    public function filtersUpdated($filters)
    {
        $this->search = $filters['search'] ?? '';
        $this->type = $filters['type'] ?? '';
        $this->city = $filters['city'] ?? '';
        $this->priceMin = $filters['priceMin'] ?? '';
        $this->priceMax = $filters['priceMax'] ?? '';
        
        $this->resetPagination();
        $this->loadedProperties = $this->getProperties()->all();
    }

    protected function resetPagination()
    {
        $this->page = 1;
        $this->hasMorePages = true;
        $this->loadedProperties = [];
    }

    protected function getProperties()
    {
        $user = auth()->user();
        $position = $this->getUserPosition();
        
        // Si des filtres de recherche sont actifs, prioriser la recherche
        if ($this->hasActiveFilters()) {
            return $this->getSearchResults($position, $user);
        }
        
        // Sinon, utiliser l'algorithme TikTok-style
        return $this->getTikTokStyleFeed($user, $position);
    }

    protected function hasActiveFilters()
    {
        return !empty($this->search) || 
               !empty($this->type) || 
               !empty($this->city) || 
               !empty($this->priceMin) || 
               !empty($this->priceMax);
    }

    protected function getSearchResults($position, $user = null)
    {
        $geoService = app(EnhancedGeoLocationService::class);
        
        $query = Property::query()
            ->where('status', '!=', 'inactive')
            ->where('availability_status', '!=', 'sold');

        // Charger les relations nécessaires
        $query->with(['favorites' => function($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id);
            }
        }]);
        
        // Appliquer les filtres de recherche
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }
        
        if (!empty($this->type)) {
            $query->where('type', $this->type);
        }
        
        if (!empty($this->city)) {
            // Normaliser la recherche de ville pour inclure les quartiers d'Abidjan
            $normalizedCity = $geoService->normalizeAbidjanDistrict($this->city);
            
            $query->where(function($q) use ($normalizedCity) {
                $q->where('city', 'like', '%' . $this->city . '%');
                
                // Si la recherche concerne Abidjan, inclure tous ses quartiers
                if ($normalizedCity === 'Abidjan') {
                    $abidjanDistricts = ['yopougon', 'plateau', 'cocody', 'adjame', 'treichville', 
                                       'marcory', 'koumassi', 'port-bouet', 'abobo', 'anyama', 
                                       'bingerville', 'songon', 'attécoubé', 'attecoube'];
                    
                    foreach ($abidjanDistricts as $district) {
                        $q->orWhere('city', 'like', '%' . $district . '%');
                    }
                }
            });
        }
        
        if (!empty($this->priceMin)) {
            $query->where('price', '>=', $this->priceMin);
        }
        
        if (!empty($this->priceMax)) {
            $query->where('price', '<=', $this->priceMax);
        }
        
        // Exclure les propriétés déjà chargées
        if (!empty($this->loadedProperties)) {
            $loadedIds = collect($this->loadedProperties)->pluck('id')->toArray();
            $query->whereNotIn('id', $loadedIds);
        }
        
        // Trier par pertinence géographique et préférences
        if ($position && isset($position['lat']) && isset($position['lng'])) {
            $query = $this->applyTikTokScoring($query, $position, $user);
        } else {
            $query->orderByDesc('viral_score')
                  ->orderByDesc('created_at');
        }
        
        $results = $query->skip(($this->page - 1) * $this->perPage)
                        ->take($this->perPage)
                        ->get();
        
        // Marquer la source de recommandation
        $results->each(function($property) {
            $property->recommendation_source = 'search';
        });
        
        return $results;
    }

    protected function getTikTokStyleFeed($user, $position)
    {
        $geoService = app(EnhancedGeoLocationService::class);
        
        // Algorithme TikTok-style : proximité + intérêts utilisateur
        $query = Property::query()
            ->where('status', '!=', 'inactive')
            ->where('availability_status', '!=', 'sold');

        // Charger les relations nécessaires
        $query->with(['favorites' => function($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id);
            }
        }]);
        
        // Exclure les propriétés déjà chargées
        if (!empty($this->loadedProperties)) {
            $loadedIds = collect($this->loadedProperties)->pluck('id')->toArray();
            $query->whereNotIn('id', $loadedIds);
        }
        
        // Appliquer le scoring TikTok-style avec gestion des quartiers d'Abidjan
        $query = $this->applyTikTokScoringWithDistricts($query, $position, $user, $geoService);
        
        $results = $query->skip(($this->page - 1) * $this->perPage)
                        ->take($this->perPage)
                        ->get();
        
        // Assigner les sources de recommandation basées sur le score
        $results->each(function($property) use ($position, $user, $geoService) {
            $property->recommendation_source = $this->determineRecommendationSource($property, $position, $user, $geoService);
        });
        
        return $results;
    }

    protected function applyTikTokScoringWithDistricts($query, $position, $user, $geoService)
    {
        $userLat = $position['lat'] ?? 5.3600;
        $userLng = $position['lng'] ?? -4.0083;
        $userCountry = str_replace("'", "''", $position['country'] ?? 'Côte d\'Ivoire');
        $userCity = $geoService->normalizeAbidjanDistrict($position['city'] ?? 'Abidjan');
        
        // Calculer un score composite TikTok-style avec bonus pour les quartiers d'Abidjan
        $scoreQuery = "
            (
                -- Score de proximité géographique (0-40 points)
                GREATEST(0, 40 - (
                    6371 * acos(
                        cos(radians({$userLat})) * 
                        cos(radians(COALESCE(latitude, 0))) * 
                        cos(radians(COALESCE(longitude, 0)) - radians({$userLng})) + 
                        sin(radians({$userLat})) * 
                        sin(radians(COALESCE(latitude, 0)))
                    )
                )) +
                
                -- Bonus quartier d'Abidjan (0-30 points)
                CASE 
                    WHEN '{$userCity}' = 'Abidjan' AND (
                        city LIKE '%abidjan%' OR 
                        city LIKE '%yopougon%' OR 
                        city LIKE '%plateau%' OR 
                        city LIKE '%cocody%' OR 
                        city LIKE '%adjame%' OR 
                        city LIKE '%treichville%' OR 
                        city LIKE '%marcory%' OR 
                        city LIKE '%koumassi%' OR 
                        city LIKE '%port-bouet%' OR 
                        city LIKE '%abobo%' OR 
                        city LIKE '%anyama%' OR 
                        city LIKE '%bingerville%' OR 
                        city LIKE '%songon%' OR 
                        city LIKE '%attécoubé%' OR 
                        city LIKE '%attecoube%'
                    ) THEN 30
                    ELSE 0
                END +
                
                -- Score viral/popularité (0-25 points)
                LEAST(25, COALESCE(viral_score, 0) * 2.5) +
                
                -- Score de fraîcheur (0-15 points)
                CASE 
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN 15
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 10
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 5
                    ELSE 0
                END +
                
                -- Score featured (0-10 points)
                CASE WHEN is_featured = 1 THEN 10 ELSE 0 END +
                
                -- Score pays/région (0-10 points)
                CASE 
                    WHEN country = '{$userCountry}' THEN 10
                    ELSE 0
                END
            ) as tiktok_score,
            
            -- Distance pour référence
            COALESCE((6371 * acos(
                cos(radians({$userLat})) * 
                cos(radians(COALESCE(latitude, 0))) * 
                cos(radians(COALESCE(longitude, 0)) - radians({$userLng})) + 
                sin(radians({$userLat})) * 
                sin(radians(COALESCE(latitude, 0)))
            )), 999999) AS distance
        ";
        
        // Ajouter le score des préférences utilisateur si connecté
        if ($user) {
            $userPreferencesScore = $this->getUserPreferencesScore($user);
            $scoreQuery = str_replace(
                ') as tiktok_score', 
                " + {$userPreferencesScore}) as tiktok_score", 
                $scoreQuery
            );
        }
        
        return $query->selectRaw("properties.*, {$scoreQuery}")
                    ->orderByDesc('tiktok_score')
                    ->orderBy('distance');
    }

    protected function applyTikTokScoring($query, $position, $user = null)
    {
        $geoService = app(EnhancedGeoLocationService::class);
        return $this->applyTikTokScoringWithDistricts($query, $position, $user, $geoService);
    }

    protected function getUserPreferencesScore($user)
    {
        if (!$user) {
            return "0";
        }

        try {
            // Score basé sur l'historique de l'utilisateur (0-20 points)
            $viewedTypes = PropertyView::where('user_id', $user->id)
                ->join('properties', 'property_views.property_id', '=', 'properties.id')
                ->select('properties.type')
                ->groupBy('properties.type')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(3)
                ->pluck('type')
                ->toArray();
            
            $favoriteTypes = Favorite::where('user_id', $user->id)
                ->join('properties', 'favorites.property_id', '=', 'properties.id')
                ->select('properties.type')
                ->groupBy('properties.type')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(3)
                ->pluck('type')
                ->toArray();
            
            $preferredTypes = array_unique(array_merge($viewedTypes, $favoriteTypes));
            
            if (empty($preferredTypes)) {
                return "0";
            }
            
            $typeConditions = [];
            foreach ($preferredTypes as $type) {
                $escapedType = addslashes($type);
                $typeConditions[] = "WHEN type = '{$escapedType}' THEN 20";
            }
            
            return "CASE " . implode(' ', $typeConditions) . " ELSE 0 END";
        } catch (\Exception $e) {
            Log::error('Error calculating user preferences score: ' . $e->getMessage());
            return "0";
        }
    }

    protected function determineRecommendationSource($property, $position, $user, $geoService)
    {
        $userCity = $geoService->normalizeAbidjanDistrict($position['city'] ?? 'Abidjan');
        $propertyCity = $geoService->normalizeAbidjanDistrict($property->city ?? '');
        
        // Si c'est dans le même quartier/ville
        if ($userCity === $propertyCity || 
            ($userCity === 'Abidjan' && str_contains(strtolower($property->city), 'abidjan')) ||
            (isset($property->distance) && $property->distance <= 5)) {
            return 'nearby';
        }
        
        if (isset($property->viral_score) && $property->viral_score > 7) {
            return 'viral';
        }
        
        if ($user && $this->isUserInterested($property, $user)) {
            return 'personalized';
        }
        
        if (isset($property->country) && $property->country === ($position['country'] ?? 'Côte d\'Ivoire')) {
            return 'local';
        }
        
        return 'discovery';
    }

    protected function isUserInterested($property, $user)
    {
        if (!$user || !isset($property->type)) {
            return false;
        }

        try {
            // Vérifier si l'utilisateur a montré de l'intérêt pour ce type de propriété
            $hasViewedSimilar = PropertyView::where('user_id', $user->id)
                ->join('properties', 'property_views.property_id', '=', 'properties.id')
                ->where('properties.type', $property->type)
                ->exists();
            
            $hasFavoritedSimilar = Favorite::where('user_id', $user->id)
                ->join('properties', 'favorites.property_id', '=', 'properties.id')
                ->where('properties.type', $property->type)
                ->exists();
            
            return $hasViewedSimilar || $hasFavoritedSimilar;
        } catch (\Exception $e) {
            Log::error('Error checking user interest: ' . $e->getMessage());
            return false;
        }
    }

    protected function getUserPosition()
    {
        return $this->userPosition ?? session('user_position') ?? [
            'lat' => 5.3600,
            'lng' => -4.0083,
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'source' => 'default'
        ];
    }

    public function render()
    {
        return view('livewire.properties-feed', [
            'properties' => collect($this->loadedProperties),
            'hasMorePages' => $this->hasMorePages,
            'isLoading' => $this->isLoading,
            'userPosition' => $this->userPosition
        ]);
    }
}
