

<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto mt-12 p-8 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-6 text-red-600 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2v6m-6 2h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2z" />
        </svg>
        Annuler la visite
    </h1>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('visits.cancel', $visit)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('POST'); ?>

        <div>
            <label for="cancellation_reason" class="block text-gray-700 font-semibold mb-2">Raison de l'annulation <span class="text-red-500">*</span></label>
            <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"
                placeholder="Expliquez pourquoi vous annulez cette visite..."><?php echo e(old('cancellation_reason')); ?></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="<?php echo e(route('visits.show', $visit)); ?>" class="px-5 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                Annuler
            </a>
            <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                Confirmer l'annulation
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/properties/visits/cancel.blade.php ENDPATH**/ ?>