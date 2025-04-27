@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Propriétés</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('properties.show', $property) }}">{{ $property->title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Visite virtuelle</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Visite virtuelle : {{ $property->title }}</h1>
                <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la propriété
                </a>
            </div>
            <p class="text-muted">{{ $property->address }}, {{ $property->city }} {{ $property->postal_code }}</p>
        </div>
    </div>

    @if($property->virtual_tour_type === 'basic')
        <!-- Visite virtuelle basique (galerie d'images améliorée) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="virtualTourCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                @foreach($property->images as $index => $image)
                                    <button type="button" data-bs-target="#virtualTourCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($property->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="Image {{ $index + 1 }}">
                                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                            <h5>{{ $property->title }} - Image {{ $index + 1 }}</h5>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#virtualTourCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#virtualTourCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Miniatures des images -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($property->images as $index => $image)
                                <div class="col-6 col-md-3 col-lg-2">
                                    <a href="#" data-bs-target="#virtualTourCarousel" data-bs-slide-to="{{ $index }}" class="d-block">
                                        <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Miniature {{ $index + 1 }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($property->virtual_tour_type === 'panoramic')
        <!-- Visite virtuelle panoramique -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="panoramaContainer" style="width: 100%; height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sélecteur de scènes panoramiques -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sélectionner une pièce</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($property->panoramic_images as $index => $image)
                                <div class="col-6 col-md-3">
                                    <a href="#" class="d-block panorama-thumbnail" data-index="{{ $index }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Panorama {{ $index + 1 }}">
                                        <div class="mt-1 text-center">
                                            <span class="badge bg-primary">Pièce {{ $index + 1 }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Récupérer les images panoramiques
                const panoramicImages = @json($property->panoramic_images);
                
                if (panoramicImages.length > 0) {
                    // Initialiser le viewer avec la première image
                    let viewer = pannellum.viewer('panoramaContainer', {
                        type: 'equirectangular',
                        panorama: '{{ asset('storage') }}/' + panoramicImages[0],
                        autoLoad: true,
                        compass: true,
                        mouseZoom: true,
                    });
                    
                    // Ajouter les événements pour changer d'image
                    document.querySelectorAll('.panorama-thumbnail').forEach(function(thumbnail) {
                        thumbnail.addEventListener('click', function(e) {
                            e.preventDefault();
                            const index = this.getAttribute('data-index');
                            viewer.destroy();
                            viewer = pannellum.viewer('panoramaContainer', {
                                type: 'equirectangular',
                                panorama: '{{ asset('storage') }}/' + panoramicImages[index],
                                autoLoad: true,
                                compass: true,
                                mouseZoom: true,
                            });
                        });
                    });
                }
            });
        </script>
        @endpush

    @elseif($property->virtual_tour_type === '3d')
        <!-- Visite virtuelle 3D (iframe externe) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $property->virtual_tour_url }}" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Informations sur la propriété -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations sur la propriété</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Type :</span>
                                    <span class="fw-bold">{{ $property->type }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Surface :</span>
                                    <span class="fw-bold">{{ $property->surface }} m²</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Chambres :</span>
                                    <span class="fw-bold">{{ $property->bedrooms }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Salles de bain :</span>
                                    <span class="fw-bold">{{ $property->bathrooms }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Prix :</span>
                                    <span class="fw-bold">{{ number_format($property->price, 0, ',', ' ') }} €</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Statut :</span>
                                    <span class="fw-bold">{{ $property->status }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Année de construction :</span>
                                    <span class="fw-bold">{{ $property->year_built ?? 'Non spécifié' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Référence :</span>
                                    <span class="fw-bold">{{ $property->reference }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary">
                            <i class="fas fa-info-circle me-2"></i>Détails de la propriété
                        </a>
                        <a href="{{ route('properties.contact', $property) }}" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Contacter l'agent
                        </a>
                        @can('update', $property)
                            <a href="{{ route('properties.virtual-tour.edit', $property) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Modifier la visite virtuelle
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Besoin d'aide ?</strong> Consultez notre 
    <a href="{{ route('help.virtual-tour-guide') }}" target="_blank" class="alert-link">guide complet sur les visites virtuelles</a>, 
    avec des solutions adaptées à tous les budgets.
</div>
@endsection