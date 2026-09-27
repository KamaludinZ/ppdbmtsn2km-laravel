<x-layouts.public title="Informasi & Panduan - {{ $appAcronym }} MTsN 2 Kota Malang">
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Informasi & Panduan</span>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Informasi & Panduan</h1>
                <p class="text-xl text-emerald-100 max-w-2xl mx-auto">
                    Panduan lengkap untuk pendaftaran {{ $appAcronym }} MTsN 2 Kota Malang
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-gray-50" x-data="{ activeKonten: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-200 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Daftar Isi</h3>
                        <nav class="space-y-2">
                            @forelse($kontenInformasi as $konten)
                                <button
                                    @click="activeKonten = {{ $konten->id }}; window.scrollTo({ top: document.getElementById('content-section').offsetTop - 100, behavior: 'smooth' })"
                                    :class="activeKonten === {{ $konten->id }} ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:text-emerald-600 hover:bg-emerald-50'"
                                    class="w-full text-left px-3 py-2 rounded-lg transition-colors"
                                >
                                    {{ $konten->judul }}
                                </button>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada konten tersedia</p>
                            @endforelse

                            @if($faqs->count() > 0)
                                <button
                                    @click="activeKonten = 'faq'; window.scrollTo({ top: document.getElementById('faq-section').offsetTop - 100, behavior: 'smooth' })"
                                    :class="activeKonten === 'faq' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50'"
                                    class="w-full text-left px-3 py-2 rounded-lg transition-colors"
                                >
                                    FAQ (Pertanyaan Umum)
                                </button>
                            @endif
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    <!-- Dynamic Content Section -->
                    <div id="content-section">
                        @if($kontenInformasi->count() > 0)
                            @foreach($kontenInformasi as $konten)
                                <div
                                    x-show="activeKonten === {{ $konten->id }}"
                                    x-transition
                                    class="bg-white rounded-2xl p-8 shadow-lg border-2 border-gray-200"
                                >
                                    <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ $konten->judul }}</h2>
                                    <div class="prose prose-lg max-w-none text-gray-700">
                                        {!! \App\Support\HtmlSanitizer::clean($konten->isi) !!}
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Default message when no content is selected -->
                        <div
                            x-show="activeKonten === null"
                            class="bg-white rounded-2xl p-8 shadow-lg border-2 border-gray-200"
                        >
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Pilih Topik dari Daftar Isi</h3>
                                <p class="mt-2 text-gray-600">Klik salah satu topik di sidebar untuk melihat informasinya</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    @if($faqs->count() > 0)
                        <div
                            id="faq-section"
                            x-show="activeKonten === 'faq'"
                            x-transition
                            class="bg-white rounded-2xl p-8 shadow-lg border-2 border-blue-200"
                        >
                            <div class="flex items-center mb-6">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h2 class="text-3xl font-bold text-gray-900">FAQ (Pertanyaan Umum)</h2>
                            </div>

                            <div class="space-y-4" x-data="{ openFaq: null }">
                                @foreach($faqs as $index => $faq)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 transition">
                                        <button
                                            @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                            class="w-full px-6 py-4 text-left bg-gray-50 hover:bg-gray-100 transition flex items-center justify-between"
                                        >
                                            <span class="font-semibold text-gray-900">{{ $faq->pertanyaan }}</span>
                                            <svg
                                                :class="openFaq === {{ $index }} ? 'rotate-180' : ''"
                                                class="w-5 h-5 text-gray-500 transform transition-transform"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div
                                            x-show="openFaq === {{ $index }}"
                                            x-transition
                                            class="px-6 py-4 bg-white border-t border-gray-200"
                                        >
                                            <div class="prose prose-lg max-w-none text-gray-700">
                                                {!! \App\Support\HtmlSanitizer::clean($faq->jawaban) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- CTA Section -->
                    <div class="bg-gradient-to-r from-emerald-600 to-blue-600 rounded-2xl p-8 text-white text-center shadow-xl">
                        <h3 class="text-2xl font-bold mb-3">Siap Mendaftar?</h3>
                        <p class="text-emerald-100 mb-6">Pastikan Anda sudah membaca dan memahami semua informasi di atas</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/daftar" class="inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                Daftar Sekarang
                            </a>
                            <a href="/kontak-panitia" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-emerald-600 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Hubungi Panitia
                            </a>
                            @if($brosurActive && $brosurFilePath)
                            <a href="{{ Storage::url($brosurFilePath) }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-emerald-600 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Brosur
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
