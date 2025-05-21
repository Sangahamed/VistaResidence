<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\MapDataService;

class MapManager extends Component
{
    public $activeLocation = [51.505, -0.09];
    public $zoomLevel = 13;
    public $filters = [
        'type' => null,
        'price_min' => null,
        'price_max' => null,
        'bedrooms' => null
    ];

    protected $listeners = [
        'updateFilters' => 'handleFiltersUpdate',
        'focusLocation' => 'flyTo'
    ];

    public function handleFiltersUpdate($filters)
    {
        $this->filters = array_merge($this->filters, $filters);
        $this->dispatch('refreshMarkers', 
            MapDataService::getFilteredProperties($this->filters)
        );
    }

    public function flyTo($lat, $lng, $zoom = 15)
    {
        $this->activeLocation = [$lat, $lng];
        $this->zoomLevel = $zoom;
        $this->dispatch('mapFlyTo', $lat, $lng, $zoom);
    }

   // app/Http/Livewire/MapManager.php
public function render()
{
    $properties = Property::query()
        ->when($this->filters['type'], fn($q, $type) => $q->where('type', $type))
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function($property) {
            return [
                'id' => $property->id,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
                'price_formatted' => number_format($property->price, 0, ',', ' ') . ' FCFA',
                'title' => $property->title
            ];
        });

    return view('livewire.map-manager', [
        'properties' => $properties
    ]);
}
}
