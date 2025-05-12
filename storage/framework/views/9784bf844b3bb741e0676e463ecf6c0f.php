<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('stylesheets'); ?>
    <style>
    /* Animation pour un effet d'apparition en fondu */
    .fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    <blade keyframes|%20fadeIn%20%7B>from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
    }

    /* Animation pour un effet de rebond */
    .bounce {
        animation: bounce 1s infinite;
    }

    <blade keyframes|%20bounce%20%7B>0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
    }

</style>
</head>

<body>
    <?php echo $__env->make('components.front.layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

	    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/components/front/layouts/auth.blade.php ENDPATH**/ ?>