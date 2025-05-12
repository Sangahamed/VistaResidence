<!-- Desktop Menu -->
<header
    class="hidden lg:flex fixed top-0 inset-x-0 justify-between items-center px-8 py-4 bg-white/95 backdrop-blur-md shadow-md border-b border-gray-200/50 z-50 transition-all duration-300 hover:bg-white/100">
    <!-- Logo -->
    <div class="flex items-center">
        <a href="/" class="flex items-center space-x-2 group">
            <img class="h-8 w-auto transition-transform duration-300 group-hover:scale-110"
                src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&amp;shade=600" alt="Logo">
            <span
                class="font-semibold text-xl text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">Your
                Company</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex gap-8">
        <div class="relative group">
            <button type="button"
                class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 hover:text-indigo-600 transition-colors duration-300"
                aria-expanded="false">
                Product
                <svg class="h-5 w-5 flex-none text-gray-400 group-hover:text-indigo-600 transition-colors duration-300"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <div class="absolute left-1/2 z-10 mt-3 w-screen max-w-md -translate-x-1/2 transform px-2 sm:px-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 ease-in-out">
                <div class="overflow-hidden rounded-xl shadow-xl ring-1 ring-black/5 bg-gradient-to-br from-white to-gray-50">
                    <div class="relative grid gap-1 p-2">
                        <div class="group relative flex items-center gap-x-3 rounded-lg p-3 text-sm leading-6 hover:bg-indigo-50/50 transition-colors duration-200">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-indigo-50 group-hover:bg-white transition-colors duration-200">
                                <svg class="h-5 w-5 text-indigo-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                    Analytics
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Get a better understanding of your traffic</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-3 rounded-lg p-3 text-sm leading-6 hover:bg-indigo-50/50 transition-colors duration-200">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-indigo-50 group-hover:bg-white transition-colors duration-200">
                                <svg class="h-5 w-5 text-indigo-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zm-7.518-.267A8.25 8.25 0 1120.25 10.5M8.288 14.212A5.25 5.25 0 1117.25 10.5"></path>
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                    Engagement
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Speak directly to your customers</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-3 rounded-lg p-3 text-sm leading-6 hover:bg-indigo-50/50 transition-colors duration-200">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-indigo-50 group-hover:bg-white transition-colors duration-200">
                                <svg class="h-5 w-5 text-indigo-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 01-3.6 9.75m6.633-4.596a18.666 18.666 0 01-2.485 5.33"></path>
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                    Security
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Your customers' data will be safe and secure</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-3 rounded-lg p-3 text-sm leading-6 hover:bg-indigo-50/50 transition-colors duration-200">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-indigo-50 group-hover:bg-white transition-colors duration-200">
                                <svg class="h-5 w-5 text-indigo-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z"></path>
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                    Integrations
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Connect with third-party tools</p>
                            </div>
                        </div>
                        <div class="group relative flex items-center gap-x-3 rounded-lg p-3 text-sm leading-6 hover:bg-indigo-50/50 transition-colors duration-200">
                            <div class="flex h-9 w-9 flex-none items-center justify-center rounded-lg bg-indigo-50 group-hover:bg-white transition-colors duration-200">
                                <svg class="h-5 w-5 text-indigo-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                                </svg>
                            </div>
                            <div class="flex-auto">
                                <a href="#" class="block font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">
                                    Automations
                                    <span class="absolute inset-0"></span>
                                </a>
                                <p class="mt-1 text-gray-600">Build strategic funnels that will convert</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-gray-200 bg-gradient-to-r from-indigo-50 to-gray-50 rounded-b-xl">
                        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-indigo-100/50 transition-colors duration-200">
                            <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm6.39-2.908a.75.75 0 01.766.027l3.5 2.25a.75.75 0 010 1.262l-3.5 2.25A.75.75 0 018 12.25v-4.5a.75.75 0 01.39-.658z" clip-rule="evenodd"></path>
                            </svg>
                            Watch demo
                        </a>
                        <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-indigo-100/50 transition-colors duration-200">
                            <svg class="h-5 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"></path>
                            </svg>
                            Contact sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <a href="#" class="text-gray-900 hover:text-indigo-600 transition-colors duration-300">About</a>
        <a href="#" class="text-gray-900 hover:text-indigo-600 transition-colors duration-300">Services</a>
        <a href="#" class="text-gray-900 hover:text-indigo-600 transition-colors duration-300">Contact</a>
    </nav>

    <!-- Action Buttons -->
    <div class="flex items-center gap-4">
        <a href="#"
            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-md hover:shadow-indigo-500/30">
            Poster une annonce
        </a>

        <!-- Notification Bell (visible when logged in) -->
        @auth
            <div class="relative group">
                <button class="text-gray-900 hover:text-indigo-600 transition-colors duration-300 relative">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-red-500 border-2 border-white"></span>
                </button>

                <!-- Notification Dropdown -->
                <div
                    class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 divide-y divide-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-top-right z-50">
                    <div class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-900">Notifications (3)</p>
                    </div>

                    <div class="py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded-full" src="https://randomuser.me/api/portraits/women/44.jpg"
                                        alt="User avatar">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Nouveau message</p>
                                    <p class="text-xs text-gray-500">Marie a commenté votre publication</p>
                                    <p class="text-xs text-gray-400">2 min ago</p>
                                </div>
                            </div>
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-100 rounded-full p-1">
                                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Rappel</p>
                                    <p class="text-xs text-gray-500">Votre rendez-vous dans 1 heure</p>
                                    <p class="text-xs text-gray-400">30 min ago</p>
                                </div>
                            </div>
                        </a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-full p-1">
                                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Paiement confirmé</p>
                                    <p class="text-xs text-gray-500">Votre transaction a été approuvée</p>
                                    <p class="text-xs text-gray-400">1 heure ago</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-center text-indigo-600 font-medium hover:bg-gray-100 transition-colors duration-200">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>
        @endauth

        <!-- User Menu -->
        <div class="relative group">
            @auth
                <!-- Logged In State -->
                <button class="flex items-center gap-1 text-gray-900 hover:text-indigo-600 transition-colors duration-300">
                    <img class="h-8 w-8 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                        alt="{{ auth()->user()->name }}">
                    <svg class="h-4 w-4 text-gray-400 group-hover:text-indigo-600 transition-colors duration-300"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- User Dropdown Menu -->
                <div
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 divide-y divide-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 origin-top-right z-50">
                    <div class="px-4 py-3">
                        <p class="text-sm">Connecté en tant que</p>
                        <p class="text-sm font-medium text-gray-900 truncate">john.doe@example.com</p>
                    </div>

                    <div class="py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Tableau
                            de bord</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Paramètres</a>
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Messages</a>
                    </div>

                    <div class="py-1">
                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Déconnexion</a>
                    </div>
                </div>
            @else
                <!-- Logged Out State -->
                <a href="{{ route('login') }}"
                    class="text-gray-900 hover:text-indigo-600 transition-colors duration-300 relative group">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- Mobile Header -->
