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
        Schema::table('seragam', function (Blueprint $table) {
            // Add opsi_seragam field (stores selected option JSON from package)
            if (!Schema::hasColumn('seragam', 'opsi_seragam')) {
                $table->text('opsi_seragam')->nullable()->after('paket_seragam')->comment('Selected option from package (JSON)');
            }

            // Ensure nama_pengirim_seragam exists (might have been added in previous migration)
            if (!Schema::hasColumn('seragam', 'nama_pengirim_seragam')) {
                $table->string('nama_pengirim_seragam')->nullable()->after('bank_seragam')->comment('Name of payment sender');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            if (Schema::hasColumn('seragam', 'opsi_seragam')) {
                $table->dropColumn('opsi_seragam');
            }
            // Don't drop nama_pengirim_seragam as it might be used by other features
        });
    }
};
