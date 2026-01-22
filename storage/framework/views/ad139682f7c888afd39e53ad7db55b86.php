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
                        class="<?php echo e($activeTab == 'madrasah' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Daftar Ulang Madrasah
                    </button>
                    <button
                        wire:click="switchTab('seragam')"
                        class="<?php echo e($activeTab == 'seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Daftar Ulang Seragam
                    </button>
                    <button
                        wire:click="switchTab('ukuran')"
                        class="<?php echo e($activeTab == 'ukuran' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Ukuran Seragam & Atribut
                    </button>
                    <button
                        wire:click="switchTab('settings')"
                        class="<?php echo e($activeTab == 'settings' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                    >
                        Pengaturan
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab != 'settings'): ?>
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
                            <select wire:model.live="filterStatus" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="belum">Belum Daftar Ulang</option>
                                <option value="proses">Proses Daftar Ulang</option>
                                <option value="selesai">Selesai Daftar Ulang</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'settings'): ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingMadrasahTemplate): ?>
                                        <div class="flex items-center justify-between bg-white p-3 rounded border border-blue-300">
                                            <span class="text-sm text-gray-700">Template saat ini tersedia</span>
                                            <a href="<?php echo e(Storage::url($existingMadrasahTemplate)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Download</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- Nominal Biaya Daftar Ulang -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-green-900">Nominal Biaya</label>
                                    <button type="button" wire:click="addMadrasahNominal" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahNominals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nominal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="bg-white border border-green-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="madrasahNominals.<?php echo e($index); ?>.name" placeholder="Nama biaya (contoh: SPP Bulanan)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="madrasahNominals.<?php echo e($index); ?>.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeMadrasahNominal(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus nominal madrasah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="bg-white border border-yellow-300 rounded-lg p-3 mb-2">
                                        <div class="flex gap-3">
                                            <input type="text" wire:model="madrasahBanks.<?php echo e($index); ?>.name" placeholder="Nama Bank / Metode Pembayaran (Contoh: Transfer BCA, Tunai)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeMadrasahBank(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus bank madrasah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm whitespace-nowrap">Hapus</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="bg-white border border-cyan-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="seragamPackages.<?php echo e($index); ?>.name" placeholder="Nama Paket (contoh: Paket Lengkap)" class="rounded border-gray-300 text-sm">
                                            <textarea wire:model="seragamPackages.<?php echo e($index); ?>.description" placeholder="Deskripsi paket" rows="2" class="rounded border-gray-300 text-sm"></textarea>
                                            <button type="button"
                                                    wire:click="removeSeragamPackage(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus paket seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <!-- Nominal Biaya Seragam -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-semibold text-green-900">Nominal Biaya Seragam</label>
                                    <button type="button" wire:click="addSeragamNominal" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamNominals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nominal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="bg-white border border-green-300 rounded-lg p-3 mb-2">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <input type="text" wire:model="seragamNominals.<?php echo e($index); ?>.name" placeholder="Nama biaya (contoh: Seragam Putih)" class="rounded border-gray-300 text-sm">
                                            <input type="number" wire:model="seragamNominals.<?php echo e($index); ?>.amount" placeholder="Nominal (Rp)" class="rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeSeragamNominal(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus nominal seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="bg-white border border-yellow-300 rounded-lg p-3 mb-2">
                                        <div class="flex gap-3">
                                            <input type="text" wire:model="seragamBanks.<?php echo e($index); ?>.name" placeholder="Nama Bank / Metode Pembayaran (Contoh: Transfer BCA, Tunai)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeSeragamBank(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus bank seragam ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm whitespace-nowrap">Hapus</button>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranJasImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranJasImage)); ?>" class="h-32 rounded border" alt="Panduan Jas">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Jas</label>
                                        <button type="button" wire:click="addUkuranJas" class="text-xs bg-amber-600 text-white px-2 py-1 rounded hover:bg-amber-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranJasOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranJasOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranJasOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: LD 50cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranJas(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran jas ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <!-- 2. Ukuran Baju Seragam Olahraga -->
                            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-sky-900 mb-3">2. Ukuran Baju Seragam Olahraga</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Baju Olahraga</label>
                                    <input type="file" wire:model="ukuranBajuOlahragaImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranBajuOlahragaImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranBajuOlahragaImage)); ?>" class="h-32 rounded border" alt="Panduan Baju Olahraga">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Baju Olahraga</label>
                                        <button type="button" wire:click="addUkuranBajuOlahraga" class="text-xs bg-sky-600 text-white px-2 py-1 rounded hover:bg-sky-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranBajuOlahragaOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranBajuOlahragaOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranBajuOlahragaOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: LD 48cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranBajuOlahraga(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran baju olahraga ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <!-- 3. Ukuran Celana Seragam Olahraga -->
                            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-violet-900 mb-3">3. Ukuran Celana Seragam Olahraga</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Celana Olahraga</label>
                                    <input type="file" wire:model="ukuranCelanaOlahragaImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranCelanaOlahragaImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranCelanaOlahragaImage)); ?>" class="h-32 rounded border" alt="Panduan Celana Olahraga">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Celana Olahraga</label>
                                        <button type="button" wire:click="addUkuranCelanaOlahraga" class="text-xs bg-violet-600 text-white px-2 py-1 rounded hover:bg-violet-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranCelanaOlahragaOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranCelanaOlahragaOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: S, M, L, 28, 30)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranCelanaOlahragaOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: LP 70cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranCelanaOlahraga(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran celana olahraga ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <!-- 4. Ukuran Baju Seragam Harian -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-blue-900 mb-3">4. Ukuran Baju Seragam Harian</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Baju Harian</label>
                                    <input type="file" wire:model="ukuranBajuHarianImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranBajuHarianImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranBajuHarianImage)); ?>" class="h-32 rounded border" alt="Panduan Baju Harian">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Baju Harian</label>
                                        <button type="button" wire:click="addUkuranBajuHarian" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranBajuHarianOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranBajuHarianOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: S, M, L)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranBajuHarianOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: LD 48cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranBajuHarian(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran baju harian ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <!-- 5. Ukuran Celana/Rok Seragam Harian -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-green-900 mb-3">5. Ukuran Celana/Rok Seragam Harian</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Celana/Rok Harian</label>
                                    <input type="file" wire:model="ukuranCelanaRokHarianImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranCelanaRokHarianImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranCelanaRokHarianImage)); ?>" class="h-32 rounded border" alt="Panduan Celana/Rok Harian">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Celana/Rok Harian</label>
                                        <button type="button" wire:click="addUkuranCelanaRokHarian" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranCelanaRokHarianOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranCelanaRokHarianOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: S, M, L, 28, 30)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranCelanaRokHarianOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: LP 70cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranCelanaRokHarian(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran celana/rok harian ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>

                            <!-- 6. Kopyah (Khusus Laki-laki) -->
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <h5 class="text-sm font-semibold text-indigo-900 mb-3">4. Kopyah (Khusus Laki-laki)</h5>

                                <!-- Upload Gambar Panduan -->
                                <div class="mb-4">
                                    <label class="block text-xs text-gray-700 mb-2">Upload Gambar Panduan Ukuran Kopyah</label>
                                    <input type="file" wire:model="ukuranKopyahImage" accept="image/*" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($existingUkuranKopyahImage): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo e(Storage::url($existingUkuranKopyahImage)); ?>" class="h-32 rounded border" alt="Panduan Kopyah">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- CRUD Opsi Ukuran -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs text-gray-700">Opsi Ukuran Kopyah</label>
                                        <button type="button" wire:click="addUkuranKopyah" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">+ Tambah</button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranKopyahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" wire:model="ukuranKopyahOptions.<?php echo e($index); ?>.size" placeholder="Ukuran (contoh: 6, 7, 8)" class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="text" wire:model="ukuranKopyahOptions.<?php echo e($index); ?>.measurement" placeholder="Ukuran detail (contoh: Lingkar 54cm)" class="flex-1 rounded border-gray-300 text-sm">
                                            <button type="button"
                                                    wire:click="removeUkuranKopyah(<?php echo e($index); ?>)"
                                                    wire:confirm="Yakin ingin menghapus ukuran kopyah ini?"
                                                    class="text-red-600 hover:text-red-800 text-sm px-2">Hapus</button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
        <?php else: ?>
            <!-- Stats Overview -->
            <?php echo $__env->make('livewire.admin.partials.daftar-ulang-stats', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                            <p><?php echo e($paymentInfoText ?: 'Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah.'); ?></p>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah'): ?>
                            Daftar Ulang Madrasah
                        <?php elseif($activeTab == 'seragam'): ?>
                            Daftar Ulang Seragam
                        <?php else: ?>
                            Ukuran Seragam & Atribut
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </h3>
                </div>

                <!-- Responsive Table Wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nomor_pendaftaran','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nomor_pendaftaran','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                Nomor Pendaftaran
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nama_lengkap','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nama_lengkap','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                Pendaftar
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'jenis_kelamin','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'jenis_kelamin','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                Jenis Kelamin
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persetujuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Persetujuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah'): ?>
                                    Status DU Madrasah
                                <?php elseif($activeTab == 'seragam'): ?>
                                    Status DU Seragam
                                <?php else: ?>
                                    Status Ukuran
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah'): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surat Pernyataan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya & Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                            <?php elseif($activeTab == 'seragam'): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket Seragam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya & Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank/Metode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Pembayaran</th>
                            <?php else: ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Jas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Atasan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Bawahan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Kopyah</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($item->nomor_pendaftaran); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($item->nama_lengkap); ?></div>
                                    <div class="text-sm text-gray-500">NISN: <?php echo e($item->nisn); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_kelamin): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'); ?>">
                                            <?php echo e($item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-500">-</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
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
                                    ?>
                                    <div class="flex flex-col gap-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($persetujuanColor); ?>">
                                            <?php echo e($persetujuanLabel); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->tanggal_pilihan_daftar_ulang): ?>
                                            <span class="text-xs text-gray-500"><?php echo e($item->tanggal_pilihan_daftar_ulang->format('d/m/Y H:i')); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                                <button wire:click="changeStatusPersetujuan(<?php echo e($item->id); ?>, 'belum_memilih')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-gray-400 group-hover:animate-pulse"></span>
                                                    Belum Memilih
                                                </button>
                                                <button wire:click="changeStatusPersetujuan(<?php echo e($item->id); ?>, 'setuju')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-green-500 group-hover:ring-2 group-hover:ring-green-300"></span>
                                                    Setuju Daftar Ulang
                                                </button>
                                                <button wire:click="changeStatusPersetujuan(<?php echo e($item->id); ?>, 'tidak_setuju')"
                                                        @click="open = false"
                                                        class="group flex w-full items-center px-4 py-2 text-xs text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors">
                                                    <span class="mr-3 flex h-2 w-2 rounded-full bg-red-500 group-hover:ring-2 group-hover:ring-red-300"></span>
                                                    Tidak Melanjutkan
                                                </button>
                                            </div>
                                            <div class="py-1">
                                                <button wire:click="resetPersetujuan(<?php echo e($item->id); ?>)"
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $statusColors = [
                                            'menunggu' => 'bg-yellow-100 text-yellow-800',
                                            'disetujui' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                            'perbaiki' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $statusLabels = [
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
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusColor); ?>">
                                        <?php echo e($statusLabel); ?>

                                    </span>
                                </td>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah'): ?>
                                    <!-- Surat Pernyataan -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->dokumen->where('jenis_dokumen', 'pernyataan')->first()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="<?php echo e(asset('storage/' . $item->dokumen->where('jenis_dokumen', 'pernyataan')->first()->path_file)); ?>"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Biaya & Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->daftarUlang && $item->daftarUlang->nominal_biaya): ?>
                                            <?php $nominalBiaya = $item->daftarUlang->nominal_biaya; ?>
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1"><?php echo e($nominalBiaya['name'] ?? '-'); ?></div>
                                                <div class="font-bold text-emerald-700">Rp <?php echo e(number_format($nominalBiaya['amount'] ?? 0, 0, ',', '.')); ?></div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum pilih nominal</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Opsi Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->daftarUlang && $item->daftarUlang->opsi_pembayaran): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?php echo e(ucfirst($item->daftarUlang->opsi_pembayaran)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Bank/Metode -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->daftarUlang && $item->daftarUlang->metode_pembayaran): ?>
                                            <div class="text-xs text-gray-900">
                                                <span class="font-medium"><?php echo e($item->daftarUlang->bank_name ?? 'Bank'); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->daftarUlang->account_number): ?>
                                                    <div class="text-gray-600"><?php echo e($item->daftarUlang->account_number); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Bukti Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->pembayaran->where('jenis_pembayaran', 'daftar_ulang')->first()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="<?php echo e(asset('storage/' . $item->pembayaran->where('jenis_pembayaran', 'daftar_ulang')->first()->bukti_pembayaran)); ?>"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php elseif($activeTab == 'seragam'): ?>
                                    <!-- Paket Seragam -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->paket_seragam): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                <?php echo e($item->seragam->paket_seragam); ?>

                                            </span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->paket_description): ?>
                                                <div class="text-xs text-gray-600 mt-1"><?php echo e($item->seragam->paket_description); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum dipilih</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Biaya & Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->nominal_seragam): ?>
                                            <?php $nominalSeragam = $item->seragam->nominal_seragam; ?>
                                            <div class="text-xs text-gray-900">
                                                <div class="font-medium text-gray-700 mb-1"><?php echo e($nominalSeragam['name'] ?? '-'); ?></div>
                                                <div class="font-bold text-emerald-700">Rp <?php echo e(number_format($nominalSeragam['amount'] ?? 0, 0, ',', '.')); ?></div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum pilih nominal</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Opsi Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->opsi_pembayaran): ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <?php echo e(ucfirst($item->seragam->opsi_pembayaran)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Bank/Metode -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->metode_pembayaran): ?>
                                            <div class="text-xs text-gray-900">
                                                <span class="font-medium"><?php echo e($item->seragam->bank_name ?? 'Bank'); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->account_number): ?>
                                                    <div class="text-gray-600"><?php echo e($item->seragam->account_number); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Bukti Pembayaran -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->pembayaran->where('jenis_pembayaran', 'seragam')->first()): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Sudah Upload
                                            </span>
                                            <a href="<?php echo e(asset('storage/' . $item->pembayaran->where('jenis_pembayaran', 'seragam')->first()->bukti_pembayaran)); ?>"
                                               target="_blank"
                                               class="block text-blue-600 hover:text-blue-900 text-xs mt-1">Lihat File</a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Belum Upload
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php else: ?>
                                    <!-- Ukuran Jas -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->ukuran_jas): ?>
                                            <div class="text-sm font-medium text-amber-900"><?php echo e($item->seragam->ukuran_jas); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->ukuran_jas_detail): ?>
                                                <div class="text-xs text-gray-600"><?php echo e($item->seragam->ukuran_jas_detail); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum diisi</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Ukuran Atasan -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->ukuran_baju): ?>
                                            <div class="text-sm font-medium text-blue-900"><?php echo e($item->seragam->ukuran_baju); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->ukuran_baju_detail): ?>
                                                <div class="text-xs text-gray-600"><?php echo e($item->seragam->ukuran_baju_detail); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum diisi</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Ukuran Bawahan -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->ukuran_celana): ?>
                                            <div class="text-sm font-medium text-green-900"><?php echo e($item->seragam->ukuran_celana); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->ukuran_celana_detail): ?>
                                                <div class="text-xs text-gray-600"><?php echo e($item->seragam->ukuran_celana_detail); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Belum diisi</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <!-- Ukuran Kopyah -->
                                    <td class="px-6 py-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_kelamin === 'L'): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam && $item->seragam->ukuran_kopyah): ?>
                                                <div class="text-sm font-medium text-indigo-900"><?php echo e($item->seragam->ukuran_kopyah); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->seragam->ukuran_kopyah_detail): ?>
                                                    <div class="text-xs text-gray-600"><?php echo e($item->seragam->ukuran_kopyah_detail); ?></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-xs text-gray-500">Belum diisi</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Khusus laki-laki</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah'): ?>
                                            <select
                                                wire:change="changeStatusMadrasah(<?php echo e($item->id); ?>, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="disetujui" <?php echo e(($item->daftarUlang->status ?? '') === 'disetujui' ? 'selected' : ''); ?>>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" <?php echo e(($item->daftarUlang->status ?? '') === 'ditolak' ? 'selected' : ''); ?>>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" <?php echo e(($item->daftarUlang->status ?? '') === 'perbaiki' ? 'selected' : ''); ?>>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        <?php elseif($activeTab == 'seragam'): ?>
                                            <select
                                                wire:change="changeStatusSeragam(<?php echo e($item->id); ?>, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="disetujui" <?php echo e(($item->seragam->status_seragam ?? '') === 'disetujui' ? 'selected' : ''); ?>>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" <?php echo e(($item->seragam->status_seragam ?? '') === 'ditolak' ? 'selected' : ''); ?>>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" <?php echo e(($item->seragam->status_seragam ?? '') === 'perbaiki' ? 'selected' : ''); ?>>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        <?php else: ?>
                                            <select
                                                wire:change="changeStatusUkuran(<?php echo e($item->id); ?>, $event.target.value)"
                                                class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                            >
                                                <option value="">-- Pilih Status --</option>
                                                <option value="disetujui" <?php echo e(($item->seragam->status_ukuran ?? '') === 'disetujui' ? 'selected' : ''); ?>>
                                                    Disetujui
                                                </option>
                                                <option value="ditolak" <?php echo e(($item->seragam->status_ukuran ?? '') === 'ditolak' ? 'selected' : ''); ?>>
                                                    Ditolak
                                                </option>
                                                <option value="perbaiki" <?php echo e(($item->seragam->status_ukuran ?? '') === 'perbaiki' ? 'selected' : ''); ?>>
                                                    Perbaiki
                                                </option>
                                            </select>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <a href="/admin/pendaftar/<?php echo e($item->id); ?>" target="_blank" class="inline-flex items-center px-2 py-1.5 text-blue-600 hover:text-blue-900 text-xs font-medium border border-blue-300 rounded-lg hover:bg-blue-50 transition">
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
                                            wire:click="sendManualEmail(<?php echo e($item->id); ?>)"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                            title="Kirim Email Manual">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Kirim Email
                                        </button>
                                        <button
                                            wire:click="sendManualWhatsApp(<?php echo e($item->id); ?>)"
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
                                    <?php
                                        $context = 'daftar_ulang_' . $activeTab;
                                        $lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $item->id)
                                            ->where('context', $context)
                                            ->orderBy('sent_at', 'desc')
                                            ->first();
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage): ?>
                                        <div class="text-xs">
                                            <div class="flex items-center text-gray-700 mb-1">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage->message_type === 'email'): ?>
                                                    <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">Email</span>
                                                <?php elseif($lastMessage->message_type === 'whatsapp'): ?>
                                                    <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347Z"/>
                                                    </svg>
                                                    <span class="font-medium">WhatsApp</span>
                                                <?php else: ?>
                                                    <svg class="w-3.5 h-3.5 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                    </svg>
                                                    <span class="font-medium">Pesan</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="text-gray-600">
                                                <?php echo e($lastMessage->sent_at->diffForHumans()); ?>

                                            </div>
                                            <div class="text-gray-500 text-[10px] mt-0.5">
                                                <?php echo e($lastMessage->sent_at->format('d/m/Y H:i')); ?>

                                            </div>
                                            <div class="text-gray-500 text-[10px]">
                                                oleh: <?php echo e($lastMessage->sender->name ?? 'System'); ?>

                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Belum ada</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab == 'madrasah' || $activeTab == 'seragam'): ?>
                                    <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pendaftar ditemukan.
                                    </td>
                                <?php else: ?>
                                    <td colspan="12" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data pendaftar ditemukan.
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
                </div>
                <!-- End Responsive Table Wrapper -->

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    <?php echo e($pendaftar->links()); ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Verification Modal -->
        <div x-data="{ show: <?php if ((object) ('currentPendaftarId') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('currentPendaftarId'->value()); ?>')<?php echo e('currentPendaftarId'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('currentPendaftarId'); ?>')<?php endif; ?>.notEqual(null) }"
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentDocumentType): ?>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="menunggu_verifikasi">Menunggu</option>
                            <?php else: ?>
                                <option value="lunas">Lunas</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="menunggu">Menunggu</option>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
    <?php echo $__env->make('livewire.admin.partials.send-message-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/livewire/admin/daftar-ulang.blade.php ENDPATH**/ ?>