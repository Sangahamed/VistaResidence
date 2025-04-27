<!-- resources/views/properties/comparison.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('properties.index') }}">Propriétés</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Comparaison</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Comparaison de propriétés</h1>
                <div>
                    <a href="{{ route('properties.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-2"></i>Ajouter d'autres propriétés
                    </a>
                    <form action="{{ route('properties.comparison.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash-alt me-2"></i>Vider la liste
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(count($properties) > 0)
        <div class="table-responsive">
            <table class="table table-bordered comparison-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Caractéristiques</th>
                        @foreach($properties as $property)
                            <th style="width: {{ 80 / count($properties) }}%;" class="text-center">
                                <div class="position-relative">
                                    <form action="{{ route('properties.comparison.remove', $property) }}" method="POST" class="position-absolute top-0 end-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @if($property->images && count($property->images) > 0)
                                        <img src="{{ asset('storage/' . $property->images[0]) }}" alt="{{ $property->title }}" class="img-fluid rounded mb-2" style="height: 150px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded mb-2" style="height: 150px;">
                                            <i class="fas fa-home fa-3x"></i>
                                        </div>
                                    @endif
                                    <h5>{{ $property->title }}</h5>
                                    <p class="text-muted">{{ $property->city }}</p>
                                    <p class="fw-bold">{{ number_format($property->price, 0, ',', ' ') }} €</p>
                                    <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-primary w-100 mb-2">
                                        Voir le détail
                                    </a>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">Type</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->type }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Surface</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->surface }} m²</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Prix</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ number_format($property->price, 0, ',', ' ') }} €</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Prix au m²</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ number_format($property->price / $property->surface, 0, ',', ' ') }} €/m²</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Chambres</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->bedrooms }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Salles de bain</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->bathrooms }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Étage</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->floor ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Année de construction</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->year_built ?? 'N/A' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Parking</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->parking_spaces ?? 0 }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Balcon/Terrasse</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->has_balcony ? 'Oui' : 'Non' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Ascenseur</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->has_elevator ? 'Oui' : 'Non' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">DPE</td>
                        @foreach($properties as $property)
                            <td class="text-center">
                                @if($property->energy_rating)
                                    <span class="badge bg-{{ 
                                        $property->energy_rating === 'A' ? 'success' : 
                                        ($property->energy_rating === 'B' ? 'success' : 
                                        ($property->energy_rating === 'C' ? 'info' : 
                                        ($property->energy_rating === 'D' ? 'warning' : 
                                        ($property->energy_rating === 'E' ? 'warning' : 
                                        ($property->energy_rating === 'F' ? 'danger' : 'danger'))))) 
                                    }}">
                                        {{ $property->energy_rating }}
                                    </span>
                                @else
                                    N/A
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Adresse</td>
                        @foreach($properties as $property)
                            <td class="text-center">{{ $property->address }}, {{ $property->city }} {{ $property->postal_code }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Visite virtuelle</td>
                        @foreach($properties as $property)
                            <td class="text-center">
                                @if($property->has_virtual_tour)
                                    <a href="{{ route('properties.virtual-tour', $property) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-vr-cardboard me-2"></i>Voir la visite
                                    </a>
                                @else
                                    <span class="text-muted">Non disponible</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold">Actions</td>
                        @foreach($properties as $property)
                            <td class="text-center">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('properties.visits.create', $property) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-calendar-check me-2"></i>Demander une visite
                                    </a>
                                    <a href="{{ route('properties.contact', $property) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-envelope me-2"></i>Contacter l'agent
                                    </a>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                <h3>Aucune propriété à comparer</h3>
                <p class="text-muted">Vous n'avez pas encore ajouté de propriétés à votre liste de comparaison.</p>
                <a href="{{ route('properties.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-search me-2"></i>Rechercher des propriétés
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .comparison-table th, .comparison-table td {
        vertical-align: middle;
    }
    
    @media (max-width: 768px) {
        .comparison-table {
            min-width: 800px;
        }
    }
</style>
@endsection