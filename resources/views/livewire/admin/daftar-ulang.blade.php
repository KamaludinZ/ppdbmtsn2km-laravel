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

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button
                        wire:click="switchTab('madrasah')"
                        class="{{ $activeTab == 'madrasah' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Daftar Ulang Madrasah
                    </button>
                    <button
                        wire:click="switchTab('seragam')"
                        class="{{ $activeTab == 'seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Daftar Ulang Seragam
                    </button>
                    <button
                        wire:click="switchTab('ukuran')"
                        class="{{ $activeTab == 'ukuran' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Ukuran Seragam & Atribut
                    </button>
                    <button
                        wire:click="switchTab('settings')"
                        class="{{ $activeTab == 'settings' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Pengaturan
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab != 'settings')
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-sm sm:text-lg font-bold text-white uppercase tracking-wider">Filter Pendaftar</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between gap-4">
                        <div class="flex-1">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NISN, atau no. pendaftaran..." class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                        <div>
                            <select wire:model.live="filterJalur" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Jalur</option>
                                @foreach($jalurPendaftaran as $jalur)
                                    <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(in_array($activeTab, ['seragam', 'ukuran']))
                        <div>
                            <select wire:model.live="filterJenisKelamin" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        @endif
                        <div>
                            <select wire:model.live="filterStatus" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="menunggu">Menunggu</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($activeTab == 'settings')
            <!-- Settings Tab -->
            <div class="space-y-6">
                <!-- Information Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-blue-200 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <div class="flex items-center">
                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-sm sm:text-lg font-bold text-white">INFORMASI PENGATURAN DAFTAR ULANG</h3>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Info Daftar Ulang Madrasah -->
                            <div class="bg-white rounded-lg p-4 border-l-4 border-emerald-500">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Daftar Ulang Madrasah</h4>
                                        <p class="text-xs text-gray-600">
                                            Atur template surat pernyataan, nominal biaya, opsi pembayaran, dan informasi bank untuk daftar ulang madrasah.
                                        </p>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Template surat dapat diunduh oleh pendaftar
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Mendukung multiple bank/metode pembayaran
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Daftar Ulang Seragam -->
                            <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Daftar Ulang Seragam</h4>
                                        <p class="text-xs text-gray-600">
                                            Kelola paket seragam, nominal biaya, dan metode pembayaran khusus untuk pembelian seragam.
                                        </p>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Dapat membuat multiple paket seragam
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Opsi pembayaran lunas/angsuran
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Ukuran Seragam -->
                            <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900 mb-1">Ukuran Seragam & Atribut</h4>
                                        <p class="text-xs text-gray-600">
                                            Atur opsi ukuran untuk jas, atasan, bawahan, dan atribut lainnya dengan panduan visual.
                                        </p>
                                        <div class="mt-2 space-y-1">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Upload gambar panduan ukuran
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pisahkan ukuran berdasarkan gender
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Catatan Penting:</h3>
                                    <div class="mt-2 text-xs text-yellow-700 space-y-1">
                                        <li>Semua pengaturan akan langsung diterapkan setelah disimpan</li>
                                        <li>Template dan gambar panduan dapat diunduh oleh pendaftar dari halaman daftar ulang mereka</li>
                                        <li>Pastikan informasi bank dan metode pembayaran sudah benar sebelum diumumkan</li>
                                        <li>Perubahan nominal biaya tidak akan mempengaruhi data yang sudah ada</li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 0. Pengaturan Umum Daftar Ulang -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Umum Daftar Ulang</h3>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveSettings" class="p-6">
                        <div class="space-y-6">
                            <!-- Informasi Pembayaran (Ditampilkan ke semua halaman) -->
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-blue-900">Informasi Pembayaran</label>
                                        <p class="text-xs text-blue-700 mt-1">Informasi ini akan ditampilkan di halaman daftar ulang untuk Admin, Panitia, dan Pendaftar</p>
                                    </div>
                                </div>
                                <textarea
                                    wire:model="paymentInfoText"
                                    rows="4"
                                    placeholder="Contoh: Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah..."
                                    class="w-full rounded-lg border-blue-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                ></textarea>
                                <div class="mt-2 flex items-center text-xs text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Teks ini muncul di bagian atas halaman daftar ulang pada semua role (Admin, Panitia, Pendaftar)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan Umum
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 1. Pengaturan Daftar Ulang Madrasah -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Daftar Ulang Madrasah</h3>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveMadrasahSettings" class="p-6">
                        <div class="space-y-6">
                            <!-- Informasi untuk Pendaftar -->
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                                <div class="flex items-start space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-indigo-900">Informasi/Pengumuman untuk Pendaftar</label>
                                        <p class="text-xs text-indigo-700 mt-1">Informasi ini akan ditampilkan di halaman daftar ulang madrasah pendaftar</p>
                                    </div>
                                </div>
                                <textarea
                                    wire:model="madrasahInfo"
                                    rows="4"
                                    placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait daftar ulang madrasah..."
                                    class="w-full rounded-lg border-indigo-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                                <div class="mt-2 flex items-center text-xs text-indigo-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Mendukung format HTML sederhana (bold, italic, list, dll)</span>
                                </div>
                            </div>

                            <!-- Upload Template Surat Pernyataan -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-blue-900 mb-2">Template Surat Pernyataan</label>
                                <div class="space-y-3">
                                    <input type="file" wire:model="madrasahTemplateSuratPernyataan" accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
                                    <p class="text-xs text-blue-700">Upload template surat pernyataan yang bisa diunduh oleh pendaftar (PDF/DOC/DOCX)</p>
                                    @if($existingMadrasahTemplate)
                                        <div class="flex items-center justify-between bg-white p-3 rounded border border-blue-300">
                                            <span class="text-sm text-gray-700">Template saat ini tersedia</span>
                                            <a href="{{ Storage::url($existingMadrasahTemplate) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Nominal Biaya Daftar Ulang -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-green-900">Nominal Biaya</label>
                                    <button type="button" wire:click="addMadrasahNominal" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-green-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="madrasahEnableOpsiLainnya" class="rounded border-gray-300 text-green-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi nominal pembayaran secara manual.</p>
                                </div>

                                @foreach($madrasahNominals as $index => $nominal)
                                    <div class="bg-white border border-green-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="madrasahNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: SPP Bulanan)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="madrasahNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeMadrasahNominal({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus nominal madrasah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Opsi Nominal yang Dibayarkan -->
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-cyan-900">Opsi Nominal yang Dibayarkan</label>
                                    <button type="button" wire:click="addMadrasahPaymentNominal" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah</button>
                                </div>
                                <p class="text-xs text-gray-600 mb-3">Opsi nominal yang muncul pada field konfirmasi nominal yang dibayarkan (terpisah dari nominal surat pernyataan)</p>

                                @foreach($madrasahPaymentNominals as $index => $nominal)
                                    <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="madrasahPaymentNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: SPP Bulanan)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="madrasahPaymentNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeMadrasahPaymentNominal({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus opsi nominal ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Checkbox untuk Opsi Lainnya -->
                                <div class="mt-4 pt-3 border-t border-cyan-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="madrasahPaymentNominalsEnableOpsiLainnya" class="rounded border-gray-300 text-cyan-600">
                                        <span class="ml-2 text-sm text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 ml-6 mt-1">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi nominal pembayaran secara manual.</p>
                                </div>
                            </div>

                            <!-- Opsi Pembayaran -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Opsi Pembayaran</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="madrasahPaymentOptions.lunas" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Lunas</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="madrasahPaymentOptions.angsuran" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Angsuran</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="madrasahPaymentOptions.potongan" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Potongan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Bank/Metode Pembayaran -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-yellow-900">Daftar Bank/Metode Pembayaran</label>
                                    <button type="button" wire:click="addMadrasahBank" class="text-xs bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">+ Tambah</button>
                                </div>

                                @foreach($madrasahBanks as $index => $bank)
                                    <div class="bg-white border border-yellow-300 rounded-lg p-3 mb-2">
                                        <div class="flex gap-3">
                                            <input type="text" wire:model="madrasahBanks.{{ $index }}.name" placeholder="Nama Bank / Metode Pembayaran (Contoh: Transfer BCA, Tunai)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeMadrasahBank({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus bank madrasah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm whitespace-nowrap">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Waktu Pembayaran -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-indigo-900 mb-2">Waktu Pembayaran</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" wire:model="madrasahPaymentStart" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-700 mb-1">Tanggal Berakhir</label>
                                        <input type="date" wire:model="madrasahPaymentEnd" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Bukti Pembayaran -->
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="madrasahRequirePaymentProof" class="rounded border-gray-300 text-emerald-600">
                                    <span class="ml-2 text-sm font-semibold text-pink-900">Wajib Upload Bukti Pembayaran</span>
                                </label>
                                <p class="text-xs text-pink-700 mt-2">Jika diaktifkan, pendaftar wajib upload bukti pembayaran saat daftar ulang madrasah</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan Madrasah
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 2. Pengaturan Daftar Ulang Seragam -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Daftar Ulang Seragam</h3>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveSeragamSettings" class="p-6">
                        <div class="space-y-6">
                            <!-- Informasi untuk Pendaftar -->
                            <div class="bg-gradient-to-r from-cyan-50 to-teal-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex items-start space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-cyan-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-cyan-900">Informasi/Pengumuman untuk Pendaftar</label>
                                        <p class="text-xs text-cyan-700 mt-1">Informasi ini akan ditampilkan di halaman daftar ulang seragam pendaftar</p>
                                    </div>
                                </div>
                                <textarea
                                    wire:model="seragamInfo"
                                    rows="4"
                                    placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pembelian seragam..."
                                    class="w-full rounded-lg border-cyan-300 text-sm focus:border-cyan-500 focus:ring-cyan-500"
                                ></textarea>
                                <div class="mt-2 flex items-center text-xs text-cyan-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Mendukung format HTML sederhana (bold, italic, list, dll)</span>
                                </div>
                            </div>

                            <!-- Paket Seragam -->
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-cyan-900">Paket Seragam</label>
                                    <button type="button" wire:click="addSeragamPackage" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah Paket</button>
                                </div>

                                @foreach($seragamPackages as $index => $package)
                                    <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="seragamPackages.{{ $index }}.name" placeholder="Nama Paket (contoh: Paket Lengkap)" class="rounded border-gray-300 text-sm">
                                            <textarea wire:model="seragamPackages.{{ $index }}.description" placeholder="Deskripsi paket" rows="2" class="rounded border-gray-300 text-sm"></textarea>
                                            <button type="button"
                                                    wire:click="removeSeragamPackage({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus paket seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Nominal Biaya Seragam -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-green-900">Nominal Biaya Seragam</label>
                                    <button type="button" wire:click="addSeragamNominal" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-green-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="seragamEnableOpsiLainnya" class="rounded border-gray-300 text-green-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi nominal pembayaran seragam secara manual.</p>
                                </div>

                                @foreach($seragamNominals as $index => $nominal)
                                    <div class="bg-white border border-green-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="seragamNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: Seragam Putih)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="seragamNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeSeragamNominal({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus nominal seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Opsi Nominal yang Dibayarkan -->
                            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-cyan-900">Opsi Nominal yang Dibayarkan</label>
                                    <button type="button" wire:click="addSeragamPaymentNominal" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah</button>
                                </div>
                                <p class="text-xs text-gray-600 mb-3">Opsi nominal yang muncul pada field konfirmasi nominal yang dibayarkan (terpisah dari nominal surat pernyataan)</p>

                                @foreach($seragamPaymentNominals as $index => $nominal)
                                    <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="seragamPaymentNominals.{{ $index }}.name" placeholder="Nama biaya (contoh: Seragam Putih)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="seragamPaymentNominals.{{ $index }}.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeSeragamPaymentNominal({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus opsi nominal ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Checkbox untuk Opsi Lainnya -->
                                <div class="mt-4 pt-3 border-t border-cyan-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="seragamPaymentNominalsEnableOpsiLainnya" class="rounded border-gray-300 text-cyan-600">
                                        <span class="ml-2 text-sm text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 ml-6 mt-1">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi nominal pembayaran secara manual.</p>
                                </div>
                            </div>

                            <!-- Opsi Pembayaran Seragam -->
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Opsi Pembayaran</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="seragamPaymentOptions.lunas" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Lunas</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="seragamPaymentOptions.angsuran" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Angsuran</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="seragamPaymentOptions.potongan" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Potongan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Bank/Metode Pembayaran Seragam -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-yellow-900">Daftar Bank/Metode Pembayaran</label>
                                    <button type="button" wire:click="addSeragamBank" class="text-xs bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">+ Tambah</button>
                                </div>

                                @foreach($seragamBanks as $index => $bank)
                                    <div class="bg-white border border-yellow-300 rounded-lg p-3 mb-2">
                                        <div class="flex gap-3">
                                            <input type="text" wire:model="seragamBanks.{{ $index }}.name" placeholder="Nama Bank / Metode Pembayaran (Contoh: Transfer BCA, Tunai)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeSeragamBank({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus bank seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm whitespace-nowrap">Hapus</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Waktu Pembayaran Seragam -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-indigo-900 mb-2">Waktu Pembayaran</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" wire:model="seragamPaymentStart" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-700 mb-1">Tanggal Berakhir</label>
                                        <input type="date" wire:model="seragamPaymentEnd" class="w-full rounded border-gray-300 text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Bukti Pembayaran Seragam -->
                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="seragamRequirePaymentProof" class="rounded border-gray-300 text-emerald-600">
                                    <span class="ml-2 text-sm font-semibold text-pink-900">Wajib Upload Bukti Pembayaran</span>
                                </label>
                                <p class="text-xs text-pink-700 mt-2">Jika diaktifkan, pendaftar wajib upload bukti pembayaran saat pemesanan seragam</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan Seragam
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. Pengaturan Ukuran Seragam -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Ukuran Seragam</h3>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveUkuranSettings" class="p-6">
                        <div class="space-y-6">
                            <!-- Informasi untuk Pendaftar -->
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-start space-x-2 mb-3">
                                    <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <label class="block text-sm font-semibold text-purple-900">Informasi/Pengumuman untuk Pendaftar</label>
                                        <p class="text-xs text-purple-700 mt-1">Informasi ini akan ditampilkan di halaman pemilihan ukuran seragam pendaftar</p>
                                    </div>
                                </div>
                                <textarea
                                    wire:model="ukuranInfo"
                                    rows="4"
                                    placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pemilihan ukuran seragam..."
                                    class="w-full rounded-lg border-purple-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                                ></textarea>
                                <div class="mt-2 flex items-center text-xs text-purple-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Mendukung format HTML sederhana (bold, italic, list, dll)</span>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Pengaturan Berdasarkan Jenis Kelamin</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranByGender" class="rounded border-gray-300 text-emerald-600">
                                        <span class="ml-2 text-sm text-gray-700">Pisahkan ukuran untuk Laki-laki dan Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 1. Ukuran Jas -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-amber-900 mb-3">1. Ukuran Jas</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Jas</label>
                                    <input type="file" wire:model="ukuranJasImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranJasImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranJasImage) }}" class="h-32 rounded border" alt="Panduan Jas">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-amber-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranJasEnableOpsiLainnya" class="rounded border-gray-300 text-amber-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran jas secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Jas</label>
                                        <button type="button" wire:click="addUkuranJas" class="text-xs bg-amber-600 text-white px-2 py-1 rounded hover:bg-amber-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranJasOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranJasOptions.{{ $index }}.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranJasOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: LD 50cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranJas({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran jas ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 2. Ukuran Baju Seragam Olahraga -->
                            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-sky-900 mb-3">2. Ukuran Baju Seragam Olahraga</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Baju Olahraga</label>
                                    <input type="file" wire:model="ukuranBajuOlahragaImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranBajuOlahragaImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranBajuOlahragaImage) }}" class="h-32 rounded border" alt="Panduan Baju Olahraga">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-sky-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranBajuOlahragaEnableOpsiLainnya" class="rounded border-gray-300 text-sky-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran baju olahraga secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Baju Olahraga</label>
                                        <button type="button" wire:click="addUkuranBajuOlahraga" class="text-xs bg-sky-600 text-white px-2 py-1 rounded hover:bg-sky-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranBajuOlahragaOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranBajuOlahragaOptions.{{ $index }}.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranBajuOlahragaOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: LD 48cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranBajuOlahraga({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran baju olahraga ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 3. Ukuran Celana Seragam Olahraga -->
                            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-violet-900 mb-3">3. Ukuran Celana Seragam Olahraga</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Celana Olahraga</label>
                                    <input type="file" wire:model="ukuranCelanaOlahragaImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranCelanaOlahragaImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranCelanaOlahragaImage) }}" class="h-32 rounded border" alt="Panduan Celana Olahraga">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-violet-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranCelanaOlahragaEnableOpsiLainnya" class="rounded border-gray-300 text-violet-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran celana olahraga secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Celana Olahraga</label>
                                        <button type="button" wire:click="addUkuranCelanaOlahraga" class="text-xs bg-violet-600 text-white px-2 py-1 rounded hover:bg-violet-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranCelanaOlahragaOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranCelanaOlahragaOptions.{{ $index }}.size" placeholder="Ukuran (contoh: S, M, L, 28, 30)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranCelanaOlahragaOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: LP 70cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranCelanaOlahraga({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran celana olahraga ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 4. Ukuran Baju Seragam Harian -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-blue-900 mb-3">4. Ukuran Baju Seragam Harian</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Baju Harian</label>
                                    <input type="file" wire:model="ukuranBajuHarianImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranBajuHarianImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranBajuHarianImage) }}" class="h-32 rounded border" alt="Panduan Baju Harian">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-blue-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranBajuHarianEnableOpsiLainnya" class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran baju harian secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Baju Harian</label>
                                        <button type="button" wire:click="addUkuranBajuHarian" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranBajuHarianOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranBajuHarianOptions.{{ $index }}.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranBajuHarianOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: LD 48cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranBajuHarian({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran baju harian ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 5. Ukuran Celana/Rok Seragam Harian -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-green-900 mb-3">5. Ukuran Celana/Rok Seragam Harian</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Celana/Rok Harian</label>
                                    <input type="file" wire:model="ukuranCelanaRokHarianImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranCelanaRokHarianImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranCelanaRokHarianImage) }}" class="h-32 rounded border" alt="Panduan Celana/Rok Harian">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-green-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranCelanaRokHarianEnableOpsiLainnya" class="rounded border-gray-300 text-green-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran celana/rok harian secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Celana/Rok Harian</label>
                                        <button type="button" wire:click="addUkuranCelanaRokHarian" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranCelanaRokHarianOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranCelanaRokHarianOptions.{{ $index }}.size" placeholder="Ukuran (contoh: S, M, L, 28, 30)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranCelanaRokHarianOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: LP 70cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranCelanaRokHarian({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran celana/rok harian ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 6. Kopyah (Khusus Laki-laki) -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-indigo-900 mb-3">4. Kopyah (Khusus Laki-laki)</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Kopyah</label>
                                    <input type="file" wire:model="ukuranKopyahImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    @if($existingUkuranKopyahImage)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($existingUkuranKopyahImage) }}" class="h-32 rounded border" alt="Panduan Kopyah">
                                        </div>
                                    @endif
                                </div>

                                <!-- Enable Opsi Lainnya Toggle -->
                                <div class="mb-4 p-3 bg-white rounded-lg border border-indigo-300">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="ukuranKopyahEnableOpsiLainnya" class="rounded border-gray-300 text-indigo-600">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan Opsi "Lainnya" (Input Manual)</span>
                                    </label>
                                    <p class="text-xs text-gray-600 mt-1 ml-6">Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi ukuran kopyah secara manual.</p>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Kopyah</label>
                                        <button type="button" wire:click="addUkuranKopyah" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">+ Tambah</button>
                                    </div>
                                    @foreach($ukuranKopyahOptions as $index => $option)
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranKopyahOptions.{{ $index }}.size" placeholder="Ukuran (contoh: 6, 7, 8)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranKopyahOptions.{{ $index }}.measurement" placeholder="Ukuran detail (contoh: Lingkar 54cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranKopyah({{ $index }})"
                                                    wire:confirm="Yakin ingin menghapus ukuran kopyah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan Ukuran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Stats Overview -->
            @include('livewire.admin.partials.daftar-ulang-stats', ['tab' => $activeTab])

            <!-- Information Banner -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ $paymentInfoText ?: 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="text-sm sm:text-lg font-bold text-white uppercase tracking-wider">
                        @if($activeTab == 'madrasah')
                            Daftar Ulang Madrasah
                        @elseif($activeTab == 'seragam')
                            Daftar Ulang Seragam
                        @else
                            Ukuran Seragam & Atribut
                        @endif
                    </h3>
                </div>

                <!-- Responsive Table Wrapper -->
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persetujuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Persetujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @if($activeTab == 'madrasah')
                                    Status DU Madrasah
                                @elseif($activeTab == 'seragam')
                                    Status DU Seragam
                                @else
                                    Status Ukuran
                                @endif
                            </th>
                            @if($activeTab == 'madrasah')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Pernyataan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal yang Dibayar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            @elseif($activeTab == 'seragam')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket Seragam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal yang Dibayar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            @else
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Jas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baju Olahraga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celana Olahraga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Baju Seragam Harian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celana/Rok Harian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kopyah (L)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            @endif
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->jalurPendaftaran)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white" style="background-color: {{ $item->jalurPendaftaran->warna ?? '#6B7280' }}">
                                            {{ $item->jalurPendaftaran->nama }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $persetujuanColors = [
                                            'belum_memilih' => 'bg-gray-100 text-gray-800',
                                            'setuju' => 'bg-green-100 text-green-800',
                                            'tidak_setuju' => 'bg-red-100 text-red-800',
                                        ];
                                        $persetujuanLabels = [
                                            'belum_memilih' => 'Belum Memilih',
                                            'setuju' => '✓ Setuju',
                                            'tidak_setuju' => '✗ Tidak Melanjutkan',
                                        ];
                                        $statusPersetujuan = $item->status_persetujuan_daftar_ulang ?? 'belum_memilih';
                                        $persetujuanColor = $persetujuanColors[$statusPersetujuan] ?? 'bg-gray-100 text-gray-800';
                                        $persetujuanLabel = $persetujuanLabels[$statusPersetujuan] ?? 'Belum Memilih';
                                    @endphp
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $persetujuanColor }}">
                                            {{ $persetujuanLabel }}
                                        </span>
                                        @if($item->tanggal_pilihan_daftar_ulang)
                                            <span class="text-xs text-gray-500">{{ $item->tanggal_pilihan_daftar_ulang->format('d/m/Y H:i') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" @click.away="open = false" type="button"
                                                class="inline-flex items-center justify-center gap-x-1.5 rounded-md bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                                            Aksi
                                            <svg class="-mr-1 h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 z-[100] mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none" x-cloak>
                                            <div class="py-1">
                                                <div class="px-4 py-2 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Pilih Status</div>
                                                <button wire:click="changeStatusPersetujuan({{ $item->id }}, 'belum_memilih')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-gray-400 group-hover:animate-pulse"></span>
                                                    Belum Memilih
                                                </button>
                                                <button wire:click="changeStatusPersetujuan({{ $item->id }}, 'setuju')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-green-500 group-hover:ring-2 group-hover:ring-green-300"></span>
                                                    Setuju Daftar Ulang
                                                </button>
                                                <button wire:click="changeStatusPersetujuan({{ $item->id }}, 'tidak_setuju')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-red-500 group-hover:ring-2 group-hover:ring-red-300"></span>
                                                    Tidak Melanjutkan
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <button wire:click="resetPersetujuan({{ $item->id }})"
                                                        @click="open = false"
                                                        onclick="return confirm('Apakah Anda yakin ingin reset persetujuan pendaftar ini? Pendaftar harus memilih ulang dari Dashboard mereka.')"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-orange-600 hover:bg-orange-50 transition-colors">
                                                    <svg class="mr-3 h-4 w-4 text-orange-400 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Reset ke Awal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status_persetujuan_daftar_ulang === 'tidak_setuju' && $item->alasan_tidak_setuju)
                                        <div class="max-w-xs">
                                            <div class="text-xs text-gray-700 bg-gray-50 p-2 rounded border border-gray-200">
                                                <span class="font-semibold text-red-600">Alasan:</span>
                                                <p class="mt-1">{{ $item->alasan_tidak_setuju }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'proses' => 'bg-blue-100 text-blue-800',
                                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'disetujui' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                            'perbaiki' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $statusLabels = [
                                            'proses' => 'Proses',
                                            'menunggu' => 'Menunggu',
                                            'disetujui' => 'Disetujui',
                                            'ditolak' => 'Ditolak',
                                            'perbaiki' => 'Perbaiki',
                                        ];

                                        if ($activeTab == 'madrasah') {
                                            $status = $item->daftarUlang->status ?? 'menunggu';
                                        } elseif ($activeTab == 'seragam') {
                                            $status = $item->seragam->status_seragam ?? 'menunggu';
                                        } else {
                                            $status = $item->seragam->status_ukuran ?? 'menunggu';
                                        }

                                        $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                @if($activeTab == 'madrasah')
                                    <!-- Surat Pernyataan -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->surat_pernyataan_path)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="{{ route('pendaftar.download-file', ['type' => 'surat_pernyataan', 'pendaftarId' => $item->id]) }}"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Nominal Biaya (Awal untuk Surat Pernyataan) -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->nominal_biaya_awal)
                                            @php $nominalBiayaAwal = $item->daftarUlang->nominal_biaya_awal; @endphp
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1">{{ $nominalBiayaAwal['name'] ?? '-' }}</div>
                                                <div class="font-bold text-blue-700">Rp {{ number_format($nominalBiayaAwal['amount'] ?? 0, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Belum pilih</span>
                                        @endif
                                    </td>
                                    <!-- Nominal yang Dibayar (Konfirmasi) -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->nominal_biaya)
                                            @php $nominalBiaya = $item->daftarUlang->nominal_biaya; @endphp
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1">{{ $nominalBiaya['name'] ?? '-' }}</div>
                                                <div class="font-bold text-emerald-700">Rp {{ number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Belum konfirmasi</span>
                                        @endif
                                    </td>
                                    <!-- Opsi Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->opsi_pembayaran)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ ucfirst($item->daftarUlang->opsi_pembayaran) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Bank/Metode -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->metode_pembayaran)
                                            <div class="text-xs text-gray-900">
                                                <span class="font-medium">{{ $item->daftarUlang->metode_pembayaran }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Bukti Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->bukti_pembayaran_madrasah)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_madrasah', 'pendaftarId' => $item->id]) }}"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Waktu Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->daftarUlang && $item->daftarUlang->waktu_pembayaran_madrasah)
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('d/m/Y') }}</div>
                                                <div class="text-gray-600">{{ $item->daftarUlang->waktu_pembayaran_madrasah->format('H:i') }} WIB</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Catatan Madrasah -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-3">
                                            <!-- Catatan (visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->daftarUlang && $item->daftarUlang->catatan)
                                                    <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 max-w-xs">
                                                        <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->daftarUlang->catatan }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanModal('madrasah', {{ $item->id }}, '{{ addslashes($item->daftarUlang->catatan ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                                    title="Edit Catatan">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                            </div>

                                            <!-- Catatan Internal (NOT visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->daftarUlang && $item->daftarUlang->catatan_internal)
                                                    <div class="text-xs text-gray-700 bg-red-50 p-2 rounded border border-red-200 max-w-xs">
                                                        <div class="font-semibold text-red-700 mb-1">🔒 Internal:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->daftarUlang->catatan_internal }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan internal</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanInternalModal('madrasah', {{ $item->id }}, '{{ addslashes($item->daftarUlang->catatan_internal ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded hover:bg-red-100 transition-colors"
                                                    title="Edit Catatan Internal (Tidak ditampilkan ke pendaftar)">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Internal
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                @elseif($activeTab == 'seragam')
                                    <!-- Paket Seragam -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->paket_seragam)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                {{ $item->seragam->paket_seragam }}
                                            </span>
                                            @if($item->seragam->paket_description)
                                                <div class="text-xs text-gray-600 mt-1">{{ $item->seragam->paket_description }}</div>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-500">Belum dipilih</span>
                                        @endif
                                    </td>
                                    <!-- Nominal Biaya (Awal untuk Surat Pernyataan) -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->nominal_seragam_awal)
                                            @php $nominalSeragamAwal = $item->seragam->nominal_seragam_awal; @endphp
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragamAwal['name'] ?? '-' }}</div>
                                                <div class="font-bold text-blue-700">Rp {{ number_format($nominalSeragamAwal['amount'] ?? 0, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Belum pilih</span>
                                        @endif
                                    </td>
                                    <!-- Nominal yang Dibayar (Konfirmasi) -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->nominal_seragam)
                                            @php $nominalSeragam = $item->seragam->nominal_seragam; @endphp
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1">{{ $nominalSeragam['name'] ?? '-' }}</div>
                                                <div class="font-bold text-emerald-700">Rp {{ number_format($nominalSeragam['amount'] ?? 0, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Belum konfirmasi</span>
                                        @endif
                                    </td>
                                    <!-- Opsi Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->opsi_pembayaran_seragam)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ ucfirst($item->seragam->opsi_pembayaran_seragam) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Bank/Metode -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->bank_seragam)
                                            <div class="text-xs text-gray-900">
                                                <span class="font-medium">{{ $item->seragam->bank_seragam }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Bukti Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->bukti_pembayaran)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="{{ route('pendaftar.download-file', ['type' => 'bukti_pembayaran_seragam', 'pendaftarId' => $item->id]) }}"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Waktu Pembayaran -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->waktu_pembayaran_seragam)
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium">{{ $item->seragam->waktu_pembayaran_seragam->format('d/m/Y') }}</div>
                                                <div class="text-gray-600">{{ $item->seragam->waktu_pembayaran_seragam->format('H:i') }} WIB</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Catatan Seragam -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-3">
                                            <!-- Catatan (visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->seragam && $item->seragam->catatan_seragam)
                                                    <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 max-w-xs">
                                                        <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_seragam }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanModal('seragam', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_seragam ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                                    title="Edit Catatan">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                            </div>

                                            <!-- Catatan Internal (NOT visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->seragam && $item->seragam->catatan_internal_seragam)
                                                    <div class="text-xs text-gray-700 bg-red-50 p-2 rounded border border-red-200 max-w-xs">
                                                        <div class="font-semibold text-red-700 mb-1">🔒 Internal:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_internal_seragam }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan internal</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanInternalModal('seragam', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_internal_seragam ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded hover:bg-red-100 transition-colors"
                                                    title="Edit Catatan Internal (Tidak ditampilkan ke pendaftar)">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Internal
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <!-- Ukuran Jas -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->ukuran_jas)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                                {{ $item->seragam->ukuran_jas }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Baju Olahraga -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->ukuran_baju_olahraga)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->seragam->ukuran_baju_olahraga }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Celana Olahraga -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->ukuran_celana_olahraga)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">
                                                {{ $item->seragam->ukuran_celana_olahraga }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Baju Seragam Harian -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->ukuran_baju_harian)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $item->seragam->ukuran_baju_harian }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Celana/Rok Harian -->
                                    <td class="px-6 py-4">
                                        @if($item->seragam && $item->seragam->ukuran_celana_rok_harian)
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-teal-100 text-teal-800">
                                                {{ $item->seragam->ukuran_celana_rok_harian }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <!-- Kopyah (L) -->
                                    <td class="px-6 py-4">
                                        @if($item->jenis_kelamin === 'L')
                                            @if($item->seragam && $item->seragam->ukuran_kopyah)
                                                <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                    {{ $item->seragam->ukuran_kopyah }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">-</span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400 italic">Khusus L</span>
                                        @endif
                                    </td>
                                    <!-- Catatan Ukuran -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-3">
                                            <!-- Catatan (visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->seragam && $item->seragam->catatan_ukuran)
                                                    <div class="text-xs text-gray-700 bg-yellow-50 p-2 rounded border border-yellow-200 max-w-xs">
                                                        <div class="font-semibold text-yellow-700 mb-1">📝 Catatan:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_ukuran }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanModal('ukuran', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_ukuran ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded hover:bg-blue-100 transition-colors"
                                                    title="Edit Catatan">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                            </div>

                                            <!-- Catatan Internal (NOT visible to pendaftar) -->
                                            <div class="flex items-center gap-2">
                                                @if($item->seragam && $item->seragam->catatan_internal_ukuran)
                                                    <div class="text-xs text-gray-700 bg-red-50 p-2 rounded border border-red-200 max-w-xs">
                                                        <div class="font-semibold text-red-700 mb-1">🔒 Internal:</div>
                                                        <p class="whitespace-pre-wrap">{{ $item->seragam->catatan_internal_ukuran }}</p>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Belum ada catatan internal</span>
                                                @endif
                                                <button
                                                    wire:click="openCatatanInternalModal('ukuran', {{ $item->id }}, '{{ addslashes($item->seragam->catatan_internal_ukuran ?? '') }}')"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded hover:bg-red-100 transition-colors"
                                                    title="Edit Catatan Internal (Tidak ditampilkan ke pendaftar)">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Internal
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($activeTab == 'madrasah')
                                            <select
                                                wire:change="changeStatusMadrasah({{ $item->id }}, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="menunggu" {{ ($item->daftarUlang->status ?? '') === 'menunggu' ? 'selected' : '' }}>
                                                    Menunggu
                                                </option>
                                                <option value="disetujui" {{ ($item->daftarUlang->status ?? '') === 'disetujui' ? 'selected' : '' }}>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" {{ ($item->daftarUlang->status ?? '') === 'ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" {{ ($item->daftarUlang->status ?? '') === 'perbaiki' ? 'selected' : '' }}>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        @elseif($activeTab == 'seragam')
                                            <select
                                                wire:change="changeStatusSeragam({{ $item->id }}, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="disetujui" {{ ($item->seragam->status_seragam ?? '') === 'disetujui' ? 'selected' : '' }}>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" {{ ($item->seragam->status_seragam ?? '') === 'ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" {{ ($item->seragam->status_seragam ?? '') === 'perbaiki' ? 'selected' : '' }}>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        @else
                                            <!-- Tab Ukuran: Update status_ukuran -->
                                            <select
                                                wire:change="changeStatusUkuran({{ $item->id }}, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="menunggu" {{ ($item->seragam->status_ukuran ?? '') === 'menunggu' ? 'selected' : '' }}>
                                                    Menunggu
                                                </option>
                                                <option value="disetujui" {{ ($item->seragam->status_ukuran ?? '') === 'disetujui' ? 'selected' : '' }}>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" {{ ($item->seragam->status_ukuran ?? '') === 'ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" {{ ($item->seragam->status_ukuran ?? '') === 'perbaiki' ? 'selected' : '' }}>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        @endif
                                        <a href="/admin/pendaftar/{{ $item->id }}" target="_blank" class="inline-flex items-center px-2 py-1.5 text-blue-600 hover:text-blue-900 text-xs font-medium border border-blue-300 rounded-lg hover:bg-blue-50 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            wire:click="sendManualEmail({{ $item->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                            title="Kirim Email Manual">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Kirim Email
                                        </button>
                                        <button
                                            wire:click="sendManualWhatsApp({{ $item->id }})"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                            title="Kirim WhatsApp Manual">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            Kirim WhatsApp
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $lastMessage = \App\Models\RiwayatPesan::where('pendaftar_id', $item->id)
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                    @endphp
                                    @if($lastMessage)
                                        <div class="text-xs">
                                            <div class="flex items-center text-gray-700 mb-1">
                                                @if($lastMessage->jenis_pesan === 'email')
                                                    <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">Email</span>
                                                @elseif($lastMessage->jenis_pesan === 'whatsapp')
                                                    <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347Z"/>
                                                    </svg>
                                                    <span class="font-medium">WhatsApp</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                    </svg>
                                                    <span class="font-medium">Pesan</span>
                                                @endif
                                            </div>
                                            <div class="mb-1">
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $lastMessage->getStatusBadgeClass() }}">
                                                    {{ $lastMessage->getStatusLabel() }}
                                                </span>
                                            </div>
                                            @if($lastMessage->sent_at)
                                                <div class="text-gray-600">{{ $lastMessage->sent_at->diffForHumans() }}</div>
                                                <div class="text-gray-500 text-[10px] mt-0.5">{{ $lastMessage->sent_at->format('d/m/Y H:i') }}</div>
                                            @else
                                                <div class="text-gray-500 text-[10px] mt-0.5">{{ $lastMessage->created_at->format('d/m/Y H:i') }}</div>
                                            @endif
                                            @if($lastMessage->user)
                                                <div class="text-gray-500 text-[10px]">oleh: {{ $lastMessage->user->name }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum ada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @if($activeTab == 'madrasah' || $activeTab == 'seragam')
                                    <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pendaftar ditemukan.
                                    </td>
                                @else
                                    <td colspan="12" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pendaftar ditemukan.
                                    </td>
                                @endif
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <!-- End Responsive Table Wrapper -->

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $pendaftar->links() }}
                </div>
            </div>
        @endif

        <!-- Verification Modal -->
        <div x-data="{ show: @entangle('currentPendaftarId').notEqual(null) }"
             x-show="show"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10001] p-4"
             style="display: none;">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Verifikasi Dokumen</h3>
                    <button @click="show = false; $wire.set('currentPendaftarId', null);" class="text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                        <select wire:model="verificationStatus" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @if($currentDocumentType)
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="menunggu_verifikasi">Menunggu</option>
                            @else
                                <option value="lunas">Lunas</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="menunggu">Menunggu</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea wire:model="verificationNote"
                                  placeholder="Tambahkan catatan verifikasi..."
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button @click="show = false; $wire.set('currentPendaftarId', null);"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button wire:click="confirmVerification"
                                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                            Simpan Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Message Modal -->
    @include('livewire.admin.partials.send-message-modal')

    <!-- Modal Edit Catatan -->
    @if($showCatatanModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
        <!-- Background overlay with blur -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeCatatanModal"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-yellow-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Edit Catatan {{ $catatanType === 'madrasah' ? 'DU Madrasah' : ($catatanType === 'seragam' ? 'DU Seragam' : 'Ukuran Seragam') }}
                        </h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea
                                wire:model="catatanText"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                placeholder="Tulis catatan untuk pendaftar..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Catatan ini hanya bisa dilihat oleh admin dan panitia</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    type="button"
                    wire:click="saveCatatan"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Catatan
                </button>
                <button
                    type="button"
                    wire:click="closeCatatanModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Edit Catatan Internal (NOT shown to pendaftar) -->
    @if($showCatatanInternalModal)
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
        <!-- Background overlay with blur -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeCatatanInternalModal"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Edit Catatan Internal {{ $catatanInternalType === 'madrasah' ? 'DU Madrasah' : ($catatanInternalType === 'seragam' ? 'DU Seragam' : 'Ukuran Seragam') }}
                        </h3>
                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm text-red-800 font-semibold">Catatan Internal - Tidak Ditampilkan ke Pendaftar</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Internal</label>
                            <textarea
                                wire:model="catatanInternalText"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                                placeholder="Tulis catatan internal untuk admin/panitia saja..."></textarea>
                            <p class="mt-1 text-xs text-red-600 font-medium">⚠️ Catatan ini HANYA untuk internal admin/panitia dan TIDAK akan ditampilkan ke pendaftar</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    type="button"
                    wire:click="saveCatatanInternal"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Catatan Internal
                </button>
                <button
                    type="button"
                    wire:click="closeCatatanInternalModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
