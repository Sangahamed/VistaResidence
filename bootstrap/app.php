<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\EnsureTwoFactorIsVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Security.header' => App\Http\Middleware\SecurityHeaders::class,
            // 'admin.auth' => AdminAuthenticate::class,
            // 'admin.guest' => AdminRedirect::class,
            'PreventBackHistory' => PreventBackHistory::class,
            'EnsureTwoFactorIsVerified' => EnsureTwoFactorIsVerified::class ,


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
