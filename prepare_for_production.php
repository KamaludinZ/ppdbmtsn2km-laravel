<?php
/**
 * PREPARE SOURCE CODE FOR PRODUCTION UPLOAD
 * Run this script locally before uploading to production
 *
 * Usage: php prepare_for_production.php
 */

echo "========================================\n";
echo "PREPARING SOURCE CODE FOR PRODUCTION\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. Fix all @vite directives in blade templates
echo "STEP 1: Fixing @vite Directives in Templates\n";
echo "---------------------------------------------\n";

$viewPaths = [
    'resources/views/components/layouts/app.blade.php',
    'resources/views/components/layouts/dashboard.blade.php',
    'resources/views/components/layouts/public.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/errors/403.blade.php',
    'resources/views/errors/404.blade.php',
    'resources/views/errors/419.blade.php',
    'resources/views/errors/500.blade.php',
    'resources/views/errors/503.blade.php',
    'resources/views/welcome.blade.php'
];

// Replacement for @vite
$productionAssets = <<<'BLADE'
    <!-- Production Assets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    @livewireStyles
BLADE;

$livewireScripts = <<<'BLADE'
    @livewireScripts
    <script src="{{ asset('js/livewire.js') }}" defer></script>
BLADE;

foreach ($viewPaths as $viewPath) {
    if (file_exists($viewPath)) {
        $content = file_get_contents($viewPath);
        $originalContent = $content;

        // Check if has @vite
        if (strpos($content, '@vite') !== false) {
            // Backup original
            copy($viewPath, $viewPath . '.backup-vite');

            // Replace @vite directive
            $content = preg_replace(
                '/@vite\s*\([^\)]*\)/',
                $productionAssets,
                $content
            );

            // Ensure @livewireScripts is before </body>
            if (strpos($content, '@livewireScripts') === false && strpos($content, '</body>') !== false) {
                $content = str_replace('</body>', $livewireScripts . "\n</body>", $content);
            }

            if (file_put_contents($viewPath, $content)) {
                $success[] = "Fixed: $viewPath";
                echo "✓ Fixed: $viewPath\n";
            } else {
                $errors[] = "Failed to fix: $viewPath";
                echo "✗ Failed: $viewPath\n";
            }
        } else {
            echo "  Skip: $viewPath (no @vite found)\n";
        }
    } else {
        $warnings[] = "File not found: $viewPath";
    }
}

echo "\n";

// 2. Generate Production Assets
echo "STEP 2: Generating Production Assets\n";
echo "------------------------------------\n";

// Create directories
@mkdir('public/css', 0755, true);
@mkdir('public/js', 0755, true);

// Generate CSS
$cssContent = file_get_contents('resources/css/app.css');
if (empty($cssContent)) {
    $cssContent = <<<'CSS'
/* PPDB MTsN 2 Kota Malang - Production CSS */
@import url("https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap");
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");

:root {
    --primary: #10b981;
    --secondary: #3b82f6;
}

body {
    font-family: 'Figtree', system-ui, sans-serif;
    background: #f9fafb;
}

/* Livewire Loading */
.livewire-loading {
    opacity: 0.5;
}

[wire\:loading] {
    display: block;
}

[wire\:loading].hidden {
    display: none !important;
}

[wire\:offline] {
    display: none;
}

[wire\:offline].offline {
    display: block;
}

/* Custom Components */
.btn {
    @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
}

.btn-primary {
    @apply bg-green-600 text-white hover:bg-green-700;
}

.btn-secondary {
    @apply bg-gray-600 text-white hover:bg-gray-700;
}

.btn-danger {
    @apply bg-red-600 text-white hover:bg-red-700;
}

.card {
    @apply bg-white rounded-lg shadow-sm p-6;
}

.form-control {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent;
}

.form-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}

.alert {
    @apply p-4 rounded-md mb-4;
}

.alert-success {
    @apply bg-green-50 text-green-800 border border-green-200;
}

.alert-danger {
    @apply bg-red-50 text-red-800 border border-red-200;
}

.alert-warning {
    @apply bg-yellow-50 text-yellow-800 border border-yellow-200;
}

.alert-info {
    @apply bg-blue-50 text-blue-800 border border-blue-200;
}

