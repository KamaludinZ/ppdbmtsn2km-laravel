{{--
    Rekap daftar ulang (dipakai menu Daftar Ulang & Laporan untuk semua role).
    Data dari App\Support\RekapDaftarUlang::statsTab(): setiap pendaftar lulus masuk tepat
    satu kategori, sehingga jumlah rincian selalu sama dengan "Wajib Daftar Ulang".
    Parameter: $stats, $tab (madrasah|seragam|ukuran), $clickable (bool, klik kategori = filter),
    $filterProperty (nama properti Livewire yang diisi saat kategori diklik, default filterRekap).
--}}
@if(!empty($stats))
@php
    $tab = $tab ?? 'madrasah';
    $clickable = $clickable ?? false;
    $filterProperty = $filterProperty ?? 'filterRekap';
    $kategoriList = \App\Support\RekapDaftarUlang::KATEGORI;
    $total = (int) ($stats['total_wajib'] ?? $stats['total_lulus'] ?? $stats['lulus'] ?? 0);
    $selesai = (int) ($stats['selesai'] ?? $stats['disetujui'] ?? 0);
    $judul = [
        'madrasah' => ['selesai' => 'Sudah Daftar Ulang', 'belum' => 'Belum Daftar Ulang', 'rincian' => 'Rincian status daftar ulang madrasah'],
        'seragam' => ['selesai' => 'Seragam Disetujui', 'belum' => 'Seragam Belum Selesai', 'rincian' => 'Rincian status daftar ulang seragam'],
        'ukuran' => ['selesai' => 'Ukuran Disetujui', 'belum' => 'Ukuran Belum Selesai', 'rincian' => 'Rincian status ukuran seragam'],
    ][$tab] ?? ['selesai' => 'Selesai', 'belum' => 'Belum Selesai', 'rincian' => 'Rincian status'];
    // Kelas warna ditulis lengkap agar ikut ter-compile oleh Tailwind
    $warna = [
        'gray' => ['bar' => 'bg-gray-400', 'dot' => 'bg-gray-400', 'ring' => 'ring-gray-400'],
        'rose' => ['bar' => 'bg-rose-500', 'dot' => 'bg-rose-500', 'ring' => 'ring-rose-500'],
        'amber' => ['bar' => 'bg-amber-400', 'dot' => 'bg-amber-400', 'ring' => 'ring-amber-400'],
        'blue' => ['bar' => 'bg-blue-500', 'dot' => 'bg-blue-500', 'ring' => 'ring-blue-500'],
        'orange' => ['bar' => 'bg-orange-500', 'dot' => 'bg-orange-500', 'ring' => 'ring-orange-500'],
        'red' => ['bar' => 'bg-red-600', 'dot' => 'bg-red-600', 'ring' => 'ring-red-600'],
        'emerald' => ['bar' => 'bg-emerald-600', 'dot' => 'bg-emerald-600', 'ring' => 'ring-emerald-600'],
    ];
    $aktif = $clickable ? ($this->{$filterProperty} ?? '') : '';
@endphp

<section class="mb-6 space-y-4" aria-label="Rekap daftar ulang">
    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <x-stat-card color="blue" label="Wajib Daftar Ulang" :value="$total" sub="Pendaftar lulus / diterima" />
        <x-stat-card color="emerald" :label="$judul['selesai']" :value="$selesai" :sub="($total > 0 ? round($selesai / $total * 100) : 0) . '% dari wajib'" />
        <x-stat-card color="amber" :label="$judul['belum']" :value="max($total - $selesai, 0)" sub="Termasuk belum konfirmasi" />
        <x-stat-card color="gray" label="Total Pendaftar" :value="(int) ($stats['total_pendaftar'] ?? $total)" sub="Semua status pendaftaran" />
    </div>

    {{-- Rincian: jumlah semua kategori = Wajib Daftar Ulang --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <div class="flex flex-wrap items-baseline justify-between gap-2 mb-3">
            <h4 class="text-sm font-semibold text-gray-900">{{ $judul['rincian'] }}</h4>
            <p class="text-xs text-gray-500">Jumlah rincian = {{ number_format($total) }} pendaftar{{ $clickable ? ' · ' . ($clickLabel ?? 'klik untuk memfilter tabel') : '' }}</p>
        </div>

        <div class="flex h-3 w-full overflow-hidden rounded-full bg-gray-100" role="img" aria-label="Proporsi status daftar ulang">
            @foreach($kategoriList as $key => $info)
                @php $jumlah = (int) ($stats[$key] ?? 0); @endphp
                @if($jumlah > 0 && $total > 0)
                    <div class="{{ $warna[$info['color']]['bar'] }} h-full" style="width: {{ $jumlah / $total * 100 }}%" title="{{ $info['label'] }}: {{ $jumlah }}"></div>
                @endif
            @endforeach
        </div>

        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
            @foreach($kategoriList as $key => $info)
                @php
                    $jumlah = (int) ($stats[$key] ?? 0);
                    $isAktif = $aktif === $key;
                @endphp
                @if($clickable)
                    <button type="button"
                        wire:click="$set('{{ $filterProperty }}', '{{ $isAktif ? '' : $key }}')"
                        aria-pressed="{{ $isAktif ? 'true' : 'false' }}"
                        class="flex items-center justify-between gap-2 rounded-lg border px-3 py-2 text-left transition hover:bg-gray-50 focus:outline-none focus:ring-2 {{ $warna[$info['color']]['ring'] }} {{ $isAktif ? 'border-gray-900 bg-gray-50' : 'border-gray-200' }}">
                @else
                    <div class="flex items-center justify-between gap-2 rounded-lg border border-gray-200 px-3 py-2">
                @endif
                        <span class="flex min-w-0 items-center gap-2">
                            <span class="h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $warna[$info['color']]['dot'] }}"></span>
                            <span class="truncate text-sm text-gray-700">{{ $info['label'] }}</span>
                        </span>
                        <span class="text-sm font-semibold text-gray-900 tabular-nums">{{ number_format($jumlah) }}</span>
                @if($clickable)
                    </button>
                @else
                    </div>
                @endif
            @endforeach
        </div>

        @if($tab === 'madrasah' && isset($stats['upload_surat']))
            <p class="mt-3 text-xs text-gray-500">Sudah upload surat pernyataan: <span class="font-semibold text-gray-700">{{ number_format((int) $stats['upload_surat']) }}</span></p>
        @endif
        @if($tab === 'seragam' && isset($stats['ukuran_menunggu']))
            <p class="mt-3 text-xs text-gray-500">
                Ukuran seragam — menunggu verifikasi: <span class="font-semibold text-gray-700">{{ number_format((int) $stats['ukuran_menunggu']) }}</span>,
                disetujui: <span class="font-semibold text-gray-700">{{ number_format((int) $stats['ukuran_disetujui']) }}</span>,
                ditolak: <span class="font-semibold text-gray-700">{{ number_format((int) $stats['ukuran_ditolak']) }}</span>
            </p>
        @endif
    </div>
</section>
@endif
