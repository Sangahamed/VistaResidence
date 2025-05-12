

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8" x-data="propertyPage()">
        <!-- En-tête avec animation -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="space-y-2 transition-all duration-300 hover:translate-x-1">
                <h1 class="text-3xl font-bold text-gray-800"><?php echo e($property->title); ?></h1>
                <p class="text-gray-600 flex items-center">
                    <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>
                    <?php echo e($property->address); ?>, <?php echo e($property->city); ?>, <?php echo e($property->postal_code); ?>, <?php echo e($property->country); ?>

                </p>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('properties.edit', $property)); ?>" class="btn-primary group">
                    <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i> Modifier
                </a>
                <button @click="showDeleteModal = true" class="btn-danger group">
                    <i class="fas fa-trash mr-2 group-hover:shake transition-transform"></i> Supprimer
                </button>
            </div>
        </div>

        <!-- Message flash avec animation -->
        <?php if(session('success')): ?>
            <div class="alert-success mb-8 animate-fade-in">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Colonne principale -->
            <div class="w-full lg:w-2/3 space-y-6">
                <!-- Onglets Médias -->
                <?php if(!empty($property->images) && count($property->images) > 0): ?>
                    <div x-data="carousel(<?php echo e(count($property->images)); ?>)" class="relative rounded-xl overflow-hidden shadow-lg">
                        <!-- Conteneur des slides -->
                        <div class="relative w-full overflow-hidden" style="height: 500px;">
                            <?php $__currentLoopData = $property->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div x-show="currentSlide === <?php echo e($index); ?>"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 w-full h-full">
                                    <img src="<?php echo e(Storage::url($image['path'])); ?>" class="w-full h-full object-cover"
                                        alt="Image <?php echo e($index + 1); ?>">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white">
                                        <p class="text-sm">Image <?php echo e($index + 1); ?> sur <?php echo e(count($property->images)); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Contrôles du carrousel -->
                        <button @click="prev()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 text-white transition-all z-10">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button @click="next()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 text-white transition-all z-10">
                            <i class="fas fa-chevron-right"></i>
                        </button>

                        <!-- Indicateurs -->
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-10">
                            <?php $__currentLoopData = $property->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button @click="goTo(<?php echo e($index); ?>)"
                                    :class="{ 'bg-white': currentSlide === <?php echo e($index); ?>, 'bg-white/50': currentSlide !==
                                            <?php echo e($index); ?> }"
                                    class="w-3 h-3 rounded-full hover:bg-white transition-all"
                                    aria-label="Aller à l'image <?php echo e($index + 1); ?>"></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-gray-100 text-center py-16 rounded-xl">
                        <i class="fas fa-image text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Aucune image disponible</p>
                    </div>
                <?php endif; ?>

                <!-- Onglets Médias (si vidéos existent) -->
                <?php if(!empty($property->videos) && count($property->videos) > 0): ?>
                    <div x-data="{ activeTab: 'images' }" class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="border-b border-gray-200">
                            <nav class="flex -mb-px">
                                <button @click="activeTab = 'images'"
                                    :class="{ 'text-primary-600 border-primary-500': activeTab === 'images', 'text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'images' }"
                                    class="tab-button">
                                    <i class="fas fa-images mr-2"></i> Photos
                                    <span
                                        class="badge bg-primary-100 text-primary-800 ml-2"><?php echo e(count($property->images)); ?></span>
                                </button>
                                <button @click="activeTab = 'videos'"
                                    :class="{ 'text-primary-600 border-primary-500': activeTab === 'videos', 'text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'videos' }"
                                    class="tab-button">
                                    <i class="fas fa-video mr-2"></i> Vidéos
                                    <span
                                        class="badge bg-blue-100 text-blue-800 ml-2"><?php echo e(count($property->videos)); ?></span>
                                </button>
                            </nav>
                        </div>

                        <div class="p-4">
                            <!-- Onglet Images -->
                            <div x-show="activeTab === 'images'" x-transition>
                                <!-- Le carrousel sera visible ici quand l'onglet est actif -->
                            </div>

                            <!-- Onglet Vidéos -->
                            <div x-show="activeTab === 'videos'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $property->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                        class="relative rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                        <video controls class="w-full h-auto aspect-video bg-black">
                                            <source src="<?php echo e(Storage::url($video['path'])); ?>" type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture de vidéos.
                                        </video>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-black/30 transition-opacity duration-300">
                                            <i class="fas fa-play text-white text-4xl"></i>
                                        </div>
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3 text-white">
                                            <p class="text-sm">Vidéo <?php echo e($index + 1); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Carte d'actions avec animations -->
                <div class="card hover:scale-[1.01] transition-transform duration-300">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-800">Actions</h2>
                    </div>
                    <div class="card-body grid grid-cols-1 md:grid-cols-2 gap-3">
                        <a href="<?php echo e(route('properties.visits.create', $property)); ?>" class="action-btn-primary group">
                            <i class="fas fa-calendar-check mr-2 group-hover:animate-bounce"></i>Demander une visite
                        </a>
                        <?php if($property->has_virtual_tour): ?>
                            <a href="<?php echo e(route('virtual-tour', $property)); ?>" class="action-btn-secondary group">
                                <i class="fas fa-vr-cardboard mr-2 group-hover:rotate-12 transition-transform"></i>Visite
                                virtuelle
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('messenger', $property)); ?>" class="action-btn-secondary group">
                            <i class="fas fa-envelope mr-2 group-hover:animate-pulse"></i>Contacter l'agent
                        </a>
                        <form action="<?php echo e(route('properties.comparison.add', $property)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="action-btn-secondary group w-full">
                                <i class="fas fa-exchange-alt mr-2 group-hover:rotate-180 transition-transform"></i>Ajouter
                                à la comparaison
                            </button>
                        </form>
                        <?php if(auth()->guard()->check()): ?>
                            <button type="button" class="action-btn-danger group w-full md:col-span-2 toggle-favorite"
                                data-property-id="<?php echo e($property->id); ?>">
                                <i
                                    class="fas <?php echo e($property->isFavorited() ? 'fa-heart' : 'fa-heart-broken'); ?> mr-2 group-hover:scale-125 transition-transform"></i>
                                <?php echo e($property->isFavorited() ? 'Retirer des favoris' : 'Ajouter aux favoris'); ?>

                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description avec effet de dévoilement -->
                <div class="card group">
                    <div class="card-header cursor-pointer flex justify-between items-center"
                        onclick="toggleCollapse('descriptionCollapse')">
                        <h2 class="text-xl font-semibold text-gray-800">Description</h2>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-primary-500 transition-transform duration-300 transform"
                            id="descriptionIcon"></i>
                    </div>
                    <div class="card-body transition-all duration-300 ease-in-out overflow-hidden"
                        id="descriptionCollapse">
                        <p class="text-gray-700 leading-relaxed"><?php echo e($property->description); ?></p>
                    </div>
                </div>

                <!-- Caractéristiques avec grille interactive -->
                <div class="card group">
                    <div class="card-header cursor-pointer flex justify-between items-center"
                        onclick="toggleCollapse('featuresCollapse')">
                        <h2 class="text-xl font-semibold text-gray-800">Caractéristiques</h2>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-primary-500 transition-transform duration-300 transform"
                            id="featuresIcon"></i>
                    </div>
                    <div class="card-body transition-all duration-300 ease-in-out overflow-hidden" id="featuresCollapse">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-building text-primary-500 mr-2"></i>
                                    <strong>Type:</strong> <?php echo e(ucfirst($property->type)); ?>

                                </div>
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-tag text-primary-500 mr-2"></i>
                                    <strong>Statut:</strong>
                                    <?php echo e($property->status === 'for_sale' ? 'À vendre' : 'À louer'); ?>

                                </div>
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-bed text-primary-500 mr-2"></i>
                                    <strong>Chambres:</strong> <?php echo e($property->bedrooms ?? 'Non spécifié'); ?>

                                </div>
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-bath text-primary-500 mr-2"></i>
                                    <strong>Salles de bain:</strong> <?php echo e($property->bathrooms ?? 'Non spécifié'); ?>

                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-vector-square text-primary-500 mr-2"></i>
                                    <strong>Surface:</strong>
                                    <?php echo e($property->area ? $property->area . ' m²' : 'Non spécifié'); ?>

                                </div>
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-calendar-alt text-primary-500 mr-2"></i>
                                    <strong>Année de construction:</strong> <?php echo e($property->year_built ?? 'Non spécifié'); ?>

                                </div>
                                <div class="feature-item hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <i class="fas fa-star text-primary-500 mr-2"></i>
                                    <strong>Mise en avant:</strong> <?php echo e($property->is_featured ? 'Oui' : 'Non'); ?>

                                </div>
                            </div>
                        </div>

                        <?php if(!empty($property->features) && count($property->features) > 0): ?>
                            <hr class="my-4 border-gray-200">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Équipements et caractéristiques</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                <?php $__currentLoopData = $property->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                        class="bg-gray-50 hover:bg-primary-50 rounded-lg p-3 flex items-center transition-colors duration-200">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        <span class="text-gray-700"><?php echo e($feature); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Vidéos avec hover effect -->
                <?php if(!empty($property->videos) && count($property->videos) > 0): ?>
                    <div class="card group">
                        <div class="card-header cursor-pointer flex justify-between items-center"
                            onclick="toggleCollapse('videosCollapse')">
                            <h2 class="text-xl font-semibold text-gray-800">Vidéos</h2>
                            <i class="fas fa-chevron-down text-gray-500 group-hover:text-primary-500 transition-transform duration-300 transform"
                                id="videosIcon"></i>
                        </div>
                        <div class="card-body transition-all duration-300 ease-in-out overflow-hidden"
                            id="videosCollapse">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $property->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                        class="relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                        <video controls class="w-full h-auto aspect-video">
                                            <source src="<?php echo e(Storage::url($video['path'])); ?>" type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture de vidéos.
                                        </video>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-black bg-opacity-30 transition-opacity duration-300">
                                            <i class="fas fa-play text-white text-4xl"></i>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Localisation avec animation au chargement -->
                <div class="card group">
                    <div class="card-header cursor-pointer flex justify-between items-center"
                        onclick="toggleCollapse('mapCollapse')">
                        <h2 class="text-xl font-semibold text-gray-800">Localisation</h2>
                        <i class="fas fa-chevron-down text-gray-500 group-hover:text-primary-500 transition-transform duration-300 transform"
                            id="mapIcon"></i>
                    </div>
                    <div class="card-body transition-all duration-300 ease-in-out overflow-hidden" id="mapCollapse">
                        <?php if($property->latitude && $property->longitude): ?>
                            <div id="map" style="height: 400px;" class="rounded-xl shadow-md animate-fade-in">
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-4">
                                <i class="fas fa-map-marker-alt text-2xl mb-2"></i><br>
                                Aucune coordonnée GPS disponible pour cette propriété.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="w-full lg:w-1/3 space-y-6">
                <!-- Prix et statut avec effet de profondeur -->
                <div class="card hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-2xl font-bold text-primary-600 animate-pulse">
                                <?php echo e(number_format($property->price, 0, ',', ' ')); ?> €</h3>
                            <span
                                class="badge-<?php echo e($property->status === 'for_sale' ? 'danger' : 'primary'); ?> px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo e($property->status === 'for_sale' ? 'À vendre' : 'À louer'); ?>

                            </span>
                        </div>
                        <hr class="my-4 border-gray-200">
                        <button class="action-btn-secondary group w-full mb-3">
                            <i class="fas fa-share-alt mr-2 group-hover:rotate-45 transition-transform"></i> Partager
                        </button>

                        <!-- Estimation de prêt -->
                        <div class="bg-blue-50 p-4 rounded-lg mb-4 animate-fade-in">
                            <h4 class="font-medium text-blue-800 mb-2">Estimation de prêt</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Mensualité (20 ans)</span>
                                    <span class="font-medium"><?php echo e(number_format($property->price * 0.0045, 0, ',', ' ')); ?>

                                        €/mois</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">* Estimation indicative basée sur un taux de 2.5%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact avec animation -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-800">Contacter le propriétaire</h2>
                    </div>
                    <div class="card-body">
                        <?php if(auth()->guard()->guest()): ?>
                            <div class="text-center py-4 animate-bounce">
                                <i class="fas fa-lock text-3xl text-gray-400 mb-3"></i>
                                <p class="text-gray-600 mb-4">Connectez-vous pour contacter le propriétaire</p>
                                <a href="<?php echo e(route('login')); ?>" class="btn-primary w-full">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                                </a>
                            </div>
                        <?php else: ?>
                            <form action="<?php echo e(route('properties.startConversation', $property)); ?>" method="POST" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label for="message" class="form-label">Votre message</label>
                                    <textarea class="form-control" id="message" name="message" rows="4"
                                        placeholder="Bonjour, je suis intéressé(e) par cette propriété. Pourrions-nous organiser une visite ?"></textarea>
                                </div>
                                <button type="submit" class="btn-primary w-full group">
                                    <i class="fas fa-paper-plane mr-2 group-hover:animate-wiggle"></i>Envoyer un message
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informations du propriétaire avec hover effect -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header">
                        <h2 class="text-xl font-semibold text-gray-800">Propriétaire</h2>
                    </div>
                    <div class="card-body">
                        <div
                            class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                            <div class="flex-shrink-0">
                                <div
                                    class="bg-primary-500 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-medium text-gray-800"><?php echo e($property->owner->name); ?></h3>
                                <p class="text-sm text-gray-500">Propriétaire</p>
                            </div>
                        </div>
                        <ul class="space-y-2">
                            <li class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope text-gray-500 mr-3 w-5 text-center"></i>
                                <span><?php echo e($property->owner->email); ?></span>
                            </li>
                            <?php if($property->owner->phone): ?>
                                <li class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-phone text-gray-500 mr-3 w-5 text-center"></i>
                                    <span><?php echo e($property->owner->phone); ?></span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Gestion de la propriété (si autorisé) -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewStatistics', $property)): ?>
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-header">
                            <h2 class="text-xl font-semibold text-gray-800">Gestion de la propriété</h2>
                        </div>
                        <div class="card-body space-y-3">
                            <a href="<?php echo e(route('statistics', $property)); ?>" class="action-btn-secondary group">
                                <i class="fas fa-chart-bar mr-2 group-hover:animate-pulse"></i>Statistiques de visites
                            </a>
                            <a href="<?php echo e(route('properties.edit', $property)); ?>" class="action-btn-secondary group">
                                <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i>Modifier la
                                propriété
                            </a>
                            <?php if($property->has_virtual_tour): ?>
                                <a href="<?php echo e(route('virtual-tour.edit', $property)); ?>" class="action-btn-secondary group">
                                    <i class="fas fa-vr-cardboard mr-2 group-hover:animate-spin-slow"></i>Modifier la visite
                                    virtuelle
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('virtual-tour.edit', $property)); ?>" class="action-btn-secondary group">
                                    <i class="fas fa-plus mr-2 group-hover:scale-125 transition-transform"></i>Ajouter une
                                    visite virtuelle
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Agence (si applicable) -->
                <?php if($property->company): ?>
                    <div class="card hover:shadow-lg transition-shadow duration-300">
                        <div class="card-header">
                            <h2 class="text-xl font-semibold text-gray-800">Agence</h2>
                        </div>
                        <div class="card-body">
                            <div
                                class="flex items-center mb-4 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                                <div class="flex-shrink-0">
                                    <?php if($property->company->logo): ?>
                                        <img src="<?php echo e(Storage::url($property->company->logo)); ?>"
                                            alt="<?php echo e($property->company->name); ?>"
                                            class="rounded-full w-12 h-12 object-cover border-2 border-white shadow-md">
                                    <?php else: ?>
                                        <div
                                            class="bg-blue-500 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-md">
                                            <i class="fas fa-building text-xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium text-gray-800"><?php echo e($property->company->name); ?></h3>
                                    <p class="text-sm text-gray-500">Agence immobilière</p>
                                </div>
                            </div>
                            <ul class="space-y-2">
                                <li class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-envelope text-gray-500 mr-3 w-5 text-center"></i>
                                    <span><?php echo e($property->company->email); ?></span>
                                </li>
                                <?php if($property->company->phone): ?>
                                    <li class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-phone text-gray-500 mr-3 w-5 text-center"></i>
                                        <span><?php echo e($property->company->phone); ?></span>
                                    </li>
                                <?php endif; ?>
                                <?php if($property->company->website): ?>
                                    <li class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-globe text-gray-500 mr-3 w-5 text-center"></i>
                                        <a href="<?php echo e($property->company->website); ?>" target="_blank"
                                            class="text-blue-600 hover:underline">Site web</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
            <div @click.away="showDeleteModal = false"
                class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6">
                    <h3 class="text-xl font-bold">Confirmer la suppression</h3>
                </div>
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Êtes-vous sûr ?</h4>
                    <p class="text-gray-500 mb-6">
                        Cette action supprimera définitivement la propriété et toutes ses données associées.
                    </p>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-between">
                    <button @click="showDeleteModal = false" class="btn-secondary group">
                        <i class="fas fa-times mr-2 group-hover:rotate-90 transition-transform"></i> Annuler
                    </button>
                    <form action="<?php echo e(route('properties.destroy', $property)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-danger group">
                            <i class="fas fa-trash mr-2 group-hover:shake transition-transform"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <?php if($property->latitude && $property->longitude): ?>
        <?php $__env->startPush('scripts'); ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialiser la carte avec animation
                    const map = L.map('map', {
                        fadeAnimation: true,
                        zoomAnimation: true
                    }).setView([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>], 15);

                    // Ajouter la couche OpenStreetMap
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Créer une icône personnalisée avec animation
                    const propertyIcon = L.divIcon({
                        className: 'property-marker-icon animate-bounce',
                        html: '<i class="fas fa-home text-2xl text-white bg-primary-500 p-2 rounded-full shadow-lg"></i>',
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -40]
                    });

                    // Ajouter un marqueur avec animation
                    const marker = L.marker([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>], {
                        icon: propertyIcon
                    }).addTo(map);

                    // Ajouter un popup avec plus d'informations
                    marker.bindPopup(`
                        <div class="space-y-1">
                            <h4 class="font-bold text-primary-600"><?php echo e($property->title); ?></h4>
                            <p class="text-sm text-gray-600"><?php echo e($property->address); ?>, <?php echo e($property->city); ?></p>
                            <p class="text-sm font-medium"><?php echo e(number_format($property->price, 0, ',', ' ')); ?> €</p>
                        </div>
                    `).openPopup();

                    // Animation au survol
                    marker.on('mouseover', function() {
                        this.openPopup();
                    });
                });

                // Fonction pour basculer les sections
                function toggleCollapse(id) {
                    const element = document.getElementById(id);
                    const icon = document.getElementById(id + 'Icon');

                    if (element.classList.contains('max-h-0')) {
                        element.classList.remove('max-h-0');
                        element.classList.add('max-h-screen');
                        icon.classList.remove('rotate-0');
                        icon.classList.add('rotate-180');
                    } else {
                        element.classList.add('max-h-0');
                        element.classList.remove('max-h-screen');
                        icon.classList.add('rotate-0');
                        icon.classList.remove('rotate-180');
                    }
                }

                // Animation personnalisée pour le bouton favori
                document.querySelectorAll('.toggle-favorite').forEach(button => {
                    button.addEventListener('click', function() {
                        const icon = this.querySelector('i');
                        icon.classList.add('animate-ping');

                        setTimeout(() => {
                            icon.classList.remove('animate-ping');
                            if (icon.classList.contains('fa-heart')) {
                                icon.classList.remove('fa-heart');
                                icon.classList.add('fa-heart-broken');
                                this.innerHTML = this.innerHTML.replace('Retirer des favoris',
                                    'Ajouter aux favoris');
                            } else {
                                icon.classList.remove('fa-heart-broken');
                                icon.classList.add('fa-heart');
                                this.innerHTML = this.innerHTML.replace('Ajouter aux favoris',
                                    'Retirer des favoris');
                            }
                        }, 300);

                        // Ici, vous ajouteriez votre logique AJAX pour mettre à jour les favoris
                    });
                });

                // Animation de secousse personnalisée
                const shakeAnimation = [{
                        transform: 'translateX(0)'
                    },
                    {
                        transform: 'translateX(-5px)'
                    },
                    {
                        transform: 'translateX(5px)'
                    },
                    {
                        transform: 'translateX(-5px)'
                    },
                    {
                        transform: 'translateX(5px)'
                    },
                    {
                        transform: 'translateX(-5px)'
                    },
                    {
                        transform: 'translateX(0)'
                    }
                ];

                const shakeTiming = {
                    duration: 500,
                    iterations: 1
                };

                document.querySelectorAll('.shake').forEach(element => {
                    element.addEventListener('mouseenter', () => {
                        element.animate(shakeAnimation, shakeTiming);
                    });
                });
            </script>
        <?php $__env->stopPush(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        /* Animations personnalisées */
        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(-3deg);
            }

            50% {
                transform: rotate(3deg);
            }
        }

        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Styles des onglets */
        .tab-button {
            @apply py-4 px-6 flex items-center text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-300;
        }

        .tab-button.active {
            @apply text-primary-600 border-primary-500;
        }

        .tab-content {
            @apply transition-opacity duration-300;
        }

        .tab-content.active {
            @apply block opacity-100;
        }

        .tab-content.hidden {
            @apply hidden opacity-0;
        }

        /* Animation du carrousel */
        .carousel-item {
            @apply transition-transform duration-500 ease-in-out;
        }

        /* Badge */
        .badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        .animate-wiggle {
            animation: wiggle 0.5s ease-in-out infinite;
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Styles des composants */
        .card {
            @apply bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300;
        }

        .card-header {
            @apply px-6 py-4 border-b border-gray-100 bg-gray-50;
        }

        .card-body {
            @apply px-6 py-4;
        }

        .btn-primary {
            @apply bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
        }

        .btn-secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
        }

        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
        }

        .action-btn-primary {
            @apply bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow hover:shadow-md;
        }

        .action-btn-secondary {
            @apply bg-white border border-gray-300 hover:border-primary-500 text-gray-700 hover:text-primary-600 font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-md;
        }

        .action-btn-danger {
            @apply bg-white border border-red-300 hover:border-red-500 text-red-600 hover:text-white hover:bg-red-500 font-medium py-3 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-md;
        }

        .badge-primary {
            @apply bg-primary-100 text-primary-800;
        }

        .badge-danger {
            @apply bg-red-100 text-red-800;
        }

        .form-control {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300;
        }

        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }

        .alert-success {
            @apply bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm;
        }

        .feature-item {
            @apply transition-all duration-200;
        }

        /* Animation pour les éléments repliables */
        [id$="Collapse"] {
            @apply max-h-0 overflow-hidden transition-all duration-300 ease-in-out;
        }

        /* Styles pour les transitions */
            .alert-success {
                @apply bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm;
            }
            
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            /* Styles des boutons */
            .btn-primary {
                @apply bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
            }
            
            .btn-secondary {
                @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
            }
            
            .btn-danger {
                @apply bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg;
            }
            
            /* Styles des onglets */
            .tab-button {
                @apply py-4 px-6 flex items-center text-sm font-medium border-b-2 border-transparent transition-all duration-300;
            }
            
            /* Badge */
            .badge {
                @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
            }
            
            /* Animation de secousse */
            .shake:hover {
                animation: shake 0.5s ease-in-out;
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                20%, 60% { transform: translateX(-3px); }
                40%, 80% { transform: translateX(3px); }
            }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Fonctions JavaScript pour gérer la page
            function propertyPage() {
                return {
                    showDeleteModal: false,
                    // Autres propriétés/fonctions globales si nécessaire
                }
            }

            // Gestion du carrousel
            function carousel(totalSlides) {
                return {
                    currentSlide: 0,
                    totalSlides: totalSlides,
                    
                    next() {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    },
                    
                    prev() {
                        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    },
                    
                    goTo(index) {
                        this.currentSlide = index;
                    }
                }
            }
        // Initialiser toutes les sections repliables comme fermées par défaut
        document.addEventListener('DOMContentLoaded', function() {
            const collapsibles = ['description', 'features', 'videos', 'map'];
            collapsibles.forEach(id => {
                const element = document.getElementById(`${id}Collapse`);
                const icon = document.getElementById(`${id}Icon`);

                if (element && icon) {
                    element.classList.add('max-h-0');
                    element.classList.remove('max-h-screen');
                    icon.classList.add('rotate-0');
                    icon.classList.remove('rotate-180');
                }
            });

            // Activer le carrousel
            const carousel = new bootstrap.Carousel('#propertyCarousel', {
                interval: 5000,
                ride: 'carousel',
                wrap: true
            });

            // Fonction pour basculer entre les onglets
            window.switchTab = function(tabName) {
                // Désactiver tous les onglets
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                });

                // Activer l'onglet sélectionné
                document.getElementById(tabName + 'Tab').classList.add('active');
                document.getElementById(tabName + 'Content').classList.remove('hidden');
                document.getElementById(tabName + 'Content').classList.add('active');
            };

            // Initialiser la carte si nécessaire (identique à la version précédente)
            <?php if($property->latitude && $property->longitude): ?>
                const map = L.map('map', {
                    fadeAnimation: true,
                    zoomAnimation: true
                }).setView([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const propertyIcon = L.divIcon({
                    className: 'property-marker-icon animate-bounce',
                    html: '<i class="fas fa-home text-2xl text-white bg-primary-500 p-2 rounded-full shadow-lg"></i>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });

                const marker = L.marker([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>], {
                    icon: propertyIcon
                }).addTo(map);

                marker.bindPopup(`
                        <div class="space-y-1">
                            <h4 class="font-bold text-primary-600"><?php echo e($property->title); ?></h4>
                            <p class="text-sm text-gray-600"><?php echo e($property->address); ?>, <?php echo e($property->city); ?></p>
                            <p class="text-sm font-medium"><?php echo e(number_format($property->price, 0, ',', ' ')); ?> €</p>
                        </div>
                    `).openPopup();
            <?php endif; ?>
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/show.blade.php ENDPATH**/ ?>