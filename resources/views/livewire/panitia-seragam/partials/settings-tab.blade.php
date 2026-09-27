{{-- Pengaturan Settings Tab for Panitia Seragam --}}
{{-- This tab mirrors Admin settings with FULL EDIT CAPABILITIES --}}
{{-- Settings are synchronized with Admin and can be edited by Panitia Seragam --}}

<div class="space-y-6">
    <!-- 2. Pengaturan Paket Seragam -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Paket Seragam</h3>
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
                    <!-- Trix Rich Text Editor -->
                    <div wire:ignore x-data="{ trixId: 'panitia-seragam-seragam-info-{{ uniqid() }}' }" x-init="
                        $nextTick(() => {
                            const hiddenInput = document.getElementById(trixId);
                            const editor = document.querySelector(`trix-editor[input='${trixId}']`);

                            if (editor) {
                                // Set initial content
                                editor.editor.loadHTML('{{ addslashes($seragamInfo ?? '') }}');

                                // Listen for changes
                                editor.addEventListener('trix-change', function(e) {
                                    @this.set('seragamInfo', editor.value);
                                });
                            }
                        });
                    ">
                        <input :id="trixId" type="hidden" name="seragamInfo" value="{{ $seragamInfo ?? '' }}">
                        <trix-editor
                            :input="trixId"
                            class="trix-content rounded-lg border border-cyan-300 shadow-sm focus-within:border-cyan-500 focus-within:ring focus-within:ring-cyan-200/50 "
                            placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pembelian seragam..."
                        ></trix-editor>
                    </div>

                    <div class="mt-2 flex items-center text-xs text-cyan-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
                    </div>
                </div>

                <!-- Paket Seragam (Hierarchical) -->
                <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-sm font-semibold text-cyan-900">Paket Seragam</label>
                            <p class="text-xs text-gray-600 mt-1">Buat paket seragam dengan opsi-opsi dan harga yang berbeda</p>
                        </div>
                        <button type="button" wire:click="addSeragamPackage" class="text-xs bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700">+ Tambah Paket</button>
                    </div>

                    @if(count($seragamPackages) == 0)
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-sm">Belum ada paket seragam</p>
                            <p class="text-xs mt-1">Klik "Tambah Paket" untuk membuat paket baru</p>
                        </div>
                    @else
                        @foreach($seragamPackages as $pkgIndex => $package)
                            <div class="bg-white border-2 border-cyan-300 rounded-lg p-4 mb-4">
                                <!-- Package Header -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                    <input type="text"
                                           wire:model="seragamPackages.{{ $pkgIndex }}.name"
                                           placeholder="Nama Paket (contoh: Paket Lengkap)"
                                           class="rounded border-cyan-400 text-sm font-semibold">
                                    <textarea wire:model="seragamPackages.{{ $pkgIndex }}.description"
                                              placeholder="Deskripsi paket"
                                              rows="2"
                                              class="rounded border-cyan-400 text-sm"></textarea>
                                    <button type="button"
                                            wire:click="removeSeragamPackage({{ $pkgIndex }})"
                                            wire:confirm="Yakin ingin menghapus paket ini beserta semua opsinya?"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        🗑️ Hapus Paket
                                    </button>
                                </div>

                                <!-- Mode Pemilihan Opsi (Radio vs Checkbox) -->
                                <div class="mb-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                    <label class="block text-xs font-semibold text-purple-900 mb-2">
                                        Mode Pemilihan Opsi
                                    </label>
                                    <select wire:model="seragamPackages.{{ $pkgIndex }}.selection_mode"
                                            class="w-full rounded border-purple-300 text-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                        <option value="radio">📻 Radio Button - Pilih Satu Opsi</option>
                                        <option value="checkbox">☑️ Checkbox - Pilih Banyak Opsi (Multi Select)</option>
                                    </select>
                                    <p class="text-xs text-purple-700 mt-2">
                                        • <strong>Radio Button:</strong> Pendaftar hanya bisa memilih 1 opsi<br>
                                        • <strong>Checkbox:</strong> Pendaftar bisa memilih beberapa opsi sekaligus (multi-select)
                                    </p>
                                </div>

                                <!-- Package Options -->
                                <div class="bg-green-50 border border-green-300 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-semibold text-green-900">Opsi dari {{ $package['name'] ?? 'Paket ini' }}</label>
                                        <button type="button"
                                                wire:click="addSeragamPackageOption({{ $pkgIndex }})"
                                                class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                            + Tambah Opsi
                                        </button>
                                    </div>

                                    @if(isset($package['options']) && is_array($package['options']))
                                        @foreach($package['options'] as $optIndex => $option)
                                            <div class="bg-white border border-green-300 rounded p-2 mb-2">
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                                    <input type="text"
                                                           wire:model="seragamPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.name"
                                                           placeholder="Nama Opsi (contoh: Semua dengan Jas)"
                                                           class="rounded border-gray-300 text-xs col-span-2">
                                                    <input type="number"
                                                           wire:model="seragamPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.amount"
                                                           placeholder="Harga (Rp)"
                                                           class="rounded border-gray-300 text-xs">
                                                    <button type="button"
                                                            wire:click="removeSeragamPackageOption({{ $pkgIndex }}, {{ $optIndex }})"
                                                            wire:confirm="Yakin ingin menghapus opsi ini?"
                                                            class="text-red-600 hover:text-red-800 text-xs">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    @if(!isset($package['options']) || count($package['options']) == 0)
                                        <p class="text-xs text-gray-500 italic text-center py-2">Belum ada opsi. Klik "+ Tambah Opsi" untuk menambah.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Checkbox: Aktifkan Opsi Lainnya untuk Dropdown Paket -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model="seragamPackagesEnableOpsiLainnya"
                               class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm font-semibold text-blue-900">
                            Aktifkan "Opsi Lainnya" untuk Dropdown Paket Seragam
                        </span>
                    </label>
                    <p class="text-xs text-blue-700 mt-2 ml-6">
                        Jika diaktifkan, pendaftar dapat memilih "Paket Lainnya" di dropdown dan mengisi nama paket secara manual (untuk paket yang tidak ada di daftar).
                    </p>
                </div>

                <!-- Checkbox: Aktifkan Opsi Lainnya untuk Konfirmasi Nominal -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model="seragamEnableOpsiLainnya"
                               class="rounded border-gray-300 text-yellow-600">
                        <span class="ml-2 text-sm font-semibold text-yellow-900">
                            Aktifkan "Opsi Lainnya" untuk Konfirmasi Nominal yang Dibayarkan
                        </span>
                    </label>
                    <p class="text-xs text-yellow-700 mt-2 ml-6">
                        Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" untuk mengisi nominal pembayaran secara manual (misalnya untuk pembayaran tidak penuh/angsuran).
                    </p>
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
                    <p class="text-xs text-indigo-700 mb-3">Tentukan waktu mulai dan berakhir pembayaran daftar ulang seragam (termasuk jam dan menit). Waktu ini juga berlaku untuk pengisian ukuran seragam.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" wire:model="seragamPaymentStart" class="w-full rounded border-gray-300 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Waktu Berakhir</label>
                            <input type="datetime-local" wire:model="seragamPaymentEnd" class="w-full rounded border-gray-300 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-start p-3 bg-blue-100 border-l-4 border-blue-500 rounded">
                        <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-xs text-blue-800">
                            <p class="font-semibold mb-1">⚠️ Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Waktu ini berlaku untuk tab <strong>Seragam</strong> dan tab <strong>Ukuran</strong></li>
                                <li>Jika di luar waktu yang ditentukan, kedua tab tidak akan dapat diakses oleh pendaftar</li>
                            </ul>
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

            <!-- Save Button -->
            <div class="mt-6 flex justify-end">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center"
                >
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
                    <!-- Trix Rich Text Editor -->
                    <div wire:ignore x-data="{ trixId: 'panitia-seragam-ukuran-info-{{ uniqid() }}' }" x-init="
                        $nextTick(() => {
                            const hiddenInput = document.getElementById(trixId);
                            const editor = document.querySelector(`trix-editor[input='${trixId}']`);

                            if (editor) {
                                // Set initial content
                                editor.editor.loadHTML('{{ addslashes($ukuranInfo ?? '') }}');

                                // Listen for changes
                                editor.addEventListener('trix-change', function(e) {
                                    @this.set('ukuranInfo', editor.value);
                                });
                            }
                        });
                    ">
                        <input :id="trixId" type="hidden" name="ukuranInfo" value="{{ $ukuranInfo ?? '' }}">
                        <trix-editor
                            :input="trixId"
                            class="trix-content rounded-lg border border-purple-300 shadow-sm focus-within:border-purple-500 focus-within:ring focus-within:ring-purple-200/50 "
                            placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait pemilihan ukuran seragam..."
                        ></trix-editor>
                    </div>

                    <div class="mt-2 flex items-center text-xs text-purple-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
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
                    <h5 class="text-sm font-semibold text-indigo-900 mb-3">6. Kopyah (Khusus Laki-laki)</h5>

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

            <!-- Save Button -->
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
