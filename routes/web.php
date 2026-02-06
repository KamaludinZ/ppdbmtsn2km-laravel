<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\SecureFileController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\TimelinePendaftaranController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    $isOpen = \App\Models\Setting::isRegistrationOpen();
    $startDate = \App\Models\Setting::get('registration_start_date');
    $endDate = \App\Models\Setting::get('registration_end_date');
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    // Ambil jalur pendaftaran yang aktif
    $jalurPendaftaran = \App\Models\JalurPendaftaran::aktif()->ordered()->get();

    // Ambil timeline pendaftaran yang aktif dan diurutkan
    $timelinePendaftaran = \App\Models\TimelinePendaftaran::aktif()->ordered()->get();

    // Ambil video panduan yang aktif dan diurutkan
    $videoPanduan = \App\Models\VideoPanduan::aktif()->ordered()->get();

    // Ambil infografis yang aktif dan diurutkan
    $infografis = \App\Models\Infografis::aktif()->ordered()->get();

    // Ambil testimoni alumni yang aktif dan diurutkan
    $testimoni = \App\Models\Testimoni::aktif()->ordered()->get();

    $loginEnabled = in_array($registrationPhase, ['dibuka', 'proses', 'pengumuman', 'daftar_ulang']);

    // Generate captcha untuk login form di hero section
    $loginCaptcha = sprintf('%06d', rand(100000, 999999));
    session(['login_captcha' => $loginCaptcha]);

    // Add missing variables for the view
    $appAcronym = \App\Models\Setting::get('app_acronym', 'PPDB');
    $appFullName = \App\Models\Setting::get('app_full_name', 'Penerimaan Peserta Didik Baru');
    $appName = \App\Models\Setting::get('app_name', 'PPDB MTsN 2 Kota Malang');

    // Brosur settings
    $brosurActive = \App\Models\Setting::get('brosur_active', false);
    $brosurFilePath = \App\Models\Setting::get('brosur_file_path');

    return view('public.home', compact('isOpen', 'startDate', 'endDate', 'registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'jalurPendaftaran', 'timelinePendaftaran', 'videoPanduan', 'infografis', 'testimoni', 'loginEnabled', 'loginCaptcha', 'appAcronym', 'appFullName', 'appName', 'brosurActive', 'brosurFilePath'));
});

// Jalur Pendaftaran Detail
Route::get('/jalur-pendaftaran/{kode}', function ($kode) {
    $jalur = \App\Models\JalurPendaftaran::where('kode', $kode)->firstOrFail();
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    // Ambil timeline untuk jalur ini atau timeline yang berlaku untuk semua jalur
    $timelineJalur = \App\Models\TimelinePendaftaran::aktif()
        ->where(function($query) use ($kode) {
            $query->where('jalur_kode', $kode)
                  ->orWhereNull('jalur_kode');
        })
        ->ordered()
        ->get();

    // Ambil persyaratan umum yang aktif
    $persyaratanUmum = \App\Models\PersyaratanUmum::aktif()->ordered()->get();

    return view('public.jalur-detail', compact('jalur', 'registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'timelineJalur', 'persyaratanUmum'));
})->name('jalur.detail');

// Kontak Panitia
Route::get('/kontak-panitia', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    return view('public.kontak-panitia', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription'));
})->name('kontak.panitia');

// Informasi & Panduan
Route::get('/informasi', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    // Ambil konten informasi yang aktif
    $kontenInformasi = \App\Models\KontenInformasi::aktif()->ordered()->get();

    // Ambil FAQ yang aktif
    $faqs = \App\Models\Faq::aktif()->ordered()->get();

    // Brosur settings
    $brosurActive = \App\Models\Setting::get('brosur_active', false);
    $brosurFilePath = \App\Models\Setting::get('brosur_file_path');

    return view('public.informasi', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'kontenInformasi', 'faqs', 'brosurActive', 'brosurFilePath'));
})->name('informasi');

// Semua Jalur Pendaftaran
Route::get('/jalur-pendaftaran', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');
    $jalurPendaftaran = \App\Models\JalurPendaftaran::aktif()->ordered()->get();

    return view('public.jalur-list', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'jalurPendaftaran'));
})->name('jalur.list');

