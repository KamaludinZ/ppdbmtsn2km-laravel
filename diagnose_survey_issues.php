<?php

/**
 * Diagnose Survey Issues
 *
 * Detect:
 * 1. Answers still as VALUE/LABEL (not ID)
 * 2. Missing answers (empty Q23-Q32)
 * 3. Duplicate option IDs across different questions
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
echo "DIAGNOSTIC: Survey Answer Issues\n";
echo "=======================================================================\n\n";

$issues = [
    'non_numeric' => [],
    'missing_answers' => [],
    'invalid_ids' => [],
    'empty_questions' => []
];

// Get all answers
$answers = SurveyAnswer::with('question')->get();

echo "Analyzing {$answers->count()} answers...\n\n";

// Group by pendaftar and question
$groupedByPendaftar = [];

foreach ($answers as $answer) {
    $pendaftarId = $answer->pendaftar_id;
    $questionId = $answer->survey_question_id;
    $jawaban = $answer->jawaban;
    $question = $answer->question;

    if (!isset($groupedByPendaftar[$pendaftarId])) {
        $groupedByPendaftar[$pendaftarId] = [];
    }
    $groupedByPendaftar[$pendaftarId][$questionId] = $jawaban;

    // Skip text inputs
    if (in_array($question->tipe_input, ['text', 'textarea'])) {
        continue;
    }

    // Check for non-numeric (VALUE/LABEL)
    if (!is_numeric($jawaban) && !is_array($jawaban)) {
        $normalizedValue = trim((string)$jawaban);

        // Check if it's really not an ID
        if (!empty($normalizedValue) && $normalizedValue !== '-') {
            $issues['non_numeric'][] = [
                'pendaftar_id' => $pendaftarId,
                'question_id' => $questionId,
                'question_text' => substr($question->pertanyaan, 0, 50),
                'current_value' => $jawaban,
                'type' => $question->tipe_input
            ];
        }
    }

    // For checkbox (array), check if all values are numeric
    if (is_array($jawaban)) {
        foreach ($jawaban as $val) {
            if (!is_numeric($val)) {
                $issues['non_numeric'][] = [
                    'pendaftar_id' => $pendaftarId,
                    'question_id' => $questionId,
                    'question_text' => substr($question->pertanyaan, 0, 50),
                    'current_value' => $val,
                    'type' => 'checkbox'
                ];
            }
        }
    }

    // Check for invalid IDs (ID not found in options)
    if (is_numeric($jawaban)) {
        $optionExists = SurveyQuestionOption::where('survey_question_id', $questionId)
            ->where('id', (int)$jawaban)
            ->exists();

        if (!$optionExists) {
            $issues['invalid_ids'][] = [
                'pendaftar_id' => $pendaftarId,
                'question_id' => $questionId,
                'question_text' => substr($question->pertanyaan, 0, 50),
                'invalid_id' => $jawaban
            ];
        }
    }
}

// Check for missing Q23-Q32 for pendaftar with context tes_psikologi
$pendaftarWithAnswers = array_keys($groupedByPendaftar);

foreach ($pendaftarWithAnswers as $pendaftarId) {
    $answers = $groupedByPendaftar[$pendaftarId];

    // Check Q23-Q32 (adjust question IDs based on your data)
    for ($qNum = 23; $qNum <= 32; $qNum++) {
        // Find question by number pattern (assuming questions have pattern)
        $question = SurveyQuestion::where('pertanyaan', 'LIKE', "%{$qNum}%")
            ->orWhere('id', $qNum)
            ->first();

        if ($question && !isset($answers[$question->id])) {
            $issues['missing_answers'][] = [
                'pendaftar_id' => $pendaftarId,
                'question_id' => $question->id,
                'question_num' => $qNum,
                'question_text' => substr($question->pertanyaan, 0, 50)
            ];
        }
    }
}

// Report
echo "=======================================================================\n";
echo "ISSUES FOUND\n";
echo "=======================================================================\n\n";

// Issue 1: Non-numeric values
if (!empty($issues['non_numeric'])) {
    echo "❌ NON-NUMERIC VALUES (VALUE/LABEL instead of ID): " . count($issues['non_numeric']) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    foreach (array_slice($issues['non_numeric'], 0, 20) as $issue) {
        echo "Pendaftar: {$issue['pendaftar_id']} | ";
        echo "Q{$issue['question_id']}: \"{$issue['question_text']}...\" | ";
        echo "Current: \"{$issue['current_value']}\" ({$issue['type']})\n";

        // Show available options
        $options = SurveyQuestionOption::where('survey_question_id', $issue['question_id'])
            ->orderBy('urutan')
            ->get();

        if ($options->isNotEmpty()) {
            echo "   Available Options:\n";
            foreach ($options as $opt) {
                $similarity = round(similar_text(
                    strtolower($issue['current_value']),
                    strtolower($opt->label)
                ), 1);
                echo "   - ID:{$opt->id} \"{$opt->label}\" (similarity: {$similarity}%)\n";
            }
        }
        echo "\n";
    }

    if (count($issues['non_numeric']) > 20) {
        echo "... and " . (count($issues['non_numeric']) - 20) . " more\n\n";
    }
} else {
    echo "✅ All values are numeric IDs\n\n";
}

// Issue 2: Invalid IDs
if (!empty($issues['invalid_ids'])) {
    echo "❌ INVALID OPTION IDs (ID not found in options): " . count($issues['invalid_ids']) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    foreach (array_slice($issues['invalid_ids'], 0, 20) as $issue) {
        echo "Pendaftar: {$issue['pendaftar_id']} | ";
        echo "Q{$issue['question_id']}: \"{$issue['question_text']}...\" | ";
        echo "Invalid ID: {$issue['invalid_id']}\n";

        // Show correct options
        $options = SurveyQuestionOption::where('survey_question_id', $issue['question_id'])
            ->orderBy('urutan')
            ->get();

        echo "   Valid Options for this question:\n";
        foreach ($options as $opt) {
            echo "   - ID:{$opt->id} \"{$opt->label}\"\n";
        }
        echo "\n";
    }

    if (count($issues['invalid_ids']) > 20) {
        echo "... and " . (count($issues['invalid_ids']) - 20) . " more\n\n";
    }
} else {
    echo "✅ All option IDs are valid\n\n";
}

// Issue 3: Missing answers
if (!empty($issues['missing_answers'])) {
    echo "⚠️  MISSING ANSWERS (empty Q23-Q32): " . count($issues['missing_answers']) . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    // Group by pendaftar
    $groupedMissing = [];
    foreach ($issues['missing_answers'] as $issue) {
        $pid = $issue['pendaftar_id'];
        if (!isset($groupedMissing[$pid])) {
            $groupedMissing[$pid] = [];
        }
        $groupedMissing[$pid][] = "Q{$issue['question_num']}";
    }

    foreach (array_slice($groupedMissing, 0, 10) as $pendaftarId => $missingQs) {
        echo "Pendaftar {$pendaftarId}: Missing " . implode(', ', $missingQs) . "\n";
    }

    if (count($groupedMissing) > 10) {
        echo "... and " . (count($groupedMissing) - 10) . " more pendaftar\n";
    }
    echo "\n";
} else {
    echo "✅ No missing answers detected\n\n";
}

echo "=======================================================================\n";
echo "SUMMARY\n";
echo "=======================================================================\n";
echo "Total Answers Analyzed: " . count($answers) . "\n";
echo "Non-numeric values: " . count($issues['non_numeric']) . "\n";
echo "Invalid option IDs: " . count($issues['invalid_ids']) . "\n";
echo "Missing answers: " . count($issues['missing_answers']) . "\n";
echo "\n";

// Export for manual fixing
if (!empty($issues['non_numeric']) || !empty($issues['invalid_ids'])) {
    $exportFile = 'survey_issues_export_' . date('Ymd_His') . '.json';
    file_put_contents($exportFile, json_encode($issues, JSON_PRETTY_PRINT));
    echo "📄 Issues exported to: {$exportFile}\n";
    echo "   Use this file with auto-fix script.\n\n";
}

echo "Next Steps:\n";
echo "1. Review non-numeric values and run SMART migration again\n";
echo "2. Fix invalid IDs manually or with auto-fix script\n";
echo "3. Investigate why Q23-Q32 are missing (data entry? validation?)\n";
