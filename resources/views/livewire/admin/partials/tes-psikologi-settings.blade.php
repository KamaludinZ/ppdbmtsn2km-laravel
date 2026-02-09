<!-- Settings Tab Content -->
<div class="space-y-6">
    <!-- Pengaturan Umum -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Umum</h3>
        </div>
        <div class="p-6 space-y-6">
            <!-- Toggle Aktif/Nonaktif -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h4 class="font-semibold text-gray-900">Status Tes Psikologi</h4>
                    <p class="text-sm text-gray-600">Aktifkan atau nonaktifkan tahap tes psikologi dalam proses pendaftaran</p>
                </div>
                <button wire:click="$toggle('aktif')" type="button" class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors {{ $aktif ? 'bg-emerald-600' : 'bg-gray-300' }}">
                    <span class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform {{ $aktif ? 'translate-x-7' : 'translate-x-1' }}"></span>
                </button>
            </div>

            <!-- Informasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Tes Psikologi</label>
                <!-- Trix Rich Text Editor -->
                <div wire:ignore x-data="{ trixId: 'tes-psikologi-informasi-{{ uniqid() }}' }" x-init="
                    $nextTick(() => {
                        const hiddenInput = document.getElementById(trixId);
                        const editor = document.querySelector(`trix-editor[input='${trixId}']`);

                        if (editor) {
                            // Set initial content
                            editor.editor.loadHTML('{{ addslashes($informasi ?? '') }}');

                            // Listen for changes
                            editor.addEventListener('trix-change', function(e) {
                                @this.set('informasi', editor.value);
                            });
                        }
                    });
                ">
                    <input :id="trixId" type="hidden" name="informasi" value="{{ $informasi ?? '' }}">
                    <trix-editor
                        :input="trixId"
                        class="trix-content rounded-lg border border-gray-300 shadow-sm focus-within:border-emerald-500 focus-within:ring focus-within:ring-emerald-200 focus-within:ring-opacity-50"
                        placeholder="Masukkan informasi tentang tes psikologi..."
                    ></trix-editor>
                </div>
                @error('informasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Informasi untuk Admin & Panitia -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                        Box Informasi Khusus Admin & Panitia
                    </span>
                </label>
                <p class="text-xs text-gray-500 mb-2">Informasi ini hanya akan ditampilkan di halaman Tes Psikologi untuk Admin dan Panitia (tidak terlihat oleh pendaftar)</p>
                <!-- Trix Rich Text Editor -->
                <div wire:ignore x-data="{ trixId: 'tes-psikologi-informasi-admin-{{ uniqid() }}' }" x-init="
                    $nextTick(() => {
                        const hiddenInput = document.getElementById(trixId);
                        const editor = document.querySelector(`trix-editor[input='${trixId}']`);

                        if (editor) {
                            // Set initial content
                            editor.editor.loadHTML('{{ addslashes($informasi_admin_panitia ?? '') }}');

                            // Listen for changes
                            editor.addEventListener('trix-change', function(e) {
                                @this.set('informasi_admin_panitia', editor.value);
                            });
                        }
                    });
                ">
                    <input :id="trixId" type="hidden" name="informasi_admin_panitia" value="{{ $informasi_admin_panitia ?? '' }}">
                    <trix-editor
                        :input="trixId"
                        class="trix-content rounded-lg border border-gray-300 shadow-sm focus-within:border-blue-500 focus-within:ring focus-within:ring-blue-200 focus-within:ring-opacity-50"
                        placeholder="Contoh: Reminder untuk verifikasi pembayaran, catatan internal, dll..."
                    ></trix-editor>
                </div>
                @error('informasi_admin_panitia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button wire:click="simpanSetting" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition">
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi Rekening Tujuan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-green-600 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Rekening Tujuan Transfer</h3>
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm text-blue-800">
                    <strong>Catatan:</strong> Informasi rekening ini akan ditampilkan kepada pendaftar sebagai tujuan transfer pembayaran tes psikologi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                    <input wire:model="rekening_bank_nama" type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" placeholder="Contoh: Bank BCA" />
                    @error('rekening_bank_nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                    <input wire:model="rekening_nomor" type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" placeholder="1234567890" />
                    @error('rekening_nomor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Atas Nama</label>
                    <input wire:model="rekening_atas_nama" type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" placeholder="MTsN 2 Kota Malang" />
                    @error('rekening_atas_nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan (Opsional)</label>
                <textarea wire:model="rekening_keterangan" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200" placeholder="Contoh: Transfer hanya dapat dilakukan pada hari kerja..."></textarea>
                @error('rekening_keterangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button wire:click="simpanSetting" class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    Simpan Informasi Rekening
                </button>
            </div>
        </div>
    </div>

    <!-- Opsi Nominal Biaya -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Opsi Nominal Biaya Tes Psikologi</h3>
        </div>
        <div class="p-6 space-y-6">
            <!-- Form Opsi -->
            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                <h4 class="font-semibold text-gray-900">{{ $editingOpsi ? 'Edit Nominal Biaya' : 'Tambah Nominal Biaya Baru' }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Biaya (Rp) *</label>
                        <input wire:model="opsiNama" type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Contoh: 50000 atau 50.000" />
                        @error('opsiNama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Masukkan angka nominal biaya (bisa dengan atau tanpa titik pemisah)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input wire:model="opsiUrutan" type="number" min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" />
                        @error('opsiUrutan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi / Keterangan</label>
                    <textarea wire:model="opsiDeskripsi" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Contoh: Biaya untuk tes psikologi standar..."></textarea>
                    @error('opsiDeskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center">
                    <input wire:model="opsiAktif" type="checkbox" id="opsiAktif" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" />
                    <label for="opsiAktif" class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
                <div class="flex gap-2">
                    <button wire:click="simpanOpsi" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        {{ $editingOpsi ? 'Update' : 'Tambah' }} Nominal
                    </button>
                    @if($editingOpsi)
                        <button wire:click="resetOpsiForm" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                            Batal
                        </button>
                    @endif
                </div>
            </div>

            <!-- Tabel Opsi -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal Biaya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($opsiList as $opsi)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format(floatval(str_replace('.', '', $opsi->nama)), 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($opsi->deskripsi, 50) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $opsi->urutan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $opsi->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $opsi->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editOpsi({{ $opsi->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button wire:click="hapusOpsi({{ $opsi->id }})" onclick="return confirm('Yakin ingin menghapus nominal ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada opsi nominal biaya</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bank Pengirim / Metode Pembayaran -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Bank Pengirim / Metode Pembayaran</h3>
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm text-blue-800">
                    <strong>Catatan:</strong> Ini adalah daftar bank atau metode pembayaran yang dapat dipilih oleh pendaftar sebagai metode pembayaran yang mereka gunakan untuk transfer.
                </p>
            </div>

            <!-- Form Bank -->
            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                <h4 class="font-semibold text-gray-900">{{ $editingBank ? 'Edit Bank Pengirim' : 'Tambah Bank Pengirim Baru' }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank / Metode Pembayaran *</label>
                        <input wire:model="bankNamaBank" type="text" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Contoh: BCA, Mandiri, DANA, GoPay, dll" />
                        @error('bankNamaBank') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Nama bank atau e-wallet yang digunakan pendaftar untuk transfer</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input wire:model="bankUrutan" type="number" min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" />
                        @error('bankUrutan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex items-center">
                    <input wire:model="bankAktif" type="checkbox" id="bankAktif" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" />
                    <label for="bankAktif" class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
                <div class="flex gap-2">
                    <button wire:click="simpanBank" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                        {{ $editingBank ? 'Update' : 'Tambah' }} Bank
                    </button>
                    @if($editingBank)
                        <button wire:click="resetBankForm" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-400 transition">
                            Batal
                        </button>
                    @endif
                </div>
            </div>

            <!-- Tabel Bank -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Bank / Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bankList as $bank)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bank->nama_bank }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $bank->urutan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bank->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $bank->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editBank({{ $bank->id }})" class="text-purple-600 hover:text-purple-900 mr-3">Edit</button>
                                    <button wire:click="hapusBank({{ $bank->id }})" onclick="return confirm('Yakin ingin menghapus bank pengirim ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada bank pengirim / metode pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
