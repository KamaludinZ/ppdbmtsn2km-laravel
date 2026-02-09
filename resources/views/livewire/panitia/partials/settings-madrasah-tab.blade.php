{{-- Settings Tab for Panitia Daftar Ulang - Madrasah --}}
<div class="space-y-6">

    {{-- Pengaturan Daftar Ulang Madrasah Section --}}
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
                {{-- Informasi untuk Pendaftar --}}
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
                    <!-- Trix Rich Text Editor -->
                    <div wire:ignore x-data="{ trixId: 'panitia-madrasah-info-{{ uniqid() }}' }" x-init="
                        $nextTick(() => {
                            const hiddenInput = document.getElementById(trixId);
                            const editor = document.querySelector(`trix-editor[input='${trixId}']`);

                            if (editor) {
                                // Set initial content
                                editor.editor.loadHTML('{{ addslashes($madrasahInfo ?? '') }}');

                                // Listen for changes
                                editor.addEventListener('trix-change', function(e) {
                                    @this.set('madrasahInfo', editor.value);
                                });
                            }
                        });
                    ">
                        <input :id="trixId" type="hidden" name="madrasahInfo" value="{{ $madrasahInfo ?? '' }}">
                        <trix-editor
                            :input="trixId"
                            class="trix-content rounded-lg border border-indigo-300 shadow-sm focus-within:border-indigo-500 focus-within:ring focus-within:ring-indigo-200 focus-within:ring-opacity-50"
                            placeholder="Tulis informasi, pengumuman, atau petunjuk untuk pendaftar terkait daftar ulang madrasah..."
                        ></trix-editor>
                    </div>

                    <div class="mt-2 flex items-center text-xs text-indigo-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Gunakan toolbar untuk format (Bold, Italic, Link, List, dll)</span>
                    </div>
                </div>

                {{-- Upload Template Surat Pernyataan --}}
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

                {{-- Paket Madrasah (Hierarchical) --}}
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-sm font-semibold text-green-900">Paket Daftar Ulang Madrasah</label>
                            <p class="text-xs text-gray-600 mt-1">Buat paket daftar ulang dengan opsi-opsi dan nominal yang berbeda</p>
                        </div>
                        <button type="button" wire:click="addMadrasahPackage" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Tambah Paket</button>
                    </div>

                    @foreach($madrasahPackages as $pkgIndex => $package)
                        <div class="bg-white border-2 border-green-300 rounded-lg p-4 mb-4">
                            {{-- Package Header --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                                <input type="text"
                                       wire:model="madrasahPackages.{{ $pkgIndex }}.name"
                                       placeholder="Nama Paket (contoh: Paket Lengkap)"
                                       class="rounded border-green-400 text-sm font-semibold">
                                <textarea wire:model="madrasahPackages.{{ $pkgIndex }}.description"
                                          placeholder="Deskripsi paket"
                                          rows="2"
                                          class="rounded border-green-400 text-sm"></textarea>
                                <button type="button"
                                        wire:click="removeMadrasahPackage({{ $pkgIndex }})"
                                        wire:confirm="Yakin ingin menghapus paket ini beserta semua opsinya?"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    🗑️ Hapus Paket
                                </button>
                            </div>

                            {{-- Package Options --}}
                            <div class="bg-cyan-50 border border-cyan-300 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-semibold text-cyan-900">Opsi dari {{ $package['name'] ?? 'Paket ini' }}</label>
                                    <button type="button"
                                            wire:click="addMadrasahPackageOption({{ $pkgIndex }})"
                                            class="text-xs bg-cyan-600 text-white px-2 py-1 rounded hover:bg-cyan-700">
                                        + Tambah Opsi
                                    </button>
                                </div>

                                @if(isset($package['options']) && is_array($package['options']))
                                    @foreach($package['options'] as $optIndex => $option)
                                        <div class="bg-white border border-cyan-300 rounded p-2 mb-2">
                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                                <input type="text"
                                                       wire:model="madrasahPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.name"
                                                       placeholder="Nama Opsi (contoh: SPP Bulanan)"
                                                       class="rounded border-gray-300 text-xs col-span-2">
                                                <input type="number"
                                                       wire:model="madrasahPackages.{{ $pkgIndex }}.options.{{ $optIndex }}.amount"
                                                       placeholder="Nominal (Rp)"
                                                       class="rounded border-gray-300 text-xs">
                                                <button type="button"
                                                        wire:click="removeMadrasahPackageOption({{ $pkgIndex }}, {{ $optIndex }})"
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

                    @if(count($madrasahPackages) == 0)
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-sm">Belum ada paket daftar ulang madrasah</p>
                            <p class="text-xs mt-1">Klik "Tambah Paket" untuk membuat paket baru</p>
                        </div>
                    @endif
                </div>

                <!-- Checkbox: Aktifkan Opsi Lainnya untuk Dropdown Paket -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model="madrasahPackagesEnableOpsiLainnya"
                               class="rounded border-gray-300 text-blue-600">
                        <span class="ml-2 text-sm font-semibold text-blue-900">
                            Aktifkan "Opsi Lainnya" untuk Dropdown Paket Madrasah
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
                               wire:model="madrasahEnableOpsiLainnya"
                               class="rounded border-gray-300 text-yellow-600">
                        <span class="ml-2 text-sm font-semibold text-yellow-900">
                            Aktifkan "Opsi Lainnya" untuk Konfirmasi Nominal yang Dibayarkan
                        </span>
                    </label>
                    <p class="text-xs text-yellow-700 mt-2 ml-6">
                        Jika diaktifkan, pendaftar dapat memilih "Opsi Lainnya" dan mengisi nominal pembayaran secara manual pada form konfirmasi nominal.
                    </p>
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
                    <p class="text-xs text-indigo-700 mb-3">Tentukan waktu mulai dan berakhir pembayaran daftar ulang madrasah (termasuk jam dan menit)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Waktu Mulai</label>
                            <input type="datetime-local" wire:model="madrasahPaymentStart" class="w-full rounded border-gray-300 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">Waktu Berakhir</label>
                            <input type="datetime-local" wire:model="madrasahPaymentEnd" class="w-full rounded border-gray-300 text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: Tanggal dan Waktu (24 jam)</p>
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

                {{-- Save Button --}}
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button
                        type="submit"
                        class="px-8 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 flex items-center text-sm font-semibold shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pengaturan Madrasah
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
