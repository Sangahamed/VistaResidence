<?php

namespace App\Services;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PropertyRecommendationService
{
    /**
     * Obtenir des recommandations de propriétés pour un utilisateur
     */
    public function getRecommendationsForUser(User $user, int $limit = 5): array
    {
        // Récupérer les propriétés consultées par l'utilisateur
        $viewedProperties = $this->getViewedProperties($user);
        
        if ($viewedProperties->isEmpty()) {
            // Si l'utilisateur n'a pas encore consulté de propriétés, recommander les plus populaires
            return $this->getPopularProperties($limit);
        }
        
        // Récupérer les caractéristiques des propriétés consultées
        $preferences = $this->extractPreferences($viewedProperties);
        
        // Trouver des propriétés similaires
        $recommendations = $this->findSimilarProperties($preferences, $viewedProperties->pluck('id')->toArray(), $limit);
        
        return $recommendations;
    }
    
    /**
     * Récupérer les propriétés consultées par l'utilisateur
     */
    private function getViewedProperties(User $user)
    {
        return Property::whereHas('activityLogs', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('action', 'property_viewed');
        })->get();
    }
    
    /**
     * Extraire les préférences de l'utilisateur à partir des propriétés consultées
     */
    private function extractPreferences($properties): array
    {
        $priceSum = 0;
        $bedroomsSum = 0;
        $bathroomsSum = 0;
        $areaSum = 0;
        $count = $properties->count();
        
        $cities = [];
        $propertyTypes = [];
        
        foreach ($properties as $property) {
            $priceSum += $property->price;
            $bedroomsSum += $property->bedrooms;
            $bathroomsSum += $property->bathrooms;
            $areaSum += $property->area;
            
            $cities[$property->city] = ($cities[$property->city] ?? 0) + 1;
            $propertyTypes[$property->property_type_id] = ($propertyTypes[$property->property_type_id] ?? 0) + 1;
        }
        
        // Trier les villes et types de propriétés par fréquence
        arsort($cities);
        arsort($propertyTypes);
        
        return [
            'avg_price' => $count > 0 ? $priceSum / $count : 0,
            'avg_bedrooms' => $count > 0 ? $bedroomsSum / $count : 0,
            'avg_bathrooms' => $count > 0 ? $bathroomsSum / $count : 0,
            'avg_area' => $count > 0 ? $areaSum / $count : 0,
            'preferred_cities' => array_keys(array_slice($cities, 0, 3)),
            'preferred_property_types' => array_keys(array_slice($propertyTypes, 0, 2)),
        ];
    }
    
    /**
     * Trouver des propriétés similaires aux préférences
     */

     public function getNearbyProperties($lat, $lng, $radius = 10)
    {
        return Property::selectRaw("*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude))) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    public function getSimilarProperties(Property $property, $limit = 4)
    {
        return Property::where('type', $property->type)
            ->where('id', '!=', $property->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
    
    private function findSimilarProperties(array $preferences, array $excludeIds, int $limit): array
    {
        $query = Property::query()
            ->where('status', 'available')
            ->whereNotIn('id', $excludeIds);
        
        // Filtrer par villes préférées si disponibles
        if (!empty($preferences['preferred_cities'])) {
            $query->whereIn('city', $preferences['preferred_cities']);
        }
        
        // Filtrer par types de propriétés préférés si disponibles
        if (!empty($preferences['preferred_property_types'])) {
            $query->whereIn('property_type_id', $preferences['preferred_property_types']);
        }
        
        // Calculer un score de similarité
        $query->select('properties.*')
            ->selectRaw('
                (1 - ABS(price - ?) / ?) * 0.4 +
                (1 - ABS(bedrooms - ?) / 5) * 0.2 +
                (1 - ABS(bathrooms - ?) / 3) * 0.2 +
                (1 - ABS(area - ?) / 200) * 0.2 AS similarity_score
            ', [
                $preferences['avg_price'],
                max($preferences['avg_price'], 1),
                $preferences['avg_bedrooms'],
                $preferences['avg_bathrooms'],
                $preferences['avg_area'],
            ])
            ->orderByDesc('similarity_score')
            ->limit($limit);
        
        return $query->get()->toArray();
    }
    
    /**
     * Obtenir les propriétés les plus populaires
     */
    private function getPopularProperties(int $limit): array
    {
        return Property::select('properties.*')
            ->leftJoin('activity_logs', function ($join) {
                $join->on('properties.id', '=', 'activity_logs.model_id')
                    ->where('activity_logs.model_type', '=', Property::class)
                    ->where('activity_logs.action', '=', 'property_viewed');
            })
            ->where('properties.status', 'available')
            ->groupBy('properties.id')
            ->orderByRaw('COUNT(activity_logs.id) DESC')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}