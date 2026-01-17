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
        Schema::table('tes_psikologi_settings', function (Blueprint $table) {
            // Remove waktu_pembayaran field
            $table->dropColumn('waktu_pembayaran');

            // Add rekening (account) information fields
            $table->string('rekening_bank_nama')->nullable()->after('informasi');
            $table->string('rekening_nomor')->nullable()->after('rekening_bank_nama');
            $table->string('rekening_atas_nama')->nullable()->after('rekening_nomor');
            $table->text('rekening_keterangan')->nullable()->after('rekening_atas_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tes_psikologi_settings', function (Blueprint $table) {
            // Restore waktu_pembayaran field
            $table->integer('waktu_pembayaran')->default(7)->after('informasi');

            // Remove rekening fields
            $table->dropColumn([
                'rekening_bank_nama',
                'rekening_nomor',
                'rekening_atas_nama',
                'rekening_keterangan'
            ]);
        });
    }
};
