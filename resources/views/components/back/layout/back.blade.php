<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    @vite('resources/css/app.css')
    @livewireStyles
    @stack('stylesheets')
    <style>

        
        @keyframes slideDown {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(-100%); opacity: 0; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
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
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (Mobile only) -->
        <div id="sidebarOverlay" class="sidebar-overlay lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Dynamic Sidebar based on account type -->
        @if(auth()->user()->isClient() || auth()->user()->isIndividual() || auth()->user()->isCompany() && auth()->user()->hasPendingCompany())
            @include('components.back.layout.navbar')
        @elseif(auth()->user()->isCompany() && auth()->user()->activeCompany())
        @include('layouts.app')
        @endif

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto transition-all duration-200 ease-in-out">
            <!-- Mobile Header -->
            <header class="bg-white shadow-sm lg:hidden flex justify-between items-center p-4 sticky top-0 z-30">
                <button id="menuBtn" class="text-gray-600 focus:outline-none transition-transform hover:scale-110" onclick="toggleSidebar()">
                    <i class="ri-menu-line text-2xl"></i>
                </button>
                <h1 class="text-xl font-bold text-gray-800 animate-slide-down">@yield('page-title', 'Dashboard')</h1>
                <button id="logoutBtn" class="text-gray-600 hover:text-red-500 transition-colors">
                    <i class="ri-logout-box-r-line text-xl"></i>
                </button>
            </header>

            <!-- Content Area -->
            <div class="p-4 lg:p-8 animate-fade-in">
                <!-- Account Status Banner -->
                @if(auth()->user()->isClient())
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg animate-slide-down">
                        <div class="flex items-center">
                            <i class="ri-information-line text-blue-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-blue-800">Vous êtes actuellement en mode client</p>
                                <p class="text-sm text-blue-600">Pour publier des annonces, passez en mode particulier</p>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->isIndividual())
                    
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
    @vite('resources/js/userdashbord.js')

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