<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pendaftar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_pendaftar']) }}</p>
                    <p class="text-sm {{ $stats['total_pendaftar_increase'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        {{ $stats['total_pendaftar_increase'] >= 0 ? '+' : '' }}{{ $stats['total_pendaftar_increase'] }}% dari tahun lalu
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['menunggu_verifikasi']) }}</p>
                    <p class="text-sm text-yellow-600 mt-2">{{ $stats['menunggu_verifikasi'] > 0 ? 'Perlu ditindaklanjuti' : 'Semua terverifikasi' }}</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Diterima -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Diterima</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['diterima']) }}</p>
                    <p class="text-sm text-green-600 mt-2">{{ $stats['diterima_percentage'] }}% dari total</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Panitia Aktif -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Panitia Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['panitia_aktif']) }}</p>
                    <p class="text-sm text-gray-600 mt-2">Tim {{ $appAcronym }} 2025</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pendaftar Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pendaftar Terbaru</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                @if($recentPendaftar->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pendaftaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentPendaftar as $pendaftar)
                                @php
                                    $avatarColors = [
                                        'blue' => 'bg-blue-100 text-blue-600',
                                        'indigo' => 'bg-indigo-100 text-indigo-600',
                                        'emerald' => 'bg-emerald-100 text-emerald-600',
                                        'purple' => 'bg-purple-100 text-purple-600',
                                        'yellow' => 'bg-yellow-100 text-yellow-600',
                                        'red' => 'bg-red-100 text-red-600',
                                        'orange' => 'bg-orange-100 text-orange-600',
                                    ];
                                    $jalurBadge = [
                                        'blue' => 'bg-blue-100 text-blue-800',
                                        'indigo' => 'bg-indigo-100 text-indigo-800',
                                        'emerald' => 'bg-emerald-100 text-emerald-800',
                                        'purple' => 'bg-purple-100 text-purple-800',
                                        'yellow' => 'bg-yellow-100 text-yellow-800',
                                        'red' => 'bg-red-100 text-red-800',
                                        'orange' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $statusBadge = [
                                        'yellow' => 'bg-yellow-100 text-yellow-800',
                                        'blue' => 'bg-blue-100 text-blue-800',
                                        'green' => 'bg-green-100 text-green-800',
                                        'red' => 'bg-red-100 text-red-800',
                                        'gray' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $pendaftar['nomor_pendaftaran'] ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 {{ $avatarColors[$pendaftar['jalur_warna']] ?? 'bg-emerald-100 text-emerald-600' }} rounded-full flex items-center justify-center">
                                                <span class="font-semibold">{{ $pendaftar['initials'] }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $pendaftar['nama'] }}</div>
                                                <div class="text-sm text-gray-500">NISN: {{ $pendaftar['nisn'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jalurBadge[$pendaftar['jalur_warna']] ?? 'bg-blue-100 text-blue-800' }}">
                                            {{ $pendaftar['jalur'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge[$pendaftar['status_color']] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $pendaftar['status_label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $pendaftar['tanggal'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Belum ada pendaftar</p>
                    </div>
                @endif
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="/admin/pendaftar" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    Lihat semua pendaftar →
                </a>
            </div>
        </div>

        <!-- Aktivitas & Quick Actions -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Aksi Cepat</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <a href="/admin/pendaftar/tambah" class="flex items-center px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Tambah Pendaftar</span>
                    </a>
                    <a href="/admin/pengumuman/buat" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Buat Pengumuman</span>
                    </a>
                    <a href="/admin/laporan/export" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Export Laporan</span>
                    </a>
                </div>
            </div>

            <!-- Statistik Jalur -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Jalur</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if(count($jalurStats) > 0)
                        <div class="space-y-4">
                            @foreach($jalurStats as $jalur)
                                @php
                                    $barColors = [
                                        'blue' => 'bg-blue-600',
                                        'indigo' => 'bg-indigo-600',
                                        'emerald' => 'bg-emerald-600',
                                        'purple' => 'bg-purple-600',
                                        'yellow' => 'bg-yellow-600',
                                        'red' => 'bg-red-600',
                                        'orange' => 'bg-orange-600',
                                    ];
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">{{ $jalur['nama'] }}</span>
                                        <span class="font-semibold text-gray-900">{{ number_format($jalur['count']) }} ({{ $jalur['percentage'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $barColors[$jalur['warna']] ?? 'bg-blue-600' }} h-2 rounded-full" style="width: {{ $jalur['percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada data jalur pendaftaran</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- EMIS Completeness Dashboard -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-blue-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Kelengkapan Data EMIS Kemenag 4.0</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                        <p class="text-xs text-gray-900 opacity-90">Rata-rata Kelengkapan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $emisCompletenessStats['average_completion'] ?? 0 }}%</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-lg">
                        <p class="text-xs font-medium text-green-800 uppercase tracking-wide">Lengkap (≥80%)</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $emisCompletenessStats['lengkap'] ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ $emisCompletenessStats['lengkap_percentage'] ?? 0 }}% dari total</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-r-lg">
                        <p class="text-xs font-medium text-yellow-800 uppercase tracking-wide">Sebagian (50-79%)</p>
                        <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $emisCompletenessStats['sebagian'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-600 mt-1">Perlu dilengkapi</p>
                    </div>
                    <div class="border-l-4 border-orange-500 bg-orange-50 p-4 rounded-r-lg">
                        <p class="text-xs font-medium text-orange-800 uppercase tracking-wide">Kurang Lengkap (1-49%)</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">{{ $emisCompletenessStats['kurang_lengkap'] ?? 0 }}</p>
                        <p class="text-xs text-orange-600 mt-1">Segera lengkapi</p>
                    </div>
                    <div class="border-l-4 border-red-500 bg-red-50 p-4 rounded-r-lg">
                        <p class="text-xs font-medium text-red-800 uppercase tracking-wide">Belum Diisi</p>
                        <p class="text-2xl font-bold text-red-900 mt-1">{{ $emisCompletenessStats['belum_diisi'] ?? 0 }}</p>
                        <p class="text-xs text-red-600 mt-1">Perhatian khusus</p>
                    </div>
                </div>

                <!-- Section Breakdown -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Kelengkapan Per Seksi Data</h4>
                    @if(isset($emisCompletenessStats['section_averages']) && count($emisCompletenessStats['section_averages']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($emisCompletenessStats['section_averages'] as $key => $section)
                                @php
                                    $percentage = $section['average'];
                                    if ($percentage >= 80) {
                                        $bgColor = 'bg-green-50';
                                        $borderColor = 'border-green-200';
                                        $textColor = 'text-green-900';
                                        $barColor = 'bg-green-600';
                                    } elseif ($percentage >= 50) {
                                        $bgColor = 'bg-yellow-50';
                                        $borderColor = 'border-yellow-200';
                                        $textColor = 'text-yellow-900';
                                        $barColor = 'bg-yellow-600';
                                    } elseif ($percentage > 0) {
                                        $bgColor = 'bg-orange-50';
                                        $borderColor = 'border-orange-200';
                                        $textColor = 'text-orange-900';
                                        $barColor = 'bg-orange-600';
                                    } else {
                                        $bgColor = 'bg-red-50';
                                        $borderColor = 'border-red-200';
                                        $textColor = 'text-red-900';
                                        $barColor = 'bg-red-600';
                                    }
                                @endphp
                                <div class="{{ $bgColor }} {{ $borderColor }} border p-4 rounded-lg">
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="text-xs font-medium {{ $textColor }}">{{ $section['label'] }}</p>
                                        <span class="text-sm font-bold {{ $textColor }}">{{ $percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-white bg-opacity-60 rounded-full h-2">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada data kelengkapan EMIS</p>
                    @endif
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data EMIS diperlukan untuk pelaporan ke Kementerian Agama
                    </p>
                    <a href="/admin/pendaftar" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                        Lihat Detail Pendaftar →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
