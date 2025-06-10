<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        });
    }

    public function boot()
    {
        //
    }
}
