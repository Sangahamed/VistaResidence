<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
        Avis (<?php echo e($totalReviews); ?>)
        <span class="text-sm font-normal">
            - Note moyenne : <?php echo e(number_format($averageRating, 1)); ?>/5
        </span>
    </h2>

    <!--[if BLOCK]><![endif]--><?php if(auth()->guard()->check()): ?>
        <!--[if BLOCK]><![endif]--><?php if (! ($hasReviewed)): ?>
            
            <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="font-medium mb-3">Laisser un avis</h3>

                <div class="star-rating mb-3">
                    <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star cursor-pointer
                            <?php echo e($i <= $rating ? 'text-yellow-400' : 'text-gray-300'); ?> text-xl"
                            wire:click="setRating(<?php echo e($i); ?>)"></i>
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <textarea wire:model.defer="comment" class="w-full p-3 border rounded-lg mb-2" placeholder="Partagez votre expérience..."></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                <button wire:click.prevent="submitReview"
                    class="mt-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Publier
                </button>
            </div>
        <?php else: ?>
            <p class="text-green-500 mb-4">Vous avez déjà laissé un avis pour cette propriété.</p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php else: ?>
        <p class="text-gray-600 mb-4">
            Veuillez <a href="<?php echo e(route('login')); ?>" class="text-orange-500">vous connecter</a> pour laisser un avis.
        </p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div class="space-y-4">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <div class="flex items-center mb-2">
                    <img src="<?php echo e($review->user->avatar_url ?? asset('images/default-avatar.png')); ?>"
                         alt="<?php echo e($review->user->name); ?>"
                         class="w-10 h-10 rounded-full mr-3">
                    <div>
                        <h4 class="font-medium"><?php echo e($review->user->name); ?></h4>
                        <div class="flex">
                            <!--[if BLOCK]><![endif]--><?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?> text-sm"></i>
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300"><?php echo e($review->comment); ?></p>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($review->created_at->diffForHumans()); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-500">Aucun avis pour l'instant. Soyez le premier à en laisser un !</p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(session()->has('error')): ?>
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/livewire/property-reviews.blade.php ENDPATH**/ ?>