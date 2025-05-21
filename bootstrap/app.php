<?php

use Illuminate\Http\Client\Request;
use Illuminate\Foundation\Application;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;



return Application::configure(basePath: dirname(__DIR__))
->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'throttle:api', // Utilisation du limiteur de débit
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

    
    // return Application::configure(basePath: dirname(__DIR__))
    //     ->withRouting(
    //         web: __DIR__.'/../routes/web.php',
    //         api: __DIR__.'/../routes/api.php',
    //         commands: __DIR__.'/../routes/console.php',
    //         health: '/up',
    //     )
    //     ->withMiddleware(function (Middleware $middleware) {
    //         $middleware->group('api', [
    //             \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    //             \Illuminate\Routing\Middleware\SubstituteBindings::class,
    //             'throttle:api', // Utilisation du limiteur de débit

    //         $middleware->alias([
    //             'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
    //             'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    //             'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    //         ]);

    //         ]);


    //     })
    //     ->withExceptions(function (Exceptions $exceptions) {
    //         //
    //     })


    //     ->create();
