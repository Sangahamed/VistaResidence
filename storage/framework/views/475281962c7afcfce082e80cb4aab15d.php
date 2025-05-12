

<?php $__env->startSection('title', 'Créer une Propriete'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid relative px-3 mx-auto animate-fade-in">
        <div class="layout-specing">
            <!-- Start Content -->
            <div class="md:flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-home text-orange-500 mr-3"></i>
                    <span>Ajouter une propriété</span>
                  </h1>

                <ul class="tracking-[0.5px] inline-block sm:mt-0 mt-3">
                    <li
                        class="inline-block capitalize text-[16px] font-medium duration-500 dark:text-white/70 hover:text-green-600 dark:hover:text-white">
                        <a href="index.html">Viscaresidence</a>/
                    </li>
                    <li class="inline-block text-base text-slate-950 dark:text-white/70 mx-0.5 ltr:rotate-0 rtl:rotate-180">
                        <i class="mdi mdi-chevron-right"></i>
                    </li>
                    <li class="inline-block capitalize text-[16px] font-medium text-orange-600 dark:text-white"
                        aria-current="page">Ajouter propriete</li>
                </ul>
            </div>
            <form class="space-y-8 divide-y divide-gray-200" action="<?php echo e(route('properties.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="container relative">

                    <div class="grid md:grid-cols-2 grid-cols-1 gap-6 mt-6">
                        
                        <div class="rounded-md shadow dark:shadow-gray-700 p-6 bg-slate-200 dark:bg-slate-900 max-h-fit">
                            <div class="animate-fade-in">
                                <h2 class="text-xl font-medium text-gray-900 flex items-center">
                                  <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                  Informations de base
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">Renseignez les détails principaux de votre propriété.</p>
                              </div>
                            <div class="space-y-4">
                                <!-- titre -->
                                <div>
                                    <label for="title" class="block text-lg font-medium text-gray-700">Nom <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="title" name="title" placeholder="Nom de la propriété "
                                        value="<?php echo e(old('title')); ?>" required
                                        class="mt-2 block w-full p-2 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />

                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>


                                </div>
                                <!-- type  -->
                                <div>
                                    <label for="type" class="block text-lg font-medium text-gray-700">Type de bien <span
                                            class="text-red-500">*</span></label>
                                    <select id="type" name="type"
                                        class="w-full p-2 mt-2 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Sélectionner un type</option>
                                        <option value="apartment" <?php echo e(old('type') == 'apartment' ? 'selected' : ''); ?>>
                                            Appartement</option>
                                        <option value="house" <?php echo e(old('type') == 'house' ? 'selected' : ''); ?>>Maison
                                        </option>
                                        <option value="villa" <?php echo e(old('type') == 'villa' ? 'selected' : ''); ?>>Villa</option>
                                        <option value="land" <?php echo e(old('type') == 'land' ? 'selected' : ''); ?>>Terrain
                                        </option>
                                        <option value="commercial" <?php echo e(old('type') == 'commercial' ? 'selected' : ''); ?>>Local
                                            commercial</option>
                                        <option value="office" <?php echo e(old('type') == 'office' ? 'selected' : ''); ?>>Bureau
                                        </option>
                                    </select>

                                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Statut -->
                                <div class="card meta-boxes">
                                    <div>
                                        <label for="status" class="block text-lg font-medium text-gray-700">Statut
                                            <span class="text-red-500">*</span></label>
                                        <select id="status" name="status" required
                                            class="w-full p-2 mt-2 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="">Sélectionner un statut</option>
                                            <option value="for_sale" <?php echo e(old('status') == 'for_sale' ? 'selected' : ''); ?>>À
                                                vendre</option>
                                            <option value="for_rent" <?php echo e(old('status') == 'for_rent' ? 'selected' : ''); ?>>À
                                                louer</option>
                                            <option value="sold" <?php echo e(old('status') == 'sold' ? 'selected' : ''); ?>>Vendu
                                            </option>
                                            <option value="rented" <?php echo e(old('status') == 'rented' ? 'selected' : ''); ?>>Loué
                                            </option>
                                        </select>
                                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>

                                    <label for="description" class="block text-lg font-medium text-gray-700">Description
                                        <span class="text-red-500">*</span></label>
                                    <textarea type="text" id="description" name="description" placeholder="veuillez entrez une description" required
                                        class="w-full p-2 mt-2 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>

                                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-red-500 text-sm mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- situation geographique -->

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Adresse -->
                                    <div class="col-span-2">
                                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Adresse complète</label>
                                        <div class="flex">
                                            <input type="text" id="address" name="address" required
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                placeholder="Ex: Yopougon Figayo">
                                            <button id="get-address" type="button"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 rounded-r-lg">
                                                <i class="fas fa-map-marker-alt mr-2"></i>Localiser
                                            </button>
                                        </div>
                                    </div>
                                
                                    <!-- Ville -->
                                    <div>
                                        <label for="city" class="block mb-2 text-sm font-medium text-gray-900">Ville</label>
                                        <input type="text" id="city" name="city" required
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                
                                    <!-- Code postal -->
                                    <div>
                                        <label for="postal_code" class="block mb-2 text-sm font-medium text-gray-900">Code postal</label>
                                        <input type="text" id="postal_code" name="postal_code"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                
                                    <!-- Pays -->
                                    <div>
                                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Pays</label>
                                        <input type="text" id="country" name="country" required
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                
                                    <!-- Coordonnées -->
                                    <div>
                                        <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900">Latitude</label>
                                        <input type="text" id="latitude" name="latitude" readonly
                                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                    </div>
                                    <div>
                                        <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900">Longitude</label>
                                        <input type="text" id="longitude" name="longitude" readonly
                                            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                    </div>
                                
                                    <!-- Carte -->
                                    <div class="col-span-2 h-64 rounded-lg overflow-hidden border border-gray-300">
                                        <div id="map" class="h-full w-full"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div
                            class="rounded-md shadow dark:shadow-gray-700 p-6 bg-slate-200 dark:bg-slate-900 max-h-fit w-fit">
                            <div>
                                <h2 class="text-xl font-medium text-gray-900 flex items-center">
                                  <i class="fas fa-ruler-combined text-purple-500 mr-2"></i>
                                  Caractéristiques
                                </h2>
                                <p class="mt-1 text-sm text-gray-500">Détaillez les spécificités de votre propriété.</p>
                              </div>
                            <div class="space-y-4">


                                <!-- detail propriete -->

                                <h5 class="text-lg font-semibold">Détails de la propriété <span
                                        class="text-red-500">*</span>
                                </h5>

                                <!-- Modification de la structure de la grille pour améliorer la lisibilité -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <!-- Nombre de pièces -->
                                    <div>
                                        <label for="number_pieces" class="block text-sm font-medium text-gray-700">Pièces
                                            <span class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Nombre de pièces" name="" type="number"
                                            min="0" id="number_pieces">
                                    </div>

                                    <!-- Nombre de chambres -->
                                    <div>
                                        <label for="bedrooms"
                                            class="block text-sm font-medium text-gray-700 ">Chambres
                                            <span class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Nombre de chambres" name="bedrooms" type="number"
                                            min="0" id="bedrooms">
                                    </div>
                                    <!-- Nombre de salles de bains -->
                                    <div>
                                        <label for="bathrooms"
                                            class="block text-sm font-medium text-gray-700 whitespace-nowrap">Salles
                                            de bains<span class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Nombre de salles de bains" name="bathrooms" type="number"
                                            min="0" id="bathrooms">
                                    </div>
                                    <!-- Nombre d'étages -->
                                    <div>
                                        <label for="number_floor" class="block text-sm font-medium text-gray-700">Étages
                                            <span class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Nombre d’étages" name="" type="number"
                                            min="0" id="number_floor" va>
                                    </div>
                                    <!-- Superficie en m² -->
                                    <div>
                                        <label for="square" class="block text-sm font-medium text-gray-700"
                                            id="square-label" style="display: none;">Superficie (m²) <span
                                                class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Superficie (m²)" name="square" type="number" min="0"
                                            id="square" style="display: none;">
                                    </div>

                                    <div>
                                        <label for="year_built" class="block text-sm font-medium text-gray-700"
                                            id="year_built">Année de construction<span
                                                class="text-red-500">*</span></label>
                                        <input
                                            class="mt-2 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="2020" name="year_built" type="number"
                                            value="<?php echo e(old('year_built')); ?>" min="1800" max="<?php echo e(date('Y')); ?>"
                                            id="year_built">
                                    </div>

                                </div>


                                <!-- prix -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="price" class="block text-lg font-medium text-gray-700">Prix
                                            <span class="text-red-500">*</span></label>
                                        <input
                                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            id="price" placeholder="Prix" name="price" type="text"
                                            value="<?php echo e(old('price')); ?>">
                                    </div>
                                    <div>
                                        <label for="currency_id" class="block text-lg font-medium text-gray-700">Periode
                                            <span class="text-red-500">*</span></label>
                                        <select
                                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            id="currency_id" name="">
                                            <option value="1">Nuitee</option>
                                            <option value="2">Jours</option>
                                            <option value="3">Mois</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- fonctionnalite -->
                                <div class="card mb-3 mt-5">
                                    <div class="card-header">
                                        <label class="block text-lg font-medium text-gray-700">Équipements et
                                            caractéristiques<span class="text-red-500">*</span></label>
                                    </div>
                                    <div class="card-body mt-2">
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
                                        <div class="grid grid-cols-4 gap-4">


                                            <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="form-check" for="feature_<?php echo e($key); ?>">
                                                    <input type="checkbox" id="feature_<?php echo e($key); ?>"
                                                        name="features[]" class="form-check-input"
                                                        value="<?php echo e($key); ?>"
                                                        <?php echo e(in_array($key, $oldFeatures) ? 'checked' : ''); ?>>
                                                    <span class="form-check-label"><?php echo e($label); ?></span>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Entreprise -->
                                    <?php if(auth()->user()->companies->count() > 0): ?>
                                        <div class="card mb-4">
                                            <div class="card-header">
                                                <h5 class="mb-0">Entreprise</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label for="company_id" class="form-label">Associer à une
                                                        entreprise</label>
                                                    <select class="form-select <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        id="company_id" name="company_id">
                                                        <option value="">Aucune entreprise</option>
                                                        <?php $__currentLoopData = auth()->user()->companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($company->id); ?>"
                                                                <?php echo e(old('company_id') == $company->id ? 'selected' : ''); ?>>
                                                                <?php echo e($company->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <div class="grid grid-cols-2 gap-3 card meta-boxes mt-5">
                                    <button
                                        class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base flex items-center"
                                        type="submit" name="submitter" value="apply">
                                        <span class="icon-tabler-wrapper icon-left mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-device-floppy" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                                </path>
                                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                                <path d="M14 4l0 4l-6 0l0 -4"></path>
                                            </svg>
                                        </span>
                                        <span>Valider</span>
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/create.blade.php ENDPATH**/ ?>