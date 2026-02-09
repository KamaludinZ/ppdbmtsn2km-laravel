<?php

namespace App\Exports;

use App\Models\Pendaftar;
use App\Models\SurveyQuestion;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class PendaftarExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $jalurId;
    protected $statusPendaftaran;
    protected $search;
    protected $surveyQuestions;
    protected $maxPrestasi = 3;  // Show up to 3 prestasi records
    protected $maxBeasiswa = 3;  // Show up to 3 beasiswa records
    protected $maxPendidikanLain = 3;  // Show up to 3 pendidikan lain records
    protected $regionCache = [];  // Cache for region names

    public function __construct($jalurId = null, $statusPendaftaran = null, $search = null)
    {
        $this->jalurId = $jalurId;
        $this->statusPendaftaran = $statusPendaftaran;
        $this->search = $search;

        // Load active survey questions untuk kolom dinamis (hanya jika survey aktif)
        // PENTING: Filter by context 'tes_psikologi' karena ini export untuk Tes Psikologi
        $setting = \App\Models\TesPsikologiSetting::first();
        if ($setting && $setting->survey_aktif) {
            $this->surveyQuestions = SurveyQuestion::tesPsikologi()->aktif()->ordered()->get();
        } else {
            $this->surveyQuestions = collect(); // Empty collection jika survey tidak aktif
        }

        // Preload all regions to cache for performance
        $this->loadRegionCache();
    }

    /**
     * Preload region names to cache
     * FIXED: Use 'id' instead of 'code' column (production doesn't have 'code' column)
     */
    private function loadRegionCache()
    {
        // Load provinces - use id as key (production DB stores id in pendaftar fields)
        $provinces = \DB::table('indonesia_provinces')->get(['id', 'name']);
        foreach ($provinces as $province) {
            $this->regionCache[(string)$province->id] = $province->name;
        }

        // Load cities (kabupaten/kota)
        $cities = \DB::table('indonesia_cities')->get(['id', 'name']);
        foreach ($cities as $city) {
            $this->regionCache[(string)$city->id] = $city->name;
        }

        // Load districts (kecamatan)
        $districts = \DB::table('indonesia_districts')->get(['id', 'name']);
        foreach ($districts as $district) {
            $this->regionCache[(string)$district->id] = $district->name;
        }

        // Load villages (kelurahan/desa)
        $villages = \DB::table('indonesia_villages')->get(['id', 'name']);
        foreach ($villages as $village) {
            $this->regionCache[(string)$village->id] = $village->name;
        }
    }

    public function query()
    {
        $query = Pendaftar::with([
            'jalurPendaftaran',
            'user',
            'dokumen',
            'prestasis',
            'beasiswas',
            'pendidikanLains',
            'surveyAnswers.question.options',
            'tesPsikologiPendaftar',
            'daftarUlang',
            'seragam'
        ]);

        if ($this->jalurId) {
            $query->where('jalur_pendaftaran_id', $this->jalurId);
        }

        if ($this->statusPendaftaran) {
            $query->where('status_pendaftaran', $this->statusPendaftaran);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function map($pendaftar): array
    {
        $baseData = [];

        // ========================================
        // IDENTITAS PENDAFTAR (4 columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->nomor_pendaftaran,
            $pendaftar->jalurPendaftaran->nama ?? '',
            $this->getStatusLabel($pendaftar->status_pendaftaran),
            $pendaftar->tanggal_daftar ? Carbon::parse($pendaftar->tanggal_daftar)->format('d-m-Y H:i') : '',
        ]);

        // ========================================
        // DATA SISWA (30+ columns with conditional logic)
        // ========================================
        $baseData = array_merge($baseData, [
            // Identitas Dasar
            $pendaftar->nama_lengkap,
            $pendaftar->nisn,
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $pendaftar->tempat_lahir,
            $pendaftar->tanggal_lahir ? Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y') : '',
            $pendaftar->agama,

            // Kewarganegaraan (conditional)
            $pendaftar->kewarganegaraan,
            // NIK (only for WNI)
            $pendaftar->kewarganegaraan === 'WNI' ? $pendaftar->nik : '',
            // Asal Negara & Nomor Izin Tinggal (only for WNA)
            $pendaftar->kewarganegaraan === 'WNA' ? $pendaftar->negara_asal : '',
            $pendaftar->kewarganegaraan === 'WNA' ? $pendaftar->nomor_kitas : '',

            // Data Keluarga
            $pendaftar->jumlah_saudara,
            $pendaftar->anak_ke,
            $pendaftar->hobi,
            $pendaftar->cita_cita,

            // Kontak (conditional)
            $pendaftar->tidak_punya_hp ? 'Tidak Ada' : ($pendaftar->nomor_hp ?? $pendaftar->no_hp),
            $pendaftar->email_pribadi ?? $pendaftar->user->email ?? '',

            // Yang Membiayai
            $pendaftar->yang_membiayai_sekolah,

            // Pra Sekolah
            $this->formatBoolean($pendaftar->pra_sekolah_tk, 'TK'),
            $this->formatBoolean($pendaftar->pra_sekolah_paud, 'PAUD'),

            // Imunisasi (6 jenis)
            $this->formatBoolean($pendaftar->imunisasi_hepatitis_b, 'Ya', 'Tidak'),
            $this->formatBoolean($pendaftar->imunisasi_bcg, 'Ya', 'Tidak'),
            $this->formatBoolean($pendaftar->imunisasi_dpt, 'Ya', 'Tidak'),
            $this->formatBoolean($pendaftar->imunisasi_polio, 'Ya', 'Tidak'),
            $this->formatBoolean($pendaftar->imunisasi_campak, 'Ya', 'Tidak'),
            $this->formatBoolean($pendaftar->imunisasi_covid, 'Ya', 'Tidak'),

            // Nomor Kartu
            $pendaftar->nomor_kip,
            $pendaftar->nomor_kk,
            $pendaftar->nama_kepala_keluarga,

            // Asal Sekolah
            $pendaftar->asal_sekolah,
            $pendaftar->jenis_sekolah_asal,
            $pendaftar->nsm_sekolah_asal,
            $pendaftar->npsn_sekolah_asal,
        ]);

        // ========================================
        // DATA ORANG TUA - AYAH (15+ columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->nama_ayah,
            $pendaftar->ayah_status,
        ]);

        // Conditional fields (only if ayah masih hidup)
        if ($pendaftar->ayah_status === 'masih_hidup') {
            $baseData = array_merge($baseData, [
                $pendaftar->ayah_kewarganegaraan,
                // NIK or WNA fields
                $pendaftar->ayah_kewarganegaraan === 'WNI' ? $pendaftar->ayah_nik : '',
                $pendaftar->ayah_kewarganegaraan === 'WNA' ? $pendaftar->ayah_negara_asal : '',
                $pendaftar->ayah_kewarganegaraan === 'WNA' ? $pendaftar->ayah_nomor_kitas : '',
                // Detail
                $pendaftar->ayah_tempat_lahir,
                $pendaftar->ayah_tanggal_lahir ? Carbon::parse($pendaftar->ayah_tanggal_lahir)->format('d-m-Y') : '',
                $pendaftar->ayah_pendidikan,
                $pendaftar->ayah_pekerjaan,
                $pendaftar->ayah_penghasilan,
                // HP (conditional)
                $pendaftar->ayah_tidak_punya_hp ? 'Tidak Ada' : $pendaftar->ayah_no_hp,
                // Kartu Bantuan
                $pendaftar->nomor_kks,
                $pendaftar->nomor_pkh,
            ]);
        } else {
            // Fill empty values if not alive
            $baseData = array_merge($baseData, array_fill(0, 13, ''));
        }

        // ========================================
        // DATA ORANG TUA - IBU (15+ columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->nama_ibu,
            $pendaftar->ibu_status,
        ]);

        // Conditional fields (only if ibu masih hidup)
        if ($pendaftar->ibu_status === 'masih_hidup') {
            $baseData = array_merge($baseData, [
                $pendaftar->ibu_kewarganegaraan,
                // NIK or WNA fields
                $pendaftar->ibu_kewarganegaraan === 'WNI' ? $pendaftar->ibu_nik : '',
                $pendaftar->ibu_kewarganegaraan === 'WNA' ? $pendaftar->ibu_negara_asal : '',
                $pendaftar->ibu_kewarganegaraan === 'WNA' ? $pendaftar->ibu_nomor_kitas : '',
                // Detail
                $pendaftar->ibu_tempat_lahir,
                $pendaftar->ibu_tanggal_lahir ? Carbon::parse($pendaftar->ibu_tanggal_lahir)->format('d-m-Y') : '',
                $pendaftar->ibu_pendidikan,
                $pendaftar->ibu_pekerjaan,
                $pendaftar->ibu_penghasilan,
                // HP (conditional)
                $pendaftar->ibu_tidak_punya_hp ? 'Tidak Ada' : $pendaftar->ibu_no_hp,
            ]);
        } else {
            // Fill empty values if not alive
            $baseData = array_merge($baseData, array_fill(0, 11, ''));
        }

        // ========================================
        // DATA WALI (17+ columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->wali_hubungan,
        ]);

        // Conditional fields (only if wali_hubungan = lainnya)
        if ($pendaftar->wali_hubungan === 'lainnya') {
            $baseData = array_merge($baseData, [
                $pendaftar->wali_nama,
                $pendaftar->wali_status,
                $pendaftar->wali_kewarganegaraan,
                // NIK or WNA fields
                $pendaftar->wali_kewarganegaraan === 'WNI' ? $pendaftar->wali_nik : '',
                $pendaftar->wali_kewarganegaraan === 'WNA' ? $pendaftar->wali_negara_asal : '',
                $pendaftar->wali_kewarganegaraan === 'WNA' ? $pendaftar->wali_nomor_kitas : '',
                // Detail
                $pendaftar->wali_tempat_lahir,
                $pendaftar->wali_tanggal_lahir ? Carbon::parse($pendaftar->wali_tanggal_lahir)->format('d-m-Y') : '',
                $pendaftar->wali_pekerjaan,
                $pendaftar->wali_pendidikan,
                $pendaftar->wali_penghasilan,
                // HP (conditional)
                $pendaftar->wali_tidak_punya_hp ? 'Tidak Ada' : $pendaftar->wali_no_hp,
            ]);
        } else {
            // Fill empty values if wali is not 'lainnya'
            $baseData = array_merge($baseData, array_fill(0, 12, ''));
        }

        // ========================================
        // DATA ALAMAT AYAH (12+ columns)
        // ========================================
        if ($pendaftar->ayah_status === 'masih_hidup') {
            $baseData = array_merge($baseData, [
                $this->formatBoolean($pendaftar->ayah_tinggal_luar_negeri, 'Ya', 'Tidak'),
            ]);

            // Detail address (only if not living abroad)
            if (!$pendaftar->ayah_tinggal_luar_negeri) {
                $baseData = array_merge($baseData, [
                    $pendaftar->ayah_status_kepemilikan_rumah,
                    $this->getRegionName($pendaftar->ayah_provinsi),
                    $this->getRegionName($pendaftar->ayah_kabupaten),
                    $this->getRegionName($pendaftar->ayah_kecamatan),
                    $this->getRegionName($pendaftar->ayah_kelurahan),
                    $pendaftar->ayah_rt,
                    $pendaftar->ayah_rw,
                    $pendaftar->alamat_ayah,
                    $pendaftar->ayah_kode_pos,
                ]);
            } else {
                $baseData = array_merge($baseData, array_fill(0, 9, ''));
            }
        } else {
            // Fill empty values if ayah not alive
            $baseData = array_merge($baseData, array_fill(0, 10, ''));
        }

        // ========================================
        // DATA ALAMAT IBU (13+ columns)
        // ========================================
        if ($pendaftar->ibu_status === 'masih_hidup') {
            $baseData = array_merge($baseData, [
                $this->formatBoolean($pendaftar->ibu_sama_dengan_ayah, 'Sama dengan Ayah', 'Berbeda'),
            ]);

            // Detail address (only if not same as ayah)
            if (!$pendaftar->ibu_sama_dengan_ayah) {
                $baseData = array_merge($baseData, [
                    $this->formatBoolean($pendaftar->ibu_tinggal_luar_negeri, 'Ya', 'Tidak'),
                ]);

                // Detail (only if not living abroad)
                if (!$pendaftar->ibu_tinggal_luar_negeri) {
                    $baseData = array_merge($baseData, [
                        $pendaftar->ibu_status_kepemilikan_rumah,
                        $this->getRegionName($pendaftar->ibu_provinsi),
                        $this->getRegionName($pendaftar->ibu_kabupaten),
                        $this->getRegionName($pendaftar->ibu_kecamatan),
                        $this->getRegionName($pendaftar->ibu_kelurahan),
                        $pendaftar->ibu_rt,
                        $pendaftar->ibu_rw,
                        $pendaftar->alamat_ibu,
                        $pendaftar->ibu_kode_pos,
                    ]);
                } else {
                    $baseData = array_merge($baseData, array_fill(0, 9, ''));
                }
            } else {
                $baseData = array_merge($baseData, array_fill(0, 10, ''));
            }
        } else {
            // Fill empty values if ibu not alive
            $baseData = array_merge($baseData, array_fill(0, 11, ''));
        }

        // ========================================
        // DATA ALAMAT WALI (13+ columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->wali_status_wali,
        ]);

        // Detail address (only if wali_status_wali = lainnya)
        if ($pendaftar->wali_status_wali === 'lainnya') {
            $baseData = array_merge($baseData, [
                $this->formatBoolean($pendaftar->wali_sama_dengan_ayah, 'Sama dengan Ayah', 'Berbeda'),
            ]);

            // Detail (only if not same as ayah)
            if (!$pendaftar->wali_sama_dengan_ayah) {
                $baseData = array_merge($baseData, [
                    $this->formatBoolean($pendaftar->wali_tinggal_luar_negeri, 'Ya', 'Tidak'),
                ]);

                // Detail (only if not living abroad)
                if (!$pendaftar->wali_tinggal_luar_negeri) {
                    $baseData = array_merge($baseData, [
                        $pendaftar->wali_status_kepemilikan_rumah,
                        $this->getRegionName($pendaftar->wali_provinsi),
                        $this->getRegionName($pendaftar->wali_kabupaten),
                        $this->getRegionName($pendaftar->wali_kecamatan),
                        $this->getRegionName($pendaftar->wali_kelurahan),
                        $pendaftar->wali_rt,
                        $pendaftar->wali_rw,
                        $pendaftar->alamat_wali,
                        $pendaftar->wali_kode_pos,
                    ]);
                } else {
                    $baseData = array_merge($baseData, array_fill(0, 9, ''));
                }
            } else {
                $baseData = array_merge($baseData, array_fill(0, 10, ''));
            }
        } else {
            // Fill empty values if wali not 'lainnya'
            $baseData = array_merge($baseData, array_fill(0, 11, ''));
        }

        // ========================================
        // DATA ALAMAT SISWA (12+ columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->status_tempat_tinggal,
        ]);

        // Detail address (only if status_tempat_tinggal = lainnya)
        if ($pendaftar->status_tempat_tinggal === 'lainnya') {
            $baseData = array_merge($baseData, [
                $pendaftar->siswa_status_kepemilikan_rumah,
                $this->getRegionName($pendaftar->siswa_provinsi ?? $pendaftar->provinsi),
                $this->getRegionName($pendaftar->siswa_kabupaten ?? $pendaftar->kabupaten),
                $this->getRegionName($pendaftar->siswa_kecamatan ?? $pendaftar->kecamatan),
                $this->getRegionName($pendaftar->siswa_kelurahan ?? $pendaftar->desa),
                $pendaftar->siswa_rt ?? $pendaftar->rt,
                $pendaftar->siswa_rw ?? $pendaftar->rw,
                $pendaftar->siswa_alamat ?? $pendaftar->alamat,
                $pendaftar->siswa_kode_pos ?? $pendaftar->kode_pos,
                // Transport
                $pendaftar->jarak_tempat_tinggal ?? $pendaftar->jarak_ke_sekolah,
                $pendaftar->alat_transportasi ?? $pendaftar->transportasi,
                $pendaftar->waktu_tempuh,
            ]);
        } else {
            $baseData = array_merge($baseData, array_fill(0, 12, ''));
        }

        // ========================================
        // PRESTASI SISWA (7 fields x 3 records = 21 columns)
        // ========================================
        $prestasis = $pendaftar->prestasis->take($this->maxPrestasi);
        for ($i = 0; $i < $this->maxPrestasi; $i++) {
            if (isset($prestasis[$i])) {
                $prestasi = $prestasis[$i];
                $baseData = array_merge($baseData, [
                    $prestasi->tahun,
                    $prestasi->nama_lomba,
                    $prestasi->bidang_lomba,
                    $prestasi->nama_penyelenggara,
                    $prestasi->tingkat_lomba,
                    $prestasi->peringkat,
                    $prestasi->kategori_lomba,
                ]);
            } else {
                // Empty columns if no prestasi
                $baseData = array_merge($baseData, array_fill(0, 7, ''));
            }
        }

        // ========================================
        // BEASISWA & BANTUAN (7 fields x 3 records = 21 columns)
        // ========================================
        $beasiswas = $pendaftar->beasiswas->take($this->maxBeasiswa);
        for ($i = 0; $i < $this->maxBeasiswa; $i++) {
            if (isset($beasiswas[$i])) {
                $beasiswa = $beasiswas[$i];
                $baseData = array_merge($baseData, [
                    $beasiswa->tahun,
                    $beasiswa->kategori,
                    $beasiswa->nama_beasiswa,
                    $beasiswa->jenis_instansi_pemberi,
                    $beasiswa->nama_instansi_pemberi,
                    $beasiswa->jangka_waktu_bulan,
                    $beasiswa->nominal_beasiswa,
                ]);
            } else {
                // Empty columns if no beasiswa
                $baseData = array_merge($baseData, array_fill(0, 7, ''));
            }
        }

        // ========================================
        // PENDIDIKAN LAIN (5 fields x 3 records = 15 columns)
        // ========================================
        $pendidikanLains = $pendaftar->pendidikanLains->take($this->maxPendidikanLain);
        for ($i = 0; $i < $this->maxPendidikanLain; $i++) {
            if (isset($pendidikanLains[$i])) {
                $pendidikan = $pendidikanLains[$i];
                $baseData = array_merge($baseData, [
                    $pendidikan->nama_lembaga,
                    $pendidikan->jenis_lembaga,
                    $pendidikan->mulai_belajar ? Carbon::parse($pendidikan->mulai_belajar)->format('d-m-Y') : '',
                    $pendidikan->frekuensi_belajar,
                    $pendidikan->lokasi_lembaga,
                ]);
            } else {
                // Empty columns if no pendidikan lain
                $baseData = array_merge($baseData, array_fill(0, 5, ''));
            }
        }

        // ========================================
        // UPLOAD BERKAS (16 columns - filename + status for each type)
        // ========================================
        $dokumenTypes = [
            'akta_kelahiran' => 'Akta Kelahiran',
            'raport' => 'Rapor',
            'kartu_keluarga' => 'Kartu Keluarga',
            'nisn' => 'NISN',
            'ijazah' => 'Ijazah',
            'sertifikat' => 'Sertifikat',
            'kip' => 'KIP',
            'foto' => 'Foto',
        ];

        foreach ($dokumenTypes as $jenis => $label) {
            if ($jenis === 'foto') {
                // Foto stored directly in pendaftar table
                $fotoExists = !empty($pendaftar->foto);
                $baseData[] = $pendaftar->foto ?? '';  // Filename
                $baseData[] = $fotoExists ? 'Sudah Upload' : 'Belum Upload';  // Status
            } else {
                // Get document filename if exists
                $dokumen = $pendaftar->dokumen->where('jenis_dokumen', $jenis)->first();
                $filename = $dokumen ? ($dokumen->nama_file ?? '') : '';
                $baseData[] = $filename;  // Filename
                $baseData[] = $filename ? 'Sudah Upload' : 'Belum Upload';  // Status
            }
        }

        // ========================================
        // UPLOAD BERKAS TAMBAHAN (8 columns)
        // ========================================
        // Bukti Daftar Ulang Madrasah
        $buktiDuMadrasah = $pendaftar->daftarUlang->bukti_pembayaran_madrasah ?? '';
        $baseData[] = $buktiDuMadrasah ? basename($buktiDuMadrasah) : '';  // Filename only
        $baseData[] = $buktiDuMadrasah ? 'Sudah Upload' : 'Belum Upload';  // Status

        // Surat Pernyataan DU
        $suratPernyataan = $pendaftar->daftarUlang->surat_pernyataan_path ?? '';
        $baseData[] = $suratPernyataan ? basename($suratPernyataan) : '';  // Filename only
        $baseData[] = $suratPernyataan ? 'Sudah Upload' : 'Belum Upload';  // Status

        // Bukti Daftar Ulang Seragam
        $buktiDuSeragam = $pendaftar->seragam->bukti_pembayaran ?? '';
        $baseData[] = $buktiDuSeragam ? basename($buktiDuSeragam) : '';  // Filename only
        $baseData[] = $buktiDuSeragam ? 'Sudah Upload' : 'Belum Upload';  // Status

        // Bukti Tes Psikologi
        $buktiPsikologi = $pendaftar->tesPsikologiPendaftar->bukti_transfer ?? '';
        $baseData[] = $buktiPsikologi ? basename($buktiPsikologi) : '';  // Filename only
        $baseData[] = $buktiPsikologi ? 'Sudah Upload' : 'Belum Upload';  // Status

        // ========================================
        // NILAI RAPOR (9 columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->nilai_b_indo_5_1,
            $pendaftar->nilai_matematika_5_1,
            $pendaftar->nilai_ipa_5_1,
            $pendaftar->nilai_b_indo_5_2,
            $pendaftar->nilai_matematika_5_2,
            $pendaftar->nilai_ipa_5_2,
            $pendaftar->nilai_b_indo_6_1,
            $pendaftar->nilai_matematika_6_1,
            $pendaftar->nilai_ipa_6_1,
            $pendaftar->nilai_rata_rata,
        ]);

        // ========================================
        // SURVEY ANSWERS (dynamic columns)
        // ========================================
        $surveyAnswers = [];
        foreach ($this->surveyQuestions as $question) {
            // Cari jawaban pendaftar untuk pertanyaan ini
            $answer = $pendaftar->surveyAnswers->firstWhere('survey_question_id', $question->id);

            if ($answer) {
                // Format jawaban dengan helper method (handles checkbox dengan comma-separated)
                $surveyAnswers[] = $answer->getJawabanFormatted();
            } else {
                // Jika tidak ada jawaban untuk pertanyaan ini
                $surveyAnswers[] = '';
            }
        }

        // ========================================
        // STATUS COLUMNS (6 columns)
        // ========================================
        $baseData = array_merge($baseData, [
            // Status Pendaftaran
            $this->getStatusPendaftaranLabel($pendaftar->status_pendaftaran),

            // Status Tes Psikologi
            $this->getStatusTesPsikologiLabel($pendaftar->tesPsikologiPendaftar->status_verifikasi ?? null),

            // Status Kelulusan (from status_pendaftaran)
            $this->getStatusKelulusanLabel($pendaftar->status_pendaftaran),

            // Status Daftar Ulang Madrasah
            $this->getStatusDaftarUlangLabel($pendaftar->daftarUlang->status ?? null),

            // Status Daftar Ulang Seragam
            $this->getStatusSeragamLabel($pendaftar->seragam->status_seragam ?? null),

            // Status Ukuran Seragam
            $this->getStatusUkuranLabel($pendaftar->seragam->status_ukuran ?? null),
        ]);

        // ========================================
        // TANGGAL PENTING (2 columns)
        // ========================================
        $baseData = array_merge($baseData, [
            $pendaftar->tanggal_verifikasi ? Carbon::parse($pendaftar->tanggal_verifikasi)->format('d-m-Y H:i') : '',
            $pendaftar->created_at ? Carbon::parse($pendaftar->created_at)->format('d-m-Y H:i') : '',
        ]);

        // Gabungkan base data dengan survey answers
        return array_merge($baseData, $surveyAnswers);
    }

    public function headings(): array
    {
        $baseHeadings = [];

        // ========================================
        // IDENTITAS PENDAFTAR (4 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nomor Pendaftaran',
            'Jalur Pendaftaran',
            'Status Pendaftaran',
            'Tanggal Daftar',
        ]);

        // ========================================
        // DATA SISWA (30+ columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nama Lengkap',
            'NISN',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kewarganegaraan',
            'NIK (WNI)',
            'Asal Negara (WNA)',
            'Nomor Izin Tinggal (WNA)',
            'Jumlah Saudara',
            'Anak Ke',
            'Hobi',
            'Cita-cita',
            'Nomor HP',
            'Email',
            'Yang Membiayai Sekolah',
            'Pra Sekolah TK',
            'Pra Sekolah PAUD',
            'Imunisasi Hepatitis B',
            'Imunisasi BCG',
            'Imunisasi DPT',
            'Imunisasi Polio',
            'Imunisasi Campak',
            'Imunisasi COVID',
            'Nomor KIP',
            'Nomor KK',
            'Nama Kepala Keluarga',
            'Asal Sekolah',
            'Jenis Sekolah',
            'NSM Sekolah Asal',
            'NPSN Sekolah Asal',
        ]);

        // ========================================
        // DATA AYAH (15 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nama Ayah',
            'Status Ayah',
            'Kewarganegaraan Ayah',
            'NIK Ayah (WNI)',
            'Asal Negara Ayah (WNA)',
            'Nomor Izin Tinggal Ayah (WNA)',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Pendidikan Terakhir Ayah',
            'Pekerjaan Utama Ayah',
            'Penghasilan Ayah',
            'Nomor HP Ayah',
            'Nomor KKS',
            'Nomor PKH',
        ]);

        // ========================================
        // DATA IBU (13 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nama Ibu',
            'Status Ibu',
            'Kewarganegaraan Ibu',
            'NIK Ibu (WNI)',
            'Asal Negara Ibu (WNA)',
            'Nomor Izin Tinggal Ibu (WNA)',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Pendidikan Terakhir Ibu',
            'Pekerjaan Utama Ibu',
            'Penghasilan Ibu',
            'Nomor HP Ibu',
        ]);

        // ========================================
        // DATA WALI (13 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Hubungan Wali',
            'Nama Wali',
            'Status Wali',
            'Kewarganegaraan Wali',
            'NIK Wali (WNI)',
            'Asal Negara Wali (WNA)',
            'Nomor Izin Tinggal Wali (WNA)',
            'Tempat Lahir Wali',
            'Tanggal Lahir Wali',
            'Pekerjaan Utama Wali',
            'Pendidikan Terakhir Wali',
            'Penghasilan Wali',
            'Nomor HP Wali',
        ]);

        // ========================================
        // DATA ALAMAT AYAH (10 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Ayah Tinggal di Luar Negeri',
            'Status Kepemilikan Rumah Ayah',
            'Provinsi Ayah',
            'Kabupaten/Kota Ayah',
            'Kecamatan Ayah',
            'Kelurahan/Desa Ayah',
            'RT Ayah',
            'RW Ayah',
            'Alamat Lengkap Ayah',
            'Kode Pos Ayah',
        ]);

        // ========================================
        // DATA ALAMAT IBU (11 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Alamat Ibu Sama dengan Ayah',
            'Ibu Tinggal di Luar Negeri',
            'Status Kepemilikan Rumah Ibu',
            'Provinsi Ibu',
            'Kabupaten/Kota Ibu',
            'Kecamatan Ibu',
            'Kelurahan/Desa Ibu',
            'RT Ibu',
            'RW Ibu',
            'Alamat Lengkap Ibu',
            'Kode Pos Ibu',
        ]);

        // ========================================
        // DATA ALAMAT WALI (12 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Status Wali',
            'Alamat Wali Sama dengan Ayah',
            'Wali Tinggal di Luar Negeri',
            'Status Kepemilikan Rumah Wali',
            'Provinsi Wali',
            'Kabupaten/Kota Wali',
            'Kecamatan Wali',
            'Kelurahan/Desa Wali',
            'RT Wali',
            'RW Wali',
            'Alamat Lengkap Wali',
            'Kode Pos Wali',
        ]);

        // ========================================
        // DATA ALAMAT SISWA (13 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Status Tempat Tinggal Siswa',
            'Status Kepemilikan Rumah Siswa',
            'Provinsi Siswa',
            'Kabupaten/Kota Siswa',
            'Kecamatan Siswa',
            'Kelurahan/Desa Siswa',
            'RT Siswa',
            'RW Siswa',
            'Alamat Lengkap Siswa',
            'Kode Pos Siswa',
            'Jarak ke Sekolah (km)',
            'Alat Transportasi',
            'Waktu Tempuh (menit)',
        ]);

        // ========================================
        // PRESTASI SISWA (7 fields x 3 = 21 columns)
        // ========================================
        for ($i = 1; $i <= $this->maxPrestasi; $i++) {
            $baseHeadings = array_merge($baseHeadings, [
                "Prestasi $i - Tahun",
                "Prestasi $i - Nama Lomba",
                "Prestasi $i - Bidang",
                "Prestasi $i - Penyelenggara",
                "Prestasi $i - Tingkat",
                "Prestasi $i - Peringkat",
                "Prestasi $i - Kategori",
            ]);
        }

        // ========================================
        // BEASISWA & BANTUAN (7 fields x 3 = 21 columns)
        // ========================================
        for ($i = 1; $i <= $this->maxBeasiswa; $i++) {
            $baseHeadings = array_merge($baseHeadings, [
                "Beasiswa $i - Tahun",
                "Beasiswa $i - Kategori",
                "Beasiswa $i - Nama",
                "Beasiswa $i - Jenis Instansi Pemberi",
                "Beasiswa $i - Nama Instansi Pemberi",
                "Beasiswa $i - Jangka Waktu (bulan)",
                "Beasiswa $i - Nominal",
            ]);
        }

        // ========================================
        // PENDIDIKAN LAIN (5 fields x 3 = 15 columns)
        // ========================================
        for ($i = 1; $i <= $this->maxPendidikanLain; $i++) {
            $baseHeadings = array_merge($baseHeadings, [
                "Pendidikan Lain $i - Nama Lembaga",
                "Pendidikan Lain $i - Jenis",
                "Pendidikan Lain $i - Mulai Belajar",
                "Pendidikan Lain $i - Frekuensi",
                "Pendidikan Lain $i - Lokasi",
            ]);
        }

        // ========================================
        // UPLOAD BERKAS (16 columns - filename + status for each)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nama File Akta Kelahiran',
            'Status File Akta Kelahiran',
            'Nama File Rapor',
            'Status File Rapor',
            'Nama File Kartu Keluarga',
            'Status File Kartu Keluarga',
            'Nama File NISN',
            'Status File NISN',
            'Nama File Ijazah',
            'Status File Ijazah',
            'Nama File Sertifikat',
            'Status File Sertifikat',
            'Nama File KIP',
            'Status File KIP',
            'Nama File Foto',
            'Status File Foto',
        ]);

        // ========================================
        // UPLOAD BERKAS TAMBAHAN (8 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nama File Bukti Daftar Ulang Madrasah',
            'Status File Bukti DU Madrasah',
            'Nama File Surat Pernyataan DU',
            'Status File Surat Pernyataan DU',
            'Nama File Bukti Daftar Ulang Seragam',
            'Status File Bukti DU Seragam',
            'Nama File Bukti Tes Psikologi',
            'Status File Bukti Tes Psikologi',
        ]);

        // ========================================
        // NILAI RAPOR (10 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Nilai B. Indonesia Sem 1 Kelas 5',
            'Nilai Matematika Sem 1 Kelas 5',
            'Nilai IPA Sem 1 Kelas 5',
            'Nilai B. Indonesia Sem 2 Kelas 5',
            'Nilai Matematika Sem 2 Kelas 5',
            'Nilai IPA Sem 2 Kelas 5',
            'Nilai B. Indonesia Sem 1 Kelas 6',
            'Nilai Matematika Sem 1 Kelas 6',
            'Nilai IPA Sem 1 Kelas 6',
            'Nilai Rata-rata',
        ]);

        // ========================================
        // SURVEY ANSWERS (dynamic columns)
        // ========================================
        $surveyHeadings = [];
        foreach ($this->surveyQuestions as $question) {
            $surveyHeadings[] = 'SURVEI: ' . $question->pertanyaan;
        }

        // ========================================
        // STATUS COLUMNS (6 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Status Pendaftaran',
            'Status Tes Psikologi',
            'Status Kelulusan',
            'Status Daftar Ulang Madrasah',
            'Status Daftar Ulang Seragam',
            'Status Ukuran Seragam',
        ]);

        // ========================================
        // TANGGAL PENTING (2 columns)
        // ========================================
        $baseHeadings = array_merge($baseHeadings, [
            'Tanggal Verifikasi',
            'Tanggal Input Data',
        ]);

        return array_merge($baseHeadings, $surveyHeadings);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Pendaftar Lengkap';
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lengkapi_data' => 'Perlu Dilengkapi',
            'perbaiki_data' => 'Perlu Diperbaiki',
            'data_ditolak' => 'Ditolak',
            'data_disetujui' => 'Disetujui',
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak'
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    private function formatBoolean($value, $trueLabel = 'Ya', $falseLabel = 'Tidak')
    {
        if ($value === null) {
            return '';
        }
        return $value ? $trueLabel : $falseLabel;
    }

    /**
     * Get region name from ID
     * FIXED: Changed from code to id (production uses id not code)
     */
    private function getRegionName($id)
    {
        if (empty($id)) {
            return '';
        }

        // Convert to string for cache lookup
        $idStr = (string)$id;

        // Lookup in cache
        if (isset($this->regionCache[$idStr])) {
            return $this->regionCache[$idStr];
        }

        // Return empty if not found (should not happen if cache loaded correctly)
        return '';
    }

    /**
     * Get status pendaftaran label
     */
    private function getStatusPendaftaranLabel($status)
    {
        $statusLabels = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lengkapi_data' => 'Perlu Dilengkapi',
            'perbaiki_data' => 'Perlu Diperbaiki',
            'data_ditolak' => 'Ditolak',
            'data_disetujui' => 'Disetujui',
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status ?? ''));
    }

    /**
     * Get status tes psikologi label
     */
    private function getStatusTesPsikologiLabel($status)
    {
        if (empty($status)) {
            return 'Belum Mendaftar';
        }

        $statusLabels = [
            'belum_upload' => 'Belum Upload Bukti',
            'disetujui' => 'Disetujui',
            'perbaiki' => 'Perlu Perbaikan',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get status kelulusan label
     */
    private function getStatusKelulusanLabel($statusPendaftaran)
    {
        $statusKelulusan = [
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
        ];

        // If status pendaftaran is one of the kelulusan statuses
        if (isset($statusKelulusan[$statusPendaftaran])) {
            return $statusKelulusan[$statusPendaftaran];
        }

        return 'Belum Diumumkan';
    }

    /**
     * Get status daftar ulang label
     */
    private function getStatusDaftarUlangLabel($status)
    {
        if (empty($status)) {
            return 'Belum Daftar Ulang';
        }

        $statusLabels = [
            'selesai' => 'Selesai',
            'belum' => 'Belum Selesai',
            'proses' => 'Dalam Proses',
            'verifikasi' => 'Menunggu Verifikasi',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get status seragam label
     */
    private function getStatusSeragamLabel($status)
    {
        if (empty($status)) {
            return 'Belum Pesan';
        }

        $statusLabels = [
            'belum_pesan' => 'Belum Pesan',
            'sudah_pesan' => 'Sudah Pesan',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            'diterima' => 'Sudah Diterima',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get status ukuran label
     */
    private function getStatusUkuranLabel($status)
    {
        if (empty($status)) {
            return 'Belum Diukur';
        }

        $statusLabels = [
            'belum_diukur' => 'Belum Diukur',
            'sudah_diukur' => 'Sudah Diukur',
            'perlu_ukur_ulang' => 'Perlu Ukur Ulang',
            'selesai' => 'Selesai',
        ];

        return $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
