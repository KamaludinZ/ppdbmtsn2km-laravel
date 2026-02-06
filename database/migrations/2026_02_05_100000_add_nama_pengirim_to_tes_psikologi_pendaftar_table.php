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
            $table->string('nama_pengirim')->nullable()->after('waktu_transfer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_pendaftar', function (Blueprint $table) {
            $table->dropColumn('nama_pengirim');
        });
    }
};
