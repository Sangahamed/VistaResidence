<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
    'Image' => Intervention\Image\Facades\Image::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    'PDF' => Barryvdh\DomPDF\Facade::class,

];
