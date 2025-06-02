<?php

namespace App\Services;

use App\Models\Property;
use App\Models\SavedSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PropertySearchService
{
    protected GeoLocationService $geoService;

    public function __construct(GeoLocationService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Effectue une recherche de propriétés avec filtres, mise en cache 5 minutes
     *
     * @param array $params
     * @param array|null $position
     * @return Builder
     */
    public function search(array $params, ?array $position = null): Builder
    {
        $cacheKey = 'property_search_' . md5(json_encode($params));

        return Cache::remember($cacheKey, 300, function () use ($params, $position) {
            $query = Property::query()
                ->with(['owner'])
                ->where('status', '!=', 'inactive');

            $this->applyBasicFilters($query, $params);
            $this->applyAdvancedFilters($query, $params);

            if ($position && isset($position['lat']) && isset($position['lng'])) {
                $this->applyGeoSorting($query, $position['lat'], $position['lng']);
            } else {
                $query->orderByDesc('created_at');
            }

            return $query;
        });
    }

    /**
     * Obtient les résultats principaux de recherche (priorité métriques)
     *
     * @param array $params
     * @param array|null $position
     * @return Builder
     */
    public function getPrimaryResults(array $params, ?array $position = null): Builder
    {
        $query = Property::query()
            ->with(['owner'])
            ->where('status', '!=', 'inactive');

        $this->applyBasicFilters($query, $params);
        $this->applyGeoFilters($query, $params);

        $query->orderByDesc('viral_score')
              ->orderByDesc('is_featured')
              ->orderByDesc('created_at');

        return $query;
    }

    /**
     * Recherche avancée avec scoring de pertinence
     *
     * @param array $params
     * @param array|null $position
     * @return Builder
     */
    public function advancedSearch(array $params, ?array $position = null): Builder
    {
        $query = Property::query()
            ->with(['owner'])
            ->where('status', '!=', 'inactive');

        $this->applyBasicFilters($query, $params);
        $this->applyAdvancedFilters($query, $params);

        $query->selectRaw('properties.*, 
            (CASE 
                WHEN properties.is_featured = 1 THEN 10 
                ELSE 0 
            END +
            CASE 
                WHEN properties.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 5 
                ELSE 0 
            END +
            COALESCE(properties.viral_score, 0)
            ) as relevance_score');

        if ($position && isset($position['lat']) && isset($position['lng'])) {
            $this->applyGeoSorting($query, $position['lat'], $position['lng']);
        } else {
            $query->orderByDesc('relevance_score');
        }

        return $query;
    }

    /**
     * Applique les filtres basiques
     *
     * @param Builder $query
     * @param array $params
     * @return void
     */
    protected function applyBasicFilters(Builder $query, array $params): void
    {
        $directFilters = ['type', 'city', 'status'];
        foreach ($directFilters as $filter) {
            if (!empty($params[$filter])) {
                $query->where($filter, $params[$filter]);
            }
        }

        if (!empty($params['search'])) {
            $searchTerm = $params['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        }

        if (!empty($params['price_min'])) {
            $query->where('price', '>=', $params['price_min']);
        }

        if (!empty($params['price_max'])) {
            $query->where('price', '<=', $params['price_max']);
        }

        if (!empty($params['bedrooms'])) {
            $query->where('bedrooms', '>=', $params['bedrooms']);
        }

        if (!empty($params['bathrooms'])) {
            $query->where('bathrooms', '>=', $params['bathrooms']);
        }

        if (!empty($params['area_min'])) {
            $query->where('area', '>=', $params['area_min']);
        }

        if (!empty($params['area_max'])) {
            $query->where('area', '<=', $params['area_max']);
        }
    }

    /**
     * Applique les filtres avancés
     *
     * @param Builder $query
     * @param array $params
     * @return void
     */
    protected function applyAdvancedFilters(Builder $query, array $params): void
    {
        if (!empty($params['year_built_min'])) {
            $query->where('year_built', '>=', $params['year_built_min']);
        }

        if (!empty($params['year_built_max'])) {
            $query->where('year_built', '<=', $params['year_built_max']);
        }

        if (!empty($params['has_virtual_tour'])) {
            $query->where('has_virtual_tour', true);
        }

        if (!empty($params['featured_only'])) {
            $query->where('is_featured', true);
        }

        if (!empty($params['recent_only'])) {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        if (!empty($params['features']) && is_array($params['features'])) {
            $query->where(function ($q) use ($params) {
                foreach ($params['features'] as $feature) {
                    $q->orWhereJsonContains('features', $feature);
                }
            });
        }
    }

    /**
     * Applique les filtres géographiques
     *
     * @param Builder $query
     * @param array $params
     * @return void
     */
    protected function applyGeoFilters(Builder $query, array $params): void
    {
        if (!empty($params['lat']) && !empty($params['lng'])) {
            $radius = $params['radius'] ?? 10; // km

            $query->whereRaw("
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) <= ?
            ", [$params['lat'], $params['lng'], $params['lat'], $radius]);
        }
    }

    /**
     * Applique le tri par distance géographique
     *
     * @param Builder $query
     * @param float $lat
     * @param float $lng
     * @return void
     */
    protected function applyGeoSorting(Builder $query, float $lat, float $lng): void
    {
        $query->selectRaw("properties.*, 
            (6371 * acos(
                cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude))
            )) AS distance", [$lat, $lng, $lat])
            ->orderBy('distance');
    }

    /**
     * Sauvegarde une recherche utilisateur
     *
     * @param array $criteria
     * @param string $name
     * @param int $userId
     * @return SavedSearch
     */
    public function saveSearch(array $criteria, string $name, int $userId): SavedSearch
    {
        return SavedSearch::create([
            'user_id' => $userId,
            'name' => $name,
            'criteria' => json_encode($criteria),
            'is_active' => true,
        ]);
    }

    /**
     * Vérifie les nouvelles correspondances pour une recherche sauvegardée
     *
     * @param SavedSearch $search
     * @return \Illuminate\Support\Collection
     */
    public function checkForNewMatches(SavedSearch $search): \Illuminate\Support\Collection
    {
        $criteria = json_decode($search->criteria, true);

        if (!is_array($criteria)) {
            return collect();
        }

        $query = Property::query()
            ->where('status', '!=', 'inactive')
            ->where('created_at', '>', $search->last_checked_at ?? $search->created_at);

        $this->applyBasicFilters($query, $criteria);
        $this->applyAdvancedFilters($query, $criteria);

        $newProperties = $query->get();

        $search->update(['last_checked_at' => now()]);

        return $newProperties;
    }

    /**
     * Suggestions de recherches sauvegardées pour un utilisateur
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getSearchSuggestions(int $userId, int $limit = 5): \Illuminate\Support\Collection
    {
        return SavedSearch::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($search) {
                $criteria = json_decode($search->criteria, true);
                return [
                    'id' => $search->id,
                    'name' => $search->name,
                    'criteria' => $criteria,
                    'created_at' => $search->created_at,
                ];
            });
    }

    /**
     * Recherche par mots-clés avec scoring
     *
     * @param string $keywords
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function searchByKeywords(string $keywords, int $limit = 20): \Illuminate\Support\Collection
    {
        $terms = explode(' ', $keywords);

        $query = Property::query()
            ->where('status', '!=', 'inactive')
            ->select('properties.*');

        $scoreCase = '';
        foreach ($terms as $term) {
            $term = addslashes($term); // éviter injection SQL simple
            $scoreCase .= "
                + (CASE WHEN title LIKE '%{$term}%' THEN 10 ELSE 0 END)
                + (CASE WHEN description LIKE '%{$term}%' THEN 5 ELSE 0 END)
                + (CASE WHEN city LIKE '%{$term}%' THEN 8 ELSE 0 END)
                + (CASE WHEN address LIKE '%{$term}%' THEN 3 ELSE 0 END)
            ";
        }

        $query->selectRaw("properties.*, (0 {$scoreCase}) as search_score")
            ->having('search_score', '>', 0)
            ->orderByDesc('search_score')
            ->orderByDesc('viral_score')
            ->limit($limit);

        return $query->get();
    }

    /**
     * Recherche de propriétés similaires
     *
     * @param int $propertyId
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function findSimilarProperties(int $propertyId, int $limit = 6): \Illuminate\Support\Collection
    {
        $property = Property::find($propertyId);

        if (!$property) {
            return collect();
        }

        return Property::where('id', '!=', $propertyId)
            ->where('status', '!=', 'inactive')
            ->where(function ($query) use ($property) {
                $query->where('type', $property->type)
                    ->orWhere('city', $property->city)
                    ->orWhereBetween('price', [
                        $property->price * 0.8,
                        $property->price * 1.2,
                    ]);

                if ($property->bedrooms) {
                    $query->orWhereBetween('bedrooms', [
                        max(1, $property->bedrooms - 1),
                        $property->bedrooms + 1,
                    ]);
                }
            })
            ->orderByDesc('viral_score')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtient les filtres populaires (types, villes, tranches de prix)
     *
     * @return array
     */
    public function getPopularFilters(): array
    {
        return [
            'types' => Property::select('type', DB::raw('COUNT(*) as count'))
                ->where('status', '!=', 'inactive')
                ->groupBy('type')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'type')
                ->toArray(),

            'cities' => Property::select('city', DB::raw('COUNT(*) as count'))
                ->where('status', '!=', 'inactive')
                ->whereNotNull('city')
                ->groupBy('city')
                ->orderByDesc('count')
                ->limit(15)
                ->pluck('count', 'city')
                ->toArray(),

            'price_ranges' => [
                '0-100000' => Property::where('price', '<=', 100000)->count(),
                '100000-500000' => Property::whereBetween('price', [100000, 500000])->count(),
                '500000-1000000' => Property::whereBetween('price', [500000, 1000000])->count(),
                '1000000+' => Property::where('price', '>', 1000000)->count(),
            ],
        ];
    }
}
