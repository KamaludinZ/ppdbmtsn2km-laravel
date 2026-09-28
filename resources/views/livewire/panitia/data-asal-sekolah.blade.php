<div>
    <!-- Notification -->
    @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Data Asal Sekolah</h2>
                    <p class="text-gray-600 mt-1">Data sekolah asal pendaftar (otomatis tersinkron dari data pendaftar)</p>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari nama sekolah, NPSN, NSM..."
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 "
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                    <select wire:model.live="perPage" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 ">
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                        <option value="100">100 per halaman</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Sekolah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('nama_sekolah')" class="flex items-center">
                                    Nama Sekolah
                                    @if($sortBy === 'nama_sekolah')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('npsn')" class="flex items-center">
                                    NPSN
                                    @if($sortBy === 'npsn')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NSM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pendaftar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($asalSekolah as $index => $sekolah)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $asalSekolah->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $sekolah->jenis_sekolah_asal == 'madrasah' ? 'emerald' : 'gray' }}-100 text-{{ $sekolah->jenis_sekolah_asal == 'madrasah' ? 'emerald' : 'gray' }}-800">
                                        {{ $sekolah->jenis_sekolah_asal == 'madrasah' ? 'Madrasah' : 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button
                                        wire:click="showDetail({{ json_encode($sekolah->nama_sekolah) }})"
                                        class="text-left hover:text-emerald-600 transition"
                                    >
                                        <div class="text-sm font-medium text-emerald-700 hover:text-emerald-900 cursor-pointer underline">
                                            {{ $sekolah->nama_sekolah }}
                                        </div>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $sekolah->npsn }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $sekolah->nsm ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $sekolah->pendaftar_count ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data sekolah</h3>
                                    <p class="mt-1 text-sm text-gray-500">Data akan otomatis muncul saat ada pendaftar yang mengisi data sekolah asal.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $asalSekolah->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail Siswa -->
    @if($showDetailModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeModal"></div>

            <div class="relative bg-white rounded-lg shadow-xl transform transition-all w-full max-w-4xl z-[10000] my-8">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 rounded-t-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-white">Daftar Siswa</h3>
                                <p class="text-sm text-emerald-100">{{ $selectedSekolah }}</p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="text-white hover:text-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                    @if(count($pendaftarList) > 0)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Total:</strong> {{ count($pendaftarList) }} siswa terdaftar dari sekolah ini
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendaftarList as $index => $pendaftar)
                                        @php
                                            $statusBadge = [
                                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                                'blue' => 'bg-blue-100 text-blue-800',
                                                'green' => 'bg-green-100 text-green-800',
                                                'red' => 'bg-red-100 text-red-800',
                                                'gray' => 'bg-gray-100 text-gray-800',
                                            ];
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $pendaftar['nama'] }}</div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                {{ $pendaftar['nisn'] }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $pendaftar['jalur'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge[$pendaftar['status_color']] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $pendaftar['status_label'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                <a href="/panitia/pendaftar/{{ $pendaftar['id'] }}" class="text-emerald-600 hover:text-emerald-900 font-medium">
                                                    Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Tidak ada data siswa</p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <button
                        wire:click="closeModal"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>