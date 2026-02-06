<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CLEAR CHECKBOX CORRUPT DATA (boolean values) ===\n\n";

// Find all survey answers with boolean values
$corruptAnswers = \App\Models\SurveyAnswer::whereHas('question', function($q) {
    $q->where('context', 'tes_psikologi')
      ->where('tipe_input', 'checkbox');
})
->get();

$fixed = 0;
$deleted = 0;

foreach ($corruptAnswers as $answer) {
    $jawaban = $answer->jawaban;
    $originalJawaban = json_encode($jawaban);

    // Filter out boolean values
    $cleaned = array_filter($jawaban, function($val) {
        return !is_bool($val) && $val !== '' && $val !== null;
    });

    // Re-index
    $cleaned = array_values($cleaned);

    // Check if data was corrupt
    if ($originalJawaban !== json_encode($cleaned)) {
        echo "Pendaftar ID {$answer->pendaftar_id} - Question ID {$answer->survey_question_id}\n";
        echo "  Before: {$originalJawaban}\n";
        echo "  After:  " . json_encode($cleaned) . "\n";

        if (empty($cleaned)) {
            // If all values were boolean, delete the answer
            $answer->delete();
            $deleted++;
            echo "  Action: DELETED (no valid data left)\n";
        } else {
            // Update with cleaned data
            $answer->update(['jawaban' => $cleaned]);
            $fixed++;
            echo "  Action: FIXED\n";
        }
        echo "\n";
    }
}

echo "===========================================\n";
echo "Summary:\n";
echo "  - Fixed:   {$fixed} answers\n";
echo "  - Deleted: {$deleted} answers\n";
echo "===========================================\n";
