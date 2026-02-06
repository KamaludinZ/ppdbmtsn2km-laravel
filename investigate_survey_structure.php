<?php

/**
 * Investigate Survey Structure & Option Mapping Issues
 *
 * Purpose:
 * 1. Analyze current question-option mapping
 * 2. Find duplicate or mismatched option IDs
 * 3. Identify questions with wrong options
 * 4. Suggest manual fixes
 *
 * Date: February 6, 2026
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\SurveyAnswer;

echo "=======================================================================\n";
echo "INVESTIGATION: Survey Structure & Option Mapping\n";
echo "=======================================================================\n\n";

// Get all survey questions (both contexts)
$questionsTesPsikologi = SurveyQuestion::where('context', 'tes_psikologi')
    ->with('options')
    ->orderBy('id')
    ->get();

$questionsUmum = SurveyQuestion::where('context', 'umum')
    ->with('options')
    ->orderBy('id')
    ->get();

echo "Total Questions (Tes Psikologi): {$questionsTesPsikologi->count()}\n";
echo "Total Questions (Umum): {$questionsUmum->count()}\n\n";

// SECTION 1: Analyze Tes Psikologi Questions
echo "=======================================================================\n";
echo "TES PSIKOLOGI QUESTIONS - Option Mapping\n";
echo "=======================================================================\n\n";

foreach ($questionsTesPsikologi as $question) {
    echo "Q{$question->id}: " . substr($question->pertanyaan, 0, 60) . "...\n";
    echo "Type: {$question->tipe_input} | Wajib: " . ($question->wajib_diisi ? 'Ya' : 'Tidak') . "\n";

    if ($question->options->isEmpty()) {
        echo "⚠️  WARNING: NO OPTIONS!\n";
    } else {
        echo "Options ({$question->options->count()}):\n";
        foreach ($question->options->sortBy('urutan') as $option) {
            echo "   - ID:{$option->id} | Urutan:{$option->urutan} | Value:\"{$option->value}\" | Label:\"{$option->label}\"\n";
        }
    }

    // Check answers for this question
    $answerStats = SurveyAnswer::where('survey_question_id', $question->id)->get();
    $uniqueAnswers = $answerStats->pluck('jawaban')->unique()->values();

    echo "Total Answers: {$answerStats->count()}\n";
    echo "Unique Values: " . $uniqueAnswers->count() . "\n";

    // Check for problematic answers
    $problematic = [];
    foreach ($uniqueAnswers as $ans) {
        if (!is_numeric($ans) && !is_array($ans)) {
            $problematic[] = $ans;
        }
    }

    if (!empty($problematic)) {
        echo "⚠️  Problematic Values: " . implode(', ', array_slice($problematic, 0, 5)) . "\n";
    }

    echo "\n";
}

// SECTION 2: Analyze Umum Questions
echo "=======================================================================\n";
echo "SURVEY UMUM QUESTIONS - Option Mapping\n";
echo "=======================================================================\n\n";

foreach ($questionsUmum as $question) {
    echo "Q{$question->id}: " . substr($question->pertanyaan, 0, 60) . "...\n";
    echo "Type: {$question->tipe_input} | Wajib: " . ($question->wajib_diisi ? 'Ya' : 'Tidak') . "\n";

    if ($question->options->isEmpty()) {
        echo "⚠️  WARNING: NO OPTIONS!\n";
    } else {
        echo "Options ({$question->options->count()}):\n";
        foreach ($question->options->sortBy('urutan') as $option) {
            echo "   - ID:{$option->id} | Urutan:{$option->urutan} | Value:\"{$option->value}\" | Label:\"{$option->label}\"\n";
        }
    }

    // Check answers for this question
    $answerStats = SurveyAnswer::where('survey_question_id', $question->id)->get();
    $uniqueAnswers = $answerStats->pluck('jawaban')->unique()->values();

    echo "Total Answers: {$answerStats->count()}\n";
    echo "Unique Values: " . $uniqueAnswers->count() . "\n";

    // Check for problematic answers
    $problematic = [];
    foreach ($uniqueAnswers as $ans) {
        if (!is_numeric($ans) && !is_array($ans)) {
            $problematic[] = $ans;
        }
    }

    if (!empty($problematic)) {
        echo "⚠️  Problematic Values: " . implode(', ', array_slice($problematic, 0, 5)) . "\n";
    }

    echo "\n";
}

// SECTION 3: Find Duplicate or Conflicting Option IDs
echo "=======================================================================\n";
echo "OPTION ID CONFLICTS\n";
echo "=======================================================================\n\n";

// Get all options and group by ID
$allOptions = SurveyQuestionOption::with('question')->get();
$groupedById = $allOptions->groupBy('id');

$conflicts = [];
foreach ($groupedById as $optionId => $options) {
    if ($options->count() > 1) {
        $conflicts[$optionId] = $options;
    }
}

if (!empty($conflicts)) {
    echo "⚠️  FOUND " . count($conflicts) . " CONFLICTING OPTION IDs:\n\n";

    foreach ($conflicts as $optionId => $options) {
        echo "Option ID: {$optionId} (used by {$options->count()} questions)\n";
        foreach ($options as $opt) {
            echo "   - Q{$opt->survey_question_id}: \"{$opt->label}\" (value: {$opt->value})\n";
        }
        echo "\n";
    }
} else {
    echo "✅ No conflicting option IDs found\n\n";
}

// SECTION 4: Analyze Invalid IDs from answers
echo "=======================================================================\n";
echo "ORPHANED OPTION IDs (Used in answers but not in options table)\n";
echo "=======================================================================\n\n";

$allAnswers = SurveyAnswer::all();
$usedIds = [];

foreach ($allAnswers as $answer) {
    $jawaban = $answer->jawaban;

    if (is_numeric($jawaban)) {
        $usedIds[(int)$jawaban] = ($usedIds[(int)$jawaban] ?? 0) + 1;
    } elseif (is_array($jawaban)) {
        foreach ($jawaban as $val) {
            if (is_numeric($val)) {
                $usedIds[(int)$val] = ($usedIds[(int)$val] ?? 0) + 1;
            }
        }
    }
}

// Check which IDs are used but don't exist
$validOptionIds = SurveyQuestionOption::pluck('id')->toArray();
$orphanedIds = [];

foreach ($usedIds as $id => $count) {
    if (!in_array($id, $validOptionIds)) {
        $orphanedIds[$id] = $count;
    }
}

if (!empty($orphanedIds)) {
    echo "⚠️  FOUND " . count($orphanedIds) . " ORPHANED OPTION IDs:\n\n";

    arsort($orphanedIds); // Sort by count descending
    foreach (array_slice($orphanedIds, 0, 20, true) as $id => $count) {
        echo "ID {$id}: Used {$count} times in answers (but doesn't exist in options table)\n";

        // Show sample questions using this ID
        $sampleAnswers = SurveyAnswer::where('jawaban', $id)
            ->orWhereJsonContains('jawaban', $id)
            ->with('question')
            ->limit(3)
            ->get();

        foreach ($sampleAnswers as $sample) {
            echo "   - Q{$sample->survey_question_id}: " . substr($sample->question->pertanyaan ?? 'Unknown', 0, 50) . "...\n";
        }
        echo "\n";
    }

    if (count($orphanedIds) > 20) {
        echo "... and " . (count($orphanedIds) - 20) . " more\n\n";
    }
} else {
    echo "✅ No orphaned option IDs found\n\n";
}

// SECTION 5: Summary and Recommendations
echo "=======================================================================\n";
echo "SUMMARY & RECOMMENDATIONS\n";
echo "=======================================================================\n\n";

echo "Total Questions: " . ($questionsTesPsikologi->count() + $questionsUmum->count()) . "\n";
echo "Total Options: " . $allOptions->count() . "\n";
echo "Total Answers: " . $allAnswers->count() . "\n";
echo "Conflicting Option IDs: " . count($conflicts) . "\n";
echo "Orphaned Option IDs: " . count($orphanedIds) . "\n\n";

if (!empty($orphanedIds) || !empty($conflicts)) {
    echo "⚠️  CRITICAL ISSUES FOUND!\n\n";

    echo "RECOMMENDED ACTIONS:\n";
    echo "1. Review option mapping for each question\n";
    echo "2. Check if options were deleted/recreated (causing ID changes)\n";
    echo "3. Consider manual re-mapping for orphaned IDs:\n";
    echo "   - Find old ID in answers\n";
    echo "   - Match with current valid option based on label/context\n";
    echo "   - Update answer to use new ID\n\n";

    echo "EXAMPLE SQL FIX:\n";
    echo "UPDATE survey_answers\n";
    echo "SET jawaban = '[NEW_VALID_OPTION_ID]'\n";
    echo "WHERE jawaban = '[ORPHANED_OPTION_ID]'\n";
    echo "  AND survey_question_id = X;\n\n";

    if (!empty($orphanedIds)) {
        echo "MOST CRITICAL ORPHANED IDs TO FIX:\n";
        $top5 = array_slice($orphanedIds, 0, 5, true);
        foreach ($top5 as $id => $count) {
            echo "   - ID {$id}: {$count} affected answers\n";
        }
    }
} else {
    echo "✅ Structure looks good! You can proceed with SMART migration.\n";
}

echo "\n=======================================================================\n";
echo "INVESTIGATION COMPLETE!\n";
echo "=======================================================================\n";