/* Table */
.table {
    @apply w-full divide-y divide-gray-200;
}

.table thead {
    @apply bg-gray-50;
}

.table th {
    @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.table td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
}

/* Animations */
@keyframes spin {
    to { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Print Styles */
@media print {
    .no-print { display: none !important; }
}
CSS;
}

file_put_contents('public/css/app.css', $cssContent);
echo "✓ Generated: public/css/app.css\n";
$success[] = "Generated CSS";

// Generate JavaScript
$jsContent = <<<'JS'
/* PPDB MTsN 2 Kota Malang - Production JavaScript */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('PPDB Application Loaded - Production Mode');

    // CSRF Token Setup
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.content;

        // Add to fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            if (args[1] && args[1].method && args[1].method.toUpperCase() !== 'GET') {
                args[1].headers = args[1].headers || {};
                args[1].headers['X-CSRF-TOKEN'] = window.csrfToken;
                args[1].headers['X-Requested-With'] = 'XMLHttpRequest';
            }
            return originalFetch.apply(this, args);
        };
    }

    // Initialize Alpine.js if present
    if (typeof Alpine !== 'undefined') {
        // Already initialized by Alpine CDN
        console.log('Alpine.js detected');
    }

    // Initialize Livewire hooks if present
    if (typeof Livewire !== 'undefined') {
        console.log('Livewire detected');

        Livewire.hook('message.sent', () => {
            // Show loading state
        });

        Livewire.hook('message.received', () => {
            // Hide loading state
        });

        Livewire.hook('message.failed', () => {
            console.error('Livewire request failed');
        });
    }

    // Auto-dismiss alerts
    document.querySelectorAll('.alert[data-dismiss]').forEach(alert => {
        const timeout = parseInt(alert.dataset.dismiss) || 5000;
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, timeout);
    });

    // Form validation
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;

            // Check required fields
            form.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi');
            }
        });
    });

    // File upload preview
    document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.getElementById(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        preview.textContent = this.files[0].name;
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Global functions
    window.confirmDelete = function(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus data ini?');
    };

    window.printPage = function() {
        window.print();
    };
});

// Handle Alpine.js loading
if (typeof Alpine === 'undefined') {
    // Load Alpine from CDN if not already loaded
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
    script.defer = true;
    document.head.appendChild(script);
}
JS;

file_put_contents('public/js/app.js', $jsContent);
echo "✓ Generated: public/js/app.js\n";
$success[] = "Generated JavaScript";

// Copy Livewire assets if available
if (file_exists('vendor/livewire/livewire/dist/livewire.js')) {
    copy('vendor/livewire/livewire/dist/livewire.js', 'public/js/livewire.js');
    echo "✓ Copied: public/js/livewire.js\n";
    $success[] = "Copied Livewire JS";
}

echo "\n";

// 3. Create .env.production template
echo "STEP 3: Creating .env.production Template\n";
echo "-----------------------------------------\n";

$envProduction = <<<'ENV'
APP_NAME="PPDB MTs N 2 Kota Malang"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://pmbm.mtsn2kotamalang.sch.id
APP_TIMEZONE=Asia/Jakarta

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Cache & Session
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Redis (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="noreply@mtsn2kotamalang.sch.id"
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp Gateway (optional)
WHATSAPP_GATEWAY_URL=
WHATSAPP_GATEWAY_TOKEN=
WHATSAPP_GATEWAY_DEVICE_ID=

# AWS (optional - for file storage)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Pusher (optional - for real-time)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Production Specific
FORCE_HTTPS=true
DEBUGBAR_ENABLED=false
ENV;

file_put_contents('.env.production', $envProduction);
echo "✓ Created: .env.production\n";
$success[] = "Created .env.production template";

echo "\n";

// 4. Create .htaccess for public folder
echo "STEP 4: Creating .htaccess for Public Folder\n";
echo "--------------------------------------------\n";

$htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache Control
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/x-javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-shockwave-flash "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresDefault "access plus 2 days"
</IfModule>

# PHP Settings (if allowed by hosting)
<IfModule mod_php.c>
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
    php_value memory_limit 256M
</IfModule>
HTACCESS;

file_put_contents('public/.htaccess', $htaccess);
echo "✓ Created: public/.htaccess\n";
$success[] = "Created .htaccess";

echo "\n";

// 5. Clear all caches locally
echo "STEP 5: Clearing All Caches\n";
echo "---------------------------\n";

$commands = [
    'php artisan config:clear',
    'php artisan cache:clear',
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    exec($command . ' 2>&1', $output, $return);
    if ($return === 0) {
        echo "✓ Success\n";
    } else {
        echo "✗ Failed\n";
        $warnings[] = "Failed to run: $command";
    }
}

echo "\n";

// 6. Remove development files
echo "STEP 6: Removing Development Files\n";
echo "-----------------------------------\n";

$removeFiles = [
    '.env.local',
    '.env.development',
    'hot',
    'storage/debugbar',
    'public/hot'
];

foreach ($removeFiles as $file) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            // Remove directory
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($file, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            rmdir($file);
            echo "✓ Removed directory: $file\n";
        } else {
            unlink($file);
            echo "✓ Removed file: $file\n";
        }
        $success[] = "Removed: $file";
    }
}

