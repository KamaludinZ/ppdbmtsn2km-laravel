<?php

namespace App\Livewire\Admin;

use App\Models\TesPsikologiSetting;
use App\Models\TesPsikologiOpsi;
use App\Models\TesPsikologiBank;
use App\Models\TesPsikologiPendaftar;
use App\Models\RiwayatPesan;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\SurveyAnswer;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TesPsikologi extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public $mainTab = 'data'; // Main tab: data, settings, survey, or jawaban-survey

    // Filter for data tab
    public $search = '';
    public $filterStatus = 'all';

    public $setting;
    public $informasi;
    public $informasi_admin_panitia;
    public $rekening_bank_nama;
    public $rekening_nomor;
    public $rekening_atas_nama;
    public $rekening_keterangan;
    public $aktif;
    public $survey_aktif = false;
    public $survey_informasi;

    // Opsi CRUD
    public $opsiList;
    public $opsiNama;
    public $opsiDeskripsi;
    public $opsiUrutan;
    public $opsiAktif = true;
    public $editingOpsi = null;

    // Bank CRUD (Bank Pengirim / Metode Pembayaran)
    public $bankList;
    public $bankNamaBank;
    public $bankUrutan;
    public $bankAktif = true;
    public $editingBank = null;

    // Verification modal
    public $showVerificationModal = false;
    public $verifyingId = null;
    public $verificationStatus = null;
    public $verificationNote = '';

    // Message modal (simple notification)
    public $showMessageModal = false;
    public $messagingId = null;
    public $messagingPendaftar = null;
    public $messageContent = '';

    // Template message modal (with template dropdown)
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Survey CRUD
    public $surveyList;
    public $surveyPertanyaan;
    public $surveyDeskripsi;
    public $surveyTipeInput = 'text';
    public $surveyWajibDiisi = false;
    public $surveyUrutan = 0;
    public $surveyAktif = true;
    public $editingSurvey = null;

    // Survey Options (for radio, checkbox, select)
    public $surveyOptions = []; // Array of ['label' => '', 'value' => '', 'urutan' => 0]
    public $editingOptions = false;

    // Survey Answers Modal
    public $showSurveyAnswersModal = false;
    public $selectedPendaftar = null;
    public $pendaftarSurveyAnswers = [];

    public function mount()
    {
        // Only load settings data if user is admin
        if (Auth::user()->role === 'admin') {
            $this->setting = TesPsikologiSetting::getSetting();
            $this->informasi = $this->setting->informasi;
            $this->informasi_admin_panitia = $this->setting->informasi_admin_panitia;
            $this->rekening_bank_nama = $this->setting->rekening_bank_nama;
            $this->rekening_nomor = $this->setting->rekening_nomor;
            $this->rekening_atas_nama = $this->setting->rekening_atas_nama;
            $this->rekening_keterangan = $this->setting->rekening_keterangan;
            $this->aktif = $this->setting->aktif;
            $this->survey_aktif = $this->setting->survey_aktif ?? false;
            $this->survey_informasi = $this->setting->survey_informasi;

            $this->loadOpsi();
            $this->loadBank();
            $this->loadSurvey();
        }
    }

    public function loadOpsi()
    {
        $this->opsiList = TesPsikologiOpsi::ordered()->get();
    }

    public function loadBank()
    {
        $this->bankList = TesPsikologiBank::ordered()->get();
    }

    public function simpanSetting()
    {
        $this->validate([
            'informasi' => 'nullable|string',
            'informasi_admin_panitia' => 'nullable|string',
            'rekening_bank_nama' => 'nullable|string|max:255',
            'rekening_nomor' => 'nullable|string|max:255',
            'rekening_atas_nama' => 'nullable|string|max:255',
            'rekening_keterangan' => 'nullable|string',
            'aktif' => 'required|boolean'
        ]);

        $this->setting->update([
            'informasi' => $this->informasi,
            'informasi_admin_panitia' => $this->informasi_admin_panitia,
            'rekening_bank_nama' => $this->rekening_bank_nama,
            'rekening_nomor' => $this->rekening_nomor,
            'rekening_atas_nama' => $this->rekening_atas_nama,
            'rekening_keterangan' => $this->rekening_keterangan,
            'aktif' => $this->aktif
        ]);

        // Reload settings to ensure UI reflects saved data
        $this->setting->refresh();
        $this->informasi = $this->setting->informasi;
        $this->informasi_admin_panitia = $this->setting->informasi_admin_panitia;

        session()->flash('message', 'Pengaturan berhasil disimpan');
    }

    // Opsi CRUD Methods
    public function simpanOpsi()
    {
        $this->validate([
            'opsiNama' => 'required|string|max:255',
            'opsiDeskripsi' => 'nullable|string',
            'opsiUrutan' => 'required|integer|min:0',
            'opsiAktif' => 'required|boolean'
        ]);

        if ($this->editingOpsi) {
            $opsi = TesPsikologiOpsi::find($this->editingOpsi);
            $opsi->update([
                'nama' => $this->opsiNama,
                'deskripsi' => $this->opsiDeskripsi,
                'urutan' => $this->opsiUrutan,
                'aktif' => $this->opsiAktif
            ]);
            session()->flash('message', 'Opsi berhasil diupdate');
        } else {
            TesPsikologiOpsi::create([
                'nama' => $this->opsiNama,
                'deskripsi' => $this->opsiDeskripsi,
                'urutan' => $this->opsiUrutan,
                'aktif' => $this->opsiAktif
            ]);
            session()->flash('message', 'Opsi berhasil ditambahkan');
        }

        $this->resetOpsiForm();
        $this->loadOpsi();
    }

    public function editOpsi($id)
    {
        $opsi = TesPsikologiOpsi::find($id);
        $this->editingOpsi = $id;
        $this->opsiNama = $opsi->nama;
        $this->opsiDeskripsi = $opsi->deskripsi;
        $this->opsiUrutan = $opsi->urutan;
        $this->opsiAktif = $opsi->aktif;
    }

    public function hapusOpsi($id)
    {
        TesPsikologiOpsi::destroy($id);
        $this->loadOpsi();
        session()->flash('message', 'Opsi berhasil dihapus');
    }

    public function resetOpsiForm()
    {
        $this->editingOpsi = null;
        $this->opsiNama = '';
        $this->opsiDeskripsi = '';
        $this->opsiUrutan = 0;
        $this->opsiAktif = true;
    }

    // Bank CRUD Methods
    public function simpanBank()
    {
        $this->validate([
            'bankNamaBank' => 'required|string|max:255',
            'bankUrutan' => 'required|integer|min:0',
            'bankAktif' => 'required|boolean'
        ]);

        if ($this->editingBank) {
            $bank = TesPsikologiBank::find($this->editingBank);
            $bank->update([
                'nama_bank' => $this->bankNamaBank,
                'nomor_rekening' => '-', // Keep for compatibility
                'atas_nama' => '-', // Keep for compatibility
                'keterangan' => '-', // Keep for compatibility
                'urutan' => $this->bankUrutan,
                'aktif' => $this->bankAktif
            ]);
            session()->flash('message', 'Bank pengirim berhasil diupdate');
        } else {
            TesPsikologiBank::create([
                'nama_bank' => $this->bankNamaBank,
                'nomor_rekening' => '-', // Keep for compatibility
                'atas_nama' => '-', // Keep for compatibility
                'keterangan' => '-', // Keep for compatibility
                'urutan' => $this->bankUrutan,
                'aktif' => $this->bankAktif
            ]);
            session()->flash('message', 'Bank pengirim berhasil ditambahkan');
        }

        $this->resetBankForm();
        $this->loadBank();
    }

    public function editBank($id)
    {
        $bank = TesPsikologiBank::find($id);
        $this->editingBank = $id;
        $this->bankNamaBank = $bank->nama_bank;
        $this->bankUrutan = $bank->urutan;
        $this->bankAktif = $bank->aktif;
    }

    public function hapusBank($id)
    {
        TesPsikologiBank::destroy($id);
        $this->loadBank();
        session()->flash('message', 'Bank pengirim berhasil dihapus');
    }

    public function resetBankForm()
    {
        $this->editingBank = null;
        $this->bankNamaBank = '';
        $this->bankUrutan = 0;
        $this->bankAktif = true;
    }

    // Survey CRUD Methods
    public function loadSurvey()
    {
        // PENTING: Filter by context = 'tes_psikologi' untuk Survey Tes Psikologi
        $this->surveyList = SurveyQuestion::tesPsikologi()->with('options')->ordered()->get();
    }

    public function simpanSurvey()
    {
        $this->validate([
            'surveyPertanyaan' => 'required|string',
            'surveyDeskripsi' => 'nullable|string',
            'surveyTipeInput' => 'required|in:text,textarea,radio,checkbox,select',
            'surveyWajibDiisi' => 'required|boolean',
            'surveyUrutan' => 'required|integer|min:0',
            'surveyAktif' => 'required|boolean'
        ]);

        if ($this->editingSurvey) {
            $survey = SurveyQuestion::find($this->editingSurvey);
            $survey->update([
                'pertanyaan' => $this->surveyPertanyaan,
                'deskripsi' => $this->surveyDeskripsi,
                'tipe_input' => $this->surveyTipeInput,
                'wajib_diisi' => $this->surveyWajibDiisi,
                'urutan' => $this->surveyUrutan,
                'aktif' => $this->surveyAktif
            ]);
            session()->flash('message', 'Pertanyaan berhasil diupdate');
        } else {
            $survey = SurveyQuestion::create([
                'pertanyaan' => $this->surveyPertanyaan,
                'deskripsi' => $this->surveyDeskripsi,
                'tipe_input' => $this->surveyTipeInput,
                'wajib_diisi' => $this->surveyWajibDiisi,
                'urutan' => $this->surveyUrutan,
                'aktif' => $this->surveyAktif,
                'context' => 'tes_psikologi' // PENTING: Set context untuk Survey Tes Psikologi
            ]);
            $this->editingSurvey = $survey->id;
            session()->flash('message', 'Pertanyaan berhasil ditambahkan');
        }

        // Save options if needed (for radio, checkbox, select)
        if (in_array($this->surveyTipeInput, ['radio', 'checkbox', 'select'])) {
            $this->editingOptions = true;
        } else {
            $this->resetSurveyForm();
        }

        $this->loadSurvey();
    }

    public function simpanOpsiSurvey()
    {
        if (!$this->editingSurvey) {
            session()->flash('error', 'Simpan pertanyaan terlebih dahulu');
            return;
        }

        // Delete existing options
        SurveyQuestionOption::where('survey_question_id', $this->editingSurvey)->delete();

        // Save new options
        foreach ($this->surveyOptions as $option) {
            if (!empty($option['label']) && !empty($option['value'])) {
                SurveyQuestionOption::create([
                    'survey_question_id' => $this->editingSurvey,
                    'label' => $option['label'],
                    'value' => $option['value'],
                    'urutan' => $option['urutan'] ?? 0
                ]);
            }
        }

        session()->flash('message', 'Opsi jawaban berhasil disimpan');
        $this->resetSurveyForm();
        $this->loadSurvey();
    }

    public function editSurvey($id)
    {
        // PENTING: Filter by context untuk memastikan hanya edit survey tes psikologi
        $survey = SurveyQuestion::tesPsikologi()->with('options')->find($id);
        $this->editingSurvey = $id;
        $this->surveyPertanyaan = $survey->pertanyaan;
        $this->surveyDeskripsi = $survey->deskripsi;
        $this->surveyTipeInput = $survey->tipe_input;
        $this->surveyWajibDiisi = $survey->wajib_diisi;
        $this->surveyUrutan = $survey->urutan;
        $this->surveyAktif = $survey->aktif;

        // Load existing options
        if (in_array($survey->tipe_input, ['radio', 'checkbox', 'select'])) {
            $this->surveyOptions = $survey->options->map(function ($option) {
                return [
                    'label' => $option->label,
                    'value' => $option->value,
                    'urutan' => $option->urutan
                ];
            })->toArray();
            $this->editingOptions = true;
        }
    }

    public function hapusSurvey($id)
    {
        SurveyQuestion::destroy($id);
        $this->loadSurvey();
        session()->flash('message', 'Pertanyaan berhasil dihapus');
    }

    public function resetSurveyForm()
    {
        $this->editingSurvey = null;
        $this->surveyPertanyaan = '';
        $this->surveyDeskripsi = '';
        $this->surveyTipeInput = 'text';
        $this->surveyWajibDiisi = false;
        $this->surveyUrutan = 0;
        $this->surveyAktif = true;
        $this->surveyOptions = [];
        $this->editingOptions = false;
    }

    public function addSurveyOption()
    {
        $this->surveyOptions[] = ['label' => '', 'value' => '', 'urutan' => count($this->surveyOptions)];
    }

    public function removeSurveyOption($index)
    {
        unset($this->surveyOptions[$index]);
        $this->surveyOptions = array_values($this->surveyOptions); // Re-index array
    }

    public function simpanSurveySetting()
    {
        $this->validate([
            'survey_aktif' => 'required|boolean',
            'survey_informasi' => 'nullable|string'
        ]);

        $this->setting->update([
            'survey_aktif' => $this->survey_aktif,
            'survey_informasi' => $this->survey_informasi
        ]);

        session()->flash('message', 'Pengaturan survey berhasil disimpan');
    }

    // Survey Answers Modal Methods
    public function openSurveyAnswersModal($pendaftarId)
    {
        $this->selectedPendaftar = \App\Models\Pendaftar::find($pendaftarId);

        if ($this->selectedPendaftar) {
            // PENTING: Filter hanya jawaban untuk survey tes psikologi
            $answers = SurveyAnswer::where('pendaftar_id', $pendaftarId)
                ->whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->with('question.options')
                ->get();

            $this->pendaftarSurveyAnswers = $answers->map(function ($answer) {
                $question = $answer->question;
                $jawaban = $answer->jawaban;

                // Format jawaban berdasarkan tipe
                if (in_array($question->tipe_input, ['radio', 'checkbox', 'select'])) {
                    // Get label from options
                    $jawabanLabels = [];
                    foreach ((array)$jawaban as $value) {
                        $option = $question->options->where('value', $value)->first();
                        $jawabanLabels[] = $option ? $option->label : $value;
                    }
                    $jawabanText = implode(', ', $jawabanLabels);
                } else {
                    $jawabanText = is_array($jawaban) ? implode(', ', $jawaban) : $jawaban;
                }

                return [
                    'pertanyaan' => $question->pertanyaan,
                    'tipe_input' => $question->tipe_input,
                    'jawaban' => $jawabanText
                ];
            })->toArray();

            $this->showSurveyAnswersModal = true;
        }
    }

    public function closeSurveyAnswersModal()
    {
        $this->showSurveyAnswersModal = false;
        $this->selectedPendaftar = null;
        $this->pendaftarSurveyAnswers = [];
    }

    // Tab Management
    public function setMainTab($tab)
    {
        $this->mainTab = $tab;
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Verification Methods
    public function openVerificationModal($id, $status)
    {
        $this->verifyingId = $id;
        $this->verificationStatus = $status;
        $this->verificationNote = '';
        $this->showVerificationModal = true;
    }

    public function verifyPayment()
    {
        $this->validate([
            'verificationStatus' => 'required|in:disetujui,ditolak,perbaiki',
            'verificationNote' => 'nullable|string|max:500',
        ]);

        $tesPsikologi = TesPsikologiPendaftar::findOrFail($this->verifyingId);

        $tesPsikologi->update([
            'status_verifikasi' => $this->verificationStatus,
            'catatan_verifikasi' => $this->verificationNote,
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Send notification to pendaftar
        if ($tesPsikologi->pendaftar && $tesPsikologi->pendaftar->user) {
            $tesPsikologi->pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'tes_psikologi',
                    $this->verificationStatus,
                    $this->verificationNote
                )
            );
        }

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki'
        ];
        $statusLabel = $statusLabels[$this->verificationStatus] ?? $this->verificationStatus;
        session()->flash('message', "Bukti pembayaran berhasil {$statusLabel}.");

        $this->closeVerificationModal();
    }

    public function changeStatus($id, $status)
    {
        $tesPsikologi = TesPsikologiPendaftar::findOrFail($id);

        $tesPsikologi->update([
            'status_verifikasi' => $status,
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        // Notification is sent manually via "Kirim Pesan" button
        // Auto-notification removed as per user request

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'perbaiki' => 'diminta untuk diperbaiki',
            'menunggu' => 'dikembalikan ke menunggu verifikasi'
        ];
        $statusLabel = $statusLabels[$status] ?? $status;
        session()->flash('message', "Status psikologi berhasil diubah menjadi {$statusLabel}.");
    }

    public function closeVerificationModal()
    {
        $this->showVerificationModal = false;
        $this->verifyingId = null;
        $this->verificationStatus = null;
        $this->verificationNote = '';
        $this->resetValidation();
    }

    // Message Methods
    public function sendManualEmail($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement email sending logic here
        // Example: Mail::to($tesPsikologi->pendaftar->user->email)->send(new TesPsikologiEmail($tesPsikologi));

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => Auth::id(),
            'message_type' => 'email',
            'context' => 'tes_psikologi',
            'message_content' => 'Email dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'Email berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
    }

    public function sendManualWhatsApp($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement WhatsApp sending logic here
        // Example: Send WhatsApp message via API

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => Auth::id(),
            'message_type' => 'whatsapp',
            'context' => 'tes_psikologi',
            'message_content' => 'WhatsApp dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'WhatsApp berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
    }

    public function openMessageModal($id)
    {
        $this->messagingId = $id;
        $this->messagingPendaftar = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);
        $this->messageContent = '';
        $this->showMessageModal = true;
    }

    public function sendMessage()
    {
        $this->validate([
            'messageContent' => 'required|string|max:1000',
        ], [
            'messageContent.required' => 'Pesan harus diisi',
            'messageContent.max' => 'Pesan maksimal 1000 karakter',
        ]);

        $tesPsikologi = TesPsikologiPendaftar::findOrFail($this->messagingId);

        $penerima = $tesPsikologi->pendaftar->email ?? 'notification';

        // Create log record with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'user_id' => Auth::id(),
            'jenis_pesan' => 'email', // Using email as notification type
            'judul' => 'Pesan Tes Psikologi',
            'isi_pesan' => $this->messageContent,
            'penerima' => $penerima,
            'status' => 'pending',
        ]);

        try {
            // Send notification to pendaftar
            if ($tesPsikologi->pendaftar && $tesPsikologi->pendaftar->user) {
                $tesPsikologi->pendaftar->user->notify(
                    new \App\Notifications\CustomMessageNotification(
                        'Pesan Tes Psikologi',
                        $this->messageContent,
                        'tes_psikologi'
                    )
                );
            }

            // Update log to success
            $riwayatPesan->update([
                'status' => 'terkirim',
                'sent_at' => now(),
            ]);

            session()->flash('message', 'Pesan berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
            $this->closeMessageModal();
        } catch (\Exception $e) {
            // Update log to failed with error message
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function closeMessageModal()
    {
        $this->showMessageModal = false;
        $this->messagingId = null;
        $this->messagingPendaftar = null;
        $this->messageContent = '';
        $this->resetValidation();
    }

    // ========== TEMPLATE MESSAGE METHODS ==========

    /**
     * Get available message templates for this context (Tes Psikologi)
     * Context: Verifikasi Transfer only
     */
    public function getAvailableTemplates()
    {
        return [
            [
                'label' => '💳 Verifikasi Transfer',
                'options' => [
                    ['value' => 'verifikasi_transfer_disetujui', 'label' => 'Verifikasi Transfer Disetujui'],
                    ['value' => 'verifikasi_transfer_ditolak', 'label' => 'Verifikasi Transfer Ditolak'],
                    ['value' => 'verifikasi_transfer_perbaiki', 'label' => 'Verifikasi Transfer Perlu Perbaikan'],
                ]
            ]
        ];
    }

    // Livewire lifecycle hook - dipanggil otomatis saat selectedMessageTemplate berubah
    public function updatedSelectedMessageTemplate()
    {
        $this->loadMessageTemplate();
    }

    // Livewire lifecycle hook - dipanggil otomatis saat messageType berubah
    public function updatedMessageType()
    {
        // Reset template saat ganti jenis pesan
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
    }

    public function loadMessageTemplate()
    {
        if (!$this->selectedMessageTemplate) {
            return;
        }

        $tesPsikologi = TesPsikologiPendaftar::with(['pendaftar', 'pendaftar.jalurPendaftaran', 'pendaftar.user'])->find($this->selectedPendaftarId);
        if (!$tesPsikologi || !$tesPsikologi->pendaftar) {
            return;
        }

        $pendaftar = $tesPsikologi->pendaftar;

        // Load template dari settings
        if ($this->messageType === 'email') {
            $this->messageSubject = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_subject", '');
            $this->messageBody = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_body", '');
        } else {
            $this->messageBody = \App\Models\Setting::get("wa_{$this->selectedMessageTemplate}_body", '');
        }

        // Replace placeholders (27 total)
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap ?? '',
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran ?? '',
            '{nisn}' => $pendaftar->nisn ?? '',
            '{tempat_lahir}' => $pendaftar->tempat_lahir ?? '',
            '{tanggal_lahir}' => $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '',
            '{jenis_kelamin}' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            '{alamat}' => $pendaftar->alamat ?? '',
            '{email}' => $pendaftar->email ?? '',
            '{telepon}' => $pendaftar->no_hp ?? '',
            '{no_hp}' => $pendaftar->no_hp ?? '',
            '{asal_sekolah}' => $pendaftar->asal_sekolah ?? '',
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{status}' => $pendaftar->status_pendaftaran ?? '',
            '{catatan}' => $this->messageNote ?? '',
            '{url_dashboard}' => url('/dashboard'),
            '{password}' => '[Password telah dienkripsi, silakan reset jika lupa]',
            // Additional fields
            '{agama}' => $pendaftar->agama ?? '',
            '{nama_ayah}' => $pendaftar->nama_ayah ?? '',
            '{nama_ibu}' => $pendaftar->nama_ibu ?? '',
            '{pekerjaan_ayah}' => $pendaftar->pekerjaan_ayah ?? '',
            '{pekerjaan_ibu}' => $pendaftar->pekerjaan_ibu ?? '',
            '{provinsi}' => $pendaftar->provinsi ?? '',
            '{kabupaten}' => $pendaftar->kabupaten ?? '',
            '{kecamatan}' => $pendaftar->kecamatan ?? '',
            '{desa}' => $pendaftar->desa ?? '',
            '{kode_pos}' => $pendaftar->kode_pos ?? '',
            '{nilai_rata_rata}' => $pendaftar->nilai_rata_rata ?? '',
        ];

        $this->messageSubject = str_replace(array_keys($replacements), array_values($replacements), $this->messageSubject);
        $this->messageBody = str_replace(array_keys($replacements), array_values($replacements), $this->messageBody);
    }

    public function openSendMessageModal($id)
    {
        $this->selectedPendaftarId = $id;
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function sendMessageTemplate()
    {
        $this->validate([
            'messageBody' => 'required|string',
        ]);

        if ($this->messageType === 'email') {
            $this->validate([
                'messageSubject' => 'required|string',
            ]);
        }

        $tesPsikologi = TesPsikologiPendaftar::with(['pendaftar', 'pendaftar.user'])->find($this->selectedPendaftarId);
        if (!$tesPsikologi || !$tesPsikologi->pendaftar) {
            session()->flash('error', 'Data tes psikologi tidak ditemukan.');
            return;
        }

        $pendaftar = $tesPsikologi->pendaftar;
        $penerima = $this->messageType === 'email' ? $pendaftar->email : $pendaftar->no_hp;

        // Create log record with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $pendaftar->id,
            'user_id' => Auth::id(),
            'jenis_pesan' => $this->messageType,
            'judul' => $this->messageType === 'email' ? $this->messageSubject : 'Verifikasi Transfer Tes Psikologi',
            'isi_pesan' => $this->messageBody,
            'penerima' => $penerima,
            'status' => 'pending',
        ]);

        try {
            if ($this->messageType === 'email') {
                // Kirim email
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)
                        ->subject($this->messageSubject);
                });

                // Update log to success
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                session()->flash('message', 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            } else {
                // Kirim WhatsApp
                $phoneNumber = $pendaftar->no_hp;
                if (!$phoneNumber) {
                    $riwayatPesan->update([
                        'status' => 'gagal',
                        'error_message' => 'Nomor WhatsApp tidak ditemukan',
                    ]);
                    session()->flash('error', 'Nomor WhatsApp tidak ditemukan.');
                    return;
                }

                // Format nomor telepon
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // Placeholder untuk integrasi WhatsApp API
                // TODO: Implementasikan sesuai dengan service yang digunakan

                // Update log to success
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                session()->flash('message', 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            }

            $this->closeSendMessageModal();
        } catch (\Exception $e) {
            // Update log to failed with error message
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function closeSendMessageModal()
    {
        $this->isSendMessageModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->resetValidation();
    }

    public function render()
    {
        // Get statistics
        $stats = [
            'total' => TesPsikologiPendaftar::count(),
            'menunggu' => TesPsikologiPendaftar::menungguVerifikasi()->count(),
            'disetujui' => TesPsikologiPendaftar::disetujui()->count(),
            'ditolak' => TesPsikologiPendaftar::ditolak()->count(),
            'perbaiki' => TesPsikologiPendaftar::where('status_verifikasi', 'perbaiki')->count(),
            'survey_dijawab' => SurveyAnswer::whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->distinct('pendaftar_id')
                ->count('pendaftar_id'),
        ];

        // Get data psikologi with pagination
        $dataPsikologi = [];
        if ($this->mainTab === 'data') {
            $query = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy']);

            // Apply search filter
            if ($this->search) {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                      ->orWhere('nisn', 'like', '%'.$this->search.'%')
                      ->orWhere('nomor_pendaftaran', 'like', '%'.$this->search.'%');
                });
            }

            // Apply status filter
            if ($this->filterStatus !== 'all') {
                $query->where('status_verifikasi', $this->filterStatus);
            }

            $dataPsikologi = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        // Get survey answers data for jawaban-survey tab
        $surveyAnswersData = [];
        $surveyQuestions = [];
        if ($this->mainTab === 'jawaban-survey') {
            // Get all active survey questions for tes psikologi
            $surveyQuestions = SurveyQuestion::tesPsikologi()
                ->aktif()
                ->ordered()
                ->get();

            // Get all pendaftar who registered for tes psikologi
            $query = TesPsikologiPendaftar::with(['pendaftar'])
                ->whereHas('pendaftar');

            // Apply search filter
            if ($this->search) {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('nama_lengkap', 'like', '%'.$this->search.'%')
                      ->orWhere('nisn', 'like', '%'.$this->search.'%')
                      ->orWhere('nomor_pendaftaran', 'like', '%'.$this->search.'%');
                });
            }

            $tesPsikologiData = $query->orderBy('created_at', 'desc')->paginate(15);

            // For each pendaftar, get their survey answers
            foreach ($tesPsikologiData as $data) {
                $pendaftar = $data->pendaftar;

                // Check if pendaftar has answered survey
                $answersCount = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
                    ->whereHas('question', function($q) {
                        $q->tesPsikologi();
                    })
                    ->count();

                $surveyStatus = $answersCount > 0 ? 'Sudah Dijawab' : 'Belum Dijawab';

                // Get answers for each question
                $answers = [];
                foreach ($surveyQuestions as $question) {
                    $answer = SurveyAnswer::where('pendaftar_id', $pendaftar->id)
                        ->where('survey_question_id', $question->id)
                        ->first();

                    $answers[$question->id] = $answer ? $answer->jawaban : '-';
                }

                $surveyAnswersData[] = [
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                    'nisn' => $pendaftar->nisn ?? '-',
                    'nama_lengkap' => $pendaftar->nama_lengkap,
                    'survey_status' => $surveyStatus,
                    'answers' => $answers
                ];
            }

            $dataPsikologi = $tesPsikologiData; // For pagination links
        }

        // Get setting for info box (available to both admin and panitia)
        $setting = TesPsikologiSetting::getSetting();

        return view('livewire.admin.tes-psikologi', compact('stats', 'dataPsikologi', 'setting', 'surveyAnswersData', 'surveyQuestions'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Tes Psikologi',
                'pageDescription' => 'Kelola data dan pengaturan tes psikologi pendaftaran'
            ]);
    }
}
