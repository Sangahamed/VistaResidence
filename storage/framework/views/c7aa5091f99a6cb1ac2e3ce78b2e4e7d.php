

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto space-y-10">

        <!-- Titre -->
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Préférences de notification</h1>
            <p class="text-gray-600 mt-2">Personnalisez vos préférences pour recevoir les notifications qui comptent pour vous.</p>
        </div>

        <!-- Formulaire -->
        <div class="bg-white shadow-md rounded-lg p-8 transition-all duration-300 ease-in-out hover:shadow-lg">
            <form method="POST" action="<?php echo e(route('notifications.preferences.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-10">

                    <!-- Canaux de réception -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Canaux de réception</h2>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="email_enabled" <?php echo e($preferences->email_enabled ?? true ? 'checked' : ''); ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 transition duration-200">
                                <span>Notifications par email</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="push_enabled" <?php echo e($preferences->push_enabled ?? true ? 'checked' : ''); ?> class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 transition duration-200">
                                <span>Notifications push (application)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Fréquence des résumés -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Fréquence des résumés</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <?php $__currentLoopData = ['instant' => 'Instantané', 'daily' => 'Quotidien', 'weekly' => 'Hebdomadaire']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all hover:bg-indigo-50">
                                    <input type="radio" name="frequency" value="<?php echo e($value); ?>" <?php echo e($preferences->frequency === $value ? 'checked' : ''); ?> class="h-5 w-5 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3"><?php echo e($label); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Types de notifications -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Types de notifications</h2>

                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $types = config("notification.types.$category", []);
                                $categoryLabel = [
                                    'properties' => 'Propriétés',
                                    'visits' => 'Visites',
                                    'favorites' => 'Favoris',
                                    'searches' => 'Recherches',
                                ][$category] ?? ucfirst($category);
                            ?>

                            <?php if(!empty($types)): ?>
                                <div class="border border-gray-200 rounded-lg p-5 mb-6 transition-all hover:shadow-sm">
                                    <h3 class="text-lg font-medium text-gray-800 mb-3"><?php echo e($categoryLabel); ?></h3>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center justify-between">
                                                <span><?php echo e($label); ?></span>
                                                <input type="checkbox" name="alerts[<?php echo e($category); ?>][<?php echo e($type); ?>]"
                                                    <?php echo e($preferences->alerts[$category][$type] ?? config("notification.default_preferences.alerts.$category.$type", false) ? 'checked' : ''); ?>

                                                    class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition">
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Bouton -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-300 ease-in-out shadow">
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/notifications/preferences.blade.php ENDPATH**/ ?>