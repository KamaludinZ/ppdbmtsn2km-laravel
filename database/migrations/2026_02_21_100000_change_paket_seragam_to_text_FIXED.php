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
        // STEP 1: Update NULL values to empty string first
        // This prevents "NOT NULL violation" error
        DB::statement("UPDATE seragam SET paket_seragam = '' WHERE paket_seragam IS NULL");

        // STEP 2: Change column type to TEXT and make it nullable
        Schema::table('seragam', function (Blueprint $table) {
            // Change paket_seragam from VARCHAR(255) to TEXT
            // Make it nullable to handle existing data safely
            $table->text('paket_seragam')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            // Revert to VARCHAR(255), keep nullable
            $table->string('paket_seragam', 255)->nullable()->change();
        });
    }
};
