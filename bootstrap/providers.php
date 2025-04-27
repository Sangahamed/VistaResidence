<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
    'Image' => Intervention\Image\Facades\Image::class,
];