// Tentang
Route::get('/tentang', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    // Ambil misi, tujuan, dan fasilitas yang aktif
    $misi = \App\Models\Misi::aktif()->ordered()->get();
    $tujuan = \App\Models\Tujuan::aktif()->ordered()->get();
    $fasilitas = \App\Models\Fasilitas::aktif()->ordered()->get();

    return view('public.tentang', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'misi', 'tujuan', 'fasilitas'));
})->name('tentang');

// Jadwal
Route::get('/jadwal', function () {
    $timelinePendaftaran = \App\Models\TimelinePendaftaran::aktif()->ordered()->get();
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    return view('public.jadwal', compact('timelinePendaftaran', 'registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription'));
})->name('jadwal');

// Alur Pendaftaran
Route::get('/alur-pendaftaran', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    return view('public.alur-pendaftaran', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription'));
})->name('alur.pendaftaran');

// Cek Pendaftaran
Route::get('/cek-pendaftaran', \App\Livewire\Public\CekPendaftaran::class)->name('cek.pendaftaran');

// Cek NSM/NPSN
Route::get('/cek-nsm-npsn', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    // Ambil data sekolah unik dari pendaftar dengan jumlah pendaftar
    $sekolahList = \App\Models\Pendaftar::select(
            'asal_sekolah',
            'jenis_sekolah_asal',
            'nsm_sekolah_asal',
            'npsn_sekolah_asal',
            \DB::raw('COUNT(*) as jumlah_pendaftar')
        )
        ->whereNotNull('jenis_sekolah_asal')
        ->where('status_pendaftaran', '!=', 'menunggu_verifikasi')
        ->where(function($query) {
            $query->whereNotNull('nsm_sekolah_asal')
                  ->orWhereNotNull('npsn_sekolah_asal');
        })
        ->groupBy('asal_sekolah', 'jenis_sekolah_asal', 'nsm_sekolah_asal', 'npsn_sekolah_asal')
        ->orderBy('jumlah_pendaftar', 'desc') // Urutkan berdasarkan jumlah pendaftar terbanyak
        ->orderBy('asal_sekolah')
        ->get();

    return view('public.cek-nsm-npsn', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'sekolahList'));
})->name('cek.nsm.npsn');

// Cek NISN
Route::get('/cek-nisn', function () {
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    return view('public.cek-nisn', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription'));
})->name('cek.nisn');

// Pengumuman
Route::get('/pengumuman', function () {
    $pengumumanPenting = \App\Models\Pengumuman::aktif()
        ->nonPendaftar()
        ->orderByDesc('tanggal_publikasi')
        ->get();
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

    return view('public.pengumuman', compact('pengumumanPenting', 'registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription'));
})->name('pengumuman');

// Auth Routes
Route::get('/login', Login::class)->name('login'); // Login untuk Pendaftar
Route::post('/login', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
        'captcha' => 'required|size:6',
    ]);

    // Verify captcha
    if ($request->captcha !== session('login_captcha')) {
        return back()->withErrors(['captcha' => 'Kode keamanan tidak sesuai'])->withInput();
    }

    // Check if registration is open for login
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    if (!in_array($registrationPhase, ['dibuka', 'proses', 'pengumuman', 'daftar_ulang'])) {
        return back()->withErrors(['email' => 'Login pendaftar sedang tidak aktif. Silakan cek kembali jadwal pendaftaran.'])->withInput();
    }

    // Attempt login
    if (\Illuminate\Support\Facades\Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'aktif' => true])) {
        $request->session()->regenerate();
        $request->session()->forget('login_captcha');

        $user = \Illuminate\Support\Facades\Auth::user();

        // Only allow pendaftar role
        if ($user->isPendaftar()) {
            return redirect()->intended('/pendaftar/dashboard');
        }

        // If admin or panitia, logout and show error
        \Illuminate\Support\Facades\Auth::logout();
        return back()->withErrors(['email' => 'Akses ditolak. Admin dan Panitia silakan login di halaman khusus.'])->withInput();
    }

    return back()->withErrors(['email' => 'Email atau password salah, atau akun tidak aktif.'])->withInput(['email' => $credentials['email']]);
})->name('login.process');
Route::get('/login/admin', \App\Livewire\Auth\AdminLogin::class)->name('login.admin'); // Login untuk Admin/Panitia
// Gunakan RegisterFixed untuk performa dan interaktivitas yang optimal
Route::get('/daftar', \App\Livewire\Auth\RegisterFixed::class)->name('register');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Demo route untuk Livewire component
Route::get('/welcome', function () {
    return view('welcome');
});

