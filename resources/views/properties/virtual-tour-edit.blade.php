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
                    <li class="breadcrumb-item active" aria-current="page">Modifier la visite virtuelle</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Modifier la visite virtuelle</h1>
                <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la propriété
                </a>
            </div>
            <p class="text-muted">{{ $property->title }} - {{ $property->address }}, {{ $property->city }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Configuration de la visite virtuelle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('properties.virtual-tour.update', $property) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Type de visite virtuelle</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card h-100 @if($property->virtual_tour_type === 'basic') border-primary @endif">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="virtual_tour_type" id="type_basic" value="basic" @if($property->virtual_tour_type === 'basic') checked @endif>
                                                <label class="form-check-label" for="type_basic">
                                                    <h6>Visite basique</h6>
                                                </label>
                                            </div>
                                            <p class="text-muted small">Galerie d'images améliorée utilisant les photos existantes de la propriété.</p>
                                            <div class="text-center">
                                                <i class="fas fa-images fa-3x text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 @if($property->virtual_tour_type === 'panoramic') border-primary @endif">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="virtual_tour_type" id="type_panoramic" value="panoramic" @if($property->virtual_tour_type === 'panoramic') checked @endif>
                                                <label class="form-check-label" for="type_panoramic">
                                                    <h6>Visite panoramique</h6>
                                                </label>
                                            </div>
                                            <p class="text-muted small">Visite interactive avec des photos panoramiques à 360° de chaque pièce.</p>
                                            <div class="text-center">
                                                <i class="fas fa-panorama fa-3x text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 @if($property->virtual_tour_type === '3d') border-primary @endif">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="virtual_tour_type" id="type_3d" value="3d" @if($property->virtual_tour_type === '3d') checked @endif>
                                                <label class="form-check-label" for="type_3d">
                                                    <h6>Visite 3D</h6>
                                                </label>
                                            </div>
                                            <p class="text-muted small">Visite 3D immersive créée avec un service externe comme Matterport.</p>
                                            <div class="text-center">
                                                <i class="fas fa-cube fa-3x text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options pour la visite 3D -->
                        <div id="options_3d" class="mb-4 @if($property->virtual_tour_type !== '3d') d-none @endif">
                            <div class="mb-3">
                                <label for="virtual_tour_url" class="form-label">URL de la visite 3D</label>
                                <input type="url" class="form-control" id="virtual_tour_url" name="virtual_tour_url" value="{{ $property->virtual_tour_url }}" placeholder="https://my.matterport.com/show/?m=...">
                                <div class="form-text">Entrez l'URL de votre visite 3D créée avec Matterport, ou un service similaire.</div>
                            </div>
                        </div>

                        <!-- Options pour la visite panoramique -->
                        <div id="options_panoramic" class="mb-4 @if($property->virtual_tour_type !== 'panoramic') d-none @endif">
                            <div class="mb-3">
                                <label class="form-label">Images panoramiques actuelles</label>
                                @if(!empty($property->panoramic_images))
                                    <div class="row g-2 mb-3">
                                        @foreach($property->panoramic_images as $index => $image)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="Panorama {{ $index + 1 }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="delete_panoramic_images[]" value="{{ $index }}" id="delete_image_{{ $index }}">
                                                            <label class="form-check-label" for="delete_image_{{ $index }}">
                                                                Supprimer
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Aucune image panoramique n'a encore été ajoutée.</p>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="panoramic_images" class="form-label">Ajouter des images panoramiques</label>
                                <input class="form-control" type="file" id="panoramic_images" name="panoramic_images[]" multiple accept="image/jpeg,image/png">
                                <div class="form-text">Sélectionnez des images panoramiques à 360° (format équirectangulaire). Maximum 5 Mo par image.</div>
                            </div>
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Comment créer des images panoramiques ?</h6>
                                <p class="mb-0">Vous pouvez créer des images panoramiques avec :</p>
                                <ul class="mb-0">
                                    <li>Un smartphone avec une application comme Google Street View</li>
                                    <li>Un appareil photo avec un objectif fisheye</li>
                                    <li>Une caméra 360° comme Ricoh Theta ou Insta360</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Options pour la visite basique -->
                        <div id="options_basic" class="mb-4 @if($property->virtual_tour_type !== 'basic') d-none @endif">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Visite virtuelle basique</h6>
                                <p class="mb-0">La visite basique utilise les images existantes de votre propriété. Pour ajouter ou modifier ces images, veuillez utiliser la section "Images" dans l'édition de la propriété.</p>
                            </div>
                            @if(empty($property->images))
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Aucune image n'a été ajoutée à cette propriété. Veuillez d'abord ajouter des images.
                                </div>
                            @else
                                <p>Nombre d'images disponibles : {{ count($property->images) }}</p>
                                <div class="row g-2">
                                    @foreach($property->images as $index => $image)
                                        <div class="col-md-3">
                                            <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Image {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer la visite virtuelle
                            </button>
                            @if($property->has_virtual_tour)
                                <a href="{{ route('properties.virtual-tour', $property) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Prévisualiser la visite virtuelle
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Conseils pour les visites virtuelles</h5>
                </div>
                <div class="card-body">
                    <h6>Visite basique</h6>
                    <ul class="mb-3">
                        <li>Prenez des photos de chaque pièce</li>
                        <li>Assurez-vous que les pièces sont bien éclairées</li>
                        <li>Rangez et nettoyez avant de prendre les photos</li>
                        <li>Prenez des photos en format paysage</li>
                    </ul>

                    <h6>Visite panoramique</h6>
                    <ul class="mb-3">
                        <li>Utilisez une application comme Google Street View sur votre smartphone</li>
                        <li>Placez-vous au centre de chaque pièce</li>
                        <li>Suivez les instructions de l'application pour créer une photo à 360°</li>
                        <li>Exportez l'image au format équirectangulaire</li>
                    </ul>

                    <h6>Visite 3D</h6>
                    <ul class="mb-0">
                        <li>Utilisez un service comme Matterport ou Zillow 3D Home</li>
                        <li>Suivez les instructions du service choisi</li>
                        <li>Copiez l'URL de partage fournie par le service</li>
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Supprimer la visite virtuelle</h5>
                </div>
                <div class="card-body">
                    <p>Si vous souhaitez supprimer complètement la visite virtuelle de cette propriété, utilisez le bouton ci-dessous.</p>
                    <form action="{{ route('properties.virtual-tour.destroy', $property) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette visite virtuelle ?');">
                        @csrf
                        @method('DELETE')
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-2"></i>Supprimer la visite virtuelle
                            </button>
                        </div>
                    </form>
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer l'affichage des options en fonction du type de visite sélectionné
        const radioButtons = document.querySelectorAll('input[name="virtual_tour_type"]');
        const options3d = document.getElementById('options_3d');
        const optionsPanoramic = document.getElementById('options_panoramic');
        const optionsBasic = document.getElementById('options_basic');
        
        radioButtons.forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Masquer toutes les options
                options3d.classList.add('d-none');
                optionsPanoramic.classList.add('d-none');
                optionsBasic.classList.add('d-none');
                
                // Afficher les options correspondantes
                if (this.value === '3d') {
                    options3d.classList.remove('d-none');
                } else if (this.value === 'panoramic') {
                    optionsPanoramic.classList.remove('d-none');
                } else if (this.value === 'basic') {
                    optionsBasic.classList.remove('d-none');
                }
            });
        });
    });
</script>
@endpush
@endsection