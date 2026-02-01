<div>
    @if(!in_array($pendaftar->status_pendaftaran, ['data_disetujui', 'lulus', 'diterima']))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Menu daftar ulang hanya tersedia bagi pendaftar dengan status <strong>DATA DISETUJUI</strong> atau <strong>LULUS</strong>.
                        Status Anda saat ini: <strong class="uppercase">{{ str_replace('_', ' ', $pendaftar->status_pendaftaran ?? 'menunggu') }}</strong>.
                        Silakan cek <a href="{{ route('pendaftar.dashboard') }}" class="font-bold underline">Dashboard</a> untuk informasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    @elseif($pendaftar->status_pendaftaran == 'data_disetujui')
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Menunggu Pengumuman Kelulusan</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Data pendaftaran Anda telah disetujui. Silakan menunggu pengumuman kelulusan/tes untuk dapat melanjutkan ke tahap daftar ulang.
                        Pantau terus informasi terbaru di <a href="{{ route('pendaftar.dashboard') }}" class="font-bold underline">Dashboard</a>.
                    </p>
                </div>
            </div>
        </div>
    @else
        @if(!$pendaftar->setuju_daftar_ulang)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Anda dinyatakan Lulus! Silakan lakukan konfirmasi persetujuan daftar ulang di <a href="{{ route('pendaftar.dashboard') }}" class="font-bold underline">Dashboard</a> terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Status Overview -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-blue-600">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Status Daftar Ulang</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Status Daftar Ulang Madrasah -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h4 class="text-sm font-semibold text-gray-700">Daftar Ulang Madrasah</h4>
                            </div>
                            @php
                                // Determine status based on bukti pembayaran and actual status
                                if ($daftarUlang->status === 'disetujui' || $daftarUlang->status === 'selesai') {
                                    $statusMadrasah = 'disetujui';
                                } elseif ($daftarUlang->status === 'ditolak') {
                                    $statusMadrasah = 'ditolak';
                                } elseif ($daftarUlang->status === 'perbaiki') {
                                    $statusMadrasah = 'perbaiki';
                                } elseif ($daftarUlang->bukti_pembayaran_madrasah) {
                                    // If bukti uploaded but status not set or 'menunggu'
                                    $statusMadrasah = 'menunggu';
                                } else {
                                    // No bukti uploaded yet
                                    $statusMadrasah = 'belum_upload';
                                }

                                $statusMadrasahInfo = match($statusMadrasah) {
                                    'disetujui' => ['label' => 'DISETUJUI', 'color' => 'bg-green-100 text-green-800 border-green-300', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'ditolak' => ['label' => 'DITOLAK', 'color' => 'bg-red-100 text-red-800 border-red-300', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'perbaiki' => ['label' => 'PERLU PERBAIKI', 'color' => 'bg-orange-100 text-orange-800 border-orange-300', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                                    'menunggu' => ['label' => 'MENUNGGU', 'color' => 'bg-yellow-100 text-yellow-800 border-yellow-300', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    default => ['label' => 'BELUM UPLOAD', 'color' => 'bg-gray-100 text-gray-800 border-gray-300', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
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
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Overall Status Description -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        @php
                            $allApproved = ($statusMadrasah === 'disetujui' && $statusSeragam === 'disetujui' && $statusUkuran === 'disetujui');
                            $anyRejected = ($statusMadrasah === 'ditolak' || $statusSeragam === 'ditolak' || $statusUkuran === 'ditolak');
                            $anyPerbaiki = ($statusMadrasah === 'perbaiki' || $statusSeragam === 'perbaiki' || $statusUkuran === 'perbaiki');
                            $allWaiting = ($statusMadrasah === 'menunggu' && $statusSeragam === 'menunggu' && $statusUkuran === 'menunggu');
                        @endphp
                        @if($allApproved)
                            <div class="flex items-center text-green-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Selamat! Semua proses daftar ulang Anda telah disetujui.</span>
                            </div>
                        @elseif($anyRejected)
                            <div class="flex items-center text-red-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Ada data yang ditolak. Silakan periksa catatan dari panitia dan perbaiki data Anda.</span>
                            </div>
                        @elseif($anyPerbaiki)
                            <div class="flex items-center text-orange-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                <span class="font-medium">Ada data yang perlu diperbaiki. Silakan periksa catatan dari panitia dan lakukan perbaikan.</span>
                            </div>
                        @elseif($allWaiting)
                            <div class="flex items-center text-yellow-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Data Anda sedang diperiksa oleh panitia. Mohon tunggu.</span>
                            </div>
                        @else
                            <div class="flex items-center text-gray-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">Silakan lengkapi semua persyaratan daftar ulang di bawah ini.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ $paymentInfoText }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Content -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button wire:click="$set('activeTab', 'madrasah')" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $activeTab === 'madrasah' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Daftar Ulang Madrasah
                        </button>
                        <button wire:click="$set('activeTab', 'seragam')" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $activeTab === 'seragam' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Daftar Ulang Seragam
                        </button>
                        <button wire:click="$set('activeTab', 'ukuran')" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $activeTab === 'ukuran' ? 'border-purple-500 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Ukuran Seragam & Atribut
                        </button>
                    </nav>
                </div>

                <!-- Madrasah Tab -->
                @if($activeTab === 'madrasah')
                <div class="p-6 space-y-6">
                    @php
                        // Check if madrasah is approved - disable all fields if true
                        $isMadrasahApproved = $daftarUlang && $daftarUlang->status === 'disetujui';
                    @endphp

                    <!-- Countdown Timer -->
                    @if($madrasahPaymentEnd)
                        <div x-data="{
                            endTime: '{{ $madrasahPaymentEnd }}',
                            timeRemaining: '',
                            isExpired: false,
                            updateCountdown() {
                                const end = new Date(this.endTime).getTime();
                                const now = new Date().getTime();
                                const distance = end - now;

                                if (distance < 0) {
                                    this.isExpired = true;
                                    this.timeRemaining = 'Waktu telah berakhir';
                                    return;
                                }

                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                this.timeRemaining = `${days} hari ${hours} jam ${minutes} menit ${seconds} detik`;
                            }
                        }" x-init="updateCountdown(); setInterval(() => updateCountdown(), 1000)" class="rounded-lg p-4" :class="isExpired ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200'">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="isExpired ? 'text-red-600' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold" :class="isExpired ? 'text-red-900' : 'text-blue-900'">
                                        Waktu Pembayaran Madrasah
                                    </h4>
                                    <p class="text-sm" :class="isExpired ? 'text-red-700' : 'text-blue-700'" x-text="timeRemaining"></p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success/Error Messages -->
                    @if(session()->has('message'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">{{ session('message') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Status Alert Daftar Ulang Madrasah -->
                    @if($daftarUlang)
                        <x-status-alert
                            :status="$daftarUlang->status ?? 'menunggu'"
                            :catatan="$daftarUlang->catatan ?? null"
                            type="daftar_ulang"
                        />
                    @endif

                    <!-- Informasi/Pengumuman dari Admin -->
                    @php
                        $madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
                        $showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
                        $showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
                    @endphp

                    @if($madrasahInfo)
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-indigo-900">
                                    {!! $madrasahInfo !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Daftar Ulang Madrasah</h4>
                    </div>

                    @if($isMadrasahApproved)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-green-800 font-medium">Data Daftar Ulang Madrasah Sudah Disetujui</p>
                                    <p class="text-xs text-green-600 mt-1">Data daftar ulang madrasah tidak dapat diubah karena sudah disetujui oleh panitia.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
                    @if($showPembayaranMadrasah)
                    <!-- Biaya & Pembayaran Madrasah -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pilih Nominal Biaya Daftar Ulang
                        </h5>

                        @php
                            $madrasahNominals = \App\Models\Setting::get('daftar_ulang_madrasah_nominals', []);
                        @endphp

                        @if($madrasahNominals && count($madrasahNominals) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Nominal Biaya <span class="text-red-500">*</span></label>
                                <div class="space-y-2">
                                    @foreach($madrasahNominals as $index => $nominal)
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors {{ $nominalBiaya == json_encode($nominal) ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="nominalBiaya"
                                                   value="{{ json_encode($nominal) }}"
                                                   class="text-emerald-600 focus:ring-emerald-500">
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <span class="font-medium text-gray-900">{{ $nominal['name'] ?? '-' }}</span>
                                                <span class="font-bold text-emerald-700">Rp {{ number_format($nominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    @if($madrasahEnableOpsiLainnya)
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors {{ $nominalBiaya == 'opsi_lainnya' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="nominalBiaya"
                                                   value="opsi_lainnya"
                                                   class="text-emerald-600 focus:ring-emerald-500">
                                            <div class="ml-3 flex-1">
                                                <span class="font-medium text-gray-900">Opsi Lainnya</span>
                                                <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual</p>
                                            </div>
                                        </label>

                                        @if($nominalBiaya == 'opsi_lainnya')
                                            <div class="ml-6 mt-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                <input type="number"
                                                       wire:model.live="madrasahOpsiLainnyaNominal"
                                                       placeholder="Contoh: 500000"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                @error('madrasahOpsiLainnyaNominal')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @php
                                    $selectedNominal = $nominalBiaya ? json_decode($nominalBiaya, true) : null;
                                @endphp

                                @if($selectedNominal)
                                    <div class="mt-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Nominal yang dipilih:</span>
                                            <span class="text-lg font-bold text-emerald-700">Rp {{ number_format($selectedNominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @error('nominalBiaya')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @php
                                $madrasahPaymentNominals = \App\Models\Setting::get('daftar_ulang_madrasah_payment_nominals', []);
                            @endphp

                            <!-- Konfirmasi Nominal Biaya -->
                            @if($madrasahPaymentNominals && count($madrasahPaymentNominals) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Nominal yang Dibayarkan <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-600 mb-2">Pilih nominal yang akan dibayarkan</p>
                                <div class="space-y-2">
                                    @foreach($madrasahPaymentNominals as $index => $nominal)
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors {{ $konfirmasiNominalBiaya == json_encode($nominal) ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalBiaya"
                                                   value="{{ json_encode($nominal) }}"
                                                   class="text-emerald-600 focus:ring-emerald-500">
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <span class="font-medium text-gray-900">{{ $nominal['name'] ?? '-' }}</span>
                                                <span class="font-bold text-emerald-700">Rp {{ number_format($nominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    @if($madrasahPaymentNominalsEnableOpsiLainnya)
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors {{ $konfirmasiNominalBiaya == 'opsi_lainnya' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalBiaya"
                                                   value="opsi_lainnya"
                                                   class="text-emerald-600 focus:ring-emerald-500">
                                            <div class="ml-3 flex-1">
                                                <span class="font-medium text-gray-900">Opsi Lainnya</span>
                                                <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual</p>
                                            </div>
                                        </label>

                                        @if($konfirmasiNominalBiaya == 'opsi_lainnya')
                                            <div class="ml-6 mt-3 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                <input type="number"
                                                       wire:model.live="konfirmasiNominalBiayaOpsiLainnya"
                                                       placeholder="Contoh: 500000"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                @error('konfirmasiNominalBiayaOpsiLainnya')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @error('konfirmasiNominalBiaya')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        @endif

                        <!-- Opsi Pembayaran -->
                        @php
                            $madrasahPaymentOptions = \App\Models\Setting::get('daftar_ulang_madrasah_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
                            $availableMadrasahOptions = array_filter($madrasahPaymentOptions);
                        @endphp

                        @if(!empty($availableMadrasahOptions))
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi Pembayaran</label>
                                <select wire:model="opsiPembayaran" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Opsi --</option>
                                    @foreach($availableMadrasahOptions as $option => $enabled)
                                        @if($enabled)
                                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Bank Selection -->
                        @php
                            $madrasahBanks = \App\Models\Setting::get('daftar_ulang_madrasah_banks', []);
                        @endphp

                        @if($madrasahBanks && count($madrasahBanks) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Bank/Metode Pembayaran</label>
                                <select wire:model="bankPilihan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
                                    @foreach($madrasahBanks as $bank)
                                        <option value="{{ $bank['name'] ?? '' }}">
                                            {{ $bank['name'] ?? 'Bank' }}@if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif@if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    <!-- End of bg-emerald-50 container for form fields -->
                    </div>
                    <!-- End of disabled wrapper for form fields -->

                    <!-- Upload Bukti Pembayaran -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>

                            @if(!$isMadrasahPaymentOpen)
                                <div class="p-4 bg-red-50 border-2 border-red-200 rounded-lg mb-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-red-800">Waktu Upload Telah Berakhir</p>
                                            <p class="text-xs text-red-600 mt-1">Upload bukti pembayaran tidak dapat dilakukan karena melewati batas waktu yang ditentukan.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah)
                                <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg mb-3">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
                                    </div>
                                    @if($daftarUlang->status == 'disetujui' || $daftarUlang->status == 'selesai')
                                    <div class="flex gap-2">
                                        <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'madrasah', 'file' => basename($daftarUlang->bukti_pembayaran_madrasah)]) }}" target="_blank"
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition text-center flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                        <button wire:click="previewInvoiceMadrasah" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Invoice
                                        </button>
                                    </div>
                                    @else
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm text-yellow-800 font-medium">Menunggu Persetujuan</p>
                                                <p class="text-xs text-yellow-600 mt-1">Bukti pembayaran dan invoice dapat diakses setelah data Anda disetujui oleh panitia.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif

                            @if($daftarUlang && $daftarUlang->status === 'disetujui')
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm text-green-800 font-medium">Data Sudah Disetujui</p>
                                            <p class="text-xs text-green-600 mt-1">Upload tidak dapat dilakukan karena data daftar ulang madrasah sudah disetujui.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif(!$isMadrasahPaymentOpen)
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-800 font-medium">Upload Terkunci</p>
                                            <p class="text-xs text-gray-600 mt-1">Upload tidak dapat dilakukan karena melewati batas waktu pembayaran.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" wire:loading.class="opacity-50" wire:target="buktiPembayaran">
                                    <input type="file" wire:model="buktiPembayaran" id="bukti-madrasah" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="bukti-madrasah" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">
                                            @if($buktiPembayaran)
                                                File terpilih: {{ $buktiPembayaran->getClientOriginalName() }}
                                            @else
                                                Klik untuk upload bukti pembayaran
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max. 2MB)</p>
                                    </label>
                                    <div wire:loading wire:target="buktiPembayaran" class="mt-2">
                                        <span class="text-sm text-gray-600">Uploading...</span>
                                    </div>
                                </div>
                            @endif
                            @error('buktiPembayaran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    @if($showPernyataan)
                    <!-- Surat Pernyataan -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Surat Pernyataan
                        </h5>
                        <p class="text-sm text-gray-600 mb-3">Download template surat pernyataan, isi, tandatangani, lalu upload kembali.</p>

                        @php
                            $templatePath = \App\Models\Setting::get('daftar_ulang_madrasah_template', null);
                        @endphp

                        @if($templatePath)
                            <a href="{{ Storage::url($templatePath) }}" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template
                            </a>
                        @endif

                        @if($daftarUlang && $daftarUlang->surat_pernyataan_path)
                            <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded mb-3">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm text-gray-700 flex-1">Surat pernyataan telah diupload</span>
                                <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'pernyataan', 'file' => basename($daftarUlang->surat_pernyataan_path)]) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat</a>
                            </div>
                        @endif

                        <div class="{{ $isMadrasahApproved ? 'pointer-events-none opacity-60' : '' }}">
                        <!-- Start of disabled wrapper for surat pernyataan upload -->
                        @if($daftarUlang && $daftarUlang->status === 'disetujui')
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm text-green-800 font-medium">Data Sudah Disetujui</p>
                                        <p class="text-xs text-green-600 mt-1">Upload tidak dapat dilakukan karena data daftar ulang madrasah sudah disetujui.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" wire:loading.class="opacity-50" wire:target="suratPernyataan">
                                <input type="file" wire:model="suratPernyataan" id="surat-pernyataan" class="hidden" accept=".pdf">
                                <label for="surat-pernyataan" class="cursor-pointer block w-full h-full">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        @if($suratPernyataan)
                                            File terpilih: {{ $suratPernyataan->getClientOriginalName() }}
                                        @else
                                            Klik untuk upload surat pernyataan
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Hanya PDF (Max. 2MB)</p>
                                </label>
                                <div wire:loading wire:target="suratPernyataan" class="mt-2">
                                    <span class="text-sm text-gray-600">Uploading...</span>
                                </div>
                            </div>
                        @endif
                        @error('suratPernyataan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <!-- End of disabled wrapper for surat pernyataan upload -->
                    </div>
                    @endif

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button
                            wire:click="saveMadrasah"
                            wire:loading.attr="disabled"
                            wire:target="saveMadrasah"
                            @if($isMadrasahApproved) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed {{ $isMadrasahApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <svg wire:loading.remove wire:target="saveMadrasah" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveMadrasah" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveMadrasah">{{ $isMadrasahApproved ? 'Data Sudah Disetujui' : 'Simpan Data' }}</span>
                            <span wire:loading wire:target="saveMadrasah">Menyimpan...</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Seragam Tab -->
                @if($activeTab === 'seragam')
                <div class="p-6 space-y-6">
                    @php
                        // Check if seragam is approved - disable all fields if true
                        $isSeragamApproved = $seragam && $seragam->status_seragam === 'disetujui';
                    @endphp

                    <!-- Countdown Timer Seragam -->
                    @if($seragamPaymentEnd)
                        <div x-data="{
                            endTime: '{{ $seragamPaymentEnd }}',
                            timeRemaining: '',
                            isExpired: false,
                            updateCountdown() {
                                const end = new Date(this.endTime).getTime();
                                const now = new Date().getTime();
                                const distance = end - now;

                                if (distance < 0) {
                                    this.isExpired = true;
                                    this.timeRemaining = 'Waktu telah berakhir';
                                    return;
                                }

                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                this.timeRemaining = `${days} hari ${hours} jam ${minutes} menit ${seconds} detik`;
                            }
                        }" x-init="updateCountdown(); setInterval(() => updateCountdown(), 1000)" class="rounded-lg p-4" :class="isExpired ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200'">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" :class="isExpired ? 'text-red-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold" :class="isExpired ? 'text-red-900' : 'text-green-900'">
                                        Waktu Pembayaran Seragam
                                    </h4>
                                    <p class="text-sm" :class="isExpired ? 'text-red-700' : 'text-green-700'" x-text="timeRemaining"></p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success/Error Messages -->
                    @if(session()->has('message_seragam'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">{{ session('message_seragam') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Status Alert Daftar Ulang Seragam -->
                    @if($seragam && $seragam->status_seragam && in_array($seragam->status_seragam, ['ditolak', 'perbaiki', 'menunggu']))
                        <x-status-alert
                            :status="$seragam->status_seragam"
                            :catatan="$seragam->catatan_seragam ?? null"
                            type="daftar_ulang"
                        />
                    @endif

                    <!-- Informasi/Pengumuman dari Admin -->
                    @php
                        $seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
                        $showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
                    @endphp

                    @if($seragamInfo)
                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 border border-cyan-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-cyan-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-cyan-900">
                                    {!! $seragamInfo !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Daftar Ulang Seragam</h4>
                    </div>

                    <!-- Paket Seragam -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Paket Seragam
                        </h5>

                        @php
                            $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
                        @endphp

                        @if($seragamPackages && count($seragamPackages) > 0)
                            <div class="space-y-2">
                                @foreach($seragamPackages as $index => $package)
                                    <label class="flex items-start p-3 border-2 border-gray-300 rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:border-indigo-500' }} transition">
                                        <input type="radio" wire:model="paketSeragam" name="paket_seragam" value="{{ $index }}" class="mt-1 text-indigo-600 focus:ring-indigo-500" {{ $isSeragamApproved ? 'disabled' : '' }}>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-900">{{ $package['name'] ?? '-' }}</p>
                                            <p class="text-sm text-gray-600">{{ $package['description'] ?? '-' }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Paket seragam belum tersedia</p>
                        @endif
                    </div>

                    @if($showPembayaranSeragam)
                    <!-- Biaya & Pembayaran Seragam -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pilih Nominal Biaya Seragam
                        </h5>

                        @php
                            $seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
                        @endphp

                        @if($seragamNominals && count($seragamNominals) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Nominal Biaya <span class="text-red-500">*</span></label>
                                <div class="space-y-2">
                                    @foreach($seragamNominals as $index => $nominal)
                                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors {{ $nominalSeragam == json_encode($nominal) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="nominalSeragam"
                                                   value="{{ json_encode($nominal) }}"
                                                   class="text-green-600 focus:ring-green-500"
                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <span class="font-medium text-gray-900">{{ $nominal['name'] ?? '-' }}</span>
                                                <span class="font-bold text-green-700">Rp {{ number_format($nominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    @if($seragamEnableOpsiLainnya)
                                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors {{ $nominalSeragam == 'opsi_lainnya' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="nominalSeragam"
                                                   value="opsi_lainnya"
                                                   class="text-green-600 focus:ring-green-500"
                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                            <div class="ml-3 flex-1">
                                                <span class="font-medium text-gray-900">Opsi Lainnya</span>
                                                <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual</p>
                                            </div>
                                        </label>

                                        @if($nominalSeragam == 'opsi_lainnya')
                                            <div class="ml-6 mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                <input type="number"
                                                       wire:model.live="seragamOpsiLainnyaNominal"
                                                       placeholder="Contoh: 750000"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                                                       {{ $isSeragamApproved ? 'disabled' : '' }}>
                                                @error('seragamOpsiLainnyaNominal')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @php
                                    $selectedSeragamNominal = $nominalSeragam ? json_decode($nominalSeragam, true) : null;
                                @endphp

                                @if($selectedSeragamNominal)
                                    <div class="mt-3 p-3 bg-green-50 border border-green-300 rounded-lg">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700">Nominal yang dipilih:</span>
                                            <span class="text-lg font-bold text-green-700">Rp {{ number_format($selectedSeragamNominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @error('nominalSeragam')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @php
                                $seragamPaymentNominals = \App\Models\Setting::get('daftar_ulang_seragam_payment_nominals', []);
                            @endphp

                            <!-- Konfirmasi Nominal Biaya Seragam -->
                            @if($seragamPaymentNominals && count($seragamPaymentNominals) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Nominal yang Dibayarkan <span class="text-red-500">*</span>
                                </label>
                                <p class="text-xs text-gray-600 mb-2">Pilih nominal yang akan dibayarkan</p>
                                <div class="space-y-2">
                                    @foreach($seragamPaymentNominals as $index => $nominal)
                                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors {{ $konfirmasiNominalSeragam == json_encode($nominal) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalSeragam"
                                                   value="{{ json_encode($nominal) }}"
                                                   class="text-green-600 focus:ring-green-500"
                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <span class="font-medium text-gray-900">{{ $nominal['name'] ?? '-' }}</span>
                                                <span class="font-bold text-green-700">Rp {{ number_format($nominal['amount'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    @if($seragamPaymentNominalsEnableOpsiLainnya)
                                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors {{ $konfirmasiNominalSeragam == 'opsi_lainnya' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalSeragam"
                                                   value="opsi_lainnya"
                                                   class="text-green-600 focus:ring-green-500"
                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                            <div class="ml-3 flex-1">
                                                <span class="font-medium text-gray-900">Opsi Lainnya</span>
                                                <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual</p>
                                            </div>
                                        </label>

                                        @if($konfirmasiNominalSeragam == 'opsi_lainnya')
                                            <div class="ml-6 mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                <input type="number"
                                                       wire:model.live="konfirmasiNominalSeragamOpsiLainnya"
                                                       placeholder="Contoh: 1200000"
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                                                       {{ $isSeragamApproved ? 'disabled' : '' }}>
                                                @error('konfirmasiNominalSeragamOpsiLainnya')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @error('konfirmasiNominalSeragam')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif
                        @endif

                        <!-- Opsi Pembayaran Seragam -->
                        @php
                            $seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
                            $availableSeragamOptions = array_filter($seragamPaymentOptions);
                        @endphp

                        @if(!empty($availableSeragamOptions))
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi Pembayaran</label>
                                <select wire:model="opsiPembayaranSeragam" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}" {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Opsi --</option>
                                    @foreach($availableSeragamOptions as $option => $enabled)
                                        @if($enabled)
                                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Bank Selection Seragam -->
                        @php
                            $seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
                        @endphp

                        @if($seragamBanks && count($seragamBanks) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Bank/Metode Pembayaran</label>
                                <select wire:model="bankSeragam" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}" {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
                                    @foreach($seragamBanks as $bank)
                                        <option value="{{ $bank['name'] ?? '' }}">
                                            {{ $bank['name'] ?? 'Bank' }}@if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif@if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Upload Bukti Pembayaran Seragam -->
                        @php
                            $requireSeragamPaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);
                        @endphp

                        @if($requireSeragamPaymentProof)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>

                                @if(!$isSeragamPaymentOpen)
                                    <div class="p-4 bg-red-50 border-2 border-red-200 rounded-lg mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-red-800">Waktu Upload Telah Berakhir</p>
                                                <p class="text-xs text-red-600 mt-1">Upload bukti pembayaran tidak dapat dilakukan karena melewati batas waktu yang ditentukan.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran)
                                    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg mb-3">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
                                        </div>
                                        @if($pendaftar->seragam->status_seragam == 'disetujui' || $pendaftar->seragam->status_seragam == 'selesai')
                                        <div class="flex gap-2">
                                            <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'seragam', 'file' => basename($pendaftar->seragam->bukti_pembayaran)]) }}" target="_blank"
                                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Bukti
                                            </a>
                                            <button wire:click="downloadInvoiceSeragam" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download Invoice
                                            </button>
                                        </div>
                                        @else
                                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm text-yellow-800 font-medium">Menunggu Persetujuan</p>
                                                    <p class="text-xs text-yellow-600 mt-1">Bukti pembayaran dan invoice dapat diakses setelah data Anda disetujui oleh panitia.</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    @if($seragam && $seragam->status_seragam === 'disetujui')
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm text-green-800 font-medium">Data Sudah Disetujui</p>
                                                    <p class="text-xs text-green-600 mt-1">Upload tidak dapat dilakukan karena data daftar ulang seragam sudah disetujui.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(!$isSeragamPaymentOpen)
                                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-gray-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-800 font-medium">Upload Terkunci</p>
                                                    <p class="text-xs text-gray-600 mt-1">Upload tidak dapat dilakukan karena melewati batas waktu pembayaran.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center" wire:loading.class="opacity-50" wire:target="buktiSeragam">
                                            <input type="file" wire:model="buktiSeragam" id="bukti-seragam" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                            <label for="bukti-seragam" class="cursor-pointer">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    @if($buktiSeragam)
                                                        File terpilih: {{ $buktiSeragam->getClientOriginalName() }}
                                                    @else
                                                        Klik untuk upload bukti
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                                            </label>
                                            <div wire:loading wire:target="buktiSeragam" class="mt-2">
                                                <span class="text-sm text-gray-600">Uploading...</span>
                                            </div>
                                        </div>
                                    @endif
                                    @error('buktiSeragam') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                @endif
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        @php
                            $isSeragamApproved = $seragam && $seragam->status_seragam === 'disetujui';
                        @endphp
                        <button
                            wire:click="saveSeragam"
                            wire:loading.attr="disabled"
                            wire:target="saveSeragam"
                            @if($isSeragamApproved) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed {{ $isSeragamApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <svg wire:loading.remove wire:target="saveSeragam" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveSeragam" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveSeragam">{{ $isSeragamApproved ? 'Data Sudah Disetujui' : 'Simpan Data Seragam' }}</span>
                            <span wire:loading wire:target="saveSeragam">Menyimpan...</span>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Ukuran Tab -->
                @if($activeTab === 'ukuran')
                <div class="p-6 space-y-6">
                    @php
                        // Check if ukuran is approved - disable all fields if true
                        $isUkuranApproved = $seragam && $seragam->status_ukuran === 'disetujui';
                    @endphp

                    <!-- Success/Error Messages -->
                    @if(session()->has('message_ukuran'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-800">{{ session('message_ukuran') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Status Alert Ukuran Seragam -->
                    @if($seragam && $seragam->status_ukuran && in_array($seragam->status_ukuran, ['ditolak', 'perbaiki', 'menunggu']))
                        <x-status-alert
                            :status="$seragam->status_ukuran"
                            :catatan="$seragam->catatan_ukuran ?? null"
                            type="daftar_ulang"
                        />
                    @endif

                    <!-- Informasi/Pengumuman dari Admin -->
                    @php
                        $ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
                        $showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
                        $showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
                        $showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);
                    @endphp

                    @if($ukuranInfo)
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-purple-900">
                                    {!! $ukuranInfo !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Ukuran Seragam & Atribut</h4>
                    </div>

                    @if($isUkuranApproved)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-green-800 font-medium">Data Ukuran Sudah Disetujui</p>
                                    <p class="text-xs text-green-600 mt-1">Data ukuran seragam tidak dapat diubah karena sudah disetujui oleh panitia.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="{{ $isUkuranApproved ? 'pointer-events-none opacity-60' : '' }}">
                    @php
                        $ukuranJasImage = \App\Models\Setting::get('daftar_ulang_ukuran_jas_image', null);
                        $ukuranJasOptions = \App\Models\Setting::get('daftar_ulang_ukuran_jas_options', []);
                        $ukuranBajuOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_image', null);
                        $ukuranBajuOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_olahraga_options', []);
                        $ukuranCelanaOlahragaImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_image', null);
                        $ukuranCelanaOlahragaOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_olahraga_options', []);
                        $ukuranBajuHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_image', null);
                        $ukuranBajuHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_baju_harian_options', []);
                        $ukuranCelanaRokHarianImage = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_image', null);
                        $ukuranCelanaRokHarianOptions = \App\Models\Setting::get('daftar_ulang_ukuran_celana_rok_harian_options', []);
                        $ukuranKopyahImage = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_image', null);
                        $ukuranKopyahOptions = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_options', []);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ukuran Jas -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Jas</h5>
                            @if($ukuranJasImage)
                                <img src="{{ Storage::url($ukuranJasImage) }}" alt="Panduan Ukuran Jas" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranJasOptions && count($ukuranJasOptions) > 0)
                                <select wire:model.live="ukuran_jas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranJasOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranJasEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranJasEnableOpsiLainnya && $ukuran_jas == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranJasOpsiLainnya"
                                               placeholder="Contoh: M Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranJasOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>

                        <!-- Ukuran Baju Seragam Olahraga -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Baju Seragam Olahraga</h5>
                            @if($ukuranBajuOlahragaImage)
                                <img src="{{ Storage::url($ukuranBajuOlahragaImage) }}" alt="Panduan Ukuran Baju Olahraga" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranBajuOlahragaOptions && count($ukuranBajuOlahragaOptions) > 0)
                                <select wire:model.live="ukuran_baju_olahraga" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranBajuOlahragaOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranBajuOlahragaEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranBajuOlahragaEnableOpsiLainnya && $ukuran_baju_olahraga == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranBajuOlahragaOpsiLainnya"
                                               placeholder="Contoh: L Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranBajuOlahragaOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>

                        <!-- Ukuran Celana Seragam Olahraga -->
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Celana Seragam Olahraga</h5>
                            @if($ukuranCelanaOlahragaImage)
                                <img src="{{ Storage::url($ukuranCelanaOlahragaImage) }}" alt="Panduan Ukuran Celana Olahraga" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranCelanaOlahragaOptions && count($ukuranCelanaOlahragaOptions) > 0)
                                <select wire:model.live="ukuran_celana_olahraga" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranCelanaOlahragaOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranCelanaOlahragaEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranCelanaOlahragaEnableOpsiLainnya && $ukuran_celana_olahraga == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranCelanaOlahragaOpsiLainnya"
                                               placeholder="Contoh: XL Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranCelanaOlahragaOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>

                        <!-- Ukuran Baju Seragam Harian -->
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Baju Seragam Harian</h5>
                            @if($ukuranBajuHarianImage)
                                <img src="{{ Storage::url($ukuranBajuHarianImage) }}" alt="Panduan Ukuran Baju Harian" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranBajuHarianOptions && count($ukuranBajuHarianOptions) > 0)
                                <select wire:model.live="ukuran_baju_harian" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranBajuHarianOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranBajuHarianEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranBajuHarianEnableOpsiLainnya && $ukuran_baju_harian == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranBajuHarianOpsiLainnya"
                                               placeholder="Contoh: S Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranBajuHarianOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>

                        <!-- Ukuran Celana/Rok Seragam Harian -->
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">
                                Ukuran {{ $pendaftar->jenis_kelamin === 'L' ? 'Celana' : 'Rok' }} Seragam Harian
                            </h5>
                            @if($ukuranCelanaRokHarianImage)
                                <img src="{{ Storage::url($ukuranCelanaRokHarianImage) }}" alt="Panduan Ukuran Celana/Rok Harian" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranCelanaRokHarianOptions && count($ukuranCelanaRokHarianOptions) > 0)
                                <select wire:model.live="ukuran_celana_rok_harian" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranCelanaRokHarianOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranCelanaRokHarianEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranCelanaRokHarianEnableOpsiLainnya && $ukuran_celana_rok_harian == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranCelanaRokHarianOpsiLainnya"
                                               placeholder="Contoh: M Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranCelanaRokHarianOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>

                        <!-- Ukuran Kopyah (Khusus Laki-laki) -->
                        @if($pendaftar->jenis_kelamin === 'L')
                        <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Kopyah (Laki-laki)</h5>
                            @if($ukuranKopyahImage)
                                <img src="{{ Storage::url($ukuranKopyahImage) }}" alt="Panduan Ukuran Kopyah" class="w-full rounded-lg mb-3">
                            @endif
                            @if($ukuranKopyahOptions && count($ukuranKopyahOptions) > 0)
                                <select wire:model.live="ukuran_kopyah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 mb-3">
                                    <option value="">-- Pilih Ukuran --</option>
                                    @foreach($ukuranKopyahOptions as $option)
                                        <option value="{{ $option['size'] ?? '-' }}">
                                            {{ $option['size'] ?? '-' }} ({{ $option['measurement'] ?? '-' }})
                                        </option>
                                    @endforeach
                                    @if($ukuranKopyahEnableOpsiLainnya)
                                        <option value="opsi_lainnya">Opsi Lainnya</option>
                                    @endif
                                </select>

                                @if($ukuranKopyahEnableOpsiLainnya && $ukuran_kopyah == 'opsi_lainnya')
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Lainnya</label>
                                        <input type="text"
                                               wire:model.live="ukuranKopyahOpsiLainnya"
                                               placeholder="Contoh: 56 Custom"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        @error('ukuranKopyahOpsiLainnya')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Catatan:</strong> Pastikan Anda memilih ukuran yang sesuai dengan panduan gambar. Jika ragu, silakan hubungi panitia.
                        </p>
                    </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button
                            wire:click="saveUkuran"
                            wire:loading.attr="disabled"
                            wire:target="saveUkuran"
                            @if($isUkuranApproved) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed {{ $isUkuranApproved ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <svg wire:loading.remove wire:target="saveUkuran" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveUkuran" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveUkuran">{{ $isUkuranApproved ? 'Data Sudah Disetujui' : 'Simpan Data Ukuran' }}</span>
                            <span wire:loading wire:target="saveUkuran">Menyimpan...</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        @endif
    @endif
</div>
