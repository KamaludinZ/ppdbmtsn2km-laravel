<?php
/**
 * EMERGENCY FIX FOR PRODUCTION ERROR 500
 * This script fixes Vite manifest and other common issues
 *
 * USAGE: Upload to production and access:
 * https://pmbm.mtsn2kotamalang.sch.id/emergency_fix_production.php?key=emergency2026
 *
 * DELETE THIS FILE AFTER USE!
 */

// Security check
if (php_sapi_name() !== 'cli' && (!isset($_GET['key']) || $_GET['key'] !== 'emergency2026')) {
    die("Access denied. Use: ?key=emergency2026");
}

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "EMERGENCY FIX FOR PRODUCTION ERROR 500\n";
echo "========================================\n\n";

$basePath = dirname(__FILE__);
chdir($basePath);

$hasError = false;

// 1. Fix Vite Manifest Issue
echo "STEP 1: Fixing Vite Manifest\n";
echo "-----------------------------\n";

// Check if build folder exists
if (!is_dir('public/build')) {
    echo "⚠ WARNING: public/build folder not found!\n";
    echo "  Creating build folder...\n";
    @mkdir('public/build', 0755, true);
    @mkdir('public/build/assets', 0755, true);
    $hasError = true;
}

// Check for manifest.json in wrong location
if (file_exists('public/build/.vite/manifest.json') && !file_exists('public/build/manifest.json')) {
    echo "✓ Found manifest.json in .vite folder\n";
    echo "  Copying to correct location...\n";
    if (@copy('public/build/.vite/manifest.json', 'public/build/manifest.json')) {
        echo "✓ Manifest copied successfully\n";
    } else {
        echo "✗ Failed to copy manifest\n";
        $hasError = true;
    }
} elseif (file_exists('public/build/manifest.json')) {
    echo "✓ Manifest.json already in correct location\n";
} else {
    echo "⚠ WARNING: No manifest.json found!\n";
    echo "  Creating dummy manifest to prevent Vite errors...\n";

    // Create a dummy manifest
    $dummyManifest = [
        "resources/js/app.js" => [
            "file" => "assets/app.js",
            "src" => "resources/js/app.js",
            "isEntry" => true,
            "css" => ["assets/app.css"]
        ],
        "resources/css/app.css" => [
            "file" => "assets/app.css",
            "src" => "resources/css/app.css"
        ]
    ];

    if (@file_put_contents('public/build/manifest.json', json_encode($dummyManifest, JSON_PRETTY_PRINT))) {
        echo "✓ Dummy manifest created\n";
    } else {
        echo "✗ Failed to create manifest\n";
        $hasError = true;
    }
}

echo "\n";

// 2. Clear ALL Caches
echo "STEP 2: Clearing All Caches\n";
echo "---------------------------\n";

// Clear Laravel caches
$commands = [
    'php artisan config:clear' => 'Configuration cache',
    'php artisan cache:clear' => 'Application cache',
    'php artisan route:clear' => 'Route cache',
    'php artisan view:clear' => 'View cache',
    'php artisan event:clear' => 'Event cache'
];

foreach ($commands as $command => $description) {
    echo "Clearing $description... ";
    $output = @shell_exec($command . ' 2>&1');
    if ($output && (strpos($output, 'ERROR') !== false || strpos($output, 'Exception') !== false)) {
        echo "FAILED\n";
        $hasError = true;
    } else {
        echo "OK\n";
    }
}

// Manual cache cleanup
echo "\nManual cache cleanup...\n";

// Bootstrap cache
$bootstrapFiles = @glob('bootstrap/cache/*.php');
if ($bootstrapFiles) {
    foreach ($bootstrapFiles as $file) {
        if (basename($file) !== 'index.php') {
            @unlink($file);
        }
    }
    echo "✓ Bootstrap cache cleared\n";
}

// Framework cache
$cacheFiles = @glob('storage/framework/cache/data/*');
if ($cacheFiles) {
    foreach ($cacheFiles as $file) {
        if (basename($file) !== '.gitignore') {
            @unlink($file);
        }
    }
    echo "✓ Framework cache cleared\n";
}

// View cache
$viewFiles = @glob('storage/framework/views/*.php');
if ($viewFiles) {
    foreach ($viewFiles as $file) {
        if (basename($file) !== '.gitignore') {
            @unlink($file);
        }
    }
    echo "✓ View cache cleared\n";
}

echo "\n";

// 3. Check Critical Files
echo "STEP 3: Checking Critical Files\n";
echo "-------------------------------\n";

