<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Services\LoggerService;

/**
 * Middleware untuk logging semua HTTP requests
 * Useful untuk debugging dan monitoring aktivitas user
 */
class LogRequests
{
    /**
     * Daftar routes yang tidak perlu di-log (untuk menghindari log spam)
     */
    protected array $except = [
        'livewire/*',
        '_debugbar/*',
        'telescope/*',
        'horizon/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip logging untuk routes tertentu
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $startTime = microtime(true);

        // Log incoming request
        $this->logRequest($request);

        // Process request
        $response = $next($request);

        // Log response
        $duration = (microtime(true) - $startTime) * 1000; // Convert to milliseconds
        $this->logResponse($request, $response, $duration);

        return $response;
    }

    /**
     * Check if request should be skipped from logging
     */
    protected function shouldSkip(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log incoming request
     */
    protected function logRequest(Request $request): void
    {
        // Only log in production with info level
        if (app()->environment('production')) {
            Log::channel('activity')->info('Incoming Request', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    /**
     * Log response with performance metrics
     */
    protected function logResponse(Request $request, Response $response, float $duration): void
    {
        $statusCode = $response->getStatusCode();

        // Log different levels based on status code
        if ($statusCode >= 500) {
            // Server errors - critical
            LoggerService::logSecurity('Server Error Response', 'error', [
                'status_code' => $statusCode,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration_ms' => round($duration, 2),
            ]);
        } elseif ($statusCode >= 400) {
            // Client errors - warning
            LoggerService::logSecurity('Client Error Response', 'warning', [
                'status_code' => $statusCode,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration_ms' => round($duration, 2),
            ]);
        } elseif ($duration > 2000) {
            // Slow responses (> 2 seconds) - warning
            Log::channel('database')->warning('Slow Response', [
                'status_code' => $statusCode,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration_ms' => round($duration, 2),
                'user_id' => auth()->id(),
            ]);
        }
    }
}
