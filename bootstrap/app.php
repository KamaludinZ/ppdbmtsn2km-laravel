<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        App\Providers\EventServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Maintenance mode middleware - check before other middleware
        $middleware->append(MaintenanceMode::class);

        // Security headers untuk semua request
        $middleware->append(SecurityHeaders::class);

        // Rate limiting
        $middleware->throttleApi();

        // Web middleware dengan rate limiting
        $middleware->web(append: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Alias untuk rate limiting dan role checking
        $middleware->alias([
            'role' => CheckRole::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withEvents()
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
