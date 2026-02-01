<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Professional Logging Service
 *
 * Provides structured logging with context for better debugging and monitoring
 */
class LoggerService
{
    /**
     * Log authentication events
     */
    public static function logAuth(string $event, array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ], $context);

        Log::channel('auth')->info($event, $context);
    }

    /**
     * Log security events
     */
    public static function logSecurity(string $event, string $level = 'warning', array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'timestamp' => now()->toIso8601String(),
        ], $context);

        Log::channel('security')->log($level, $event, $context);
    }

    /**
     * Log user activity
     */
    public static function logActivity(string $action, string $model, $modelId = null, array $changes = []): void
    {
        $context = [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'user_role' => Auth::user()?->role,
            'action' => $action, // create, update, delete, view
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ];

        Log::channel('activity')->info("User {$action} {$model}", $context);
    }

    /**
     * Log payment/transaction events
     */
    public static function logPayment(string $event, array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'pendaftar_id' => Auth::user()?->pendaftar?->id,
            'ip_address' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ], $context);

        Log::channel('payment')->info($event, $context);
    }

    /**
     * Log database queries (for debugging slow queries)
     */
    public static function logQuery(string $query, float $time, array $bindings = []): void
    {
        // Only log slow queries (> 1 second)
        if ($time > 1000) {
            $context = [
                'query' => $query,
                'time_ms' => $time,
                'bindings' => $bindings,
                'url' => request()->fullUrl(),
                'timestamp' => now()->toIso8601String(),
            ];

            Log::channel('database')->warning('Slow query detected', $context);
        }
    }

    /**
     * Log critical errors with full context
     */
    public static function logCritical(\Throwable $exception, array $additionalContext = []): void
    {
        $context = array_merge([
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ], $additionalContext);

        Log::channel('critical')->critical($exception->getMessage(), $context);
    }

    /**
     * Log with custom channel
     */
    public static function log(string $channel, string $level, string $message, array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ], $context);

        Log::channel($channel)->log($level, $message, $context);
    }

    /**
     * Format exception for logging
     */
    public static function formatException(\Throwable $exception): array
    {
        return [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'trace' => collect($exception->getTrace())->take(5)->toArray(), // Only first 5 frames
        ];
    }

    /**
     * Format request for logging
     */
    public static function formatRequest(Request $request): array
    {
        return [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'params' => $request->except(['password', 'password_confirmation', '_token']),
        ];
    }
}
