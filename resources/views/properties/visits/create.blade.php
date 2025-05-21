@extends('components.back.layout.back')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl animate-fade-in-down">
                Planifier une visite
            </h1>
            <div class="mt-4 h-1 w-24 bg-blue-500 mx-auto rounded-full animate-scale-x"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Property Card (if applicable) -->
                @if(isset($property) && $property)
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold text-gray-900 truncate">{{ $property->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $property->address }}, {{ $property->city }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ $property->type }}</span>
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">{{ $property->surface }} m²</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ $property->bedrooms }} chambres</span>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">{{ number_format($property->price, 0, ',', ' ') }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Visit Form -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-500 animate-fade-in-up">
                    <div class="p-6 sm:p-8">
                        <form action="{{ isset($property) ? route('visits.store', $property) : route('visits.store') }}" method="POST">
                            @csrf

                            @if(!isset($property) || !$property)
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de la visite privée</label>
                                <div class="relative">
                                    <input type="text" id="title" name="title" required
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                        placeholder="Ex: Visite appartement, Rencontre client...">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @else
                                <input type="hidden" name="property_id" value="{{ $property->id }}">
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <!-- Date Field -->
                                <div>
                                    <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                    <div class="relative">
                                        <input type="date" id="visit_date" name="visit_date" min="{{ date('Y-m-d') }}" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label for="visit_time_start" class="block text-sm font-medium text-gray-700 mb-2">Heure début</label>
                                    <div class="relative">
                                        <select id="visit_time_start" name="visit_time_start" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 appearance-none">
                                            @for($i = 9; $i <= 18; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">{{ $i }}:00</option>
                                            @endfor
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- End Time -->
                                <div>
                                    <label for="visit_time_end" class="block text-sm font-medium text-gray-700 mb-2">Heure fin</label>
                                    <div class="relative">
                                        <select id="visit_time_end" name="visit_time_end" required
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300 appearance-none">
                                            @for($i = 10; $i <= 19; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00" @if($i === 10) selected @endif>{{ $i }}:00</option>
                                            @endfor
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="mb-8">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes supplémentaires</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                    placeholder="Informations complémentaires..."></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Confirmer la visite
                                </button>
                                <a href="{{ isset($property) ? route('properties.show', $property) : route('visits.index') }}" 
                                   class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informations importantes
                        </h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-sm text-gray-600">Durée moyenne : 1 heure</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-sm text-gray-600">Présentation obligatoire</p>
                            </li>
                            @if(isset($property) && $property && $property->agent)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 h-6 w-6 text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-600">Agent : {{ $property->agent->name }}</p>
                                    <a href="{{ route('messenger', ['recipient_id' => $property->agent_id]) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        Contacter l'agent
                                    </a>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Availability Card -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Disponibilités
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="h-3 w-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Lundi - Vendredi</span>
                            </div>
                            <div class="flex items-center">
                                <div class="h-3 w-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm text-gray-600">9h - 19h</span>
                            </div>
                            <div class="flex items-center">
                                <div class="h-3 w-3 rounded-full bg-amber-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Samedi (sur rendez-vous)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time validation logic
    const startSelect = document.getElementById('visit_time_start');
    const endSelect = document.getElementById('visit_time_end');
    
    startSelect.addEventListener('change', function() {
        const startHour = parseInt(this.value.split(':')[0]);
        
        // Enable all options first
        Array.from(endSelect.options).forEach(option => {
            option.disabled = false;
        });
        
        // Disable invalid options
        Array.from(endSelect.options).forEach(option => {
            const endHour = parseInt(option.value.split(':')[0]);
            if (endHour <= startHour) {
                option.disabled = true;
            }
        });
        
        // Select first enabled option
        const firstEnabled = Array.from(endSelect.options).find(opt => !opt.disabled);
        if (firstEnabled) {
            endSelect.value = firstEnabled.value;
        }
    });
    
    // Trigger change event on load
    startSelect.dispatchEvent(new Event('change'));
});
</script>
@endpush

@push('styles')
<style>
.animate-fade-in-down {
    animation: fadeInDown 0.6s ease-out forwards;
}

.animate-scale-x {
    animation: scaleX 0.8s ease-out forwards;
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scaleX {
    from {
        transform: scaleX(0);
    }
    to {
        transform: scaleX(1);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom select arrow */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
</style>
@endpush
@endsection