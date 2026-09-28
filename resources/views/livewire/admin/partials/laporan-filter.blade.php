{{--
    Kartu filter + tombol export untuk tab laporan admin.
    Parameter: $judul, $deskripsi, $jumlahExport, $filterStatus [model, label, semua, opsi], $tampilJenisKelamin (opsional).
    Memakai $exportUrl dan $jalurPendaftaran dari komponen.
--}}
@php
    $inputClass = $inputClass ?? 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30';
    $labelClass = $labelClass ?? 'mb-1 block text-xs font-medium text-gray-600';
    $tampilJenisKelamin = $tampilJenisKelamin ?? false;
    $idPrefix = 'f-' . $filterStatus['model'];
@endphp
<section class="rounded-xl border border-gray-200 bg-white" wire:key="filter-{{ $filterStatus['model'] }}">
    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-900">{{ $judul }}</h2>
            <p class="text-sm text-gray-500">{{ $deskripsi }}</p>
        </div>
        <div class="flex flex-col items-stretch gap-1 sm:items-end">
            @if($jumlahExport > 0)
                <a href="{{ $exportUrl }}"
                    x-data="{ menyiapkan: false }"
                    @click="menyiapkan = true; setTimeout(() => menyiapkan = false, 8000)"
                    :class="menyiapkan && 'pointer-events-none opacity-70'"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                    <svg x-show="!menyiapkan" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2V8l-6-6H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <svg x-show="menyiapkan" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="menyiapkan ? 'Menyiapkan file…' : 'Export Excel'">Export Excel</span>
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-400" title="Tidak ada data untuk diexport">Export Excel</span>
            @endif
            <p class="text-xs text-gray-500">{{ number_format($jumlahExport) }} baris akan diexport</p>
        </div>
    </div>
    <div @class(['grid grid-cols-1 gap-4 p-5 sm:grid-cols-2', 'lg:grid-cols-4' => $tampilJenisKelamin, 'lg:grid-cols-3' => !$tampilJenisKelamin, 'lg:items-end'])>
        <div>
            <label for="{{ $idPrefix }}-jalur" class="{{ $labelClass }}">Jalur Pendaftaran</label>
            <select id="{{ $idPrefix }}-jalur" wire:model.live="filterJalur" class="{{ $inputClass }}">
                <option value="">Semua Jalur</option>
                @foreach($jalurPendaftaran as $jalur)
                    <option value="{{ $jalur->id }}">{{ $jalur->nama }}{{ $jalur->aktif ? '' : ' (nonaktif)' }}</option>
                @endforeach
            </select>
        </div>
        @if($tampilJenisKelamin)
            <div>
                <label for="{{ $idPrefix }}-jk" class="{{ $labelClass }}">Jenis Kelamin</label>
                <select id="{{ $idPrefix }}-jk" wire:model.live="filterJenisKelamin" class="{{ $inputClass }}">
                    <option value="">Semua</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        @endif
        <div>
            <label for="{{ $idPrefix }}-status" class="{{ $labelClass }}">{{ $filterStatus['label'] }}</label>
            <select id="{{ $idPrefix }}-status" wire:model.live="{{ $filterStatus['model'] }}" class="{{ $inputClass }}">
                <option value="">{{ $filterStatus['semua'] }}</option>
                @foreach($filterStatus['opsi'] as $nilai => $label)
                    <option value="{{ $nilai }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="button" wire:click="resetFilters" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset Filter</button>
        </div>
    </div>
</section>
