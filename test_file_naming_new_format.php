<?php
/**
 * Test Script untuk File Naming Format Baru
 *
 * Format Baru: {jenis}-{nomor}-{nama}-{datestamp}.{ext}
 * Contoh: rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf
 */

require __DIR__ . '/vendor/autoload.php';

use App\Services\FileNamingService;

// Test cases
$testCases = [
    [
        'jenis' => 'rapor',
        'nomor' => 'PPDB2024001',
        'ext' => 'pdf',
        'nama' => 'Ahmad Fulan Bin Zubair',
        'expected_pattern' => 'rapor-PPDB2024001-Ahmad_Fulan_Bin_Zubair-20260131.pdf'
    ],
    [
        'jenis' => 'foto_profil',
        'nomor' => 'PPDB2024002',
        'ext' => 'webp',
        'nama' => 'Siti Aisyah Binti Abdullah',
        'expected_pattern' => 'foto_profil-PPDB2024002-Siti_Aisyah_Binti_Abdullah-20260131.webp'
    ],
    [
        'jenis' => 'bukti_daftar_ulang',
        'nomor' => 'PPDB2024003',
        'ext' => 'jpg',
        'nama' => 'Muhammad Al-Fatih',
        'expected_pattern' => 'bukti_daftar_ulang-PPDB2024003-Muhammad_AlFatih-20260131.jpg'
    ],
    [
        'jenis' => 'sertifikat_prestasi',
        'nomor' => 'PPDB2024004',
        'ext' => 'pdf',
        'nama' => 'Fatimah Az-Zahra',
        'expected_pattern' => 'sertifikat_prestasi-PPDB2024004-Fatimah_AzZahra-20260131.pdf'
    ],
];

echo "===========================================\n";
echo "TEST FILE NAMING FORMAT BARU\n";
echo "===========================================\n\n";

$passCount = 0;
$failCount = 0;

foreach ($testCases as $index => $test) {
    echo "Test Case " . ($index + 1) . ":\n";
    echo "  Jenis: {$test['jenis']}\n";
    echo "  Nomor: {$test['nomor']}\n";
    echo "  Nama: {$test['nama']}\n";
    echo "  Extension: {$test['ext']}\n";

    $result = FileNamingService::generateFileName(
        $test['jenis'],
        $test['nomor'],
        $test['ext'],
        $test['nama']
    );

    echo "  Result: $result\n";

    // Check format
    $isCorrect = true;

    // Check separator (should use dash -)
    if (strpos($result, '_') === false || strpos($result, '-') === false) {
        echo "  ❌ FAIL: Missing dash separator or underscore in name\n";
        $isCorrect = false;
        $failCount++;
    }

    // Check contains nama
    $namaClean = str_replace([' ', '-'], ['_', ''], $test['nama']);
    if (strpos($result, substr($namaClean, 0, 20)) === false) {
        echo "  ⚠️  WARNING: Nama might not be included correctly\n";
    }

    // Check datestamp format (8 digits only, no time)
    if (preg_match('/-(\d{8})\.' . $test['ext'] . '$/', $result, $matches)) {
        echo "  ✅ PASS: Datestamp format correct (8 digits): {$matches[1]}\n";
        $passCount++;
    } else {
        echo "  ❌ FAIL: Datestamp not in correct format (should be YYYYMMDD)\n";
        $isCorrect = false;
        $failCount++;
    }

    // Check no time in filename
    if (preg_match('/_\d{6}\./', $result)) {
        echo "  ❌ FAIL: Time found in filename (should only have date)\n";
        $failCount++;
    } else {
        echo "  ✅ PASS: No time in filename\n";
        $passCount++;
    }

    echo "\n";
}

echo "===========================================\n";
echo "SUMMARY\n";
echo "===========================================\n";
echo "✅ Passed: $passCount\n";
echo "❌ Failed: $failCount\n";

if ($failCount === 0) {
    echo "\n🎉 All tests passed! File naming format is correct.\n";
} else {
    echo "\n⚠️  Some tests failed. Please check the implementation.\n";
}

echo "\n===========================================\n";
echo "EXPECTED FORMAT EXAMPLES:\n";
echo "===========================================\n";
echo "rapor-PPDB2024001-Ahmad_Fulan-20260131.pdf\n";
echo "foto_profil-PPDB2024002-Siti_Aisyah-20260131.webp\n";
echo "bukti_daftar_ulang-PPDB2024003-Muhammad-20260131.jpg\n";
echo "===========================================\n";
