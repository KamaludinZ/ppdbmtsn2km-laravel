<?php

namespace App\Support;

use App\Models\Pendaftar;

/**
 * Ringkasan status pendaftar untuk halaman cek status publik (tanpa login).
 * Dipakai oleh /cek-pendaftaran dan kotak cek status di beranda.
 */
class StatusPendaftaran
{
    public const MAX_PERCOBAAN_PER_MENIT = 10;

    /** Warna badge: kelas ditulis lengkap agar ikut ter-compile Tailwind. */
    public const TONE = [
        'gray' => ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'text' => 'text-gray-700', 'dot' => 'bg-gray-400'],
        'yellow' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-800', 'dot' => 'bg-amber-500'],
        'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'dot' => 'bg-blue-500'],
        'orange' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'text' => 'text-orange-800', 'dot' => 'bg-orange-500'],
        'red' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'dot' => 'bg-red-500'],
        'green' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'dot' => 'bg-emerald-600'],
    ];

    private const VERIFIKASI = [
        'menunggu_verifikasi' => ['Menunggu Verifikasi', 'yellow'],
        'menunggu' => ['Menunggu Verifikasi', 'yellow'],
        'baru' => ['Menunggu Verifikasi', 'yellow'],
        'diproses' => ['Sedang Diverifikasi', 'blue'],
        'lengkapi_data' => ['Lengkapi Data', 'orange'],
        'perbaiki_data' => ['Perbaiki Data', 'orange'],
        'data_ditolak' => ['Data Ditolak', 'red'],
        'dicabut' => ['Pendaftaran Dicabut', 'red'],
        'data_disetujui' => ['Data Disetujui', 'green'],
    ];

    private const KELULUSAN = [
        'lulus' => ['Lulus', 'green'],
        'diterima' => ['Diterima', 'green'],
        'selesai' => ['Diterima', 'green'],
        'cadangan' => ['Cadangan', 'blue'],
        'tidak_lulus' => ['Tidak Lulus', 'red'],
        'ditolak' => ['Tidak Lulus', 'red'],
    ];

    private const PROSES = [
        'belum_diisi' => ['Belum Diisi', 'gray'],
        'menunggu' => ['Menunggu Verifikasi', 'yellow'],
        'proses' => ['Menunggu Verifikasi', 'yellow'],
        'pending' => ['Menunggu Verifikasi', 'yellow'],
        'perbaiki' => ['Perlu Perbaikan', 'orange'],
        'perlu_perbaikan' => ['Perlu Perbaikan', 'orange'],
        'ditolak' => ['Ditolak', 'red'],
        'disetujui' => ['Disetujui', 'green'],
        'selesai' => ['Disetujui', 'green'],
    ];

    /**
     * Rapikan input: buang spasi, huruf besar untuk nomor pendaftaran.
     * Mengembalikan null bila formatnya tidak mungkin nomor pendaftaran / NISN.
     */
    public static function normalisasi(?string $input): ?string
    {
        $input = strtoupper(preg_replace('/\s+/', '', (string) $input));

        return preg_match('/^[A-Z0-9][A-Z0-9\-\/.]{2,39}$/', $input) ? $input : null;
    }

    /**
     * Cari pendaftar berdasarkan nomor pendaftaran (tidak peka huruf besar/kecil) atau NISN.
     */
    public static function cari(string $kode): ?Pendaftar
    {
        return Pendaftar::query()
            ->where(function ($q) use ($kode) {
                $q->whereRaw('UPPER(nomor_pendaftaran) = ?', [$kode]);
                if (ctype_digit($kode)) {
                    $q->orWhere('nisn', $kode);
                }
            })
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Susun semua status dalam bentuk siap tampil.
     */
    public static function ringkasan(Pendaftar $p): array
    {
        $p->loadMissing(['jalurPendaftaran', 'tesPsikologiPendaftar', 'daftarUlang', 'seragam']);

        $status = (string) $p->status_pendaftaran;
        $isKelulusan = isset(self::KELULUSAN[$status]);
        $isLulus = in_array($status, ['lulus', 'diterima', 'selesai'], true);

        // Status kelulusan hanya berarti "sudah diverifikasi"
        $verifikasi = $isKelulusan && $status !== 'ditolak'
            ? self::item('Verifikasi Pendaftaran', 'Data Disetujui', 'green')
            : self::dari('Verifikasi Pendaftaran', self::VERIFIKASI, $status, 'Menunggu Verifikasi', 'yellow');

        $kelulusan = $isKelulusan
            ? self::dari('Status Kelulusan', self::KELULUSAN, $status)
            : self::item('Status Kelulusan', 'Belum Diumumkan', 'gray');

        $psikologiStatus = $p->tesPsikologiPendaftar?->status_verifikasi;
        $psikologi = $psikologiStatus
            ? self::dari('Biaya Tes Psikologi', self::PROSES + ['belum_upload' => ['Belum Upload Bukti', 'gray']], $psikologiStatus)
            : self::item('Biaya Tes Psikologi', 'Belum Upload Bukti', 'gray');

        $utama = [$verifikasi, $psikologi, $kelulusan];

        $daftarUlang = [];
        if ($isLulus) {
            $kategori = RekapDaftarUlang::kategori($p, 'madrasah');
            $tone = ['belum_konfirmasi' => 'gray', 'tidak_melanjutkan' => 'red', 'belum_mengisi' => 'gray', 'menunggu_verifikasi' => 'yellow', 'perlu_perbaikan' => 'orange', 'ditolak' => 'red', 'selesai' => 'green'][$kategori] ?? 'gray';
            $daftarUlang = [
                self::item('Daftar Ulang Madrasah', RekapDaftarUlang::label($kategori), $tone),
                self::dari('Daftar Ulang Seragam', self::PROSES, $p->seragam?->status_seragam, 'Belum Diisi', 'gray'),
                self::dari('Ukuran Seragam', self::PROSES, $p->seragam?->status_ukuran, 'Belum Diisi', 'gray'),
            ];
        }

        return [
            'nomor' => $p->nomor_pendaftaran,
            'nama' => $p->nama_lengkap,
            'nisn' => self::samarkan($p->nisn),
            'jalur' => $p->jalurPendaftaran?->nama ?? '-',
            'tanggal_daftar' => ($p->tanggal_daftar ?? $p->created_at)?->copy()->locale('id')->translatedFormat('d F Y') ?? '-',
            'utama' => $utama,
            'daftar_ulang' => $daftarUlang,
            'catatan' => in_array($status, ['lengkapi_data', 'perbaiki_data', 'data_ditolak'], true) ? trim((string) $p->catatan_verifikasi) : '',
            'petunjuk' => self::petunjuk($status),
        ];
    }

    /** Tampilkan sebagian NISN saja (mis. 00123*****). */
    public static function samarkan(?string $nisn): string
    {
        $nisn = (string) $nisn;

        return strlen($nisn) > 5 ? substr($nisn, 0, 5) . str_repeat('•', strlen($nisn) - 5) : ($nisn ?: '-');
    }

    private static function petunjuk(string $status): ?array
    {
        return match ($status) {
            'menunggu_verifikasi', 'menunggu', 'baru', 'diproses' => ['yellow', 'Pendaftaran sedang diverifikasi panitia', 'Proses verifikasi biasanya 1–3 hari kerja. Pantau status ini secara berkala dan pastikan nomor WhatsApp/email aktif.'],
            'lengkapi_data', 'perbaiki_data' => ['orange', 'Data perlu dilengkapi/diperbaiki', 'Login ke dashboard pendaftar, perbaiki data sesuai catatan panitia, lalu simpan kembali.'],
            'data_ditolak', 'dicabut' => ['red', 'Pendaftaran tidak dapat diproses', 'Baca catatan panitia dan hubungi panitia untuk informasi lebih lanjut.'],
            'data_disetujui' => ['green', 'Data pendaftaran sudah disetujui', 'Tunggu pengumuman hasil seleksi sesuai jadwal.'],
            'lulus', 'diterima', 'selesai' => ['green', 'Selamat, Anda dinyatakan lulus!', 'Login ke dashboard pendaftar untuk konfirmasi dan menyelesaikan daftar ulang sesuai jadwal.'],
            'cadangan' => ['blue', 'Anda masuk daftar cadangan', 'Pantau pengumuman berikutnya dari panitia.'],
            'tidak_lulus', 'ditolak' => ['red', 'Mohon maaf, Anda belum lulus seleksi', 'Terima kasih telah mendaftar. Hubungi panitia bila ada pertanyaan.'],
            default => null,
        };
    }

    private static function dari(string $judul, array $peta, ?string $status, string $default = '-', string $defaultTone = 'gray'): array
    {
        [$label, $tone] = $peta[$status] ?? [$status ? ucwords(str_replace('_', ' ', $status)) : $default, $status ? 'gray' : $defaultTone];

        return self::item($judul, $label, $tone);
    }

    private static function item(string $judul, string $label, string $tone): array
    {
        return ['judul' => $judul, 'label' => $label, 'tone' => self::TONE[$tone] ?? self::TONE['gray']];
    }
}
