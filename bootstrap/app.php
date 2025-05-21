<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\EnsureTwoFactorVerifiedMiddleware;
use App\Http\Middleware\CacheApiResponse;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Security.header' => App\Http\Middleware\SecurityHeaders::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'webhook.verify' => \App\Http\Middleware\VerifyWebhookSignature::class,
            'accescompany' => \App\Http\Middleware\CompanyAccess::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'prevent.back.history' => PreventBackHistory::class,
            'twofactor.verified' => EnsureTwoFactorVerifiedMiddleware::class,
            'cache.api' => CacheApiResponse::class,


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
