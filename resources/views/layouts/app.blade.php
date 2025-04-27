<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->

    
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('messenger*') ? 'active' : '' }}"
                                    href="{{ route('messenger') }}">

                                    <i class="fas fa-comments me-2"></i>Messages
                                    @if (Auth::check() && Auth::user()->unreadMessagesCount() > 0)
                                        <span class="badge bg-danger">{{ Auth::user()->unreadMessagesCount() }}</span>
                                    @endif
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link position-relative" href="{{ route('properties.index') }}">
                                    <i class="fas fa-exchange-alt me-1"></i>Comparaison
                                    @if (session()->has('comparison_list') && count(session('comparison_list')) > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ count(session('comparison_list')) }}
                                            <span class="visually-hidden">propriétés à comparer</span>
                                        </span>
                                    @endif
                                </a>
                            </li>

                            <!-- Liens à ajouter dans la navigation -->
                            <li>
                                <a href="{{ route('maps.index') }}"
                                    class="text-gray-700 hover:text-primary {{ request()->routeIs('maps.*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-map-marker-alt mr-2"></i> Carte
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('auctions.index') }}"
                                    class="text-gray-700 hover:text-primary {{ request()->routeIs('auctions.*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-gavel mr-2"></i> Enchères
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mortgage.calculator') }}"
                                    class="text-gray-700 hover:text-primary {{ request()->routeIs('mortgage.*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-calculator mr-2"></i> Calculateur
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('recommendations.index') }}"
                                    class="text-gray-700 hover:text-primary {{ request()->routeIs('recommendations.*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-lightbulb mr-2"></i> Recommandations
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('notifications.index') }}"
                                    class="text-gray-700 hover:text-primary {{ request()->routeIs('notifications.*') ? 'text-primary font-medium' : '' }}">
                                    <i class="fas fa-bell mr-2"></i> Notifications
                                    @if (auth()->check() && auth()->user()->unreadNotifications()->count() > 0)
                                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-red-500 text-white">
                                            {{ auth()->user()->unreadNotifications()->count() }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @auth
                                <div class="min-h-screen flex flex-col">
                                    <!-- Navbar -->
                                    <nav class="bg-white border-b border-gray-200">
                                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                            <div class="flex justify-between h-16">
                                                <div class="flex">
                                                    <div class="flex-shrink-0 flex items-center">
                                                        <a href="{{ route('dashboard') }}">
                                                            <img class="h-8 w-auto" src="{{ asset('images/logo.svg') }}"
                                                                alt="{{ config('app.name') }}">
                                                        </a>
                                                    </div>
                                                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                                        <a href="{{ route('dashboard') }}"
                                                            class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                                            Dashboard
                                                        </a>
                                                        @can('view-companies')
                                                            <a href="{{ route('companies.index') }}"
                                                                class="{{ request()->routeIs('companies.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                                                Entreprises
                                                            </a>
                                                        @endcan
                                                        @can('view-roles')
                                                            <a href="{{ route('roles.index') }}"
                                                                class="{{ request()->routeIs('roles.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                                                Rôles
                                                            </a>
                                                        @endcan
                                                        @can('manage-modules')
                                                            <a href="{{ route('modules.index') }}"
                                                                class="{{ request()->routeIs('modules.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                                                Modules
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </div>
                                                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                                                    <!-- Notifications -->
                                                    <div class="ml-3 relative">
                                                        <button type="button"
                                                            class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            <span class="sr-only">Voir les notifications</span>
                                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Profile dropdown -->
                                                    <div class="ml-3 relative" x-data="{ open: false }">
                                                        <div>
                                                            <button @click="open = !open" type="button"
                                                                class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                id="user-menu-button" aria-expanded="false"
                                                                aria-haspopup="true">
                                                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                                                <img class="h-8 w-8 rounded-full"
                                                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                                                                    alt="{{ auth()->user()->name }}">
                                                            </button>
                                                        </div>
                                                        <div x-show="open" @click.away="open = false"
                                                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                            role="menu" aria-orientation="vertical"
                                                            aria-labelledby="user-menu-button" tabindex="-1">
                                                            {{-- <a href="{{ route('profile.edit') }}"
                                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                role="menuitem">Votre profil</a> --}}
                                                            <form method="POST" action="{{ route('logout') }}">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                    role="menuitem">Se déconnecter</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="-mr-2 flex items-center sm:hidden">
                                                    <!-- Mobile menu button -->
                                                    <button type="button"
                                                        class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        aria-controls="mobile-menu" aria-expanded="false">
                                                        <span class="sr-only">Ouvrir le menu principal</span>
                                                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                                        </svg>
                                                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mobile menu, show/hide based on menu state. -->
                                        <div class="sm:hidden" id="mobile-menu">
                                            <div class="pt-2 pb-3 space-y-1">
                                                <a href="{{ route('dashboard') }}"
                                                    class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                                    Dashboard
                                                </a>
                                                @can('view-companies')
                                                    <a href="{{ route('companies.index') }}"
                                                        class="{{ request()->routeIs('companies.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                                        Entreprises
                                                    </a>
                                                @endcan
                                                @can('view-roles')
                                                    <a href="{{ route('roles.index') }}"
                                                        class="{{ request()->routeIs('roles.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                                        Rôles
                                                    </a>
                                                @endcan
                                                @can('manage-modules')
                                                    <a href="{{ route('modules.index') }}"
                                                        class="{{ request()->routeIs('modules.*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                                                        Modules
                                                    </a>
                                                @endcan
                                            </div>
                                            <div class="pt-4 pb-3 border-t border-gray-200">
                                                <div class="flex items-center px-4">
                                                    <div class="flex-shrink-0">
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF"
                                                            alt="{{ auth()->user()->name }}">
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-base font-medium text-gray-800">
                                                            {{ auth()->user()->name }}</div>
                                                        <div class="text-sm font-medium text-gray-500">
                                                            {{ auth()->user()->email }}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 space-y-1">
                                                    {{-- <a href="{{ route('profile.edit') }}"
                                                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                                        Votre profil
                                                    </a> --}}
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                                            Se déconnecter
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </nav>

                                    {{-- <!-- Page Content -->
                                    <main class="flex-1">
                                        @if (isset($header))
                                            <header class="bg-white shadow">
                                                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                                    {{ $header }}
                                                </div>
                                            </header>
                                        @endif

                                        <!-- Flash Messages -->
                                        @if (session('success'))
                                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                                                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-green-400"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-green-700">
                                                                {{ session('success') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (session('error'))
                                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                                                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-red-400"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" aria-hidden="true">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-red-700">
                                                                {{ session('error') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="py-6">
                                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                                @yield('content')
                                            </div>
                                        </div>
                                    </main> --}}

                                    <!-- Footer -->
                                    <footer class="bg-white border-t border-gray-200">
                                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                            <div class="flex justify-between items-center">
                                                <div class="text-sm text-gray-500">
                                                    &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Version 1.0.0
                                                </div>
                                            </div>
                                        </div>
                                    </footer>
                                </div>

                            @endauth



                        @endguest
                    </ul>
                </div>
            </div>
        </nav>



        <main class="py-4">
            @yield('content')

        </main>

    </div>
</body>

</html>
