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
                    <div class="flex md:flex-col items-start md:items-end justify-start md:justify-center">
                        @php
                            // Map old status (kelulusan) to new status (pendaftaran) for backward compatibility
                            $statusPendaftaran = $pendaftar->status_pendaftaran;

                            // Convert old kelulusan status to pendaftaran status
                            if (in_array($statusPendaftaran, ['lulus', 'tidak_lulus', 'cadangan'])) {
                                // Status kelulusan shouldn't appear here, default to menunggu_verifikasi
                                $statusPendaftaran = 'menunggu_verifikasi';
                            }

                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800 border-gray-300',
                                'menunggu' => 'bg-yellow-50 text-yellow-800 border-yellow-300',
                                'menunggu_verifikasi' => 'bg-yellow-50 text-yellow-800 border-yellow-300',
                                'lengkapi_data' => 'bg-orange-50 text-orange-800 border-orange-300',
                                'perbaiki_data' => 'bg-red-50 text-red-800 border-red-300',
                                'data_ditolak' => 'bg-red-50 text-red-800 border-red-300',
                                'data_disetujui' => 'bg-green-50 text-green-800 border-green-300',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'menunggu' => 'Menunggu Verifikasi',
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'lengkapi_data' => 'Lengkapi Data',
                                'perbaiki_data' => 'Perbaiki Data',
                                'data_ditolak' => 'Data Ditolak',
                                'data_disetujui' => 'Data Disetujui',
                            ];
                            $statusClass = $statusColors[$statusPendaftaran] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                            $statusLabel = $statusLabels[$statusPendaftaran] ?? 'Menunggu Verifikasi';
                        @endphp
                        <div class="text-left md:text-right">
                            <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide">Status Pendaftaran</p>
                            <span class="inline-block px-4 py-2.5 rounded-lg text-sm font-bold border-2 shadow-md {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        <x-status-alert
            :status="$pendaftar->status_pendaftaran"
            :catatan="$pendaftar->catatan_verifikasi"
            type="formulir"
        />

        <!-- Tab Navigation dengan 6 Tab EMIS Baru -->
        <div x-data="{ activeTab: $wire.entangle('activeTab') }" class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Tab Buttons -->
            <div class="border-b border-gray-200 overflow-x-auto">
                <nav class="flex -mb-px whitespace-nowrap">
                    <!-- Tab 1: Data Siswa -->
                    <button @click="activeTab = 'siswa'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'siswa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'siswa' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Data Siswa
                        <span class="text-red-500 ml-1">*</span>
                    </button>

                    <!-- Tab 2: Data Orang Tua -->
                    <button @click="activeTab = 'orangtua'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'orangtua' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Data Orang Tua
                        <span class="text-red-500 ml-1">*</span>
                    </button>

                    <!-- Tab 3: Data Alamat -->
                    <button @click="activeTab = 'alamat'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'alamat', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'alamat' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Data Alamat
                        <span class="text-red-500 ml-1">*</span>
                    </button>

                    <!-- Tab 4: Prestasi -->
                    <button @click="activeTab = 'prestasi'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'prestasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prestasi' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        Prestasi Siswa
                        @if($pendaftar->jalurPendaftaran && str_contains(strtolower($pendaftar->jalurPendaftaran->nama), 'prestasi'))
                            <span class="text-red-500 ml-1">*</span>
                        @endif
                    </button>

                    <!-- Tab 5: Keahlian (Kursus/Club/Sanggar) -->
                    <button @click="activeTab = 'keahlian'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'keahlian', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'keahlian' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        Keahlian
                    </button>

                    <!-- Tab 6: Tahfidz -->
                    <button @click="activeTab = 'tahfidz'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'tahfidz', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'tahfidz' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Tahfidz
                    </button>

                    <!-- Tab 7: Survey -->
                    <button @click="activeTab = 'survey'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'survey', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'survey' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Survey
                        <span class="text-red-500 ml-1">*</span>
                    </button>

                    <!-- Tab 8: Beasiswa & Bantuan -->
                    <button @click="activeTab = 'beasiswa'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'beasiswa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'beasiswa' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Beasiswa & Bantuan
                    </button>

                    <!-- Tab 9: Pendidikan Lain -->
                    <button @click="activeTab = 'pendidikan'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'pendidikan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pendidikan' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Pendidikan Lain
                    </button>
                </nav>
            </div>

            <!-- Information Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-500 px-6 py-3">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800 w-full">
                        <p class="mb-2">
                            <span class="font-semibold">Informasi:</span> Tab dengan tanda <span class="text-red-500 font-bold">*</span> (bintang merah) wajib diisi sebelum mengirim pendaftaran.
                        </p>

                        <!-- Tab Wajib untuk Semua Jalur -->
                        <p class="mb-2 text-xs">
                            <span class="font-semibold text-blue-900">Tab Wajib untuk Semua Jalur:</span>
                            <span class="text-blue-700">Data Siswa, Data Orang Tua, Data Alamat, dan Survey</span>
                        </p>

                        <!-- Catatan Khusus Jalur -->
                        <div class="space-y-1 mb-2">
                            @if($pendaftar->jalurPendaftaran && str_contains(strtolower($pendaftar->jalurPendaftaran->nama), 'prestasi'))
                                <p class="text-xs bg-amber-50 border border-amber-200 rounded px-2 py-1 inline-block">
                                    <span class="font-semibold text-amber-800">Catatan Jalur Prestasi:</span> <span class="text-amber-700">Tab <strong>Prestasi Siswa</strong> wajib diisi bagi Jalur Prestasi Non-Akademik</span>
                                </p>
                            @endif

                            @if($pendaftar->jalurPendaftaran && str_contains(strtolower($pendaftar->jalurPendaftaran->nama), 'tahfidz'))
                                <p class="text-xs bg-emerald-50 border border-emerald-200 rounded px-2 py-1 inline-block">
                                    <span class="font-semibold text-emerald-800">Catatan Jalur Tahfidz:</span> <span class="text-emerald-700">Tab <strong>Tahfidz</strong> wajib diisi</span>
                                </p>
                            @endif
                        </div>

                        <!-- Petunjuk Navigasi Tab -->
                        <p class="text-xs bg-blue-100 border border-blue-200 rounded px-2 py-1 inline-flex items-center">
                            <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            <span class="text-blue-700"><strong>Petunjuk:</strong> Geser tab ke kanan/kiri untuk melihat tab lainnya</span>
                        </p>
                    </div>
                </div>
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

                <!-- Tab Prestasi -->
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

                <!-- Tab Survey -->
                <div x-show="activeTab === 'survey'" x-transition>
                    @include('tab_templates.tab-survey')
                </div>

                <!-- Tab Beasiswa & Bantuan -->
                <div x-show="activeTab === 'beasiswa'" x-transition>
                    @include('tab_templates.tab-beasiswa')
                </div>

                <!-- Tab Pendidikan Lain -->
                <div x-show="activeTab === 'pendidikan'" x-transition>
                    @include('tab_templates.tab-pendidikan-lain')
                </div>
            </div>
        </div>
    </div>
</div>
