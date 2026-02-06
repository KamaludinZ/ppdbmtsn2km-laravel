<?php

/**
 * Migration Script: Convert Survey Answers from VALUE to OPTION ID
 *
 * Purpose: Mengubah existing survey answers dari menyimpan VALUE (semantic data)
 *          menjadi menyimpan OPTION ID untuk data integrity.
 *
 * Benefits:
 * - Admin dapat ubah LABEL/VALUE kapan saja tanpa corrupt existing answers
 * - Lebih relational dan normalized
 * - ID tidak akan berubah (stable reference)
 *
 * Date: February 5, 2026
 * Context: BOTH Survey Systems (Survey Umum + Tes Psikologi)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;

echo "=======================================================================\n";
echo "MIGRATION: Survey Answers VALUE → OPTION ID\n";
echo "=======================================================================\n\n";

// Get all survey answers for BOTH contexts
$answers = SurveyAnswer::with('question.options')->get();

echo "Total Answers to Migrate (All Surveys): {$answers->count()}\n\n";

$stats = [
    'total' => $answers->count(),
    'migrated' => 0,
    'skipped_already_id' => 0,
    'skipped_text_input' => 0,
    'failed_no_match' => 0,
    'errors' => []
];

foreach ($answers as $answer) {
    $question = $answer->question;
    $jawaban = $answer->jawaban;

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Pendaftar ID: {$answer->pendaftar_id}\n";
    echo "Question ID: {$question->id} - {$question->pertanyaan}\n";
    echo "Question Type: {$question->tipe_input}\n";
    echo "Current Answer (RAW): " . json_encode($jawaban) . "\n";

    // Skip text/textarea inputs (no options to migrate)
    if (in_array($question->tipe_input, ['text', 'textarea'])) {
        echo "Status: ⏭️  SKIPPED (text input, no options)\n\n";
        $stats['skipped_text_input']++;
        continue;
    }

    // Get options for this question
    $options = SurveyQuestionOption::where('survey_question_id', $question->id)->get();

    if ($options->isEmpty()) {
        echo "Status: ⚠️  WARNING - No options found for this question!\n\n";
        $stats['failed_no_match']++;
        $stats['errors'][] = [
            'pendaftar_id' => $answer->pendaftar_id,
            'question_id' => $question->id,
            'reason' => 'No options found'
        ];
        continue;
    }

    // Convert jawaban to array
    $jawabanArray = is_array($jawaban) ? $jawaban : [$jawaban];
    $newJawabanArray = [];
    $needsMigration = false;

    foreach ($jawabanArray as $value) {
        // Skip empty values
        if (empty($value) || $value === '-') {
            continue;
        }

        $normalizedValue = trim((string)$value);

        // Check if already ID (numeric)
        if (is_numeric($normalizedValue)) {
            // Verify ID exists in options
            $option = $options->firstWhere('id', (int)$normalizedValue);
            if ($option) {
                echo "  ✅ Value '{$normalizedValue}' is already OPTION ID (label: {$option->label})\n";
                $newJawabanArray[] = (int)$normalizedValue;
                continue;
            }
        }

        // Need to find Option ID from VALUE
        $needsMigration = true;
        $option = null;

        // Method 1: Exact match by VALUE
        $option = $options->first(function($opt) use ($normalizedValue) {
            return trim((string)$opt->value) === $normalizedValue;
        });

        // Method 2: Case-insensitive match by VALUE
        if (!$option) {
            $option = $options->first(function($opt) use ($normalizedValue) {
                return strcasecmp(trim((string)$opt->value), $normalizedValue) === 0;
            });
        }

        // Method 3: Match by LABEL (fallback)
        if (!$option) {
            $option = $options->first(function($opt) use ($normalizedValue) {
                return strcasecmp(trim((string)$opt->label), $normalizedValue) === 0;
            });
        }

        if ($option) {
            echo "  🔄 Converting VALUE '{$normalizedValue}' → OPTION ID {$option->id} (label: {$option->label})\n";
            $newJawabanArray[] = $option->id;
        } else {
            echo "  ❌ FAILED: Cannot find option for VALUE '{$normalizedValue}'\n";
            echo "     Available options:\n";
            foreach ($options as $opt) {
                echo "       - ID:{$opt->id} VALUE:\"{$opt->value}\" LABEL:\"{$opt->label}\"\n";
            }
            $stats['failed_no_match']++;
            $stats['errors'][] = [
                'pendaftar_id' => $answer->pendaftar_id,
                'question_id' => $question->id,
                'value' => $normalizedValue,
                'reason' => 'Option not found'
            ];
            // Keep original value as fallback
            $newJawabanArray[] = $normalizedValue;
        }
    }

    // Update answer if migration needed
    if ($needsMigration && !empty($newJawabanArray)) {
        // For single-select (radio/select), use single value
        if (in_array($question->tipe_input, ['radio', 'select'])) {
            $finalAnswer = [$newJawabanArray[0]]; // Take first value
        } else {
            // For checkbox, keep as array
            $finalAnswer = $newJawabanArray;
        }

        echo "  New Answer: " . json_encode($finalAnswer) . "\n";

        try {
            $answer->update(['jawaban' => $finalAnswer]);
            echo "Status: ✅ MIGRATED\n\n";
            $stats['migrated']++;
        } catch (\Exception $e) {
            echo "Status: ❌ ERROR - " . $e->getMessage() . "\n\n";
            $stats['errors'][] = [
                'pendaftar_id' => $answer->pendaftar_id,
                'question_id' => $question->id,
                'error' => $e->getMessage()
            ];
        }
    } else if (empty($newJawabanArray)) {
        echo "Status: ⏭️  SKIPPED (no valid values)\n\n";
        $stats['skipped_already_id']++;
    } else {
        echo "Status: ⏭️  SKIPPED (already using OPTION ID)\n\n";
        $stats['skipped_already_id']++;
    }
}

echo "=======================================================================\n";
echo "MIGRATION SUMMARY\n";
echo "=======================================================================\n\n";
echo "Total Answers: {$stats['total']}\n";
echo "✅ Migrated: {$stats['migrated']}\n";
echo "⏭️  Skipped (already ID): {$stats['skipped_already_id']}\n";
echo "⏭️  Skipped (text input): {$stats['skipped_text_input']}\n";
echo "❌ Failed (no match): {$stats['failed_no_match']}\n\n";

if (!empty($stats['errors'])) {
    echo "ERRORS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    foreach ($stats['errors'] as $error) {
        echo "Pendaftar ID: {$error['pendaftar_id']}\n";
        echo "Question ID: {$error['question_id']}\n";
        if (isset($error['value'])) {
            echo "Value: {$error['value']}\n";
        }
        if (isset($error['error'])) {
            echo "Error: {$error['error']}\n";
        }
        echo "Reason: {$error['reason']}\n";
        echo "---\n";
    }
}

echo "\n=======================================================================\n";
echo "MIGRATION COMPLETED!\n";
echo "=======================================================================\n";
echo "\nNext Steps:\n";
echo "1. Review any errors above\n";
echo "2. Test survey system locally\n";
echo "3. Verify that admin can change LABEL without breaking answers\n";
echo "4. Upload changes to production\n";
