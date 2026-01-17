<?php
/**
 * Production Server Fix Script for Laravel 500 Errors
 * Upload this file to your production server and run it
 *
 * Run via browser: https://pmbm.mtsn2kotamalang.sch.id/production_fix.php
 * Or via CLI: php production_fix.php
 */

// Security check - delete this file after use!
$secret = 'fix500error2026'; // Change this for security
if (!isset($_GET['secret']) || $_GET['secret'] !== $secret) {
    if (php_sapi_name() !== 'cli') {
        die("Access denied. Append ?secret=$secret to URL");
    }
}

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "Production Server 500 Error Fix Script\n";
echo "========================================\n\n";

// Change to the application directory
$basePath = dirname(__FILE__);
chdir($basePath);

echo "Working directory: " . getcwd() . "\n\n";

// 1. Check if artisan exists
if (!file_exists('artisan')) {
    echo "ERROR: artisan file not found!\n";
    echo "Please ensure this script is in your Laravel root directory.\n";
    exit(1);
}

echo "✓ Laravel application detected\n\n";

// 2. Clear all Laravel caches (safe for production)
echo "Step 1: Clearing all caches...\n";
echo str_repeat('-', 40) . "\n";

$clearCommands = [
    'config:clear' => 'Configuration cache',
    'cache:clear' => 'Application cache',
    'route:clear' => 'Route cache',
    'view:clear' => 'Compiled views',
    'event:clear' => 'Event cache'
];

foreach ($clearCommands as $command => $description) {
    echo "Clearing $description... ";
    $output = shell_exec("php artisan $command 2>&1");
    if (strpos($output, 'ERROR') !== false || strpos($output, 'Exception') !== false) {
        echo "FAILED\n";
        echo "  Error: " . trim($output) . "\n";
    } else {
        echo "OK\n";
    }
}

echo "\n";

// 3. Check and create required directories
echo "Step 2: Checking directory structure...\n";
echo str_repeat('-', 40) . "\n";

$requiredDirs = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        echo "Creating $dir... ";
        if (@mkdir($dir, 0755, true)) {
            echo "OK\n";
        } else {
            echo "FAILED\n";
        }
    } else {
        echo "✓ $dir exists\n";
    }
}

echo "\n";

// 4. Check file permissions
echo "Step 3: Checking permissions...\n";
echo str_repeat('-', 40) . "\n";

$writableDirs = [
    'storage',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

$hasPermissionIssues = false;
foreach ($writableDirs as $dir) {
    if (is_dir($dir)) {
        if (!is_writable($dir)) {
            echo "⚠ $dir is NOT writable - needs chmod 755 or 775\n";
            $hasPermissionIssues = true;
            // Try to fix permission
            @chmod($dir, 0755);
        } else {
            echo "✓ $dir is writable\n";
        }
    }
}

if ($hasPermissionIssues) {
    echo "\n⚠ Permission issues detected. Please run these commands on your server:\n";
    echo "  chmod -R 755 storage\n";
    echo "  chmod -R 755 bootstrap/cache\n";
}

echo "\n";

// 5. Check .env file
echo "Step 4: Checking configuration...\n";
echo str_repeat('-', 40) . "\n";

if (!file_exists('.env')) {
    echo "ERROR: .env file not found!\n";
    echo "Please create .env file from .env.example and configure it.\n";
} else {
    echo "✓ .env file exists\n";

    // Check critical .env variables
    $envContent = file_get_contents('.env');
    $criticalVars = ['APP_KEY', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];

    foreach ($criticalVars as $var) {
        if (strpos($envContent, "$var=") === false) {
            echo "⚠ Missing $var in .env file\n";
        } else {
            echo "✓ $var is configured\n";
        }
    }
}

echo "\n";

// 6. Test database connection
echo "Step 5: Testing database connection...\n";
echo str_repeat('-', 40) . "\n";

$dbTestOutput = shell_exec('php artisan tinker --execute="try { \\DB::connection()->getPdo(); echo \'OK\'; } catch (\\Exception \\$e) { echo \\$e->getMessage(); }" 2>&1');

if (strpos($dbTestOutput, 'OK') !== false) {
    echo "✓ Database connection successful\n";
} else {
    echo "⚠ Database connection failed\n";
    echo "  Please check your database configuration in .env\n";
    if (strpos($dbTestOutput, 'could not find driver') !== false) {
        echo "  ⚠ PHP PDO driver not installed for your database\n";
    }
}

echo "\n";

// 7. Rebuild caches for production
echo "Step 6: Rebuilding production caches...\n";
echo str_repeat('-', 40) . "\n";

$buildCommands = [
    'config:cache' => 'Configuration cache',
    'route:cache' => 'Route cache',
    'view:cache' => 'View cache'
];

foreach ($buildCommands as $command => $description) {
    echo "Building $description... ";
    $output = shell_exec("php artisan $command 2>&1");

    if (strpos($output, 'ERROR') !== false || strpos($output, 'Exception') !== false) {
        echo "FAILED\n";
        echo "  Error: " . trim($output) . "\n";

        // If route:cache fails, it might be due to closures in routes
        if ($command === 'route:cache') {
            echo "  Note: Route caching requires all routes to use controller actions, not closures\n";
        }
    } else {
        echo "OK\n";
    }
}

echo "\n";

// 8. Check for common issues
echo "Step 7: Checking for common issues...\n";
echo str_repeat('-', 40) . "\n";

// Check PHP version
$phpVersion = phpversion();
echo "PHP Version: $phpVersion ";
if (version_compare($phpVersion, '8.1.0', '>=')) {
    echo "✓\n";
} else {
    echo "⚠ (Laravel 11 requires PHP 8.1+)\n";
}

// Check memory limit
$memoryLimit = ini_get('memory_limit');
echo "Memory Limit: $memoryLimit ";
$memoryBytes = $this->convertToBytes($memoryLimit);
if ($memoryBytes >= 256 * 1024 * 1024) {
    echo "✓\n";
} else {
    echo "⚠ (Consider increasing to at least 256M)\n";
}

// Check max execution time
$maxExecTime = ini_get('max_execution_time');
echo "Max Execution Time: $maxExecTime seconds\n";

// Check important extensions
$requiredExtensions = [
    'mbstring',
    'openssl',
    'pdo',
    'tokenizer',
    'xml',
    'ctype',
    'json',
    'bcmath',
    'fileinfo'
];

echo "\nPHP Extensions:\n";
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "  ✓ $ext\n";
    } else {
        echo "  ✗ $ext (missing)\n";
    }
}

echo "\n";
echo "========================================\n";
echo "Fix Script Complete!\n";
echo "========================================\n\n";

echo "Summary:\n";
echo "1. All caches have been cleared\n";
echo "2. Directory structure verified\n";
echo "3. Permissions checked (fix manually if needed)\n";
echo "4. Configuration verified\n";
echo "5. Database connection tested\n";
echo "6. Production caches rebuilt\n\n";

echo "Next Steps:\n";
echo "1. Test your application at: https://pmbm.mtsn2kotamalang.sch.id\n";
echo "2. Check error logs at: storage/logs/laravel.log\n";
echo "3. DELETE THIS SCRIPT after use for security!\n\n";

echo "If issues persist:\n";
echo "- Check web server error logs (Apache/Nginx)\n";
echo "- Verify file ownership (should match web server user)\n";
echo "- Ensure all vendor packages are installed: composer install --no-dev\n";
echo "- Check .htaccess file in public directory\n";

// Helper function to convert memory limit to bytes
function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int)$value;

    switch($last) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }

    return $value;
}