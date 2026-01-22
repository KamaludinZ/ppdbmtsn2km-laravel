# Cloudflare Integration Guide for Laravel Application

## Issues with Cloudflare CDN

When using Cloudflare as a CDN/cache layer, you may experience:

1. **CSP Violations** - Cloudflare injects scripts that violate CSP
2. **Asset Caching Issues** - Static assets cached by Cloudflare
3. **Security Header Conflicts** - Cloudflare adds its own headers
4. **JavaScript Injection** - Cloudflare may inject analytics/scripts
5. **Cache Bypass Issues** - Dynamic content getting cached

## Cloudflare-Specific Solutions

### 1. Update CSP Policy for Cloudflare

Cloudflare injects its own scripts and analytics. Update your CSP policy to account for this:

```php
// In your SecurityHeaders middleware
$cspPolicy = [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://*.cloudflareinsights.com https://challenges.cloudflare.com",
    "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com https://fonts.googleapis.com",
    "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com https://fonts.googleapis.com data:",
    "img-src 'self' data: blob: https: http:",
    "connect-src 'self' https://*.googleapis.com https://*.gstatic.com https://*.cloudflareinsights.com",
    "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://challenges.cloudflare.com",
    "media-src 'self'",
    "object-src 'none'",
    "child-src 'self'",
    "frame-ancestors 'none'"
];
```

### 2. Cloudflare Page Rules Configuration

Set up these Page Rules in Cloudflare Dashboard:

```
URL: */api/*
Setting: Bypass Cache

URL: */livewire/*
Setting: Bypass Cache

URL: */login*
Setting: Bypass Cache

URL: */admin/*
Setting: Bypass Cache

URL: */panitia/*
Setting: Bypass Cache

URL: */pendaftar/*
Setting: Bypass Cache

URL: *.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)
Setting: Cache Level: Cache Everything
```

### 3. Cloudflare Security Headers Adjustment

Since Cloudflare adds its own security headers, adjust your Laravel headers:

```php
// In your SecurityHeaders middleware
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);

    // Only add headers if not already added by Cloudflare
    if (!$response->headers->has('cf-ray')) {
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
    }
    
    // Add CSP with Cloudflare allowances
    $cspPolicy = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://*.cloudflareinsights.com https://challenges.cloudflare.com",
        "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com https://fonts.googleapis.com",
        "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com https://fonts.googleapis.com data:",
        "img-src 'self' data: blob: https: http:",
        "connect-src 'self' https://*.googleapis.com https://*.gstatic.com https://*.cloudflareinsights.com",
        "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://challenges.cloudflare.com",
        "media-src 'self'",
        "object-src 'none'",
        "child-src 'self'",
        "frame-ancestors 'self'"  // Changed from 'none' to 'self' for Cloudflare compatibility
    ];

    $response->headers->set('Content-Security-Policy', implode('; ', $cspPolicy));
    
    return $response;
}
```

### 4. Cloudflare Workers (Optional) for Advanced Control

If you need more control, create a Cloudflare Worker to handle specific routing:

```javascript
// Cloudflare Worker script
addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request))
})

async function handleRequest(request) {
  const url = new URL(request.url)
  
  // Bypass cache for dynamic content
  if (url.pathname.includes('/api/') || 
      url.pathname.includes('/livewire/') ||
      url.pathname.includes('/login') ||
      url.pathname.includes('/admin/') ||
      url.pathname.includes('/panitia/') ||
      url.pathname.includes('/pendaftar/')) {
    const response = await fetch(request)
    return new Response(response.body, {
      headers: {
        ...response.headers,
        'Cache-Control': 'no-store, no-cache, must-revalidate, proxy-revalidate',
        'Pragma': 'no-cache',
        'Expires': '0'
      }
    })
  }
  
  return fetch(request)
}
```

### 5. Cloudflare Analytics Integration

For Cloudflare analytics, you can allow it in your CSP:

```php
// Add to your CSP policy
"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://*.cloudflareinsights.com",
```

### 6. Cache Busting for Assets

To ensure Cloudflare serves fresh assets after deployment:

```php
// In your app service provider or view composers
view()->composer('*', function ($view) {
    $version = md5(config('app.version', '1.0.0') . time()); // Or use git hash
    $view->with('cache_version', $version);
});
```

Then in your blade templates:
```html
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ $cache_version }}">
<script src="{{ asset('js/app.js') }}?v={{ $cache_version }}"></script>
```

### 7. Purge Cloudflare Cache After Deployment

After deployment, purge Cloudflare cache using API or dashboard:

```bash
# Using curl to purge cache
curl -X POST "https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/purge_cache" \
     -H "X-Auth-Email: YOUR_EMAIL" \
     -H "X-Auth-Key: YOUR_API_KEY" \
     -H "Content-Type: application/json" \
     --data '{"purge_everything":true}'
```

### 8. Laravel Configuration for Cloudflare

Update your `.env` for Cloudflare compatibility:

```
# Trust Cloudflare proxies
TRUST_PROXIES=*

# Cloudflare specific settings
APP_URL=https://pmbm.mtsn2kotamalang.sch.id
ASSET_URL=https://pmbm.mtsn2kotamalang.sch.id
```

### 9. Cloudflare-Specific Middleware

Create a Cloudflare-compatible middleware:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloudflareCompatibility
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Add headers that work well with Cloudflare
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow', false);
        
        // Ensure dynamic content isn't cached by Cloudflare
        if ($this->shouldNotBeCached($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
        
        return $response;
    }
    
    private function shouldNotBeCached(Request $request): bool
    {
        $path = $request->path();
        
        return str_contains($path, '/api/') ||
               str_contains($path, '/livewire/') ||
               str_contains($path, '/login') ||
               str_contains($path, '/admin/') ||
               str_contains($path, '/panitia/') ||
               str_contains($path, '/pendaftar/') ||
               $request->isMethod('post') ||
               $request->isMethod('put') ||
               $request->isMethod('patch') ||
               $request->isMethod('delete');
    }
}
```

Add this middleware to your `app/Http/Kernel.php`:
```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\CloudflareCompatibility::class,
    ],
];
```

This comprehensive guide addresses Cloudflare-specific issues while maintaining security and performance.