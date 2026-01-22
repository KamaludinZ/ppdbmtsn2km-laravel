<div>
    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    wire:click="switchTab('pendaftaran')"
                    class="{{$activeTab === 'pendaftaran' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📊 Laporan Pendaftaran
                </button>
                <button
                    wire:click="switchTab('data_lengkap')"
                    class="{{$activeTab === 'data_lengkap' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📝 Data Pendaftar Lengkap
                </button>
                <button
                    wire:click="switchTab('daftar_ulang')"
                    class="{{$activeTab === 'daftar_ulang' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    ✅ Daftar Ulang Madrasah
                </button>
                <button
                    wire:click="switchTab('seragam')"
                    class="{{$activeTab === 'seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👕 Seragam & Ukuran
                </button>
            </nav>
        </div>
    </div>

    <div class="space-y-6">
        @if($activeTab === 'pendaftaran')
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Laporan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" wire:model.live="tanggalMulai" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" wire:model.live="tanggalAkhir" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
                    <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">Semua Jalur</option>
                        @foreach($jalurPendaftaran as $jalur)
                            <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="lulus">Lulus</option>
                        <option value="tidak_lulus">Tidak Lulus</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-gradient-to-br from-gray-500 to-gray-700 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Menunggu Verifikasi</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['menunggu']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Data Disetujui</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['disetujui']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Lulus Seleksi</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['lulus']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Diterima</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['diterima']) }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm font-medium opacity-90">Cadangan</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($stats['cadangan']) }}</p>
            </div>
        </div>

        <!-- Per Jalur Statistics -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Per Jalur</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jalur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pendaftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($jalurStats as $jalur)
                            @php
                                $percentage = $jalur->kuota > 0 ? round(($jalur->pendaftar_count / $jalur->kuota) * 100, 1) : 0;

                                // Color mapping for different jalur
                                $colorMap = [
                                    'emerald' => ['bg' => '#d1fae5', 'text' => '#065f46', 'bar' => '#10b981'],
                                    'green' => ['bg' => '#d1fae5', 'text' => '#065f46', 'bar' => '#10b981'],
                                    'blue' => ['bg' => '#dbeafe', 'text' => '#1e3a8a', 'bar' => '#3b82f6'],
                                    'purple' => ['bg' => '#e9d5ff', 'text' => '#581c87', 'bar' => '#a855f7'],
                                    'pink' => ['bg' => '#fce7f3', 'text' => '#831843', 'bar' => '#ec4899'],
                                    'red' => ['bg' => '#fee2e2', 'text' => '#7f1d1d', 'bar' => '#ef4444'],
                                    'orange' => ['bg' => '#fed7aa', 'text' => '#7c2d12', 'bar' => '#f97316'],
                                    'yellow' => ['bg' => '#fef3c7', 'text' => '#713f12', 'bar' => '#eab308'],
                                    'indigo' => ['bg' => '#e0e7ff', 'text' => '#312e81', 'bar' => '#6366f1'],
                                    'teal' => ['bg' => '#ccfbf1', 'text' => '#134e4a', 'bar' => '#14b8a6'],
                                ];

                                $warna = $jalur->warna ?? 'blue';
                                $colors = $colorMap[$warna] ?? $colorMap['blue'];
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                        {{ $jalur->nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($jalur->kuota) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($jalur->pendaftar_count) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full transition-all duration-300" style="width: {{ min($percentage, 100) }}%; background-color: {{ $colors['bar'] }};"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 min-w-[3.5rem] text-right">{{ $percentage }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="flex justify-end gap-3">
            <button wire:click="exportExcel" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
            <button wire:click="exportPdf" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF
            </button>
        </div>

        @elseif($activeTab === 'data_lengkap')
        <!-- Laporan Data Lengkap Pendaftar -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Data Lengkap Pendaftar</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan seluruh data pendaftar secara lengkap termasuk data pribadi, asal sekolah, prestasi, data orang tua, pendidikan orang tua, dan penghasilan orang tua.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pendaftaran</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diverifikasi">Diverifikasi</option>
                            <option value="lulus">Lulus</option>
                            <option value="diterima">Diterima</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportDataLengkap"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg wire:loading.remove wire:target="exportDataLengkap" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg wire:loading wire:target="exportDataLengkap" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="exportDataLengkap">Export Excel</span>
                            <span wire:loading wire:target="exportDataLengkap">Processing...</span>
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi: Nama, NISN, Tempat/Tanggal Lahir, Jenis Kelamin, Agama, Alamat</li>
                        <li>Data Asal Sekolah: Nama Sekolah, Jenis Sekolah, NSM, NPSN, Alamat Sekolah</li>
                        <li>Data Prestasi: Daftar Prestasi yang Dimiliki</li>
                        <li>Data Orang Tua: Nama Ayah, Nama Ibu, Pekerjaan Ayah/Ibu, Telepon</li>
                        <li>Data Penghasilan: Penghasilan Orang Tua</li>
                        <li>Nilai Rapor: Nilai B.Indo, Matematika, IPA kelas 5-6</li>
                        <li>Status Pendaftaran & Jalur</li>
                    </ul>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'daftar_ulang')
        <!-- Laporan Daftar Ulang Madrasah -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Daftar Ulang Madrasah</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data siswa yang telah melakukan daftar ulang madrasah, termasuk status pembayaran dan kelengkapan berkas.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Daftar Ulang</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="sudah_daftar_ulang">Sudah Daftar Ulang</option>
                            <option value="belum_daftar_ulang">Belum Daftar Ulang</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportDaftarUlang"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg wire:loading.remove wire:target="exportDaftarUlang" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg wire:loading wire:target="exportDaftarUlang" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="exportDaftarUlang">Export Excel</span>
                            <span wire:loading wire:target="exportDaftarUlang">Processing...</span>
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa</li>
                        <li>Status Daftar Ulang</li>
                        <li>Tanggal Daftar Ulang</li>
                        <li>Status Pembayaran Daftar Ulang</li>
                        <li>Bukti Pembayaran</li>
                        <li>Status Pernyataan</li>
                        <li>Kelengkapan Berkas</li>
                    </ul>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'seragam')
        <!-- Laporan Seragam & Ukuran -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Seragam & Ukuran</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data ukuran seragam siswa baru yang telah diterima, untuk keperluan pemesanan seragam.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <select wire:model.live="filterJenisKelamin" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportSeragam"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-wait"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg wire:loading.remove wire:target="exportSeragam" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg wire:loading wire:target="exportSeragam" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="exportSeragam">Export Excel</span>
                            <span wire:loading wire:target="exportSeragam">Processing...</span>
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa (Nama, NISN, Jenis Kelamin)</li>
                        <li>Ukuran Seragam Atas (S, M, L, XL, XXL, XXXL)</li>
                        <li>Ukuran Seragam Bawah (Celana/Rok)</li>
                        <li>Ukuran Sepatu</li>
                        <li>Ukuran Atribut Lainnya (Topi, Dasi, dll)</li>
                        <li>Status Pembayaran Seragam</li>
                        <li>Tanggal Pemesanan</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
