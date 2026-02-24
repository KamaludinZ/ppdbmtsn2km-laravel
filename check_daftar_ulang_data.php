<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING DAFTAR ULANG DATA ===\n\n";

$daftarUlang = DB::table('daftar_ulang')
    ->where('pendaftar_id', 6)
    ->first();

if ($daftarUlang) {
    echo "✅ Record FOUND!\n\n";
    echo "ID: {$daftarUlang->id}\n";
    echo "Pendaftar ID: {$daftarUlang->pendaftar_id}\n";
    echo "Status: {$daftarUlang->status}\n";
    echo "Nominal Biaya Awal: " . ($daftarUlang->nominal_biaya_awal ?? 'NULL') . "\n";
    echo "Nominal Biaya: " . ($daftarUlang->nominal_biaya ?? 'NULL') . "\n";
    echo "Opsi Pembayaran: " . ($daftarUlang->opsi_pembayaran ?? 'NULL') . "\n";
    echo "Metode Pembayaran: " . ($daftarUlang->metode_pembayaran ?? 'NULL') . "\n";
    echo "Nama Pengirim: " . ($daftarUlang->nama_pengirim ?? 'NULL') . "\n";
    echo "Bukti Pembayaran: " . ($daftarUlang->bukti_pembayaran_madrasah ?? 'NULL') . "\n";
    echo "Surat Pernyataan: " . ($daftarUlang->surat_pernyataan_path ?? 'NULL') . "\n";
    echo "Waktu Pembayaran: " . ($daftarUlang->waktu_pembayaran_madrasah ?? 'NULL') . "\n";
    echo "Created At: {$daftarUlang->created_at}\n";
    echo "Updated At: {$daftarUlang->updated_at}\n";
} else {
    echo "❌ NO RECORD FOUND for pendaftar_id = 6\n";
}
