<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Remove old/unused ukuran fields from seragam table
     * These fields have been replaced by more specific fields:
     * - ukuran_jas
     * - ukuran_baju_olahraga
     * - ukuran_celana_olahraga
     * - ukuran_baju_harian
     * - ukuran_celana_rok_harian
     * - ukuran_kopyah
     *
     * OLD FIELDS (REMOVED):
     * - tinggi_badan, berat_badan (no longer used)
     * - ukuran_baju, ukuran_celana (replaced by specific fields)
     * - lingkar_dada, lingkar_pinggang, panjang_lengan (no longer used)
     * - ukuran_sepatu, ukuran_topi (no longer used)
     */
    public function up(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            // Drop old/unused fields
            $table->dropColumn([
                'tinggi_badan',         // No longer used
                'berat_badan',          // No longer used
                'ukuran_baju',          // Replaced by ukuran_baju_olahraga & ukuran_baju_harian
                'ukuran_celana',        // Replaced by ukuran_celana_olahraga & ukuran_celana_rok_harian
                'lingkar_dada',         // No longer used
                'lingkar_pinggang',     // No longer used
                'panjang_lengan',       // No longer used
                'ukuran_sepatu',        // No longer used
                'ukuran_topi',          // No longer used (replaced by ukuran_kopyah)
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            // Restore old fields if migration is rolled back
            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->string('ukuran_baju')->nullable();
            $table->string('ukuran_celana')->nullable();
            $table->integer('lingkar_dada')->nullable();
            $table->integer('lingkar_pinggang')->nullable();
            $table->integer('panjang_lengan')->nullable();
            $table->string('ukuran_sepatu')->nullable();
            $table->string('ukuran_topi')->nullable();
        });
    }
};
