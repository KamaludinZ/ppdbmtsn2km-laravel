{{--
    Rekap persetujuan melanjutkan daftar ulang (tab Daftar Ulang Madrasah di Laporan).
    Data: $rekapStats (RekapDaftarUlang::statsTab), $tidakMelanjutkan (RekapDaftarUlang::tidakMelanjutkan),
    $diLuarWajib (pendaftar non-lulus yang sudah memilih / punya data daftar ulang).
--}}
@php
    $wajib = (int) ($rekapStats['total_wajib'] ?? 0);
    $pSetuju = (int) ($rekapStats['persetujuan_setuju'] ?? 0);
    $pTidak = (int) ($rekapStats['persetujuan_tidak_setuju'] ?? 0);
    $pBelum = (int) ($rekapStats['persetujuan_belum_memilih'] ?? 0);
    $cocok = ($pSetuju + $pTidak + $pBelum) === $wajib;
    $persen = fn ($n) => $wajib > 0 ? round($n / $wajib * 100) : 0;
    $daftarTidak = $tidakMelanjutkan['daftar'];
    $perAlasan = $tidakMelanjutkan['per_alasan'];
    $maksAlasan = max(1, (int) $perAlasan->max());
@endphp

<section class="rounded-xl border border-gray-200 bg-white">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Persetujuan Melanjutkan Daftar Ulang</h2>
            <p class="text-sm text-gray-500">Pilihan pendaftar lulus/diterima di dashboard mereka (atau yang diubah admin/panitia).</p>
        </div>
        @if($cocok)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Setuju + Tidak + Belum = {{ number_format($wajib) }} wajib
            </span>
        @else
            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">Jumlah tidak cocok, periksa data</span>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-3 p-5 sm:grid-cols-3">
        <x-stat-card color="emerald" label="Setuju Melanjutkan" :value="$pSetuju" :sub="$persen($pSetuju) . '% dari wajib'" />
        <x-stat-card color="rose" label="Tidak Melanjutkan" :value="$pTidak" :sub="$persen($pTidak) . '% dari wajib, beserta alasan di bawah'" />
        <x-stat-card color="gray" label="Belum Memilih / Belum Konfirmasi" :value="$pBelum" :sub="$persen($pBelum) . '% dari wajib, perlu diingatkan'" />
    </div>

    @if($pTidak > 0)
        <div class="grid grid-cols-1 gap-6 border-t border-gray-100 p-5 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Alasan Tidak Melanjutkan</h3>
                <ul class="space-y-2">
                    @foreach($perAlasan as $alasan => $jumlah)
                        <li>
                            <div class="flex items-baseline justify-between gap-3 text-sm">
                                <span class="min-w-0 break-words text-gray-700">{{ $alasan }}</span>
                                <span class="shrink-0 font-semibold tabular-nums text-gray-900">{{ number_format($jumlah) }}</span>
                            </div>
                            <div class="mt-1 h-1.5 rounded-full bg-gray-100"><div class="h-1.5 rounded-full bg-rose-500" style="width: {{ round($jumlah / $maksAlasan * 100) }}%"></div></div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="lg:col-span-3">
                <h3 class="mb-3 text-sm font-semibold text-gray-900">Daftar Pendaftar Tidak Melanjutkan ({{ number_format($daftarTidak->count()) }})</h3>
                <div class="max-h-96 overflow-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-3 py-2 text-left">Pendaftar</th>
                                <th class="px-3 py-2 text-left">Jalur</th>
                                <th class="px-3 py-2 text-left">Alasan</th>
                                <th class="px-3 py-2 text-left">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($daftarTidak as $p)
                                <tr class="align-top">
                                    <td class="px-3 py-2">
                                        <p class="font-medium text-gray-900">{{ $p->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-500">{{ $p->nomor_pendaftaran }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ $p->jalurPendaftaran->nama ?? '-' }}</td>
                                    <td class="min-w-[12rem] px-3 py-2 text-gray-700">
                                        @if(trim((string) $p->alasan_tidak_setuju) !== '')
                                            {{ $p->alasan_tidak_setuju }}
                                        @else
                                            <span class="italic text-gray-400">tidak dicatat</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $p->tanggal_pilihan_daftar_ulang?->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</section>

@if(($rekapStats['di_luar_wajib'] ?? 0) > 0)
    <section class="rounded-xl border border-amber-300 bg-amber-50 p-5" role="note">
        <h3 class="text-sm font-semibold text-amber-900">
            {{ number_format($rekapStats['di_luar_wajib']) }} pendaftar tidak masuk rekap karena statusnya bukan Lulus/Diterima
        </h3>
        <p class="mt-1 text-sm text-amber-800">
            Pendaftar berikut sudah memilih persetujuan atau sudah punya data daftar ulang, tetapi status pendaftarannya kemudian berubah.
            Periksa apakah statusnya perlu dikembalikan ke Lulus/Diterima agar ikut dihitung.
        </p>
        <div class="mt-3 overflow-x-auto rounded-lg border border-amber-200 bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-amber-100/60 text-xs font-medium uppercase tracking-wide text-amber-900">
                    <tr>
                        <th class="px-3 py-2 text-left">Pendaftar</th>
                        <th class="px-3 py-2 text-left">Jalur</th>
                        <th class="px-3 py-2 text-left">Status Pendaftaran</th>
                        <th class="px-3 py-2 text-left">Persetujuan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    @foreach($diLuarWajib as $p)
                        <tr>
                            <td class="px-3 py-2"><span class="font-medium text-gray-900">{{ $p->nama_lengkap }}</span> <span class="text-xs text-gray-500">{{ $p->nomor_pendaftaran }}</span></td>
                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ $p->jalurPendaftaran->nama ?? '-' }}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ ucwords(str_replace('_', ' ', $p->status_pendaftaran)) }}</td>
                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ \App\Support\PersetujuanDaftarUlang::label(\App\Support\PersetujuanDaftarUlang::status($p)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($rekapStats['di_luar_wajib'] > $diLuarWajib->count())
            <p class="mt-2 text-xs text-amber-800">Menampilkan {{ $diLuarWajib->count() }} pertama.</p>
        @endif
    </section>
@endif
