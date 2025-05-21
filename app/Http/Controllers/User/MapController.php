<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PointOfInterest;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $propertyTypes = Property::select('type')
                           ->distinct()
                           ->pluck('type')
                           ->filter()
                           ->toArray();

        $filters = $request->only([
            'property_type', 
            'min_price', 
            'max_price',
            'min_bedrooms',
            'min_bathrooms',
            'location',
            'poi_types'
        ]);

        $properties = $this->getFilteredProperties($filters);
        $pointsOfInterest = $this->getPointsOfInterest($filters['poi_types'] ?? []);

        return view('maps.index', [
            'propertyTypes' => $propertyTypes,
            'properties' => $properties,
            'pointsOfInterest' => $pointsOfInterest,
            'priceRange' => $this->getPriceRange()
        ]);
    }

    protected function getFilteredProperties(array $filters)
    {
        $query = Property::query()
            ->whereNotNull('location')
            ->when($filters['property_type'] ?? false, fn($q, $type) => $q->where('property_type', $type))
            ->when($filters['min_price'] ?? false, fn($q, $price) => $q->where('price', '>=', $price))
            ->when($filters['max_price'] ?? false, fn($q, $price) => $q->where('price', '<=', $price))
            ->when($filters['min_bedrooms'] ?? false, fn($q, $bedrooms) => $q->where('bedrooms', '>=', $bedrooms))
            ->when($filters['min_bathrooms'] ?? false, fn($q, $bathrooms) => $q->where('bathrooms', '>=', $bathrooms))
            ->when($filters['location'] ?? false, fn($q, $location) => $q->where(function($query) use ($location) {
                $query->where('city', 'like', "%{$location}%")
                      ->orWhere('address', 'like', "%{$location}%")
                      ->orWhere('postal_code', 'like', "%{$location}%");
            }));

        return $query->get();
    }

    protected function getPointsOfInterest(array $types = [])
    {
        if (empty($types)) {
            return collect();
        }

        return PointOfInterest::whereIn('type', $types)->get();
    }

    protected function getPriceRange()
    {
        return [
            'min' => Property::min('price'),
            'max' => Property::max('price')
        ];
    }

    public function showProperty(Property $property)
    {
        abort_unless($property->location, 404, 'Cette propriété n\'a pas de coordonnées géographiques.');

        $pointsOfInterest = PointOfInterest::selectRaw("*, 
            ST_Distance_Sphere(
                POINT(longitude, latitude),
                POINT(?, ?)
            ) / 1000 AS distance", 
            [$property->longitude, $property->latitude]
        )
        ->having('distance', '<', 1)
        ->orderBy('distance')
        ->get();

        return view('maps.property', compact('property', 'pointsOfInterest'));
    }

public function getPropertiesGeoJson(Request $request)
{
    $properties = Property::with([]) // Retirez le with(['images']) si nécessaire
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    $features = $properties->map(function ($property) {
        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    (float)$property->longitude,
                    (float)$property->latitude
                ]
            ],
            'properties' => [
                'id' => $property->id,
                'title' => $property->title,
                'price' => number_format($property->price, 0, '', ' '),
                'address' => $property->address,
                'type' => $property->type,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'url' => route('properties.show', $property),
                'image' => $this->getFirstImageUrl($property),
            ]
        ];
    });

    return response()->json([
        'type' => 'FeatureCollection',
        'features' => $features
    ]);
}

    protected function getPropertyAttributes(Property $property)
    {
        return [
            'id' => $property->id,
            'title' => $property->title,
            'price' => $property->formatted_price,
            'address' => $property->address,
            'type' => $property->property_type,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'url' => route('properties.show', $property),
            'thumbnail' => $property->featured_image_url,
            'distance' => $property->distance ?? null
        ];
    }

    private function getFirstImageUrl($property)
    {
        if (empty($property->images)) {
            return null;
        }
        
        $firstImage = $property->images[0];
        return asset('storage/' . $firstImage['path']);
    }
}