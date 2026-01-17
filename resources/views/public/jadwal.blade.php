<x-layouts.public title="Jadwal {{ $appAcronym }} - MTsN 2 Kota Malang">
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Jadwal {{ $appAcronym }}</span>
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
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Jadwal {{ $appAcronym }} {{ date('Y') }}</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Timeline lengkap proses {{ $appFullName }}
                </p>
            </div>
        </div>
    </section>

    <!-- Timeline per Jalur -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($timelinePendaftaran->count() > 0)
                @php
                    // Kelompokkan timeline berdasarkan jalur
                    $timelineByJalur = $timelinePendaftaran->groupBy('jalur_kode');

                    // Ambil semua jalur dari database untuk mendapatkan nama asli
                    $jalurPendaftaranList = \App\Models\JalurPendaftaran::aktif()->get()->keyBy('kode');

                    // Function untuk mendapatkan warna jalur berdasarkan kode
                    $getJalurColors = function($kode) {
                        // Mapping warna berdasarkan pattern kode jalur
                        if (str_contains($kode, 'prestasi')) {
                            return [
                                'border' => 'border-blue-500',
                                'bg' => 'bg-blue-50',
                                'headerBg' => 'bg-blue-600',
                                'textColor' => 'text-blue-700',
                                'badgeBg' => 'bg-blue-100',
                                'badgeText' => 'text-blue-800',
                                'rowHover' => 'hover:bg-blue-50'
                            ];
                        } elseif (str_contains($kode, 'tahfidz') || str_contains($kode, 'tahfiz')) {
                            return [
                                'border' => 'border-purple-500',
                                'bg' => 'bg-purple-50',
                                'headerBg' => 'bg-purple-600',
                                'textColor' => 'text-purple-700',
                                'badgeBg' => 'bg-purple-100',
                                'badgeText' => 'text-purple-800',
                                'rowHover' => 'hover:bg-purple-50'
                            ];
                        } else {
                            // Default untuk reguler dan lainnya
                            return [
                                'border' => 'border-emerald-500',
                                'bg' => 'bg-emerald-50',
                                'headerBg' => 'bg-emerald-600',
                                'textColor' => 'text-emerald-700',
                                'badgeBg' => 'bg-emerald-100',
                                'badgeText' => 'text-emerald-800',
                                'rowHover' => 'hover:bg-emerald-50'
                            ];
                        }
                    };

                    // Function untuk format nama jalur
                    $formatJalurNama = function($kode) use ($jalurPendaftaranList) {
                        // Coba ambil dari database dulu
                        if (isset($jalurPendaftaranList[$kode])) {
                            return $jalurPendaftaranList[$kode]->nama;
                        }

                        // Jika tidak ada, format dari kode
                        // Ganti underscore dengan spasi dan capitalize setiap kata
                        return ucwords(str_replace('_', ' ', $kode));
                    };
                @endphp

                <div class="space-y-8">
                    @foreach($timelineByJalur as $jalurKode => $timelines)
                        @php
                            $colors = $getJalurColors($jalurKode);
                            $jalurNama = $formatJalurNama($jalurKode);
                        @endphp

                        <!-- Card per Jalur -->
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-t-4 {{ $colors['border'] }}">
                            <!-- Header Jalur -->
                            <div class="{{ $colors['headerBg'] }} px-6 py-4">
                                <div class="flex items-center">
                                    <svg class="w-7 h-7 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-2xl font-bold text-white">{{ $jalurNama }}</h3>
                                </div>
                            </div>

                            <!-- Tabel Timeline -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="{{ $colors['bg'] }}">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">Kegiatan</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">Waktu</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">Tempat</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold {{ $colors['textColor'] }} uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($timelines->sortBy('urutan') as $index => $timeline)
                                            @php
                                                // Check if current date is between tanggal_mulai and tanggal_selesai
                                                $now = \Carbon\Carbon::now();
                                                $start = \Carbon\Carbon::parse($timeline->tanggal_mulai);
                                                $end = \Carbon\Carbon::parse($timeline->tanggal_selesai);
                                                $isActive = $now->between($start, $end);
                                                $isPast = $now->greaterThan($end);
                                            @endphp
                                            <tr class="{{ $colors['rowHover'] }} transition-colors {{ $isActive ? $colors['bg'] : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $colors['badgeBg'] }} {{ $colors['badgeText'] }} font-bold text-sm">
                                                        {{ $index + 1 }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $timeline->judul_kegiatan }}</div>
                                                    @if($timeline->deskripsi)
                                                        <div class="text-xs text-gray-600 mt-1">{{ $timeline->deskripsi }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900 font-medium">
                                                        {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->format('d/m/Y') }}
                                                        @if($timeline->tanggal_mulai != $timeline->tanggal_selesai)
                                                            <br><span class="text-gray-500">s/d</span><br>
                                                            {{ \Carbon\Carbon::parse($timeline->tanggal_selesai)->format('d/m/Y') }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($timeline->waktu_mulai)
                                                        <div class="text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($timeline->waktu_mulai)->format('H:i') }}
                                                            @if($timeline->waktu_selesai)
                                                                - {{ \Carbon\Carbon::parse($timeline->waktu_selesai)->format('H:i') }}
                                                            @endif
                                                            <div class="text-xs text-gray-500">WIB</div>
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($timeline->tempat)
                                                        <div class="text-sm text-gray-900 flex items-start">
                                                            <svg class="w-4 h-4 {{ $colors['textColor'] }} mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            <span>{{ $timeline->tempat }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($isActive)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                                            Berlangsung
                                                        </span>
                                                    @elseif($isPast)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Akan Datang
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer dengan jumlah kegiatan -->
                            <div class="{{ $colors['bg'] }} px-6 py-3 border-t border-gray-200">
                                <div class="text-sm {{ $colors['textColor'] }} font-medium">
                                    Total {{ $timelines->count() }} kegiatan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Jadwal Belum Tersedia</h3>
                    <p class="text-gray-600">
                        Jadwal {{ $appAcronym }} akan segera diumumkan. Silakan cek kembali nanti.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- Info Penting -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">Catatan Penting:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Pastikan Anda mendaftar sesuai dengan jadwal yang telah ditentukan</li>
                            <li>• Pendaftaran yang tidak sesuai jadwal tidak akan diproses</li>
                            <li>• Pantau terus website resmi untuk informasi terbaru</li>
                            <li>• Hubungi panitia jika ada pertanyaan terkait jadwal</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Mendaftar?</h2>
            <p class="text-blue-100 mb-8 max-w-2xl mx-auto">
                Jangan lewatkan kesempatan untuk bergabung dengan MTsN 2 Kota Malang
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/daftar" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-blue-600 bg-white hover:bg-gray-50 transition shadow-lg">
                    Daftar Sekarang
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="/alur-pendaftaran" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-blue-700 transition">
                    Lihat Alur Pendaftaran
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
