<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add catatan_internal column to daftar_ulang table
     * For admin/panitia internal notes - NOT shown to pendaftar
     */
    public function up(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->text('catatan_internal')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->dropColumn('catatan_internal');
        });
    }
};
