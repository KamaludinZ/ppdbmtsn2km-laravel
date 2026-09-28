<!-- Tab Data Siswa Content -->
@php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeSiswa)
    $isEditMode = $editModeSiswa ?? $editMode ?? false;
@endphp

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Data Siswa</h3>
    @if($isEditMode)
        <div class="space-x-2">
            <button wire:click="saveDataSiswa" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg wire:loading.remove wire:target="saveDataSiswa" class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg wire:loading wire:target="saveDataSiswa" class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="saveDataSiswa">Simpan</span>
                <span wire:loading wire:target="saveDataSiswa">Menyimpan...</span>
            </button>
            <button wire:click="toggleEdit('siswa')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    @else
        @php
            $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
            $isPendaftar = Auth::user()->role === 'pendaftar';
            // Hanya disable untuk role Pendaftar, Admin/Panitia tetap bisa edit
            $shouldDisable = $isApproved && $isPendaftar;
        @endphp
        <button
            wire:click="toggleEdit('siswa')"
            @if($shouldDisable) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition {{ $shouldDisable ? 'opacity-50 cursor-not-allowed' : '' }}">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ $shouldDisable ? 'Data Sudah Disetujui' : 'Edit Data' }}
        </button>
    @endif
</div>

@if($isEditMode)
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataSiswa" class="space-y-6">

        <!-- Validation Error Summary -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-6" role="alert">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-red-800 mb-2">
                            Terdapat {{ $errors->count() }} error yang harus diperbaiki:
                        </h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <p class="text-xs text-red-600 mt-2 italic">
                            Silakan scroll ke bawah untuk memperbaiki field yang ditandai merah.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section 1: Identitas Dasar -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Identitas Dasar
            </h4>

            <!-- Upload Foto -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Pendaftar</label>
                <div class="flex items-start space-x-4">
                    @if($pendaftar->foto)
                        <img src="{{ Storage::url($pendaftar->foto) }}" alt="Current foto" class="w-24 h-24 rounded-lg object-cover border-2 border-emerald-300 shadow-md">
                    @else
                        <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-gray-300">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" wire:model="newFoto" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        @error('newFoto') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Ukuran maksimal: 2MB. Foto pas foto dengan latar belakang biru atau merah.</p>
                        <div wire:loading wire:target="newFoto" class="text-xs text-blue-600 mt-1">
                            <svg class="animate-spin h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengupload foto...
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_lengkap" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Nama Lengkap" required>
                    @error('nama_lengkap') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Isi sesuai akta kelahiran / ijazah</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nisn" maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="10 digit angka" required>
                    @error('nisn') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select wire:model="jenis_kelamin" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="tempat_lahir" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Kota/Kabupaten" required>
                    @error('tempat_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="tanggal_lahir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                    @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Kewarganegaraan & NIK/KITAS -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                Kewarganegaraan
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warga Negara <span class="text-red-500">*</span></label>
                    <select wire:model.live="kewarganegaraan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Kewarganegaraan</option>
                        <option value="WNI">WNI (Warga Negara Indonesia)</option>
                        <option value="WNA">WNA (Warga Negara Asing)</option>
                    </select>
                    @error('kewarganegaraan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                @if($kewarganegaraan === 'WNI')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nik" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="16 digit angka" required>
                        @error('nik') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if($kewarganegaraan === 'WNA')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal Negara <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="negara_asal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Nama Negara" required>
                        @error('negara_asal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Izin Tinggal (KITAS) <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nomor_kitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Nomor KITAS" required>
                        @error('nomor_kitas') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Data Keluarga -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Data Keluarga
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Saudara <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="jumlah_saudara" min="0" max="99" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Maksimal 2 digit" required>
                    @error('jumlah_saudara') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Total saudara kandung dan tiri</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anak Ke <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="anak_ke" min="1" max="99" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Maksimal 2 digit" required>
                    @error('anak_ke') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KK (Kartu Keluarga) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nomor_kk" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="16 digit angka" required>
                    @error('nomor_kk') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Keluarga <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_kepala_keluarga" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Nama Kepala Keluarga" required>
                    @error('nama_kepala_keluarga') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 4: Agama & Cita-cita -->
        <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-orange-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Agama & Cita-cita
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agama <span class="text-red-500">*</span></label>
                    <select wire:model="agama" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200" required>
                        <option value="">Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen Protestan">Kristen Protestan</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Kong Hu Cu">Kong Hu Cu</option>
                    </select>
                    @error('agama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cita-cita <span class="text-red-500">*</span></label>
                    <select wire:model="cita_cita" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200" required>
                        <option value="">Pilih Cita-cita</option>
                        <option value="PNS">PNS</option>
                        <option value="TNI/Polri">TNI/Polri</option>
                        <option value="Guru/Dosen">Guru/Dosen</option>
                        <option value="Dokter">Dokter</option>
                        <option value="Politikus">Politikus</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="Seniman/Artis">Seniman/Artis</option>
                        <option value="Ilmuwan">Ilmuwan</option>
                        <option value="Agamawan">Agamawan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('cita_cita') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 5: Kontak -->
        <div class="bg-teal-50 border-2 border-teal-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-teal-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Informasi Kontak
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" wire:model.live="tidak_punya_hp" id="tidak_punya_hp" class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
                        <label for="tidak_punya_hp" class="text-sm font-medium text-gray-700">Tidak memiliki nomor HP</label>
                    </div>

                    @if(!$tidak_punya_hp)
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                        <input type="tel" wire:model="no_hp" maxlength="15" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" placeholder="08xxxxxxxxxx">
                        @error('no_hp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 15 digit</p>
                    @else
                        <p class="text-sm text-gray-500 italic">Siswa tidak memiliki nomor HP</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email Siswa</label>
                    <input type="email" wire:model="email" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" placeholder="contoh@email.com">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 6: Hobi & Pembiayaan -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Hobi & Pembiayaan Sekolah
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hobi <span class="text-red-500">*</span></label>
                    <select wire:model="hobi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Hobi</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Kesenian">Kesenian</option>
                        <option value="Membaca">Membaca</option>
                        <option value="Menulis">Menulis</option>
                        <option value="Jalan-jalan">Jalan-jalan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('hobi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yang Membiayai Sekolah <span class="text-red-500">*</span></label>
                    <select wire:model="yang_membiayai_sekolah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Pembiayaan</option>
                        <option value="Orang Tua">Orang Tua</option>
                        <option value="Wali/Orang Tua Asuh">Wali/Orang Tua Asuh</option>
                        <option value="Tanggungan Sendiri">Tanggungan Sendiri</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('yang_membiayai_sekolah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Section 7: Pra Sekolah -->
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-yellow-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Pra Sekolah <span class="text-red-500">*</span>
            </h4>

            <div class="space-y-3">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="pra_sekolah_tk" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <span class="text-sm font-medium text-gray-700">Pernah TK/RA</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="pra_sekolah_paud" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <span class="text-sm font-medium text-gray-700">Pernah PAUD</span>
                </label>

                @error('pra_sekolah_tk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                @error('pra_sekolah_paud') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Section 8: Imunisasi -->
        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-green-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Imunisasi <span class="text-red-500">*</span>
            </h4>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_hepatitis_b" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">Hepatitis B</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_bcg" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">BCG</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_dpt" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">DPT</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_polio" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">Polio</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_campak" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">Campak</span>
                </label>

                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="imunisasi_covid" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    <span class="text-sm font-medium text-gray-700">Covid</span>
                </label>
            </div>
        </div>

        <!-- Section 9: Nomor KIP (Optional) -->
        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-indigo-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Kartu Indonesia Pintar (KIP)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KIP</label>
                    <input type="text" wire:model="nomor_kip" maxlength="25" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200" placeholder="Nomor KIP (opsional)">
                    @error('nomor_kip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload KIP akan aktif di menu Upload Dokumen</p>
                </div>
            </div>
        </div>

    </form>
@else
    <!-- VIEW MODE -->
    <div class="space-y-6">

        <!-- Section 1: Identitas Dasar -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Identitas Dasar
            </h4>

            <!-- Display Foto -->
            <div class="flex items-start space-x-6 mb-6">
                @if($pendaftar->foto)
                    <img src="{{ Storage::url($pendaftar->foto) }}" alt="Foto" class="w-32 h-32 rounded-lg object-cover border-2 border-emerald-300 shadow-md">
                @else
                    <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-gray-300">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                @endif

                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Lengkap</label>
                    <p class="text-lg font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">NISN</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nisn }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tempat Lahir</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->tempat_lahir ?: '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d F Y') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Kewarganegaraan -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                Kewarganegaraan
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Warga Negara</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->kewarganegaraan ?: '-' }}</p>
                </div>

                @if($pendaftar->kewarganegaraan === 'WNI')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">NIK</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nik ?: '-' }}</p>
                    </div>
                @endif

                @if($pendaftar->kewarganegaraan === 'WNA')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Asal Negara</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->negara_asal ?: '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Nomor Izin Tinggal (KITAS)</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nomor_kitas ?: '-' }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Data Keluarga -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Data Keluarga
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah Saudara</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->jumlah_saudara ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Anak Ke</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->anak_ke ?: '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nomor KK</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nomor_kk ?: '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Kepala Keluarga</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nama_kepala_keluarga ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 4: Agama & Cita-cita -->
        <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-orange-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Agama & Cita-cita
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Agama</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->agama ?: '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cita-cita</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->cita_cita ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 5: Kontak -->
        <div class="bg-teal-50 border-2 border-teal-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-teal-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Informasi Kontak
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nomor HP</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->no_hp ?: ($pendaftar->tidak_punya_hp ? 'Tidak memiliki nomor HP' : '-') }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Alamat Email</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->email ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 6: Hobi & Pembiayaan -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Hobi & Pembiayaan Sekolah
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Hobi</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->hobi ?: '-' }}</p>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Yang Membiayai Sekolah</label>
                    <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->yang_membiayai_sekolah ?: '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Section 7: Pra Sekolah -->
        <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-yellow-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Pra Sekolah
            </h4>

            <div class="flex flex-wrap gap-3">
                @if($pendaftar->pra_sekolah_tk)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pernah TK/RA
                    </span>
                @endif

                @if($pendaftar->pra_sekolah_paud)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pernah PAUD
                    </span>
                @endif

                @if(!$pendaftar->pra_sekolah_tk && !$pendaftar->pra_sekolah_paud)
                    <p class="text-sm text-gray-500">-</p>
                @endif
            </div>
        </div>

        <!-- Section 8: Imunisasi -->
        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-green-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Imunisasi
            </h4>

            <div class="flex flex-wrap gap-2">
                @if($pendaftar->imunisasi_hepatitis_b)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Hepatitis B</span>
                @endif
                @if($pendaftar->imunisasi_bcg)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">BCG</span>
                @endif
                @if($pendaftar->imunisasi_dpt)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">DPT</span>
                @endif
                @if($pendaftar->imunisasi_polio)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Polio</span>
                @endif
                @if($pendaftar->imunisasi_campak)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Campak</span>
                @endif
                @if($pendaftar->imunisasi_covid)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Covid</span>
                @endif

                @if(!$pendaftar->imunisasi_hepatitis_b && !$pendaftar->imunisasi_bcg && !$pendaftar->imunisasi_dpt && !$pendaftar->imunisasi_polio && !$pendaftar->imunisasi_campak && !$pendaftar->imunisasi_covid)
                    <p class="text-sm text-gray-500">-</p>
                @endif
            </div>
        </div>

        <!-- Section 9: Nomor KIP -->
        <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-6">
            <h4 class="text-base font-semibold text-indigo-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Kartu Indonesia Pintar (KIP)
            </h4>

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Nomor KIP</label>
                <p class="text-sm font-semibold text-gray-900">{{ $pendaftar->nomor_kip ?: '-' }}</p>
            </div>
        </div>

    </div>
@endif