// Test Livewire functionality
Route::get('/test-livewire', \App\Livewire\TestLivewire::class)->name('test.livewire');

// Dashboard Routes - Admin Only
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/notifikasi', \App\Livewire\Admin\Notifikasi::class)->name('admin.notifikasi');
    Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('admin.settings');
    Route::get('/pengaturan-pesan', \App\Livewire\Admin\PengaturanPesan::class)->name('admin.pengaturan-pesan');
    Route::get('/pengaturan-kartu', \App\Livewire\Admin\PengaturanKartu::class)->name('admin.pengaturan-kartu');
    Route::get('/kontak', \App\Livewire\Admin\Kontak::class)->name('admin.kontak');
    Route::get('/tentang', \App\Livewire\Admin\Tentang::class)->name('admin.tentang');
    Route::get('/informasi', \App\Livewire\Admin\Informasi::class)->name('admin.informasi');
    Route::get('/jadwal', \App\Livewire\Admin\Jadwal::class)->name('admin.jadwal');
    Route::get('/daftar-ulang', \App\Livewire\Admin\DaftarUlang::class)->name('admin.daftar-ulang');
    Route::get('/kelulusan', \App\Livewire\Admin\Kelulusan::class)->name('admin.kelulusan');
    Route::get('/pengumuman', \App\Livewire\Admin\Pengumuman::class)->name('admin.pengumuman');
    Route::get('/pendaftar', \App\Livewire\Admin\Pendaftar::class)->name('admin.pendaftar');
    Route::get('/pendaftar/{id}', \App\Livewire\Admin\DetailPendaftar::class)->name('admin.pendaftar.detail');
    Route::get('/panitia', \App\Livewire\Admin\Panitia::class)->name('admin.panitia');
    Route::get('/jalur', \App\Livewire\Admin\Jalur::class)->name('admin.jalur');
    Route::get('/laporan', \App\Livewire\Admin\Laporan::class)->name('admin.laporan');
    Route::get('/survey', \App\Livewire\Admin\SurveyManagement::class)->name('admin.survey');
    Route::get('/data-asal-sekolah', \App\Livewire\Admin\DataAsalSekolah::class)->name('admin.data-asal-sekolah');
    Route::get('/video-infografis', \App\Livewire\Admin\VideoInfografis::class)->name('admin.video-infografis');
    Route::get('/monitoring', \App\Livewire\Admin\Monitoring::class)->name('admin.monitoring');
    Route::get('/logs', \App\Livewire\Admin\LogMonitoring::class)->name('admin.logs');
    Route::get('/security', \App\Livewire\Admin\Security::class)->name('admin.security');
    Route::get('/backup-restore', \App\Livewire\Admin\BackupRestore::class)->name('admin.backup-restore');
    Route::get('/backup-restore/download/{filename}', function($filename) {
        $path = storage_path("app/backups/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return response()->download($path);
    })->name('admin.backup.download');
    Route::get('/emis-input', \App\Livewire\Admin\EmisInput::class)->name('admin.emis-input');
    Route::get('/tes-psikologi', \App\Livewire\Admin\TesPsikologi::class)->name('admin.tes-psikologi');
});

// Admin Timeline Pendaftaran Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('timeline-pendaftaran', \App\Http\Controllers\Admin\TimelinePendaftaranController::class);
});

