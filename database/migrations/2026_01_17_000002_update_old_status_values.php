<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any existing records that might have 'menunggu' to 'menunggu_verifikasi'
        // This handles the case where old data might exist with the 'menunggu' value
        DB::statement("UPDATE pendaftar SET status_pendaftaran = 'menunggu_verifikasi' WHERE status_pendaftaran = 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed for data update
        echo "Data update migration - no reversal needed.\n";
    }
};