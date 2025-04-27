@extends('layouts.app')

@section('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
<style>
    #property-map {
        width: 100%;
        height: 400px;
    }
    .mapboxgl-popup {
        max-width: 300px;
    }
    .poi-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    .poi-legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }
    .poi-legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <a href="{{ route('properties.show', $property) }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la propriété
            </a>
            <h1 class="text-3xl font-bold tracking-tight">{{ $property->title }}</h1>
            <p class="text-muted-foreground">
                {{ $property->address }}, {{ $property->city }} {{ $property->postal_code }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div id="property-map"></div>
            
            <div class="p-4">
                <h2 class="text-lg font-semibold mb-2">Points d'intérêt à proximité</h2>
                
                <div class="poi-legend">
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #4f46e5;"></span>
                        <span>Propriété</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #4285F4;"></span>
                        <span>Écoles</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #EA4335;"></span>
                        <span>Restaurants</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #34A853;"></span>
                        <span>Parcs</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #FBBC05;"></span>
                        <span>Hôpitaux</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #FF6D01;"></span>
                        <span>Commerces</span>
                    </div>
                    <div class="poi-legend-item">
                        <span class="poi-legend-color" style="background-color: #46BDC6;"></span>
                        <span>Transports</span>
                    </div>
                </div>
                
                @if($pointsOfInterest->count() > 0)
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pointsOfInterest as $poi)
                            <div class="border rounded-lg p-3">
                                <h3 class="font-medium">{{ $poi->name }}</h3>
                                <p class="text-sm text-gray-600">{{ ucfirst($poi->type) }}</p>
                                @if($poi->description)
                                    <p class="text-sm mt-2">{{ $poi->description }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    Distance: {{ number_format($poi->distance * 1000, 0) }} m
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 mt-4">Aucun point d'intérêt trouvé à proximité.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Mapbox
        mapboxgl.accessToken = '{{ env('MAPBOX_ACCESS_TOKEN') }}';
        
        const map = new mapboxgl.Map({
            container: 'property-map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [{{ $property->longitude }}, {{ $property->latitude }}],
            zoom: 15
        });
        
        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());
        
        // Add property marker
        new mapboxgl.Marker({
            color: '#4f46e5'
        })
        .setLngLat([{{ $property->longitude }}, {{ $property->latitude }}])
        .setPopup(new mapboxgl.Popup().setHTML(`
            <div class="p-3">
                <h3 class="font-medium">{{ $property->title }}</h3>
                <p class="text-sm">{{ $property->address }}</p>
                <p class="text-sm font-semibold mt-1">{{ number_format($property->price, 0, ',', ' ') }} €</p>
            </div>
        `))
        .addTo(map);
        
        // Add POI markers
        const poiColors = {
            'school': '#4285F4',
            'restaurant': '#EA4335',
            'park': '#34A853',
            'hospital': '#FBBC05',
            'shopping': '#FF6D01',
            'transport': '#46BDC6'
        };
        
        @foreach($pointsOfInterest as $poi)
            new mapboxgl.Marker({
                color: poiColors['{{ $poi->type }}'] || '#000000'
            })
            .setLngLat([{{ $poi->longitude }}, {{ $poi->latitude }}])
            .setPopup(new mapboxgl.Popup().setHTML(`
                <div class="p-3">
                    <h3 class="font-medium">{{ $poi->name }}</h3>
                    <p class="text-sm text-gray-600">{{ ucfirst($poi->type) }}</p>
                    @if($poi->description)
                        <p class="text-sm mt-2">{{ $poi->description }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">
                        Distance: {{ number_format($poi->distance * 1000, 0) }} m
                    </p>
                </div>
            `))
            .addTo(map);
        @endforeach
    });
</script>
@endsection