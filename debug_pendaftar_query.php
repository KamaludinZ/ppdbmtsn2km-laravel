<?php

use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "Fetching Pendaftar records...\n";

try {
    $count = Pendaftar::count();
    echo "Total Pendaftar: $count\n";

    $pendaftar = Pendaftar::select('id', 'nama_lengkap', 'status_pendaftaran', 'nomor_pendaftaran', 'nisn')->get();

    foreach ($pendaftar as $p) {
        echo "ID: {$p->id} | Nama: {$p->nama_lengkap} | Status: {$p->status_pendaftaran} | No. Daf: {$p->nomor_pendaftaran} | NISN: {$p->nisn}\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

