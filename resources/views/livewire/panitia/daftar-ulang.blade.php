<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{ show: false, message: '' }"
            x-on:saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center"
            style="display: none;">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message"></span>
        </div>

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Ulang Madrasah</h2>
            <p class="text-gray-600">Manajemen daftar ulang pendaftar baru</p>
        </div>

        {{-- Tabs Navigation --}}
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button
                        wire:click="switchTab('madrasah')"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'madrasah' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $activeTab === 'madrasah' ? 'text-emerald-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Data Daftar Ulang
                    </button>
                    <button
                        wire:click="switchTab('settings')"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'settings' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <svg class="w-5 h-5 mr-2 {{ $activeTab === 'settings' ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Pengaturan
                    </button>
                </nav>
            </div>
        </div>

        {{-- Content for Madrasah Tab --}}
        @if($activeTab !== 'settings')
        <!-- Stats Overview -->
        @include('livewire.admin.partials.daftar-ulang-stats', ['tab' => 'madrasah'])

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Pendaftar</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex-1">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NISN, atau no. pendaftaran..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <select wire:model.live="filterJalur" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select wire:model.live="filterStatus" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="perbaiki">Perbaiki</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
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

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-sm sm:text-lg font-bold text-white uppercase tracking-wider">Daftar Ulang Madrasah</h3>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <x-sortable-header field="nomor_pendaftaran" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Pendaftaran
                            </x-sortable-header>
                            <x-sortable-header field="nama_lengkap" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pendaftar
                            </x-sortable-header>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ortu WhatsApp</th>
                            <x-sortable-header field="jenis_kelamin" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Kelamin
                            </x-sortable-header>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persetujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status DU Madrasah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Pernyataan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal yang Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pengirim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Opsi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendaftar as $item)
                            <tr>
                                <!-- Nomor Pendaftaran -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nomor_pendaftaran }}</div>
                                </td>

                                <!-- Pendaftar -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">NISN: {{ $item->nisn }}</div>
                                </td>

                                <!-- Ortu WhatsApp -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        @if($item->telepon_ortu)
                                            @php
                                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->telepon_ortu);
                                                if (substr($cleanPhone, 0, 1) === '0') {
                                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                                } elseif (substr($cleanPhone, 0, 2) !== '62') {
                                                    $cleanPhone = '62' . $cleanPhone;
                                                }
                                                $waUrl = 'https://wa.me/' . $cleanPhone;
                                            @endphp
                                            <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center text-sm text-green-600 hover:text-green-800 hover:underline transition">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                                                </svg>
                                                {{ $item->telepon_ortu }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Jenis Kelamin -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jenis_kelamin)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Jalur -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jalurPendaftaran)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $item->jalurPendaftaran->warna ?? '#6B7280' }}">
                                            {{ $item->jalurPendaftaran->nama }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Persetujuan -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $persetujuanColors = [
                                            'belum_memilih' => 'bg-gray-100 text-gray-800',
                                            'setuju' => 'bg-green-100 text-green-800',
                                            'tidak_setuju' => 'bg-red-100 text-red-800',
                                        ];
                                        $persetujuanLabels = [
                                            'belum_memilih' => 'Belum Memilih',
                                            'setuju' => '✓ Setuju',
                                            'tidak_setuju' => '✗ Tidak Melanjutkan',
                                        ];
                                        $statusPersetujuan = $item->status_persetujuan_daftar_ulang ?? 'belum_memilih';
                                        $persetujuanColor = $persetujuanColors[$statusPersetujuan] ?? 'bg-gray-100 text-gray-800';
                                        $persetujuanLabel = $persetujuanLabels[$statusPersetujuan] ?? 'Belum Memilih';
                                    @endphp
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $persetujuanColor }}">
                                            {{ $persetujuanLabel }}
                                        </span>
                                        @if($item->tanggal_pilihan_daftar_ulang)
                                            <span class="text-xs text-gray-500">{{ $item->tanggal_pilihan_daftar_ulang->format('d/m/Y H:i') }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Alasan -->
                                <td class="px-6 py-4">
                                    @if($item->status_persetujuan_daftar_ulang === 'tidak_setuju' && $item->alasan_tidak_setuju)
                                        <div class="max-w-xs">
                                            <div class="text-xs text-gray-700 bg-gray-50 p-2 rounded border border-gray-200">
                                                <span class="font-semibold text-red-600">Alasan:</span>
                                                <p class="mt-1">{{ $item->alasan_tidak_setuju }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status DU Madrasah -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'belum_diisi' => 'bg-gray-200 text-gray-700',
                                            'proses' => 'bg-blue-100 text-blue-800',
                                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'disetujui' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                            'perbaiki' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $statusLabels = [
                                            'belum_diisi' => 'Belum Diisi',
                                            'proses' => 'Proses',
                                            'menunggu' => 'Menunggu',
                                            'disetujui' => 'Disetujui',
                                            'ditolak' => 'Ditolak',
                                            'perbaiki' => 'Perbaiki',
                                        ];

                                        $status = $item->daftarUlang && $item->daftarUlang->status ? $item->daftarUlang->status : 'belum_diisi';
                                        $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <!-- Surat Pernyataan -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->surat_pernyataan_path)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Sudah Upload
                                        </span>
                                        <a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan', 'pendaftarId' => $item->id]) }}"
                                           target="_blank"
                                           class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            ✗ Belum Upload
                                        </span>
                                    @endif
                                </td>

                                <!-- Nominal Biaya (Awal untuk Surat Pernyataan) -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya_awal)
                                        @php $nominalBiayaAwal = $item->daftarUlang->nominal_biaya_awal; @endphp
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiayaAwal['name'] ?? '-' }}</div>
                                            <div class="font-bold text-blue-700">Rp {{ number_format($nominalBiayaAwal['amount'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Belum pilih</span>
                                    @endif
                                </td>

                                <!-- Nominal yang Dibayar (Konfirmasi) -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
                                        @php $nominalBiaya = $item->daftarUlang->nominal_biaya; @endphp
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium text-gray-700 mb-1">{{ $nominalBiaya['name'] ?? '-' }}</div>
                                            <div class="font-bold text-emerald-700">Rp {{ number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Belum konfirmasi</span>
                                    @endif
                                </td>

                                <!-- Opsi Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->opsi_pembayaran)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ ucfirst($item->daftarUlang->opsi_pembayaran) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Bank/Metode -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
                                        <div class="text-xs text-gray-900">
                                            <span class="font-medium">{{ $item->daftarUlang->metode_pembayaran }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Nama Pengirim -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->nama_pengirim)
                                        <div class="text-xs text-gray-900">
                                            <span class="font-medium">{{ $item->daftarUlang->nama_pengirim }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Bukti Pembayaran -->
                                <td class="px-6 py-4">
                                    @if($item->daftarUlang && $item->daftarUlang->bukti_pembayaran_madrasah)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓ Sudah Upload
                                        </span>
                                        <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_madrasah', 'pendaftarId' => $item->id]) }}"
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
                                    @if($item->daftarUlang && $item->daftarUlang->waktu_pembayaran_madrasah)
                                        <div class="text-xs text-gray-900">
                                            <div class="font-medium">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('d/m/Y') }}</div>
                                            <div class="text-gray-600">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('H:i') }} WIB</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">-</span>
                                    @endif
                                </td>

                                <!-- Catatan -->
                                <td class="px-6 py-4">
                                    <div class="space-y-3">
                                        <!-- Catatan (visible to pendaftar) -->
                                        <div class="flex gap-2">
                                            @if($item->daftarUlang && $item->daftarUlang->catatan)
                                                <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 flex-1 min-w-0 max-w-xs overflow-hidden">
                                                    <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                    <p class="whitespace-pre-wrap break-words" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; max-height: 4.5rem;">{{ $item->daftarUlang->catatan }}</p>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                            @endif
                                            <div class="flex-shrink-0 relative z-50">
                                                <button
                                                    wire:click="openCatatanModal('madrasah', {{ $item->id }})"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                                    title="Edit Catatan"
                                                    style="pointer-events: auto; z-index: 100; position: relative;">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Catatan Internal (NOT visible to pendaftar) -->
                                        <div class="flex gap-2">
                                            @if($item->daftarUlang && $item->daftarUlang->catatan_internal)
                                                <div class="text-xs text-gray-700 bg-red-50 p-2 rounded border border-red-200 flex-1 min-w-0 max-w-xs overflow-hidden">
                                                    <div class="font-semibold text-red-700 mb-1">🔒 Internal:</div>
                                                    <p class="whitespace-pre-wrap break-words" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; max-height: 4.5rem;">{{ $item->daftarUlang->catatan_internal }}</p>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada catatan internal</span>
                                            @endif
                                            <div class="flex-shrink-0 relative z-50">
                                                <button
                                                    wire:click="openCatatanInternalModal('madrasah', {{ $item->id }})"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded hover:bg-red-100 transition-colors"
                                                    title="Edit Catatan Internal (Tidak ditampilkan ke pendaftar)"
                                                    style="pointer-events: auto; z-index: 100; position: relative;">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Internal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Aksi Opsi (Individual Toggles) - Madrasah -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-3">
                                        <!-- Toggle 1: Opsi Lainnya Dropdown -->
                                        @php
                                            $dropdownEnabled = false;
                                            try {
                                                $dropdownEnabled = isset($item->daftarUlang->opsi_lainnya_dropdown_individual)
                                                    && $item->daftarUlang->opsi_lainnya_dropdown_individual == true;
                                            } catch (\Exception $e) {
                                                // Silently fail if column doesn't exist
                                            }
                                        @endphp
                                        <div x-data="{
                                            enabled: {{ $dropdownEnabled ? 'true' : 'false' }}
                                        }" class="flex flex-col">
                                            <label class="text-[10px] font-semibold text-gray-600 mb-1">Dropdown Opsi Lainnya</label>
                                            <button
                                                @click="enabled = !enabled; $wire.toggleOpsiLainnyaDropdownIndividual({{ $item->id }}, 'madrasah')"
                                                type="button"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                                :class="enabled ? 'bg-blue-600' : 'bg-gray-200'"
                                                role="switch"
                                                :aria-checked="enabled">
                                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                      :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                            </button>
                                            <span class="text-[9px] text-gray-500 mt-1" x-text="enabled ? 'Aktif untuk orang ini' : 'Tidak aktif'"></span>
                                        </div>

                                        <!-- Toggle 2: Opsi Lainnya Konfirmasi -->
                                        @php
                                            $konfirmasiEnabled = false;
                                            try {
                                                $konfirmasiEnabled = isset($item->daftarUlang->opsi_lainnya_konfirmasi_individual)
                                                    && $item->daftarUlang->opsi_lainnya_konfirmasi_individual == true;
                                            } catch (\Exception $e) {
                                                // Silently fail if column doesn't exist
                                            }
                                        @endphp
                                        <div x-data="{
                                            enabled: {{ $konfirmasiEnabled ? 'true' : 'false' }}
                                        }" class="flex flex-col">
                                            <label class="text-[10px] font-semibold text-gray-600 mb-1">Konfirmasi Opsi Lainnya</label>
                                            <button
                                                @click="enabled = !enabled; $wire.toggleOpsiLainnyaKonfirmasiIndividual({{ $item->id }}, 'madrasah')"
                                                type="button"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                                :class="enabled ? 'bg-emerald-600' : 'bg-gray-200'"
                                                role="switch"
                                                :aria-checked="enabled">
                                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                      :class="enabled ? 'translate-x-5' : 'translate-x-0'"></span>
                                            </button>
                                            <span class="text-[9px] text-gray-500 mt-1" x-text="enabled ? 'Aktif untuk orang ini' : 'Tidak aktif'"></span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Ubah Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" @click.away="open = false" type="button"
                                                class="inline-flex items-center justify-center gap-x-1.5 rounded-md bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 transition-all duration-200">
                                            Ubah Status
                                            <svg class="-mr-1 h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div x-show="open"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 z-[100] mt-2 w-48 origin-top-right rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                            <div class="py-1">
                                                <button wire:click="changeStatusMadrasah({{ $item->id }}, 'menunggu')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-yellow-500"></span>
                                                    Menunggu
                                                </button>
                                                <button wire:click="changeStatusMadrasah({{ $item->id }}, 'disetujui')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-green-500"></span>
                                                    Disetujui
                                                </button>
                                                <button wire:click="changeStatusMadrasah({{ $item->id }}, 'ditolak')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-red-500"></span>
                                                    Ditolak
                                                </button>
                                                <button wire:click="changeStatusMadrasah({{ $item->id }}, 'perbaiki')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-orange-500"></span>
                                                    Perbaiki
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Aksi Pesan -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            wire:click="sendEmail({{ $item->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                            title="Kirim Email">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Kirim Email
                                        </button>
                                        <button
                                            wire:click="openMessageModal({{ $item->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-emerald-600 text-white text-xs rounded-md hover:bg-emerald-700 transition"
                                            title="Kirim Pesan Internal">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                            Kirim Pesan
                                        </button>
                                    </div>
                                </td>

                                <!-- Riwayat Pesan -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $lastMessage = \App\Models\RiwayatPesan::where('pendaftar_id', $item->id)
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                    @endphp
                                    @if($lastMessage)
                                        <div class="text-xs">
                                            <div class="flex items-center text-gray-700 mb-1">
                                                @if($lastMessage->jenis_pesan === 'email')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">Email</span>
                                                @elseif($lastMessage->jenis_pesan === 'whatsapp')
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                    </svg>
                                                    <span class="font-medium">WhatsApp</span>
                                                @else
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <div class="text-gray-500 text-[10px]">{{ $lastMessage->sent_at->format('d/m/Y H:i') }}</div>
                                            @else
                                                <div class="text-gray-500 text-[10px]">{{ $lastMessage->created_at->format('d/m/Y H:i') }}</div>
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
                                <td colspan="19" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data pendaftar ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendaftar->links() }}
            </div>
        </div>

        <!-- Message Modal -->
        @if($showMessageModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="message-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeMessageModal"></div>

                    <!-- Center modal -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="message-modal-title">
                                        Kirim Pesan ke Pendaftar
                                    </h3>
                                    @if($messagingPendaftar)
                                        <p class="text-sm text-gray-600 mt-2">
                                            Kepada: <span class="font-semibold">{{ $messagingPendaftar->nama_lengkap }}</span>
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $messagingPendaftar->nomor_pendaftaran }}</span>
                                        </p>
                                    @endif
                                    <div class="mt-4">
                                        <label for="messageContent" class="block text-sm font-medium text-gray-700 mb-2">
                                            Pesan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            wire:model="messageContent"
                                            id="messageContent"
                                            rows="5"
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200"
                                            placeholder="Tulis pesan Anda di sini..."
                                        ></textarea>
                                        @error('messageContent')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-2">Pesan akan dikirim sebagai notifikasi ke pendaftar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                wire:click="sendMessage"
                                type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Pesan
                            </button>
                            <button
                                wire:click="closeMessageModal"
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Edit Catatan -->
        <div x-data="{
            showCatatan: false,
            catatanType: '',
            catatanPendaftarId: null,
            catatanText: ''
        }"
             @open-catatan-modal.window="
                showCatatan = true;
                catatanType = $event.detail.type;
                catatanPendaftarId = $event.detail.pendaftarId;
                catatanText = $event.detail.currentCatatan || '';
             "
             x-show="showCatatan"
             x-cloak
             class="fixed inset-0 z-[9999] overflow-y-auto"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showCatatan = false"></div>

                <!-- Modal panel -->
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-yellow-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Edit Catatan DU Madrasah
                                </h3>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                    <textarea
                                        x-model="catatanText"
                                        rows="5"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        placeholder="Tulis catatan untuk pendaftar..."></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Catatan ini hanya bisa dilihat oleh admin dan panitia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button
                            @click="$wire.saveCatatan(catatanPendaftarId, catatanType, catatanText).then(() => { showCatatan = false; })"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Catatan
                        </button>
                        <button
                            @click="showCatatan = false"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:w-auto sm:text-sm transition">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catatan Modal -->
    @if($showCatatanModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
        <!-- Background overlay with blur -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeCatatanModal"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-yellow-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Edit Catatan DU Madrasah
                        </h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea
                                wire:model="catatanText"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                placeholder="Tulis catatan untuk pendaftar..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Catatan ini hanya bisa dilihat oleh admin dan panitia</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    type="button"
                    wire:click="saveCatatanFromModal"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Catatan
                </button>
                <button
                    type="button"
                    wire:click="closeCatatanModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Catatan Internal -->
    @if($showCatatanInternalModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeCatatanInternalModal"></div>

        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Edit Catatan Internal {{ $catatanInternalType === 'madrasah' ? 'DU Madrasah' : ($catatanInternalType === 'seragam' ? 'DU Seragam' : 'Ukuran Seragam') }}
                        </h3>

                        <!-- Warning Box -->
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm text-red-800 font-semibold">Catatan Internal - Tidak Ditampilkan ke Pendaftar</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                            <textarea
                                wire:model="catatanInternalText"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                                placeholder="Tulis catatan internal untuk admin/panitia saja..."></textarea>
                            <p class="mt-1 text-xs text-red-600 font-medium">⚠️ Catatan ini HANYA untuk internal admin/panitia dan TIDAK akan ditampilkan ke pendaftar</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    type="button"
                    wire:click="saveCatatanInternal"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Catatan Internal
                </button>
                <button
                    type="button"
                    wire:click="closeCatatanInternalModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif

    {{-- Content for Settings Tab --}}
    @if($activeTab === 'settings')
        @include('livewire.panitia.partials.settings-madrasah-tab')
    @endif
</div>
