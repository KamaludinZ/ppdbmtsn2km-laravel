<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Jalur Pendaftaran Status ===\n\n";

$jalur = \App\Models\JalurPendaftaran::select('id', 'nama', 'aktif', 'tampil_di_daftar', 'urutan')
    ->orderBy('urutan')
    ->get();

echo "Total Jalur: " . $jalur->count() . "\n\n";

foreach($jalur as $j) {
    echo "ID: {$j->id}\n";
    echo "Nama: {$j->nama}\n";
    echo "Status Aktif: " . ($j->aktif ? '✅ Ya' : '❌ Tidak') . "\n";
    echo "Tampil di Daftar: " . ($j->tampil_di_daftar ? '✅ Ya' : '❌ Tidak') . "\n";
    echo "Urutan: {$j->urutan}\n";
    echo "---\n";
}

echo "\n=== Query Test: tampilDiDaftar() ===\n\n";
$jalurTampil = \App\Models\JalurPendaftaran::tampilDiDaftar()->ordered()->get();
echo "Jalur yang TAMPIL di /daftar: " . $jalurTampil->count() . "\n";
foreach($jalurTampil as $j) {
    echo "  - {$j->nama} (ID: {$j->id})\n";
}

echo "\n✅ Done!\n";
