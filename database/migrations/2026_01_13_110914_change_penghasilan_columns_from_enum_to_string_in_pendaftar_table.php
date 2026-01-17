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
     * Mengubah kolom penghasilan dari ENUM ke STRING untuk fleksibilitas
     */
    public function up(): void
    {
        // PostgreSQL requires us to drop the enum type and recreate as string
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN ayah_penghasilan TYPE VARCHAR(100) USING ayah_penghasilan::VARCHAR');
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN ibu_penghasilan TYPE VARCHAR(100) USING ibu_penghasilan::VARCHAR');
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN wali_penghasilan TYPE VARCHAR(100) USING wali_penghasilan::VARCHAR');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to string (cannot easily revert to exact enum)
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN ayah_penghasilan TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN ibu_penghasilan TYPE VARCHAR(255)');
        DB::statement('ALTER TABLE pendaftar ALTER COLUMN wali_penghasilan TYPE VARCHAR(255)');
    }
};
