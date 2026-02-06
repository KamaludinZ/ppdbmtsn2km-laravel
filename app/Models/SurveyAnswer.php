<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'survey_question_id',
        'jawaban',
    ];

    // CRITICAL FIX: REMOVE auto-cast to array!
    // Problem: Forcing ALL jawaban to array causes corruption for radio/select
    // - Radio/Select should store: single value (integer atau string)
    // - Checkbox should store: array
    // Laravel PostgreSQL will auto-handle JSONB encode/decode based on what we save
    protected $casts = [
        // 'jawaban' => 'array', // ❌ REMOVED! This was causing the overwrite bug!
    ];

    /**
     * Relationship: Jawaban milik satu pendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Relationship: Jawaban untuk satu pertanyaan
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * Helper: Get jawaban as array (for checkbox with multiple values)
     * Handles both array (checkbox) and single value (radio/select)
     * Also handles JSONB data from PostgreSQL
     */
    public function getJawabanArray()
    {
        $jawaban = $this->attributes['jawaban'] ?? null;

        // If null or empty, return empty array
        if (empty($jawaban)) {
            return [];
        }

        // If already an array, return as-is
        if (is_array($jawaban)) {
            return $jawaban;
        }

        // If string that looks like JSON, decode it
        if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
            $decoded = json_decode($jawaban, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return is_array($decoded) ? $decoded : [$decoded];
            }
        }

        // Otherwise, wrap single value in array
        return [$jawaban];
    }

    /**
     * Helper: Get jawaban as formatted string
     * For text/textarea: return text directly
     * For radio/select/checkbox: convert option IDs/VALUES to labels
     *
     * BACKWARD COMPATIBLE: Supports both ID-based (new) and VALUE-based (old) data
     */
    public function getJawabanFormatted()
    {
        // Get the question to check its type
        $question = $this->question;

        if (!$question) {
            return '-';
        }

        // For text and textarea: return the text directly
        if ($question->tipe_input === 'text' || $question->tipe_input === 'textarea') {
            return $this->jawaban ?? '-';
        }

        // For option-based inputs (radio, select, checkbox): convert IDs/VALUES to labels
        $jawaban = $this->getJawabanArray();

        if (empty($jawaban)) {
            return '-';
        }

        // Get all options for this question
        $options = $question->options;

        // Convert option IDs/VALUES to labels
        $labels = [];
        foreach ($jawaban as $value) {
            $normalizedValue = trim((string)$value);
            $option = null;

            // ======================================================================
            // NEW APPROACH: Match by OPTION ID (data integrity)
            // ======================================================================
            // Starting Feb 2026: Jawaban menyimpan OPTION ID untuk data integrity

            // Method 1: Match by OPTION ID (PRIMARY METHOD for new data)
            if (is_numeric($normalizedValue)) {
                $option = $options->firstWhere('id', (int)$normalizedValue);
            }

            // ======================================================================
            // BACKWARD COMPATIBILITY: Fallback untuk data lama (before Feb 2026)
            // ======================================================================

            // Method 2: Exact string match on VALUE (untuk data lama yang simpan VALUE)
            if (!$option) {
                $option = $options->first(function($opt) use ($normalizedValue) {
                    return trim((string)$opt->value) === $normalizedValue;
                });
            }

            // Method 3: Case-insensitive match on VALUE
            if (!$option) {
                $option = $options->first(function($opt) use ($normalizedValue) {
                    return strcasecmp(trim((string)$opt->value), $normalizedValue) === 0;
                });
            }

            // Method 4: Try matching by LABEL (untuk data lama yang simpan label)
            if (!$option) {
                $option = $options->first(function($opt) use ($normalizedValue) {
                    return strcasecmp(trim((string)$opt->label), $normalizedValue) === 0;
                });
            }

            if ($option) {
                $labels[] = $option->label;
            } else {
                // Show raw value with warning if option not found
                $labels[] = "{$normalizedValue} ⚠️";
            }
        }

        if (empty($labels)) {
            return '-';
        }

        // For checkbox (multiple values): join with comma
        if (count($labels) > 1) {
            return implode(', ', $labels);
        }

        // For radio/select (single value): return the label
        return $labels[0];
    }
}
