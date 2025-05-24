<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Services\PropertyRecommendationService;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    function index(Request $request)
    {
        $properties = Property::query();

        // Gestion de la géolocalisation sécurisée
        $userLocation = auth()->check() ? auth()->user()->location : null;
        $nearby = $userLocation
            ? app(RecommendationService::class)->getNearbyProperties($userLocation->lat, $userLocation->lng)
            : collect();

        // Filtres de recherche
        if ($request->has('type')) {
            $properties->where('type', $request->type);
        }

        if ($request->has('city')) {
            $properties->where('city', $request->city);
        }

        if ($request->filled('status')) {
            $properties->where('status', $request->status);
        }

        // Géolocalisation par l'utilisateur
        if ($request->has('lat') && $request->has('lng')) {
            $service = new PropertyRecommendationService();
            $nearby = $service->getNearbyProperties(
                $request->lat, 
                $request->lng,
                10 // Rayon de 10km
            );
            $properties->whereIn('id', $nearby->pluck('id'));
        }

        $data = array_merge([
            'pageTitle' => 'Accueil',
            'properties' => $properties->paginate(12),
            'propertyTypes' => Property::distinct()->pluck('type'),
            'propertycity' => Property::distinct()->pluck('city'),
            'nearby' => $nearby
        ]);

        Log::info('Affichage de la page accueil.', [
            'url' => request()->fullUrl(),
            'ip' => request()->ip()
        ]);

        return view('front.pages.index', $data);
    }

    public function detail(Property $property)
{
    $data = [
        'pageTitle' => 'Info Propriété',
        'property' => $property,
        'similar' => (new PropertyRecommendationService())->getSimilarProperties($property),
        'properties_count' => Property::where('owner_id', $property->owner_id)->count(),

    ];

    Log::info('Affichage des Informations des Propriétés.', [
        'url' => request()->fullUrl(),
        'ip' => request()->ip()
    ]);

    return view('front.pages.detail', $data);
}

}
