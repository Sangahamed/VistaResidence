

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Modifier la propriété</h2>

        <form action="<?php echo e(route('properties.update', $property)); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Colonne de gauche - Informations de base -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white">Informations de base</h3>
                    </div>
                    
                    <!-- Titre -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="<?php echo e(old('title', $property->title)); ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Type de bien -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Type de bien <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Sélectionner un type</option>
                            <option value="apartment" <?php echo e(old('type', $property->type) == 'apartment' ? 'selected' : ''); ?>>Appartement</option>
                            <option value="house" <?php echo e(old('type', $property->type) == 'house' ? 'selected' : ''); ?>>Maison</option>
                            <option value="villa" <?php echo e(old('type', $property->type) == 'villa' ? 'selected' : ''); ?>>Villa</option>
                            <option value="land" <?php echo e(old('type', $property->type) == 'land' ? 'selected' : ''); ?>>Terrain</option>
                            <option value="commercial" <?php echo e(old('type', $property->type) == 'commercial' ? 'selected' : ''); ?>>Local commercial</option>
                            <option value="office" <?php echo e(old('type', $property->type) == 'office' ? 'selected' : ''); ?>>Bureau</option>
                        </select>
                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Statut -->
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Sélectionner un statut</option>
                            <option value="for_sale" <?php echo e(old('status', $property->status) == 'for_sale' ? 'selected' : ''); ?>>À vendre</option>
                            <option value="for_rent" <?php echo e(old('status', $property->status) == 'for_rent' ? 'selected' : ''); ?>>À louer</option>
                            <option value="sold" <?php echo e(old('status', $property->status) == 'sold' ? 'selected' : ''); ?>>Vendu</option>
                            <option value="rented" <?php echo e(old('status', $property->status) == 'rented' ? 'selected' : ''); ?>>Loué</option>
                        </select>
                        <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required><?php echo e(old('description', $property->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Localisation -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Localisation</h4>
                        
                        <!-- Adresse -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Adresse complète <span class="text-red-500">*</span>
                            </label>
                            <div class="flex mt-1">
                                <input type="text" id="address" name="address" value="<?php echo e(old('address', $property->address)); ?>"
                                    class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                                <button type="button" id="get-address"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-md">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Localiser
                                </button>
                            </div>
                        </div>

                        <!-- Ville, Code postal, Pays -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Ville <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" name="city" value="<?php echo e(old('city', $property->city)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Code postal
                                </label>
                                <input type="text" id="postal_code" name="postal_code" value="<?php echo e(old('postal_code', $property->postal_code)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Pays <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="country" name="country" value="<?php echo e(old('country', $property->country)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                        </div>

                        <!-- Coordonnées GPS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Latitude
                                </label>
                                <input type="text" id="latitude" name="latitude" value="<?php echo e(old('latitude', $property->latitude)); ?>" readonly
                                    class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Longitude
                                </label>
                                <input type="text" id="longitude" name="longitude" value="<?php echo e(old('longitude', $property->longitude)); ?>" readonly
                                    class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        <!-- Carte -->
                        <div class="h-64 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                            <div id="map" class="h-full w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Colonne de droite - Caractéristiques et médias -->
                <div class="space-y-6">
                    <!-- Caractéristiques -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-ruler-combined text-purple-500 mr-2"></i>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">Caractéristiques</h3>
                        </div>

                        <!-- Détails de la propriété -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="bedrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Chambres <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="bedrooms" name="bedrooms" min="0" value="<?php echo e(old('bedrooms', $property->bedrooms)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Salles de bain <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="bathrooms" name="bathrooms" min="0" value="<?php echo e(old('bathrooms', $property->bathrooms)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Superficie (m²) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="area" name="area" min="0" step="0.01" value="<?php echo e(old('area', $property->area)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="year_built" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Année de construction
                                </label>
                                <input type="number" id="year_built" name="year_built" min="1800" max="<?php echo e(date('Y')); ?>" 
                                    value="<?php echo e(old('year_built', $property->year_built)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prix <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo e(old('price', $property->price)); ?>"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    required>
                            </div>
                            <div>
                                <label for="price_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Période
                                </label>
                                <select id="price_period" name="price_period"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="night" <?php echo e(old('price_period', $property->price_period) == 'night' ? 'selected' : ''); ?>>Par nuit</option>
                                    <option value="week" <?php echo e(old('price_period', $property->price_period) == 'week' ? 'selected' : ''); ?>>Par semaine</option>
                                    <option value="month" <?php echo e(old('price_period', $property->price_period) == 'month' ? 'selected' : ''); ?>>Par mois</option>
                                    <option value="year" <?php echo e(old('price_period', $property->price_period) == 'year' ? 'selected' : ''); ?>>Par an</option>
                                </select>
                            </div>
                        </div>

                        <!-- Équipements -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Équipements et caractéristiques
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <?php
                                    $features = [
                                        'garage' => 'Garage',
                                        'parking' => 'Parking',
                                        'garden' => 'Jardin',
                                        'terrace' => 'Terrasse',
                                        'wifi' => 'Wi-Fi',
                                        'balcony' => 'Balcon',
                                        'pool' => 'Piscine',
                                        'elevator' => 'Ascenseur',
                                        'air_conditioning' => 'Climatisation',
                                        'heating' => 'Chauffage',
                                        'security_system' => 'Système de sécurité',
                                        'furnished' => 'Meublé',
                                    ];
                                    $currentFeatures = old('features', $property->features ?? []);
                                ?>
                                
                                <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="features[]" value="<?php echo e($key); ?>"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                            <?php echo e(in_array($key, (array)$currentFeatures) ? 'checked' : ''); ?>>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300"><?php echo e($label); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Médias existants -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-4">Médias existants</h3>
                        
                        <!-- Images -->
                        <?php if($property->images && count($property->images) > 0): ?>
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Images</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <?php $__currentLoopData = $property->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="relative group">
                                            <img src="<?php echo e(Storage::url($image['path'])); ?>" 
                                                 class="w-full h-32 object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg transition-opacity">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="delete_images[]" value="<?php echo e($index); ?>"
                                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                    <span class="ml-2 text-white">Supprimer</span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Vidéos -->
                        <?php if($property->videos && count($property->videos) > 0): ?>
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Vidéos</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <?php $__currentLoopData = $property->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="relative group">
                                            <video controls class="w-full rounded-lg">
                                                <source src="<?php echo e(Storage::url($video['path'])); ?>" type="video/mp4">
                                                Votre navigateur ne supporte pas la vidéo.
                                            </video>
                                            <div class="absolute top-2 right-2 bg-black bg-opacity-50 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" name="delete_videos[]" value="<?php echo e($index); ?>"
                                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring-red-500">
                                                    <span class="ml-1 text-white text-sm">Supprimer</span>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Nouveaux médias -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ajouter des images
                                </label>
                                <input type="file" name="new_images[]" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ajouter des vidéos
                                </label>
                                <input type="file" name="new_videos[]" multiple accept="video/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Entreprise -->
                    <?php if(auth()->user()->companies->count() > 0): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-4">Entreprise</h3>
                            <div>
                                <label for="company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Associer à une entreprise
                                </label>
                                <select id="company_id" name="company_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Aucune entreprise</option>
                                    <?php $__currentLoopData = auth()->user()->companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id', $property->company_id) == $company->id ? 'selected' : ''); ?>>
                                            <?php echo e($company->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Boutons de soumission -->
                    <div class="flex justify-end space-x-4">
                        <button type="submit" name="submitter" value="apply"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md shadow-sm">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                        <button type="submit" name="submitter" value="save"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm">
                            <i class="fas fa-check mr-2"></i> Valider
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Initialisation de la carte
            document.addEventListener('DOMContentLoaded', function() {
                <?php if($property->latitude && $property->longitude): ?>
                    const map = L.map('map').setView([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>], 15);
                <?php else: ?>
                    const map = L.map('map').setView([0, 0], 2);
                <?php endif; ?>

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                let marker;
                <?php if($property->latitude && $property->longitude): ?>
                    marker = L.marker([<?php echo e($property->latitude); ?>, <?php echo e($property->longitude); ?>]).addTo(map)
                        .bindPopup("<?php echo e($property->title); ?>");
                <?php endif; ?>

                // Géocodage de l'adresse
                document.getElementById('get-address').addEventListener('click', function() {
                    const address = document.getElementById('address').value;
                    if (!address) return;

                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lon = parseFloat(data[0].lon);
                                
                                document.getElementById('latitude').value = lat;
                                document.getElementById('longitude').value = lon;
                                
                                if (marker) {
                                    map.removeLayer(marker);
                                }
                                
                                marker = L.marker([lat, lon]).addTo(map)
                                    .bindPopup(address);
                                map.setView([lat, lon], 15);
                                
                                // Remplir automatiquement les champs de localisation si disponibles
                                const addressParts = data[0].display_name.split(', ');
                                if (addressParts.length > 0) {
                                    document.getElementById('city').value = addressParts[0] || '';
                                    document.getElementById('country').value = addressParts[addressParts.length - 1] || '';
                                }
                            }
                        });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <style>
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/edit.blade.php ENDPATH**/ ?>