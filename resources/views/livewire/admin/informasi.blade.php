<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{ show: false, message: '' }"
            x-on:saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center"
            style="display: none;">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message"></span>
        </div>

        <!-- Konten Informasi & Panduan Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Konten Informasi & Panduan</h3>
                </div>
                <button wire:click="createKonten" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-emerald-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Konten
                </button>
            </div>

            <!-- Search & Filter Konten -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input
                            type="text"
                            wire:model.live="searchKonten"
                            placeholder="Cari judul atau isi konten..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 "
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                        <select wire:model.live="perPageKonten" class="rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 ">
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Konten List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kontenInformasi as $index => $konten)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $kontenInformasi->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $konten->judul }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md truncate" title="{{ strip_tags($konten->isi) }}">{{ Str::limit(strip_tags($konten->isi), 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $konten->urutan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $konten->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $konten->aktif ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editKonten({{ $konten->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button wire:click="deleteKonten({{ $konten->id }})" wire:confirm="Yakin ingin menghapus konten ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada Konten</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tambahkan konten informasi & panduan pertama Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $kontenInformasi->links() }}
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">FAQ (Pertanyaan Umum)</h3>
                </div>
                <button wire:click="createFaq" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-blue-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah FAQ
                </button>
            </div>

            <!-- Search & Filter FAQ -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Cari pertanyaan atau jawaban..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200/50 "
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                        <select wire:model.live="perPage" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200/50 ">
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- FAQ List -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($faqs as $index => $faq)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $faqs->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $faq->pertanyaan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md truncate" title="{{ strip_tags($faq->jawaban) }}">{{ Str::limit(strip_tags($faq->jawaban), 100) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $faq->urutan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $faq->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $faq->aktif ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editFaq({{ $faq->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button wire:click="deleteFaq({{ $faq->id }})" wire:confirm="Yakin ingin menghapus FAQ ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada FAQ</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tambahkan pertanyaan umum pertama Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $faqs->links() }}
            </div>
        </div>
    </div>

    <!-- Konten Form Modal -->
    @if($showKontenForm)
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="konten-modal" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="cancelKonten"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full z-[10000]" role="dialog">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="konten-modal">
                            {{ $isEditingKonten ? 'Edit Konten' : 'Tambah Konten Baru' }}
                        </h3>
                        <form wire:submit.prevent="saveKonten" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                                <input
                                    type="text"
                                    wire:model="judul"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 "
                                    placeholder="Masukkan judul konten..."
                                >
                                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Konten *</label>
                                <textarea
                                    wire:model="isi"
                                    rows="10"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 "
                                    placeholder="Masukkan isi konten... (HTML didukung)"
                                ></textarea>
                                <p class="mt-1 text-xs text-gray-500">Anda dapat menggunakan HTML untuk formatting.</p>
                                @error('isi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                    <input
                                        type="number"
                                        wire:model="urutan"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200/50 "
                                        min="0"
                                    >
                                </div>
                                <div class="flex items-center pt-6">
                                    <input
                                        type="checkbox"
                                        wire:model="aktif"
                                        id="konten-aktif"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                    >
                                    <label for="konten-aktif" class="ml-2 block text-sm text-gray-900">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            wire:click="saveKonten"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            wire:click="cancelKonten"
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

    <!-- FAQ Form Modal -->
    @if($showFaqForm)
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="faq-modal" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="cancelFaq"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0">
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[10000]" role="dialog">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="faq-modal">
                            {{ $isEditingFaq ? 'Edit FAQ' : 'Tambah FAQ Baru' }}
                        </h3>
                        <form wire:submit.prevent="saveFaq" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan *</label>
                                <input
                                    type="text"
                                    wire:model="pertanyaan"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200/50 "
                                    placeholder="Masukkan pertanyaan..."
                                >
                                @error('pertanyaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban *</label>
                                <textarea
                                    wire:model="jawaban"
                                    rows="4"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200/50 "
                                    placeholder="Masukkan jawaban..."
                                ></textarea>
                                @error('jawaban') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                    <input
                                        type="number"
                                        wire:model="urutan_faq"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200/50 "
                                        min="0"
                                    >
                                </div>
                                <div class="flex items-center pt-6">
                                    <input
                                        type="checkbox"
                                        wire:model="aktif_faq"
                                        id="faq-aktif"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    >
                                    <label for="faq-aktif" class="ml-2 block text-sm text-gray-900">
                                        Aktif
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            wire:click="saveFaq"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            wire:click="cancelFaq"
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
</div>