// Pendaftar Routes
Route::prefix('pendaftar')->middleware(['auth', 'role:pendaftar'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Pendaftar\Dashboard::class)->name('pendaftar.dashboard');
    Route::get('/formulir', \App\Livewire\Pendaftar\Formulir::class)->name('pendaftar.formulir');
    Route::get('/dokumen', \App\Livewire\Pendaftar\Dokumen::class)->name('pendaftar.dokumen');
    Route::get('/status', \App\Livewire\Pendaftar\Status::class)->name('pendaftar.status');
    Route::get('/daftarulang', \App\Livewire\Pendaftar\DaftarUlang::class)->name('pendaftar.daftarulang');
    Route::get('/kartu-tes-seleksi', \App\Livewire\Pendaftar\KartuTesSeleksi::class)->name('pendaftar.kartu-tes-seleksi');
    Route::get('/tes-psikologi', \App\Livewire\Pendaftar\TesPsikologi::class)->name('pendaftar.tes-psikologi');
    Route::get('/tes-psikologi/preview-invoice', [\App\Livewire\Pendaftar\TesPsikologi::class, 'previewInvoice'])->name('pendaftar.tes-psikologi.preview-invoice');
    Route::get('/notifikasi', \App\Livewire\Admin\Notifikasi::class)->name('pendaftar.notifikasi');
});

// Panitia Pendaftaran Routes - Full access to registration management
Route::prefix('panitia-pendaftaran')->middleware(['auth', 'role:panitia_pendaftaran'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia-pendaftaran.dashboard');
    Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia-pendaftaran.pendaftar');
    Route::get('/pendaftar/{id}', \App\Livewire\Panitia\DetailPendaftar::class)->name('panitia-pendaftaran.pendaftar.detail');
    Route::get('/kelulusan', \App\Livewire\Panitia\Kelulusan::class)->name('panitia-pendaftaran.kelulusan');
    Route::get('/data-asal-sekolah', \App\Livewire\Panitia\DataAsalSekolah::class)->name('panitia-pendaftaran.data-asal-sekolah');
    Route::get('/jalur', \App\Livewire\Panitia\Jalur::class)->name('panitia-pendaftaran.jalur');
    Route::get('/jadwal', \App\Livewire\Panitia\Jadwal::class)->name('panitia-pendaftaran.jadwal');
    Route::get('/pengumuman', \App\Livewire\Panitia\Pengumuman::class)->name('panitia-pendaftaran.pengumuman');
    Route::get('/daftar-ulang', \App\Livewire\Panitia\DaftarUlang::class)->name('panitia-pendaftaran.daftar-ulang');
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)->name('panitia-pendaftaran.tes-psikologi');
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)->name('panitia-pendaftaran.laporan');
    Route::get('/emis-input', \App\Livewire\Panitia\EmisInput::class)->name('panitia-pendaftaran.emis-input');
});

// Panitia Daftar Ulang Routes - Focus on re-registration
Route::prefix('panitia-daftarulang')->middleware(['auth', 'role:panitia_daftarulang'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia-daftarulang.dashboard');
    Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia-daftarulang.pendaftar');
    Route::get('/pendaftar/{id}', \App\Livewire\Panitia\DetailPendaftar::class)->name('panitia-daftarulang.pendaftar.detail');
    Route::get('/kelulusan', \App\Livewire\Panitia\Kelulusan::class)->name('panitia-daftarulang.kelulusan');
    Route::get('/daftar-ulang', \App\Livewire\Panitia\DaftarUlang::class)->name('panitia-daftarulang.daftar-ulang');
    Route::get('/pengumuman', \App\Livewire\Panitia\Pengumuman::class)->name('panitia-daftarulang.pengumuman');
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)->name('panitia-daftarulang.laporan');
    Route::get('/emis-input', \App\Livewire\Panitia\EmisInput::class)->name('panitia-daftarulang.emis-input');
});

// Panitia Tes Psikologi Routes - Focus on psychology test management
Route::prefix('panitia-psikologi')->middleware(['auth', 'role:panitia_tes_psikologi'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia-psikologi.dashboard');
    Route::get('/kelulusan', \App\Livewire\Panitia\Kelulusan::class)->name('panitia-psikologi.kelulusan');
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)->name('panitia-psikologi.tes-psikologi');
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)->name('panitia-psikologi.laporan');
});

// Panitia Seragam Routes - Fokus pada manajemen seragam dan ukuran
Route::prefix('panitia-seragam')->middleware(['auth', 'role:panitia_seragam'])->group(function () {
    // Dashboard - Overview data seragam
    Route::get('/dashboard', \App\Livewire\PanitiaSeragam\Dashboard::class)->name('panitia-seragam.dashboard');

    // Kelulusan - Daftar calon siswa yang lulus (readonly)
    Route::get('/kelulusan', \App\Livewire\PanitiaSeragam\Kelulusan::class)->name('panitia-seragam.kelulusan');

    // Daftar Ulang - Hanya 2 tab (Daftar Ulang dan Ukuran Seragam & Atribut)
    Route::get('/daftar-ulang', \App\Livewire\PanitiaSeragam\DaftarUlang::class)->name('panitia-seragam.daftar-ulang');

    // Laporan - Laporan terkait seragam
    Route::get('/laporan', \App\Livewire\PanitiaSeragam\Laporan::class)->name('panitia-seragam.laporan');
});

// Export routes - Protected (Admin & Panitia only)
Route::middleware(['auth', 'role:admin,panitia_pendaftaran,panitia_daftarulang'])->group(function () {
    Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('/export/pdf', [ExportController::class, 'generatePDF'])->name('export.pdf');
    Route::get('/export/excel', [ExportController::class, 'generateExcel'])->name('export.excel');
});

// Secure File Download Routes - Protected with Authorization via SecureFileController
Route::middleware(['auth'])->group(function () {
    Route::get('/download/dokumen/{id}', [\App\Http\Controllers\SecureFileController::class, 'downloadDokumen'])
        ->name('download.dokumen');

    Route::get('/view/dokumen/{id}', [\App\Http\Controllers\SecureFileController::class, 'viewDokumen'])
        ->name('view.dokumen');

    Route::get('/download/bukti-bayar/{pendaftar}/{type}', [\App\Http\Controllers\SecureFileController::class, 'downloadBuktiBayar'])
        ->name('download.bukti-bayar');

    Route::get('/view/bukti-bayar/{pendaftar}/{type}', [\App\Http\Controllers\SecureFileController::class, 'viewBuktiBayar'])
        ->name('view.bukti-bayar');
});

// Dokumen download route - Protected dengan authorization
Route::get('/dokumen/{id}/download', function ($id) {
    $dokumen = \App\Models\Dokumen::findOrFail($id);
    $user = auth()->user();

    // Authorization check
    if (!$user) {
        abort(403, 'Silakan login terlebih dahulu.');
    }

    // Pendaftar only can access their own documents
    if ($user->role === 'pendaftar') {
        if ($user->pendaftar->id !== $dokumen->pendaftar_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }
    }

    // Admin and Panitia can access all documents
    if (!in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'pendaftar'])) {
        abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
    }

    // Check if file exists
    if (!Storage::exists($dokumen->path_file)) {
        abort(404, 'File tidak ditemukan.');
    }

    // Return file for download
    return response()->download(
        storage_path('app/' . $dokumen->path_file),
        $dokumen->nama_file,
        [
            'Content-Type' => $dokumen->mime_type ?? 'application/pdf',
        ]
    );
})->middleware('auth')->name('dokumen.download');

// Dokumen view route - Protected untuk view in browser
Route::get('/dokumen/{id}/view', function ($id) {
    $dokumen = \App\Models\Dokumen::findOrFail($id);
    $user = auth()->user();

    // Authorization check
    if (!$user) {
        abort(403, 'Silakan login terlebih dahulu.');
    }

    // Pendaftar only can access their own documents
    if ($user->role === 'pendaftar') {
        if ($user->pendaftar->id !== $dokumen->pendaftar_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }
    }

    // Admin and Panitia can access all documents
    if (!in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'pendaftar'])) {
        abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
    }

    // Check if file exists in private storage
    if (!Storage::disk('private')->exists($dokumen->path_file)) {
        \Log::error("VIEW: File not found in storage! Path: " . $dokumen->path_file);
        abort(404, 'File tidak ditemukan.');
    }

    $fullPath = storage_path('app/private/' . $dokumen->path_file);
    $fileSize = Storage::disk('private')->size($dokumen->path_file);

    \Log::info("VIEW DOKUMEN ID " . $id . ":");
    \Log::info("  - Database path: " . $dokumen->path_file);
    \Log::info("  - Database size: " . $dokumen->ukuran_file . " bytes");
    \Log::info("  - Actual size: " . $fileSize . " bytes");
    \Log::info("  - Full path: " . $fullPath);
    \Log::info("  - File exists: " . (file_exists($fullPath) ? 'YES' : 'NO'));

    // Return file for viewing in browser (inline) from private storage
    // Add no-cache headers to prevent browser from caching the PDF
    return response()->file(
        $fullPath,
        [
            'Content-Type' => $dokumen->mime_type ?? 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $dokumen->nama_file . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]
    );
})->middleware('auth')->name('dokumen.view');

