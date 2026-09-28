<div @if($autoRefresh) wire:poll.{{ $refreshInterval }}s="refresh" @endif>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Log Monitoring</h1>
                <p class="text-sm text-gray-500">Pantau dan analisis log aplikasi</p>
            </div>
            <button wire:click="refresh" wire:loading.attr="disabled" wire:target="refresh"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 disabled:opacity-60">
                <svg class="h-4 w-4" wire:loading.class="animate-spin" wire:target="refresh" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @php
                $diskKritis = $logStats['disk_used_percentage'] > 90;
                $kartu = [
                    ['Total File Log', $logStats['total_files'], 'text-gray-900'],
                    ['Ukuran Log', $logStats['total_size'], 'text-gray-900'],
                    ['Sisa Disk', $logStats['disk_free'], 'text-gray-900'],
                    ['Pemakaian Disk', $logStats['disk_used_percentage'] . '%', $diskKritis ? 'text-red-600' : 'text-gray-900'],
                ];
            @endphp
            @foreach($kartu as [$judul, $nilai, $warna])
                <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4">
                    <p class="text-xs font-medium text-gray-500">{{ $judul }}</p>
                    <p class="mt-1 truncate text-2xl font-bold tabular-nums {{ $warna }}">{{ $nilai }}</p>
                </div>
            @endforeach
        </div>

        @if($diskKritis)
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                <strong>Peringatan:</strong> ruang disk hampir penuh. Pertimbangkan menjalankan <code class="rounded bg-red-100 px-1">php artisan log:cleanup</code>.
            </div>
        @endif

        <!-- Error terbaru -->
        @if(count($recentErrors) > 0)
            <div class="rounded-xl border border-red-200 bg-white">
                <div class="border-b border-red-100 px-4 py-3">
                    <h2 class="text-sm font-semibold text-red-700">Error Terbaru (10 terakhir)</h2>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach($recentErrors as $error)
                        <li class="px-4 py-2 font-mono text-xs text-red-700 break-all">{{ \Illuminate\Support\Str::limit($error, 200) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Log viewer -->
        <div class="rounded-xl border border-gray-200 bg-white">
            <div class="grid grid-cols-1 gap-3 border-b border-gray-200 p-4 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Jenis Log</label>
                    <select wire:model.live="selectedLog" class="w-full rounded-lg border border-gray-300 text-sm">
                        @forelse($availableLogs as $log)
                            <option value="{{ $log }}">{{ ucfirst($log) }}</option>
                        @empty
                            <option value="laravel">Laravel</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Level</label>
                    <select wire:model.live="selectedLevel" class="w-full rounded-lg border border-gray-300 text-sm">
                        <option value="all">Semua Level</option>
                        @foreach(['debug', 'info', 'warning', 'error', 'critical', 'emergency'] as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Tanggal</label>
                    <input type="date" wire:model.live="selectedDate" class="w-full rounded-lg border border-gray-300 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Cari</label>
                    <input type="text" wire:model.live.debounce.500ms="searchTerm" class="w-full rounded-lg border border-gray-300 text-sm" placeholder="Cari di log...">
                </div>
                <div class="flex flex-wrap justify-end gap-2 sm:col-span-2 lg:col-span-4">
                    <button wire:click="downloadLog" title="Unduh log" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Unduh</button>
                    <button wire:click="clearLog" wire:confirm="Kosongkan isi file log ini?" title="Kosongkan log" class="rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800 hover:bg-amber-100">Kosongkan</button>
                    <button wire:click="deleteLog" wire:confirm="Hapus file log ini?" title="Hapus log" class="rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">Hapus</button>
                </div>
            </div>

            <pre class="max-h-[600px] overflow-auto bg-gray-900 p-4 font-mono text-xs leading-relaxed text-gray-100 whitespace-pre-wrap break-all">{{ $logContent ?: 'Tidak ada isi log.' }}</pre>

            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-200 px-4 py-3 text-sm">
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input type="checkbox" wire:model.live="autoRefresh" class="h-4 w-4 rounded border-gray-300 text-emerald-600">
                    Refresh otomatis setiap {{ $refreshInterval }} detik
                </label>
                <span class="text-xs text-gray-500">Menampilkan 500 baris terakhir</span>
            </div>
        </div>
    </div>
</div>
