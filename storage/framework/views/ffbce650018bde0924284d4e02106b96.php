

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-900">Vos Recommandations Immobilières</h1>
                <p class="text-gray-600 mt-2">Découvrez des propriétés adaptées à vos préférences</p>
            </div>
            <a href="<?php echo e(route('recommendations.preferences')); ?>"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                        clip-rule="evenodd" />
                </svg>
                Modifier mes préférences
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Recommendations -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personalized Recommendations -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Recommandations personnalisées</h2>
                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pour
                                vous</span>
                        </div>

                        <?php if($personalizedRecommendations->isNotEmpty()): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php $__currentLoopData = $personalizedRecommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('recommendations.partials.property-card', [
                                        'property' => $property,
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">Aucune recommandation</h3>
                                <p class="mt-1 text-gray-500">Modifiez vos préférences pour obtenir des suggestions
                                    personnalisées.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recently Viewed -->
                <?php if($recentlyViewed->count() > 0): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Consultés récemment</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php $__currentLoopData = $recentlyViewed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('properties.show', $property->id)); ?>" class="group">
                                        <div class="relative h-32 rounded-lg overflow-hidden">
                                            <?php if($property->featured_image): ?>
                                                <img src="<?php echo e(asset($property->featured_image)); ?>"
                                                    alt="<?php echo e($property->title); ?>"
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <?php else: ?>
                                                <div
                                                    class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                            <div
                                                class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-all duration-300">
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm font-medium text-gray-900 truncate"><?php echo e($property->title); ?>

                                        </p>
                                        <p class="text-xs text-gray-500">$<?php echo e(number_format($property->price)); ?></p>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-8">
                <!-- Trending Properties -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Tendances du marché</h2>
                        <?php if($trendingProperties->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $trendingProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('properties.show', $property->id)); ?>"
                                        class="group flex items-start gap-4">
                                        <div class="flex-shrink-0 relative h-16 w-16 rounded-lg overflow-hidden">
                                            <?php if($property->featured_image): ?>
                                                <img src="<?php echo e(asset($property->featured_image)); ?>"
                                                    alt="<?php echo e($property->title); ?>"
                                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <?php else: ?>
                                                <div
                                                    class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                <?php echo e($property->title); ?></h3>
                                            <div class="flex items-center mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 ml-1"><?php echo e(rand(4, 5)); ?>.0
                                                    (<?php echo e(rand(10, 50)); ?> avis)</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">$<?php echo e(number_format($property->price)); ?>

                                            </p>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-gray-500">Aucune tendance disponible</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- New Listings -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Nouvelles annonces</h2>
                        <?php if($newListings->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $newListings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('properties.show', $property->id)); ?>" class="group">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 relative">
                                                <div class="h-16 w-16 rounded-lg overflow-hidden">
                                                    <?php if($property->featured_image): ?>
                                                        <img src="<?php echo e(asset($property->featured_image)); ?>"
                                                            alt="<?php echo e($property->title); ?>"
                                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                    <?php else: ?>
                                                        <div
                                                            class="w-full h-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <span
                                                    class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">NEW</span>
                                            </div>
                                            <div>
                                                <h3
                                                    class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                    <?php echo e($property->title); ?></h3>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <?php echo e($property->created_at->diffForHumans()); ?></p>
                                                <p class="text-xs font-medium text-gray-900 mt-1">
                                                    $<?php echo e(number_format($property->price)); ?></p>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-gray-500">Aucune nouvelle annonce disponible</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Preferences Summary -->
                <div class="bg-indigo-50 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-indigo-800 mb-4">Vos Préférences</h2>
                        <?php if(auth()->check() && auth()->user()->preferences): ?>
                            <div class="space-y-3">
                                <div>
                                    <h3 class="text-sm font-medium text-indigo-700">Types de biens</h3>
                                    <p class="text-sm text-gray-600">
                                        <?php if(!empty(auth()->user()->preferences->preferred_property_types)): ?>
                                            <?php echo e(implode(', ', auth()->user()->preferences->preferred_property_types)); ?>

                                        <?php else: ?>
                                            Tous types
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-indigo-700">Budget</h3>
                                    <p class="text-sm text-gray-600">
                                        <?php if(auth()->user()->preferences->min_price && auth()->user()->preferences->max_price): ?>
                                            $<?php echo e(number_format(auth()->user()->preferences->min_price)); ?> -
                                            $<?php echo e(number_format(auth()->user()->preferences->max_price)); ?>

                                        <?php else: ?>
                                            Non spécifié
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-indigo-700">Superficie</h3>
                                    <p class="text-sm text-gray-600">
                                        <?php if(auth()->user()->preferences->min_surface): ?>
                                            Min. <?php echo e(number_format(auth()->user()->preferences->min_surface)); ?> m²
                                        <?php else: ?>
                                            Non spécifié
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-medium text-indigo-700">Équipements</h3>
                                    <p class="text-sm text-gray-600">
                                        <?php if(!empty(auth()->user()->preferences->features)): ?>
                                            <?php echo e(implode(', ', auth()->user()->preferences->features)); ?>

                                        
                                        <?php else: ?>
                                            Aucune préférence
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-600">Vous n'avez pas encore défini de préférences.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/recommendations/index.blade.php ENDPATH**/ ?>