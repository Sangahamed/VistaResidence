<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Leaflet MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Custom Styles -->
    @vite('resources/css/app.css')
    @livewireStyles
    @stack('stylesheets')

    <!-- Third-party Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
</head>

<body class="bg-gradient-to-l from-[#224172] to-[#6a3c3c]">

    @extends('components.front.layouts.header')

    @yield('content')

    <!-- Scripts -->
    @livewireScripts
    @stack('scripts')

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- Vite Scripts -->
    @vite(['resources/js/app.js', 'resources/js/notifications.js'])

    <!-- Authenticated User Script -->
    @auth
        <script>
            window.authUserId = {{ auth()->id() }};
        </script>
    @endauth

</body>

</html>
