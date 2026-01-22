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

    public function __construct($jalurId = null, $statusPendaftaran = null, $search = null)
    {
        $this->jalurId = $jalurId;
        $this->statusPendaftaran = $statusPendaftaran;
        $this->search = $search;

        // Load active survey questions untuk kolom dinamis
        $this->surveyQuestions = SurveyQuestion::active()->ordered()->get();
    }

    public function query()
    {
        $query = Pendaftar::with([
            'jalurPendaftaran',
            'user',
            'dokumen',
            'prestasi',
            'beasiswa',
            'pendidikanLains',
            'surveyAnswers.question',
            'surveyAnswers.option'
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
        $baseData = [
            // Identitas Dasar
            $pendaftar->nomor_pendaftaran,
            $pendaftar->nisn,
            $pendaftar->nik,
            $pendaftar->no_akta_kelahiran,
            $pendaftar->nama_lengkap,
            $pendaftar->nama_panggilan,
            $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $pendaftar->tempat_lahir,
            $pendaftar->tanggal_lahir ? Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y') : '',
            $pendaftar->agama,
            $pendaftar->kewarganegaraan,
            $pendaftar->negara_asal,

            // Data Keluarga
            $pendaftar->nomor_kk,
            $pendaftar->anak_ke,
            $pendaftar->jumlah_saudara_kandung,
            $pendaftar->jumlah_saudara_tiri,
            $pendaftar->yatim_piatu,

            // Data Fisik & Kontak
            $pendaftar->golongan_darah,
            $pendaftar->tinggi_badan,
            $pendaftar->berat_badan,
            $pendaftar->bahasa_sehari_hari,
            $pendaftar->nomor_telepon_rumah,
            $pendaftar->nomor_hp ?? $pendaftar->no_hp,
            $pendaftar->email_pribadi ?? $pendaftar->user->email ?? '',

            // Alamat Siswa
            $pendaftar->alamat,
            $pendaftar->provinsi,
            $pendaftar->kabupaten,
            $pendaftar->kecamatan,
            $pendaftar->desa,
            $pendaftar->kode_pos,
            $pendaftar->jenis_tinggal,

            // Transportasi
            $pendaftar->alat_transportasi ?? $pendaftar->transportasi,
            $pendaftar->jarak_tempat_tinggal ?? $pendaftar->jarak_ke_sekolah,
            $pendaftar->waktu_tempuh,

            // Data Orang Tua - Ayah
            $pendaftar->nama_ayah,
            $pendaftar->nik_ayah,
            $pendaftar->tempat_lahir_ayah,
            $pendaftar->tanggal_lahir_ayah ? Carbon::parse($pendaftar->tanggal_lahir_ayah)->format('d-m-Y') : '',
            $pendaftar->pendidikan_ayah,
            $pendaftar->pekerjaan_ayah,
            $pendaftar->penghasilan_ayah,
            $pendaftar->no_hp_ayah,
            $pendaftar->alamat_ayah,

            // Data Orang Tua - Ibu
            $pendaftar->nama_ibu,
            $pendaftar->nik_ibu,
            $pendaftar->tempat_lahir_ibu,
            $pendaftar->tanggal_lahir_ibu ? Carbon::parse($pendaftar->tanggal_lahir_ibu)->format('d-m-Y') : '',
            $pendaftar->pendidikan_ibu,
            $pendaftar->pekerjaan_ibu,
            $pendaftar->penghasilan_ibu,
            $pendaftar->no_hp_ibu,
            $pendaftar->alamat_ibu,

            // Data Wali (jika ada)
            $pendaftar->nama_wali,
            $pendaftar->nik_wali,
            $pendaftar->tempat_lahir_wali,
            $pendaftar->tanggal_lahir_wali ? Carbon::parse($pendaftar->tanggal_lahir_wali)->format('d-m-Y') : '',
            $pendaftar->pendidikan_wali,
            $pendaftar->pekerjaan_wali,
            $pendaftar->penghasilan_wali,
            $pendaftar->no_hp_wali,
            $pendaftar->alamat_wali,

            // Data Sekolah Asal
            $pendaftar->asal_sekolah,
            $pendaftar->jenis_sekolah_asal,
            $pendaftar->nsm_sekolah_asal,
            $pendaftar->npsn_sekolah_asal,
            $pendaftar->alamat_sekolah,
            $pendaftar->asal_kabkota,

            // Nilai Rapor Semester 1 Kelas 5
            $pendaftar->nilai_b_indo_5_1,
            $pendaftar->nilai_matematika_5_1,
            $pendaftar->nilai_ipa_5_1,

            // Nilai Rapor Semester 2 Kelas 5
            $pendaftar->nilai_b_indo_5_2,
            $pendaftar->nilai_matematika_5_2,
            $pendaftar->nilai_ipa_5_2,

            // Nilai Rapor Semester 1 Kelas 6
            $pendaftar->nilai_b_indo_6_1,
            $pendaftar->nilai_matematika_6_1,
            $pendaftar->nilai_ipa_6_1,

            // Nilai Rata-rata
            $pendaftar->nilai_rata_rata,

            // Beasiswa & KPS
            $pendaftar->penerima_kps,
            $pendaftar->nomor_kps,

            // Prestasi (ambil prestasi pertama jika ada)
            $pendaftar->prestasi->first()->nama_prestasi ?? '',
            $pendaftar->prestasi->first()->tingkat ?? '',
            $pendaftar->prestasi->first()->peringkat ?? '',

            // Jalur & Status
            $pendaftar->jalurPendaftaran->nama ?? '',
            $this->getStatusLabel($pendaftar->status_pendaftaran),

            // Tanggal Penting
            $pendaftar->tanggal_daftar ? Carbon::parse($pendaftar->tanggal_daftar)->format('d-m-Y H:i') : '',
            $pendaftar->tanggal_verifikasi ? Carbon::parse($pendaftar->tanggal_verifikasi)->format('d-m-Y H:i') : '',
            $pendaftar->created_at ? Carbon::parse($pendaftar->created_at)->format('d-m-Y H:i') : '',
        ];

        // Survey Answers (dinamis sesuai pertanyaan aktif)
        $surveyAnswers = [];
        foreach ($this->surveyQuestions as $question) {
            // Cari jawaban pendaftar untuk pertanyaan ini
            $answer = $pendaftar->surveyAnswers->firstWhere('survey_question_id', $question->id);

            if ($answer) {
                // Jika pertanyaan multiple choice, ambil dari option
                if ($answer->option) {
                    $surveyAnswers[] = $answer->option->option_text;
                } else {
                    // Jika pertanyaan text/essay, ambil dari answer_text
                    $surveyAnswers[] = $answer->answer_text ?? '';
                }
            } else {
                // Jika tidak ada jawaban untuk pertanyaan ini
                $surveyAnswers[] = '';
            }
        }

        // Gabungkan base data dengan survey answers
        return array_merge($baseData, $surveyAnswers);
    }

    public function headings(): array
    {
        $baseHeadings = [
            // Identitas Dasar
            'No. Pendaftaran',
            'NISN',
            'NIK',
            'No. Akta Kelahiran',
            'Nama Lengkap',
            'Nama Panggilan',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Kewarganegaraan',
            'Negara Asal',

            // Data Keluarga
            'Nomor KK',
            'Anak Ke',
            'Jumlah Saudara Kandung',
            'Jumlah Saudara Tiri',
            'Status Yatim/Piatu',

            // Data Fisik & Kontak
            'Golongan Darah',
            'Tinggi Badan (cm)',
            'Berat Badan (kg)',
            'Bahasa Sehari-hari',
            'Telepon Rumah',
            'No. HP',
            'Email',

            // Alamat Siswa
            'Alamat Lengkap',
            'Provinsi',
            'Kabupaten/Kota',
            'Kecamatan',
            'Desa/Kelurahan',
            'Kode Pos',
            'Jenis Tinggal',

            // Transportasi
            'Alat Transportasi',
            'Jarak ke Sekolah (km)',
            'Waktu Tempuh (menit)',

            // Data Ayah
            'Nama Ayah',
            'NIK Ayah',
            'Tempat Lahir Ayah',
            'Tanggal Lahir Ayah',
            'Pendidikan Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'No. HP Ayah',
            'Alamat Ayah',

            // Data Ibu
            'Nama Ibu',
            'NIK Ibu',
            'Tempat Lahir Ibu',
            'Tanggal Lahir Ibu',
            'Pendidikan Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
            'No. HP Ibu',
            'Alamat Ibu',

            // Data Wali
            'Nama Wali',
            'NIK Wali',
            'Tempat Lahir Wali',
            'Tanggal Lahir Wali',
            'Pendidikan Wali',
            'Pekerjaan Wali',
            'Penghasilan Wali',
            'No. HP Wali',
            'Alamat Wali',

            // Data Sekolah Asal
            'Asal Sekolah',
            'Jenis Sekolah',
            'NSM',
            'NPSN',
            'Alamat Sekolah Asal',
            'Kabupaten/Kota Sekolah Asal',

            // Nilai Rapor
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

            // Beasiswa
            'Penerima KPS',
            'Nomor KPS',

            // Prestasi
            'Nama Prestasi',
            'Tingkat Prestasi',
            'Peringkat Prestasi',

            // Status
            'Jalur Pendaftaran',
            'Status Pendaftaran',

            // Tanggal
            'Tanggal Daftar',
            'Tanggal Verifikasi',
            'Tanggal Input Data',
        ];

        // Tambahkan kolom survey secara dinamis
        $surveyHeadings = [];
        foreach ($this->surveyQuestions as $question) {
            $surveyHeadings[] = 'SURVEI: ' . $question->question_text;
        }

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
}
