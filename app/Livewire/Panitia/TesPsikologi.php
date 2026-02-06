<?php

namespace App\Livewire\Panitia;

use App\Models\TesPsikologiPendaftar;
use App\Models\TesPsikologiSetting;
use App\Models\Pendaftar;
use App\Models\RiwayatPesan;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class TesPsikologi extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public $mainTab = 'data'; // Main tab: data, survey, or jawaban-survey

    public $search = '';
    public $filterStatus = 'all';
    public $detailModal = false;
    public $selectedData = null;
    public $catatanVerifikasi = '';
    public $stats = [];
    public $isPanitiaPsikologi = false; // Flag untuk panitia tes psikologi

    // Survey properties
    public $surveyQuestions = [];

    // Survey CRUD properties
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

    // Survey Settings
    public $survey_aktif = false;
    public $survey_informasi;

    // Message modal (simple notification - legacy)
    public $showMessageModal = false;
    public $messagingId = null;
    public $messagingPendaftar = null;
    public $messageContent = '';

    // Template message modal (with template dropdown & auto-fill)
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedPendaftarId = null;
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Survey Answers Modal
    public $showSurveyAnswersModal = false;
    public $selectedPendaftar = null;
    public $pendaftarSurveyAnswers = [];

    public function mount()
    {
        // Check if user is panitia
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Detect panitia tes psikologi role
        $this->isPanitiaPsikologi = ($user->role === 'panitia_tes_psikologi');

        $this->loadStats();

        // Load survey dan settings hanya untuk panitia_tes_psikologi
        if ($this->isPanitiaPsikologi) {
            $this->loadSurvey();

            // Load survey settings
            $setting = TesPsikologiSetting::getSetting();
            $this->survey_aktif = $setting->survey_aktif ?? false;
            $this->survey_informasi = $setting->survey_informasi;
        }
    }

    public function loadStats()
    {
        $total = TesPsikologiPendaftar::count();
        $menunggu = TesPsikologiPendaftar::where('status_verifikasi', 'menunggu')->count();
        $disetujui = TesPsikologiPendaftar::where('status_verifikasi', 'disetujui')->count();
        $ditolak = TesPsikologiPendaftar::where('status_verifikasi', 'ditolak')->count();
        $perbaiki = TesPsikologiPendaftar::where('status_verifikasi', 'perbaiki')->count();
        $surveyDijawab = SurveyAnswer::whereHas('question', function($q) {
                $q->tesPsikologi();
            })
            ->distinct('pendaftar_id')
            ->count('pendaftar_id');

        $this->stats = [
            'total' => $total,
            'menunggu' => $menunggu,
            'disetujui' => $disetujui,
            'ditolak' => $ditolak,
            'perbaiki' => $perbaiki,
            'survey_dijawab' => $surveyDijawab,
        ];
    }

    public function loadSurvey()
    {
        // Load survey questions dengan context 'tes_psikologi'
        $this->surveyList = SurveyQuestion::tesPsikologi()->with('options')->ordered()->get();
        $this->surveyQuestions = $this->surveyList; // Untuk backward compatibility
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetail($id)
    {
        $this->selectedData = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy'])->find($id);
        $this->catatanVerifikasi = $this->selectedData->catatan_verifikasi ?? '';
        $this->detailModal = true;
    }

    public function closeDetail()
    {
        $this->detailModal = false;
        $this->selectedData = null;
        $this->catatanVerifikasi = '';
    }

    public function setujui($id)
    {
        $data = TesPsikologiPendaftar::find($id);
        $data->update([
            'status_verifikasi' => 'disetujui',
            'catatan_verifikasi' => $this->catatanVerifikasi,
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id()
        ]);

        session()->flash('message', 'Bukti transfer berhasil disetujui');
        $this->closeDetail();
    }

    public function tolak($id)
    {
        $data = TesPsikologiPendaftar::find($id);
        $data->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $this->catatanVerifikasi,
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id()
        ]);

        session()->flash('message', 'Bukti transfer ditolak');
        $this->closeDetail();
    }

    // Message Methods
    public function sendEmail($id)
    {
        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($id);

        // TODO: Implement email sending logic here
        // Example: Mail::to($tesPsikologi->pendaftar->user->email)->send(new TesPsikologiEmail($tesPsikologi));

        // Save to message history
        \App\Models\MessageHistory::create([
            'pendaftar_id' => $tesPsikologi->pendaftar_id,
            'sender_id' => auth()->id(),
            'message_type' => 'email',
            'context' => 'tes_psikologi',
            'message_content' => 'Email dikirim untuk tes psikologi',
            'sent_at' => now(),
        ]);

        session()->flash('message', 'Email berhasil dikirim ke ' . $tesPsikologi->pendaftar->nama_lengkap);
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
            'user_id' => auth()->id(),
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

    /**
     * Lifecycle hook: When template selection changes, auto-load template content
     */
    public function updatedSelectedMessageTemplate()
    {
        $this->loadMessageTemplate();
    }

    /**
     * Lifecycle hook: When message type changes, reset template selection
     */
    public function updatedMessageType()
    {
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
    }

    /**
     * Load message template content based on selected template
     * Auto-replace 27 placeholders with pendaftar data
     */
    public function loadMessageTemplate()
    {
        if (!$this->selectedMessageTemplate || !$this->selectedPendaftarId) {
            return;
        }

        $tesPsikologi = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank'])
            ->findOrFail($this->selectedPendaftarId);

        $pendaftar = $tesPsikologi->pendaftar;
        $templateContent = $this->getTemplateContent($this->selectedMessageTemplate);

        // Define all 27 placeholders
        $placeholders = [
            '{nama}' => $pendaftar->nama_lengkap ?? '',
            '{nisn}' => $pendaftar->nisn ?? '',
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran ?? '',
            '{email}' => $pendaftar->email ?? '',
            '{no_hp}' => $pendaftar->no_hp ?? '',
            '{jalur}' => $pendaftar->jalur->nama_jalur ?? '',
            '{asal_sekolah}' => $pendaftar->asalSekolah->nama_sekolah ?? '',
            '{nilai_un}' => $pendaftar->nilai_un ?? '',
            '{rata_rata_raport}' => $pendaftar->rata_rata_raport ?? '',
            '{total_nilai}' => $pendaftar->total_nilai ?? '',
            '{tanggal_daftar}' => $pendaftar->created_at ? $pendaftar->created_at->format('d F Y') : '',
            '{status}' => ucfirst($pendaftar->status ?? ''),
            '{status_verifikasi}' => ucfirst($pendaftar->status_verifikasi ?? ''),
            '{tanggal_verifikasi}' => $pendaftar->tanggal_verifikasi ? $pendaftar->tanggal_verifikasi->format('d F Y') : '',
            '{nama_verifikator}' => $pendaftar->verifiedBy->name ?? '',
            '{catatan_verifikasi}' => $pendaftar->catatan_verifikasi ?? '',
            '{status_kelulusan}' => ucfirst($pendaftar->status_kelulusan ?? ''),
            '{tanggal_pengumuman}' => $pendaftar->tanggal_pengumuman ? $pendaftar->tanggal_pengumuman->format('d F Y') : '',
            '{link_pengumuman}' => url('/kelulusan/' . ($pendaftar->nomor_pendaftaran ?? '')),
            '{link_dashboard}' => url('/pendaftar/dashboard'),
            '{link_daftar_ulang}' => url('/pendaftar/daftar-ulang'),
            '{link_dokumen}' => url('/pendaftar/dokumen'),
            '{kontak_wa}' => '081234567890', // Sesuaikan dengan nomor WA admin
            '{email_admin}' => 'admin@mtsn2kotamalang.sch.id',
            '{nama_sekolah}' => 'MTsN 2 Kota Malang',
            '{website}' => url('/'),
            '{catatan}' => $this->messageNote ?? '',
        ];

        // Replace all placeholders
        $processedContent = str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $templateContent
        );

        // Set subject and body
        if ($this->messageType === 'email') {
            $this->messageSubject = $this->getTemplateSubject($this->selectedMessageTemplate);
        }
        $this->messageBody = $processedContent;
    }

    /**
     * Get template content by template key
     */
    private function getTemplateContent($templateKey)
    {
        $templates = [
            // Verifikasi Transfer Templates
            'verifikasi_transfer_disetujui' => "Assalamu'alaikum {nama},\n\nKami informasikan bahwa bukti transfer tes psikologi Anda telah DISETUJUI.\n\nNomor Pendaftaran: {nomor_pendaftaran}\nStatus: Pembayaran Terverifikasi ✅\n\nSilakan ikuti tes psikologi sesuai jadwal yang telah ditentukan.\n\nTerima kasih.\n\nSalam,\nPanitia PPDB {nama_sekolah}",

            'verifikasi_transfer_ditolak' => "Assalamu'alaikum {nama},\n\nKami informasikan bahwa bukti transfer tes psikologi Anda DITOLAK.\n\nNomor Pendaftaran: {nomor_pendaftaran}\nAlasan: {catatan}\n\nSilakan upload ulang bukti transfer yang benar melalui dashboard Anda: {link_dashboard}\n\nJika ada pertanyaan, hubungi kami di:\nWhatsApp: {kontak_wa}\nEmail: {email_admin}\n\nTerima kasih.\n\nSalam,\nPanitia PPDB {nama_sekolah}",

            'verifikasi_transfer_perbaiki' => "Assalamu'alaikum {nama},\n\nKami informasikan bahwa bukti transfer tes psikologi Anda perlu DIPERBAIKI.\n\nNomor Pendaftaran: {nomor_pendaftaran}\nCatatan: {catatan}\n\nSilakan perbaiki dan upload ulang bukti transfer melalui dashboard Anda: {link_dashboard}\n\nJika ada pertanyaan, hubungi kami di:\nWhatsApp: {kontak_wa}\nEmail: {email_admin}\n\nTerima kasih.\n\nSalam,\nPanitia PPDB {nama_sekolah}",
        ];

        return $templates[$templateKey] ?? '';
    }

    /**
     * Get email subject by template key
     */
    private function getTemplateSubject($templateKey)
    {
        $subjects = [
            'verifikasi_transfer_disetujui' => 'Bukti Transfer Tes Psikologi Disetujui',
            'verifikasi_transfer_ditolak' => 'Bukti Transfer Tes Psikologi Ditolak',
            'verifikasi_transfer_perbaiki' => 'Bukti Transfer Tes Psikologi Perlu Perbaikan',
        ];

        return $subjects[$templateKey] ?? 'Pesan dari Panitia PPDB';
    }

    /**
     * Open send message modal with template support
     */
    public function openSendMessageModal($id, $type = 'email')
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = $type;
        $this->isSendMessageModalOpen = true;
        $this->resetTemplateForm();
    }

    /**
     * Send message using selected template
     */
    public function sendMessageTemplate()
    {
        $this->validate([
            'messageBody' => 'required|string|max:5000',
            'messageSubject' => $this->messageType === 'email' ? 'required|string|max:255' : '',
        ], [
            'messageBody.required' => 'Isi pesan harus diisi',
            'messageBody.max' => 'Isi pesan maksimal 5000 karakter',
            'messageSubject.required' => 'Subject email harus diisi',
            'messageSubject.max' => 'Subject maksimal 255 karakter',
        ]);

        $tesPsikologi = TesPsikologiPendaftar::with('pendaftar')->findOrFail($this->selectedPendaftarId);
        $pendaftar = $tesPsikologi->pendaftar;

        // Prepare recipient
        $penerima = $this->messageType === 'email'
            ? ($pendaftar->email ?? 'no-email')
            : ($pendaftar->no_hp ?? 'no-phone');

        // Create RiwayatPesan log with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $pendaftar->id,
            'user_id' => auth()->id(),
            'jenis_pesan' => $this->messageType,
            'judul' => $this->messageType === 'email' ? $this->messageSubject : 'WhatsApp Message',
            'isi_pesan' => $this->messageBody,
            'penerima' => $penerima,
            'template_digunakan' => $this->selectedMessageTemplate,
            'status' => 'pending',
        ]);

        try {
            if ($this->messageType === 'email') {
                // Send Email
                \Mail::to($pendaftar->email)->send(
                    new \App\Mail\ManualMessageMail(
                        $this->messageSubject,
                        $this->messageBody,
                        $pendaftar->nama_lengkap
                    )
                );
            } else {
                // Send WhatsApp (implement your WhatsApp API here)
                // Example: $this->sendWhatsApp($pendaftar->no_hp, $this->messageBody);
            }

            // Update log to success
            $riwayatPesan->update([
                'status' => 'terkirim',
                'sent_at' => now(),
            ]);

            session()->flash('message', ucfirst($this->messageType) . ' berhasil dikirim ke ' . $pendaftar->nama_lengkap);
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

    /**
     * Close send message modal and reset form
     */
    public function closeSendMessageModal()
    {
        $this->isSendMessageModalOpen = false;
        $this->resetTemplateForm();
    }

    /**
     * Reset template form fields
     */
    private function resetTemplateForm()
    {
        $this->selectedPendaftarId = null;
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->resetValidation();
    }

    // Survey Answers Modal Methods
    public function openSurveyAnswersModal($pendaftarId)
    {
        $this->selectedPendaftar = Pendaftar::find($pendaftarId);

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

    public function changeStatus($id, $status)
    {
        if (empty($status)) {
            return;
        }

        $data = TesPsikologiPendaftar::findOrFail($id);
        $data->update([
            'status_verifikasi' => $status,
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id()
        ]);

        session()->flash('message', 'Status berhasil diubah menjadi ' . $status);
        $this->loadStats(); // Refresh stats
    }

    // ========== SURVEY CRUD METHODS ==========

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
        // Filter by context untuk memastikan hanya edit survey tes psikologi
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

        $setting = TesPsikologiSetting::getSetting();
        $setting->update([
            'survey_aktif' => $this->survey_aktif,
            'survey_informasi' => $this->survey_informasi
        ]);

        session()->flash('message', 'Pengaturan survey berhasil disimpan');
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

    public function render()
    {
        // Conditionally load data based on mainTab
        $dataPsikologi = collect();
        if ($this->mainTab === 'data') {
            $query = TesPsikologiPendaftar::with(['pendaftar', 'opsi', 'bank', 'verifiedBy'])
                ->whereHas('pendaftar', function($q) {
                    if ($this->search) {
                        $q->where('nama_lengkap', 'ilike', '%'.$this->search.'%')
                          ->orWhere('nisn', 'ilike', '%'.$this->search.'%')
                          ->orWhere('nomor_pendaftaran', 'ilike', '%'.$this->search.'%');
                    }
                });

            // Filter by status
            if ($this->filterStatus && $this->filterStatus !== 'all') {
                $query->where('status_verifikasi', $this->filterStatus);
            }

            $dataPsikologi = $query->latest()->paginate(20);
        }

        // Get survey answers data for jawaban-survey tab (only for panitia_tes_psikologi)
        $surveyAnswersData = [];
        $surveyQuestionsForAnswers = [];
        if ($this->mainTab === 'jawaban-survey' && $this->isPanitiaPsikologi) {
            // Get all active survey questions for tes psikologi (with options for label conversion)
            $surveyQuestionsForAnswers = SurveyQuestion::tesPsikologi()
                ->aktif()
                ->ordered()
                ->with('options')
                ->get();

            // Get all pendaftar who registered for tes psikologi
            $query = TesPsikologiPendaftar::with(['pendaftar'])
                ->whereHas('pendaftar');

            // Apply search filter
            if ($this->search) {
                $query->whereHas('pendaftar', function($q) {
                    $q->where('nama_lengkap', 'ilike', '%'.$this->search.'%')
                      ->orWhere('nisn', 'ilike', '%'.$this->search.'%')
                      ->orWhere('nomor_pendaftaran', 'ilike', '%'.$this->search.'%');
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
                foreach ($surveyQuestionsForAnswers as $question) {
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
                        $jawabanArray = is_array($jawaban) ? $jawaban : [$jawaban];

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
                                \Log::warning('Survey option not found (Panitia)', [
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

                $surveyAnswersData[] = [
                    'pendaftar_id' => $pendaftar->id,
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                    'nisn' => $pendaftar->nisn ?? '-',
                    'nama_lengkap' => $pendaftar->nama_lengkap,
                    'survey_status' => $surveyStatus,
                    'answers' => $answers
                ];
            }

            $dataPsikologi = $tesPsikologiData; // For pagination links
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        // Get setting for info box
        $setting = TesPsikologiSetting::getSetting();

        return view('livewire.panitia.tes-psikologi', [
            'dataPsikologi' => $dataPsikologi,
            'setting' => $setting,
            'surveyQuestions' => $this->surveyQuestions,
            'isPanitiaPsikologi' => $this->isPanitiaPsikologi,
            'surveyAnswersData' => $surveyAnswersData,
            'surveyQuestionsForAnswers' => $surveyQuestionsForAnswers
        ])->layout('components.layouts.dashboard', [
            'role' => $user->role,
            'pageTitle' => 'Data Tes Psikologi',
            'pageDescription' => 'Verifikasi bukti transfer tes psikologi pendaftar'
        ]);
    }
}
