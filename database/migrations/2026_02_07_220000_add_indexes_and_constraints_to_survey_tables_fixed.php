<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan indexes dan constraints untuk:
     * 1. Meningkatkan performa query
     * 2. Menjaga data integrity
     * 3. Mencegah duplicate entries
     *
     * FIXED VERSION: Compatible with Laravel 11+ (no Doctrine DBAL)
     */
    public function up(): void
    {
        // ================================================================
        // Table: survey_question_options
        // ================================================================
        try {
            // Index untuk faster query saat load options dengan urutan
            DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_options_question_urutan ON survey_question_options (survey_question_id, urutan)');
        } catch (\Exception $e) {
            // Index already exists, skip
            \Log::info('Index idx_survey_options_question_urutan already exists or error: ' . $e->getMessage());
        }

        // ================================================================
        // Table: survey_answers
        // ================================================================
        try {
            // Index untuk query answers by pendaftar
            DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_answers_pendaftar_question ON survey_answers (pendaftar_id, survey_question_id)');
        } catch (\Exception $e) {
            \Log::info('Index idx_survey_answers_pendaftar_question already exists or error: ' . $e->getMessage());
        }

        try {
            // Unique constraint: Satu pendaftar = satu jawaban per pertanyaan
            // Ini mencegah duplicate submission
            DB::statement('ALTER TABLE survey_answers ADD CONSTRAINT unique_pendaftar_question UNIQUE (pendaftar_id, survey_question_id)');
        } catch (\Exception $e) {
            // Constraint already exists or there are duplicate data
            \Log::warning('Cannot add unique constraint unique_pendaftar_question: ' . $e->getMessage());
        }

        // ================================================================
        // Table: survey_questions
        // ================================================================
        try {
            // Index untuk query active questions dengan urutan
            DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_questions_context_aktif_urutan ON survey_questions (context, aktif, urutan)');
        } catch (\Exception $e) {
            \Log::info('Index idx_survey_questions_context_aktif_urutan already exists or error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes yang ditambahkan
        try {
            DB::statement('DROP INDEX IF EXISTS idx_survey_options_question_urutan');
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            DB::statement('DROP INDEX IF EXISTS idx_survey_answers_pendaftar_question');
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            DB::statement('ALTER TABLE survey_answers DROP CONSTRAINT IF EXISTS unique_pendaftar_question');
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            DB::statement('DROP INDEX IF EXISTS idx_survey_questions_context_aktif_urutan');
        } catch (\Exception $e) {
            // Ignore
        }
    }
};
