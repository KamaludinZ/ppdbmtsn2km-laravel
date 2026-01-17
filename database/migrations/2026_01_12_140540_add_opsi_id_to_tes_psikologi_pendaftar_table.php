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
        Schema::table('tes_psikologi_pendaftar', function (Blueprint $table) {
            $table->foreignId('opsi_id')->nullable()->after('bank_id')->constrained('tes_psikologi_opsi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_pendaftar', function (Blueprint $table) {
            $table->dropForeign(['opsi_id']);
            $table->dropColumn('opsi_id');
        });
    }
};
