<div>
    {{-- URL Cleaner --}}
    <div x-data x-init="
        // Clean URL on component init
        if (window.location.search.includes('activeTab=')) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
        // Also clean URL after any Livewire update
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (window.location.search.includes('activeTab=')) {
                    const cleanUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            });
        });
    " style="display: none;"></div>

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Daftar Ulang Seragam</h2>
        <p class="text-gray-600">Manajemen daftar ulang dan ukuran seragam siswa baru</p>
    </div>

    {{-- Tabs Navigation --}}
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button
                    wire:click="switchTab('seragam')"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'seragam' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $activeTab === 'seragam' ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Daftar Ulang Seragam
                </button>
                <button
                    wire:click="switchTab('ukuran')"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'ukuran' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <svg class="w-5 h-5 mr-2 {{ $activeTab === 'ukuran' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                    Ukuran Seragam & Atribut
                </button>
            </nav>
        </div>
    </div>

    {{-- Stats Overview --}}
    @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $stats, 'tab' => $activeTab])

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" wire:model.live="search" placeholder="Nama, NISN, No. Pendaftaran..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>

            {{-- Filter Jalur --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
                <select wire:model.live="filterJalur" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="">Semua Jalur</option>
                    @foreach($jalurPendaftaran as $jalur)
                        <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Jenis Kelamin --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <select wire:model.live="filterJenisKelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <option value="">Semua</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>

            {{-- Filter Status based on active tab --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                @if($activeTab === 'seragam')
                    <select wire:model.live="filterStatusSeragam" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="perbaiki">Perlu Perbaikan</option>
                    </select>
                @else
                    <select wire:model.live="filterStatusUkuran" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="perbaiki">Perlu Perbaikan</option>
                    </select>
                @endif
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 {{ $activeTab === 'seragam' ? 'bg-orange-600' : 'bg-indigo-600' }} flex items-center">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                @if($activeTab === 'seragam')
                    Daftar Ulang Seragam
                @else
                    Ukuran Seragam & Atribut
                @endif
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($activeTab === 'seragam')
                                Status DU Seragam
                            @else
                                Status Ukuran
                            @endif
                        </th>
                        @if($activeTab === 'seragam')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket Seragam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal yang Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        @else
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Jas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baju Olahraga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celana Olahraga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baju Seragam Harian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celana/Rok Harian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kopyah (L)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        @endif
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendaftar as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nomor_pendaftaran }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">NISN: {{ $item->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->jenis_kelamin)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->jalurPendaftaran)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $item->jalurPendaftaran->warna ?? '#6B7280' }}">
                                        {{ $item->jalurPendaftaran->nama }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'proses' => 'bg-blue-100 text-blue-800',
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'perbaiki' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $statusLabels = [
                                        'proses' => 'Proses',
                                        'menunggu' => 'Menunggu',
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        'perbaiki' => 'Perbaiki',
                                    ];

                                    if ($activeTab == 'seragam') {
                                        $status = $item->seragam->status_seragam ?? 'menunggu';
                                    } else {
                                        $status = $item->seragam->status_ukuran ?? 'menunggu';
                                    }

                                    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                    $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            @if($activeTab === 'seragam')
                                <!-- Paket Seragam -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->paket_seragam)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            {{ $item->seragam->paket_seragam }}
                                        </span>
                                        @if($item->seragam->paket_description)
                                            <div class="text-xs text-gray-600 mt-1">{{ $item->seragam->paket_description }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-500">Belum dipilih</span>
                                    @endif
                                </td>
                                <!-- Nominal Biaya (Awal) -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->nominal_seragam_awal)
                                        @php $nominalSeragamAwal = $item->seragam->nominal_seragam_awal; @endphp
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragamAwal['name'] ?? '-' }}</div>
                                            <div class="font-bold text-blue-700">Rp {{ number_format($nominalSeragamAwal['amount'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Belum pilih</span>
                                    @endif
                                </td>
                                <!-- Nominal yang Dibayar -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->nominal_seragam)
                                        @php $nominalSeragam = $item->seragam->nominal_seragam; @endphp
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragam['name'] ?? '-' }}</div>
                                            <div class="font-bold text-emerald-700">Rp {{ number_format($nominalSeragam['amount'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Belum konfirmasi</span>
                                    @endif
                                </td>
                                <!-- Opsi Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->opsi_pembayaran_seragam)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ ucfirst($item->seragam->opsi_pembayaran_seragam) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Bank/Metode -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->bank_seragam)
                                        <div class="text-xs text-gray-900">
                                            <span class="font-medium">{{ $item->seragam->bank_seragam }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Bukti Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->bukti_pembayaran)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Sudah Upload
                                        </span>
                                        <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_seragam', 'pendaftarId' => $item->id]) }}"
                                           target="_blank"
                                           class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ✗ Belum Upload
                                        </span>
                                    @endif
                                </td>
                                <!-- Waktu Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->waktu_pembayaran_seragam)
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium">{{ $item->seragam->waktu_pembayaran_seragam->format('d/m/Y') }}</div>
                                            <div class="text-gray-600">{{ $item->seragam->waktu_pembayaran_seragam->format('H:i') }} WIB</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Catatan Seragam -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($item->seragam && $item->seragam->catatan_seragam)
                                            <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 max-w-xs">
                                                <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_seragam }}</p>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                        @endif
                                        <button
                                            wire:click="openCatatanModal('seragam', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_seragam ?? '') }}')"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                            title="Edit Catatan">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            @else
                                <!-- Ukuran Jas -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->ukuran_jas)
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                            {{ $item->seragam->ukuran_jas }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Baju Olahraga -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->ukuran_baju_olahraga)
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->seragam->ukuran_baju_olahraga }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Celana Olahraga -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->ukuran_celana_olahraga)
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">
                                            {{ $item->seragam->ukuran_celana_olahraga }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Baju Seragam Harian -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->ukuran_baju_harian)
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $item->seragam->ukuran_baju_harian }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Celana/Rok Harian -->
                                <td class="px-6 py-4">
                                    @if($item->seragam && $item->seragam->ukuran_celana_rok_harian)
                                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-teal-100 text-teal-800">
                                            {{ $item->seragam->ukuran_celana_rok_harian }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>
                                <!-- Kopyah (L) -->
                                <td class="px-6 py-4">
                                    @if($item->jenis_kelamin === 'L')
                                        @if($item->seragam && $item->seragam->ukuran_kopyah)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $item->seragam->ukuran_kopyah }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Khusus L</span>
                                    @endif
                                </td>
                                <!-- Catatan Ukuran -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($item->seragam && $item->seragam->catatan_ukuran)
                                            <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 max-w-xs">
                                                <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_ukuran }}</p>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                        @endif
                                        <button
                                            wire:click="openCatatanModal('ukuran', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_ukuran ?? '') }}')"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                            title="Edit Catatan">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            @endif

                            <!-- Ubah Status -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($activeTab === 'seragam')
                                        <select
                                            wire:change="changeStatusSeragam({{ $item->id }}, $event.target.value)"
                                            class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                        >
                                            <option value="">-- Pilih Status --</option>
                                            <option value="disetujui" {{ ($item->seragam->status_seragam ?? '') === 'disetujui' ? 'selected' : '' }}>
                                                Disetujui
                                            </option>
                                            <option value="ditolak" {{ ($item->seragam->status_seragam ?? '') === 'ditolak' ? 'selected' : '' }}>
                                                Ditolak
                                            </option>
                                            <option value="perbaiki" {{ ($item->seragam->status_seragam ?? '') === 'perbaiki' ? 'selected' : '' }}>
                                                Perbaiki
                                            </option>
                                        </select>
                                    @else
                                        <select
                                            wire:change="changeStatusUkuran({{ $item->id }}, $event.target.value)"
                                            class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                        >
                                            <option value="">-- Pilih Status --</option>
                                            <option value="menunggu" {{ ($item->seragam->status_ukuran ?? '') === 'menunggu' ? 'selected' : '' }}>
                                                Menunggu
                                            </option>
                                            <option value="disetujui" {{ ($item->seragam->status_ukuran ?? '') === 'disetujui' ? 'selected' : '' }}>
                                                Disetujui
                                            </option>
                                            <option value="ditolak" {{ ($item->seragam->status_ukuran ?? '') === 'ditolak' ? 'selected' : '' }}>
                                                Ditolak
                                            </option>
                                            <option value="perbaiki" {{ ($item->seragam->status_ukuran ?? '') === 'perbaiki' ? 'selected' : '' }}>
                                                Perbaiki
                                            </option>
                                        </select>
                                    @endif
                                    <a href="/panitia-seragam/pendaftar/{{ $item->id }}" target="_blank" class="inline-flex items-center px-2 py-1.5 text-blue-600 hover:text-blue-900 text-xs font-medium border border-blue-300 rounded-lg hover:bg-blue-50 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>

                            <!-- Aksi Pesan -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <button
                                        wire:click="sendManualEmail({{ $item->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                        title="Kirim Email Manual">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Kirim Email
                                    </button>
                                    <button
                                        wire:click="sendManualWhatsApp({{ $item->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                        title="Kirim WhatsApp Manual">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        Kirim WhatsApp
                                    </button>
                                </div>
                            </td>

                            <!-- Riwayat Pesan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $lastMessage = \App\Models\RiwayatPesan::where('pendaftar_id', $item->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                @endphp
                                @if($lastMessage)
                                    <div class="text-xs">
                                        <div class="flex items-center text-gray-700 mb-1">
                                            @if($lastMessage->jenis_pesan === 'email')
                                                <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">Email</span>
                                            @elseif($lastMessage->jenis_pesan === 'whatsapp')
                                                <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347Z"/>
                                                </svg>
                                                <span class="font-medium">WhatsApp</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                                <span class="font-medium">Pesan</span>
                                            @endif
                                        </div>
                                        <div class="mb-1">
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $lastMessage->getStatusBadgeClass() }}">
                                                {{ $lastMessage->getStatusLabel() }}
                                            </span>
                                        </div>
                                        @if($lastMessage->sent_at)
                                            <div class="text-gray-600">{{ $lastMessage->sent_at->diffForHumans() }}</div>
                                            <div class="text-gray-500 text-[10px] mt-0.5">{{ $lastMessage->sent_at->format('d/m/Y H:i') }}</div>
                                        @else
                                            <div class="text-gray-500 text-[10px] mt-0.5">{{ $lastMessage->created_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if($lastMessage->user)
                                            <div class="text-gray-500 text-[10px]">oleh: {{ $lastMessage->user->name }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            @if($activeTab === 'seragam')
                                <td colspan="16" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada data yang ditemukan
                                </td>
                            @else
                                <td colspan="15" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada data yang ditemukan
                                </td>
                            @endif
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pendaftar->links() }}
        </div>
    </div>

    {{-- Send Message Modal --}}
    @include('livewire.admin.partials.send-message-modal')

    {{-- Catatan Modal --}}
    <div x-data="{ show: @entangle('showCatatanModal').live }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="show = false"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Edit Catatan {{ ucfirst($catatanType) }}
                        </h3>
                        <div class="mt-4">
                            <textarea
                                wire:model="catatanText"
                                rows="5"
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Masukkan catatan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button"
                            wire:click="saveCatatan"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Simpan
                    </button>
                    <button type="button"
                            wire:click="closeCatatanModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
