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
        // Professional exception reporting with context
        $exceptions->report(function (\Throwable $e) {
            // Log to appropriate channel based on severity
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                \App\Services\LoggerService::logSecurity('Authentication failed', 'warning', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                ]);
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                // Don't log validation errors to reduce noise
                return;
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                // Log 404s to security channel (could be attack reconnaissance)
                \App\Services\LoggerService::logSecurity('404 Not Found', 'info', [
                    'url' => request()->fullUrl(),
                ]);
            } elseif ($e instanceof \Illuminate\Database\QueryException) {
                // Log database errors
                \App\Services\LoggerService::log('database', 'error', 'Database query failed', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'sql' => $e->getSql() ?? null,
                ]);
            } elseif ($e instanceof \Error || $e instanceof \ErrorException) {
                // Critical PHP errors
                \App\Services\LoggerService::logCritical($e);
            }

            // Always log to default channel
            return true; // Continue with default reporting
        });

        // Custom error rendering for production
        $exceptions->render(function (\Throwable $e, $request) {
            // In production, never show detailed error pages
            if (app()->environment('production') && !config('app.debug')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                // Return appropriate error view
                if (view()->exists("errors.{$statusCode}")) {
                    return response()->view("errors.{$statusCode}", [], $statusCode);
                }

                return response()->view('errors.500', [], 500);
            }

            return null; // Use default rendering
        });
    })->create();
