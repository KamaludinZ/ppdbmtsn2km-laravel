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
        Schema::table('pendaftar', function (Blueprint $table) {
            // Tambahkan kolom asal kab/kota jika belum ada
            if (!Schema::hasColumn('pendaftar', 'asal_kabkota')) {
                $table->string('asal_kabkota', 100)->nullable()->after('alamat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftar', 'asal_kabkota')) {
                $table->dropColumn('asal_kabkota');
            }
        });
    }
};
