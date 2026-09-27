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
                    wire:click="switchTab('daftar_ulang_seragam')"
                    class="{{$activeTab === 'daftar_ulang_seragam' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👔 Daftar Ulang Seragam
                </button>
                <button
                    wire:click="switchTab('seragam')"
                    class="{{$activeTab === 'seragam' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👕 Seragam & Ukuran
                </button>
                <button
                    wire:click="switchTab('tes_psikologi')"
                    class="{{$activeTab === 'tes_psikologi' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    🧠 Tes Psikologi
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
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
            </div>
            <div class="flex justify-end">
                <button wire:click="resetFilters" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset Filter
                </button>
            </div>
        </div>

        <!-- Stats Overview - Status Pendaftaran (Same as Pendaftar page) -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['menunggu_verifikasi'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lengkapi Data -->
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Lengkapi Data</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['lengkapi_data'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perbaiki Data -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Perbaiki Data</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['perbaiki_data'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Data Ditolak -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Data Ditolak</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['data_ditolak'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Data Disetujui -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Data Disetujui</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['data_disetujui'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Per Jalur Statistics -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Per Jalur</h3>
                </div>
                <div class="flex gap-2">
                    <button wire:click="exportExcel" class="bg-white hover:bg-gray-100 text-emerald-700 px-4 py-2 rounded-lg flex items-center text-sm font-medium transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Excel
                    </button>
                    <button wire:click="exportPdf" class="bg-white hover:bg-gray-100 text-emerald-700 px-4 py-2 rounded-lg flex items-center text-sm font-medium transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        PDF
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-tight w-40">Jalur</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Kuota</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Total</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Menunggu</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Lengkapi</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Perbaiki</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Ditolak</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Disetujui</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Lulus</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Tdk Lulus</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Cadangan</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">L</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">P</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">MI</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">SD</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-tight w-32">%</th>
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
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                        {{ $jalur->nama }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 text-center font-semibold">{{ number_format($jalur->kuota) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 text-center font-bold">{{ number_format($jalur->pendaftar_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-yellow-600 text-center">{{ number_format($jalur->menunggu_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-orange-600 text-center">{{ number_format($jalur->lengkapi_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-amber-600 text-center">{{ number_format($jalur->perbaiki_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-red-600 text-center font-semibold">{{ number_format($jalur->ditolak_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-green-600 text-center font-semibold">{{ number_format($jalur->disetujui_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-emerald-600 text-center font-semibold">{{ number_format($jalur->lulus_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-rose-600 text-center">{{ number_format($jalur->tidak_lulus_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-blue-600 text-center">{{ number_format($jalur->cadangan_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-cyan-600 text-center">{{ number_format($jalur->laki_laki_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-pink-600 text-center">{{ number_format($jalur->perempuan_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-teal-600 text-center">{{ number_format($jalur->madrasah_count) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-indigo-600 text-center">{{ number_format($jalur->umum_count) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
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

        @if(session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Statistik Per Tahun -->
        <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Per Tahun</h3>
                </div>
                <button wire:click="simpanStatistikTahun" class="bg-white hover:bg-gray-100 text-purple-700 px-4 py-2 rounded-lg flex items-center text-sm font-medium transition-all duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Statistik Tahun Ini
                </button>
            </div>
            <div class="overflow-x-auto">
                @if($statistikTahun->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-tight w-24">Tahun</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Kuota</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Total</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Menunggu</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Lengkapi</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Perbaiki</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Ditolak</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Disetujui</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-16">Lulus</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Tdk Lulus</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-20">Cadangan</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">L</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">P</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">MI</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-tight w-12">SD</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-tight w-32">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($statistikTahun as $stat)
                            @php
                                $percentage = $stat->total_kuota > 0 ? round(($stat->total_pendaftar / $stat->total_kuota) * 100, 1) : 0;
                                $isExpanded = in_array($stat->tahun, $expandedYears);
                            @endphp
                            <tr class="hover:bg-gray-50 cursor-pointer" wire:click="toggleTahunDetail({{ $stat->tahun }})">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 transition-transform {{ $isExpanded ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span class="text-sm font-bold text-purple-700">{{ $stat->tahun }}</span>
                                    </div>
                                </td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 text-center font-semibold">{{ number_format($stat->total_kuota) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-gray-900 text-center font-bold">{{ number_format($stat->total_pendaftar) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-yellow-600 text-center">{{ number_format($stat->menunggu) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-orange-600 text-center">{{ number_format($stat->lengkapi) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-amber-600 text-center">{{ number_format($stat->perbaiki) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-red-600 text-center font-semibold">{{ number_format($stat->ditolak) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-green-600 text-center font-semibold">{{ number_format($stat->disetujui) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-emerald-600 text-center font-semibold">{{ number_format($stat->lulus) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-rose-600 text-center">{{ number_format($stat->tidak_lulus) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-blue-600 text-center">{{ number_format($stat->cadangan) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-cyan-600 text-center">{{ number_format($stat->laki_laki) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-pink-600 text-center">{{ number_format($stat->perempuan) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-teal-600 text-center">{{ number_format($stat->madrasah) }}</td>
                                <td class="px-2 py-3 whitespace-nowrap text-sm text-indigo-600 text-center">{{ number_format($stat->umum) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full transition-all duration-300 bg-purple-600" style="width: {{ min($percentage, 100) }}%;"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 min-w-[3.5rem] text-right">{{ $percentage }}%</span>
                                    </div>
                                </td>
                            </tr>

                            @if($isExpanded && $stat->jalurStats->count() > 0)
                                <!-- Detail Per Jalur untuk tahun ini -->
                                <tr>
                                    <td colspan="16" class="px-4 py-4 bg-gray-50">
                                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Detail Per Jalur Tahun {{ $stat->tahun }}</h4>
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jalur</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Kuota</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Menunggu</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lengkapi</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Perbaiki</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Ditolak</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Disetujui</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lulus</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Tdk Lulus</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Cadangan</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">L</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">P</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">MI</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">SD</th>
                                                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    @foreach($stat->jalurStats as $jalurStat)
                                                        @php
                                                            $jalurPercentage = $jalurStat->kuota > 0 ? round(($jalurStat->total / $jalurStat->kuota) * 100, 1) : 0;
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
                                                            $warna = $jalurStat->warna ?? 'blue';
                                                            $colors = $colorMap[$warna] ?? $colorMap['blue'];
                                                        @endphp
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <span class="px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                                                                    {{ $jalurStat->nama_jalur }}
                                                                </span>
                                                            </td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900 text-center font-semibold">{{ number_format($jalurStat->kuota) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-gray-900 text-center font-bold">{{ number_format($jalurStat->total) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-yellow-600 text-center">{{ number_format($jalurStat->menunggu) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-orange-600 text-center">{{ number_format($jalurStat->lengkapi) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-amber-600 text-center">{{ number_format($jalurStat->perbaiki) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-red-600 text-center font-semibold">{{ number_format($jalurStat->ditolak) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-green-600 text-center font-semibold">{{ number_format($jalurStat->disetujui) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-emerald-600 text-center font-semibold">{{ number_format($jalurStat->lulus) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-rose-600 text-center">{{ number_format($jalurStat->tidak_lulus) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-blue-600 text-center">{{ number_format($jalurStat->cadangan) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-cyan-600 text-center">{{ number_format($jalurStat->laki_laki) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-pink-600 text-center">{{ number_format($jalurStat->perempuan) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-teal-600 text-center">{{ number_format($jalurStat->madrasah) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-indigo-600 text-center">{{ number_format($jalurStat->umum) }}</td>
                                                            <td class="px-2 py-2 whitespace-nowrap text-xs text-center">
                                                                <div class="flex items-center gap-1">
                                                                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                                                        <div class="h-1.5 rounded-full transition-all duration-300" style="width: {{ min($jalurPercentage, 100) }}%; background-color: {{ $colors['bar'] }};"></div>
                                                                    </div>
                                                                    <span class="text-xs font-semibold text-gray-900 min-w-[2.5rem] text-right">{{ $jalurPercentage }}%</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-lg font-medium mb-2">Belum Ada Data Statistik Tahun</p>
                    <p class="text-sm">Klik tombol "Simpan Statistik Tahun Ini" untuk menyimpan data statistik pertama</p>
                </div>
                @endif
            </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Kelulusan</label>
                        <select wire:model.live="filterStatusKelulusan" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="lulus">Lulus</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                            <option value="cadangan">Cadangan</option>
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgress: false, progress: 0, elapsed: 0, stage: '', totalRecords: 0 }">
                        <button @click="showProgress = true; progress = 0; elapsed = 0; stage = 'Memulai proses export...'; totalRecords = {{ $dataLengkapStats['data_siswa'] ?? 0 }}; $wire.exportDataLengkap()"
                                :disabled="showProgress"
                                :class="{ 'opacity-50 cursor-wait': showProgress }"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgress" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgress" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgress ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal -->
                        <div x-show="showProgress"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-50 overflow-y-auto"
                             style="display: none;"
                             x-init="
                                $watch('showProgress', value => {
                                    if (value) {
                                        let startTime = Date.now();
                                        let interval = setInterval(() => {
                                            elapsed = Math.floor((Date.now() - startTime) / 1000);

                                            // Simulate progress stages
                                            if (progress < 20) {
                                                stage = 'Mempersiapkan data...';
                                                progress += 2;
                                            } else if (progress < 40) {
                                                stage = 'Mengambil data pendaftar (' + totalRecords + ' records)...';
                                                progress += 1.5;
                                            } else if (progress < 60) {
                                                stage = 'Memproses data formulir...';
                                                progress += 1;
                                            } else if (progress < 80) {
                                                stage = 'Menyusun struktur Excel...';
                                                progress += 1;
                                            } else if (progress < 95) {
                                                stage = 'Generating file Excel...';
                                                progress += 0.5;
                                            } else {
                                                stage = 'Menyelesaikan export...';
                                                if (progress < 99) progress += 0.2;
                                            }

                                            // Auto close after download starts (assumed at 100%)
                                            if (progress >= 100) {
                                                setTimeout(() => {
                                                    showProgress = false;
                                                    progress = 0;
                                                    elapsed = 0;
                                                    clearInterval(interval);
                                                }, 1500);
                                            }
                                        }, 200);

                                        // Fallback: close after 30 seconds
                                        setTimeout(() => {
                                            showProgress = false;
                                            progress = 0;
                                            elapsed = 0;
                                            clearInterval(interval);
                                        }, 30000);
                                    }
                                });
                             ">
                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                <!-- Background overlay -->
                                <div class="fixed inset-0 z-40 transition-opacity bg-gray-500/75 " @click="showProgress = false"></div>

                                <!-- Modal panel -->
                                <div class="relative z-50 inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                    <!-- Icon -->
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 mb-4">
                                        <svg class="h-8 w-8 text-purple-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>

                                    <!-- Title -->
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2">
                                            Proses Export Data
                                        </h3>
                                        <p class="text-sm text-gray-500" x-text="stage"></p>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-purple-600" x-text="Math.floor(progress) + '%'"></span>
                                            <span class="text-sm text-gray-500">
                                                <span x-text="Math.floor(elapsed / 60)"></span>:<span x-text="(elapsed % 60).toString().padStart(2, '0')"></span>
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden shadow-inner">
                                            <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-300 ease-out flex items-center justify-end pr-2"
                                                 :style="'width: ' + Math.floor(progress) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progress > 10" x-text="Math.floor(progress) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info -->
                                    <div class="bg-purple-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="text-sm text-purple-800">
                                                <p class="font-semibold mb-1">Sedang memproses data...</p>
                                                <p class="text-xs">Download akan dimulai otomatis setelah proses selesai. Harap tunggu dan jangan tutup halaman ini.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stats -->
                                    <div class="grid grid-cols-3 gap-3 mb-4">
                                        <div class="bg-blue-50 rounded-lg p-3 text-center">
                                            <p class="text-xs text-blue-600 font-medium mb-1">Total Data</p>
                                            <p class="text-lg font-bold text-blue-700" x-text="totalRecords"></p>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3 text-center">
                                            <p class="text-xs text-green-600 font-medium mb-1">Progress</p>
                                            <p class="text-lg font-bold text-green-700" x-text="Math.floor(progress) + '%'"></p>
                                        </div>
                                        <div class="bg-orange-50 rounded-lg p-3 text-center">
                                            <p class="text-xs text-orange-600 font-medium mb-1">Waktu</p>
                                            <p class="text-lg font-bold text-orange-700">
                                                <span x-text="elapsed"></span>s
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Close Button (hidden until near completion) -->
                                    <div x-show="progress >= 95" class="mt-4">
                                        <button @click="showProgress = false; progress = 0; elapsed = 0"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:text-sm transition-all">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview Kelengkapan Data -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Statistik Kelengkapan Data Formulir</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <!-- Data Siswa -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Data Siswa</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_siswa'] ?? 0) }}</p>
                        </div>

                        <!-- Data Ayah -->
                        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Data Ayah</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_ayah'] ?? 0) }}</p>
                        </div>

                        <!-- Data Ibu -->
                        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Data Ibu</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_ibu'] ?? 0) }}</p>
                        </div>

                        <!-- Data Wali -->
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Data Wali</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_wali'] ?? 0) }}</p>
                        </div>

                        <!-- Data Alamat Ayah -->
                        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Alamat Ayah</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_alamat_ayah'] ?? 0) }}</p>
                        </div>

                        <!-- Data Alamat Ibu -->
                        <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Alamat Ibu</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_alamat_ibu'] ?? 0) }}</p>
                        </div>

                        <!-- Data Alamat Wali -->
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Alamat Wali</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_alamat_wali'] ?? 0) }}</p>
                        </div>

                        <!-- Data Alamat Siswa -->
                        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Alamat Siswa</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['data_alamat_siswa'] ?? 0) }}</p>
                        </div>

                        <!-- Data Prestasi -->
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Prestasi</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['prestasi'] ?? 0) }}</p>
                        </div>

                        <!-- Data Beasiswa & Bantuan -->
                        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Beasiswa</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['beasiswa'] ?? 0) }}</p>
                        </div>

                        <!-- Pendidikan Lain -->
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Pendidikan Lain</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['pendidikan_lain'] ?? 0) }}</p>
                        </div>

                        <!-- Total Pendaftar -->
                        <div class="bg-gradient-to-br from-slate-500 to-slate-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Total Pendaftar</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['total_pendaftar'] ?? 0) }}</p>
                        </div>

                        <!-- Lulus -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Lulus</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['lulus'] ?? 0) }}</p>
                        </div>

                        <!-- Tidak Lulus -->
                        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Tidak Lulus</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['tidak_lulus'] ?? 0) }}</p>
                        </div>

                        <!-- Cadangan -->
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-4 text-white">
                            <div class="flex items-center justify-between mb-2">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs opacity-90 font-medium">Cadangan</p>
                            <p class="text-2xl font-bold mt-1">{{ number_format($dataLengkapStats['cadangan'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-3 text-gray-800">Data yang akan diexport meliputi (lebih dari 200 kolom):</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Kolom Kiri -->
                        <div>
                            <ul class="list-disc list-inside space-y-1.5 ml-2 text-xs">
                                <li><span class="font-semibold text-gray-700">Identitas Pendaftar:</span> Nomor Pendaftaran, Jalur, Status, Tanggal Daftar</li>
                                <li><span class="font-semibold text-gray-700">Data Siswa Lengkap (32 field):</span> Identitas dasar, Kewarganegaraan (WNI/WNA), Keluarga, Kontak, Pra Sekolah (TK/PAUD), Imunisasi (6 jenis), Nomor KIP/KK, Nama Kepala Keluarga, Asal Sekolah (NSM/NPSN)</li>
                                <li><span class="font-semibold text-gray-700">Data Ayah (15 field):</span> Nama, Status hidup, Kewarganegaraan (WNI/WNA), Identitas (NIK/Izin Tinggal), Tempat/Tanggal Lahir, Pendidikan, Pekerjaan, Penghasilan, Kontak, Nomor KKS/PKH</li>
                                <li><span class="font-semibold text-gray-700">Data Ibu (13 field):</span> Data lengkap seperti Ayah (tanpa KKS/PKH)</li>
                                <li><span class="font-semibold text-gray-700">Data Wali (13 field):</span> Hubungan, Data lengkap Wali (jika ada)</li>
                                <li><span class="font-semibold text-gray-700">Alamat Ayah (10 field):</span> Status tinggal LN, Status kepemilikan rumah, Provinsi, Kab/Kota, Kecamatan, Kel/Desa, RT/RW, Alamat lengkap, Kode Pos</li>
                                <li><span class="font-semibold text-gray-700">Alamat Ibu (11 field):</span> Status sama dengan Ayah, Detail alamat lengkap</li>
                                <li><span class="font-semibold text-gray-700">Alamat Wali (12 field):</span> Status Wali, Detail alamat lengkap</li>
                                <li><span class="font-semibold text-gray-700">Alamat Siswa (13 field):</span> Status tempat tinggal, Detail alamat, Jarak ke sekolah, Transportasi, Waktu tempuh</li>
                            </ul>
                        </div>
                        <!-- Kolom Kanan -->
                        <div>
                            <ul class="list-disc list-inside space-y-1.5 ml-2 text-xs">
                                <li><span class="font-semibold text-gray-700">Prestasi (s.d. 3 prestasi x 7 field = 21 field):</span> Tahun, Nama Lomba, Bidang, Penyelenggara, Tingkat, Peringkat, Kategori</li>
                                <li><span class="font-semibold text-gray-700">Beasiswa & Bantuan (s.d. 3 beasiswa x 7 field = 21 field):</span> Tahun, Kategori, Nama, Jenis Instansi, Nama Instansi, Jangka Waktu, Nominal</li>
                                <li><span class="font-semibold text-gray-700">Pendidikan Lain (s.d. 3 lembaga x 5 field = 15 field):</span> Nama Lembaga, Jenis, Mulai Belajar, Frekuensi, Lokasi</li>
                                <li><span class="font-semibold text-gray-700">Upload Berkas (8 dokumen):</span> Akta Kelahiran, Rapor, KK, NISN, Ijazah, Sertifikat, KIP, Foto</li>
                                <li><span class="font-semibold text-gray-700">Nilai Rapor (10 field):</span> B. Indonesia, Matematika, IPA (Sem 1 & 2 Kelas 5, Sem 1 Kelas 6), Nilai Rata-rata</li>
                                <li><span class="font-semibold text-gray-700">Jawaban Survey (dinamis):</span> Semua pertanyaan survey yang aktif</li>
                                <li><span class="font-semibold text-gray-700">Status (6 field):</span> Status Pendaftaran, Tes Psikologi, Kelulusan, Daftar Ulang Madrasah, Daftar Ulang Seragam, Ukuran Seragam</li>
                                <li><span class="font-semibold text-gray-700">Tanggal (2 field):</span> Tanggal Verifikasi, Tanggal Input Data</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded">
                        <p class="text-xs text-blue-800 flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong>Total kolom:</strong> Lebih dari 200 kolom data lengkap mencakup semua aspek formulir PPDB. Data yang diexport akan disesuaikan dengan filter Jalur Pendaftaran dan Status Kelulusan yang aktif.</span>
                        </p>
                    </div>
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
                    Laporan ini menampilkan <strong>semua pendaftar lulus</strong> beserta status daftar ulangnya — termasuk yang belum konfirmasi atau tidak melanjutkan — sehingga jumlahnya selalu sama dengan total wajib daftar ulang.
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Rekap Daftar Ulang</label>
                        <select wire:model.live="filterStatusDU" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status Rekap</option>
                            @foreach(\App\Support\RekapDaftarUlang::KATEGORI as $kategoriKey => $kategoriInfo)
                                <option value="{{ $kategoriKey }}">{{ $kategoriInfo['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgressDU: false, progressDU: 0, elapsedDU: 0, stageDU: '', totalRecordsDU: 0 }">
                        <button @click="showProgressDU = true; progressDU = 0; elapsedDU = 0; stageDU = 'Memulai proses export...'; totalRecordsDU = 0; $wire.exportDaftarUlang()"
                                :disabled="showProgressDU"
                                :class="{ 'opacity-50 cursor-wait': showProgressDU }"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgressDU" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgressDU" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgressDU ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal Daftar Ulang -->
                        <div x-show="showProgressDU" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                             x-init="$watch('showProgressDU', value => { if (value) { let startTime = Date.now(); let interval = setInterval(() => { elapsedDU = Math.floor((Date.now() - startTime) / 1000); if (progressDU < 20) { stageDU = 'Mempersiapkan data...'; progressDU += 2; } else if (progressDU < 40) { stageDU = 'Mengambil data daftar ulang...'; progressDU += 1.5; } else if (progressDU < 60) { stageDU = 'Memproses data pembayaran...'; progressDU += 1; } else if (progressDU < 80) { stageDU = 'Menyusun struktur Excel...'; progressDU += 1; } else if (progressDU < 95) { stageDU = 'Generating file Excel...'; progressDU += 0.5; } else { stageDU = 'Menyelesaikan export...'; if (progressDU < 99) progressDU += 0.2; } if (progressDU >= 100) { setTimeout(() => { showProgressDU = false; progressDU = 0; elapsedDU = 0; clearInterval(interval); }, 1500); } }, 200); setTimeout(() => { showProgressDU = false; progressDU = 0; elapsedDU = 0; clearInterval(interval); }, 30000); } });">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 z-40 bg-gray-500/75 " @click="showProgressDU = false"></div>
                                <div class="relative z-50 inline-block bg-white rounded-lg px-6 py-6 shadow-xl max-w-lg w-full">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-4">
                                        <svg class="h-8 w-8 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Export Daftar Ulang</h3>
                                        <p class="text-sm text-gray-500" x-text="stageDU"></p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-emerald-600" x-text="Math.floor(progressDU) + '%'"></span>
                                            <span class="text-sm text-gray-500"><span x-text="Math.floor(elapsedDU / 60)"></span>:<span x-text="(elapsedDU % 60).toString().padStart(2, '0')"></span></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.floor(progressDU) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progressDU > 10" x-text="Math.floor(progressDU) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-emerald-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-emerald-800">Download akan dimulai otomatis setelah proses selesai.</p>
                                    </div>
                                    <div x-show="progressDU >= 95">
                                        <button @click="showProgressDU = false" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $daftarUlangStats, 'tab' => 'madrasah'])

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

        @elseif($activeTab === 'daftar_ulang_seragam')
        <!-- Laporan Daftar Ulang Seragam -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-orange-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Daftar Ulang Seragam</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data siswa yang telah melakukan daftar ulang seragam, termasuk status pembayaran dan pemesanan seragam.
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="pembayaran_diterima">Pembayaran Diterima</option>
                            <option value="seragam_disiapkan">Seragam Disiapkan</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgressDUS: false, progressDUS: 0, elapsedDUS: 0, stageDUS: '', totalRecordsDUS: 0 }">
                        <button @click="showProgressDUS = true; progressDUS = 0; elapsedDUS = 0; stageDUS = 'Memulai proses export...'; totalRecordsDUS = 0; $wire.exportDaftarUlangSeragam()"
                                :disabled="showProgressDUS"
                                :class="{ 'opacity-50 cursor-wait': showProgressDUS }"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgressDUS" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgressDUS" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgressDUS ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal Daftar Ulang Seragam -->
                        <div x-show="showProgressDUS" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                             x-init="$watch('showProgressDUS', value => { if (value) { let startTime = Date.now(); let interval = setInterval(() => { elapsedDUS = Math.floor((Date.now() - startTime) / 1000); if (progressDUS < 20) { stageDUS = 'Mempersiapkan data...'; progressDUS += 2; } else if (progressDUS < 40) { stageDUS = 'Mengambil data daftar ulang seragam...'; progressDUS += 1.5; } else if (progressDUS < 60) { stageDUS = 'Memproses data pembayaran...'; progressDUS += 1; } else if (progressDUS < 80) { stageDUS = 'Menyusun struktur Excel...'; progressDUS += 1; } else if (progressDUS < 95) { stageDUS = 'Generating file Excel...'; progressDUS += 0.5; } else { stageDUS = 'Menyelesaikan export...'; if (progressDUS < 99) progressDUS += 0.2; } if (progressDUS >= 100) { setTimeout(() => { showProgressDUS = false; progressDUS = 0; elapsedDUS = 0; clearInterval(interval); }, 1500); } }, 200); setTimeout(() => { showProgressDUS = false; progressDUS = 0; elapsedDUS = 0; clearInterval(interval); }, 30000); } });">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 z-40 bg-gray-500/75 " @click="showProgressDUS = false"></div>
                                <div class="relative z-50 inline-block bg-white rounded-lg px-6 py-6 shadow-xl max-w-lg w-full">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                                        <svg class="h-8 w-8 text-orange-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Export Daftar Ulang Seragam</h3>
                                        <p class="text-sm text-gray-500" x-text="stageDUS"></p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-orange-600" x-text="Math.floor(progressDUS) + '%'"></span>
                                            <span class="text-sm text-gray-500"><span x-text="Math.floor(elapsedDUS / 60)"></span>:<span x-text="(elapsedDUS % 60).toString().padStart(2, '0')"></span></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.floor(progressDUS) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progressDUS > 10" x-text="Math.floor(progressDUS) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-orange-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-orange-800">Download akan dimulai otomatis setelah proses selesai.</p>
                                    </div>
                                    <div x-show="progressDUS >= 95">
                                        <button @click="showProgressDUS = false" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $seragamStats, 'tab' => 'seragam'])

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa</li>
                        <li>Paket Seragam</li>
                        <li>Total Pembayaran</li>
                        <li>Status Pembayaran Seragam</li>
                        <li>Tanggal Pembayaran</li>
                        <li>Bukti Pembayaran</li>
                        <li>Tanggal Verifikasi</li>
                        <li>Catatan</li>
                    </ul>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'seragam')
        <!-- Laporan Seragam & Ukuran -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Seragam & Ukuran</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data ukuran seragam siswa baru yang telah diterima, untuk keperluan pemesanan seragam.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ukuran</label>
                        <select wire:model.live="filterStatusSeragam" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan</option>
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgressSR: false, progressSR: 0, elapsedSR: 0, stageSR: '', totalRecordsSR: 0 }">
                        <button @click="showProgressSR = true; progressSR = 0; elapsedSR = 0; stageSR = 'Memulai proses export...'; totalRecordsSR = 0; $wire.exportSeragam()"
                                :disabled="showProgressSR"
                                :class="{ 'opacity-50 cursor-wait': showProgressSR }"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgressSR" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgressSR" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgressSR ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal Seragam -->
                        <div x-show="showProgressSR" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                             x-init="$watch('showProgressSR', value => { if (value) { let startTime = Date.now(); let interval = setInterval(() => { elapsedSR = Math.floor((Date.now() - startTime) / 1000); if (progressSR < 20) { stageSR = 'Mempersiapkan data...'; progressSR += 2; } else if (progressSR < 40) { stageSR = 'Mengambil data seragam...'; progressSR += 1.5; } else if (progressSR < 60) { stageSR = 'Memproses ukuran seragam...'; progressSR += 1; } else if (progressSR < 80) { stageSR = 'Menyusun struktur Excel...'; progressSR += 1; } else if (progressSR < 95) { stageSR = 'Generating file Excel...'; progressSR += 0.5; } else { stageSR = 'Menyelesaikan export...'; if (progressSR < 99) progressSR += 0.2; } if (progressSR >= 100) { setTimeout(() => { showProgressSR = false; progressSR = 0; elapsedSR = 0; clearInterval(interval); }, 1500); } }, 200); setTimeout(() => { showProgressSR = false; progressSR = 0; elapsedSR = 0; clearInterval(interval); }, 30000); } });">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 z-40 bg-gray-500/75 " @click="showProgressSR = false"></div>
                                <div class="relative z-50 inline-block bg-white rounded-lg px-6 py-6 shadow-xl max-w-lg w-full">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 mb-4">
                                        <svg class="h-8 w-8 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Export Seragam & Ukuran</h3>
                                        <p class="text-sm text-gray-500" x-text="stageSR"></p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-indigo-600" x-text="Math.floor(progressSR) + '%'"></span>
                                            <span class="text-sm text-gray-500"><span x-text="Math.floor(elapsedSR / 60)"></span>:<span x-text="(elapsedSR % 60).toString().padStart(2, '0')"></span></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.floor(progressSR) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progressSR > 10" x-text="Math.floor(progressSR) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-indigo-800">Download akan dimulai otomatis setelah proses selesai.</p>
                                    </div>
                                    <div x-show="progressSR >= 95">
                                        <button @click="showProgressSR = false" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $ukuranStats, 'tab' => 'ukuran'])

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa (Nama, NISN, Jenis Kelamin, Jalur Pendaftaran)</li>
                        <li>Ukuran Jas</li>
                        <li>Ukuran Baju Olahraga</li>
                        <li>Ukuran Celana Olahraga</li>
                        <li>Ukuran Baju Seragam Harian</li>
                        <li>Ukuran Celana/Rok Harian</li>
                        <li>Ukuran Kopyah (khusus laki-laki)</li>
                        <li>Paket Seragam yang Dipilih</li>
                        <li>Status Ukuran Seragam (Menunggu/Disetujui/Ditolak)</li>
                        <li>Status Pembayaran Seragam</li>
                    </ul>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'tes_psikologi')
        <!-- Filters for Tes Psikologi -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Laporan Tes Psikologi</h3>
                </div>
                <button
                    wire:click="exportTesPsikologi"
                    class="bg-white hover:bg-gray-100 text-purple-600 font-semibold py-2 px-4 rounded-lg flex items-center transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                        <select wire:model.live="filterStatusTesPsikologi" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="belum_upload">Belum Upload</option>
                            <option value="menunggu">Menunggu Verifikasi</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="perbaiki">Perlu Perbaikan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards for Tes Psikologi -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <!-- Total -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Total</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['total'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Belum Upload -->
            <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Belum Upload</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['belum_upload'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Menunggu</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['menunggu'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Disetujui</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['disetujui'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Ditolak</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['ditolak'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perlu Perbaikan -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 font-medium">Perbaikan</p>
                        <p class="text-3xl font-bold mt-1">{{ $tesPsikologiStats['stats']['perbaiki'] }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table: Statistik Per Jalur -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600">
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Tes Psikologi Per Jalur</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur Pendaftaran</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Belum Upload</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Menunggu</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ditolak</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Perbaikan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tesPsikologiStats['jalur_stats'] as $jalurStat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $jalurStat['nama'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-bold text-purple-600">{{ $jalurStat['total'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $jalurStat['belum_upload'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $jalurStat['menunggu'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $jalurStat['disetujui'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $jalurStat['ditolak'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    {{ $jalurStat['perbaiki'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data tes psikologi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @endif
    </div>
</div>
