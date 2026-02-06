<?php

namespace App\Livewire\Pendaftar;

use App\Models\TesPsikologiSetting;
use App\Models\TesPsikologiOpsi;
use App\Models\TesPsikologiBank;
use App\Models\TesPsikologiPendaftar;
use App\Models\Setting;
use App\Models\SurveyQuestion;
use App\Models\SurveyAnswer;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Barryvdh\DomPDF\Facade\Pdf;

class TesPsikologi extends Component
{
    use WithFileUploads;

    public $setting;
    public $opsiList;
    public $bankList;
    public $tesPsikologiData;
    public $pendaftar;

    public $selectedBank;
    public $selectedOpsi;
    public $waktuTransfer;
    public $namaPengirim;
    public $buktiTransfer;
    public $uploadProgress = false;

    // Survey properties
    public $surveyQuestions;
    public $surveyAnswers = [];
    public $surveyCompleted = false;
    public $currentQuestionIndex = 0; // For wizard-style survey

    /**
     * Check apakah pertanyaan current sudah dijawab
     * Computed property dengan #[Computed] attribute untuk Livewire 3
     */
    #[Computed]
    public function isCurrentQuestionAnswered()
    {
        if (!$this->surveyQuestions || $this->currentQuestionIndex >= $this->surveyQuestions->count()) {
            return false;
        }

        $currentQuestion = $this->surveyQuestions[$this->currentQuestionIndex];

        // Jika tidak wajib, selalu return true (boleh skip)
        if (!$currentQuestion->wajib_diisi) {
            return true;
        }

        // Check apakah ada jawaban
        $answer = $this->surveyAnswers[$currentQuestion->id] ?? null;

        // Empty check untuk berbagai tipe jawaban
        if (empty($answer)) {
            return false;
        }

        // Array check (untuk checkbox)
        if (is_array($answer) && count($answer) === 0) {
            return false;
        }

        // String check (untuk text/textarea/select yang value kosong)
        if (is_string($answer) && trim($answer) === '') {
            return false;
        }

        return true;
    }

    /**
     * Check apakah semua pertanyaan wajib sudah dijawab
     * Computed property dengan #[Computed] attribute untuk Livewire 3
     */
    #[Computed]
    public function areAllRequiredQuestionsAnswered()
    {
        if (!$this->surveyQuestions) {
            return false;
        }

        foreach ($this->surveyQuestions as $question) {
            if ($question->wajib_diisi) {
                $answer = $this->surveyAnswers[$question->id] ?? null;

                // Empty checks
                if (empty($answer)) {
                    return false;
                }

                if (is_array($answer) && count($answer) === 0) {
                    return false;
                }

                if (is_string($answer) && trim($answer) === '') {
                    return false;
                }
            }
        }

        return true;
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;
        if (!$this->pendaftar) {
            return redirect()->to('/daftar');
        }

        $this->setting = TesPsikologiSetting::getSetting();

        // Check if tes psikologi is active
        if (!$this->setting->aktif) {
            session()->flash('error', 'Fitur tes psikologi saat ini tidak aktif');
            return redirect()->to('/pendaftar/dashboard');
        }

        $this->opsiList = TesPsikologiOpsi::aktif()->ordered()->get();
        $this->bankList = TesPsikologiBank::aktif()->ordered()->get();

        // Get or create tes psikologi data
        $this->tesPsikologiData = TesPsikologiPendaftar::firstOrCreate(
            ['pendaftar_id' => $this->pendaftar->id],
            ['status_verifikasi' => 'belum_upload']
        );

        $this->selectedBank = $this->tesPsikologiData->bank_id;
        $this->selectedOpsi = $this->tesPsikologiData->opsi_id;
        $this->waktuTransfer = $this->tesPsikologiData->waktu_transfer ? $this->tesPsikologiData->waktu_transfer->format('Y-m-d\TH:i') : null;
        $this->namaPengirim = $this->tesPsikologiData->nama_pengirim;

        // Load survey if active
        if ($this->setting->survey_aktif) {
            // PENTING: Filter by context 'tes_psikologi' untuk Survey Tes Psikologi
            $this->surveyQuestions = SurveyQuestion::tesPsikologi()->with('options')->aktif()->ordered()->get();

            // Load existing answers - PENTING: Filter hanya jawaban untuk survey tes psikologi
            $existingAnswers = SurveyAnswer::where('pendaftar_id', $this->pendaftar->id)
                ->whereHas('question', function($q) {
                    $q->tesPsikologi();
                })
                ->get();

            foreach ($existingAnswers as $answer) {
                $jawaban = $answer->jawaban;

                // CRITICAL FIX: Decode JSON string for checkbox arrays
                // Database stores checkbox as JSON string "[10,12]"
                // But Livewire checkbox needs PHP array [10, 12]
                if (is_string($jawaban) && (str_starts_with($jawaban, '[') || str_starts_with($jawaban, '{'))) {
                    $decoded = json_decode($jawaban, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $jawaban = $decoded;
                    }
                }

                $this->surveyAnswers[$answer->survey_question_id] = $jawaban;
            }

            // CRITICAL FIX: Initialize checkbox questions dengan array kosong jika belum ada jawaban
            // Ini penting agar Livewire wire:model tahu bahwa ini adalah multi-select checkbox
            foreach ($this->surveyQuestions as $question) {
                if ($question->tipe_input === 'checkbox' && !isset($this->surveyAnswers[$question->id])) {
                    $this->surveyAnswers[$question->id] = [];
                }
            }

            // Check if survey is completed
            $this->checkSurveyCompletion();
        } else {
            $this->surveyCompleted = true; // If survey not active, consider it completed
        }
    }

