<div>
    @if (empty($loadedProperties))
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
                                @if (!empty($property->images))
                                    @foreach ($property->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ Storage::url($image['path']) }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                alt="{{ $property->title }}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="swiper-slide">
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-home text-4xl text-gray-400"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="swiper-pagination text-white"></div>
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
                        </div>

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 z-10 flex gap-2 flex-wrap">
                            @if ($property->created_at && \Carbon\Carbon::parse($property->created_at)->diffInDays() < 7)
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

                        <!-- Bouton favori avec clé unique et stable -->
                        @livewire('toggle-favorite', ['property' => $property], key('fav-' . $property->id))
                    </div>

                    <div class="p-6">
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-white transition-colors duration-300 group-hover:text-orange-500 mb-2">
                            {{ $property->type }} :
                            <a href="{{ route('detail', $property->slug) }}">
                                {{ $property->title }} à {{ $property->city }}
                            </a>
                        </h3>

                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300 mb-3">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                            {{ $property->city }}, {{ $property->address }}
                            
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
                            @if ($property->area)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-ruler-combined text-orange-500 mr-2"></i>
                                    {{ $property->area }} m²
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

        <!-- Infinite scroll trigger -->
        @if ($hasMorePages)
            <div x-data="{
                init() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !@this.isLoading) {
                                @this.call('loadMore');
                            }
                        });
                    }, {
                        root: null,
                        threshold: 0.1,
                        rootMargin: '100px'
                    });
                    observer.observe(this.$el);
                }
            }" class="h-20 mt-8 flex items-center justify-center">
                @if ($isLoading)
                    <div class="text-center">
                        <div
                            class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-orange-500">
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Chargement...</p>
                    </div>
                @else
                    <div wire:loading.flex wire:target="loadMore" class="justify-center my-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
