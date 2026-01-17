<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Strict-Transport-Security (HSTS) - hanya untuk HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy (CSP) - disesuaikan dengan kebutuhan
        // Determine Vite URL dynamically from the hot file or use default
        $viteUrl = '';
        $wsUrl = '';

        if (config('app.debug') && file_exists(public_path('hot'))) {
            $hotFileContent = file_get_contents(public_path('hot'));
            $viteServer = trim($hotFileContent);
            if (!empty($viteServer)) {
                $viteUrl = " {$viteServer}";
                // Extract WebSocket URL from Vite server URL
                $parsedUrl = parse_url($viteServer);
                if ($parsedUrl && isset($parsedUrl['host'])) {
                    $port = $parsedUrl['port'] ?? 5173;  // Default to 5173 if no port
                    $wsUrl = " ws://{$parsedUrl['host']}:{$port}";
                } else {
                    // Fallback if parsing fails completely
                    $wsUrl = " ws://localhost:5173";
                }
            } else {
                $viteUrl = ' http://localhost:5173';
                $wsUrl = ' ws://localhost:5173';
            }
        }

        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com{$viteUrl}; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com{$viteUrl}; ";
        $csp .= "font-src 'self' https://fonts.bunny.net https://cdn.tiny.cloud data:; ";
        $csp .= "img-src 'self' data: https:; ";
        $csp .= "connect-src 'self' https://*.tiny.cloud https://*.tinymce.com{$viteUrl}{$wsUrl}; ";
        $csp .= "worker-src 'self' blob:; ";
        $csp .= "frame-src 'self' https://www.google.com https://maps.google.com https://www.google.co.id https://maps.google.co.id https://www.googleapis.com https://www.youtube.com https://youtube.com https://*.youtube.com; ";
        $csp .= "frame-ancestors 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
