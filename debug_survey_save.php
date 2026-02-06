<?php

/**
 * DEBUG SCRIPT - Analyze Survey Save Logic
 *
 * This will help us understand what's happening in $surveyAnswers
 * when simpanSurvey() is called
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\Pendaftar;

echo "=======================================================================\n";
echo "DEBUG: Survey Save Logic Analysis\n";
echo "=======================================================================\n\n";

// Get the test pendaftar (JPNA-26020002)
$pendaftar = Pendaftar::where('nomor_pendaftaran', 'JPNA-26020002')->first();

if (!$pendaftar) {
    echo "❌ Pendaftar not found!\n";
    exit;
}

echo "Pendaftar: {$pendaftar->nomor_pendaftaran}\n";
echo "Nama: {$pendaftar->nama_lengkap}\n\n";

// Get all survey answers for this pendaftar
$answers = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
    ->whereHas('question', function($q) {
        $q->where('context', 'tes_psikologi');
    })
    ->with('question')
    ->orderBy('survey_question_id')
    ->get();

echo "Total Answers: {$answers->count()}\n\n";

echo "=======================================================================\n";
echo "CURRENT ANSWERS IN DATABASE\n";
echo "=======================================================================\n\n";

$uniqueValues = [];
foreach ($answers as $answer) {
    $jawaban = $answer->jawaban;
    $jawabanStr = is_array($jawaban) ? '[' . implode(',', $jawaban) . ']' : $jawaban;

    echo "Q{$answer->survey_question_id}: {$jawabanStr}\n";

    // Track unique values
    if (is_array($jawaban)) {
        foreach ($jawaban as $val) {
            $uniqueValues[$val] = ($uniqueValues[$val] ?? 0) + 1;
        }
    } else {
        $uniqueValues[$jawaban] = ($uniqueValues[$jawaban] ?? 0) + 1;
    }
}

echo "\n";
echo "=======================================================================\n";
echo "UNIQUE VALUES FREQUENCY\n";
echo "=======================================================================\n\n";

arsort($uniqueValues);
foreach ($uniqueValues as $value => $count) {
    $percentage = round(($count / $answers->count()) * 100, 1);
    echo "ID {$value}: Used {$count} times ({$percentage}%)\n";
}

echo "\n";
echo "=======================================================================\n";
echo "ANALYSIS\n";
echo "=======================================================================\n\n";

// Check if all answers are the same (BUG SIGNATURE)
if (count($uniqueValues) <= 3) {
    echo "🚨 BUG DETECTED!\n";
    echo "   Most answers have the same value (overwrite bug)\n\n";

    // Find the dominant value
    $dominantValue = array_key_first($uniqueValues);
    $dominantCount = $uniqueValues[$dominantValue];

    echo "Dominant Value: {$dominantValue}\n";
    echo "Used in: {$dominantCount}/{$answers->count()} questions\n\n";

    // Check which questions have this value
    $questionsWithDominantValue = $answers->filter(function($answer) use ($dominantValue) {
        $jawaban = $answer->jawaban;
        if (is_array($jawaban)) {
            return in_array($dominantValue, $jawaban);
        }
        return $jawaban == $dominantValue;
    });

    echo "Questions with ID {$dominantValue}:\n";
    foreach ($questionsWithDominantValue as $answer) {
        $question = $answer->question;
        echo "   Q{$question->id}: " . substr($question->pertanyaan, 0, 50) . "...\n";

        // Check if this ID is valid for this question
        $validOptions = $question->options->pluck('id')->toArray();
        if (!in_array($dominantValue, $validOptions)) {
            echo "      ⚠️  ID {$dominantValue} NOT VALID for this question!\n";
            echo "      Valid IDs: " . implode(', ', $validOptions) . "\n";
        }
    }

} else {
    echo "✅ Answers look diverse (no obvious overwrite)\n";
}

echo "\n";
echo "=======================================================================\n";
echo "RECOMMENDATIONS\n";
echo "=======================================================================\n\n";

if (count($uniqueValues) <= 3) {
    echo "1. This data is corrupted by the overwrite bug\n";
    echo "2. Options:\n";
    echo "   A. Delete all answers for this pendaftar (let them re-submit)\n";
    echo "   B. Try to restore from backup if available\n\n";

    echo "SQL to delete:\n";
    echo "DELETE FROM survey_answers\n";
    echo "WHERE pendaftar_id = {$pendaftar->id}\n";
    echo "  AND EXISTS (\n";
    echo "    SELECT 1 FROM survey_questions sq\n";
    echo "    WHERE sq.id = survey_answers.survey_question_id\n";
    echo "    AND sq.context = 'tes_psikologi'\n";
    echo "  );\n\n";
} else {
    echo "Data looks OK. No action needed.\n\n";
}

echo "=======================================================================\n";
echo "DEBUG COMPLETE\n";
echo "=======================================================================\n";
