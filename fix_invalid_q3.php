<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIX INVALID Q3 ANSWER ===\n\n";

$pendaftar = \App\Models\Pendaftar::where('nomor_pendaftaran', 'REG-26010022')->first();

if (!$pendaftar) {
    echo "Pendaftar not found\n";
    exit;
}

// Find Q3 answer
$q3Answer = \App\Models\SurveyAnswer::where('pendaftar_id', $pendaftar->id)
    ->where('survey_question_id', 3)
    ->first();

if ($q3Answer) {
    echo "Found Q3 answer:\n";
    echo "  Jawaban: " . json_encode($q3Answer->jawaban) . "\n";
    echo "  Problem: Jawaban \"Tidak\" tidak ada di options Q3\n\n";

    echo "Options Q3:\n";
    $options = \App\Models\SurveyQuestionOption::where('survey_question_id', 3)->get();
    foreach ($options as $opt) {
        echo "  - VALUE: \"{$opt->value}\"  LABEL: \"{$opt->label}\"\n";
    }

    echo "\nDeleting invalid answer...\n";
    $q3Answer->delete();
    echo "✅ Deleted!\n\n";

    echo "User perlu isi ulang Q3 dengan jawaban yang benar:\n";
    echo "  - opsi A, atau\n";
    echo "  - opsi B\n";
} else {
    echo "Q3 answer not found for this pendaftar\n";
}
