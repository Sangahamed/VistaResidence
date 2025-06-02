<div class="max-w-6xl mx-auto p-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Gestion des médias - {{ $property->title }}
        </h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">
            Gérez les images, vidéos et visites virtuelles de votre propriété
        </p>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button wire:click="switchTab('images')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'images' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-images mr-2"></i>Images ({{ count($images) }})
            </button>
            <button wire:click="switchTab('videos')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'videos' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-video mr-2"></i>Vidéos ({{ count($videos) }})
            </button>
            <button wire:click="switchTab('panoramic')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'panoramic' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-panorama mr-2"></i>Images 360° ({{ count($panoramicImages) }})
            </button>
            <button wire:click="switchTab('virtual-tour')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'virtual-tour' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fas fa-vr-cardboard mr-2"></i>Visite virtuelle
                @if($hasVirtualTour)
                    <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Activée
                    </span>
                @endif
            </button>
        </nav>
    </div>

    <!-- Images Tab -->
    @if($activeTab === 'images')
        <div class="space-y-6">
            <!-- Upload Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Ajouter des images
                </h3>
                
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <div class="flex text-sm text-gray-600 dark:text-gray-300">
                            <label for="images-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Télécharger des images</span>
                                <input id="images-upload" wire:model="newImages" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            PNG, JPG, GIF jusqu'à 5MB chacune
                        </p>
                    </div>
                </div>

                @if($uploadInProgress)
                    <div class="mt-4 flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-sm text-gray-600">Upload en cours...</span>
                    </div>
                @endif
            </div>

            <!-- Images Grid -->
            @if(!empty($images))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Images existantes
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($images as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image['path']) }}" 
                                     alt="Image {{ $index + 1 }}" 
                                     class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button wire:click="removeMedia('image', {{ $index }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Videos Tab -->
    @if($activeTab === 'videos')
        <div class="space-y-6">
            <!-- Upload Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Ajouter des vidéos
                </h3>
                
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-video text-4xl text-gray-400 mb-4"></i>
                        <div class="flex text-sm text-gray-600 dark:text-gray-300">
                            <label for="videos-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Télécharger des vidéos</span>
                                <input id="videos-upload" wire:model="newVideos" type="file" class="sr-only" multiple accept="video/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            MP4, MOV, AVI jusqu'à 50MB chacune
                        </p>
                    </div>
                </div>
            </div>

            <!-- Videos Grid -->
            @if(!empty($videos))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Vidéos existantes
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($videos as $index => $video)
                            <div class="relative group">
                                <video class="w-full h-48 object-cover rounded-lg" controls>
                                    <source src="{{ Storage::url($video['path']) }}" type="{{ $video['mime_type'] }}">
                                    Votre navigateur ne supporte pas la lecture vidéo.
                                </video>
                                <div class="absolute top-2 right-2">
                                    <button wire:click="removeMedia('video', {{ $index }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Panoramic Tab -->
    @if($activeTab === 'panoramic')
        <div class="space-y-6">
            <!-- Info Section -->
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Images panoramiques 360°
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>Utilisez des applications comme Google Street View, Panorama 360, ou RICOH THETA pour créer des images 360°.</p>
                            <a href="https://support.google.com/maps/answer/7011737" target="_blank" class="underline">
                                Guide Google Street View
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Ajouter des images 360°
                </h3>
                
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-panorama text-4xl text-gray-400 mb-4"></i>
                        <div class="flex text-sm text-gray-600 dark:text-gray-300">
                            <label for="panoramic-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Télécharger des images 360°</span>
                                <input id="panoramic-upload" wire:model="newPanoramicImages" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            PNG, JPG jusqu'à 10MB chacune
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panoramic Images Grid -->
            @if(!empty($panoramicImages))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Images panoramiques existantes
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($panoramicImages as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image['path']) }}" 
                                     alt="Image panoramique {{ $index + 1 }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button wire:click="removeMedia('panoramic', {{ $index }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                                <div class="absolute bottom-2 left-2">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                        Pièce {{ $index + 1 }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Virtual Tour Tab -->
    @if($activeTab === 'virtual-tour')
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    État actuel de la visite virtuelle
                </h3>
                
                @if($hasVirtualTour)
                    <div class="flex items-center p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="text-green-800 dark:text-green-200 font-medium">
                                Visite virtuelle active ({{ ucfirst($virtualTourType) }})
                            </p>
                            <div class="mt-2 flex space-x-4">
                                @if($property->slug)
                                    <a href="{{ route('properties.virtual-tour', $property->slug) }}" 
                                       target="_blank"
                                       class="text-sm text-green-600 dark:text-green-400 hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>Voir la visite
                                    </a>
                                @endif
                                <button wire:click="deleteVirtualTour" 
                                        class="text-sm text-red-600 hover:underline"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer la visite virtuelle ?')">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <i class="fas fa-info-circle text-gray-500 mr-3"></i>
                        <p class="text-gray-700 dark:text-gray-300">
                            Aucune visite virtuelle configurée
                        </p>
                    </div>
                @endif
            </div>

            <!-- Virtual Tour Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Tour -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center mb-4">
                        <i class="fas fa-images text-4xl text-blue-500 mb-2"></i>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Visite basique</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Gratuit - Utilise vos images existantes</p>
                    </div>
                    
                    @if(count($images) > 0)
                        <button wire:click="createBasicVirtualTour" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                            Créer une visite basique
                        </button>
                    @else
                        <p class="text-sm text-gray-500 text-center">
                            Ajoutez d'abord des images dans l'onglet "Images"
                        </p>
                    @endif
                </div>

                <!-- Panoramic Tour -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center mb-4">
                        <i class="fas fa-panorama text-4xl text-green-500 mb-2"></i>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Visite panoramique</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Gratuit - Images 360°</p>
                    </div>
                    
                    @if(count($panoramicImages) > 0)
                        <button wire:click="createPanoramicVirtualTour" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                            Créer une visite panoramique
                        </button>
                    @else
                        <p class="text-sm text-gray-500 text-center">
                            Ajoutez d'abord des images 360° dans l'onglet "Images 360°"
                        </p>
                    @endif
                </div>

                <!-- 3D Tour -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-center mb-4">
                        <i class="fas fa-cube text-4xl text-purple-500 mb-2"></i>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Visite 3D</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Lien externe (Matterport, etc.)</p>
                    </div>
                    
                    <div class="space-y-3">
                        <input type="url" 
                               wire:model="virtualTourUrl" 
                               placeholder="https://my.matterport.com/show/..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                        <button wire:click="create3DVirtualTour" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-lg">
                            Créer une visite 3D
                        </button>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6">
                <h4 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">
                    <i class="fas fa-question-circle mr-2"></i>Besoin d'aide ?
                </h4>
                <p class="text-blue-800 dark:text-blue-200 mb-4">
                    Consultez notre guide complet pour créer des visites virtuelles adaptées à votre budget.
                </p>
                <div class="space-y-2">
                    <a href="https://support.google.com/maps/answer/7011737" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg mr-2">
                        <i class="fas fa-external-link-alt mr-2"></i>Guide Google Street View
                    </a>
                    <a href="https://www.matterport.com/how-it-works" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                        <i class="fas fa-external-link-alt mr-2"></i>Guide Matterport 3D
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
