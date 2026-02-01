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
        Schema::table('survey_questions', function (Blueprint $table) {
            // Add context column to separate Survey Umum from Survey Tes Psikologi
            $table->enum('context', ['umum', 'tes_psikologi'])->default('umum')->after('aktif');

            // Add index for better query performance
            $table->index('context');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropIndex(['context']);
            $table->dropColumn('context');
        });
    }
};
