<?php
/**
 * Script untuk export data postal_codes dari local ke file SQL
 * Jalankan: php export_postal_codes.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== EXPORT POSTAL CODES DATA ===" . PHP_EOL . PHP_EOL;

try {
    // Check if data exists
    $totalRecords = \DB::table('postal_codes')->count();

    if ($totalRecords === 0) {
        echo "❌ Tidak ada data postal codes untuk di-export!" . PHP_EOL;
        exit(1);
    }

    echo "✓ Found {$totalRecords} postal codes records" . PHP_EOL;
    echo "  Starting export..." . PHP_EOL;
    echo PHP_EOL;

    // Create directory if not exists
    $sqlDir = __DIR__ . '/database/seeders/sql';
    if (!file_exists($sqlDir)) {
        mkdir($sqlDir, 0755, true);
        echo "✓ Created directory: database/seeders/sql/" . PHP_EOL;
    }

    $sqlFile = $sqlDir . '/postal_codes.sql';

    // Start writing SQL file
    $handle = fopen($sqlFile, 'w');

    // Write header
    fwrite($handle, "-- Postal Codes Data for Indonesia\n");
    fwrite($handle, "-- Total records: {$totalRecords}\n");
    fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n\n");

    // Truncate table first
    fwrite($handle, "TRUNCATE TABLE postal_codes RESTART IDENTITY CASCADE;\n\n");

    // Get data in chunks
    $chunkSize = 500;
    $currentChunk = 0;
    $totalChunks = ceil($totalRecords / $chunkSize);

    \DB::table('postal_codes')->orderBy('id')->chunk($chunkSize, function ($records) use ($handle, &$currentChunk, $totalChunks) {
        $currentChunk++;

        $values = [];
        foreach ($records as $record) {
            $urban = addslashes($record->urban);
            $subDistrict = addslashes($record->sub_district);
            $city = addslashes($record->city);
            $provinceCode = $record->province_code;
            $postalCode = $record->postal_code;

            $values[] = "('{$urban}', '{$subDistrict}', '{$city}', {$provinceCode}, '{$postalCode}')";
        }

        // Write INSERT statement
        $valuesString = implode(",\n    ", $values);
        fwrite($handle, "INSERT INTO postal_codes (urban, sub_district, city, province_code, postal_code) VALUES\n    ");
        fwrite($handle, $valuesString);
        fwrite($handle, ";\n\n");

        // Progress indicator
        $progress = round(($currentChunk / $totalChunks) * 100);
        echo "\r  Progress: {$progress}% ({$currentChunk}/{$totalChunks} chunks)";
    });

    fclose($handle);

    echo PHP_EOL;
    echo PHP_EOL;
    echo "=== EXPORT COMPLETE ===" . PHP_EOL;
    echo "✓ File saved: database/seeders/sql/postal_codes.sql" . PHP_EOL;
    echo "✓ Total records: " . number_format($totalRecords) . PHP_EOL;
    echo "✓ File size: " . number_format(filesize($sqlFile) / 1024 / 1024, 2) . " MB" . PHP_EOL;
    echo PHP_EOL;
    echo "Next steps:" . PHP_EOL;
    echo "1. Upload file ke production: database/seeders/sql/postal_codes.sql" . PHP_EOL;
    echo "2. Jalankan: php artisan db:seed --class=PostalCodeSeeder" . PHP_EOL;

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}

echo PHP_EOL;
