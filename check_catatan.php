<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pendaftar;
use App\Models\DaftarUlang;

echo "=== CEK DATA DAFTAR ULANG ===\n\n";

// Ambil sample pendaftar yang sudah lulus
$pendaftar = Pendaftar::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
    ->first();

if (!$pendaftar) {
    echo "Tidak ada pendaftar yang lulus\n";
    exit;
}

echo "Pendaftar ID: {$pendaftar->id}\n";
echo "Nama: {$pendaftar->nama_lengkap}\n";
echo "Status: {$pendaftar->status_pendaftaran}\n\n";

// Cek semua record daftar_ulang untuk pendaftar ini
$daftarUlangs = DaftarUlang::where('pendaftar_id', $pendaftar->id)->get();

echo "Jumlah record daftar_ulang: {$daftarUlangs->count()}\n\n";

foreach ($daftarUlangs as $index => $du) {
    echo "Record #" . ($index + 1) . ":\n";
    echo "  ID: {$du->id}\n";
    echo "  Status: {$du->status}\n";
    echo "  Catatan: " . ($du->catatan ?? 'NULL') . "\n";
    echo "  Created: {$du->created_at}\n";
    echo "  Updated: {$du->updated_at}\n\n";
}

// Cek relationship
$pendaftar->load('daftarUlang');

echo "=== RELATIONSHIP QUERY ===\n";
if ($pendaftar->daftarUlang) {
    echo "DaftarUlang ID dari relationship: {$pendaftar->daftarUlang->id}\n";
    echo "Catatan dari relationship: " . ($pendaftar->daftarUlang->catatan ?? 'NULL') . "\n";
} else {
    echo "Tidak ada daftar ulang via relationship\n";
}
