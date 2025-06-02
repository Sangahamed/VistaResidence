<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title'); ?></title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Custom Styles -->
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('stylesheets'); ?>

    <!-- Third-party Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
</head>

<body class="bg-gradient-to-l from-[#224172] to-[#6a3c3c]">

    

    <?php echo $__env->yieldContent('content'); ?>

    <!-- Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- Vite Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js', 'resources/js/notifications.js']); ?>

    <!-- Authenticated User Script -->
    <?php if(auth()->guard()->check()): ?>
        <script>
            window.authUserId = <?php echo e(auth()->id()); ?>;
        </script>
    <?php endif; ?>

</body>

</html>

<?php echo $__env->make('components.front.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/components/front/layouts/front.blade.php ENDPATH**/ ?>