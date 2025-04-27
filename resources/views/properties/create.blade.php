@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Ajouter une propriété</h1>
        </div>
    </div>

    <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <!-- Informations de base -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations de base</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type de bien <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Appartement</option>
                                    <option value="house" {{ old('type') == 'house' ? 'selected' : '' }}>Maison</option>
                                    <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                    <option value="land" {{ old('type') == 'land' ? 'selected' : '' }}>Terrain</option>
                                    <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Local commercial</option>
                                    <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Bureau</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="for_sale" {{ old('status') == 'for_sale' ? 'selected' : '' }}>À vendre</option>
                                    <option value="for_rent" {{ old('status') == 'for_rent' ? 'selected' : '' }}>À louer</option>
                                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Vendu</option>
                                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Loué</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                <span class="input-group-text">€</span>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Localisation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Localisation</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="country" class="form-label">Pays</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', 'France') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" step="0.000001">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" step="0.000001">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary" id="findCoordinates">
                                <i class="fas fa-map-marker-alt"></i> Trouver les coordonnées
                            </button>
                        </div>
                        
                        <div id="map" style="height: 300px; display: none;" class="mb-3 rounded"></div>
                    </div>
                </div>
                
                <!-- Caractéristiques -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Caractéristiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bedrooms" class="form-label">Chambres</label>
                                <input type="number" class="form-control @error('bedrooms') is-invalid @enderror" id="bedrooms" name="bedrooms" value="{{ old('bedrooms') }}" min="0">
                                @error('bedrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bathrooms" class="form-label">Salles de bain</label>
                                <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms" name="bathrooms" value="{{ old('bathrooms') }}" min="0">
                                @error('bathrooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="area" class="form-label">Surface (m²)</label>
                                <input type="number" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area') }}" min="0" step="0.01">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="year_built" class="form-label">Année de construction</label>
                                <input type="number" class="form-control @error('year_built') is-invalid @enderror" id="year_built" name="year_built" value="{{ old('year_built') }}" min="1800" max="{{ date('Y') }}">
                                @error('year_built')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Équipements et caractéristiques</label>
                            <div class="row">
                                @php
                                    $features = [
                                        'garage' => 'Garage',
                                        'parking' => 'Parking',
                                        'garden' => 'Jardin',
                                        'terrace' => 'Terrasse',
                                        'balcony' => 'Balcon',
                                        'pool' => 'Piscine',
                                        'elevator' => 'Ascenseur',
                                        'air_conditioning' => 'Climatisation',
                                        'heating' => 'Chauffage',
                                        'security_system' => 'Système de sécurité',
                                        'storage' => 'Espace de stockage',
                                        'furnished' => 'Meublé',
                                    ];
                                    $oldFeatures = old('features', []);
                                @endphp
                                
                                @foreach($features as $key => $label)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="feature_{{ $key }}" name="features[]" value="{{ $key }}" {{ in_array($key, $oldFeatures) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="feature_{{ $key }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('features')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Mettre en avant cette propriété
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Médias -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Médias</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="images" class="form-label">Images (JPG, PNG, GIF - Max 2MB par image)</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Vous pouvez sélectionner plusieurs images à la fois.</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="videos" class="form-label">Vidéos (MP4, MOV, AVI - Max 20MB par vidéo)</label>
                            <input type="file" class="form-control @error('videos.*') is-invalid @enderror" id="videos" name="videos[]" multiple accept="video/*">
                            <div class="form-text">Vous pouvez sélectionner plusieurs vidéos à la fois.</div>
                            @error('videos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Entreprise -->
                @if(auth()->user()->companies->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Entreprise</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="company_id" class="form-label">Associer à une entreprise</label>
                                <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                                    <option value="">Aucune entreprise</option>
                                    @foreach(auth()->user()->companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer la propriété
                            </button>
                            <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('map');
        const findCoordinatesBtn = document.getElementById('findCoordinates');
        const addressInput = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const postalCodeInput = document.getElementById('postal_code');
        const countryInput = document.getElementById('country');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        
        let map = null;
        let marker = null;
        
        // Initialiser la carte
        function initMap(lat, lng) {
            if (map === null) {
                map = L.map('map').setView([lat, lng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                mapElement.style.display = 'block';
            } else {
                map.setView([lat, lng], 15);
            }
            
            if (marker) {
                map.removeLayer(marker);
            }
            
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);
            
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                latitudeInput.value = position.lat.toFixed(6);
                longitudeInput.value = position.lng.toFixed(6);
            });
        }
        
        // Rechercher les coordonnées à partir de l'adresse
        findCoordinatesBtn.addEventListener('click', function() {
            const address = [
                addressInput.value,
                cityInput.value,
                postalCodeInput.value,
                countryInput.value
            ].filter(Boolean).join(', ');
            
            if (!address) {
                alert('Veuillez saisir au moins une partie de l\'adresse.');
                return;
            }
            
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const result = data[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);
                        
                        latitudeInput.value = lat.toFixed(6);
                        longitudeInput.value = lng.toFixed(6);
                        
                        initMap(lat, lng);
                    } else {
                        alert('Aucun résultat trouvé pour cette adresse.');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche des coordonnées:', error);
                    alert('Une erreur est survenue lors de la recherche des coordonnées.');
                });
        });
        
        // Initialiser la carte si des coordonnées sont déjà définies
        if (latitudeInput.value && longitudeInput.value) {
            const lat = parseFloat(latitudeInput.value);
            const lng = parseFloat(longitudeInput.value);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                initMap(lat, lng);
            }
        }
    });
</script>
@endpush
@endsection