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
use Livewire\Livewire;
use App\Services\PropertySearchService;
use App\Services\RecommendationService;
use App\Services\GeoLocationService;
use App\Services\EnhancedGeoLocationService;
use App\Services\PropertyViewService;
use App\Livewire\ToggleFavorite;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Enregistrer les services
        $this->app->singleton(GeoLocationService::class, function ($app) {
            return new GeoLocationService();
        });

        $this->app->singleton(EnhancedGeoLocationService::class, function ($app) {
            return new EnhancedGeoLocationService();
        });

        $this->app->singleton(PropertySearchService::class, function ($app) {
            return new PropertySearchService($app->make(GeoLocationService::class));
        });

        $this->app->singleton(RecommendationService::class, function ($app) {
            return new RecommendationService($app->make(EnhancedGeoLocationService::class));
        });

        $this->app->singleton(PropertyViewService::class, function ($app) {
            return new PropertyViewService();
        });
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
        Livewire::component('toggle-favorite', ToggleFavorite::class);
    }
}
