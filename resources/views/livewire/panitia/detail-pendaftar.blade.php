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
                            <div class="text-left md:text-right">
                                <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide">Ubah Status</p>
                                <select wire:model.live="status_pendaftaran" wire:change="updateStatusPendaftaran" class="bg-white text-gray-800 rounded-lg border-2 border-white shadow-md px-4 py-2 text-sm font-semibold focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 hover:bg-gray-50 transition cursor-pointer">
                                    <option value="menunggu_verifikasi" class="text-gray-800">Menunggu Verifikasi</option>
                                    <option value="lengkapi_data" class="text-gray-800">Lengkapi Data</option>
                                    <option value="perbaiki_data" class="text-gray-800">Perbaiki Data</option>
                                    <option value="data_ditolak" class="text-gray-800">Data Ditolak</option>
                                    <option value="data_disetujui" class="text-gray-800">Data Disetujui</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div x-data="{ activeTab: 'siswa' }" class="bg-white rounded-lg shadow-lg overflow-hidden">
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
                        <button @click="activeTab = 'dokumen'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dokumen' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Verifikasi Dokumen
                        </button>
                        <button @click="activeTab = 'daftarulang'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'daftarulang', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'daftarulang' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Daftar Ulang
                        </button>
                        <button @click="activeTab = 'survey'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'survey', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'survey' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Survey
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
                                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Daftar Ulang Madrasah</h4>

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
                                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Daftar Ulang Seragam</h4>

                                <!-- Paket Seragam -->
                                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        Paket Seragam
                                    </h5>
                                    @if($seragam && $seragam->paket_seragam)
                                        <p class="text-sm text-gray-700"><strong>Paket yang dipilih:</strong> {{ $seragam->paket_seragam }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Belum memilih paket seragam</p>
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
                                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b pb-2">Ukuran Seragam & Atribut</h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Ukuran Jas -->
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Ukuran Jas</h5>
                                        @if($seragam && $seragam->ukuran_jas)
                                            <p class="text-sm text-gray-700">{{ $seragam->ukuran_jas }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Atasan -->
                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Ukuran Atasan/Baju</h5>
                                        @if($seragam && $seragam->ukuran_atasan)
                                            <p class="text-sm text-gray-700">{{ $seragam->ukuran_atasan }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Bawahan -->
                                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Ukuran Bawahan/Rok/Celana</h5>
                                        @if($seragam && $seragam->ukuran_bawahan)
                                            <p class="text-sm text-gray-700">{{ $seragam->ukuran_bawahan }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum diisi</p>
                                        @endif
                                    </div>

                                    <!-- Ukuran Kopyah (Khusus Laki-laki) -->
                                    @if($pendaftar->jenis_kelamin === 'L')
                                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-900 mb-2">Ukuran Kopyah (Laki-laki)</h5>
                                        @if($seragam && $seragam->ukuran_kopyah)
                                            <p class="text-sm text-gray-700">{{ $seragam->ukuran_kopyah }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 italic">Belum diisi</p>
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
                                            <select wire:model="{{$statusField}}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm">
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
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm"
                                                placeholder="Tambahkan catatan (opsional)"
                                            >
                                        </div>
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
                                                wire:target="updateStatusDokumen"
                                                class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center"
                                            >
                                                <span wire:loading.remove wire:target="updateStatusDokumen" class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Simpan Status
                                                </span>
                                                <span wire:loading wire:target="updateStatusDokumen" class="inline-flex items-center">
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
                <a href="{{ route($routePrefix . '.pendaftar') }}" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Pendaftar
                </a>
            </div>
        </div>
    @endif
</div>
