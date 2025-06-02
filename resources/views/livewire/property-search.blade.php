<div
    class="p-6 bg-white dark:bg-slate-900 rounded-2xl shadow-xl dark:shadow-gray-700 transition-all duration-300 hover:shadow-2xl">
    <form wire:submit.prevent="submitSearch" class="animate-fade-in delay-200">
        <div class="text-dark text-start">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
                <!-- Champ de recherche -->
                <div>
                    <label id="search" class="form-label font-medium text-slate-900 dark:text-white">
                        Rechercher
                    </label>
                    <div class="relative mt-2">
                        <i class="uil uil-search icons absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" wire:model.defer="search"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                            placeholder="Rechercher des mots-clés">
                    </div>
                </div>

                <!-- Sélection de catégorie -->
                <div>
                    <label id="type" class="form-label font-medium text-slate-900 dark:text-white">
                        Catégorie
                    </label>
                    <div class="relative mt-2">
                        <i class="uil uil-estate icons absolute left-3 top-3 text-gray-400"></i>
                        <select wire:model.defer="type"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 appearance-none">
                            <option value="">Toutes les catégories</option>
                            @foreach ($propertyTypes as $propertyType)
                                <option value="{{ $propertyType }}">{{ ucfirst($propertyType) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sélection de ville -->
                <div>
                    <label id="city" class="form-label font-medium text-slate-900 dark:text-white">
                        Ville
                    </label>
                    <div class="relative mt-2">
                        <i class="uil uil-location-point icons absolute left-3 top-3 text-gray-400"></i>
                        <select wire:model.defer="city"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 dark:bg-slate-800 dark:border-gray-700 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 appearance-none">
                            <option value="">Toutes les villes</option>
                            @foreach ($propertyCities as $propertyCity)
                                <option value="{{ $propertyCity }}">{{ ucfirst($propertyCity) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sélection du prix avec valeurs dynamiques -->
                <div>
                    <label class="form-label font-medium text-slate-900 dark:text-white">
                        Prix ({{ number_format($minPrice, 0, ',', ' ') }} - {{ number_format($maxPrice, 0, ',', ' ') }}
                        FCFA)
                    </label>
                    <div class="relative mt-4 space-y-2">
                        <input type="range" id="price-min" wire:model.defer="priceMin" min="{{ $minPrice }}"
                            max="{{ $maxPrice }}" step="10000"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-slider">
                        <input type="range" id="price-max" wire:model.defer="priceMax" min="{{ $minPrice }}"
                            max="{{ $maxPrice }}" step="10000"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer range-slider">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-3">
                            <span>Min : <span class="font-medium"
                                    id="minPriceValue">{{ number_format($priceMin, 0, ',', ' ') }}</span> FCFA</span>
                            <span>Max : <span class="font-medium"
                                    id="maxPriceValue">{{ number_format($priceMax, 0, ',', ' ') }}</span> FCFA</span>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="lg:mt-6 mt-4 flex gap-2">
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex-1 py-3 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold transition-all duration-300 transform hover:-translate-y-1 shadow-md hover:shadow-lg flex items-center justify-center">
                        <span wire:loading.remove>
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin mr-2"></i> Recherche en cours...
                        </span>
                    </button>

                    <button type="button" wire:click="clearFilters" wire:loading.attr="disabled"
                        class="px-4 py-3 rounded-lg bg-gray-500 hover:bg-gray-600 text-white font-semibold transition-all duration-300">
                        <i class="fas fa-times"></i>
                    </button>


                </div>
            </div>
        </div>

        @auth
            <div class="mt-4 flex items-center">
                <input type="checkbox" wire:model.defer="saveSearch" class="mr-2">
                <label>Sauvegarder cette recherche</label>
            </div>
        @endauth
    </form>

    @if (session()->has('message'))
        <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
</div>
