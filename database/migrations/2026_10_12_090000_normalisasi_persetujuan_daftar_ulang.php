<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rapikan data persetujuan daftar ulang lama agar setiap pendaftar punya satu status yang jelas
 * (belum_memilih / setuju / tidak_setuju) dan rekap laporan tidak ada yang terlewat.
 * Aman dijalankan berulang kali.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Data sebelum kolom status ada: hanya setuju_daftar_ulang = true → dianggap setuju
        DB::table('pendaftar')
            ->where('setuju_daftar_ulang', true)
            ->where(fn ($q) => $q->whereNull('status_persetujuan_daftar_ulang')->orWhere('status_persetujuan_daftar_ulang', 'belum_memilih'))
            ->update(['status_persetujuan_daftar_ulang' => 'setuju']);

        // Nilai kosong → belum memilih
        DB::table('pendaftar')
            ->whereNull('status_persetujuan_daftar_ulang')
            ->update(['status_persetujuan_daftar_ulang' => 'belum_memilih']);

        // Flag lama mengikuti status (status adalah sumber kebenaran)
        DB::table('pendaftar')->where('status_persetujuan_daftar_ulang', 'tidak_setuju')->update(['setuju_daftar_ulang' => false]);
        DB::table('pendaftar')->where('status_persetujuan_daftar_ulang', 'belum_memilih')->update(['setuju_daftar_ulang' => false]);

        // Alasan hanya relevan untuk "tidak melanjutkan"
        DB::table('pendaftar')
            ->where('status_persetujuan_daftar_ulang', '!=', 'tidak_setuju')
            ->whereNotNull('alasan_tidak_setuju')
            ->update(['alasan_tidak_setuju' => null]);
    }

    public function down(): void
    {
        // Perapian data, tidak perlu dikembalikan
    }
};
