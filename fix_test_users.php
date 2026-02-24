<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== FIXING TEST USERS ===\n\n";

// 1. Fix Admin
$admin = User::where('email', 'admin@test.com')->first();
if ($admin) {
    $admin->update([
        'role' => 'admin',
        'email_verified_at' => now(),
        'password' => Hash::make('admin123'),
    ]);
    echo "✅ Admin fixed: admin@test.com / admin123 (role: admin)\n";
} else {
    echo "❌ Admin not found\n";
}

// 2. Fix Panitia
$panitia = User::where('email', 'panitia@test.com')->first();
if ($panitia) {
    $panitia->update([
        'role' => 'panitia',
        'email_verified_at' => now(),
        'password' => Hash::make('panitia123'),
    ]);
    echo "✅ Panitia fixed: panitia@test.com / panitia123 (role: panitia)\n";
} else {
    echo "❌ Panitia not found\n";
}

// 3. Fix Panitia Seragam
$panitiaSeragam = User::where('email', 'panitia-seragam@test.com')->first();
if ($panitiaSeragam) {
    $panitiaSeragam->update([
        'role' => 'panitia_seragam',
        'email_verified_at' => now(),
        'password' => Hash::make('seragam123'),
    ]);
    echo "✅ Panitia Seragam fixed: panitia-seragam@test.com / seragam123 (role: panitia_seragam)\n";
} else {
    echo "❌ Panitia Seragam not found\n";
}

// 4. Fix Panitia Psikologi
$panitiaPsikologi = User::where('email', 'panitia-psikologi@test.com')->first();
if ($panitiaPsikologi) {
    $panitiaPsikologi->update([
        'role' => 'panitia_tes_psikologi',
        'email_verified_at' => now(),
        'password' => Hash::make('psikologi123'),
    ]);
    echo "✅ Panitia Psikologi fixed: panitia-psikologi@test.com / psikologi123 (role: panitia_tes_psikologi)\n";
} else {
    echo "❌ Panitia Psikologi not found\n";
}

// 5. Fix Pendaftar
$pendaftar = User::where('email', 'pendaftar@test.com')->first();
if ($pendaftar) {
    $pendaftar->update([
        'role' => 'pendaftar',
        'email_verified_at' => now(),
        'password' => Hash::make('pendaftar123'),
    ]);
    echo "✅ Pendaftar fixed: pendaftar@test.com / pendaftar123 (role: pendaftar)\n";
} else {
    echo "❌ Pendaftar not found\n";
}

echo "\n=== VERIFICATION ===\n\n";

$users = User::all(['id', 'name', 'email', 'role', 'email_verified_at']);

foreach ($users as $user) {
    $verified = $user->email_verified_at ? '✅' : '❌';
    echo "{$verified} {$user->email} => role: {$user->role}\n";
}

echo "\n✅ All test users fixed!\n";
echo "\n=== LOGIN URLS (CORRECTED) ===\n\n";
echo "Admin/Panitia: http://localhost:8000/login/admin\n";
echo "Pendaftar: http://localhost:8000/login\n";
