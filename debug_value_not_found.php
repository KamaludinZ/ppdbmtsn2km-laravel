<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG VALUE NOT FOUND - REG-26010022 ===\n\n";

$pendaftar = \App\Models\Pendaftar::where('nomor_pendaftaran', 'REG-26010022')->first();

if (!$pendaftar) {
    echo "Pendaftar not found\n";
    exit;
}

echo "Pendaftar: {$pendaftar->nama_lengkap} (ID: {$pendaftar->id})\n\n";

$answers = \App\Models\SurveyAnswer::where('pendaftar_id', $pendaftar->id)
    ->whereHas('question', function($q) {
        $q->where('context', 'tes_psikologi');
    })
    ->with('question.options')
    ->get();

echo "Total Answers: " . $answers->count() . "\n\n";
echo "===========================================\n\n";

foreach ($answers as $ans) {
    $question = $ans->question;
    $jawaban = $ans->jawaban;

    echo "Q{$question->id}: {$question->pertanyaan}\n";
    echo "  Type: {$question->tipe_input}\n";
    echo "  Raw Answer: " . json_encode($jawaban) . "\n";

    // Get all options for this question
    $options = \App\Models\SurveyQuestionOption::where('survey_question_id', $question->id)
        ->orderBy('urutan')
        ->get();

    echo "  Available Options:\n";
    foreach ($options as $opt) {
        echo "    - ID:{$opt->id}  VALUE:\"{$opt->value}\"  LABEL:\"{$opt->label}\"\n";
    }

    // Try to match each answer
    echo "  Matching Results:\n";
    $jawabanArray = is_array($jawaban) ? $jawaban : [$jawaban];

    foreach ($jawabanArray as $val) {
        if (empty($val) || $val === '-') {
            continue;
        }

        $normalizedValue = trim((string)$val);

        // Try to find matching option
        $found = false;

        // Method 1: Exact VALUE match
        $option = $options->first(function($opt) use ($normalizedValue) {
            return trim((string)$opt->value) === $normalizedValue;
        });

        if ($option) {
            echo "    ✅ \"{$normalizedValue}\" → Matched by VALUE → LABEL: \"{$option->label}\"\n";
            $found = true;
        }

        // Method 2: Case-insensitive VALUE
        if (!$found) {
            $option = $options->first(function($opt) use ($normalizedValue) {
                return strcasecmp(trim((string)$opt->value), $normalizedValue) === 0;
            });

            if ($option) {
                echo "    ✅ \"{$normalizedValue}\" → Matched by VALUE (case-insensitive) → LABEL: \"{$option->label}\"\n";
                $found = true;
            }
        }

        // Method 3: LABEL match
        if (!$found) {
            $option = $options->first(function($opt) use ($normalizedValue) {
                return strcasecmp(trim((string)$opt->label), $normalizedValue) === 0;
            });

            if ($option) {
                echo "    ⚠️  \"{$normalizedValue}\" → Matched by LABEL → LABEL: \"{$option->label}\"\n";
                echo "        WARNING: Jawaban menyimpan LABEL, seharusnya VALUE!\n";
                echo "        Option VALUE: \"{$option->value}\"\n";
                $found = true;
            }
        }

        // Not found
        if (!$found) {
            echo "    ❌ \"{$normalizedValue}\" → [VALUE NOT FOUND]\n";
            echo "        Possible causes:\n";
            echo "        1. Option dengan VALUE \"{$normalizedValue}\" tidak ada untuk pertanyaan ini\n";
            echo "        2. Options berubah setelah user submit jawaban\n";
            echo "        3. Data corrupt dari form lama\n";
        }
    }

    echo "\n";
    echo "===========================================\n\n";
}

echo "\n=== RECOMMENDATIONS ===\n\n";
echo "Jika ada [VALUE NOT FOUND]:\n";
echo "1. Check apakah OPTIONS VALUE sudah benar (semantic data, bukan label duplikat)\n";
echo "2. Jika options berubah, user perlu isi ulang\n";
echo "3. Jika data simpan LABEL, update OPTIONS VALUE = sesuai data yang tersimpan\n";
echo "\n";
