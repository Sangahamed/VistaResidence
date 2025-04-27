<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PointOfInterest;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Display the map view with properties.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $propertyType = $request->input('property_type');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minBedrooms = $request->input('min_bedrooms');
        $minBathrooms = $request->input('min_bathrooms');
        $location = $request->input('location');
        
        // Base query
        $query = Property::whereNotNull('latitude')
            ->whereNotNull('longitude');
            
        // Apply filters
        if ($propertyType) {
            $query->where('property_type', $propertyType);
        }
        
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        
        if ($minBedrooms) {
            $query->where('bedrooms', '>=', $minBedrooms);
        }
        
        if ($minBathrooms) {
            $query->where('bathrooms', '>=', $minBathrooms);
        }
        
        if ($location) {
            $query->where(function($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%")
                  ->orWhere('postal_code', 'like', "%{$location}%");
            });
        }
        
        // Get properties
        $properties = $query->get();
        
        // Get points of interest if requested
        $pointsOfInterest = [];
        $poiTypes = $request->input('poi_types', []);
        
        if (!empty($poiTypes)) {
            $pointsOfInterest = PointOfInterest::whereIn('type', $poiTypes)->get();
        }
        
        return view('maps.index', compact('properties', 'pointsOfInterest'));
    }

    /**
     * Display the map for a specific property.
     */
    public function showProperty(Property $property)
    {
        // Check if property has coordinates
        if (!$property->latitude || !$property->longitude) {
            return redirect()->route('properties.show', $property)
                ->with('error', 'Cette propriété n\'a pas de coordonnées géographiques.');
        }
        
        // Get nearby points of interest (within 1km)
        $pointsOfInterest = PointOfInterest::selectRaw("*, (
            6371 * acos(
                cos(radians(?)) 
                * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(latitude))
            )
        ) AS distance", [$property->latitude, $property->longitude, $property->latitude])
        ->having('distance', '<', 1)
        ->orderBy('distance')
        ->get();
        
        return view('maps.property', compact('property', 'pointsOfInterest'));
    }

    /**
     * Get properties as GeoJSON for AJAX requests.
     */
    public function getPropertiesGeoJson(Request $request)
    {
        // Similar filtering as in index method
        $query = Property::whereNotNull('latitude')
            ->whereNotNull('longitude');
            
        // Apply filters from request
        // ...
        
        $properties = $query->get();
        
        // Transform to GeoJSON
        $features = [];
        
        foreach ($properties as $property) {
            // Skip properties that don't want to show exact location
            if (!$property->show_exact_location) {
                continue;
            }
            
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$property->longitude, $property->latitude]
                ],
                'properties' => [
                    'id' => $property->id,
                    'title' => $property->title,
                    'price' => $property->price,
                    'address' => $property->address,
                    'property_type' => $property->property_type,
                    'bedrooms' => $property->bedrooms,
                    'bathrooms' => $property->bathrooms,
                    'url' => route('properties.show', $property),
                    'thumbnail' => $property->featured_image ? asset('storage/' . $property->featured_image) : null,
                ]
            ];
        }
        
        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
        
        return response()->json($geoJson);
    }

    /**
     * Get points of interest as GeoJSON for AJAX requests.
     */
    public function getPointsOfInterestGeoJson(Request $request)
    {
        $types = $request->input('types', []);
        
        $query = PointOfInterest::query();
        
        if (!empty($types)) {
            $query->whereIn('type', $types);
        }
        
        $pois = $query->get();
        
        // Transform to GeoJSON
        $features = [];
        
        foreach ($pois as $poi) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$poi->longitude, $poi->latitude]
                ],
                'properties' => [
                    'id' => $poi->id,
                    'name' => $poi->name,
                    'type' => $poi->type,
                    'description' => $poi->description,
                    'address' => $poi->address,
                ]
            ];
        }
        
        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
        
        return response()->json($geoJson);
    }
}