    public function checkSurveyCompletion()
    {
        if (!$this->setting->survey_aktif || !$this->surveyQuestions) {
            $this->surveyCompleted = true;
            return;
        }

        // Check if all required questions are answered
        foreach ($this->surveyQuestions as $question) {
            if ($question->wajib_diisi) {
                $answer = $this->surveyAnswers[$question->id] ?? null;

                if (empty($answer)) {
                    $this->surveyCompleted = false;
                    return;
                }

                // For checkbox, answer is array - check if not empty
                if (is_array($answer) && count($answer) === 0) {
                    $this->surveyCompleted = false;
                    return;
                }
            }
        }

        $this->surveyCompleted = true;
    }

    /**
     * Move to next question
     */
    public function nextQuestion()
    {
        $currentQuestion = $this->surveyQuestions[$this->currentQuestionIndex];

        // Validate current question if required
        if ($currentQuestion->wajib_diisi) {
            $answer = $this->surveyAnswers[$currentQuestion->id] ?? null;

            if (empty($answer) || (is_array($answer) && count($answer) === 0)) {
                session()->flash('error', 'Pertanyaan ini wajib diisi');
                return;
            }
        }

        // Save current answer immediately
        $answer = $this->surveyAnswers[$currentQuestion->id] ?? null;
        if (!empty($answer)) {
            \Log::info("nextQuestion: Q{$currentQuestion->id} = " . json_encode($answer));

            // CRITICAL FIX: Different storage format for different input types
            if (in_array($currentQuestion->tipe_input, ['radio', 'select', 'text', 'textarea'])) {
                // Single value - store as-is (NOT as array)
                $answerValue = is_array($answer) ? ($answer[0] ?? null) : $answer;
                \Log::info("nextQuestion: Q{$currentQuestion->id} single value = {$answerValue}");
            } else {
                // Checkbox - store as array
                $answerValue = is_array($answer) ? $answer : [$answer];

                // CRITICAL FIX: Filter out boolean values dari checkbox array
                // Livewire kadang inject boolean true ke array checkbox
                $answerValue = array_filter($answerValue, function($val) {
                    return !is_bool($val) && $val !== '' && $val !== null;
                });

                // Re-index array (remove gaps dari filter)
                $answerValue = array_values($answerValue);
                \Log::info("nextQuestion: Q{$currentQuestion->id} array value = " . json_encode($answerValue));
            }

            // Only save if we have valid data
            if (!empty($answerValue) && $answerValue !== null) {
                \Log::info("nextQuestion: Saving Q{$currentQuestion->id} to DB");

                // CRITICAL FIX: JSON encode arrays (checkbox) before saving to TEXT column
                // - Checkbox answers are arrays → need JSON encoding
                // - Radio/select/text answers are strings → save as-is
                $jawabanToSave = is_array($answerValue) ? json_encode($answerValue) : $answerValue;

                SurveyAnswer::updateOrCreate(
                    [
                        'pendaftar_id' => $this->pendaftar->id,
                        'survey_question_id' => $currentQuestion->id
                    ],
                    [
                        'jawaban' => $jawabanToSave
                    ]
                );
                \Log::info("nextQuestion: Q{$currentQuestion->id} ✓ Saved");
            }
        }

        // Move to next question
        if ($this->currentQuestionIndex < count($this->surveyQuestions) - 1) {
            $this->currentQuestionIndex++;
        }

        // Clear flash messages
        session()->forget('error');
    }

