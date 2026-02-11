<?php

namespace App\Livewire\Auth;

use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Setting;
use App\Models\RiwayatPesan;
use App\Services\EmailService;
use App\Services\WhatsAppGatewayService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    protected $listeners = ['selectJalur' => 'selectJalur'];

    // Multi-step tracker
    public $currentStep = 1;
    public $totalSteps = 3;

    // Computed property untuk status jalur
    public function getJalurDipilihProperty()
    {
        return !empty($this->jalur_pendaftaran_id);
    }

    // Step 1: Pilih Jalur
    public $jalur_pendaftaran_id;
    public $selectedJalur;
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

    // Step 3: Validasi (automatic)
    public $isValid = false;
    public $validationMessage = '';

    // Modal Konfirmasi
    public $showConfirmModal = false;

    // Step 4: Data Pribadi
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

    protected function rules()
    {
        $rules = [];

        if ($this->currentStep == 1) {
            $rules = [
                'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftaran,id',
            ];
        }

        if ($this->currentStep == 2) {
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
                'file_rapor' => 'required|file|mimes:pdf|max:2048', // PDF only, maksimal 2MB
            ];
        }

        if ($this->currentStep == 3) {
            $rules = [
                'nisn' => [
                    'required',
                    'numeric',
                    'digits:10',
                    'unique:pendaftar,nisn', // NISN must be unique
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
                    'unique:users,email', // Email must be unique
                ],
                'password' => 'required|min:8|confirmed',
                'jenis_sekolah_asal' => 'required|in:madrasah,umum',
                'nsm_sekolah_asal' => 'nullable|digits:12',
                'npsn_sekolah_asal' => 'nullable|digits:8',
                'asal_sekolah' => 'required|string|max:255',
            ];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'jalur_pendaftaran_id.required' => 'Pilih jalur pendaftaran terlebih dahulu',
            'nilai_b_indo_5_1.required' => 'Nilai Bahasa Indonesia Kelas 5 Semester 1 wajib diisi',
            'nilai_matematika_5_1.required' => 'Nilai Matematika Kelas 5 Semester 1 wajib diisi',
            'nilai_ipa_5_1.required' => 'Nilai IPA Kelas 5 Semester 1 wajib diisi',
            'file_rapor.required' => 'Upload scan rapor wajib dilakukan',
            'file_rapor.mimes' => 'File rapor harus berupa PDF. Silahkan konversi file Anda ke format PDF terlebih dahulu.',
            'file_rapor.max' => 'Ukuran file rapor terlalu besar (maksimal 2 MB). Silahkan kompres atau perkecil ukuran file Anda terlebih dahulu.',
            'nisn.required' => 'NISN wajib diisi',
            'nisn.digits' => 'NISN harus 10 digit',
            'nisn.numeric' => 'NISN harus berupa angka',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem kami. Silahkan cek email atau WhatsApp Anda untuk informasi akun. Jika Anda lupa password, silahkan gunakan fitur lupa password.',
            'email.unique' => 'Email ini sudah terdaftar di sistem kami. Silahkan cek email atau WhatsApp Anda untuk informasi akun dan password. Jika Anda lupa password, silahkan gunakan fitur lupa password atau login langsung.',
            'email.email' => 'Format email tidak valid',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }

    public function mount()
    {
        // Check if registration is open
        $isOpen = \App\Models\Setting::isRegistrationOpen();
        if (!$isOpen) {
            session()->flash('error', 'Pendaftaran belum dibuka atau sudah ditutup');
            return redirect()->to('/');
        }

        // Initialize properties
        $this->currentStep = 1;
        $this->jalur_pendaftaran_id = null;
        $this->selectedJalur = null;
        $this->hasSelectedJalur = false;

        // Initialize nilai properties
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
    }

    public function updated($propertyName)
    {
        // Calculate average whenever any nilai property changes
        if (str_starts_with($propertyName, 'nilai_')) {
            $this->calculateAverage();
            $this->validateNilai(); // Also update validation status
        }
    }

    public function nextStep()
    {
        // Log for debugging
        \Log::info('nextStep called', [
            'currentStep' => $this->currentStep,
            'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,
        ]);

        // Validate step 1 - Must select jalur
        if ($this->currentStep == 1) {
            // Pastikan jalur_pendaftaran_id telah diisi
            if (!$this->jalur_pendaftaran_id) {
                $this->addError('jalur_pendaftaran_id', 'Silahkan pilih jalur pendaftaran terlebih dahulu.');
                session()->flash('error', 'Silahkan pilih jalur pendaftaran terlebih dahulu.');
                return;
            }

            // Verifikasi bahwa jalur yang dipilih benar-benar ada di database
            $jalur = JalurPendaftaran::find($this->jalur_pendaftaran_id);
            if (!$jalur) {
                $this->addError('jalur_pendaftaran_id', 'Jalur yang dipilih tidak valid.');
                session()->flash('error', 'Jalur yang dipilih tidak valid.');
                return;
            }

            // Load selected jalur data
            $this->selectedJalur = $jalur;
        }

        if ($this->currentStep == 2) {
            // Validate nilai rapor and file upload
            $this->validate([
                'nilai_b_indo_5_1' => 'required|numeric|min:0|max:100',
                'nilai_matematika_5_1' => 'required|numeric|min:0|max:100',
                'nilai_ipa_5_1' => 'required|numeric|min:0|max:100',
                'nilai_b_indo_5_2' => 'required|numeric|min:0|max:100',
                'nilai_matematika_5_2' => 'required|numeric|min:0|max:100',
                'nilai_ipa_5_2' => 'required|numeric|min:0|max:100',
                'nilai_b_indo_6_1' => 'required|numeric|min:0|max:100',
                'nilai_matematika_6_1' => 'required|numeric|min:0|max:100',
                'nilai_ipa_6_1' => 'required|numeric|min:0|max:100',
                'file_rapor' => 'required|file|mimes:pdf|max:2048', // PDF only, maksimal 2MB
            ]);

            // Calculate average and validate
            $this->calculateAverage();
            $this->validateNilai();

            if (!$this->isValid) {
                session()->flash('error', 'Nilai Anda belum memenuhi syarat minimum jalur yang dipilih.');
                return; // Don't proceed if validation fails
            }
        } else if ($this->currentStep != 1) {
            $this->validate();
        }

        // Pastikan tidak melewati batas jumlah langkah
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }

        // Log perubahan langkah
        \Log::info('nextStep completed', [
            'newCurrentStep' => $this->currentStep,
        ]);
    }

    public function previousStep()
    {
        // Ensure we don't go below step 1
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function calculateAverage()
    {
        $nilai = [
            $this->nilai_b_indo_5_1,
            $this->nilai_matematika_5_1,
            $this->nilai_ipa_5_1,
            $this->nilai_b_indo_5_2,
            $this->nilai_matematika_5_2,
            $this->nilai_ipa_5_2,
            $this->nilai_b_indo_6_1,
            $this->nilai_matematika_6_1,
            $this->nilai_ipa_6_1,
        ];

        $nilaiValid = array_filter($nilai, function($n) {
            return $n !== null && $n !== '';
        });

        if (empty($nilaiValid)) {
            $this->rata_rata_nilai = 0;
        } else {
            $this->rata_rata_nilai = round(array_sum($nilaiValid) / count($nilaiValid), 2);
        }
    }

    public function validateNilai()
    {
        if (!$this->selectedJalur) {
            $this->selectedJalur = JalurPendaftaran::find($this->jalur_pendaftaran_id);
        }

        if ($this->selectedJalur && $this->rata_rata_nilai >= $this->selectedJalur->nilai_minimum) {
            $this->isValid = true;
            $this->validationMessage = "Selamat! Nilai rata-rata Anda ({$this->rata_rata_nilai}) memenuhi syarat minimum jalur {$this->selectedJalur->nama} (minimum: {$this->selectedJalur->nilai_minimum})";
        } else {
            $this->isValid = false;
            $nilaiMin = $this->selectedJalur ? $this->selectedJalur->nilai_minimum : 'tidak diketahui';
            $namaJalur = $this->selectedJalur ? $this->selectedJalur->nama : 'tidak dipilih';
            $this->validationMessage = "Maaf, nilai rata-rata Anda ({$this->rata_rata_nilai}) belum memenuhi syarat minimum jalur {$namaJalur} (minimum: {$nilaiMin}). Silakan pilih jalur lain atau tingkatkan nilai Anda.";
        }
    }


    public function updatedJalurPendaftaranId($value)
    {
        \Log::info('updatedJalurPendaftaranId called', ['value' => $value]);

        if ($value) {
            $this->hasSelectedJalur = true;
            $this->selectedJalur = JalurPendaftaran::find($value);

            // Clear any errors
            $this->resetErrorBag('jalur_pendaftaran_id');

            \Log::info('Jalur loaded', ['jalur' => $this->selectedJalur?->nama]);
        } else {
            $this->hasSelectedJalur = false;
            $this->selectedJalur = null;
        }
    }

    public function selectJalur($jalurId)
    {
        // Set the selected jalur ID
        $this->jalur_pendaftaran_id = $jalurId;

        // Set flag that jalur has been selected
        $this->hasSelectedJalur = true;

        // Load the complete jalur object
        $this->selectedJalur = JalurPendaftaran::find($jalurId);

        // Clear any existing errors on jalur_pendaftaran_id
        $this->resetErrorBag('jalur_pendaftaran_id');

        // Log for debugging
        \Log::info('selectJalur method called', [
            'jalurId' => $jalurId,
            'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,
            'hasSelectedJalur' => $this->hasSelectedJalur,
            'selectedJalur' => $this->selectedJalur?->nama
        ]);
    }

    public function testMethod()
    {
        $this->jalur_pendaftaran_id = 999;
        session()->flash('debug', 'Test method called successfully!');
    }

    public function setJalurPendaftaran($id)
    {
        $this->jalur_pendaftaran_id = $id;
        $this->hasSelectedJalur = true;
        $this->selectedJalur = JalurPendaftaran::find($id);

        // Log untuk debugging
        \Log::info('setJalurPendaftaran called', [
            'id' => $id,
            'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,
            'hasSelectedJalur' => $this->hasSelectedJalur,
            'selectedJalur' => $this->selectedJalur?->nama
        ]);
    }


    public function updatedNsmSekolahAsal($value)
    {
        if ($value && strlen($value) == 12) {
            $this->fillSchoolNameByNsm($value);
        }
    }

    public function updatedNpsnSekolahAsal($value)
    {
        if ($value && strlen($value) == 8) {
            $this->fillSchoolNameByNpsn($value);
        }
    }

    private function fillSchoolNameByNsm($nsm)
    {
        $existingPendaftar = \App\Models\Pendaftar::where('nsm_sekolah_asal', $nsm)->first();
        if ($existingPendaftar) {
            $this->asal_sekolah = $existingPendaftar->asal_sekolah;
        }
    }

    private function fillSchoolNameByNpsn($npsn)
    {
        $existingPendaftar = \App\Models\Pendaftar::where('npsn_sekolah_asal', $npsn)->first();
        if ($existingPendaftar) {
            $this->asal_sekolah = $existingPendaftar->asal_sekolah;
        }
    }

    public function openConfirmModal()
    {
        // Validasi dulu sebelum buka modal
        $this->validate();
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal()
    {
        $this->showConfirmModal = false;
    }

    public function resetFileRapor()
    {
        $this->reset('file_rapor');
    }


    public function register()
    {
        // Validate all fields (including NISN unique constraint)
        $this->validate();

        // Variables to store data
        $user = null;
        $pendaftar = null;
        $jalur = null;
        $nomorPendaftaran = null;
        $tempFilePath = null;
        $finalFilePath = null;

        try {
            // START DATABASE TRANSACTION
            \DB::beginTransaction();

            // 1. Create User
            $user = User::create([
                'name' => $this->nama_lengkap,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'pendaftar',
            ]);

            // 2. Generate nomor pendaftaran
            $jalur = JalurPendaftaran::find($this->jalur_pendaftaran_id);
            $nomorPendaftaran = $jalur->generateNomorPendaftaran();

            // 3. Create Pendaftar (without file first)
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
                'nsm_sekolah_asal' => $this->nsm_sekolah_asal,
                'npsn_sekolah_asal' => $this->npsn_sekolah_asal,
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
                'file_rapor' => null, // Will be set after file upload
                'nilai_rata_rata' => $this->rata_rata_nilai,
                'status_pendaftaran' => 'menunggu_verifikasi',
                'tanggal_daftar' => now(),
            ]);

            // 4. Upload file to TEMPORARY storage first
            if ($this->file_rapor) {
                $extension = FileNamingService::getFileExtension($this->file_rapor);
                $customFileName = FileNamingService::generateFileName(
                    'rapor',
                    $pendaftar->nomor_pendaftaran,
                    $extension,
                    $pendaftar->nama_lengkap
                );

                // Store to TEMPORARY location first
                $tempFilePath = $this->file_rapor->storeAs(
                    'temp/rapor',
                    'temp_' . time() . '_' . $customFileName,
                    'private'
                );

                // Set final file path (will be moved after commit)
                $finalFilePath = 'dokumen/' . $pendaftar->id . '/' . $customFileName;

                // Update pendaftar with FINAL path (not temp)
                $pendaftar->update(['file_rapor' => $finalFilePath]);
            }

            // COMMIT TRANSACTION - All database changes are now permanent
            \DB::commit();

            // 5. Move file from TEMP to PERMANENT storage (after successful commit)
            if ($tempFilePath && $finalFilePath) {
                \Storage::disk('private')->move($tempFilePath, $finalFilePath);
                \Log::info('File moved from temp to permanent', [
                    'from' => $tempFilePath,
                    'to' => $finalFilePath
                ]);
            }

            // Close the confirmation modal
            $this->showConfirmModal = false;

            // 6. Send notification email and WhatsApp (after everything is saved)
            $this->sendRegistrationNotification($pendaftar, $jalur, $this->password);

            session()->flash('success', "Pendaftaran berhasil! Silakan cek email dan WhatsApp Anda untuk informasi login. Nomor pendaftaran Anda: {$nomorPendaftaran}");

            return redirect()->to('/login');

        } catch (\Illuminate\Database\QueryException $e) {
            // ROLLBACK all database changes
            \DB::rollBack();

            // DELETE temporary file if exists
            if ($tempFilePath && \Storage::disk('private')->exists($tempFilePath)) {
                \Storage::disk('private')->delete($tempFilePath);
                \Log::info('Temporary file deleted after rollback', ['file' => $tempFilePath]);
            }

            $this->showConfirmModal = false;

            $errorMessage = $e->getMessage();

            // Handle duplicate email error (cek berbagai variasi pesan error)
            if (str_contains($errorMessage, 'users_email_unique') ||
                str_contains($errorMessage, 'duplicate key') && str_contains($errorMessage, 'email')) {
                session()->flash('error', 'Email ini sudah terdaftar di sistem kami. Silahkan gunakan email lain atau login jika Anda sudah memiliki akun. Jika lupa password, gunakan fitur reset password.');
                \Log::warning('Duplicate email registration attempt', [
                    'email' => $this->email,
                    'nama' => $this->nama_lengkap,
                ]);

                // Set validation error pada field email
                $this->addError('email', 'Email ini sudah terdaftar. Silahkan gunakan email lain.');
            }
            // Handle duplicate NISN error (cek berbagai variasi pesan error)
            elseif (str_contains($errorMessage, 'pendaftar_nisn_unique') ||
                    str_contains($errorMessage, 'pendaftars_nisn') ||
                    (str_contains($errorMessage, 'duplicate key') && str_contains($errorMessage, 'nisn'))) {
                session()->flash('error', 'NISN ini sudah terdaftar di sistem kami. Silahkan cek email atau WhatsApp Anda untuk informasi login. Jika lupa password, gunakan fitur reset password atau hubungi panitia.');
                \Log::warning('Duplicate NISN registration attempt', [
                    'nisn' => $this->nisn,
                    'nama' => $this->nama_lengkap,
                ]);

                // Set validation error pada field NISN
                $this->addError('nisn', 'NISN ini sudah terdaftar. Silahkan hubungi panitia jika ada masalah.');
            }
            else {
                session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi atau hubungi panitia.');
                \Log::error('Registration database error', [
                    'error' => $errorMessage,
                    'email' => $this->email,
                    'nisn' => $this->nisn,
                ]);
            }
        } catch (\Exception $e) {
            // ROLLBACK all database changes
            \DB::rollBack();

            // DELETE temporary file if exists
            if ($tempFilePath && \Storage::disk('private')->exists($tempFilePath)) {
                \Storage::disk('private')->delete($tempFilePath);
                \Log::info('Temporary file deleted after rollback', ['file' => $tempFilePath]);
            }

            $this->showConfirmModal = false;
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            \Log::error('Registration error', [
                'error' => $e->getMessage(),
                'email' => $this->email,
            ]);
        }
    }

    private function sendRegistrationNotification($pendaftar, $jalur, $password)
    {
        try {
            // Prepare placeholders
            $jenisKelamin = $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
            $tanggalLahir = \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y');

            $replacements = [
                '{nama}' => $pendaftar->nama_lengkap,
                '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran,
                '{nisn}' => $pendaftar->nisn,
                '{tempat_lahir}' => $pendaftar->tempat_lahir,
                '{tanggal_lahir}' => $tanggalLahir,
                '{jenis_kelamin}' => $jenisKelamin,
                '{alamat}' => $pendaftar->alamat,
                '{telepon}' => $pendaftar->telepon_ortu,
                '{email}' => $pendaftar->email,
                '{asal_sekolah}' => $pendaftar->asal_sekolah,
                '{jalur}' => $jalur->nama,
                '{password}' => $password,
                '{url_dashboard}' => url('/pendaftar/dashboard'),
            ];

            // Get email template from settings
            $emailSubject = Setting::get('email_pendaftaran_subject', 'Pendaftaran Berhasil - MTsN 2 Kota Malang');
            $emailBody = Setting::get('email_pendaftaran_body',
                "Kepada Yth. {nama},\n\nSelamat! Akun pendaftaran Anda telah berhasil dibuat.\n\n=== DATA PENDAFTARAN ANDA ===\nNomor Pendaftaran: {nomor_pendaftaran}\nNama Lengkap: {nama}\nNISN: {nisn}\nTempat, Tanggal Lahir: {tempat_lahir}, {tanggal_lahir}\nJenis Kelamin: {jenis_kelamin}\nAlamat: {alamat}\nTelepon: {telepon}\nEmail: {email}\nAsal Sekolah: {asal_sekolah}\nJalur Pendaftaran: {jalur}\n\n=== AKUN LOGIN ===\nEmail: {email}\nPassword: {password}\n\nSilakan login ke dashboard Anda di: {url_dashboard}\n\nTim kami akan segera meninjau berkas pendaftaran Anda. Pantau terus dashboard untuk update status terbaru.\n\nSalam,\nPanitia PPDB MTsN 2 Kota Malang"
            );

            // Replace placeholders for email
            $emailSubject = str_replace(array_keys($replacements), array_values($replacements), $emailSubject);
            $emailBody = str_replace(array_keys($replacements), array_values($replacements), $emailBody);

            // Create log record for email with pending status
            $emailRiwayat = RiwayatPesan::create([
                'pendaftar_id' => $pendaftar->id,
                'user_id' => null, // Otomatis dari sistem
                'jenis_pesan' => 'email',
                'judul' => $emailSubject,
                'isi_pesan' => $emailBody,
                'penerima' => $pendaftar->email,
                'status' => 'pending',
            ]);

            // Send Email
            $emailService = new EmailService();
            $emailResult = $emailService->sendEmail($pendaftar->email, $emailSubject, $emailBody);

            // Update email log status
            if ($emailResult['success'] ?? false) {
                $emailRiwayat->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);
            } else {
                $emailRiwayat->update([
                    'status' => 'gagal',
                    'error_message' => $emailResult['message'] ?? 'Unknown error',
                ]);
            }

            // Get WhatsApp template from settings
            $waBody = Setting::get('wa_pendaftaran_body',
                "*MTsN 2 Kota Malang*\n\nKepada Yth. Bapak/Ibu *{nama}*\n\nSelamat! Akun pendaftaran Anda telah berhasil dibuat.\n\n*=== DATA PENDAFTARAN ===*\nNomor Pendaftaran: *{nomor_pendaftaran}*\nNama: {nama}\nNISN: {nisn}\nTempat, Tgl Lahir: {tempat_lahir}, {tanggal_lahir}\nJenis Kelamin: {jenis_kelamin}\nAlamat: {alamat}\nTelepon: {telepon}\nEmail: {email}\nAsal Sekolah: {asal_sekolah}\nJalur: {jalur}\n\n*=== AKUN LOGIN ===*\nEmail: {email}\nPassword: {password}\n\nSilakan login ke: {url_dashboard}\n\nTim kami akan segera meninjau berkas pendaftaran Anda. Pantau terus dashboard untuk update status terbaru.\n\nTerima kasih."
            );

            // Replace placeholders for WhatsApp
            $waBody = str_replace(array_keys($replacements), array_values($replacements), $waBody);

            // Create log record for WhatsApp with pending status
            $whatsappRiwayat = RiwayatPesan::create([
                'pendaftar_id' => $pendaftar->id,
                'user_id' => null, // Otomatis dari sistem
                'jenis_pesan' => 'whatsapp',
                'judul' => null,
                'isi_pesan' => $waBody,
                'penerima' => $pendaftar->telepon_ortu,
                'status' => 'pending',
            ]);

            // Send WhatsApp to parent's phone number
            $whatsappService = new WhatsAppGatewayService();
            $whatsappResult = $whatsappService->sendMessage($pendaftar->telepon_ortu, $waBody);

            // Update WhatsApp log status
            if ($whatsappResult['success'] ?? false) {
                $whatsappRiwayat->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);
            } else {
                $whatsappRiwayat->update([
                    'status' => 'gagal',
                    'error_message' => $whatsappResult['message'] ?? 'Unknown error',
                ]);
            }

            \Log::info("Registration notification sent", [
                'pendaftar_id' => $pendaftar->id,
                'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                'email_status' => $emailResult['success'] ?? false,
                'whatsapp_status' => $whatsappResult['success'] ?? false,
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to send registration notification", [
                'pendaftar_id' => $pendaftar->id ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function cekSekolah($jenisSekolahAsal, $nomorSekolah)
    {
        // Cek apakah nomor sekolah (NSM/NPSN) sudah ada di database pendaftar sebelumnya
        $pendaftar = Pendaftar::where($jenisSekolahAsal === 'madrasah' ? 'nsm_sekolah_asal' : 'npsn_sekolah_asal', $nomorSekolah)
                             ->first();

        if ($pendaftar) {
            return response()->json([
                'found' => true,
                'nama_sekolah' => $pendaftar->asal_sekolah
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'Sekolah tidak ditemukan'
        ]);
    }

    public function getAverageForSubject($subject)
    {
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
            default:
                $nilai = [];
        }

        $nilaiValid = array_filter($nilai, function($n) {
            return $n !== null && $n !== '';
        });

        if (empty($nilaiValid)) {
            return 0;
        }

        return array_sum($nilaiValid) / count($nilaiValid);
    }

    public function getOverallAverage()
    {
        $allNilai = [
            $this->nilai_b_indo_5_1, $this->nilai_matematika_5_1, $this->nilai_ipa_5_1,
            $this->nilai_b_indo_5_2, $this->nilai_matematika_5_2, $this->nilai_ipa_5_2,
            $this->nilai_b_indo_6_1, $this->nilai_matematika_6_1, $this->nilai_ipa_6_1,
        ];

        $nilaiValid = array_filter($allNilai, function($n) {
            return $n !== null && $n !== '';
        });

        if (empty($nilaiValid)) {
            return 0;
        }

        return array_sum($nilaiValid) / count($nilaiValid);
    }

    public function render()
    {
        $jalurPendaftaran = JalurPendaftaran::tampilDiDaftar()->ordered()->get();
        $appAcronym = Setting::get('app_acronym', 'SPMBM');

        return view('livewire.auth.register', [
            'jalurPendaftaran' => $jalurPendaftaran,
            'appAcronym' => $appAcronym,
            'jalur_pendaftaran_id' => $this->jalur_pendaftaran_id,
            'hasSelectedJalur' => $this->hasSelectedJalur,
        ])->layout('components.layouts.public')->layoutData(['title' => 'Pendaftaran SPMBM']);
    }
}
