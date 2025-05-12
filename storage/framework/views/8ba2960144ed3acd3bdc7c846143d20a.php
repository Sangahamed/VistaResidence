

<?php $__env->startSection('content'); ?>
    <main class="flex-1 p-6 animate-fade-in">
        <!-- Header with Breadcrumb and Buttons -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2 text-sm text-blue-500">
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:underline">DASHBOARD</a>
                <span>/</span>
                <span class="font-medium">Propriete</span>
            </div>

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <a href="<?php echo e(route('properties.create')); ?>"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all transform hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle Propriété
                </a>
                <button
                    class="border border-blue-500 text-blue-500 hover:bg-blue-50 px-4 py-2 rounded-md flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Importer
                </button>
                <button
                    class="border border-blue-500 text-blue-500 hover:bg-blue-50 px-4 py-2 rounded-md flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Exporter
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="mb-6 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded animate-slide-down">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 animate-slide-up">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Catégorie</label>
                <select
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Toutes les catégories</option>
                    <option>Appartement</option>
                    <option>Maison</option>
                    <option>Villa</option>
                    <option>Bureau</option>
                    <option>Local commercial</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Statut</label>
                <select
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Tous les statuts</option>
                    <option>À vendre</option>
                    <option>À louer</option>
                    <option>Vendu</option>
                    <option>Loué</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Localisation</label>
                <select
                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Toutes les localisations</option>
                    <option>Paris</option>
                    <option>Lyon</option>
                    <option>Marseille</option>
                    <option>Bordeaux</option>
                </select>
            </div>
        </div>

        <!-- Properties Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm animate-fade-in">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catégorie</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if(!empty($property->images) && count($property->images) > 0): ?>
                                        <img src="<?php echo e(Storage::url($property->images[0]['path'])); ?>" alt="Property"
                                            class="w-12 h-12 rounded-md object-cover shadow-sm">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-md bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900"><?php echo e($property->title); ?></div>
                                    <div class="text-sm text-gray-500">Ref:
                                        VMP-<?php echo e(str_pad($property->id, 4, '0', STR_PAD_LEFT)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e(ucfirst($property->type)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo e(number_format($property->price, 0, ',', ' ')); ?> XOF
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($property->status === 'for_sale'): ?>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            À vendre
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            À louer
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($property->city); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="<?php echo e(route('properties.show', $property)); ?>"
                                            class="text-gray-400 hover:text-blue-500 transition-colors" title="Voir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('properties.edit', $property)); ?>"
                                            class="text-gray-400 hover:text-yellow-500 transition-colors"
                                            title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="<?php echo e(route('properties.destroy', $property)); ?>" method="POST"
                                            class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="text-gray-400 hover:text-red-500 transition-colors"
                                                title="Supprimer"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette propriété ?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune propriété trouvée</h3>
                                        <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore ajouté de propriétés.
                                        </p>
                                        <div class="mt-6">
                                            <a href="<?php echo e(route('properties.create')); ?>"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Ajouter une propriété
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div
            class="flex flex-col md:flex-row items-center justify-between mt-6 px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-b-lg">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Affichage de
                        <span class="font-medium"><?php echo e($properties->firstItem()); ?></span>
                        à
                        <span class="font-medium"><?php echo e($properties->lastItem()); ?></span>
                        sur
                        <span class="font-medium"><?php echo e($properties->total()); ?></span>
                        résultats
                    </p>
                </div>
                <div>
                    <?php echo e($properties->links()); ?>

                </div>
            </div>
        </div>
    </main>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out forwards;
        }

        .animate-slide-down {
            animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Confirmation de suppression avec SweetAlert
                const deleteForms = document.querySelectorAll('form[action*="/properties/"]');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Confirmer la suppression',
                            text: "Êtes-vous sûr de vouloir supprimer cette propriété ? Cette action est irréversible.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oui, supprimer',
                            cancelButtonText: 'Annuler',
                            backdrop: `
                        rgba(0,0,0,0.7)
                        url("<?php echo e(asset('images/trash-icon-animated.gif')); ?>")
                        center top
                        no-repeat
                    `
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Soumettre le formulaire après confirmation
                                form.submit();

                                // Afficher un message de chargement
                                Swal.fire({
                                    title: 'Suppression en cours',
                                    html: 'Veuillez patienter...',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            }
                        });
                    });
                });

                // Alternative simple avec confirm() si SweetAlert n'est pas disponible
                function confirmDelete(event) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cette propriété ?')) {
                        event.preventDefault();
                    }
                }
            });
        </script>

        <style>
            /* Animation pour le bouton de suppression */
            .delete-btn {
                transition: all 0.3s ease;
            }

            .delete-btn:hover {
                transform: scale(1.1);
                color: #ef4444 !important;
            }

            .delete-btn:active {
                transform: scale(0.95);
            }
        </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/index.blade.php ENDPATH**/ ?>