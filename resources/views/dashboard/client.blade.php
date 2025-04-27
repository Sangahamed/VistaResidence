@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Tableau de bord client</h1>
            <p class="text-muted">Bienvenue sur votre espace personnel. Gérez vos favoris et suivez vos demandes.</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Propriétés visitées</h6>
                            <h3>{{ $stats['properties_viewed'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-home text-primary"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+2 depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Favoris</h6>
                            <h3>{{ $stats['favorites_count'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-heart text-danger"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+3 depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Messages</h6>
                            <h3>{{ $stats['messages_count'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-envelope text-info"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+5 depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Recherches sauvegardées</h6>
                            <h3>{{ $stats['searches_count'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-search text-success"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+1 depuis le mois dernier</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Activité récente -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activité récente</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if(count($recentVisits) > 0 || count($messages) > 0)
                            @foreach($recentVisits as $visit)
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="fas fa-home text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Visite de propriété</h6>
                                            <p class="mb-1 text-muted">Vous avez visité "{{ $visit->property->title }}"</p>
                                        </div>
                                        <small class="text-muted">{{ $visit->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                            
                            @foreach($messages as $message)
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded me-3">
                                            <i class="fas fa-envelope text-info"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Message</h6>
                                            <p class="mb-1 text-muted">
                                                @if($message->sender_id === auth()->id())
                                                    Vous avez envoyé un message à {{ $message->recipient->name }}
                                                @else
                                                    Vous avez reçu un message de {{ $message->sender->name }}
                                                @endif
                                            </p>
                                        </div>
                                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">Aucune activité récente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recherches sauvegardées -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recherches sauvegardées</h5>
                    <button class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nouvelle
                    </button>
                </div>
                <div class="card-body">
                    @if(count($savedSearches) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($savedSearches as $search)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $search->title }}</h6>
                                            <p class="mb-1 text-muted small">{{ $search->criteria }}</p>
                                            <span class="badge bg-primary">{{ $search->frequency }}</span>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Aucune recherche sauvegardée</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer une recherche
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Propriétés favorites -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Mes propriétés favorites</h3>
                <a href="#" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i> Voir toutes
                </a>
            </div>
            
            <div class="row">
                @if(count($favorites) > 0)
                    @foreach($favorites as $favorite)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="{{ $favorite->property->featured_image ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $favorite->property->title }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">{{ $favorite->property->title }}</h5>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                    <p class="card-text text-muted">{{ $favorite->property->location }}</p>
                                    <p class="card-text fw-bold">{{ number_format($favorite->property->price) }} €</p>
                                    <p class="card-text text-muted">
                                        {{ $favorite->property->surface }} m² - {{ $favorite->property->bedrooms }} ch.
                                    </p>
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">Détails</a>
                                        <a href="#" class="btn btn-sm btn-outline-success">Contacter</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            Vous n'avez pas encore de propriétés favorites. 
                            <a href="{{ route('properties.index') }}" class="alert-link">Parcourir les propriétés</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection