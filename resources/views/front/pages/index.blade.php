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

        .new-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>

    <section class="relative mt-3">
        <div class="container-fluid md:mx-4 mx-2">
            <div class="relative pt-40 pb-52 table w-full rounded-3xl shadow-md overflow-hidden">
                <div class="absolute inset-0 bg-black/60"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/30 to-purple-500/30"></div>

                <div class="container relative z-10">
                    <div class="grid grid-cols-1">
                        <div class="md:text-start text-center">
                            <h1 class="font-bold text-white lg:leading-normal text-4xl lg:text-5xl mb-6 animate-fade-in">
                                Trouvez votre propriété idéale
                            </h1>
                            <p class="text-xl text-white/80 max-w-2xl mx-auto md:mx-0 animate-fade-in delay-100">
                                Découvrez des milliers d'offres parmi nos biens sélectionnés
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative md:pb-24 pb-20 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid justify-center">
                <div class="relative -mt-32">
                    <div class="grid">
                        <div
                            class="p-6 bg-white dark:bg-slate-900 rounded-2xl shadow-xl dark:shadow-gray-700 transition-all duration-300 hover:shadow-2xl">
                            <div id="buy-home" role="tabpanel" aria-labelledby="buy-home-tab">
                                <form action="{{ route('properties.index') }}" method="GET"
                                    class="animate-fade-in delay-200">
                                    <div class="text-dark text-start">
                                        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
                                            <!-- Champ de recherche -->
                                            <div>
                                                <label for="search"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Rechercher <span class="text-red-600">*</span>
                                                </label>
                                                <div class="relative mt-2">
                                                    <i class="uil uil-search icons absolute left-3 top-3 text-gray-400"></i>
                                                    <input type="text" id="search" name="search"
                                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                                                        placeholder="Rechercher des mots-clés"
                                                        value="{{ request('search') }}">
                                                </div>
                                            </div>

                                            <!-- Sélection de catégorie -->
                                            <div>
                                                <label for="type"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Catégorie
                                                </label>
                                                <div class="relative mt-2">
                                                    <i class="uil uil-estate icons absolute left-3 top-3 text-gray-400"></i>
                                                    <select id="type" name="type"
                                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 appearance-none">
                                                        <option value="">Toutes les catégories</option>
                                                        @foreach ($propertyTypes as $type)
                                                            <option value="{{ $type }}">{{ ucfirst($type) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Sélection de ville -->
                                            <div>
                                                <label for="city"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Ville
                                                </label>
                                                <div class="relative mt-2">
                                                    <i
                                                        class="uil uil-location-point icons absolute left-3 top-3 text-gray-400"></i>
                                                    <select id="city" name="city"
                                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 appearance-none">
                                                        <option value="">Toutes les villes</option>
                                                        @foreach ($propertycity as $city)
                                                            <option value="{{ $city }}">{{ ucfirst($city) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Sélection du prix avec slider -->
                                            <div>
                                                <label class="form-label font-medium text-slate-900 dark:text-white">
                                                    Prix (min - max)
                                                </label>
                                                <div class="relative mt-4 space-y-2">
                                                    <input type="range" id="price-min" name="price_min" min="50000"
                                                        max="5000000" step="5000"
                                                        value="{{ request('price_min', 50000) }}"
                                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-slider">
                                                    <input type="range" id="price-max" name="price_max" min="50000"
                                                        max="5000000" step="5000"
                                                        value="{{ request('price_max', 500000) }}"
                                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-slider">
                                                    <div
                                                        class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-3">
                                                        <span>Min : <span class="font-medium"
                                                                id="minPriceValue">{{ number_format(request('price_min', 50000), 0, ',', ' ') }}</span>
                                                            Cfa</span>
                                                        <span>Max : <span class="font-medium"
                                                                id="maxPriceValue">{{ number_format(request('price_max', 500000), 0, ',', ' ') }}</span>
                                                            Cfa</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bouton de recherche -->
                                            <div class="lg:mt-6 mt-4">
                                                <button type="submit" id="search-buy"
                                                    class="w-full py-3 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg flex items-center justify-center">
                                                    <i class="fas fa-search mr-2"></i> Rechercher
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 lg:py-8 -mt-10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Nos propriétés</h2>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $properties->total() }} propriétés trouvées
                </div>
            </div>

            @if ($properties->isEmpty())
                <div class="text-center py-12">
                    <div class="text-orange-500 text-5xl mb-4">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Aucune propriété trouvée</h3>
                    <p class="text-gray-600 dark:text-gray-300">Essayez de modifier vos critères de recherche</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($properties as $property)
                        <div
                            class="property-card bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl group">
                            <div class="relative h-64 overflow-hidden">
                                <!-- Swiper container -->
                                <div class="swiper h-full">
                                    <div class="swiper-wrapper">
                                        @foreach ($property->images as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ Storage::url($image['path']) }}"
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                    alt="{{ $property->title }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Pagination -->
                                    <div class="swiper-pagination text-white"></div>

                                    <!-- Navigation Buttons -->
                                    {{-- <div
                                        class="swiper-button-prev hidden lg:flex absolute top-[60%] left-4 z-30 items-center justify-center w-6 h-6 -translate-y-1/2 rounded-full bg-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-white/90">
                                        <svg class="w-4 h-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </div>
                                    <div
                                        class="swiper-button-next hidden lg:flex absolute top-[60%] right-4 z-30 items-center justify-center w-6 h-6 -translate-y-1/2 rounded-full bg-white/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-white/90">
                                        <svg class="w-4 h-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div> --}}
                                </div>

                                <!-- Badges -->
                                <div class="absolute top-4 left-4 z-10 flex gap-2">
                                    @if ($property->created_at->diffInDays() < 7)
                                        <span
                                            class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-bold new-badge">
                                            Nouveau
                                        </span>
                                    @endif
                                    <span
                                        class="{{ $property->status === 'for_sale' ? 'bg-blue-500' : 'bg-purple-500' }} text-white text-xs px-2 py-1 rounded-full font-bold">
                                        {{ $property->status === 'for_sale' ? 'À vendre' : 'Location' }}
                                    </span>
                                </div>

                                <!-- Bouton favori -->
                                @livewire('toggle-favorite', ['property' => $property], key($property->id))
                            </div>

                            <div class="p-6">
                                <h3
                                    class="text-xl font-bold text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-orange-500 mb-2">
                                    {{ $property->type }} : <a
                                        href="{{ route('detail', $property->slug) }}">{{ $property->title }} à
                                        {{ $property->city }}</a>
                                </h3>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-3">
                                    <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                    {{ $property->city }},{{ $property->address }}
                                </div>

                                <div class="flex flex-wrap gap-4 mb-4">
                                    @if ($property->bedrooms)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-bed text-orange-500 mr-2"></i>
                                            {{ $property->bedrooms }} chambres
                                        </div>
                                    @endif
                                    @if ($property->bathrooms)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-bath text-orange-500 mr-2"></i>
                                            {{ $property->bathrooms }} salles de bain
                                        </div>
                                    @endif
                                    @if ($property->size)
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-ruler-combined text-orange-500 mr-2"></i>
                                            {{ $property->size }} m²
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-tag text-orange-500 mr-2"></i>
                                            {{ $property->features[1] ?? 'Non défini' }}
                                        </div>
                                    @endif
                                </div>

                                <div
                                    class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <div class="text-2xl font-bold text-orange-500">
                                        {{ number_format($property->price, 0, ',', ' ') }} FCFA
                                        @if ($property->status !== 'for_sale')
                                            <span class="text-sm font-normal">/mois</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('detail', $property->slug) }}"
                                        class="text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg transition-colors duration-300">
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $properties->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-orange-500 to-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-6 animate-fade-in">Vous cherchez quelque chose de spécifique ?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto animate-fade-in delay-100">
                Notre équipe d'experts peut vous aider à trouver la propriété parfaite.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in delay-200">
                <a href="#"
                    class="px-8 py-3 bg-white text-orange-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-300 transform hover:-translate-y-1">
                    Parlez à un expert
                </a>
                <a href="{{ route('properties.index') }}"
                    class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition-colors duration-300 transform hover:-translate-y-1">
                    Voir toutes les offres
                </a>
            </div>
        </div>
    </section>

    <!-- Fixed CTA Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="#"
            class="flex items-center justify-center w-14 h-14 bg-orange-500 hover:bg-orange-600 text-white rounded-full shadow-xl transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-phone-alt text-xl"></i>
        </a>
    </div>
@endsection
