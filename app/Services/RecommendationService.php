<?php

namespace App\Services;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\UserPreference;
use App\Models\SavedSearch;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecommendationService
{
    /**
     * Get personalized recommendations for a user.
     */
    public function getRecommendationsForUser(User $user, $limit = 10)
    {
        // Get user preferences
        $preferences = UserPreference::where('user_id', $user->id)->first();
        
        // Get user's viewed properties
        $viewedPropertyIds = PropertyView::where('user_id', $user->id)
            ->pluck('property_id')
            ->toArray();
            
        // Get user's favorited properties
        $favoritedPropertyIds = Favorite::where('user_id', $user->id)
            ->pluck('property_id')
            ->toArray();
            
        // Get user's saved searches
        $savedSearches = SavedSearch::where('user_id', $user->id)->get();
        
        // Base query for recommendations
        $query = Property::where('status', 'active')
            ->whereNotIn('id', $viewedPropertyIds)
            ->whereNotIn('id', $favoritedPropertyIds);
            
        // Apply preferences if available
        if ($preferences) {
            if ($preferences->preferred_locations) {
                $query->where(function($q) use ($preferences) {
                    foreach ($preferences->preferred_locations as $location) {
                        $q->orWhere('city', 'like', "%{$location}%")
                          ->orWhere('postal_code', 'like', "%{$location}%");
                    }
                });
            }
            
            if ($preferences->preferred_property_types) {
                $query->whereIn('property_type', $preferences->preferred_property_types);
            }
            
            if ($preferences->min_price) {
                $query->where('price', '>=', $preferences->min_price);
            }
            
            if ($preferences->max_price) {
                $query->where('price', '<=', $preferences->max_price);
            }
            
            if ($preferences->min_bedrooms) {
                $query->where('bedrooms', '>=', $preferences->min_bedrooms);
            }
            
            if ($preferences->min_bathrooms) {
                $query->where('bathrooms', '>=', $preferences->min_bathrooms);
            }
            
            if ($preferences->min_surface) {
                $query->where('surface', '>=', $preferences->min_surface);
            }
            
            if ($preferences->has_garden) {
                $query->where('has_garden', true);
            }
            
            if ($preferences->has_balcony) {
                $query->where('has_balcony', true);
            }
            
            if ($preferences->has_parking) {
                $query->where('has_parking', true);
            }
            
            if ($preferences->has_elevator) {
                $query->where('has_elevator', true);
            }
        }
        
        // Apply saved searches criteria
        if ($savedSearches->count() > 0) {
            $query->orWhere(function($mainQuery) use ($savedSearches) {
                foreach ($savedSearches as $savedSearch) {
                    $criteria = json_decode($savedSearch->criteria, true);
                    
                    $mainQuery->orWhere(function($q) use ($criteria) {
                        if (isset($criteria['property_type'])) {
                            $q->where('property_type', $criteria['property_type']);
                        }
                        
                        if (isset($criteria['min_price'])) {
                            $q->where('price', '>=', $criteria['min_price']);
                        }
                        
                        if (isset($criteria['max_price'])) {
                            $q->where('price', '<=', $criteria['max_price']);
                        }
                        
                        if (isset($criteria['min_bedrooms'])) {
                            $q->where('bedrooms', '>=', $criteria['min_bedrooms']);
                        }
                        
                        if (isset($criteria['min_bathrooms'])) {
                            $q->where('bathrooms', '>=', $criteria['min_bathrooms']);
                        }
                        
                        if (isset($criteria['location'])) {
                            $q->where(function($locationQuery) use ($criteria) {
                                $locationQuery->where('city', 'like', "%{$criteria['location']}%")
                                    ->orWhere('postal_code', 'like', "%{$criteria['location']}%");
                            });
                        }
                    });
                }
            });
        }
        
        // Get collaborative recommendations
        $collaborativeRecommendations = $this->getCollaborativeRecommendations($user, $viewedPropertyIds, $favoritedPropertyIds);
        
        // Combine with content-based recommendations
        if (!empty($collaborativeRecommendations)) {
            $query->orWhereIn('id', $collaborativeRecommendations);
        }
        
        // Get final recommendations
        $recommendations = $query->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
            
        return $recommendations;
    }

    /**
     * Get similar properties based on a specific property.
     */
    public function getSimilarProperties(Property $property, $limit = 6)
    {
        return Property::where('id', '!=', $property->id)
            ->where('status', 'active')
            ->where(function($query) use ($property) {
                $query->where('property_type', $property->property_type)
                    ->orWhere('city', $property->city)
                    ->orWhereBetween('price', [$property->price * 0.8, $property->price * 1.2])
                    ->orWhere(function($q) use ($property) {
                        if ($property->bedrooms) {
                            $q->whereBetween('bedrooms', [$property->bedrooms - 1, $property->bedrooms + 1]);
                        }
                    });
            })
            ->orderBy(DB::raw('RAND()'))
            ->take($limit)
            ->get();
    }

    /**
     * Get trending properties based on recent views.
     */
    public function getTrendingProperties($limit = 8)
    {
        $lastWeek = Carbon::now()->subWeek();
        
        return Property::where('status', 'active')
            ->select('properties.*', DB::raw('COUNT(property_views.id) as view_count'))
            ->join('property_views', 'properties.id', '=', 'property_views.property_id')
            ->where('property_views.last_viewed_at', '>=', $lastWeek)
            ->groupBy('properties.id')
            ->orderBy('view_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get recently viewed properties for a user.
     */
    public function getRecentlyViewedProperties(User $user, $limit = 6)
    {
        return Property::select('properties.*', 'property_views.last_viewed_at')
            ->join('property_views', 'properties.id', '=', 'property_views.property_id')
            ->where('property_views.user_id', $user->id)
            ->orderBy('property_views.last_viewed_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get collaborative filtering recommendations.
     */
    private function getCollaborativeRecommendations(User $user, $viewedPropertyIds, $favoritedPropertyIds)
    {
        // Find users with similar viewing patterns
        $similarUsers = PropertyView::select('user_id', DB::raw('COUNT(*) as common_views'))
            ->whereIn('property_id', $viewedPropertyIds)
            ->where('user_id', '!=', $user->id)
            ->groupBy('user_id')
            ->having('common_views', '>=', 3) // Users who viewed at least 3 same properties
            ->orderBy('common_views', 'desc')
            ->take(10)
            ->pluck('user_id')
            ->toArray();
            
        // Find users with similar favorites
        $similarUsersByFavorites = Favorite::select('user_id', DB::raw('COUNT(*) as common_favorites'))
            ->whereIn('property_id', $favoritedPropertyIds)
            ->where('user_id', '!=', $user->id)
            ->groupBy('user_id')
            ->having('common_favorites', '>=', 2) // Users who favorited at least 2 same properties
            ->orderBy('common_favorites', 'desc')
            ->take(10)
            ->pluck('user_id')
            ->toArray();
            
        // Combine similar users
        $similarUsers = array_unique(array_merge($similarUsers, $similarUsersByFavorites));
        
        if (empty($similarUsers)) {
            return [];
        }
        
        // Get properties viewed by similar users but not by the current user
        $recommendedProperties = PropertyView::select('property_id', DB::raw('COUNT(*) as view_count'))
            ->whereIn('user_id', $similarUsers)
            ->whereNotIn('property_id', $viewedPropertyIds)
            ->whereNotIn('property_id', $favoritedPropertyIds)
            ->groupBy('property_id')
            ->orderBy('view_count', 'desc')
            ->take(20)
            ->pluck('property_id')
            ->toArray();
            
        // Get properties favorited by similar users but not by the current user
        $recommendedPropertiesByFavorites = Favorite::select('property_id', DB::raw('COUNT(*) as favorite_count'))
            ->whereIn('user_id', $similarUsers)
            ->whereNotIn('property_id', $viewedPropertyIds)
            ->whereNotIn('property_id', $favoritedPropertyIds)
            ->groupBy('property_id')
            ->orderBy('favorite_count', 'desc')
            ->take(20)
            ->pluck('property_id')
            ->toArray();
            
        // Combine recommended properties
        return array_unique(array_merge($recommendedProperties, $recommendedPropertiesByFavorites));
    }

    /**
     * Record a property view.
     */
    public function recordPropertyView($propertyId, $userId = null, $sessionId = null)
    {
        $now = Carbon::now();
        
        if ($userId) {
            $propertyView = PropertyView::firstOrNew([
                'user_id' => $userId,
                'property_id' => $propertyId,
            ]);
        } else {
            $propertyView = PropertyView::firstOrNew([
                'session_id' => $sessionId,
                'property_id' => $propertyId,
                'user_id' => null,
            ]);
        }
        
        if ($propertyView->exists) {
            $propertyView->view_count += 1;
        } else {
            $propertyView->view_count = 1;
        }
        
        $propertyView->last_viewed_at = $now;
        $propertyView->save();
        
        return $propertyView;
    }

    /**
     * Update user preferences based on behavior.
     */
    public function updateUserPreferencesFromBehavior(User $user)
    {
        // Get user's viewed properties
        $viewedProperties = Property::select('properties.*')
            ->join('property_views', 'properties.id', '=', 'property_views.property_id')
            ->where('property_views.user_id', $user->id)
            ->get();
            
        // Get user's favorited properties
        $favoritedProperties = Property::select('properties.*')
            ->join('favorites', 'properties.id', '=', 'favorites.property_id')
            ->where('favorites.user_id', $user->id)
            ->get();
            
        // Combine properties
        $properties = $viewedProperties->merge($favoritedProperties);
        
        if ($properties->isEmpty()) {
            return;
        }
        
        // Extract preferences
        $propertyTypes = $properties->pluck('property_type')->toArray();
        $cities = $properties->pluck('city')->toArray();
        $prices = $properties->pluck('price')->toArray();
        $bedrooms = $properties->pluck('bedrooms')->filter()->toArray();
        $bathrooms = $properties->pluck('bathrooms')->filter()->toArray();
        $surfaces = $properties->pluck('surface')->filter()->toArray();
        
        // Count occurrences
        $propertyTypeCounts = array_count_values($propertyTypes);
        $cityCounts = array_count_values($cities);
        
        // Get most common property types (at least 20% of views)
        $totalProperties = count($properties);
        $preferredPropertyTypes = [];
        
        foreach ($propertyTypeCounts as $type => $count) {
            if ($count / $totalProperties >= 0.2) {
                $preferredPropertyTypes[] = $type;
            }
        }
        
        // Get most common cities (at least 20% of views)
        $preferredLocations = [];
        
        foreach ($cityCounts as $city => $count) {
            if ($count / $totalProperties >= 0.2) {
                $preferredLocations[] = $city;
            }
        }
        
        // Calculate price range
        $minPrice = !empty($prices) ? min($prices) * 0.8 : null;
        $maxPrice = !empty($prices) ? max($prices) * 1.2 : null;
        
        // Calculate other preferences
        $minBedrooms = !empty($bedrooms) ? min($bedrooms) : null;
        $minBathrooms = !empty($bathrooms) ? min($bathrooms) : null;
        $minSurface = !empty($surfaces) ? min($surfaces) * 0.8 : null;
        
        // Update or create user preferences
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_property_types' => $preferredPropertyTypes,
                'preferred_locations' => $preferredLocations,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'min_bedrooms' => $minBedrooms,
                'min_bathrooms' => $minBathrooms,
                'min_surface' => $minSurface,
            ]
        );
    }
}