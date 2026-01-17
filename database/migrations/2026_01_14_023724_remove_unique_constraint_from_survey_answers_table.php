<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            // Drop the unique constraint to allow multiple answers per question (for checkboxes)
            $table->dropUnique(['pendaftar_id', 'survey_question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            // Re-add the unique constraint
            $table->unique(['pendaftar_id', 'survey_question_id']);
        });
    }
};
