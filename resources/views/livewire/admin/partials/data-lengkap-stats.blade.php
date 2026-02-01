<!-- Data Lengkap Stats Overview - 2 Row Layout -->
@if(!empty($stats))

<!-- Row 1: Status Kelulusan Stats -->
<div class="mb-4">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Status Kelulusan</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Pendaftar Card -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pendaftar'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Lulus Card -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['lulus'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Tidak Lulus Card -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Tidak Lulus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['tidak_lulus'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Cadangan Card -->
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Cadangan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['cadangan'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Data Completeness Stats -->
<div>
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Kelengkapan Data</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- Data Siswa Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Data Siswa</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_siswa'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Ayah Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Data Ayah</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_ayah'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Ibu Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Data Ibu</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_ibu'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Wali Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Data Wali</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_wali'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Asal Sekolah Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Asal Sekolah</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_asal_sekolah'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Prestasi Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Prestasi</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_prestasi'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Beasiswa Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Beasiswa</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_beasiswa'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Nilai Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Nilai</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_nilai'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Kesehatan Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Kesehatan</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_kesehatan'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Pernyataan Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Pernyataan</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_pernyataan'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Data Berkas Card -->
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
            <div class="text-center">
                <div class="w-10 h-10 bg-lime-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">Berkas</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['data_berkas'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

@endif
