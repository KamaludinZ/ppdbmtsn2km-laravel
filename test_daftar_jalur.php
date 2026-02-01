<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Query yang Digunakan di /daftar ===\n\n";

// Query yang sama persis dengan yang ada di Register.php render()
$jalurPendaftaran = \App\Models\JalurPendaftaran::tampilDiDaftar()->ordered()->get();

echo "Query: JalurPendaftaran::tampilDiDaftar()->ordered()->get()\n\n";
echo "Jumlah jalur yang MUNCUL di /daftar: " . $jalurPendaftaran->count() . "\n\n";

echo "Daftar jalur yang MUNCUL:\n";
echo str_repeat("-", 80) . "\n";

foreach($jalurPendaftaran as $jalur) {
    echo sprintf(
        "ID: %-2s | %-40s | Aktif: %-5s | Tampil: %-5s | Urutan: %s\n",
        $jalur->id,
        $jalur->nama,
        $jalur->aktif ? 'Ya' : 'Tidak',
        $jalur->tampil_di_daftar ? 'Ya' : 'Tidak',
        $jalur->urutan
    );
}

echo "\n" . str_repeat("-", 80) . "\n\n";

// Cek jalur yang TIDAK tampil
$jalurTersembunyi = \App\Models\JalurPendaftaran::where('tampil_di_daftar', false)->get();
echo "Jalur yang TIDAK MUNCUL (tampil_di_daftar = false): " . $jalurTersembunyi->count() . "\n";
foreach($jalurTersembunyi as $jalur) {
    echo "  - {$jalur->nama} (ID: {$jalur->id})\n";
}

echo "\n✅ Test Selesai!\n";
echo "\nKesimpulan:\n";
echo "- Jalur dengan 'Tampil di Daftar = Ya' akan MUNCUL di halaman /daftar\n";
echo "- Jalur dengan 'Tampil di Daftar = Tidak' TIDAK AKAN MUNCUL di halaman /daftar\n";
echo "- Status 'Aktif' TIDAK mempengaruhi kemunculan di /daftar\n";
