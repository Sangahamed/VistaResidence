<?php $__env->startSection('content'); ?>
    <style>
        .favorite-btn {
            transition: all 0.3s ease;
        }

        .favorite-btn.active {
            color: #ef4444;
        }

        .star-rating i {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .star-rating i:hover {
            transform: scale(1.2);
        }

        .new-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>

    <section class="relative mt-3">
        <div class="container-fluid md:mx-4 mx-2">
            <div class="relative pt-40 pb-52 table w-full rounded-3xl shadow-md overflow-hidden">
                <div class="absolute inset-0 bg-black/60"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/30 to-purple-500/30"></div>

                <div class="container relative z-10">
                    <div class="grid grid-cols-1">
                        <div class="md:text-start text-center">
                            <h1 class="font-bold text-white lg:leading-normal text-4xl lg:text-5xl mb-6 animate-fade-in">
                                Trouvez votre propriété idéale
                            </h1>
                            <p class="text-xl text-white/80 max-w-2xl mx-auto md:mx-0 animate-fade-in delay-100">
                                Découvrez des milliers d'offres parmi nos biens sélectionnés
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative md:pb-24 pb-20 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid justify-center">
                <div class="relative -mt-32">
                    <div class="grid">

                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('property-search');

$__html = app('livewire')->mount($__name, $__params, 'lw-1168444830-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 lg:py-8 -mt-10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Nos propriétés</h2>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    
                </div>
            </div>

            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties-feed');

$__html = app('livewire')->mount($__name, $__params, 'lw-1168444830-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>

        <div class="hidden lg:block fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50">
            <a href="<?php echo e(route('map.index')); ?>" class="bg-black text-white px-4 py-2 rounded-full shadow-md">
                Afficher la carte 🗺️
            </a>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-orange-500 to-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-6 animate-fade-in">Vous cherchez quelque chose de spécifique ?
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto animate-fade-in delay-100">
                Notre équipe d'experts peut vous aider à trouver la propriété parfaite.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in delay-200">
                <a href="#"
                    class="px-8 py-3 bg-white text-orange-600 font-bold rounded-lg hover:bg-gray-100 transition-colors duration-300 transform hover:-translate-y-1">
                    Parlez à un expert
                </a>
                <a href="<?php echo e(route('properties.index')); ?>"
                    class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white/10 transition-colors duration-300 transform hover:-translate-y-1">
                    Voir toutes les offres
                </a>
            </div>
        </div>
    </section>

    <!-- Fixed CTA Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="#"
            class="flex items-center justify-center w-14 h-14 bg-orange-500 hover:bg-orange-600 text-white rounded-full shadow-xl transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-phone-alt text-xl"></i>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components.front.layouts.front', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/front/pages/index.blade.php ENDPATH**/ ?>