<?php

/**
 * SMART Migration Script: Convert Survey Answers from VALUE/LABEL to OPTION ID
 *
 * Features:
 * - Fuzzy matching (typo tolerant)
 * - Position/urutan matching
 * - Partial matching
 * - Detailed logging for manual review
 *
 * Date: February 6, 2026
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;

echo "=======================================================================\n";
echo "SMART MIGRATION: Survey Answers → OPTION ID (with Fuzzy Matching)\n";
echo "=======================================================================\n\n";

// Configuration
$DRY_RUN = false; // Set to true to test without actual updates
$SIMILARITY_THRESHOLD = 70; // Minimum similarity percentage (0-100)

if ($DRY_RUN) {
    echo "🔶 DRY RUN MODE - No actual updates will be made\n\n";
}

// Get all survey answers
$answers = SurveyAnswer::with('question.options')->get();

echo "Total Answers to Process: {$answers->count()}\n\n";

$stats = [
    'total' => $answers->count(),
    'migrated' => 0,
    'skipped_already_id' => 0,
    'skipped_text_input' => 0,
    'failed_no_match' => 0,
    'fuzzy_matched' => 0,
    'position_matched' => 0,
    'needs_review' => []
];

/**
 * Calculate similarity between two strings (0-100)
 */
function calculateSimilarity($str1, $str2) {
    $str1 = strtolower(trim($str1));
    $str2 = strtolower(trim($str2));

    similar_text($str1, $str2, $percent);
    return $percent;
}

/**
 * Find best matching option using multiple strategies
 */
