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
                    <a
                        href="/admin/video-infografis?activeTab=video"
                        wire:navigate
                        class="{{ $activeTab === 'video' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Video Panduan
                    </a>
                    <a
                        href="/admin/video-infografis?activeTab=infografis"
                        wire:navigate
                        class="{{ $activeTab === 'infografis' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Infografis
                    </a>
                    <a
                        href="/admin/video-infografis?activeTab=testimoni"
                        wire:navigate
                        class="{{ $activeTab === 'testimoni' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Testimoni Alumni
                    </a>
                    <a
                        href="/admin/video-infografis?activeTab=brosur"
                        wire:navigate
                        class="{{ $activeTab === 'brosur' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        File Brosur
                    </a>
                </nav>
            </div>

            <div class="p-6">
                @if($activeTab === 'video')
                    <!-- Video Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Daftar Video Panduan</h3>
                        <button
                            wire:click="createVideo"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="createVideo" class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Video
                            </span>
                            <span wire:loading wire:target="createVideo" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
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
                                    <tr wire:key="video-{{ $video->id }}" class="hover:bg-gray-50">
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
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                <span wire:loading.remove wire:target="editVideo({{ $video->id }})">Edit</span>
                                                <span wire:loading wire:target="editVideo({{ $video->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
                                            </button>
                                            <button
                                                wire:click="deleteVideo({{ $video->id }})"
                                                wire:confirm="Yakin ingin menghapus video ini?"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <span wire:loading.remove wire:target="deleteVideo({{ $video->id }})">Hapus</span>
                                                <span wire:loading wire:target="deleteVideo({{ $video->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
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
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="createInfografis" class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Infografis
                            </span>
                            <span wire:loading wire:target="createInfografis" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
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
                                    <tr wire:key="infografis-{{ $info->id }}" class="hover:bg-gray-50">
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
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                <span wire:loading.remove wire:target="editInfografis({{ $info->id }})">Edit</span>
                                                <span wire:loading wire:target="editInfografis({{ $info->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
                                            </button>
                                            <button
                                                wire:click="deleteInfografis({{ $info->id }})"
                                                wire:confirm="Yakin ingin menghapus infografis ini?"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <span wire:loading.remove wire:target="deleteInfografis({{ $info->id }})">Hapus</span>
                                                <span wire:loading wire:target="deleteInfografis({{ $info->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
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
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="createTestimoni" class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Tambah Testimoni
                            </span>
                            <span wire:loading wire:target="createTestimoni" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
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
                                    <tr wire:key="testimoni-{{ $testi->id }}" class="hover:bg-gray-50">
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
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                <span wire:loading.remove wire:target="editTestimoni({{ $testi->id }})">Edit</span>
                                                <span wire:loading wire:target="editTestimoni({{ $testi->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
                                            </button>
                                            <button
                                                wire:click="deleteTestimoni({{ $testi->id }})"
                                                wire:confirm="Yakin ingin menghapus testimoni ini?"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <span wire:loading.remove wire:target="deleteTestimoni({{ $testi->id }})">Hapus</span>
                                                <span wire:loading wire:target="deleteTestimoni({{ $testi->id }})" class="inline-flex items-center">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    Loading...
                                                </span>
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

                @elseif($activeTab === 'brosur')
                    <!-- Brosur Section -->
                    <div>
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">File Brosur</h3>
                            <p class="text-gray-600 text-sm">Upload file brosur (PDF) dan aktifkan tombol download untuk ditampilkan di halaman beranda dan informasi</p>
                        </div>

                        <!-- Brosur Form -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                            <!-- Current File Display -->
                            @if($brosur_file_path)
                                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-2 border-emerald-300 rounded-xl p-6 shadow-md">
                                    <!-- Header dengan Status Badge -->
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-base font-bold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            File Brosur Terupload
                                        </h4>
                                        @if($brosur_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-300">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Aktif & Ditampilkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Tidak Ditampilkan
                                            </span>
                                        @endif
                                    </div>

                                    <!-- File Info Card -->
                                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                        <div class="flex items-start space-x-4">
                                            <!-- PDF Icon -->
                                            <div class="flex-shrink-0">
                                                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- File Details -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ basename($brosur_file_path) }}
                                                </p>
                                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                                    <!-- File Size -->
                                                    @php
                                                        $filePath = storage_path('app/public/' . $brosur_file_path);
                                                        $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                                        $fileSizeMB = number_format($fileSize / 1048576, 2);
                                                    @endphp
                                                    <span class="inline-flex items-center text-xs text-gray-600">
                                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="font-medium">{{ $fileSizeMB }} MB</span>
                                                    </span>

                                                    <!-- File Type -->
                                                    <span class="inline-flex items-center text-xs text-gray-600">
                                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <span class="font-medium">PDF Document</span>
                                                    </span>
                                                </div>

                                                <!-- Display Location Info -->
                                                @if($brosur_active)
                                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                    <p class="text-xs font-semibold text-blue-900 mb-2 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Tombol Download Muncul Di:
                                                    </p>
                                                    <ul class="space-y-1 ml-5">
                                                        <li class="text-xs text-blue-800 flex items-start">
                                                            <svg class="w-3 h-3 mt-0.5 mr-1.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span><strong>Halaman Beranda</strong> - Hero section (sebelah kanan tombol "Info Lengkap")</span>
                                                        </li>
                                                        <li class="text-xs text-blue-800 flex items-start">
                                                            <svg class="w-3 h-3 mt-0.5 mr-1.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span><strong>Halaman Informasi</strong> - CTA section (sebelah kanan tombol "Hubungi Panitia")</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-200">
                                            <a
                                                href="{{ Storage::url($brosur_file_path) }}"
                                                target="_blank"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview File
                                            </a>
                                            <button
                                                wire:click="deleteBrosur"
                                                wire:loading.attr="disabled"
                                                wire:confirm="Apakah Anda yakin ingin menghapus file brosur ini? Tombol download akan hilang dari halaman publik."
                                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span wire:loading.remove wire:target="deleteBrosur">Hapus File</span>
                                                <span wire:loading wire:target="deleteBrosur">Menghapus...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Empty State - No File Uploaded -->
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada File Brosur</h4>
                                        <p class="text-sm text-gray-600 max-w-md mb-4">
                                            Upload file brosur (PDF) agar calon siswa dapat mengunduh informasi lengkap tentang PPDB
                                        </p>
                                        <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-xs font-medium rounded-lg">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Upload file PDF maksimal 10 MB
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- File Upload -->
                            <div x-data="{
                                isUploading: false,
                                progress: 0,
                                uploadFileName: ''
                            }"
                            x-on:livewire-upload-start="isUploading = true; uploadFileName = $event.detail.upload.filename"
                            x-on:livewire-upload-finish="isUploading = false; progress = 0"
                            x-on:livewire-upload-cancel="isUploading = false; progress = 0"
                            x-on:livewire-upload-error="isUploading = false; progress = 0"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $brosur_file_path ? '📄 Upload File Baru (Opsional - akan menggantikan file lama)' : '📤 Upload File Brosur (PDF)' }}
                                </label>

                                <!-- Upload Area -->
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 {{ $brosur_file_baru ? 'border-emerald-400 bg-emerald-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 hover:border-gray-400' }}">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            @if($brosur_file_baru)
                                                <!-- File Selected State -->
                                                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <p class="mb-1 text-sm font-semibold text-emerald-700">File berhasil dipilih!</p>
                                                <p class="text-xs text-emerald-600 font-medium">{{ $brosur_file_baru->getClientOriginalName() }}</p>
                                                <p class="text-xs text-gray-500 mt-2">Klik "Simpan Pengaturan" untuk mengupload</p>
                                            @else
                                                <!-- Default Upload State -->
                                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                </div>
                                                <p class="mb-1 text-sm text-gray-700">
                                                    <span class="font-semibold text-blue-600">Klik untuk memilih file</span>
                                                    <span class="text-gray-500">atau drag & drop di sini</span>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">Format: PDF • Ukuran Maksimal: 10 MB</p>
                                            @endif
                                        </div>
                                        <input wire:model="brosur_file_baru" type="file" accept=".pdf" class="hidden" />
                                    </label>
                                </div>

                                <!-- Upload Progress Bar -->
                                <div x-show="isUploading" x-cloak class="mt-4">
                                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-sm font-semibold text-blue-900">Mengupload file...</span>
                                            </div>
                                            <span class="text-sm font-bold text-blue-700" x-text="progress + '%'"></span>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="w-full bg-blue-200 rounded-full h-3 overflow-hidden shadow-inner">
                                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-300 ease-out shadow-sm"
                                                 :style="`width: ${progress}%`">
                                            </div>
                                        </div>

                                        <p class="text-xs text-blue-700 mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span x-text="uploadFileName"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Error Message -->
                                @error('brosur_file_baru')
                                    <div class="mt-3 p-4 bg-red-50 border-2 border-red-200 rounded-xl shadow-sm">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <h3 class="text-sm font-bold text-red-800 mb-1">❌ Upload Gagal</h3>
                                                <p class="text-sm text-red-700 leading-relaxed">{{ $message }}</p>

                                                <!-- Troubleshooting Tips -->
                                                <div class="mt-3 p-3 bg-white rounded-lg border border-red-200">
                                                    <p class="text-xs font-semibold text-gray-800 mb-2">💡 Tips Mengatasi:</p>
                                                    <ul class="text-xs text-gray-700 space-y-1 list-disc ml-4">
                                                        <li>Pastikan ukuran file <strong>tidak melebihi 10 MB</strong></li>
                                                        <li>Coba <strong>kompres PDF</strong> jika terlalu besar</li>
                                                        <li>Pastikan koneksi internet stabil</li>
                                                        <li>Tutup aplikasi lain yang menggunakan bandwidth</li>
                                                        <li>Refresh halaman dan coba lagi</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @enderror
                            </div>

                            <!-- Toggle Active -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-5 shadow-sm">
                                <div class="flex items-start space-x-4">
                                    <!-- Toggle Switch with wire:model.live + Blade conditional -->
                                    <div class="flex items-center">
                                        <label for="brosur_active" class="relative inline-flex items-center cursor-pointer" wire:loading.class="opacity-50">
                                            <input
                                                type="checkbox"
                                                wire:model.live="brosur_active"
                                                id="brosur_active"
                                                class="hidden"
                                            >
                                            <!-- Toggle Background (Livewire reactive) -->
                                            <div class="relative">
                                                <div class="block w-14 h-7 rounded-full transition-all duration-300 ease-in-out {{ $brosur_active ? 'bg-emerald-600' : 'bg-gray-300' }}"></div>
                                                <!-- Toggle Circle (Livewire reactive) -->
                                                <div class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-all duration-300 ease-in-out shadow-md {{ $brosur_active ? 'transform translate-x-7' : '' }}"></div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="flex-1">
                                        <label for="brosur_active" class="text-sm font-bold text-gray-900 cursor-pointer flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Tampilkan Tombol Download di Halaman Publik
                                        </label>
                                        <!-- Dynamic status text (Livewire reactive) -->
                                        <p class="text-xs text-gray-700 mt-2 font-medium">
                                            @if($brosur_active)
                                                ✅ Tombol download AKTIF dan akan ditampilkan
                                            @else
                                                ⚠️ Tombol download TIDAK AKTIF dan tidak akan ditampilkan
                                            @endif
                                        </p>

                                        <!-- Info box - conditional display -->
                                        @if($brosur_file_path && $brosur_active)
                                        <!-- Lokasi Tombol (muncul saat toggle ON) -->
                                        <div class="mt-3 bg-white rounded-lg p-3 border border-blue-200">
                                            <p class="text-xs font-semibold text-blue-900 mb-2 flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Lokasi Tombol Download:
                                            </p>
                                            <ul class="space-y-1.5">
                                                <li class="text-xs text-gray-700 flex items-start">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold mr-2 flex-shrink-0">1</span>
                                                    <span><strong class="text-gray-900">Halaman Beranda</strong> → Hero Section (sebelah kanan tombol "Info Lengkap")</span>
                                                </li>
                                                <li class="text-xs text-gray-700 flex items-start">
                                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold mr-2 flex-shrink-0">2</span>
                                                    <span><strong class="text-gray-900">Halaman Informasi</strong> → CTA "Siap Mendaftar?" (sebelah kanan tombol "Hubungi Panitia")</span>
                                                </li>
                                            </ul>
                                        </div>
                                        @else
                                        <!-- Warning box (saat belum upload file) -->
                                        <div class="mt-3 bg-orange-50 border border-orange-200 rounded-lg p-3">
                                            <p class="text-xs text-orange-800 flex items-start">
                                                <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                <span><strong>Perhatian:</strong> Upload file brosur terlebih dahulu sebelum mengaktifkan toggle. Tombol hanya akan muncul jika ada file yang diupload.</span>
                                            </p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 mt-6 rounded-b-lg border-t-2 border-gray-200">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Klik tombol "Simpan" untuk menyimpan perubahan
                                    </p>
                                    <button
                                        wire:click="saveBrosur"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl font-bold hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    >
                                        <span wire:loading.remove wire:target="saveBrosur" class="inline-flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                            </svg>
                                            Simpan Pengaturan
                                        </span>
                                        <span wire:loading wire:target="saveBrosur" class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-yellow-900 mb-1">Catatan Penting</h4>
                                    <ul class="text-xs text-yellow-800 space-y-1 list-disc ml-4">
                                        <li>Pastikan file brosur sudah di-upload sebelum mengaktifkan tombol download</li>
                                        <li>Format file harus PDF dengan ukuran maksimal 10MB</li>
                                        <li>Gunakan nama file yang deskriptif untuk memudahkan identifikasi</li>
                                        <li>Tombol download hanya akan muncul jika toggle aktif DAN file sudah di-upload</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            <span wire:loading.remove wire:target="cancelVideo">Batal</span>
                            <span wire:loading wire:target="cancelVideo" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
                        </button>
                        <button
                            wire:click="saveVideo"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveVideo">Simpan Video</span>
                            <span wire:loading wire:target="saveVideo" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
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
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            <span wire:loading.remove wire:target="cancelTestimoni">Batal</span>
                            <span wire:loading wire:target="cancelTestimoni" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
                        </button>
                        <button
                            wire:click="saveTestimoni"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveTestimoni">Simpan Testimoni</span>
                            <span wire:loading wire:target="saveTestimoni" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
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
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                        >
                            <span wire:loading.remove wire:target="cancelInfografis">Batal</span>
                            <span wire:loading wire:target="cancelInfografis" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
                        </button>
                        <button
                            wire:click="saveInfografis"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-md"
                        >
                            <span wire:loading.remove wire:target="saveInfografis">Simpan Infografis</span>
                            <span wire:loading wire:target="saveInfografis" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

