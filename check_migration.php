<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$total = DB::table('pendaftar')->whereNotNull('telepon_ortu')->count();
$sample = DB::table('pendaftar')->select('id', 'nama_lengkap', 'telepon', 'telepon_ortu')->limit(3)->get();

echo "Total pendaftar dengan telepon_ortu: $total\n\n";
echo "Sample data:\n";
foreach ($sample as $row) {
    echo "ID: {$row->id}, Nama: {$row->nama_lengkap}\n";
    echo "  telepon: {$row->telepon}\n";
    echo "  telepon_ortu: {$row->telepon_ortu}\n\n";
}
