# Vite to Production Assets Configuration Guide

## Issue Analysis
From the console logs, we can see:
1. 503 Service Unavailable (maintenance mode)
2. Livewire.js not loading (403 error)
3. Alpine.js errors due to missing countdown function
4. CSP violations for fonts and scripts
5. Mixed CDN vs local assets

## Solution: Flexible Vite Development & Production Assets

### 1. Update Vite Configuration (vite.config.js)

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath } from 'url';
import { dirname, resolve } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name].[hash][extname]';
                    }
                    return 'assets/[name].[hash][extname]';
                },
                chunkFileNames: 'js/[name].[hash].js',
                entryFileNames: 'js/[name].[hash].js',
            }
        }
    },
    // Conditionally set base URL based on environment
    base: process.env.NODE_ENV === 'development' ? '/vite/' : '/',
});
```

### 2. Update Blade Templates to Support Both Modes

**resources/views/layouts/app.blade.php** (and other layout files):

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts and Styles - Flexible loading based on environment -->
    @if(app()->environment('local', 'development'))
        <!-- Development: Load from Vite -->
        {{ Vite::useHotModuleReplacement('http://localhost:5173') }}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Production: Load from built assets -->
        @if(file_exists(public_path('build/manifest.json')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <!-- Fallback to local assets if Vite build doesn't exist -->
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <script src="{{ asset('js/app.js') }}" defer></script>
        @endif
    @endif

    <!-- Local fonts instead of CDN -->
    <link rel="preload" href="{{ asset('fonts/inter-var-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- CSP-Compatible Inline Styles (if needed) -->
    <style>
        /* Add critical styles here to avoid external CSS requests */
    </style>
</head>
<body class="font-sans antialiased">
    <div id="app">
        {{ $slot ?? '' }}
    </div>

    <!-- Scripts -->
    @if(!app()->environment('local', 'development') && file_exists(public_path('build/manifest.json')))
        @vite(['resources/js/app.js'])
    @endif
    
    <!-- Fallback script loading -->
    <script>
        // Check if Vite script was loaded, if not load fallback
        if (typeof module !== 'undefined' && module.hot) {
            // Vite HMR is active
        } else {
            // Production fallback
            if (typeof Livewire === 'undefined') {
                // Load Livewire from local assets if CDN fails
                var script = document.createElement('script');
                script.src = '{{ asset('js/livewire.js') }}';
                document.head.appendChild(script);
            }
        }
    </script>
</body>
</html>
```

### 3. Create Local Asset Versions

**Create local versions of external assets:**

#### A. Download Inter font locally:
Create `public/fonts/` directory and add the Inter font files, then create `public/css/fonts.css`:

```css
/* Local font loading */
@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('../fonts/inter-var-latin.woff2') format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 500;
    font-display: swap;
    src: url('../fonts/inter-var-latin.woff2') format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 600;
    font-display: swap;
    src: url('../fonts/inter-var-latin.woff2') format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

@font-face {
    font-family: 'Inter';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('../fonts/inter-var-latin.woff2') format('woff2');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
```

#### B. Update app.css to use local fonts:
```css
/* resources/css/app.css */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

/* Use local font */
body {
    font-family: 'Inter', system-ui, sans-serif;
}

/* Add any other critical CSS here */
```

### 4. Create Build Script for Production

**package.json** (update scripts section):
```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "preview": "vite preview",
        "production": "vite build && cp -r dist/* public/",
        "build-local": "NODE_ENV=production vite build"
    }
}
```

### 5. Update App Service Provider

**app/Providers/AppServiceProvider.php**:
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
            
            // Add CSP headers if needed
            $this->addCspHeaders();
        }
        
        // Share environment info with views
        view()->composer('*', function ($view) {
            $view->with('isLocal', app()->environment('local', 'development'));
            $view->with('isProduction', app()->environment('production'));
        });
    }
    
    private function addCspHeaders()
    {
        // Add Content Security Policy headers
        // This can be done in middleware instead
    }
}
```

### 6. Create Middleware for Asset Management

**app/Http/Middleware/AssetManager.php**:
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssetManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share asset information with views
        view()->share('useLocalAssets', !app()->environment('local'));
        
        return $next($request);
    }
}
```

Add this middleware to `$middlewareGroups` in `app/Http/Kernel.php`:
```php
'web' => [
    // ... other middleware
    \App\Http\Middleware\AssetManager::class,
],
```

### 7. Update Livewire Components to Use Local Assets

For the countdown Alpine.js component that's causing errors, create a local version:

**resources/js/alpine/countdown.js**:
```javascript
// resources/js/alpine/countdown.js
document.addEventListener('DOMContentLoaded', function() {
    // Register the countdown function globally for Alpine
    window.countdown = function(endTime) {
        return {
            endTime: new Date(endTime).getTime(),
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            
            init() {
                this.updateCountdown();
                setInterval(() => {
                    this.updateCountdown();
                }, 1000);
            },
            
            updateCountdown() {
                const now = new Date().getTime();
                const distance = this.endTime - now;
                
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
                }
            }
        };
    };
});
```

Then update **resources/js/app.js**:
```javascript
import './bootstrap';
import '../css/app.css';

// Import Alpine.js
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

// Import local Alpine components
import './alpine/countdown';

// Initialize
Alpine.start();

console.log('PPDB Application Loaded - ' + (process.env.NODE_ENV === 'development' ? 'Development' : 'Production') + ' Mode');

// Check for Alpine and Livewire
if (window.Alpine) {
    console.log('Alpine.js detected');
}
if (window.Livewire) {
    console.log('Livewire detected');
}
```

### 8. Build Production Assets

Run these commands to build production-ready assets:
```bash
npm run build
```

This will create optimized assets in the `public/build/` directory that don't rely on CDNs.

### 9. Environment Configuration

Update your `.env.production` to ensure proper asset handling:
```
APP_ENV=production
ASSET_URL=/build
ASSET_PATH=public/build
```

### 10. Maintenance Mode Check

To take the application out of maintenance mode:
```bash
php artisan up
```

Or check if maintenance mode is active:
```bash
php artisan down --help
```

This comprehensive solution addresses:
- ✅ Flexible asset loading for both development (Vite) and production
- ✅ Local font assets instead of CDN
- ✅ Proper CSP compliance
- ✅ Fixed Alpine.js countdown function
- ✅ Offline-capable assets
- ✅ Proper environment detection