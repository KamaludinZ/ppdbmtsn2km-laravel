<x-layouts.public title="Jalur Pendaftaran - {{ $appAcronym }} MTsN 2 Kota Malang">
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Jalur Pendaftaran</span>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Jalur Pendaftaran {{ $appAcronym }}</h1>
                <p class="text-xl text-emerald-100 max-w-2xl mx-auto">
                    Pilih jalur yang sesuai dengan kualifikasi dan potensi Anda
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($jalurPendaftaran->count() > 0)
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-emerald-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-600">Total Jalur Aktif</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $jalurPendaftaran->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-blue-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-600">Total Kuota</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $jalurPendaftaran->sum('kuota') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-purple-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-600">Nilai Minimum Terendah</p>
                                <p class="text-3xl font-bold text-gray-900">{{ number_format($jalurPendaftaran->min('nilai_minimum'), 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jalur Cards -->
                <div class="space-y-8">
                    @foreach($jalurPendaftaran as $index => $jalur)
                        @php
                            $colorClasses = [
                                'blue' => [
                                    'border' => 'border-blue-200',
                                    'borderHover' => 'hover:border-blue-400',
                                    'bg' => 'from-blue-500 to-blue-600',
                                    'bgLight' => 'bg-blue-50',
                                    'text' => 'text-blue-600',
                                    'textDark' => 'text-blue-800',
                                    'button' => 'bg-blue-600 hover:bg-blue-700',
                                ],
                                'emerald' => [
                                    'border' => 'border-emerald-200',
                                    'borderHover' => 'hover:border-emerald-400',
                                    'bg' => 'from-emerald-500 to-emerald-600',
                                    'bgLight' => 'bg-emerald-50',
                                    'text' => 'text-emerald-600',
                                    'textDark' => 'text-emerald-800',
                                    'button' => 'bg-emerald-600 hover:bg-emerald-700',
                                ],
                                'purple' => [
                                    'border' => 'border-purple-200',
                                    'borderHover' => 'hover:border-purple-400',
                                    'bg' => 'from-purple-500 to-purple-600',
                                    'bgLight' => 'bg-purple-50',
                                    'text' => 'text-purple-600',
                                    'textDark' => 'text-purple-800',
                                    'button' => 'bg-purple-600 hover:bg-purple-700',
                                ],
                                'indigo' => [
                                    'border' => 'border-indigo-200',
                                    'borderHover' => 'hover:border-indigo-400',
                                    'bg' => 'from-indigo-500 to-indigo-600',
                                    'bgLight' => 'bg-indigo-50',
                                    'text' => 'text-indigo-600',
                                    'textDark' => 'text-indigo-800',
                                    'button' => 'bg-indigo-600 hover:bg-indigo-700',
                                ],
                                'orange' => [
                                    'border' => 'border-orange-200',
                                    'borderHover' => 'hover:border-orange-400',
                                    'bg' => 'from-orange-500 to-orange-600',
                                    'bgLight' => 'bg-orange-50',
                                    'text' => 'text-orange-600',
                                    'textDark' => 'text-orange-800',
                                    'button' => 'bg-orange-600 hover:bg-orange-700',
                                ],
                                'teal' => [
                                    'border' => 'border-teal-200',
                                    'borderHover' => 'hover:border-teal-400',
                                    'bg' => 'from-teal-500 to-teal-600',
                                    'bgLight' => 'bg-teal-50',
                                    'text' => 'text-teal-600',
                                    'textDark' => 'text-teal-800',
                                    'button' => 'bg-teal-600 hover:bg-teal-700',
                                ],
                            ];
                            $colors = $colorClasses[$jalur->warna] ?? $colorClasses['blue'];
                            $isPopular = $jalur->kode === 'reguler';
                        @endphp

                        <!-- Jalur Card -->
                        <div class="bg-white border-2 {{ $colors['border'] }} {{ $colors['borderHover'] }} rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 {{ $isPopular ? 'ring-4 ring-emerald-100' : '' }}">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                                <!-- Left Section - Icon & Title -->
                                <div class="lg:col-span-1 p-8 {{ $colors['bgLight'] }} rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none border-b-2 lg:border-b-0 lg:border-r-2 {{ $colors['border'] }}">
                                    <div class="flex flex-col items-center text-center h-full justify-center">
                                        <div class="w-20 h-20 bg-gradient-to-br {{ $colors['bg'] }} rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                        </div>
                                        @if($isPopular)
                                            <div class="inline-block bg-emerald-600 text-white text-xs font-bold px-4 py-1 rounded-full mb-4">
                                                PALING POPULER
                                            </div>
                                        @endif
                                        <h3 class="text-2xl font-bold {{ $colors['textDark'] }} mb-2">{{ $jalur->nama }}</h3>
                                        <div class="flex flex-wrap justify-center gap-2 mt-4">
                                            <span class="inline-flex items-center bg-white border {{ $colors['border'] }} {{ $colors['text'] }} text-sm font-semibold px-3 py-1 rounded-full">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Min: {{ number_format($jalur->nilai_minimum, 0) }}
                                            </span>
                                            @if($jalur->kuota > 0)
                                                <span class="inline-flex items-center bg-white border {{ $colors['border'] }} {{ $colors['text'] }} text-sm font-semibold px-3 py-1 rounded-full">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    {{ $jalur->kuota }} Siswa
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Section - Details -->
                                <div class="lg:col-span-2 p-8">
                                    <p class="text-gray-700 text-lg mb-6 leading-relaxed">
                                        {{ $jalur->deskripsi }}
                                    </p>

                                    @if($jalur->persyaratan && is_array($jalur->persyaratan) && count($jalur->persyaratan) > 0)
                                    <div class="mb-6">
                                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                            <svg class="w-5 h-5 {{ $colors['text'] }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Persyaratan:
                                        </h4>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($jalur->persyaratan as $syarat)
                                            <li class="flex items-start">
                                                <svg class="w-5 h-5 {{ $colors['text'] }} mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-gray-800 text-sm">{{ $syarat }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="flex flex-col sm:flex-row gap-3 mt-6">
                                        <a href="/jalur-pendaftaran/{{ $jalur->kode }}" class="flex-1 text-center {{ $colors['button'] }} text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                                            Lihat Detail Lengkap
                                        </a>
                                        <a href="/daftar" class="flex-1 text-center border-2 {{ $colors['border'] }} {{ $colors['text'] }} px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200">
                                            Daftar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Info Box -->
                <div class="mt-12 bg-gradient-to-r from-emerald-50 to-blue-50 border-l-4 border-emerald-600 rounded-lg p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-emerald-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Informasi Penting:</h4>
                            <ul class="text-sm text-gray-700 space-y-1">
                                <li>• Setiap pendaftar hanya dapat memilih satu jalur pendaftaran</li>
                                <li>• Pastikan nilai rata-rata rapor Anda memenuhi nilai minimum jalur yang dipilih</li>
                                <li>• Kuota bersifat terbatas, daftar secepatnya untuk peluang lebih besar</li>
                                <li>• Baca persyaratan setiap jalur dengan teliti sebelum mendaftar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Jalur Pendaftaran Aktif</h3>
                    <p class="text-gray-600 mb-6">Jalur pendaftaran akan segera dibuka. Silakan cek kembali nanti.</p>
                    <a href="/" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white border-t-2 border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-emerald-600 to-blue-600 rounded-2xl p-8 md:p-12 text-center shadow-2xl">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Masih Bingung Memilih Jalur?</h2>
                <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
                    Baca panduan lengkap atau hubungi panitia untuk konsultasi
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/informasi" class="inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Baca Panduan
                    </a>
                    <a href="/kontak-panitia" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-emerald-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Hubungi Panitia
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
