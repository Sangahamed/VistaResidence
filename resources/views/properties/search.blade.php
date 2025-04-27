<!-- resources/views/properties/search.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Recherche avancée de propriétés</h1>
            <p class="text-muted">Trouvez la propriété idéale en utilisant nos filtres avancés.</p>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire de recherche -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('properties.search') }}" method="GET" id="searchForm">
                        <!-- Type de transaction -->
                        <div class="mb-3">
                            <label class="form-label">Type de transaction</label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="transaction_type" id="transaction_sale" value="sale" {{ isset($validated['transaction_type']) && $validated['transaction_type'] === 'sale' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="transaction_sale">Achat</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="transaction_type" id="transaction_rental" value="rental" {{ isset($validated['transaction_type']) && $validated['transaction_type'] === 'rental' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="transaction_rental">Location</label>
                                </div>
                            </div>
                        </div>

                        <!-- Type de propriété -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de bien</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Tous les types</option>
                                @foreach($propertyTypes as $type)
                                    <option value="{{ $type }}" {{ isset($validated['type']) && $validated['type'] === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Localisation -->
                        <div class="mb-3">
                            <label for="city" class="form-label">Ville</label>
                            <select class="form-select" id="city" name="city">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ isset($validated['city']) && $validated['city'] === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $validated['postal_code'] ?? '' }}">
                        </div>

                        <!-- Prix -->
                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" placeholder="Min" value="{{ $validated['min_price'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" placeholder="Max" value="{{ $validated['max_price'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Surface -->
                        <div class="mb-3">
                            <label class="form-label">Surface (m²)</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_surface" placeholder="Min" value="{{ $validated['min_surface'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_surface" placeholder="Max" value="{{ $validated['max_surface'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Pièces -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de pièces</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_rooms" placeholder="Min" value="{{ $validated['min_rooms'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_rooms" placeholder="Max" value="{{ $validated['max_rooms'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Chambres -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de chambres</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_bedrooms" placeholder="Min" value="{{ $validated['min_bedrooms'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_bedrooms" placeholder="Max" value="{{ $validated['max_bedrooms'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Salles de bain -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de salles de bain</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_bathrooms" placeholder="Min" value="{{ $validated['min_bathrooms'] ?? '' }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_bathrooms" placeholder="Max" value="{{ $validated['max_bathrooms'] ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Caractéristiques -->
                        <div class="mb-3">
                            <label class="form-label">Caractéristiques</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_garage" value="garage" {{ isset($validated['features']) && in_array('garage', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_garage">Garage</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_garden" value="garden" {{ isset($validated['features']) && in_array('garden', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_garden">Jardin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_pool" value="pool" {{ isset($validated['features']) && in_array('pool', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_pool">Piscine</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_elevator" value="elevator" {{ isset($validated['features']) && in_array('elevator', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_elevator">Ascenseur</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_terrace" value="terrace" {{ isset($validated['features']) && in_array('terrace', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_terrace">Terrasse</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="features[]" id="feature_balcony" value="balcony" {{ isset($validated['features']) && in_array('balcony', $validated['features']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="feature_balcony">Balcon</label>
                            </div>
                        </div>

                        <!-- Recherche par rayon -->
                        <div class="mb-3">
                            <label class="form-label">Recherche par rayon</label>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="location_search" placeholder="Adresse, ville...">
                                <button class="btn btn-outline-secondary" type="button" id="geocodeButton">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <input type="hidden" name="latitude" id="latitude" value="{{ $validated['latitude'] ?? '' }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ $validated['longitude'] ?? '' }}">
                                    <input type="number" class="form-control" name="radius" id="radius" placeholder="Rayon (km)" value="{{ $validated['radius'] ?? '' }}">
                                </div>
                                <div class="col-6 d-flex align-items-center">
                                    <span id="location_display" class="text-muted small">
                                        @if(isset($validated['latitude']) && isset($validated['longitude']))
                                            Position définie
                                        @else
                                            Non défini
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div id="map" style="height: 200px; width: 100%;" class="mb-2"></div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clearLocationButton">
                                Effacer la position
                            </button>
                        </div>

                        <!-- Tri -->
                        <div class="mb-3">
                            <label for="sort_by" class="form-label">Trier par</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="date_desc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'date_desc' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="date_asc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'date_asc' ? 'selected' : '' }}>Plus anciennes</option>
                                <option value="price_asc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="surface_asc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'surface_asc' ? 'selected' : '' }}>Surface croissante</option>
                                <option value="surface_desc" {{ isset($validated['sort_by']) && $validated['sort_by'] === 'surface_desc' ? 'selected' : '' }}>Surface décroissante</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Rechercher
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="resetButton">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recherches sauvegardées -->
            @auth
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mes recherches</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="saveSearchButton" {{ isset($properties) ? '' : 'disabled' }}>
                            <i class="fas fa-save me-1"></i>Sauvegarder
                        </button>
                    </div>
                    <div class="card-body">
                        @if(count($savedSearches) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($savedSearches as $search)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $search->name }}</h6>
                                                @if($search->alert_frequency)
                                                    <span class="badge bg-primary">
                                                        @switch($search->alert_frequency)
                                                            @case('instant')
                                                                Alertes instantanées
                                                                @break
                                                            @case('daily')
                                                                Alertes quotidiennes
                                                                @break
                                                            @case('weekly')
                                                                Alertes hebdomadaires
                                                                @break
                                                            @case('monthly')
                                                                Alertes mensuelles
                                                                @break
                                                        @endswitch
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ route('properties.search.load', $search) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                                <form action="{{ route('properties.search.delete', $search) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette recherche ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Aucune recherche sauvegardée</p>
                        @endif
                    </div>
                </div>
            @endauth
        </div>

        <!-- Résultats de recherche -->
        <div class="col-md-8">
            @if(isset($properties))
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Résultats ({{ $properties->total() }})</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($properties) > 0)
                            <div class="row">
                                @foreach($properties as $property)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <img src="{{ $property->featured_image ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $property->title }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="card-title">{{ $property->title }}</h5>
                                                    <span class="badge {{ $property->transaction_type === 'sale' ? 'bg-primary' : 'bg-success' }}">
                                                        {{ $property->transaction_type === 'sale' ? 'Vente' : 'Location' }}
                                                    </span>
                                                </div>
                                                <p class="card-text text-muted">{{ $property->city }}, {{ $property->postal_code }}</p>
                                                <p class="card-text fw-bold">{{ number_format($property->price) }} €{{ $property->transaction_type === 'rental' ? '/mois' : '' }}</p>
                                                <p class="card-text text-muted">
                                                    {{ $property->surface }} m² - {{ $property->rooms }} pièce(s) - {{ $property->bedrooms }} ch.
                                                </p>
                                                @if(isset($property->distance))
                                                    <p class="card-text text-muted small">
                                                        <i class="fas fa-map-marker-alt me-1"></i> À {{ number_format($property->distance, 1) }} km
                                                    </p>
                                                @endif
                                                <div class="d-flex gap-2 mt-3">
                                                    <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                                    @auth
                                                        <form action="{{ route('properties.favorite.toggle', $property) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas {{ $property->isFavorited() ? 'fa-heart' : 'fa-heart' }}"></i>
                                                            </button>
                                                        </form>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="d-flex justify-content-center mt-4">
                                {{ $properties->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h4>Aucun résultat trouvé</h4>
                                <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h4>Recherchez votre bien idéal</h4>
                    <p class="text-muted">Utilisez les filtres à gauche pour trouver la propriété qui vous correspond.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour sauvegarder une recherche -->
<div class="modal fade" id="saveSearchModal" tabindex="-1" aria-labelledby="saveSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveSearchModalLabel">Sauvegarder la recherche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('properties.search.save') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search_name" class="form-label">Nom de la recherche</label>
                        <input type="text" class="form-control" id="search_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="alert_frequency" class="form-label">Fréquence des alertes (optionnel)</label>
                        <select class="form-select" id="alert_frequency" name="alert_frequency">
                            <option value="">Pas d'alerte</option>
                            <option value="instant">Instantanée</option>
                            <option value="daily">Quotidienne</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuelle</option>
                        </select>
                        <div class="form-text">Recevez des alertes par email lorsque de nouvelles propriétés correspondent à vos critères.</div>
                    </div>
                    <input type="hidden" name="criteria" id="search_criteria">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation de la carte
        const mapElement = document.getElementById('map');
        const map = new google.maps.Map(mapElement, {
            center: { lat: 46.603354, lng: 1.888334 }, // Centre de la France
            zoom: 5
        });
        
        let marker = null;
        
        // Géocodage de l'adresse
        const geocodeButton = document.getElementById('geocodeButton');
        const locationSearch = document.getElementById('location_search');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const locationDisplay = document.getElementById('location_display');
        
        geocodeButton.addEventListener('click', function() {
            const address = locationSearch.value;
            if (!address) return;
            
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    
                    // Mettre à jour les champs cachés
                    latitudeInput.value = location.lat();
                    longitudeInput.value = location.lng();
                    locationDisplay.textContent = results[0].formatted_address;
                    
                    // Centrer la carte et ajouter un marqueur
                    map.setCenter(location);
                    map.setZoom(12);
                    
                    if (marker) {
                        marker.setMap(null);
                    }
                    
                    marker = new google.maps.Marker({
                        map: map,
                        position: location
                    });
                } else {
                    alert('Adresse non trouvée');
                }
            });
        });
        
        // Effacer la position
        const clearLocationButton = document.getElementById('clearLocationButton');
        clearLocationButton.addEventListener('click', function() {
            latitudeInput.value = '';
            longitudeInput.value = '';
            locationSearch.value = '';
            locationDisplay.textContent = 'Non défini';
            
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
            
            map.setCenter({ lat: 46.603354, lng: 1.888334 });
            map.setZoom(5);
        });
        
        // Réinitialiser le formulaire
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', function() {
            document.getElementById('searchForm').reset();
            
            // Effacer aussi la position
            latitudeInput.value = '';
            longitudeInput.value = '';
            locationDisplay.textContent = 'Non défini';
            
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
            
            map.setCenter({ lat: 46.603354, lng: 1.888334 });
            map.setZoom(5);
        });
        
        // Sauvegarder la recherche
        const saveSearchButton = document.getElementById('saveSearchButton');
        if (saveSearchButton) {
            saveSearchButton.addEventListener('click', function() {
                // Récupérer tous les paramètres du formulaire
                const formData = new FormData(document.getElementById('searchForm'));
                const searchParams = {};
                
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        if (key === 'features[]') {
                            if (!searchParams.features) {
                                searchParams.features = [];
                            }
                            searchParams.features.push(value);
                        } else {
                            searchParams[key] = value;
                        }
                    }
                }
                
                // Mettre à jour le champ caché avec les critères de recherche
                document.getElementById('search_criteria').value = JSON.stringify(searchParams);
                
                // Afficher la modal
                const saveSearchModal = new bootstrap.Modal(document.getElementById('saveSearchModal'));
                saveSearchModal.show();
            });
        }
        
        // Initialiser la carte avec les coordonnées existantes
        if (latitudeInput.value && longitudeInput.value) {
            const lat = parseFloat(latitudeInput.value);
            const lng = parseFloat(longitudeInput.value);
            const location = { lat, lng };
            
            map.setCenter(location);
            map.setZoom(12);
            
            marker = new google.maps.Marker({
                map: map,
                position: location
            });
        }
    });
</script>
@endpush
@endsection