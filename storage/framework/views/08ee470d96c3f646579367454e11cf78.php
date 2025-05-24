

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto py-10 px-4">

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2 text-sm text-blue-500">
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:underline">DASHBOARD</a>
                <span>/</span>
                <span class="font-medium">Notification</span>
            </div>

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <a href="<?php echo e(route('notifications.preferences')); ?>"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all transform hover:scale-[1.02]">
                    <i class="fas fa-bell mr-2"></i>
                    Préférences de notification
                </a>

            </div>
        </div>
        <div class="max-w-4xl mx-auto space-y-8">

            <!-- En-tête -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">Notifications</h1>
                    <p class="text-gray-600 mt-1">
                        Restez informé des dernières mises à jour concernant vos propriétés et recherches.
                    </p>
                </div>

                <?php if($unreadCount > 0): ?>
                    <form method="POST" action="<?php echo e(route('notifications.mark-all-read')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="text-sm text-indigo-600 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            Marquer tout comme lu
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Liste des notifications -->
            <div class="bg-white shadow rounded-lg divide-y divide-gray-100 overflow-hidden">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($notification->data['url'] ?? '#'); ?>"
                        class="flex px-6 py-4 gap-4 hover:bg-gray-50 transition-all duration-200 ease-in-out <?php echo e($notification->unread() ? 'bg-blue-50' : ''); ?>">
                        <!-- Icône -->
                        <div class="mt-1 flex-shrink-0">
                            <?php echo $__env->make('notifications.partials.icon', ['type' => $notification->type], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p
                                    class="text-sm font-semibold <?php echo e($notification->unread() ? 'text-gray-900' : 'text-gray-500'); ?>">
                                    <?php echo e($notification->data['title'] ?? 'Notification'); ?>

                                </p>
                                <span class="text-xs text-gray-400">
                                    <?php echo e($notification->created_at->format('d/m/Y H:i')); ?>

                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                <?php echo e($notification->data['message']  ?? 'message'); ?>

                            </p>
                            <?php if(!empty($notification->data['action'])): ?>
                                <div class="mt-2">
                                    <span
                                        class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded">
                                        <?php echo e($notification->data['action']); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 text-gray-500">
                        Vous n'avez aucune notification pour le moment.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <?php echo e($notifications->links()); ?>

            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/notifications/index.blade.php ENDPATH**/ ?>