// Generic file view route for Prestasi, Keahlian, Tahfidz - Protected with auth
Route::get('/file/view/{model}/{id}/{field}', function ($model, $id, $field) {
    $user = auth()->user();

    if (!$user) {
        abort(403, 'Silakan login terlebih dahulu.');
    }

    // Map model name to class
    $modelMap = [
        'prestasi' => \App\Models\Prestasi::class,
        'keahlian' => \App\Models\Keahlian::class,
        'tahfidz' => \App\Models\Tahfidz::class,
    ];

    if (!isset($modelMap[$model])) {
        abort(404, 'Model tidak ditemukan.');
    }

    $modelClass = $modelMap[$model];
    $record = $modelClass::findOrFail($id);

    // Authorization check - Admin, Panitia can access all, Pendaftar only their own
    if ($user->role === 'pendaftar') {
        if ($user->pendaftar->id !== $record->pendaftar_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }
    } elseif (!in_array($user->role, ['admin', 'panitia', 'panitia_pendaftaran', 'panitia_seragam', 'panitia_tes_psikologi'])) {
        abort(403, 'Anda tidak memiliki akses ke file ini.');
    }

    // Get file path from field
    $filePath = $record->$field;

    if (!$filePath) {
        abort(404, 'File tidak ditemukan.');
    }

    // Try both private and public storage for backward compatibility
    $disk = 'private';
    if (!Storage::disk('private')->exists($filePath)) {
        if (Storage::disk('public')->exists($filePath)) {
            $disk = 'public';
        } else {
            abort(404, 'File tidak ditemukan di storage.');
        }
    }

    // Get file and mime type
    $file = Storage::disk($disk)->get($filePath);
    $mimeType = Storage::disk($disk)->mimeType($filePath);

    // Return file for viewing in browser with no-cache headers
    return response($file, 200)
        ->header('Content-Type', $mimeType)
        ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->middleware('auth')->name('file.view');

// Daftar Ulang file view route - Protected untuk pendaftar melihat file mereka
Route::get('/pendaftar/daftarulang/view-file/{type}/{file}', function ($type, $file) {
    $user = auth()->user();

    // Authorization check
    if (!$user || $user->role !== 'pendaftar') {
        abort(403, 'Anda tidak memiliki akses ke file ini.');
    }

    $pendaftar = $user->pendaftar;

    // Determine file path based on type
    $filePath = null;
    $displayName = $file;

    if ($type === 'madrasah') {
        // Bukti pembayaran madrasah
        $daftarUlang = $pendaftar->daftarUlang;
        if ($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah) {
            $filePath = $daftarUlang->bukti_pembayaran_madrasah;
        }
    } elseif ($type === 'pernyataan') {
        // Surat pernyataan
        $daftarUlang = $pendaftar->daftarUlang;
        if ($daftarUlang && $daftarUlang->surat_pernyataan_path) {
            $filePath = $daftarUlang->surat_pernyataan_path;
        }
    } elseif ($type === 'seragam') {
        // Bukti pembayaran seragam
        $seragam = $pendaftar->seragam;
        if ($seragam && $seragam->bukti_pembayaran) {
            $filePath = $seragam->bukti_pembayaran;
        }
    }

    if (!$filePath) {
        abort(404, 'File tidak ditemukan.');
    }

    // Check if file exists in private storage
    if (!Storage::disk('private')->exists($filePath)) {
        abort(404, 'File tidak ditemukan di storage.');
    }

    // Get full path to file
    $fullPath = storage_path('app/private/' . $filePath);

    // Determine mime type based on extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeType = match(strtolower($extension)) {
        'pdf' => 'application/pdf',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        default => 'application/octet-stream',
    };

    // Return file for viewing in browser (inline)
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $displayName . '"'
    ]);
})->middleware('auth')->name('pendaftar.daftarulang.view-file');

