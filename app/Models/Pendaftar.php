<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'user_id',
        'jalur_pendaftaran_id',
        'nomor_pendaftaran',
        'nisn',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'alamat_ayah',
        'alamat_ibu',
        'alamat_wali',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'kode_pos',
        'jarak_ke_sekolah',
        'transportasi',
        'asal_kabkota',
        'telepon',
        'email',
        'no_hp',
        'foto',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'penghasilan_ortu',
        'telepon_ortu',
        'asal_sekolah',
        'jenis_sekolah_asal',
        'nsm_sekolah_asal',
        'npsn_sekolah_asal',
        'alamat_sekolah',
        'nilai_rata_rata',
        'prestasi',
        // Nilai Rapor
        'nilai_b_indo_5_1',
        'nilai_matematika_5_1',
        'nilai_ipa_5_1',
        'nilai_b_indo_5_2',
        'nilai_matematika_5_2',
        'nilai_ipa_5_2',
        'nilai_b_indo_6_1',
        'nilai_matematika_6_1',
        'nilai_ipa_6_1',
        'file_rapor',
        'status_pendaftaran',
        'setuju_daftar_ulang',
        'status_persetujuan_daftar_ulang',
        'alasan_tidak_setuju',
        'tanggal_setuju_daftar_ulang',
        'tanggal_pilihan_daftar_ulang',
        'diperbarui_oleh',
        'catatan_verifikasi',
        'tanggal_daftar',
        'tanggal_verifikasi',

        // EMIS Fields - Data Identitas
        'nik',
        'no_akta_kelahiran',
        'nama_panggilan',
        'kewarganegaraan',
        'negara_asal',
        'nomor_kk',
        'anak_ke',
        'jumlah_saudara_kandung',
        'jumlah_saudara_tiri',
        'golongan_darah',
        'tinggi_badan',
        'berat_badan',
        'bahasa_sehari_hari',
        'yatim_piatu',
        'nomor_telepon_rumah',
        'nomor_hp',
        'email_pribadi',
        'jenis_tinggal',
        'alat_transportasi',
        'jarak_tempat_tinggal',
        'waktu_tempuh',
        'penerima_kps',
        'nomor_kps',
        'penerima_kip',
        'nomor_kip',
        'layak_pip',
        'alasan_layak_pip',

        // EMIS Fields - Data Alamat
        'rt',
        'rw',
        'dusun',
        'latitude',
        'longitude',

        // EMIS Fields - Data Ayah
        'ayah_nik',
        'ayah_tahun_lahir',
        'ayah_pendidikan',
        'ayah_penghasilan',
        'ayah_no_hp',
        'ayah_berkebutuhan_khusus',

        // EMIS Fields - Data Ibu
        'ibu_nik',
        'ibu_tahun_lahir',
        'ibu_pendidikan',
        'ibu_penghasilan',
        'ibu_no_hp',
        'ibu_berkebutuhan_khusus',

        // EMIS Fields - Data Wali
        'memiliki_wali',
        'wali_nik',
        'wali_nama',
        'wali_tahun_lahir',
        'wali_pendidikan',
        'wali_pekerjaan',
        'wali_penghasilan',
        'wali_no_hp',

        // EMIS Fields - Riwayat Pendidikan
        'tk_nama',
        'tk_npsn',
        'tk_tahun_lulus',
        'sd_nama',
        'sd_npsn',
        'sd_tahun_lulus',
        'sd_nomor_peserta_ujian',
        'sd_nomor_seri_ijazah',
        'sd_nomor_skhun',
        'sd_nilai_matematika',
        'sd_nilai_ipa',
        'sd_nilai_ips',
        'sd_nilai_bahasa_indonesia',
        'sd_nilai_bahasa_inggris',
        'hobi',
        'cita_cita',
        'nomor_kitas',
        'jumlah_saudara',
        'nama_kepala_keluarga',
        'tidak_punya_hp',
        'yang_membiayai_sekolah',
        'pra_sekolah_tk',
        'pra_sekolah_paud',
        'imunisasi_hepatitis_b',
        'imunisasi_bcg',
        'imunisasi_dpt',
        'imunisasi_polio',
        'imunisasi_campak',
        'imunisasi_covid',
        'berkebutuhan_khusus',
        'jenis_kebutuhan_khusus',
        'keterangan_kondisi_khusus',
        'file_ijazah_sd',
        'file_skhun_sd',
        'file_kartu_keluarga',
        'file_akta_kelahiran',

        // Tab Data Orang Tua & Wali - Ayah fields
        'ayah_status',
        'ayah_kewarganegaraan',
        'ayah_negara_asal',
        'ayah_nomor_kitas',
        'ayah_tempat_lahir',
        'ayah_tanggal_lahir',
        'ayah_pekerjaan',
        'ayah_tidak_punya_hp',

        // Tab Data Orang Tua & Wali - Ibu fields
        'ibu_status',
        'ibu_kewarganegaraan',
        'ibu_negara_asal',
        'ibu_nomor_kitas',
        'ibu_tempat_lahir',
        'ibu_tanggal_lahir',
        'ibu_pekerjaan',
        'ibu_tidak_punya_hp',

        // Tab Data Orang Tua & Wali - Wali fields
        'wali_hubungan',
        'wali_status',
        'wali_kewarganegaraan',
        'wali_negara_asal',
        'wali_nomor_kitas',
        'wali_tempat_lahir',
        'wali_tanggal_lahir',
        'wali_tidak_punya_hp',

        // Tab Data Orang Tua & Wali - Kartu Bantuan
        'nomor_kks',
        'nomor_pkh',

        // Detailed Address Fields - Ayah
        'ayah_tinggal_luar_negeri',
        'ayah_status_kepemilikan_rumah',
        'ayah_provinsi',
        'ayah_kabupaten',
        'ayah_kecamatan',
        'ayah_kelurahan',
        'ayah_rt',
        'ayah_rw',
        'ayah_kode_pos',

        // Detailed Address Fields - Ibu
        'ibu_tinggal_luar_negeri',
        'ibu_sama_dengan_ayah',
        'ibu_status_kepemilikan_rumah',
        'ibu_provinsi',
        'ibu_kabupaten',
        'ibu_kecamatan',
        'ibu_kelurahan',
        'ibu_rt',
        'ibu_rw',
        'ibu_kode_pos',

        // Detailed Address Fields - Wali
        'wali_status_wali',
        'wali_tinggal_luar_negeri',
        'wali_sama_dengan_ayah',
        'wali_status_kepemilikan_rumah',
        'wali_provinsi',
        'wali_kabupaten',
        'wali_kecamatan',
        'wali_kelurahan',
        'wali_rt',
        'wali_rw',
        'wali_kode_pos',

        // Detailed Address Fields - Siswa
        'status_tempat_tinggal',
        'siswa_status_kepemilikan_rumah',
        'siswa_provinsi',
        'siswa_kabupaten',
        'siswa_kecamatan',
        'siswa_kelurahan',
        'siswa_rt',
        'siswa_rw',
        'siswa_kode_pos',
        'siswa_alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'setuju_daftar_ulang' => 'boolean',
        'tanggal_setuju_daftar_ulang' => 'datetime',
        'tanggal_pilihan_daftar_ulang' => 'datetime',
        'nilai_rata_rata' => 'decimal:2',
        'nilai_b_indo_5_1' => 'decimal:2',
        'nilai_matematika_5_1' => 'decimal:2',
        'nilai_ipa_5_1' => 'decimal:2',
        'nilai_b_indo_5_2' => 'decimal:2',
        'nilai_matematika_5_2' => 'decimal:2',
        'nilai_ipa_5_2' => 'decimal:2',
        'nilai_b_indo_6_1' => 'decimal:2',
        'nilai_matematika_6_1' => 'decimal:2',
        'nilai_ipa_6_1' => 'decimal:2',

        // EMIS Casts
        'penerima_kps' => 'boolean',
        'penerima_kip' => 'boolean',
        'layak_pip' => 'boolean',
        'ayah_berkebutuhan_khusus' => 'boolean',
        'ibu_berkebutuhan_khusus' => 'boolean',
        'memiliki_wali' => 'boolean',
        'berkebutuhan_khusus' => 'boolean',
        'tidak_punya_hp' => 'boolean',
        'pra_sekolah_tk' => 'boolean',
        'pra_sekolah_paud' => 'boolean',
        'imunisasi_hepatitis_b' => 'boolean',
        'imunisasi_bcg' => 'boolean',
        'imunisasi_dpt' => 'boolean',
        'imunisasi_polio' => 'boolean',
        'imunisasi_campak' => 'boolean',
        'imunisasi_covid' => 'boolean',
        'jenis_kebutuhan_khusus' => 'json',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sd_nilai_matematika' => 'decimal:2',
        'sd_nilai_ipa' => 'decimal:2',
        'sd_nilai_ips' => 'decimal:2',
        'sd_nilai_bahasa_indonesia' => 'decimal:2',
        'sd_nilai_bahasa_inggris' => 'decimal:2',

        // Tab Data Orang Tua & Wali casts
        'ayah_tanggal_lahir' => 'date',
        'ayah_tidak_punya_hp' => 'boolean',
        'ibu_tanggal_lahir' => 'date',
        'ibu_tidak_punya_hp' => 'boolean',
        'wali_tanggal_lahir' => 'date',
        'wali_tidak_punya_hp' => 'boolean',
    ];

    /**
     * Get the user that owns this pendaftar
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who last updated the status
     */
    public function diperbaruiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diperbarui_oleh');
    }

    /**
     * Get the jalur pendaftaran
     */
    public function jalurPendaftaran(): BelongsTo
    {
        return $this->belongsTo(JalurPendaftaran::class);
    }

    /**
     * Alias untuk jalurPendaftaran() - untuk kemudahan penggunaan
     */
    public function jalur(): BelongsTo
    {
        return $this->belongsTo(JalurPendaftaran::class, 'jalur_pendaftaran_id');
    }

    /**
     * Get all documents for this pendaftar
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Get verification record
     */
    public function verifikasiBerkas(): HasOne
    {
        return $this->hasOne(VerifikasiBerkas::class);
    }

    /**
     * Get all payments
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    /**
     * Get all prestasi records
     */
    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }

    /**
     * Get all beasiswa records
     */
    public function beasiswas(): HasMany
    {
        return $this->hasMany(Beasiswa::class);
    }

    /**
     * Get all pendidikan lain records
     */
    public function pendidikanLains(): HasMany
    {
        return $this->hasMany(PendidikanLain::class);
    }

    /**
     * Alias untuk prestasis() - untuk kemudahan penggunaan
     */
    public function prestasi(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }

    /**
     * Alias untuk beasiswas() - untuk kemudahan penggunaan
     */
    public function beasiswa(): HasMany
    {
        return $this->hasMany(Beasiswa::class);
    }

    /**
     * Alias untuk pendidikanLains() - untuk kemudahan penggunaan
     */
    public function pendidikan_lain(): HasMany
    {
        return $this->hasMany(PendidikanLain::class);
    }

    /**
     * Get all keahlian records
     */
    public function keahlians(): HasMany
    {
        return $this->hasMany(Keahlian::class);
    }

    /**
     * Alias untuk keahlians() - untuk kemudahan penggunaan
     */
    public function keahlian(): HasMany
    {
        return $this->hasMany(Keahlian::class);
    }

    /**
     * Get tahfidz record
     */
    public function tahfidz(): HasOne
    {
        return $this->hasOne(Tahfidz::class);
    }

    /**
     * Get all survey answers
     */
    public function surveyAnswers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }

    /**
     * Get all message history (riwayat pesan)
     */
    public function riwayatPesan(): HasMany
    {
        return $this->hasMany(RiwayatPesan::class);
    }

    /**
     * Get daftar ulang record
     */
    public function daftarUlang(): HasOne
    {
        return $this->hasOne(DaftarUlang::class);
    }

    /**
     * Get seragam record
     */
    public function seragam(): HasOne
    {
        return $this->hasOne(Seragam::class);
    }

    /**
     * Get tes psikologi pendaftar record
     */
    public function tesPsikologiPendaftar(): HasOne
    {
        return $this->hasOne(TesPsikologiPendaftar::class);
    }

    /**
     * Hitung rata-rata nilai rapor dari 9 nilai
     * (B. Indonesia, Matematika, IPA) x (Kelas 5 Sem 1, Kelas 5 Sem 2, Kelas 6 Sem 1)
     */
    public function hitungRataRataRapor(): float
    {
        $nilai = [
            $this->nilai_b_indo_5_1,
            $this->nilai_matematika_5_1,
            $this->nilai_ipa_5_1,
            $this->nilai_b_indo_5_2,
            $this->nilai_matematika_5_2,
            $this->nilai_ipa_5_2,
            $this->nilai_b_indo_6_1,
            $this->nilai_matematika_6_1,
            $this->nilai_ipa_6_1,
        ];

        // Filter nilai yang tidak null
        $nilaiValid = array_filter($nilai, function($n) {
            return $n !== null;
        });

        if (empty($nilaiValid)) {
            return 0;
        }

        return round(array_sum($nilaiValid) / count($nilaiValid), 2);
    }

    /**
     * Cek apakah nilai rapor memenuhi minimum jalur
     */
    public function memenuhiNilaiMinimum(): bool
    {
        if (!$this->jalurPendaftaran) {
            return false;
        }

        $rataRata = $this->hitungRataRataRapor();
        return $rataRata >= $this->jalurPendaftaran->nilai_minimum;
    }

    /**
     * Get status label dengan warna
     */
    public function getStatusBadge(): array
    {
        $badges = [
            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'yellow'],
            'lengkapi_data' => ['label' => 'Lengkapi Data', 'color' => 'orange'],
            'perbaiki_data' => ['label' => 'Perbaiki Data', 'color' => 'red'],
            'data_ditolak' => ['label' => 'Data Ditolak', 'color' => 'red'],
            'data_disetujui' => ['label' => 'Data Disetujui', 'color' => 'green'],
        ];

        return $badges[$this->status_pendaftaran] ?? ['label' => $this->status_pendaftaran, 'color' => 'gray'];
    }

    /**
     * Send notification after pendaftaran is completed
     */
    public function sendPendaftaranCompletedNotification()
    {
        event(new \App\Events\PendaftarNotificationSent($this, 'pendaftaran_selesai'));
    }

    /**
     * Send verification berkas notification
     */
    public function sendVerifikasiBerkasNotification($status, $catatan = null)
    {
        $notificationType = $status === 'diterima' ?
            'verifikasi_berkas_disetujui' :
            'verifikasi_berkas_ditolak';

        event(new \App\Events\PendaftarNotificationSent($this, $notificationType, $status, $catatan));
    }

    /**
     * Send verification daftar ulang notification
     */
    public function sendVerifikasiDaftarUlangNotification($status, $catatan = null)
    {
        // Don't send notification for pending/initial statuses
        if (in_array($status, ['belum', 'menunggu', 'proses'])) {
            return;
        }

        $notificationType = in_array($status, ['diterima', 'disetujui', 'selesai']) ?
            'verifikasi_daftar_ulang_disetujui' :
            'verifikasi_daftar_ulang_ditolak';

        event(new \App\Events\PendaftarNotificationSent($this, $notificationType, $status, $catatan));
    }

    /**
     * Get EMIS data completeness percentage
     * Returns percentage (0-100) of filled EMIS fields
     */
    public function getEmisCompletenessPercentage(): int
    {
        $details = $this->getEmisCompletenessDetails();
        $totalFields = $details['total_fields'];
        $filledFields = $details['filled_fields'];

        if ($totalFields === 0) {
            return 0;
        }

        return (int) round(($filledFields / $totalFields) * 100);
    }

    /**
     * Get detailed EMIS data completeness breakdown by section
     * Sesuai dengan tab formulir pendaftaran
     */
    public function getEmisCompletenessDetails(): array
    {
        $sections = [
            'data_siswa' => [
                'label' => 'Data Siswa',
                'fields' => [
                    // Identitas (semua wajib)
                    'nama_lengkap', 'nisn', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
                    'kewarganegaraan', 'nik', 'negara_asal', 'nomor_kitas',
                    'nomor_kk', 'nama_kepala_keluarga', 'anak_ke', 'jumlah_saudara',
                    'hobi', 'cita_cita', 'yang_membiayai_sekolah',
                    // No HP (wajib jika tidak check "tidak punya HP")
                    'no_hp',
                    // Foto
                    'foto',
                ],
            ],
            'data_ayah' => [
                'label' => 'Data Ayah',
                'fields' => [
                    // Basic fields (always required)
                    'nama_ayah', 'ayah_status',
                    // Detail fields (required if ayah_status = masih_hidup)
                    'ayah_kewarganegaraan', 'ayah_nik', 'ayah_negara_asal', 'ayah_nomor_kitas',
                    'ayah_tempat_lahir', 'ayah_tanggal_lahir',
                    'ayah_pekerjaan', 'ayah_pendidikan', 'ayah_penghasilan',
                    'ayah_no_hp',
                ],
            ],
            'data_ibu' => [
                'label' => 'Data Ibu',
                'fields' => [
                    // Basic fields (always required)
                    'nama_ibu', 'ibu_status',
                    // Detail fields (required if ibu_status = masih_hidup)
                    'ibu_kewarganegaraan', 'ibu_nik', 'ibu_negara_asal', 'ibu_nomor_kitas',
                    'ibu_tempat_lahir', 'ibu_tanggal_lahir',
                    'ibu_pekerjaan', 'ibu_pendidikan', 'ibu_penghasilan',
                    'ibu_no_hp',
                ],
            ],
            'data_wali' => [
                'label' => 'Data Wali',
                'fields' => [
                    // Hubungan wali (ALWAYS REQUIRED - user must choose)
                    'wali_hubungan',
                    // Detail fields (required if wali_hubungan = lainnya)
                    'wali_nama', 'wali_status',
                    'wali_kewarganegaraan', 'wali_nik', 'wali_negara_asal', 'wali_nomor_kitas',
                    'wali_tempat_lahir', 'wali_tanggal_lahir',
                    'wali_pekerjaan', 'wali_pendidikan', 'wali_penghasilan',
                    'wali_no_hp',
                ],
            ],
            'data_alamat_ayah' => [
                'label' => 'Data Alamat Ayah',
                'fields' => [
                    // Checkbox (ALWAYS required if ayah_status = masih_hidup)
                    'ayah_tinggal_luar_negeri',
                    // Detail fields (required if ayah alive AND not living abroad)
                    'ayah_status_kepemilikan_rumah', 'alamat_ayah',
                    'ayah_provinsi', 'ayah_kabupaten', 'ayah_kecamatan', 'ayah_kelurahan',
                    'ayah_rt', 'ayah_rw', 'ayah_kode_pos',
                ],
            ],
            'data_alamat_ibu' => [
                'label' => 'Data Alamat Ibu',
                'fields' => [
                    // Checkbox (ALWAYS required if ibu_status = masih_hidup)
                    'ibu_sama_dengan_ayah',
                    // Checkbox (required if ibu_status = masih_hidup AND ibu_sama_dengan_ayah = false)
                    'ibu_tinggal_luar_negeri',
                    // Detail fields (required if not same as ayah AND not living abroad)
                    'ibu_status_kepemilikan_rumah', 'alamat_ibu',
                    'ibu_provinsi', 'ibu_kabupaten', 'ibu_kecamatan', 'ibu_kelurahan',
                    'ibu_rt', 'ibu_rw', 'ibu_kode_pos',
                ],
            ],
            'data_alamat_wali' => [
                'label' => 'Data Alamat Wali',
                'fields' => [
                    // Status Wali (ALWAYS REQUIRED - user must choose)
                    'wali_status_wali',
                    // Checkbox (required if wali_status_wali = lainnya)
                    'wali_sama_dengan_ayah',
                    // Checkbox (required if wali_status_wali = lainnya AND wali_sama_dengan_ayah = false)
                    'wali_tinggal_luar_negeri',
                    // Detail fields (required if wali_status_wali = lainnya AND not same as ayah AND not living abroad)
                    'wali_status_kepemilikan_rumah', 'alamat_wali',
                    'wali_provinsi', 'wali_kabupaten', 'wali_kecamatan', 'wali_kelurahan',
                    'wali_rt', 'wali_rw', 'wali_kode_pos',
                ],
            ],
            'data_alamat_siswa' => [
                'label' => 'Data Alamat Siswa',
                'fields' => [
                    // Required if status_tempat_tinggal = lainnya
                    'status_tempat_tinggal', 'siswa_status_kepemilikan_rumah', 'siswa_alamat',
                    'siswa_provinsi', 'siswa_kabupaten', 'siswa_kecamatan', 'siswa_kelurahan',
                    'siswa_rt', 'siswa_rw', 'siswa_kode_pos',
                ],
            ],
        ];

        $sectionStats = [];
        $totalFields = 0;
        $totalFilled = 0;

        foreach ($sections as $key => $section) {
            $applicableFields = 0;
            $filledCount = 0;

            foreach ($section['fields'] as $field) {
                $isApplicable = true;

                // ===== DATA SISWA =====
                // NIK only for WNI, negara_asal & nomor_kitas only for WNA
                if ($field === 'nik') {
                    $isApplicable = ($this->kewarganegaraan === 'WNI');
                }
                if (in_array($field, ['negara_asal', 'nomor_kitas'])) {
                    $isApplicable = ($this->kewarganegaraan === 'WNA');
                }
                // No HP only if tidak_punya_hp = false
                if ($field === 'no_hp' && $key === 'data_siswa') {
                    $isApplicable = !$this->tidak_punya_hp;
                }

                // ===== DATA AYAH =====
                // Detail ayah only if ayah_status = masih_hidup
                if ($key === 'data_ayah' && !in_array($field, ['nama_ayah', 'ayah_status'])) {
                    $isApplicable = ($this->ayah_status === 'masih_hidup');

                    // Ayah NIK only for WNI
                    if ($field === 'ayah_nik' && $this->ayah_kewarganegaraan !== 'WNI') {
                        $isApplicable = false;
                    }
                    // Ayah negara_asal & nomor_kitas only for WNA
                    if (in_array($field, ['ayah_negara_asal', 'ayah_nomor_kitas']) && $this->ayah_kewarganegaraan !== 'WNA') {
                        $isApplicable = false;
                    }
                    // Ayah no HP only if ayah_tidak_punya_hp = false
                    if ($field === 'ayah_no_hp' && $this->ayah_tidak_punya_hp) {
                        $isApplicable = false;
                    }
                }

                // ===== DATA IBU =====
                // Detail ibu only if ibu_status = masih_hidup
                if ($key === 'data_ibu' && !in_array($field, ['nama_ibu', 'ibu_status'])) {
                    $isApplicable = ($this->ibu_status === 'masih_hidup');

                    // Ibu NIK only for WNI
                    if ($field === 'ibu_nik' && $this->ibu_kewarganegaraan !== 'WNI') {
                        $isApplicable = false;
                    }
                    // Ibu negara_asal & nomor_kitas only for WNA
                    if (in_array($field, ['ibu_negara_asal', 'ibu_nomor_kitas']) && $this->ibu_kewarganegaraan !== 'WNA') {
                        $isApplicable = false;
                    }
                    // Ibu no HP only if ibu_tidak_punya_hp = false
                    if ($field === 'ibu_no_hp' && $this->ibu_tidak_punya_hp) {
                        $isApplicable = false;
                    }
                }

                // ===== DATA WALI =====
                // wali_hubungan is ALWAYS REQUIRED (user must choose)
                if ($key === 'data_wali' && $field === 'wali_hubungan') {
                    $isApplicable = true;
                }
                // Detail wali only if wali_hubungan = lainnya
                if ($key === 'data_wali' && $field !== 'wali_hubungan') {
                    $isApplicable = ($this->wali_hubungan === 'lainnya');

                    // Wali NIK only for WNI
                    if ($field === 'wali_nik' && $this->wali_kewarganegaraan !== 'WNI') {
                        $isApplicable = false;
                    }
                    // Wali negara_asal & nomor_kitas only for WNA
                    if (in_array($field, ['wali_negara_asal', 'wali_nomor_kitas']) && $this->wali_kewarganegaraan !== 'WNA') {
                        $isApplicable = false;
                    }
                    // Wali no HP only if wali_tidak_punya_hp = false
                    if ($field === 'wali_no_hp' && $this->wali_tidak_punya_hp) {
                        $isApplicable = false;
                    }
                }

                // ===== DATA ALAMAT AYAH =====
                if ($key === 'data_alamat_ayah') {
                    // Base requirement: ayah must be alive
                    if ($this->ayah_status !== 'masih_hidup') {
                        $isApplicable = false;
                    } else {
                        // ayah_tinggal_luar_negeri checkbox is ALWAYS applicable if ayah alive
                        if ($field === 'ayah_tinggal_luar_negeri') {
                            $isApplicable = true;
                        }
                        // Detail address fields applicable only if not living abroad
                        else {
                            $isApplicable = !$this->ayah_tinggal_luar_negeri;
                        }
                    }
                }

                // ===== DATA ALAMAT IBU =====
                if ($key === 'data_alamat_ibu') {
                    // Base requirement: ibu must be alive
                    if ($this->ibu_status !== 'masih_hidup') {
                        $isApplicable = false;
                    } else {
                        // ibu_sama_dengan_ayah checkbox is ALWAYS applicable if ibu alive
                        if ($field === 'ibu_sama_dengan_ayah') {
                            $isApplicable = true;
                        }
                        // ibu_tinggal_luar_negeri applicable only if not same as ayah
                        elseif ($field === 'ibu_tinggal_luar_negeri') {
                            $isApplicable = !$this->ibu_sama_dengan_ayah;
                        }
                        // Detail address fields applicable only if not same as ayah AND not living abroad
                        else {
                            $isApplicable = !$this->ibu_sama_dengan_ayah && !$this->ibu_tinggal_luar_negeri;
                        }
                    }
                }

                // ===== DATA ALAMAT WALI =====
                if ($key === 'data_alamat_wali') {
                    // wali_status_wali is ALWAYS applicable (user must choose)
                    if ($field === 'wali_status_wali') {
                        $isApplicable = true;
                    }
                    // Other fields only applicable if wali_status_wali = 'lainnya'
                    elseif ($this->wali_status_wali !== 'lainnya') {
                        $isApplicable = false;
                    } else {
                        // wali_sama_dengan_ayah checkbox is applicable if wali_status_wali = lainnya
                        if ($field === 'wali_sama_dengan_ayah') {
                            $isApplicable = true;
                        }
                        // wali_tinggal_luar_negeri applicable only if not same as ayah
                        elseif ($field === 'wali_tinggal_luar_negeri') {
                            $isApplicable = !$this->wali_sama_dengan_ayah;
                        }
                        // Detail address fields applicable only if not same as ayah AND not living abroad
                        else {
                            $isApplicable = !$this->wali_sama_dengan_ayah && !$this->wali_tinggal_luar_negeri;
                        }
                    }
                }

                // ===== DATA ALAMAT SISWA =====
                // status_tempat_tinggal is always required
                if ($key === 'data_alamat_siswa' && $field === 'status_tempat_tinggal') {
                    $isApplicable = true;
                }
                // Other fields required if status_tempat_tinggal = lainnya
                if ($key === 'data_alamat_siswa' && $field !== 'status_tempat_tinggal') {
                    $isApplicable = ($this->status_tempat_tinggal === 'lainnya');
                }

                // Only count applicable fields
                if ($isApplicable) {
                    $applicableFields++;

                    $value = $this->$field;
                    // Check if field is filled (not null and not empty string)
                    if ($value !== null && $value !== '') {
                        $filledCount++;
                    }
                }
            }

            $percentage = $applicableFields > 0 ? (int) round(($filledCount / $applicableFields) * 100) : 0;

            $sectionStats[$key] = [
                'label' => $section['label'],
                'total' => $applicableFields,
                'filled' => $filledCount,
                'percentage' => $percentage,
            ];

            $totalFields += $applicableFields;
            $totalFilled += $filledCount;
        }

        // Add array-based fields (prestasi, beasiswa, pendidikan_lain)
        // Sesuai dengan tab formulir pendaftaran
        // OPTIONAL: tidak wajib diisi, jadi tidak masuk perhitungan total
        $arrayFields = [
            'prestasi' => 'Data Prestasi',
            'beasiswa' => 'Data Beasiswa & Bantuan',
            'pendidikan_lain' => 'Pendidikan Lain'
        ];

        foreach ($arrayFields as $field => $label) {
            // Cek relasi model jika ada
            if (in_array($field, ['prestasi', 'beasiswa', 'pendidikan_lain'])) {
                // Gunakan relasi untuk cek data
                $relationData = $this->$field()->exists();
                $hasFilled = $relationData;
            } else {
                // Fallback ke JSON field
                $arrayData = $this->$field;

                // Decode if JSON string
                if (is_string($arrayData)) {
                    $arrayData = json_decode($arrayData, true);
                }

                // Check if array has data
                $hasFilled = is_array($arrayData) && count($arrayData) > 0;
            }

            $sectionStats[$field] = [
                'label' => $label,
                'total' => 1,
                'filled' => $hasFilled ? 1 : 0,
                'percentage' => $hasFilled ? 100 : 0,
            ];

            // OPTIONAL fields - NOT counted in total percentage
            // $totalFields += 1;
            // $totalFilled += $hasFilled ? 1 : 0;
        }

        return [
            'sections' => $sectionStats,
            'total_fields' => $totalFields,
            'filled_fields' => $totalFilled,
            'percentage' => $totalFields > 0 ? (int) round(($totalFilled / $totalFields) * 100) : 0,
        ];
    }

    /**
     * Check if EMIS data is complete (>= 80% filled)
     */
    public function isEmisDataComplete(): bool
    {
        return $this->getEmisCompletenessPercentage() >= 80;
    }

    /**
     * Get completeness status badge color
     */
    public function getEmisCompletenessBadge(): array
    {
        $percentage = $this->getEmisCompletenessPercentage();

        if ($percentage >= 80) {
            return ['label' => 'Lengkap', 'color' => 'green', 'percentage' => $percentage];
        } elseif ($percentage >= 50) {
            return ['label' => 'Sebagian', 'color' => 'yellow', 'percentage' => $percentage];
        } elseif ($percentage > 0) {
            return ['label' => 'Kurang Lengkap', 'color' => 'orange', 'percentage' => $percentage];
        } else {
            return ['label' => 'Belum Diisi', 'color' => 'red', 'percentage' => $percentage];
        }
    }

    /**
     * Boot method untuk menangani events
     */
    protected static function booted()
    {
        // Event: Setelah Pendaftar dibuat
        static::created(function ($pendaftar) {
            // Buat record VerifikasiBerkas jika belum ada
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

            // Jika file rapor langsung di-upload saat registrasi, update status
            if ($pendaftar->file_rapor) {
                $verifikasi->update(['status_rapor' => 'menunggu_verifikasi']);
            }
        });

        // Event: Setelah Pendaftar diupdate
        static::updated(function ($pendaftar) {
            // Cek apakah file_rapor berubah
            if ($pendaftar->isDirty('file_rapor')) {
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

                // Jika file_rapor ada (baru upload atau ganti file)
                if ($pendaftar->file_rapor) {
                    $verifikasi->update(['status_rapor' => 'menunggu_verifikasi']);
                } else {
                    // Jika file_rapor dihapus
                    $verifikasi->update(['status_rapor' => 'belum_upload']);
                }
            }
        });
    }

    /**
     * Check if all required tabs (Data Siswa, Data Orang Tua, Data Alamat) are completed
     * Based on actual validation rules from save methods
     *
     * @return bool
     */
    public function isRequiredTabsCompleted(): bool
    {
        // Check Data Siswa tab required fields
        $dataSiswaComplete = !empty($this->nama_lengkap)
            && !empty($this->nisn)
            && !empty($this->tempat_lahir)
            && !empty($this->tanggal_lahir)
            && !empty($this->jenis_kelamin)
            && !empty($this->agama)
            && !empty($this->kewarganegaraan)
            && !empty($this->nomor_kk)
            && !empty($this->nama_kepala_keluarga)
            && !empty($this->anak_ke)
            && isset($this->jumlah_saudara)
            && !empty($this->hobi)
            && !empty($this->cita_cita)
            && !empty($this->yang_membiayai_sekolah)
            && !empty($this->foto);

        // Check NIK/KITAS based on citizenship
        if ($this->kewarganegaraan === 'WNI') {
            $dataSiswaComplete = $dataSiswaComplete && !empty($this->nik);
        } elseif ($this->kewarganegaraan === 'WNA') {
            $dataSiswaComplete = $dataSiswaComplete
                && !empty($this->negara_asal)
                && !empty($this->nomor_kitas);
        }

        // Check Data Orang Tua tab required fields
        $dataOrangTuaComplete = !empty($this->nama_ayah)
            && !empty($this->ayah_status)
            && !empty($this->nama_ibu)
            && !empty($this->ibu_status);

        // Check Ayah conditional fields
        if ($this->ayah_status === 'masih_hidup') {
            $dataOrangTuaComplete = $dataOrangTuaComplete
                && !empty($this->ayah_kewarganegaraan)
                && !empty($this->ayah_tempat_lahir)
                && !empty($this->ayah_tanggal_lahir)
                && !empty($this->ayah_pekerjaan);

            // Check Ayah NIK/KITAS
            if ($this->ayah_kewarganegaraan === 'WNI') {
                $dataOrangTuaComplete = $dataOrangTuaComplete && !empty($this->ayah_nik);
            } elseif ($this->ayah_kewarganegaraan === 'WNA') {
                $dataOrangTuaComplete = $dataOrangTuaComplete
                    && !empty($this->ayah_negara_asal)
                    && !empty($this->ayah_nomor_kitas);
            }

            // Check Ayah phone (required if not checked "tidak punya hp")
            if (!$this->ayah_tidak_punya_hp) {
                $dataOrangTuaComplete = $dataOrangTuaComplete && !empty($this->ayah_no_hp);
            }
        }

        // Check Ibu conditional fields
        if ($this->ibu_status === 'masih_hidup') {
            $dataOrangTuaComplete = $dataOrangTuaComplete
                && !empty($this->ibu_kewarganegaraan)
                && !empty($this->ibu_tempat_lahir)
                && !empty($this->ibu_tanggal_lahir)
                && !empty($this->ibu_pekerjaan);

            // Check Ibu NIK/KITAS
            if ($this->ibu_kewarganegaraan === 'WNI') {
                $dataOrangTuaComplete = $dataOrangTuaComplete && !empty($this->ibu_nik);
            } elseif ($this->ibu_kewarganegaraan === 'WNA') {
                $dataOrangTuaComplete = $dataOrangTuaComplete
                    && !empty($this->ibu_negara_asal)
                    && !empty($this->ibu_nomor_kitas);
            }

            // Check Ibu phone (required if not checked "tidak punya hp")
            if (!$this->ibu_tidak_punya_hp) {
                $dataOrangTuaComplete = $dataOrangTuaComplete && !empty($this->ibu_no_hp);
            }
        }

        // Check Data Alamat tab required fields
        // Only email is required in Data Alamat
        $dataAlamatComplete = !empty($this->email);

        // Return true only if all three required tabs are completed
        return $dataSiswaComplete && $dataOrangTuaComplete && $dataAlamatComplete;
    }
}
