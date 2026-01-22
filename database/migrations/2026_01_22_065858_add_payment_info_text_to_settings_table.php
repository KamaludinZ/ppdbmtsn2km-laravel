<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add payment information text setting for daftar ulang pages
        Setting::set(
            'daftar_ulang_payment_info_text',
            'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.',
            'text',
            'Payment information text displayed on daftar ulang pages (admin, panitia, pendaftar)'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the setting
        Setting::where('key', 'daftar_ulang_payment_info_text')->delete();
    }
};
