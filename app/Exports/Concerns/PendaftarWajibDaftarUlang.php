<?php

namespace App\Exports\Concerns;

use App\Models\Pendaftar;
use App\Support\RekapDaftarUlang;

/**
 * Query bersama untuk export daftar ulang: berbasis SEMUA pendaftar wajib daftar ulang
 * (lulus/diterima), bukan hanya yang sudah punya data seragam/daftar ulang, sehingga
 * jumlah baris Excel selalu sama dengan rekap di layar.
 */
trait PendaftarWajibDaftarUlang
{
    protected function queryPendaftarWajib(?string $kategori, string $jenis, $search = null, $jalur = null, $jenisKelamin = null)
    {
        $query = Pendaftar::with(['jalurPendaftaran', 'user', 'daftarUlang', 'seragam'])
            ->whereIn('status_pendaftaran', RekapDaftarUlang::STATUS_WAJIB)
            ->when($jalur, fn ($q) => $q->where('jalur_pendaftaran_id', $jalur))
            ->when($jenisKelamin, fn ($q) => $q->where('jenis_kelamin', $jenisKelamin))
            ->when($search, fn ($q) => $q->where(fn ($w) => $w
                ->where('nama_lengkap', 'ilike', '%' . $search . '%')
                ->orWhere('nomor_pendaftaran', 'ilike', '%' . $search . '%')));

        if ($kategori && isset(RekapDaftarUlang::KATEGORI[$kategori])) {
            RekapDaftarUlang::filterKategori($query, $kategori, $jenis);
        } elseif ($kategori) {
            // Kompatibel dengan filter lama yang memakai nilai status mentah (menunggu, disetujui, ...)
            $kolom = $jenis === 'ukuran' ? 'status_ukuran' : ($jenis === 'seragam' ? 'status_seragam' : null);
            if ($kolom) {
                $query->whereHas('seragam', fn ($q) => $q->where($kolom, $kategori));
            } else {
                $query->whereHas('daftarUlang', fn ($q) => $q->where('status', $kategori));
            }
        }

        return $query->orderBy('nama_lengkap');
    }
}
