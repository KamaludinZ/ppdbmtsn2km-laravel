<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pendaftar;
use App\Models\Dokumen;
use App\Models\VerifikasiBerkas;

class SyncDokumenStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dokumen:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi status verifikasi dokumen berdasarkan file yang sudah di-upload';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi status dokumen...');

        $pendaftarCount = 0;
        $updatedCount = 0;

        // Loop semua pendaftar
        Pendaftar::with('dokumen')->chunk(100, function ($pendaftarList) use (&$pendaftarCount, &$updatedCount) {
            foreach ($pendaftarList as $pendaftar) {
                $pendaftarCount++;

                // Buat atau ambil record verifikasi berkas
                $verifikasi = VerifikasiBerkas::firstOrCreate(
                    ['pendaftar_id' => $pendaftar->id],
                    [
                        'status' => 'menunggu',
                        'status_rapor' => 'belum_upload',
                        'status_akte' => 'belum_upload',
                        'status_kk' => 'belum_upload',
                        'status_nisn' => 'belum_upload',
                        'status_ijazah' => 'belum_upload',
                        'status_sertifikat' => 'belum_upload',
                        'status_kip' => 'belum_upload',
                    ]
                );

                $needsUpdate = false;
                $updateData = [];

                // 1. Cek file rapor dari pendaftar
                if ($pendaftar->file_rapor && $verifikasi->status_rapor === 'belum_upload') {
                    $updateData['status_rapor'] = 'menunggu_verifikasi';
                    $needsUpdate = true;
                    $this->line("  - Pendaftar #{$pendaftar->id} ({$pendaftar->nama_lengkap}): Rapor ditemukan");
                }

                // 2. Cek dokumen lain dari tabel dokumen
                foreach ($pendaftar->dokumen as $dokumen) {
                    $statusField = 'status_' . $dokumen->jenis_dokumen;

                    if (property_exists($verifikasi, $statusField) && $verifikasi->$statusField === 'belum_upload') {
                        $updateData[$statusField] = 'menunggu_verifikasi';
                        $needsUpdate = true;
                        $this->line("  - Pendaftar #{$pendaftar->id} ({$pendaftar->nama_lengkap}): {$dokumen->jenis_dokumen} ditemukan");
                    }
                }

                // Update jika ada perubahan
                if ($needsUpdate) {
                    $verifikasi->update($updateData);
                    $updatedCount++;
                }
            }
        });

        $this->newLine();
        $this->info("Sinkronisasi selesai!");
        $this->info("Total pendaftar diproses: {$pendaftarCount}");
        $this->info("Total pendaftar yang diupdate: {$updatedCount}");

        return Command::SUCCESS;
    }
}
