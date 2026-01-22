# CSP and Asset Fix Guide

## Issue: CSP Violations and Missing Functions

The application is experiencing:
1. CSP violations blocking external fonts and scripts
2. Missing countdown function for Alpine.js
3. External resource loading blocked by CSP

## Solution: Update CSP Policy and Add Local Assets

### 1. Update CSP Headers in Middleware

Create or update the CSP middleware:

**app/Http/Middleware/SecurityHeaders.php**:
```php
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

        // Add Content Security Policy header
        $cspPolicy = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com https://fonts.googleapis.com data:",
            "img-src 'self' data: blob: https: http:",
            "connect-src 'self' https://*.googleapis.com https://*.gstatic.com",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            "media-src 'self'",
            "object-src 'none'",
            "child-src 'self'",
            "frame-ancestors 'none'"
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $cspPolicy));
        
        // Additional security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        return $response;
    }
}
```

### 2. Add the countdown fix to your main JS file

Update **resources/js/app.js** to include the countdown function:
```javascript
import './bootstrap';
import '../css/app.css';

// Import Alpine.js
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

// Define the countdown function that was missing
window.countdown = function(endTime) {
    let endDate = typeof endTime === 'string' ? new Date(endTime).getTime() : endTime;
    
    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        
        init() {
            this.updateCountdown();
            this.interval = setInterval(() => {
                this.updateCountdown();
            }, 1000);
        },
        
        destroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        },
        
        updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;
            
            if (distance > 0) {
                this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
            } else {
                this.days = 0;
                this.hours = 0;
                this.minutes = 0;
                this.seconds = 0;
                
                if (this.interval) {
                    clearInterval(this.interval);
                }
            }
        }
    };
};

// Initialize Alpine.js
Alpine.start();

console.log('PPDB Application Loaded - Production Mode');
console.log('Alpine.js and countdown function initialized');
```

### 3. Update your layout to preload the countdown script

In your main layout file, add the countdown fix script:
```html
<!-- Add this to your head section -->
<script src="{{ asset('js/countdown-fix.js') }}"></script>
```

### 4. Alternative: Update CSP to allow required external resources

If you need to keep using external resources, update your CSP policy to allow them:

```php
"csp-policy" => [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com",
    "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com https://fonts.googleapis.com",
    "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com https://fonts.googleapis.com data:",
    "img-src 'self' data: blob: https: http:",
    "connect-src 'self' https://*.googleapis.com https://*.gstatic.com https://googleads.g.doubleclick.net",
    "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
    "media-src 'self'",
    "object-src 'none'",
    "child-src 'self'",
    "frame-ancestors 'none'"
]
```

### 5. Build the assets

Run these commands to rebuild your assets with the fixes:
```bash
npm run build
```

This solution addresses:
- ✅ Missing countdown function for Alpine.js
- ✅ CSP violations by updating policies
- ✅ Proper asset loading
- ✅ Security headers configuration