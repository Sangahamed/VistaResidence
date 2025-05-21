<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('stylesheets'); ?>
    <style>
        @keyframes slideDown {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .searching {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1rem;
        }

        .nav-link.active {
            @apply bg-blue-500 text-white;
        }

        .nav-link.pending {
            @apply bg-yellow-100 text-yellow-800;
        }

        .badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        .badge-primary {
            @apply bg-blue-100 text-blue-800;
        }

        .badge-warning {
            @apply bg-yellow-100 text-yellow-800;
        }

        .badge-success {
            @apply bg-green-100 text-green-800;
        }

        .badge-danger {
            @apply bg-red-100 text-red-800;
        }

        .transition-all {
            transition: all 0.2s ease;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.2s ease;
            z-index: 50;
        }

        .sidebar-mobile.open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            .sidebar-overlay {
                display: none !important;
            }

            .sidebar-mobile {
                transform: none;
                display: block !important;
            }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    
    <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-4 right-4 z-50">
            <div class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (Mobile only) -->
        <div id="sidebarOverlay" class="sidebar-overlay lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Dynamic Sidebar based on account type -->
        <?php if(auth()->user()->isClient() ||
                auth()->user()->isIndividual() ||
                (auth()->user()->isCompany() && auth()->user()->hasPendingCompany())): ?>
            <?php echo $__env->make('components.back.layout.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php elseif(auth()->user()->isCompany() && auth()->user()->activeCompany()): ?>
            <?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto transition-all duration-200 ease-in-out">
            <!-- Mobile Header -->
            <header class="bg-white shadow-sm lg:hidden flex justify-between items-center p-4 sticky top-0 z-30">
                <button id="menuBtn" class="text-gray-600 focus:outline-none transition-transform hover:scale-110"
                    onclick="toggleSidebar()">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
                <h1 class="text-xl font-bold text-gray-800 animate-slide-down"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                <button id="logoutBtn" class="text-gray-600 hover:text-red-500 transition-colors">
                    <i class="ri-logout-box-r-line text-xl"></i>
                </button>
            </header>

            <!-- Content Area -->
            <div class="p-4 lg:p-8 animate-fade-in">
                <!-- Account Status Banner -->
                <?php if(auth()->user()->isClient()): ?>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg animate-slide-down">
                        <div class="flex items-center">
                            <i class="ri-information-line text-blue-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-800">Vous êtes actuellement en mode client</p>
                                <p class="text-sm text-blue-600">Pour publier des annonces, passez en mode particulier
                                </p>
                            </div>
                        </div>
                    </div>
                <?php elseif(auth()->user()->isIndividual()): ?>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/userdashbord.js'); ?>

    <script>
        // Animation for elements when they come into view
        document.addEventListener('DOMContentLoaded', () => {
            const animatedElements = document.querySelectorAll('.animate-fade-in');

            animatedElements.forEach(el => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 50);
            });

            // Initialize sidebar state
            initSidebar();
        });

        // Sidebar functions
        function initSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');

            // On desktop, always show sidebar
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('hidden');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('hidden');
            }
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                overlay.style.display = 'block';
                setTimeout(() => {
                    sidebar.classList.add('sidebar-mobile');
                    sidebar.classList.add('open');
                }, 10);
            } else {
                sidebar.classList.remove('open');
                overlay.style.display = 'none';
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                }, 200);
            }
        }

        // Close sidebar when clicking outside (on overlay)
        document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);

        // Handle window resize
        window.addEventListener('resize', () => {
            initSidebar();
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/components/back/layout/back.blade.php ENDPATH**/ ?>