<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\SavedSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PropertySearchController extends Controller
{
    /**
     * Affiche la page de recherche avancée.
     */
    public function index()
    {
        // Récupérer les options pour les filtres
        $propertyTypes = Property::distinct('type')->pluck('type');
        $cities = Property::distinct('city')->pluck('city');
        
        // Récupérer les recherches sauvegardées de l'utilisateur connecté
        $savedSearches = [];
        if (Auth::check()) {
            $savedSearches = SavedSearch::where('user_id', Auth::id())->get();
        }
        
        return view('properties.search', compact('propertyTypes', 'cities', 'savedSearches'));
    }

    /**
     * Effectue la recherche et affiche les résultats.
     */
    public function search(Request $request)
    {
        // Valider les données de recherche
        $validated = $request->validate([
            'type' => 'nullable|string',
            'transaction_type' => 'nullable|string|in:sale,rental',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'min_surface' => 'nullable|numeric|min:0',
            'max_surface' => 'nullable|numeric|min:0',
            'min_rooms' => 'nullable|integer|min:0',
            'max_rooms' => 'nullable|integer|min:0',
            'min_bedrooms' => 'nullable|integer|min:0',
            'max_bedrooms' => 'nullable|integer|min:0',
            'min_bathrooms' => 'nullable|integer|min:0',
            'max_bathrooms' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|string|in:price_asc,price_desc,date_desc,date_asc,surface_asc,surface_desc',
        ]);
        
        // Construire la requête de recherche
        $query = Property::query();
        
        // Filtrer par type de propriété
        if (!empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }
        
        // Filtrer par type de transaction
        if (!empty($validated['transaction_type'])) {
            $query->where('transaction_type', $validated['transaction_type']);
        }
        
        // Filtrer par ville
        if (!empty($validated['city'])) {
            $query->where('city', 'LIKE', '%' . $validated['city'] . '%');
        }
        
        // Filtrer par code postal
        if (!empty($validated['postal_code'])) {
            $query->where('postal_code', 'LIKE', '%' . $validated['postal_code'] . '%');
        }
        
        // Filtrer par prix
        if (!empty($validated['min_price'])) {
            $query->where('price', '>=', $validated['min_price']);
        }
        if (!empty($validated['max_price'])) {
            $query->where('price', '<=', $validated['max_price']);
        }
        
        // Filtrer par surface
        if (!empty($validated['min_surface'])) {
            $query->where('surface', '>=', $validated['min_surface']);
        }
        if (!empty($validated['max_surface'])) {
            $query->where('surface', '<=', $validated['max_surface']);
        }
        
        // Filtrer par nombre de pièces
        if (!empty($validated['min_rooms'])) {
            $query->where('rooms', '>=', $validated['min_rooms']);
        }
        if (!empty($validated['max_rooms'])) {
            $query->where('rooms', '<=', $validated['max_rooms']);
        }
        
        // Filtrer par nombre de chambres
        if (!empty($validated['min_bedrooms'])) {
            $query->where('bedrooms', '>=', $validated['min_bedrooms']);
        }
        if (!empty($validated['max_bedrooms'])) {
            $query->where('bedrooms', '<=', $validated['max_bedrooms']);
        }
        
        // Filtrer par nombre de salles de bain
        if (!empty($validated['min_bathrooms'])) {
            $query->where('bathrooms', '>=', $validated['min_bathrooms']);
        }
        if (!empty($validated['max_bathrooms'])) {
            $query->where('bathrooms', '<=', $validated['max_bathrooms']);
        }
        
        // Filtrer par caractéristiques
        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $feature) {
                $query->whereJsonContains('features', $feature);
            }
        }
        
        // Recherche géographique par rayon
        if (!empty($validated['latitude']) && !empty($validated['longitude']) && !empty($validated['radius'])) {
            $lat = $validated['latitude'];
            $lng = $validated['longitude'];
            $radius = $validated['radius'];
            
            // Calcul de la distance en utilisant la formule de Haversine
            $query->selectRaw("*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance", 
                [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }
        
        // Appliquer le tri
        if (!empty($validated['sort_by'])) {
            switch ($validated['sort_by']) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'surface_asc':
                    $query->orderBy('surface', 'asc');
                    break;
                case 'surface_desc':
                    $query->orderBy('surface', 'desc');
                    break;
            }
        } else {
            // Tri par défaut
            $query->orderBy('created_at', 'desc');
        }
        
        // Exécuter la requête
        $properties = $query->paginate(12)->withQueryString();
        
        // Récupérer les options pour les filtres
        $propertyTypes = Property::distinct('type')->pluck('type');
        $cities = Property::distinct('city')->pluck('city');
        
        // Récupérer les recherches sauvegardées de l'utilisateur connecté
        $savedSearches = [];
        if (Auth::check()) {
            $savedSearches = SavedSearch::where('user_id', Auth::id())->get();
        }
        
        return view('properties.search', compact('properties', 'propertyTypes', 'cities', 'savedSearches', 'validated'));
    }

    /**
     * Sauvegarde une recherche pour l'utilisateur connecté.
     */
    public function saveSearch(Request $request)
    {
        // Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour sauvegarder une recherche.');
        }
        
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alert_frequency' => 'nullable|string|in:instant,daily,weekly,monthly',
            'criteria' => 'required|json',
        ]);
        
        // Créer la recherche sauvegardée
        $savedSearch = SavedSearch::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'criteria' => json_decode($validated['criteria'], true),
            'alert_frequency' => $validated['alert_frequency'],
        ]);
        
        return redirect()->back()->with('success', 'Recherche sauvegardée avec succès.');
    }

    /**
     * Supprime une recherche sauvegardée.
     */
    public function deleteSavedSearch(SavedSearch $savedSearch)
    {
        // Vérifier que l'utilisateur est le propriétaire de la recherche
        if (Auth::id() !== $savedSearch->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cette recherche.');
        }
        
        $savedSearch->delete();
        
        return redirect()->back()->with('success', 'Recherche supprimée avec succès.');
    }

    /**
     * Charge une recherche sauvegardée.
     */
    public function loadSavedSearch(SavedSearch $savedSearch)
    {
        // Vérifier que l'utilisateur est le propriétaire de la recherche
        if (Auth::id() !== $savedSearch->user_id) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à charger cette recherche.');
        }
        
        // Rediriger vers la page de recherche avec les critères sauvegardés
        return redirect()->route('properties.search', $savedSearch->criteria);
    }
}