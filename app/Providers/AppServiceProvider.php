<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Bepsvpt\SecureHeaders\SecureHeaders;
use App\Models\Property;
use App\Models\PropertyVisit;
use App\Observers\PropertyObserver;
use App\Observers\VisitObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('components.nav-link', 'nav-link');
        Blade::component('components.responsive-nav-link', 'responsive-nav-link');
        Blade::component('components.dropdown', 'dropdown');
        Blade::component('components.dropdown-link', 'dropdown-link');
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // SecureHeaders::fromArray(config('secure-headers'))->send();

        Property::observe(PropertyObserver::class);
        PropertyVisit::observe(VisitObserver::class);
    }
}
