<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'pendaftar_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran_file',
        'status_verifikasi',
        'catatan',
        'tanggal_upload',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'ukuran_file' => 'integer',
    ];

    /**
     * Get the pendaftar that owns this document
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Map jenis dokumen to verifikasi_berkas status field
     */
    protected static function mapStatusField($jenisDokumen)
    {
        // Mapping jenis_dokumen ke nama kolom di verifikasi_berkas
        $mapping = [
            'akta_kelahiran' => 'status_akte',
            'raport' => 'status_rapor',
            'kartu_keluarga' => 'status_kk',
            'kk' => 'status_kk',
            'nisn' => 'status_nisn',
            'ijazah' => 'status_ijazah',
            'sertifikat' => 'status_sertifikat',
            'kip' => 'status_kip',
        ];

        return $mapping[$jenisDokumen] ?? 'status_' . $jenisDokumen;
    }

    /**
     * Boot method untuk menangani events
     */
    protected static function booted()
    {
        // Event: Setelah Dokumen dibuat (file di-upload)
        static::created(function ($dokumen) {
            // Map jenis_dokumen ke status field di verifikasi_berkas
            $statusField = static::mapStatusField($dokumen->jenis_dokumen);

            // Update status di verifikasi_berkas
            VerifikasiBerkas::updateOrCreate(
                ['pendaftar_id' => $dokumen->pendaftar_id],
                [$statusField => 'menunggu_verifikasi']
            );
        });

        // Event: Setelah Dokumen diupdate (file diganti/diupload ulang)
        static::updated(function ($dokumen) {
            // Cek apakah path_file berubah (file diganti)
            if ($dokumen->isDirty('path_file')) {
                $statusField = static::mapStatusField($dokumen->jenis_dokumen);

                // Update status di verifikasi_berkas
                VerifikasiBerkas::updateOrCreate(
                    ['pendaftar_id' => $dokumen->pendaftar_id],
                    [$statusField => 'menunggu_verifikasi']
                );
            }
        });

        // Event: Setelah Dokumen dihapus
        static::deleted(function ($dokumen) {
            $statusField = static::mapStatusField($dokumen->jenis_dokumen);

            // Kembalikan status ke belum_upload
            VerifikasiBerkas::where('pendaftar_id', $dokumen->pendaftar_id)
                ->update([$statusField => 'belum_upload']);
        });
    }
}
