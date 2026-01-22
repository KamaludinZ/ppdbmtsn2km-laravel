<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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

        <!-- Lengkapi Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Lengkapi Data</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['lengkapi_data']) }}</p>
                    <p class="text-sm text-orange-600 mt-2">{{ $stats['lengkapi_data_percentage'] }}% dari total</p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Perbaiki Data -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Perbaiki Data</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['perbaiki_data']) }}</p>
                    <p class="text-sm text-red-600 mt-2">{{ $stats['perbaiki_data_percentage'] }}% dari total</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Data Disetujui -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Data Disetujui</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['data_disetujui']) }}</p>
                    <p class="text-sm text-green-600 mt-2">{{ $stats['data_disetujui_percentage'] }}% dari total</p>
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
                                        'emerald' => 'bg-emerald-100 text-emerald-600',
                                        'purple' => 'bg-purple-100 text-purple-600',
                                        'yellow' => 'bg-yellow-100 text-yellow-600',
                                        'red' => 'bg-red-100 text-red-600',
                                        'orange' => 'bg-orange-100 text-orange-600',
                                    ];
                                    $jalurBadge = [
                                        'blue' => 'bg-blue-100 text-blue-800',
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
                <a href="/panitia/pendaftar" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
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
                    <a href="/panitia/pendaftar" class="flex items-center px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Lihat Data Pendaftar</span>
                    </a>
                    <a href="/panitia/kelulusan" class="flex items-center px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Kelola Kelulusan</span>
                    </a>
                    <a href="/panitia/laporan" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Lihat Laporan</span>
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
</div>
