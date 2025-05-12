

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Vos préférences immobilières</h1>
                <p class="text-gray-600 mt-2">Personnalisez vos recommandations selon vos critères</p>
            </div>
            <a href="<?php echo e(route('recommendations.index')); ?>" 
               class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour aux recommandations
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <form action="<?php echo e(route('recommendations.update')); ?>" method="POST" class="p-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-8 divide-y divide-gray-200">
                    <!-- Section Type de bien -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Type de propriété</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php $__currentLoopData = ['apartment' => 'Appartement', 'house' => 'Maison', 'villa' => 'Villa', 'land' => 'Terrain', 'commercial' => 'Commercial']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center">
                                    <input id="type-<?php echo e($value); ?>" name="preferred_property_types[]" type="checkbox" 
                                           value="<?php echo e($value); ?>" 
                                           <?php echo e(in_array($value, old('preferred_property_types', $preferences->preferred_property_types ?? [])) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="type-<?php echo e($value); ?>" class="ml-3 block text-sm font-medium text-gray-700"><?php echo e($label); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Section Budget -->
                    <div class="pt-8 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Budget</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="min_price" class="block text-sm font-medium text-gray-700">Prix minimum (€)</label>
                                <input type="number" id="min_price" name="min_price" 
                                       value="<?php echo e(old('min_price', $preferences->min_price)); ?>"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="max_price" class="block text-sm font-medium text-gray-700">Prix maximum (€)</label>
                                <input type="number" id="max_price" name="max_price" 
                                       value="<?php echo e(old('max_price', $preferences->max_price)); ?>"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Caractéristiques -->
                    <div class="pt-8 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Caractéristiques</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="min_bedrooms" class="block text-sm font-medium text-gray-700">Nombre minimum de chambres</label>
                                <select id="min_bedrooms" name="min_bedrooms" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Peu importe</option>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(old('min_bedrooms', $preferences->min_bedrooms) == $i ? 'selected' : ''); ?>><?php echo e($i); ?>+</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label for="min_bathrooms" class="block text-sm font-medium text-gray-700">Nombre minimum de salles de bain</label>
                                <select id="min_bathrooms" name="min_bathrooms" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Peu importe</option>
                                    <?php for($i = 1; $i <= 3; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(old('min_bathrooms', $preferences->min_bathrooms) == $i ? 'selected' : ''); ?>><?php echo e($i); ?>+</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label for="min_surface" class="block text-sm font-medium text-gray-700">Surface minimum (m²)</label>
                                <input type="number" id="min_surface" name="min_surface" 
                                       value="<?php echo e(old('min_surface', $preferences->min_surface)); ?>"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Section Équipements -->
                    <div class="pt-8 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Équipements souhaités</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <?php
                                            $features = [
                                                'garage' => 'Garage',
                                                'parking' => 'Parking',
                                                'garden' => 'Jardin',
                                                'terrace' => 'Terrasse',
                                                'Wifi' => 'Wi-Fi',
                                                'balcony' => 'Balcon',
                                                'pool' => 'Piscine',
                                                'elevator' => 'Ascenseur',
                                                'air_conditioning' => 'Climatisation',
                                                'heating' => 'Chauffage',
                                                'security_system' => 'Système de sécurité',
                                                'storage' => 'Espace de stockage',
                                                'Salle de sport' => 'Salle de sport',
                                                'Salle de jeux' => 'Salle de jeux',
                                                'Salle de réunion' => 'Salle de réunion',
                                                'Salle de conférence' => 'Salle de conférence',
                                                'Restaurant' => 'Restaurant',
                                                'furnished' => 'Meublé',
                                            ];
                                            $oldFeatures = old('features', []);
                                        ?>
                            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center">
                                    <input id="feature-<?php echo e($key); ?>" name="features[]" type="checkbox" 
                                           value="<?php echo e($key); ?>"
                                           <?php echo e(in_array($key, $oldFeatures) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="feature-<?php echo e($key); ?>" class="ml-3 block text-sm font-medium text-gray-700">
                                        <div class="flex items-center">
                                            
                                            <?php echo e($feature); ?>

                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Section Localisation -->
                    <div class="pt-8 space-y-6">
                        <h3 class="text-lg font-medium text-gray-900">Localisation préférée</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="location-input" class="block text-sm font-medium text-gray-700">Villes ou quartiers</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" id="location-input" 
                                           class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2 px-3 border"
                                           placeholder="Ajouter une ville ou un quartier">
                                    <button type="button" id="add-location" 
                                            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                        Ajouter
                                    </button>
                                </div>
                                <div id="locations-container" class="mt-2 space-y-2">
                                    <?php $__currentLoopData = old('preferred_locations', $preferences->preferred_locations ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($location): ?>
                                            <div class="flex items-center bg-gray-50 rounded-md p-2 location-tag">
                                                <input type="hidden" name="preferred_locations[]" value="<?php echo e($location); ?>">
                                                <span class="flex-1 text-sm"><?php echo e($location); ?></span>
                                                <button type="button" class="text-red-500 hover:text-red-700 remove-location">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Enregistrer les préférences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des localisations
        const locationInput = document.getElementById('location-input');
        const addLocationBtn = document.getElementById('add-location');
        const locationsContainer = document.getElementById('locations-container');

        function addLocation(value) {
            if (!value.trim()) return;
            
            const div = document.createElement('div');
            div.className = 'flex items-center bg-gray-50 rounded-md p-2 location-tag';
            div.innerHTML = `
                <input type="hidden" name="preferred_locations[]" value="${value.trim()}">
                <span class="flex-1 text-sm">${value.trim()}</span>
                <button type="button" class="text-red-500 hover:text-red-700 remove-location">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;
            locationsContainer.appendChild(div);
            locationInput.value = '';
        }

        addLocationBtn.addEventListener('click', () => addLocation(locationInput.value));
        locationInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addLocation(locationInput.value);
            }
        });

        // Délégation d'événement pour la suppression
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-location') || 
                e.target.closest('.remove-location')) {
                e.target.closest('.location-tag').remove();
            }
        });

        // Validation des prix
        const minPrice = document.getElementById('min_price');
        const maxPrice = document.getElementById('max_price');

        [minPrice, maxPrice].forEach(input => {
            input.addEventListener('change', function() {
                if (minPrice.value && maxPrice.value && parseFloat(minPrice.value) > parseFloat(maxPrice.value)) {
                    alert('Le prix minimum ne peut pas être supérieur au prix maximum');
                    this.value = '';
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/recommendations/preferences.blade.php ENDPATH**/ ?>