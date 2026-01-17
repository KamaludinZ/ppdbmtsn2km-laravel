<x-layouts.public title="Tentang Kami - MTsN 2 Kota Malang">

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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Tentang Kami</span>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang {{ \App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang') }}</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Lembaga pendidikan Islam yang berkomitmen mencetak generasi berakhlak mulia dan berprestasi
                </p>
            </div>
        </div>
    </section>

    <!-- Profil Madrasah -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Profil Madrasah</h2>
                    <div class="prose prose-lg text-gray-600 text-justify">
                        {!! \App\Models\Setting::get('page_tentang_content', '<p>Konten profil belum diisi.</p>') !!}
                    </div>
                </div>
                <div class="bg-gray-100 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Identitas Madrasah</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="font-medium text-gray-700">Nama Madrasah</span>
                            <span class="text-gray-900 font-semibold">{{ \App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="font-medium text-gray-700">NSM</span>
                            <span class="text-gray-900 font-semibold">{{ \App\Models\Setting::get('app_nsm', '121135740002') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="font-medium text-gray-700">NPSN</span>
                            <span class="text-gray-900 font-semibold">{{ \App\Models\Setting::get('app_npsn', '20536326') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="font-medium text-gray-700">Akreditasi</span>
                            <span class="text-gray-900 font-semibold">{{ \App\Models\Setting::get('app_akreditasi', 'A (Unggul)') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-3">
                            <span class="font-medium text-gray-700">Alamat</span>
                            <span class="text-gray-900 font-semibold text-right">{{ \App\Models\Setting::get('app_address', 'Jl. Raya Sulfat No. 01') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Telepon</span>
                            <span class="text-gray-900 font-semibold">{{ \App\Models\Setting::get('app_phone', '(0341) 123456') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Visi -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Visi</h3>
                    </div>
                    <div class="text-gray-700 leading-relaxed text-lg prose prose-emerald max-w-none text-justify">
                        {!! \App\Models\Setting::get('page_tentang_visi', 'Visi belum diisi.') !!}
                    </div>
                </div>

                <!-- Misi -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900">Misi</h3>
                    </div>
                    @if($misi->count() > 0)
                        <ul class="space-y-3 text-gray-700 leading-relaxed">
                            @foreach($misi as $item)
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-justify">{{ $item->misi }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">Misi belum diisi.</p>
                    @endif
                </div>
            </div>

            <!-- Tujuan (New Section) -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Tujuan</h3>
                </div>
                @if($tujuan->count() > 0)
                    <ul class="space-y-3 text-gray-700 leading-relaxed">
                        @foreach($tujuan as $item)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-justify">{{ $item->tujuan }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 italic">Tujuan belum diisi.</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Fasilitas -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fasilitas Madrasah</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Didukung dengan berbagai fasilitas yang memadai untuk menunjang proses pembelajaran
                </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @if($fasilitas->count() > 0)
                    @php
                        // Color mapping untuk inline styles
                        $colorMap = [
                            'emerald' => ['bg' => '#D1FAE5', 'text' => '#059669'],
                            'blue' => ['bg' => '#DBEAFE', 'text' => '#2563EB'],
                            'purple' => ['bg' => '#E9D5FF', 'text' => '#9333EA'],
                            'yellow' => ['bg' => '#FEF3C7', 'text' => '#D97706'],
                            'red' => ['bg' => '#FEE2E2', 'text' => '#DC2626'],
                            'green' => ['bg' => '#D1FAE5', 'text' => '#059669'],
                            'indigo' => ['bg' => '#E0E7FF', 'text' => '#4F46E5'],
                            'pink' => ['bg' => '#FCE7F3', 'text' => '#EC4899'],
                        ];
                    @endphp

                    @foreach($fasilitas as $item)
                    @php
                        $colors = $colorMap[$item->warna] ?? $colorMap['emerald'];
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-3" style="background-color: {{ $colors['bg'] }}">
                            @switch($item->icon)
                                @case('building')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    @break
                                @case('library')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    @break
                                @case('computer')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    @break
                                @case('book')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    @break
                                @case('science')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    @break
                                @case('sports')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @break
                                @case('mosque')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    @break
                                @case('music')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                    @break
                                @case('art')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    @break
                                @case('cafe')
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6" style="color: {{ $colors['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                            @endswitch
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $item->nama_fasilitas }}</h3>
                        @if($item->deskripsi)
                        <p class="text-sm text-gray-600 mt-1">{{ $item->deskripsi }}</p>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 italic">Belum ada data fasilitas.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-emerald-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Bergabunglah Bersama Kami</h2>
            <p class="text-emerald-100 mb-8 max-w-2xl mx-auto">
                Daftar sekarang dan jadilah bagian dari keluarga besar MTsN 2 Kota Malang
            </p>
            <a href="/daftar" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-emerald-600 bg-white hover:bg-gray-50 transition shadow-lg">
                Daftar Sekarang
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>
</x-layouts.public>
