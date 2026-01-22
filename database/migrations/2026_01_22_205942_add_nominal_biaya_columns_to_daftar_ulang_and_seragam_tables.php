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
        // Add nominal_biaya column to daftar_ulang table
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->json('nominal_biaya')->nullable()->after('pendaftar_id');
        });

        // Add nominal_seragam column to seragam table
        Schema::table('seragam', function (Blueprint $table) {
            $table->json('nominal_seragam')->nullable()->after('pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->dropColumn('nominal_biaya');
        });

        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn('nominal_seragam');
        });
    }
};
