@extends('components.front.layouts.front')

@section('content')
    <style>
        #map {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .leaflet-top,
        .leaflet-bottom {
            z-index: 1;
        }

        @media (max-width: 768px) {
            #map {
                height: calc(100vh - 60px);
            }

            #mobile-filter-btn {
                display: block;
            }
        }

        .leaflet-marker-icon {
            filter: drop-shadow(0 0 5px red) !important;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="flex flex-col h-screen bg-gray-900 text-white md:flex-row">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden md:block w-80 bg-gray-800 p-6 overflow-y-auto">
            <h2 class="text-2xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-600">
                Filtres</h2>
            <form id="map-filters" class="space-y-4">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium mb-1">Type de propriété</label>
                    <select name="property_type" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md">
                        <option value="">Tous</option>
                        @foreach ($propertyTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prix -->
                <div>
                    <label class="block text-sm font-medium mb-1">Prix ({{ config('app.currency') }})</label>
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" placeholder="Min"
                            class="w-1/2 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md">
                        <input type="number" name="max_price" placeholder="Max"
                            class="w-1/2 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md">Filtrer</button>
            </form>
        </div>

        <!-- Mobile filter button -->
        <button id="mobile-filter-btn" class="md:hidden fixed bottom-20 right-4 z-50 p-4 bg-blue-600 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M4 8h16M6 12h12M8 16h8" />
            </svg>
        </button>

        <!-- Map -->
        <div class="flex-1 relative">
            <div id="map"></div>
            <div id="loading-spinner" class="loading-spinner hidden"></div>
        </div>
    </div>

    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const map = L.map('map').setView([5.3167, -4.0333], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18,
            }).addTo(map);

            const markerLayer = L.layerGroup().addTo(map);
            const loadingSpinner = document.getElementById('loading-spinner');

            function getCustomIcon(type) {
                return L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/619/619153.png',
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });
            }

            function createPopupContent(p) {
                return `
        <div class="w-60">
            ${p.image ? `<img src="${p.image}" class="w-full h-32 object-cover mb-2 rounded">` : ''}
            <h4 class="font-bold">${p.title}</h4>
            <p class="text-blue-400 font-semibold">${p.price || 'Prix non défini'} XOF</p>
            <p class="text-sm text-gray-300">${p.address || 'Adresse non définie'}</p>
            <div class="flex justify-between text-sm mt-1">
                <span>${p.bedrooms || 0} 🛏</span>
                <span>${p.bathrooms || 0} 🛁</span>
                <span>${p.type || 'Type inconnu'}</span>
            </div>
            <a href="${p.url || '#'}" class="block mt-2 text-center bg-blue-600 hover:bg-blue-700 text-white py-1 rounded">Voir</a>
        </div>
    `;
            }

            function loadProperties(filters = {}) {
                loadingSpinner.classList.remove('hidden'); // Show loading spinner

                const params = new URLSearchParams(filters);
                fetch(`/api/map/properties?${params}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log('Propriétés chargées :', data);
                        markerLayer.clearLayers();
                        L.geoJSON(data, {
                            pointToLayer: (feature, latlng) => {
                                const lat = parseFloat(feature.geometry.coordinates[1]);
                                const lng = parseFloat(feature.geometry.coordinates[0]);

                                return L.marker([lat, lng], {
                                    icon: getCustomIcon(feature.properties.type)
                                }).bindPopup(createPopupContent(feature.properties));
                            }
                        }).addTo(markerLayer);

                        loadingSpinner.classList.add('hidden'); // Hide loading spinner
                    })
                    .catch(err => {
                        console.error("Erreur de chargement des propriétés:", err);
                        loadingSpinner.classList.add('hidden'); // Hide loading spinner even in error
                    });
            }

            document.getElementById('map-filters').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                loadProperties(Object.fromEntries(formData));
            });

            document.getElementById('mobile-filter-btn').addEventListener('click', () => {
                document.getElementById('sidebar').classList.toggle('hidden');
            });

            loadProperties();
        });
    </script>
@endsection
