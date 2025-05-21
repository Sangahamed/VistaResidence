<div class="relative" x-data="{ open: false }">
    <!-- Bouton Cloche -->
    <button @click="open = !open" class="p-1 text-gray-400 hover:text-indigo-600 relative">
        <?php if (isset($component)) { $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c = $attributes; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('heroicon-o-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\BladeUI\Icons\Components\Svg::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-6 w-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $attributes = $__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__attributesOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c)): ?>
<?php $component = $__componentOriginal643fe1b47aec0b76658e1a0200b34b2c; ?>
<?php unset($__componentOriginal643fe1b47aec0b76658e1a0200b34b2c); ?>
<?php endif; ?>
        <!--[if BLOCK]><![endif]--><?php if($unreadCount > 0): ?>
            <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-50 border border-gray-200">
        <div class="px-4 py-2 border-b bg-gray-50">
            <h3 class="text-sm font-medium">Notifications (<?php echo e($unreadCount); ?>)</h3>
        </div>

        <div class="divide-y max-h-96 overflow-y-auto">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e($notification->data['url'] ?? '#'); ?>" 
                   wire:click="markAsRead('<?php echo e($notification->id); ?>')"
                   class="block px-4 py-3 hover:bg-gray-50 transition <?php echo e($notification->unread() ? 'bg-blue-50' : ''); ?>">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <?php if (isset($component)) { $__componentOriginal5ed21fce7eca45924c297066ea7bf322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ed21fce7eca45924c297066ea7bf322 = $attributes; } ?>
<?php $component = App\View\Components\NotificationIcon::resolve(['type' => $notification->type] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\NotificationIcon::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'h-5 w-5 text-indigo-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ed21fce7eca45924c297066ea7bf322)): ?>
<?php $attributes = $__attributesOriginal5ed21fce7eca45924c297066ea7bf322; ?>
<?php unset($__attributesOriginal5ed21fce7eca45924c297066ea7bf322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ed21fce7eca45924c297066ea7bf322)): ?>
<?php $component = $__componentOriginal5ed21fce7eca45924c297066ea7bf322; ?>
<?php unset($__componentOriginal5ed21fce7eca45924c297066ea7bf322); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium <?php echo e($notification->unread() ? 'text-gray-900' : 'text-gray-500'); ?>">
                                <?php echo e($notification->data['title']); ?>

                            </p>
                            <p class="text-xs text-gray-500"><?php echo e($notification->data['message']); ?></p>
                            <p class="text-xs text-gray-400 mt-1">
                                <?php echo e($notification->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-4 py-3 text-center text-sm text-gray-500">
                    Aucune notification
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="px-4 py-2 border-t bg-gray-50 text-center">
            <a href="<?php echo e(route('notifications.index')); ?>" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/livewire/notification-bell.blade.php ENDPATH**/ ?>