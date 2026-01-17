<?php
/**
 * Simple Cache Clear Script for Production
 * Upload to production server and access via browser
 * DELETE THIS FILE AFTER USE!
 */

// Simple security check
if (!isset($_GET['key']) || $_GET['key'] !== 'clear2026') {
    die("Access denied. Use: ?key=clear2026");
}

echo "<pre>";
echo "=================================\n";
echo "Laravel Cache Clear Script\n";
echo "=================================\n\n";

// Change to app directory
chdir(dirname(__FILE__));

// Function to run command safely
function runCommand($command) {
    echo "Running: $command\n";
    $output = shell_exec($command . ' 2>&1');
    echo $output ? $output : "Command executed\n";
    echo "\n";
}

// Clear all caches
echo "Step 1: Clearing Caches\n";
echo "------------------------\n";
runCommand('php artisan config:clear');
runCommand('php artisan cache:clear');
runCommand('php artisan route:clear');
runCommand('php artisan view:clear');
runCommand('php artisan optimize:clear');

// Delete cache files manually
echo "Step 2: Manual Cache Cleanup\n";
echo "----------------------------\n";

// Bootstrap cache
$bootstrapFiles = glob('bootstrap/cache/*.php');
foreach ($bootstrapFiles as $file) {
    if (basename($file) !== 'index.php') {
        if (@unlink($file)) {
            echo "Deleted: $file\n";
        }
    }
}

// View cache
$viewFiles = glob('storage/framework/views/*.php');
foreach ($viewFiles as $file) {
    if (basename($file) !== '.gitignore') {
        @unlink($file);
    }
}
echo "View cache cleared\n";

// Session files (be careful)
$sessionFiles = glob('storage/framework/sessions/*');
$count = 0;
foreach ($sessionFiles as $file) {
    if (basename($file) !== '.gitignore') {
        @unlink($file);
        $count++;
    }
}
echo "Cleared $count session files\n\n";

// Rebuild cache
echo "Step 3: Rebuilding Cache\n";
echo "------------------------\n";
runCommand('php artisan config:cache');
runCommand('php artisan route:cache');

echo "=================================\n";
echo "CACHE CLEAR COMPLETE!\n";
echo "=================================\n\n";

echo "Next steps:\n";
echo "1. Test your website\n";
echo "2. DELETE THIS FILE!\n";
echo "3. Check error logs if issues persist\n";
echo "</pre>";
?>