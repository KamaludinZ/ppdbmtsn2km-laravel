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

    // Filter for jawaban-survey tab
    public $filterStatusSurvey = 'all'; // all, sudah, belum
    public $filterJalur = 'all';
    public $filterJenisKelamin = 'all'; // all, L, P

    // Edit Mode (Admin Only - Crucial Case Handling)
    // Persistent state via query string - survives page refresh
    #[Url(keep: true)]
    public $editMode = false;

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

    // Edit Catatan Psikologi modal
    public $showCatatanModal = false;
    public $editingCatatanId = null;
    public $catatanPsikologi = '';

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

    // Individual Option Edit Modal
    public $showOptionModal = false;
    public $editingOptionId = null;
    public $optionLabel = '';
    public $optionValue = '';
    public $optionUrutan = 0;
    public $optionQuestionId = null;

    // Survey Answers Modal
    public $showSurveyAnswersModal = false;
    public $selectedPendaftar = null;
    public $pendaftarSurveyAnswers = [];

    // Edit Answer Modal (Admin Only - Crucial Case Handling)
    public $showEditAnswerModal = false;
    public $editingAnswerPendaftarId = null;
    public $editingAnswerQuestionId = null;
    public $editingAnswerValue = ''; // For text, radio, select
    public $editingAnswerCheckboxValues = []; // For checkbox (array of selected IDs)
    public $editingAnswerQuestion = null;

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

        // NOTE: Tidak perlu set editingOptions lagi!
        // Admin bisa tambah/edit options via individual modal (hover buttons)
        // Form pertanyaan direset setelah simpan, kembali ke mode add new question
        $this->resetSurveyForm();
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

        // NOTE: TIDAK set editingOptions = true lagi!
        // Karena sekarang opsi bisa diedit individual via hover buttons
        // Form pertanyaan tetap tampil untuk edit teks, deskripsi, tipe, dll
        // Options sudah ada di list survey dengan individual edit/delete buttons
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

    /**
     * Create New Option for a Question
     * Opens modal with empty form to add new option
     */
    public function createOption($questionId)
    {
        // Verify question exists and belongs to tes_psikologi context
        $question = SurveyQuestion::tesPsikologi()->find($questionId);

        if (!$question) {
            session()->flash('error', 'Pertanyaan tidak ditemukan.');
            return;
        }

        // Verify question type supports options
        if (!in_array($question->tipe_input, ['radio', 'checkbox', 'select'])) {
            session()->flash('error', 'Pertanyaan ini tidak memerlukan opsi jawaban.');
            return;
        }

        // Open modal with empty form (for creating new option)
        $this->editingOptionId = null;
        $this->optionLabel = '';
        $this->optionValue = '';
        $this->optionUrutan = 0;
        $this->optionQuestionId = $questionId;
        $this->showOptionModal = true;
    }

    /**
     * Edit Individual Option
     * Opens modal to edit a specific option
     */
    public function editOption($id)
    {
        $option = SurveyQuestionOption::findOrFail($id);

        // Verify this option belongs to a tes_psikologi survey question
        if ($option->question->context !== 'tes_psikologi') {
            session()->flash('error', 'Opsi tidak ditemukan atau bukan milik survey tes psikologi.');
            return;
        }

        $this->editingOptionId = $option->id;
        $this->optionLabel = $option->label;
        $this->optionValue = $option->value;
        $this->optionUrutan = $option->urutan;
        $this->optionQuestionId = $option->survey_question_id;
        $this->showOptionModal = true;
    }

    /**
     * Save/Update Individual Option
     */
    public function saveOption()
    {
        $this->validate([
            'optionLabel' => 'required|string|max:255',
            'optionValue' => 'required|string|max:255',
            'optionUrutan' => 'integer|min:0',
        ]);

        if ($this->editingOptionId) {
            // Update existing option
            $option = SurveyQuestionOption::findOrFail($this->editingOptionId);
            $option->update([
                'label' => $this->optionLabel,
                'value' => $this->optionValue,
                'urutan' => $this->optionUrutan,
            ]);

            session()->flash('message', 'Opsi berhasil diperbarui.');
        } else {
            // Create new option
            SurveyQuestionOption::create([
                'survey_question_id' => $this->optionQuestionId,
                'label' => $this->optionLabel,
                'value' => $this->optionValue,
                'urutan' => $this->optionUrutan,
            ]);

            session()->flash('message', 'Opsi berhasil ditambahkan.');
        }

        $this->closeOptionModal();
        $this->loadSurvey();
    }

    /**
     * Delete Individual Option with Protection
     * Prevents deletion if option has submissions
     */
    public function deleteOption($id)
    {
        $option = SurveyQuestionOption::find($id);

        if (!$option) {
            session()->flash('error', 'Opsi tidak ditemukan.');
            return;
        }

        // Verify this option belongs to a tes_psikologi survey question
        if ($option->question->context !== 'tes_psikologi') {
            session()->flash('error', 'Opsi tidak ditemukan atau bukan milik survey tes psikologi.');
            return;
        }

        // CRITICAL: Check if option has submissions before delete
        // Mencegah data integrity issue (VALUE NOT FOUND)
        $answersQuery = SurveyAnswer::where('survey_question_id', $option->survey_question_id)
            ->where(function($query) use ($option) {
                // Check for VALUE (old data)
                $query->whereJsonContains('jawaban', $option->value)
                      // Check for LABEL (some old data)
                      ->orWhereJsonContains('jawaban', $option->label)
                      // Check for ID (new data)
                      ->orWhereJsonContains('jawaban', $option->id)
                      ->orWhereJsonContains('jawaban', (string)$option->id);
            });

        $submissionCount = $answersQuery->count();

        if ($submissionCount > 0) {
            session()->flash('error',
                'Tidak dapat menghapus opsi ini! Ada ' . $submissionCount . ' pendaftar yang sudah submit jawaban dengan opsi ini. ' .
                'Menghapus akan menyebabkan "[VALUE NOT FOUND]" di data mereka.'
            );
            return;
        }

        // Safe to delete
        $option->delete();
        session()->flash('message', 'Opsi berhasil dihapus.');
        $this->loadSurvey();
    }

    /**
     * Close Option Modal and Reset Fields
     */
    public function closeOptionModal()
    {
        $this->showOptionModal = false;
        $this->editingOptionId = null;
        $this->optionLabel = '';
        $this->optionValue = '';
        $this->optionUrutan = 0;
        $this->optionQuestionId = null;
        $this->resetValidation();
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

    public function openCatatanModal($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::findOrFail($id);
        $this->editingCatatanId = $id;
        $this->catatanPsikologi = $tesPsikologi->catatan_psikologi ?? '';
        $this->showCatatanModal = true;
    }

    public function saveCatatan()
    {
        $this->validate([
            'catatanPsikologi' => 'nullable|string|max:1000',
        ]);

        $tesPsikologi = TesPsikologiPendaftar::findOrFail($this->editingCatatanId);

        $tesPsikologi->update([
            'catatan_psikologi' => $this->catatanPsikologi,
        ]);

        session()->flash('message', 'Catatan psikologi berhasil disimpan.');

        $this->closeCatatanModal();
    }

    public function closeCatatanModal()
    {
        $this->showCatatanModal = false;
        $this->editingCatatanId = null;
        $this->catatanPsikologi = '';
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

    /**
     * Reset survey answers for a specific pendaftar
     */
    public function resetSurveyAnswers($pendaftarId)
    {
        try {
            // Delete all survey answers for this pendaftar (tes psikologi context only)
            SurveyAnswer::where('pendaftar_id', $pendaftarId)
                ->whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->delete();

            session()->flash('message', 'Jawaban survey berhasil direset');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mereset jawaban survey: ' . $e->getMessage());
        }
    }

    /**
     * Open edit answer modal (Admin only - crucial case handling)
     */
    public function openEditAnswerModal($pendaftarId, $questionId)
    {
        $this->editingAnswerPendaftarId = $pendaftarId;
        $this->editingAnswerQuestionId = $questionId;

        // Get question data
        $this->editingAnswerQuestion = SurveyQuestion::with('options')->find($questionId);

        // Reset values
        $this->editingAnswerValue = '';
        $this->editingAnswerCheckboxValues = [];

        // Get current answer
        $answer = SurveyAnswer::where('pendaftar_id', $pendaftarId)
            ->where('survey_question_id', $questionId)
            ->first();

        if ($answer) {
            $jawaban = $answer->jawaban;

            // CRITICAL FIX: Handle JSON string from PostgreSQL JSONB
            // PostgreSQL can return JSONB as string "[10,12]" instead of PHP array
            if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
                $decoded = json_decode($jawaban, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $jawaban = $decoded;
                }
            }

            // For checkbox, store as array for checkboxes
            if ($this->editingAnswerQuestion->tipe_input === 'checkbox') {
                if (is_array($jawaban)) {
                    // Convert to strings for wire:model binding, filter out empty/zero values
                    $filtered = array_filter($jawaban, function($val) {
                        return !empty($val) && $val != 0 && $val !== "0";
                    });
                    $this->editingAnswerCheckboxValues = array_map('strval', $filtered);
                } else {
                    // Single value - only add if not empty/zero
                    if (!empty($jawaban) && $jawaban != 0 && $jawaban !== "0") {
                        $this->editingAnswerCheckboxValues = [strval($jawaban)];
                    } else {
                        $this->editingAnswerCheckboxValues = [];
                    }
                }
            } else {
                // For text, radio, select - store as string
                $this->editingAnswerValue = is_array($jawaban) ? implode(',', $jawaban) : $jawaban;
            }
        }

        $this->showEditAnswerModal = true;
    }

    /**
     * Close edit answer modal
     */
    public function closeEditAnswerModal()
    {
        $this->showEditAnswerModal = false;
        $this->editingAnswerPendaftarId = null;
        $this->editingAnswerQuestionId = null;
        $this->editingAnswerValue = '';
        $this->editingAnswerCheckboxValues = [];
        $this->editingAnswerQuestion = null;
    }

    /**
     * Toggle Edit Mode (Admin Only - Crucial Case Handling)
     * State persists via URL query string - survives page refresh
     */
    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;

        // Log action for audit trail
        \Log::info('Admin toggled Edit Mode', [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'edit_mode' => $this->editMode ? 'ENABLED' : 'DISABLED',
            'tab' => $this->mainTab,
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Update survey answer (Admin only - crucial case handling)
     */
    public function updateSurveyAnswer()
    {
        $question = $this->editingAnswerQuestion;

        // Validate based on question type
        if ($question->tipe_input === 'checkbox') {
            $this->validate([
                'editingAnswerCheckboxValues' => 'required|array|min:1',
            ], [
                'editingAnswerCheckboxValues.required' => 'Pilih minimal 1 jawaban',
                'editingAnswerCheckboxValues.min' => 'Pilih minimal 1 jawaban'
            ]);
        } else {
            $this->validate([
                'editingAnswerValue' => 'required',
            ], [
                'editingAnswerValue.required' => 'Jawaban tidak boleh kosong'
            ]);
        }

        try {
            // Prepare jawaban based on question type
            $jawaban = null;

            if ($question->tipe_input === 'text' || $question->tipe_input === 'textarea') {
                // Text: store as string
                $jawaban = $this->editingAnswerValue;
            } elseif ($question->tipe_input === 'checkbox') {
                // Checkbox: store array of selected option IDs
                // Convert to integers and filter out empty/zero values
                $jawaban = array_map('intval', $this->editingAnswerCheckboxValues);
                $jawaban = array_filter($jawaban, function($val) {
                    return $val > 0; // Only keep positive integers
                });
                $jawaban = array_values($jawaban); // Re-index

                // Validate we have at least one valid value
                if (empty($jawaban)) {
                    session()->flash('error', 'Pilih minimal 1 jawaban yang valid');
                    return;
                }
            } else {
                // Radio/Select: store as single value (convert to int if numeric)
                $value = trim($this->editingAnswerValue);
                $jawaban = is_numeric($value) ? intval($value) : $value;
            }

            // Update or create answer
            // CRITICAL FIX: JSON encode arrays (checkbox) before saving to TEXT column
            $jawabanToSave = is_array($jawaban) ? json_encode($jawaban) : $jawaban;

            SurveyAnswer::updateOrCreate(
                [
                    'pendaftar_id' => $this->editingAnswerPendaftarId,
                    'survey_question_id' => $this->editingAnswerQuestionId
                ],
                [
                    'jawaban' => $jawabanToSave
                ]
            );

            session()->flash('message', 'Jawaban berhasil diupdate');
            $this->closeEditAnswerModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengupdate jawaban: ' . $e->getMessage());
        }
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
            $query = TesPsikologiPendaftar::with(['pendaftar', 'pendaftar.jalurPendaftaran', 'opsi', 'bank', 'verifiedBy']);

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
            // Get all active survey questions for tes psikologi (with options for label conversion)
            $surveyQuestions = SurveyQuestion::tesPsikologi()
                ->aktif()
                ->ordered()
                ->with('options')
                ->get();

            // Get all pendaftar who registered for tes psikologi
            $query = TesPsikologiPendaftar::with(['pendaftar', 'pendaftar.jalurPendaftaran'])
                ->whereHas('pendaftar');

            // Apply search filter
            if ($this->search) {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('nama_lengkap', 'ilike', '%'.$this->search.'%')
                      ->orWhere('nisn', 'ilike', '%'.$this->search.'%')
                      ->orWhere('nomor_pendaftaran', 'ilike', '%'.$this->search.'%');
                });
            }

            // Apply jalur filter
            if ($this->filterJalur !== 'all') {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('jalur_pendaftaran_id', $this->filterJalur);
                });
            }

            // Apply jenis kelamin filter
            if ($this->filterJenisKelamin !== 'all') {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('jenis_kelamin', $this->filterJenisKelamin);
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

                    if (!$answer) {
                        $answers[$question->id] = '-';
                        continue;
                    }

                    $jawaban = $answer->jawaban;

                    // Format jawaban berdasarkan tipe - convert VALUE to LABEL for options-based questions
                    if (in_array($question->tipe_input, ['radio', 'checkbox', 'select'])) {
                        // CRITICAL FIX: Query options directly with WHERE clause to ensure correct filtering
                        $questionOptions = SurveyQuestionOption::where('survey_question_id', $question->id)
                            ->orderBy('urutan')
                            ->get();

                        // Get label from options instead of showing raw value
                        $jawabanLabels = [];

                        // CRITICAL FIX: Handle both PHP array and JSON string array
                        // PostgreSQL JSONB can return as string "[10,12]" instead of PHP array
                        if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
                            // Decode JSON string to PHP array
                            $decoded = json_decode($jawaban, true);
                            $jawabanArray = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$jawaban];
                        } elseif (is_array($jawaban)) {
                            $jawabanArray = $jawaban;
                        } else {
                            $jawabanArray = [$jawaban];
                        }

                        foreach ($jawabanArray as $value) {
                            // Skip empty values
                            if (empty($value) || $value === '-') {
                                continue;
                            }

                            // Normalize value: trim whitespace and convert to string for comparison
                            $normalizedValue = trim((string)$value);

                            // Find matching option - try multiple comparison methods with PRIORITY
                            $option = null;

                            // ======================================================================
                            // NEW APPROACH: Match by OPTION ID (data integrity)
                            // ======================================================================
                            // Starting Feb 2026: Jawaban menyimpan OPTION ID untuk data integrity
                            // Benefit: Admin dapat ubah LABEL/VALUE tanpa corrupt existing answers

                            // Method 1: Match by OPTION ID (PRIMARY METHOD for new data)
                            // Data baru (Feb 2026+) simpan Option ID, bukan VALUE
                            if (is_numeric($normalizedValue)) {
                                $option = $questionOptions->firstWhere('id', (int)$normalizedValue);
                            }

                            // ======================================================================
                            // BACKWARD COMPATIBILITY: Fallback untuk data lama (before Feb 2026)
                            // ======================================================================

                            // Method 2: Exact string match on VALUE (untuk data lama yang simpan VALUE)
                            if (!$option) {
                                $option = $questionOptions->first(function($opt) use ($normalizedValue) {
                                    return trim((string)$opt->value) === $normalizedValue;
                                });
                            }

                            // Method 3: Case-insensitive match on VALUE
                            if (!$option) {
                                $option = $questionOptions->first(function($opt) use ($normalizedValue) {
                                    return strcasecmp(trim((string)$opt->value), $normalizedValue) === 0;
                                });
                            }

                            // Method 4: Try matching by LABEL (untuk data lama yang simpan label)
                            if (!$option) {
                                $option = $questionOptions->first(function($opt) use ($normalizedValue) {
                                    $normalizedLabel = trim((string)$opt->label);
                                    return strcasecmp($normalizedLabel, $normalizedValue) === 0;
                                });
                            }

                            // Method 5: Partial match by label (untuk typo atau extra spaces)
                            if (!$option) {
                                $option = $questionOptions->first(function($opt) use ($normalizedValue) {
                                    $normalizedLabel = trim((string)$opt->label);
                                    return stripos($normalizedLabel, $normalizedValue) !== false
                                        || stripos($normalizedValue, $normalizedLabel) !== false;
                                });
                            }

                            // Debug log if option not found
                            if (!$option) {
                                \Log::warning('Survey option not found (Admin)', [
                                    'pendaftar_id' => $pendaftar->id,
                                    'question_id' => $question->id,
                                    'pertanyaan' => $question->pertanyaan,
                                    'tipe_input' => $question->tipe_input,
                                    'looking_for_value' => $normalizedValue,
                                    'options_count' => $questionOptions->count(),
                                    'available_options' => $questionOptions->map(function($opt) {
                                        return ['opt_id' => $opt->id, 'question_id' => $opt->survey_question_id, 'label' => $opt->label, 'value' => $opt->value];
                                    })->toArray()
                                ]);
                            }

                            // Use label if found, otherwise show raw value with archived marker
                            // PRODUCTION FIX: Tampilkan raw value jika option tidak ketemu (happens when admin change/delete options)
                            if ($option) {
                                $jawabanLabels[] = $option->label;
                            } else {
                                // Show raw value with marker (data integrity issue)
                                $jawabanLabels[] = "{$normalizedValue} ⚠️";
                            }
                        }

                        $answers[$question->id] = !empty($jawabanLabels) ? implode(', ', $jawabanLabels) : '-';
                    } else {
                        // For text/textarea, show as is
                        $answers[$question->id] = is_array($jawaban) ? implode(', ', $jawaban) : $jawaban;
                    }
                }

                // Apply status survey filter before adding to results
                if ($this->filterStatusSurvey !== 'all') {
                    if ($this->filterStatusSurvey === 'sudah' && $surveyStatus !== 'Sudah Dijawab') {
                        continue;
                    }
                    if ($this->filterStatusSurvey === 'belum' && $surveyStatus !== 'Belum Dijawab') {
                        continue;
                    }
                }

                $surveyAnswersData[] = [
                    'pendaftar_id' => $pendaftar->id,
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                    'nisn' => $pendaftar->nisn ?? '-',
                    'nama_lengkap' => $pendaftar->nama_lengkap,
                    'jalur' => $pendaftar->jalurPendaftaran->nama ?? '-',
                    'jenis_kelamin' => $pendaftar->jenis_kelamin ?? '-',
                    'survey_status' => $surveyStatus,
                    'answers' => $answers
                ];
            }

            $dataPsikologi = $tesPsikologiData; // For pagination links
        }

        // Get setting for info box (available to both admin and panitia)
        $setting = TesPsikologiSetting::getSetting();

        // Get jalur list for filter dropdown
        $jalurList = \App\Models\JalurPendaftaran::aktif()->ordered()->get();

        return view('livewire.admin.tes-psikologi', compact('stats', 'dataPsikologi', 'setting', 'surveyAnswersData', 'surveyQuestions', 'jalurList'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Tes Psikologi',
                'pageDescription' => 'Kelola data dan pengaturan tes psikologi pendaftaran'
            ]);
    }
}
