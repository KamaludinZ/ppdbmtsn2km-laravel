<div class="space-y-6">
    <!-- Error untuk Opsi -->
    @error('selectedOpsi')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-sm text-red-800">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $message }}
            </p>
        </div>
    @enderror

    <!-- Petunjuk di atas -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-blue-800 mb-2">Petunjuk Upload Bukti Transfer</h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Pastikan Anda sudah melakukan transfer sesuai nominal yang dipilih</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Isi <strong>tanggal dan waktu transfer</strong> sesuai yang tertera di bukti transfer Anda</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Isi <strong>nama pengirim</strong> (atas nama rekening yang digunakan untuk transfer)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Pilih bank/metode pembayaran yang Anda gunakan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">5.</span>
                        <span>Upload foto/screenshot bukti transfer yang jelas (nama, nomor rekening, dan jumlah transfer terlihat)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">6.</span>
                        <span>Format file: JPG, PNG, atau JPEG (maksimal 2MB) - akan otomatis dikonversi ke WebP</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Upload -->
    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 space-y-5">
        <!-- Langkah 1: Tanggal dan Waktu Transfer -->
        <div class="relative">
            <div class="flex items-center mb-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm mr-3">1</span>
                <label class="block text-base font-semibold text-gray-900">Tanggal dan Waktu Transfer *</label>
            </div>
            <div class="ml-11">
                <p class="text-sm text-gray-600 mb-2">Masukkan tanggal dan waktu saat Anda melakukan transfer (sesuai bukti transfer)</p>
                <input
                    type="datetime-local"
                    wire:model="waktuTransfer"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-base"
                    placeholder="Pilih tanggal dan waktu">
                <p class="text-xs text-gray-500 mt-1">Contoh: 14 Januari 2026, 14:30</p>
                @error('waktuTransfer')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200">

        <!-- Langkah 2: Nama Pengirim -->
        <div class="relative">
            <div class="flex items-center mb-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm mr-3">2</span>
                <label class="block text-base font-semibold text-gray-900">Nama Pengirim (Atas Nama Rekening) *</label>
            </div>
            <div class="ml-11">
                <p class="text-sm text-gray-600 mb-2">Atas nama siapa transfer dilakukan (sesuai pemilik rekening pengirim)</p>
                <input
                    type="text"
                    wire:model="namaPengirim"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-base"
                    placeholder="Contoh: Budi Santoso">
                <p class="text-xs text-gray-500 mt-1">Isi sesuai nama pemilik rekening yang Anda gunakan untuk transfer</p>
                @error('namaPengirim')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200">

        <!-- Langkah 3: Pilih Bank/Metode -->
        <div class="relative">
            <div class="flex items-center mb-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm mr-3">3</span>
                <label class="block text-base font-semibold text-gray-900">Bank/Metode Pembayaran yang Digunakan *</label>
            </div>
            <div class="ml-11">
                <p class="text-sm text-gray-600 mb-2">Pilih dari mana Anda melakukan transfer</p>
                <select
                    wire:model="selectedBank"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 text-base">
                    <option value="">-- Pilih Bank/Metode Pembayaran --</option>
                    @foreach($bankList as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                    @endforeach
                </select>
                @error('selectedBank')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <hr class="border-gray-200">

        <!-- Langkah 4: Upload Bukti Transfer -->
        <div class="relative">
            <div class="flex items-center mb-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-sm mr-3">4</span>
                <label class="block text-base font-semibold text-gray-900">Upload Foto/Screenshot Bukti Transfer *</label>
            </div>
            <div class="ml-11">
                <p class="text-sm text-gray-600 mb-3">Upload bukti transfer yang menampilkan <strong>nama pengirim, nomor rekening tujuan, dan jumlah transfer</strong></p>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition bg-gray-50">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="bukti-transfer" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500 px-2">
                                <span>Klik untuk upload file</span>
                                <input id="bukti-transfer" wire:model="buktiTransfer" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">Format: PNG, JPG, JPEG (maks. 2MB, otomatis dikonversi ke WebP)</p>
                    </div>
                </div>
                @error('buktiTransfer')
                    <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                <!-- Preview -->
                @if($buktiTransfer)
                    <div class="mt-4 bg-white p-3 border border-gray-300 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Preview Bukti Transfer:
                        </p>
                        <img src="{{ $buktiTransfer->temporaryUrl() }}" alt="Preview" class="w-full max-w-md rounded-lg border-2 border-gray-300 shadow-sm">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end pt-2">
        <button
            wire:click="uploadBukti"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-75 cursor-wait"
            type="button"
            class="px-8 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl">

            <!-- Normal State (Tampil saat tidak loading) -->
            <span wire:loading.remove wire:target="uploadBukti" class="inline-flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload Bukti Transfer
            </span>

            <!-- Loading State (Tampil saat loading) -->
            <span wire:loading wire:target="uploadBukti" wire:loading.class="inline-flex" class="hidden items-center justify-center">
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengupload...
            </span>
        </button>
    </div>
</div>
