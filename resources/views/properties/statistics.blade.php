<!-- resources/views/properties/statistics.blade.php -->
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
                    <li class="breadcrumb-item active" aria-current="page">Statistiques de visites</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Statistiques de visites</h1>
                <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la propriété
                </a>
            </div>
            <p class="text-muted">{{ $property->title }} - {{ $property->address }}, {{ $property->city }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h1 class="display-4 text-primary">{{ $totalVisits }}</h1>
                    <p class="text-muted mb-0">Visites totales</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h1 class="display-4 text-success">{{ $completedVisits }}</h1>
                    <p class="text-muted mb-0">Visites effectuées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h1 class="display-4 text-warning">{{ $pendingVisits + $confirmedVisits }}</h1>
                    <p class="text-muted mb-0">Visites à venir</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h1 class="display-4 text-danger">{{ $cancelledVisits }}</h1>
                    <p class="text-muted mb-0">Visites annulées</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Évolution des visites (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <canvas id="visitsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Taux de conversion</h5>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block">
                        <canvas id="conversionChart" width="200" height="200"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <h2 class="mb-0">{{ $conversionRate }}%</h2>
                            <p class="text-muted small mb-0">Taux de conversion</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1">Visites effectuées: <strong>{{ $completedVisits }}</strong></p>
                        <p class="mb-1">Visites annulées: <strong>{{ $cancelledVisits }}</strong></p>
                        <p class="mb-0">Taux d'annulation: <strong>{{ $cancellationRate }}%</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Jours les plus demandés</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularDaysChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Créneaux horaires les plus demandés</h5>
                </div>
                <div class="card-body">
                    <canvas id="popularTimeSlotsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Prochaines visites</h5>
                </div>
                <div class="card-body">
                    @if(count($upcomingVisits) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Visiteur</th>
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
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $visit->visitor->name }}</h6>
                                                        <small class="text-muted">{{ $visit->visitor->email }}</small>
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
                                                    <a href="{{ route('agent.visits.show', $visit) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('messages.create', ['recipient_id' => $visit->visitor_id]) }}" class="btn btn-outline-info">
                                                        <i class="fas fa-envelope"></i>
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
                            <p class="text-muted">Il n'y a pas de visites programmées pour cette propriété.</p>
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
        // Données pour le graphique d'évolution des visites
        const visitsData = @json($visitsByMonth);
        
        // Graphique d'évolution des visites
        const visitsCtx = document.getElementById('visitsChart').getContext('2d');
        new Chart(visitsCtx, {
            type: 'bar',
            data: {
                labels: visitsData.months,
                datasets: [
                    {
                        label: 'Total des visites',
                        data: visitsData.totals,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Visites effectuées',
                        data: visitsData.completed,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Visites annulées',
                        data: visitsData.cancelled,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Graphique de taux de conversion
        const conversionCtx = document.getElementById('conversionChart').getContext('2d');
        new Chart(conversionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Visites effectuées', 'Visites annulées', 'Visites en attente/confirmées'],
                datasets: [{
                    data: [{{ $completedVisits }}, {{ $cancelledVisits }}, {{ $pendingVisits + $confirmedVisits }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Graphique des jours populaires
        const popularDays = @json($popularDays);
        const popularDaysCtx = document.getElementById('popularDaysChart').getContext('2d');
        new Chart(popularDaysCtx, {
            type: 'bar',
            data: {
                labels: popularDays.map(item => item.day),
                datasets: [{
                    label: 'Nombre de visites',
                    data: popularDays.map(item => item.count),
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Graphique des créneaux horaires populaires
        const popularTimeSlots = @json($popularTimeSlots);
        const popularTimeSlotsCtx = document.getElementById('popularTimeSlotsChart').getContext('2d');
        new Chart(popularTimeSlotsCtx, {
            type: 'bar',
            data: {
                labels: popularTimeSlots.map(item => item.hour),
                datasets: [{
                    label: 'Nombre de visites',
                    data: popularTimeSlots.map(item => item.count),
                    backgroundColor: 'rgba(255, 159, 64, 0.5)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection