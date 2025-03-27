<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    @vite('resources/css/app.css')
        @livewireStyles
            @stack('stylesheets')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
            <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


</head>
<!-- class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%" -->

<body class="bg-gradient-to-l from-[#224172] 0% to-[#6a3c3c] 100%">
@extends('components.front.layouts.header')

@yield('content')

    @livewireScripts
        @stack('scripts')
        @vite('resources/js/detail.js')
        @vite('resources/js/app.js')
</body>

</html>
