<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JalurPendaftaran extends Model
{
    protected $table = 'jalur_pendaftaran';

    protected $fillable = [
        'nama',
        'kode',
        'prefix_nomor',
        'deskripsi',
        'deskripsi_lengkap',
        'konten_html',
        'aset_jalur',
        'kuota',
        'nilai_minimum',
        'persyaratan',
        'icon',
        'warna',
        'aktif',
        'tampil_di_daftar',
        'paling_populer',
        'urutan',
    ];

    protected $casts = [
        'persyaratan' => 'array',
        'aset_jalur' => 'array',
        'aktif' => 'boolean',
        'tampil_di_daftar' => 'boolean',
        'paling_populer' => 'boolean',
        'kuota' => 'integer',
        'nilai_minimum' => 'decimal:2',
        'urutan' => 'integer',
    ];

    /**
     * Get all pendaftar for this jalur
     */
    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }

    /**
     * Get histories for this jalur
     */
    public function histories(): HasMany
    {
        return $this->hasMany(JalurPendaftaranHistory::class)->latest();
    }

    /**
     * Get prefix untuk nomor pendaftaran
     * Menggunakan prefix_nomor dari database, atau huruf pertama nama jalur sebagai fallback
     */
    public function getNomorPrefix(): string
    {
        // Gunakan prefix_nomor jika ada, jika tidak ambil huruf pertama dari nama jalur
        return !empty($this->prefix_nomor)
            ? strtoupper($this->prefix_nomor)
            : strtoupper(substr($this->nama, 0, 1));
    }

    /**
     * Generate nomor pendaftaran berikutnya untuk jalur ini
     * Format: [PREFIX]-[YY][MM][0001-9999]
     * Contoh: REG-25040001 (REG prefix, tahun 25, bulan 04, urutan 0001)
     */
    public function generateNomorPendaftaran(): string
    {
        $prefix = $this->getNomorPrefix();

        // Ambil tahun dan bulan saat ini
        $year = date('y');  // 2 digit tahun (contoh: 25 untuk 2025)
        $month = date('m'); // 2 digit bulan (contoh: 04 untuk April)

        // Hitung jumlah pendaftar di jalur ini untuk bulan ini
        $count = $this->pendaftar()
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count() + 1;

        // Format dengan 4 digit urutan
        $number = str_pad($count, 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $year . $month . $number;
    }

    /**
     * Scope untuk jalur yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk jalur yang tampil di halaman daftar
     */
    public function scopeTampilDiDaftar($query)
    {
        return $query->where('tampil_di_daftar', true);
    }

    /**
     * Scope untuk jalur diurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    /**
     * Cek apakah jalur masih menerima pendaftar
     * Kuota 0 = unlimited (tidak dibatasi)
     * Kuota > 0 = dibatasi sesuai kuota
     */
    public function isKuotaTersedia(): bool
    {
        // Jika kuota = 0, berarti unlimited (tidak ada batasan)
        if ($this->kuota == 0) {
            return true;
        }

        // Jika kuota > 0, cek apakah masih ada sisa kuota
        $jumlahPendaftar = $this->pendaftar()->count();
        return $jumlahPendaftar < $this->kuota;
    }

    /**
     * Get sisa kuota
     * Return null jika kuota unlimited (0)
     */
    public function getSisaKuota(): ?int
    {
        // Jika kuota = 0, return null (unlimited)
        if ($this->kuota == 0) {
            return null;
        }

        // Hitung sisa kuota
        $jumlahPendaftar = $this->pendaftar()->count();
        $sisa = $this->kuota - $jumlahPendaftar;
        return max(0, $sisa);
    }

    /**
     * Get label kuota untuk display
     */
    public function getKuotaLabel(): string
    {
        if ($this->kuota == 0) {
            return 'Tidak Terbatas';
        }

        return number_format($this->kuota, 0, ',', '.');
    }
}