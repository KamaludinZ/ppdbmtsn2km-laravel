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
            $table->enum('status_seragam', ['menunggu', 'disetujui', 'ditolak', 'perbaiki'])->default('menunggu')->after('petugas_id');
            $table->enum('status_ukuran', ['menunggu', 'disetujui', 'ditolak', 'perbaiki'])->default('menunggu')->after('status_seragam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn(['status_seragam', 'status_ukuran']);
        });
    }
};
