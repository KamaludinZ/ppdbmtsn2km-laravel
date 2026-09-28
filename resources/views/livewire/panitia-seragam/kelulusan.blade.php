<div>
    <!-- Global Loading Overlay -->
    <div wire:loading.delay.longest class="fixed inset-0 bg-gray-900/50 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Memproses...</span>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{
                show: false,
                message: '',
                type: 'success',
                init() {
                    window.addEventListener('statusUpdated', (event) => {
                        this.type = event.detail[0].type || 'success';
                        this.message = event.detail[0].message;
                        this.show = true;
                        setTimeout(() => this.show = false, 4000);
                    });
                }
            }"
            x-show="show"
            x-transition
            :class="{
                'bg-emerald-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-blue-500': type === 'info'
            }"
            class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center max-w-md"
            style="display: none;">
            <svg x-show="type === 'success'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span x-text="message" class="flex-1"></span>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Kelulusan</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div class="flex-1">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NISN, atau no. pendaftaran..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select wire:model.live="filterJalur" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="filterStatus" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="lulus">Lulus</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                            <option value="cadangan">Cadangan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview - Status Kelulusan -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Menunggu</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['menunggu'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lulus -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Lulus</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['lulus'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cadangan -->
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Cadangan</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['cadangan'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tidak Lulus -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium opacity-90">Tidak Lulus</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($stats['tidak_lulus'] ?? 0) }}</p>
                    </div>
                    <div class="bg-black/10 rounded-full p-3 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Pendaftar & Status Kelulusan</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-sortable-header field="nomor_pendaftaran" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nomor Pendaftaran
                        </x-sortable-header>
                        <x-sortable-header field="nama_lengkap" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pendaftar
                        </x-sortable-header>
                        <x-sortable-header field="jenis_kelamin" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kelamin
                        </x-sortable-header>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <x-sortable-header field="status_pendaftaran" :sort-field="$sortField" :sort-direction="$sortDirection" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status Saat Ini
                        </x-sortable-header>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendaftar as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nomor_pendaftaran }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">NISN: {{ $item->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->jenis_kelamin)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->jalurPendaftaran->nama ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'lulus' => 'bg-green-100 text-green-800',
                                        'tidak_lulus' => 'bg-red-100 text-red-800',
                                        'cadangan' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $color = $colors[$item->status_pendaftaran] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status_pendaftaran)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data pendaftar ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendaftar->links() }}
            </div>
        </div>
    </div>
</div>
