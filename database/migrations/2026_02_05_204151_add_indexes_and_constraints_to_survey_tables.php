<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan indexes dan constraints untuk:
     * 1. Meningkatkan performa query
     * 2. Menjaga data integrity
     * 3. Mencegah duplicate entries
     */
    public function up(): void
    {
        // ================================================================
        // Table: survey_question_options
        // ================================================================
        if (Schema::hasTable('survey_question_options') && Schema::hasColumn('survey_question_options', 'survey_question_id')) {
            try {
                \DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_options_question_urutan ON survey_question_options (survey_question_id, urutan)');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // ================================================================
        // Table: survey_answers
        // ================================================================
        if (Schema::hasTable('survey_answers')) {
            try {
                \DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_answers_pendaftar_question ON survey_answers (pendaftar_id, survey_question_id)');
            } catch (\Exception $e) {
                // Index already exists, skip
            }

            // Unique constraint
            try {
                \DB::statement('ALTER TABLE survey_answers ADD CONSTRAINT unique_pendaftar_question UNIQUE (pendaftar_id, survey_question_id)');
            } catch (\Exception $e) {
                // Constraint already exists or duplicate data, skip
            }
        }

        // ================================================================
        // Table: survey_questions
        // ================================================================
        if (Schema::hasTable('survey_questions')) {
            try {
                \DB::statement('CREATE INDEX IF NOT EXISTS idx_survey_questions_context_aktif_urutan ON survey_questions (context, aktif, urutan)');
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes yang ditambahkan
        Schema::table('survey_question_options', function (Blueprint $table) {
            $table->dropIndex('idx_survey_options_question_urutan');
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropIndex('idx_survey_answers_pendaftar_question');

            // Drop unique constraint jika ada
            try {
                $table->dropUnique('unique_pendaftar_question');
            } catch (\Exception $e) {
                // Ignore jika tidak ada
            }
        });

        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropIndex('idx_survey_questions_context_aktif_urutan');
        });
    }
};
