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
            $table->text('informasi_admin_panitia')->nullable()->after('informasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_settings', function (Blueprint $table) {
            $table->dropColumn('informasi_admin_panitia');
        });
    }
};
