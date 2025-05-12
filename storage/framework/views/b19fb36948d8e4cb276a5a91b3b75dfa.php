

<?php $__env->startSection('content'); ?>
<main class="container mx-auto py-8 px-4">
    <div class="flex flex-col gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Enchères immobilières</h1>
            <p class="text-muted-foreground">
                Découvrez les propriétés disponibles aux enchères et placez vos offres.
            </p>
        </div>

        <div class="flex flex-wrap gap-4 mb-6">
            <a href="<?php echo e(route('auctions.index', ['status' => 'active'])); ?>" 
                class="px-4 py-2 rounded-md <?php echo e($status === 'active' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200'); ?>">
                Enchères en cours
            </a>
            <a href="<?php echo e(route('auctions.index', ['status' => 'upcoming'])); ?>" 
                class="px-4 py-2 rounded-md <?php echo e($status === 'upcoming' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200'); ?>">
                Enchères à venir
            </a>
            <a href="<?php echo e(route('auctions.index', ['status' => 'ended'])); ?>" 
                class="px-4 py-2 rounded-md <?php echo e($status === 'ended' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200'); ?>">
                Enchères terminées
            </a>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('auctions.history')); ?>" 
                    class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 ml-auto">
                    Mes enchères
                </a>
            <?php endif; ?>
        </div>

        <?php if($auctions->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $auctions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="relative">
                            <?php if($auction->property->featured_image): ?>
                                <img src="<?php echo e(asset('storage/' . $auction->property->featured_image)); ?>" 
                                    alt="<?php echo e($auction->property->title); ?>" 
                                    class="w-full h-48 object-cover">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400 text-4xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="absolute top-0 right-0 m-2">
                                <?php if($auction->status === 'active'): ?>
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        En cours
                                    </span>
                                <?php elseif($auction->status === 'upcoming'): ?>
                                    <span class="bg-blue-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        À venir
                                    </span>
                                <?php elseif($auction->status === 'ended'): ?>
                                    <span class="bg-gray-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        Terminée
                                    </span>
                                <?php else: ?>
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-md text-xs font-semibold">
                                        Annulée
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-2">
                                <a href="<?php echo e(route('auctions.show', $auction)); ?>" class="hover:text-primary">
                                    <?php echo e($auction->property->title); ?>

                                </a>
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                <?php echo e($auction->property->address); ?>, <?php echo e($auction->property->city); ?>

                            </p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Enchère actuelle</p>
                                    <p class="text-xl font-bold text-primary">
                                        <?php echo e($auction->current_bid ? number_format($auction->current_bid, 0, ',', ' ') . ' €' : 'Aucune enchère'); ?>

                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Prix de départ</p>
                                    <p class="text-gray-700">
                                        <?php echo e(number_format($auction->starting_price, 0, ',', ' ')); ?> €
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Enchères</p>
                                    <p class="text-gray-700"><?php echo e($auction->total_bids); ?></p>
                                </div>
                                <div class="text-right">
                                    <?php if($auction->status === 'active'): ?>
                                        <p class="text-sm text-gray-500">Fin dans</p>
                                        <p class="text-gray-700 countdown" data-end="<?php echo e($auction->end_date->timestamp); ?>">
                                            <?php echo e($auction->end_date->diffForHumans()); ?>

                                        </p>
                                    <?php elseif($auction->status === 'upcoming'): ?>
                                        <p class="text-sm text-gray-500">Début dans</p>
                                        <p class="text-gray-700">
                                            <?php echo e($auction->start_date->diffForHumans()); ?>

                                        </p>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500">Terminée le</p>
                                        <p class="text-gray-700">
                                            <?php echo e($auction->end_date->format('d/m/Y H:i')); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <a href="<?php echo e(route('auctions.show', $auction)); ?>" class="block w-full bg-primary text-white text-center py-2 rounded-md hover:bg-primary-dark">
                                <?php if($auction->status === 'active'): ?>
                                    Enchérir maintenant
                                <?php elseif($auction->status === 'upcoming'): ?>
                                    Voir les détails
                                <?php else: ?>
                                    Voir les résultats
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mt-6">
                <?php echo e($auctions->links()); ?>

            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-gavel text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aucune enchère disponible</h3>
                <p class="mt-1 text-sm text-gray-500">
                    <?php if($status === 'active'): ?>
                        Il n'y a actuellement aucune enchère en cours.
                    <?php elseif($status === 'upcoming'): ?>
                        Il n'y a actuellement aucune enchère à venir.
                    <?php else: ?>
                        Il n'y a actuellement aucune enchère terminée.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Update countdown timers
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const endTime = parseInt(el.dataset.end) * 1000;
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance <= 0) {
                el.textContent = 'Terminée';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            if (days > 0) {
                el.textContent = `${days}j ${hours}h ${minutes}m`;
            } else {
                el.textContent = `${hours}h ${minutes}m ${seconds}s`;
            }
        });
    }
    
    // Update countdowns every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.back.layout.back', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/auctions/index.blade.php ENDPATH**/ ?>