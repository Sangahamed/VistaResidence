

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header with breadcrumb -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="<?php echo e(route('home')); ?>"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <a href="<?php echo e(route('visits.index')); ?>"
                                    class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Mes
                                    visites</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="mt-4 flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">Détails de la visite</h1>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium 
                    <?php if($visit->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                    <?php elseif($visit->status === 'confirmed'): ?> bg-green-100 text-green-800
                    <?php elseif($visit->status === 'completed'): ?> bg-blue-100 text-blue-800
                    <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                        <?php if($visit->status === 'pending'): ?>
                            En attente
                        <?php elseif($visit->status === 'confirmed'): ?>
                            Confirmée
                        <?php elseif($visit->status === 'completed'): ?>
                            Terminée
                        <?php else: ?>
                            Annulée
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Visit Details Card -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                        <div class="p-6 sm:p-8">
                            <!-- Property or Private Visit Info -->
                            <?php if($visit->is_private): ?>
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-900"><?php echo e($visit->title); ?></h2>
                                    <p class="text-gray-500 mt-1">Visite privée</p>
                                </div>
                            <?php else: ?>
                                <div class="flex items-start space-x-4 mb-6">
                                    <?php if($visit->property->images): ?>
                                        <img src="<?php echo e(Storage::url($visit->property->images[0]['path'])); ?>"
                                            alt="<?php echo e($visit->property->title); ?>" class="h-16 w-16 rounded-lg object-cover">
                                    <?php endif; ?>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900"><?php echo e($visit->property->title); ?></h2>
                                        <p class="text-gray-500 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <?php echo e($visit->property->address); ?>, <?php echo e($visit->property->city); ?>

                                        </p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full"><?php echo e($visit->property->type); ?></span>
                                            <span
                                                class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full"><?php echo e($visit->property->surface); ?>

                                                m²</span>
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full"><?php echo e($visit->property->bedrooms); ?>

                                                chambres</span>
                                            <span
                                                class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full"><?php echo e(number_format($visit->property->price, 0, ',', ' ')); ?>

                                                €</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Status Alert -->
                            <div class="mb-6">
                                <?php if($visit->isConfirmed()): ?>
                                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800">Visite confirmée</h3>
                                                <div class="mt-2 text-sm text-green-700">
                                                    <p>Votre visite a été confirmée. Veuillez vous présenter à l'adresse
                                                        indiquée à l'heure prévue.</p>
                                                    <?php if($visit->confirmation_code): ?>
                                                        <p class="mt-2 font-bold">Code de confirmation :
                                                            <?php echo e($visit->confirmation_code); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($visit->isPending()): ?>
                                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">En attente de confirmation
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>Votre demande de visite est en cours de traitement. L'agent vous
                                                        contactera pour confirmer.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($visit->isCompleted()): ?>
                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-blue-800">Visite terminée</h3>
                                                <div class="mt-2 text-sm text-blue-700">
                                                    <p>Cette visite a été effectuée. Nous espérons que la propriété vous a
                                                        plu.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($visit->isCancelled()): ?>
                                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">Visite annulée</h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <p><strong>Raison :</strong>
                                                        <?php echo e($visit->cancellation_reason ?? 'Non spécifiée'); ?></p>
                                                    <?php if($visit->cancelledBy): ?>
                                                        <p class="mt-1">Annulée par : <?php echo e($visit->cancelledBy->name); ?>

                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Visit Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date</h3>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <?php echo e($visit->visit_date->translatedFormat('l j F Y')); ?>

                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Horaire</h3>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <?php echo e($visit->visit_time_start); ?> - <?php echo e($visit->visit_time_end); ?>

                                    </p>
                                </div>
                                <?php if($visit->agent): ?>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Agent immobilier</h3>
                                        <p class="mt-1 text-sm text-gray-900">
                                            <?php echo e($visit->agent->name); ?>

                                        </p>
                                    </div>
                                <?php endif; ?>
                                <?php if($visit->notes): ?>
                                    <div class="md:col-span-2">
                                        <h3 class="text-sm font-medium text-gray-500">Notes</h3>
                                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line"><?php echo e($visit->notes); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Agent Card -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <?php if($visit->visitor_id === auth()->id()): ?>
                                    Agent immobilier
                                <?php else: ?>
                                    Client
                                <?php endif; ?>
                            </h3>
                        </div>
                        <div class="p-6">
                            <?php if($visit->visitor_id === auth()->id()): ?>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold">
                                            <?php echo e(substr($visit->agent->name, 0, 1)); ?>

                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900"><?php echo e($visit->agent->name); ?></h4>
                                        <p class="text-sm text-gray-500"><?php echo e($visit->agent->email); ?></p>
                                        <?php if($visit->agent->phone): ?>
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e($visit->agent->phone); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <a href="<?php echo e(route('messenger', ['recipient_id' => $visit->agent_id])); ?>"
                                        class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Message
                                    </a>
                                    <?php if($visit->agent->phone): ?>
                                        <a href="tel:<?php echo e($visit->agent->phone); ?>"
                                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Appeler
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($visit->agent): ?>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold">
                                            <?php echo e(substr($visit->visitor->name, 0, 1)); ?>

                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900"><?php echo e($visit->visitor->name); ?></h4>
                                        <p class="text-sm text-gray-500"><?php echo e($visit->visitor->email); ?></p>
                                        <?php if($visit->visitor->phone): ?>
                                            <p class="text-sm text-gray-500 mt-1"><?php echo e($visit->visitor->phone); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <a href="<?php echo e(route('messenger', ['recipient_id' => $visit->visitor_id])); ?>"
                                        class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Message
                                    </a>
                                    <?php if($visit->visitor->phone): ?>
                                        <a href="tel:<?php echo e($visit->visitor->phone); ?>"
                                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Appeler
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Aucun agent n'est assigné à cette visite.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Actions
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <?php if(!$visit->is_private && $visit->property): ?>
                                    <a href="<?php echo e(route('properties.show', $visit->property)); ?>"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Voir la propriété
                                    </a>
                                <?php endif; ?>

                                <?php if($visit->isPending() || $visit->isConfirmed()): ?>
                                    <a href="<?php echo e(route('visits.cancel', $visit)); ?>"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Annuler la visite
                                    </a>
                                <?php endif; ?>

                                <?php if($visit->agent): ?>
                                    <a href="<?php echo e(route('messenger', ['recipient_id' => $visit->agent_id])); ?>"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        Contacter l'agent
                                    </a>
                                <?php endif; ?>

                                <?php if(!$visit->is_private && $visit->property): ?>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($visit->property->address . ', ' . $visit->property->city . ' ' . $visit->property->postal_code)); ?>"
                                        target="_blank"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Voir sur Google Maps
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/visits/show.blade.php ENDPATH**/ ?>