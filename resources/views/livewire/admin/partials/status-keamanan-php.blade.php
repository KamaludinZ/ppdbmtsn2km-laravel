{{--
    Status kesiapan server terhadap PHP2Shell (CVE-2026-109618 & CVE-2026-109619).
    Data: $statusPhp, $ringkasanPhp (StatusKeamananPhp::periksa/ringkasan), $indikatorPhp (StatusKeamananPhp::indikatorSerangan).
--}}
@php
    $gaya = [
        'aman' => ['badge' => 'bg-emerald-100 text-emerald-800', 'teks' => 'Aman'],
        'info' => ['badge' => 'bg-sky-100 text-sky-800', 'teks' => 'Info'],
        'waspada' => ['badge' => 'bg-amber-100 text-amber-800', 'teks' => 'Waspada'],
        'bahaya' => ['badge' => 'bg-red-100 text-red-800', 'teks' => 'Perlu tindakan'],
    ];
    $kotak = [
        'aman' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'waspada' => 'border-amber-300 bg-amber-50 text-amber-900',
        'bahaya' => 'border-red-300 bg-red-50 text-red-900',
    ][$ringkasanPhp];
    $totalOom = array_sum(array_column($indikatorPhp['files'], 'jumlah'));
@endphp

<section class="mb-6 rounded-xl border border-gray-200 bg-white" aria-labelledby="judul-status-php">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
        <div class="min-w-0">
            <h4 id="judul-status-php" class="text-base font-semibold text-gray-900">Kesiapan Server PHP: PHP2Shell</h4>
            <p class="text-sm text-gray-500">CVE-2026-109618 &amp; CVE-2026-109619, celah di inti PHP. Perbaikan utamanya adalah update PHP di hosting.</p>
        </div>
        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $gaya[$ringkasanPhp]['badge'] }}">{{ $gaya[$ringkasanPhp]['teks'] }}</span>
    </div>

    <div class="px-5 py-4">
        <div class="mb-4 rounded-lg border px-4 py-3 text-sm {{ $kotak }}" role="status">
            @if($ringkasanPhp === 'aman')
                Mitigasi aplikasi sudah aktif. Tetap pastikan ke hosting bahwa PHP sudah memakai rilis patch 21 Oktober 2026 atau lebih baru.
            @elseif($ringkasanPhp === 'waspada')
                Sebagian pemeriksaan belum bisa dipastikan. Lihat saran di bawah dan konfirmasikan ke hosting.
            @else
                Ada pemeriksaan yang perlu tindakan. Ikuti saran di bawah, lalu muat ulang halaman ini.
            @endif
        </div>

        <ul class="divide-y divide-gray-100 rounded-lg border border-gray-200">
            @foreach($statusPhp as $cek)
                <li class="grid gap-x-4 gap-y-1 px-4 py-3 sm:grid-cols-12">
                    <div class="min-w-0 sm:col-span-5">
                        <p class="break-all text-sm font-medium text-gray-900">{{ $cek['label'] }}</p>
                        <p class="mt-0.5 font-mono text-xs text-gray-600">{{ $cek['nilai'] }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="inline-block rounded-full px-2 py-0.5 text-xs font-semibold {{ $gaya[$cek['status']]['badge'] }}">{{ $gaya[$cek['status']]['teks'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600 sm:col-span-5">{{ $cek['saran'] }}</p>
                </li>
            @endforeach
        </ul>

        <div class="mt-5">
            <h5 class="text-sm font-semibold text-gray-900">Tanda serangan di log</h5>
            <p class="mt-1 text-sm text-gray-500">
                Menurut advisory, serangan memicu pesan <code class="rounded bg-gray-100 px-1 text-xs">Allowed memory size of … exhausted</code>
                berulang bersama POST multipart/form-data dan HTTP 500.
            </p>

            @if(empty($indikatorPhp['files']))
                <p class="mt-2 text-sm text-gray-600">Tidak ada file log PHP yang bisa dibaca aplikasi. Periksa menu <strong>Errors</strong> dan <strong>Raw Access</strong> di cPanel.</p>
            @else
                <ul class="mt-2 flex flex-wrap gap-2 text-xs">
                    @foreach($indikatorPhp['files'] as $f)
                        <li class="rounded-full border px-3 py-1 {{ $f['jumlah'] > 0 ? 'border-amber-300 bg-amber-50 text-amber-900' : 'border-gray-200 bg-gray-50 text-gray-600' }}">
                            <span class="font-mono">{{ $f['nama'] }}</span>: {{ number_format($f['jumlah']) }} pesan
                        </li>
                    @endforeach
                </ul>

                @if($totalOom > 0)
                    <div class="mt-3 rounded-lg border border-amber-300 bg-amber-50 p-3">
                        <p class="text-sm text-amber-900">
                            Ditemukan {{ number_format($totalOom) }} pesan kehabisan memori. Belum tentu serangan (bisa karena export besar),
                            tetapi bila banyak dan berdekatan waktunya, cocokkan dengan Raw Access log cPanel dan hubungi hosting.
                        </p>
                        <ul class="mt-2 space-y-1">
                            @foreach($indikatorPhp['terakhir'] as $baris)
                                <li class="break-all font-mono text-xs text-amber-900">{{ $baris }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="mt-2 text-sm text-gray-600">Tidak ada pesan kehabisan memori di bagian akhir log yang terbaca. Log server (Apache/LiteSpeed) tetap perlu dicek di cPanel.</p>
                @endif
            @endif
        </div>
    </div>
</section>
