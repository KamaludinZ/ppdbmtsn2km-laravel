{{--
    Persetujuan daftar ulang per jalur (tab Laporan Pendaftaran).
    Data: $persetujuanJalur dari RekapPendaftaran::persetujuanPerJalur(); $warnaJalur dari view laporan.
--}}
@php
    $tot = $persetujuanJalur['total'];
    $baris = $persetujuanJalur['jalur'];
@endphp
<section class="rounded-xl border border-gray-200 bg-white">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Persetujuan Daftar Ulang per Jalur</h2>
            <p class="text-sm text-gray-500">Dari pendaftar yang dinyatakan Lulus/Diterima: berapa yang setuju melanjutkan, tidak melanjutkan, dan belum memilih. Persentase dihitung dari jumlah lulus/diterima di jalur tersebut.</p>
        </div>
        @if($tot['setuju'] + $tot['tidak_setuju'] + $tot['belum_memilih'] === $tot['wajib'])
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Setuju + Tidak + Belum = {{ number_format($tot['wajib']) }}
            </span>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-3 p-5 lg:grid-cols-4">
        <x-stat-card color="blue" label="Lulus / Diterima" :value="$tot['wajib']" sub="Wajib memilih persetujuan" />
        <x-stat-card color="emerald" label="Setuju Daftar Ulang" :value="$tot['setuju']" :sub="$tot['setuju_persen'] . '% dari lulus/diterima'" />
        <x-stat-card color="rose" label="Tidak Melanjutkan" :value="$tot['tidak_setuju']" :sub="$tot['tidak_setuju_persen'] . '% dari lulus/diterima'" />
        <x-stat-card color="gray" label="Belum Memilih" :value="$tot['belum_memilih']" :sub="$tot['belum_memilih_persen'] . '% dari lulus/diterima'" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="sticky left-0 bg-gray-50 px-4 py-3 text-left">Jalur</th>
                    <th class="px-3 py-3 text-center">Lulus / Diterima</th>
                    <th class="px-3 py-3 text-center">Setuju</th>
                    <th class="px-3 py-3 text-center">Tidak Melanjutkan</th>
                    <th class="px-3 py-3 text-center">Belum Memilih</th>
                    <th class="px-4 py-3 text-left">Proporsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($baris as $j)
                    @php [$bg, $fg] = $warnaJalur[$j['warna'] ?? 'blue'] ?? $warnaJalur['blue']; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="sticky left-0 bg-white px-4 py-3">
                            <span class="inline-block whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-semibold" style="background-color: {{ $bg }}; color: {{ $fg }};">{{ $j['nama'] }}</span>
                            @if($j['nonaktif'])
                                <span class="ml-1 whitespace-nowrap rounded bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-600">nonaktif</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center font-semibold tabular-nums text-gray-900">{{ number_format($j['wajib']) }}</td>
                        @foreach([['setuju', 'text-emerald-700'], ['tidak_setuju', 'text-rose-700'], ['belum_memilih', 'text-gray-700']] as [$kunci, $warna])
                            <td class="whitespace-nowrap px-3 py-3 text-center tabular-nums">
                                <span class="font-semibold {{ $warna }}">{{ number_format($j[$kunci]) }}</span>
                                <span class="text-xs text-gray-500">({{ $j[$kunci . '_persen'] }}%)</span>
                            </td>
                        @endforeach
                        <td class="px-4 py-3">
                            @if($j['wajib'] > 0)
                                <div class="flex h-2.5 min-w-[9rem] overflow-hidden rounded-full bg-gray-100" role="img" aria-label="Setuju {{ $j['setuju_persen'] }}%, tidak {{ $j['tidak_setuju_persen'] }}%, belum {{ $j['belum_memilih_persen'] }}%">
                                    <div class="h-full bg-emerald-600" style="width: {{ $j['setuju_persen'] }}%"></div>
                                    <div class="h-full bg-rose-500" style="width: {{ $j['tidak_setuju_persen'] }}%"></div>
                                    <div class="h-full bg-gray-400" style="width: {{ $j['belum_memilih_persen'] }}%"></div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">belum ada yang lulus</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data jalur.</td></tr>
                @endforelse
            </tbody>
            @if($baris->count() > 1)
                <tfoot class="border-t-2 border-gray-200 bg-gray-50 font-semibold text-gray-900">
                    <tr>
                        <td class="sticky left-0 bg-gray-50 px-4 py-3">Total</td>
                        <td class="px-3 py-3 text-center tabular-nums">{{ number_format($tot['wajib']) }}</td>
                        @foreach(['setuju', 'tidak_setuju', 'belum_memilih'] as $kunci)
                            <td class="whitespace-nowrap px-3 py-3 text-center tabular-nums">{{ number_format($tot[$kunci]) }} <span class="text-xs font-normal text-gray-500">({{ $tot[$kunci . '_persen'] }}%)</span></td>
                        @endforeach
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 text-xs font-normal text-gray-600">
                                <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-600"></span>Setuju</span>
                                <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span>Tidak</span>
                                <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-gray-400"></span>Belum</span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</section>
