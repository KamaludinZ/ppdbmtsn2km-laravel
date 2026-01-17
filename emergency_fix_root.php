<?php
/**
 * EMERGENCY FIX - Document Root Issue
 * Upload ke root subdomain dan akses:
 * https://pmbm.mtsn2kotamalang.sch.id/emergency_fix_root.php
 *
 * Script ini akan create .htaccess untuk redirect ke public
 * HAPUS SETELAH SELESAI!
 */

echo "<pre>";
echo "========================================\n";
echo "EMERGENCY FIX - DOCUMENT ROOT ISSUE\n";
echo "========================================\n\n";

$currentDir = dirname(__FILE__);
echo "Current Directory: $currentDir\n\n";

// Check if we're in the right place
if (!file_exists('public/index.php')) {
    echo "ERROR: public/index.php not found!\n";
    echo "Make sure this script is in the root of your Laravel application.\n";
    exit;
}

echo "✓ Laravel application detected\n\n";

// Create .htaccess in root
echo "Creating .htaccess in root directory...\n";

$htaccessContent = '<IfModule mod_rewrite.c>
    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]

    # Redirect everything to public folder except this script
    RewriteCond %{REQUEST_URI} !^/emergency_fix_root\.php$
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]

    # Redirect root specifically
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^$ /public/ [L]
</IfModule>

# Security - Prevent directory listing
Options -Indexes

# Deny access to sensitive files
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "\.(json|lock|md|yml|yaml|xml)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Block access to vendor directory
<Directory "vendor">
    Order allow,deny
    Deny from all
</Directory>

# Block access to other directories
<DirectoryMatch "^(app|bootstrap|config|database|resources|routes|storage|tests)">
    Order allow,deny
    Deny from all
</DirectoryMatch>';

if (file_put_contents('.htaccess', $htaccessContent)) {
    echo "✓ .htaccess created successfully!\n\n";
} else {
    echo "✗ Failed to create .htaccess\n";
    echo "Please create it manually with the following content:\n\n";
    echo htmlspecialchars($htaccessContent);
    echo "\n\n";
}

// Check public/.htaccess
echo "Checking public/.htaccess...\n";
if (!file_exists('public/.htaccess')) {
    echo "⚠ public/.htaccess not found! Creating...\n";

    $publicHtaccess = '<IfModule mod_rewrite.c>
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
</IfModule>';

    if (file_put_contents('public/.htaccess', $publicHtaccess)) {
        echo "✓ public/.htaccess created\n\n";
    } else {
        echo "✗ Failed to create public/.htaccess\n\n";
    }
} else {
    echo "✓ public/.htaccess exists\n\n";
}

// Check if mod_rewrite is enabled
echo "Checking server configuration...\n";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo "✓ mod_rewrite is enabled\n";
    } else {
        echo "⚠ mod_rewrite might not be enabled\n";
    }
} else {
    echo "⚠ Cannot check if mod_rewrite is enabled\n";
}

// Create a test index.php in root as fallback
echo "\nCreating fallback index.php in root...\n";

$indexContent = '<?php
/**
 * Laravel - Web Application
 * This file redirects to the public folder
 */

// Check if request is for this emergency script
if ($_SERVER["REQUEST_URI"] === "/emergency_fix_root.php") {
    // Let it pass through
    return false;
}

// Load Laravel
require __DIR__."/public/index.php";
';

if (file_put_contents('index.php', $indexContent)) {
    echo "✓ Fallback index.php created\n\n";
} else {
    echo "✗ Failed to create fallback index.php\n\n";
}

echo "========================================\n";
echo "FIX COMPLETE!\n";
echo "========================================\n\n";

echo "NEXT STEPS:\n";
echo "1. Test your website: https://pmbm.mtsn2kotamalang.sch.id\n";
echo "2. If it works, DELETE THIS SCRIPT immediately!\n";
echo "3. If still showing directory listing:\n";
echo "   - Clear browser cache\n";
echo "   - Contact hosting to change document root to /public\n";
echo "\n";

echo "ALTERNATIVE SOLUTION:\n";
echo "Ask your hosting provider to:\n";
echo "1. Change subdomain document root to:\n";
echo "   /home/[username]/pmbm.mtsn2kotamalang.sch.id/public\n";
echo "2. Or enable mod_rewrite if it's disabled\n";
echo "\n";

echo "FILES CREATED:\n";
echo "- .htaccess (root directory)\n";
echo "- index.php (fallback redirect)\n";
echo "- public/.htaccess (if missing)\n";
echo "\n";

echo "⚠ SECURITY WARNING:\n";
echo "DELETE emergency_fix_root.php after fixing the issue!\n";

echo "</pre>";

// Add auto-redirect after 5 seconds
echo '<script>
setTimeout(function() {
    window.location.href = "/";
}, 5000);
</script>';

echo "<p>Redirecting to homepage in 5 seconds...</p>";
?>