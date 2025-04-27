<!-- resources/views/properties/visits/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Mes visites</h1>
            <p class="text-muted">Gérez vos demandes de visites de propriétés</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="visitsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                                Visites à venir
                                @if(count($upcomingVisits) > 0)
                                    <span class="badge bg-primary ms-2">{{ count($upcomingVisits) }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                                Visites passées
                                @if(count($pastVisits) > 0)
                                    <span class="badge bg-secondary ms-2">{{ count($pastVisits) }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="visitsTabContent">
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if(count($upcomingVisits) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Propriété</th>
                                                <th>Date</th>
                                                <th>Horaire</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingVisits as $visit)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                @if($visit->property->images && count($visit->property->images) > 0)
                                                                    <img src="{{ asset('storage/' . $visit->property->images[0]) }}" alt="{{ $visit->property->title }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                        <i class="fas fa-home"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $visit->property->title }}</h6>
                                                                <small class="text-muted">{{ $visit->property->city }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $visit->visit_date->format('d/m/Y') }}</td>
                                                    <td>{{ $visit->visit_time_start }} - {{ $visit->visit_time_end }}</td>
                                                    <td>
                                                        <span class="badge {{ $visit->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                            {{ $visit->status === 'pending' ? 'En attente' : 'Confirmée' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('visits.show', $visit) }}" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('properties.show', $visit->property) }}" class="btn btn-outline-secondary">
                                                                <i class="fas fa-home"></i>
                                                            </a>
                                                            @if($visit->agent)
                                                                <a href="{{ route('messenger', ['recipient_id' => $visit->agent_id]) }}" class="btn btn-outline-info">
                                                                    <i class="fas fa-envelope"></i>
                                                                </a>
                                                            @endif
                                                            <a href="{{ route('visits.cancel.form', $visit) }}" class="btn btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5>Aucune visite à venir</h5>
                                    <p class="text-muted">Vous n'avez pas de visites programmées pour le moment.</p>
                                    <a href="{{ route('properties.index') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Rechercher des propriétés
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                            @if(count($pastVisits) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Propriété</th>
                                                <th>Date</th>
                                                <th>Horaire</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pastVisits as $visit)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                @if($visit->property->images && count($visit->property->images) > 0)
                                                                    <img src="{{ asset('storage/' . $visit->property->images[0]) }}" alt="{{ $visit->property->title }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                        <i class="fas fa-home"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $visit->property->title }}</h6>
                                                                <small class="text-muted">{{ $visit->property->city }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $visit->visit_date->format('d/m/Y') }}</td>
                                                    <td>{{ $visit->visit_time_start }} - {{ $visit->visit_time_end }}</td>
                                                    <td>
                                                        <span class="badge {{ 
                                                            $visit->status === 'completed' ? 'bg-info' : 
                                                            ($visit->status === 'cancelled' ? 'bg-danger' : 'bg-secondary') 
                                                        }}">
                                                            {{ 
                                                                $visit->status === 'completed' ? 'Terminée' : 
                                                                ($visit->status === 'cancelled' ? 'Annulée' : $visit->status) 
                                                            }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('visits.show', $visit) }}" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('properties.show', $visit->property) }}" class="btn btn-outline-secondary">
                                                                <i class="fas fa-home"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5>Aucune visite passée</h5>
                                    <p class="text-muted">Vous n'avez pas encore effectué de visites.</p>
                                    <a href="{{ route('properties.index') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Rechercher des propriétés
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection