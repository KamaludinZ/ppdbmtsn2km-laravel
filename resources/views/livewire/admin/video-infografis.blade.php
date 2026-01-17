<div>
    <!-- Notification -->
    @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-10000 flex items-center">
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
                    <h2 class="text-2xl font-bold text-gray-900">Video & Infografis</h2>
                    <p class="text-gray-600 mt-1">Kelola konten video dan infografis untuk halaman beranda</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button
                        wire:click="$set('activeTab', 'video')"
                        class="{{ $activeTab === 'video' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Video Panduan
                    </button>
                    <button
                        wire:click="$set('activeTab', 'infografis')"
                        class="{{ $activeTab === 'infografis' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Infografis
                    </button>
                    <button
                        wire:click="$set('activeTab', 'testimoni')"
                        class="{{ $activeTab === 'testimoni' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Testimoni Alumni
                    </button>
                </nav>
            </div>

            <div class="p-6">
                @if($activeTab === 'video')
                    <!-- Video Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Video Panduan</h3>
                        <button 
                            wire:click="createVideo" 
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Video
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($videos as $index => $video)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $videos->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $video->judul }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $video->deskripsi }}">{{ $video->deskripsi ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $video->kategori }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $video->urutan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $video->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $video->aktif ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                wire:click="editVideo({{ $video->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                wire:click="deleteVideo({{ $video->id }})"
                                                wire:confirm="Yakin ingin menghapus video ini?"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada video</h3>
                                            <p class="mt-1 text-sm text-gray-500">Tambahkan video panduan pertama Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $videos->links() }}
                    </div>
                @elseif($activeTab === 'infografis')
                    <!-- Infografis Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Infografis</h3>
                        <button
                            wire:click="createInfografis"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Infografis
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($infografis as $index => $info)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $infografis->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $info->judul }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $info->deskripsi }}">{{ $info->deskripsi ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $info->kategori }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $info->urutan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $info->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $info->aktif ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                wire:click="editInfografis({{ $info->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                wire:click="deleteInfografis({{ $info->id }})"
                                                wire:confirm="Yakin ingin menghapus infografis ini?"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada infografis</h3>
                                            <p class="mt-1 text-sm text-gray-500">Tambahkan infografis pertama Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $infografis->links() }}
                    </div>
                @elseif($activeTab === 'testimoni')
                    <!-- Testimoni Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Testimoni Alumni</h3>
                        <button
                            wire:click="createTestimoni"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Testimoni
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-lg overflow-hidden shadow">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Lulus</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Testimoni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institusi Sekarang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($testimoni as $index => $testi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $testimoni->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($testi->foto_path)
                                                    <img src="{{ asset('storage/' . $testi->foto_path) }}" alt="{{ $testi->nama }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center mr-3">
                                                        <span class="text-emerald-600 font-semibold text-sm">{{ substr($testi->nama, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="text-sm font-medium text-gray-900">{{ $testi->nama }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $testi->tahun_lulus }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $testi->testimoni }}">{{ $testi->testimoni }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $testi->institusi_sekarang ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $testi->urutan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testi->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $testi->aktif ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                wire:click="editTestimoni({{ $testi->id }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                wire:click="deleteTestimoni({{ $testi->id }})"
                                                wire:confirm="Yakin ingin menghapus testimoni ini?"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada testimoni</h3>
                                            <p class="mt-1 text-sm text-gray-500">Tambahkan testimoni alumni pertama Anda.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $testimoni->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Video Form Modal -->
    @if($showVideoForm)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm z-[9998]" wire:click="cancelVideo"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="inline-block bg-white rounded-xl shadow-xl z-[10000] w-full max-w-2xl" role="dialog" aria-modal="true">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $isEditingVideo ? 'Edit Video Panduan' : 'Tambah Video Panduan Baru' }}
                        </h3>
                        <button wire:click="cancelVideo" class="text-gray-400 hover:text-gray-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <form wire:submit.prevent="saveVideo" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Video <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    wire:model="video_judul"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Contoh: Tutorial Cara Mendaftar {{ $appAcronym }} Online"
                                >
                                @error('video_judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea
                                    wire:model="video_deskripsi"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Deskripsi singkat tentang video ini (opsional)"
                                ></textarea>
                                @error('video_deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">URL Video YouTube <span class="text-red-500">*</span></label>
                                <input
                                    type="url"
                                    wire:model="video_url"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                >
                                @error('video_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Masukkan URL lengkap video YouTube</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                    <select
                                        wire:model="video_kategori"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    >
                                        <option value="">Pilih Kategori</option>
                                        <option value="profil">Profil Sekolah</option>
                                        <option value="panduan">Panduan Pendaftaran</option>
                                        <option value="fasilitas">Fasilitas</option>
                                        <option value="kegiatan">Kegiatan Sekolah</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('video_kategori') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                                    <input
                                        type="number"
                                        wire:model="video_urutan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        min="0"
                                        placeholder="0"
                                    >
                                    @error('video_urutan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">Urutan tampil di beranda (0 = paling awal)</p>
                                </div>
                            </div>

                            <div class="flex items-center pt-2">
                                <input
                                    type="checkbox"
                                    wire:model="video_aktif"
                                    id="video_aktif"
                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                >
                                <label for="video_aktif" class="ml-2 text-sm text-gray-700">
                                    Aktifkan video (tampilkan di beranda)
                                </label>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button
                            wire:click="cancelVideo"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="saveVideo"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveVideo">Simpan Video</span>
                            <span wire:loading wire:target="saveVideo">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Testimoni Form Modal -->
    @if($showTestimoniForm)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm z-[9998]" wire:click="cancelTestimoni"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="inline-block bg-white rounded-xl shadow-xl z-[10000] w-full max-w-2xl" role="dialog" aria-modal="true">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $isEditingTestimoni ? 'Edit Testimoni Alumni' : 'Tambah Testimoni Alumni Baru' }}
                        </h3>
                        <button wire:click="cancelTestimoni" class="text-gray-400 hover:text-gray-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <form wire:submit.prevent="saveTestimoni" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Alumni <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        wire:model="testimoni_nama"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="Nama lengkap alumni"
                                    >
                                    @error('testimoni_nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        wire:model="testimoni_tahun_lulus"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="Contoh: 2023"
                                    >
                                    @error('testimoni_tahun_lulus') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Alumni (Optional)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:bg-gray-100 transition">
                                    <input
                                        type="file"
                                        wire:model="foto_baru"
                                        accept="image/*"
                                        class="hidden"
                                        id="foto-upload"
                                    >
                                    <label for="foto-upload" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Klik untuk upload foto</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF, SVG (Maks. 2MB)</p>
                                    </label>
                                    <div wire:loading wire:target="foto_baru" class="mt-2 text-emerald-600 text-sm">Sedang mengupload...</div>
                                </div>
                                @error('foto_baru') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                <!-- Preview Foto -->
                                @if($foto_baru)
                                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                        <p class="text-sm text-emerald-700 font-medium mb-2">Preview Foto Baru:</p>
                                        <img src="{{ $foto_baru->temporaryUrl() }}" alt="Preview" class="h-32 w-32 object-cover mx-auto rounded-full">
                                    </div>
                                @elseif($testimoni_foto_path)
                                    <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-sm text-gray-700 font-medium mb-2">Foto Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $testimoni_foto_path) }}" alt="Current" class="h-32 w-32 object-cover mx-auto rounded-full">
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Testimoni <span class="text-red-500">*</span></label>
                                <textarea
                                    wire:model="testimoni_testimoni"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Tuliskan testimoni alumni..."
                                ></textarea>
                                @error('testimoni_testimoni') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Institusi/Kampus Sekarang</label>
                                    <input
                                        type="text"
                                        wire:model="testimoni_institusi_sekarang"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="Contoh: Universitas Brawijaya"
                                    >
                                    @error('testimoni_institusi_sekarang') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                                    <input
                                        type="number"
                                        wire:model="testimoni_urutan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        min="0"
                                        placeholder="0"
                                    >
                                    @error('testimoni_urutan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">Urutan tampil di beranda (0 = paling awal)</p>
                                </div>
                            </div>

                            <div class="flex items-center pt-2">
                                <input
                                    type="checkbox"
                                    wire:model="testimoni_aktif"
                                    id="testimoni_aktif"
                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                >
                                <label for="testimoni_aktif" class="ml-2 text-sm text-gray-700">
                                    Aktifkan testimoni (tampilkan di beranda)
                                </label>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button
                            wire:click="cancelTestimoni"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="saveTestimoni"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveTestimoni">Simpan Testimoni</span>
                            <span wire:loading wire:target="saveTestimoni">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Infografis Form Modal -->
    @if($showInfografisForm)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm z-[9998]" wire:click="cancelInfografis"></div>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                <div class="inline-block bg-white rounded-xl shadow-xl z-[10000] w-full max-w-2xl" role="dialog" aria-modal="true">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $isEditingInfografis ? 'Edit Infografis' : 'Tambah Infografis Baru' }}
                        </h3>
                        <button wire:click="cancelInfografis" class="text-gray-400 hover:text-gray-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <form wire:submit.prevent="saveInfografis" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Infografis <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    wire:model="infografis_judul"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Contoh: Statistik Pendaftar {{ $appAcronym }} 2026"
                                >
                                @error('infografis_judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea
                                    wire:model="infografis_deskripsi"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Deskripsi singkat tentang infografis ini (opsional)"
                                ></textarea>
                                @error('infografis_deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Infografis <span class="text-red-500">*</span></label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:bg-gray-100 transition">
                                    <input
                                        type="file"
                                        wire:model="gambar_baru"
                                        accept="image/*"
                                        class="hidden"
                                        id="gambar-upload"
                                    >
                                    <label for="gambar-upload" class="cursor-pointer">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Klik untuk upload gambar</p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF, SVG (Maks. 2MB)</p>
                                    </label>
                                    <div wire:loading wire:target="gambar_baru" class="mt-2 text-emerald-600 text-sm">Sedang mengupload...</div>
                                </div>
                                @error('gambar_baru') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                <!-- Preview Gambar -->
                                @if($gambar_baru)
                                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                                        <p class="text-sm text-emerald-700 font-medium mb-2">Preview Gambar Baru:</p>
                                        <img src="{{ $gambar_baru->temporaryUrl() }}" alt="Preview" class="h-48 object-contain mx-auto rounded">
                                    </div>
                                @elseif($infografis_gambar_path)
                                    <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-sm text-gray-700 font-medium mb-2">Gambar Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $infografis_gambar_path) }}" alt="Current" class="h-48 object-contain mx-auto rounded">
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                    <select
                                        wire:model="infografis_kategori"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                    >
                                        <option value="">Pilih Kategori</option>
                                        <option value="profil">Profil Sekolah</option>
                                        <option value="panduan">Panduan Pendaftaran</option>
                                        <option value="fasilitas">Fasilitas</option>
                                        <option value="kegiatan">Kegiatan Sekolah</option>
                                        <option value="statistik">Statistik</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('infografis_kategori') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil</label>
                                    <input
                                        type="number"
                                        wire:model="infografis_urutan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                        min="0"
                                        placeholder="0"
                                    >
                                    @error('infografis_urutan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">Urutan tampil di beranda (0 = paling awal)</p>
                                </div>
                            </div>

                            <div class="flex items-center pt-2">
                                <input
                                    type="checkbox"
                                    wire:model="infografis_aktif"
                                    id="infografis_aktif"
                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                >
                                <label for="infografis_aktif" class="ml-2 text-sm text-gray-700">
                                    Aktifkan infografis (tampilkan di beranda)
                                </label>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button
                            wire:click="cancelInfografis"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            Batal
                        </button>
                        <button
                            wire:click="saveInfografis"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveInfografis">Simpan Infografis</span>
                            <span wire:loading wire:target="saveInfografis">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
