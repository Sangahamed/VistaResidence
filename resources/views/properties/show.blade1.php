<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $property->title }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $property)
                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Modifier
                </a>
                @endcan
                
                <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="mb-6">
                                <div class="relative h-96 rounded-lg overflow-hidden">
                                    <img src="{{ $property->featured_image_url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                    <div class="absolute top-0 right-0 p-2">
                                        @if($property->status === 'available')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disponible</span>
                                        @elseif($property->status === 'sold')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Vendu</span>
                                        @elseif($property->status === 'rented')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Loué</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($property->images->count() > 0)
                                    <div class="mt-2 grid grid-cols-4 gap-2">
                                        @foreach($property->images->take(4) as $image)
                                            <div class="h-24 rounded-lg overflow-hidden">
                                                <img src="{{ $image->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($property->images->count() > 4)
                                        <div class="mt-2 text-right">
                                            <button type="button" class="text-sm text-indigo-600 hover:text-indigo-900" x-data x-on:click="$dispatch('open-modal', 'property-gallery')">
                                                Voir toutes les photos ({{ $property->images->count() }})
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                                <div class="prose max-w-none">
                                    {!! nl2br(e($property->description)) !!}
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Caractéristiques</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                        </svg>
                                        <span>Surface: {{ $property->area }} m²</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                        </svg>
                                        <span>Chambres: {{ $property->bedrooms }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 013.5 3.5V9H2V5.5A3.5 3.5 0 015.5 2zM11 9V5.5a5.5 5.5 0 00-11 0V9h11zm-2.5 2a3.5 3.5 0 013.5 3.5V18H2v-3.5A3.5 3.5 0 015.5 11H8.5zM11 18v-3.5a5.5 5.5 0 00-11 0V18h11z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Salles de bain: {{ $property->bathrooms }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Type: {{ $property->propertyType->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Adresse: {{ $property->address }}, {{ $property->city }} {{ $property->postal_code }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Référence: {{ $property->reference }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($property->latitude && $property->longitude)
                                <div class="mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Localisation</h3>
                                    <div id="map" class="h-64 w-full rounded-lg"></div>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Prix</h3>
                                    <span class="text-2xl font-bold text-indigo-600">{{ number_format($property->price, 0, ',', ' ') }} €</span>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Demander une visite</h4>
                                    @auth
                                        <form action="{{ route('visits.request', $property) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="visit_date" class="block text-sm font-medium text-gray-700">Date souhaitée</label>
                                                <input type="date" name="visit_date" id="visit_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required min="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="mb-4">
                                                <label for="visit_time" class="block text-sm font-medium text-gray-700">Heure souhaitée</label>
                                                <select name="visit_time" id="visit_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                    <option value="">Sélectionnez une heure</option>
                                                    @for($hour = 9; $hour <= 18; $hour++)
                                                        <option value="{{ sprintf('%02d', $hour) }}:00">{{ sprintf('%02d', $hour) }}:00</option>
                                                        @if($hour < 18)
                                                            <option value="{{ sprintf('%02d', $hour) }}:30">{{ sprintf('%02d', $hour) }}:30</option>
                                                        @endif
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="message" class="block text-sm font-medium text-gray-700">Message (optionnel)</label>
                                                <textarea name="message" id="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            </div>
                                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Demander une visite
                                            </button>
                                        </form>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-sm text-gray-500 mb-4">Connectez-vous pour demander une visite</p>
                                            <a href="{{ route('login') }}" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Se connecter
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Agent immobilier</h3>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full" src="{{ $property->agent->user->profile_photo_url }}" alt="{{ $property->agent->user->name }}">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $property->agent->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $property->agent->agency->name }}</p>
                                        
                                        @auth
                                            <a href="{{ route('chatify.conversation', $property->agent->user->id) }}" class="inline-flex items-center mt-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                                Contacter l'agent
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="inline-flex items-center mt-2 px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                                Connexion pour contacter
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($similarProperties->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Propriétés similaires</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($similarProperties as $similarProperty)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="relative h-48">
                                    <img src="{{ $similarProperty->featured_image_url }}" alt="{{ $similarProperty->title }}" class="w-full h-full object-cover">
                                    <div class="absolute top-0 right-0 p-2">
                                        @if($similarProperty->status === 'available')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disponible</span>
                                        @elseif($similarProperty->status === 'sold')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Vendu</span>
                                        @elseif($similarProperty->status === 'rented')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Loué</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">
                                        <a href="{{ route('properties.show', $similarProperty) }}" class="hover:text-indigo-600">{{ $similarProperty->title }}</a>
                                    </h4>
                                    <p class="text-sm text-gray-500 mb-2">{{ $similarProperty->city }}, {{ $similarProperty->postal_code }}</p>
                                    <p class="text-lg font-bold text-indigo-600 mb-2">{{ number_format($similarProperty->price, 0, ',', ' ') }} €</p>
                                    <div class="flex space-x-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                            </svg>
                                            {{ $similarProperty->area }} m²
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                            </svg>
                                            {{ $similarProperty->bedrooms }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 013.5 3.5V9H2V5.5A3.5 3.5 0 015.5 2zM11 9V5.5a5.5 5.5 0 00-11 0V9h11zm-2.5 2a3.5 3.5 0 013.5 3.5V18H2v-3.5A3.5 3.5 0 015.5 11H8.5zM11 18v-3.5a5.5 5.5 0 00-11 0V18h11z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $similarProperty->bathrooms }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    @if($property->latitude && $property->longitude)
        @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
        <script>
            function initMap() {
                const position = {
                    lat: {{ $property->latitude }},
                    lng: {{ $property->longitude }}
                };
                
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: position,
                    zoom: 15
                });
                
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: '{{ $property->title }}'
                });
            }
        </script>
        @endpush
    @endif
    
    <x-modal name="property-gallery" :show="false" maxWidth="6xl">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Galerie de photos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($property->images as $image)
                    <div class="h-64 rounded-lg overflow-hidden">
                        <img src="{{ $image->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            <div class="mt-6 text-right">
                <button type="button" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" x-on:click="$dispatch('close')">
                    Fermer
                </button>
            </div>
        </div>
    </x-modal>
</x-app-layout>