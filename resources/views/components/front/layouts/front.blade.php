<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- JavaScript Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @vite('resources/css/app.css')
    @livewireStyles
    @stack('stylesheets')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


</head>
<!-- class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%" -->
<style>
    .notification-item.unread {
        background-color: #f0f9ff;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
    }

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

<body class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%">
    @extends('components.front.layouts.header')

    @yield('content')

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
    {{-- @vite('resources/js/detail.js') --}}
    @vite(['resources/js/app.js', 'resources/js/notifications.js'])
</body>

</html>
