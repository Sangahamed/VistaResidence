<script src="https://js.pusher.com/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@3.0.3/dist/index.min.js"></script>
<script >
    // Gloabl Chatify variables from PHP to JS
    window.chatify = {
        name: "<?php echo e(config('chatify.name')); ?>",
        sounds: <?php echo json_encode(config('chatify.sounds')); ?>,
        allowedImages: <?php echo json_encode(config('chatify.attachments.allowed_images')); ?>,
        allowedFiles: <?php echo json_encode(config('chatify.attachments.allowed_files')); ?>,
        maxUploadSize: <?php echo e(Chatify::getMaxUploadSize()); ?>,
        pusher: <?php echo json_encode(config('chatify.pusher')); ?>,
        pusherAuthEndpoint: '<?php echo e(route("pusher.auth")); ?>'
    };
    window.chatify.allAllowedExtensions = chatify.allowedImages.concat(chatify.allowedFiles);
</script>
<script src="<?php echo e(asset('js/chatify/utils.js')); ?>"></script>
<script src="<?php echo e(asset('js/chatify/code.js')); ?>"></script>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/vendor/Chatify/layouts/footerLinks.blade.php ENDPATH**/ ?>