<div>
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Jalur Pendaftaran</h2>
        <p class="text-gray-600">Kelola informasi dan konten dinamis untuk setiap jalur pendaftaran.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Jalur</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Jalur Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['aktif'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Kuota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_kuota'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Jalur</h3>
            </div>
            <button wire:click="create" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-emerald-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="create">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <svg class="animate-spin w-4 h-4 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" wire:loading wire:target="create">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="create">Tambah Jalur</span>
                <span wire:loading wire:target="create">Loading...</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-sortable-header field="nama" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Nama Jalur
                        </x-sortable-header>
                        <x-sortable-header field="kode" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Kode
                        </x-sortable-header>
                        <x-sortable-header field="urutan" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Urutan
                        </x-sortable-header>
                        <x-sortable-header field="prefix_nomor" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Prefix
                        </x-sortable-header>
                        <x-sortable-header field="kuota" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Kuota
                        </x-sortable-header>
                        <x-sortable-header field="nilai_minimum" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Nilai Minimal
                        </x-sortable-header>
                        <x-sortable-header field="aktif" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Status
                        </x-sortable-header>
                        <x-sortable-header field="tampil_di_daftar" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Status Daftar
                        </x-sortable-header>
                        <x-sortable-header field="paling_populer" :sort-field="$sortField" :sort-direction="$sortDirection">
                            Paling Populer
                        </x-sortable-header>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jalurPendaftaran as $jalur)
                    <tr wire:key="jalur-{{ $jalur->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $jalur->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jalur->kode }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold text-sm">
                                {{ $jalur->urutan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($jalur->prefix_nomor)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $jalur->prefix_nomor }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($jalur->kuota == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Tidak Terbatas
                                </span>
                            @else
                                {{ number_format($jalur->kuota, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-medium">{{ number_format($jalur->nilai_minimum, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jalur->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $jalur->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jalur->tampil_di_daftar ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $jalur->tampil_di_daftar ? 'Tampil' : 'Tersembunyi' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($jalur->paling_populer)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⭐ Ya
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Tidak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $jalur->id }})" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="text-blue-600 hover:text-blue-900 mr-3">
                                <span wire:loading.remove wire:target="edit({{ $jalur->id }})">Edit</span>
                                <span wire:loading wire:target="edit({{ $jalur->id }})" class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                            @if(Auth::user()->role === 'admin')
                                <button wire:click="delete({{ $jalur->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus jalur ini?"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="text-red-600 hover:text-red-900">
                                    <span wire:loading.remove wire:target="delete({{ $jalur->id }})">Hapus</span>
                                    <span wire:loading wire:target="delete({{ $jalur->id }})" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $jalurPendaftaran->links() }}
        </div>
    </div>

    <!-- Persyaratan Umum Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Persyaratan Umum</h3>
            </div>
            <button wire:click="createSyarat" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-blue-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="createSyarat">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <svg class="animate-spin w-4 h-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" wire:loading wire:target="createSyarat">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="createSyarat">Tambah Persyaratan</span>
                <span wire:loading wire:target="createSyarat">Loading...</span>
            </button>
        </div>
        <div class="p-4 bg-blue-50 border-b border-blue-100">
            <p class="text-sm text-blue-800">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                Persyaratan umum akan ditampilkan di semua halaman detail jalur pendaftaran
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persyaratan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($persyaratanUmum as $index => $syarat)
                    <tr wire:key="syarat-{{ $syarat->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $persyaratanUmum->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $syarat->persyaratan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $syarat->urutan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $syarat->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $syarat->aktif ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editSyarat({{ $syarat->id }})" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="text-blue-600 hover:text-blue-900 mr-3">
                                <span wire:loading.remove wire:target="editSyarat({{ $syarat->id }})">Edit</span>
                                <span wire:loading wire:target="editSyarat({{ $syarat->id }})" class="inline-flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading...
                                </span>
                            </button>
                            @if(Auth::user()->role === 'admin')
                                <button wire:click="deleteSyarat({{ $syarat->id }})"
                                        wire:confirm="Yakin ingin menghapus persyaratan ini?"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="text-red-600 hover:text-red-900">
                                    <span wire:loading.remove wire:target="deleteSyarat({{ $syarat->id }})">Hapus</span>
                                    <span wire:loading wire:target="deleteSyarat({{ $syarat->id }})" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Persyaratan</h3>
                            <p class="mt-1 text-sm text-gray-500">Tambahkan persyaratan umum yang berlaku untuk semua jalur.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $persyaratanUmum->links() }}
        </div>
    </div>

    <!-- Persyaratan Form Modal -->
    @if($showSyaratForm)
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="syarat-modal" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="cancelSyarat"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[10000]" role="dialog">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="syarat-modal">
                            {{ $isEditingSyarat ? 'Edit Persyaratan' : 'Tambah Persyaratan Baru' }}
                        </h3>
                        <form wire:submit.prevent="saveSyarat" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Persyaratan *</label>
                                <textarea
                                    wire:model="persyaratan"
                                    rows="3"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    placeholder="Masukkan persyaratan..."
                                ></textarea>
                                @error('persyaratan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                    <input
                                        type="number"
                                        wire:model="urutan_syarat"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                        min="0"
                                    >
                                </div>
                                <div class="flex items-center pt-6">
                                    <input
                                        type="checkbox"
                                        wire:model="aktif_syarat"
                                        id="syarat-aktif"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    >
                                    <label for="syarat-aktif" class="ml-2 block text-sm text-gray-900">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            wire:click="saveSyarat"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            wire:click="cancelSyarat"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="jalur-modal" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="closeModal"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                <div class="inline-block w-full max-w-5xl overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle z-[10000]" role="dialog">
                    <div class="bg-white">
                        <!-- Modal Header -->
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-xl font-bold text-gray-900" id="jalur-modal">{{ $isEdit ? 'Edit Jalur Pendaftaran' : 'Tambah Jalur Baru' }}</h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-100 overflow-x-auto">
                        <button wire:click="setTab('general')" class="px-6 py-3 text-sm font-medium transition-colors border-b-2 {{ $activeTab === 'general' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Umum</button>
                        <button wire:click="setTab('persyaratan')" class="px-6 py-3 text-sm font-medium transition-colors border-b-2 {{ $activeTab === 'persyaratan' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Persyaratan Khusus</button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        @if($activeTab === 'general')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jalur <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Jalur <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="kode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" {{ $isEdit ? 'disabled' : '' }}>
                                    @error('kode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prefix Nomor Pendaftaran</label>
                                    <input type="text" wire:model="prefix_nomor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: REG" maxlength="10">
                                    @error('prefix_nomor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Prefix untuk nomor pendaftaran. Format: <strong>PREFIX-YYMM0001</strong><br>
                                        <span class="ml-4">Contoh: <code class="bg-gray-100 px-1 py-0.5 rounded text-emerald-600">REG-25040001</code> (REG prefix, 25=tahun 2025, 04=April, 0001=urutan)</span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuota <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="kuota" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="0">
                                    @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <strong>0</strong> = Tidak Terbatas (unlimited), <strong>> 0</strong> = Dibatasi sesuai kuota
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Minimum <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" wire:model="nilai_minimum" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="0.00">
                                    @error('nilai_minimum') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">Nilai rata-rata minimum untuk mendaftar (0-100)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampilan <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="urutan" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="1">
                                    @error('urutan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Urutan jalur untuk ditampilkan di halaman beranda dan jalur pendaftaran
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna Tema <span class="text-red-500">*</span></label>
                                    <select wire:model="warna" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="blue">Blue</option>
                                        <option value="emerald">Emerald</option>
                                        <option value="purple">Purple</option>
                                        <option value="indigo">Indigo</option>
                                        <option value="orange">Orange</option>
                                        <option value="teal">Teal</option>
                                    </select>
                                    @error('warna') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Optional)</label>
                                    <input type="text" wire:model="ikon" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="default">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                    <textarea wire:model="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Deskripsi singkat untuk ditampilkan di kartu jalur"></textarea>
                                </div>
                                <div class="flex items-center gap-6 md:col-span-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="aktif" class="w-4 h-4 text-emerald-600 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Jalur Aktif</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="tampil_di_daftar" class="w-4 h-4 text-blue-600 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Tampil di Halaman Daftar</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="paling_populer" class="w-4 h-4 text-emerald-600 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Paling Populer</span>
                                    </label>
                                </div>
                            </div>

                        @elseif($activeTab === 'persyaratan')
                            <div class="space-y-6">
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                    <p class="text-sm text-blue-700">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Persyaratan khusus akan ditampilkan di halaman detail jalur ini, halaman daftar jalur, dan di beranda.
                                    </p>
                                </div>

                                <!-- Form Tambah Persyaratan -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                    <div class="flex gap-3">
                                        <input
                                            type="text"
                                            wire:model="new_persyaratan_khusus"
                                            wire:keydown.enter="addPersyaratanKhusus"
                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Ketik persyaratan khusus baru...">
                                        <button
                                            wire:click="addPersyaratanKhusus"
                                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Tekan Enter atau klik tombol + untuk menambahkan</p>
                                </div>

                                <!-- Daftar Persyaratan -->
                                @if(count($persyaratan_khusus) > 0)
                                <div class="space-y-2">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Daftar Persyaratan Khusus ({{ count($persyaratan_khusus) }})</h4>
                                    @foreach($persyaratan_khusus as $index => $syarat)
                                    <div wire:key="persyaratan-khusus-{{ $index }}" class="flex items-start gap-3 bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition group">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800">{{ $syarat }}</p>
                                        </div>
                                        <button
                                            wire:click="removePersyaratanKhusus({{ $index }})"
                                            class="flex-shrink-0 p-1.5 text-red-600 hover:bg-red-50 rounded transition opacity-0 group-hover:opacity-100"
                                            title="Hapus persyaratan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Belum ada persyaratan khusus</p>
                                    <p class="text-xs text-gray-400 mt-1">Tambahkan persyaratan khusus untuk jalur ini</p>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button wire:click="closeModal" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                        <button wire:click="{{ $isEdit ? 'update' : 'store' }}" type="button" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md">
                            <span wire:loading.remove wire:target="{{ $isEdit ? 'update' : 'store' }}">Simpan Perubahan</span>
                            <span wire:loading wire:target="{{ $isEdit ? 'update' : 'store' }}">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>