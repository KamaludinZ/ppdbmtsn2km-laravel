<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SURVEY QUESTIONS & OPTIONS (Tes Psikologi) ===\n\n";

$questions = \App\Models\SurveyQuestion::where('context', 'tes_psikologi')
    ->with('options')
    ->orderBy('urutan')
    ->get();

foreach ($questions as $q) {
    echo "Q{$q->id} (Type: {$q->tipe_input}, Urutan: {$q->urutan})\n";
    echo "Pertanyaan: {$q->pertanyaan}\n";
    echo "Options:\n";

    foreach ($q->options->sortBy('urutan') as $opt) {
        echo sprintf("  - ID:%-3d  VALUE:[%-20s]  LABEL:%-30s  URUTAN:%d\n",
            $opt->id,
            $opt->value,
            $opt->label,
            $opt->urutan
        );
    }
    echo "\n";
}

echo "\n=== SURVEY ANSWERS (Pendaftar: REG-26010022) ===\n\n";

$pendaftar = \App\Models\Pendaftar::where('nomor_pendaftaran', 'REG-26010022')->first();

if ($pendaftar) {
    $answers = \App\Models\SurveyAnswer::where('pendaftar_id', $pendaftar->id)
        ->whereHas('question', function($q) {
            $q->where('context', 'tes_psikologi');
        })
        ->with('question')
        ->get();

    foreach ($answers as $ans) {
        echo "Q{$ans->survey_question_id}: {$ans->question->pertanyaan}\n";
        echo "  Jawaban (raw): " . json_encode($ans->jawaban) . "\n";

        // Try to find matching options
        $questionOptions = \App\Models\SurveyQuestionOption::where('survey_question_id', $ans->survey_question_id)
            ->orderBy('urutan')
            ->get();

        echo "  Available options for this question:\n";
        foreach ($questionOptions as $opt) {
            echo sprintf("    - ID:%-3d VALUE:[%-20s] LABEL:%-30s\n", $opt->id, $opt->value, $opt->label);
        }

        echo "\n";
    }
} else {
    echo "Pendaftar REG-26010022 not found\n";
}
