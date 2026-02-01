<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{
                show: false,
                message: '',
                type: 'success',
                init() {
                    window.addEventListener('statusUpdated', (event) => {
                        this.type = event.detail[0].type || 'success';
                        this.message = event.detail[0].message;
                        this.show = true;
                        setTimeout(() => this.show = false, 3000);
                    });
                }
            }"
            x-show="show"
            x-transition
            :class="{
                'bg-emerald-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-blue-500': type === 'info'
            }"
            class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center max-w-md"
            style="display: none;">
            <svg x-show="type === 'success'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span x-text="message" class="flex-1"></span>
        </div>

        <!-- Status Alert - Check if any document needs attention -->
        @php
            $hasIssue = false;
            $issueStatus = '';
            $issueCatatan = '';

            foreach(['rapor', 'akte', 'kk', 'nisn', 'ijazah', 'sertifikat', 'kip', 'pkh', 'kks'] as $docType) {
                $statusField = 'status_' . $docType;
                $catatanField = 'catatan_' . $docType;
                $status = $verifikasi->$statusField ?? '';

                if (in_array($status, ['ditolak', 'perbaiki', 'menunggu_verifikasi', 'belum_upload'])) {
                    $hasIssue = true;
                    $issueStatus = $status;
                    $issueCatatan = $verifikasi->$catatanField ?? '';
                    break;
                }
            }
        @endphp

        @if($hasIssue)
            <x-status-alert
                :status="$issueStatus"
                :catatan="$issueCatatan"
                type="upload_berkas"
            />
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Berkas Persyaratan</h3>
                <p class="text-emerald-100 text-sm">Pastikan semua dokumen yang wajib diupload sudah sesuai dan terbaca dengan jelas.</p>
            </div>
            
            <div class="p-6">
                <!-- Info Box -->
                <div class="space-y-4 mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 font-semibold mb-1">Informasi Upload Dokumen:</p>
                                <ul class="text-sm text-blue-700 space-y-1 list-disc list-inside">
                                    <li>Format file: <strong>PDF SAJA</strong></li>
                                    <li>Ukuran maksimal: <strong>2 MB</strong> per file</li>
                                    <li>Scan dokumen Anda terlebih dahulu, lalu <strong>simpan/convert ke PDF</strong></li>
                                    <li>Pastikan dokumen <strong>jelas, terbaca, dan tidak terpotong</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800">
                                    <strong>PERHATIAN!</strong> File yang bukan format PDF atau lebih dari 2 MB akan <strong>ditolak</strong> oleh sistem. Gunakan aplikasi scanner atau tools online untuk convert gambar ke PDF terlebih dahulu.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Penting!</strong> Dokumen dengan badge <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold">WAJIB</span> harus diupload untuk melanjutkan proses seleksi. Dokumen <span class="px-2 py-0.5 text-xs bg-gray-400 text-white rounded-full font-medium">Opsional</span> dapat diupload jika Anda memilikinya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($documentLabels as $key => $dokInfo)
                        @php
                            // Mapping ke verifikasi_berkas (untuk backward compatibility)
                            $statusKeyMapping = [
                                'akta_kelahiran' => 'status_akte',
                                'kartu_keluarga' => 'status_kk',
                                'rapor' => 'status_rapor',
                                'nisn' => 'status_nisn',
                                'ijazah' => 'status_ijazah',
                                'sertifikat' => 'status_sertifikat',
                                'kip' => 'status_kip',
                                'pkh' => 'status_pkh',
                                'kks' => 'status_kks',
                            ];
                            $catatanKeyMapping = [
                                'akta_kelahiran' => 'catatan_akte',
                                'kartu_keluarga' => 'catatan_kk',
                                'rapor' => 'catatan_rapor',
                                'nisn' => 'catatan_nisn',
                                'ijazah' => 'catatan_ijazah',
                                'sertifikat' => 'catatan_sertifikat',
                                'kip' => 'catatan_kip',
                                'pkh' => 'catatan_pkh',
                                'kks' => 'catatan_kks',
                            ];

                            $statusKey = $statusKeyMapping[$key] ?? 'status_' . $key;
                            $catatanKey = $catatanKeyMapping[$key] ?? 'catatan_' . $key;
                            $status = $verifikasi->$statusKey ?? 'belum_upload';
                            $uploaded = $uploaded_dokumen[$key] ?? null;

                            // Check if this is a conditional upload (KIP/PKH/KKS)
                            $isConditional = isset($dokInfo['conditional']);
                            $isEnabled = true;
                            if ($isConditional) {
                                $conditionalField = $dokInfo['conditional'];
                                $isEnabled = !empty($pendaftar->$conditionalField);
                            }

                            $statusColors = [
                                'belum_upload' => 'bg-gray-100 text-gray-800',
                                'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'perlu_perbaikan' => 'bg-orange-100 text-orange-800',
                                'tidak_sesuai' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'belum_upload' => 'Belum Upload',
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'disetujui' => 'Disetujui',
                                'perlu_perbaikan' => 'Perlu Perbaikan',
                                'tidak_sesuai' => 'Tidak Sesuai',
                            ];
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-white">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-gray-900">{{ $dokInfo['label'] }}</h4>
                                        @if($dokInfo['wajib'])
                                            <span class="px-2 py-0.5 text-xs bg-red-500 text-white rounded-full font-bold">WAJIB</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs bg-gray-400 text-white rounded-full font-medium">Opsional</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 italic">{{ $dokInfo['keterangan'] }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full font-medium {{ $statusColors[$status] ?? 'bg-gray-100' }} ml-2 whitespace-nowrap">
                                    {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>

                            @if($verifikasi->$catatanKey)
                                <div class="mb-3 bg-red-50 border border-red-100 p-2 rounded text-xs text-red-600">
                                    <strong>Catatan Panitia:</strong> {{ $verifikasi->$catatanKey }}
                                </div>
                            @endif

                            @if($uploaded)
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-3 flex items-center justify-between">
                                    <div class="flex items-center overflow-hidden">
                                        <svg class="w-8 h-8 text-emerald-600 flex-shrink-0 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $uploaded->nama_file }}</p>
                                            @if(isset($uploaded->tanggal_upload))
                                                <p class="text-xs text-gray-500">Uploaded: {{ \Carbon\Carbon::parse($uploaded->tanggal_upload)->format('d M Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('dokumen.view', $uploaded->id) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium flex-shrink-0">Lihat</a>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mb-3 text-center text-gray-500 text-sm italic">
                                    Belum ada file
                                </div>
                            @endif

                            @if($isConditional && !$isEnabled)
                                <!-- Conditional upload disabled message -->
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm text-blue-800 font-medium">Upload Tidak Tersedia</p>
                                            <p class="text-xs text-blue-600 mt-1">
                                                Silakan isi nomor {{ strtoupper(str_replace('nomor_', '', $dokInfo['conditional'])) }} di formulir pendaftaran terlebih dahulu untuk mengaktifkan upload dokumen ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Normal upload section -->
                                @php
                                    $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
                                @endphp
                                <div class="mt-2">
                                    @if($isApproved)
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm text-green-800 font-medium">Data Sudah Disetujui</p>
                                                    <p class="text-xs text-green-600 mt-1">
                                                        Upload dokumen tidak dapat dilakukan karena data pendaftaran sudah disetujui.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            {{ $uploaded ? 'Ganti File (PDF)' : 'Upload File (PDF)' }}
                                        </label>
                                        <div class="space-y-2">
                                            <div class="relative">
                                                <input
                                                    type="file"
                                                    wire:model.live="file_{{ $key }}"
                                                    accept=".pdf,application/pdf"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">

                                            <!-- Loading overlay during upload -->
                                            <div wire:loading.delay wire:target="file_{{ $key }}" class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded z-10">
                                                <div class="flex flex-col items-center text-emerald-600 text-sm font-medium">
                                                    <svg class="animate-spin h-6 w-6 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span>Mengunggah file...</span>
                                                    <span class="text-xs text-gray-500 mt-1">Mohon tunggu</span>
                                                </div>
                                            </div>
                                        </div>

                                        @error('file_' . $key) <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

                                        <div wire:loading.delay wire:target="file_{{ $key }}" class="text-xs text-emerald-600 font-medium">
                                            ⏳ Sedang memproses upload...
                                        </div>
                                    </div>
                                @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
