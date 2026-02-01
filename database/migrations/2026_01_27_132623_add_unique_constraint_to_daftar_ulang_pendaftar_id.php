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
        Schema::table('daftar_ulang', function (Blueprint $table) {
            // Add unique constraint to pendaftar_id to prevent duplicate records
            $table->unique('pendaftar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['pendaftar_id']);
        });
    }
};