    /**
     * Move to previous question
     */
    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }

        // Clear flash messages
        session()->forget('error');
    }

    /**
     * Complete survey - called on last question
     *
     * CRITICAL FIX 7 FEB 2026: SAVE CURRENT (LAST) QUESTION ONLY
     * Trust nextQuestion() for all previous saves - prevents data corruption
     */
    public function simpanSurvey()
    {
        if (!$this->setting->survey_aktif) {
            session()->flash('error', 'Survey tidak aktif');
            return;
        }

        // Validate all required questions are answered
        $validationRules = [];
        $validationMessages = [];

        foreach ($this->surveyQuestions as $question) {
            if ($question->wajib_diisi) {
                $validationRules["surveyAnswers.{$question->id}"] = 'required';
                $validationMessages["surveyAnswers.{$question->id}.required"] = "Pertanyaan '{$question->pertanyaan}' wajib diisi.";
            }
        }

        // Validate if there are required questions
        if (!empty($validationRules)) {
            try {
                $this->validate($validationRules, $validationMessages);
            } catch (\Illuminate\Validation\ValidationException $e) {
                session()->flash('error', 'Harap lengkapi semua pertanyaan wajib sebelum menyelesaikan survey');
                return;
            }
        }

        try {
            \Log::info('=== SIMPAN SURVEY (FINAL - CURRENT QUESTION ONLY) START ===');
            \Log::info('Pendaftar ID: ' . $this->pendaftar->id);
            \Log::info('Current Question Index: ' . $this->currentQuestionIndex);

            // CRITICAL FIX: Save current question using EXACT SAME logic as nextQuestion()
            // This ensures 100% consistent behavior
            $currentQuestion = $this->surveyQuestions[$this->currentQuestionIndex];

            // Save current answer immediately (EXACT COPY from nextQuestion)
            $answer = $this->surveyAnswers[$currentQuestion->id] ?? null;
            if (!empty($answer)) {
                \Log::info("simpanSurvey: Q{$currentQuestion->id} = " . json_encode($answer));

                // CRITICAL FIX: Different storage format for different input types
                if (in_array($currentQuestion->tipe_input, ['radio', 'select', 'text', 'textarea'])) {
                    // Single value - store as-is (NOT as array)
                    $answerValue = is_array($answer) ? ($answer[0] ?? null) : $answer;
                    \Log::info("simpanSurvey: Q{$currentQuestion->id} single value = {$answerValue}");
                } else {
                    // Checkbox - store as array
                    $answerValue = is_array($answer) ? $answer : [$answer];

                    // CRITICAL FIX: Filter out boolean values dari checkbox array
                    // Livewire kadang inject boolean true ke array checkbox
                    $answerValue = array_filter($answerValue, function($val) {
                        return !is_bool($val) && $val !== '' && $val !== null;
                    });

                    // Re-index array (remove gaps dari filter)
                    $answerValue = array_values($answerValue);
                    \Log::info("simpanSurvey: Q{$currentQuestion->id} array value = " . json_encode($answerValue));
                }

                // Only save if we have valid data
                if (!empty($answerValue) && $answerValue !== null) {
                    \Log::info("simpanSurvey: Saving Q{$currentQuestion->id} to DB");

                    // CRITICAL FIX: JSON encode arrays (checkbox) before saving to TEXT column
                    // - Checkbox answers are arrays → need JSON encoding
                    // - Radio/select/text answers are strings → save as-is
                    $jawabanToSave = is_array($answerValue) ? json_encode($answerValue) : $answerValue;

                    SurveyAnswer::updateOrCreate(
                        [
                            'pendaftar_id' => $this->pendaftar->id,
                            'survey_question_id' => $currentQuestion->id
                        ],
                        [
                            'jawaban' => $jawabanToSave
                        ]
                    );
                    \Log::info("simpanSurvey: Q{$currentQuestion->id} ✓ Saved");
                }
            }

            \Log::info('=== SIMPAN SURVEY (FINAL) END ===');

            // Mark survey as complete
            $this->checkSurveyCompletion();

            // Refresh component to update UI
            $this->dispatch('survey-saved');

            session()->flash('message', 'Survey berhasil disimpan. Anda sekarang dapat melanjutkan ke upload bukti transfer.');
        } catch (\Exception $e) {
            \Log::error('simpanSurvey ERROR: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan survey: ' . $e->getMessage());
        }
    }

    public function uploadBukti()
    {
        // Reset upload progress state
        $this->uploadProgress = false;

        // Check survey completion first if survey is active
        if ($this->setting->survey_aktif && !$this->surveyCompleted) {
            session()->flash('error', 'Harap lengkapi survey terlebih dahulu sebelum upload bukti pembayaran');
            return;
        }

        try {
            $this->validate([
                'selectedOpsi' => 'required|exists:tes_psikologi_opsi,id',
                'waktuTransfer' => 'required|date',
                'namaPengirim' => 'required|string|max:255',
                'selectedBank' => 'required|exists:tes_psikologi_bank,id',
                'buktiTransfer' => 'required|image|max:2048'
            ], [
                'selectedOpsi.required' => 'Pilih biaya tes psikologi terlebih dahulu',
                'waktuTransfer.required' => 'Waktu transfer harus diisi',
                'waktuTransfer.date' => 'Format waktu transfer tidak valid',
                'namaPengirim.required' => 'Nama pengirim harus diisi',
                'namaPengirim.string' => 'Nama pengirim harus berupa teks',
                'namaPengirim.max' => 'Nama pengirim maksimal 255 karakter',
                'selectedBank.required' => 'Pilih bank terlebih dahulu',
                'buktiTransfer.required' => 'Upload bukti transfer terlebih dahulu',
                'buktiTransfer.image' => 'File harus berupa gambar',
                'buktiTransfer.max' => 'Ukuran file maksimal 2MB'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Reset progress on validation error
            $this->uploadProgress = false;
            throw $e;
        }

        $this->uploadProgress = true;

        try {
            // Delete old file if exists
            if ($this->tesPsikologiData->bukti_transfer) {
                Storage::disk('private')->delete($this->tesPsikologiData->bukti_transfer);
            }

            // Generate nama file sistematis: bukti_psikologi-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.{ext}
            $extension = FileNamingService::getFileExtension($this->buktiTransfer);
            $customFileName = FileNamingService::generateBuktiBayarFileName(
                'psikologi',
                $this->pendaftar->nomor_pendaftaran,
                $extension,
                $this->pendaftar->nama_lengkap
            );

            // Simpan ke private storage untuk keamanan dokumen keuangan
            $path = $this->buktiTransfer->storeAs(
                'bukti_bayar/' . $this->pendaftar->id,
                $customFileName,
                'private'
            );

            // Update data
            $this->tesPsikologiData->update([
                'opsi_id' => $this->selectedOpsi,
                'waktu_transfer' => $this->waktuTransfer,
                'nama_pengirim' => $this->namaPengirim,
                'bank_id' => $this->selectedBank,
                'bukti_transfer' => $path,
                'tanggal_upload' => now(),
                'status_verifikasi' => 'menunggu'
            ]);

            session()->flash('message', 'Bukti transfer berhasil diupload. Menunggu verifikasi panitia.');

            // Refresh data
            $this->tesPsikologiData = $this->tesPsikologiData->fresh();
            $this->buktiTransfer = null;
            $this->uploadProgress = false;

            // Dispatch event to ensure UI update
            $this->dispatch('upload-complete');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->uploadProgress = false;

            // Dispatch event on error too
            $this->dispatch('upload-error');
        }
    }

    public function hapusBukti()
    {
        if ($this->tesPsikologiData->status_verifikasi === 'disetujui') {
            session()->flash('error', 'Tidak dapat menghapus bukti yang sudah disetujui');
            return;
        }

        // Delete file from private storage
        if ($this->tesPsikologiData->bukti_transfer) {
            Storage::disk('private')->delete($this->tesPsikologiData->bukti_transfer);
        }

        // Reset data
        $this->tesPsikologiData->update([
            'opsi_id' => null,
            'waktu_transfer' => null,
            'nama_pengirim' => null,
            'bank_id' => null,
            'bukti_transfer' => null,
            'tanggal_upload' => null,
            'status_verifikasi' => 'belum_upload',
            'catatan_verifikasi' => null,
            'tanggal_verifikasi' => null,
            'verified_by' => null
        ]);

        session()->flash('message', 'Bukti transfer berhasil dihapus');
        $this->tesPsikologiData = $this->tesPsikologiData->fresh();
        $this->selectedBank = null;
        $this->selectedOpsi = null;
        $this->waktuTransfer = null;
        $this->namaPengirim = null;
    }

    public function downloadInvoice()
    {
        if ($this->tesPsikologiData->status_verifikasi !== 'disetujui') {
            return;
        }

        $pdf = $this->generateInvoicePdf();
        $filename = 'invoice-tes-psikologi-' . $this->pendaftar->nomor_pendaftaran . '-' . Str::slug($this->pendaftar->nama_lengkap) . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function previewInvoice()
    {
        if (!$this->pendaftar) {
            $user = Auth::user();
            if (!$user || $user->role !== 'pendaftar') {
                abort(403);
            }
            $this->pendaftar = $user->pendaftar;
        }

        if (!$this->tesPsikologiData) {
            $this->tesPsikologiData = TesPsikologiPendaftar::where('pendaftar_id', $this->pendaftar->id)->first();
        }

        if (!$this->tesPsikologiData || $this->tesPsikologiData->status_verifikasi !== 'disetujui') {
            abort(403, 'Invoice hanya tersedia untuk pembayaran yang sudah disetujui.');
        }

        $pdf = $this->generateInvoicePdf();
        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-tes-psikologi.pdf"',
        ]);
    }

    private function generateInvoicePdf()
    {
        $printSettings = [
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
        ];

        $invoiceSettings = [
            'nomor_format' => Setting::get('invoice_psikologi_nomor_format', 'INV-PSI-{TAHUN}-{NOMOR}'),
            'footer' => Setting::get('invoice_psikologi_footer', ''),
        ];

        $opsi = TesPsikologiOpsi::find($this->tesPsikologiData->opsi_id);
        $bank = TesPsikologiBank::find($this->tesPsikologiData->bank_id);

        $pdf = Pdf::loadView('pdf.invoice-psikologi', [
            'pendaftar' => $this->pendaftar,
            'tesPsikologiData' => $this->tesPsikologiData,
            'printSettings' => $printSettings,
            'invoiceSettings' => $invoiceSettings,
            'opsi' => $opsi,
            'bank' => $bank,
        ]);

        $pdf->setPaper('A5', 'landscape');

        return $pdf;
    }

    /**
     * Convert uploaded image to WebP format and store it
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string Path to stored file
     */
    private function convertAndStoreAsWebp($file, $folder)
    {
        // Generate unique filename
        $filename = Str::random(40) . '.webp';
        $storagePath = storage_path('app/public/' . $folder);

        // Create directory if not exists
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $fullPath = $storagePath . '/' . $filename;

        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        $mimeType = $imageInfo['mime'];

        // Create image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'image/png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }

        // Convert to WebP with quality 80
        imagewebp($image, $fullPath, 80);

        // Free memory
        imagedestroy($image);

        // Return relative path
        return $folder . '/' . $filename;
    }

    public function render()
    {
        return view('livewire.pendaftar.tes-psikologi')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Tes Psikologi',
                'pageDescription' => 'Upload bukti pembayaran tes psikologi'
            ]);
    }
}
