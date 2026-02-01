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
        // Add fields to daftar_ulang table
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->json('nominal_biaya_awal')->nullable()->after('nominal_biaya');
            $table->timestamp('waktu_pembayaran_madrasah')->nullable()->after('bukti_pembayaran_madrasah');
        });

        // Add fields to seragam table
        Schema::table('seragam', function (Blueprint $table) {
            $table->json('nominal_seragam_awal')->nullable()->after('nominal_seragam');
            $table->timestamp('waktu_pembayaran_seragam')->nullable()->after('bukti_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->dropColumn(['nominal_biaya_awal', 'waktu_pembayaran_madrasah']);
        });

        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn(['nominal_seragam_awal', 'waktu_pembayaran_seragam']);
        });
    }
};
