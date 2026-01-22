<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "   VERIFY NEW ADMIN CREDENTIALS\n";
echo "========================================\n\n";

$testEmail = 'superadmin@mtsn2kotamalang.sch.id';
$testPassword = 'SuperAdmin2026!@#';

$admin = User::where('email', $testEmail)->first();

if (!$admin) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "User Found:\n";
echo "ID: {$admin->id}\n";
echo "Name: {$admin->name}\n";
echo "Email: {$admin->email}\n";
echo "Role: {$admin->role}\n";
echo "Email Verified: " . ($admin->email_verified_at ? "Yes" : "No") . "\n\n";

echo "Testing Password:\n";
$isValid = Hash::check($testPassword, $admin->password);
echo "Password: {$testPassword}\n";
echo "Result: " . ($isValid ? "✅ VALID" : "❌ INVALID") . "\n\n";

if ($isValid && $admin->role === 'admin') {
    echo "✅ SUCCESS! Can login with:\n";
    echo "   Email: {$admin->email}\n";
    echo "   Password: {$testPassword}\n";
    echo "   Role: {$admin->role}\n";
} else {
    echo "❌ FAILED! Something is wrong.\n";
    if (!$isValid) echo "   - Password hash invalid\n";
    if ($admin->role !== 'admin') echo "   - Role is not 'admin'\n";
    exit(1);
}
