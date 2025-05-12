<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\UserPreference;
use App\Services\RecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        // $this->middleware('auth')->only(['editPreferences', 'updatePreferences']);
        $this->recommendationService = $recommendationService;
    }

    /**
     * Affiche la page des recommandations
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $data = [
            'trendingProperties' => $this->recommendationService->getTrendingProperties(8),
            'newListings' => $this->getNewListings(),
        ];

        if ($user) {
            $data['personalizedRecommendations'] = $this->recommendationService->getRecommendationsForUser($user, 8);
            $data['recentlyViewed'] = $this->recommendationService->getRecentlyViewedProperties($user, 4);
        } else {
            $data['personalizedRecommendations'] = collect();
            $data['recentlyViewed'] = collect();
        }

        return view('recommendations.index', $data);
    }

    /**
     * Affiche les propriétés similaires
     */
    public function similarProperties(Property $property): View
    {
        $similarProperties = $this->recommendationService->getSimilarProperties($property);

        return view('recommendations.similar', [
            'property' => $property,
            'similarProperties' => $similarProperties,
        ]);
    }

    /**
     * Affiche le formulaire de préférences
     */
    public function editPreferences(): View
    {
        $preferences = UserPreference::firstOrCreate(
            ['user_id' => Auth::id()],
            $this->getDefaultPreferences()
        );

        return view('recommendations.preferences', compact('preferences'));
    }

    /**
     * Met à jour les préférences utilisateur
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validated = $this->validatePreferences($request);

        UserPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $this->formatPreferencesForStorage($validated)
        );

        return redirect()
            ->route('recommendations.preferences')
            ->with('success', 'Vos préférences ont été mises à jour avec succès.');
    }

    /**
     * Enregistre une vue de propriété
     */
    public function recordView(Request $request, Property $property): JsonResponse
    {
        $this->recommendationService->recordPropertyView(
            $property->id,
            Auth::id(),
            $request->session()->getId()
        );

        if (Auth::check()) {
            $this->recommendationService->updateUserPreferencesFromBehavior(Auth::user());
        }

        return response()->json(['success' => true]);
    }

    /**
     * Récupère les nouvelles annonces
     */
    protected function getNewListings()
    {
        return Property::query()
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();
    }

    /**
     * Validation des préférences
     */
    protected function validatePreferences(Request $request): array
    {
        return $request->validate([
            'preferred_locations' => 'nullable|array',
            'preferred_locations.*' => 'string|max:255',
            'preferred_property_types' => 'nullable|array',
            'preferred_property_types.*' => 'string|in:apartment,house,villa,land,commercial',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gt:min_price',
            'min_bedrooms' => 'nullable|integer|min:0',
            'min_bathrooms' => 'nullable|integer|min:0',
            'min_surface' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'preferred_amenities' => 'nullable|array',
            'preferred_amenities.*' => 'string|max:255',
        ]);
    }

    /**
     * Formatage des préférences pour le stockage
     */
    protected function formatPreferencesForStorage(array $validated): array
    {
        return [
            'preferred_locations' => $validated['preferred_locations'] ?? null,
            'preferred_property_types' => $validated['preferred_property_types'] ?? null,
            'min_price' => $validated['min_price'] ?? null,
            'max_price' => $validated['max_price'] ?? null,
            'min_bedrooms' => $validated['min_bedrooms'] ?? null,
            'min_bathrooms' => $validated['min_bathrooms'] ?? null,
            'min_surface' => $validated['min_surface'] ?? null,
            'features' => $validated['features'] ?? null,
            'preferred_amenities' => $validated['preferred_amenities'] ?? null,
        ];
    }

    /**
     * Valeurs par défaut des préférences
     */
    protected function getDefaultPreferences(): array
    {
        return [
            'preferred_locations' => [],
            'preferred_property_types' => ['apartment', 'house'],
            'min_price' => null,
            'max_price' => null,
            'min_bedrooms' => 1,
            'min_bathrooms' => 1,
            'min_surface' => null,
            'features' => [],
            'preferred_amenities' => [],
        ];
    }
}