<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use Illuminate\Support\Facades\DB;

echo "=== CREATING PENDAFTAR RECORD ===\n\n";

// Get pendaftar user
$user = User::where('email', 'pendaftar@test.com')->first();

if (!$user) {
    echo "❌ User pendaftar@test.com not found!\n";
    exit;
}

// Get jalur
$jalur = JalurPendaftaran::first();

if (!$jalur) {
    echo "❌ No jalur pendaftaran found!\n";
    exit;
}

// Check if pendaftar record already exists
$existingPendaftar = Pendaftar::where('user_id', $user->id)->first();

if ($existingPendaftar) {
    echo "Pendaftar record already exists. Updating...\n";

    // Update to make them eligible for daftar ulang
    DB::table('pendaftar')->where('id', $existingPendaftar->id)->update([
        'status_pendaftaran' => 'lulus',
        'setuju_daftar_ulang' => true,
    ]);

    echo "✅ Pendaftar updated: status = lulus, setuju_daftar_ulang = true\n";
} else {
    echo "Creating new pendaftar record...\n";

    // Create with direct DB to bypass fillable restrictions
    $pendaftarId = DB::table('pendaftar')->insertGetId([
        'user_id' => $user->id,
        'jalur_pendaftaran_id' => $jalur->id,
        'nomor_pendaftaran' => 'TEST-' . date('Ymd') . '-001',
        'nama_lengkap' => 'Pendaftar Testing',
        'email' => 'pendaftar@test.com',
        'nisn' => '5555555555',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'jenis_kelamin' => 'L',
        'agama' => 'Islam',
        'alamat' => 'Jl. Testing No. 123',
        'telepon' => '0215551234',
        'no_hp' => '081234567890',
        'asal_sekolah' => 'SD Negeri 1 Jakarta',
        'status_pendaftaran' => 'lulus',
        'setuju_daftar_ulang' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "✅ Pendaftar record created with ID: {$pendaftarId}\n";
}

echo "\n=== VERIFICATION ===\n\n";

$pendaftar = Pendaftar::where('user_id', $user->id)->first();

if ($pendaftar) {
    echo "ID: {$pendaftar->id}\n";
    echo "User ID: {$pendaftar->user_id}\n";
    echo "Nama: {$pendaftar->nama_lengkap}\n";
    echo "Status: {$pendaftar->status_pendaftaran}\n";
    echo "Setuju Daftar Ulang: " . ($pendaftar->setuju_daftar_ulang ? 'YES' : 'NO') . "\n";
    echo "\n✅ READY!\n\n";
    echo "Sekarang coba akses: http://localhost:8000/pendaftar/daftarulang\n";
} else {
    echo "❌ Failed to create pendaftar record\n";
}