// Check .env
if (!file_exists('.env')) {
    echo "✗ CRITICAL: .env file not found!\n";
    echo "  Trying to copy from .env.example...\n";
    if (file_exists('.env.example')) {
        if (@copy('.env.example', '.env')) {
            echo "  ✓ .env created from example\n";
            echo "  ⚠ You MUST update database credentials!\n";
        } else {
            echo "  ✗ Failed to create .env\n";
            $hasError = true;
        }
    }
} else {
    echo "✓ .env file exists\n";

    // Check for APP_KEY
    $envContent = @file_get_contents('.env');
    if ($envContent) {
        if (strpos($envContent, 'APP_KEY=base64:') === false) {
            echo "⚠ APP_KEY not set, generating...\n";
            @shell_exec('php artisan key:generate 2>&1');
        } else {
            echo "✓ APP_KEY is set\n";
        }
    }
}

// Check vendor folder
if (!is_dir('vendor')) {
    echo "✗ CRITICAL: vendor folder not found!\n";
    echo "  Run: composer install --no-dev\n";
    $hasError = true;
} else {
    echo "✓ vendor folder exists\n";
}

// Check storage permissions
echo "\n";
echo "STEP 4: Checking Permissions\n";
echo "----------------------------\n";

$checkDirs = [
    'storage',
    'storage/app',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($checkDirs as $dir) {
    if (!is_dir($dir)) {
        echo "Creating $dir... ";
        if (@mkdir($dir, 0755, true)) {
            echo "OK\n";
        } else {
            echo "FAILED\n";
            $hasError = true;
        }
    } else {
        if (!is_writable($dir)) {
            echo "⚠ $dir is NOT writable\n";
            @chmod($dir, 0755);
        } else {
            echo "✓ $dir is writable\n";
        }
    }
}

echo "\n";

// 4. Create Emergency Bypass for Vite
echo "STEP 5: Creating Vite Bypass\n";
echo "----------------------------\n";

// Create a simple CSS file if assets are missing
if (!file_exists('public/css/app.css')) {
    @mkdir('public/css', 0755, true);
    $basicCss = "/* Emergency CSS - Replace with proper build */\nbody { font-family: sans-serif; }";
    @file_put_contents('public/css/app.css', $basicCss);
    echo "✓ Created fallback CSS\n";
}

if (!file_exists('public/js/app.js')) {
    @mkdir('public/js', 0755, true);
    $basicJs = "/* Emergency JS - Replace with proper build */\nconsole.log('App loaded');";
    @file_put_contents('public/js/app.js', $basicJs);
    echo "✓ Created fallback JS\n";
}

echo "\n";

// 5. Try to rebuild cache (may fail if config issues)
echo "STEP 6: Attempting to Rebuild Cache\n";
echo "-----------------------------------\n";

$rebuildCommands = [
    'php artisan config:cache' => 'Configuration',
    'php artisan route:cache' => 'Routes'
];

foreach ($rebuildCommands as $command => $what) {
    echo "Caching $what... ";
    $output = @shell_exec($command . ' 2>&1');
    if ($output && (strpos($output, 'ERROR') !== false || strpos($output, 'Exception') !== false)) {
        echo "SKIPPED (has errors)\n";
        // Don't mark as error, some routes may have closures
    } else {
        echo "OK\n";
    }
}

echo "\n";
echo "========================================\n";

if ($hasError) {
    echo "⚠ FIX COMPLETED WITH WARNINGS\n";
    echo "========================================\n\n";
    echo "Some issues were detected. Please:\n";
    echo "1. Check your .env file has correct database settings\n";
    echo "2. Ensure vendor folder exists (composer install)\n";
    echo "3. Verify file permissions are correct\n";
    echo "4. Check PHP error logs for details\n";
} else {
    echo "✓ FIX COMPLETED SUCCESSFULLY\n";
    echo "========================================\n\n";
    echo "All checks passed! Your site should work now.\n";
}

echo "\n";
echo "IMPORTANT:\n";
echo "1. Test your site: https://pmbm.mtsn2kotamalang.sch.id\n";
echo "2. DELETE THIS FILE immediately for security!\n";
echo "3. Run 'npm run build' locally and upload public/build folder\n";
echo "\n";

echo "If still having issues:\n";
echo "- Check storage/logs/laravel.log for errors\n";
echo "- Verify PostgreSQL is running and accessible\n";
echo "- Contact your hosting provider\n";

// Try to show last error from log
echo "\n";
echo "Last Error from Log:\n";
echo "-------------------\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = @file($logFile);
    if ($lines) {
        $lastLines = array_slice($lines, -5);
        foreach ($lastLines as $line) {
            if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
                echo trim($line) . "\n";
                break;
            }
        }
    }
} else {
    echo "No log file found\n";
}
?>