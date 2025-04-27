@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>{{ $property->title }}</h1>
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $property->address }}, {{ $property->city }}, {{ $property->postal_code }}, {{ $property->country }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('properties.edit', $property) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePropertyModal">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Carousel d'images -->
                @if (!empty($property->images) && count($property->images) > 0)
                    <div id="propertyCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach ($property->images as $index => $image)
                                <button type="button" data-bs-target="#propertyCarousel"
                                    data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner rounded">
                            @foreach ($property->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($image['path']) }}" class="d-block w-100"
                                        alt="Image {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    <div class="bg-light text-center py-5 mb-4 rounded">
                        <i class="fas fa-home fa-5x text-muted"></i>
                        <p class="mt-3 text-muted">Aucune image disponible</p>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('properties.visits.create', $property) }}" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Demander une visite
                            </a>
                            @if($property->has_virtual_tour)
                                <a href="{{ route('properties.virtual-tour', $property) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-vr-cardboard me-2"></i>Visite virtuelle
                                </a>
                            @endif
                            <a href="{{ route('properties.contact', $property) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-envelope me-2"></i>Contacter l'agent
                            </a>
                            <form action="{{ route('properties.comparison.add', $property) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info w-100">
                                    <i class="fas fa-exchange-alt me-2"></i>Ajouter à la comparaison
                                </button>
                            </form>
                            @auth
                                <button type="button" class="btn btn-outline-danger toggle-favorite" data-property-id="{{ $property->id }}">
                                    <i class="fas {{ $property->isFavorited() ? 'fa-heart' : 'fa-heart-broken' }} me-2"></i>
                                    {{ $property->isFavorited() ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>


                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $property->description }}</p>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Caractéristiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-building text-primary me-2"></i>
                                        <strong>Type:</strong> {{ ucfirst($property->type) }}
                                    </li>
                                    <li class="mb-2"><i class="fas fa-tag text-primary me-2"></i> <strong>Statut:</strong>
                                        {{ $property->status === 'for_sale' ? 'À vendre' : 'À louer' }}</li>
                                    <li class="mb-2"><i class="fas fa-bed text-primary me-2"></i>
                                        <strong>Chambres:</strong> {{ $property->bedrooms ?? 'Non spécifié' }}
                                    </li>
                                    <li class="mb-2"><i class="fas fa-bath text-primary me-2"></i> <strong>Salles de
                                            bain:</strong> {{ $property->bathrooms ?? 'Non spécifié' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-vector-square text-primary me-2"></i>
                                        <strong>Surface:</strong>
                                        {{ $property->area ? $property->area . ' m²' : 'Non spécifié' }}
                                    </li>
                                    <li class="mb-2"><i class="fas fa-calendar-alt text-primary me-2"></i> <strong>Année
                                            de construction:</strong> {{ $property->year_built ?? 'Non spécifié' }}</li>
                                    <li class="mb-2"><i class="fas fa-star text-primary me-2"></i> <strong>Mise en
                                            avant:</strong> {{ $property->is_featured ? 'Oui' : 'Non' }}</li>
                                </ul>
                            </div>
                        </div>

                        @if (!empty($property->features) && count($property->features) > 0)
                            <hr>
                            <h6>Équipements et caractéristiques</h6>
                            <div class="row mt-3">
                                @foreach ($property->features as $feature)
                                    <div class="col-md-4 mb-2">
                                        <i class="fas fa-check text-success me-2"></i> {{ $feature }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Vidéos -->
                @if (!empty($property->videos) && count($property->videos) > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Vidéos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($property->videos as $video)
                                    <div class="col-md-6 mb-3">
                                        <div class="ratio ratio-16x9">
                                            <video controls>
                                                <source src="{{ Storage::url($video['path']) }}" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Localisation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Localisation</h5>
                    </div>
                    <div class="card-body">
                        @if ($property->latitude && $property->longitude)
                            <div id="map" style="height: 400px;" class="rounded"></div>
                        @else
                            <p class="text-muted">Aucune coordonnée GPS disponible pour cette propriété.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Prix et statut -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="text-primary mb-3">{{ number_format($property->price, 0, ',', ' ') }} €</h3>
                        <p class="mb-0">
                            <span class="badge bg-{{ $property->status === 'for_sale' ? 'danger' : 'primary' }} mb-2">
                                {{ $property->status === 'for_sale' ? 'À vendre' : 'À louer' }}
                            </span>
                        </p>
                        <hr>
                        <div class="d-grid gap-2">
                            <!-- resources/views/properties/show.blade.php (extrait) -->

                            <!-- Section actions -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('properties.visits.create', $property) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-calendar-check me-2"></i>Demander une visite
                                        </a>
                                        @if ($property->has_virtual_tour)
                                            <a href="{{ route('properties.virtual-tour', $property) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-vr-cardboard me-2"></i>Visite virtuelle
                                            </a>
                                        @endif
                                        <a href="{{ route('properties.contact', $property) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-envelope me-2"></i>Contacter l'agent
                                        </a>
                                        @auth
                                            <button type="button" class="btn btn-outline-danger toggle-favorite"
                                                data-property-id="{{ $property->id }}">
                                                <i
                                                    class="fas {{ $property->isFavorited() ? 'fa-heart' : 'fa-heart-broken' }} me-2"></i>
                                                {{ $property->isFavorited() ? 'Retirer des favoris' : 'Ajouter aux favoris' }}
                                            </button>
                                        @endauth
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Contacter le propriétaire</h5>
                                </div>
                                <div class="card-body">
                                    @guest
                                        <p class="text-center">
                                            <a href="{{ route('login') }}" class="btn btn-primary">Connectez-vous pour
                                                contacter le propriétaire</a>
                                        </p>
                                    @else
                                        <form action="{{ route('properties.contact', $property) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="message" class="form-label">Votre message</label>
                                                <textarea class="form-control" id="message" name="message" rows="4"
                                                    placeholder="Bonjour, je suis intéressé(e) par cette propriété. Pourrions-nous organiser une visite ?"></textarea>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-2"></i>Envoyer un message
                                                </button>
                                            </div>
                                        </form>
                                    @endguest
                                </div>
                            </div>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-share-alt me-2"></i> Partager
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informations du propriétaire -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Propriétaire</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $property->owner->name }}</h6>
                                <p class="text-muted small mb-0">Propriétaire</p>
                            </div>
                        </div>
                        <hr>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-envelope text-muted me-2"></i>
                                {{ $property->owner->email }}</li>
                            @if ($property->owner->phone)
                                <li><i class="fas fa-phone text-muted me-2"></i> {{ $property->owner->phone }}</li>
                            @endif
                        </ul>
                    </div>

                    @can('viewStatistics', $property)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Gestion de la propriété</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('properties.statistics', $property) }}" class="btn btn-primary">
                                        <i class="fas fa-chart-bar me-2"></i>Statistiques de visites
                                    </a>
                                    <a href="{{ route('properties.edit', $property) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i>Modifier la propriété
                                    </a>
                                    @if ($property->has_virtual_tour)
                                        <a href="{{ route('properties.virtual-tour.edit', $property) }}"
                                            class="btn btn-outline-primary">
                                            <i class="fas fa-vr-cardboard me-2"></i>Modifier la visite virtuelle
                                        </a>
                                    @else
                                        <a href="{{ route('properties.virtual-tour.edit', $property) }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-plus me-2"></i>Ajouter une visite virtuelle
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>

                <!-- Entreprise (si applicable) -->
                @if ($property->company)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Agence</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    @if ($property->company->logo)
                                        <img src="{{ Storage::url($property->company->logo) }}"
                                            alt="{{ $property->company->name }}" class="rounded" width="50">
                                    @else
                                        <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="fas fa-building"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $property->company->name }}</h6>
                                    <p class="text-muted small mb-0">Agence immobilière</p>
                                </div>
                            </div>
                            <hr>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-envelope text-muted me-2"></i>
                                    {{ $property->company->email }}</li>
                                @if ($property->company->phone)
                                    <li class="mb-2"><i class="fas fa-phone text-muted me-2"></i>
                                        {{ $property->company->phone }}</li>
                                @endif
                                @if ($property->company->website)
                                    <li><i class="fas fa-globe text-muted me-2"></i> <a
                                            href="{{ $property->company->website }}" target="_blank">Site web</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deletePropertyModal" tabindex="-1" aria-labelledby="deletePropertyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePropertyModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cette propriété ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('properties.destroy', $property) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($property->latitude && $property->longitude)
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialiser la carte
                    const map = L.map('map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

                    // Ajouter la couche OpenStreetMap
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Ajouter un marqueur
                    L.marker([{{ $property->latitude }}, {{ $property->longitude }}])
                        .addTo(map)
                        .bindPopup('{{ $property->title }}')
                        .openPopup();
                });
            </script>
        @endpush
    @endif
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endpush
