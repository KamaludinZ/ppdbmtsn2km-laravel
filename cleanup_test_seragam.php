<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CLEANING UP TEST SERAGAM DATA ===\n\n";

// Delete seragam records for test pendaftar
$deleted = DB::table('seragam')
    ->where('pendaftar_id', 6)  // Test pendaftar ID
    ->delete();

echo "✅ Deleted {$deleted} seragam record(s) for test pendaftar\n\n";

echo "=== READY FOR FRESH TESTING ===\n";
echo "Sekarang coba akses halaman daftar ulang lagi!\n";
