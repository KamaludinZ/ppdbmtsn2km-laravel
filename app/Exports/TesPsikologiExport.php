<?php

namespace App\Exports;

use App\Models\TesPsikologiPendaftar;
use App\Models\SurveyQuestion;
use App\Models\TesPsikologiSetting;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class TesPsikologiExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $jalurId;
    protected $statusVerifikasi;
    protected $surveyQuestions;
    protected $surveyAktif = false;

    public function __construct($jalurId = null, $statusVerifikasi = null)
    {
        $this->jalurId = $jalurId;
        $this->statusVerifikasi = $statusVerifikasi;

        // Load active survey questions untuk tes psikologi
        $setting = TesPsikologiSetting::first();
        if ($setting && $setting->survey_aktif) {
            $this->surveyAktif = true;
            $this->surveyQuestions = SurveyQuestion::tesPsikologi()->aktif()->ordered()->get();
        } else {
            $this->surveyQuestions = collect();
        }
    }

    public function query()
    {
        $query = TesPsikologiPendaftar::with([
            'pendaftar.jalurPendaftaran',
            'pendaftar.user',
            'pendaftar.surveyAnswers.question.options',
            'opsi',
            'bank',
            'verifiedBy'
        ]);

        // Filter by jalur if specified
        if ($this->jalurId) {
            $query->whereHas('pendaftar', function ($q) {
                $q->where('jalur_pendaftaran_id', $this->jalurId);
            });
        }

        // Filter by status if specified
        if ($this->statusVerifikasi) {
            $query->where('status_verifikasi', $this->statusVerifikasi);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        $baseHeadings = [
            'No. Pendaftaran',
            'Nama Lengkap',
            'NISN',
            'Jalur Pendaftaran',
            'Opsi Tes Psikologi',
            'Bank Transfer',
            'Waktu Transfer',
            'Nama Pengirim',
            'Tanggal Upload Bukti',
            'Status Verifikasi',
            'Catatan Verifikasi',
            'Tanggal Verifikasi',
            'Diverifikasi Oleh',
            'Email Pendaftar',
            'No. HP',
        ];

        // Tambahkan kolom Status Survey jika survey aktif
        if ($this->surveyAktif) {
            $baseHeadings[] = 'Status Survey';

            // Tambahkan kolom untuk setiap pertanyaan survey
            foreach ($this->surveyQuestions as $question) {
                $baseHeadings[] = $question->pertanyaan;
            }
        }

        return $baseHeadings;
    }

    public function map($tesPsikologi): array
    {
        $baseData = [
            $tesPsikologi->pendaftar->nomor_pendaftaran ?? '-',
            $tesPsikologi->pendaftar->nama_lengkap ?? '-',
            $tesPsikologi->pendaftar->nisn ?? '-',
            $tesPsikologi->pendaftar->jalurPendaftaran->nama ?? '-',
            $tesPsikologi->opsi->nama ?? '-',
            $tesPsikologi->bank->nama_bank ?? '-',
            $tesPsikologi->waktu_transfer ? Carbon::parse($tesPsikologi->waktu_transfer)->format('d/m/Y H:i') : '-',
            $tesPsikologi->nama_pengirim ?? '-',
            $tesPsikologi->tanggal_upload ? Carbon::parse($tesPsikologi->tanggal_upload)->format('d/m/Y H:i') : '-',
            $this->formatStatus($tesPsikologi->status_verifikasi),
            $tesPsikologi->catatan_verifikasi ?? '-',
            $tesPsikologi->tanggal_verifikasi ? Carbon::parse($tesPsikologi->tanggal_verifikasi)->format('d/m/Y H:i') : '-',
            $tesPsikologi->verifiedBy->name ?? '-',
            $tesPsikologi->pendaftar->user->email ?? '-',
            $tesPsikologi->pendaftar->no_hp ?? '-',
        ];

        // Tambahkan data survey jika survey aktif
        if ($this->surveyAktif) {
            // Get survey answers untuk pendaftar ini yang context-nya tes_psikologi
            $surveyAnswers = $tesPsikologi->pendaftar->surveyAnswers->filter(function($answer) {
                return $answer->question && $answer->question->context === 'tes_psikologi';
            });

            // Hitung jumlah pertanyaan wajib yang sudah dijawab
            $totalWajib = $this->surveyQuestions->where('wajib_diisi', true)->count();
            $jawabanWajib = $surveyAnswers->filter(function($answer) {
                return $answer->question && $answer->question->wajib_diisi;
            })->count();

            // Status survey: Sudah Mengisi / Belum Lengkap / Belum Mengisi
            if ($surveyAnswers->isEmpty()) {
                $statusSurvey = 'Belum Mengisi';
            } elseif ($totalWajib > 0 && $jawabanWajib < $totalWajib) {
                $statusSurvey = 'Belum Lengkap (' . $jawabanWajib . '/' . $totalWajib . ')';
            } else {
                $statusSurvey = 'Sudah Mengisi';
            }

            $baseData[] = $statusSurvey;

            // Tambahkan jawaban untuk setiap pertanyaan survey
            foreach ($this->surveyQuestions as $question) {
                $answer = $surveyAnswers->firstWhere('survey_question_id', $question->id);

                if ($answer) {
                    // Gunakan getJawabanFormatted() agar ID opsi diubah ke teks label
                    $baseData[] = $answer->getJawabanFormatted();
                } else {
                    $baseData[] = '-';
                }
            }
        }

        return $baseData;
    }

    private function formatStatus($status)
    {
        $statusMap = [
            'belum_upload' => 'Belum Upload',
            'menunggu' => 'Menunggu Verifikasi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'perbaiki' => 'Perlu Perbaikan',
        ];

        return $statusMap[$status] ?? $status;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '9333EA'] // Purple color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Tes Psikologi';
    }
}