// Download file route for admin/panitia - Generic route to download daftar ulang files
Route::get('/download-file/{type}/{pendaftarId?}', function ($type, $pendaftarId = null) {
    $user = auth()->user();

    // Authorization check - Allow admin and panitia roles
    if (!$user || !in_array($user->role, ['admin', 'panitia', 'panitia_daftarulang', 'panitia_seragam', 'pendaftar'])) {
        abort(403, 'Anda tidak memiliki akses ke file ini.');
    }

    // If pendaftar, use their own ID
    if ($user->role === 'pendaftar') {
        $pendaftar = $user->pendaftar;
    } else {
        // For admin/panitia, get pendaftar from ID if provided, or from current page context
        if ($pendaftarId) {
            $pendaftar = \App\Models\Pendaftar::findOrFail($pendaftarId);
        } else {
            // Try to get from referer or session
            abort(404, 'Pendaftar ID tidak ditemukan.');
        }
    }

    // Determine file path based on type
    $filePath = null;
    $fileName = null;

    switch ($type) {
        case 'surat_pernyataan':
            $daftarUlang = $pendaftar->daftarUlang;
            if ($daftarUlang && $daftarUlang->surat_pernyataan_path) {
                $filePath = $daftarUlang->surat_pernyataan_path;
                $fileName = 'surat_pernyataan_' . $pendaftar->nomor_pendaftaran . '.pdf';
            }
            break;

        case 'bukti_pembayaran_madrasah':
            $daftarUlang = $pendaftar->daftarUlang;
            if ($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah) {
                $filePath = $daftarUlang->bukti_pembayaran_madrasah;
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileName = 'bukti_pembayaran_madrasah_' . $pendaftar->nomor_pendaftaran . '.' . $extension;
            }
            break;

        case 'bukti_pembayaran_seragam':
            $seragam = $pendaftar->seragam;
            if ($seragam && $seragam->bukti_pembayaran) {
                $filePath = $seragam->bukti_pembayaran;
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileName = 'bukti_pembayaran_seragam_' . $pendaftar->nomor_pendaftaran . '.' . $extension;
            }
            break;

        default:
            abort(404, 'Tipe file tidak valid.');
    }

    if (!$filePath) {
        abort(404, 'File tidak ditemukan.');
    }

    // Check if file exists in private storage
    if (!Storage::disk('private')->exists($filePath)) {
        abort(404, 'File tidak ditemukan di storage: ' . $filePath);
    }

    // Get full path to file
    $fullPath = storage_path('app/private/' . $filePath);

    // Determine mime type based on extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $mimeType = match(strtolower($extension)) {
        'pdf' => 'application/pdf',
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        default => 'application/octet-stream',
    };

    // Return file for viewing in browser (inline)
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Content-Disposition' => 'inline; filename="' . $fileName . '"'
    ]);
})->middleware('auth')->name('pendaftar.download-file');

// API untuk auto-fill nama sekolah berdasarkan NSM/NPSN
Route::get('/api/cek-sekolah', function () {
    $jenisSekolah = request('jenis_sekolah_asal');
    $nomorSekolah = request('nomor_sekolah');

    if (!$jenisSekolah || !$nomorSekolah) {
        return response()->json([
            'found' => false,
            'message' => 'Parameter tidak lengkap'
        ]);
    }

    $query = \App\Models\Pendaftar::whereNotNull('jenis_sekolah_asal')
        ->whereNotNull('asal_sekolah')
        ->where('jenis_sekolah_asal', $jenisSekolah);

    if ($jenisSekolah === 'madrasah') {
        $query->where('nsm_sekolah_asal', $nomorSekolah);
    } else {
        $query->where('npsn_sekolah_asal', $nomorSekolah);
    }

    $sekolah = $query->first();

    if ($sekolah) {
        return response()->json([
            'found' => true,
            'nama_sekolah' => $sekolah->asal_sekolah,
            'jenis_sekolah' => $sekolah->jenis_sekolah_asal,
            'nsm' => $sekolah->nsm_sekolah_asal,
            'npsn' => $sekolah->npsn_sekolah_asal
        ]);
    }

    return response()->json([
        'found' => false,
        'message' => 'Sekolah tidak ditemukan'
    ]);
})->name('api.cek.sekolah');

// Test route untuk debug jalur daftar
// require __DIR__ . '/test_jalur.php'; // Disabled for production
