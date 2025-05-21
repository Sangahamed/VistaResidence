<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- JavaScript Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('stylesheets'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


</head>
<!-- class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%" -->
<style>
    .notification-item.unread {
        background-color: #f0f9ff;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
    }

    .notification-enter-active,
    .notification-leave-active {
        transition: all 0.3s ease;
    }

    .notification-enter-from,
    .notification-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>

<body class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%">
    

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Écoute les nouvelles notifications (via Echo)
            <?php if(auth()->guard()->check()): ?>
            window.Echo.private(`App.Models.User.<?php echo e(auth()->id()); ?>`)
                .notification((notification) => {
                    // Rafraîchit le composant Livewire
                    Livewire.emit('notificationReceived');

                    // Affiche un toast
                    showNotificationToast({
                        title: notification.title,
                        message: notification.message,
                        icon: notification.type,
                        url: notification.url
                    });
                });
        <?php endif; ?>

        function showNotificationToast(data) {
            // Implémentez votre système de toast (ex: Toastify, Alpine.js)
            console.log("New notification:", data);
        }
        });
    </script>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/js/notifications.js']); ?>
</body>

</html>

<?php echo $__env->make('components.front.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/components/front/layouts/front.blade.php ENDPATH**/ ?>