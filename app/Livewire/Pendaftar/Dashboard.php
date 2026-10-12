<?php

namespace App\Livewire\Pendaftar;

use App\Models\Pengumuman as PengumumanModel;
use App\Models\Setting;
use App\Models\TimelinePendaftaran;
use App\Models\TesPsikologiSetting;
use App\Models\TesPsikologiPendaftar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $pendaftar;
    public $jalur;
    public $timeline;
    public $progress = [];
    public $pengumumanPendaftar;
    public $tesPsikologiAktif = false;
    public $tesPsikologiData = null;

    // Link Grup
    public $linkGrupUrl = '';
    public $linkGrupEnabled = false;

    public $documentLabels = [
        'rapor' => ['label' => 'Scan Rapor', 'keterangan' => 'Upload dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'akta_kelahiran' => ['label' => 'Akta Kelahiran', 'keterangan' => 'Scan akta kelahiran dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'kartu_keluarga' => ['label' => 'Kartu Keluarga', 'keterangan' => 'Scan Kartu Keluarga dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'nisn' => ['label' => 'Scan/Bukti NISN', 'keterangan' => 'Kartu NISN atau screenshot cek NISN dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'ijazah' => ['label' => 'Ijazah/SKL/Surat Keterangan Aktif', 'keterangan' => 'Dari sekolah asal (SD/MI) dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'sertifikat' => ['label' => 'Sertifikat/Piagam', 'keterangan' => 'Jika memiliki prestasi, upload dalam format PDF, maksimal 2 MB', 'wajib' => false],
        'kip' => ['label' => 'Kartu Indonesia Pintar (KIP)', 'keterangan' => 'Upload jika memiliki nomor KIP di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kip'],
        'pkh' => ['label' => 'Program Keluarga Harapan (PKH)', 'keterangan' => 'Upload jika memiliki nomor PKH di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_pkh'],
        'kks' => ['label' => 'Kartu Keluarga Sejahtera (KKS)', 'keterangan' => 'Upload jika memiliki nomor KKS di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kks'],
    ];

    // Modal alasan tidak setuju daftar ulang
    public $showModalAlasan = false;
    public $alasanTidakSetuju = '';
    public $alasanLainnya = '';

    public function mount()
    {
        // Get authenticated user's pendaftar data
        $user = Auth::user();

        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;

        if (!$this->pendaftar) {
            return redirect()->to('/daftar');
        }

        // Load relasi dokumen and verifikasi berkas
        $this->pendaftar->load(['dokumen', 'verifikasiBerkas']);

        $this->jalur = $this->pendaftar->jalurPendaftaran;

        $this->timeline = TimelinePendaftaran::aktif()
            ->byJalur($this->jalur->kode)
            ->ordered()
            ->get();

        // Load pengumuman with kategori 'pendaftar'
        $this->pengumumanPendaftar = PengumumanModel::where('kategori', 'pendaftar')
            ->where('aktif', true)
            ->orderByDesc('tanggal_publikasi')
            ->get();

        // Check if tes psikologi is active
        $tesPsikologiSetting = TesPsikologiSetting::getSetting();
        $this->tesPsikologiAktif = $tesPsikologiSetting->aktif;

        // Load tes psikologi data if active
        if ($this->tesPsikologiAktif) {
            $this->tesPsikologiData = TesPsikologiPendaftar::firstOrCreate(
                ['pendaftar_id' => $this->pendaftar->id],
                ['status_verifikasi' => 'belum_upload']
            );
        }

        // Load link grup settings
        $this->linkGrupUrl = Setting::get('link_grup_url', '');
        $this->linkGrupEnabled = Setting::get('link_grup_enabled', false);

        $this->calculateProgress();
    }

    /**
     * Check if formulir is complete (required fields filled)
     * Delegates to Pendaftar model method for accurate validation
     */
    private function isFormulirComplete(): bool
    {
        return $this->pendaftar->isRequiredTabsCompleted();
    }

    /**
     * Check if all required documents are uploaded
     */
    private function isUploadBerkasComplete(): bool
    {
        $requiredDocs = array_filter($this->documentLabels, function($doc) {
            // Check if document is required (wajib = true)
            if (isset($doc['wajib']) && $doc['wajib'] === true) {
                // If conditional field is set, check if that field has value
                if (isset($doc['conditional'])) {
                    $conditionalField = $doc['conditional'];
                    return !empty($this->pendaftar->$conditionalField);
                }
                return true;
            }
            return false;
        });

        // Get uploaded documents
        $uploadedDocs = $this->pendaftar->dokumen->pluck('jenis_dokumen')->toArray();

        foreach (array_keys($requiredDocs) as $docType) {
            if (!in_array($docType, $uploadedDocs)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Map jenis dokumen to verifikasi_berkas status field
     * Same mapping as in Dokumen model
     */
    private function mapDocumentToStatusField($jenisDokumen)
    {
        $mapping = [
            'akta_kelahiran' => 'status_akte',
            'raport' => 'status_rapor',
            'rapor' => 'status_rapor',
            'kartu_keluarga' => 'status_kk',
            'kk' => 'status_kk',
            'nisn' => 'status_nisn',
            'ijazah' => 'status_ijazah',
            'sertifikat' => 'status_sertifikat',
            'kip' => 'status_kip',
            'pkh' => 'status_pkh',
            'kks' => 'status_kks',
        ];

        return $mapping[$jenisDokumen] ?? null;
    }

    /**
     * Check if any required document needs fixing (status: tidak_sesuai or perlu_perbaikan)
     */
    private function hasDocumentNeedsFix(): bool
    {
        $verifikasi = $this->pendaftar->verifikasiBerkas;
        if (!$verifikasi) {
            return false;
        }

        // Get uploaded documents
        $uploadedDocs = $this->pendaftar->dokumen;

        // Check if any uploaded document has "tidak_sesuai" or "perlu_perbaikan" status
        foreach ($uploadedDocs as $dokumen) {
            $statusField = $this->mapDocumentToStatusField($dokumen->jenis_dokumen);

            if ($statusField && isset($verifikasi->$statusField)) {
                $status = $verifikasi->$statusField;

                if (in_array($status, ['tidak_sesuai', 'perlu_perbaikan'])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if all required documents are waiting for verification (menunggu_verifikasi)
     */
    private function areAllDocumentsWaitingVerification(): bool
    {
        $verifikasi = $this->pendaftar->verifikasiBerkas;
        if (!$verifikasi) {
            return false;
        }

        // Get required document types based on documentLabels
        $requiredDocTypes = array_keys(array_filter($this->documentLabels, function($doc) {
            if (isset($doc['wajib']) && $doc['wajib'] === true) {
                if (isset($doc['conditional'])) {
                    $conditionalField = $doc['conditional'];
                    return !empty($this->pendaftar->$conditionalField);
                }
                return true;
            }
            return false;
        }));

        // Map to status fields and check each required document
        foreach ($requiredDocTypes as $docType) {
            $statusField = $this->mapDocumentToStatusField($docType);

            if ($statusField && isset($verifikasi->$statusField)) {
                $status = $verifikasi->$statusField;

                // If status is not menunggu_verifikasi or disetujui, return false
                if (!in_array($status, ['menunggu_verifikasi', 'disetujui'])) {
                    return false;
                }
            } else {
                // Required document field doesn't exist or no mapping
                return false;
            }
        }

        return true;
    }

    /**
     * Check if all uploaded documents (wajib + tidak wajib) are approved
     * Returns true only if ALL uploaded documents have status 'disetujui'
     */
    private function areAllUploadedDocumentsApproved(): bool
    {
        $verifikasi = $this->pendaftar->verifikasiBerkas;
        if (!$verifikasi) {
            return false;
        }

        // Get all uploaded documents (both required and optional)
        $uploadedDocs = $this->pendaftar->dokumen;

        if ($uploadedDocs->isEmpty()) {
            return false;
        }

        // Check each uploaded document
        foreach ($uploadedDocs as $dokumen) {
            $statusField = $this->mapDocumentToStatusField($dokumen->jenis_dokumen);

            if ($statusField && isset($verifikasi->$statusField)) {
                $status = $verifikasi->$statusField;

                // If any document is NOT 'disetujui', return false
                if ($status !== 'disetujui') {
                    return false;
                }
            } else {
                // Document field doesn't exist or no mapping - consider as not approved
                return false;
            }
        }

        return true;
    }

    /**
     * Check if any uploaded document is waiting for verification (menunggu_verifikasi)
     */
    private function hasAnyDocumentWaiting(): bool
    {
        $verifikasi = $this->pendaftar->verifikasiBerkas;
        if (!$verifikasi) {
            return false;
        }

        // Get all uploaded documents
        $uploadedDocs = $this->pendaftar->dokumen;

        // Check if any uploaded document has "menunggu_verifikasi" status
        foreach ($uploadedDocs as $dokumen) {
            $statusField = $this->mapDocumentToStatusField($dokumen->jenis_dokumen);

            if ($statusField && isset($verifikasi->$statusField)) {
                $status = $verifikasi->$statusField;

                if ($status === 'menunggu_verifikasi') {
                    return true;
                }
            }
        }

        return false;
    }

    public function calculateProgress()
    {
        $status = $this->pendaftar->status_pendaftaran;
        $daftarUlang = $this->pendaftar->daftarUlang;

        // Akun step - always completed
        $this->progress = [
            'akun' => [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ]
        ];

        // Check if verification is approved
        $isVerified = in_array($status, ['data_disetujui', 'lulus', 'tidak_lulus', 'cadangan']);
        $isPostTest = in_array($status, ['lulus', 'tidak_lulus', 'cadangan']);
        $needsFix = in_array($status, ['lengkapi_data', 'perbaiki_data']);

        // FORMULIR STEP
        $formulirComplete = $this->isFormulirComplete();
        if ($isVerified) {
            // After verification approved
            $this->progress['formulir'] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ];
        } elseif ($status === 'data_ditolak') {
            // Data ditolak - explicitly show rejected status
            $this->progress['formulir'] = [
                'status' => 'current',
                'label' => 'Ditolak',
                'color' => 'red'
            ];
        } elseif ($needsFix) {
            // Needs to be fixed
            $this->progress['formulir'] = [
                'status' => 'current',
                'label' => 'Perbaiki',
                'color' => 'red'
            ];
        } elseif ($formulirComplete) {
            // Complete and waiting for verification
            $this->progress['formulir'] = [
                'status' => 'current',
                'label' => 'Menunggu',
                'color' => 'yellow'
            ];
        } else {
            // Incomplete
            $this->progress['formulir'] = [
                'status' => 'current',
                'label' => 'Lengkapi',
                'color' => 'yellow'
            ];
        }

        // UPLOAD BERKAS STEP
        $berkasComplete = $this->isUploadBerkasComplete();
        $hasDocumentNeedsFix = $this->hasDocumentNeedsFix();
        $allUploadedDocsApproved = $this->areAllUploadedDocumentsApproved();
        $hasDocumentWaiting = $this->hasAnyDocumentWaiting();

        if ($isVerified) {
            // After verification approved (lulus/tidak_lulus/cadangan)
            $this->progress['upload_berkas'] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ];
        } elseif ($hasDocumentNeedsFix) {
            // Any document has "tidak_sesuai" or "perlu_perbaikan" status
            $this->progress['upload_berkas'] = [
                'status' => 'current',
                'label' => 'Perbaiki',
                'color' => 'red'
            ];
        } elseif ($berkasComplete && $allUploadedDocsApproved) {
            // All required documents uploaded AND all uploaded documents (wajib + tidak wajib) are approved
            $this->progress['upload_berkas'] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ];
        } elseif ($berkasComplete && $hasDocumentWaiting) {
            // All required documents uploaded but at least one is still waiting for verification
            $this->progress['upload_berkas'] = [
                'status' => 'current',
                'label' => 'Menunggu',
                'color' => 'yellow'
            ];
        } else {
            // Incomplete - not all required documents uploaded
            $this->progress['upload_berkas'] = [
                'status' => 'current',
                'label' => 'Lengkapi',
                'color' => 'yellow'
            ];
        }

        // TES PSIKOLOGI STEP (if active)
        $tesPsikologiComplete = false;
        if ($this->tesPsikologiAktif) {
            // CRITICAL FIX: Only consider tes psikologi complete if:
            // 1. Pendaftar sudah lulus/tidak_lulus/cadangan (sudah lewat tahap tes), OR
            // 2. Status pembayaran tes psikologi = 'disetujui'
            // JANGAN langsung selesai hanya karena status = 'data_disetujui'!
            $isPostTest = in_array($status, ['lulus', 'tidak_lulus', 'cadangan']);

            if ($isPostTest) {
                // Sudah lewat tahap tes - pembayaran sudah tidak relevan
                $this->progress['tes_psikologi'] = [
                    'status' => 'completed',
                    'label' => 'Selesai',
                    'color' => 'emerald'
                ];
                $tesPsikologiComplete = true;
            } elseif ($this->tesPsikologiData) {
                if ($this->tesPsikologiData->status_verifikasi === 'disetujui') {
                    // Pembayaran sudah diverifikasi dan disetujui
                    $this->progress['tes_psikologi'] = [
                        'status' => 'completed',
                        'label' => 'Selesai',
                        'color' => 'emerald'
                    ];
                    $tesPsikologiComplete = true;
                } elseif ($this->tesPsikologiData->status_verifikasi === 'menunggu') {
                    // Pembayaran masih menunggu verifikasi
                    $this->progress['tes_psikologi'] = [
                        'status' => 'current',
                        'label' => 'Menunggu',
                        'color' => 'yellow'
                    ];
                    // FIXED: Set true karena sudah "at least menunggu" (bukan lengkapi)
                    // Ini unlock step verifikasi menjadi "Menunggu" (bukan "Terkunci")
                    $tesPsikologiComplete = true;
                } elseif ($this->tesPsikologiData->status_verifikasi === 'ditolak') {
                    // Pembayaran ditolak, perlu upload ulang
                    $this->progress['tes_psikologi'] = [
                        'status' => 'current',
                        'label' => 'Perbaiki',
                        'color' => 'red'
                    ];
                    $tesPsikologiComplete = false;
                } else {
                    // belum_upload - masih perlu upload bukti transfer
                    $this->progress['tes_psikologi'] = [
                        'status' => 'current',
                        'label' => 'Lengkapi',
                        'color' => 'yellow'
                    ];
                    $tesPsikologiComplete = false;
                }
            } else {
                // Belum ada data tes psikologi sama sekali
                $this->progress['tes_psikologi'] = [
                    'status' => 'current',
                    'label' => 'Lengkapi',
                    'color' => 'yellow'
                ];
                $tesPsikologiComplete = false;
            }
        } else {
            // If tes psikologi not active, consider it complete for verification unlock
            $tesPsikologiComplete = true;
        }

        // VERIFIKASI STEP - new logic
        // Check if all previous steps are "Selesai" (completed)
        $allStepsCompleted = ($this->progress['formulir']['label'] === 'Selesai') &&
                            ($this->progress['upload_berkas']['label'] === 'Selesai') &&
                            (!$this->tesPsikologiAktif || $this->progress['tes_psikologi']['label'] === 'Selesai');

        // Check if all previous steps are at least "Menunggu" (waiting or completed)
        $allStepsAtLeastWaiting = ($formulirComplete &&
                                   in_array($this->progress['formulir']['label'], ['Menunggu', 'Selesai'])) &&
                                  ($berkasComplete &&
                                   in_array($this->progress['upload_berkas']['label'], ['Menunggu', 'Selesai'])) &&
                                  ($tesPsikologiComplete &&
                                   (!$this->tesPsikologiAktif || in_array($this->progress['tes_psikologi']['label'], ['Menunggu', 'Selesai'])));

        // CRITICAL FIX: Jangan pakai $isVerified di sini! Pakai $isPostTest!
        // $isVerified = ['data_disetujui', 'lulus', 'tidak_lulus', 'cadangan']
        // $isPostTest = ['lulus', 'tidak_lulus', 'cadangan']
        // Verifikasi hanya selesai jika sudah lewat tahap tes ATAU semua step complete
        if ($isPostTest || $allStepsCompleted) {
            // Verification completed - when all steps are "Selesai" OR test phase done
            $this->progress['verifikasi'] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ];
        } elseif ($allStepsAtLeastWaiting) {
            // Waiting for verification - when all steps are at least "Menunggu"
            $this->progress['verifikasi'] = [
                'status' => 'current',
                'label' => 'Menunggu',
                'color' => 'yellow'
            ];
        } elseif ($needsFix) {
            // Needs to be fixed
            $this->progress['verifikasi'] = [
                'status' => 'current',
                'label' => 'Perbaiki',
                'color' => 'red'
            ];
        } elseif (in_array($status, ['data_ditolak'])) {
            // Rejected
            $this->progress['verifikasi'] = [
                'status' => 'current',
                'label' => 'Ditolak',
                'color' => 'red'
            ];
        } else {
            // Locked until all steps are at least menunggu
            $this->progress['verifikasi'] = [
                'status' => 'pending',
                'label' => 'Terkunci',
                'color' => 'gray'
            ];
        }

        // UJIAN STEP - active when verification is approved
        $this->progress['ujian'] = [
            'status' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'completed' : ($status === 'data_disetujui' ? 'current' : 'pending'),
            'label' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'Selesai' : ($status === 'data_disetujui' ? 'Menunggu' : 'Terkunci'),
            'color' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'emerald' : ($status === 'data_disetujui' ? 'yellow' : 'gray')
        ];

        // PENGUMUMAN STEP
        $this->progress['pengumuman'] = [
            'status' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'completed' : 'pending',
            'label' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'Selesai' : 'Terkunci',
            'color' => in_array($status, ['lulus', 'tidak_lulus', 'cadangan']) ? 'emerald' : 'gray'
        ];

        // DAFTAR ULANG STEP
        $this->progress['daftar_ulang'] = [
            'status' => $this->pendaftar->setuju_daftar_ulang ? 'completed' : ($status === 'lulus' ? 'current' : 'pending'),
            'label' => $this->pendaftar->setuju_daftar_ulang ? 'Selesai' : ($status === 'lulus' ? 'Upload' : 'Terkunci'),
            'color' => $this->pendaftar->setuju_daftar_ulang ? 'emerald' : ($status === 'lulus' ? 'yellow' : 'gray')
        ];

        // UPLOAD DAFTAR ULANG STEP
        $this->progress['upload_daftar_ulang'] = [
            'status' => ($daftarUlang && in_array($daftarUlang->status, ['menunggu', 'disetujui', 'ditolak', 'perbaiki'])) ? 'completed' : (($this->pendaftar->setuju_daftar_ulang) ? 'current' : 'pending'),
            'label' => ($daftarUlang && in_array($daftarUlang->status, ['menunggu', 'disetujui', 'ditolak', 'perbaiki'])) ? 'Selesai' : (($this->pendaftar->setuju_daftar_ulang) ? 'Upload' : 'Terkunci'),
            'color' => ($daftarUlang && in_array($daftarUlang->status, ['menunggu', 'disetujui', 'ditolak', 'perbaiki'])) ? 'emerald' : (($this->pendaftar->setuju_daftar_ulang) ? 'yellow' : 'gray')
        ];

        // VERIFIKASI AKHIR STEP
        // Check status of all three: madrasah, seragam, ukuran
        $seragam = $this->pendaftar->seragam;
        $statusMadrasah = $daftarUlang ? $daftarUlang->status : null;
        $statusSeragam = $seragam ? $seragam->status_seragam : null;
        $statusUkuran = $seragam ? $seragam->status_ukuran : null;

        // Check if any status is 'diperbaiki'
        $adaDiperbaiki = in_array('diperbaiki', [$statusMadrasah, $statusSeragam, $statusUkuran]);

        // Check if all three are 'disetujui'
        $semuaDisetujui = ($statusMadrasah === 'disetujui' && $statusSeragam === 'disetujui' && $statusUkuran === 'disetujui');

        // Determine status, label, and color
        if ($semuaDisetujui) {
            // All three approved
            $this->progress['verifikasi_akhir'] = [
                'status' => 'completed',
                'label' => 'Selesai',
                'color' => 'emerald'
            ];
        } elseif ($adaDiperbaiki) {
            // At least one needs to be fixed
            $this->progress['verifikasi_akhir'] = [
                'status' => 'current',
                'label' => 'Perbaiki',
                'color' => 'red'
            ];
        } elseif ($daftarUlang || $seragam) {
            // Has data but not all approved yet
            $this->progress['verifikasi_akhir'] = [
                'status' => 'current',
                'label' => 'Menunggu',
                'color' => 'yellow'
            ];
        } else {
            // No data yet - locked
            $this->progress['verifikasi_akhir'] = [
                'status' => 'pending',
                'label' => 'Terkunci',
                'color' => 'gray'
            ];
        }
    }

    public function getStatusBadgesProperty()
    {
        $badges = [];
        $status = $this->pendaftar->status_pendaftaran ?? 'menunggu';
        $daftarUlang = $this->pendaftar->daftarUlang;

        // Badge 1: STATUS PENDAFTARAN (always show)
        $statusPendaftaranMap = [
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'color' => 'yellow'],
            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'yellow'],
            'lengkapi_data' => ['label' => 'Lengkapi Data', 'color' => 'orange'],
            'perbaiki_data' => ['label' => 'Perbaiki Data', 'color' => 'red'],
            'data_disetujui' => ['label' => 'Data Disetujui', 'color' => 'green'],
            'data_ditolak' => ['label' => 'Data Ditolak', 'color' => 'red'],
            'draft' => ['label' => 'Draft', 'color' => 'gray'],
        ];

        // Determine display status for registration phase
        $displayStatus = $status;
        if (in_array($status, ['lulus', 'diterima', 'tidak_lulus', 'ditolak', 'cadangan'])) {
            $displayStatus = 'data_disetujui';
        }

        // Default to waiting if status not in map
        $pendaftaranStatus = isset($statusPendaftaranMap[$displayStatus]) ? $displayStatus : 'menunggu';
        $badges[] = array_merge(
            ['type' => 'Status Pendaftaran'],
            $statusPendaftaranMap[$pendaftaranStatus]
        );

        // Badge 2: STATUS KELULUSAN (status tes)
        $statusKelulusanMap = [
            'menunggu' => ['label' => 'Belum Tes', 'color' => 'gray'],
            'lulus' => ['label' => 'LULUS', 'color' => 'green'],
            'diterima' => ['label' => 'LULUS', 'color' => 'green'],
            'tidak_lulus' => ['label' => 'TIDAK LULUS', 'color' => 'red'],
            'ditolak' => ['label' => 'TIDAK LULUS', 'color' => 'red'],
            'cadangan' => ['label' => 'CADANGAN', 'color' => 'orange'],
        ];

        // For kelulusan, check if pendaftar has reached test/announcement phase
        if (in_array($status, ['lulus', 'diterima', 'tidak_lulus', 'ditolak', 'cadangan'])) {
            $kelulusanStatus = $status;
        } else {
            $kelulusanStatus = 'menunggu';
        }

        $badges[] = array_merge(
            ['type' => 'Status Kelulusan'],
            $statusKelulusanMap[$kelulusanStatus]
        );

        // Badge 3: STATUS DAFTAR ULANG
        $statusDaftarUlangMap = [
            'belum_diisi' => ['label' => 'Belum Daftar Ulang', 'color' => 'gray'],
            'belum' => ['label' => 'Belum Daftar Ulang', 'color' => 'gray'],
            'proses' => ['label' => 'Sedang Proses', 'color' => 'yellow'],
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'color' => 'yellow'],
            'perbaiki' => ['label' => 'Perlu Perbaikan', 'color' => 'orange'],
            'disetujui' => ['label' => 'Disetujui', 'color' => 'green'],
            'ditolak' => ['label' => 'Ditolak', 'color' => 'red'],
            'selesai' => ['label' => 'Selesai', 'color' => 'green'],
            'batal' => ['label' => 'Dibatalkan', 'color' => 'gray'],
        ];

        $daftarUlangStatus = $daftarUlang ? $daftarUlang->status : 'belum_diisi';
        // Default to 'belum' if status not in map (defensive programming for backward compatibility)
        if (!isset($statusDaftarUlangMap[$daftarUlangStatus])) {
            $daftarUlangStatus = 'belum';
        }
        $badges[] = array_merge(
            ['type' => 'Status Daftar Ulang'],
            $statusDaftarUlangMap[$daftarUlangStatus]
        );

        return $badges;
    }

    public function setujuDaftarUlang()
    {
        try {
            $this->pendaftar = \App\Support\PersetujuanDaftarUlang::pilihSetuju($this->pendaftar);

            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => 'Terima kasih! Persetujuan daftar ulang Anda telah tercatat. Silakan lengkapi persyaratan daftar ulang segera.'
            ]);
        } catch (\RuntimeException $e) {
            // Pesan aturan (bukan lulus / sudah memilih) aman ditampilkan apa adanya
            $this->pendaftar = $this->pendaftar->fresh();
            $this->dispatch('statusUpdated', ['type' => 'info', 'message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            \Log::error('Gagal menyimpan persetujuan daftar ulang', ['pendaftar_id' => $this->pendaftar->id, 'error' => $e->getMessage()]);
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Pilihan belum tersimpan karena gangguan sistem. Silakan coba lagi beberapa saat lagi.'
            ]);
        }
    }

    public function showModalTidakSetuju()
    {
        $this->pendaftar = $this->pendaftar->fresh();

        if (!\App\Support\PersetujuanDaftarUlang::wajibMemilih($this->pendaftar)) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Hanya pendaftar dengan status LULUS yang dapat membuat pilihan daftar ulang'
            ]);
            return;
        }

        if (\App\Support\PersetujuanDaftarUlang::status($this->pendaftar) !== 'belum_memilih') {
            $this->dispatch('statusUpdated', [
                'type' => 'info',
                'message' => 'Anda sudah membuat pilihan untuk daftar ulang sebelumnya'
            ]);
            return;
        }

        $this->resetErrorBag();
        $this->showModalAlasan = true;
    }

    public function closeModalAlasan()
    {
        $this->showModalAlasan = false;
        $this->alasanTidakSetuju = '';
        $this->alasanLainnya = '';
    }

    public function tidakSetujuDaftarUlang()
    {
        $this->validate([
            'alasanTidakSetuju' => 'required|string|max:200',
            'alasanLainnya' => 'required_if:alasanTidakSetuju,lainnya|nullable|string|max:' . \App\Support\PersetujuanDaftarUlang::ALASAN_MAKS,
        ], [
            'alasanTidakSetuju.required' => 'Silakan pilih alasan mengapa Anda tidak melanjutkan daftar ulang.',
            'alasanLainnya.required_if' => 'Silakan isi alasan lainnya.',
            'alasanLainnya.max' => 'Alasan maksimal ' . \App\Support\PersetujuanDaftarUlang::ALASAN_MAKS . ' karakter.',
        ]);

        $alasanFinal = $this->alasanTidakSetuju === 'lainnya' ? $this->alasanLainnya : $this->alasanTidakSetuju;

        try {
            // Tidak lagi membuat record daftar_ulang berstatus "selesai" (ditolak database dan
            // membuat pilihan tersimpan setengah). Keputusan & alasan cukup dicatat di data pendaftar.
            $this->pendaftar = \App\Support\PersetujuanDaftarUlang::pilihTidakSetuju($this->pendaftar, $alasanFinal);
            $this->closeModalAlasan();

            $this->dispatch('statusUpdated', [
                'type' => 'info',
                'message' => 'Pilihan Anda telah tercatat. Terima kasih atas partisipasi Anda dalam proses seleksi ini.'
            ]);
        } catch (\RuntimeException $e) {
            $this->pendaftar = $this->pendaftar->fresh();
            $this->closeModalAlasan();
            $this->dispatch('statusUpdated', ['type' => 'info', 'message' => $e->getMessage()]);
        } catch (\Throwable $e) {
            \Log::error('Gagal menyimpan pilihan tidak melanjutkan daftar ulang', ['pendaftar_id' => $this->pendaftar->id, 'error' => $e->getMessage()]);
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Pilihan belum tersimpan karena gangguan sistem. Silakan coba lagi beberapa saat lagi.'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pendaftar.dashboard', [
            'pengumumanPendaftar' => $this->pengumumanPendaftar
        ])
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Dashboard Pendaftar',
                'pageDescription' => 'Selamat datang di dashboard pendaftaran Anda'
            ]);
    }
}
