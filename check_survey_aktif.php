<?php

/**
 * Script untuk mengecek dan mengaktifkan survey tes psikologi
 * Run: php check_survey_aktif.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\TesPsikologiSetting;

echo "\n=== CEK STATUS SURVEY TES PSIKOLOGI ===\n\n";

$setting = TesPsikologiSetting::first();

if (!$setting) {
    echo "❌ Setting tes psikologi belum ada!\n";
    echo "ℹ️  Membuat setting baru...\n";

    $setting = TesPsikologiSetting::create([
        'aktif' => true,
        'survey_aktif' => true,
        'informasi' => 'Informasi tes psikologi',
        'informasi_admin_panitia' => 'Informasi untuk admin dan panitia',
        'rekening_bank_nama' => 'Bank Transfer',
        'rekening_nomor' => '-',
        'rekening_atas_nama' => '-',
        'rekening_keterangan' => '-',
        'survey_informasi' => 'Silakan isi survey tes psikologi'
    ]);

    echo "✅ Setting berhasil dibuat!\n\n";
} else {
    echo "Current Status:\n";
    echo "├─ ID: {$setting->id}\n";
    echo "├─ Tes Psikologi Aktif: " . ($setting->aktif ? '✅ YA' : '❌ TIDAK') . "\n";
    echo "├─ Survey Aktif: " . ($setting->survey_aktif ? '✅ YA' : '❌ TIDAK') . "\n";
    echo "└─ Survey Informasi: " . ($setting->survey_informasi ?: '(kosong)') . "\n\n";

    if (!$setting->survey_aktif) {
        echo "⚠️  Survey belum aktif!\n";
        echo "ℹ️  Mengaktifkan survey...\n";

        $setting->update(['survey_aktif' => true]);

        echo "✅ Survey berhasil diaktifkan!\n\n";
    } else {
        echo "✅ Survey sudah aktif!\n\n";
    }
}

// Cek jumlah pertanyaan survey tes psikologi
use App\Models\SurveyQuestion;

$totalQuestions = SurveyQuestion::where('context', 'tes_psikologi')->count();
$activeQuestions = SurveyQuestion::where('context', 'tes_psikologi')->where('aktif', true)->count();

echo "=== PERTANYAAN SURVEY ===\n\n";
echo "Total Pertanyaan: {$totalQuestions}\n";
echo "Pertanyaan Aktif: {$activeQuestions}\n\n";

if ($totalQuestions === 0) {
    echo "⚠️  Belum ada pertanyaan survey!\n";
    echo "ℹ️  Silakan buat pertanyaan survey di: /admin/tes-psikologi?mainTab=survey\n\n";
} else {
    echo "✅ Sudah ada pertanyaan survey!\n\n";

    echo "Daftar Pertanyaan:\n";
    $questions = SurveyQuestion::where('context', 'tes_psikologi')
        ->orderBy('urutan')
        ->get();

    foreach ($questions as $index => $question) {
        $status = $question->aktif ? '✅' : '❌';
        echo ($index + 1) . ". {$status} {$question->pertanyaan}\n";
        echo "   └─ Tipe: {$question->tipe_input}, Wajib: " . ($question->wajib_diisi ? 'Ya' : 'Tidak') . "\n";
    }
    echo "\n";
}

echo "=== SELESAI ===\n\n";
echo "Silakan refresh halaman admin/tes-psikologi atau panitia-pendaftaran/tes-psikologi\n";
echo "Kolom 'Status Survey' seharusnya sudah muncul!\n\n";
