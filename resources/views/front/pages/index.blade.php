@extends('components.front.layouts.front')

@section('content')

    <section class="relative mt-3">
        <div class="container-fluid md:mx-4 mx-2">
            <div class="relative pt-40 pb-52 table w-full rounded-3xl shadow-md overflow-hidden">
                <div class="absolute inset-0 bg-black/60"></div>

                <div class="container relative">
                    <div class="grid grid-cols-1">
                        <div class="md:text-start text-center">
                            <h1 class="font-bold text-white lg:leading-normal text-4xl lg:text-5xl mb-6"></h1>
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
                        <div class="p-6 bg-white dark:bg-slate-900 rounded-xl shadow-lg dark:shadow-gray-700">
                            <div id="buy-home" role="tabpanel" aria-labelledby="buy-home-tab">
                                <form action="#" method="GET">
                                    <div class="text-dark text-start">
                                        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">

                                            <!-- Champ de recherche -->
                                            <div>
                                                <label for="job-keyword"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Rechercher <span class="text-red-600">*</span>
                                                </label>
                                                <div class="relative mt-2">
                                                    <i
                                                        class="uil uil-search icons absolute left-3 top-3 text-gray-400"></i>
                                                    <input type="text" id="job-keyword" name="keyword"
                                                        class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-0"
                                                        placeholder="Rechercher des mots-clés">
                                                </div>
                                            </div>

                                            <!-- Sélection de catégorie -->
                                            <div>
                                                <label for="choices-catagory-buy"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Sélectionnez une catégorie
                                                </label>
                                                <div class="relative mt-2">
                                                    <i
                                                        class="uil uil-estate icons absolute left-3 top-3 text-gray-400"></i>
                                                    <select id="choices-catagory-buy" name="category"
                                                        class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-0">
                                                        <option>Maisons</option>
                                                        <option>Appartements</option>
                                                        <option>Bureaux</option>
                                                        <option>Maisons de ville</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Sélection de ville -->
                                            <div>
                                                <label for="choix-de-ville"
                                                    class="form-label font-medium text-slate-900 dark:text-white">
                                                    Ville
                                                </label>
                                                <div class="relative mt-2">
                                                    <i
                                                        class="uil uil-location-point icons absolute left-3 top-3 text-gray-400"></i>
                                                    <select id="choix-de-ville" name="city"
                                                        class="w-full pl-10 pr-4 py-2 rounded-md border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-0">
                                                        <option>Ville</option>
                                                        <option>Abidjan</option>
                                                        <option>Bouake</option>
                                                        <option>Yamoussokro</option>
                                                        <option>Korhogo</option>
                                                        <option>Man</option>
                                                        <option>Abengourou</option>
                                                        <option>Daoukro</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Sélection du prix avec slider -->
                                            <div>
                                                <label class="form-label font-medium text-slate-900 dark:text-white">
                                                    Prix (min - max)
                                                </label>
                                                <div class="relative mt-4 space-y-2">
                                                    <input type="range" id="price-min" name="price-min" min="50000"
                                                        max="5000000" step="5000" value="50000"
                                                        class="w-full accent-orange-500/50">
                                                    <input type="range" id="price-max" name="price-max" min="50000"
                                                        max="5000000" step="5000" value="500000"
                                                        class="w-full mt-2 accent-orange-500">
                                                    <div
                                                        class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-3 ">
                                                        <span>Prix minimum : <span class="whitespace-nowrap" id="minPriceValue">50 000</span>
                                                            Cfa</span>
                                                        <span>Prix maximum : <span class="whitespace-nowrap" id="maxPriceValue">500 000</span>
                                                            Cfa</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bouton de recherche -->
                                            <div class="lg:mt-6 mt-4">
                                                <button type="submit" id="search-buy"
                                                    class="w-full py-3 rounded-lg bg-orange-600 text-white font-semibold hover:bg-orange-700 transition-colors">
                                                    Rechercher
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Carte d'une propriété -->
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative group">
                        <!-- Swiper container -->
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 1">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 2">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 3">
                                </div>
                            </div>
                            <!-- Pagination -->
                            <div class="swiper-pagination text-white"></div>

                            <!-- Navigation Buttons -->
                            <div
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
                            </div>



                            <!-- Favorite Button -->

                        </div>
                        <button
                            class="favorite-btn absolute top-4 right-4 z-50 p-2 shadow-md hover:text-white transition duration-300 focus:outline-none"
                            aria-label="Ajouter aux favoris">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>

                    </div>

                    <div class="p-6 space-y-4">
                        <h3 class="text-xl font-semibold hover:text-black transition">
                            10765 Hillshire Ave, Baton Rouge, LA 70810, USA
                        </h3>
                        <div class="flex space-x-2">
                            <span class="bg-black/70 py-1 px-3 text-white text-sm rounded-full">Appartement</span>
                            <span class="bg-red-700/70 py-1 px-3 text-white text-sm rounded-full">À vendre</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Price</p>
                                <p class="text-2xl font-bold">€5000</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Rating</p>
                                <div class="flex items-center text-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927a1 1 0 011.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative group">
                        <!-- Swiper container -->
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 1">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 2">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 3">
                                </div>
                            </div>
                            <!-- Pagination -->
                            <div class="swiper-pagination text-white"></div>

                            <!-- Navigation Buttons -->
                            <div
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
                            </div>



                            <!-- Favorite Button -->

                        </div>
                        <button
                            class="favorite-btn absolute top-4 right-4 z-50 p-2 shadow-md hover:text-white transition duration-300 focus:outline-none"
                            aria-label="Ajouter aux favoris">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>

                    </div>

                    <div class="p-6 space-y-4">
                        <h3 class="text-xl font-semibold hover:text-black transition">
                            10765 Hillshire Ave, Baton Rouge, LA 70810, USA
                        </h3>
                        <div class="flex space-x-2">
                            <span class="bg-black/70 py-1 px-3 text-white text-sm rounded-full">Appartement</span>
                            <span class="bg-red-700/70 py-1 px-3 text-white text-sm rounded-full">À vendre</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Price</p>
                                <p class="text-2xl font-bold">€5000</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Rating</p>
                                <div class="flex items-center text-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927a1 1 0 011.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="relative group">
                        <!-- Swiper container -->
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 1">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 2">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                        class="w-full h-full object-cover" alt="Property 3">
                                </div>
                            </div>
                            <!-- Pagination -->
                            <div class="swiper-pagination"></div>

                            <!-- Navigation Buttons -->
                            <div
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
                            </div>



                            <!-- Favorite Button -->

                        </div>
                        <button
                            class="favorite-btn absolute top-4 right-4 z-50 p-2 shadow-md hover:text-white transition duration-300 focus:outline-none"
                            aria-label="Ajouter aux favoris">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>

                    </div>

                    <div class="p-6 space-y-4">
                        <h3 class="text-xl font-semibold hover:text-black transition">
                            10765 Hillshire Ave, Baton Rouge, LA 70810, USA
                        </h3>
                        <div class="flex space-x-2">
                            <span class="bg-black/70 py-1 px-3 text-white text-sm rounded-full">Appartement</span>
                            <span class="bg-red-700/70 py-1 px-3 text-white text-sm rounded-full">À vendre</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Price</p>
                                <p class="text-2xl font-bold">€5000</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Rating</p>
                                <div class="flex items-center text-yellow-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927a1 1 0 011.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">5.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <div class="hidden lg:block fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50">
        <button class="bg-black text-white px-4 py-2 rounded-full shadow-md">
            Afficher la carte 🗺️
        </button>
    </div>

    @endsection

