@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Tableau de bord particulier</h1>
            <p class="text-muted">Bienvenue sur votre espace personnel. Gérez vos propriétés et suivez vos revenus locatifs.</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Propriétés</h6>
                            <h3>{{ $stats['properties_count'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-home text-primary"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+1 depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Annonces actives</h6>
                            <h3>{{ $stats['active_listings'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-list text-success"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">Inchangé depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Revenus locatifs</h6>
                            <h3>{{ number_format($stats['rental_income']) }} €</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-euro-sign text-warning"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+800 € depuis le mois dernier</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Visites en attente</h6>
                            <h3>{{ $stats['pending_visits'] }}</h3>
                        </div>
                        <div class="bg-light p-3 rounded">
                            <i class="fas fa-calendar text-info"></i>
                        </div>
                    </div>
                    <p class="text-muted small mt-2">+2 depuis le mois dernier</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique des revenus locatifs -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenus locatifs</h5>
                </div>
                <div class="card-body">
                    <canvas id="rentalIncomeChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Visites à venir -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Visites à venir</h5>
                </div>
                <div class="card-body">
                    @if(count($upcomingVisits) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingVisits as $visit)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $visit->property->title }}</h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-user me-1"></i> {{ $visit->user->name }}
                                            </p>
                                            <p class="mb-0 text-muted small">
                                                <i class="fas fa-calendar me-1"></i> {{ $visit->visit_date->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">Confirmée</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Aucune visite à venir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mes propriétés -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Mes propriétés</h3>
                <a href="{{ route('properties.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une propriété
                </a>
            </div>
            
            <div class="row">
                @if(count($properties) > 0)
                    @foreach($properties as $property)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="{{ $property->featured_image ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $property->title }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">{{ $property->title }}</h5>
                                        <span class="badge {{ $property->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $property->status === 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <p class="card-text text-muted">{{ $property->location }}</p>
                                    <p class="card-text fw-bold">{{ number_format($property->price) }} €</p>
                                    <p class="card-text text-muted">
                                        {{ $property->surface }} m² - {{ $property->bedrooms }} ch.
                                    </p>
                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('properties.show', $property) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                        <a href="{{ route('properties.edit', $property) }}" class="btn btn-sm btn-outline-secondary">Modifier</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info">
                            Vous n'avez pas encore de propriétés. 
                            <a href="{{ route('properties.create') }}" class="alert-link">Ajouter une propriété</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contrats actifs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Contrats actifs</h5>
                </div>
                <div class="card-body">
                    @if(count($activeContracts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Propriété</th>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>Début</th>
                                        <th>Fin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeContracts as $contract)
                                        <tr>
                                            <td>{{ $contract->property->title }}</td>
                                            <td>
                                                @if($contract->type === 'rental')
                                                    <span class="badge bg-info">Location</span>
                                                @else
                                                    <span class="badge bg-primary">Vente</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($contract->amount) }} €</td>
                                            <td>{{ $contract->start_date->format('d/m/Y') }}</td>
                                            <td>{{ $contract->end_date ? $contract->end_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <p class="text-muted">Aucun contrat actif</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour le graphique des revenus locatifs
        const rentalData = @json($rentalData);
        
        const ctx = document.getElementById('rentalIncomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rentalData.map(item => item.month),
                datasets: [{
                    label: 'Revenus locatifs (€)',
                    data: rentalData.map(item => item.amount),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' €';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection