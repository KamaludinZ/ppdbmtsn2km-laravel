<?php

namespace App\Livewire\Admin;

use App\Models\DaftarUlang as DaftarUlangModel;
use App\Models\Pendaftar;
use App\Models\Pembayaran;
use App\Models\Seragam;
use App\Models\Dokumen;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class DaftarUlang extends Component
{
    use WithPagination, WithSorting, WithFileUploads;

    public $activeTab = 'madrasah';
    public $search = '';
    public $filterStatus = '';

    // For verification
    public $verificationStatus = '';
    public $verificationNote = '';

    // Properties for send message modal
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Temporary variables for verification
    public $currentPendaftarId = null;
    public $currentDocumentType = null;
    public $currentPaymentType = null;

    // Settings variables
    public $madrasahContent = '';
    public $seragamContent = '';
    public $ukuranContent = '';
    public $madrasahInfo = '';  // Informasi untuk pendaftar - Madrasah
    public $seragamInfo = '';   // Informasi untuk pendaftar - Seragam
    public $ukuranInfo = '';    // Informasi untuk pendaftar - Ukuran
    public $paymentInfoText = ''; // Informasi Pembayaran untuk semua halaman daftar ulang
    public $showPernyataan = true;
    public $showPembayaranMadrasah = true;
    public $showPembayaranSeragam = true;
    public $showUkuranAtasan = true;
    public $showUkuranBawahan = true;
    public $showAtribut = true;

    // Madrasah Settings
    public $madrasahTemplateSuratPernyataan = null;
    public $existingMadrasahTemplate = null;
    public $madrasahNominals = [];
    public $madrasahPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
    public $madrasahBanks = [];
    public $madrasahPaymentStart = null;
    public $madrasahPaymentEnd = null;
    public $madrasahRequirePaymentProof = false;

    // Seragam Settings
    public $seragamPackages = [];
    public $seragamNominals = [];
    public $seragamPaymentOptions = ['lunas' => false, 'angsuran' => false, 'potongan' => false];
    public $seragamBanks = [];
    public $seragamPaymentStart = null;
    public $seragamPaymentEnd = null;
    public $seragamRequirePaymentProof = false;

    // Ukuran Settings
    public $ukuranByGender = false;
    public $ukuranJasImage = null;
    public $existingUkuranJasImage = null;
    public $ukuranJasOptions = [];
    public $ukuranBajuOlahragaImage = null;
    public $existingUkuranBajuOlahragaImage = null;
    public $ukuranBajuOlahragaOptions = [];
    public $ukuranCelanaOlahragaImage = null;
    public $existingUkuranCelanaOlahragaImage = null;
    public $ukuranCelanaOlahragaOptions = [];
    public $ukuranBajuHarianImage = null;
    public $existingUkuranBajuHarianImage = null;
    public $ukuranBajuHarianOptions = [];
    public $ukuranCelanaRokHarianImage = null;
    public $existingUkuranCelanaRokHarianImage = null;
    public $ukuranCelanaRokHarianOptions = [];
    public $ukuranKopyahImage = null;
    public $existingUkuranKopyahImage = null;
    public $ukuranKopyahOptions = [];
    // Legacy properties for backward compatibility
    public $ukuranAtasanImage = null;
    public $existingUkuranAtasanImage = null;
    public $ukuranAtasanOptions = [];
    public $ukuranBawahanImage = null;
    public $existingUkuranBawahanImage = null;
    public $ukuranBawahanOptions = [];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        // Text & Boolean Settings
        $this->madrasahContent = \App\Models\Setting::get('daftar_ulang_madrasah_content', '');
        $this->seragamContent = \App\Models\Setting::get('daftar_ulang_seragam_content', '');
        $this->ukuranContent = \App\Models\Setting::get('daftar_ulang_ukuran_content', '');
        $this->madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
        $this->seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
        $this->ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
        $this->paymentInfoText = \App\Models\Setting::get('daftar_ulang_payment_info_text', 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.');

        $this->showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
        $this->showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
        $this->showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
        $this->showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
        $this->showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
        $this->showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);

        // Madrasah JSON/Array Settings
        $this->existingMadrasahTemplate = \App\Models\Setting::get('daftar_ulang_madrasah_template');
        $this->madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []);
        $this->madrasahPaymentOptions = \App\Models\Setting::get('daftar_ulang_madrasah_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $this->madrasahBanks = \App\Models\Setting::get('daftar_ulang_madrasah_banks', []);
        $this->madrasahPaymentStart = \App\Models\Setting::get('daftar_ulang_madrasah_payment_start');
        $this->madrasahPaymentEnd = \App\Models\Setting::get('daftar_ulang_madrasah_payment_end');
        $this->madrasahRequirePaymentProof = \App\Models\Setting::get('daftar_ulang_madrasah_require_payment_proof', false);

        // Seragam JSON/Array Settings
        $this->seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
        $this->seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
        $this->seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
        $this->seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
        $this->seragamPaymentStart = \App\Models\Setting::get('daftar_ulang_seragam_payment_start');
        $this->seragamPaymentEnd = \App\Models\Setting::get('daftar_ulang_seragam_payment_end');
        $this->seragamRequirePaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);

        // Ukuran JSON/Array Settings
        $this->ukuranByGender = \App\Models\Setting::get('ukuran_by_gender', false);
        $this->existingUkuranJasImage = \App\Models\Setting::get('daftar_ulang_ukuran_jas_image');
        $this->existingUkuranBajuOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_image');
        $this->existingUkuranCelanaOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_image');
        $this->existingUkuranBajuHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_image');
        $this->existingUkuranCelanaRokHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_image');
        $this->existingUkuranKopyahImage = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_image');
        // Legacy compatibility
        $this->existingUkuranAtasanImage = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_image');
        $this->existingUkuranBawahanImage = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_image');

        $this->ukuranJasOptions = \App\Models\Setting::get('daftar_ulang_ukuran_jas_options', []);
        $this->ukuranBajuOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_options', []);
        $this->ukuranCelanaOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_options', []);
        $this->ukuranBajuHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_options', []);
        $this->ukuranCelanaRokHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_options', []);
        $this->ukuranKopyahOptions = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_options', []);
        // Legacy compatibility
        $this->ukuranAtasanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_options', []);
        $this->ukuranBawahanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_options', []);
    }

    public function render()
    {
        $query = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam', 'dokumen'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima']); // Hanya yang data disetujui (lulus)

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus) {
            $query->whereHas('daftarUlang', function($q) {
                $q->where('status', $this->filterStatus);
            });
        }

        $pendaftar = $this->applySorting($query)->paginate(10);

        // Calculate stats based on active tab
        $allPendaftar = Pendaftar::with(['daftarUlang', 'pembayaran', 'seragam'])
            ->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima'])
            ->get();

        $stats = [];
        if ($this->activeTab === 'madrasah') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->daftarUlang && $p->daftarUlang->status === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->daftarUlang || $p->daftarUlang->status === 'menunggu';
                })->count(),
            ];
        } elseif ($this->activeTab === 'seragam') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_seragam === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_seragam === 'menunggu' || !$p->seragam->status_seragam;
                })->count(),
            ];
        } elseif ($this->activeTab === 'ukuran') {
            $stats = [
                'total' => $allPendaftar->count(),
                'disetujui' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'disetujui';
                })->count(),
                'ditolak' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'ditolak';
                })->count(),
                'perbaiki' => $allPendaftar->filter(function($p) {
                    return $p->seragam && $p->seragam->status_ukuran === 'perbaiki';
                })->count(),
                'menunggu' => $allPendaftar->filter(function($p) {
                    return !$p->seragam || $p->seragam->status_ukuran === 'menunggu' || !$p->seragam->status_ukuran;
                })->count(),
            ];
        }

        return view('livewire.admin.daftar-ulang', [
            'pendaftar' => $pendaftar,
            'stats' => $stats
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Manajemen Daftar Ulang',
            'pageDescription' => 'Kelola proses daftar ulang pendaftar'
        ]);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function prepareVerification($pendaftarId, $documentType = null, $paymentType = null)
    {
        $this->currentPendaftarId = $pendaftarId;
        $this->currentDocumentType = $documentType;
        $this->currentPaymentType = $paymentType;
        $this->verificationStatus = $documentType ? 'diterima' : 'lunas'; // Default status
        $this->verificationNote = '';
    }

    public function confirmVerification()
    {
        if (!$this->currentPendaftarId) {
            return;
        }

        if ($this->currentDocumentType) {
            // Verify document
            $this->verifyDocument($this->currentPendaftarId, $this->currentDocumentType, $this->verificationStatus, $this->verificationNote);
        } elseif ($this->currentPaymentType) {
            // Verify payment based on type
            if ($this->currentPaymentType === 'daftar_ulang') {
                $this->verifyDaftarUlang($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            } elseif ($this->currentPaymentType === 'seragam') {
                $this->verifySeragam($this->currentPendaftarId, $this->verificationStatus, $this->verificationNote);
            }
        }

        // Reset the verification form
        $this->reset(['currentPendaftarId', 'currentDocumentType', 'currentPaymentType', 'verificationStatus', 'verificationNote']);
    }

    public function saveSettings()
    {
        $this->validate([
            'madrasahContent' => 'nullable|string',
            'seragamContent' => 'nullable|string',
            'ukuranContent' => 'nullable|string',
            'paymentInfoText' => 'nullable|string',
        ]);

        \App\Models\Setting::set('daftar_ulang_madrasah_content', $this->madrasahContent, 'text', 'Konten Daftar Ulang Madrasah');
        \App\Models\Setting::set('daftar_ulang_seragam_content', $this->seragamContent, 'text', 'Konten Daftar Ulang Seragam');
        \App\Models\Setting::set('daftar_ulang_ukuran_content', $this->ukuranContent, 'text', 'Konten Ukuran Seragam & Atribut');
        \App\Models\Setting::set('daftar_ulang_payment_info_text', $this->paymentInfoText, 'text', 'Informasi Pembayaran Daftar Ulang');
        \App\Models\Setting::set('daftar_ulang_show_pernyataan', $this->showPernyataan, 'boolean', 'Tampilkan Form Pernyataan');
        \App\Models\Setting::set('daftar_ulang_show_pembayaran_madrasah', $this->showPembayaranMadrasah, 'boolean', 'Tampilkan Form Pembayaran Madrasah');
        \App\Models\Setting::set('daftar_ulang_show_pembayaran_seragam', $this->showPembayaranSeragam, 'boolean', 'Tampilkan Form Pembayaran Seragam');
        \App\Models\Setting::set('daftar_ulang_show_ukuran_atasan', $this->showUkuranAtasan, 'boolean', 'Tampilkan Form Ukuran Atasan');
        \App\Models\Setting::set('daftar_ulang_show_ukuran_bawahan', $this->showUkuranBawahan, 'boolean', 'Tampilkan Form Ukuran Bawahan');
        \App\Models\Setting::set('daftar_ulang_show_atribut', $this->showAtribut, 'boolean', 'Tampilkan Form Atribut');

        $this->dispatch('saved', message: 'Pengaturan daftar ulang berhasil disimpan!');
    }

    public function verifyDocument($pendaftarId, $documentType, $status, $note = null)
    {
        $dokumen = Dokumen::where('pendaftar_id', $pendaftarId)
                         ->where('jenis_dokumen', $documentType)
                         ->first();

        if ($dokumen) {
            $dokumen->update([
                'status_verifikasi' => $status,
                'catatan' => $note
            ]);

            // Also update the corresponding status in verifikasi_berkas
            $verifikasiBerkas = \App\Models\VerifikasiBerkas::firstOrCreate(
                ['pendaftar_id' => $pendaftarId],
                ['status' => 'menunggu']
            );

            $statusField = 'status_' . $documentType;
            $catatanField = 'catatan_' . $documentType;

            $verifikasiBerkas->update([
                $statusField => $status,
                $catatanField => $note
            ]);

            $this->dispatch('saved', message: 'Status verifikasi dokumen berhasil diperbarui.');
        }
    }

    public function verifyDaftarUlang($pendaftarId, $status, $note = null)
    {
        $daftarUlang = DaftarUlangModel::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            [
                'status' => $status,
                'catatan' => $note
            ]
        );

        $daftarUlang->update([
            'status' => $status,
            'catatan' => $note
        ]);

        $this->dispatch('saved', message: 'Status daftar ulang berhasil diperbarui.');
    }

    public function verifySeragam($pendaftarId, $status, $note = null)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            [
                'sudah_dipesan' => $status === 'diterima',
                'catatan' => $note
            ]
        );

        $seragam->update([
            'sudah_dipesan' => $status === 'diterima',
            'catatan' => $note
        ]);

        $this->dispatch('saved', message: 'Status seragam berhasil diperbarui.');
    }

    // ========== MADRASAH SETTINGS METHODS ==========
    public function addMadrasahNominal()
    {
        $this->madrasahNominals[] = ['name' => '', 'amount' => ''];
    }

    public function removeMadrasahNominal($index)
    {
        unset($this->madrasahNominals[$index]);
        $this->madrasahNominals = array_values($this->madrasahNominals);
    }

    public function addMadrasahBank()
    {
        $this->madrasahBanks[] = ['name' => ''];
    }

    public function removeMadrasahBank($index)
    {
        unset($this->madrasahBanks[$index]);
        $this->madrasahBanks = array_values($this->madrasahBanks);
    }

    public function saveMadrasahSettings()
    {
        // Handle Template Upload
        if ($this->madrasahTemplateSuratPernyataan) {
            $path = $this->madrasahTemplateSuratPernyataan->store('templates', 'public');
            \App\Models\Setting::set('daftar_ulang_madrasah_template', $path);
            $this->existingMadrasahTemplate = $path;
            $this->madrasahTemplateSuratPernyataan = null;
        }

        // Save settings
        \App\Models\Setting::set('daftar_ulang_madrasah_info', $this->madrasahInfo);
        \App\Models\Setting::set('daftar_ulang_madrasah_nominals', $this->madrasahNominals, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_options', $this->madrasahPaymentOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_banks', $this->madrasahBanks, 'json');
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_start', $this->madrasahPaymentStart);
        \App\Models\Setting::set('daftar_ulang_madrasah_payment_end', $this->madrasahPaymentEnd);
        \App\Models\Setting::set('daftar_ulang_madrasah_require_payment_proof', $this->madrasahRequirePaymentProof, 'boolean');

        $this->dispatch('saved', message: 'Pengaturan daftar ulang madrasah berhasil disimpan.');
    }

    // ========== SERAGAM SETTINGS METHODS ==========
    public function addSeragamPackage()
    {
        $this->seragamPackages[] = ['name' => '', 'description' => ''];
    }

    public function removeSeragamPackage($index)
    {
        unset($this->seragamPackages[$index]);
        $this->seragamPackages = array_values($this->seragamPackages);
    }

    public function addSeragamNominal()
    {
        $this->seragamNominals[] = ['name' => '', 'amount' => ''];
    }

    public function removeSeragamNominal($index)
    {
        unset($this->seragamNominals[$index]);
        $this->seragamNominals = array_values($this->seragamNominals);
    }

    public function addSeragamBank()
    {
        $this->seragamBanks[] = ['name' => ''];
    }

    public function removeSeragamBank($index)
    {
        unset($this->seragamBanks[$index]);
        $this->seragamBanks = array_values($this->seragamBanks);
    }

    public function saveSeragamSettings()
    {
        // Save settings
        \App\Models\Setting::set('daftar_ulang_seragam_info', $this->seragamInfo);
        \App\Models\Setting::set('daftar_ulang_seragam_packages', $this->seragamPackages, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_nominals', $this->seragamNominals, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_payment_options', $this->seragamPaymentOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_banks', $this->seragamBanks, 'json');
        \App\Models\Setting::set('daftar_ulang_seragam_payment_start', $this->seragamPaymentStart);
        \App\Models\Setting::set('daftar_ulang_seragam_payment_end', $this->seragamPaymentEnd);
        \App\Models\Setting::set('daftar_ulang_seragam_require_payment_proof', $this->seragamRequirePaymentProof, 'boolean');

        $this->dispatch('saved', message: 'Pengaturan daftar ulang seragam berhasil disimpan.');
    }

    // ========== UKURAN SETTINGS METHODS ==========
    public function addUkuranJas()
    {
        $this->ukuranJasOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranJas($index)
    {
        unset($this->ukuranJasOptions[$index]);
        $this->ukuranJasOptions = array_values($this->ukuranJasOptions);
    }

    public function addUkuranAtasan()
    {
        $this->ukuranAtasanOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranAtasan($index)
    {
        unset($this->ukuranAtasanOptions[$index]);
        $this->ukuranAtasanOptions = array_values($this->ukuranAtasanOptions);
    }

    public function addUkuranBawahan()
    {
        $this->ukuranBawahanOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBawahan($index)
    {
        unset($this->ukuranBawahanOptions[$index]);
        $this->ukuranBawahanOptions = array_values($this->ukuranBawahanOptions);
    }

    public function addUkuranKopyah()
    {
        $this->ukuranKopyahOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranKopyah($index)
    {
        unset($this->ukuranKopyahOptions[$index]);
        $this->ukuranKopyahOptions = array_values($this->ukuranKopyahOptions);
    }

    public function addUkuranBajuOlahraga()
    {
        $this->ukuranBajuOlahragaOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBajuOlahraga($index)
    {
        unset($this->ukuranBajuOlahragaOptions[$index]);
        $this->ukuranBajuOlahragaOptions = array_values($this->ukuranBajuOlahragaOptions);
    }

    public function addUkuranCelanaOlahraga()
    {
        $this->ukuranCelanaOlahragaOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranCelanaOlahraga($index)
    {
        unset($this->ukuranCelanaOlahragaOptions[$index]);
        $this->ukuranCelanaOlahragaOptions = array_values($this->ukuranCelanaOlahragaOptions);
    }

    public function addUkuranBajuHarian()
    {
        $this->ukuranBajuHarianOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranBajuHarian($index)
    {
        unset($this->ukuranBajuHarianOptions[$index]);
        $this->ukuranBajuHarianOptions = array_values($this->ukuranBajuHarianOptions);
    }

    public function addUkuranCelanaRokHarian()
    {
        $this->ukuranCelanaRokHarianOptions[] = ['size' => '', 'measurement' => ''];
    }

    public function removeUkuranCelanaRokHarian($index)
    {
        unset($this->ukuranCelanaRokHarianOptions[$index]);
        $this->ukuranCelanaRokHarianOptions = array_values($this->ukuranCelanaRokHarianOptions);
    }

    public function saveUkuranSettings()
    {
        // Handle Image Uploads
        if ($this->ukuranJasImage) {
            $path = $this->ukuranJasImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_jas_image', $path);
            $this->existingUkuranJasImage = $path;
            $this->ukuranJasImage = null;
        }
        if ($this->ukuranBajuOlahragaImage) {
            $path = $this->ukuranBajuOlahragaImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_image', $path);
            $this->existingUkuranBajuOlahragaImage = $path;
            $this->ukuranBajuOlahragaImage = null;
        }
        if ($this->ukuranCelanaOlahragaImage) {
            $path = $this->ukuranCelanaOlahragaImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_image', $path);
            $this->existingUkuranCelanaOlahragaImage = $path;
            $this->ukuranCelanaOlahragaImage = null;
        }
        if ($this->ukuranBajuHarianImage) {
            $path = $this->ukuranBajuHarianImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_image', $path);
            $this->existingUkuranBajuHarianImage = $path;
            $this->ukuranBajuHarianImage = null;
        }
        if ($this->ukuranCelanaRokHarianImage) {
            $path = $this->ukuranCelanaRokHarianImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_image', $path);
            $this->existingUkuranCelanaRokHarianImage = $path;
            $this->ukuranCelanaRokHarianImage = null;
        }
        if ($this->ukuranKopyahImage) {
            $path = $this->ukuranKopyahImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_image', $path);
            $this->existingUkuranKopyahImage = $path;
            $this->ukuranKopyahImage = null;
        }
        // Legacy compatibility
        if ($this->ukuranAtasanImage) {
            $path = $this->ukuranAtasanImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_atasan_image', $path);
            $this->existingUkuranAtasanImage = $path;
            $this->ukuranAtasanImage = null;
        }
        if ($this->ukuranBawahanImage) {
            $path = $this->ukuranBawahanImage->store('panduan-ukuran', 'public');
            \App\Models\Setting::set('daftar_ulang_ukuran_bawahan_image', $path);
            $this->existingUkuranBawahanImage = $path;
            $this->ukuranBawahanImage = null;
        }

        // Save Settings
        \App\Models\Setting::set('daftar_ulang_ukuran_info', $this->ukuranInfo);
        \App\Models\Setting::set('ukuran_by_gender', $this->ukuranByGender, 'boolean');
        \App\Models\Setting::set('daftar_ulang_ukuran_jas_options', $this->ukuranJasOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_olahraga_options', $this->ukuranBajuOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_olahraga_options', $this->ukuranCelanaOlahragaOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_baju_harian_options', $this->ukuranBajuHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_celana_rok_harian_options', $this->ukuranCelanaRokHarianOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_kopyah_options', $this->ukuranKopyahOptions, 'json');
        // Legacy compatibility
        \App\Models\Setting::set('daftar_ulang_ukuran_atasan_options', $this->ukuranAtasanOptions, 'json');
        \App\Models\Setting::set('daftar_ulang_ukuran_bawahan_options', $this->ukuranBawahanOptions, 'json');

        $this->dispatch('saved', message: 'Pengaturan ukuran seragam berhasil disimpan.');
    }

    public function resetPersetujuan($pendaftarId)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($pendaftarId);

            // Reset status persetujuan ke belum_memilih
            $pendaftar->update([
                'status_persetujuan_daftar_ulang' => 'belum_memilih',
                'setuju_daftar_ulang' => false,
                'tanggal_pilihan_daftar_ulang' => null,
            ]);

            // Hapus record daftar ulang jika ada (agar bisa dibuat ulang)
            if ($pendaftar->daftarUlang) {
                $pendaftar->daftarUlang->delete();
            }

            $this->dispatch('saved', message: 'Persetujuan pendaftar berhasil direset. Pendaftar dapat memilih lagi.');

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal reset persetujuan: ' . $e->getMessage());
        }
    }

    public function changeStatusPersetujuan($pendaftarId, $status)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($pendaftarId);

            // Update status persetujuan
            $pendaftar->update([
                'status_persetujuan_daftar_ulang' => $status,
                'setuju_daftar_ulang' => $status === 'setuju',
                'tanggal_pilihan_daftar_ulang' => now(),
            ]);

            // Create or update daftar ulang record if approving
            if ($status === 'setuju') {
                DaftarUlangModel::firstOrCreate(
                    ['pendaftar_id' => $pendaftarId],
                    ['status' => 'menunggu']
                );
            } elseif ($status === 'tidak_setuju' && $pendaftar->daftarUlang) {
                // Delete daftar ulang record if rejecting
                $pendaftar->daftarUlang->delete();
            }

            $statusLabels = [
                'belum_memilih' => 'Belum Memilih',
                'setuju' => 'Setuju',
                'tidak_setuju' => 'Tidak Melanjutkan'
            ];
            $statusLabel = $statusLabels[$status] ?? $status;

            $this->dispatch('saved', message: "Status persetujuan berhasil diubah menjadi {$statusLabel}.");

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengubah status persetujuan: ' . $e->getMessage());
        }
    }

    public function changeStatusMadrasah($pendaftarId, $status)
    {
        $daftarUlang = DaftarUlangModel::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status' => $status]
        );

        $daftarUlang->update(['status' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status DU Madrasah berhasil diubah menjadi {$statusLabel}.");
    }

    public function changeStatusSeragam($pendaftarId, $status)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status_seragam' => $status]
        );

        $seragam->update(['status_seragam' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status DU Seragam berhasil diubah menjadi {$statusLabel}.");
    }

    public function changeStatusUkuran($pendaftarId, $status)
    {
        $seragam = Seragam::firstOrCreate(
            ['pendaftar_id' => $pendaftarId],
            ['status_ukuran' => $status]
        );

        $seragam->update(['status_ukuran' => $status]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        $this->dispatch('saved', message: "Status Ukuran berhasil diubah menjadi {$statusLabel}.");
    }

    // ========== SEND MESSAGE METHODS ==========
    public function sendManualEmail($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function sendManualWhatsApp($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'whatsapp';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function closeSendMessageModal()
    {
        $this->isSendMessageModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
    }

    public function loadMessageTemplate()
    {
        if (!$this->selectedMessageTemplate) {
            return;
        }

        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            return;
        }

        // Load template dari settings
        if ($this->messageType === 'email') {
            $this->messageSubject = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_subject", '');
            $this->messageBody = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_body", '');
        } else {
            $this->messageBody = \App\Models\Setting::get("wa_{$this->selectedMessageTemplate}_body", '');
        }

        // Replace placeholders
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap,
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran,
            '{email}' => $pendaftar->email,
            '{telepon}' => $pendaftar->no_hp,
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{status}' => $pendaftar->status_pendaftaran,
            '{catatan}' => $this->messageNote,
            '{url_dashboard}' => url('/dashboard'),
        ];

        $this->messageSubject = str_replace(array_keys($replacements), array_values($replacements), $this->messageSubject);
        $this->messageBody = str_replace(array_keys($replacements), array_values($replacements), $this->messageBody);
    }

    public function sendMessage()
    {
        $this->validate([
            'messageBody' => 'required|string',
        ]);

        if ($this->messageType === 'email') {
            $this->validate([
                'messageSubject' => 'required|string',
            ]);
        }

        $pendaftar = Pendaftar::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            $this->dispatch('saved', message: 'Pendaftar tidak ditemukan.');
            return;
        }

        try {
            if ($this->messageType === 'email') {
                // Kirim email
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)
                        ->subject($this->messageSubject);
                });

                // Save to message history
                \App\Models\MessageHistory::create([
                    'pendaftar_id' => $pendaftar->id,
                    'sender_id' => Auth::id(),
                    'message_type' => 'email',
                    'context' => 'daftar_ulang_' . $this->activeTab,
                    'message_content' => $this->messageSubject . ': ' . $this->messageBody,
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            } else {
                // Kirim WhatsApp
                $phoneNumber = $pendaftar->no_hp;
                if (!$phoneNumber) {
                    $this->dispatch('saved', message: 'Nomor WhatsApp tidak ditemukan.');
                    return;
                }

                // Format nomor telepon
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // Placeholder untuk integrasi WhatsApp API
                // TODO: Implementasikan sesuai dengan service yang digunakan

                // Save to message history
                \App\Models\MessageHistory::create([
                    'pendaftar_id' => $pendaftar->id,
                    'sender_id' => Auth::id(),
                    'message_type' => 'whatsapp',
                    'context' => 'daftar_ulang_' . $this->activeTab,
                    'message_content' => $this->messageBody,
                    'sent_at' => now(),
                ]);

                $this->dispatch('saved', message: 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            }

            $this->closeSendMessageModal();

        } catch (\Exception $e) {
            $this->dispatch('saved', message: 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }
}
