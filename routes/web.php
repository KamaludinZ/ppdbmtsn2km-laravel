<?php

use App\Http\Controllers\ExportController;
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

    return view('public.home', compact('isOpen', 'startDate', 'endDate', 'registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'jalurPendaftaran', 'timelinePendaftaran', 'videoPanduan', 'infografis', 'testimoni', 'loginEnabled', 'loginCaptcha'));
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

    return view('public.informasi', compact('registrationPhase', 'registrationPhaseLabel', 'registrationPhaseDescription', 'kontenInformasi', 'faqs'));
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
        ->where('status_pendaftaran', '!=', 'draft')
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
})->name('login');
Route::get('/login/admin', \App\Livewire\Auth\AdminLogin::class)->name('login.admin'); // Login untuk Admin/Panitia
Route::get('/daftar', Register::class)->name('register');
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
    Route::get('/security', \App\Livewire\Admin\Security::class)->name('admin.security');
    Route::get('/backup-restore', \App\Livewire\Admin\BackupRestore::class)->name('admin.backup-restore');
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
    Route::get('/kartu-ujian', \App\Livewire\Pendaftar\KartuUjian::class)->name('pendaftar.kartu-ujian');
    Route::get('/tes-psikologi', \App\Livewire\Pendaftar\TesPsikologi::class)->name('pendaftar.tes-psikologi');
    Route::get('/tes-psikologi/preview-invoice', [\App\Livewire\Pendaftar\TesPsikologi::class, 'previewInvoice'])->name('pendaftar.tes-psikologi.preview-invoice');
    Route::get('/notifikasi', \App\Livewire\Admin\Notifikasi::class)->name('pendaftar.notifikasi');
});

// Panitia Routes
Route::prefix('panitia')->middleware(['auth', 'role:panitia_pendaftaran,panitia_daftarulang'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Panitia\Dashboard::class)->name('panitia.dashboard');

    Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('panitia.pendaftar');
    Route::get('/pendaftar/{id}', \App\Livewire\Panitia\DetailPendaftar::class)->name('panitia.pendaftar.detail');
    Route::get('/kelulusan', \App\Livewire\Panitia\Kelulusan::class)->name('panitia.kelulusan');
    Route::get('/data-asal-sekolah', \App\Livewire\Panitia\DataAsalSekolah::class)->name('panitia.data-asal-sekolah');
    Route::get('/jalur', \App\Livewire\Panitia\Jalur::class)->name('panitia.jalur');
    Route::get('/jadwal', \App\Livewire\Panitia\Jadwal::class)->name('panitia.jadwal');
    Route::get('/pengumuman', \App\Livewire\Panitia\Pengumuman::class)->name('panitia.pengumuman');
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)->name('panitia.laporan');
    Route::get('/daftar-ulang', \App\Livewire\Panitia\DaftarUlang::class)->name('panitia.daftar-ulang');
    Route::get('/tes-psikologi', \App\Livewire\Panitia\TesPsikologi::class)->name('panitia.tes-psikologi');
    Route::get('/emis-input', \App\Livewire\Panitia\EmisInput::class)->name('panitia.emis-input');
});

// Daftar Ulang Routes
Route::prefix('daftarulang')->middleware(['auth', 'role:panitia_daftarulang'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.panitia-daftarulang.index');
    })->name('daftarulang.dashboard');

    Route::get('/pendaftar', \App\Livewire\Panitia\Pendaftar::class)->name('daftarulang.pendaftar');
    Route::get('/pendaftar/{id}', \App\Livewire\Panitia\DetailPendaftar::class)->name('daftarulang.pendaftar.detail');
    Route::get('/kelulusan', \App\Livewire\Panitia\Kelulusan::class)->name('daftarulang.kelulusan');
    Route::get('/daftar-ulang', \App\Livewire\Panitia\DaftarUlang::class)->name('daftarulang.daftar-ulang');
    Route::get('/pengumuman', \App\Livewire\Panitia\Pengumuman::class)->name('daftarulang.pengumuman');
    Route::get('/laporan', \App\Livewire\Panitia\Laporan::class)->name('daftarulang.laporan');
    Route::get('/emis-input', \App\Livewire\Panitia\EmisInput::class)->name('daftarulang.emis-input');
});

// Export routes - Protected (Admin & Panitia only)
Route::middleware(['auth', 'role:admin,panitia_pendaftaran,panitia_daftarulang'])->group(function () {
    Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('/export/pdf', [ExportController::class, 'generatePDF'])->name('export.pdf');
    Route::get('/export/excel', [ExportController::class, 'generateExcel'])->name('export.excel');
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

    // Check if file exists
    if (!Storage::exists($dokumen->path_file)) {
        abort(404, 'File tidak ditemukan.');
    }

    // Return file for viewing in browser (inline)
    return response()->file(
        storage_path('app/' . $dokumen->path_file),
        [
            'Content-Type' => $dokumen->mime_type ?? 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $dokumen->nama_file . '"'
        ]
    );
})->middleware('auth')->name('dokumen.view');

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

