<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Kartu Tes Seleksi</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola jadwal tes dan kartu kendali untuk kartu tes seleksi peserta.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap space-x-2 md:space-x-4">
            <button
                wire:click="$set('activeTab', 'jadwal-tes')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'jadwal-tes' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Jadwal Tes
            </button>
            <button
                wire:click="$set('activeTab', 'kartu-kendali')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'kartu-kendali' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Kartu Kendali
            </button>
            <button
                wire:click="$set('activeTab', 'catatan-kartu')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'catatan-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Catatan Kartu
            </button>
            <button
                wire:click="$set('activeTab', 'link-grup')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'link-grup' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Link Grup
            </button>
            <button
                wire:click="$set('activeTab', 'preview-kartu')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'preview-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Preview Kartu
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-psikologi')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'invoice-psikologi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Invoice Psikologi
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-madrasah')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'invoice-madrasah' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Invoice DU Madrasah
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-seragam')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm {{ $activeTab === 'invoice-seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Invoice DU Seragam
            </button>
        </nav>
    </div>

    <!-- Jadwal Tes Tab -->
    @if($activeTab === 'jadwal-tes')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isEditingJadwal ? 'Edit Jadwal Tes' : 'Tambah Jadwal Tes' }}
                    </h3>

                    <form wire:submit.prevent="saveJadwalTes">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tes</label>
                            <input type="text" wire:model="nama_tes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Tes Akademik">
                            @error('nama_tes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Deskripsi tes (opsional)"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                            <input type="datetime-local" wire:model="waktu"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            @error('waktu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat</label>
                            <input type="text" wire:model="tempat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Ruang A1">
                            @error('tempat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                            <input type="number" wire:model="urutan_tes" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="0">
                            @error('urutan_tes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                {{ $isEditingJadwal ? 'Perbarui' : 'Tambah' }}
                            </button>
                            @if($isEditingJadwal)
                                <button type="button" wire:click="resetJadwalTes"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                                    Batal
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Jadwal Tes</h3>

                    @if($jadwalTesList->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada jadwal tes.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($jadwalTesList as $jadwal)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-emerald-300 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800">{{ $jadwal->nama_tes }}</h4>
                                            @if($jadwal->deskripsi)
                                                <p class="text-sm text-gray-600 mt-1">{{ $jadwal->deskripsi }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">
                                            #{{ $jadwal->urutan }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $jadwal->waktu->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $jadwal->tempat }}
                                        </div>
                                    </div>
                                    <div class="mt-3 flex space-x-2">
                                        <button wire:click="editJadwalTes({{ $jadwal->id }})"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </button>
                                        <button wire:click="deleteJadwalTes({{ $jadwal->id }})"
                                            onclick="return confirm('Yakin ingin menghapus jadwal tes ini?')"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Kartu Kendali Tab -->
    @if($activeTab === 'kartu-kendali')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        {{ $isEditingKendali ? 'Edit Kartu Kendali' : 'Tambah Kartu Kendali' }}
                    </h3>

                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Info:</strong> Kartu kendali akan ditampilkan di bagian bawah kartu pendaftaran peserta.
                        </p>
                    </div>

                    <form wire:submit.prevent="saveKartuKendali">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan</label>
                            <input type="text" wire:model="nama_kegiatan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Verifikasi Berkas">
                            @error('nama_kegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea wire:model="catatan_kendali" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Catatan tambahan (opsional)"></textarea>
                            @error('catatan_kendali') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                            <input type="number" wire:model="urutan_kendali" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="0">
                            @error('urutan_kendali') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                {{ $isEditingKendali ? 'Perbarui' : 'Tambah' }}
                            </button>
                            @if($isEditingKendali)
                                <button type="button" wire:click="resetKartuKendali"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                                    Batal
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kartu Kendali</h3>

                    @if($kartuKendaliList->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada kartu kendali.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($kartuKendaliList as $kendali)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-emerald-300 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800">{{ $kendali->nama_kegiatan }}</h4>
                                            @if($kendali->catatan)
                                                <p class="text-sm text-gray-600 mt-1">{{ $kendali->catatan }}</p>
                                            @endif
                                        </div>
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">
                                            #{{ $kendali->urutan }}
                                        </span>
                                    </div>
                                    <div class="mt-3 flex space-x-2">
                                        <button wire:click="editKartuKendali({{ $kendali->id }})"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </button>
                                        <button wire:click="deleteKartuKendali({{ $kendali->id }})"
                                            onclick="return confirm('Yakin ingin menghapus kartu kendali ini?')"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Catatan Kartu Tab -->
    @if($activeTab === 'catatan-kartu')
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Catatan Kartu Tes Seleksi</h3>

            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Catatan ini akan ditampilkan di bagian bawah kartu tes seleksi peserta.
                </p>
            </div>

            <form wire:submit.prevent="saveCatatanKartu">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Kartu Tes Seleksi</label>
                    <textarea wire:model="catatan_kartu_ujian" rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Masukkan catatan yang akan ditampilkan di kartu tes seleksi..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Catatan ini akan muncul di bagian bawah kartu tes seleksi</p>
                </div>

                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    Simpan Catatan
                </button>
            </form>

            <!-- Preview Catatan -->
            @if($catatan_kartu_ujian)
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Preview Catatan:</h4>
                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="whitespace-pre-wrap text-sm text-gray-700">{{ $catatan_kartu_ujian }}</div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Link Grup Tab -->
    @if($activeTab === 'link-grup')
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Link Grup WhatsApp/Telegram</h3>

            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Link grup akan ditampilkan sebagai tombol "Bergabung ke Grup" di dashboard pendaftar (di sebelah tombol Cetak Kartu) dan di halaman cetak kartu tes seleksi. Tombol hanya muncul jika link grup diaktifkan.
                </p>
            </div>

            <form wire:submit.prevent="saveLinkGrup">
                <!-- Toggle Enable/Disable -->
                <div class="mb-6" x-data="{ enabled: @entangle('link_grup_enabled') }">
                    <label class="flex items-center cursor-pointer">
                        <div class="relative inline-block w-14 h-7 transition duration-200 ease-in-out">
                            <input
                                type="checkbox"
                                x-model="enabled"
                                class="opacity-0 w-0 h-0 absolute">
                            <span
                                class="absolute cursor-pointer inset-0 rounded-full transition-colors duration-200"
                                :class="enabled ? 'bg-emerald-600' : 'bg-gray-300'">
                            </span>
                            <span
                                class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform duration-200 shadow-md"
                                :class="enabled ? 'translate-x-7' : 'translate-x-0'">
                            </span>
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Aktifkan Link Grup (Tombol "Bergabung ke Grup" akan muncul di dashboard dan kartu tes)
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-17">Toggle ini untuk mengontrol apakah link grup ditampilkan atau tidak</p>
                </div>

                <!-- Link Grup URL -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        URL Link Grup
                        @if($link_grup_enabled)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input
                        type="url"
                        wire:model="link_grup_url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="https://chat.whatsapp.com/... atau https://t.me/...">
                    @error('link_grup_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        Masukkan link grup WhatsApp atau Telegram. Contoh:<br>
                        - WhatsApp: https://chat.whatsapp.com/ABC123<br>
                        - Telegram: https://t.me/nama_grup
                    </p>
                </div>

                <!-- Preview Link -->
                @if($link_grup_url)
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Preview Link:</h4>
                        <a href="{{ $link_grup_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline break-all">
                            {{ $link_grup_url }}
                        </a>
                        <p class="text-xs text-gray-500 mt-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Link akan dibuka di tab baru ketika diklik
                        </p>
                    </div>
                @endif

                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    Simpan Link Grup
                </button>
            </form>

            <!-- Preview Tombol -->
            @if($link_grup_enabled && $link_grup_url)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Preview Tombol di Dashboard Pendaftar:</h4>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <a href="{{ $link_grup_url }}" target="_blank" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105 shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Cetak Kartu Ujian
                            </a>
                            <a href="{{ $link_grup_url }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-green-600 transition transform hover:scale-105 shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                                </svg>
                                Bergabung ke Grup
                            </a>
                        </div>
                        <p class="text-sm text-white mt-3 opacity-90">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tombol "Bergabung ke Grup" akan muncul di sebelah kanan tombol "Cetak Kartu Ujian"
                        </p>
                    </div>
                </div>
            @elseif(!$link_grup_enabled)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Link grup tidak aktif. Aktifkan toggle di atas untuk menampilkan tombol "Bergabung ke Grup" di dashboard pendaftar.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Preview Kartu Tab -->
    @if($activeTab === 'preview-kartu')
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Kartu Tes Seleksi (A4 Portrait)</h3>

            <!-- Sample Card Preview - A4 Portrait Format -->
            <div class="mx-auto border-2 border-gray-300 bg-white shadow-lg" style="width: 210mm; min-height: 297mm; padding: 12mm 20mm;">
                <!-- Kop Header (dari Settings) -->
                <div class="flex items-start gap-6 pb-3 border-b-4 border-black mb-4">
                    <!-- Logo Kiri -->
                    <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                        @if(!empty($printSettings['print_logo_left']))
                            <img src="{{ $printSettings['print_logo_left'] }}" alt="Logo Kiri" class="max-w-full max-h-full object-contain">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Identitas Tengah -->
                    <div class="flex-1 text-center">
                        <h2 class="text-xl font-bold text-gray-900 uppercase mb-1">
                            {{ $printSettings['print_institution_name'] ?: 'NAMA LEMBAGA' }}
                        </h2>
                        <p class="text-sm text-gray-700 mb-1">
                            {{ $printSettings['print_institution_address'] ?: 'Alamat Lembaga' }}
                        </p>
                        <p class="text-xs text-gray-600">
                            @if($printSettings['print_institution_phone']) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                            @if($printSettings['print_institution_email']) | Email: {{ $printSettings['print_institution_email'] }} @endif
                            @if($printSettings['print_institution_website']) | {{ $printSettings['print_institution_website'] }} @endif
                        </p>
                    </div>

                    <!-- Logo Kanan -->
                    <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                        @if(!empty($printSettings['print_logo_right']))
                            <img src="{{ $printSettings['print_logo_right'] }}" alt="Logo Kanan" class="max-w-full max-h-full object-contain">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">KARTU TES SELEKSI</h1>
                    <p class="text-base text-gray-600">Tahun Pelajaran {{ $printSettings['tahun_pelajaran'] ?: date('Y') . '/' . (date('Y') + 1) }}</p>
                </div>

                <!-- Student Info (Expanded) -->
                <div class="grid grid-cols-3 gap-6 mb-4">
                    <div class="col-span-2">
                        <table class="text-sm w-full">
                            <tr>
                                <td class="font-medium text-gray-700 py-1" style="width: 35%">No. Pendaftaran</td>
                                <td class="font-semibold text-gray-900 py-1">: PPDB-2024-001</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Nama Lengkap</td>
                                <td class="font-semibold text-gray-900 py-1">: CONTOH NAMA PESERTA</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">NISN</td>
                                <td class="font-semibold text-gray-900 py-1">: 0011223344</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Jenis Kelamin</td>
                                <td class="font-semibold text-gray-900 py-1">: Laki-laki</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Jalur Pendaftaran</td>
                                <td class="font-semibold text-gray-900 py-1">: Prestasi</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Asal Sekolah</td>
                                <td class="font-semibold text-gray-900 py-1">: SD Negeri 1 Contoh</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Nomor WA Orang Tua</td>
                                <td class="font-semibold text-gray-900 py-1">: 628123456789</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1 align-top">Alamat</td>
                                <td class="font-semibold text-gray-900 py-1">: Jl. Contoh No. 123, Desa Contoh, Kec. Contoh, Kab. Kulon Progo, DIY</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex items-start justify-center">
                        <div class="w-28 h-36 border-2 border-gray-400 bg-gray-100 flex items-center justify-center">
                            <span class="text-sm text-gray-500">Foto 3x4</span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Prestasi (hanya muncul jika ada prestasi, maksimal 5 baris) -->
                @php
                    // Untuk preview, gunakan data contoh. Di implementasi nyata akan menggunakan data dari $pendaftar->prestasis
                    $prestasiContoh = [
                        [
                            'tahun' => '2023',
                            'nama_lomba' => 'Olimpiade Matematika',
                            'bidang_lomba' => 'Akademik',
                            'nama_penyelenggara' => 'Dinas Pendidikan Kabupaten',
                            'tingkat_lomba' => 'Kabupaten/Kota',
                            'peringkat' => 'Juara 1/Medali Emas',
                            'kategori_lomba' => 'Individu'
                        ],
                        [
                            'tahun' => '2023',
                            'nama_lomba' => 'Lomba Cerdas Cermat',
                            'bidang_lomba' => 'Akademik',
                            'nama_penyelenggara' => 'Dinas Pendidikan Provinsi',
                            'tingkat_lomba' => 'Provinsi',
                            'peringkat' => 'Juara 2/Medali Perak',
                            'kategori_lomba' => 'Kelompok'
                        ],
                        [
                            'tahun' => '2022',
                            'nama_lomba' => 'Lomba Pidato Bahasa Indonesia',
                            'bidang_lomba' => 'Kesenian',
                            'nama_penyelenggara' => 'Kementerian Pendidikan',
                            'tingkat_lomba' => 'Nasional',
                            'peringkat' => 'Juara 3/Medali Perunggu',
                            'kategori_lomba' => 'Individu'
                        ],
                        [
                            'tahun' => '2022',
                            'nama_lomba' => 'Lomba Karya Ilmiah Remaja',
                            'bidang_lomba' => 'Sains',
                            'nama_penyelenggara' => 'LIPI',
                            'tingkat_lomba' => 'Nasional',
                            'peringkat' => 'Juara Harapan 1',
                            'kategori_lomba' => 'Kelompok'
                        ],
                        [
                            'tahun' => '2021',
                            'nama_lomba' => 'Lomba Tahfidz Al-Quran',
                            'bidang_lomba' => 'Keagamaan',
                            'nama_penyelenggara' => 'Kemenag Kabupaten',
                            'tingkat_lomba' => 'Kabupaten/Kota',
                            'peringkat' => 'Juara 2/Medali Perak',
                            'kategori_lomba' => 'Individu'
                        ],
                    ];
                    // Limit hanya 5 prestasi pertama
                    $prestasiTampil = array_slice($prestasiContoh, 0, 5);
                @endphp

                @if(count($prestasiTampil) > 0)
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 mb-2 text-sm uppercase">DATA PRESTASI (Maksimal 5 Prestasi):</h4>
                    <table class="w-full border-collapse border border-gray-300" style="font-size: 9px; line-height: 1.3;">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-1 py-1 text-center font-semibold" style="width: 4%">No</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 6%">Tahun</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 24%">Nama Lomba</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 10%">Bidang</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 22%">Penyelenggara</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 12%">Tingkat</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 16%">Peringkat</th>
                                <th class="border border-gray-300 px-1 py-1 text-center font-semibold" style="width: 6%">Kat.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prestasiTampil as $index => $prestasi)
                                <tr>
                                    <td class="border border-gray-300 px-1 py-1 text-center font-semibold">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['tahun'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['nama_lomba'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['bidang_lomba'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['nama_penyelenggara'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['tingkat_lomba'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1">{{ $prestasi['peringkat'] }}</td>
                                    <td class="border border-gray-300 px-1 py-1 text-center">{{ substr($prestasi['kategori_lomba'], 0, 1) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Jadwal Tes (Table Format) -->
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">JADWAL TES:</h4>
                    @if($jadwalTesList->isNotEmpty())
                        <table class="w-full text-xs border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 30%">Nama Tes</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 25%">Tanggal</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 20%">Waktu</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 25%">Tempat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalTesList as $jadwal)
                                    <tr>
                                        <td class="border border-gray-300 px-1 py-0.5 font-semibold">{{ $jadwal->nama_tes }}</td>
                                        <td class="border border-gray-300 px-1 py-0.5">{{ $jadwal->waktu->format('d/m/Y') }}</td>
                                        <td class="border border-gray-300 px-1 py-0.5">{{ $jadwal->waktu->format('H:i') }} WIB</td>
                                        <td class="border border-gray-300 px-1 py-0.5">{{ $jadwal->tempat }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-xs text-gray-500 italic">Belum ada jadwal tes yang dikonfigurasi</p>
                    @endif
                </div>

                <!-- Kartu Kendali (Table Format) -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">KARTU KENDALI:</h4>
                    @if($kartuKendaliList->isNotEmpty())
                        <table class="w-full text-xs border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 8%">No</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 40%">Kegiatan</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 20%">Ruang</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 32%">Paraf Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kartuKendaliList as $kendali)
                                    <tr>
                                        <td class="border border-gray-300 px-1 py-0.5 text-center font-semibold">{{ $loop->iteration }}</td>
                                        <td class="border border-gray-300 px-1 py-0.5">{{ $kendali->nama_kegiatan }}</td>
                                        <td class="border border-gray-300 px-1 py-0.5 text-center">&nbsp;</td>
                                        <td class="border border-gray-300 px-1 py-0.5">&nbsp;</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-xs text-gray-500 italic">Belum ada kartu kendali yang dikonfigurasi</p>
                    @endif
                </div>

                <!-- Catatan Section dari Kartu Kendali -->
                @if($kartuKendaliList->filter(function($k) { return !empty($k->catatan); })->isNotEmpty())
                <div class="mt-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">CATATAN:</h4>
                    <div class="border border-gray-300 rounded p-2 bg-gray-50">
                        @foreach($kartuKendaliList->filter(function($k) { return !empty($k->catatan); }) as $kendali)
                            <div class="text-xs mb-1">
                                <span class="font-semibold">{{ $kendali->nama_kegiatan }}:</span>
                                <span class="text-gray-700">{{ $kendali->catatan }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Catatan Kartu Tes Seleksi dari Admin -->
                @if($catatan_kartu_ujian)
                <div class="mt-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">CATATAN:</h4>
                    <div class="border-2 border-gray-400 rounded p-2 bg-yellow-50">
                        <div class="whitespace-pre-wrap text-xs text-gray-700">{{ $catatan_kartu_ujian }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Invoice Tes Psikologi Tab -->
    @if($activeTab === 'invoice-psikologi')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Tes Psikologi</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="psikologi_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-PSI-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="psikologi_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice tes psikologi yang diterima oleh pendaftar.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-3 pb-2 border-b-2 border-gray-800">
                            <h2 style="font-size: 13px;" class="font-bold text-gray-900 uppercase">
                                {{ $printSettings['print_institution_name'] ?: 'NAMA LEMBAGA' }}
                            </h2>
                            <p style="font-size: 10px;" class="text-gray-700">
                                {{ $printSettings['print_institution_address'] ?: 'Alamat Lembaga' }}
                            </p>
                            <p style="font-size: 9px;" class="text-gray-600">
                                @if($printSettings['print_institution_phone']) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                                @if($printSettings['print_institution_email']) | Email: {{ $printSettings['print_institution_email'] }} @endif
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 style="font-size: 12px;" class="font-bold text-gray-800">INVOICE TES PSIKOLOGI</h3>
                            <p style="font-size: 10px;" class="text-gray-600">
                                No: {{ str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $psikologi_nomor_format) }}
                            </p>
                            <p style="font-size: 9px;" class="text-gray-500">Tanggal: {{ date('d/m/Y') }}</p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 30%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 10px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Keterangan</th>
                                        <th class="border border-gray-300 px-3 py-2 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-3 py-2">Biaya Tes Psikologi</td>
                                        <td class="border border-gray-300 px-3 py-2 text-right">Rp 100.000</td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-3 py-2">Total</td>
                                        <td class="border border-gray-300 px-3 py-2 text-right">Rp 100.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="mt-3 pt-3 border-t border-gray-300 text-center" style="font-size: 10px;" class="text-gray-600">
                            @if($psikologi_footer)
                                {{ $psikologi_footer }}
                            @else
                                Terima kasih atas kepercayaan Anda
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Invoice DU Madrasah Tab -->
    @if($activeTab === 'invoice-madrasah')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Daftar Ulang Madrasah</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="madrasah_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-MDR-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="madrasah_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice daftar ulang madrasah yang diterima oleh pendaftar.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-2 pb-1 border-b border-gray-800">
                            <h2 style="font-size: 10px;" class="font-bold text-gray-900 uppercase">
                                {{ $printSettings['print_institution_name'] ?: 'NAMA LEMBAGA' }}
                            </h2>
                            <p style="font-size: 8px;" class="text-gray-700">
                                {{ $printSettings['print_institution_address'] ?: 'Alamat Lembaga' }}
                            </p>
                            <p style="font-size: 7px;" class="text-gray-600">
                                @if($printSettings['print_institution_phone']) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                                @if($printSettings['print_institution_email']) | Email: {{ $printSettings['print_institution_email'] }} @endif
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 class="text-xs font-bold text-gray-800">INVOICE DAFTAR ULANG MADRASAH</h3>
                            <p class="text-xs text-gray-600">
                                No: {{ str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $madrasah_nomor_format) }}
                            </p>
                            <p style="font-size: 8px;" class="text-gray-500">Tanggal: {{ date('d/m/Y') }}</p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 35%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Opsi Pembayaran</td>
                                        <td class="font-semibold text-gray-900">: LUNAS</td>
                                    </tr>
                                </table>
                                <p class="text-xs text-gray-500 mt-1">
                                    * Status akan <span class="text-emerald-600 font-semibold">hijau (LUNAS/DISETUJUI)</span> jika disetujui,
                                    <span class="text-orange-600 font-semibold">orange (MENUNGGU VERIFIKASI)</span> jika belum,
                                    atau <span class="text-red-600 font-semibold">merah (DITOLAK)</span> jika ditolak
                                </p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-2">
                            <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 8px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 text-left" style="width: 40%;">Keterangan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Nominal Biaya</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Konfirmasi Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">
                                            <span class="text-xs text-gray-700">Biaya Daftar Ulang Full</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs">Rp 600.000</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs">Rp 550.000</span>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-2 py-1">Status Pembayaran</td>
                                        <td colspan="2" class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs text-orange-600">KEKURANGAN: Rp 50.000</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-2 bg-blue-50 border border-blue-200 rounded px-2 py-1" style="font-size: 7px;">
                                <strong>ℹ️ Info:</strong> Invoice menampilkan:<br>
                                • <strong>Nominal Biaya</strong>: dari "Pilih Nominal Biaya"<br>
                                • <strong>Konfirmasi Pembayaran</strong>: dari "Konfirmasi Nominal yang Dibayarkan"<br>
                                • <strong>Status Pembayaran</strong>: LUNAS (sama), KEMBALIAN (lebih), atau KEKURANGAN (kurang)
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-2 pt-2 border-t border-gray-300 text-center" style="font-size: 8px;" class="text-gray-600">
                            @if($madrasah_footer)
                                {{ $madrasah_footer }}
                            @else
                                Terima kasih atas kepercayaan Anda
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Invoice DU Seragam Tab -->
    @if($activeTab === 'invoice-seragam')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Daftar Ulang Seragam</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="seragam_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-SRG-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="seragam_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice daftar ulang seragam yang diterima oleh pendaftar. Invoice ini juga akan menampilkan ukuran seragam peserta.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-2 pb-1 border-b border-gray-800">
                            <h2 style="font-size: 10px;" class="font-bold text-gray-900 uppercase">
                                {{ $printSettings['print_institution_name'] ?: 'NAMA LEMBAGA' }}
                            </h2>
                            <p style="font-size: 8px;" class="text-gray-700">
                                {{ $printSettings['print_institution_address'] ?: 'Alamat Lembaga' }}
                            </p>
                            <p style="font-size: 7px;" class="text-gray-600">
                                @if($printSettings['print_institution_phone']) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                                @if($printSettings['print_institution_email']) | Email: {{ $printSettings['print_institution_email'] }} @endif
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 class="text-xs font-bold text-gray-800">INVOICE DAFTAR ULANG SERAGAM</h3>
                            <p class="text-xs text-gray-600">
                                No: {{ str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $seragam_nomor_format) }}
                            </p>
                            <p style="font-size: 8px;" class="text-gray-500">Tanggal: {{ date('d/m/Y') }}</p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 35%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Opsi Pembayaran</td>
                                        <td class="font-semibold text-gray-900">: ANGSURAN</td>
                                    </tr>
                                </table>
                                <p class="text-xs text-gray-500 mt-1">
                                    * Status akan <span class="text-emerald-600 font-semibold">hijau (LUNAS/DISETUJUI)</span> jika disetujui,
                                    <span class="text-orange-600 font-semibold">orange (MENUNGGU VERIFIKASI)</span> jika belum,
                                    atau <span class="text-red-600 font-semibold">merah (DITOLAK)</span> jika ditolak
                                </p>
                            </div>
                        </div>

                        <!-- Uniform Size -->
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Ukuran Seragam & Atribut:</h4>
                            <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2">
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td style="width: 25%">Ukuran Jas</td>
                                        <td class="font-semibold" style="width: 25%">: M</td>
                                        <td style="width: 25%">Ukuran Baju Olahraga</td>
                                        <td class="font-semibold" style="width: 25%">: L</td>
                                    </tr>
                                    <tr>
                                        <td>Ukuran Celana Olahraga</td>
                                        <td class="font-semibold">: L</td>
                                        <td>Ukuran Baju Harian</td>
                                        <td class="font-semibold">: M</td>
                                    </tr>
                                    <tr>
                                        <td>Ukuran Celana/Rok Harian</td>
                                        <td class="font-semibold">: M</td>
                                        <td>Ukuran Kopyah</td>
                                        <td class="font-semibold">: M</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-2">
                            <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 8px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 text-left" style="width: 40%;">Keterangan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Nominal Biaya</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right" style="width: 30%;">Konfirmasi Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">
                                            <span class="text-xs text-gray-700">Paket Seragam Lengkap</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs">Rp 750.000</span>
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs">Rp 750.000</span>
                                        </td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-2 py-1">Status Pembayaran</td>
                                        <td colspan="2" class="border border-gray-300 px-2 py-1 text-right">
                                            <span class="text-xs text-emerald-600">LUNAS</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-2 bg-blue-50 border border-blue-200 rounded px-2 py-1" style="font-size: 7px;">
                                <strong>ℹ️ Info:</strong> Invoice menampilkan:<br>
                                • <strong>Nominal Biaya</strong>: dari "Pilih Paket Seragam"<br>
                                • <strong>Konfirmasi Pembayaran</strong>: dari "Konfirmasi Nominal Seragam yang Dibayarkan"<br>
                                • <strong>Status</strong>: LUNAS (sama), KEMBALIAN (lebih), atau KEKURANGAN (kurang)
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-2 pt-2 border-t border-gray-300 text-center" style="font-size: 8px;" class="text-gray-600">
                            @if($seragam_footer)
                                {{ $seragam_footer }}
                            @else
                                Terima kasih atas kepercayaan Anda
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
