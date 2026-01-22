<?php
/**
 * Emergency Admin Password Reset Script
 *
 * This script resets the admin user credentials and clears all authentication caches
 *
 * Usage:
 * 1. Upload this file to your Laravel root directory
 * 2. Run: php emergency_admin_reset.php
 * 3. Delete this file after use for security
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "   EMERGENCY ADMIN RESET - PPDB MTSN2\n";
echo "========================================\n\n";

try {
    // Find admin user
    $admin = User::where('role', 'admin')->first();

    if (!$admin) {
        echo "❌ ERROR: Admin user not found!\n";
        echo "Creating new admin user...\n\n";

        // Create with fillable fields only
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@mtsn2kotamalang.sch.id',
            'password' => Hash::make('Admin123!@#'),
        ]);

        // Set protected fields
        $admin->role = 'admin';
        $admin->aktif = true;  // CRITICAL!
        $admin->email_verified_at = now();
        $admin->save();

        echo "✅ New admin user created!\n";
    } else {
        echo "Found admin user:\n";
        echo "ID: {$admin->id}\n";
        echo "Name: {$admin->name}\n";
        echo "Current Email: {$admin->email}\n";
        echo "Role: {$admin->role}\n\n";

        echo "Resetting credentials...\n";

        // Reset to default credentials
        $admin->email = 'admin@mtsn2kotamalang.sch.id';
        $admin->password = Hash::make('Admin123!@#');
        $admin->aktif = true;  // CRITICAL: Ensure aktif is true!
        $admin->email_verified_at = now();
        $admin->save();

        echo "✅ Admin credentials reset!\n";
    }

    echo "\n";
    echo "========================================\n";
    echo "   NEW ADMIN CREDENTIALS:\n";
    echo "========================================\n";
    echo "Email:    admin@mtsn2kotamalang.sch.id\n";
    echo "Password: Admin123!@#\n";
    echo "========================================\n\n";

    echo "Clearing authentication caches...\n";

    // Clear all caches
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "✓ Cache cleared\n";

    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "✓ Config cleared\n";

    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "✓ Routes cleared\n";

    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "✓ Views cleared\n";

    // Clear sessions
    try {
        \Illuminate\Support\Facades\Artisan::call('session:flush');
        echo "✓ Sessions flushed\n";
    } catch (\Exception $e) {
        echo "⚠ Could not flush sessions: {$e->getMessage()}\n";
    }

    echo "\n✅ ALL DONE!\n\n";
    echo "You can now login at: /login/admin\n";
    echo "⚠️  IMPORTANT: Delete this script after use!\n";
    echo "     Run: rm emergency_admin_reset.php\n\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: {$e->getMessage()}\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
    echo "\n\n";
    exit(1);
}
