<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sidebar-bg': '#1e2530',
                        'stats-purple': '#8e44ad',
                        'stats-turquoise': '#32c5d2',
                        'stats-red': '#e7505a',
                        'stats-blue': '#3598dc',
                    }
                }
            }
        }
    </script>
    <style>
        #sidebar {
            position: fixed;
            left: -100%;
            height: 100vh;
            width: 16rem;
            transition: left 0.3s ease-in-out;
            z-index: 50;
        }

        #sidebar:not(.hidden) {
            left: 0;
        }

        @media (min-width: 1024px) {
            #sidebar {
                position: static;
                left: 0;
            }

            #overlay {
                display: none !important;
            }

            #mobileMenuBtn {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-800 font-sans">
    <!-- Search Modal -->
    <div id="searchModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
        <div class="relative min-h-screen flex items-start justify-center pt-20 px-4">
            <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-lg shadow-2xl">
                <div class="relative">
                    <input type="text" id="modalSearch" placeholder="Search..."
                        class="w-full px-4 py-3 pl-10 text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-700 rounded-lg focus:outline-none"
                        autofocus>
                    <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400 dark:text-gray-600" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                    No result found
                </div>
                <div
                    class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center space-x-4">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7l4-4m0 0l4 4m-4-4v18" />
                        </svg>
                        <span>↵ to select</span>
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span>↑↓ to navigate</span>
                    </span>
                    <span class="flex items-center">
                        <kbd
                            class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">esc</kbd>
                        <span class="ml-2">to close</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <header class="bg-white dark:bg-gray-800 shadow-md p-4 lg:hidden flex justify-between items-center">
        <button id="mobileMenuBtn" aria-label="Toggle menu" aria-expanded="false"
            class="fixed top-2 right-10 z-50 lg:hidden bg-sidebar-bg text-white p-2 rounded-lg shadow-lg transition-opacity duration-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="menuIcon" class="transition-transform duration-300" stroke-linecap="round"
                    stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
        <button id="logoutBtn" class="text-gray-600 dark:text-gray-300">
            <i class="ri-logout-box-r-line text-xl"></i>
        </button>
    </header>

    <!-- Top Navigation -->
    <nav class="hidden lg:flex fixed top-0 left-0 right-0 z-50 bg-sidebar-bg text-white h-14 items-center px-4">
        <div class="flex-1 flex items-center justify-between max-w-8xl mx-auto w-full">
            <img src="/placeholder.svg?height=32&width=100" alt="Hously" class="h-8">
            <div class="flex items-center space-x-4">

                <div class="relative">
                    <button id="searchButton"
                        class="w-64 px-4 py-1.5 bg-gray-700 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-left text-gray-300">
                        Search
                        <span class="absolute right-3 top-1.5 text-gray-400 text-xs">ctrl/cmd + k</span>
                    </button>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-sm px-3 py-1.5 border border-gray-600 rounded-md hover:bg-gray-700">
                    View website
                </button>
                <button id="themeToggle" class="p-1.5 hover:bg-gray-700 rounded-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <div class="relative">
                    <button class="p-1.5 hover:bg-gray-700 rounded-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute -top-1 -right-1 bg-blue-500 text-xs rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Profile"
                        class="w-8 h-8 rounded-full">
                    <span class="text-sm text-gray-300">pablomachado maresca</span>
                </div>
            </div>
        </div>
    </nav>

    <div id="sidebarOverlay" class="flex h-screen overflow-hidden pt-0 lg:pt-14">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-sidebar-bg text-white overflow-y-auto">
            <div class="p-4">
                <div class="flex items-center space-x-2">
                    <span class="text-xl font-semibold text-white">Hously</span>
                </div>
            </div>

            <nav class="py-4">
                <a href="#" class="flex items-center px-4 py-2 bg-blue-600 text-white">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Real Estate
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Pages
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Blog
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Payments
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    Testimonials
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Consults
                </a>

                <div class="px-4 py-2">
                    <div class="text-xs uppercase text-gray-500 font-semibold">Administration</div>
                </div>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>

                <a href="#" class="flex items-center px-4 py-2 hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Platform Administration
                </a>
            </nav>
        </aside>
        <div id="overlay" class="hidden"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto bg-gray-100">
            @yield('content')
        </main>
    </div>

     <script>
        // Gestion de la modale de recherche
        function setupSearchModal() {
            const searchModal = document.getElementById('searchModal');
            const searchButton = document.getElementById('searchButton');
            const modalSearch = document.getElementById('modalSearch');

            if (searchButton && searchModal && modalSearch) {
                // Ouvrir la modale lors du clic sur le bouton de recherche
                searchButton.addEventListener('click', () => {
                    searchModal.classList.remove('hidden');
                    modalSearch.focus();
                });

                // Ouvrir la modale avec le raccourci Ctrl+K ou Cmd+K
                document.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault(); // Empêcher le comportement par défaut du navigateur
                        searchModal.classList.remove('hidden');
                        modalSearch.focus();
                    }

                    // Fermer la modale avec la touche Escape
                    if (e.key === 'Escape') {
                        searchModal.classList.add('hidden');
                    }
                });
            }
        }

        // Gestion de l'affichage/masquage de la sidebar en mode mobile
        function setupMobileSidebar() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            if (mobileMenuBtn && sidebar && overlay) {
                mobileMenuBtn.addEventListener('click', () => {
                    // Basculer l'état de la sidebar (ouvert/fermé)
                    sidebar.classList.toggle('hidden');
                    overlay.classList.toggle('hidden');
                });

                // Fermer la sidebar lorsque l'overlay est cliqué
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('hidden');
                    overlay.classList.add('hidden');
                });
            } else {
                console.error('Un ou plusieurs éléments du DOM n\'ont pas été trouvés.');
            }
        }

        // Initialisation
        function init() {
            setupSearchModal();
            setupMobileSidebar();
        }

        // Démarrer le script
        init();
    </script>
    @stack('scripts')
</body>

</html>
