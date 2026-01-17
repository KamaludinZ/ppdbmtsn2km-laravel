<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = config('app.maintenance_mode', false);

        // Allow access for specific IPs (for development/management)
        $allowedIps = config('app.maintenance_allowed_ips', []);

        // Check if maintenance mode is enabled and the IP is not in the allowed list
        if ($maintenanceMode && !in_array($request->ip(), $allowedIps)) {
            // Return maintenance view
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
