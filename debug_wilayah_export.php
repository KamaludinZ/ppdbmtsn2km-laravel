<?php

/**
 * DEBUG SCRIPT - Check Wilayah Data di Production
 *
 * Upload file ini ke root production, lalu akses via browser:
 * https://pmbm.mtsn2kotamalang.sch.id/debug_wilayah_export.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>DEBUG: Wilayah Data Export</h1>";
echo "<pre>";

// 1. Check sample pendaftar data
echo "\n=== SAMPLE PENDAFTAR DATA ===\n";
$pendaftar = \App\Models\Pendaftar::whereNotNull('ayah_provinsi')
    ->orWhereNotNull('ibu_provinsi')
    ->orWhereNotNull('siswa_provinsi')
    ->first();

if ($pendaftar) {
    echo "Pendaftar ID: {$pendaftar->id}\n";
    echo "Nama: {$pendaftar->nama_lengkap}\n\n";

    echo "=== ALAMAT AYAH ===\n";
    echo "ayah_status: " . ($pendaftar->ayah_status ?? 'NULL') . "\n";
    echo "ayah_tinggal_luar_negeri: " . ($pendaftar->ayah_tinggal_luar_negeri ? 'true' : 'false') . "\n";
    echo "ayah_provinsi: " . var_export($pendaftar->ayah_provinsi, true) . "\n";
    echo "ayah_kabupaten: " . var_export($pendaftar->ayah_kabupaten, true) . "\n";
    echo "ayah_kecamatan: " . var_export($pendaftar->ayah_kecamatan, true) . "\n";
    echo "ayah_kelurahan: " . var_export($pendaftar->ayah_kelurahan, true) . "\n";

    echo "\n=== ALAMAT IBU ===\n";
    echo "ibu_status: " . ($pendaftar->ibu_status ?? 'NULL') . "\n";
    echo "ibu_sama_dengan_ayah: " . ($pendaftar->ibu_sama_dengan_ayah ? 'true' : 'false') . "\n";
    echo "ibu_tinggal_luar_negeri: " . ($pendaftar->ibu_tinggal_luar_negeri ? 'true' : 'false') . "\n";
    echo "ibu_provinsi: " . var_export($pendaftar->ibu_provinsi, true) . "\n";
    echo "ibu_kabupaten: " . var_export($pendaftar->ibu_kabupaten, true) . "\n";
    echo "ibu_kecamatan: " . var_export($pendaftar->ibu_kecamatan, true) . "\n";
    echo "ibu_kelurahan: " . var_export($pendaftar->ibu_kelurahan, true) . "\n";

    echo "\n=== ALAMAT SISWA ===\n";
    echo "status_tempat_tinggal: " . ($pendaftar->status_tempat_tinggal ?? 'NULL') . "\n";
    echo "siswa_provinsi: " . var_export($pendaftar->siswa_provinsi, true) . "\n";
    echo "siswa_kabupaten: " . var_export($pendaftar->siswa_kabupaten, true) . "\n";
    echo "siswa_kecamatan: " . var_export($pendaftar->siswa_kecamatan, true) . "\n";
    echo "siswa_kelurahan: " . var_export($pendaftar->siswa_kelurahan, true) . "\n";
    echo "provinsi (old): " . var_export($pendaftar->provinsi, true) . "\n";
    echo "kabupaten (old): " . var_export($pendaftar->kabupaten, true) . "\n";
    echo "kecamatan (old): " . var_export($pendaftar->kecamatan, true) . "\n";
    echo "desa (old): " . var_export($pendaftar->desa, true) . "\n";

    // 2. Check tabel indonesia_provinces
    echo "\n\n=== CHECK TABEL INDONESIA_PROVINCES ===\n";
    $provinces = \DB::table('indonesia_provinces')->limit(3)->get(['id', 'name']);
    echo "Total provinces: " . \DB::table('indonesia_provinces')->count() . "\n";
    echo "Sample data:\n";
    foreach ($provinces as $prov) {
        echo "  ID: " . var_export($prov->id, true) . " => Name: {$prov->name}\n";
    }

    // 3. Try to lookup ayah_provinsi
    if ($pendaftar->ayah_provinsi) {
        echo "\n\n=== LOOKUP ayah_provinsi ===\n";
        $ayahProvId = trim((string)$pendaftar->ayah_provinsi);
        echo "Looking for ID: '{$ayahProvId}' (length: " . strlen($ayahProvId) . ")\n";

        $province = \DB::table('indonesia_provinces')->where('id', $ayahProvId)->first();
        if ($province) {
            echo "FOUND: {$province->name}\n";
        } else {
            echo "NOT FOUND in indonesia_provinces\n";

            // Try code
            $provinceByCode = \DB::table('indonesia_provinces')->where('code', $ayahProvId)->first();
            if ($provinceByCode) {
                echo "FOUND BY CODE: {$provinceByCode->name}\n";
            } else {
                echo "NOT FOUND by code either\n";
            }
        }
    }

    // 4. Check table structure
    echo "\n\n=== INDONESIA_PROVINCES TABLE STRUCTURE ===\n";
    $columns = \DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'indonesia_provinces' AND table_schema = 'public'");
    foreach ($columns as $col) {
        echo "  {$col->column_name}: {$col->data_type}\n";
    }

} else {
    echo "NO PENDAFTAR DATA FOUND with provinsi!\n";
}

echo "\n\n=== DONE ===\n";
echo "</pre>";
