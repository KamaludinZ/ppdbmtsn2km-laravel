<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drop check constraint lama dan buat yang baru dengan nilai yang diperbarui
     */
    public function up(): void
    {
        // Drop check constraint yang lama
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_ayah_penghasilan_check');
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_ibu_penghasilan_check');
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_wali_penghasilan_check');

        // Buat check constraint baru dengan nilai yang diperbarui
        $penghasilanValues = [
            'Di bawah Rp. 800,000',
            'Rp. 800,001 - Rp. 1,200,000',
            'Rp. 1,200,001 - Rp. 1,800,000',
            'Rp. 1,800,001 - Rp. 2,500,000',
            'Rp. 2,500,001 - Rp. 3,500,000',
            'Rp. 3,500,001 - Rp. 4,800,000',
            'Rp. 4,800,001 - Rp. 6,500,000',
            'Rp. 6,500,001 - Rp. 10,000,000',
            'Rp. 10,000,001 - Rp. 20,000,000',
            'Lebih dari Rp. 20,000,000'
        ];

        // Format array untuk SQL
        $valuesList = implode("', '", $penghasilanValues);

        // Tambahkan check constraint baru untuk ayah_penghasilan
        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_ayah_penghasilan_check CHECK (ayah_penghasilan IS NULL OR ayah_penghasilan IN ('$valuesList'))");

        // Tambahkan check constraint baru untuk ibu_penghasilan
        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_ibu_penghasilan_check CHECK (ibu_penghasilan IS NULL OR ibu_penghasilan IN ('$valuesList'))");

        // Tambahkan check constraint baru untuk wali_penghasilan
        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_wali_penghasilan_check CHECK (wali_penghasilan IS NULL OR wali_penghasilan IN ('$valuesList'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop constraint yang baru
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_ayah_penghasilan_check');
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_ibu_penghasilan_check');
        DB::statement('ALTER TABLE pendaftar DROP CONSTRAINT IF EXISTS pendaftar_wali_penghasilan_check');

        // Restore constraint yang lama
        $oldValues = [
            'Tidak berpenghasilan',
            'Kurang dari Rp. 500,000',
            'Rp. 500,000 - Rp. 999,999',
            'Rp. 1,000,000 - Rp. 1,999,999',
            'Rp. 2,000,000 - Rp. 4,999,999',
            'Rp. 5,000,000 - Rp. 20,000,000',
            'Lebih dari Rp. 20,000,000'
        ];

        $oldValuesList = implode("', '", $oldValues);

        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_ayah_penghasilan_check CHECK (ayah_penghasilan IS NULL OR ayah_penghasilan IN ('$oldValuesList'))");
        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_ibu_penghasilan_check CHECK (ibu_penghasilan IS NULL OR ibu_penghasilan IN ('$oldValuesList'))");
        DB::statement("ALTER TABLE pendaftar ADD CONSTRAINT pendaftar_wali_penghasilan_check CHECK (wali_penghasilan IS NULL OR wali_penghasilan IN ('$oldValuesList'))");
    }
};
