<?php
$seenIcon = (!!$seen ? 'check-double' : 'check');
$timeAndSeen = "<span data-time='$created_at' class='message-time'>
        ".($isSender ? "<span class='fas fa-$seenIcon' seen'></span>" : '' )." <span class='time'>$timeAgo</span>
    </span>";
?>

<div class="message-card <?php if($isSender): ?> mc-sender <?php endif; ?>" data-id="<?php echo e($id); ?>">
    
    <?php if($isSender): ?>
        <div class="actions">
            <i class="fas fa-trash delete-btn" data-id="<?php echo e($id); ?>"></i>
        </div>
    <?php endif; ?>
    
    <div class="message-card-content">
        <?php if(@$attachment->type != 'image' || $message): ?>
            <div class="message">
                <?php echo ($message == null && $attachment != null && @$attachment->type != 'file') ? $attachment->title : nl2br($message); ?>

                <?php echo $timeAndSeen; ?>

                
                <?php if(@$attachment->type == 'file'): ?>
                <a href="<?php echo e(route(config('chatify.attachments.download_route_name'), ['fileName'=>$attachment->file])); ?>" class="file-download">
                    <span class="fas fa-file"></span> <?php echo e($attachment->title); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if(@$attachment->type == 'image'): ?>
        <div class="image-wrapper" style="text-align: <?php echo e($isSender ? 'end' : 'start'); ?>">
            <div class="image-file chat-image" style="background-image: url('<?php echo e(Chatify::getAttachmentUrl($attachment->file)); ?>')">
                <div><?php echo e($attachment->title); ?></div>
            </div>
            <div style="margin-bottom:5px">
                <?php echo $timeAndSeen; ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/vendor/Chatify/layouts/messageCard.blade.php ENDPATH**/ ?>