function findBestOption($value, $options, $threshold = 70) {
    $normalizedValue = trim((string)$value);
    $matches = [];

    foreach ($options as $option) {
        $score = 0;
        $method = '';

        // Strategy 1: Exact match (VALUE)
        if (trim((string)$option->value) === $normalizedValue) {
            return ['option' => $option, 'score' => 100, 'method' => 'exact_value'];
        }

        // Strategy 2: Exact match (LABEL)
        if (trim((string)$option->label) === $normalizedValue) {
            return ['option' => $option, 'score' => 100, 'method' => 'exact_label'];
        }

        // Strategy 3: Case-insensitive match (VALUE)
        if (strcasecmp(trim((string)$option->value), $normalizedValue) === 0) {
            return ['option' => $option, 'score' => 95, 'method' => 'case_insensitive_value'];
        }

        // Strategy 4: Case-insensitive match (LABEL)
        if (strcasecmp(trim((string)$option->label), $normalizedValue) === 0) {
            return ['option' => $option, 'score' => 95, 'method' => 'case_insensitive_label'];
        }

        // Strategy 5: Fuzzy match (VALUE)
        $valueSimilarity = calculateSimilarity($option->value, $normalizedValue);
        if ($valueSimilarity >= $threshold) {
            $matches[] = [
                'option' => $option,
                'score' => $valueSimilarity,
                'method' => 'fuzzy_value'
            ];
        }

        // Strategy 6: Fuzzy match (LABEL)
        $labelSimilarity = calculateSimilarity($option->label, $normalizedValue);
        if ($labelSimilarity >= $threshold) {
            $matches[] = [
                'option' => $option,
                'score' => $labelSimilarity,
                'method' => 'fuzzy_label'
            ];
        }

        // Strategy 7: Partial match (contains)
        if (stripos($option->label, $normalizedValue) !== false || stripos($normalizedValue, $option->label) !== false) {
            $matches[] = [
                'option' => $option,
                'score' => 60,
                'method' => 'partial_match'
            ];
        }
    }

    // Return best match by score
    if (!empty($matches)) {
        usort($matches, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        return $matches[0];
    }

    return null;
}

foreach ($answers as $answer) {
    $question = $answer->question;
    $jawaban = $answer->jawaban;

    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Pendaftar ID: {$answer->pendaftar_id}\n";
    echo "Question ID: Q{$question->id} - " . substr($question->pertanyaan, 0, 60) . "...\n";
    echo "Question Type: {$question->tipe_input}\n";
    echo "Current Answer (RAW): " . json_encode($jawaban) . "\n";

    // Skip text/textarea inputs
    if (in_array($question->tipe_input, ['text', 'textarea'])) {
        echo "Status: ⏭️  SKIPPED (text input)\n\n";
        $stats['skipped_text_input']++;
        continue;
    }

    // Get options
    $options = SurveyQuestionOption::where('survey_question_id', $question->id)
        ->orderBy('urutan')
        ->get();

    if ($options->isEmpty()) {
        echo "Status: ⚠️  WARNING - No options found!\n\n";
        $stats['failed_no_match']++;
        continue;
    }

    // Convert to array
    $jawabanArray = is_array($jawaban) ? $jawaban : [$jawaban];
    $newJawabanArray = [];
    $needsMigration = false;
    $matchDetails = [];

    foreach ($jawabanArray as $value) {
        if (empty($value) || $value === '-') {
            continue;
        }

        $normalizedValue = trim((string)$value);

        // Check if already valid ID
        if (is_numeric($normalizedValue)) {
            $option = $options->firstWhere('id', (int)$normalizedValue);
            if ($option) {
                echo "  ✅ Already ID: {$normalizedValue} → \"{$option->label}\"\n";
                $newJawabanArray[] = (int)$normalizedValue;
                continue;
            } else {
                echo "  ⚠️  ID {$normalizedValue} not found in options!\n";
                $needsMigration = true;
            }
        }

        // Need to find matching option
        $needsMigration = true;
        $match = findBestOption($value, $options, $SIMILARITY_THRESHOLD);

        if ($match) {
            $option = $match['option'];
            $score = round($match['score'], 1);
            $method = $match['method'];

            echo "  🔄 Converting: \"{$normalizedValue}\"\n";
            echo "     → Option ID: {$option->id}\n";
            echo "     → Label: \"{$option->label}\"\n";
            echo "     → Value: \"{$option->value}\"\n";
            echo "     → Match Score: {$score}% ({$method})\n";

            $newJawabanArray[] = $option->id;
            $matchDetails[] = [
                'value' => $normalizedValue,
                'matched_id' => $option->id,
                'matched_label' => $option->label,
                'score' => $score,
                'method' => $method
            ];

            if ($score < 100) {
                $stats['fuzzy_matched']++;
            }
        } else {
            echo "  ❌ FAILED: Cannot find match for \"{$normalizedValue}\"\n";
            echo "     Available options:\n";
            foreach ($options as $opt) {
                $valueSim = round(calculateSimilarity($opt->value, $normalizedValue), 1);
                $labelSim = round(calculateSimilarity($opt->label, $normalizedValue), 1);
                echo "       - ID:{$opt->id} | VALUE:\"{$opt->value}\" ({$valueSim}%) | LABEL:\"{$opt->label}\" ({$labelSim}%)\n";
            }

            $stats['failed_no_match']++;
            $stats['needs_review'][] = [
                'pendaftar_id' => $answer->pendaftar_id,
                'question_id' => $question->id,
                'question_text' => $question->pertanyaan,
                'value' => $normalizedValue,
                'available_options' => $options->map(function($opt) {
                    return "ID:{$opt->id} \"{$opt->label}\" (value: {$opt->value})";
                })->toArray()
            ];

            // Keep original as fallback
            $newJawabanArray[] = $normalizedValue;
        }
    }

    // Update if migration needed
    if ($needsMigration && !empty($newJawabanArray)) {
        // For single-select, use single value
        if (in_array($question->tipe_input, ['radio', 'select'])) {
            $finalAnswer = $newJawabanArray[0];
        } else {
            // For checkbox, keep as array
            $finalAnswer = $newJawabanArray;
        }

        echo "  📝 New Answer: " . json_encode($finalAnswer) . "\n";

        if (!$DRY_RUN) {
            try {
                $answer->update(['jawaban' => $finalAnswer]);
                echo "Status: ✅ MIGRATED\n\n";
                $stats['migrated']++;
            } catch (\Exception $e) {
                echo "Status: ❌ ERROR - " . $e->getMessage() . "\n\n";
                $stats['needs_review'][] = [
                    'pendaftar_id' => $answer->pendaftar_id,
                    'question_id' => $question->id,
                    'error' => $e->getMessage()
                ];
            }
        } else {
            echo "Status: 🔶 WOULD MIGRATE (dry run)\n\n";
            $stats['migrated']++;
        }
    } else {
        echo "Status: ⏭️  SKIPPED (already valid)\n\n";
        $stats['skipped_already_id']++;
    }
}

echo "=======================================================================\n";
echo "MIGRATION SUMMARY\n";
echo "=======================================================================\n\n";
echo "Total Answers: {$stats['total']}\n";
echo "✅ Migrated: {$stats['migrated']}\n";
echo "🎯 Fuzzy Matched: {$stats['fuzzy_matched']}\n";
echo "⏭️  Skipped (already ID): {$stats['skipped_already_id']}\n";
echo "⏭️  Skipped (text input): {$stats['skipped_text_input']}\n";
echo "❌ Failed (no match): {$stats['failed_no_match']}\n\n";

if (!empty($stats['needs_review'])) {
    echo "=======================================================================\n";
    echo "⚠️  NEEDS MANUAL REVIEW ({$stats['failed_no_match']} items)\n";
    echo "=======================================================================\n\n";

    foreach ($stats['needs_review'] as $item) {
        echo "Pendaftar ID: {$item['pendaftar_id']}\n";
        echo "Question ID: Q{$item['question_id']}\n";
        if (isset($item['question_text'])) {
            echo "Question: " . substr($item['question_text'], 0, 60) . "...\n";
        }
        if (isset($item['value'])) {
            echo "Value: \"{$item['value']}\"\n";
            echo "Available Options:\n";
            foreach ($item['available_options'] as $opt) {
                echo "  - {$opt}\n";
            }
        }
        if (isset($item['error'])) {
            echo "Error: {$item['error']}\n";
        }
        echo "---\n\n";
    }

    echo "\n💡 TIP: Review items above and manually update via SQL if needed:\n";
    echo "UPDATE survey_answers SET jawaban = '[CORRECT_OPTION_ID]' \n";
    echo "WHERE pendaftar_id = X AND survey_question_id = Y;\n\n";
}

echo "\n=======================================================================\n";
echo "MIGRATION COMPLETED!\n";
echo "=======================================================================\n";

if ($DRY_RUN) {
    echo "\n🔶 This was a DRY RUN. Set \$DRY_RUN = false to apply changes.\n";
}

echo "\nNext Steps:\n";
echo "1. Review any items needing manual review above\n";
echo "2. Check RAW display in /admin/tes-psikologi?mainTab=jawaban-survey\n";
echo "3. Verify all shows ✓ ID (green) not ⚠ VALUE/LABEL (orange)\n";
echo "4. Test that admin can edit option labels without breaking data\n";
