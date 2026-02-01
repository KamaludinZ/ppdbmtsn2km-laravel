<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pendaftar;

echo "\n========================================\n";
echo "   DATA COMPLETENESS CHECK\n";
echo "========================================\n\n";

// Get all pendaftar
$pendaftars = Pendaftar::with(['prestasis', 'beasiswas', 'pendidikan_lain'])->get();

echo "Total Pendaftar: " . $pendaftars->count() . "\n\n";

if ($pendaftars->isEmpty()) {
    echo "⚠️  Tidak ada data pendaftar di database!\n\n";
    exit;
}

// Track completeness
$completeness = [
    'data_siswa' => [],
    'data_ayah' => [],
    'data_ibu' => [],
    'data_wali' => [],
    'data_alamat_ayah' => [],
    'data_alamat_ibu' => [],
    'data_alamat_wali' => [],
    'data_alamat_siswa' => [],
    'prestasi' => [],
    'beasiswa' => [],
    'pendidikan_lain' => [],
];

echo "Checking each pendaftar...\n";
echo "===========================================\n\n";

foreach ($pendaftars as $pendaftar) {
    echo "📋 Pendaftar: {$pendaftar->nama_lengkap} (ID: {$pendaftar->id})\n";
    echo "   Jalur: " . ($pendaftar->jalurPendaftaran->nama ?? 'N/A') . "\n";
    echo "   Status: {$pendaftar->status_pendaftaran}\n";

    // Get completeness details
    $details = $pendaftar->getEmisCompletenessDetails();

    echo "\n   Kelengkapan Data:\n";
    echo "   " . str_repeat("-", 50) . "\n";

    foreach ($details['sections'] as $key => $section) {
        $percentage = $section['percentage'];
        $filled = $section['filled'];
        $total = $section['total'];

        // Progress bar
        $barLength = 20;
        $filledBars = round(($percentage / 100) * $barLength);
        $emptyBars = $barLength - $filledBars;
        $bar = str_repeat("█", $filledBars) . str_repeat("░", $emptyBars);

        // Color indicator
        if ($percentage == 100) {
            $indicator = "✅";
            // Add to completeness tracking
            if (isset($completeness[$key])) {
                $completeness[$key][] = $pendaftar->id;
            }
        } elseif ($percentage >= 80) {
            $indicator = "⚠️ ";
        } else {
            $indicator = "❌";
        }

        $label = str_pad($section['label'], 25);
        echo "   {$indicator} {$label} [{$bar}] {$percentage}% ({$filled}/{$total})\n";
    }

    echo "\n   Overall: {$details['percentage']}% ({$details['filled_fields']}/{$details['total_fields']})\n";
    echo "\n" . str_repeat("=", 60) . "\n\n";
}

// Summary
echo "\n========================================\n";
echo "   SUMMARY - 100% Complete Count\n";
echo "========================================\n\n";

foreach ($completeness as $key => $ids) {
    $count = count($ids);
    $label = ucwords(str_replace('_', ' ', $key));
    $label = str_pad($label, 25);

    if ($count > 0) {
        echo "✅ {$label}: {$count} pendaftar\n";
        echo "   IDs: " . implode(', ', $ids) . "\n\n";
    } else {
        echo "❌ {$label}: 0 pendaftar\n\n";
    }
}

echo "========================================\n";
echo "Selesai!\n";
echo "========================================\n\n";
