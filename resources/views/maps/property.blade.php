@extends('layouts.app')

@section('content')
<div class="flex flex-col h-screen bg-gray-900 text-white">
    <!-- Header -->
    <div class="bg-gray-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">
                {{ $property->title }}
            </h1>
            <a href="{{ route('properties.show', $property) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                Retour à la fiche
            </a>
        </div>
    </div>
    
    <!-- Map and Info Container -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Map Container -->
        <div id="map" class="flex-1"></div>
        
        <!-- Nearby POIs Panel -->
        <div class="w-80 bg-gray-800 p-4 overflow-y-auto border-l border-gray-700">
            <h2 class="text-xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">
                Points d'intérêt à proximité
            </h2>
            
            @if($pointsOfInterest->isEmpty())
                <p class="text-gray-400">Aucun point d'intérêt à proximité.</p>
            @else
                <div class="space-y-3">
                    @foreach($pointsOfInterest as $poi)
                        <div class="bg-gray-700 p-3 rounded-lg hover:bg-gray-600 transition-all duration-300 cursor-pointer" 
                             onclick="highlightPOI({{ $poi->id }})">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    @if($poi->type === 'school')
                                        <div class="p-2 bg-green-600 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                    @elseif($poi->type === 'transport')
                                        <div class="p-2 bg-blue-600 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3zM3 5h2v2H3V5zm0 4h2v2H3V9zm0 4h2v2H3v-2zm12-8h2v2h-2V5zm0 4h2v2h-2V9zm0 4h2v2h-2v-2z" />
                                            </svg>
                                        </div>
                                    @elseif($poi->type === 'park')
                                        <div class="p-2 bg-emerald-600 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="p-2 bg-purple-600 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium">{{ $poi->name }}</h3>
                                    <p class="text-sm text-gray-300">{{ $poi->address }}</p>
                                    <p class="text-xs mt-1 text-gray-400">{{ $poi->description }}</p>
                                    <div class="mt-1 text-xs">
                                        <span class="inline-block px-2 py-1 rounded-full 
                                            {{ $poi->type === 'school' ? 'bg-green-600' : 
                                               ($poi->type === 'transport' ? 'bg-blue-600' : 
                                               ($poi->type === 'park' ? 'bg-emerald-600' : 'bg-purple-600')) }}">
                                            {{ $poi->type === 'school' ? 'École' : 
                                               ($poi->type === 'transport' ? 'Transport' : 
                                               ($poi->type === 'park' ? 'Parc' : 'Commerce')) }}
                                        </span>
                                        <span class="ml-2 text-gray-400">{{ number_format($poi->distance * 1000, 0) }}m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map centered on the property
    const map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);
    
    // Custom icons
    const propertyIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/619/619153.png',
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });
    
    const poiIcons = {
        school: L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/2583/2583344.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        }),
        transport: L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/2907/2907253.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        }),
        park: L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/484/484167.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        }),
        shopping: L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/891/891462.png',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        })
    };
    
    // Add property marker
    const propertyMarker = L.marker([{{ $property->latitude }}, {{ $property->longitude }}], { 
        icon: propertyIcon,
        zIndexOffset: 1000
    }).addTo(map).bindPopup(`
        <div class="w-64">
            <h4 class="font-bold text-lg">{{ $property->title }}</h4>
            <p class="text-blue-400 font-semibold">{{ number_format($property->price, 0, ',', ' ') }} €</p>
            <p class="text-sm text-gray-300">{{ $property->address }}</p>
            <div class="flex justify-between mt-2 text-sm">
                <span>{{ $property->bedrooms }} <i class="fas fa-bed"></i></span>
                <span>{{ $property->bathrooms }} <i class="fas fa-bath"></i></span>
                <span>{{ $property->property_type }}</span>
            </div>
        </div>
    `).openPopup();
    
    // Add circle around property to show proximity
    L.circle([{{ $property->latitude }}, {{ $property->longitude }}], {
        color: '#3b82f6',
        fillColor: '#3b82f6',
        fillOpacity: 0.1,
        radius: 1000 // 1km radius
    }).addTo(map);
    
    // Store POI markers for highlighting
    const poiMarkers = {};
    
    // Add POI markers
    @foreach($pointsOfInterest as $poi)
        poiMarkers[{{ $poi->id }}] = L.marker([{{ $poi->latitude }}, {{ $poi->longitude }}], { 
            icon: poiIcons['{{ $poi->type }}'],
            poiId: {{ $poi->id }}
        }).addTo(map).bindPopup(`
            <div class="w-64">
                <h4 class="font-bold text-lg">{{ $poi->name }}</h4>
                <p class="text-sm text-gray-300">{{ $poi->address }}</p>
                <p class="text-sm mt-1">{{ $poi->description }}</p>
                <div class="mt-1 text-xs px-2 py-1 
                    {{ $poi->type === 'school' ? 'bg-green-600' : 
                       ($poi->type === 'transport' ? 'bg-blue-600' : 
                       ($poi->type === 'park' ? 'bg-emerald-600' : 'bg-purple-600')) }} 
                    rounded-full inline-block">
                    {{ $poi->type === 'school' ? 'École' : 
                       ($poi->type === 'transport' ? 'Transport' : 
                       ($poi->type === 'park' ? 'Parc' : 'Commerce')) }}
                </div>
                <div class="mt-1 text-xs text-gray-400">{{ number_format($poi->distance * 1000, 0) }}m de la propriété</div>
            </div>
        `);
    @endforeach
    
    // Function to highlight a POI
    window.highlightPOI = function(poiId) {
        // Close all popups first
        map.eachLayer(layer => {
            if (layer instanceof L.Marker && layer.getPopup()) {
                layer.closePopup();
            }
        });
        
        // Highlight the selected POI
        const marker = poiMarkers[poiId];
        if (marker) {
            // Pan to the marker
            map.panTo(marker.getLatLng());
            
            // Open its popup
            marker.openPopup();
            
            // Add a pulsing effect
            const icon = marker.getIcon();
            const originalSize = icon.options.iconSize;
            
            // Animate the icon
            let size = originalSize[0];
            let growing = true;
            const interval = setInterval(() => {
                if (growing) {
                    size += 2;
                    if (size >= originalSize[0] + 10) growing = false;
                } else {
                    size -= 2;
                    if (size <= originalSize[0]) {
                        clearInterval(interval);
                        size = originalSize[0];
                    }
                }
                
                icon.options.iconSize = [size, size];
                icon.options.iconAnchor = [size/2, size];
                marker.setIcon(icon);
            }, 50);
        }
    };
});
</script>
@endsection