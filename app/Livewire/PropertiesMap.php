<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\EnhancedGeoLocationService;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PropertiesMap extends Component
{
    public $search = '';
    public $type = '';
    public $city = '';
    public $priceMin = '';
    public $priceMax = '';
    public $userPosition = null;
    public $mapCenter = ['lat' => 5.3167, 'lng' => -4.0333];
    public $zoom = 12;
    public $properties = [];
    public $propertyTypes = [];
    public $totalProperties = 0;
    public $viewMode = 'map';
    public $isSidebarOpen = false;
    public $mapBounds = null;
    public $performanceMetrics = [
        'last_query_time' => 0,
        'properties_loaded' => 0,
        'cache_hits' => 0
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'city' => ['except' => ''],
        'priceMin' => ['except' => ''],
        'priceMax' => ['except' => ''],
        'viewMode' => ['except' => 'map'],
    ];

    protected $listeners = ['refreshLocation', 'mapMoved'];

    public function mount()
    {
        $this->search = request('search', '');
        $this->type = request('type', '');
        $this->city = request('city', '');
        $this->priceMin = request('price_min', '');
        $this->priceMax = request('price_max', '');
        $this->viewMode = request('view_mode', 'map');

        $this->propertyTypes = cache()->remember('property_types', 3600, function () {
            return Property::distinct()->pluck('type')->filter()->toArray();
        });

        $this->refreshUserPosition();

        // Charger les propriétés immédiatement
        $this->loadProperties();
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'map' ? 'list' : 'map';
    }

    public function toggleSidebar()
    {
        $this->isSidebarOpen = !$this->isSidebarOpen;
    }

    public function refreshLocation()
    {
        session()->forget('user_position');
        $this->refreshUserPosition();
        $this->loadProperties();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->city = '';
        $this->priceMin = '';
        $this->priceMax = '';
        $this->loadProperties();
    }

    public function mapMoved($center, $zoom, $bounds = null)
    {
        $this->mapCenter = $center;
        $this->zoom = $zoom;
        $this->mapBounds = $bounds;

        // Debounce map updates to improve performance
        $this->loadProperties();
    }

    public function toggleFavorite($propertyId)
    {
        if (!auth()->check()) {
            $this->dispatch('show-login-modal');
            return;
        }

        $userId = auth()->id();
        $favorite = Favorite::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $this->dispatch('favorite-removed', propertyId: $propertyId);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'property_id' => $propertyId
            ]);
            $this->dispatch('favorite-added', propertyId: $propertyId);
        }

        $this->loadProperties();
    }

    protected function refreshUserPosition()
    {
        try {
            $geoService = app(EnhancedGeoLocationService::class);
            $this->userPosition = $geoService->getLocationFromIP();
            session(['user_position' => $this->userPosition]);

            if ($this->userPosition) {
                $this->mapCenter = [
                    'lat' => $this->userPosition['lat'],
                    'lng' => $this->userPosition['lng']
                ];
            }
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

    public function loadProperties()
    {
        $startTime = microtime(true);

        $user = auth()->user();
        $position = $this->getUserPosition();

        $cacheKey = 'properties_map_' . md5(serialize([
            'search' => $this->search,
            'type' => $this->type,
            'city' => $this->city,
            'priceMin' => $this->priceMin,
            'priceMax' => $this->priceMax,
            'bounds' => $this->mapBounds,
            'user_id' => $user?->id
        ]));

        $properties = cache()->remember($cacheKey, 300, function () use ($position, $user) {
            if ($this->hasActiveFilters()) {
                return $this->getSearchResults($position, $user);
            } else {
                return $this->getTikTokStyleFeed($user, $position);
            }
        });

        $this->totalProperties = $properties->count();

        $geoJsonFeatures = [];
        $coordinates = [];

        foreach ($properties as $property) {
            try {
                $latitude = (float)$property->latitude;
                $longitude = (float)$property->longitude;

                if ($latitude === 0.0 || $longitude === 0.0) continue;

                if ($this->mapBounds && !$this->isInBounds($latitude, $longitude)) {
                    continue;
                }

                $geoJsonFeatures[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$longitude, $latitude]
                    ],
                    'properties' => [
                        'id' => $property->id,
                        'title' => $property->title,
                        'price' => number_format($property->price, 0, ',', ' '),
                        'type' => $property->type,
                        'address' => $property->address,
                        'city' => $property->city,
                        'bedrooms' => $property->bedrooms ?? 0,
                        'bathrooms' => $property->bathrooms ?? 0,
                        'area' => $property->area ?? 0,
                        'image' => $this->getPropertyMainImage($property),
                        'url' => route('detail', $property->slug),
                        'is_favorited' => $user ? ($property->relationLoaded('favorites') ? $property->favorites->where('user_id', $user->id)->isNotEmpty() : false) : false,
                    ]
                ];

                $coordinates[] = [$latitude, $longitude];
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->properties = $geoJsonFeatures;

        // Si recherche par ville, calculer le centre des résultats
        if (!empty($this->city)) {
            $latSum = $lngSum = 0;
            foreach ($coordinates as $coord) {
                $latSum += $coord[0];
                $lngSum += $coord[1];
            }

            if (count($coordinates) > 0) {
                $this->mapCenter = [
                    'lat' => $latSum / count($coordinates),
                    'lng' => $lngSum / count($coordinates)
                ];
            }
        }

        $this->performanceMetrics['last_query_time'] = round((microtime(true) - $startTime) * 1000);
        $this->performanceMetrics['properties_loaded'] = count($geoJsonFeatures);

        $this->dispatch(
            'propertiesUpdated',
            properties: $this->properties,
            total: $this->totalProperties,
            center: $this->mapCenter,
            zoom: $this->zoom,
            metrics: $this->performanceMetrics,
            shouldFitBounds: !empty($this->city) || !empty($this->search)
        );
    }

    protected function getPropertyMainImage($property)
    {
        $mainImage = null;
        $images = [];

        if (is_array($property->images)) {
            $images = $property->images;
        } elseif (is_string($property->images)) {
            $images = json_decode($property->images, true) ?? [];
        }

        if (!empty($images) && isset($images[0]['path'])) {
            $mainImage = asset('storage/' . $images[0]['path']);
        }

        return $mainImage;
    }

    protected function isInBounds($lat, $lng)
    {
        if (!$this->mapBounds) {
            return true;
        }

        return $lat >= $this->mapBounds['south'] &&
            $lat <= $this->mapBounds['north'] &&
            $lng >= $this->mapBounds['west'] &&
            $lng <= $this->mapBounds['east'];
    }

    protected function hasActiveFilters()
    {
        return !empty($this->search) ||
            !empty($this->type) ||
            !empty($this->city) ||
            !empty($this->priceMin) ||
            !empty($this->priceMax);
    }

    protected function applySearchFilters($query)
    {
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->type)) {
            $query->where('type', $this->type);
        }

        if (!empty($this->city)) {
            $query->where('city', 'like', '%' . $this->city . '%');
        }

        if (!empty($this->priceMin)) {
            $query->where('price', '>=', $this->priceMin);
        }

        if (!empty($this->priceMax)) {
            $query->where('price', '<=', $this->priceMax);
        }
    }

    protected function getSearchResults($position, $user = null)
    {
        $query = Property::query()
            ->where('availability_status', '!=', 'inactive')
            ->where('status', '!=', 'sold')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0);

        $query->with(['favorites' => function ($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id);
            }
        }]);

        $this->applySearchFilters($query);

        if ($position && isset($position['lat']) && isset($position['lng'])) {
            $query = $this->applyTikTokScoring($query, $position, $user);
        } else {
            $query->orderByDesc('viral_score')->orderByDesc('created_at');
        }

        $results = $query->limit(200)->get(); // Increased limit for better map coverage

        $results->each(function ($property) {
            $property->recommendation_source = 'search';
        });

        return $results;
    }

    protected function getTikTokStyleFeed($user, $position)
    {
        $geoService = app(EnhancedGeoLocationService::class);

        $query = Property::query()
            ->where('availability_status', '!=', 'inactive')
            ->where('status', '!=', 'sold')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0);

        $query->with(['favorites' => function ($q) use ($user) {
            if ($user) {
                $q->where('user_id', $user->id);
            }
        }]);

        $query = $this->applyTikTokScoringWithDistricts($query, $position, $user, $geoService);

        $results = $query->limit(200)->get();

        $results->each(function ($property) use ($position, $user, $geoService) {
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

        $scoreQuery = "
            (
                GREATEST(0, 40 - (
                    6371 * acos(
                        cos(radians({$userLat})) * 
                        cos(radians(COALESCE(latitude, 0))) * 
                        cos(radians(COALESCE(longitude, 0)) - radians({$userLng})) + 
                        sin(radians({$userLat})) * 
                        sin(radians(COALESCE(latitude, 0)))
                    )
                )) +
                
                CASE 
                    WHEN '{$userCity}' = 'Abidjan' AND (
                        city LIKE '%abidjan%' OR city LIKE '%yopougon%' OR city LIKE '%plateau%' OR 
                        city LIKE '%cocody%' OR city LIKE '%adjame%' OR city LIKE '%treichville%' OR 
                        city LIKE '%marcory%' OR city LIKE '%koumassi%' OR city LIKE '%port-bouet%' OR 
                        city LIKE '%abobo%' OR city LIKE '%anyama%' OR city LIKE '%bingerville%' OR 
                        city LIKE '%songon%' OR city LIKE '%attécoubé%' OR city LIKE '%attecoube%'
                    ) THEN 30
                    ELSE 0
                END +
                
                LEAST(25, COALESCE(viral_score, 0) * 2.5) +
                
                CASE 
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) THEN 15
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 10
                    WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 5
                    ELSE 0
                END +
                
                CASE WHEN is_featured = 1 THEN 10 ELSE 0 END +
                
                CASE WHEN country = '{$userCountry}' THEN 10 ELSE 0 END
            ) as tiktok_score,
            
            COALESCE((6371 * acos(
                cos(radians({$userLat})) * 
                cos(radians(COALESCE(latitude, 0))) * 
                cos(radians(COALESCE(longitude, 0)) - radians({$userLng})) + 
                sin(radians({$userLat})) * 
                sin(radians(COALESCE(latitude, 0)))
            )), 999999) AS distance
        ";

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
        if (!$user) return "0";

        try {
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

            if (empty($preferredTypes)) return "0";

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

        if (
            $userCity === $propertyCity ||
            ($userCity === 'Abidjan' && str_contains(strtolower($property->city), 'abidjan')) ||
            (isset($property->distance) && $property->distance <= 5)
        ) {
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
        if (!$user || !isset($property->type)) return false;

        try {
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
        return view('livewire.properties-map');
    }
}
