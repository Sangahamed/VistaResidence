<!-- resources/views/properties/visits/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('visits.index') }}">Mes visites</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Détails de la visite</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails de la visite</h4>
                    <span class="badge {{ 
                        $visit->status === 'pending' ? 'bg-warning' : 
                        ($visit->status === 'confirmed' ? 'bg-success' : 
                        ($visit->status === 'completed' ? 'bg-info' : 'bg-danger')) 
                    }}">
                        {{ 
                            $visit->status === 'pending' ? 'En attente' : 
                            ($visit->status === 'confirmed' ? 'Confirmée' : 
                            ($visit->status === 'completed' ? 'Terminée' : 'Annulée')) 
                        }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $visit->property->title }}</h5>
                        <p class="text-muted">{{ $visit->property->address }}, {{ $visit->property->city }} {{ $visit->property->postal_code }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2">{{ $visit->property->type }}</span>
                            <span class="badge bg-secondary me-2">{{ $visit->property->surface }} m²</span>
                            <span class="badge bg-info">{{ $visit->property->bedrooms }} chambre(s)</span>
                        </div>
                        <p class="fw-bold">{{ number_format($visit->property->price, 0, ',', ' ') }} €</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de visite</label>
                                <p>{{ $visit->visit_date->translatedFormat('l j F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Horaire</label>
                                <p>{{ $visit->visit_time_start }} - {{ $visit->visit_time_end }}</p>
                            </div>
                        </div>
                    </div>

                    @if($visit->isConfirmed())
                        <div class="alert alert-success">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Visite confirmée</h5>
                                    <p class="mb-0">Votre visite a été confirmée par l'agent immobilier. Veuillez vous présenter à l'adresse indiquée à l'heure prévue.</p>
                                    <hr>
                                    <p class="mb-0"><strong>Code de confirmation :</strong> {{ $visit->confirmation_code }}</p>
                                    <p class="mb-0 small">Veuillez présenter ce code à l'agent lors de votre visite.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($visit->isPending())
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-clock fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Visite en attente de confirmation</h5>
                                    <p class="mb-0">Votre demande de visite est en cours de traitement. L'agent immobilier vous contactera prochainement pour confirmer votre rendez-vous.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($visit->isCompleted())
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-double fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Visite terminée</h5>
                                    <p class="mb-0">Cette visite a été effectuée. Nous espérons que la propriété vous a plu.</p>
                                </div>
                            </div>
                        </div>
                    @elseif($visit->isCancelled())
                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-times-circle fa-2x me-3"></i>
                                </div>
                                <div>
                                    <h5 class="alert-heading">Visite annulée</h5>
                                    <p class="mb-0"><strong>Raison :</strong> {{ $visit->cancellation_reason }}</p>
                                    <p class="mb-0 small">Annulée par : {{ $visit->cancelledBy->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($visit->visitor_notes)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Vos notes</label>
                            <p>{{ $visit->visitor_notes }}</p>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('properties.show', $visit->property) }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i>Voir la propriété
                        </a>
                        
                        @if($visit->isPending() || $visit->isConfirmed())
                            <a href="{{ route('visits.cancel.form', $visit) }}" class="btn btn-outline-danger">
                                <i class="fas fa-times me-2"></i>Annuler la visite
                            </a>
                        @endif
                        
                        @if($visit->isConfirmed() || $visit->isCompleted())
                            <a href="{{ route('messenger', ['recipient_id' => $visit->agent_id]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-envelope me-2"></i>Contacter l'agent
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Agent immobilier</h5>
                </div>
                <div class="card-body">
                    @if($visit->agent)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $visit->agent->name }}</h6>
                                <p class="text-muted mb-0">Agent immobilier</p>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('messenger', ['recipient_id' => $visit->agent_id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Contacter l'agent
                            </a>
                            @if($visit->agent->phone)
                                <a href="tel:{{ $visit->agent->phone }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-phone me-2"></i>{{ $visit->agent->phone }}
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">Aucun agent n'est actuellement assigné à cette visite.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations pratiques</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-map-marker-alt text-primary me-2"></i>Adresse : {{ $visit->property->address }}, {{ $visit->property->city }} {{ $visit->property->postal_code }}</p>
                    <p><i class="fas fa-info-circle text-primary me-2"></i>La visite dure environ 1 heure.</p>
                    <p><i class="fas fa-id-card text-primary me-2"></i>Veuillez vous munir d'une pièce d'identité.</p>
                    <p><i class="fas fa-clock text-primary me-2"></i>Merci d'arriver à l'heure prévue.</p>
                    
                    @if($visit->isConfirmed())
                        <div class="mt-3">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($visit->property->address . ', ' . $visit->property->city . ' ' . $visit->property->postal_code) }}" class="btn btn-outline-primary w-100" target="_blank">
                                <i class="fas fa-map me-2"></i>Voir sur Google Maps
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection