<?php

/**
 * FIX SURVEY ANSWERS - Convert String IDs to Integer IDs
 *
 * Problem: Some answers stored as string "21" instead of integer 21
 * Solution: Convert all numeric strings to integers
 *
 * Usage:
 * php fix_survey_string_to_integer.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=============================================================\n";
echo "FIX SURVEY ANSWERS - String to Integer Conversion\n";
echo "=============================================================\n\n";

echo "This script will convert answers like \"21\" to 21 (integer)\n";
echo "Only affects numeric strings wrapped in quotes.\n\n";

// Get all survey answers
echo "[1/4] Fetching all survey answers...\n";
$answers = DB::table('survey_answers')->get();
echo "Found: " . $answers->count() . " answers\n\n";

// Statistics
$stats = [
    'total' => $answers->count(),
    'already_correct' => 0,
    'needs_fix' => 0,
    'fixed' => 0,
    'errors' => 0,
    'skipped_text' => 0,
    'skipped_array' => 0,
];

echo "[2/4] Analyzing answers...\n";

foreach ($answers as $answer) {
    $questionId = $answer->survey_question_id;
    $jawabanRaw = $answer->jawaban;

    // Decode JSONB if it's JSON string
    if (is_string($jawabanRaw)) {
        // Try to decode as JSON
        $decoded = json_decode($jawabanRaw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $jawaban = $decoded;
        } else {
            $jawaban = $jawabanRaw;
        }
    } else {
        $jawaban = $jawabanRaw;
    }

    // Check type
    if (is_array($jawaban)) {
        // Array (checkbox) - check each element
        $needsFix = false;
        foreach ($jawaban as $val) {
            if (is_string($val) && is_numeric($val)) {
                $needsFix = true;
                break;
            }
        }

        if ($needsFix) {
            $stats['needs_fix']++;
            echo "  Answer ID {$answer->id} (Q{$questionId}): Array with string numbers\n";
        } else {
            $stats['already_correct']++;
        }

    } elseif (is_string($jawaban)) {
        // String value
        if (is_numeric($jawaban)) {
            // Numeric string like "21" - NEEDS FIX
            $stats['needs_fix']++;
            echo "  Answer ID {$answer->id} (Q{$questionId}): \"{$jawaban}\" → {$jawaban} (convert to integer)\n";
        } else {
            // Text answer (text/textarea) - SKIP
            $stats['skipped_text']++;
        }

    } elseif (is_int($jawaban) || is_float($jawaban)) {
        // Already integer - CORRECT!
        $stats['already_correct']++;

    } else {
        echo "  Answer ID {$answer->id} (Q{$questionId}): Unknown type - " . gettype($jawaban) . "\n";
    }
}

echo "\nAnalysis Results:\n";
echo "  Total answers: {$stats['total']}\n";
echo "  Already correct (integer): {$stats['already_correct']}\n";
echo "  Needs fix (string numbers): {$stats['needs_fix']}\n";
echo "  Skipped (text answers): {$stats['skipped_text']}\n";
echo "\n";

if ($stats['needs_fix'] === 0) {
    echo "✅ All answers are already correct! No fixes needed.\n";
    exit(0);
}

// Confirm before fixing
echo "[3/4] Ready to fix {$stats['needs_fix']} answers.\n";
echo "Continue? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'yes') {
    echo "Aborted by user.\n";
    exit(0);
}

echo "\n[4/4] Fixing answers...\n";

// Fix each answer
foreach ($answers as $answer) {
    $questionId = $answer->survey_question_id;
    $jawabanRaw = $answer->jawaban;

    // Decode JSONB if it's JSON string
    if (is_string($jawabanRaw)) {
        $decoded = json_decode($jawabanRaw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $jawaban = $decoded;
        } else {
            $jawaban = $jawabanRaw;
        }
    } else {
        $jawaban = $jawabanRaw;
    }

    $needsUpdate = false;
    $newJawaban = null;

    // Check and fix
    if (is_array($jawaban)) {
        // Array (checkbox) - convert each string number to integer
        $fixed = [];
        foreach ($jawaban as $val) {
            if (is_string($val) && is_numeric($val)) {
                $fixed[] = (int)$val;
                $needsUpdate = true;
            } else {
                $fixed[] = $val;
            }
        }

        if ($needsUpdate) {
            $newJawaban = $fixed;
        }

    } elseif (is_string($jawaban) && is_numeric($jawaban)) {
        // String number like "21" - convert to integer
        $newJawaban = (int)$jawaban;
        $needsUpdate = true;
    }

    // Update database if needed
    if ($needsUpdate && $newJawaban !== null) {
        try {
            DB::table('survey_answers')
                ->where('id', $answer->id)
                ->update(['jawaban' => $newJawaban]);

            $stats['fixed']++;
            echo "  ✓ Fixed Answer ID {$answer->id} (Q{$questionId})\n";

        } catch (\Exception $e) {
            $stats['errors']++;
            echo "  ✗ Error fixing Answer ID {$answer->id}: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n=============================================================\n";
echo "SUMMARY\n";
echo "=============================================================\n";
echo "Total answers: {$stats['total']}\n";
echo "Already correct: {$stats['already_correct']}\n";
echo "Fixed: {$stats['fixed']}\n";
echo "Errors: {$stats['errors']}\n";
echo "Skipped (text): {$stats['skipped_text']}\n";
echo "\n";

if ($stats['fixed'] > 0) {
    echo "✅ Successfully fixed {$stats['fixed']} answers!\n";
    echo "\nRecommendation: Clear cache after this fix:\n";
    echo "  php artisan view:clear\n";
    echo "  php artisan optimize:clear\n";
} else {
    echo "⚠️  No answers were fixed.\n";
}

echo "\n";
