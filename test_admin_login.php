<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('role', 'admin')->first();

if (!$admin) {
    echo "❌ Admin user not found!\n";
    exit(1);
}

echo "Admin User Found:\n";
echo "ID: {$admin->id}\n";
echo "Name: {$admin->name}\n";
echo "Email: {$admin->email}\n";
echo "Role: {$admin->role}\n";
echo "Email Verified: " . ($admin->email_verified_at ? "Yes" : "No") . "\n\n";

echo "Testing Password Hash:\n";
$testPassword = 'Admin123!@#';
$isValid = Hash::check($testPassword, $admin->password);

echo "Password: {$testPassword}\n";
echo "Result: " . ($isValid ? "✅ VALID" : "❌ INVALID") . "\n\n";

if ($isValid) {
    echo "✅ SUCCESS! Admin can login with these credentials:\n";
    echo "   Email: {$admin->email}\n";
    echo "   Password: {$testPassword}\n";
} else {
    echo "❌ ERROR! Password hash does not match.\n";
    exit(1);
}
