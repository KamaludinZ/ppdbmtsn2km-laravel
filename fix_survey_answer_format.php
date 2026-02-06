<?php

/**
 * Fix Survey Answer Format
 *
 * Problem: Radio/Select answers stored as array [id] should be single id
 * Solution: Convert [id] → id for radio/select questions
 *
 * Date: February 6, 2026
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;

echo "=======================================================================\n";
echo "FIX: Survey Answer Format (Array → Single Value for Radio/Select)\n";
echo "=======================================================================\n\n";

$DRY_RUN = false; // Set true to test without updates

if ($DRY_RUN) {
    echo "🔶 DRY RUN MODE\n\n";
}

$answers = SurveyAnswer::with('question')->get();
echo "Total Answers: {$answers->count()}\n\n";

$stats = [
    'total' => $answers->count(),
    'fixed' => 0,
    'skipped' => 0
];

foreach ($answers as $answer) {
    $question = $answer->question;
    $jawaban = $answer->jawaban;

    // Only process radio/select
    if (!in_array($question->tipe_input, ['radio', 'select'])) {
        continue;
    }

    // Check if stored as array with single value
    if (is_array($jawaban) && count($jawaban) === 1) {
        $singleValue = $jawaban[0];

        echo "Pendaftar ID: {$answer->pendaftar_id}\n";
        echo "Question ID: Q{$question->id} ({$question->tipe_input})\n";
        echo "Before: " . json_encode($jawaban) . " (array)\n";
        echo "After:  {$singleValue} (single value)\n";

        if (!$DRY_RUN) {
            try {
                $answer->update(['jawaban' => $singleValue]);
                echo "Status: ✅ FIXED\n\n";
                $stats['fixed']++;
            } catch (\Exception $e) {
                echo "Status: ❌ ERROR - " . $e->getMessage() . "\n\n";
            }
        } else {
            echo "Status: 🔶 WOULD FIX (dry run)\n\n";
            $stats['fixed']++;
        }
    } else {
        $stats['skipped']++;
    }
}

echo "=======================================================================\n";
echo "SUMMARY\n";
echo "=======================================================================\n";
echo "Total: {$stats['total']}\n";
echo "Fixed: {$stats['fixed']}\n";
echo "Skipped: {$stats['skipped']}\n";

if ($DRY_RUN) {
    echo "\n🔶 This was a DRY RUN. Set \$DRY_RUN = false to apply.\n";
}
