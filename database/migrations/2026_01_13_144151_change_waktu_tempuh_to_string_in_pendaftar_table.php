<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom waktu_tempuh dari integer ke string
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN waktu_tempuh TYPE VARCHAR(50) USING waktu_tempuh::VARCHAR');

        // Update comment
        DB::statement("COMMENT ON COLUMN pendaftar.waktu_tempuh IS 'Waktu tempuh ke sekolah (contoh: 1-10 menit, 1-2 jam)'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke integer (akan kehilangan data string)
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN waktu_tempuh TYPE INTEGER USING NULL');

        // Restore comment
        DB::statement("COMMENT ON COLUMN pendaftar.waktu_tempuh IS 'Dalam menit'");
    }
};
