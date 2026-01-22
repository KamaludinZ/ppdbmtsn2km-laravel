<?php

namespace App\Livewire\Auth;

use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Setting;
use App\Services\EmailService;
use App\Services\WhatsAppGatewayService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class RegisterFixed extends Component
{
    use WithFileUploads;

    // Multi-step tracker
    public $currentStep = 1;
    public $totalSteps = 3;

    // Step 1: Pilih Jalur
    public $jalur_pendaftaran_id = null;
    public $selectedJalur = null;
    public $hasSelectedJalur = false;

    // Step 2: Nilai Rapor
    public $nilai_b_indo_5_1 = null;
    public $nilai_matematika_5_1 = null;
    public $nilai_ipa_5_1 = null;
    public $nilai_b_indo_5_2 = null;
    public $nilai_matematika_5_2 = null;
    public $nilai_ipa_5_2 = null;
    public $nilai_b_indo_6_1 = null;
    public $nilai_matematika_6_1 = null;
    public $nilai_ipa_6_1 = null;
    public $file_rapor = null;
    public $rata_rata_nilai = 0;

    // Step 3: Validasi
    public $isValid = false;
    public $validationMessage = '';

    // Step 3: Data Pribadi
    public $nisn = '';
    public $nama_lengkap = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = '';
    public $agama = 'Islam';
    public $alamat = '';
    public $telepon_ortu = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    // Data Sekolah Asal
    public $jenis_sekolah_asal = '';
    public $nsm_sekolah_asal = '';
    public $npsn_sekolah_asal = '';
    public $asal_sekolah = '';

    // Loading states untuk UI feedback
    public $isProcessing = false;

    // Real-time validation messages
    public $emailValidationMessage = '';
    public $nisnValidationMessage = '';

    // Cache untuk validation
    private $nisnCountCache = [];
    private $emailCountCache = [];

    // Modal confirmation
    public $showConfirmationModal = false;

    protected $rules = [
        'jalur_pendaftaran_id' => 'nullable|exists:jalur_pendaftaran,id',
    ];

    protected $messages = [
        'jalur_pendaftaran_id.required' => 'Silakan pilih jalur pendaftaran terlebih dahulu',
        'jalur_pendaftaran_id.exists' => 'Jalur pendaftaran tidak valid',
    ];

    /**
     * Mount component
     */
    public function mount()
    {
        // Check if registration is open
        $isOpen = Cache::remember('registration_is_open', 60, function () {
            return Setting::isRegistrationOpen();
        });

        if (!$isOpen) {
            session()->flash('error', 'Pendaftaran belum dibuka atau sudah ditutup');
            return redirect()->to('/');
        }

        // Check and clear old session data (24+ hours old)
        $this->clearOldSessionIfNeeded();

        // Generate unique session ID for this registration attempt
        if (!session()->has('registration_session_id')) {
            session(['registration_session_id' => uniqid('reg_', true)]);
        }

        // Initialize
        $this->currentStep = 1;
        $this->resetFormData();

        // Restore data from session if exists (for retry attempts)
        $this->restoreFromSession();
    }

    /**
     * Computed property untuk jalur pendaftaran
     * Selalu load fresh data untuk avoid serialization issues
     */
    public function getJalurPendaftaranProperty()
    {
        // Load fresh data from database with counts
        return JalurPendaftaran::aktif()
            ->ordered()
            ->withCount('pendaftar')
            ->get();
    }

    /**
     * Reset all form data
     */
    private function resetFormData()
    {
        $this->jalur_pendaftaran_id = null;
        $this->selectedJalur = null;
        $this->hasSelectedJalur = false;

        // Reset nilai
        $this->nilai_b_indo_5_1 = null;
        $this->nilai_matematika_5_1 = null;
        $this->nilai_ipa_5_1 = null;
        $this->nilai_b_indo_5_2 = null;
        $this->nilai_matematika_5_2 = null;
        $this->nilai_ipa_5_2 = null;
        $this->nilai_b_indo_6_1 = null;
        $this->nilai_matematika_6_1 = null;
        $this->nilai_ipa_6_1 = null;
        $this->rata_rata_nilai = 0;
        $this->file_rapor = null;

        // Reset data pribadi
        $this->nisn = '';
        $this->nama_lengkap = '';
        $this->tempat_lahir = '';
        $this->tanggal_lahir = '';
        $this->jenis_kelamin = '';
        $this->agama = 'Islam';
        $this->alamat = '';
        $this->telepon_ortu = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->jenis_sekolah_asal = '';
        $this->nsm_sekolah_asal = '';
        $this->npsn_sekolah_asal = '';
        $this->asal_sekolah = '';
    }

    /**
     * Select jalur pendaftaran
     */
    public function selectJalur($jalurId)
    {
        try {
            $this->isProcessing = true;

            // Get fresh data from database (not from cache) untuk pengecekan real-time
            $jalur = JalurPendaftaran::where('id', $jalurId)
                ->where('aktif', true)
                ->withCount('pendaftar')
                ->first();

            if (!$jalur) {
                $this->addError('jalur_pendaftaran_id', 'Jalur pendaftaran tidak ditemukan atau tidak aktif');
                return;
            }

            // Cek kuota dengan real-time data
            // Kuota 0 = unlimited, selalu available
            $isKuotaTersedia = ($jalur->kuota == 0) || ($jalur->pendaftar_count < $jalur->kuota);

            if (!$isKuotaTersedia) {
                Log::warning('Jalur quota full', [
                    'jalur_id' => $jalurId,
                    'jalur_nama' => $jalur->nama,
                    'kuota' => $jalur->kuota,
                    'pendaftar_count' => $jalur->pendaftar_count
                ]);

                $this->addError('jalur_pendaftaran_id', 'Maaf, kuota untuk jalur ini sudah penuh');
                return;
            }

            // Calculate sisa kuota untuk display
            $jalur->sisa_kuota = $jalur->kuota == 0
                ? null
                : max(0, $jalur->kuota - $jalur->pendaftar_count);

            $jalur->kuota_tersedia = true; // Sudah dicek di atas

            // Set selected jalur
            $this->jalur_pendaftaran_id = $jalurId;
            $this->selectedJalur = $jalur;
            $this->hasSelectedJalur = true;

            // Clear any errors
            $this->resetErrorBag('jalur_pendaftaran_id');

            Log::info('Jalur selected successfully', [
                'jalur_id' => $jalurId,
                'jalur_nama' => $jalur->nama,
                'kuota' => $jalur->kuota,
                'pendaftar_count' => $jalur->pendaftar_count,
                'sisa_kuota' => $jalur->sisa_kuota,
                'is_unlimited' => $jalur->kuota == 0
            ]);

        } catch (\Exception $e) {
            Log::error('Error selecting jalur', [
                'error' => $e->getMessage(),
                'jalur_id' => $jalurId,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('jalur_pendaftaran_id', 'Terjadi kesalahan saat memilih jalur');
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Navigate to next step
     */
    public function nextStep()
    {
        $this->isProcessing = true;

        try {
            // Validate current step
            if (!$this->validateCurrentStep()) {
                return;
            }

            // Save form data to session (temporary storage)
            $this->saveToSession();

            // Move to next step
            if ($this->currentStep < $this->totalSteps) {
                $this->currentStep++;

                // If moving to step 2, ensure jalur is loaded
                if ($this->currentStep == 2 && !$this->selectedJalur) {
                    $this->selectedJalur = $this->jalurPendaftaran->firstWhere('id', $this->jalur_pendaftaran_id);
                }
            }

            // Scroll to top
            $this->dispatch('scroll-to-top');

        } catch (\Exception $e) {
            Log::error('Error in nextStep', [
                'error' => $e->getMessage(),
                'currentStep' => $this->currentStep
            ]);
            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Navigate to previous step
     */
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('scroll-to-top');
        }
    }

    /**
     * Validate current step
     */
    private function validateCurrentStep()
    {
        $isValid = true;

        switch ($this->currentStep) {
            case 1:
                // Validate jalur selection
                if (!$this->jalur_pendaftaran_id) {
                    $this->addError('jalur_pendaftaran_id', 'Silakan pilih jalur pendaftaran');
                    session()->flash('error', 'Silakan pilih jalur pendaftaran terlebih dahulu');
                    $isValid = false;
                }
                break;

            case 2:
                // Validate nilai rapor
                $rules = [
                    'nilai_b_indo_5_1' => 'required|numeric|min:0|max:100',
                    'nilai_matematika_5_1' => 'required|numeric|min:0|max:100',
                    'nilai_ipa_5_1' => 'required|numeric|min:0|max:100',
                    'nilai_b_indo_5_2' => 'required|numeric|min:0|max:100',
                    'nilai_matematika_5_2' => 'required|numeric|min:0|max:100',
                    'nilai_ipa_5_2' => 'required|numeric|min:0|max:100',
                    'nilai_b_indo_6_1' => 'required|numeric|min:0|max:100',
                    'nilai_matematika_6_1' => 'required|numeric|min:0|max:100',
                    'nilai_ipa_6_1' => 'required|numeric|min:0|max:100',
                    'file_rapor' => 'required|file|mimes:pdf|max:2048',
                ];

                $this->validate($rules);

                // Calculate and validate average
                $this->calculateAverage();
                $this->validateNilai();

                if (!$this->isValid) {
                    session()->flash('error', $this->validationMessage);
                    $isValid = false;
                }
                break;

            case 3:
                // This is handled in confirmSubmit method
                break;
        }

        return $isValid;
    }

    /**
     * Updated hook for nilai fields
     */
    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'nilai_')) {
            $this->calculateAverage();
            if ($this->selectedJalur) {
                $this->validateNilai();
            }
        }

        // Auto-fill school name
        if ($propertyName === 'nsm_sekolah_asal' && strlen($this->nsm_sekolah_asal) == 12) {
            $this->fillSchoolNameByNsm($this->nsm_sekolah_asal);
        }

        if ($propertyName === 'npsn_sekolah_asal' && strlen($this->npsn_sekolah_asal) == 8) {
            $this->fillSchoolNameByNpsn($this->npsn_sekolah_asal);
        }
    }

    /**
     * Calculate average nilai
     */
    public function calculateAverage()
    {
        $nilai = array_filter([
            $this->nilai_b_indo_5_1,
            $this->nilai_matematika_5_1,
            $this->nilai_ipa_5_1,
            $this->nilai_b_indo_5_2,
            $this->nilai_matematika_5_2,
            $this->nilai_ipa_5_2,
            $this->nilai_b_indo_6_1,
            $this->nilai_matematika_6_1,
            $this->nilai_ipa_6_1,
        ], function($n) {
            return $n !== null && $n !== '';
        });

        $this->rata_rata_nilai = empty($nilai) ? 0 : round(array_sum($nilai) / count($nilai), 2);
    }

    /**
     * Validate nilai against minimum requirement
     */
    public function validateNilai()
    {
        if (!$this->selectedJalur) {
            return;
        }

        if ($this->rata_rata_nilai >= $this->selectedJalur->nilai_minimum) {
            $this->isValid = true;
            $this->validationMessage = "Nilai rata-rata Anda ({$this->rata_rata_nilai}) memenuhi syarat minimum ({$this->selectedJalur->nilai_minimum})";
        } else {
            $this->isValid = false;
            $this->validationMessage = "Nilai rata-rata Anda ({$this->rata_rata_nilai}) belum memenuhi syarat minimum ({$this->selectedJalur->nilai_minimum})";
        }
    }

    /**
     * Fill school name by NSM
     */
    private function fillSchoolNameByNsm($nsm)
    {
        $cacheKey = "school_nsm_{$nsm}";
        $school = Cache::remember($cacheKey, 3600, function () use ($nsm) {
            return Pendaftar::where('nsm_sekolah_asal', $nsm)->first(['asal_sekolah']);
        });

        if ($school) {
            $this->asal_sekolah = $school->asal_sekolah;
        }
    }

    /**
     * Fill school name by NPSN
     */
    private function fillSchoolNameByNpsn($npsn)
    {
        $cacheKey = "school_npsn_{$npsn}";
        $school = Cache::remember($cacheKey, 3600, function () use ($npsn) {
            return Pendaftar::where('npsn_sekolah_asal', $npsn)->first(['asal_sekolah']);
        });

        if ($school) {
            $this->asal_sekolah = $school->asal_sekolah;
        }
    }

    /**
     * Reset file rapor
     */
    public function resetFileRapor()
    {
        $this->reset('file_rapor');
    }

    /**
     * Format phone number - convert 0 to 62 and remove +62 to 62
     */
    public function formatPhoneNumber()
    {
        if (empty($this->telepon_ortu)) {
            return;
        }

        // Remove all non-numeric except +
        $phone = preg_replace('/[^0-9+]/', '', $this->telepon_ortu);

        // Convert +62 to 62
        if (substr($phone, 0, 3) === '+62') {
            $phone = '62' . substr($phone, 3);
        }

        // Convert leading 0 to 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Ensure it starts with 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        $this->telepon_ortu = $phone;
    }

    /**
     * Validate email in real-time
     */
    public function validateEmail()
    {
        $this->emailValidationMessage = '';

        if (empty($this->email)) {
            return;
        }

        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->emailValidationMessage = 'Format email tidak valid.';
            return;
        }

        // Check if email already exists (exclude current session email)
        $sessionEmail = session('registration_temp.email');
        $query = User::where('email', $this->email);

        // Ignore if this is the same email from current session (retry attempt)
        if ($sessionEmail && $sessionEmail === $this->email) {
            // Same email from current session, allow retry
            $this->emailValidationMessage = '';
            return;
        }

        $exists = $query->exists();
        if ($exists) {
            $this->emailValidationMessage = 'Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun.';
        } else {
            $this->emailValidationMessage = ''; // Clear error if email is available
        }
    }

    /**
     * Validate NISN in real-time
     */
    public function validateNisn()
    {
        $this->nisnValidationMessage = '';

        if (empty($this->nisn)) {
            return;
        }

        // Validate NISN format (10 digits)
        if (strlen($this->nisn) !== 10 || !is_numeric($this->nisn)) {
            $this->nisnValidationMessage = 'NISN harus 10 digit angka.';
            return;
        }

        // Check if NISN already used 5 times (exclude current session NISN)
        $sessionNisn = session('registration_temp.nisn');
        $query = Pendaftar::where('nisn', $this->nisn);

        // Ignore if this is the same NISN from current session (retry attempt)
        if ($sessionNisn && $sessionNisn === $this->nisn) {
            // Same NISN from current session, allow retry
            $this->nisnValidationMessage = '';
            return;
        }

        $count = $query->count();
        if ($count >= 5) {
            $this->nisnValidationMessage = 'NISN ini telah digunakan untuk 5 kali pendaftaran.';
        } else {
            $this->nisnValidationMessage = ''; // Clear error if NISN is available
        }
    }

    /**
     * Save current form data to session (temporary storage)
     * Note: File uploads are handled separately by Livewire and cannot be serialized
     */
    private function saveToSession()
    {
        session(['registration_temp' => [
            // Step 1
            'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,

            // Step 2
            'nilai_b_indo_5_1' => $this->nilai_b_indo_5_1,
            'nilai_matematika_5_1' => $this->nilai_matematika_5_1,
            'nilai_ipa_5_1' => $this->nilai_ipa_5_1,
            'nilai_b_indo_5_2' => $this->nilai_b_indo_5_2,
            'nilai_matematika_5_2' => $this->nilai_matematika_5_2,
            'nilai_ipa_5_2' => $this->nilai_ipa_5_2,
            'nilai_b_indo_6_1' => $this->nilai_b_indo_6_1,
            'nilai_matematika_6_1' => $this->nilai_matematika_6_1,
            'nilai_ipa_6_1' => $this->nilai_ipa_6_1,
            // Note: file_rapor excluded - Livewire handles file uploads separately
            'rata_rata_nilai' => $this->rata_rata_nilai,

            // Step 3
            'nisn' => $this->nisn,
            'nama_lengkap' => $this->nama_lengkap,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'alamat' => $this->alamat,
            'telepon_ortu' => $this->telepon_ortu,
            'email' => $this->email,
            'password' => $this->password, // Store temporarily for retry
            'jenis_sekolah_asal' => $this->jenis_sekolah_asal,
            'nsm_sekolah_asal' => $this->nsm_sekolah_asal,
            'npsn_sekolah_asal' => $this->npsn_sekolah_asal,
            'asal_sekolah' => $this->asal_sekolah,

            // Metadata
            'last_updated' => now()->toDateTimeString(),
        ]]);
    }

    /**
     * Restore form data from session (for retry attempts)
     */
    private function restoreFromSession()
    {
        $data = session('registration_temp');

        if (!$data) {
            return;
        }

        // Restore Step 1
        $this->jalur_pendaftaran_id = $data['jalur_pendaftaran_id'] ?? null;

        // Restore Step 2
        $this->nilai_b_indo_5_1 = $data['nilai_b_indo_5_1'] ?? null;
        $this->nilai_matematika_5_1 = $data['nilai_matematika_5_1'] ?? null;
        $this->nilai_ipa_5_1 = $data['nilai_ipa_5_1'] ?? null;
        $this->nilai_b_indo_5_2 = $data['nilai_b_indo_5_2'] ?? null;
        $this->nilai_matematika_5_2 = $data['nilai_matematika_5_2'] ?? null;
        $this->nilai_ipa_5_2 = $data['nilai_ipa_5_2'] ?? null;
        $this->nilai_b_indo_6_1 = $data['nilai_b_indo_6_1'] ?? null;
        $this->nilai_matematika_6_1 = $data['nilai_matematika_6_1'] ?? null;
        $this->nilai_ipa_6_1 = $data['nilai_ipa_6_1'] ?? null;
        // Note: file_rapor not restored - Livewire handles file state separately
        $this->rata_rata_nilai = $data['rata_rata_nilai'] ?? 0;

        // Restore Step 3
        $this->nisn = $data['nisn'] ?? '';
        $this->nama_lengkap = $data['nama_lengkap'] ?? '';
        $this->tempat_lahir = $data['tempat_lahir'] ?? '';
        $this->tanggal_lahir = $data['tanggal_lahir'] ?? '';
        $this->jenis_kelamin = $data['jenis_kelamin'] ?? '';
        $this->agama = $data['agama'] ?? 'Islam';
        $this->alamat = $data['alamat'] ?? '';
        $this->telepon_ortu = $data['telepon_ortu'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->jenis_sekolah_asal = $data['jenis_sekolah_asal'] ?? '';
        $this->nsm_sekolah_asal = $data['nsm_sekolah_asal'] ?? '';
        $this->npsn_sekolah_asal = $data['npsn_sekolah_asal'] ?? '';
        $this->asal_sekolah = $data['asal_sekolah'] ?? '';
    }

    /**
     * Clear temporary session data
     */
    private function clearSession()
    {
        session()->forget('registration_temp');
        session()->forget('registration_session_id');
    }

    /**
     * Check and clear old session data (abandoned registrations)
     * Sessions older than 24 hours will be cleared
     */
    private function clearOldSessionIfNeeded()
    {
        $data = session('registration_temp');

        if (!$data) {
            return;
        }

        // Check if session is older than 24 hours
        $lastUpdated = $data['last_updated'] ?? null;

        if ($lastUpdated) {
            $sessionAge = now()->diffInHours($lastUpdated);

            // Clear if older than 24 hours (abandoned registration)
            if ($sessionAge >= 24) {
                $this->clearSession();
                Log::info('Cleared abandoned registration session', [
                    'session_age_hours' => $sessionAge,
                    'last_updated' => $lastUpdated
                ]);
            }
        }
    }

    /**
     * Show confirmation modal
     */
    public function confirmSubmit()
    {
        // Save current state to session before validation
        $this->saveToSession();

        // Validate step 3
        $rules = [
            'nisn' => [
                'required',
                'numeric',
                'digits:10',
                function ($attribute, $value, $fail) {
                    // Exclude current session NISN from count (allow retry)
                    $sessionNisn = session('registration_temp.nisn');
                    $query = Pendaftar::where('nisn', $value);

                    // Don't count if this is the same NISN from current session
                    if ($sessionNisn && $sessionNisn === $value) {
                        // This is a retry attempt, skip check
                        return;
                    }

                    if (!isset($this->nisnCountCache[$value])) {
                        $this->nisnCountCache[$value] = $query->count();
                    }
                    if ($this->nisnCountCache[$value] >= 5) {
                        $fail('NISN ini telah digunakan untuk 5 kali pendaftaran.');
                    }
                },
            ],
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string',
            'alamat' => 'required|string',
            'telepon_ortu' => 'required|string|max:15',
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    // Exclude current session email (allow retry)
                    $sessionEmail = session('registration_temp.email');

                    // Don't check if this is the same email from current session
                    if ($sessionEmail && $sessionEmail === $value) {
                        // This is a retry attempt, skip check
                        return;
                    }

                    // Check if email already exists (unique constraint)
                    $exists = User::where('email', $value)->exists();
                    if ($exists) {
                        $fail('Email ini sudah terdaftar. Silakan gunakan email lain atau login jika Anda sudah memiliki akun.');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed',
            'jenis_sekolah_asal' => 'required|in:madrasah,umum',
            'asal_sekolah' => 'required|string|max:255',
        ];

        // Add conditional validation for NSM/NPSN
        if ($this->jenis_sekolah_asal === 'madrasah') {
            $rules['nsm_sekolah_asal'] = 'required|digits:12';
        } else if ($this->jenis_sekolah_asal === 'umum') {
            $rules['npsn_sekolah_asal'] = 'required|digits:8';
        }

        $this->validate($rules);
        $this->showConfirmationModal = true;
    }

    /**
     * Cancel submission
     */
    public function cancelSubmit()
    {
        $this->showConfirmationModal = false;
    }

    /**
     * Process registration
     */
    public function register()
    {
        $this->isProcessing = true;

        try {
            // Final validation
            if (!isset($this->nisnCountCache[$this->nisn])) {
                $this->nisnCountCache[$this->nisn] = Pendaftar::where('nisn', $this->nisn)->count();
            }

            if ($this->nisnCountCache[$this->nisn] >= 5) {
                throw new \Exception('NISN ini telah digunakan untuk 5 kali pendaftaran.');
            }

            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $this->nama_lengkap,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'pendaftar',
                'aktif' => true,
            ]);

            // Generate nomor pendaftaran
            $jalur = JalurPendaftaran::find($this->jalur_pendaftaran_id);
            $nomorPendaftaran = $jalur->generateNomorPendaftaran();

            // Create pendaftar record
            $pendaftar = Pendaftar::create([
                'user_id' => $user->id,
                'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,
                'nomor_pendaftaran' => $nomorPendaftaran,
                'nisn' => $this->nisn,
                'nama_lengkap' => $this->nama_lengkap,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'agama' => $this->agama,
                'alamat' => $this->alamat,
                'telepon' => $this->telepon_ortu,
                'telepon_ortu' => $this->telepon_ortu,
                'email' => $this->email,
                'jenis_sekolah_asal' => $this->jenis_sekolah_asal,
                'nsm_sekolah_asal' => $this->nsm_sekolah_asal ?: null,
                'npsn_sekolah_asal' => $this->npsn_sekolah_asal ?: null,
                'asal_sekolah' => $this->asal_sekolah,
                'nilai_b_indo_5_1' => $this->nilai_b_indo_5_1,
                'nilai_matematika_5_1' => $this->nilai_matematika_5_1,
                'nilai_ipa_5_1' => $this->nilai_ipa_5_1,
                'nilai_b_indo_5_2' => $this->nilai_b_indo_5_2,
                'nilai_matematika_5_2' => $this->nilai_matematika_5_2,
                'nilai_ipa_5_2' => $this->nilai_ipa_5_2,
                'nilai_b_indo_6_1' => $this->nilai_b_indo_6_1,
                'nilai_matematika_6_1' => $this->nilai_matematika_6_1,
                'nilai_ipa_6_1' => $this->nilai_ipa_6_1,
                'file_rapor' => null, // Will be updated after upload
                'nilai_rata_rata' => $this->rata_rata_nilai,
                'status_pendaftaran' => 'menunggu_verifikasi',
                'tanggal_daftar' => now(),
            ]);

            // Upload file rapor AFTER pendaftar created to get ID for directory
            if ($this->file_rapor) {
                $extension = FileNamingService::getFileExtension($this->file_rapor);
                $customFileName = FileNamingService::generateFileName(
                    'rapor',
                    $pendaftar->nomor_pendaftaran,
                    $extension
                );

                // Store to PRIVATE storage for security
                $raporPath = $this->file_rapor->storeAs(
                    'dokumen/' . $pendaftar->id,
                    $customFileName,
                    'private'
                );

                // Update pendaftar with rapor path
                $pendaftar->update(['file_rapor' => $raporPath]);
            }

            DB::commit();

            // Clear temporary session data (registration completed successfully)
            $this->clearSession();

            // Clear cache
            Cache::forget('jalur_pendaftaran_with_counts');

            // Send notifications (in background if possible)
            $this->sendRegistrationNotification($pendaftar, $jalur, $this->password);

            // Success redirect
            session()->flash('success', "Pendaftaran berhasil! Nomor pendaftaran Anda: {$nomorPendaftaran}. Silakan cek email dan WhatsApp untuk informasi login.");

            return redirect()->route('login');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'nisn' => $this->nisn,
                'email' => $this->email
            ]);

            session()->flash('error', 'Pendaftaran gagal: ' . $e->getMessage());
            $this->showConfirmationModal = false;

        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Send registration notification
     */
    private function sendRegistrationNotification($pendaftar, $jalur, $password)
    {
        try {
            $replacements = [
                '{nama}' => $pendaftar->nama_lengkap,
                '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran,
                '{nisn}' => $pendaftar->nisn,
                '{tempat_lahir}' => $pendaftar->tempat_lahir,
                '{tanggal_lahir}' => \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y'),
                '{jenis_kelamin}' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                '{alamat}' => $pendaftar->alamat,
                '{telepon}' => $pendaftar->telepon_ortu,
                '{email}' => $pendaftar->email,
                '{asal_sekolah}' => $pendaftar->asal_sekolah,
                '{jalur}' => $jalur->nama,
                '{password}' => $password,
                '{url_dashboard}' => url('/pendaftar/dashboard'),
            ];

            // Email notification
            $emailSubject = Cache::remember('email_pendaftaran_subject', 3600, function () {
                return Setting::get('email_pendaftaran_subject', 'Pendaftaran Berhasil - MTsN 2 Kota Malang');
            });

            $emailBody = Cache::remember('email_pendaftaran_body', 3600, function () {
                return Setting::get('email_pendaftaran_body', 'Pendaftaran Anda berhasil.');
            });

            $emailSubject = str_replace(array_keys($replacements), array_values($replacements), $emailSubject);
            $emailBody = str_replace(array_keys($replacements), array_values($replacements), $emailBody);

            $emailService = new EmailService();
            $emailService->sendEmail($pendaftar->email, $emailSubject, $emailBody);

            // WhatsApp notification
            $waBody = Cache::remember('wa_pendaftaran_body', 3600, function () {
                return Setting::get('wa_pendaftaran_body', 'Pendaftaran Anda berhasil.');
            });

            $waBody = str_replace(array_keys($replacements), array_values($replacements), $waBody);

            $whatsappService = new WhatsAppGatewayService();
            $whatsappService->sendMessage($pendaftar->telepon, $waBody);

        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'error' => $e->getMessage(),
                'pendaftar_id' => $pendaftar->id
            ]);
        }
    }

    /**
     * Get average for subject
     */
    public function getAverageForSubject($subject)
    {
        $nilai = [];

        switch ($subject) {
            case 'b_indo':
                $nilai = [
                    $this->nilai_b_indo_5_1,
                    $this->nilai_b_indo_5_2,
                    $this->nilai_b_indo_6_1,
                ];
                break;
            case 'matematika':
                $nilai = [
                    $this->nilai_matematika_5_1,
                    $this->nilai_matematika_5_2,
                    $this->nilai_matematika_6_1,
                ];
                break;
            case 'ipa':
                $nilai = [
                    $this->nilai_ipa_5_1,
                    $this->nilai_ipa_5_2,
                    $this->nilai_ipa_6_1,
                ];
                break;
        }

        $nilaiValid = array_filter($nilai, fn($n) => $n !== null && $n !== '');

        return empty($nilaiValid) ? 0 : round(array_sum($nilaiValid) / count($nilaiValid), 2);
    }

    /**
     * Get overall average
     */
    public function getOverallAverage()
    {
        return $this->rata_rata_nilai;
    }

    /**
     * Check if Step 2 is complete and valid
     * Computed property untuk button enable/disable di Step 2
     */
    public function getIsStep2ValidProperty()
    {
        // Check semua nilai terisi
        $allNilaiFilled = !empty($this->nilai_b_indo_5_1) &&
                         !empty($this->nilai_matematika_5_1) &&
                         !empty($this->nilai_ipa_5_1) &&
                         !empty($this->nilai_b_indo_5_2) &&
                         !empty($this->nilai_matematika_5_2) &&
                         !empty($this->nilai_ipa_5_2) &&
                         !empty($this->nilai_b_indo_6_1) &&
                         !empty($this->nilai_matematika_6_1) &&
                         !empty($this->nilai_ipa_6_1);

        // Check file rapor uploaded
        $fileRaporUploaded = $this->file_rapor !== null;

        // Check nilai memenuhi minimum requirement
        $nilaiMemenuhi = $this->isValid;

        return $allNilaiFilled && $fileRaporUploaded && $nilaiMemenuhi;
    }

    /**
     * Check if Step 3 is complete and valid
     * Computed property untuk button enable/disable di Step 3
     */
    public function getIsStep3ValidProperty()
    {
        // Check semua field data pribadi terisi
        $dataPribadiValid = !empty($this->nisn) &&
                           !empty($this->nama_lengkap) &&
                           !empty($this->tempat_lahir) &&
                           !empty($this->tanggal_lahir) &&
                           !empty($this->jenis_kelamin) &&
                           !empty($this->agama) &&
                           !empty($this->alamat) &&
                           !empty($this->telepon_ortu);

        // Check data sekolah asal terisi
        $dataSekolahValid = !empty($this->jenis_sekolah_asal) &&
                           !empty($this->asal_sekolah);

        // Check NSM atau NPSN terisi sesuai jenis sekolah
        $nsmNpsnValid = false;
        if ($this->jenis_sekolah_asal === 'madrasah') {
            $nsmNpsnValid = !empty($this->nsm_sekolah_asal) && strlen($this->nsm_sekolah_asal) == 12;
        } elseif ($this->jenis_sekolah_asal === 'umum') {
            $nsmNpsnValid = !empty($this->npsn_sekolah_asal) && strlen($this->npsn_sekolah_asal) == 8;
        }

        // Check data akun terisi
        $dataAkunValid = !empty($this->email) &&
                        !empty($this->password) &&
                        !empty($this->password_confirmation) &&
                        $this->password === $this->password_confirmation;

        // Check tidak ada validation error
        $noValidationErrors = empty($this->emailValidationMessage) && empty($this->nisnValidationMessage);

        return $dataPribadiValid && $dataSekolahValid && $nsmNpsnValid && $dataAkunValid && $noValidationErrors;
    }

    /**
     * Render view
     */
    public function render()
    {
        $appAcronym = Cache::remember('app_acronym', 3600, function () {
            return Setting::get('app_acronym', 'SPMBM');
        });

        return view('livewire.auth.register-fixed', [
            'jalurPendaftaran' => $this->jalurPendaftaran ?? collect(),
            'appAcronym' => $appAcronym,
        ])->layout('components.layouts.public')->layoutData(['title' => 'Pendaftaran SPMBM']);
    }
}