<header
    class="lg:hidden fixed inset-x-0 bottom-0 bg-white/95 backdrop-blur-md shadow-lg border-t border-gray-200/50 z-50">
    <nav class="flex justify-around items-center py-2">
        <!-- Icon: Home -->
        <a href="#"
            class="flex flex-col items-center p-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
            <svg class="h-6 w-6 mb-1 group-hover:scale-110 transition-transform duration-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h3.5m7 0H18a2 2 0 002-2V10"></path>
            </svg>
            <span class="text-xs group-hover:font-medium transition-all duration-300">Home</span>
        </a>

        <!-- Icon: Menu -->
        <a href="#"
            class="flex flex-col items-center p-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
            <svg class="h-6 w-6 mb-1 group-hover:scale-110 transition-transform duration-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <span class="text-xs group-hover:font-medium transition-all duration-300">Menu</span>
        </a>

        <!-- Icon: Search -->
        <a href="#"
            class="flex flex-col items-center p-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
            <svg class="h-6 w-6 mb-1 group-hover:scale-110 transition-transform duration-300" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10 4a6 6 0 016 6 6 6 0 01-1.5 4l4.5 4.5m-5.5-9a4 4 0 100 8 4 4 0 000-8z"></path>
            </svg>
            <span class="text-xs group-hover:font-medium transition-all duration-300">Search</span>
        </a>

        <!-- Icon: Profile -->
        @auth
            <a href="#"
                class="flex flex-col items-center p-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group relative">
                <div class="relative">
                    <img class="h-6 w-6 rounded-full group-hover:scale-110 transition-transform duration-300"
                        src="https://randomuser.me/api/portraits/men/32.jpg" alt="User profile">
                    <span class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-red-500 border-2 border-white"></span>
                </div>
                <span class="text-xs group-hover:font-medium transition-all duration-300">Profile</span>
            </a>
        @else
            <a href="{{ route('login') }}"
                class="flex flex-col items-center p-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
                <svg class="h-6 w-6 mb-1 group-hover:scale-110 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.5a6 6 0 016 6v.75a9 9 0 01-18 0V10.5a6 6 0 016-6z"></path>
                </svg>
                <span class="text-xs group-hover:font-medium transition-all duration-300">Login</span>
            </a>
        @endauth
    </nav>
</header>
