<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\UserPreference;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display personalized recommendations.
     */
    public function index()
    {
        // Get personalized recommendations for authenticated user
        if (auth()->check()) {
            $personalizedRecommendations = $this->recommendationService->getRecommendationsForUser(auth()->user(), 8);
            $recentlyViewed = $this->recommendationService->getRecentlyViewedProperties(auth()->user(), 4);
        } else {
            $personalizedRecommendations = collect();
            $recentlyViewed = collect();
        }
        
        // Get trending properties for all users
        $trendingProperties = $this->recommendationService->getTrendingProperties(8);
        
        // Get new listings
        $newListings = Property::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        return view('recommendations.index', compact(
            'personalizedRecommendations',
            'trendingProperties',
            'newListings',
            'recentlyViewed'
        ));
    }

    /**
     * Display similar properties to a specific property.
     */
    public function similarProperties(Property $property)
    {
        $similarProperties = $this->recommendationService->getSimilarProperties($property);
        
        return view('recommendations.similar', compact('property', 'similarProperties'));
    }

    /**
     * Show the form for editing user preferences.
     */
    public function editPreferences()
    {
        $preferences = UserPreference::firstOrNew(['user_id' => auth()->id()]);
        
        return view('recommendations.preferences', compact('preferences'));
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'preferred_locations' => 'nullable|array',
            'preferred_locations.*' => 'string',
            'preferred_property_types' => 'nullable|array',
            'preferred_property_types.*' => 'string|in:apartment,house,villa,land,commercial',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gt:min_price',
            'min_bedrooms' => 'nullable|integer|min:0',
            'min_bathrooms' => 'nullable|integer|min:0',
            'min_surface' => 'nullable|numeric|min:0',
            'has_garden' => 'boolean',
            'has_balcony' => 'boolean',
            'has_parking' => 'boolean',
            'has_elevator' => 'boolean',
            'preferred_amenities' => 'nullable|array',
            'preferred_amenities.*' => 'string',
        ]);
        
        UserPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'preferred_locations' => $request->input('preferred_locations'),
                'preferred_property_types' => $request->input('preferred_property_types'),
                'min_price' => $request->input('min_price'),
                'max_price' => $request->input('max_price'),
                'min_bedrooms' => $request->input('min_bedrooms'),
                'min_bathrooms' => $request->input('min_bathrooms'),
                'min_surface' => $request->input('min_surface'),
                'has_garden' => $request->boolean('has_garden'),
                'has_balcony' => $request->boolean('has_balcony'),
                'has_parking' => $request->boolean('has_parking'),
                'has_elevator' => $request->boolean('has_elevator'),
                'preferred_amenities' => $request->input('preferred_amenities'),
            ]
        );
        
        return redirect()->route('recommendations.preferences')
            ->with('success', 'Vos préférences ont été mises à jour avec succès.');
    }

    /**
     * Record a property view.
     */
    public function recordView(Request $request, Property $property)
    {
        $userId = auth()->id();
        $sessionId = $request->session()->getId();
        
        $this->recommendationService->recordPropertyView($property->id, $userId, $sessionId);
        
        // Update user preferences if authenticated
        if ($userId) {
            $this->recommendationService->updateUserPreferencesFromBehavior(auth()->user());
        }
        
        return response()->json(['success' => true]);
    }
}
