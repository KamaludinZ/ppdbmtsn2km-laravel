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

        <!-- Identitas Madrasah Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Identitas Madrasah</h3>
                </div>
                <p class="text-xs text-emerald-100 mt-1">Informasi detail sekolah (NSM, NPSN, Alamat, dll)</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveIdentity">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-900 mb-1">Alamat Sekolah</label>
                            <textarea wire:model="app_address" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium"></textarea>
                            @error('app_address') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">Telepon Sekolah</label>
                            <input type="text" wire:model="app_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_phone') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">Email Sekolah / PTSP</label>
                            <input type="email" wire:model="app_email_ptsp" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_email_ptsp') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">NSM</label>
                            <input type="text" wire:model="app_nsm" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_nsm') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">NPSN</label>
                            <input type="text" wire:model="app_npsn" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_npsn') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">Akreditasi</label>
                            <input type="text" wire:model="app_akreditasi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_akreditasi') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-1">Kontak WhatsApp Panitia</label>
                            <input type="text" wire:model="app_whatsapp_contact" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 font-medium">
                            @error('app_whatsapp_contact') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition flex items-center font-bold shadow-md">
                            <svg wire:loading.remove wire:target="saveIdentity" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveIdentity" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            UPDATE IDENTITAS MADRASAH
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Konten Profil Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Konten Profil Madrasah</h3>
                </div>
                <p class="text-xs text-blue-100 mt-1">Edit narasi profil dan visi menggunakan editor teks</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveContent">
                    <div class="space-y-8">
                        <!-- Profil -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wide">Profil Madrasah</label>
                            <div wire:ignore>
                                <input id="profil-trix" type="hidden" name="profil">
                                <trix-editor
                                    input="profil-trix"
                                    class="trix-content w-full border border-gray-300 rounded-lg shadow-sm min-h-[350px] bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 p-4 text-gray-700"
                                    placeholder="Masukkan profil madrasah...">
                                </trix-editor>
                            </div>
                            @error('profil') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Visi -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2 uppercase tracking-wide">Visi</label>
                            <div wire:ignore>
                                <input id="visi-trix" type="hidden" name="visi">
                                <trix-editor
                                    input="visi-trix"
                                    class="trix-content w-full border border-gray-300 rounded-lg shadow-sm min-h-[200px] bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 p-4 text-gray-700"
                                    placeholder="Masukkan visi madrasah...">
                                </trix-editor>
                            </div>
                            @error('visi') <span class="text-red-500 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition flex items-center font-bold shadow-md">
                            <svg wire:loading.remove wire:target="saveContent" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveContent" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            UPDATE PROFIL & VISI
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Misi Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Misi Madrasah</h3>
                    </div>
                    <p class="text-xs text-indigo-100 mt-1">Kelola misi madrasah yang ditampilkan di halaman publik</p>
                </div>
                <button wire:click="createMisi" class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-indigo-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Misi
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Misi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($misiList as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">{{ $item->urutan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $item->misi }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($item->aktif)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                <button wire:click="editMisi({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="deleteMisi({{ $item->id }})" wire:confirm="Yakin ingin menghapus misi ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data misi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $misiList->links() }}
            </div>
        </div>

        <!-- Tujuan Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Tujuan Madrasah</h3>
                    </div>
                    <p class="text-xs text-purple-100 mt-1">Kelola tujuan madrasah yang ditampilkan di halaman publik</p>
                </div>
                <button wire:click="createTujuan" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-purple-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Tujuan
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tujuanList as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">{{ $item->urutan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ $item->tujuan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($item->aktif)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                <button wire:click="editTujuan({{ $item->id }})" class="text-purple-600 hover:text-purple-900 mr-3">Edit</button>
                                <button wire:click="deleteTujuan({{ $item->id }})" wire:confirm="Yakin ingin menghapus tujuan ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data tujuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $tujuanList->links() }}
            </div>
        </div>

        <!-- Fasilitas Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-orange-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Fasilitas Madrasah</h3>
                    </div>
                    <p class="text-xs text-orange-100 mt-1">Kelola fasilitas madrasah yang ditampilkan di halaman publik</p>
                </div>
                <button wire:click="createFasilitas" class="inline-flex items-center px-4 py-2 bg-white text-orange-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-orange-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Fasilitas
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Fasilitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Warna</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($fasilitasList as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-500">{{ $item->urutan }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $item->nama_fasilitas }}</div>
                                <div class="text-xs text-gray-500">Icon: {{ $item->icon }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-{{ $item->warna }}-100 text-{{ $item->warna }}-800">
                                    {{ ucfirst($item->warna) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($item->aktif)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                <button wire:click="editFasilitas({{ $item->id }})" class="text-orange-600 hover:text-orange-900 mr-3">Edit</button>
                                <button wire:click="deleteFasilitas({{ $item->id }})" wire:confirm="Yakin ingin menghapus fasilitas ini?" class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data fasilitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $fasilitasList->links() }}
            </div>
        </div>

        <!-- Misi Modal -->
        @if($showMisiForm)
        <div class="fixed inset-0 overflow-y-auto z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelMisi"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveMisi">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditingMisi ? 'Edit Misi' : 'Tambah Misi' }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten Misi *</label>
                                    <textarea wire:model="misi_text" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                    @error('misi_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                        <input type="number" wire:model="urutan_misi" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        @error('urutan_misi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="aktif_misi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" wire:click="cancelMisi" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Tujuan Modal -->
        @if($showTujuanForm)
        <div class="fixed inset-0 overflow-y-auto z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelTujuan"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveTujuan">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditingTujuan ? 'Edit Tujuan' : 'Tambah Tujuan' }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten Tujuan *</label>
                                    <textarea wire:model="tujuan_text" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50"></textarea>
                                    @error('tujuan_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                        <input type="number" wire:model="urutan_tujuan" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        @error('urutan_tujuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="aktif_tujuan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" wire:click="cancelTujuan" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Fasilitas Modal -->
        @if($showFasilitasForm)
        <div class="fixed inset-0 overflow-y-auto z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelFasilitas"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveFasilitas">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditingFasilitas ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas *</label>
                                    <input type="text" wire:model="nama_fasilitas" maxlength="100" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Contoh: Laboratorium Komputer">
                                    @error('nama_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea wire:model="deskripsi_fasilitas" rows="3" maxlength="200" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Deskripsi singkat fasilitas (opsional)"></textarea>
                                    @error('deskripsi_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                                        <select wire:model="icon_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <option value="building">🏢 Building</option>
                                            <option value="computer">💻 Computer</option>
                                            <option value="book">📚 Book</option>
                                            <option value="science">🔬 Science</option>
                                            <option value="sports">⚽ Sports</option>
                                            <option value="mosque">🕌 Mosque</option>
                                            <option value="library">📖 Library</option>
                                            <option value="music">🎵 Music</option>
                                            <option value="art">🎨 Art</option>
                                            <option value="cafe">☕ Cafeteria</option>
                                        </select>
                                        @error('icon_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                                        <select wire:model="warna_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <option value="emerald">🟢 Emerald</option>
                                            <option value="blue">🔵 Blue</option>
                                            <option value="purple">🟣 Purple</option>
                                            <option value="yellow">🟡 Yellow</option>
                                            <option value="red">🔴 Red</option>
                                            <option value="green">🟢 Green</option>
                                            <option value="indigo">🔵 Indigo</option>
                                            <option value="pink">🩷 Pink</option>
                                        </select>
                                        @error('warna_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                        <input type="number" wire:model="urutan_fasilitas" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        @error('urutan_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="aktif_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button type="button" wire:click="cancelFasilitas" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        // Flag untuk mencegah inisialisasi ganda
        let trixInitialized = false;

        // Fungsi untuk setup Trix editor
        function setupTrixEditor() {
            // Cegah inisialisasi ganda
            if (trixInitialized) {
                return;
            }

            // Setup Profil Editor
            const profilEditor = document.querySelector('trix-editor[input="profil-trix"]');
            if (profilEditor && profilEditor.editor) {
                // Load initial content
                const profilContent = @js($profil);
                if (profilContent) {
                    profilEditor.editor.loadHTML(profilContent);
                }

                // Remove existing listener jika ada
                profilEditor.removeEventListener('trix-change', handleProfilChange);

                // Add event listener untuk update Livewire
                profilEditor.addEventListener('trix-change', handleProfilChange);
            }

            // Setup Visi Editor
            const visiEditor = document.querySelector('trix-editor[input="visi-trix"]');
            if (visiEditor && visiEditor.editor) {
                // Load initial content
                const visiContent = @js($visi);
                if (visiContent) {
                    visiEditor.editor.loadHTML(visiContent);
                }

                // Remove existing listener jika ada
                visiEditor.removeEventListener('trix-change', handleVisiChange);

                // Add event listener untuk update Livewire
                visiEditor.addEventListener('trix-change', handleVisiChange);
            }

            trixInitialized = true;
        }

        // Handler functions (defined outside to avoid duplicates)
        function handleProfilChange(e) {
            @this.set('profil', e.target.innerHTML);
        }

        function handleVisiChange(e) {
            @this.set('visi', e.target.innerHTML);
        }

        // Inisialisasi saat halaman dimuat
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(setupTrixEditor, 500);
            });
        } else {
            setTimeout(setupTrixEditor, 500);
        }

        // Inisialisasi saat Livewire navigated (untuk SPA mode)
        document.addEventListener('livewire:navigated', function () {
            trixInitialized = false; // Reset flag
            setTimeout(setupTrixEditor, 500);
        });
    </script>

    <!-- Fasilitas Modal -->
    @if($showFasilitasForm)
    <div class="fixed inset-0 overflow-y-auto z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelFasilitas"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="saveFasilitas">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $isEditingFasilitas ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas *</label>
                                <input type="text" wire:model="nama_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                @error('nama_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <input type="text" wire:model="deskripsi_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                @error('deskripsi_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon</label>
                                    <select wire:model="icon_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        <option value="building">Building</option>
                                        <option value="library">Library</option>
                                        <option value="computer">Computer</option>
                                        <option value="flask">Flask (Lab)</option>
                                        <option value="sports">Sports</option>
                                        <option value="mosque">Mosque</option>
                                        <option value="store">Store</option>
                                        <option value="wifi">WiFi</option>
                                        <option value="shopping-cart">Shopping Cart</option>
                                        <option value="projector">Projector</option>
                                        <option value="board">Board</option>
                                        <option value="home">Home</option>
                                    </select>
                                    @error('icon_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                                    <select wire:model="warna_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        <option value="emerald">Emerald</option>
                                        <option value="blue">Blue</option>
                                        <option value="purple">Purple</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="red">Red</option>
                                        <option value="green">Green</option>
                                        <option value="indigo">Indigo</option>
                                        <option value="pink">Pink</option>
                                        <option value="teal">Teal</option>
                                        <option value="cyan">Cyan</option>
                                        <option value="violet">Violet</option>
                                        <option value="orange">Orange</option>
                                    </select>
                                    @error('warna_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                    <input type="number" wire:model="urutan_fasilitas" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                    @error('urutan_fasilitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select wire:model="aktif_fasilitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                        <button type="button" wire:click="cancelFasilitas" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>