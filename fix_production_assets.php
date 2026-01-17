<?php
/**
 * Production Asset Fix Script
 * This script helps fix the immediate issues on production
 */

echo "Fixing Production Assets...\n";

// 1. Take the application out of maintenance mode
echo "Taking application out of maintenance mode...\n";
if (file_exists('artisan')) {
    $output = shell_exec('php artisan up 2>&1');
    echo "Maintenance mode: " . trim($output) . "\n";
} else {
    echo "artisan file not found in current directory\n";
}

// 2. Check if we're in the right directory
$basePath = __DIR__; // Adjust this to your actual Laravel app directory
chdir($basePath);

// 3. Clear all caches
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

// 4. Check if Vite manifest exists, if not, we need to build assets
$viteManifest = 'public/build/manifest.json';
if (!file_exists($viteManifest)) {
    echo "Vite manifest not found. Creating fallback assets...\n";
    
    // Create a simple fallback CSS file
    $fallbackCss = "public/css/app.css";
    if (!file_exists(dirname($fallbackCss))) {
        mkdir(dirname($fallbackCss), 0755, true);
    }
    
    $cssContent = "
/* Fallback CSS for production */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Inter', system-ui, sans-serif;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

/* Add other critical styles here */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}
";
    
    file_put_contents($fallbackCss, $cssContent);
    
    // Create a simple fallback JS file
    $fallbackJs = "public/js/app.js";
    if (!file_exists(dirname($fallbackJs))) {
        mkdir(dirname($fallbackJs), 0755, true);
    }
    
    $jsContent = "
// Fallback JS for production
document.addEventListener('DOMContentLoaded', function() {
    console.log('PPDB Application Loaded - Production Mode');
    
    // Define the countdown function that was missing
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
    
    // Check for Alpine and Livewire
    if (window.Alpine) {
        console.log('Alpine.js detected');
    }
    if (window.Livewire) {
        console.log('Livewire detected');
    }
});
";
    
    file_put_contents($fallbackJs, $jsContent);
    
    echo "Fallback CSS and JS created.\n";
} else {
    echo "Vite manifest found. Assets appear to be built.\n";
}

// 5. Ensure storage directories exist and are writable
echo "Ensuring storage directories exist...\n";
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
}

// 6. Regenerate application key if not set
if (!getenv('APP_KEY')) {
    echo "Generating application key...\n";
    $output = shell_exec('php artisan key:generate 2>&1');
    echo "Key generation output: " . trim($output) . "\n";
}

// 7. Re-cache configurations
echo "Re-caching configurations...\n";
$cacheCommands = [
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
];

foreach ($cacheCommands as $command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    if ($output) {
        echo "Output: " . trim($output) . "\n";
    }
}

// 8. Optimize autoloader
echo "Optimizing autoloader...\n";
$output = shell_exec('composer dump-autoload --optimize 2>&1');
echo "Autoloader output: " . trim($output) . "\n";

echo "\nFix script completed!\n";
echo "====================\n";
echo "Next steps:\n";
echo "1. Verify your .env file has correct settings\n";
echo "2. Check that your web server points to the 'public' directory\n";
echo "3. Verify file permissions are correct\n";
echo "4. Test your application\n\n";

echo "If you still see 503 errors:\n";
echo "- Check if the application is still in maintenance mode\n";
echo "- Verify your web server configuration\n";
echo "- Check error logs in storage/logs/laravel.log\n";