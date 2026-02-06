<div>
    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-6 text-center">
            @php
                $statusColors = [
                    'draft' => 'bg-gray-100 text-gray-800',
                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                    'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
                    'verifikasi' => 'bg-yellow-100 text-yellow-800',
                    'lengkapi_data' => 'bg-orange-100 text-orange-800',
                    'perbaiki_data' => 'bg-amber-100 text-amber-800',
                    'data_ditolak' => 'bg-red-100 text-red-800',
                    'data_disetujui' => 'bg-green-100 text-green-800',
                    'diverifikasi' => 'bg-blue-100 text-blue-800',
                    'lulus' => 'bg-green-100 text-green-800',
                    'diterima' => 'bg-green-100 text-green-800',
                    'tidak_lulus' => 'bg-red-100 text-red-800',
                    'ditolak' => 'bg-red-100 text-red-800',
                    'cadangan' => 'bg-orange-100 text-orange-800',
                ];
                $statusLabels = [
                    'draft' => 'Draft (Belum Diajukan)',
                    'menunggu' => 'Menunggu Verifikasi',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'verifikasi' => 'Menunggu Verifikasi',
                    'diverifikasi' => 'Terverifikasi (Siap Ujian)',
                    'lengkapi_data' => 'Lengkapi Data',
                    'perbaiki_data' => 'Perbaiki Data',
                    'data_ditolak' => 'Data Ditolak',
                    'data_disetujui' => 'Data Disetujui',
                    'lulus' => 'Lulus Seleksi',
                    'diterima' => 'Lulus Seleksi',
                    'tidak_lulus' => 'Tidak Lulus',
                    'ditolak' => 'Tidak Lulus',
                    'cadangan' => 'Cadangan',
                ];
                $color = $statusColors[$pendaftar->status_pendaftaran] ?? 'bg-gray-100 text-gray-800';
                $label = $statusLabels[$pendaftar->status_pendaftaran] ?? ucwords(str_replace('_', ' ', $pendaftar->status_pendaftaran));
            @endphp
            
            <h2 class="text-lg font-medium text-gray-600 mb-2">Status Saat Ini</h2>
            <span class="px-6 py-3 rounded-full text-xl font-bold {{ $color }}">
                {{ $label }}
            </span>
            
            <p class="mt-4 text-gray-500">
                @if($pendaftar->status_pendaftaran === 'draft')
                    Silakan lengkapi formulir dan upload dokumen, lalu tunggu verifikasi panitia.
                @elseif(in_array($pendaftar->status_pendaftaran, ['menunggu', 'menunggu_verifikasi', 'verifikasi']))
                    Data Anda sedang dalam proses verifikasi oleh panitia. Mohon tunggu informasi selanjutnya.
                @elseif(in_array($pendaftar->status_pendaftaran, ['lengkapi_data', 'perbaiki_data']))
                    Silakan periksa catatan dari panitia dan perbaiki data Anda sesuai dengan petunjuk yang diberikan.
                @elseif($pendaftar->status_pendaftaran === 'data_ditolak')
                    Data Anda tidak memenuhi persyaratan. Silakan hubungi panitia untuk informasi lebih lanjut.
                @elseif(in_array($pendaftar->status_pendaftaran, ['diverifikasi', 'data_disetujui']))
                    Data Anda telah diverifikasi. Silakan cetak kartu ujian dan persiapkan diri untuk tes seleksi.
                @elseif(in_array($pendaftar->status_pendaftaran, ['lulus', 'diterima']))
                    Selamat! Anda dinyatakan LULUS. Silakan lanjutkan ke menu Daftar Ulang.
                @elseif(in_array($pendaftar->status_pendaftaran, ['tidak_lulus', 'ditolak']))
                    Mohon maaf, Anda belum lulus seleksi. Tetap semangat!
                @elseif($pendaftar->status_pendaftaran === 'cadangan')
                    Anda berada dalam daftar cadangan. Silakan tunggu informasi lebih lanjut dari panitia.
                @endif
            </p>
        </div>
    </div>

    <!-- Catatan Verifikasi -->
    @if(in_array($pendaftar->status_pendaftaran, ['lengkapi_data', 'perbaiki_data', 'data_ditolak']) && !empty($pendaftar->catatan_verifikasi))
        <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 mb-6 text-white border-4 border-yellow-400">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-3 flex items-center gap-2">
                        📝 Catatan dari Panitia
                    </h3>
                    <div class="bg-yellow-50 rounded-lg p-4 border-2 border-yellow-400 shadow-md">
                        <p class="text-gray-800 font-semibold leading-relaxed whitespace-pre-line text-sm">{{ $pendaftar->catatan_verifikasi }}</p>
                    </div>
                    <p class="text-yellow-100 text-sm mt-3 font-medium">
                        💡 Silakan perbaiki data Anda sesuai dengan catatan di atas dan simpan kembali.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Verifikasi Dokumen -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Detail Verifikasi Dokumen</h3>
            </div>
            <a href="/pendaftar/dokumen" class="text-xs font-bold text-emerald-100 hover:text-white uppercase tracking-widest bg-emerald-700 px-3 py-1 rounded-full transition-colors">
                Kelola →
            </a>
        </div>
        <div class="p-6">
            @php
                // Helper function untuk cek dokumen sudah diupload atau belum
                $cekDokumen = function($jenis) use ($pendaftar) {
                    if ($jenis === 'rapor') {
                        // Rapor ada di field khusus di tabel pendaftar
                        return $pendaftar->file_rapor ? true : false;
                    }
                    // Dokumen lainnya ada di tabel dokumen
                    return $pendaftar->dokumen->where('jenis_dokumen', $jenis)->first() ? true : false;
                };

                // Mapping jenis dokumen ke field status di verifikasi_berkas
                $statusMapping = [
                    'rapor' => 'status_rapor',
                    'akta_kelahiran' => 'status_akte',
                    'kartu_keluarga' => 'status_kk',
                    'nisn' => 'status_nisn',
                    'ijazah' => 'status_ijazah',
                    'sertifikat' => 'status_sertifikat',
                    'kip' => 'status_kip',
                    'pkh' => 'status_pkh',
                    'kks' => 'status_kks',
                ];

                // Get verifikasi berkas data
                $verifikasi = $pendaftar->verifikasiBerkas;

                // Status options dengan warna
                $statusOptions = [
                    'belum_upload' => ['label' => 'Belum Upload', 'color' => 'bg-gray-100 text-gray-800', 'icon_color' => 'text-gray-400'],
                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'bg-yellow-100 text-yellow-800', 'icon_color' => 'text-yellow-600'],
                    'tidak_sesuai' => ['label' => 'Tidak Sesuai', 'color' => 'bg-red-100 text-red-800', 'icon_color' => 'text-red-600'],
                    'perlu_perbaikan' => ['label' => 'Perlu Perbaikan', 'color' => 'bg-orange-100 text-orange-800', 'icon_color' => 'text-orange-600'],
                    'disetujui' => ['label' => 'Disetujui', 'color' => 'bg-green-100 text-green-800', 'icon_color' => 'text-green-600'],
                ];

                // Hitung progress
                $uploadedCount = 0;
                $uploadedWajib = 0;
                $totalWajib = 0;
                foreach ($documentLabels as $jenis => $dokInfo) {
                    if ($dokInfo['wajib']) {
                        $totalWajib++;
                        if ($cekDokumen($jenis)) {
                            $uploadedWajib++;
                        }
                    }
                    if ($cekDokumen($jenis)) {
                        $uploadedCount++;
                    }
                }
            @endphp

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Upload Dokumen Wajib</span>
                    <span class="text-sm font-bold {{ $uploadedWajib == $totalWajib ? 'text-green-600' : 'text-orange-600' }}">{{ $uploadedWajib }}/{{ $totalWajib }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $totalWajib > 0 ? ($uploadedWajib / $totalWajib) * 100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Daftar Dokumen Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($documentLabels as $jenis => $dokInfo)
                    @php
                        $isUploaded = $cekDokumen($jenis);

                        // Check if this is a conditional upload
                        $isConditional = isset($dokInfo['conditional']);
                        $isEnabled = true;
                        if ($isConditional) {
                            $conditionalField = $dokInfo['conditional'];
                            $isEnabled = !empty($pendaftar->$conditionalField);
                        }

                        // Get status verifikasi
                        $statusField = $statusMapping[$jenis] ?? null;
                        $currentStatus = 'belum_upload';
                        $catatan = null;
                        if ($verifikasi && $statusField) {
                            $currentStatus = $verifikasi->$statusField ?? 'belum_upload';
                            $catatanField = str_replace('status_', 'catatan_', $statusField);
                            $catatan = $verifikasi->$catatanField ?? null;
                        }
                        $statusInfo = $statusOptions[$currentStatus] ?? $statusOptions['belum_upload'];

                        // Determine background color
                        $bgColor = 'bg-gray-50 border-gray-200';
                        if ($currentStatus === 'disetujui') {
                            $bgColor = 'bg-green-50 border-green-200';
                        } elseif ($currentStatus === 'menunggu_verifikasi') {
                            $bgColor = 'bg-yellow-50 border-yellow-200';
                        } elseif (in_array($currentStatus, ['tidak_sesuai', 'perlu_perbaikan'])) {
                            $bgColor = 'bg-red-50 border-red-200';
                        } elseif ($isConditional && !$isEnabled) {
                            $bgColor = 'bg-blue-50 border-blue-200';
                        } elseif ($dokInfo['wajib'] && $currentStatus === 'belum_upload') {
                            $bgColor = 'bg-orange-50 border-orange-200';
                        }
                    @endphp
                    <div class="flex flex-col p-3 {{ $bgColor }} border rounded-lg">
                        <div class="flex items-start justify-between">
                            <!-- Label Dokumen -->
                            <div class="flex-1">
                                <div class="flex items-center flex-wrap gap-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900">{{ $dokInfo['label'] }}</span>
                                    @if($dokInfo['wajib'])
                                        <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">WAJIB</span>
                                    @elseif($isConditional && !$isEnabled)
                                        <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full font-medium">TERKUNCI</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="ml-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusInfo['color'] }}">
                                    @if($currentStatus === 'disetujui')
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    @elseif($currentStatus === 'menunggu_verifikasi')
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                    @elseif(in_array($currentStatus, ['tidak_sesuai', 'perlu_perbaikan']))
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    @endif
                                    {{ $statusInfo['label'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Catatan Verifikasi -->
                        @if($catatan && in_array($currentStatus, ['tidak_sesuai', 'perlu_perbaikan']))
                            <div class="mt-2 pt-2 border-t border-current border-opacity-20">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 {{ $statusInfo['icon_color'] }} mr-1.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-xs {{ $statusInfo['icon_color'] }} font-medium">
                                        <span class="font-bold">Catatan Panitia:</span> {{ $catatan }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detail Status Tes Psikologi -->
    @if($tesPsikologiAktif)
    <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Detail Status Tes Psikologi</h3>
            </div>
            <a href="/pendaftar/tes-psikologi" class="text-xs font-bold text-purple-100 hover:text-white uppercase tracking-widest bg-purple-700 px-3 py-1 rounded-full transition-colors">
                Kelola →
            </a>
        </div>
        <div class="p-6">
            <div class="flex flex-col p-3 border rounded-lg {{ $pendaftar->tesPsikologiPendaftar ? ($pendaftar->tesPsikologiPendaftar->status_verifikasi == 'disetujui' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200') : 'bg-gray-50 border-gray-200' }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Pembayaran Tes Psikologi</span>
                    @php
                        $statusPsi = $pendaftar->tesPsikologiPendaftar->status_verifikasi ?? 'belum_upload';
                        $statusPsiLabels = [
                            'belum_upload' => ['label' => 'Belum Upload', 'color' => 'bg-gray-100 text-gray-800'],
                            'menunggu' => ['label' => 'Menunggu Verifikasi', 'color' => 'bg-yellow-100 text-yellow-800'],
                            'disetujui' => ['label' => 'Lunas / Disetujui', 'color' => 'bg-green-100 text-green-800'],
                            'ditolak' => ['label' => 'Ditolak', 'color' => 'bg-red-100 text-red-800'],
                        ];
                        $badgePsi = $statusPsiLabels[$statusPsi] ?? $statusPsiLabels['belum_upload'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgePsi['color'] }}">
                        {{ $badgePsi['label'] }}
                    </span>
                </div>
                @if($pendaftar->tesPsikologiPendaftar && $pendaftar->tesPsikologiPendaftar->catatan_verifikasi)
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <p class="text-xs text-gray-600"><span class="font-bold">Catatan:</span> {{ $pendaftar->tesPsikologiPendaftar->catatan_verifikasi }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Detail Status Kelulusan -->
    <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Detail Status Kelulusan</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col p-3 border rounded-lg bg-gray-50 border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Hasil Seleksi</span>
                    @php
                        $statusKelulusan = 'Belum Tes';
                        $colorKelulusan = 'bg-gray-100 text-gray-800';
                        
                        if (in_array($pendaftar->status_pendaftaran, ['lulus', 'diterima'])) {
                            $statusKelulusan = 'LULUS';
                            $colorKelulusan = 'bg-green-100 text-green-800';
                        } elseif (in_array($pendaftar->status_pendaftaran, ['tidak_lulus', 'ditolak'])) {
                            $statusKelulusan = 'TIDAK LULUS';
                            $colorKelulusan = 'bg-red-100 text-red-800';
                        } elseif ($pendaftar->status_pendaftaran == 'cadangan') {
                            $statusKelulusan = 'CADANGAN';
                            $colorKelulusan = 'bg-orange-100 text-orange-800';
                        }
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $colorKelulusan }}">
                        {{ $statusKelulusan }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Status Daftar Ulang -->
    <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-orange-600 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Detail Status Daftar Ulang</h3>
            </div>
            @if(in_array($pendaftar->status_pendaftaran, ['lulus', 'diterima']))
                <a href="/pendaftar/daftarulang" class="text-xs font-bold text-orange-100 hover:text-white uppercase tracking-widest bg-orange-700 px-3 py-1 rounded-full transition-colors">
                    Kelola →
                </a>
            @endif
        </div>
        <div class="p-6">
            @php
                $isLulus = in_array($pendaftar->status_pendaftaran, ['lulus', 'diterima']);
                $daftarUlang = $pendaftar->daftarUlang;
                $seragam = $pendaftar->seragam;
            @endphp

            @if(!$isLulus)
                <!-- Terkunci Message -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">Daftar ulang belum dapat diakses</p>
                    <p class="text-sm text-gray-500 mt-1">Selesaikan proses seleksi terlebih dahulu</p>
                </div>
            @else
                <!-- 3 Status Columns -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Status Daftar Ulang Madrasah -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-700">Daftar Ulang Madrasah</h4>
                        </div>
                        @php
                            // Determine status based on bukti pembayaran and actual status
                            if ($daftarUlang && ($daftarUlang->status === 'disetujui' || $daftarUlang->status === 'selesai')) {
                                $statusMadrasah = 'disetujui';
                            } elseif ($daftarUlang && $daftarUlang->status === 'ditolak') {
                                $statusMadrasah = 'ditolak';
                            } elseif ($daftarUlang && $daftarUlang->status === 'perbaiki') {
                                $statusMadrasah = 'perbaiki';
                            } elseif ($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah) {
                                // If bukti uploaded but status not set or 'menunggu'
                                $statusMadrasah = 'menunggu';
                            } else {
                                // No bukti uploaded yet
                                $statusMadrasah = 'belum_upload';
                            }

                            $statusMadrasahInfo = match($statusMadrasah) {
                                'disetujui' => ['label' => 'DISETUJUI', 'color' => 'bg-green-100 text-green-800 border-green-300'],
                                'ditolak' => ['label' => 'DITOLAK', 'color' => 'bg-red-100 text-red-800 border-red-300'],
                                'perbaiki' => ['label' => 'PERLU PERBAIKI', 'color' => 'bg-orange-100 text-orange-800 border-orange-300'],
                                'menunggu' => ['label' => 'MENUNGGU', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                                default => ['label' => 'BELUM UPLOAD', 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
                            };
                        @endphp
                        <div class="flex items-center justify-center py-3">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusMadrasahInfo['color'] }}">
                                {{ $statusMadrasahInfo['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Daftar Ulang Seragam -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-700">Daftar Ulang Seragam</h4>
                        </div>
                        @php
                            // Determine status based on bukti pembayaran and actual status
                            if ($seragam && ($seragam->status_seragam === 'disetujui' || $seragam->status_seragam === 'selesai')) {
                                $statusSeragam = 'disetujui';
                            } elseif ($seragam && $seragam->status_seragam === 'ditolak') {
                                $statusSeragam = 'ditolak';
                            } elseif ($seragam && $seragam->status_seragam === 'perbaiki') {
                                $statusSeragam = 'perbaiki';
                            } elseif ($seragam && $seragam->bukti_pembayaran) {
                                // If bukti uploaded but status not set or 'menunggu'
                                $statusSeragam = 'menunggu';
                            } else {
                                // No bukti uploaded yet
                                $statusSeragam = 'belum_upload';
                            }

                            $statusSeragamInfo = match($statusSeragam) {
                                'disetujui' => ['label' => 'DISETUJUI', 'color' => 'bg-green-100 text-green-800 border-green-300'],
                                'ditolak' => ['label' => 'DITOLAK', 'color' => 'bg-red-100 text-red-800 border-red-300'],
                                'perbaiki' => ['label' => 'PERLU PERBAIKI', 'color' => 'bg-orange-100 text-orange-800 border-orange-300'],
                                'menunggu' => ['label' => 'MENUNGGU', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                                default => ['label' => 'BELUM UPLOAD', 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
                            };
                        @endphp
                        <div class="flex items-center justify-center py-3">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusSeragamInfo['color'] }}">
                                {{ $statusSeragamInfo['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Ukuran Seragam -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                            <h4 class="text-sm font-semibold text-gray-700">Ukuran Seragam</h4>
                        </div>
                        @php
                            // Determine status based on ukuran data and actual status
                            if ($seragam && ($seragam->status_ukuran === 'disetujui' || $seragam->status_ukuran === 'selesai')) {
                                $statusUkuran = 'disetujui';
                            } elseif ($seragam && $seragam->status_ukuran === 'ditolak') {
                                $statusUkuran = 'ditolak';
                            } elseif ($seragam && $seragam->status_ukuran === 'perbaiki') {
                                $statusUkuran = 'perbaiki';
                            } elseif ($seragam && (
                                $seragam->ukuran_jas ||
                                $seragam->ukuran_baju_olahraga ||
                                $seragam->ukuran_celana_olahraga ||
                                $seragam->ukuran_baju_harian ||
                                $seragam->ukuran_celana_rok_harian ||
                                $seragam->ukuran_kopyah
                            )) {
                                // If any ukuran data filled but status not set or 'menunggu'
                                $statusUkuran = 'menunggu';
                            } else {
                                // No ukuran data filled yet
                                $statusUkuran = 'belum_isi';
                            }

                            $statusUkuranInfo = match($statusUkuran) {
                                'disetujui' => ['label' => 'DISETUJUI', 'color' => 'bg-green-100 text-green-800 border-green-300'],
                                'ditolak' => ['label' => 'DITOLAK', 'color' => 'bg-red-100 text-red-800 border-red-300'],
                                'perbaiki' => ['label' => 'PERLU PERBAIKI', 'color' => 'bg-orange-100 text-orange-800 border-orange-300'],
                                'menunggu' => ['label' => 'MENUNGGU', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
                                default => ['label' => 'BELUM ISI', 'color' => 'bg-gray-100 text-gray-800 border-gray-300'],
                            };
                        @endphp
                        <div class="flex items-center justify-center py-3">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusUkuranInfo['color'] }}">
                                {{ $statusUkuranInfo['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
