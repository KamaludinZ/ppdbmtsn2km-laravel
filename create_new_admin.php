<?php
/**
 * Create New Admin User Script
 *
 * Script untuk membuat akun admin baru ketika akun lama bermasalah
 *
 * Usage:
 * 1. Upload ke Laravel root directory
 * 2. Run: php create_new_admin.php
 * 3. Delete script setelah selesai
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "   CREATE NEW ADMIN - PPDB MTSN2\n";
echo "========================================\n\n";

try {
    // Email untuk admin baru
    $newEmail = 'superadmin@mtsn2kotamalang.sch.id';
    $newPassword = 'SuperAdmin2026!@#';

    // Check if email already exists
    $existing = User::where('email', $newEmail)->first();
    if ($existing) {
        echo "⚠️  User dengan email {$newEmail} sudah ada!\n";
        echo "ID: {$existing->id}\n";
        echo "Name: {$existing->name}\n";
        echo "Role: {$existing->role}\n\n";

        echo "Updating existing user to admin...\n";
        $existing->role = 'admin';
        $existing->aktif = true;  // CRITICAL: Set aktif to true!
        $existing->password = Hash::make($newPassword);
        $existing->email_verified_at = now();
        $existing->save();

        echo "✅ Existing user updated to admin!\n\n";
    } else {
        // Create new admin user
        echo "Creating new admin user...\n";

        // Use create() for fillable fields, then set protected fields
        $newAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => $newEmail,
            'password' => Hash::make($newPassword),
        ]);

        // Set protected fields (not in fillable for security)
        $newAdmin->role = 'admin';
        $newAdmin->aktif = true;  // CRITICAL: Set aktif to true!
        $newAdmin->email_verified_at = now();
        $newAdmin->save();

        echo "✅ New admin user created!\n";
        echo "ID: {$newAdmin->id}\n\n";
    }

    // Show all admin users
    echo "========================================\n";
    echo "   ALL ADMIN ACCOUNTS:\n";
    echo "========================================\n";

    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        echo "\n";
        echo "ID: {$admin->id}\n";
        echo "Name: {$admin->name}\n";
        echo "Email: {$admin->email}\n";
        echo "Verified: " . ($admin->email_verified_at ? "Yes" : "No") . "\n";
        echo "---\n";
    }

    echo "\n";
    echo "========================================\n";
    echo "   NEW ADMIN LOGIN CREDENTIALS:\n";
    echo "========================================\n";
    echo "Email:    {$newEmail}\n";
    echo "Password: {$newPassword}\n";
    echo "========================================\n\n";

    echo "Clearing caches...\n";

    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "✓ Cache cleared\n";

    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "✓ Config cleared\n";

    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "✓ Routes cleared\n";

    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "✓ Views cleared\n";

    echo "\n✅ ALL DONE!\n\n";
    echo "Login URL: /login/admin\n";
    echo "Email:     {$newEmail}\n";
    echo "Password:  {$newPassword}\n\n";
    echo "⚠️  IMPORTANT: Delete this script after use!\n";
    echo "     Run: rm create_new_admin.php\n\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: {$e->getMessage()}\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
    echo "\n\n";
    exit(1);
}
