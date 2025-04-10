<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\EnsureTwoFactorVerifiedMiddleware;
use App\Http\Middleware\EnsureIsProprietaire;
use App\Http\Middleware\CheckAccountType;
use \App\Http\Middleware\CheckUserRole;

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
            'prevent.back.history' => PreventBackHistory::class,
            'twofactor.verified' => EnsureTwoFactorVerifiedMiddleware::class,
            'proprietaire.verified' => EnsureIsProprietaire::class,
            'account.type' => CheckAccountType::class,
            'role.check' => CheckUserRole::class,


        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
