<div>
    <!-- Toast Notification -->
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
                    setTimeout(() => this.show = false, 5000);
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
        class="fixed top-4 right-4 text-white px-6 py-4 rounded-lg shadow-lg z-[10000] flex items-center max-w-md"
        style="display: none;">
        <svg x-show="type === 'success'" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <svg x-show="type === 'error'" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <svg x-show="type === 'info'" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span x-text="message" class="flex-1"></span>
    </div>

    @if(!$pendaftar)
        <div class="text-center py-12">
            <p class="text-gray-500">Data pendaftar tidak ditemukan.</p>
        </div>
    @else
        <div class="space-y-6">
            <!-- Flash Message -->
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            <!-- Header Info -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-8 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <!-- Left Section: Photo & Info -->
                        <div class="flex items-start space-x-4 flex-1">
                            @if($pendaftar->foto)
                                <img src="{{ Storage::url($pendaftar->foto) }}" alt="Foto" class="w-24 h-24 rounded-lg object-cover border-4 border-white shadow-lg flex-shrink-0">
                            @else
                                <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center border-4 border-white shadow-lg flex-shrink-0">
                                    <span class="text-4xl font-bold text-emerald-600">{{ strtoupper(substr($pendaftar->nama_lengkap, 0, 2)) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="text-2xl md:text-3xl font-bold mb-2">{{ $pendaftar->nama_lengkap }}</h2>
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">No. Pendaftaran: <span class="font-semibold text-white">{{ $pendaftar->nomor_pendaftaran }}</span></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">NISN: <span class="font-semibold text-white">{{ $pendaftar->nisn }}</span></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">Jalur: <span class="font-semibold text-white">{{ $pendaftar->jalurPendaftaran->nama ?? '-' }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Status Badge -->
                        <div class="flex md:flex-col items-start md:items-end justify-start md:justify-center gap-3">
                            @php
                                $statusColors = [
                                    'menunggu_verifikasi' => 'bg-yellow-50 text-yellow-800 border-yellow-300',
                                    'lengkapi_data' => 'bg-orange-50 text-orange-800 border-orange-300',
                                    'perbaiki_data' => 'bg-amber-50 text-amber-800 border-amber-300',
                                    'data_ditolak' => 'bg-red-50 text-red-800 border-red-300',
                                    'data_disetujui' => 'bg-green-50 text-green-800 border-green-300',
                                ];
                                $statusLabels = [
                                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                    'lengkapi_data' => 'Lengkapi Data',
                                    'perbaiki_data' => 'Perbaiki Data',
                                    'data_ditolak' => 'Data Ditolak',
                                    'data_disetujui' => 'Data Disetujui',
                                ];
                                $statusClass = $statusColors[$pendaftar->status_pendaftaran] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                $statusLabel = $statusLabels[$pendaftar->status_pendaftaran] ?? ucfirst(str_replace('_', ' ', $pendaftar->status_pendaftaran));
                            @endphp
                            <div class="text-left md:text-right">
                                <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide">Status Pendaftaran</p>
                                <span class="inline-block px-4 py-2.5 rounded-lg text-sm font-bold border-2 shadow-md {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <!-- Catatan Verifikasi (Only show for specific statuses) -->
                            @if(in_array($pendaftar->status_pendaftaran, ['lengkapi_data', 'perbaiki_data', 'data_ditolak']))
                                <div class="text-left md:text-right w-full md:max-w-md">
                                    <p class="text-white text-xs font-semibold mb-2 uppercase tracking-wide flex items-center gap-1 justify-start md:justify-end">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        Catatan untuk Pendaftar
                                    </p>
                                    <div class="bg-yellow-50 rounded-lg px-4 py-3 border-2 border-yellow-400 shadow-lg">
                                        @if(!empty($catatan_verifikasi))
                                            <div class="mb-3">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    <p class="text-gray-800 text-sm font-medium leading-relaxed flex-1">{{ $catatan_verifikasi }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <p class="text-gray-500 text-sm italic flex-1">Catatan belum di isi</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Edit Catatan Button -->
                                        <button type="button"
                                                onclick="document.getElementById('editCatatanModal').classList.remove('hidden')"
                                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 py-2 rounded-md text-xs font-semibold transition shadow-md flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Catatan
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <div class="text-left md:text-right">
                                <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide flex items-center gap-1 justify-end">
                                    Ubah Status
                                    @if(!$this->isAllDocumentsVerified)
                                        <span class="inline-block" title="Verifikasi semua dokumen terlebih dahulu">
                                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </p>
                                @if($this->isAllDocumentsVerified)
                                    <select wire:model.live="status_pendaftaran" wire:change="updateStatusPendaftaran" class="bg-white text-gray-800 rounded-lg border-2 border-white shadow-md px-4 py-2 text-sm font-semibold focus:border-emerald-300 focus:ring focus:ring-emerald-200/50 hover:bg-gray-50 transition cursor-pointer">
                                        <option value="menunggu_verifikasi" class="text-gray-800">Menunggu Verifikasi</option>
                                        <option value="lengkapi_data" class="text-gray-800">Lengkapi Data</option>
                                        <option value="perbaiki_data" class="text-gray-800">Perbaiki Data</option>
                                        <option value="data_ditolak" class="text-gray-800">Data Ditolak</option>
                                        <option value="data_disetujui" class="text-gray-800">Data Disetujui</option>
                                    </select>
                                @else
                                    <div class="relative group">
                                        <select disabled class="bg-gray-300 text-gray-600 rounded-lg border-2 border-gray-400 shadow-md px-4 py-2 text-sm font-semibold cursor-not-allowed opacity-60">
                                            <option>{{ $statusLabel }}</option>
                                        </select>
                                        <div class="absolute bottom-full right-0 mb-2 hidden group-hover:block z-10 w-64">
                                            <div class="bg-red-600 text-white text-xs rounded-lg px-3 py-2 shadow-lg">
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-bold mb-1">🔒 Terkunci!</p>
                                                        <p>Verifikasi semua <strong>dokumen wajib</strong> dan dokumen yang sudah di-upload dengan status <strong>"Disetujui"</strong> terlebih dahulu di tab <strong>Verifikasi Dokumen</strong></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div x-data="{ activeTab: @entangle('activeTab') }" class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Tab Buttons -->
                <div class="border-b border-gray-200 overflow-x-auto">
                    <nav class="flex -mb-px whitespace-nowrap">
                        <button @click="activeTab = 'siswa'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'siswa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'siswa' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Data Siswa
                        </button>
                        <button @click="activeTab = 'orangtua'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'orangtua' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Orang Tua
                        </button>
                        <button @click="activeTab = 'alamat'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'alamat', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'alamat' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Data Alamat
                        </button>
                        <button @click="activeTab = 'prestasi'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'prestasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prestasi' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Prestasi Siswa
                        </button>
                        <button @click="activeTab = 'keahlian'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'keahlian', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'keahlian' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            Keahlian
                        </button>
                        <button @click="activeTab = 'tahfidz'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'tahfidz', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'tahfidz' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Tahfidz
                        </button>
                        <button @click="activeTab = 'beasiswa'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'beasiswa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'beasiswa' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Beasiswa & Bantuan
                        </button>
                        <button @click="activeTab = 'pendidikan'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'pendidikan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pendidikan' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Pendidikan Lain
                        </button>
                        <button @click="activeTab = 'survey'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'survey', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'survey' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Survey
                        </button>
                        <button @click="activeTab = 'infoakun'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'infoakun', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'infoakun' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Info Akun
                        </button>
                        <button @click="activeTab = 'dokumen'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dokumen' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Verifikasi Dokumen
                        </button>
                        <button @click="activeTab = 'daftarulang'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'daftarulang', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'daftarulang' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Daftar Ulang
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Tab Data Siswa -->
                    <div x-show="activeTab === 'siswa'" x-transition>
                        @include('tab_templates.tab-data-siswa')
                    </div>

                    <!-- Tab Data Orang Tua -->
                    <div x-show="activeTab === 'orangtua'" x-transition>
                        @include('tab_templates.tab-data-orangtua')
                    </div>

                    <!-- Tab Data Alamat -->
                    <div x-show="activeTab === 'alamat'" x-transition>
                        @include('tab_templates.tab-data-alamat')
                    </div>

                    <!-- Tab Prestasi Siswa -->
                    <div x-show="activeTab === 'prestasi'" x-transition>
                        @include('tab_templates.tab-prestasi')
                    </div>

                    <!-- Tab Keahlian -->
                    <div x-show="activeTab === 'keahlian'" x-transition>
                        @include('tab_templates.tab-keahlian')
                    </div>

                    <!-- Tab Tahfidz -->
                    <div x-show="activeTab === 'tahfidz'" x-transition>
                        @include('tab_templates.tab-tahfidz')
                    </div>

                    <!-- Tab Beasiswa & Bantuan -->
                    <div x-show="activeTab === 'beasiswa'" x-transition>
                        @include('tab_templates.tab-beasiswa')
                    </div>

                    <!-- Tab Pendidikan Lain -->
                    <div x-show="activeTab === 'pendidikan'" x-transition>
                        @include('tab_templates.tab-pendidikan-lain')
                    </div>

                    <!-- Tab Survey -->
                    <div x-show="activeTab === 'survey'" x-transition>
                        @include('tab_templates.tab-survey')
                    </div>

                    <!-- Tab Info Akun -->
                    <div x-show="activeTab === 'infoakun'" x-transition>
                        @include('tab_templates.tab-info-akun')
                    </div>

                    <!-- Tab Daftar Ulang -->
                    <div x-show="activeTab === 'daftarulang'" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Daftar Ulang</h3>

                        @php
                            $daftarUlang = $pendaftar->daftarUlang;
                            $seragam = $pendaftar->seragam;
                        @endphp

                        <!-- Sub-tab Navigation -->
                        <div x-data="{ subTab: 'madrasah' }" class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="border-b border-gray-200">
                                <nav class="flex">
                                    <button @click="subTab = 'madrasah'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': subTab === 'madrasah', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': subTab !== 'madrasah' }" class="flex-1 py-3 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                                        Daftar Ulang Madrasah
                                    </button>
                                    <button @click="subTab = 'seragam'" :class="{ 'border-blue-500 text-blue-600 bg-blue-50': subTab === 'seragam', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': subTab !== 'seragam' }" class="flex-1 py-3 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                                        Daftar Ulang Seragam
                                    </button>
                                    <button @click="subTab = 'ukuran'" :class="{ 'border-purple-500 text-purple-600 bg-purple-50': subTab === 'ukuran', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': subTab !== 'ukuran' }" class="flex-1 py-3 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                                        Ukuran Seragam & Atribut
                                    </button>
                                </nav>
                            </div>

                            <!-- Madrasah Sub-tab -->
                            <div x-show="subTab === 'madrasah'" x-transition class="p-6 space-y-6">
                                <h4 class="text-base font-semibold text-gray-900 mb-4 border-b pb-2">Daftar Ulang Madrasah</h4>

                                <!-- Informasi Pembayaran -->
                                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Informasi Pembayaran
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-gray-600 font-medium">Opsi Pembayaran:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($daftarUlang && $daftarUlang->opsi_pembayaran)
                                                    {{ ucwords(str_replace('_', ' ', $daftarUlang->opsi_pembayaran)) }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum dipilih</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Metode Pembayaran:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($daftarUlang && $daftarUlang->metode_pembayaran)
                                                    {{ strtoupper($daftarUlang->metode_pembayaran) }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum dipilih</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Nominal Biaya:</p>
                                            <p class="text-gray-900 font-bold text-lg">
                                                @if($daftarUlang && $daftarUlang->nominal_biaya)
                                                    @php
                                                        $nominalBiaya = is_array($daftarUlang->nominal_biaya) ? $daftarUlang->nominal_biaya : json_decode($daftarUlang->nominal_biaya, true);
                                                        $totalNominal = is_array($nominalBiaya) ? \App\Support\Nominal::sum($nominalBiaya) : 0;
                                                    @endphp
                                                    Rp {{ number_format($totalNominal, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-400 italic text-sm">Belum ada</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Waktu Pembayaran:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($daftarUlang && $daftarUlang->waktu_pembayaran_madrasah)
                                                    {{ $daftarUlang->waktu_pembayaran_madrasah->format('d M Y H:i') }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum bayar</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Status Daftar Ulang
                                    </h5>
                                    <p class="text-sm">
                                        @if($daftarUlang && $daftarUlang->status)
                                            @php
                                                $statusColors = [
                                                    'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                    'setuju' => 'bg-green-100 text-green-800 border-green-300',
                                                    'tidak_setuju' => 'bg-red-100 text-red-800 border-red-300',
                                                ];
                                                $statusColor = $statusColors[$daftarUlang->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColor }}">
                                                {{ ucwords(str_replace('_', ' ', $daftarUlang->status)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada status</span>
                                        @endif
                                    </p>
                                    @if($daftarUlang && $daftarUlang->catatan)
                                        <div class="mt-3 p-3 bg-white border border-gray-200 rounded">
                                            <p class="text-xs text-gray-600 font-medium mb-1">Catatan:</p>
                                            <p class="text-sm text-gray-700">{{ $daftarUlang->catatan }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Surat Pernyataan -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Surat Pernyataan
                                    </h5>
                                    @if($daftarUlang && $daftarUlang->surat_pernyataan_path)
                                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded">
                                            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <span class="text-sm text-gray-700 flex-1">Surat pernyataan telah diupload</span>
                                            <a href="{{ Storage::url($daftarUlang->surat_pernyataan_path) }}" target="_blank" class="ml-3 text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat File</a>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Belum upload surat pernyataan</p>
                                    @endif
                                </div>

                                <!-- Bukti Pembayaran Madrasah -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Bukti Pembayaran
                                    </h5>
                                    @if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah)
                                        <div class="p-3 bg-green-100 border border-green-300 rounded-lg">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                <span class="text-sm font-semibold text-green-800 flex-1">Bukti pembayaran telah diupload</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ Storage::url($daftarUlang->bukti_pembayaran_madrasah) }}" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Lihat Bukti
                                                </a>
                                                <a href="{{ Storage::url($daftarUlang->bukti_pembayaran_madrasah) }}" download class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded text-sm font-medium transition flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Belum upload bukti pembayaran</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Seragam Sub-tab -->
                            <div x-show="subTab === 'seragam'" x-transition class="p-6 space-y-6">
                                <h4 class="text-base font-semibold text-gray-900 mb-4 border-b pb-2">Daftar Ulang Seragam</h4>

                                <!-- Informasi Pembayaran Seragam -->
                                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Informasi Pembayaran Seragam
                                    </h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-gray-600 font-medium">Opsi Pembayaran:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($seragam && $seragam->opsi_pembayaran_seragam)
                                                    {{ ucwords(str_replace('_', ' ', $seragam->opsi_pembayaran_seragam)) }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum dipilih</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Bank Transfer:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($seragam && $seragam->bank_seragam)
                                                    {{ strtoupper($seragam->bank_seragam) }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum dipilih</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Nominal Seragam:</p>
                                            <p class="text-gray-900 font-bold text-lg">
                                                @if($seragam && $seragam->nominal_seragam)
                                                    @php
                                                        $nominalSeragam = is_array($seragam->nominal_seragam) ? $seragam->nominal_seragam : json_decode($seragam->nominal_seragam, true);
                                                        $totalSeragam = is_array($nominalSeragam) ? \App\Support\Nominal::sum($nominalSeragam) : 0;
                                                    @endphp
                                                    Rp {{ number_format($totalSeragam, 0, ',', '.') }}
                                                @else
                                                    <span class="text-gray-400 italic text-sm">Belum ada</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 font-medium">Waktu Pembayaran:</p>
                                            <p class="text-gray-900 font-semibold">
                                                @if($seragam && $seragam->waktu_pembayaran_seragam)
                                                    {{ $seragam->waktu_pembayaran_seragam->format('d M Y H:i') }}
                                                @else
                                                    <span class="text-gray-400 italic">Belum bayar</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paket Seragam -->
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        Paket Seragam
                                    </h5>
                                    @if($seragam && $seragam->paket_seragam)
                                        @php
                                            try {
                                                $paketData = is_string($seragam->paket_seragam)
                                                    ? json_decode($seragam->paket_seragam, true)
                                                    : $seragam->paket_seragam;
                                            } catch (\Exception $e) {
                                                $paketData = null;
                                            }
                                        @endphp

                                        @if($paketData && is_array($paketData))
                                            <div class="bg-white border border-indigo-200 rounded-lg p-3 space-y-2">
                                                <div>
                                                    <span class="text-xs font-medium text-gray-500 uppercase">Nama Paket:</span>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $paketData['name'] ?? 'N/A' }}</p>
                                                </div>
                                                @if(!empty($paketData['description']))
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 uppercase">Deskripsi:</span>
                                                        <p class="text-sm text-gray-700">{{ $paketData['description'] }}</p>
                                                    </div>
                                                @endif
                                                @if(!empty($paketData['options']) && is_array($paketData['options']))
                                                    <div>
                                                        <span class="text-xs font-medium text-gray-500 uppercase block mb-1">Opsi Paket:</span>
                                                        <ul class="space-y-1">
                                                            @foreach($paketData['options'] as $option)
                                                                <li class="flex items-center justify-between text-sm bg-indigo-50 px-2 py-1 rounded">
                                                                    <span class="font-medium text-gray-700">{{ $option['name'] ?? 'N/A' }}</span>
                                                                    <span class="text-indigo-600 font-semibold">Rp {{ number_format(\App\Support\Nominal::toNumber($option['amount'] ?? 0), 0, ',', '.') }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-700">{{ $seragam->paket_seragam }}</p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-500 italic">Belum memilih paket seragam</p>
                                    @endif
                                </div>

                                <!-- Status Seragam -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Status Seragam
                                    </h5>
                                    <p class="text-sm">
                                        @if($seragam && $seragam->status_seragam)
                                            @php
                                                $statusColors = [
                                                    'menunggu' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                    'disetujui' => 'bg-green-100 text-green-800 border-green-300',
                                                    'ditolak' => 'bg-red-100 text-red-800 border-red-300',
                                                ];
                                                $statusColor = $statusColors[$seragam->status_seragam] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColor }}">
                                                {{ ucwords(str_replace('_', ' ', $seragam->status_seragam)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada status</span>
                                        @endif
                                    </p>
                                    @if($seragam && $seragam->catatan_seragam)
                                        <div class="mt-3 p-3 bg-white border border-gray-200 rounded">
                                            <p class="text-xs text-gray-600 font-medium mb-1">Catatan:</p>
                                            <p class="text-sm text-gray-700">{{ $seragam->catatan_seragam }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bukti Pembayaran Seragam -->
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Bukti Pembayaran Seragam
                                    </h5>
                                    @if($seragam && $seragam->bukti_pembayaran)
                                        <div class="p-3 bg-green-100 border border-green-300 rounded-lg">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                <span class="text-sm font-semibold text-green-800 flex-1">Bukti pembayaran telah diupload</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ Storage::url($seragam->bukti_pembayaran) }}" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm font-medium transition flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Lihat Bukti
                                                </a>
                                                <a href="{{ Storage::url($seragam->bukti_pembayaran) }}" download class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded text-sm font-medium transition flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Belum upload bukti pembayaran</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Ukuran Sub-tab -->
                            <div x-show="subTab === 'ukuran'" x-transition class="p-6 space-y-6">
                                <h4 class="text-base font-semibold text-gray-900 mb-4 border-b pb-2">Ukuran Seragam & Atribut</h4>

                                <!-- Status Ukuran -->
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                    <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Status Ukuran
                                    </h5>
                                    <p class="text-sm">
                                        @if($seragam && $seragam->status_ukuran)
                                            @php
                                                $statusColors = [
                                                    'belum' => 'bg-gray-100 text-gray-800 border-gray-300',
                                                    'sedang_diukur' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                    'selesai' => 'bg-green-100 text-green-800 border-green-300',
                                                ];
                                                $statusColor = $statusColors[$seragam->status_ukuran] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColor }}">
                                                {{ ucwords(str_replace('_', ' ', $seragam->status_ukuran)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada status</span>
                                        @endif
                                    </p>
                                    @if($seragam && $seragam->catatan_ukuran)
                                        <div class="mt-3 p-3 bg-white border border-gray-200 rounded">
                                            <p class="text-xs text-gray-600 font-medium mb-1">Catatan:</p>
                                            <p class="text-sm text-gray-700">{{ $seragam->catatan_ukuran }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Ukuran Jas -->
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Ukuran Jas
                                        </h5>
                                        @if($seragam && $seragam->ukuran_jas)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_jas }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Baju Harian -->
                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Ukuran Baju Harian
                                        </h5>
                                        @if($seragam && $seragam->ukuran_baju_harian)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_baju_harian }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Celana/Rok Harian -->
                                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Ukuran {{ $pendaftar->jenis_kelamin === 'L' ? 'Celana' : 'Rok' }} Harian
                                        </h5>
                                        @if($seragam && $seragam->ukuran_celana_rok_harian)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_celana_rok_harian }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Baju Olahraga -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                                            Ukuran Baju Olahraga
                                        </h5>
                                        @if($seragam && $seragam->ukuran_baju_olahraga)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_baju_olahraga }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Celana Olahraga -->
                                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                                            Ukuran Celana Olahraga
                                        </h5>
                                        @if($seragam && $seragam->ukuran_celana_olahraga)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_celana_olahraga }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Kopyah (Khusus Laki-laki) -->
                                    @if($pendaftar->jenis_kelamin === 'L')
                                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                            Ukuran Kopyah (Laki-laki)
                                        </h5>
                                        @if($seragam && $seragam->ukuran_kopyah)
                                            <p class="text-sm text-gray-900 font-semibold">{{ $seragam->ukuran_kopyah }}</p>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Belum diisi</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Verifikasi Dokumen -->
                    <div x-show="activeTab === 'dokumen'" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Berkas / Dokumen</h3>
                        <div class="space-y-6">
                            <!-- Nilai Rapor Section -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-sm">
                                @php
                                    // Hitung rata-rata per mapel
                                    $nilaiBindoArr = array_filter([
                                        $pendaftar->nilai_b_indo_5_1,
                                        $pendaftar->nilai_b_indo_5_2,
                                        $pendaftar->nilai_b_indo_6_1
                                    ]);
                                    $nilaiMatematikaArr = array_filter([
                                        $pendaftar->nilai_matematika_5_1,
                                        $pendaftar->nilai_matematika_5_2,
                                        $pendaftar->nilai_matematika_6_1
                                    ]);
                                    $nilaiIPAArr = array_filter([
                                        $pendaftar->nilai_ipa_5_1,
                                        $pendaftar->nilai_ipa_5_2,
                                        $pendaftar->nilai_ipa_6_1
                                    ]);

                                    $rataBindo = count($nilaiBindoArr) > 0 ? array_sum($nilaiBindoArr) / count($nilaiBindoArr) : 0;
                                    $rataMatematika = count($nilaiMatematikaArr) > 0 ? array_sum($nilaiMatematikaArr) / count($nilaiMatematikaArr) : 0;
                                    $rataIPA = count($nilaiIPAArr) > 0 ? array_sum($nilaiIPAArr) / count($nilaiIPAArr) : 0;

                                    // Hitung rata-rata total
                                    $semuaNilai = array_merge($nilaiBindoArr, $nilaiMatematikaArr, $nilaiIPAArr);
                                    $rataTotal = count($semuaNilai) > 0 ? array_sum($semuaNilai) / count($semuaNilai) : 0;
                                @endphp

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-bold text-gray-900">Nilai Rapor yang Diinputkan</h4>
                                    </div>
                                    <button type="button" wire:click="openEditNilaiRapor" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Nilai
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                                    <!-- Tabel Nilai per Semester -->
                                    <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                        <h5 class="font-semibold text-gray-900 mb-3 text-sm">Nilai per Semester & Mata Pelajaran</h5>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-b">Semester</th>
                                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-b">B. Indo</th>
                                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-b">Matematika</th>
                                                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-700 border-b">IPA</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-3 py-2 text-gray-700 font-medium">Kelas 5 Sem 1</td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-blue-600">{{ $pendaftar->nilai_b_indo_5_1 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-green-600">{{ $pendaftar->nilai_matematika_5_1 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-purple-600">{{ $pendaftar->nilai_ipa_5_1 ?? '-' }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-3 py-2 text-gray-700 font-medium">Kelas 5 Sem 2</td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-blue-600">{{ $pendaftar->nilai_b_indo_5_2 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-green-600">{{ $pendaftar->nilai_matematika_5_2 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-purple-600">{{ $pendaftar->nilai_ipa_5_2 ?? '-' }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-3 py-2 text-gray-700 font-medium">Kelas 6 Sem 1</td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-blue-600">{{ $pendaftar->nilai_b_indo_6_1 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-green-600">{{ $pendaftar->nilai_matematika_6_1 ?? '-' }}</span>
                                                        </td>
                                                        <td class="px-3 py-2 text-center">
                                                            <span class="font-semibold text-purple-600">{{ $pendaftar->nilai_ipa_6_1 ?? '-' }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Nilai Rata-rata -->
                                    <div class="space-y-3">
                                        <!-- Rata-rata per Mapel -->
                                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                                            <h5 class="font-semibold text-gray-900 mb-3 text-sm">Rata-rata per Mata Pelajaran</h5>
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                                                    <span class="text-sm font-medium text-gray-700">Bahasa Indonesia</span>
                                                    <span class="text-base font-bold text-blue-600">{{ number_format($rataBindo, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between items-center p-2 bg-green-50 rounded">
                                                    <span class="text-sm font-medium text-gray-700">Matematika</span>
                                                    <span class="text-base font-bold text-green-600">{{ number_format($rataMatematika, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between items-center p-2 bg-purple-50 rounded">
                                                    <span class="text-sm font-medium text-gray-700">IPA</span>
                                                    <span class="text-base font-bold text-purple-600">{{ number_format($rataIPA, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rata-rata Total -->
                                        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg p-4 shadow-md text-white">
                                            <h5 class="font-semibold mb-2 text-sm opacity-90">Nilai Rata-rata Total</h5>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm">Semua Mata Pelajaran</span>
                                                <span class="text-3xl font-bold">{{ number_format($rataTotal, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(count($semuaNilai) === 0)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                        <p class="text-sm text-yellow-800 flex items-center">
                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <span><strong>Perhatian:</strong> Pendaftar belum mengisi nilai rapor</span>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @php
                                $dokumenList = [
                                    ['key' => 'rapor', 'label' => 'Scan Rapor', 'search_keys' => ['rapor', 'raport'], 'wajib' => true],
                                    ['key' => 'akte', 'label' => 'Akta Kelahiran', 'search_keys' => ['akte', 'akta_kelahiran'], 'wajib' => true],
                                    ['key' => 'kk', 'label' => 'Kartu Keluarga', 'search_keys' => ['kk', 'kartu_keluarga'], 'wajib' => true],
                                    ['key' => 'nisn', 'label' => 'Scan/Bukti NISN', 'search_keys' => ['nisn'], 'wajib' => true],
                                    ['key' => 'ijazah', 'label' => 'Ijazah/SKL/Surat Keterangan Aktif', 'search_keys' => ['ijazah'], 'wajib' => true],
                                    ['key' => 'sertifikat', 'label' => 'Sertifikat/Piagam', 'search_keys' => ['sertifikat'], 'wajib' => false],
                                    ['key' => 'kip', 'label' => 'Kartu Indonesia Pintar (KIP)', 'search_keys' => ['kip'], 'wajib' => false, 'conditional' => 'nomor_kip'],
                                    ['key' => 'pkh', 'label' => 'Program Keluarga Harapan (PKH)', 'search_keys' => ['pkh'], 'wajib' => false, 'conditional' => 'nomor_pkh'],
                                    ['key' => 'kks', 'label' => 'Kartu Keluarga Sejahtera (KKS)', 'search_keys' => ['kks'], 'wajib' => false, 'conditional' => 'nomor_kks'],
                                ];

                                $statusOptions = [
                                    'belum_upload' => ['label' => 'Belum Upload', 'color' => 'bg-gray-100 text-gray-800'],
                                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'bg-yellow-100 text-yellow-800'],
                                    'tidak_sesuai' => ['label' => 'Tidak Sesuai', 'color' => 'bg-red-100 text-red-800'],
                                    'perlu_perbaikan' => ['label' => 'Perlu Perbaikan', 'color' => 'bg-orange-100 text-orange-800'],
                                    'disetujui' => ['label' => 'Disetujui', 'color' => 'bg-green-100 text-green-800'],
                                ];
                            @endphp

                            @foreach($dokumenList as $dok)
                                @php
                                    $statusField = 'status_' . $dok['key'];
                                    $catatanField = 'catatan_' . $dok['key'];
                                    $currentStatus = $this->$statusField ?? 'belum_upload';
                                    $statusInfo = $statusOptions[$currentStatus] ?? ['label' => $currentStatus, 'color' => 'bg-gray-100 text-gray-800'];

                                    // Cari dokumen yang sudah di-upload berdasarkan jenis (cek multiple keys)
                                    $uploadedDokumen = null;
                                    foreach ($dok['search_keys'] as $searchKey) {
                                        $uploadedDokumen = $dokumen->firstWhere('jenis_dokumen', $searchKey);
                                        if ($uploadedDokumen) break;
                                    }

                                    // Khusus untuk rapor, cek juga field file_rapor di pendaftar
                                    $fileRapor = null;
                                    if ($dok['key'] === 'rapor' && $pendaftar->file_rapor) {
                                        $fileRapor = $pendaftar->file_rapor;
                                    }

                                    // Check if conditional document
                                    $isConditional = isset($dok['conditional']);
                                    $isEnabled = true;
                                    if ($isConditional) {
                                        $conditionalField = $dok['conditional'];
                                        $isEnabled = !empty($pendaftar->$conditionalField);
                                    }
                                @endphp

                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition {{ !$isEnabled ? 'bg-gray-50 opacity-60' : '' }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-semibold text-gray-900">{{ $dok['label'] }}</h4>
                                                @if($dok['wajib'])
                                                    <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">WAJIB</span>
                                                @elseif($isConditional && !$isEnabled)
                                                    <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full font-medium">🔒 TERKUNCI</span>
                                                @else
                                                    <span class="text-xs bg-gray-400 text-white px-2 py-0.5 rounded-full font-medium">Opsional</span>
                                                @endif
                                            </div>
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusInfo['color'] }}">
                                                {{ $statusInfo['label'] }}
                                            </span>
                                            @if($isConditional && !$isEnabled)
                                                <p class="text-xs text-blue-600 mt-2 font-medium">
                                                    📌 Dokumen ini terkunci karena pendaftar tidak mengisi nomor {{ strtoupper(str_replace('nomor_', '', $dok['conditional'])) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- File yang Sudah Di-upload -->
                                    @if($uploadedDokumen || $fileRapor)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                            <div class="flex items-start space-x-3">
                                                <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $uploadedDokumen ? $uploadedDokumen->nama_file : basename($fileRapor) }}
                                                    </p>
                                                    <div class="flex items-center space-x-3 mt-1">
                                                        @if($uploadedDokumen)
                                                            <span class="text-xs text-gray-500">{{ number_format($uploadedDokumen->ukuran_file / 1024, 2) }} KB</span>
                                                            <span class="text-xs text-gray-500">{{ $uploadedDokumen->tanggal_upload->format('d/m/Y H:i') }}</span>
                                                        @endif
                                                        @if($uploadedDokumen)
                                                            <a href="{{ route('dokumen.view', $uploadedDokumen->id) }}"
                                                               target="_blank"
                                                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                Lihat File
                                                            </a>
                                                        @elseif($fileRapor)
                                                            <span class="text-xs text-amber-600 font-medium">Legacy file (refresh page to migrate)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3 text-center">
                                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-500">Belum ada file yang di-upload</p>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <!-- Status Dropdown -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status</label>
                                            <select wire:model="{{$statusField}}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 text-sm">
                                                <option value="belum_upload">Belum Upload</option>
                                                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                                <option value="tidak_sesuai">Tidak Sesuai</option>
                                                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                                <option value="disetujui">Disetujui</option>
                                            </select>
                                        </div>

                                        <!-- Catatan -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                            <input
                                                type="text"
                                                wire:model="{{$catatanField}}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 text-sm"
                                                placeholder="Tambahkan catatan (opsional)"
                                            >
                                        </div>
                                    </div>

                                    <!-- Upload Dokumen (Admin/Panitia Help Upload) -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Dokumen (Bantu Pendaftar)
                                        </label>
                                        @if($isConditional && !$isEnabled)
                                            <div class="bg-gray-100 border border-gray-300 rounded-lg p-3 text-center">
                                                <p class="text-xs text-gray-600">
                                                    🔒 Upload dinonaktifkan karena dokumen ini terkunci
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="file"
                                                    wire:model="uploadDokumen{{ ucfirst($dok['key']) }}"
                                                    accept=".pdf"
                                                    class="flex-1 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md"
                                                />
                                                <button
                                                    wire:click="uploadDokumen('{{ $dok['key'] }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="uploadDokumen{{ ucfirst($dok['key']) }}, uploadDokumen('{{ $dok['key'] }}')"
                                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center whitespace-nowrap"
                                                >
                                                    <span wire:loading.remove wire:target="uploadDokumen{{ ucfirst($dok['key']) }}, uploadDokumen('{{ $dok['key'] }}')" class="inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                        Upload
                                                    </span>
                                                    <span wire:loading wire:target="uploadDokumen{{ ucfirst($dok['key']) }}, uploadDokumen('{{ $dok['key'] }}')" class="inline-flex items-center">
                                                        <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Uploading...
                                                    </span>
                                                </button>
                                            </div>
                                            @error('uploadDokumen' . ucfirst($dok['key']))
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                            <p class="text-xs text-gray-500 mt-1">
                                                Format: PDF. Maks: 2MB
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Tombol Simpan -->
                                    <div class="mt-3">
                                        @if($isConditional && !$isEnabled)
                                            <button
                                                disabled
                                                class="px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-md cursor-not-allowed inline-flex items-center opacity-60"
                                                title="Dokumen ini terkunci karena pendaftar tidak mengisi nomor {{ strtoupper(str_replace('nomor_', '', $dok['conditional'])) }}"
                                            >
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Dokumen Terkunci
                                            </button>
                                        @else
                                            <button
                                                wire:click="updateStatusDokumen('{{ $dok['key'] }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="updateStatusDokumen('{{ $dok['key'] }}')"
                                                class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center"
                                            >
                                                <span wire:loading.remove wire:target="updateStatusDokumen('{{ $dok['key'] }}')" class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Simpan Status
                                                </span>
                                                <span wire:loading wire:target="updateStatusDokumen('{{ $dok['key'] }}')" class="inline-flex items-center">
                                                    <svg class="animate-spin w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Menyimpan...
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="flex justify-end">
                <a href="{{ route('panitia-pendaftaran.pendaftar') }}" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Pendaftar
                </a>
            </div>
        </div>
    @endif

    <!-- Modal Edit Nilai Rapor -->
    @if($showEditNilaiRapor)
        <div class="fixed inset-0 bg-gray-600/75 overflow-y-auto h-full w-full z-50" wire:click.self="closeEditNilaiRapor">
            <div class="relative top-10 mx-auto p-6 border w-full max-w-5xl shadow-2xl rounded-xl bg-white mb-10">
                <div class="flex items-center justify-between mb-4 border-b pb-3">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Nilai Rapor
                    </h3>
                    <button wire:click="closeEditNilaiRapor" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveNilaiRapor">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Bahasa Indonesia -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Bahasa Indonesia
                            </h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_b_indo_5_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('edit_nilai_b_indo_5_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 2</label>
                                    <input type="number" wire:model.live="edit_nilai_b_indo_5_2" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('edit_nilai_b_indo_5_2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 6 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_b_indo_6_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('edit_nilai_b_indo_6_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @php
                                $nilaiBindoEdit = array_filter([
                                    $edit_nilai_b_indo_5_1,
                                    $edit_nilai_b_indo_5_2,
                                    $edit_nilai_b_indo_6_1
                                ]);
                                $rataBindoEdit = count($nilaiBindoEdit) > 0 ? array_sum($nilaiBindoEdit) / count($nilaiBindoEdit) : 0;
                            @endphp
                            <div class="mt-3 pt-3 border-t border-blue-300">
                                <div class="flex justify-between items-center bg-blue-100 rounded px-2 py-1.5">
                                    <span class="text-xs font-medium text-blue-900">Rata-rata:</span>
                                    <span class="text-sm font-bold text-blue-700">{{ number_format($rataBindoEdit, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Matematika -->
                        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-3 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Matematika
                            </h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_matematika_5_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('edit_nilai_matematika_5_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 2</label>
                                    <input type="number" wire:model.live="edit_nilai_matematika_5_2" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('edit_nilai_matematika_5_2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 6 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_matematika_6_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('edit_nilai_matematika_6_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @php
                                $nilaiMatematikaEdit = array_filter([
                                    $edit_nilai_matematika_5_1,
                                    $edit_nilai_matematika_5_2,
                                    $edit_nilai_matematika_6_1
                                ]);
                                $rataMatematikaEdit = count($nilaiMatematikaEdit) > 0 ? array_sum($nilaiMatematikaEdit) / count($nilaiMatematikaEdit) : 0;
                            @endphp
                            <div class="mt-3 pt-3 border-t border-green-300">
                                <div class="flex justify-between items-center bg-green-100 rounded px-2 py-1.5">
                                    <span class="text-xs font-medium text-green-900">Rata-rata:</span>
                                    <span class="text-sm font-bold text-green-700">{{ number_format($rataMatematikaEdit, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- IPA -->
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-3 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                IPA
                            </h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_ipa_5_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('edit_nilai_ipa_5_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 5 Sem 2</label>
                                    <input type="number" wire:model.live="edit_nilai_ipa_5_2" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('edit_nilai_ipa_5_2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Kelas 6 Sem 1</label>
                                    <input type="number" wire:model.live="edit_nilai_ipa_6_1" step="0.01" min="0" max="100" class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    @error('edit_nilai_ipa_6_1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @php
                                $nilaiIPAEdit = array_filter([
                                    $edit_nilai_ipa_5_1,
                                    $edit_nilai_ipa_5_2,
                                    $edit_nilai_ipa_6_1
                                ]);
                                $rataIPAEdit = count($nilaiIPAEdit) > 0 ? array_sum($nilaiIPAEdit) / count($nilaiIPAEdit) : 0;
                            @endphp
                            <div class="mt-3 pt-3 border-t border-purple-300">
                                <div class="flex justify-between items-center bg-purple-100 rounded px-2 py-1.5">
                                    <span class="text-xs font-medium text-purple-900">Rata-rata:</span>
                                    <span class="text-sm font-bold text-purple-700">{{ number_format($rataIPAEdit, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Total -->
                    @php
                        $semuaNilaiEdit = array_merge($nilaiBindoEdit ?? [], $nilaiMatematikaEdit ?? [], $nilaiIPAEdit ?? []);
                        $rataTotalEdit = count($semuaNilaiEdit) > 0 ? array_sum($semuaNilaiEdit) / count($semuaNilaiEdit) : 0;
                    @endphp
                    <div class="mt-4 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg p-4 shadow-md text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="font-semibold">Nilai Rata-rata Total</span>
                            </div>
                            <span class="text-3xl font-bold">{{ number_format($rataTotalEdit, 2) }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 mt-4 pt-4 border-t">
                        <button type="button" wire:click="closeEditNilaiRapor" class="px-5 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition text-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-md text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Edit Catatan Verifikasi -->
    <div id="editCatatanModal" class="hidden fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Catatan untuk Pendaftar
                </h3>
                <button type="button" onclick="document.getElementById('editCatatanModal').classList.add('hidden')" class="text-white hover:text-gray-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updateStatusPendaftaran" class="p-6">
                <div class="mb-4">
                    <label for="catatan_verifikasi" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Verifikasi
                        <span class="text-gray-500 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        wire:model="catatan_verifikasi"
                        id="catatan_verifikasi"
                        rows="5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Tulis catatan untuk pendaftar, misalnya: apa yang perlu diperbaiki atau dilengkapi..."></textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        💡 Catatan ini akan ditampilkan di dashboard pendaftar untuk memberikan feedback yang jelas.
                    </p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-xs text-yellow-800">
                            <p class="font-semibold mb-1">Tips menulis catatan yang baik:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Jelaskan secara spesifik apa yang perlu diperbaiki/dilengkapi</li>
                                <li>Gunakan bahasa yang sopan dan konstruktif</li>
                                <li>Berikan instruksi yang jelas dan mudah dipahami</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button
                        type="button"
                        onclick="document.getElementById('editCatatanModal').classList.add('hidden')"
                        class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition text-sm">
                        Batal
                    </button>
                    <button
                        type="submit"
                        onclick="document.getElementById('editCatatanModal').classList.add('hidden')"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-md text-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
