<?php
/**
 * Script untuk mengecek data wilayah di production
 * Upload ke root project, jalankan: php check_wilayah_data.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

echo "=== CHECKING WILAYAH DATA ===" . PHP_EOL . PHP_EOL;

try {
    // Check Provinces
    $provinceCount = Province::count();
    echo "✓ Tabel 'provinces' exists" . PHP_EOL;
    echo "  Total provinces: {$provinceCount}" . PHP_EOL;

    if ($provinceCount > 0) {
        $sampleProvince = Province::first();
        echo "  Sample: [{$sampleProvince->id}] {$sampleProvince->name}" . PHP_EOL;
    } else {
        echo "  ⚠ WARNING: No provinces data found!" . PHP_EOL;
    }
    echo PHP_EOL;

    // Check Regencies
    $regencyCount = Regency::count();
    echo "✓ Tabel 'regencies' exists" . PHP_EOL;
    echo "  Total regencies: {$regencyCount}" . PHP_EOL;

    if ($regencyCount > 0) {
        $sampleRegency = Regency::first();
        echo "  Sample: [{$sampleRegency->id}] {$sampleRegency->name}" . PHP_EOL;
    } else {
        echo "  ⚠ WARNING: No regencies data found!" . PHP_EOL;
    }
    echo PHP_EOL;

    // Check Districts
    $districtCount = District::count();
    echo "✓ Tabel 'districts' exists" . PHP_EOL;
    echo "  Total districts: {$districtCount}" . PHP_EOL;

    if ($districtCount > 0) {
        $sampleDistrict = District::first();
        echo "  Sample: [{$sampleDistrict->id}] {$sampleDistrict->name}" . PHP_EOL;
    } else {
        echo "  ⚠ WARNING: No districts data found!" . PHP_EOL;
    }
    echo PHP_EOL;

    // Check Villages
    $villageCount = Village::count();
    echo "✓ Tabel 'villages' exists" . PHP_EOL;
    echo "  Total villages: {$villageCount}" . PHP_EOL;

    if ($villageCount > 0) {
        $sampleVillage = Village::first();
        echo "  Sample: [{$sampleVillage->id}] {$sampleVillage->name}" . PHP_EOL;
    } else {
        echo "  ⚠ WARNING: No villages data found!" . PHP_EOL;
    }
    echo PHP_EOL;

    // Summary
    echo "=== SUMMARY ===" . PHP_EOL;
    if ($provinceCount > 0 && $regencyCount > 0 && $districtCount > 0 && $villageCount > 0) {
        echo "✓ All wilayah data exists!" . PHP_EOL;
        echo PHP_EOL;
        echo "Total data:" . PHP_EOL;
        echo "  - Provinces: " . number_format($provinceCount) . PHP_EOL;
        echo "  - Regencies: " . number_format($regencyCount) . PHP_EOL;
        echo "  - Districts: " . number_format($districtCount) . PHP_EOL;
        echo "  - Villages: " . number_format($villageCount) . PHP_EOL;
    } else {
        echo "❌ Some wilayah data is missing!" . PHP_EOL;
        echo PHP_EOL;
        echo "You need to seed wilayah data by running:" . PHP_EOL;
        echo "  php artisan db:seed --class=IndoRegionSeeder" . PHP_EOL;
        echo "  OR" . PHP_EOL;
        echo "  php artisan laravolt:region:seed" . PHP_EOL;
    }

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . PHP_EOL;
    echo PHP_EOL;
    echo "Possible causes:" . PHP_EOL;
    echo "1. Tables not created - run migrations" . PHP_EOL;
    echo "2. Package not installed - run: composer require azishapidin/indoregion" . PHP_EOL;
    echo "3. Database connection issue" . PHP_EOL;
}

echo PHP_EOL;
