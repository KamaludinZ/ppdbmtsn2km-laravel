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
        Schema::table('tes_psikologi_settings', function (Blueprint $table) {
            $table->boolean('survey_aktif')->default(false)->after('aktif');
            $table->text('survey_informasi')->nullable()->after('survey_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_settings', function (Blueprint $table) {
            $table->dropColumn(['survey_aktif', 'survey_informasi']);
        });
    }
};
