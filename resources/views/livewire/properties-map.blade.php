<div class="flex flex-col h-screen bg-gray-50">
    <!-- Header mobile uniquement -->
    <div class="md:hidden bg-white shadow-sm py-3 px-4 flex items-center justify-between z-50 flex-shrink-0">
        <h1 class="text-xl font-bold text-indigo-600 flex items-center">
            <i class="fas fa-map-marked-alt mr-2"></i> PropertyFinder
        </h1>

        <div class="flex items-center space-x-3">
            <button wire:click="toggleViewMode"
                class="flex items-center bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg text-sm">
                <i class="fas {{ $viewMode === 'map' ? 'fa-list' : 'fa-map' }} mr-1"></i>
                {{ $viewMode === 'map' ? 'Liste' : 'Carte' }}
            </button>

            <button wire:click="toggleSidebar" class="text-gray-600 p-2">
                <i class="fas fa-filter text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex flex-1 overflow-hidden relative min-h-0">
        <!-- Sidebar -->
        <aside
            class="w-full md:w-80 bg-white shadow-lg z-40 transition-all duration-300 transform {{ $isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0' }} absolute md:relative h-full overflow-y-auto">
            <div class="p-4 md:p-6 h-full">
                <!-- Header desktop dans la sidebar -->
                <div class="hidden md:block mb-6">
                    <h1 class="text-2xl font-bold text-indigo-600 flex items-center mb-4">
                        <i class="fas fa-map-marked-alt mr-2"></i> PropertyFinder
                    </h1>
                </div>

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-sliders-h mr-2"></i> Filtres
                    </h2>
                    <button wire:click="toggleSidebar" class="md:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- User Position Info -->
                @if ($userPosition)
                    <div
                        class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-100">
                        <div class="flex items-center text-sm text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                            <span>{{ $userPosition['city'] ?? 'Position inconnue' }}</span>
                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">
                                {{ $userPosition['source'] ?? 'IP' }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="refreshLocation"
                                class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center transition-colors">
                                <i class="fas fa-sync-alt mr-1"></i> Actualiser IP
                            </button>
                            <button onclick="window.mapInstance?.requestGeolocation()"
                                class="text-xs text-green-600 hover:text-green-800 flex items-center transition-colors">
                                <i class="fas fa-crosshairs mr-1"></i> GPS précis
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Search -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            placeholder="Propriété, localisation..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <div wire:loading wire:target="search" class="absolute right-3 top-3">
                            <i class="fas fa-spinner animate-spin text-indigo-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de propriété</label>
                        <select wire:model.live="type"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <option value="">Tous les types</option>
                            @foreach ($propertyTypes as $propertyType)
                                <option value="{{ $propertyType }}">{{ ucfirst($propertyType) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gamme de prix (FCFA)</label>
                        <div class="flex items-center space-x-2">
                            <input type="number" wire:model.live.debounce.500ms="priceMin" placeholder="Min"
                                class="w-1/2 border-gray-300 rounded-lg focus:ring-indigo-500 transition-all duration-200">
                            <span class="text-gray-400">-</span>
                            <input type="number" wire:model.live.debounce.500ms="priceMax" placeholder="Max"
                                class="w-1/2 border-gray-300 rounded-lg focus:ring-indigo-500 transition-all duration-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville/Zone</label>
                        <input type="text" wire:model.live.debounce.500ms="city" placeholder="Entrez la ville..."
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 transition-all duration-200">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-2">
                    <button wire:click="loadProperties"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <button wire:click="resetFilters"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg transition-all duration-200">
                        <i class="fas fa-undo mr-2"></i>
                        Réinitialiser tout
                    </button>
                </div>

                <!-- Results Count -->
                <div class="mt-6 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                    <div class="text-sm text-gray-700 flex items-center">
                        <i class="fas fa-home mr-2 text-indigo-500"></i>
                        <span class="font-medium">{{ $totalProperties }}</span>
                        <span class="ml-1">propriétés trouvées</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        <span id="visibleCount">{{ count($properties) }}</span> visibles sur la carte
                    </div>
                </div>

                <!-- Performance Info -->
                <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-xs text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span>Dernière mise à jour: <span id="lastUpdate">--:--:--</span></span>
                    </div>
                    <div class="text-xs text-blue-600 mt-1">
                        <span>Temps de chargement: <span id="loadTime">0</span>ms</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 relative min-h-0">
            <!-- Desktop Header dans le main content -->
            <div class="hidden md:block absolute top-0 left-0 right-0 z-30 bg-white/90 backdrop-blur-sm shadow-sm pt-16">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-4">
                        <button wire:click="toggleViewMode"
                            class="flex items-center space-x-2 {{ $viewMode === 'map' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600' }} px-4 py-2 rounded-lg shadow-md transition-all"
                            title="Vue Carte">
                            <i class="fas fa-map"></i>
                            <span class="hidden lg:inline">Carte</span>
                        </button>
                        <button wire:click="toggleViewMode"
                            class="flex items-center space-x-2 {{ $viewMode === 'list' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600' }} px-4 py-2 rounded-lg shadow-md transition-all"
                            title="Vue Liste">
                            <i class="fas fa-list"></i>
                            <span class="hidden lg:inline">Liste</span>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button onclick="window.mapInstance?.requestGeolocation()"
                            class="bg-white text-indigo-600 p-2 rounded-lg shadow-md hover:bg-indigo-50 transition-all"
                            title="Ma position">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                        <button onclick="window.mapInstance?.toggleFullscreen()"
                            class="bg-white text-gray-600 p-2 rounded-lg shadow-md hover:bg-gray-50 transition-all"
                            title="Plein écran">
                            <i class="fas fa-expand" id="fullscreenIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map View -->
            @if ($viewMode === 'map')
                <div class="h-full w-full relative">
                    <div id="map" wire:ignore class="h-full w-full {{ $isSidebarOpen ? 'md:ml-0' : '' }}">
                    </div>

                    <!-- Map Controls Mobile -->
                    <div class="md:hidden absolute top-4 left-4 z-30 space-y-2">
                        <div class="bg-white rounded-lg shadow-lg p-1">
                            <button onclick="window.mapInstance?.zoomIn()"
                                class="block w-10 h-10 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button onclick="window.mapInstance?.zoomOut()"
                                class="block w-10 h-10 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Map Controls Desktop -->
                    <div class="hidden md:block absolute top-44 left-4 z-30 space-y-2">
                        <div class="bg-white rounded-lg shadow-lg p-1">
                            <button onclick="window.mapInstance?.zoomIn()"
                                class="block w-10 h-10 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button onclick="window.mapInstance?.zoomOut()"
                                class="block w-10 h-10 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Geolocation Button Mobile -->
                    <button onclick="window.mapInstance?.requestGeolocation()"
                        class="md:hidden absolute bottom-20 right-4 z-30 bg-white p-3 rounded-full shadow-lg text-indigo-600 hover:bg-indigo-50">
                        <i class="fas fa-crosshairs"></i>
                    </button>
                </div>
            @endif

            <!-- List View -->
            @if ($viewMode === 'list')
                <div class="h-full overflow-y-auto {{ $isSidebarOpen ? 'md:pt-16' : 'md:pt-16' }} pt-4">
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($properties as $index => $property)
                                <div
                                    class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 property-card">
                                    <div class="relative h-48 overflow-hidden">
                                        <!-- Image with fallback -->
                                        <img src="{{ $property['properties']['image'] ?? '/storage/images/default-property.jpg' }}"
                                            alt="{{ $property['properties']['title'] }}"
                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                            onerror="this.src='/storage/images/default-property.jpg'">

                                        <!-- Favorite Button -->
                                        <div class="absolute top-2 right-2">
                                            <button wire:click="toggleFavorite({{ $property['properties']['id'] }})"
                                                class="bg-white bg-opacity-90 backdrop-blur-sm rounded-full p-2 shadow-md hover:bg-opacity-100 transition-all duration-200 transform hover:scale-110 {{ $property['properties']['is_favorited'] ? 'text-red-500' : 'text-gray-400' }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>

                                        <!-- Type Badge -->
                                        <div class="absolute top-2 left-2">
                                            @php
                                                $typeLabels = [
                                                    'house' => ['Maison', 'bg-blue-500'],
                                                    'apartment' => ['Appartement', 'bg-green-500'],
                                                    'land' => ['Terrain', 'bg-yellow-500'],
                                                    'commercial' => ['Commercial', 'bg-purple-500'],
                                                    'villa' => ['Villa', 'bg-red-500'],
                                                ];
                                                $type = $property['properties']['type'] ?? 'house';
                                                $label = $typeLabels[$type] ?? ['Propriété', 'bg-gray-500'];
                                            @endphp
                                            <span
                                                class="inline-block px-2 py-1 text-xs text-white rounded-full {{ $label[1] }}">
                                                {{ $label[0] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h3
                                            class="font-bold text-lg mb-2 text-gray-900 hover:text-indigo-600 transition-colors">
                                            {{ $property['properties']['title'] }}
                                        </h3>
                                        <p class="text-indigo-600 font-semibold text-xl mb-2">
                                            {{ $property['properties']['price'] }} FCFA
                                        </p>
                                        <p class="text-sm text-gray-600 mb-3 flex items-center">
                                            <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i>
                                            {{ $property['properties']['address'] }},
                                            {{ $property['properties']['city'] }}
                                        </p>

                                        <!-- Property Details -->
                                        <div class="flex items-center text-sm text-gray-500 mb-4 space-x-4">
                                            <span class="flex items-center">
                                                <i class="fas fa-bed mr-1"></i>
                                                {{ $property['properties']['bedrooms'] }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-bath mr-1"></i>
                                                {{ $property['properties']['bathrooms'] }}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="fas fa-ruler-combined mr-1"></i>
                                                {{ $property['properties']['area'] }} m²
                                            </span>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            <a href="{{ $property['properties']['url'] }}"
                                                class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir détails
                                            </a>
                                            <button
                                                onclick="window.mapInstance?.getDirections({{ $property['geometry']['coordinates'][1] }}, {{ $property['geometry']['coordinates'][0] }})"
                                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg transition-all duration-200 transform hover:scale-105"
                                                title="Itinéraire">
                                                <i class="fas fa-directions"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-16">
                                    <div class="animate-bounce">
                                        <i class="fas fa-home text-6xl text-gray-300 mb-4"></i>
                                    </div>
                                    <h3 class="text-xl font-medium text-gray-600 mb-2">Aucune propriété trouvée</h3>
                                    <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
                                    <button wire:click="resetFilters"
                                        class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg">
                                        <i class="fas fa-undo mr-2"></i> Réinitialiser les filtres
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- Mobile Filter Button -->
    <button wire:click="toggleSidebar"
        class="md:hidden fixed bottom-4 right-4 z-50 bg-indigo-600 text-white p-4 rounded-full shadow-lg hover:bg-indigo-700 transition-all duration-200 transform hover:scale-110">
        <i class="fas fa-filter"></i>
    </button>

    <!-- Geolocation Permission Modal -->
    <div id="geoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md mx-4 transform transition-all duration-300">
            <div class="text-center">
                <i class="fas fa-location-arrow text-4xl text-indigo-600 mb-4"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Autoriser la géolocalisation</h3>
                <p class="text-gray-600 mb-6">
                    Autorisez l'accès à votre position pour voir les propriétés les plus proches de vous.
                </p>
                <div class="flex space-x-3">
                    <button onclick="window.mapInstance?.hideGeoModal()"
                        class="flex-1 bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition-colors">
                        Annuler
                    </button>
                    <button onclick="window.mapInstance?.enableGeolocation()"
                        class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors">
                        Autoriser
                    </button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

        <script>
            class PropertyMap {
                constructor() {
                    this.map = null;
                    this.markerCluster = null;
                    this.userLocationMarker = null;
                    this.initialized = false;
                    this.mapHasBeenMoved = false;
                    this.isFullscreen = false;

                    this.init();
                }

                init() {
                    // Détruire la carte existante si elle existe
                    if (this.map) {
                        this.map.remove();
                        this.map = null;
                    }

                    if (document.getElementById('map')) {
                        this.initMap();
                        this.setupEventListeners();
                        this.initialized = true;

                        // Si on est en mode carte, charger les marqueurs
                        if (@json($viewMode) === 'map') {
                            this.updateMarkers({
                                properties: @json($properties),
                                shouldFitBounds: true
                            });
                        }
                    }
                }

                initMap() {
                    this.map = L.map('map', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView(
                        [@json($mapCenter['lat']), @json($mapCenter['lng'])],
                        @json($zoom)
                    );

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(this.map);

                    this.markerCluster = L.markerClusterGroup({
                        spiderfyOnMaxZoom: true,
                        showCoverageOnHover: false,
                        maxClusterRadius: 60,
                        animateAddingMarkers: true
                    });
                    this.map.addLayer(this.markerCluster);

                    // Fullscreen change listener
                    document.addEventListener('fullscreenchange', () => {
                        this.isFullscreen = !!document.fullscreenElement;
                        this.updateFullscreenIcon();
                        setTimeout(() => this.map.invalidateSize(), 100);
                    });
                }

                setupEventListeners() {
                    // Écouteur pour le déplacement de la carte
                    this.map.on('moveend', () => {
                        const center = this.map.getCenter();
                        const bounds = this.map.getBounds();

                        @this.call('mapMoved', {
                            lat: center.lat,
                            lng: center.lng
                        }, this.map.getZoom(), {
                            north: bounds.getNorth(),
                            south: bounds.getSouth(),
                            east: bounds.getEast(),
                            west: bounds.getWest()
                        });
                    });

                    // Écouteur pour les mises à jour des propriétés
                    window.addEventListener('propertiesUpdated', (event) => {
                        this.updateMarkers(event.detail);
                    });

                    // Écouteur pour les changements de mode
                    window.addEventListener('viewModeChanged', () => {
                        if (@json($viewMode) === 'map') {
                            setTimeout(() => {
                                this.map.invalidateSize();
                                this.updateMarkers({
                                    properties: @json($properties),
                                    shouldFitBounds: false
                                });
                            }, 100);
                        }
                    });
                }

                updateMarkers(eventData) {
                    if (!this.markerCluster || !this.map) return;

                    this.markerCluster.clearLayers();

                    const properties = eventData.properties || [];

                    properties.forEach((feature) => {
                        if (!feature.geometry || !feature.geometry.coordinates) return;

                        const marker = L.marker(
                            [feature.geometry.coordinates[1], feature.geometry.coordinates[0]], {
                                icon: this.getCustomIcon(feature.properties.type),
                                riseOnHover: true
                            }
                        ).bindPopup(this.createPopupContent(feature.properties));

                        this.markerCluster.addLayer(marker);
                    });

                    if (properties.length > 0 && eventData.shouldFitBounds) {
                        setTimeout(() => {
                            this.fitAllMarkers();
                        }, 100);
                    }

                    // Mettre à jour le compteur
                    document.getElementById('visibleCount').textContent = properties.length;
                    document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
                }

                getCustomIcon(type) {
                    // URLs des icônes PNG par type de propriété
                    const typeIcons = {
                        house: 'https://cdn-icons-png.flaticon.com/512/619/619153.png',
                        apartment: 'https://cdn-icons-png.flaticon.com/512/2674/2674468.png',
                        land: 'https://cdn-icons-png.flaticon.com/512/1048/1048953.png',
                        commercial: 'https://cdn-icons-png.flaticon.com/512/869/869636.png',
                        villa: 'https://cdn-icons-png.flaticon.com/512/1946/1946488.png'
                    };

                    const iconUrl = typeIcons[type] || typeIcons.house;

                    return L.icon({
                        iconUrl: iconUrl,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32],
                        className: 'custom-marker'
                    });
                }

                createPopupContent(props) {
                    return `
                    <div class="property-popup">
                        ${props.image ? `<img src="${props.image}" class="w-full h-40 object-cover mb-3 rounded-lg" onerror="this.src='/storage/images/default-property.jpg'">` : ''}
                        <h4 class="font-bold text-gray-900 mb-2 text-lg">${props.title}</h4>
                        <p class="text-indigo-600 font-semibold mb-2 text-xl">${props.price} FCFA</p>
                        <p class="text-sm text-gray-600 mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            ${props.address}, ${props.city}
                        </p>
                        <div class="flex justify-between text-sm text-gray-500 mb-3">
                            <span><i class="fas fa-bed mr-1"></i> ${props.bedrooms}</span>
                            <span><i class="fas fa-bath mr-1"></i> ${props.bathrooms}</span>
                            <span><i class="fas fa-ruler-combined mr-1"></i> ${props.area} m²</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="${props.url}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-eye mr-1"></i> Voir détails
                            </a>
                            <button onclick="window.mapInstance.getDirections(${props.id})" class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg text-sm transition-colors" title="Itinéraire">
                                <i class="fas fa-directions"></i>
                            </button>
                        </div>
                    </div>
                `;
                }

                // Geolocation methods
                requestGeolocation() {
                    if (!navigator.geolocation) {
                        alert("La géolocalisation n'est pas supportée par votre navigateur");
                        return;
                    }

                    this.showGeoModal();
                }

                enableGeolocation() {
                    this.hideGeoModal();

                    navigator.geolocation.getCurrentPosition(
                        (position) => this.handleGeolocationSuccess(position),
                        (error) => this.handleGeolocationError(error), {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                }

                handleGeolocationSuccess(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    @this.set('userPosition', {
                        lat: lat,
                        lng: lng,
                        accuracy: position.coords.accuracy,
                        source: 'gps'
                    });

                    this.map.setView([lat, lng], 15);

                    if (this.userLocationMarker) {
                        this.map.removeLayer(this.userLocationMarker);
                    }

                    this.userLocationMarker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            html: '<div class="user-location-marker"></div>',
                            className: 'user-location-marker-container',
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        })
                    }).addTo(this.map);

                    @this.call('loadProperties');
                }

                handleGeolocationError(error) {
                    console.error("Erreur de géolocalisation:", error);
                    let message = "Erreur de géolocalisation";

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = "Permission de géolocalisation refusée";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = "Position non disponible";
                            break;
                        case error.TIMEOUT:
                            message = "Délai de géolocalisation dépassé";
                            break;
                    }

                    alert(message);
                }

                // Map control methods
                zoomIn() {
                    this.map.zoomIn();
                }

                zoomOut() {
                    this.map.zoomOut();
                }

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                }

                updateFullscreenIcon() {
                    const icon = document.getElementById('fullscreenIcon');
                    if (icon) {
                        icon.className = this.isFullscreen ? 'fas fa-compress' : 'fas fa-expand';
                    }
                }

                fitAllMarkers() {
                    if (this.markerCluster.getLayers().length > 0) {
                        this.map.fitBounds(this.markerCluster.getBounds(), {
                            padding: [50, 50],
                            maxZoom: 15
                        });
                    }
                }

                getDirections(lat, lng) {
                    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                    window.open(url, '_blank');
                }

                showGeoModal() {
                    const modal = document.getElementById('geoModal');
                    if (modal) modal.classList.remove('hidden');
                }

                hideGeoModal() {
                    const modal = document.getElementById('geoModal');
                    if (modal) modal.classList.add('hidden');
                }
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', () => {
                window.mapInstance = new PropertyMap();

                // Écouter les changements de mode de vue
                Livewire.on('viewModeChanged', () => {
                    if (window.mapInstance && @json($viewMode) === 'map') {
                        setTimeout(() => {
                            window.mapInstance.init();
                        }, 100);
                    }
                });
            });

            // Rafraîchir la carte quand Livewire navigue
            document.addEventListener('livewire:navigated', () => {
                if (window.mapInstance) {
                    setTimeout(() => {
                        window.mapInstance.init();
                    }, 100);
                }
            });
        </script>
    @endpush
</div>
