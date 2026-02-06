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
        // Index untuk query options by question dan urutan
        if (!Schema::hasColumn('survey_question_options', 'survey_question_id')) {
            Schema::table('survey_question_options', function (Blueprint $table) {
                // Index untuk faster query saat load options dengan urutan
                $table->index(['survey_question_id', 'urutan'], 'idx_survey_options_question_urutan');
            });
        } else {
            // Cek jika index belum ada
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('survey_question_options');

            if (!isset($indexesFound['idx_survey_options_question_urutan'])) {
                Schema::table('survey_question_options', function (Blueprint $table) {
                    $table->index(['survey_question_id', 'urutan'], 'idx_survey_options_question_urutan');
                });
            }
        }

        // ================================================================
        // Table: survey_answers
        // ================================================================
        // Prevent duplicate answers untuk question yang sama dari pendaftar yang sama
        Schema::table('survey_answers', function (Blueprint $table) {
            // Drop index/constraint lama jika ada
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('survey_answers');

            // Index untuk query answers by pendaftar
            if (!isset($indexesFound['idx_survey_answers_pendaftar_question'])) {
                $table->index(['pendaftar_id', 'survey_question_id'], 'idx_survey_answers_pendaftar_question');
            }

            // Unique constraint: Satu pendaftar = satu jawaban per pertanyaan
            // Ini mencegah duplicate submission
            if (!isset($indexesFound['unique_pendaftar_question'])) {
                // Cek apakah sudah ada unique constraint
                try {
                    $table->unique(['pendaftar_id', 'survey_question_id'], 'unique_pendaftar_question');
                } catch (\Exception $e) {
                    // Jika sudah ada duplicate data, skip constraint ini
                    \Log::warning('Cannot add unique constraint due to existing duplicates: ' . $e->getMessage());
                }
            }
        });

        // ================================================================
        // Table: survey_questions
        // ================================================================
        // Index untuk query active questions dengan urutan
        Schema::table('survey_questions', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('survey_questions');

            if (!isset($indexesFound['idx_survey_questions_context_aktif_urutan'])) {
                $table->index(['context', 'aktif', 'urutan'], 'idx_survey_questions_context_aktif_urutan');
            }
        });
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
