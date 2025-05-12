<div x-data="{ activeTab: 'images' }" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Card principale -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 transition-all duration-300 hover:shadow-lg">
        <!-- En-tête -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-images text-blue-500 mr-3"></i>
                Gestion des Médias
            </h3>
            <p class="text-sm text-gray-500 mt-1">Propriété: {{ $property->title }}</p>
        </div>

        <!-- Contenu -->
        <div class="p-6">
            <!-- Onglets -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8">
                    <button @click="activeTab = 'images'; $wire.switchTab('images')" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'images', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'images' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <i class="fas fa-camera mr-2"></i>
                        Images ({{ count($images) }})
                    </button>
                    
                    <button @click="activeTab = 'videos'; $wire.switchTab('videos')" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'videos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'videos' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                        <i class="fas fa-video mr-2"></i>
                        Vidéos ({{ count($videos) }})
                    </button>
                </nav>
            </div>

            <!-- Onglet Images -->
            <div x-show="activeTab === 'images" x-transition>
                <!-- Galerie d'images existantes -->
                @if(!empty($images))
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 my-6">
                        @foreach($images as $index => $image)
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-all duration-300">
                                <img src="{{ Storage::url($image['path']) }}" 
                                    class="w-full h-40 object-cover"
                                    alt="Image de la propriété">
                                
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                    <button wire:click="removeMedia('image', {{ $index }})"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-transform duration-300 transform hover:scale-110">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded my-6 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucune image n'a été ajoutée à cette propriété.
                    </div>
                @endif

                <!-- Upload de nouvelles images -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ajouter des images
                    </label>
                    <div wire:ignore class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition duration-300">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Télécharger des fichiers</span>
                                    <input type="file" class="sr-only" wire:model="newImages" multiple accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG jusqu'à 2MB
                            </p>
                        </div>
                    </div>
                    @error('newImages.*') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Onglet Vidéos -->
            <div x-show="activeTab === 'videos'" x-transition>
                <!-- Liste des vidéos existantes -->
                @if(!empty($videos))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-6">
                        @foreach($videos as $index => $video)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition duration-300">
                                <div class="bg-gray-100 p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-video text-blue-500 mr-3"></i>
                                        <span class="text-sm font-medium text-gray-700 truncate">
                                            {{ $video['filename'] }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ Storage::url($video['path']) }}" target="_blank"
                                            class="text-blue-500 hover:text-blue-700 p-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button wire:click="removeMedia('video', {{ $index }})"
                                            class="text-red-500 hover:text-red-700 p-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded my-6 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucune vidéo n'a été ajoutée à cette propriété.
                    </div>
                @endif

                <!-- Upload de nouvelles vidéos -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ajouter des vidéos
                    </label>
                    <div wire:ignore class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition duration-300">
                        <div class="space-y-1 text-center">
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Télécharger des fichiers</span>
                                    <input type="file" class="sr-only" wire:model="newVideos" multiple accept="video/*">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">
                                MP4, MOV jusqu'à 20MB
                            </p>
                        </div>
                    </div>
                    @error('newVideos.*') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons de navigation -->
    <div class="flex justify-between mt-6">
        <a href="{{ route('properties.edit', $property) }}" 
            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à l'édition
        </a>
        
        <a href="{{ route('properties.show', $property) }}" 
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 transform hover:scale-105">
            <i class="fas fa-check-circle mr-2"></i>
            Terminer et voir la fiche
        </a>
    </div>

    <!-- Overlay de chargement -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center">
            <i class="fas fa-spinner fa-spin text-blue-500 mr-3 text-2xl"></i>
            <span class="text-lg font-medium">Traitement des médias...</span>
        </div>
    </div>
</div>