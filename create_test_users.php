<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CREATING TEST USERS ===\n\n";

// Get or Create Jalur Pendaftaran
echo "Getting/Creating Jalur Pendaftaran...\n";
$jalur = \App\Models\JalurPendaftaran::firstOrCreate(
    ['kode' => 'prestasi'],
    [
        'nama' => 'Jalur Prestasi',
        'kuota' => 100,
        'tampil_di_daftar' => true,
    ]
);
echo "✅ Jalur Pendaftaran: ID {$jalur->id} ({$jalur->nama})\n\n";

// 1. Admin
$admin = User::updateOrCreate(
    ['email' => 'admin@test.com'],
    [
        'name' => 'Admin Testing',
        'nisn' => '1111111111',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]
);
echo "✅ Admin: admin@test.com / admin123\n";

// 2. Panitia
$panitia = User::updateOrCreate(
    ['email' => 'panitia@test.com'],
    [
        'name' => 'Panitia Testing',
        'nisn' => '2222222222',
        'password' => Hash::make('panitia123'),
        'role' => 'panitia',
        'email_verified_at' => now(),
    ]
);
echo "✅ Panitia: panitia@test.com / panitia123\n";

// 3. Panitia Seragam
$panitiaSeragam = User::updateOrCreate(
    ['email' => 'panitia-seragam@test.com'],
    [
        'name' => 'Panitia Seragam Testing',
        'nisn' => '3333333333',
        'password' => Hash::make('seragam123'),
        'role' => 'panitia_seragam',
        'email_verified_at' => now(),
    ]
);
echo "✅ Panitia Seragam: panitia-seragam@test.com / seragam123\n";

// 4. Panitia Tes Psikologi
$panitiaPsikologi = User::updateOrCreate(
    ['email' => 'panitia-psikologi@test.com'],
    [
        'name' => 'Panitia Psikologi Testing',
        'nisn' => '4444444444',
        'password' => Hash::make('psikologi123'),
        'role' => 'panitia_tes_psikologi',
        'email_verified_at' => now(),
    ]
);
echo "✅ Panitia Psikologi: panitia-psikologi@test.com / psikologi123\n";

// 5. Pendaftar
$pendaftar = User::updateOrCreate(
    ['email' => 'pendaftar@test.com'],
    [
        'name' => 'Pendaftar Testing',
        'nisn' => '5555555555',
        'password' => Hash::make('pendaftar123'),
        'role' => 'pendaftar',
        'email_verified_at' => now(),
    ]
);
echo "✅ Pendaftar: pendaftar@test.com / pendaftar123\n";

// Note: Pendaftar record akan otomatis dibuat saat user register pertama kali
echo "(Pendaftar record akan dibuat saat register melalui UI)\n";

echo "\n=== SUMMARY ===\n\n";
echo "LOGIN CREDENTIALS:\n\n";
echo "1. ADMIN\n";
echo "   Email: admin@test.com\n";
echo "   Password: admin123\n";
echo "   URL: http://localhost:8000/admin/login\n\n";

echo "2. PANITIA\n";
echo "   Email: panitia@test.com\n";
echo "   Password: panitia123\n";
echo "   URL: http://localhost:8000/admin/login\n\n";

echo "3. PANITIA SERAGAM\n";
echo "   Email: panitia-seragam@test.com\n";
echo "   Password: seragam123\n";
echo "   URL: http://localhost:8000/admin/login\n\n";

echo "4. PANITIA TES PSIKOLOGI\n";
echo "   Email: panitia-psikologi@test.com\n";
echo "   Password: psikologi123\n";
echo "   URL: http://localhost:8000/admin/login\n\n";

echo "5. PENDAFTAR\n";
echo "   Email: pendaftar@test.com\n";
echo "   Password: pendaftar123\n";
echo "   URL: http://localhost:8000/login\n\n";

echo "✅ All test users created successfully!\n";
