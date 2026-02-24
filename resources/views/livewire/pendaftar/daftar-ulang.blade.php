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

                        <!-- Status Ukuran Seragam - MOVED TO CENTER -->
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

                        <!-- Status Daftar Ulang Seragam - MOVED TO RIGHT -->
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
                        <button wire:click="$set('activeTab', 'ukuran')" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $activeTab === 'ukuran' ? 'border-purple-500 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Ukuran Seragam & Atribut
                        </button>
                        <button wire:click="$set('activeTab', 'seragam')" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none {{ $activeTab === 'seragam' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Daftar Ulang Seragam
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
                        $showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
                        $showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
                    @endphp

                    @if($this->madrasahInfo)
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-indigo-900 prose prose-sm prose-indigo max-w-none">
                                    {!! $this->madrasahInfo !!}
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

                    @if($showPembayaranMadrasah)

                    @if(!$isMadrasahPaymentOpen)
                        <!-- Pesan jika di luar waktu pembayaran -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-red-900 mb-2">
                                        🕐 Di Luar Waktu Pembayaran Daftar Ulang Madrasah
                                    </h3>
                                    <p class="text-sm text-red-800 mb-3">
                                        Saat ini Anda tidak dapat mengisi form daftar ulang madrasah karena berada di luar waktu yang telah ditentukan.
                                    </p>
                                    @if($madrasahPaymentStart && $madrasahPaymentEnd)
                                        <div class="bg-red-100 border-l-4 border-red-500 rounded p-3">
                                            <p class="text-xs text-red-800 font-semibold mb-2">Jadwal Pembayaran Daftar Ulang Madrasah:</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-red-700">
                                                <div>
                                                    <span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($madrasahPaymentStart)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                                <div>
                                                    <span class="font-medium">Berakhir:</span> {{ \Carbon\Carbon::parse($madrasahPaymentEnd)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex items-start p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-xs text-yellow-800">
                                            Silakan kembali ke halaman ini pada waktu yang telah ditentukan untuk melanjutkan proses daftar ulang madrasah.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                    {{-- Check if status is 'menunggu' or 'disetujui' - show status display only --}}
                    @if($daftarUlang && in_array($daftarUlang->status, ['menunggu', 'disetujui']))

                    <!-- Status Display untuk Menunggu dan Disetujui -->
                    <div class="space-y-4">
                        @if($daftarUlang->status === 'menunggu')
                        <!-- Status Menunggu -->
                        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-8 h-8 text-yellow-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-yellow-900 mb-2">
                                        ⏳ Status: Menunggu Persetujuan
                                    </h3>
                                    <p class="text-base text-yellow-800 mb-4">
                                        Data daftar ulang madrasah Anda telah berhasil dikirim dan sedang dalam proses verifikasi oleh panitia.
                                    </p>
                                    <div class="bg-yellow-100 border-l-4 border-yellow-500 rounded p-4">
                                        <p class="text-sm text-yellow-800 font-medium mb-2">📌 Informasi:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-yellow-700">
                                            <li>Bukti pembayaran dan surat pernyataan Anda sedang diverifikasi</li>
                                            <li>Proses verifikasi biasanya memakan waktu 1-3 hari kerja</li>
                                            <li>Anda akan mendapat notifikasi jika data disetujui atau perlu perbaikan</li>
                                            <li>Pantau terus status di halaman ini</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($daftarUlang->status === 'disetujui')
                        <!-- Status Disetujui -->
                        <div class="bg-green-50 border-2 border-green-400 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-8 h-8 text-green-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-green-900 mb-2">
                                        ✅ Status: Disetujui
                                    </h3>
                                    <p class="text-base text-green-800 mb-4">
                                        Selamat! Data daftar ulang madrasah Anda telah disetujui oleh panitia.
                                    </p>
                                    <div class="bg-green-100 border-l-4 border-green-500 rounded p-4 mb-4">
                                        <p class="text-sm text-green-800 font-medium mb-2">✓ Data Telah Diverifikasi:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-green-700">
                                            <li>Surat pernyataan telah divalidasi</li>
                                            <li>Bukti pembayaran telah diverifikasi</li>
                                            <li>Data nominal pembayaran sesuai</li>
                                            <li>Proses daftar ulang madrasah selesai</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Tombol Lihat Surat Pernyataan -->
                            @if($daftarUlang->surat_pernyataan_path)
                            <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'pernyataan', 'file' => basename($daftarUlang->surat_pernyataan_path)]) }}"
                               target="_blank"
                               class="flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Lihat Surat Pernyataan</span>
                            </a>
                            @endif

                            <!-- Tombol Lihat Bukti Pembayaran -->
                            @if($daftarUlang->bukti_pembayaran_madrasah)
                            <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'madrasah', 'file' => basename($daftarUlang->bukti_pembayaran_madrasah)]) }}"
                               target="_blank"
                               class="flex items-center justify-center px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Lihat Bukti Pembayaran</span>
                            </a>
                            @endif

                            <!-- Tombol Download Invoice -->
                            @if($daftarUlang->status === 'disetujui')
                            <button wire:click="previewInvoiceMadrasah"
                                    class="flex items-center justify-center px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Download Invoice</span>
                            </button>
                            @endif
                        </div>

                        <!-- Info Data yang Telah Disubmit -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Data yang Telah Dikirim
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                @if($daftarUlang->nominal_biaya)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Nominal Biaya (Surat Pernyataan):</p>
                                    <p class="font-semibold text-gray-900">
                                        Rp {{ number_format($daftarUlang->nominal_biaya['amount'] ?? 0, 0, ',', '.') }}
                                        @if(isset($daftarUlang->nominal_biaya['name']))
                                            <span class="text-xs text-gray-500">({{ $daftarUlang->nominal_biaya['name'] }})</span>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                @if($daftarUlang->nominal_biaya_awal)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Nominal Dibayarkan:</p>
                                    <p class="font-semibold text-gray-900">
                                        Rp {{ number_format($daftarUlang->nominal_biaya_awal['amount'] ?? 0, 0, ',', '.') }}
                                        @if(isset($daftarUlang->nominal_biaya_awal['name']))
                                            <span class="text-xs text-gray-500">({{ $daftarUlang->nominal_biaya_awal['name'] }})</span>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                @if($daftarUlang->opsi_pembayaran)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Opsi Pembayaran:</p>
                                    <p class="font-semibold text-gray-900 capitalize">{{ $daftarUlang->opsi_pembayaran }}</p>
                                </div>
                                @endif

                                @if($daftarUlang->metode_pembayaran)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Bank/Metode Pembayaran:</p>
                                    <p class="font-semibold text-gray-900">{{ $daftarUlang->metode_pembayaran }}</p>
                                </div>
                                @endif

                                @if($daftarUlang->nama_pengirim)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Nama Pengirim:</p>
                                    <p class="font-semibold text-gray-900">{{ $daftarUlang->nama_pengirim }}</p>
                                </div>
                                @endif

                                @if($daftarUlang->waktu_pembayaran_madrasah)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Waktu Submit:</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($daftarUlang->waktu_pembayaran_madrasah)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- Form untuk mengisi data (status belum menunggu/disetujui) --}}

                    {{-- Link Grup Daftar Ulang - Muncul saat sudah setuju daftar ulang --}}
                    @php
                        $linkGrupDaftarUlangEnabled = \App\Models\Setting::get('link_grup_daftar_ulang_enabled', false);
                        $linkGrupDaftarUlangUrl = \App\Models\Setting::get('link_grup_daftar_ulang_url', '');
                    @endphp

                    @if($linkGrupDaftarUlangEnabled && !empty($linkGrupDaftarUlangUrl))
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-6 mb-6">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="mb-3">
                                    <svg class="w-12 h-12 text-white mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white mb-2">Segera Bergabung ke Grup Daftar Ulang!</h4>
                                <p class="text-sm text-emerald-50 mb-4 max-w-lg leading-relaxed">
                                    Bergabunglah ke grup WhatsApp khusus untuk calon siswa yang melakukan daftar ulang.
                                    Dapatkan informasi terbaru dan koordinasi langsung dengan panitia.
                                </p>
                                <a href="{{ $linkGrupDaftarUlangUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center bg-white text-emerald-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-emerald-50 transition transform hover:scale-105 shadow-xl">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                    </svg>
                                    Bergabung ke Grup WhatsApp
                                </a>
                                <p class="text-xs text-emerald-100 mt-3 italic">
                                    * Silakan bergabung sebelum melanjutkan proses daftar ulang
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($showPernyataan)
                    <!-- Surat Pernyataan -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
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
                            @if($suratPernyataan)
                            {{-- File Selected State --}}
                            <div class="border-2 border-solid border-green-400 bg-green-50 rounded-lg p-4" wire:loading.class="opacity-50" wire:target="suratPernyataan">
                                <div class="flex items-start space-x-4">
                                    {{-- PDF Icon --}}
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- File Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                                                    <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    File Siap Disimpan
                                                </p>
                                                <p class="text-sm text-gray-800 font-medium truncate mb-1">
                                                    {{ $suratPernyataan->getClientOriginalName() }}
                                                </p>
                                                <div class="flex items-center space-x-3 text-xs text-gray-600">
                                                    <span class="flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                        PDF
                                                    </span>
                                                    <span class="flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        {{ number_format($suratPernyataan->getSize() / 1024, 0) }} KB
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Change File Button --}}
                                        <div class="mt-3">
                                            <input type="file" wire:model="suratPernyataan" id="surat-pernyataan-change" class="hidden" accept=".pdf">
                                            <label for="surat-pernyataan-change" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                </svg>
                                                Ganti File
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Loading State --}}
                                <div wire:loading wire:target="suratPernyataan" class="mt-3 pt-3 border-t border-green-200">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memuat file...
                                    </div>
                                </div>
                            </div>
                            @else
                            {{-- Empty State --}}
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors" wire:loading.class="opacity-50" wire:target="suratPernyataan">
                                <input type="file" wire:model="suratPernyataan" id="surat-pernyataan" class="hidden" accept=".pdf">
                                <label for="surat-pernyataan" class="cursor-pointer block">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-gray-700">
                                        Klik untuk upload surat pernyataan
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">atau drag and drop file di sini</p>
                                    <p class="mt-2 text-xs text-gray-500">
                                        <span class="font-medium">Format:</span> PDF
                                        <span class="mx-2">•</span>
                                        <span class="font-medium">Ukuran Maks:</span> 2MB
                                    </p>
                                </label>
                                <div wire:loading wire:target="suratPernyataan" class="mt-3">
                                    <div class="flex items-center justify-center text-sm text-gray-600">
                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memuat file...
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($suratPernyataan)
                                <div class="mt-3 flex justify-end">
                                    <button
                                        wire:click="saveSuratPernyataan"
                                        wire:loading.attr="disabled"
                                        wire:target="saveSuratPernyataan"
                                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        <svg wire:loading.remove wire:target="saveSuratPernyataan" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <svg wire:loading wire:target="saveSuratPernyataan" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="saveSuratPernyataan">Simpan Surat Pernyataan</span>
                                        <span wire:loading wire:target="saveSuratPernyataan">Menyimpan...</span>
                                    </button>
                                </div>
                            @endif
                        @endif
                        @error('suratPernyataan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif

                    @if($daftarUlang && $daftarUlang->surat_pernyataan_path)
                    <!-- Form akan tampil setelah surat pernyataan diupload -->

                    <!-- SECTION 1: PILIH PAKET & NOMINAL -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-bold text-emerald-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Step 1: Pilih Paket Madrasah & Nominal
                        </h4>

                        @php
                            $madrasahPackages = \App\Models\Setting::get('daftar_ulang_madrasah_packages', []);
                            // Use computed property for Global OR Individual logic
                            $madrasahPackagesEnableOpsiLainnya = $this->shouldShowMadrasahOpsiLainnyaDropdown;
                        @endphp

                        <!-- 1. Pilih Paket Madrasah -->
                        @if($madrasahPackages && count($madrasahPackages) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Paket Madrasah <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="paketMadrasah"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Paket Madrasah --</option>
                                    @foreach($madrasahPackages as $package)
                                        <option value="{{ json_encode($package) }}">
                                            {{ $package['name'] ?? 'Paket' }}
                                            @if(!empty($package['description'])) - {{ $package['description'] }}@endif
                                        </option>
                                    @endforeach
                                    @if($madrasahPackagesEnableOpsiLainnya)
                                        <option value="paket_lainnya">Paket Lainnya (Input Manual)</option>
                                    @endif
                                </select>
                                @error('paketMadrasah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Manual Input Paket Lainnya --}}
                            @if($paketMadrasah === 'paket_lainnya')
                                <div class="mb-4 ml-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Paket Custom <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.live="paketMadrasahCustomName"
                                           placeholder="Contoh: Paket Khusus Beasiswa"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <p class="text-xs text-gray-600 mt-1">Masukkan nama paket yang tidak tersedia di daftar</p>
                                    @error('paketMadrasahCustomName')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- 2. Pilih Opsi dari Paket (Dependent Dropdown) -->
                            @if($paketMadrasah && $paketMadrasah !== 'paket_lainnya')
                                @php
                                    $selectedPackage = json_decode($paketMadrasah, true);
                                    $packageOptions = $selectedPackage['options'] ?? [];
                                @endphp

                                @if(count($packageOptions) > 0)
                                    <div class="mb-4 ml-4 p-4 bg-white border-l-4 border-emerald-500 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Opsi dari {{ $selectedPackage['name'] ?? 'Paket' }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="space-y-2">
                                            @foreach($packageOptions as $option)
                                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-emerald-50 transition-colors {{ $opsiMadrasah == json_encode($option) ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300' }}">
                                                    <input type="radio"
                                                           wire:model.live="opsiMadrasah"
                                                           value="{{ json_encode($option) }}"
                                                           class="text-emerald-600 focus:ring-emerald-500">
                                                    <div class="ml-3 flex-1 flex justify-between items-center">
                                                        <span class="font-medium text-gray-900">{{ $option['name'] ?? '-' }}</span>
                                                        <span class="font-bold text-emerald-700">Rp {{ number_format($option['amount'] ?? 0, 0, ',', '.') }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('opsiMadrasah')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <div class="mb-4 ml-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                                        <p class="text-sm text-yellow-800">Paket ini belum memiliki opsi. Silakan hubungi admin.</p>
                                    </div>
                                @endif
                            @endif

                            {{-- Manual Input Nominal untuk Paket Lainnya --}}
                            @if($paketMadrasah === 'paket_lainnya')
                                <div class="mb-4 ml-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nominal Biaya (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="paketMadrasahCustomAmount"
                                           placeholder="Contoh: 500000"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <p class="text-xs text-gray-600 mt-1">Masukkan nominal biaya untuk paket custom</p>
                                    @error('paketMadrasahCustomAmount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- 3. Konfirmasi Nominal yang Dibayarkan -->
                            @if($opsiMadrasah || ($paketMadrasah === 'paket_lainnya' && $paketMadrasahCustomAmount))
                                @php
                                    // Check if this is paket_lainnya (manual input)
                                    if ($paketMadrasah === 'paket_lainnya') {
                                        // Build selectedOption from custom fields
                                        $selectedOption = [
                                            'name' => $paketMadrasahCustomName ?? 'Paket Custom',
                                            'amount' => $paketMadrasahCustomAmount ?? 0
                                        ];
                                    } else {
                                        // Normal package: decode from opsiMadrasah
                                        $selectedOption = json_decode($opsiMadrasah, true);
                                    }
                                    // Use computed property for Global OR Individual logic
                                    $madrasahEnableOpsiLainnyaPayment = $this->shouldShowMadrasahOpsiLainnyaKonfirmasi;
                                @endphp

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Nominal yang Dibayarkan <span class="text-red-500">*</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mb-2">Pilih nominal yang akan dibayarkan (default: sesuai opsi yang dipilih)</p>

                                    <div class="space-y-2">
                                        <!-- Option: Bayar Penuh (Auto from selected option) -->
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition-colors {{ $konfirmasiNominalBiaya == json_encode($selectedOption) ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalBiaya"
                                                   value="{{ json_encode($selectedOption) }}"
                                                   class="text-green-600 focus:ring-green-500">
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <div>
                                                    <span class="font-medium text-gray-900">Bayar Penuh</span>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $selectedOption['name'] ?? '' }}</p>
                                                </div>
                                                <span class="font-bold text-green-700">Rp {{ number_format($selectedOption['amount'] ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </label>

                                        <!-- Option: Opsi Lainnya (Manual Input) -->
                                        @if($madrasahEnableOpsiLainnyaPayment)
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-yellow-50 transition-colors {{ $konfirmasiNominalBiaya == 'opsi_lainnya' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                                <input type="radio"
                                                       wire:model.live="konfirmasiNominalBiaya"
                                                       value="opsi_lainnya"
                                                       class="text-yellow-600 focus:ring-yellow-500">
                                                <div class="ml-3 flex-1">
                                                    <span class="font-medium text-gray-900">Opsi Lainnya (Bayar Tidak Penuh)</span>
                                                    <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual (untuk angsuran/potongan)</p>
                                                </div>
                                            </label>

                                            @if($konfirmasiNominalBiaya == 'opsi_lainnya')
                                                <div class="ml-6 mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                    <input type="number"
                                                           wire:model.live="konfirmasiNominalBiayaOpsiLainnya"
                                                           placeholder="Contoh: 500000"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
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
                    </div>
                    {{-- End of Step 1 container --}}

                    <!-- SECTION 2: INFORMASI PEMBAYARAN & UPLOAD BUKTI -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-200 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-bold text-emerald-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Step 2: Informasi Pembayaran & Upload Bukti
                        </h4>

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

                        <!-- Nama Pengirim -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="namaPengirim"
                                   placeholder="Masukkan nama pengirim sesuai bukti transfer"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Nama pengirim harus sesuai dengan yang tertera pada bukti transfer</p>
                            @error('namaPengirim')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-emerald-300 mb-4"></div>

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

                            @if($daftarUlang && $daftarUlang->bukti_pembayaran_madrasah && !in_array($daftarUlang->status, ['perbaiki', 'ditolak']))
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
                                @if($buktiPembayaran)
                                {{-- File Selected State --}}
                                <div class="border-2 border-solid border-green-400 bg-green-50 rounded-lg p-4" wire:loading.class="opacity-50" wire:target="buktiPembayaran">
                                    <div class="flex items-start space-x-4">
                                        {{-- File Icon (Dynamic based on type) --}}
                                        <div class="flex-shrink-0">
                                            @php
                                                $extension = strtolower($buktiPembayaran->getClientOriginalExtension());
                                                $isPdf = $extension === 'pdf';
                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                            @endphp
                                            <div class="w-16 h-16 {{ $isPdf ? 'bg-red-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center">
                                                @if($isPdf)
                                                    {{-- PDF Icon --}}
                                                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    {{-- Image Icon --}}
                                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- File Info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                                                        <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        File Siap Disimpan
                                                    </p>
                                                    <p class="text-sm text-gray-800 font-medium truncate mb-1">
                                                        {{ $buktiPembayaran->getClientOriginalName() }}
                                                    </p>
                                                    <div class="flex items-center space-x-3 text-xs text-gray-600">
                                                        <span class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                            </svg>
                                                            {{ strtoupper($extension) }}
                                                        </span>
                                                        <span class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            {{ number_format($buktiPembayaran->getSize() / 1024, 0) }} KB
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Change File Button --}}
                                            <div class="mt-3">
                                                <input type="file" wire:model="buktiPembayaran" id="bukti-madrasah-change" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                <label for="bukti-madrasah-change" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 cursor-pointer transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    Ganti File
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Loading State --}}
                                    <div wire:loading wire:target="buktiPembayaran" class="mt-3 pt-3 border-t border-green-200">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memuat file...
                                        </div>
                                    </div>
                                </div>
                                @else
                                {{-- Empty State --}}
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors" wire:loading.class="opacity-50" wire:target="buktiPembayaran">
                                    <input type="file" wire:model="buktiPembayaran" id="bukti-madrasah" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="bukti-madrasah" class="cursor-pointer block">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mt-2 text-sm font-medium text-gray-700">
                                            Klik untuk upload bukti pembayaran
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">atau drag and drop file di sini</p>
                                        <p class="mt-2 text-xs text-gray-500">
                                            <span class="font-medium">Format:</span> PDF, JPG, PNG
                                            <span class="mx-2">•</span>
                                            <span class="font-medium">Ukuran Maks:</span> 2MB
                                        </p>
                                    </label>
                                    <div wire:loading wire:target="buktiPembayaran" class="mt-3">
                                        <div class="flex items-center justify-center text-sm text-gray-600">
                                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memuat file...
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endif
                            @error('buktiPembayaran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

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

                    @else
                    <!-- Pesan jika belum upload surat pernyataan -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-yellow-900 mb-2">
                                    📋 Upload Surat Pernyataan Terlebih Dahulu
                                </h3>
                                <p class="text-sm text-yellow-800 mb-3">
                                    Untuk melanjutkan proses daftar ulang madrasah, Anda harus menyelesaikan langkah berikut:
                                </p>
                                <ol class="list-decimal list-inside space-y-2 text-sm text-yellow-800 mb-4">
                                    <li>Download template Surat Pernyataan dari bagian di atas</li>
                                    <li>Isi dan tandatangani surat pernyataan tersebut</li>
                                    <li>Scan atau foto surat dalam format PDF</li>
                                    <li>Upload surat pernyataan menggunakan tombol "Upload"</li>
                                    <li>Klik tombol <strong>"Simpan Surat Pernyataan"</strong> untuk menyimpan</li>
                                </ol>
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 rounded p-3">
                                    <p class="text-xs text-yellow-800 flex items-start">
                                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>
                                            Setelah surat pernyataan berhasil disimpan, form untuk memilih nominal biaya dan upload bukti pembayaran akan muncul di bawah ini.
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif {{-- End of @if(!$isMadrasahPaymentOpen) --}}
                    @endif {{-- End of @if($showPembayaranMadrasah) --}}
                </div>
                @endif {{-- End of @else (form untuk mengisi data) --}}
                @endif {{-- End of @if($daftarUlang && in_array($daftarUlang->status, ['menunggu', 'disetujui'])) - Progressive Disclosure --}}

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

                    @if(!$isSeragamPaymentOpen)
                        <!-- Pesan jika di luar waktu pembayaran seragam -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-red-900 mb-2">
                                        🕐 Di Luar Waktu Pembayaran Daftar Ulang Seragam
                                    </h3>
                                    <p class="text-sm text-red-800 mb-3">
                                        Saat ini Anda tidak dapat mengisi form daftar ulang seragam karena berada di luar waktu yang telah ditentukan.
                                    </p>
                                    @if($seragamPaymentStart && $seragamPaymentEnd)
                                        <div class="bg-red-100 border-l-4 border-red-500 rounded p-3">
                                            <p class="text-xs text-red-800 font-semibold mb-2">Jadwal Pembayaran Daftar Ulang Seragam:</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-red-700">
                                                <div>
                                                    <span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($seragamPaymentStart)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                                <div>
                                                    <span class="font-medium">Berakhir:</span> {{ \Carbon\Carbon::parse($seragamPaymentEnd)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex items-start p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-xs text-yellow-800">
                                            Silakan kembali ke halaman ini pada waktu yang telah ditentukan untuk melanjutkan proses daftar ulang seragam.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
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
                        $showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
                    @endphp

                    @if($this->seragamInfo)
                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 border border-cyan-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-cyan-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-cyan-900 prose prose-sm prose-cyan max-w-none">
                                    {!! $this->seragamInfo !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Daftar Ulang Seragam</h4>
                    </div>

                    {{-- PHP Logic for Warning Perbedaan Ukuran --}}
                    @php
                        // Get ukuran data from seragam table
                        $actualUkuranBajuHarian = $seragam->ukuran_baju_harian ?? '';
                        $actualUkuranCelanaHarian = $seragam->ukuran_celana_rok_harian ?? '';

                        // Check if there's a difference between baju harian and celana harian
                        $hasSizeDifference = false;
                        $largerSize = '';
                        $recommendedSize = '';  // Size sesuai mode perhitungan

                        if ($actualUkuranBajuHarian && $actualUkuranCelanaHarian && $actualUkuranBajuHarian != $actualUkuranCelanaHarian) {
                            $hasSizeDifference = true;

                            // Determine which size is larger (simple comparison based on common sizes)
                            $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                            $bajuIndex = array_search($actualUkuranBajuHarian, $sizeOrder);
                            $celanaIndex = array_search($actualUkuranCelanaHarian, $sizeOrder);

                            if ($bajuIndex !== false && $celanaIndex !== false) {
                                $largerSize = $bajuIndex > $celanaIndex ? $actualUkuranBajuHarian : $actualUkuranCelanaHarian;
                            } else {
                                // If not standard sizes, just mention both
                                $largerSize = 'ukuran terbesar antara ' . $actualUkuranBajuHarian . ' dan ' . $actualUkuranCelanaHarian;
                            }

                            // Set recommendedSize based on mode
                            if ($ukuranAlertPerhitunganMode === 'baju_harian') {
                                $recommendedSize = $actualUkuranBajuHarian;
                            } else {
                                $recommendedSize = $largerSize;
                            }
                        }
                    @endphp

                    {{-- Warning Perbedaan Ukuran - Shows ONLY if enabled AND there's a size difference --}}
                    @if($ukuranAlertPerbedaanEnabled && $hasSizeDifference)
                    <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-5 shadow-md mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-base font-bold text-orange-900 mb-2">
                                    ⚠️ Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!
                                </h3>
                                <div class="text-sm text-orange-800 space-y-2">
                                    <p class="font-semibold">
                                        Anda memilih ukuran yang berbeda untuk baju seragam harian (<span class="font-bold text-orange-900">{{ $actualUkuranBajuHarian }}</span>)
                                        dan celana/rok harian (<span class="font-bold text-orange-900">{{ $actualUkuranCelanaHarian }}</span>).
                                    </p>
                                    <div class="bg-orange-100 border border-orange-300 rounded-md p-3 mt-3">
                                        <p class="font-semibold text-orange-900 mb-2">📌 Informasi Penting untuk Pembayaran:</p>
                                        <p class="leading-relaxed">
                                            Saat melakukan pembayaran daftar ulang seragam, <span class="font-bold underline">Anda diharuskan memilih dan membayar sesuai dengan
                                            @if($ukuranAlertPerhitunganMode === 'baju_harian')
                                                ukuran baju seragam harian/atasan
                                            @else
                                                ukuran terbesar
                                            @endif
                                            </span> yang telah Anda pilih.
                                        </p>
                                        <p class="mt-2 font-bold text-orange-900">
                                            💰 Ukuran untuk pembayaran: <span class="text-lg">{{ $recommendedSize }}</span>
                                        </p>
                                    </div>
                                    <p class="text-xs mt-2 italic">
                                        Pastikan Anda memilih paket seragam dan nominal pembayaran yang sesuai dengan ukuran
                                        @if($ukuranAlertPerhitunganMode === 'baju_harian')
                                            baju seragam harian/atasan
                                        @else
                                            terbesar
                                        @endif
                                        tersebut pada saat mengisi form pembayaran di bawah ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($showPembayaranSeragam)

                    {{-- Check if status is 'menunggu' or 'disetujui' - show status display only --}}
                    @if($seragam && in_array($seragam->status_seragam, ['menunggu', 'disetujui']))

                    <!-- Status Display untuk Menunggu dan Disetujui -->
                    <div class="space-y-4">
                        @if($seragam->status_seragam === 'menunggu')
                        <!-- Status Menunggu -->
                        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-8 h-8 text-yellow-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-yellow-900 mb-2">
                                        ⏳ Status: Menunggu Persetujuan
                                    </h3>
                                    <p class="text-base text-yellow-800 mb-4">
                                        Data daftar ulang seragam Anda telah berhasil dikirim dan sedang dalam proses verifikasi oleh panitia.
                                    </p>
                                    <div class="bg-yellow-100 border-l-4 border-yellow-500 rounded p-4">
                                        <p class="text-sm text-yellow-800 font-medium mb-2">📌 Informasi:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-yellow-700">
                                            <li>Bukti pembayaran seragam Anda sedang diverifikasi</li>
                                            <li>Proses verifikasi biasanya memakan waktu 1-3 hari kerja</li>
                                            <li>Anda akan mendapat notifikasi jika data disetujui atau perlu perbaikan</li>
                                            <li>Pantau terus status di halaman ini</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($seragam->status_seragam === 'disetujui')
                        <!-- Status Disetujui -->
                        <div class="bg-green-50 border-2 border-green-400 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-8 h-8 text-green-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-green-900 mb-2">
                                        ✅ Status: Disetujui
                                    </h3>
                                    <p class="text-base text-green-800 mb-4">
                                        Selamat! Data daftar ulang seragam Anda telah disetujui oleh panitia.
                                    </p>
                                    <div class="bg-green-100 border-l-4 border-green-500 rounded p-4 mb-4">
                                        <p class="text-sm text-green-800 font-medium mb-2">✓ Data Telah Diverifikasi:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-green-700">
                                            <li>Bukti pembayaran seragam telah diverifikasi</li>
                                            <li>Data nominal pembayaran sesuai</li>
                                            <li>Proses daftar ulang seragam selesai</li>
                                            <li>Anda dapat mengunduh invoice pembayaran</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tombol Lihat Bukti Pembayaran -->
                            @if($seragam->bukti_pembayaran)
                            <a href="{{ route('pendaftar.daftarulang.view-file', ['type' => 'seragam', 'file' => basename($seragam->bukti_pembayaran)]) }}"
                               target="_blank"
                               class="flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Lihat Bukti Pembayaran</span>
                            </a>
                            @endif

                            <!-- Tombol Download Invoice (hanya muncul jika disetujui) -->
                            @if($seragam->status_seragam === 'disetujui')
                            <button wire:click="downloadInvoiceSeragam"
                                    class="flex items-center justify-center px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Download Invoice Seragam</span>
                            </button>
                            @endif
                        </div>

                        <!-- Ringkasan Data yang Telah Dikirim -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Data yang Telah Dikirim
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                @php
                                    $paketSeragamData = is_string($seragam->paket_seragam) ? json_decode($seragam->paket_seragam, true) : $seragam->paket_seragam;
                                    $opsiSeragamData = is_string($seragam->opsi_seragam) ? json_decode($seragam->opsi_seragam, true) : $seragam->opsi_seragam;
                                    $nominalSeragamData = is_string($seragam->nominal_seragam) ? json_decode($seragam->nominal_seragam, true) : $seragam->nominal_seragam;
                                @endphp

                                @if($paketSeragamData)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Paket Seragam:</p>
                                    <p class="font-semibold text-gray-900">{{ $paketSeragamData['name'] ?? '-' }}</p>
                                </div>
                                @endif

                                @if($opsiSeragamData)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Opsi Paket:</p>
                                    @php
                                        // Deteksi apakah multi-select (array of options) atau single option
                                        $isMultiSelectSeragam = is_array($opsiSeragamData) && isset($opsiSeragamData[0]) && is_array($opsiSeragamData[0]);
                                    @endphp

                                    @if($isMultiSelectSeragam)
                                        {{-- Multi-select: tampilkan semua opsi yang dipilih --}}
                                        <div class="space-y-2">
                                            @foreach($opsiSeragamData as $index => $opsi)
                                                <div class="flex justify-between items-center {{ $index > 0 ? 'pt-2 border-t border-gray-200' : '' }}">
                                                    <span class="font-semibold text-gray-900">
                                                        {{ $index + 1 }}. {{ $opsi['name'] ?? '-' }}
                                                    </span>
                                                    @if(isset($opsi['amount']))
                                                        <span class="text-sm text-purple-700 font-medium">
                                                            Rp {{ number_format($opsi['amount'], 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                            <div class="pt-2 border-t-2 border-purple-200 flex justify-between items-center">
                                                <span class="font-bold text-gray-900">Total:</span>
                                                <span class="font-bold text-purple-700">
                                                    Rp {{ number_format(array_sum(array_column($opsiSeragamData, 'amount')), 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Single option: tampilkan seperti biasa --}}
                                        <p class="font-semibold text-gray-900">
                                            {{ $opsiSeragamData['name'] ?? '-' }}
                                            @if(isset($opsiSeragamData['amount']))
                                                <span class="text-xs text-gray-500">(Rp {{ number_format($opsiSeragamData['amount'], 0, ',', '.') }})</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                @endif

                                @if($nominalSeragamData)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Nominal yang Dibayarkan:</p>
                                    <p class="font-semibold text-gray-900">
                                        Rp {{ number_format($nominalSeragamData['amount'] ?? 0, 0, ',', '.') }}
                                        @if(isset($nominalSeragamData['name']) && $nominalSeragamData['name'] !== ($opsiSeragamData['name'] ?? ''))
                                            <span class="text-xs text-gray-500">({{ $nominalSeragamData['name'] }})</span>
                                        @endif
                                    </p>
                                </div>
                                @endif

                                @if($seragam->opsi_pembayaran_seragam)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Opsi Pembayaran:</p>
                                    <p class="font-semibold text-gray-900 capitalize">{{ $seragam->opsi_pembayaran_seragam }}</p>
                                </div>
                                @endif

                                @if($seragam->bank_seragam)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Bank/Metode Pembayaran:</p>
                                    <p class="font-semibold text-gray-900">{{ $seragam->bank_seragam }}</p>
                                </div>
                                @endif

                                @if($seragam->nama_pengirim_seragam)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Nama Pengirim:</p>
                                    <p class="font-semibold text-gray-900">{{ $seragam->nama_pengirim_seragam }}</p>
                                </div>
                                @endif

                                @if($seragam->waktu_pembayaran_seragam)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-gray-600 mb-1">Waktu Pembayaran:</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($seragam->waktu_pembayaran_seragam)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- Show form if status is NOT menunggu or disetujui (belum isi, ditolak, atau perbaiki) --}}

                    <!-- SECTION 1: PILIH PAKET & NOMINAL -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Step 1: Pilih Paket Seragam & Nominal
                        </h4>

                        @php
                            $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
                            // Use computed property for Global OR Individual logic
                            $seragamPackagesEnableOpsiLainnya = $this->shouldShowSeragamOpsiLainnyaDropdown;
                        @endphp

                        <!-- 1. Pilih Paket Seragam -->
                        @if($seragamPackages && count($seragamPackages) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Paket Seragam <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="paketSeragam"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Paket Seragam --</option>
                                    @foreach($seragamPackages as $package)
                                        <option value="{{ json_encode($package) }}">
                                            {{ $package['name'] ?? 'Paket' }}
                                            @if(!empty($package['description'])) - {{ $package['description'] }}@endif
                                        </option>
                                    @endforeach
                                    @if($seragamPackagesEnableOpsiLainnya && !$isSeragamApproved)
                                        <option value="paket_lainnya">Paket Lainnya (Input Manual)</option>
                                    @endif
                                </select>
                                @error('paketSeragam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Manual Input Paket Lainnya --}}
                            @if($paketSeragam === 'paket_lainnya')
                                <div class="mb-4 ml-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Paket Custom <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.live="paketSeragamCustomName"
                                           placeholder="Contoh: Paket Khusus Beasiswa"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                           {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <p class="text-xs text-gray-600 mt-1">Masukkan nama paket yang tidak tersedia di daftar</p>
                                    @error('paketSeragamCustomName')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">
                                        Nominal Biaya (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="paketSeragamCustomAmount"
                                           placeholder="Contoh: 1000000"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                           {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <p class="text-xs text-gray-600 mt-1">Masukkan nominal biaya untuk paket custom</p>
                                    @error('paketSeragamCustomAmount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <!-- 2. Pilih Opsi dari Paket (Dependent Dropdown) -->
                            @if($paketSeragam && $paketSeragam !== 'paket_lainnya')
                                @php
                                    $selectedPackage = json_decode($paketSeragam, true);
                                    $packageOptions = $selectedPackage['options'] ?? [];
                                    $selectionMode = $selectedPackage['selection_mode'] ?? 'radio'; // Default to radio for backward compatibility
                                @endphp

                                @if(count($packageOptions) > 0)
                                    <div class="mb-4 ml-4 p-4 bg-white border-l-4 border-blue-500 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Opsi dari {{ $selectedPackage['name'] ?? 'Paket' }} <span class="text-red-500">*</span>
                                            @if($selectionMode === 'checkbox')
                                                <span class="text-xs font-normal text-purple-600 ml-2">(Bisa pilih lebih dari 1 opsi)</span>
                                            @endif
                                        </label>

                                        <!-- Info Box for Checkbox Mode -->
                                        @if($selectionMode === 'checkbox')
                                            <div class="mb-3 p-3 bg-purple-50 border border-purple-200 rounded-lg flex items-start">
                                                <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div class="text-xs text-purple-800">
                                                    <strong>Mode Multi Select:</strong> Anda dapat memilih beberapa opsi sekaligus. Nominal akan dijumlahkan otomatis pada konfirmasi pembayaran.
                                                </div>
                                            </div>
                                        @endif

                                        <div class="space-y-2">
                                            @if($selectionMode === 'radio')
                                                {{-- RADIO MODE: Single Select --}}
                                                @foreach($packageOptions as $option)
                                                    <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-blue-50' }} transition-colors {{ $opsiSeragam == json_encode($option) ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                                        <input type="radio"
                                                               wire:model.live="opsiSeragam"
                                                               value="{{ json_encode($option) }}"
                                                               class="text-blue-600 focus:ring-blue-500"
                                                               {{ $isSeragamApproved ? 'disabled' : '' }}>
                                                        <div class="ml-3 flex-1 flex justify-between items-center">
                                                            <span class="font-medium text-gray-900">{{ $option['name'] ?? '-' }}</span>
                                                            <span class="font-bold text-blue-700">Rp {{ number_format($option['amount'] ?? 0, 0, ',', '.') }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            @else
                                                {{-- CHECKBOX MODE: Multi Select with Alpine.js --}}
                                                <div x-data="{
                                                    selectedOptions: @js($opsiSeragam && is_array($opsiSeragam) ? $opsiSeragam : []),
                                                    packageOptions: @js($packageOptions),
                                                    toggleOption(optionIndex) {
                                                        // Get option from packageOptions array
                                                        const option = this.packageOptions[optionIndex];
                                                        if (!option) return;

                                                        // Find by ID
                                                        const index = this.selectedOptions.findIndex(item => item.id === option.id);
                                                        if (index > -1) {
                                                            // Remove if already selected
                                                            this.selectedOptions.splice(index, 1);
                                                        } else {
                                                            // Add if not selected
                                                            this.selectedOptions.push(option);
                                                        }
                                                        // Sync to Livewire immediately (Alpine handles reactivity)
                                                        @this.set('opsiSeragam', this.selectedOptions);
                                                    },
                                                    isSelected(optionId) {
                                                        return this.selectedOptions.some(item => item.id === optionId);
                                                    }
                                                }"
                                                     wire:key="checkbox-{{ json_encode($paketSeragam) }}"
                                                >
                                                    @foreach($packageOptions as $optionIndex => $option)
                                                        <label class="flex items-center p-3 border rounded-lg transition-colors {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-purple-50' }}"
                                                               :class="{
                                                                   'border-purple-500 bg-purple-50': isSelected('{{ $option['id'] ?? $optionIndex }}'),
                                                                   'border-gray-300': !isSelected('{{ $option['id'] ?? $optionIndex }}')
                                                               }">
                                                            <input type="checkbox"
                                                                   :checked="isSelected('{{ $option['id'] ?? $optionIndex }}')"
                                                                   @change="toggleOption({{ $optionIndex }})"
                                                                   class="text-purple-600 focus:ring-purple-500 rounded"
                                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                                <span class="font-medium text-gray-900">{{ $option['name'] ?? '-' }}</span>
                                                                <span class="font-bold text-purple-700">Rp {{ number_format($option['amount'] ?? 0, 0, ',', '.') }}</span>
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        @error('opsiSeragam')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <div class="mb-4 ml-4 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                                        <p class="text-sm text-yellow-800">Paket ini belum memiliki opsi. Silakan hubungi admin.</p>
                                    </div>
                                @endif
                            @endif

                            <!-- 3. Konfirmasi Nominal yang Dibayarkan -->
                            @if($opsiSeragam || ($paketSeragam === 'paket_lainnya' && $paketSeragamCustomAmount))
                                @php
                                    // Check if this is paket_lainnya (manual input)
                                    if ($paketSeragam === 'paket_lainnya') {
                                        // Build selectedOption from custom fields
                                        $selectedOption = [
                                            'name' => $paketSeragamCustomName ?? 'Paket Custom',
                                            'amount' => $paketSeragamCustomAmount ?? 0
                                        ];
                                        $isMultiSelect = false;
                                        $totalAmount = $selectedOption['amount'];
                                        $selectedNames = [$selectedOption['name']];
                                    } else {
                                        // Normal package: Deteksi apakah opsiSeragam adalah array (checkbox mode) atau single object (radio mode)
                                        // Jika checkbox mode, Livewire wire:model sudah mengirim sebagai array PHP
                                        // Jika radio mode, Livewire wire:model mengirim sebagai JSON string
                                        if (is_array($opsiSeragam)) {
                                            // Sudah array dari checkbox mode
                                            $selectedOption = $opsiSeragam;
                                        } else {
                                            // Masih string JSON dari radio mode
                                            $selectedOption = json_decode($opsiSeragam, true);
                                        }

                                        $isMultiSelect = is_array($selectedOption) && isset($selectedOption[0]);

                                        // Hitung total amount jika multi-select
                                        if ($isMultiSelect) {
                                            $totalAmount = 0;
                                            $selectedNames = [];
                                            foreach ($selectedOption as $opt) {
                                                $totalAmount += $opt['amount'] ?? 0;
                                                $selectedNames[] = $opt['name'] ?? '';
                                            }
                                        } else {
                                            $totalAmount = $selectedOption['amount'] ?? 0;
                                            $selectedNames = [$selectedOption['name'] ?? ''];
                                        }
                                    }

                                    // Use computed property for Global OR Individual logic
                                    $seragamEnableOpsiLainnya = $this->shouldShowSeragamOpsiLainnyaKonfirmasi;
                                @endphp

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Nominal yang Dibayarkan <span class="text-red-500">*</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mb-2">
                                        @if($isMultiSelect)
                                            Total nominal dari semua opsi yang dipilih
                                        @else
                                            Pilih nominal yang akan dibayarkan (default: sesuai opsi yang dipilih)
                                        @endif
                                    </p>

                                    <div class="space-y-2">
                                        <!-- Option: Bayar Penuh (Auto from selected option) -->
                                        @php
                                            // Untuk konfirmasi nominal, struktur berbeda untuk multi-select
                                            if ($isMultiSelect) {
                                                $konfirmasiValue = [
                                                    'name' => 'Bayar Penuh - Multi Select (' . count($selectedOption) . ' item)',
                                                    'amount' => $totalAmount,
                                                    'items' => $selectedOption
                                                ];
                                            } else {
                                                $konfirmasiValue = $selectedOption;
                                            }
                                            $konfirmasiValueJson = json_encode($konfirmasiValue);
                                        @endphp
                                        <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-green-50' }} transition-colors border-gray-300">
                                            <input type="radio"
                                                   wire:model.live="konfirmasiNominalSeragam"
                                                   value="{{ $konfirmasiValueJson }}"
                                                   class="text-green-600 focus:ring-green-500"
                                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                                            <div class="ml-3 flex-1 flex justify-between items-center">
                                                <div>
                                                    <span class="font-medium text-gray-900">Bayar Penuh</span>
                                                    @if($isMultiSelect)
                                                        <div class="text-xs text-gray-600 mt-1">
                                                            <strong>Rincian {{ count($selectedOption) }} opsi:</strong>
                                                            @foreach($selectedOption as $idx => $opt)
                                                                <div class="ml-2">• {{ $opt['name'] ?? '' }} - Rp {{ number_format($opt['amount'] ?? 0, 0, ',', '.') }}</div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-600 mt-1">{{ $selectedOption['name'] ?? '' }}</p>
                                                    @endif
                                                </div>
                                                <span class="font-bold text-green-700">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                            </div>
                                        </label>

                                        <!-- Option: Opsi Lainnya (Manual Input) -->
                                        @if($seragamEnableOpsiLainnya)
                                            <label class="flex items-center p-3 border rounded-lg {{ $isSeragamApproved ? 'cursor-not-allowed opacity-60' : 'cursor-pointer hover:bg-yellow-50' }} transition-colors {{ $konfirmasiNominalSeragam == 'opsi_lainnya' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-300' }}">
                                                <input type="radio"
                                                       wire:model.live="konfirmasiNominalSeragam"
                                                       value="opsi_lainnya"
                                                       class="text-yellow-600 focus:ring-yellow-500"
                                                       {{ $isSeragamApproved ? 'disabled' : '' }}>
                                                <div class="ml-3 flex-1">
                                                    <span class="font-medium text-gray-900">Opsi Lainnya (Bayar Tidak Penuh)</span>
                                                    <p class="text-xs text-gray-500 mt-1">Isi nominal secara manual (untuk angsuran/potongan)</p>
                                                </div>
                                            </label>

                                            @if($konfirmasiNominalSeragam == 'opsi_lainnya')
                                                <div class="ml-6 mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Nominal (Rp)</label>
                                                    <input type="number"
                                                           wire:model.live="konfirmasiNominalSeragamOpsiLainnya"
                                                           placeholder="Contoh: 750000"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200"
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
                        @else
                            <div class="p-4 bg-gray-50 border border-gray-300 rounded-lg text-center">
                                <p class="text-sm text-gray-600">Belum ada paket seragam yang tersedia. Silakan hubungi admin.</p>
                            </div>
                        @endif
                    </div>

                    <!-- SECTION BREAK -->
                    <div class="my-6 border-t-4 border-dashed border-gray-300"></div>

                    <!-- SECTION 2: PEMBAYARAN & UPLOAD -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-6">
                        <h4 class="text-lg font-bold text-green-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Step 2: Informasi Pembayaran & Upload Bukti
                        </h4>

                        <!-- 4. Opsi Pembayaran -->
                        @php
                            $seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
                            $availableSeragamOptions = array_filter($seragamPaymentOptions);
                        @endphp

                        @if(!empty($availableSeragamOptions))
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi Pembayaran <span class="text-red-500">*</span></label>
                                <select wire:model="opsiPembayaranSeragam"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Opsi --</option>
                                    @foreach($availableSeragamOptions as $option => $enabled)
                                        @if($enabled)
                                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('opsiPembayaranSeragam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- 5. Bank/Metode Pembayaran -->
                        @php
                            $seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
                        @endphp

                        @if($seragamBanks && count($seragamBanks) > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Daftar Bank/Metode Pembayaran <span class="text-red-500">*</span></label>
                                <select wire:model="bankSeragam"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                                        {{ $isSeragamApproved ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
                                    @foreach($seragamBanks as $bank)
                                        <option value="{{ $bank['name'] ?? '' }}">
                                            {{ $bank['name'] ?? 'Bank' }}@if(!empty($bank['account_number'])) - {{ $bank['account_number'] }}@endif@if(!empty($bank['account_name'])) ({{ $bank['account_name'] }})@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('bankSeragam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- 6. Nama Pengirim -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-600 mb-2">Nama orang yang mengirimkan pembayaran (sesuai bukti transfer)</p>
                            <input type="text"
                                   wire:model="namaPengirimSeragam"
                                   placeholder="Contoh: Ahmad Zulkifli"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 {{ $isSeragamApproved ? 'opacity-60 cursor-not-allowed' : '' }}"
                                   {{ $isSeragamApproved ? 'disabled' : '' }}>
                            @error('namaPengirimSeragam')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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

                                @if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran && !in_array($pendaftar->seragam->status_seragam, ['perbaiki', 'ditolak']))
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
                                        <div class="{{ $isSeragamApproved ? 'pointer-events-none opacity-60' : '' }}">
                                            @if($buktiSeragam)
                                            {{-- File Selected State --}}
                                            <div class="border-2 border-solid border-green-400 bg-green-50 rounded-lg p-4" wire:loading.class="opacity-50" wire:target="buktiSeragam">
                                                <div class="flex items-start space-x-4">
                                                    {{-- File Icon --}}
                                                    <div class="flex-shrink-0">
                                                        <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                                                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    {{-- File Info --}}
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-semibold text-gray-900 mb-1 flex items-center">
                                                                    <svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    File Siap Disimpan
                                                                </p>
                                                                <p class="text-sm text-gray-800 font-medium truncate mb-1">
                                                                    {{ $buktiSeragam->getClientOriginalName() }}
                                                                </p>
                                                                <div class="flex items-center space-x-3 text-xs text-gray-600">
                                                                    <span class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                        </svg>
                                                                        {{ strtoupper(pathinfo($buktiSeragam->getClientOriginalName(), PATHINFO_EXTENSION)) }}
                                                                    </span>
                                                                    <span class="flex items-center">
                                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                        </svg>
                                                                        {{ number_format($buktiSeragam->getSize() / 1024, 0) }} KB
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Change File Button --}}
                                                        <div class="mt-3">
                                                            <input type="file" wire:model="buktiSeragam" id="bukti-seragam-change" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                            <label for="bukti-seragam-change" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer transition-colors">
                                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                                </svg>
                                                                Ganti File
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Loading State --}}
                                                <div wire:loading wire:target="buktiSeragam" class="mt-3 pt-3 border-t border-green-200">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Memuat file...
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            {{-- Empty State --}}
                                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors" wire:loading.class="opacity-50" wire:target="buktiSeragam">
                                                <input type="file" wire:model="buktiSeragam" id="bukti-seragam" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                <label for="bukti-seragam" class="cursor-pointer block">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <p class="mt-2 text-sm font-medium text-gray-700">
                                                        Klik untuk upload bukti pembayaran
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-500">atau drag and drop file di sini</p>
                                                    <p class="mt-2 text-xs text-gray-500">PDF, JPG, PNG (Max. 5MB)</p>
                                                </label>

                                                {{-- Loading State --}}
                                                <div wire:loading wire:target="buktiSeragam" class="mt-3">
                                                    <div class="inline-flex items-center text-sm text-gray-600">
                                                        <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Memuat file...
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @error('buktiSeragam') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button
                            wire:click="saveSeragam"
                            wire:loading.attr="disabled"
                            wire:target="saveSeragam"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg wire:loading.remove wire:target="saveSeragam" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveSeragam" class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveSeragam">Simpan Data Seragam</span>
                            <span wire:loading wire:target="saveSeragam">Menyimpan...</span>
                        </button>
                    </div>
                    @endif {{-- End of @if($requireSeragamPaymentProof) --}}

                    @endif {{-- End of @else (show form for ditolak/perbaiki/belum isi) --}}
                    @endif {{-- End of @if($seragam && in_array($seragam->status_seragam, ['menunggu', 'disetujui'])) --}}

                </div>
                @endif {{-- End of @if($showPembayaranSeragam) --}}

                <!-- Ukuran Tab -->
                @if($activeTab === 'ukuran')
                <div class="p-6 space-y-6">
                    @php
                        // Check if ukuran is approved - disable all fields if true
                        $isUkuranApproved = $seragam && $seragam->status_ukuran === 'disetujui';
                        // Check if ukuran is waiting for approval - hide form completely
                        $isUkuranWaiting = $seragam && $seragam->status_ukuran === 'menunggu';
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

                    @if(!$isSeragamPaymentOpen)
                        <!-- Pesan jika di luar waktu pembayaran seragam -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-red-900 mb-2">
                                        🕐 Di Luar Waktu Pengisian Ukuran Seragam
                                    </h3>
                                    <p class="text-sm text-red-800 mb-3">
                                        Saat ini Anda tidak dapat mengisi form ukuran seragam karena berada di luar waktu yang telah ditentukan.
                                    </p>
                                    @if($seragamPaymentStart && $seragamPaymentEnd)
                                        <div class="bg-red-100 border-l-4 border-red-500 rounded p-3">
                                            <p class="text-xs text-red-800 font-semibold mb-2">Jadwal Pengisian Ukuran Seragam (Sama dengan Pembayaran Seragam):</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-red-700">
                                                <div>
                                                    <span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($seragamPaymentStart)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                                <div>
                                                    <span class="font-medium">Berakhir:</span> {{ \Carbon\Carbon::parse($seragamPaymentEnd)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-3 flex items-start p-3 bg-yellow-100 border-l-4 border-yellow-500 rounded">
                                        <svg class="w-4 h-4 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="text-xs text-yellow-800">
                                            <p class="mb-1"><strong>ℹ️ Informasi Penting:</strong></p>
                                            <p>Pengisian ukuran seragam mengikuti jadwal pembayaran daftar ulang seragam. Silakan kembali ke halaman ini pada waktu yang telah ditentukan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
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
                        $showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
                        $showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
                        $showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);
                    @endphp

                    @if($this->ukuranInfo)
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-purple-900 prose prose-sm prose-purple max-w-none">
                                    {!! $this->ukuranInfo !!}
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

                    @if($isUkuranWaiting)
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-yellow-800 font-medium">Data Ukuran Sedang Menunggu Persetujuan</p>
                                    <p class="text-xs text-yellow-600 mt-1">Data ukuran seragam yang Anda kirim sedang dalam proses verifikasi oleh panitia. Form tidak dapat diubah saat menunggu persetujuan.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!$isUkuranWaiting && !$isUkuranApproved)
                    {{-- Only show form if NOT waiting and NOT approved --}}
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
                    @endif {{-- End of @if(!$isUkuranWaiting && !$isUkuranApproved) - Form only shown if not waiting and not approved --}}

                    {{-- PHP Logic for Ringkasan and Warning - OUTSIDE form conditional so it works even when form is hidden --}}
                    @php
                        // Determine actual values - use database values if form is hidden (waiting/approved)
                        // Otherwise use livewire properties (form is active)
                        if ($isUkuranWaiting || $isUkuranApproved) {
                            // Get from database - form is hidden, livewire properties not populated
                            $actualUkuranJas = $seragam->ukuran_jas ?? '';
                            $actualUkuranBajuOlahraga = $seragam->ukuran_baju_olahraga ?? '';
                            $actualUkuranCelanaOlahraga = $seragam->ukuran_celana_olahraga ?? '';
                            $actualUkuranBajuHarian = $seragam->ukuran_baju_harian ?? '';
                            $actualUkuranCelanaHarian = $seragam->ukuran_celana_rok_harian ?? '';
                            $actualUkuranKopyah = $seragam->ukuran_kopyah ?? '';
                        } else {
                            // Get from livewire properties - form is active, user may be editing
                            $actualUkuranJas = $ukuran_jas == 'opsi_lainnya' ? $ukuranJasOpsiLainnya : $ukuran_jas;
                            $actualUkuranBajuOlahraga = $ukuran_baju_olahraga == 'opsi_lainnya' ? $ukuranBajuOlahragaOpsiLainnya : $ukuran_baju_olahraga;
                            $actualUkuranCelanaOlahraga = $ukuran_celana_olahraga == 'opsi_lainnya' ? $ukuranCelanaOlahragaOpsiLainnya : $ukuran_celana_olahraga;
                            $actualUkuranBajuHarian = $ukuran_baju_harian == 'opsi_lainnya' ? $ukuranBajuHarianOpsiLainnya : $ukuran_baju_harian;
                            $actualUkuranCelanaHarian = $ukuran_celana_rok_harian == 'opsi_lainnya' ? $ukuranCelanaRokHarianOpsiLainnya : $ukuran_celana_rok_harian;
                            $actualUkuranKopyah = $ukuran_kopyah == 'opsi_lainnya' ? $ukuranKopyahOpsiLainnya : $ukuran_kopyah;
                        }

                        // Check if any size is selected
                        $anyUkuranSelected = $actualUkuranJas || $actualUkuranBajuOlahraga || $actualUkuranCelanaOlahraga || $actualUkuranBajuHarian || $actualUkuranCelanaHarian || $actualUkuranKopyah;

                        // Check if there's a difference between baju harian and celana harian
                        $hasSizeDifference = false;
                        $largerSize = '';
                        $recommendedSize = '';  // Size sesuai mode perhitungan

                        if ($actualUkuranBajuHarian && $actualUkuranCelanaHarian && $actualUkuranBajuHarian != $actualUkuranCelanaHarian) {
                            $hasSizeDifference = true;

                            // Determine which size is larger (simple comparison based on common sizes)
                            $sizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                            $bajuIndex = array_search($actualUkuranBajuHarian, $sizeOrder);
                            $celanaIndex = array_search($actualUkuranCelanaHarian, $sizeOrder);

                            if ($bajuIndex !== false && $celanaIndex !== false) {
                                $largerSize = $bajuIndex > $celanaIndex ? $actualUkuranBajuHarian : $actualUkuranCelanaHarian;
                            } else {
                                // If not standard sizes, just mention both
                                $largerSize = 'ukuran terbesar antara ' . $actualUkuranBajuHarian . ' dan ' . $actualUkuranCelanaHarian;
                            }

                            // Set recommendedSize based on mode
                            if ($ukuranAlertPerhitunganMode === 'baju_harian') {
                                $recommendedSize = $actualUkuranBajuHarian;
                            } else {
                                $recommendedSize = $largerSize;
                            }
                        }
                    @endphp

                    {{-- Ringkasan Pilihan Ukuran - OUTSIDE form conditional so it shows even when form is hidden --}}
                    @if($anyUkuranSelected)
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-300 rounded-lg p-6 shadow-md mt-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <h4 class="text-lg font-bold text-purple-900">📋 Ringkasan Pilihan Ukuran Anda</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($actualUkuranJas)
                            <div class="bg-white rounded-lg p-4 border border-red-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-red-600 font-bold text-lg">👔</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Ukuran Jas</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranJas }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($actualUkuranBajuOlahraga)
                            <div class="bg-white rounded-lg p-4 border border-blue-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-bold text-lg">👕</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Baju Olahraga</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranBajuOlahraga }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($actualUkuranCelanaOlahraga)
                            <div class="bg-white rounded-lg p-4 border border-indigo-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-indigo-600 font-bold text-lg">👖</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Celana Olahraga</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranCelanaOlahraga }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($actualUkuranBajuHarian)
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-green-600 font-bold text-lg">👔</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Baju Seragam Harian</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranBajuHarian }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($actualUkuranCelanaHarian)
                            <div class="bg-white rounded-lg p-4 border border-teal-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-teal-600 font-bold text-lg">👗</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Celana/Rok Harian</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranCelanaHarian }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($actualUkuranKopyah && $pendaftar->jenis_kelamin === 'L')
                            <div class="bg-white rounded-lg p-4 border border-cyan-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-cyan-600 font-bold text-lg">🧢</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Ukuran Kopyah</p>
                                        <p class="text-base font-bold text-gray-900">{{ $actualUkuranKopyah }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Warning Perbedaan Ukuran - OUTSIDE form conditional so it shows even when form is hidden --}}
                    @if($ukuranAlertPerbedaanEnabled && $hasSizeDifference)
                    <div class="bg-orange-50 border-l-4 border-orange-500 rounded-lg p-5 shadow-md mt-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-orange-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-base font-bold text-orange-900 mb-2">
                                    ⚠️ Perhatian: Perbedaan Ukuran Seragam Harian Terdeteksi!
                                </h3>
                                <div class="text-sm text-orange-800 space-y-2">
                                    <p class="font-semibold">
                                        Anda memilih ukuran yang berbeda untuk baju seragam harian (<span class="font-bold text-orange-900">{{ $actualUkuranBajuHarian }}</span>)
                                        dan celana/rok harian (<span class="font-bold text-orange-900">{{ $actualUkuranCelanaHarian }}</span>).
                                    </p>
                                    <div class="bg-orange-100 border border-orange-300 rounded-md p-3 mt-3">
                                        <p class="font-semibold text-orange-900 mb-2">📌 Informasi Penting untuk Pembayaran:</p>
                                        <p class="leading-relaxed">
                                            Saat melakukan pembayaran daftar ulang seragam, <span class="font-bold underline">Anda diharuskan memilih dan membayar sesuai dengan
                                            @if($ukuranAlertPerhitunganMode === 'baju_harian')
                                                ukuran baju seragam harian/atasan
                                            @else
                                                ukuran terbesar
                                            @endif
                                            </span> yang telah Anda pilih.
                                        </p>
                                        <p class="mt-2 font-bold text-orange-900">
                                            💰 Ukuran untuk pembayaran: <span class="text-lg">{{ $recommendedSize }}</span>
                                        </p>
                                    </div>
                                    <p class="text-xs mt-2 italic">
                                        Pastikan Anda memilih paket seragam dan nominal pembayaran yang sesuai dengan ukuran
                                        @if($ukuranAlertPerhitunganMode === 'baju_harian')
                                            baju seragam harian/atasan
                                        @else
                                            terbesar
                                        @endif
                                        tersebut pada saat mengisi form pembayaran di tab "Daftar Ulang Seragam".
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @endif {{-- End of @if(!$isSeragamPaymentOpen) --}}
                </div>
                @endif {{-- End of @if($activeTab === 'ukuran') --}}
            </div>
        @endif
    @endif
</div>
