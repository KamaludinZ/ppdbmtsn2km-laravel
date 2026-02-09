<x-layouts.public title="Pengumuman - MTsN 2 Kota Malang">
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Pengumuman</span>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Pengumuman {{ $appAcronym }}</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Informasi terbaru seputar {{ $appFullName }} MTsN 2 Kota Malang
                </p>
            </div>
        </div>
    </section>

    <!-- Important Announcements Section -->
    @if($pengumumanPenting->count() > 0)
    <section class="py-16 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                @foreach($pengumumanPenting as $pengumuman)
                <div class="bg-white rounded-2xl shadow-xl border-l-8 border-green-500 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    <div class="p-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            @php
                                $kategoriBadge = [
                                    'umum' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Umum'],
                                    'jadwal' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Jadwal'],
                                    'pengumuman_lulus' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Pengumuman Lulus'],
                                ];
                                $badge = $kategoriBadge[$pengumuman->kategori] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($pengumuman->kategori)];
                            @endphp
                            <span class="px-4 py-2 {{ $badge['bg'] }} {{ $badge['text'] }} text-sm font-semibold rounded-full">
                                {{ $badge['label'] }}
                            </span>
                            <div class="flex items-center text-gray-600 text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($pengumuman->tanggal_publikasi)->isoFormat('D MMMM Y') }}
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $pengumuman->judul }}</h2>
                        <div class="text-gray-700 leading-relaxed prose prose-emerald max-w-none trix-content">
                            {!! \App\Helpers\TextFormatter::formatPengumuman($pengumuman->konten) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Info Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-2xl p-8 md:p-12 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Dapatkan Informasi Terbaru</h2>
                <p class="text-purple-100 mb-8 max-w-2xl mx-auto">
                    Pantau terus halaman pengumuman ini untuk mendapatkan informasi terbaru seputar {{ $appAcronym }} MTsN 2 Kota Malang
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/daftar" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-purple-600 bg-white hover:bg-gray-50 transition shadow-lg">
                        Daftar Sekarang
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="/jadwal" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-purple-700 transition">
                        Lihat Jadwal
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
