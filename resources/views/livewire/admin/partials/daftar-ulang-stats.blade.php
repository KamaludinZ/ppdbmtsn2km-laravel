<!-- Stats Overview Cards -->
@if(!empty($stats))
@php
    $tab = $tab ?? 'madrasah';
    $isMadrasah = $tab === 'madrasah';
    $isSeragam = $tab === 'seragam';
    $isUkuran = $tab === 'ukuran';

    // Determine grid layout based on tab
    if ($isMadrasah) {
        $gridClass = 'grid-cols-1 md:grid-cols-3 lg:grid-cols-9'; // 9 cards in 1 row (added Lulus card)
    } elseif ($isSeragam) {
        $gridClass = 'grid-cols-1 md:grid-cols-3 lg:grid-cols-9'; // 9 cards in 1 row (added Lulus card)
    } elseif ($isUkuran) {
        $gridClass = 'grid-cols-1 md:grid-cols-3 lg:grid-cols-6'; // 6 cards in 1 row (added Lulus card)
    } else {
        $gridClass = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'; // default 4 cols
    }
@endphp
<div class="grid {{ $gridClass }} gap-4 mb-6">
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
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pendaftar'] ?? $stats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Lulus Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Lulus</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['lulus'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    @if($isMadrasah)
    <!-- Status Setuju Daftar Ulang Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Setuju Daftar Ulang</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['setuju'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Tidak Setuju Daftar Ulang Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Tidak Setuju Daftar Ulang</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['tidak_setuju'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Status Menunggu Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['menunggu'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Disetujui Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['disetujui'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Ditolak Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Ditolak</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['ditolak'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Perlu Perbaikan Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Perlu Perbaikan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['perlu_perbaikan'] ?? $stats['perbaiki'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    @if($isMadrasah)
    <!-- Status Upload Surat Pernyataan Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Upload Surat Pernyataan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['upload_surat'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($isSeragam)
    <!-- Status Ukuran Menunggu Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Ukuran Menunggu</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['ukuran_menunggu'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Ukuran Disetujui Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Ukuran Disetujui</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['ukuran_disetujui'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Status Ukuran Ditolak Card -->
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Ukuran Ditolak</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['ukuran_ditolak'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endif
