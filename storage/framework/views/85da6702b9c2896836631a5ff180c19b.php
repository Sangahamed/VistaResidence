<aside class="flex flex-col h-screen w-64 bg-gradient-to-b from-white to-blue-50 shadow-lg block transform transition-all duration-300 ease-in-out">

    <div class="p-6 flex-none">
        <img src="/logo.svg" alt="Logo" class="h-10 mb-8 mx-auto animate-slide-down">

        <!-- User Profile Section -->
        <div class="flex items-center mb-6 p-3 bg-white rounded-lg shadow-xs">
            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name)); ?>&color=7F9CF5&background=EBF4FF ?? <?php echo e(Auth::user()->avatar_url); ?>" alt="User Avatar"
                class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-blue-200">
            <div>
                <p class="font-semibold text-gray-800"><?php echo e(Auth::user()->name); ?></p>
                <div class="flex items-center mt-1">
                    <?php if(auth()->user()->isClient()): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Client</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Particulier</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Credits Section -->
        <?php if(auth()->user()->isIndividual()): ?>
            <div class="mb-6 bg-white p-4 rounded-lg shadow-xs animate-fade-in">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-600">Crédits</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-bold">10</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 60%"></div>
                </div>
                <a href="#" class="mt-2 inline-block text-sm text-blue-500 hover:text-blue-700 transition-colors">
                    <i class="ri-add-line mr-1"></i> Acheter des crédits
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto px-4 space-y-1">
        <!-- Dashboard -->
        <a href="<?php echo e(route('dashboard')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="ri-dashboard-line mr-3 text-lg"></i>
            Tableau de bord
        </a>

        <!-- Individual User Links -->
        <?php if(auth()->user()->isIndividual()): ?>
            <a href="<?php echo e(route('properties.index')); ?>"
                class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('properties.index') ? 'bg-blue-500 text-white' : ''); ?>">
                <i class="ri-advertisement-line mr-3 text-lg"></i>
                Mes annonces
            </a>

            <a href="#"
                class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all">
                <i class="ri-coins-line mr-3 text-lg"></i>
                Crédits & Paiements
            </a>
        <?php endif; ?>

        <!-- Notifications -->
        <a href="<?php echo e(route('notifications.index')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('notifications.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-bell mr-3 text-lg"></i>
            Notifications
            <?php if(auth()->check() && auth()->user()->unreadNotifications()->count() > 0): ?>
                <span class="ml-auto px-2 py-0.5 text-xs rounded-full bg-red-500 text-white">
                    <?php echo e(auth()->user()->unreadNotifications()->count()); ?>

                </span>
            <?php endif; ?>
        </a>

        <!-- Messages -->
        <a href="<?php echo e(route('messenger')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('messages.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-comments mr-3 text-lg"></i>
            Messages
            <?php if(Auth::check() && Auth::user()->unreadMessagesCount() > 0): ?>
                <span class="ml-auto px-2 py-0.5 text-xs rounded-full bg-red-500 text-white">
                    <?php echo e(Auth::user()->unreadMessagesCount()); ?>

                </span>
            <?php endif; ?>
        </a>

        <!-- Property Comparison -->
        <a href="<?php echo e(route('properties.comparison')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('properties.comparison') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-exchange-alt mr-3 text-lg"></i>
            Comparaison
            <?php if(session()->has('comparison_list') && count(session('comparison_list')) > 0): ?>
                <span class="ml-auto px-2 py-0.5 text-xs rounded-full bg-red-500 text-white">
                    <?php echo e(count(session('comparison_list'))); ?>

                </span>
            <?php endif; ?>
        </a>

        <!-- Auctions -->
        <a href="<?php echo e(route('auctions.index')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('auctions.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-gavel mr-3 text-lg"></i>
            Enchères
        </a>

        <!-- Mortgage Calculator -->
        <a href="<?php echo e(route('mortgage.calculator')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('mortgage.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-calculator mr-3 text-lg"></i>
            Calculateur
        </a>

        <!-- Recommendations -->
        <a href="<?php echo e(route('recommendations.index')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('recommendations.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="fas fa-lightbulb mr-3 text-lg"></i>
            Recommandations
        </a>

        <!-- Settings -->
        <a href="<?php echo e(route('profile.index')); ?>"
            class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-500 hover:text-white rounded-lg transition-all <?php echo e(request()->routeIs('profile.*') ? 'bg-blue-500 text-white' : ''); ?>">
            <i class="ri-user-settings-line mr-3 text-lg"></i>
            Paramètres
        </a>

        <!-- Logout -->
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                <i class="ri-logout-box-r-line mr-3 text-lg"></i>
                Déconnexion
            </button>
        </form>

        <!-- Account Type Switching -->
        <div class="mt-8 px-4 animate-fade-in">
            <?php if(auth()->user()->isClient()): ?>
                <form method="POST" action="<?php echo e(route('switch.to.individual')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="w-full flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02]">
                        <i class="ri-user-add-line mr-2"></i>
                        Poster annonces
                    </button>
                </form>
            <?php elseif(!Auth::user()->isCompany()): ?>
                <a href="<?php echo e(route('companies.create')); ?>"
                    class="w-full create-company-btn flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:scale-[1.02] text-sm">
                    <i class="ri-building-2-line mr-2"></i>
                    <span class="truncate">Créer une entreprise</span>
                </a>
            <?php elseif(Auth::user()->isCompany()): ?>
                <div
                    class="w-full flex items-center justify-center bg-yellow-100 text-yellow-800 py-3 px-4 rounded-lg shadow-xs">
                    <i class="ri-time-line mr-2"></i>
                    <a href="<?php echo e(route('companies.edit')); ?>">  Demande en attente </a> 
                </div>
            <?php endif; ?>
        </div>
    </nav>
</aside><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/components/back/layout/navbar.blade.php ENDPATH**/ ?>