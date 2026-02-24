<div>
    <x-registration-phase-banner />

    <!-- Breadcrumb -->
    <section class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Cek Status Pendaftaran</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 to-emerald-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Cek Status Pendaftaran</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Masukkan nomor pendaftaran atau NISN untuk mengecek status pendaftaran Anda
                </p>
            </div>
        </div>
    </section>

    <!-- Search Form -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cari Data Pendaftaran</h2>
                    <p class="text-gray-600">Masukkan nomor pendaftaran atau NISN Anda</p>
                </div>

                <form wire:submit.prevent="search" class="space-y-4">
                    <div>
                        <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Pendaftaran / NISN
                        </label>
                        <input
                            type="text"
                            id="searchQuery"
                            wire:model.defer="searchQuery"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: P0001 atau 0012345678"
                        >
                        @error('searchQuery')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-50"
                        >
                            <span wire:loading.remove>
                                Cari
                            </span>
                            <span wire:loading>
                                Mencari...
                            </span>
                        </button>
                        @if($result || $errorMessage)
                            <button
                                type="button"
                                wire:click="resetSearch"
                                class="px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
                            >
                                Reset
                            </button>
                        @endif
                    </div>
                </form>

                <!-- Error Message -->
                @if($errorMessage)
                    <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                        </div>
                    </div>
                @endif

                <!-- Result -->
                @if($result)
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Data Pendaftaran Ditemukan</h3>

                        <div class="space-y-6">
                            @php
                                // Helper function untuk mendapatkan status badge config
                                function getStatusBadge($status, $type) {
                                    $badges = [
                                        'pendaftaran' => [
                                            'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Draft'],
                                            'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu Verifikasi'],
                                            'verifikasi' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Sedang Diverifikasi'],
                                            'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Diterima'],
                                            'data_disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Data Disetujui'],
                                            'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Ditolak'],
                                            'data_ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Data Ditolak'],
                                        ],
                                        'psikologi' => [
                                            'belum_upload' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12', 'label' => 'Belum Upload'],
                                            'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu Verifikasi'],
                                            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Disetujui'],
                                            'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Ditolak'],
                                            'perbaiki' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Perlu Perbaikan'],
                                        ],
                                        'kelulusan' => [
                                            'lulus' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'LULUS'],
                                            'diterima' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'LULUS'],
                                            'cadangan' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Cadangan'],
                                            'tidak_lulus' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Tidak Lulus'],
                                            'belum_diumumkan' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Belum Diumumkan'],
                                        ],
                                        'daftar_ulang' => [
                                            'menunggu' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu'],
                                            'disetujui' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Disetujui'],
                                            'ditolak' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Ditolak'],
                                            'perbaiki' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'label' => 'Perlu Perbaikan'],
                                            'belum' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Belum Daftar Ulang'],
                                        ],
                                    ];

                                    return $badges[$type][$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => ucfirst($status)];
                                }

                                // Determine kelulusan status
                                $isLulus = in_array($result->status_pendaftaran, ['lulus', 'diterima']);
                                $isCadangan = $result->status_pendaftaran === 'cadangan';
                                $isTidakLulus = in_array($result->status_pendaftaran, ['tidak_lulus', 'ditolak', 'data_ditolak']);

                                if ($isLulus || $isCadangan || $isTidakLulus) {
                                    $statusKelulusan = $result->status_pendaftaran;
                                } else {
                                    $statusKelulusan = 'belum_diumumkan';
                                }

                                // Map status pendaftaran untuk display verifikasi
                                // Jika status lulus/diterima, tampilkan sebagai "Data Disetujui" di verifikasi pendaftaran
                                $statusPendaftaranDisplay = $result->status_pendaftaran;
                                if (in_array($result->status_pendaftaran, ['lulus', 'diterima'])) {
                                    $statusPendaftaranDisplay = 'data_disetujui';
                                }
                            @endphp

                            <!-- Status Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- 1. Status Verifikasi Pendaftaran -->
                                @php $badge = getStatusBadge($statusPendaftaranDisplay, 'pendaftaran'); @endphp
                                <div class="{{ $badge['bg'] }} border border-gray-300 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 {{ $badge['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badge['icon'] }}"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-xs font-medium text-gray-600 mb-1">Verifikasi Pendaftaran</h4>
                                            <p class="text-sm font-bold {{ $badge['text'] }}">{{ $badge['label'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Status Verifikasi Biaya Tes Psikologi -->
                                @php
                                    $statusPsikologi = $result->tesPsikologiPendaftar->status_verifikasi ?? 'belum_upload';
                                    $badgePsikologi = getStatusBadge($statusPsikologi, 'psikologi');
                                @endphp
                                <div class="{{ $badgePsikologi['bg'] }} border border-gray-300 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 {{ $badgePsikologi['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgePsikologi['icon'] }}"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-xs font-medium text-gray-600 mb-1">Verif. Biaya Tes Psikologi</h4>
                                            <p class="text-sm font-bold {{ $badgePsikologi['text'] }}">{{ $badgePsikologi['label'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Status Kelulusan -->
                                @php $badgeKelulusan = getStatusBadge($statusKelulusan, 'kelulusan'); @endphp
                                <div class="{{ $badgeKelulusan['bg'] }} border border-gray-300 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 {{ $badgeKelulusan['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgeKelulusan['icon'] }}"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-xs font-medium text-gray-600 mb-1">Status Kelulusan</h4>
                                            <p class="text-sm font-bold {{ $badgeKelulusan['text'] }}">{{ $badgeKelulusan['label'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conditional: Daftar Ulang Status (Only if LULUS) -->
                            @if($isLulus)
                                <div class="mt-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-3">Status Daftar Ulang</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- 4. Status Daftar Ulang Madrasah -->
                                        @php
                                            $statusMadrasah = $result->daftarUlang->status ?? 'belum';
                                            $badgeMadrasah = getStatusBadge($statusMadrasah, 'daftar_ulang');
                                        @endphp
                                        <div class="{{ $badgeMadrasah['bg'] }} border border-gray-300 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="w-6 h-6 {{ $badgeMadrasah['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgeMadrasah['icon'] }}"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <h4 class="text-xs font-medium text-gray-600 mb-1">DU Madrasah</h4>
                                                    <p class="text-sm font-bold {{ $badgeMadrasah['text'] }}">{{ $badgeMadrasah['label'] }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 5. Status Ukuran Seragam -->
                                        @php
                                            $statusUkuran = $result->seragam->status_ukuran ?? 'belum';
                                            $badgeUkuran = getStatusBadge($statusUkuran, 'daftar_ulang');
                                        @endphp
                                        <div class="{{ $badgeUkuran['bg'] }} border border-gray-300 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="w-6 h-6 {{ $badgeUkuran['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgeUkuran['icon'] }}"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <h4 class="text-xs font-medium text-gray-600 mb-1">Ukuran Seragam</h4>
                                                    <p class="text-sm font-bold {{ $badgeUkuran['text'] }}">{{ $badgeUkuran['label'] }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 6. Status Daftar Ulang Seragam -->
                                        @php
                                            $statusSeragam = $result->seragam->status_seragam ?? 'belum';
                                            $badgeSeragam = getStatusBadge($statusSeragam, 'daftar_ulang');
                                        @endphp
                                        <div class="{{ $badgeSeragam['bg'] }} border border-gray-300 rounded-lg p-4">
                                            <div class="flex items-start">
                                                <svg class="w-6 h-6 {{ $badgeSeragam['text'] }} mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badgeSeragam['icon'] }}"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <h4 class="text-xs font-medium text-gray-600 mb-1">DU Seragam</h4>
                                                    <p class="text-sm font-bold {{ $badgeSeragam['text'] }}">{{ $badgeSeragam['label'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Informasi Pendaftar -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Nomor Pendaftaran</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $result->nomor_pendaftaran }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Nama Lengkap</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $result->nama_lengkap }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">NISN</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $result->nisn }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Jalur Pendaftaran</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $result->jalurPendaftaran->nama ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-600">Tanggal Daftar</span>
                                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($result->tanggal_daftar)->format('d F Y') }}</span>
                                </div>
                            </div>

                            <!-- Catatan Verifikasi (jika ada) -->
                            @if($result->catatan_verifikasi)
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-bold text-blue-900 mb-2">Catatan dari Panitia:</p>
                                            <p class="text-sm text-blue-800 leading-relaxed">{{ $result->catatan_verifikasi }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Info Tambahan berdasarkan status -->
                            @if($result->status_pendaftaran === 'diterima')
                                <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-base font-bold text-green-900 mb-2">🎉 Selamat! Anda Diterima di MTsN 2 Kota Malang</p>
                                            <p class="text-sm text-green-800 mb-2">Silakan lakukan langkah berikut:</p>
                                            <ul class="text-sm text-green-800 space-y-1 ml-4">
                                                <li>• Login ke dashboard untuk melihat informasi daftar ulang</li>
                                                <li>• Lakukan daftar ulang sesuai jadwal yang ditentukan</li>
                                                <li>• Siapkan dokumen asli (Ijazah/SKHUN, Akte Kelahiran, KK, KTP Orang Tua)</li>
                                                <li>• Pantau terus pengumuman di website untuk informasi terbaru</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif($result->status_pendaftaran === 'ditolak')
                                <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-base font-bold text-red-900 mb-2">Pendaftaran Tidak Dapat Diproses</p>
                                            <p class="text-sm text-red-800 mb-2">Mohon maaf, pendaftaran Anda tidak dapat diproses. Silakan:</p>
                                            <ul class="text-sm text-red-800 space-y-1 ml-4">
                                                <li>• Periksa catatan dari panitia di atas</li>
                                                <li>• Hubungi panitia {{ $appAcronym }} untuk informasi lebih lanjut</li>
                                                <li>• Jika memungkinkan, lengkapi dokumen dan daftar kembali di periode berikutnya</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif($result->status_pendaftaran === 'menunggu')
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-base font-bold text-yellow-900 mb-2">Menunggu Verifikasi Panitia</p>
                                            <p class="text-sm text-yellow-800 mb-2">Pendaftaran Anda sedang dalam proses verifikasi. Hal yang perlu dilakukan:</p>
                                            <ul class="text-sm text-yellow-800 space-y-1 ml-4">
                                                <li>• Mohon bersabar, proses verifikasi membutuhkan waktu 1-3 hari kerja</li>
                                                <li>• Pantau status pendaftaran secara berkala</li>
                                                <li>• Pastikan nomor telepon/email Anda aktif untuk dihubungi panitia</li>
                                                <li>• Login ke dashboard untuk melihat detail pendaftaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif($result->status_pendaftaran === 'verifikasi')
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <div>
                                            <p class="text-base font-bold text-blue-900 mb-2">Sedang Dalam Proses Verifikasi</p>
                                            <p class="text-sm text-blue-800 mb-2">Panitia sedang memverifikasi dokumen dan data Anda. Mohon:</p>
                                            <ul class="text-sm text-blue-800 space-y-1 ml-4">
                                                <li>• Tunggu proses verifikasi selesai</li>
                                                <li>• Cek email dan notifikasi secara berkala</li>
                                                <li>• Siapkan dokumen tambahan jika diperlukan</li>
                                                <li>• Segera merespon jika dihubungi panitia</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif($result->status_pendaftaran === 'draft')
                                <div class="bg-gray-50 border-l-4 border-gray-500 p-5 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-base font-bold text-gray-900 mb-2">Pendaftaran Belum Disubmit</p>
                                            <p class="text-sm text-gray-800 mb-2">Anda belum menyelesaikan proses pendaftaran. Segera:</p>
                                            <ul class="text-sm text-gray-800 space-y-1 ml-4">
                                                <li>• Login ke dashboard untuk melengkapi data</li>
                                                <li>• Isi semua formulir yang diperlukan</li>
                                                <li>• Upload dokumen persyaratan</li>
                                                <li>• Submit pendaftaran sebelum batas waktu pendaftaran ditutup</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="pt-4">
                                <a href="/login" class="block w-full text-center bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                                    Login ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Belum Punya Akun?</h3>
                    <p class="text-sm text-gray-600 mb-4">Daftar sekarang untuk memulai proses pendaftaran</p>
                    <a href="/daftar" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                        Daftar Sekarang →
                    </a>
                </div>

                <div class="bg-green-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Lihat Jadwal</h3>
                    <p class="text-sm text-gray-600 mb-4">Cek jadwal pendaftaran dan pengumuman</p>
                    <a href="/jadwal" class="text-green-600 hover:text-green-700 font-semibold text-sm">
                        Lihat Jadwal →
                    </a>
                </div>

                <div class="bg-purple-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Butuh Bantuan?</h3>
                    <p class="text-sm text-gray-600 mb-4">Hubungi panitia untuk informasi lebih lanjut</p>
                    <a href="/kontak-panitia" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                        Hubungi Kami →
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
