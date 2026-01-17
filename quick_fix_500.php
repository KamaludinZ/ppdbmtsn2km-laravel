<?php
/**
 * Quick Fix Script for Laravel 500 Errors
 * Run this script on your production server to fix common issues
 */

echo "Starting Laravel 500 Error Quick Fix...\n";

// Change to the application directory
$basePath = dirname(__FILE__); // Update this to your actual Laravel app directory
chdir($basePath);

echo "Current directory: " . getcwd() . "\n";

// 1. Check if artisan exists
if (!file_exists('artisan')) {
    echo "ERROR: artisan file not found in current directory!\n";
    echo "Please navigate to your Laravel application root directory.\n";
    exit(1);
}

echo "✓ Artisan file found\n";

// 2. Clear all caches
echo "Clearing all caches...\n";
$commands = [
    'php artisan config:clear',
    'php artisan cache:clear', 
    'php artisan route:clear',
    'php artisan view:clear',
    'php artisan event:clear',
    'php artisan optimize:clear'
];

foreach ($commands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Output: " . trim($output) . "\n";
    }
}

// 3. Check and create storage directories
echo "Checking storage directories...\n";
$storageDirs = [
    'storage',
    'storage/logs',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        echo "Creating directory: $dir\n";
        mkdir($dir, 0755, true);
    }
    
    if (!is_writable($dir)) {
        echo "Setting permissions for: $dir\n";
        chmod($dir, 0775);
    }
    
    echo "✓ Directory $dir exists and is writable\n";
}

// 4. Check if .env file exists
if (!file_exists('.env')) {
    echo "WARNING: .env file not found in current directory!\n";
    echo "You need to create a .env file with your database and application settings.\n";
} else {
    echo "✓ .env file found\n";
}

// 5. Regenerate application key if not set
$appKey = getenv('APP_KEY') ?: shell_exec('php artisan tinker --execute="echo env(\'APP_KEY\');" 2>/dev/null');
if (empty(trim($appKey))) {
    echo "Generating application key...\n";
    $output = shell_exec('php artisan key:generate 2>&1');
    echo "Key generation output: " . trim($output) . "\n";
} else {
    echo "✓ Application key already exists\n";
}

// 6. Try to cache configurations
echo "Caching configurations...\n";
$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
];

foreach ($cacheCommands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output && strpos($output, 'ailed') !== false) {
        echo "Warning - possible error: " . trim($output) . "\n";
    } else {
        echo "Success: " . trim($output) . "\n";
    }
}

// 7. Optimize autoloader
echo "Optimizing autoloader...\n";
$output = shell_exec('composer dump-autoload --optimize 2>&1');
echo "Autoloader output: " . trim($output) . "\n";

// 8. Check if storage:link exists (for public storage)
if (file_exists('public')) {
    echo "Creating storage link (if needed)...\n";
    $output = shell_exec('php artisan storage:link 2>&1');
    if ($output) {
        echo "Storage link output: " . trim($output) . "\n";
    }
}

echo "\nQuick Fix Complete!\n";
echo "====================\n";
echo "Next steps:\n";
echo "1. Check your .env file has correct database settings\n";
echo "2. Verify your web server has permission to read/write necessary files\n";
echo "3. Check error logs in storage/logs/laravel.log\n";
echo "4. Test your application\n\n";

echo "If you still have issues:\n";
echo "- Check your web server error logs\n";
echo "- Verify PHP version is compatible (8.1+)\n";
echo "- Ensure PostgreSQL is running and accessible\n";
echo "- Confirm all required PHP extensions are installed\n";