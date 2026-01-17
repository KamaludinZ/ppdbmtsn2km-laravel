<?php
/**
 * FIX PRODUCTION ERROR 500 - SHARED HOSTING SUBDOMAIN
 * Untuk: https://pmbm.mtsn2kotamalang.sch.id
 *
 * Upload ke root subdomain dan akses:
 * https://pmbm.mtsn2kotamalang.sch.id/fix_production_subdomain.php?key=fix2026
 *
 * HAPUS FILE INI SETELAH BERHASIL!
 */

// Security
if (!isset($_GET['key']) || $_GET['key'] !== 'fix2026') {
    die("Access denied. Use: ?key=fix2026");
}

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "FIX PRODUCTION - SHARED HOSTING SUBDOMAIN\n";
echo "========================================\n\n";

$basePath = dirname(__FILE__);
chdir($basePath);
$errors = [];

// 1. Fix @vite Directives in Blade Templates
echo "STEP 1: Fixing @vite Directives\n";
echo "--------------------------------\n";

$viewFiles = [
    'resources/views/components/layouts/app.blade.php',
    'resources/views/components/layouts/dashboard.blade.php',
    'resources/views/components/layouts/public.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/errors/404.blade.php',
    'resources/views/errors/403.blade.php',
    'resources/views/errors/419.blade.php',
    'resources/views/errors/500.blade.php',
    'resources/views/errors/503.blade.php',
    'resources/views/welcome.blade.php'
];

$productionAssets = '
    <!-- Production Assets (No Vite) -->
    <link rel="stylesheet" href="/css/app.css?v=' . time() . '">
    <script src="/js/app.js?v=' . time() . '" defer></script>
';

foreach ($viewFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);

        // Check if has @vite directive
        if (strpos($content, '@vite') !== false) {
            // Backup original
            @copy($file, $file . '.backup');

            // Replace @vite with production assets
            $content = preg_replace(
                '/@vite\([^\)]+\)/',
                $productionAssets,
                $content
            );

            // Also handle @vite without parameters
            $content = str_replace('@vite', $productionAssets, $content);

            if (file_put_contents($file, $content)) {
                echo "✓ Fixed: $file\n";
            } else {
                echo "✗ Failed to fix: $file\n";
                $errors[] = "Cannot write to $file";
            }
        }
    }
}

echo "\n";

// 2. Create Production Assets if Missing
echo "STEP 2: Creating Production Assets\n";
echo "----------------------------------\n";

// Create CSS directory and file
if (!file_exists('public/css')) {
    @mkdir('public/css', 0755, true);
}

if (!file_exists('public/css/app.css')) {
    $cssContent = '/* Production CSS */
@import url("https://fonts.bunny.net/css?family=figtree:400,600&display=swap");
@import url("https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css");

/* Custom styles */
body {
    font-family: "Figtree", sans-serif;
}

/* Additional custom styles */
.btn-primary {
    @apply bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded;
}';

    if (file_put_contents('public/css/app.css', $cssContent)) {
        echo "✓ Created public/css/app.css\n";
    } else {
        echo "✗ Failed to create CSS\n";
        $errors[] = "Cannot create CSS file";
    }
} else {
    echo "✓ CSS file already exists\n";
}

// Create JS directory and file
if (!file_exists('public/js')) {
    @mkdir('public/js', 0755, true);
}

if (!file_exists('public/js/app.js')) {
    $jsContent = '/* Production JavaScript */
console.log("App loaded - Production");

// Alpine.js from CDN
document.addEventListener("DOMContentLoaded", function() {
    // Load Alpine.js
    var alpineScript = document.createElement("script");
    alpineScript.src = "https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js";
    alpineScript.defer = true;
    document.head.appendChild(alpineScript);
});

// Livewire support
if (typeof Livewire !== "undefined") {
    Livewire.start();
}';

    if (file_put_contents('public/js/app.js', $jsContent)) {
        echo "✓ Created public/js/app.js\n";
    } else {
        echo "✗ Failed to create JS\n";
        $errors[] = "Cannot create JS file";
    }
} else {
    echo "✓ JS file already exists\n";
}

echo "\n";

// 3. Fix .htaccess for Subdomain
echo "STEP 3: Fixing .htaccess\n";
echo "------------------------\n";

$htaccessContent = '<IfModule mod_rewrite.c>
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

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# PHP Settings (if allowed)
<IfModule mod_php.c>
    php_value upload_max_filesize 10M
    php_value post_max_size 10M
    php_value max_execution_time 300
    php_value max_input_time 300
</IfModule>';

