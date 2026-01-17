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
     * Mengubah panjang maksimal kolom nomor HP dari 20 ke 15 digit
     */
    public function up(): void
    {
        // Ubah semua kolom nomor HP menjadi VARCHAR(15)
        $columns = [
            'telepon',
            'no_hp',
            'telepon_ortu',
            'ayah_no_hp',
            'ibu_no_hp',
            'wali_no_hp',
            'nomor_telepon_rumah',
        ];

        foreach ($columns as $column) {
            // Truncate existing data yang lebih dari 15 karakter
            DB::statement("UPDATE pendaftar SET {$column} = LEFT({$column}, 15) WHERE LENGTH({$column}) > 15");

            // Ubah tipe kolom
            DB::statement("ALTER TABLE pendaftar ALTER COLUMN {$column} TYPE VARCHAR(15)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke VARCHAR(20)
        $columns = [
            'telepon',
            'no_hp',
            'telepon_ortu',
            'ayah_no_hp',
            'ibu_no_hp',
            'wali_no_hp',
            'nomor_telepon_rumah',
        ];

        foreach ($columns as $column) {
            DB::statement("ALTER TABLE pendaftar ALTER COLUMN {$column} TYPE VARCHAR(20)");
        }
    }
};
