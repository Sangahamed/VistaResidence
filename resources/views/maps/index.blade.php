@extends('layouts.app')

@section('styles')
<link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
<style>
    #map {
        width: 100%;
        height: 600px;
    }
    .mapboxgl-popup {
        max-width: 300px;
    }
    .property-popup {
        padding: 0;
    }
    .property-popup img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    .property-popup-content {
        padding: 12px;
    }
    .property-popup h3 {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 600;
    }
    .property-popup p {
        margin: 0 0 5px 0;
        font-size: 14px;
    }
    .property-popup .price {
        font-weight: 600;
        color: #4f46e5;
    }
    .property-popup .details {
        display: flex;
        gap: 10px;
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }
    .map-controls {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .poi-toggle {
        margin-top: 15px;
    }
    .poi-toggle h3 {
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 600;
    }
    .poi-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .poi-option {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .poi-option input {
        margin: 0;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Carte des propriétés</h1>
            <p class="text-muted-foreground">
                Explorez les propriétés disponibles sur la carte et découvrez les points d'intérêt à proximité.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1">
                <div class="map-controls">
                    <h2 class="text-lg font-semibold mb-4">Filtres</h2>
                    <form action="{{ route('maps.index') }}" method="GET" id="map-filter-form">
                        <div class="space-y-4">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Localisation</label>
                                <input type="text" name="location" id="location" value="{{ request('location') }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                    placeholder="Ville, code postal...">
                            </div>
                            
                            <div>
                                <label for="property_type" class="block text-sm font-medium text-gray-700">Type de bien</label>
                                <select name="property_type" id="property_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                                    <option value="">Tous les types</option>
                                    <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Appartement</option>
                                    <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>Maison</option>
                                    <option value="villa" {{ request('property_type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="land" {{ request('property_type') == 'land' ? 'selected' : '' }}>Terrain</option>
                                    <option value="commercial" {{ request('property_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_price" class="block text-sm font-medium text-gray-700">Prix min</label>
                                    <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                        placeholder="€">
                                </div>
                                <div>
                                    <label for="max_price" class="block text-sm font-medium text-gray-700">Prix max</label>
                                    <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                        placeholder="€">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_bedrooms" class="block text-sm font-medium text-gray-700">Chambres min</label>
                                    <input type="number" name="min_bedrooms" id="min_bedrooms" value="{{ request('min_bedrooms') }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                        min="0">
                                </div>
                                <div>
                                    <label for="min_bathrooms" class="block text-sm font-medium text-gray-700">SDB min</label>
                                    <input type="number" name="min_bathrooms" id="min_bathrooms" value="{{ request('min_bathrooms') }}" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm"
                                        min="0">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Appliquer les filtres
                            </button>
                        </div>
                    </form>
                    
                    <div class="poi-toggle">
                        <h3>Points d'intérêt</h3>
                        <div class="poi-options">
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="school" class="poi-checkbox" data-poi-type="school">
                                <span>Écoles</span>
                            </label>
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="restaurant" class="poi-checkbox" data-poi-type="restaurant">
                                <span>Restaurants</span>
                            </label>
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="park" class="poi-checkbox" data-poi-type="park">
                                <span>Parcs</span>
                            </label>
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="hospital" class="poi-checkbox" data-poi-type="hospital">
                                <span>Hôpitaux</span>
                            </label>
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="shopping" class="poi-checkbox" data-poi-type="shopping">
                                <span>Commerces</span>
                            </label>
                            <label class="poi-option">
                                <input type="checkbox" name="poi_types[]" value="transport" class="poi-checkbox" data-poi-type="transport">
                                <span>Transports</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-3">
                <div id="map"></div>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle"></i> Cliquez sur un marqueur pour voir les détails de la propriété.
                </p>
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
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [2.3522, 48.8566], // Default to Paris
            zoom: 12
        });
        
        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());
        
        // Add geolocate control
        map.addControl(new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true
        }));
        
        // Load properties when map is loaded
        map.on('load', function() {
            loadProperties();
            
            // Add source for POIs
            map.addSource('poi-source', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: []
                }
            });
            
            // Add POI layers
            const poiTypes = ['school', 'restaurant', 'park', 'hospital', 'shopping', 'transport'];
            const poiColors = {
                'school': '#4285F4',
                'restaurant': '#EA4335',
                'park': '#34A853',
                'hospital': '#FBBC05',
                'shopping': '#FF6D01',
                'transport': '#46BDC6'
            };
            
            poiTypes.forEach(type => {
                map.addLayer({
                    id: `poi-${type}`,
                    type: 'circle',
                    source: 'poi-source',
                    paint: {
                        'circle-radius': 6,
                        'circle-color': poiColors[type],
                        'circle-stroke-width': 1,
                        'circle-stroke-color': '#ffffff'
                    },
                    filter: ['==', 'type', type],
                    layout: {
                        'visibility': 'none'
                    }
                });
                
                // Add click event for POIs
                map.on('click', `poi-${type}`, function(e) {
                    const coordinates = e.features[0].geometry.coordinates.slice();
                    const properties = e.features[0].properties;
                    
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(`
                            <div class="p-3">
                                <h3 class="font-medium">${properties.name}</h3>
                                <p class="text-sm text-gray-600">${properties.type}</p>
                                ${properties.description ? `<p class="text-sm mt-2">${properties.description}</p>` : ''}
                                ${properties.address ? `<p class="text-sm text-gray-500 mt-2">${properties.address}</p>` : ''}
                            </div>
                        `)
                        .addTo(map);
                });
                
                // Change cursor on hover
                map.on('mouseenter', `poi-${type}`, function() {
                    map.getCanvas().style.cursor = 'pointer';
                });
                
                map.on('mouseleave', `poi-${type}`, function() {
                    map.getCanvas().style.cursor = '';
                });
            });
        });
        
        // Handle POI checkboxes
        document.querySelectorAll('.poi-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const poiType = this.dataset.poiType;
                const visibility = this.checked ? 'visible' : 'none';
                
                map.setLayoutProperty(`poi-${poiType}`, 'visibility', visibility);
                
                // Load POI data if checked
                if (this.checked) {
                    loadPointsOfInterest([poiType]);
                }
            });
        });
        
        // Function to load properties
        function loadProperties() {
            fetch('{{ route('maps.properties.geojson') }}' + window.location.search)
                .then(response => response.json())
                .then(data => {
                    // Add source
                    if (map.getSource('properties')) {
                        map.getSource('properties').setData(data);
                    } else {
                        map.addSource('properties', {
                            type: 'geojson',
                            data: data
                        });
                        
                        // Add layer
                        map.addLayer({
                            id: 'properties-layer',
                            type: 'circle',
                            source: 'properties',
                            paint: {
                                'circle-radius': 8,
                                'circle-color': '#4f46e5',
                                'circle-stroke-width': 1,
                                'circle-stroke-color': '#ffffff'
                            }
                        });
                        
                        // Add click event
                        map.on('click', 'properties-layer', function(e) {
                            const coordinates = e.features[0].geometry.coordinates.slice();
                            const properties = e.features[0].properties;
                            
                            // Format price
                            const formattedPrice = new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'EUR',
                                maximumFractionDigits: 0
                            }).format(properties.price);
                            
                            // Create popup
                            new mapboxgl.Popup()
                                .setLngLat(coordinates)
                                .setHTML(`
                                    <div class="property-popup">
                                        ${properties.thumbnail ? `<img src="${properties.thumbnail}" alt="${properties.title}">` : ''}
                                        <div class="property-popup-content">
                                            <h3>${properties.title}</h3>
                                            <p class="price">${formattedPrice}</p>
                                            <p>${properties.address}</p>
                                            <div class="details">
                                                <span>${properties.property_type}</span>
                                                ${properties.bedrooms ? `<span>${properties.bedrooms} chambres</span>` : ''}
                                                ${properties.bathrooms ? `<span>${properties.bathrooms} SDB</span>` : ''}
                                            </div>
                                            <a href="${properties.url}" class="block mt-3 text-center bg-primary text-white py-1 px-3 rounded text-sm hover:bg-primary-dark">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                `)
                                .addTo(map);
                        });
                        
                        // Change cursor on hover
                        map.on('mouseenter', 'properties-layer', function() {
                            map.getCanvas().style.cursor = 'pointer';
                        });
                        
                        map.on('mouseleave', 'properties-layer', function() {
                            map.getCanvas().style.cursor = '';
                        });
                        
                        // Fit map to properties
                        if (data.features.length > 0) {
                            const bounds = new mapboxgl.LngLatBounds();
                            
                            data.features.forEach(feature => {
                                bounds.extend(feature.geometry.coordinates);
                            });
                            
                            map.fitBounds(bounds, {
                                padding: 50
                            });
                        }
                    }
                });
        }
        
        // Function to load points of interest
        function loadPointsOfInterest(types) {
            const params = new URLSearchParams();
            types.forEach(type => params.append('types[]', type));
            
            fetch(`{{ route('maps.pois.geojson') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    // Update source data
                    const source = map.getSource('poi-source');
                    
                    if (source) {
                        // Get current data
                        const currentData = source._data;
                        
                        // Filter out existing features of the same types
                        const filteredFeatures = currentData.features.filter(feature => 
                            !types.includes(feature.properties.type)
                        );
                        
                        // Combine with new features
                        const newData = {
                            type: 'FeatureCollection',
                            features: [...filteredFeatures, ...data.features]
                        };
                        
                        // Update source
                        source.setData(newData);
                    }
                });
        }
    });
</script>
@endsection