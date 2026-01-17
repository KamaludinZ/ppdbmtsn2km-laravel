                    <!-- Tab Data Siswa -->
                    <div x-show="activeTab === 'siswa'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Siswa</h3>
                            @if($editMode)
                                <div class="space-x-2">
                                    <button wire:click="saveDataSiswa" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            @else
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            @endif
                        </div>

                        @if($editMode)
                            <!-- EDIT MODE -->
                            <form wire:submit.prevent="saveDataSiswa" class="space-y-6">
                                <!-- Foto -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Pendaftar</label>
                                    <div class="flex items-center space-x-4">
                                        @if($foto)
                                            <img src="{{ Storage::url($foto) }}" alt="Foto" class="w-20 h-20 rounded-full object-cover border-2 border-emerald-500">
                                        @endif
                                        <div>
                                            <input type="file" wire:model="newFoto" accept="image/*" class="text-sm">
                                            @error('newFoto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Pribadi -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Data Pribadi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                                            <input type="text" wire:model="nama_lengkap" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Panggilan</label>
                                            <input type="text" wire:model="nama_panggilan" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NISN *</label>
                                            <input type="text" wire:model="nisn" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            @error('nisn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">NIK</label>
                                            <input type="text" wire:model="nik" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                            <input type="text" wire:model="tempat_lahir" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                            <input type="date" wire:model="tanggal_lahir" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                                            <select wire:model="jenis_kelamin" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                <option value="">Pilih</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Agama *</label>
                                            <select wire:model="agama" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                <option value="">Pilih</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen">Kristen</option>
                                                <option value="Katolik">Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Buddha">Buddha</option>
                                                <option value="Konghucu">Konghucu</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Keluarga -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Data Keluarga
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nomor KK</label>
                                            <input type="text" wire:model="nomor_kk" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                                            <select wire:model="kewarganegaraan" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                <option value="">Pilih</option>
                                                <option value="WNI">WNI</option>
                                                <option value="WNA">WNA</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Negara Asal (jika WNA)</label>
                                            <input type="text" wire:model="negara_asal" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <p class="text-xs text-gray-500 mt-1">Isi jika Kewarganegaraan: WNA</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Anak Ke-</label>
                                            <input type="number" wire:model="anak_ke" min="1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jumlah Saudara Kandung</label>
                                            <input type="number" wire:model="jumlah_saudara_kandung" min="0" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontak -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        Kontak
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" wire:model="email" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nomor HP/Telepon</label>
                                            <input type="text" wire:model="nomor_hp" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tempat Tinggal & Transportasi -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        Tempat Tinggal & Transportasi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jenis Tinggal</label>
                                            <select wire:model="jenis_tinggal" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                <option value="">Pilih</option>
                                                <option value="Bersama Orang Tua">Bersama Orang Tua</option>
                                                <option value="Wali">Wali</option>
                                                <option value="Kos">Kos</option>
                                                <option value="Asrama">Asrama</option>
                                                <option value="Panti Asuhan">Panti Asuhan</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Alat Transportasi</label>
                                            <select wire:model="alat_transportasi" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                                <option value="">Pilih</option>
                                                <option value="Jalan Kaki">Jalan Kaki</option>
                                                <option value="Sepeda">Sepeda</option>
                                                <option value="Sepeda Motor">Sepeda Motor</option>
                                                <option value="Mobil Pribadi">Mobil Pribadi</option>
                                                <option value="Antar Jemput Sekolah">Antar Jemput Sekolah</option>
                                                <option value="Angkutan Umum">Angkutan Umum</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jarak Tempat Tinggal (KM)</label>
                                            <input type="number" step="0.1" wire:model="jarak_tempat_tinggal" min="0" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Waktu Tempuh (Menit)</label>
                                            <input type="number" wire:model="waktu_tempuh" min="0" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Minat & Bakat -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                        Minat & Bakat
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Hobi</label>
                                            <input type="text" wire:model="hobi" placeholder="Contoh: Membaca, Olahraga" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Cita-cita</label>
                                            <input type="text" wire:model="cita_cita" placeholder="Contoh: Dokter, Guru" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        </div>
                                    </div>
                                </div>

                                <!-- Berkebutuhan Khusus -->
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 mb-3">Berkebutuhan Khusus</h4>
                                    <p class="text-sm text-gray-600 mb-3">Pilih jika siswa memiliki kebutuhan khusus (bisa pilih lebih dari satu)</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tidak" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tidak Ada</span>
                                        </label>
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tuna Netra" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tuna Netra (Gangguan Penglihatan)</span>
                                        </label>
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tuna Rungu" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tuna Rungu (Gangguan Pendengaran)</span>
                                        </label>
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tuna Daksa" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tuna Daksa (Gangguan Gerak)</span>
                                        </label>
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tuna Grahita" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tuna Grahita (Gangguan Intelektual)</span>
                                        </label>
                                        <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                            <input type="checkbox" wire:model="berkebutuhan_khusus" value="Tuna Laras" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            <span class="ml-2 text-sm text-gray-700">Tuna Laras (Gangguan Emosi & Perilaku)</span>
                                        </label>
                                    </div>
                                </div>
                            </form>
                        @else
                            <!-- VIEW MODE -->
                            <div class="space-y-6">
                                <!-- Data Pribadi -->
                                <div class="bg-emerald-50 p-6 rounded-lg border border-emerald-200">
                                    <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Data Pribadi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Lengkap</label>
                                            <p class="text-base font-semibold text-gray-900">{{ $pendaftar->nama_lengkap ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Panggilan</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->nama_panggilan ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NISN</label>
                                            <p class="text-base font-semibold text-gray-900">{{ $pendaftar->nisn ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NIK</label>
                                            <p class="text-base font-semibold text-gray-900">{{ $pendaftar->nik ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tempat, Tanggal Lahir</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->tempat_lahir ?? '-' }}, {{ $pendaftar->tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d M Y') : '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jenis Kelamin</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Agama</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->agama ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kewarganegaraan</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->kewarganegaraan ?? '-' }}</p>
                                            @if($pendaftar->negara_asal)
                                                <p class="text-sm text-gray-600">Negara: {{ $pendaftar->negara_asal }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Keluarga -->
                                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                                    <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Data Keluarga
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nomor KK</label>
                                            <p class="text-base font-semibold text-gray-900">{{ $pendaftar->nomor_kk ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Anak Ke-</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->anak_ke ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jumlah Saudara</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->jumlah_saudara_kandung ?? '-' }} orang</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kontak -->
                                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                                    <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        Kontak
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 rounded-lg">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->email ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nomor HP/Telepon</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->nomor_hp ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tempat Tinggal & Transportasi -->
                                <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                                    <h4 class="text-md font-semibold text-indigo-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        Tempat Tinggal & Transportasi
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white p-4 rounded-lg">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jenis Tinggal</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->jenis_tinggal ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Alat Transportasi</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->alat_transportasi ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jarak (KM)</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->jarak_tempat_tinggal ?? '-' }} KM</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Waktu Tempuh</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->waktu_tempuh ?? '-' }} menit</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Minat & Bakat -->
                                <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                                    <h4 class="text-md font-semibold text-yellow-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                        Minat & Bakat
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 rounded-lg">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Hobi</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->hobi ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Cita-cita</label>
                                            <p class="text-base text-gray-900">{{ $pendaftar->cita_cita ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Berkebutuhan Khusus -->
                                <div class="bg-pink-50 p-6 rounded-lg border border-pink-200">
                                    <h4 class="text-md font-semibold text-pink-900 mb-4">Berkebutuhan Khusus</h4>
                                    <div class="bg-white p-4 rounded-lg">
                                        <p class="text-base text-gray-900">
                                            @if($pendaftar->berkebutuhan_khusus)
                                                @if(is_array($pendaftar->berkebutuhan_khusus))
                                                    {{ implode(', ', $pendaftar->berkebutuhan_khusus) }}
                                                @else
                                                    {{ $pendaftar->berkebutuhan_khusus }}
                                                @endif
                                            @else
                                                Tidak Ada
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
