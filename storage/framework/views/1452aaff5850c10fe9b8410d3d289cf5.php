<title><?php echo e(config('chatify.name')); ?></title>


<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="id" content="<?php echo e($id); ?>">
<meta name="messenger-color" content="<?php echo e($messengerColor); ?>">
<meta name="messenger-theme" content="<?php echo e($dark_mode); ?>">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="url" content="<?php echo e(url('').'/'.config('chatify.routes.prefix')); ?>" data-user="<?php echo e(Auth::user()->id); ?>">


<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo e(asset('js/chatify/font.awesome.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/chatify/autosize.js')); ?>"></script>
<?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>


<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="<?php echo e(asset('css/chatify/style.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('css/chatify/'.$dark_mode.'.mode.css')); ?>" rel="stylesheet" />
 <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>


<style>
    :root {
        --primary-color: <?php echo e($messengerColor); ?>;
    }

    .messenger {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .messenger-listView {
        border-right: 1px solid #e5e7eb;
    }
    
    .messenger-listView-tabs {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .messenger-listView .m-header .user-name {
        font-weight: 600;
    }
    
    .messenger-messagingView .m-header .user-name {
        font-weight: 600;
    }
    
    .messenger-infoView {
        background-color: #f9fafb;
        border-left: 1px solid #e5e7eb;
    }
    
    .messenger-infoView .info-name {
        font-weight: 600;
    }
    
    .messenger-title {
        font-weight: 600;
    }
    
    .message-card .message {
        border-radius: 1rem;
    }
    
    .m-send {
        border-top: 1px solid #e5e7eb;
    }
</style>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/vendor/Chatify/layouts/headLinks.blade.php ENDPATH**/ ?>