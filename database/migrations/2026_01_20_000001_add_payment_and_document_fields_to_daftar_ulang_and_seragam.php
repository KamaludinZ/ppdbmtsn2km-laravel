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
        // Add payment and document fields to daftar_ulang table
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->string('opsi_pembayaran')->nullable()->after('status');
            $table->string('metode_pembayaran')->nullable()->after('opsi_pembayaran');
            $table->string('bukti_pembayaran_madrasah')->nullable()->after('metode_pembayaran');
            $table->string('surat_pernyataan_path')->nullable()->after('bukti_pembayaran_madrasah');
        });

        // Add payment and document fields to seragam table
        Schema::table('seragam', function (Blueprint $table) {
            $table->string('paket_seragam')->nullable()->after('pendaftar_id');
            $table->string('opsi_pembayaran_seragam')->nullable()->after('paket_seragam');
            $table->string('bank_seragam')->nullable()->after('opsi_pembayaran_seragam');
            $table->string('bukti_pembayaran')->nullable()->after('bank_seragam');
            $table->text('catatan_seragam')->nullable()->after('status_ukuran');
            $table->text('catatan_ukuran')->nullable()->after('catatan_seragam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_ulang', function (Blueprint $table) {
            $table->dropColumn([
                'opsi_pembayaran',
                'metode_pembayaran',
                'bukti_pembayaran_madrasah',
                'surat_pernyataan_path'
            ]);
        });

        Schema::table('seragam', function (Blueprint $table) {
            $table->dropColumn([
                'paket_seragam',
                'opsi_pembayaran_seragam',
                'bank_seragam',
                'bukti_pembayaran',
                'catatan_seragam',
                'catatan_ukuran'
            ]);
        });
    }
};
