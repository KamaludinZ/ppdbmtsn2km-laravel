# PRODUCTION DEPLOYMENT GUIDE
## Files to Update & Upload to Production

### 📁 FILES TO UPDATE ON PRODUCTION SERVER:

#### 1. **Database Migrations (RUN ON PRODUCTION SERVER)**
Run these commands on your production server to fix database issues:

```bash
# Run migrations to fix enum values
php artisan migrate

# If you have the new migrations, they should add 'menunggu' to the enum
# If not, you may need to run the following SQL manually:
# ALTER TYPE pendaftar_status_pendaftaran_final ADD VALUE IF NOT EXISTS 'menunggu';
```

#### 2. **Configuration Files to Update**

**A. Update your .env file with correct database settings:**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id

DB_CONNECTION=pgsql
DB_HOST=your_db_host
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

**B. Clear and rebuild configurations:**
```bash
# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Regenerate application key if not set
php artisan key:generate --force

# Re-cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### 3. **Security Headers Middleware (UPDATE THIS FILE)**

**File: `app/Http/Middleware/SecurityHeaders.php`**
Replace the entire file content with:

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

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Strict-Transport-Security (HSTS) - only for HTTPS
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy (CSP) - Cloudflare-compatible
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
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tiny.cloud https://*.tinymce.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com https://*.cloudflareinsights.com https://challenges.cloudflare.com{$viteUrl}; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdn.tiny.cloud https://cdnjs.cloudflare.com https://fonts.googleapis.com{$viteUrl}; ";
        $csp .= "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com https://fonts.googleapis.com data:; ";
        $csp .= "img-src 'self' data: https:; ";
        $csp .= "connect-src 'self' https://*.googleapis.com https://*.gstatic.com https://*.cloudflareinsights.com{$viteUrl}{$wsUrl}; ";
        $csp .= "worker-src 'self' blob:; ";
        $csp .= "frame-src 'self' https://www.google.com https://maps.google.com https://www.google.co.id https://maps.google.co.id https://www.googleapis.com https://www.youtube.com https://youtube.com https://*.youtube.com https://challenges.cloudflare.com; ";
        $csp .= "frame-ancestors 'self';";  // Changed from 'none' to 'self' for Cloudflare compatibility

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
```

#### 4. **Main JavaScript File (ADD COUNTDOWN FUNCTION)**

**File: `resources/js/app.js`**
Make sure this file contains the countdown function. Update it to include:

```javascript
import './bootstrap';

// Alpine.js Countdown function - ADD THIS SECTION
window.countdown = (expiryDate) => {
    return {
        expiry: new Date(expiryDate).getTime(),
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        init() {
            this.update();
            setInterval(() => {
                this.update();
            }, 1000);
        },
        update() {
            const now = new Date().getTime();
            const distance = this.expiry - now;

            if (distance < 0) {
                this.days = '00';
                this.hours = '00';
                this.minutes = '00';
                this.seconds = '00';
                return;
            }

            this.days = Math.floor(distance / (1000 * 60 * 60 * 24)).toString().padStart(2, '0');
            this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
            this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
            this.seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, '0');
        }
    }
}

// Other existing code...
```

#### 5. **Build Assets for Production**

After updating the JavaScript file, build the assets:

```bash
# If you have access to npm on production
npm run build

# OR if you're building locally and uploading
# Build locally and upload the entire public/build/ directory
```

#### 6. **Take Application Out of Maintenance Mode**

```bash
# Take application out of maintenance mode
php artisan up
```

#### 7. **Ensure Proper File Permissions**

```bash
# Make sure storage and bootstrap/cache directories are writable
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 📤 FILES TO UPLOAD TO PRODUCTION:

1. **Updated `app/Http/Middleware/SecurityHeaders.php`** - Fixed CSP for Cloudflare
2. **Updated `resources/js/app.js`** - Added countdown function
3. **Built assets** - `public/build/` directory (after running `npm run build`)
4. **Any new migration files** - If you created the enum fix migrations

### 🚀 FINAL PRODUCTION COMMANDS:

Run these commands in order on your production server:

```bash
# 1. Take out of maintenance mode
php artisan up

# 2. Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan event:clear
php artisan optimize:clear

# 3. Run migrations to fix database issues
php artisan migrate --force

# 4. Regenerate application key if needed
php artisan key:generate --force

# 5. Re-cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Optimize autoloader
composer dump-autoload --optimize

# 7. Test the application
php artisan config:get app.name
```

### 🧪 VERIFICATION CHECKS:

After deployment, verify:

- [ ] Homepage loads without 503 errors
- [ ] Login works for all user types
- [ ] Dashboard loads for admin/panitia/pendaftar
- [ ] No CSP violations in browser console
- [ ] Countdown timers work properly
- [ ] All JavaScript functions properly
- [ ] Database connections stable
- [ ] All routes accessible

### ☁️ CLOUDFLARE PAGE RULES:

Set up these Page Rules in your Cloudflare Dashboard:

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

This guide provides all the files and commands needed to fix your production issues!