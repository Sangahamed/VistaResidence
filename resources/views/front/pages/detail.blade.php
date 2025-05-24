@extends('components.front.layouts.front')

@section('content')
    <style>
        .favorite-btn {
            transition: all 0.3s ease;
        }

        .favorite-btn.active {
            color: #ef4444;
        }

        .star-rating i {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .star-rating i:hover {
            transform: scale(1.2);
        }
    </style>


    <section class="relative mt-28 lg:py-8">
        <div class="container px-4">
            {{-- <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="#"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="#"
                                class="ml-1 text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors md:ml-2">
                                Propriétés
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                                Détails
                            </span>
                        </div>
                    </li>
                </ol>
            </nav> --}}
            <div x-data="{ activeTab: 'images' }" class="mb-12">
                <!-- Navigation onglets -->
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="flex space-x-8">
                        <button @click="activeTab = 'images'"
                            :class="activeTab === 'images' ? 'text-orange-500 border-orange-500' :
                                'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                            class="py-4 px-1 border-b-2 font-medium text-sm">
                            Images ({{ count($property->images) }})
                        </button>

                        @if (count($property->videos) > 0)
                            <button @click="activeTab = 'videos'"
                                :class="activeTab === 'videos' ? 'text-orange-500 border-orange-500' :
                                    'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                                Vidéos ({{ count($property->videos) }})
                            </button>
                        @endif
                    </nav>
                </div>

                <!-- IMAGES -->
                <div x-show="activeTab === 'images'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    @php $images = collect($property->images); @endphp

                    @if ($images->isNotEmpty())
                        <!-- Image principale -->
                        <div class="relative overflow-hidden rounded-xl aspect-w-4 aspect-h-3 group">
                            <a href="#lightbox-1" class="block h-full">
                                <img src="{{ Storage::url($images[0]['path']) }}" alt="Main Property Image"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                                </div>
                                <div class="absolute bottom-4 left-4 text-white text-lg font-medium flex items-center">
                                    <i class="fas fa-expand mr-2"></i> Cliquez pour agrandir
                                </div>
                            </a>
                        </div>
                    @endif

                    <!-- Miniatures -->
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($images->slice(1, 4) as $index => $image)
                            <div class="relative overflow-hidden rounded-xl aspect-w-1 aspect-h-1 group">
                                <a href="#lightbox-{{ $index + 2 }}" class="block h-full">
                                    <img src="{{ Storage::url($image['path']) }}" alt="Image {{ $index + 2 }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @if ($index === 3 && $images->count() > 5)
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                            <span class="text-white text-xl font-bold">+{{ $images->count() - 5 }}</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- VIDEOS -->
                <div x-show="activeTab === 'videos'" x-transition>
                    <template x-if="activeTab === 'videos'">
                        <div class="grid grid-cols-1 gap-6">
                            @foreach ($property->videos as $video)
                                <div
                                    class="relative aspect-w-16 aspect-h-9 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden">
                                    <iframe class="w-full h-full" src="{{ Storage::url($video['path']) }}" frameborder="0"
                                        allow="autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>

            <!-- Lightbox (inchangé) -->
            @foreach ($images as $index => $image)
                <div id="lightbox-{{ $index + 1 }}" class="lightbox">
                    <a href="#" class="lightbox-close">&times;</a>
                    <img src="{{ Storage::url($image['path']) }}" alt="Image {{ $index + 1 }}">
                    @if ($images->count() > 1)
                        <a href="#lightbox-{{ $index === 0 ? $images->count() : $index }}"
                            class="lightbox-nav lightbox-prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="#lightbox-{{ $index + 2 > $images->count() ? 1 : $index + 2 }}"
                            class="lightbox-nav lightbox-next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif
                </div>
            @endforeach



            <!-- Property Details -->
            <div class="grid md:grid-cols-12 grid-cols-1 gap-8">

                <div class="lg:col-span-8 md:col-span-7">
                    <!-- Titre et Infos de base -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col md:flex-row md:items-center justify-between mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $property->title }} à {{ $property->city }}
                        </h1>
                        @php
                            $avgRating = $property->reviews->avg('rating') ?? 0;
                            $fullStars = floor($avgRating);
                            $halfStar = $avgRating - $fullStars >= 0.5;
                        @endphp
                        <div class="flex items-center mt-4 md:mt-0">
                            <div class="flex mr-2 space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @elseif($halfStar && $i == $fullStars + 1)
                                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($avgRating, 1) }}
                                ({{ $property->reviews->count() }} avis)</span>
                        </div>
                    </div>

                    <!-- Localisation -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt text-orange-500 text-lg mr-3"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $property->address }},
                            {{ $property->city }}</span>
                    </div>

                    <!-- Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Description</h2>
                        <div class="prose max-w-none text-gray-600 dark:text-gray-300">
                            {!! $property->description !!}
                        </div>
                    </div>

                    <!-- Caractéristiques -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8 mb-8">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                            Caractéristiques</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($property->size)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-ruler-combined text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Superficie</p>
                                        <p class="font-medium">{{ $property->size }} m²</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->bedrooms)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-bed text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Chambres</p>
                                        <p class="font-medium">{{ $property->bedrooms }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->bathrooms)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-bath text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Salles de bain</p>
                                        <p class="font-medium">{{ $property->bathrooms }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->floor)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-layer-group text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Étage</p>
                                        <p class="font-medium">{{ $property->floor }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->year_built)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-calendar-alt text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Année de construction</p>
                                        <p class="font-medium">{{ $property->year_built }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->furnished)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-couch text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Meublé</p>
                                        <p class="font-medium">{{ $property->furnished ? 'Oui' : 'Non' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($property->type)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-home text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Type</p>
                                        <p class="font-medium">{{ $property->type }}</p>
                                    </div>
                                </div>
                            @endif

                            @foreach ($property->features ?? [] as $feature)
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="feature-icon p-3 rounded-full mr-4">
                                        <i class="fas fa-tag text-orange-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Équipement</p>
                                        <p class="font-medium">{{ ucfirst($feature) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Localisation avec carte -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Localisation</h2>
                        <div class="h-96 rounded-lg overflow-hidden">
                            <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0"
                                marginwidth="0"
                                src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&z=15&output=embed">
                            </iframe>
                        </div>
                    </div>

                    <!-- Section Avis -->
                    @livewire('property-reviews', ['property' => $property])
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 md:col-span-5">
                    <div class="sticky top-6 space-y-6">
                        <!-- Contact Card -->
                        @if ($property->company)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                                <div class="flex items-center mb-6">
                                    @if ($property->company->logo)
                                        <img src="{{ Storage::url($property->company->logo) }}"
                                            alt="{{ $property->company->name }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-orange-500">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-full bg-orange-500 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ substr($property->company->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h4 class="font-bold text-lg">{{ $property->company->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm">Agence certifiée</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div
                                        class="rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700 overflow-hidden">
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($property->price, 0, ',', ' ') }} FCFA
                                                    @if ($property->status !== 'for_sale')
                                                        <span class="text-sm font-normal">/mois</span>
                                                    @endif
                                                </h3>
                                                <span
                                                    class="px-3 py-1 rounded-full text-sm font-medium
    {{ $property->status === 'for_sale' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                                    {{ $property->status === 'for_sale' ? 'À vendre' : 'Location' }}
                                                </span>

                                            </div>
                                            @guest
                                                <div class="text-center py-4">
                                                    <a href="{{ route('login') }}"
                                                        class="inline-flex items-center justify-center w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter pour contacter
                                                    </a>
                                                </div>
                                            @endguest

                                        </div>
                                    </div>

                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="feature-icon p-2 rounded-full mr-3">
                                            <i class="fas fa-envelope text-orange-500 text-sm"></i>
                                        </div>
                                        <span
                                            class="text-gray-700 dark:text-gray-300">{{ $property->company->email }}</span>
                                    </div>

                                    @if ($property->company->phone)
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="feature-icon p-2 rounded-full mr-3">
                                                <i class="fas fa-phone text-orange-500 text-sm"></i>
                                            </div>
                                            <span
                                                class="text-gray-700 dark:text-gray-300">{{ $property->company->phone }}</span>
                                        </div>
                                    @endif

                                    @if ($property->company->website)
                                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="feature-icon p-2 rounded-full mr-3">
                                                <i class="fas fa-globe text-orange-500 text-sm"></i>
                                            </div>
                                            <a href="{{ $property->company->website }}" target="_blank"
                                                class="text-blue-600 hover:underline">Site web</a>
                                        </div>
                                    @endif

                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="feature-icon p-2 rounded-full mr-3">
                                            <i class="fas fa-star text-orange-500 text-sm"></i>
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300">4.8/5 (24 avis)</span>
                                    </div>
                                </div>

                                @auth
                                    <form action="{{ route('properties.startConversation', $property) }}" method="POST"
                                        class="mt-6">
                                        @csrf
                                        <textarea name="message" rows="3"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Bonjour, je suis intéressé(e) par cette propriété..."></textarea>
                                        <button type="submit"
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                            Envoyer un message
                                        </button>
                                    </form>

                                    <div class="space-y-3 mt-4">
                                        <a href="{{ route('visits.create', ['property_id' => $property->id]) }}"
                                            class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-calendar-check mr-2"></i> Demander une visite
                                        </a>
                                        @if ($property->has_virtual_tour)
                                            <a href="{{ route('virtual-tour', $property) }}"
                                                class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                                <i class="fas fa-vr-cardboard mr-2"></i> Visite virtuelle
                                            </a>
                                        @endif
                                        <a href="{{ route('properties.comparison.add', $property) }}"
                                            class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Ajouter à la comparaison
                                        </a>
                                    </div>

                                @endauth
                            </div>
                        @else
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
                                <div class="flex items-center mb-6">
                                    <img src="{{ $property->owner->picture ?? 'https://randomuser.me/api/portraits/men/46.jpg' }}"
                                        alt="Propriétaire"
                                        class="w-16 h-16 rounded-full object-cover border-2 border-orange-500">
                                    <div class="ml-4">
                                        <h4 class="font-bold text-lg">{{ $property->owner->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm">Propriétaire</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div
                                        class="rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700 overflow-hidden">
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($property->price, 0, ',', ' ') }} FCFA
                                                    @if ($property->status !== 'for_sale')
                                                        <span class="text-sm font-normal">/mois</span>
                                                    @endif
                                                </h3>
                                                <span
                                                    class="px-3 py-1 rounded-full text-sm font-medium
    {{ $property->status === 'for_sale' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                                    {{ $property->status === 'for_sale' ? 'À vendre' : 'Location' }}
                                                </span>

                                            </div>
                                            @guest
                                                <div class="text-center py-4">
                                                    <a href="{{ route('login') }}"
                                                        class="inline-flex items-center justify-center w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                                                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter pour contacter
                                                    </a>
                                                </div>
                                            @endguest

                                        </div>
                                    </div>
                                </div>

                                @auth
                                    <form action="{{ route('properties.startConversation', $property) }}" method="POST"
                                        class="mt-6">
                                        @csrf
                                        <textarea name="message" rows="3"
                                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all mb-4 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Bonjour, je suis intéressé(e) par cette propriété..."></textarea>
                                        <button type="submit"
                                            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                            Envoyer un message
                                        </button>
                                    </form>

                                    <div class="space-y-3 mt-4">
                                        <a href="{{ route('visits.create', ['property_id' => $property->id]) }}"
                                            class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-calendar-check mr-2"></i> Demander une visite
                                        </a>
                                        @if ($property->has_virtual_tour)
                                            <a href="{{ route('virtual-tour', $property) }}"
                                                class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                                <i class="fas fa-vr-cardboard mr-2"></i> Visite virtuelle
                                            </a>
                                        @endif
                                        <a href="{{ route('properties.comparison.add', $property) }}"
                                            class="flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Ajouter à la comparaison
                                        </a>
                                    </div>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Similar Properties -->
            @if ($similar->isNotEmpty())
                <div class="mt-16">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Propriétés similaires</h2>
                        <a href="{{ route('properties.index') }}"
                            class="text-orange-500 hover:text-orange-600 font-medium">Voir plus</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($similar as $similarProperty)
                            <div
                                class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                                <div class="relative h-64 overflow-hidden">
                                    <div class="swiper h-full">
                                        <div class="swiper-wrapper">
                                            @foreach ($similarProperty->images as $image)
                                                <div class="swiper-slide">
                                                    <img src="{{ Storage::url($image['path']) }}"
                                                        class="w-full h-full object-cover"
                                                        alt="{{ $similarProperty->title }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                    <div class="absolute top-4 left-4 z-10 flex gap-2">
                                        @if ($similarProperty->created_at->diffInDays() < 7)
                                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                                Nouveau
                                            </span>
                                        @endif
                                        <span
                                            class="{{ $similarProperty->status === 'for_sale' ? 'bg-blue-500' : 'bg-purple-500' }} text-white text-xs px-2 py-1 rounded-full font-bold">
                                            {{ $similarProperty->status === 'for_sale' ? 'À vendre' : 'Location' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3
                                        class="text-xl font-bold text-gray-900 dark:text-white mb-2 hover:text-orange-500 transition-colors">
                                        <a
                                            href="{{ route('properties.show', $similarProperty->slug) }}">{{ $similarProperty->title }}</a>
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-3">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                        {{ $similarProperty->city }}, {{ $similarProperty->address }}
                                    </div>

                                    <div class="flex flex-wrap gap-4 mb-4">
                                        @if ($similarProperty->bedrooms)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-bed text-orange-500 mr-2"></i>
                                                {{ $similarProperty->bedrooms }} chambres
                                            </div>
                                        @endif
                                        @if ($similarProperty->bathrooms)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-bath text-orange-500 mr-2"></i>
                                                {{ $similarProperty->bathrooms }} salles de bain
                                            </div>
                                        @endif
                                        @if ($similarProperty->size)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-ruler-combined text-orange-500 mr-2"></i>
                                                {{ $similarProperty->size }} m²
                                            </div>
                                        @endif
                                    </div>

                                    <div
                                        class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="text-xl font-bold text-orange-500">
                                            {{ number_format($similarProperty->price, 0, ',', ' ') }} FCFA
                                            @if ($similarProperty->status !== 'for_sale')
                                                <span class="text-sm font-normal">/mois</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('detail', $similarProperty->slug) }}"
                                            class="text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg transition-colors">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col space-y-3">
        <a href="#"
            class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
            <i class="fab fa-whatsapp text-xl"></i>
        </a>
        <a href="#"
            class="w-12 h-12 bg-blue-500 hover:bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-phone-alt text-xl"></i>
        </a>
    </div>
@endsection
