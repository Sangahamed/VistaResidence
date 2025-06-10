<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\TwilioServiceProvider::class,
    Barryvdh\DomPDF\Facade::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    Intervention\Image\Facades\Image::class,
    Intervention\Image\ImageServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,

];
