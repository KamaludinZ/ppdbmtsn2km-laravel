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
        // Drop old survey tables (from previous incomplete implementation)
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_options');
        Schema::dropIfExists('survey_questions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot restore old tables - they had different structure
        // User should restore from backup if needed
    }
};
