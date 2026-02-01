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
        // Update all records with status 'proses' to 'menunggu'
        // This ensures consistency with the new status system where
        // uploaded files should show as 'menunggu' (waiting) not 'proses' (in progress)
        DB::table('daftar_ulang')
            ->where('status', 'proses')
            ->update(['status' => 'menunggu']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: change 'menunggu' back to 'proses' if needed
        // Note: This will affect all 'menunggu' records, not just the ones we changed
        DB::table('daftar_ulang')
            ->where('status', 'menunggu')
            ->update(['status' => 'proses']);
    }
};