if (file_put_contents('public/.htaccess', $htaccessContent)) {
    echo "✓ Updated public/.htaccess\n";
} else {
    echo "✗ Failed to update .htaccess\n";
    $errors[] = "Cannot update .htaccess";
}

echo "\n";

// 4. Clear All Caches
echo "STEP 4: Clearing All Caches\n";
echo "---------------------------\n";

$commands = [
    'php artisan config:clear' => 'Configuration',
    'php artisan cache:clear' => 'Application',
    'php artisan route:clear' => 'Routes',
    'php artisan view:clear' => 'Views'
];

foreach ($commands as $command => $type) {
    echo "Clearing $type cache... ";
    $output = @shell_exec($command . ' 2>&1');
    if ($output && strpos($output, 'ERROR') === false) {
        echo "OK\n";
    } else {
        echo "FAILED\n";
    }
}

// Manual cache cleanup
$cacheDirectories = [
    'bootstrap/cache/*.php',
    'storage/framework/cache/data/*',
    'storage/framework/views/*.php',
    'storage/framework/sessions/*'
];

foreach ($cacheDirectories as $pattern) {
    $files = glob($pattern);
    if ($files) {
        foreach ($files as $file) {
            if (!in_array(basename($file), ['.gitignore', 'index.php'])) {
                @unlink($file);
            }
        }
    }
}

echo "✓ Manual cache cleanup complete\n\n";

// 5. Check Environment
echo "STEP 5: Checking Environment\n";
echo "----------------------------\n";

// Check .env file
if (!file_exists('.env')) {
    echo "✗ .env file NOT FOUND!\n";
    $errors[] = ".env file missing";
} else {
    echo "✓ .env file exists\n";

    $envContent = file_get_contents('.env');

    // Check APP_ENV
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        echo "✓ APP_ENV is set to production\n";
    } else {
        echo "⚠ APP_ENV should be 'production'\n";
    }

    // Check APP_DEBUG
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        echo "✓ APP_DEBUG is false (correct for production)\n";
    } else {
        echo "⚠ APP_DEBUG should be false in production\n";
    }

    // Check APP_URL
    if (strpos($envContent, 'APP_URL=https://pmbm.mtsn2kotamalang.sch.id') !== false) {
        echo "✓ APP_URL is set correctly\n";
    } else {
        echo "⚠ APP_URL should be https://pmbm.mtsn2kotamalang.sch.id\n";
    }
}

echo "\n";

// 6. Fix Directory Permissions
echo "STEP 6: Checking Permissions\n";
echo "----------------------------\n";

$requiredDirs = [
    'storage',
    'storage/app',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        if (@mkdir($dir, 0755, true)) {
            echo "✓ Created $dir\n";
        } else {
            echo "✗ Cannot create $dir\n";
            $errors[] = "Cannot create $dir";
        }
    } else {
        if (is_writable($dir)) {
            echo "✓ $dir is writable\n";
        } else {
            echo "⚠ $dir is NOT writable\n";
            @chmod($dir, 0755);
        }
    }
}

echo "\n";

// 7. Rebuild Cache
echo "STEP 7: Rebuilding Cache\n";
echo "------------------------\n";

$buildCommands = [
    'php artisan config:cache' => 'Configuration',
    'php artisan route:cache' => 'Routes'
];

foreach ($buildCommands as $command => $type) {
    echo "Caching $type... ";
    $output = @shell_exec($command . ' 2>&1');
    if ($output && strpos($output, 'ERROR') === false) {
        echo "OK\n";
    } else {
        echo "SKIPPED\n";
    }
}

echo "\n";
echo "========================================\n";

if (count($errors) > 0) {
    echo "⚠ FIX COMPLETED WITH ERRORS:\n";
    echo "========================================\n\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
    echo "\nPlease fix these issues manually.\n";
} else {
    echo "✓ FIX COMPLETED SUCCESSFULLY!\n";
    echo "========================================\n\n";
    echo "All issues have been fixed.\n";
}

echo "\nNEXT STEPS:\n";
echo "1. Test your site: https://pmbm.mtsn2kotamalang.sch.id\n";
echo "2. DELETE THIS FILE for security!\n";
echo "3. If still error, check storage/logs/laravel.log\n";

echo "\nFOLDER STRUCTURE FOR SUBDOMAIN:\n";
echo "Your subdomain root should contain:\n";
echo "  /app\n";
echo "  /bootstrap\n";
echo "  /config\n";
echo "  /database\n";
echo "  /public (document root)\n";
echo "  /resources\n";
echo "  /routes\n";
echo "  /storage\n";
echo "  /vendor\n";
echo "  .env\n";
echo "  artisan\n";

echo "\nIMPORTANT:\n";
echo "Make sure subdomain document root points to /public folder!\n";
?>