<?php
/**
 * Script untuk mengecek data postal codes di production
 * Upload ke root project, jalankan: php check_postal_codes.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CHECKING POSTAL CODES DATA ===" . PHP_EOL . PHP_EOL;

try {
    // Check if table exists (PostgreSQL compatible)
    $tableExists = \DB::select("SELECT to_regclass('public.postal_codes') IS NOT NULL as exists");

    if (empty($tableExists) || !$tableExists[0]->exists) {
        echo "❌ Tabel 'postal_codes' tidak ditemukan!" . PHP_EOL;
        echo PHP_EOL;
        echo "Solusi:" . PHP_EOL;
        echo "1. Jalankan migration: php artisan migrate" . PHP_EOL;
        echo "2. Import data postal_codes dari local" . PHP_EOL;
        exit(1);
    }

    echo "✓ Tabel 'postal_codes' exists" . PHP_EOL;

    // Check total records
    $totalRecords = \DB::table('postal_codes')->count();
    echo "  Total records: " . number_format($totalRecords) . PHP_EOL;
    echo PHP_EOL;

    if ($totalRecords === 0) {
        echo "❌ Tabel 'postal_codes' KOSONG!" . PHP_EOL;
        echo PHP_EOL;
        echo "Data kode pos diperlukan untuk auto-fill kode pos saat memilih kelurahan." . PHP_EOL;
        echo PHP_EOL;
        echo "Solusi:" . PHP_EOL;
        echo "1. Export data dari local: php export_postal_codes.php" . PHP_EOL;
        echo "2. Upload file postal_codes.sql ke production" . PHP_EOL;
        echo "3. Import: mysql -u username -p database_name < postal_codes.sql" . PHP_EOL;
        exit(1);
    }

    // Show sample data
    $samples = \DB::table('postal_codes')->limit(5)->get();

    echo "✓ Data postal codes tersedia!" . PHP_EOL;
    echo PHP_EOL;
    echo "Sample data:" . PHP_EOL;
    echo str_repeat("-", 80) . PHP_EOL;
    printf("%-25s %-25s %-20s %s\n", "KELURAHAN", "KECAMATAN", "KOTA", "KODE POS");
    echo str_repeat("-", 80) . PHP_EOL;

    foreach ($samples as $sample) {
        printf(
            "%-25s %-25s %-20s %s\n",
            substr($sample->urban, 0, 24),
            substr($sample->sub_district, 0, 24),
            substr($sample->city, 0, 19),
            $sample->postal_code
        );
    }

    echo str_repeat("-", 80) . PHP_EOL;
    echo PHP_EOL;

    // Check by province
    echo "Breakdown by Province Code:" . PHP_EOL;
    $byProvince = \DB::table('postal_codes')
        ->select('province_code', \DB::raw('COUNT(*) as total'))
        ->groupBy('province_code')
        ->orderBy('province_code')
        ->limit(10)
        ->get();

    foreach ($byProvince as $prov) {
        echo sprintf("  Province %02d: %s records\n", $prov->province_code, number_format($prov->total));
    }

    if ($byProvince->count() > 10) {
        echo "  ... (showing first 10 provinces)" . PHP_EOL;
    }

    echo PHP_EOL;
    echo "=== SUMMARY ===" . PHP_EOL;
    echo "✓ Tabel postal_codes: OK" . PHP_EOL;
    echo "✓ Total records: " . number_format($totalRecords) . PHP_EOL;
    echo "✓ Auto-fill kode pos: READY" . PHP_EOL;

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo PHP_EOL;
    echo "Stacktrace:" . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

echo PHP_EOL;
