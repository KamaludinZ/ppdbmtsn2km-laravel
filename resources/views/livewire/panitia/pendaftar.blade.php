<div>
    <!-- Global Loading Overlay -->
    <div wire:loading class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Memproses...</span>
        </div>
    </div>

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
                        setTimeout(() => this.show = false, 4000);
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

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Stats Overview - Status Pendaftaran -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Menunggu</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['menunggu']) }}</p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lulus -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Lulus</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['lulus']) }}</p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cadangan -->
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Cadangan</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['cadangan'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tidak Lulus -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Tidak Lulus</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['tidak_lulus'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Data</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="searchInput"
                            wire:keydown.enter="applySearch"
                            placeholder="Cari nama, NISN, No. Daftar..."
                            autocomplete="new-password"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            name="search_pendaftar_{{ uniqid() }}"
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <button wire:click="applySearch" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-1 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Cari</span>
                        </button>
                        @if($search)
                            <button wire:click="clearSearch" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Hapus pencarian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                    <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Jalur</option>
                        @foreach($jalurPendaftaran as $jalur)
                            <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                        <option value="lengkapi_data">Lengkapi Data</option>
                        <option value="perbaiki_data">Perbaiki Data</option>
                        <option value="data_ditolak">Data Ditolak</option>
                        <option value="data_disetujui">Data Disetujui</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                    <select wire:model.live="perPage" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Pendaftar</h3>
                </div>
                <!-- Column Filter Dropdown -->
                <div x-data="columnFilter()" class="relative">
                    <button @click="open = !open" class="flex items-center px-4 py-2 bg-white text-emerald-600 rounded-md hover:bg-emerald-50 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span class="font-medium">Filter Kolom</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 border border-gray-200" x-cloak>
                        <div class="p-4 max-h-96 overflow-y-auto">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm uppercase">Pilih Kolom yang Ditampilkan</h4>
                            <!-- Checkboxes (Existing code omitted for brevity but preserved structure) -->
                            @foreach($showColumns as $key => $value)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" wire:model.live="showColumns.{{ $key }}" @change="toggleColumn('{{ $key }}', $event.target.checked)" class="rounded text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if($showColumns['no'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            @endif
                            @if($showColumns['nomor_pendaftaran'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</th>
                            @endif
                            @if($showColumns['pendaftar'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                            @endif
                            @if($showColumns['jenis_kelamin'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                            @endif
                            @if($showColumns['kontak'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                            @endif
                            @if($showColumns['password'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                            @endif
                            @if($showColumns['ubah_password'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Password</th>
                            @endif
                            @if($showColumns['ortu_whatsapp'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ortu WhatsApp</th>
                            @endif
                            @if($showColumns['asal_kabkota'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Kab/Kota</th>
                            @endif
                            @if($showColumns['jenis_sekolah'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Sekolah</th>
                            @endif
                            @if($showColumns['nsm_npsn'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NSM/NPSN</th>
                            @endif
                            @if($showColumns['asal_sekolah'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asal Sekolah</th>
                            @endif
                            @if($showColumns['nama_ayah'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ayah</th>
                            @endif
                            @if($showColumns['nama_ibu'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ibu</th>
                            @endif
                            @if($showColumns['jalur'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                            @endif
                            @if($showColumns['verif_psikologi'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Psikologi</th>
                            @endif
                            @if($showColumns['status'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pendaftaran</th>
                            @endif
                            @if($showColumns['status_tes'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tes</th>
                            @endif
                            @if($showColumns['setuju_daftar_ulang'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persetujuan Daftar Ulang</th>
                            @endif
                            @if($showColumns['status_du_madrasah'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status DU Madrasah</th>
                            @endif
                            @if($showColumns['status_du_seragam'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status DU Seragam</th>
                            @endif
                            @if($showColumns['status_ukuran'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Ukuran</th>
                            @endif
                            @if($showColumns['status_daftar_ulang'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Daftar Ulang</th>
                            @endif
                            @if($showColumns['verif_rapor'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Rapor</th>
                            @endif
                            @if($showColumns['verif_akte'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Akta Kelahiran</th>
                            @endif
                            @if($showColumns['verif_kk'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Kartu Keluarga</th>
                            @endif
                            @if($showColumns['verif_nisn'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. NISN</th>
                            @endif
                            @if($showColumns['verif_ijazah'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Ijazah/SKL</th>
                            @endif
                            @if($showColumns['verif_sertifikat'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Sertifikat/Piagam</th>
                            @endif
                            @if($showColumns['verif_kip'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. KIP/PKH/KKS</th>
                            @endif
                            @if($showColumns['diperbarui'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperbarui</th>
                            @endif
                            @if($showColumns['diperbarui_oleh'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperbarui Oleh</th>
                            @endif
                            @if($showColumns['tanggal'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                            @endif
                            @if($showColumns['emis_completeness'])
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelengkapan EMIS</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendaftar as $index => $item)
                            <tr class="hover:bg-gray-50">
                                @if($showColumns['no'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $pendaftar->firstItem() + $index }}
                                    </td>
                                @endif
                                @if($showColumns['nomor_pendaftaran'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $item->nomor_pendaftaran }}</span>
                                    </td>
                                @endif
                                @if($showColumns['pendaftar'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                                <span class="font-semibold">{{ strtoupper(substr($item->nama_lengkap ?? '?', 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <a href="{{ route('panitia.pendaftar.detail', $item->id) }}" target="_blank" rel="noopener noreferrer" wire:navigate.hover="false" class="text-sm font-medium text-gray-900 hover:text-emerald-600 hover:underline flex items-center" onclick="event.stopPropagation();">
                                                    {{ $item->nama_lengkap }}
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                                <div class="text-sm text-gray-500">NISN: {{ $item->nisn }}</div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                @if($showColumns['jenis_kelamin'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->jenis_kelamin)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['kontak'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->email }}</div>
                                        @if($item->no_hp)
                                            <div class="text-sm text-gray-500">{{ $item->no_hp }}</div>
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['password'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative inline-block">
                                            <input
                                                type="password"
                                                value="••••••••"
                                                readonly
                                                class="text-sm text-gray-900 bg-transparent border-none focus:outline-none w-24 overflow-hidden truncate"
                                                id="password-{{ $item->id }}"
                                            >
                                            <button
                                                type="button"
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                                onclick="togglePasswordVisibility('password-{{ $item->id }}', '{{ $item->user->password ?? '' }}')"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                                @if($showColumns['ubah_password'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click="showChangePasswordModal({{ $item->id }})"
                                            class="text-blue-600 hover:text-blue-900 hover:underline"
                                            title="Ubah Password"
                                        >
                                            Ubah
                                        </button>
                                    </td>
                                @endif
                                @if($showColumns['ortu_whatsapp'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->telepon_ortu ?? '' }}
                                    </td>
                                @endif
                                @if($showColumns['asal_kabkota'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->asal_kabkota ?? '-' }}
                                    </td>
                                @endif
                                @if($showColumns['jenis_sekolah'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->jenis_sekolah_asal)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $item->jenis_sekolah_asal === 'madrasah' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ ucfirst($item->jenis_sekolah_asal) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['nsm_npsn'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($item->jenis_sekolah_asal === 'madrasah')
                                            <div class="text-xs text-gray-500">NSM:</div>
                                            <div class="font-medium">{{ $item->nsm_sekolah_asal ?? '-' }}</div>
                                        @elseif($item->jenis_sekolah_asal === 'umum')
                                            <div class="text-xs text-gray-500">NPSN:</div>
                                            <div class="font-medium">{{ $item->npsn_sekolah_asal ?? '-' }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['asal_sekolah'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->asal_sekolah ?? '-' }}</div>
                                        @if($item->alamat_sekolah)
                                            <div class="text-xs text-gray-500">{{ $item->alamat_sekolah }}</div>
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['nama_ayah'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->nama_ayah ?? '-' }}
                                    </td>
                                @endif
                                @if($showColumns['nama_ibu'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->nama_ibu ?? '-' }}
                                    </td>
                                @endif
                                @if($showColumns['jalur'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $item->jalurPendaftaran->warna ?? 'blue' }}-100 text-{{ $item->jalurPendaftaran->warna ?? 'blue' }}-800">
                                            {{ $item->jalurPendaftaran->nama ?? '-' }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['verif_psikologi'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusPsikologi = $item->tesPsikologiPendaftar->status_verifikasi ?? 'belum_upload';
                                            $psikologiColors = ['belum_upload' => 'gray', 'menunggu' => 'yellow', 'ditolak' => 'red', 'disetujui' => 'green'];
                                            $psikologiLabels = ['belum_upload' => 'Belum Upload', 'menunggu' => 'Menunggu', 'ditolak' => 'Ditolak', 'disetujui' => 'Disetujui'];
                                            $psiColor = $psikologiColors[$statusPsikologi] ?? 'gray';
                                            $psiLabel = $psikologiLabels[$statusPsikologi] ?? $statusPsikologi;
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $psiColor }}-100 text-{{ $psiColor }}-800">{{ $psiLabel }}</span>
                                    </td>
                                @endif
                                @if($showColumns['status'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'menunggu_verifikasi' => 'yellow',
                                                'lengkapi_data' => 'orange',
                                                'perbaiki_data' => 'orange',
                                                'data_ditolak' => 'red',
                                                'data_disetujui' => 'green',
                                                // Legacy support
                                                'draft' => 'gray',
                                                'menunggu' => 'yellow',
                                                'verifikasi' => 'yellow',
                                                'diverifikasi' => 'blue',
                                                'lulus' => 'green',
                                                'diterima' => 'green',
                                                'tidak_lulus' => 'red',
                                                'ditolak' => 'red',
                                                'cadangan' => 'orange',
                                            ];
                                            $statusLabels = [
                                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                'lengkapi_data' => 'Lengkapi Data',
                                                'perbaiki_data' => 'Perbaiki Data',
                                                'data_ditolak' => 'Data Ditolak',
                                                'data_disetujui' => 'Data Disetujui',
                                                // Legacy support
                                                'draft' => 'Draft',
                                                'menunggu' => 'Menunggu Verifikasi',
                                                'verifikasi' => 'Menunggu Verifikasi',
                                                'diverifikasi' => 'Diverifikasi',
                                                'lulus' => 'Lulus Seleksi',
                                                'diterima' => 'Lulus Seleksi',
                                                'tidak_lulus' => 'Tidak Lulus',
                                                'ditolak' => 'Tidak Lulus',
                                                'cadangan' => 'Cadangan',
                                            ];
                                            $color = $statusColors[$item->status_pendaftaran] ?? 'gray';
                                            $label = $statusLabels[$item->status_pendaftaran] ?? $item->status_pendaftaran;
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                                            {{ $label }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['status_tes'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusTesColors = [
                                                'menunggu' => 'yellow',
                                                'diverifikasi' => 'blue',
                                                'lulus' => 'green',
                                                'diterima' => 'green',
                                                'tidak_lulus' => 'red',
                                                'ditolak' => 'red',
                                                'cadangan' => 'orange',
                                            ];
                                            $statusTesLabels = [
                                                'menunggu' => 'Menunggu',
                                                'diverifikasi' => 'Diverifikasi',
                                                'lulus' => 'LULUS',
                                                'diterima' => 'LULUS',
                                                'tidak_lulus' => 'TIDAK LULUS',
                                                'ditolak' => 'TIDAK LULUS',
                                                'cadangan' => 'CADANGAN',
                                            ];
                                            $tesColor = $statusTesColors[$item->status_pendaftaran] ?? 'gray';
                                            $tesLabel = $statusTesLabels[$item->status_pendaftaran] ?? 'Belum Ditentukan';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-{{ $tesColor }}-100 text-{{ $tesColor }}-800">
                                            {{ $tesLabel }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['setuju_daftar_ulang'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(in_array($item->status_pendaftaran, ['lulus', 'diterima']))
                                            @if($item->setuju_daftar_ulang)
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        ✓ Sudah Setuju
                                                    </span>
                                                    <button
                                                        wire:click="batalkanPersetujuanDaftarUlang({{ $item->id }})"
                                                        wire:loading.attr="disabled"
                                                        onclick="return confirm('Yakin ingin membatalkan persetujuan daftar ulang?')"
                                                        class="text-xs text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded hover:bg-red-100 transition disabled:opacity-50"
                                                        title="Batalkan Persetujuan">
                                                        <span wire:loading.remove wire:target="batalkanPersetujuanDaftarUlang({{ $item->id }})">✗ Batalkan</span>
                                                        <span wire:loading wire:target="batalkanPersetujuanDaftarUlang({{ $item->id }})">...</span>
                                                    </button>
                                                </div>
                                                @if($item->tanggal_setuju_daftar_ulang)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $item->tanggal_setuju_daftar_ulang->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Persetujuan
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                @if($showColumns['status_du_madrasah'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusDuMadrasah = $item->daftarUlang->status ?? 'menunggu';
                                            $duMadrasahColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $duMadrasahLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorDuMadrasah = $duMadrasahColors[$statusDuMadrasah] ?? 'bg-gray-100 text-gray-800';
                                            $labelDuMadrasah = $duMadrasahLabels[$statusDuMadrasah] ?? ucfirst($statusDuMadrasah);
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorDuMadrasah }}">
                                            {{ $labelDuMadrasah }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['status_du_seragam'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusDuSeragam = $item->seragam->status_seragam ?? 'menunggu';
                                            $duSeragamColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $duSeragamLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorDuSeragam = $duSeragamColors[$statusDuSeragam] ?? 'bg-gray-100 text-gray-800';
                                            $labelDuSeragam = $duSeragamLabels[$statusDuSeragam] ?? ucfirst($statusDuSeragam);
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorDuSeragam }}">
                                            {{ $labelDuSeragam }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['status_ukuran'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusUkuran = $item->seragam->status_ukuran ?? 'menunggu';
                                            $ukuranColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $ukuranLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorUkuran = $ukuranColors[$statusUkuran] ?? 'bg-gray-100 text-gray-800';
                                            $labelUkuran = $ukuranLabels[$statusUkuran] ?? ucfirst($statusUkuran);
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorUkuran }}">
                                            {{ $labelUkuran }}
                                        </span>
                                    </td>
                                @endif
                                @if($showColumns['status_daftar_ulang'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->daftarUlang)
                                            @php
                                                $daftarUlangColors = [
                                                    'belum' => 'gray',
                                                    'proses' => 'yellow',
                                                    'selesai' => 'green',
                                                ];
                                                $daftarUlangLabels = [
                                                    'belum' => 'Belum Daftar Ulang',
                                                    'proses' => 'Sedang Proses',
                                                    'selesai' => 'Selesai',
                                                ];
                                                $duColor = $daftarUlangColors[$item->daftarUlang->status] ?? 'gray';
                                                $duLabel = $daftarUlangLabels[$item->daftarUlang->status] ?? $item->daftarUlang->status;
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $duColor }}-100 text-{{ $duColor }}-800">
                                                {{ $duLabel }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Belum Daftar Ulang
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <!-- Verification columns omitted for brevity but preserved in output -->
                                @if($showColumns['verif_rapor'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusRapor = $item->verifikasiBerkas->status_rapor ?? 'belum_upload';
                                            $verifColors = ['belum_upload' => 'gray', 'menunggu_verifikasi' => 'yellow', 'tidak_sesuai' => 'red', 'perlu_perbaikan' => 'orange', 'disetujui' => 'green'];
                                            $verifLabels = ['belum_upload' => 'Belum Upload', 'menunggu_verifikasi' => 'Menunggu', 'tidak_sesuai' => 'Tidak Sesuai', 'perlu_perbaikan' => 'Perlu Perbaikan', 'disetujui' => 'Disetujui'];
                                            $color = $verifColors[$statusRapor] ?? 'gray';
                                            $label = $verifLabels[$statusRapor] ?? $statusRapor;
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_akte'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_akte ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_kk'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_kk ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_nisn'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_nisn ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_ijazah'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_ijazah ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_sertifikat'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_sertifikat ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['verif_kip'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $status = $item->verifikasiBerkas->status_kip ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">{{ $label }}</span>
                                    </td>
                                @endif
                                @if($showColumns['diperbarui'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                @endif
                                @if($showColumns['diperbarui_oleh'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->diperbaruiOleh->name ?? '-' }}
                                    </td>
                                @endif
                                @if($showColumns['tanggal'])
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d M Y') : '-' }}
                                    </td>
                                @endif
                                @if($showColumns['emis_completeness'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $emisBadge = $item->getEmisCompletenessBadge();
                                            $percentage = $emisBadge['percentage'];
                                            $label = $emisBadge['label'];
                                            $color = $emisBadge['color'];
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="font-medium text-gray-700">{{ $percentage }}%</span>
                                                    <span class="text-{{ $color }}-600">{{ $label }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-{{ $color }}-600 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Belum ada data pendaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendaftar->links() }}
            </div>
        </div>
    </div>

    <!-- Script for Password Toggle and Column Filter (Keep existing) -->
    <script>
        function togglePasswordVisibility(passwordFieldId, hashedPassword) {
            const passwordField = document.getElementById(passwordFieldId);
            const button = passwordField.parentNode.querySelector('button');
            const icon = button.querySelector('svg');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordField.value = hashedPassword;
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>`;
            } else {
                passwordField.type = 'password';
                passwordField.value = '••••••••';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        }

        function columnFilter() {
            return {
                open: false,
                init() {
                    this.$nextTick(() => {
                        const savedColumns = localStorage.getItem('pendaftar_column_preferences');
                        if (savedColumns) {
                            try {
                                const columns = JSON.parse(savedColumns);
                                Object.keys(columns).forEach(key => {
                                    this.$wire.set('showColumns.' + key, columns[key]);
                                });
                            } catch (e) { console.error(e); }
                        }
                    });
                },
                toggleColumn(columnName, isChecked) {
                    let savedColumns = {};
                    try {
                        const saved = localStorage.getItem('pendaftar_column_preferences');
                        if (saved) savedColumns = JSON.parse(saved);
                    } catch (e) { console.error(e); }
                    savedColumns[columnName] = isChecked;
                    localStorage.setItem('pendaftar_column_preferences', JSON.stringify(savedColumns));
                }
            }
        }
    </script>

    <!-- Change Password Modal (Keep existing) -->
    <div x-data="{ show: @entangle('isChangePasswordModalOpen') }" x-show="show" x-cloak>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeChangePasswordModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]" role="dialog" aria-modal="true" aria-labelledby="change-password-modal">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="change-password-modal">
                            Ubah Password Pendaftar
                        </h3>
                        <div class="space-y-4" x-data="{ showPassword: false, showConfirmPassword: false }">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        wire:model="new_password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 pr-10"
                                        placeholder="Masukkan password baru"
                                    >
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        wire:model="new_password_confirmation"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 pr-10"
                                        placeholder="Konfirmasi password baru"
                                    >
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('new_password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="updatePassword"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            type="button"
                            wire:click="closeChangePasswordModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>