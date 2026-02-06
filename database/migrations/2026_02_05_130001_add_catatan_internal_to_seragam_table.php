<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add catatan_internal columns to seragam table
     * For admin/panitia internal notes - NOT shown to pendaftar
     * Covers both seragam payment and ukuran sections
     */
    public function up(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            // Catatan internal untuk daftar ulang seragam (pembayaran)
            $table->text('catatan_internal_seragam')->nullable()->after('catatan_seragam');

            // Catatan internal untuk ukuran seragam
            $table->text('catatan_internal_ukuran')->nullable()->after('catatan_ukuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn(['catatan_internal_seragam', 'catatan_internal_ukuran']);
        });
    }
};
