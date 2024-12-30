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
    <style>
    /* Animation pour un effet d'apparition en fondu */
    .fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    <blade keyframes|%20fadeIn%20%7B>from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
    }

    /* Animation pour un effet de rebond */
    .bounce {
        animation: bounce 1s infinite;
    }

    <blade keyframes|%20bounce%20%7B>0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
    }

</style>
</head>

<body>
    @include('components.front.layouts.header')

    @yield('content')

    @livewireScripts
	    @stack('scripts')
</body>

</html>
