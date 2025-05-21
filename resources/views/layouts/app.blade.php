<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'VistaImmob') }} - @yield('title', 'Gestion Immobilière')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    @stack('styles')
    <style>
        .notification-enter-active,
        .notification-leave-active {
            transition: all 0.3s ease;
        }

        .notification-enter-from,
        .notification-leave-to {
            opacity: 0;
            transform: translateY(-10px);
        }
    </style>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            900: '#111827',
                            800: '#1F2937',
                            700: '#374151',
                            600: '#4B5563',
                            500: '#6B7280',
                            400: '#9CA3AF',
                            300: '#D1D5DB',
                            200: '#E5E7EB',
                            100: '#F3F4F6',
                        },
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }}. Tous
                            droits réservés.</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="" class="text-sm text-gray-500 hover:text-gray-700">Politique de
                            confidentialité</a>
                        <a href="" class="text-sm text-gray-500 hover:text-gray-700">Conditions d'utilisation</a>
                        <a href="" class="text-sm text-gray-500 hover:text-gray-700">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Écoute les nouvelles notifications (via Echo)
            @auth
            window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                .notification((notification) => {
                    // Rafraîchit le composant Livewire
                    Livewire.emit('notificationReceived');

                    // Affiche un toast
                    showNotificationToast({
                        title: notification.title,
                        message: notification.message,
                        icon: notification.type,
                        url: notification.url
                    });
                });
        @endauth

        function showNotificationToast(data) {
            // Implémentez votre système de toast (ex: Toastify, Alpine.js)
            console.log("New notification:", data);
        }
        });
    </script>
</body>

</html>
