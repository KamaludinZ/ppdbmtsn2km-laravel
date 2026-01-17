<?php

// Script untuk mengecek log Laravel terbaru
$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "Log file tidak ditemukan!\n";
    exit;
}

// Baca 100 baris terakhir
$lines = file($logFile);
$lastLines = array_slice($lines, -100);

echo "=== 100 BARIS TERAKHIR LOG LARAVEL ===\n\n";

foreach ($lastLines as $line) {
    // Highlight baris yang berisi SAVE DATA SISWA atau error
    if (stripos($line, 'SAVE DATA SISWA') !== false) {
        echo "\033[32m" . $line . "\033[0m"; // Green
    } elseif (stripos($line, 'error') !== false || stripos($line, 'exception') !== false) {
        echo "\033[31m" . $line . "\033[0m"; // Red
    } elseif (stripos($line, 'Validation') !== false) {
        echo "\033[33m" . $line . "\033[0m"; // Yellow
    } else {
        echo $line;
    }
}

echo "\n=== END OF LOG ===\n";
