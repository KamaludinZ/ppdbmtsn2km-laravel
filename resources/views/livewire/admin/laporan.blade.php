@php
    // Kelas ditulis lengkap agar ikut ter-compile Tailwind
    $inputClass = 'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30';
    $labelClass = 'mb-1 block text-xs font-medium text-gray-600';
    $warnaJalur = [
        'emerald' => ['#d1fae5', '#065f46', '#10b981'], 'green' => ['#d1fae5', '#065f46', '#10b981'],
        'blue' => ['#dbeafe', '#1e3a8a', '#3b82f6'], 'purple' => ['#ede9fe', '#5b21b6', '#8b5cf6'],
        'pink' => ['#fce7f3', '#831843', '#ec4899'], 'red' => ['#fee2e2', '#7f1d1d', '#ef4444'],
        'orange' => ['#ffedd5', '#7c2d12', '#f97316'], 'yellow' => ['#fef3c7', '#713f12', '#eab308'],
        'indigo' => ['#e0e7ff', '#312e81', '#6366f1'], 'teal' => ['#ccfbf1', '#134e4a', '#14b8a6'],
    ];
    $kategoriRekap = \App\Support\RekapDaftarUlang::KATEGORI;
@endphp

<div class="space-y-6">
    {{-- Tab: bisa digeser di layar sempit, tidak mendorong isi halaman --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex gap-1 overflow-x-auto" role="tablist" aria-label="Jenis laporan"
            x-data x-init="$el.querySelector('[aria-selected=true]')?.scrollIntoView({ block: 'nearest', inline: 'center' })">
            @foreach($tabs as $key => $label)
                <button type="button" role="tab" wire:click="switchTab('{{ $key }}')"
                    @click="$el.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' })"
                    aria-selected="{{ $activeTab === $key ? 'true' : 'false' }}"
                    @class([
                        'shrink-0 whitespace-nowrap border-b-2 px-3 py-3 text-sm font-medium transition',
                        'border-emerald-600 text-emerald-700' => $activeTab === $key,
                        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' => $activeTab !== $key,
                    ])>
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="relative">
        {{-- Indikator memuat saat pindah tab / ganti filter --}}
        <div wire:loading.delay class="absolute inset-0 z-20 rounded-xl bg-white/70" aria-live="polite">
            <div class="sticky top-24 mx-auto mt-24 flex w-fit items-center gap-3 rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm">
                <svg class="h-5 w-5 animate-spin text-emerald-600" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Memuat data…
            </div>
        </div>

        @if(session()->has('message'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('message') }}</div>
        @endif

        {{-- ================= LAPORAN PENDAFTARAN ================= --}}
        @if($activeTab === 'pendaftaran')
            <div class="space-y-6" wire:key="tab-{{ $activeTab }}">
                <section class="rounded-xl border border-gray-200 bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Laporan Pendaftaran</h2>
                            <p class="text-sm text-gray-500">Rekap status verifikasi dan statistik per jalur.</p>
                        </div>
                        @php $paramExport = array_filter(['jalur' => $filterJalur, 'start_date' => $tanggalMulai, 'end_date' => $tanggalAkhir]); @endphp
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('export.excel', $paramExport) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2V8l-6-6H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Excel
                            </a>
                            <a href="{{ route('export.pdf', $paramExport) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2V8l-6-6H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                PDF
                            </a>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                        <div>
                            <label for="lap-mulai" class="{{ $labelClass }}">Tanggal Mulai</label>
                            <input id="lap-mulai" type="date" wire:model.live="tanggalMulai" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="lap-akhir" class="{{ $labelClass }}">Tanggal Akhir</label>
                            <input id="lap-akhir" type="date" wire:model.live="tanggalAkhir" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="lap-jalur" class="{{ $labelClass }}">Jalur</label>
                            <select id="lap-jalur" wire:model.live="filterJalur" class="{{ $inputClass }}">
                                <option value="">Semua Jalur</option>
                                @foreach($jalurPendaftaran as $jalur)
                                    <option value="{{ $jalur->id }}">{{ $jalur->nama }}{{ $jalur->aktif ? '' : ' (nonaktif)' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="button" wire:click="resetFilters" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset Filter</button>
                        </div>
                    </div>
                </section>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
                    <x-stat-card color="blue" label="Total Pendaftar" :value="$stats['total']" />
                    <x-stat-card color="yellow" label="Menunggu Verifikasi" :value="$stats['menunggu_verifikasi']" />
                    <x-stat-card color="orange" label="Lengkapi Data" :value="$stats['lengkapi_data']" />
                    <x-stat-card color="amber" label="Perbaiki Data" :value="$stats['perbaiki_data']" />
                    <x-stat-card color="red" label="Data Ditolak" :value="$stats['data_ditolak']" />
                    <x-stat-card color="green" label="Data Disetujui" :value="$stats['data_disetujui']" />
                </div>

                {{-- Statistik per jalur --}}
                <section class="rounded-xl border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-gray-900">Statistik per Jalur</h2>
                        <p class="text-sm text-gray-500">Jalur yang sudah dinonaktifkan tetap tampil bila masih memiliki pendaftar, sehingga totalnya sama dengan jumlah pendaftar.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="sticky left-0 bg-gray-50 px-4 py-3 text-left">Jalur</th>
                                    @foreach(['Kuota', 'Total', 'Menunggu', 'Lengkapi', 'Perbaiki', 'Ditolak', 'Disetujui', 'Lulus', 'Tdk Lulus', 'Cadangan', 'L', 'P', 'MI', 'SD'] as $kolom)
                                        <th class="px-3 py-3 text-center">{{ $kolom }}</th>
                                    @endforeach
                                    <th class="px-4 py-3 text-left">Keterisian Kuota</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($jalurStats as $jalur)
                                    @php
                                        [$bg, $fg, $bar] = $warnaJalur[$jalur->warna ?? 'blue'] ?? $warnaJalur['blue'];
                                        $persen = $jalur->kuota > 0 ? round($jalur->pendaftar_count / $jalur->kuota * 100, 1) : 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="sticky left-0 bg-white px-4 py-3">
                                            <span class="inline-block whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-semibold" style="background-color: {{ $bg }}; color: {{ $fg }};">{{ $jalur->nama }}</span>
                                            @if($jalur->nonaktif)
                                                <span class="ml-1 whitespace-nowrap rounded bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-600">nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($jalur->kuota) }}</td>
                                        <td class="px-3 py-3 text-center font-semibold tabular-nums text-gray-900">{{ number_format($jalur->pendaftar_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-amber-700">{{ number_format($jalur->menunggu_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-orange-700">{{ number_format($jalur->lengkapi_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-orange-700">{{ number_format($jalur->perbaiki_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-red-700">{{ number_format($jalur->ditolak_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-emerald-700">{{ number_format($jalur->disetujui_count) }}</td>
                                        <td class="px-3 py-3 text-center font-semibold tabular-nums text-emerald-700">{{ number_format($jalur->lulus_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-rose-700">{{ number_format($jalur->tidak_lulus_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-blue-700">{{ number_format($jalur->cadangan_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($jalur->laki_laki_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($jalur->perempuan_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($jalur->madrasah_count) }}</td>
                                        <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($jalur->umum_count) }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex min-w-[9rem] items-center gap-2">
                                                <div class="h-2 flex-1 rounded-full bg-gray-100"><div class="h-2 rounded-full" style="width: {{ min($persen, 100) }}%; background-color: {{ $bar }};"></div></div>
                                                <span class="w-12 text-right text-xs font-semibold tabular-nums text-gray-700">{{ $persen }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="16" class="px-4 py-8 text-center text-gray-500">Belum ada data jalur.</td></tr>
                                @endforelse
                            </tbody>
                            @if($jalurStats->count() > 1)
                                <tfoot class="border-t-2 border-gray-200 bg-gray-50 font-semibold text-gray-900">
                                    <tr>
                                        <td class="sticky left-0 bg-gray-50 px-4 py-3">Total</td>
                                        @foreach(['kuota', 'pendaftar_count', 'menunggu_count', 'lengkapi_count', 'perbaiki_count', 'ditolak_count', 'disetujui_count', 'lulus_count', 'tidak_lulus_count', 'cadangan_count', 'laki_laki_count', 'perempuan_count', 'madrasah_count', 'umum_count'] as $kolom)
                                            <td class="px-3 py-3 text-center tabular-nums">{{ number_format($jalurStats->sum($kolom)) }}</td>
                                        @endforeach
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </section>

                {{-- Statistik per tahun --}}
                <section class="rounded-xl border border-gray-200 bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Statistik per Tahun</h2>
                            <p class="text-sm text-gray-500">Arsip rekap tiap tahun pelajaran. Klik baris untuk melihat rincian per jalur.</p>
                        </div>
                        <button type="button" wire:click="simpanStatistikTahun" wire:confirm="Simpan (timpa) statistik tahun {{ date('Y') }} dengan data saat ini?"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Simpan Statistik Tahun Ini
                        </button>
                    </div>
                    @if($statistikTahun->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Tahun</th>
                                        @foreach(['Kuota', 'Total', 'Menunggu', 'Lengkapi', 'Perbaiki', 'Ditolak', 'Disetujui', 'Lulus', 'Tdk Lulus', 'Cadangan', 'L', 'P', 'MI', 'SD'] as $kolom)
                                            <th class="px-3 py-3 text-center">{{ $kolom }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($statistikTahun as $stat)
                                        @php $isExpanded = in_array($stat->tahun, $expandedYears); @endphp
                                        <tr class="cursor-pointer hover:bg-gray-50" wire:click="toggleTahunDetail({{ $stat->tahun }})" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
                                            <td class="px-4 py-3 font-semibold text-gray-900">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="h-4 w-4 transition-transform {{ $isExpanded ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    {{ $stat->tahun }}
                                                </span>
                                            </td>
                                            @foreach(['total_kuota', 'total_pendaftar', 'menunggu', 'lengkapi', 'perbaiki', 'ditolak', 'disetujui', 'lulus', 'tidak_lulus', 'cadangan', 'laki_laki', 'perempuan', 'madrasah', 'umum'] as $kolom)
                                                <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($stat->{$kolom}) }}</td>
                                            @endforeach
                                        </tr>
                                        @if($isExpanded)
                                            @forelse($stat->jalurStats as $jalurStat)
                                                <tr class="bg-gray-50/70 text-xs">
                                                    <td class="py-2 pl-10 pr-4 text-gray-700">{{ $jalurStat->nama_jalur }}</td>
                                                    @foreach(['kuota', 'total', 'menunggu', 'lengkapi', 'perbaiki', 'ditolak', 'disetujui', 'lulus', 'tidak_lulus', 'cadangan', 'laki_laki', 'perempuan', 'madrasah', 'umum'] as $kolom)
                                                        <td class="px-3 py-2 text-center tabular-nums text-gray-600">{{ number_format($jalurStat->{$kolom}) }}</td>
                                                    @endforeach
                                                </tr>
                                            @empty
                                                <tr class="bg-gray-50/70"><td colspan="15" class="py-2 pl-10 text-xs text-gray-500">Tidak ada rincian jalur.</td></tr>
                                            @endforelse
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="px-5 py-10 text-center">
                            <p class="font-medium text-gray-700">Belum ada statistik tahun yang disimpan</p>
                            <p class="mt-1 text-sm text-gray-500">Klik "Simpan Statistik Tahun Ini" untuk menyimpan rekap pertama.</p>
                        </div>
                    @endif
                </section>
            </div>

        {{-- ================= DATA PENDAFTAR LENGKAP ================= --}}
        @elseif($activeTab === 'data_lengkap')
            @php $s = $dataLengkapStats; @endphp
            <div class="space-y-6" wire:key="tab-{{ $activeTab }}">
                @include('livewire.admin.partials.laporan-filter', [
                    'judul' => 'Data Pendaftar Lengkap',
                    'deskripsi' => 'Export seluruh isian formulir (lebih dari 200 kolom) sesuai filter jalur dan status kelulusan.',
                    'jumlahExport' => $s['total'],
                    'filterStatus' => ['model' => 'filterStatusKelulusan', 'label' => 'Status Kelulusan', 'semua' => 'Semua Status', 'opsi' => ['lulus' => 'Lulus / Diterima', 'tidak_lulus' => 'Tidak Lulus', 'cadangan' => 'Cadangan']],
                ])

                <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                    <x-stat-card color="blue" label="Pendaftar (sesuai filter)" :value="$s['total']"
                        :sub="'dari ' . number_format($s['total_pendaftar']) . ' pendaftar' . ($filterJalur ? ' di jalur ini' : '')" />
                    <x-stat-card color="indigo" label="Rata-rata Kelengkapan" :value="$s['rata_rata'] . '%'">
                        <div class="mt-2 h-1.5 rounded-full bg-white/30"><div class="h-1.5 rounded-full bg-white" style="width: {{ $s['rata_rata'] }}%"></div></div>
                    </x-stat-card>
                    <x-stat-card color="emerald" label="Formulir 100% Lengkap" :value="$s['semua_lengkap']"
                        :sub="($s['total'] > 0 ? round($s['semua_lengkap'] / $s['total'] * 100) : 0) . '% dari pendaftar'" />
                    <div class="min-w-0 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 p-4 text-white shadow-sm">
                        <p class="text-xs font-medium text-white/90">Status Kelulusan</p>
                        <dl class="mt-1 space-y-0.5 text-sm">
                            <div class="flex justify-between gap-2"><dt class="text-white/85">Lulus</dt><dd class="font-semibold tabular-nums">{{ number_format($s['lulus']) }}</dd></div>
                            <div class="flex justify-between gap-2"><dt class="text-white/85">Tidak lulus</dt><dd class="font-semibold tabular-nums">{{ number_format($s['tidak_lulus']) }}</dd></div>
                            <div class="flex justify-between gap-2"><dt class="text-white/85">Cadangan</dt><dd class="font-semibold tabular-nums">{{ number_format($s['cadangan']) }}</dd></div>
                        </dl>
                    </div>
                </div>

                <section class="rounded-xl border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-gray-900">Kelengkapan per Bagian Formulir</h2>
                        <p class="text-sm text-gray-500">Jumlah pendaftar yang bagian tersebut sudah terisi 100%. Pendaftar yang tidak wajib mengisi bagian itu (mis. tidak punya wali) tidak dihitung.</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach(\App\Livewire\Admin\Laporan::BAGIAN_FORMULIR as $key => $label)
                            @php
                                $lengkap = $s['bagian'][$key] ?? 0;
                                $berlaku = $s['berlaku'][$key] ?? 0;
                                $persen = $berlaku > 0 ? round($lengkap / $berlaku * 100) : 0;
                            @endphp
                            <div class="grid grid-cols-1 gap-2 px-5 py-3 sm:grid-cols-[12rem_1fr_9rem] sm:items-center">
                                <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                                <div class="h-2 rounded-full bg-gray-100"><div class="h-2 rounded-full {{ $persen >= 80 ? 'bg-emerald-600' : ($persen >= 40 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $persen }}%"></div></div>
                                <p class="text-sm tabular-nums text-gray-700 sm:text-right">
                                    @if($berlaku > 0)
                                        <span class="font-semibold text-gray-900">{{ number_format($lengkap) }}</span> / {{ number_format($berlaku) }} <span class="text-gray-500">({{ $persen }}%)</span>
                                    @else
                                        <span class="text-gray-400">tidak berlaku</span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-1 gap-3 border-t border-gray-100 px-5 py-4 sm:grid-cols-3">
                        @foreach(\App\Livewire\Admin\Laporan::BAGIAN_OPSIONAL as $key => $label)
                            <div class="rounded-lg bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-500">Mengisi {{ $label }} <span class="text-gray-400">(opsional)</span></p>
                                <p class="mt-0.5 text-lg font-semibold tabular-nums text-gray-900">{{ number_format($s['bagian'][$key] ?? 0) }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <details class="rounded-xl border border-gray-200 bg-white px-5 py-4 text-sm text-gray-600">
                    <summary class="cursor-pointer font-medium text-gray-800">Kolom yang ada di file export</summary>
                    <ul class="mt-3 grid grid-cols-1 gap-x-6 gap-y-1.5 text-xs md:grid-cols-2">
                        <li><strong class="text-gray-700">Identitas:</strong> nomor pendaftaran, jalur, status, tanggal daftar</li>
                        <li><strong class="text-gray-700">Data siswa:</strong> identitas, kewarganegaraan, keluarga, kontak, pra sekolah, imunisasi, KIP/KK, asal sekolah</li>
                        <li><strong class="text-gray-700">Data ayah, ibu, wali:</strong> status, identitas, TTL, pendidikan, pekerjaan, penghasilan, kontak, KKS/PKH</li>
                        <li><strong class="text-gray-700">Alamat siswa, ayah, ibu, wali:</strong> wilayah lengkap, RT/RW, kode pos, jarak, transportasi</li>
                        <li><strong class="text-gray-700">Prestasi, beasiswa, pendidikan lain:</strong> masing-masing s.d. 3 data</li>
                        <li><strong class="text-gray-700">Berkas & nilai rapor:</strong> status 8 dokumen, nilai rapor dan rata-rata</li>
                        <li><strong class="text-gray-700">Survey:</strong> jawaban semua pertanyaan survey aktif</li>
                        <li><strong class="text-gray-700">Status:</strong> pendaftaran, tes psikologi, kelulusan, daftar ulang madrasah, seragam, ukuran</li>
                    </ul>
                </details>
            </div>

        {{-- ================= DAFTAR ULANG MADRASAH / SERAGAM / UKURAN ================= --}}
        @elseif(in_array($activeTab, ['daftar_ulang', 'daftar_ulang_seragam', 'seragam'], true))
            @php
                $konfig = [
                    'daftar_ulang' => [
                        'judul' => 'Daftar Ulang Madrasah',
                        'deskripsi' => 'Semua pendaftar lulus/diterima beserta status daftar ulangnya, termasuk yang belum konfirmasi atau tidak melanjutkan.',
                        'tab' => 'madrasah', 'properti' => 'filterStatusDU', 'jk' => false,
                        'isi' => 'File berisi 3 sheet (Daftar Ulang Madrasah, Daftar Ulang Seragam, Ukuran Seragam) dengan daftar pendaftar yang sama.',
                    ],
                    'daftar_ulang_seragam' => [
                        'judul' => 'Daftar Ulang Seragam',
                        'deskripsi' => 'Semua pendaftar lulus/diterima beserta status pembayaran dan pemesanan seragam.',
                        'tab' => 'seragam', 'properti' => 'filterStatusDUSeragam', 'jk' => true,
                        'isi' => 'Kolom: data siswa, status persetujuan, paket & opsi seragam, nominal, pembayaran, status rekap, status verifikasi, catatan.',
                    ],
                    'seragam' => [
                        'judul' => 'Seragam & Ukuran',
                        'deskripsi' => 'Ukuran seragam semua pendaftar lulus/diterima untuk keperluan pemesanan. Yang belum mengisi tetap tercantum.',
                        'tab' => 'ukuran', 'properti' => 'filterStatusSeragam', 'jk' => true,
                        'isi' => 'Kolom: data siswa, ukuran jas, baju & celana olahraga, baju & celana/rok harian, kopyah, paket, status rekap, status ukuran & pembayaran.',
                    ],
                ][$activeTab];
                $statusAktif = $this->{$konfig['properti']};
                $jumlahExport = $statusAktif ? (int) ($rekapStats[$statusAktif] ?? 0) : (int) ($rekapStats['total_wajib'] ?? 0);
            @endphp
            <div class="space-y-6" wire:key="tab-{{ $activeTab }}">
                @include('livewire.admin.partials.laporan-filter', [
                    'judul' => $konfig['judul'],
                    'deskripsi' => $konfig['deskripsi'],
                    'jumlahExport' => $jumlahExport,
                    'tampilJenisKelamin' => $konfig['jk'],
                    'filterStatus' => ['model' => $konfig['properti'], 'label' => 'Status Rekap', 'semua' => 'Semua Status Rekap', 'opsi' => collect($kategoriRekap)->map(fn ($k) => $k['label'])->all()],
                ])

                @include('livewire.admin.partials.daftar-ulang-stats', [
                    'stats' => $rekapStats,
                    'tab' => $konfig['tab'],
                    'clickable' => true,
                    'filterProperty' => $konfig['properti'],
                    'clickLabel' => 'klik kategori untuk memfilter export',
                ])

                @if($activeTab === 'daftar_ulang')
                    @include('livewire.admin.partials.laporan-persetujuan')
                @endif

                <p class="text-xs text-gray-500">{{ $konfig['isi'] }}</p>
            </div>

        {{-- ================= TES PSIKOLOGI ================= --}}
        @elseif($activeTab === 'tes_psikologi')
            @php $st = $tesPsikologiStats['stats']; @endphp
            <div class="space-y-6" wire:key="tab-{{ $activeTab }}">
                @include('livewire.admin.partials.laporan-filter', [
                    'judul' => 'Tes Psikologi',
                    'deskripsi' => 'Status pembayaran/bukti tes psikologi per jalur.',
                    'jumlahExport' => $st['total'],
                    'filterStatus' => ['model' => 'filterStatusTesPsikologi', 'label' => 'Status Verifikasi', 'semua' => 'Semua Status', 'opsi' => \App\Livewire\Admin\Laporan::STATUS_PSIKOLOGI],
                ])

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
                    <x-stat-card color="purple" label="Total" :value="$st['total']" />
                    <x-stat-card color="gray" label="Belum Upload" :value="$st['belum_upload']" />
                    <x-stat-card color="yellow" label="Menunggu Verifikasi" :value="$st['menunggu']" />
                    <x-stat-card color="green" label="Disetujui" :value="$st['disetujui']" />
                    <x-stat-card color="red" label="Ditolak" :value="$st['ditolak']" />
                    <x-stat-card color="orange" label="Perlu Perbaikan" :value="$st['perbaiki']" />
                </div>

                <section class="rounded-xl border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-base font-semibold text-gray-900">Statistik Tes Psikologi per Jalur</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Jalur</th>
                                    <th class="px-3 py-3 text-center">Total</th>
                                    @foreach(\App\Livewire\Admin\Laporan::STATUS_PSIKOLOGI as $label)
                                        <th class="px-3 py-3 text-center">{{ $label }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($tesPsikologiStats['jalur_stats'] as $baris)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                            {{ $baris['nama'] }}
                                            @if($baris['nonaktif'])<span class="ml-1 rounded bg-gray-100 px-1.5 py-0.5 text-[11px] font-medium text-gray-600">nonaktif</span>@endif
                                        </td>
                                        <td class="px-3 py-3 text-center font-semibold tabular-nums text-gray-900">{{ number_format($baris['total']) }}</td>
                                        @foreach(array_keys(\App\Livewire\Admin\Laporan::STATUS_PSIKOLOGI) as $key)
                                            <td class="px-3 py-3 text-center tabular-nums text-gray-700">{{ number_format($baris[$key]) }}</td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data tes psikologi.</td></tr>
                                @endforelse
                            </tbody>
                            @if(count($tesPsikologiStats['jalur_stats']) > 1)
                                <tfoot class="border-t-2 border-gray-200 bg-gray-50 font-semibold text-gray-900">
                                    <tr>
                                        <td class="px-4 py-3">Total</td>
                                        <td class="px-3 py-3 text-center tabular-nums">{{ number_format($st['total']) }}</td>
                                        @foreach(array_keys(\App\Livewire\Admin\Laporan::STATUS_PSIKOLOGI) as $key)
                                            <td class="px-3 py-3 text-center tabular-nums">{{ number_format($st[$key]) }}</td>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </section>
            </div>
        @endif
    </div>
</div>