// Clear compiled views
$viewsPath = 'storage/framework/views';
if (is_dir($viewsPath)) {
    $files = glob($viewsPath . '/*.php');
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            unlink($file);
        }
    }
    echo "✓ Cleared compiled views\n";
}

// Clear sessions
$sessionsPath = 'storage/framework/sessions';
if (is_dir($sessionsPath)) {
    $files = glob($sessionsPath . '/*');
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            unlink($file);
        }
    }
    echo "✓ Cleared sessions\n";
}

// Clear cache
$cachePath = 'storage/framework/cache/data';
if (is_dir($cachePath)) {
    $files = glob($cachePath . '/*');
    foreach ($files as $file) {
        if (basename($file) !== '.gitignore') {
            if (is_dir($file)) {
                array_map('unlink', glob($file . '/*'));
                rmdir($file);
            } else {
                unlink($file);
            }
        }
    }
    echo "✓ Cleared cache\n";
}

echo "\n";

// Summary
echo "========================================\n";
echo "PREPARATION COMPLETE\n";
echo "========================================\n\n";

if (count($errors) > 0) {
    echo "❌ ERRORS:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "   - $warning\n";
    }
    echo "\n";
}

echo "✅ COMPLETED TASKS:\n";
foreach ($success as $item) {
    echo "   - $item\n";
}

echo "\n";
echo "📋 NEXT STEPS:\n";
echo "1. Review .env.production and fill in your database credentials\n";
echo "2. Run: composer install --no-dev --optimize-autoloader\n";
echo "3. Create a backup of current production (if exists)\n";
echo "4. Upload all files to production server\n";
echo "5. Set proper permissions on server\n";
echo "6. Run migrations on production\n";
echo "\n";

echo "📁 FILES TO UPLOAD:\n";
echo "   - All folders except: node_modules, .git, tests\n";
echo "   - All root files except: .env, *.log, *.lock\n";
echo "   - Make sure to upload: public/css/app.css and public/js/app.js\n";
echo "\n";

echo "⚙️  PRODUCTION CHECKLIST:\n";
echo "   [ ] Database configured in .env\n";
echo "   [ ] APP_ENV=production\n";
echo "   [ ] APP_DEBUG=false\n";
echo "   [ ] APP_KEY generated\n";
echo "   [ ] Storage folder writable (755)\n";
echo "   [ ] Bootstrap/cache folder writable (755)\n";
echo "   [ ] Document root points to /public\n";
echo "   [ ] SSL certificate active\n";
echo "\n";

// Create deployment info file
$deploymentInfo = [
    'prepared_at' => date('Y-m-d H:i:s'),
    'php_version' => phpversion(),
    'laravel_version' => '11.x',
    'changes' => [
        'vite_removed' => true,
        'static_assets' => true,
        'production_ready' => true
    ],
    'files_modified' => count($success),
    'warnings' => count($warnings),
    'errors' => count($errors)
];

file_put_contents('deployment_info.json', json_encode($deploymentInfo, JSON_PRETTY_PRINT));
echo "✓ Created deployment_info.json\n";

echo "\nSource code is now ready for production deployment!\n";
?>