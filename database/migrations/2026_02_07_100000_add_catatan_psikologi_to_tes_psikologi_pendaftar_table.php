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
            $table->text('catatan_psikologi')->nullable()->after('nama_pengirim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_pendaftar', function (Blueprint $table) {
            $table->dropColumn('catatan_psikologi');
        });
    }
};
