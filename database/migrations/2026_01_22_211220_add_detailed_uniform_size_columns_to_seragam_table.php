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
            // Ukuran detail seragam
            $table->string('ukuran_jas')->nullable()->after('paket_seragam');
            $table->string('ukuran_baju_olahraga')->nullable()->after('ukuran_jas');
            $table->string('ukuran_celana_olahraga')->nullable()->after('ukuran_baju_olahraga');
            $table->string('ukuran_baju_harian')->nullable()->after('ukuran_celana_olahraga');
            $table->string('ukuran_celana_rok_harian')->nullable()->after('ukuran_baju_harian');
            $table->string('ukuran_kopyah')->nullable()->after('ukuran_celana_rok_harian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn([
                'ukuran_jas',
                'ukuran_baju_olahraga',
                'ukuran_celana_olahraga',
                'ukuran_baju_harian',
                'ukuran_celana_rok_harian',
                'ukuran_kopyah',
            ]);
        });
    }
};
