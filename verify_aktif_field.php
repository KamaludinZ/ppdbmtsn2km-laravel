<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "   VERIFY ADMIN AKTIF STATUS\n";
echo "========================================\n\n";

$admins = User::where('role', 'admin')->get();

foreach ($admins as $admin) {
    echo "Admin: {$admin->name}\n";
    echo "  ID: {$admin->id}\n";
    echo "  Email: {$admin->email}\n";
    echo "  Role: {$admin->role}\n";
    echo "  Aktif: " . ($admin->aktif ? "✅ TRUE" : "❌ FALSE") . "\n";
    echo "  Email Verified: " . ($admin->email_verified_at ? "Yes" : "No") . "\n";
    echo "  Password Test: ";

    // Test each admin with their expected password
    if ($admin->email === 'admin@mtsn2kotamalang.sch.id') {
        $testPassword = 'Admin123!@#';
    } elseif ($admin->email === 'superadmin@mtsn2kotamalang.sch.id') {
        $testPassword = 'SuperAdmin2026!@#';
    } else {
        $testPassword = null;
    }

    if ($testPassword) {
        $valid = Hash::check($testPassword, $admin->password);
        echo ($valid ? "✅ VALID" : "❌ INVALID") . "\n";

        // Test login attempt simulation
        echo "  Login Check: ";
        $loginOk = $admin->aktif && $valid;
        echo ($loginOk ? "✅ CAN LOGIN" : "❌ CANNOT LOGIN") . "\n";

        if (!$admin->aktif) {
            echo "  ⚠️  WARNING: aktif field is FALSE - login will be blocked!\n";
        }
    } else {
        echo "N/A (unknown password)\n";
    }

    echo "---\n\n";
}

echo "========================================\n";
echo "Summary:\n";
echo "========================================\n";
$canLogin = User::where('role', 'admin')->where('aktif', true)->count();
$total = User::where('role', 'admin')->count();
echo "Admins that can login: {$canLogin} / {$total}\n";

if ($canLogin < $total) {
    echo "\n⚠️  WARNING: Some admins have aktif=false!\n";
    echo "Run create_new_admin.php to fix.\n";
}
