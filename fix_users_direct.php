<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== FIXING TEST USERS (DIRECT DB) ===\n\n";

// 1. Admin
DB::table('users')->where('email', 'admin@test.com')->update([
    'role' => 'admin',
    'email_verified_at' => now(),
    'password' => Hash::make('admin123'),
]);
echo "✅ Admin: admin@test.com / admin123\n";

// 2. Panitia
DB::table('users')->where('email', 'panitia@test.com')->update([
    'role' => 'panitia_pendaftaran',
    'email_verified_at' => now(),
    'password' => Hash::make('panitia123'),
]);
echo "✅ Panitia: panitia@test.com / panitia123\n";

// 3. Panitia Seragam
DB::table('users')->where('email', 'panitia-seragam@test.com')->update([
    'role' => 'panitia_seragam',
    'email_verified_at' => now(),
    'password' => Hash::make('seragam123'),
]);
echo "✅ Panitia Seragam: panitia-seragam@test.com / seragam123\n";

// 4. Panitia Psikologi
DB::table('users')->where('email', 'panitia-psikologi@test.com')->update([
    'role' => 'panitia_tes_psikologi',
    'email_verified_at' => now(),
    'password' => Hash::make('psikologi123'),
]);
echo "✅ Panitia Psikologi: panitia-psikologi@test.com / psikologi123\n";

// 5. Pendaftar
DB::table('users')->where('email', 'pendaftar@test.com')->update([
    'role' => 'pendaftar',
    'email_verified_at' => now(),
    'password' => Hash::make('pendaftar123'),
]);
echo "✅ Pendaftar: pendaftar@test.com / pendaftar123\n";

echo "\n=== VERIFICATION ===\n\n";

$users = DB::table('users')->select('email', 'role', 'email_verified_at')->get();

foreach ($users as $user) {
    $verified = $user->email_verified_at ? '✅' : '❌';
    echo "{$verified} {$user->email} => role: {$user->role}\n";
}

echo "\n✅ ALL USERS FIXED!\n\n";

echo "=== LOGIN CREDENTIALS ===\n\n";
echo "1. ADMIN\n";
echo "   Email: admin@test.com\n";
echo "   Password: admin123\n";
echo "   URL: http://localhost:8000/login/admin\n\n";

echo "2. PANITIA\n";
echo "   Email: panitia@test.com\n";
echo "   Password: panitia123\n";
echo "   URL: http://localhost:8000/login/admin\n\n";

echo "3. PANITIA SERAGAM\n";
echo "   Email: panitia-seragam@test.com\n";
echo "   Password: seragam123\n";
echo "   URL: http://localhost:8000/login/admin\n\n";

echo "4. PANITIA TES PSIKOLOGI\n";
echo "   Email: panitia-psikologi@test.com\n";
echo "   Password: psikologi123\n";
echo "   URL: http://localhost:8000/login/admin\n\n";

echo "5. PENDAFTAR\n";
echo "   Email: pendaftar@test.com\n";
echo "   Password: pendaftar123\n";
echo "   URL: http://localhost:8000/login\n\n";

echo "Sekarang coba login dan test fitur mode opsi seragam!\n";
