<div>
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

    <!-- Status Pendaftaran -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Side: Foto & Info Pendaftar -->
            <div class="flex-1">
                <div class="flex items-start space-x-6">
                    <!-- Foto Pendaftar -->
                    <div class="flex-shrink-0">
                        @if($pendaftar->foto)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $pendaftar->foto) }}" alt="Foto {{ $pendaftar->nama_lengkap }}" class="w-28 h-28 rounded-2xl object-cover border-4 border-white shadow-xl">
                                <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1.5 shadow-lg">
                                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-emerald-800 to-emerald-900 bg-opacity-50 backdrop-blur-sm border-4 border-white shadow-xl flex items-center justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Info Pendaftar -->
                    <div class="flex-1">
                        <!-- Nama -->
                        <div class="mb-4">
                            <h2 class="text-4xl font-extrabold text-white mb-1 tracking-tight">{{ $pendaftar->nama_lengkap }}</h2>
                            <p class="text-emerald-200 text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                No. Pendaftaran: <span class="font-bold text-white bg-black bg-opacity-30 px-2 py-0.5 rounded">{{ $pendaftar->nomor_pendaftaran }}</span>
                            </p>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- NISN -->
                            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-3 border-2 border-white shadow-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-emerald-700 font-medium uppercase tracking-wider mb-0.5">NISN</p>
                                        <p class="text-gray-900 font-bold text-base">{{ $pendaftar->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Jalur -->
                            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-3 border-2 border-white shadow-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-emerald-700 font-medium uppercase tracking-wider mb-0.5">Jalur Pendaftaran</p>
                                        <p class="text-gray-900 font-bold text-base">{{ $pendaftar->jalurPendaftaran->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Asal Sekolah -->
                            <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-3 border-2 border-white shadow-lg sm:col-span-2">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-emerald-700 font-medium uppercase tracking-wider mb-0.5">Asal Sekolah</p>
                                        <p class="text-gray-900 font-bold text-base">{{ $pendaftar->asal_sekolah ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Status Badges (3 Rows) -->
            <div class="lg:w-96">
                <div class="space-y-3">
                    @php
                        $badges = $this->statusBadges ?? [];
                        $colorStyles = [
                            'yellow' => 'from-yellow-400 to-yellow-600 border-yellow-300',
                            'blue' => 'from-blue-400 to-blue-600 border-blue-300',
                            'green' => 'from-green-400 to-green-600 border-green-300',
                            'red' => 'from-red-400 to-red-600 border-red-300',
                            'orange' => 'from-orange-400 to-orange-600 border-orange-300',
                            'purple' => 'from-purple-400 to-purple-600 border-purple-300',
                            'gray' => 'from-gray-400 to-gray-600 border-gray-300',
                        ];

                        $iconMap = [
                            'Status Pendaftaran' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'Status Kelulusan' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                            'Status Daftar Ulang' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ];
                    @endphp
                    @if(count($badges) > 0)
                        @foreach($badges as $badge)
                            @php
                                $gradient = $colorStyles[$badge['color']] ?? $colorStyles['gray'];
                                $icon = $iconMap[$badge['type']] ?? $iconMap['Status Pendaftaran'];
                            @endphp
                            <div class="relative overflow-hidden bg-gradient-to-r {{ $gradient }} rounded-xl shadow-lg border-2 border-white border-opacity-30 hover:scale-[1.02] transition-transform duration-200">
                                <div class="absolute top-0 right-0 opacity-10">
                                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="{{ $icon }}"></path>
                                    </svg>
                                </div>
                                <div class="relative p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-xs font-medium text-white opacity-90 uppercase tracking-wider mb-1">{{ $badge['type'] }}</p>
                                            <div class="flex items-center gap-3">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"></path>
                                                </svg>
                                                <span class="font-bold text-white text-lg">{{ $badge['label'] }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <span class="relative flex h-3 w-3">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 border-2 border-white border-opacity-30">
                            <span class="text-white font-medium">Tidak Ada Status</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Petunjuk Cetak Kartu Ujian (When Data Disetujui) -->
    @if(in_array($pendaftar->status_pendaftaran, ['data_disetujui']))
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 mb-8 text-white border-4 border-blue-300">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <h2 class="text-2xl font-bold">✅ Data Terverifikasi - Siap Ujian!</h2>
                    </div>
                    <p class="text-lg mb-4">Selamat! Data Anda telah diverifikasi dan disetujui oleh panitia.</p>
                    <div class="bg-blue-700 bg-opacity-90 rounded-lg p-4 mb-4">
                        <h3 class="font-bold mb-3 text-lg text-white">📋 Langkah Selanjutnya:</h3>
                        <ol class="space-y-2 ml-1">
                            <li class="flex items-start text-white">
                                <span class="flex-shrink-0 w-6 h-6 bg-white text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                <span class="pt-0.5"><strong>Cetak Kartu Ujian</strong> - Klik tombol di bawah atau menu "Cetak Kartu" untuk mengunduh kartu ujian Anda</span>
                            </li>
                            <li class="flex items-start text-white">
                                <span class="flex-shrink-0 w-6 h-6 bg-white text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                                <span class="pt-0.5"><strong>Persiapkan Diri</strong> - Pelajari materi ujian dan persiapkan diri dengan baik</span>
                            </li>
                            <li class="flex items-start text-white">
                                <span class="flex-shrink-0 w-6 h-6 bg-white text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                                <span class="pt-0.5"><strong>Datang Tepat Waktu</strong> - Bawa kartu ujian yang sudah dicetak dan dokumen identitas pada hari ujian</span>
                            </li>
                            <li class="flex items-start text-white">
                                <span class="flex-shrink-0 w-6 h-6 bg-white text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                                <span class="pt-0.5"><strong>Pantau Jadwal</strong> - Periksa jadwal ujian di bawah pada bagian "Timeline & Jadwal"</span>
                            </li>
                        </ol>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="/pendaftar/kartu-ujian" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105 shadow-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Cetak Kartu Ujian
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <svg class="w-32 h-32 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
    @endif

    <!-- Persetujuan Daftar Ulang (Only for LULUS) -->
    @if($pendaftar->status_pendaftaran === 'lulus')
        @if(!$pendaftar->setuju_daftar_ulang)
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg shadow-lg p-8 mb-8 text-white border-4 border-yellow-400 animate-pulse">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <h2 class="text-2xl font-bold">🎉 SELAMAT! Anda LULUS Seleksi</h2>
                        </div>
                        <p class="text-lg mb-4">Langkah selanjutnya adalah melakukan <strong>DAFTAR ULANG</strong> untuk mengamankan kursi Anda.</p>
                        <div class="bg-orange-800 bg-opacity-90 rounded-lg p-4 mb-4">
                            <h3 class="font-bold mb-3 text-lg text-white">📋 Langkah Selanjutnya:</h3>
                            <ol class="space-y-2 ml-1">
                                <li class="flex items-start text-white">
                                    <span class="flex-shrink-0 w-6 h-6 bg-white text-orange-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                    <span class="pt-0.5">Klik tombol <strong>"Saya Setuju Daftar Ulang"</strong> di bawah untuk konfirmasi</span>
                                </li>
                                <li class="flex items-start text-white">
                                    <span class="flex-shrink-0 w-6 h-6 bg-white text-orange-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                                    <span class="pt-0.5">Lengkapi semua persyaratan daftar ulang yang diminta</span>
                                </li>
                                <li class="flex items-start text-white">
                                    <span class="flex-shrink-0 w-6 h-6 bg-white text-orange-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                                    <span class="pt-0.5">Upload dokumen pendukung sesuai ketentuan</span>
                                </li>
                                <li class="flex items-start text-white">
                                    <span class="flex-shrink-0 w-6 h-6 bg-white text-orange-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                                    <span class="pt-0.5">Lakukan pembayaran administrasi sesuai jadwal yang ditentukan</span>
                                </li>
                            </ol>
                        </div>
                        <div class="flex items-center gap-4">
                            <button
                                wire:click="setujuDaftarUlang"
                                wire:loading.attr="disabled"
                                @if($pendaftar->status_persetujuan_daftar_ulang && $pendaftar->status_persetujuan_daftar_ulang !== 'belum_memilih') disabled @endif
                                onclick="return confirm('Apakah Anda yakin ingin menyetujui daftar ulang? Setelah setuju, Anda perlu melengkapi semua persyaratan daftar ulang.')"
                                class="bg-white text-orange-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-yellow-100 transition transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="setujuDaftarUlang">✓ Saya Setuju Daftar Ulang</span>
                                <span wire:loading wire:target="setujuDaftarUlang">Memproses...</span>
                            </button>
                            <button
                                wire:click="tidakSetujuDaftarUlang"
                                wire:loading.attr="disabled"
                                @if($pendaftar->status_persetujuan_daftar_ulang && $pendaftar->status_persetujuan_daftar_ulang !== 'belum_memilih') disabled @endif
                                onclick="return confirm('Apakah Anda yakin TIDAK INGIN melanjutkan daftar ulang? Keputusan ini akan dicatat oleh sistem.')"
                                class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-300 transition transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="tidakSetujuDaftarUlang">✗ Tidak Melanjutkan Daftar Ulang</span>
                                <span wire:loading wire:target="tidakSetujuDaftarUlang">Memproses...</span>
                            </button>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <svg class="w-32 h-32 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @else
            @if($pendaftar->status_persetujuan_daftar_ulang === 'setuju')
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="text-xl font-bold">✓ Persetujuan Daftar Ulang Tercatat</h3>
                            </div>
                            <p class="text-green-100">Terima kasih! Anda telah menyetujui untuk daftar ulang pada {{ $pendaftar->tanggal_pilihan_daftar_ulang?->format('d M Y, H:i') }}</p>
                            <p class="text-green-100 mt-2">Silakan lengkapi persyaratan daftar ulang dan lakukan pembayaran segera.</p>
                        </div>
                    <div class="hidden md:block">
                        <svg class="w-16 h-16 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @elseif($pendaftar->status_persetujuan_daftar_ulang === 'tidak_setuju')
                <div class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <h3 class="text-xl font-bold">Pilihan Tercatat: Tidak Melanjutkan Daftar Ulang</h3>
                            </div>
                            <p class="text-gray-100">Anda telah memilih untuk TIDAK melanjutkan daftar ulang pada {{ $pendaftar->tanggal_pilihan_daftar_ulang?->format('d M Y, H:i') }}</p>
                            <p class="text-gray-100 mt-2">Terima kasih atas partisipasi Anda dalam proses seleksi ini.</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif

    <!-- Progress Steps -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Progress Pendaftaran</h3>
            </div>
        </div>
        <div class="p-4 md:p-6 overflow-x-auto">
            <div class="flex items-start justify-between min-w-[900px] md:min-w-0">
                @php
                    $progressSteps = [
                        'akun' => 'Akun',
                        'formulir' => 'Formulir',
                        'upload_berkas' => 'Upload Berkas'
                    ];

                    if($tesPsikologiAktif) {
                        $progressSteps['tes_psikologi'] = 'Tes Psikologi';
                    }

                    $progressSteps = array_merge($progressSteps, [
                        'verifikasi' => 'Verifikasi',
                        'ujian' => 'Ujian',
                        'pengumuman' => 'Pengumuman',
                        'daftar_ulang' => 'Daftar Ulang',
                        'upload_daftar_ulang' => 'Upload',
                        'verifikasi_akhir' => 'Verifikasi'
                    ]);
                @endphp
                @foreach($progressSteps as $key => $label)
                    <div class="flex-1 text-center relative min-w-[70px] md:min-w-0">
                        <div class="flex flex-col items-center px-1">
                            @if($progress[$key]['status'] === 'completed')
                                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-emerald-600 rounded-full mb-1 md:mb-2 z-10 flex-shrink-0">
                                    <svg class="w-4 h-4 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @elseif($progress[$key]['status'] === 'current')
                                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-yellow-500 rounded-full animate-pulse mb-1 md:mb-2 z-10 flex-shrink-0">
                                    <span class="text-white font-bold text-xs md:text-base">{{ $loop->iteration }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-gray-300 rounded-full mb-1 md:mb-2 z-10 flex-shrink-0">
                                    <span class="text-gray-600 font-bold text-xs md:text-base">{{ $loop->iteration }}</span>
                                </div>
                            @endif

                            <p class="text-[10px] md:text-sm font-medium {{ $progress[$key]['status'] === 'pending' ? 'text-gray-500' : 'text-gray-900' }} leading-tight break-words w-full">{{ $label }}</p>
                            <p class="text-[9px] md:text-xs text-{{ $progress[$key]['color'] }}-600 leading-tight mt-0.5">{{ $progress[$key]['label'] }}</p>
                        </div>

                        <!-- Connector Line (except for last item) -->
                        @if(!$loop->last)
                            <div class="absolute top-4 md:top-5 left-1/2 w-full h-0.5 md:h-1 bg-gray-200 -z-0">
                                <div class="h-full {{ $progress[$key]['status'] === 'completed' ? 'bg-emerald-500' : 'bg-gray-200' }}" style="width: 100%"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Row 1: Data Pribadi & Menu Cepat -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Data Pribadi -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Data Pribadi</h3>
                </div>
                <a href="/pendaftar/formulir" class="text-xs font-bold text-blue-100 hover:text-white uppercase tracking-widest bg-blue-700 px-3 py-1 rounded-full transition-colors">
                    Edit →
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->nama_lengkap }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">NISN</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->nisn }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">TTL</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d M Y') }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->user->email ?? '-' }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">No. WhatsApp Ortu</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->telepon_ortu ?? '-' }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">Jalur</p>
                        <p class="font-medium text-gray-900 text-right">
                            @php
                                $jalurColors = [
                                    'blue' => 'bg-blue-100 text-blue-800',
                                    'emerald' => 'bg-emerald-100 text-emerald-800',
                                    'purple' => 'bg-purple-100 text-purple-800',
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    'red' => 'bg-red-100 text-red-800',
                                    'orange' => 'bg-orange-100 text-orange-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 {{ $jalurColors[$jalur->warna] ?? 'bg-blue-100 text-blue-800' }} rounded text-xs font-semibold">{{ $jalur->nama }}</span>
                        </p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">Asal Sekolah</p>
                        <p class="font-medium text-gray-900 text-right">{{ $pendaftar->asal_sekolah }}</p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">NSM/NPSN</p>
                        <p class="font-medium text-gray-900 text-right">
                            @if($pendaftar->jenis_sekolah_asal === 'madrasah')
                                {{ $pendaftar->nsm_sekolah_asal ?? '-' }}
                            @elseif($pendaftar->jenis_sekolah_asal === 'umum')
                                {{ $pendaftar->npsn_sekolah_asal ?? '-' }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <p class="text-sm text-gray-600">Asal Kab/Kota</p>
                        <p class="font-medium text-gray-900 text-right">
                            @php
                                $kabKota = '';
                                if ($pendaftar->siswa_kabupaten) {
                                    $regency = \App\Models\Regency::find($pendaftar->siswa_kabupaten);
                                    $kabKota = $regency ? $regency->name : '-';
                                }
                            @endphp
                            {{ $kabKota ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Cepat -->
        <div class="bg-white rounded-lg shadow overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Menu Cepat</h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="/pendaftar/formulir" class="flex items-center px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Edit Formulir</span>
                </a>
                <a href="/pendaftar/dokumen" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Upload Dokumen</span>
                </a>
                <a href="/pendaftar/tes-psikologi" class="flex items-center px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Tes Psikologi</span>
                </a>
                <a href="#" onclick="alert('Fitur status pendaftaran')" class="flex items-center px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Status Pendaftaran</span>
                </a>
                <a href="/pendaftar/daftarulang" class="flex items-center px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Daftar Ulang</span>
                </a>
                <a href="/pendaftar/kartu-ujian" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-900">Cetak Kartu</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Row 2: Status Dokumen & Timeline & Jadwal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6" style="align-items: stretch;">
        <!-- Status Dokumen -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden flex flex-col" style="height: 760px;">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Status Dokumen</h3>
                </div>
                <a href="/pendaftar/dokumen" class="text-xs font-bold text-emerald-100 hover:text-white uppercase tracking-widest bg-emerald-700 px-3 py-1 rounded-full transition-colors">
                    Kelola →
                </a>
            </div>
            <div class="p-6 overflow-y-auto" style="flex: 1; min-height: 0;">
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

                    // Menggunakan documentLabels dari controller untuk konsistensi dengan halaman upload dokumen
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
                    $totalDokumen = count($documentLabels);
                @endphp

                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress Upload Dokumen Wajib</span>
                        <span class="text-sm font-bold {{ $uploadedWajib == $totalWajib ? 'text-green-600' : 'text-orange-600' }}">{{ $uploadedWajib }}/{{ $totalWajib }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" style="width: {{ $totalWajib > 0 ? ($uploadedWajib / $totalWajib) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <!-- Daftar Dokumen -->
                <div class="space-y-2">
                    @foreach($documentLabels as $jenis => $dokInfo)
                        @php
                            $isUploaded = $cekDokumen($jenis);

                            // Check if this is a conditional upload (KIP/PKH/KKS)
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

                            // Determine background color based on status
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
                                            <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded-full font-medium">🔒 TERKUNCI</span>
                                        @else
                                            <span class="text-xs bg-gray-400 text-white px-2 py-0.5 rounded-full font-medium">Opsional</span>
                                        @endif
                                    </div>

                                    @if($isConditional && !$isEnabled)
                                        <p class="text-xs text-blue-600 mt-1 font-medium">
                                            Isi nomor {{ strtoupper(str_replace('nomor_', '', $dokInfo['conditional'])) }} di formulir untuk mengaktifkan
                                        </p>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <div class="ml-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusInfo['color'] }}">
                                        @if($currentStatus === 'disetujui')
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($currentStatus === 'menunggu_verifikasi')
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif(in_array($currentStatus, ['tidak_sesuai', 'perlu_perbaikan']))
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
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

                <!-- Warning -->
                @if($uploadedWajib < $totalWajib)
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded mt-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-yellow-800">Perhatian!</p>
                                <p class="text-xs text-yellow-700">Lengkapi {{ $totalWajib - $uploadedWajib }} dokumen WAJIB yang belum diupload. Klik tombol "Kelola" untuk upload dokumen.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded mt-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-green-800">Lengkap!</p>
                                <p class="text-xs text-green-700">Semua dokumen wajib sudah terupload. Pastikan dokumen yang diupload benar dan jelas.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timeline & Jadwal -->
        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col" style="height: 760px;">
            <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-500 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-white mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-base font-bold text-white">Timeline & Jadwal</h3>
                </div>
                @if($timeline->count() > 0)
                    <span class="text-xs bg-white text-gray-900 px-2.5 py-1 rounded-full font-bold shadow-sm">
                        {{ $timeline->count() }} Kegiatan
                    </span>
                @endif
            </div>
            <div class="p-4 overflow-y-auto" style="flex: 1; min-height: 0;">
                @forelse($timeline as $index => $item)
                    @php
                        $tanggalMulai = \Carbon\Carbon::parse($item->tanggal_mulai);
                        $tanggalSelesai = $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai) : null;
                        $today = \Carbon\Carbon::today();

                        // Determine status
                        $status = 'upcoming'; // default
                        $statusColor = 'bg-blue-50 border-blue-200 text-blue-600';
                        $dotColor = 'bg-blue-500';

                        if ($tanggalSelesai && $today->between($tanggalMulai, $tanggalSelesai)) {
                            $status = 'ongoing';
                            $statusColor = 'bg-green-50 border-green-200 text-green-600';
                            $dotColor = 'bg-green-500 animate-pulse';
                        } elseif ($tanggalSelesai && $today->greaterThan($tanggalSelesai)) {
                            $status = 'past';
                            $statusColor = 'bg-gray-50 border-gray-200 text-gray-500';
                            $dotColor = 'bg-gray-400';
                        } elseif (!$tanggalSelesai && $today->greaterThan($tanggalMulai)) {
                            $status = 'past';
                            $statusColor = 'bg-gray-50 border-gray-200 text-gray-500';
                            $dotColor = 'bg-gray-400';
                        }
                    @endphp

                    <div class="relative {{ $index < $timeline->count() - 1 ? 'pb-4' : '' }}">
                        <!-- Timeline Line -->
                        @if($index < $timeline->count() - 1)
                            <div class="absolute left-5 top-12 bottom-0 w-0.5 {{ $status === 'past' ? 'bg-gray-300' : 'bg-blue-300' }}"></div>
                        @endif

                        <!-- Item Card -->
                        <div class="relative flex gap-3">
                            <!-- Date Badge -->
                            <div class="flex-shrink-0 relative z-10">
                                <div class="flex flex-col items-center justify-center w-11 h-11 rounded-lg {{ $statusColor }} border-2 font-bold shadow-sm">
                                    <span class="text-base leading-none">{{ $tanggalMulai->format('d') }}</span>
                                    <span class="text-[9px] leading-none uppercase mt-0.5">{{ $tanggalMulai->format('M') }}</span>
                                </div>
                                <!-- Status Dot -->
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 {{ $dotColor }} rounded-full border-2 border-white"></div>
                            </div>

                            <!-- Content Card -->
                            <div class="flex-1 min-w-0 {{ $statusColor }} border rounded-lg p-3 shadow-sm">
                                <div class="flex items-start justify-between mb-1">
                                    <h4 class="text-sm font-bold text-gray-900 break-words flex-1">{{ $item->judul_kegiatan }}</h4>
                                    @if($status === 'ongoing')
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500 text-white whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-white rounded-full mr-1 animate-pulse"></span>
                                            BERLANGSUNG
                                        </span>
                                    @elseif($status === 'past')
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-400 text-white whitespace-nowrap">
                                            SELESAI
                                        </span>
                                    @endif
                                </div>

                                <!-- Date Range -->
                                <div class="flex items-center text-xs font-semibold {{ $status === 'past' ? 'text-gray-500' : 'text-emerald-600' }} mb-1">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="break-words">
                                        {{ $tanggalMulai->format('d M Y') }}
                                        @if($tanggalSelesai && !$tanggalSelesai->isSameDay($tanggalMulai))
                                            <span class="mx-1">→</span>{{ $tanggalSelesai->format('d M Y') }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Time -->
                                @if($item->waktu_mulai)
                                    <div class="flex items-center text-xs {{ $status === 'past' ? 'text-gray-500' : 'text-gray-600' }} mb-1">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                                        @if($item->waktu_selesai)
                                            - {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}
                                        @endif
                                        WIB
                                    </div>
                                @endif

                                <!-- Description -->
                                @if($item->deskripsi)
                                    <p class="text-xs {{ $status === 'past' ? 'text-gray-500' : 'text-gray-600' }} break-words line-clamp-2">{{ $item->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Belum ada jadwal kegiatan</p>
                        <p class="text-xs text-gray-400 mt-1">Jadwal untuk jalur Anda akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row 3: Pengumuman & Nilai Rapor -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Pengumuman Pendaftar -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg overflow-hidden h-full">
            <!-- Header Pengumuman -->
            <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengumuman</h3>
                        <p class="text-xs text-indigo-100">Informasi penting untuk pendaftar</p>
                    </div>
                </div>
                <span class="bg-white text-gray-900 text-xs px-3 py-1 rounded-full font-semibold">
                    {{ count($pengumumanPendaftar) }} Pengumuman
                </span>
            </div>

            <!-- Konten Pengumuman -->
            <div class="p-6 max-h-[480px] overflow-y-auto bg-gradient-to-b from-gray-50 to-white">
                @forelse($pengumumanPendaftar as $index => $pengumuman)
                    @php
                        $isNew = \Carbon\Carbon::parse($pengumuman->tanggal_publikasi)->isAfter(\Carbon\Carbon::now()->subDays(3));
                        $prioritas = $pengumuman->prioritas ?? 'normal';
                        $prioritasColors = [
                            'urgent' => 'bg-red-100 border-red-300 ring-red-500',
                            'penting' => 'bg-orange-100 border-orange-300 ring-orange-500',
                            'normal' => 'bg-blue-50 border-blue-200 ring-blue-500'
                        ];
                        $prioritasIcons = [
                            'urgent' => 'text-red-600 bg-red-100',
                            'penting' => 'text-orange-600 bg-orange-100',
                            'normal' => 'text-blue-600 bg-blue-100'
                        ];
                        $prioritasLabels = [
                            'urgent' => 'URGENT',
                            'penting' => 'PENTING',
                            'normal' => 'INFO'
                        ];
                    @endphp

                    <div class="mb-4 transform transition-all duration-200 hover:scale-[1.02]">
                        <div class="border-2 {{ $prioritasColors[$prioritas] ?? $prioritasColors['normal'] }} rounded-xl p-4 hover:ring-2 transition-all">
                            <!-- Header Pengumuman Item -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <!-- Icon Kategori -->
                                    <div class="rounded-lg p-2 {{ $prioritasIcons[$prioritas] ?? $prioritasIcons['normal'] }}">
                                        @if($prioritas == 'urgent')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($prioritas == 'penting')
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                            </svg>
                                        @endif
                                    </div>

                                    <div>
                                        <!-- Label & Tanggal -->
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-bold px-2 py-0.5 rounded
                                                {{ $prioritas == 'urgent' ? 'bg-red-200 text-red-800' :
                                                   ($prioritas == 'penting' ? 'bg-orange-200 text-orange-800' :
                                                   'bg-blue-200 text-blue-800') }}">
                                                {{ $prioritasLabels[$prioritas] ?? 'INFO' }}
                                            </span>
                                            @if($isNew)
                                                <span class="text-xs font-bold px-2 py-0.5 bg-green-200 text-green-800 rounded animate-pulse">
                                                    BARU
                                                </span>
                                            @endif
                                            <span class="text-xs text-gray-500">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($pengumuman->tanggal_publikasi)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Judul & Konten -->
                            <h4 class="font-bold text-base text-gray-900 mb-2">{{ $pengumuman->judul }}</h4>
                            <div class="text-sm text-gray-600 leading-relaxed">
                                {!! Str::limit(strip_tags($pengumuman->konten), 150) !!}
                            </div>

                            <!-- Footer dengan Action -->
                            @if(strlen(strip_tags($pengumuman->konten)) > 150)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <button onclick="alert('Detail pengumuman: {{ $pengumuman->judul }}')"
                                            class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                                        <span>Baca Selengkapnya</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium mb-1">Belum Ada Pengumuman</p>
                        <p class="text-sm text-gray-400">Pengumuman akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Nilai Rapor -->
        <div class="bg-white rounded-lg shadow overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Nilai Rapor</h3>
                </div>
                <div class="text-white text-lg font-bold">
                    Rata-rata: {{ number_format($pendaftar->nilai_rata_rata, 2) }}
                </div>
            </div>
            <div class="p-6 space-y-4">
                <!-- Kelas 5 Semester 1 -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Kelas 5 Semester 1</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">B. Indo</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_b_indo_5_1 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">MTK</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_matematika_5_1 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">IPA</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_ipa_5_1 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kelas 5 Semester 2 -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Kelas 5 Semester 2</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">B. Indo</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_b_indo_5_2 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">MTK</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_matematika_5_2 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">IPA</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_ipa_5_2 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kelas 6 Semester 1 -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Kelas 6 Semester 1</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">B. Indo</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_b_indo_6_1 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">MTK</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_matematika_6_1 }}</p>
                        </div>
                        <div class="bg-gray-50 rounded p-2 text-center">
                            <p class="text-[10px] text-gray-500 uppercase">IPA</p>
                            <p class="text-md font-bold text-gray-900">{{ $pendaftar->nilai_ipa_6_1 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>