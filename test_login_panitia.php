<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TROUBLESHOOTING LOGIN PANITIA ===\n\n";

// Test accounts
$testAccounts = [
    [
        'email' => 'panitia@test.com',
        'password' => 'panitia123',
        'role_expected' => 'panitia_pendaftaran'
    ],
    [
        'email' => 'panitia-seragam@test.com',
        'password' => 'seragam123',
        'role_expected' => 'panitia_seragam'
    ],
    [
        'email' => 'panitia-psikologi@test.com',
        'password' => 'psikologi123',
        'role_expected' => 'panitia_tes_psikologi'
    ],
];

foreach ($testAccounts as $account) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Testing: {$account['email']}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    // Find user
    $user = User::where('email', $account['email'])->first();

    if (!$user) {
        echo "❌ USER NOT FOUND\n";
        echo "   Solusi: Jalankan 'php create_test_users.php' untuk membuat user test\n\n";
        continue;
    }

    echo "✅ User ditemukan\n";
    echo "   ID: {$user->id}\n";
    echo "   Name: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Role: {$user->role}\n";
    echo "   Aktif: " . ($user->aktif ? 'TRUE ✓' : 'FALSE ✗') . "\n";

    // Check if aktif
    if (!$user->aktif) {
        echo "\n❌ MASALAH: Akun tidak aktif!\n";
        echo "   Solusi: Update field 'aktif' menjadi true di database\n";
        echo "   Command: UPDATE users SET aktif = true WHERE email = '{$user->email}';\n\n";
        continue;
    }

    // Check password
    if (Hash::check($account['password'], $user->password)) {
        echo "✅ Password cocok\n";
    } else {
        echo "❌ Password TIDAK cocok\n";
        echo "   Password yang diharapkan: {$account['password']}\n";
        echo "   Solusi: Reset password user ini\n\n";
        continue;
    }

    // Check role
    if ($user->role === $account['role_expected']) {
        echo "✅ Role benar: {$user->role}\n";
    } else {
        echo "⚠️  Role berbeda\n";
        echo "   Diharapkan: {$account['role_expected']}\n";
        echo "   Aktual: {$user->role}\n";
    }

    echo "\n✅ SEMUA CEK PASSED - LOGIN SEHARUSNYA BERHASIL\n";
    echo "\nJika masih tidak bisa login, kemungkinan:\n";
    echo "1. Captcha salah - Pastikan kode captcha diketik dengan benar\n";
    echo "2. Browser cache - Coba gunakan Incognito/Private mode\n";
    echo "3. Session issue - Clear browser cookies untuk localhost\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "INFORMASI LOGIN:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "URL Login: http://localhost:8000/admin/login\n\n";

echo "Akun Test yang Tersedia:\n\n";
echo "1. Panitia Pendaftaran\n";
echo "   Email: panitia@test.com\n";
echo "   Password: panitia123\n\n";

echo "2. Panitia Seragam\n";
echo "   Email: panitia-seragam@test.com\n";
echo "   Password: seragam123\n\n";

echo "3. Panitia Psikologi\n";
echo "   Email: panitia-psikologi@test.com\n";
echo "   Password: psikologi123\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "TIPS JIKA MASIH GAGAL LOGIN:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "1. Clear Cache Laravel:\n";
echo "   php artisan cache:clear\n";
echo "   php artisan config:clear\n";
echo "   php artisan view:clear\n\n";

echo "2. Clear Browser:\n";
echo "   - Tekan Ctrl+Shift+Delete\n";
echo "   - Clear cookies and cache untuk localhost\n";
echo "   - Atau gunakan Incognito/Private mode\n\n";

echo "3. Pastikan Captcha Benar:\n";
echo "   - Kode captcha di form adalah 6 digit angka\n";
echo "   - Ketik persis seperti yang ditampilkan\n";
echo "   - Jika tidak jelas, klik tombol refresh di sebelah captcha\n\n";

echo "4. Cek Console Browser:\n";
echo "   - Tekan F12 untuk buka Developer Tools\n";
echo "   - Lihat tab Console untuk error JavaScript\n";
echo "   - Lihat tab Network untuk error HTTP request\n\n";

echo "✅ Test selesai!\n";
