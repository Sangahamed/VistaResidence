<!-- resources/views/properties/visits/create.blade.php -->
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
                    <li class="breadcrumb-item active" aria-current="page">Demander une visite</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Demander une visite</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $property->title }}</h5>
                        <p class="text-muted">{{ $property->address }}, {{ $property->city }} {{ $property->postal_code }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2">{{ $property->type }}</span>
                            <span class="badge bg-secondary me-2">{{ $property->surface }} m²</span>
                            <span class="badge bg-info">{{ $property->bedrooms }} chambre(s)</span>
                        </div>
                        <p class="fw-bold">{{ number_format($property->price, 0, ',', ' ') }} €</p>
                    </div>

                    @if(count($availableDates) > 0)
                        <form action="{{ route('properties.visits.store', $property) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="visit_date" class="form-label">Date de visite</label>
                                <select class="form-select @error('visit_date') is-invalid @enderror" id="visit_date" name="visit_date" required>
                                    <option value="">Sélectionnez une date</option>
                                    @foreach($availableDates as $date)
                                        <option value="{{ $date['date'] }}" {{ old('visit_date') == $date['date'] ? 'selected' : '' }}>
                                            {{ $date['formatted'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('visit_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="visit_time" class="form-label">Créneau horaire</label>
                                <select class="form-select @error('visit_time') is-invalid @enderror" id="visit_time" name="visit_time" required disabled>
                                    <option value="">Sélectionnez d'abord une date</option>
                                </select>
                                @error('visit_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes ou questions (facultatif)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                <div class="form-text">Ajoutez des informations supplémentaires ou des questions pour l'agent immobilier.</div>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-calendar-check me-2"></i>Demander cette visite
                                </button>
                                <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à la propriété
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Aucun créneau disponible</strong>
                            <p class="mb-0">Il n'y a actuellement aucun créneau disponible pour visiter cette propriété. Veuillez réessayer ultérieurement ou contacter directement l'agent.</p>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('properties.show', $property) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la propriété
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations sur la visite</h5>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-info-circle text-primary me-2"></i>La visite dure environ 1 heure.</p>
                    <p><i class="fas fa-user text-primary me-2"></i>Un agent immobilier vous accompagnera.</p>
                    <p><i class="fas fa-id-card text-primary me-2"></i>Veuillez vous munir d'une pièce d'identité.</p>
                    <p><i class="fas fa-clock text-primary me-2"></i>Merci d'arriver à l'heure prévue.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Agent immobilier</h5>
                </div>
                <div class="card-body">
                    @if($property->agent)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $property->agent->name }}</h6>
                                <p class="text-muted mb-0">Agent immobilier</p>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('messenger', ['recipient_id' => $property->agent_id]) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Contacter l'agent
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Aucun agent n'est actuellement assigné à cette propriété.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visitDateSelect = document.getElementById('visit_date');
        const visitTimeSelect = document.getElementById('visit_time');
        
        // Données des créneaux disponibles par date
        const availableDates = @json($availableDates);
        
        // Fonction pour mettre à jour les créneaux horaires en fonction de la date sélectionnée
        function updateTimeSlots() {
            // Réinitialiser le select des créneaux
            visitTimeSelect.innerHTML = '';
            visitTimeSelect.disabled = true;
            
            const selectedDate = visitDateSelect.value;
            if (!selectedDate) {
                visitTimeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une date</option>';
                return;
            }
            
            // Trouver les créneaux disponibles pour la date sélectionnée
            const dateData = availableDates.find(date => date.date === selectedDate);
            if (dateData && dateData.slots.length > 0) {
                visitTimeSelect.disabled = false;
                visitTimeSelect.innerHTML = '<option value="">Sélectionnez un créneau</option>';
                
                // Ajouter les créneaux disponibles
                dateData.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.formatted;
                    option.textContent = slot.formatted;
                    visitTimeSelect.appendChild(option);
                });
                
                // Restaurer la valeur précédemment sélectionnée si elle existe
                const oldValue = "{{ old('visit_time') }}";
                if (oldValue) {
                    visitTimeSelect.value = oldValue;
                }
            } else {
                visitTimeSelect.innerHTML = '<option value="">Aucun créneau disponible pour cette date</option>';
            }
        }
        
        // Mettre à jour les créneaux lors du changement de date
        visitDateSelect.addEventListener('change', updateTimeSlots);
        
        // Initialiser les créneaux au chargement de la page
        updateTimeSlots();
    });
</script>
@endpush
@endsection