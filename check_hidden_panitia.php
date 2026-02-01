<?php
/**
 * Check Hidden Panitia Data
 *
 * Script untuk melihat data panitia yang sudah terbuat tapi tidak muncul di list
 * Jalankan di production: php check_hidden_panitia.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Carbon\Carbon;

echo "================================================\n";
echo "🔍 Checking Hidden Panitia Data\n";
echo "================================================\n\n";

// Get all panitia/admin users
$allUsers = User::whereIn('role', [
    'admin',
    'panitia_pendaftaran',
    'panitia_daftarulang',
    'panitia_tes_psikologi',
    'panitia_seragam'
])->orderBy('created_at', 'desc')->get();

echo "📊 Total Users Found: " . $allUsers->count() . "\n";
echo "================================================\n\n";

// Group by role
$byRole = $allUsers->groupBy('role');

foreach ($byRole as $role => $users) {
    echo "📋 Role: " . strtoupper($role) . " (" . $users->count() . " users)\n";
    echo "------------------------------------------------\n";

    foreach ($users as $user) {
        $status = $user->aktif ? '✅ Aktif' : '❌ Tidak Aktif';
        $created = Carbon::parse($user->created_at)->format('d-m-Y H:i:s');

        echo "  • ID: {$user->id}\n";
        echo "    Name: {$user->name}\n";
        echo "    Email: {$user->email}\n";
        echo "    Status: {$status}\n";
        echo "    Created: {$created}\n";
        echo "\n";
    }
    echo "\n";
}

// Get last 10 created users
echo "================================================\n";
echo "🆕 Last 10 Created Users (Recently Added)\n";
echo "================================================\n\n";

$recentUsers = User::whereIn('role', [
    'admin',
    'panitia_pendaftaran',
    'panitia_daftarulang',
    'panitia_tes_psikologi',
    'panitia_seragam'
])->orderBy('created_at', 'desc')
  ->limit(10)
  ->get();

foreach ($recentUsers as $user) {
    $status = $user->aktif ? '✅' : '❌';
    $created = Carbon::parse($user->created_at)->diffForHumans();

    echo "{$status} [{$user->role}] {$user->name} ({$user->email}) - Created {$created}\n";
}

echo "\n================================================\n";
echo "✅ Check Complete\n";
echo "================================================\n";

// Statistics
echo "\n📊 Statistics:\n";
echo "  • Admin: " . User::where('role', 'admin')->count() . "\n";
echo "  • Panitia Pendaftaran: " . User::where('role', 'panitia_pendaftaran')->count() . "\n";
echo "  • Panitia Daftar Ulang: " . User::where('role', 'panitia_daftarulang')->count() . "\n";
echo "  • Panitia Tes Psikologi: " . User::where('role', 'panitia_tes_psikologi')->count() . "\n";
echo "  • Panitia Seragam: " . User::where('role', 'panitia_seragam')->count() . "\n";
echo "  • Total: " . $allUsers->count() . "\n";

echo "\n💡 Tip: Data yang baru dibuat mungkin tidak muncul karena:\n";
echo "   1. Filter role active (pilih 'Semua Role')\n";
echo "   2. Pagination (user di halaman > 1)\n";
echo "   3. Search active (hapus pencarian)\n";
echo "   4. Cache (refresh halaman dengan Ctrl+F5)\n";
echo "\n";
