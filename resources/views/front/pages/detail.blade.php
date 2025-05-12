@extends('components.front.layouts.front')

@section('content')

<section class="relative mt-28 lg:py-8">
    <div class="container px-4">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors md:ml-2">
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
        </nav>

        <!-- Gallery Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <!-- Main Image -->
            <div class="relative overflow-hidden rounded-xl aspect-w-4 aspect-h-3 group">
                <a href="#lightbox-1" class="block h-full">
                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                        alt="Main Property Image"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 text-white text-lg font-medium flex items-center">
                        <i class="fas fa-expand mr-2"></i> Cliquez pour agrandir
                    </div>
                </a>
            </div>

            <!-- Thumbnails Grid -->
            <div class="grid grid-cols-2 gap-2">
                @foreach([1,2,3,4] as $index)
                <div class="relative overflow-hidden rounded-xl aspect-w-1 aspect-h-1 group">
                    <a href="#lightbox-{{ $index+1 }}" class="block h-full">
                        <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                            alt="Property Image {{ $index+1 }}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        @if($index === 3)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <span class="text-white text-xl font-bold">+3</span>
                        </div>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Lightbox Gallery -->
        @foreach([1,2,3,4,5] as $index)
        <div id="lightbox-{{ $index }}" class="lightbox">
            <a href="#" class="lightbox-close">&times;</a>
            <img src="https://a0.muscache.com/im/pictures/{{ $index % 2 === 0 ? 'miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702' : '4e26e5ec-0c7d-4f6a-8580-f8a00f45081e'  }}.jpeg?im_w=720"
                alt="Property Image {{ $index }}">
            @if($loop->count > 1)
            <a href="#lightbox-{{ $index === 1 ? $loop->count : $index-1 }}"
                class="lightbox-nav lightbox-prev">
                <i class="fas fa-chevron-left"></i>
            </a>
            <a href="#lightbox-{{ $index === $loop->count ? 1 : $index+1 }}"
                class="lightbox-nav lightbox-next">
                <i class="fas fa-chevron-right"></i>
            </a>
            @endif
        </div>
        @endforeach

        <!-- Property Details -->
        <div class="grid md:grid-cols-12 grid-cols-1 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8 md:col-span-7">
                <!-- Title and Basic Info -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Villa moderne à Cocody</h1>
                    <div class="flex items-center mt-4 md:mt-0">
                        <div class="flex mr-2">
                            @foreach([1,2,3,4,5] as $star)
                            <i class="fas fa-star text-{{ $star <= 4 ? 'yellow-400' : 'gray-300' }}"></i>
                            @endforeach
                        </div>
                        <span class="text-sm text-gray-600">4.2 (12 avis)</span>
                    </div>
                </div>

                <!-- Location -->
                <div class="flex items-center mb-6">
                    <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                    <span class="text-gray-700">Abidjan, Cocody - 2 rue des Jardins</span>
                </div>

                <!-- Key Features -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                        <i class="fas fa-bed text-2xl text-orange-500 mb-2"></i>
                        <div class="font-medium">4 Chambres</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                        <i class="fas fa-bath text-2xl text-orange-500 mb-2"></i>
                        <div class="font-medium">3 Salles de bain</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                        <i class="fas fa-ruler-combined text-2xl text-orange-500 mb-2"></i>
                        <div class="font-medium">180 m²</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg text-center">
                        <i class="fas fa-car text-2xl text-orange-500 mb-2"></i>
                        <div class="font-medium">2 Parkings</div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Description</h2>
                    <div class="prose max-w-none text-gray-600 dark:text-gray-300">
                        <p>Magnifique villa contemporaine située dans un quartier résidentiel calme de Cocody. Cette propriété de 180 m² offre un espace de vie spacieux et lumineux avec des finitions haut de gamme.</p>
                        <p>La villa comprend :</p>
                        <ul>
                            <li>4 chambres spacieuses dont une suite parentale</li>
                            <li>3 salles de bain modernes</li>
                            <li>Un grand salon/salle à manger de 50 m²</li>
                            <li>Cuisine équipée ouverte</li>
                            <li>Terrasse et jardin arboré de 300 m²</li>
                            <li>2 places de parking</li>
                        </ul>
                        <p>Idéalement située à proximité des écoles internationales, centres commerciaux et à seulement 15 minutes du centre-ville.</p>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Équipements</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            'Climatisation', 'Cuisine équipée', 'Internet haut débit', 
                            'Piscine', 'Système de sécurité', 'Jardin', 
                            'Terrasse', 'Parking privé', 'Générateur'
                        ] as $amenity)
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $amenity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                <i class="fas fa-star text-yellow-400 mr-2"></i>
                                Avis clients (12)
                            </h2>
                            <div class="mt-4 md:mt-0">
                                <div class="flex items-center">
                                    <div class="text-3xl font-bold mr-4">4.2</div>
                                    <div>
                                        <div class="flex mb-1">
                                            @foreach([1,2,3,4,5] as $star)
                                            <i class="fas fa-star text-{{ $star <= 4 ? 'yellow-400' : 'gray-300' }} text-sm"></i>
                                            @endforeach
                                        </div>
                                        <div class="text-sm text-gray-600">Moyenne sur 12 avis</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Review Form -->
                        <form id="reviewForm" class="mb-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Donnez votre avis</h3>
                            
                            <div class="flex items-center mb-4" id="starRating">
                                @foreach([1,2,3,4,5] as $star)
                                <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer transition-colors duration-200 hover:text-yellow-400 mr-1"></i>
                                @endforeach
                                <span class="text-sm text-gray-500 ml-2" id="ratingText">Noter cette propriété</span>
                            </div>
                            
                            <textarea id="reviewContent"
                                class="w-full p-4 border rounded-lg mb-4 bg-white dark:bg-gray-600 text-gray-800 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                rows="4" placeholder="Décrivez votre expérience..."></textarea>
                            
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-300 transform hover:-translate-y-1">
                                    Publier votre avis
                                </button>
                            </div>
                        </form>

                        <!-- Reviews List -->
                        <div class="space-y-6" id="reviewList">
                            <!-- Review 1 -->
                            <div class="flex space-x-4 pb-6 border-b border-gray-200 last:border-b-0">
                                <div class="flex-shrink-0">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                                         alt="Jean Dupont" 
                                         class="w-12 h-12 rounded-full object-cover shadow-md">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                        <h4 class="text-lg font-semibold">Jean Dupont</h4>
                                        <span class="text-sm text-gray-500">15 mars 2023</span>
                                    </div>
                                    <div class="flex mb-2">
                                        @foreach([1,2,3,4,5] as $star)
                                        <i class="fas fa-star text-{{ $star <= 5 ? 'yellow-400' : 'gray-300' }} text-sm"></i>
                                        @endforeach
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Excellente propriété, très bien située. Le propriétaire est très réactif et les équipements sont de qualité. Je recommande vivement !
                                    </p>
                                </div>
                            </div>

                            <!-- Review 2 -->
                            <div class="flex space-x-4 pb-6 border-b border-gray-200 last:border-b-0">
                                <div class="flex-shrink-0">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                                         alt="Marie Martin" 
                                         class="w-12 h-12 rounded-full object-cover shadow-md">
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                        <h4 class="text-lg font-semibold">Marie Martin</h4>
                                        <span class="text-sm text-gray-500">2 février 2023</span>
                                    </div>
                                    <div class="flex mb-2">
                                        @foreach([1,2,3,4,5] as $star)
                                        <i class="fas fa-star text-{{ $star <= 4 ? 'yellow-400' : 'gray-300' }} text-sm"></i>
                                        @endforeach
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Très bon rapport qualité-prix. Le quartier est calme et sécurisé. Petit bémol pour le parking un peu juste pour deux voitures.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 md:col-span-5">
                <div class="sticky top-6 space-y-6">
                    <!-- Price Card -->
                    <div class="rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700 overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">75 000 000 FCFA</h3>
                                <span class="bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                    À vendre
                                </span>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-300">Prix au m²</span>
                                    <span class="font-medium">416 667 FCFA</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-300">Disponible depuis</span>
                                    <span class="font-medium">15 jours</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-300">Référence</span>
                                    <span class="font-medium">PROP-2023-42</span>
                                </div>
                            </div>

                            <div class="flex flex-col space-y-3">
                                <a href="#contactForm" 
                                   class="btn bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg text-center font-medium transition-colors duration-300 transform hover:-translate-y-1">
                                    <i class="fas fa-envelope mr-2"></i> Contacter l'agent
                                </a>
                                <button class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium transition-colors duration-300 flex items-center justify-center">
                                    <i class="fas fa-phone-alt mr-2"></i> Appeler maintenant
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Agent Card -->
                    <div class="rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/men/46.jpg" 
                                     alt="Agent Immobilier" 
                                     class="w-16 h-16 rounded-full object-cover border-2 border-orange-500">
                                <div class="ml-4">
                                    <h4 class="font-bold text-lg">Koffi N'Guessan</h4>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm">Agent immobilier certifié</p>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-center">
                                    <i class="fas fa-briefcase text-orange-500 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">5 ans d'expérience</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-home text-orange-500 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">12 propriétés vendues</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-orange-500 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">4.8/5 (24 avis)</span>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 transition-colors">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 transition-colors">
                                    <i class="fab fa-facebook-messenger text-lg"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 transition-colors">
                                    <i class="fas fa-phone-alt text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-gray-700 overflow-hidden" id="contactForm">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Formulaire de contact</h3>
                            <form>
                                <div class="mb-4">
                                    <input type="text" 
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                           placeholder="Votre nom">
                                </div>
                                <div class="mb-4">
                                    <input type="email" 
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                           placeholder="Votre email">
                                </div>
                                <div class="mb-4">
                                    <input type="tel" 
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                           placeholder="Votre téléphone">
                                </div>
                                <div class="mb-4">
                                    <textarea class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                              rows="3" placeholder="Votre message"></textarea>
                                </div>
                                <button type="submit" 
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-300">
                                    Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Properties -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Propriétés similaires</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Similar Property 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://a0.muscache.com/im/pictures/miso/Hosting-52157178/original/39c9d4e7-78d0-4807-9f40-b43a30752d13.jpeg?im_w=720"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                             alt="Similar Property 1">
                        <span class="absolute top-4 left-4 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                            À vendre
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-1">Villa à Angré</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-2 text-xs"></i>
                            Abidjan, Angré
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-bold">65 000 000 FCFA</span>
                            <a href="#" class="text-sm text-orange-500 hover:text-orange-600 font-medium">
                                Voir <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Similar Property 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://a0.muscache.com/im/pictures/miso/Hosting-53065521/original/5d5a8a2a-0e3e-4b3c-8d3e-5e5f5b5e5d5e.jpeg?im_w=720"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                             alt="Similar Property 2">
                        <span class="absolute top-4 left-4 bg-purple-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                            Location
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-1">Appartement à Riviera</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-2 text-xs"></i>
                            Abidjan, Riviera
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-bold">450 000 FCFA/mois</span>
                            <a href="#" class="text-sm text-orange-500 hover:text-orange-600 font-medium">
                                Voir <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Similar Property 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://a0.muscache.com/im/pictures/miso/Hosting-52157178/original/39c9d4e7-78d0-4807-9f40-b43a30752d13.jpeg?im_w=720"
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                             alt="Similar Property 3">
                        <span class="absolute top-4 left-4 bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                            À vendre
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-1">Terrain à Marcory</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-2 text-xs"></i>
                            Abidjan, Marcory
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-500 font-bold">30 000 000 FCFA</span>
                            <a href="#" class="text-sm text-orange-500 hover:text-orange-600 font-medium">
                                Voir <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Action Buttons -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col space-y-3">
    <a href="#" class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
        <i class="fab fa-whatsapp text-xl"></i>
    </a>
    <a href="#" class="w-12 h-12 bg-blue-500 hover:bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
        <i class="fas fa-phone-alt text-xl"></i>
    </a>
</div>

@endsection