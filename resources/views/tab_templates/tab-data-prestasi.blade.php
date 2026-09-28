<!-- Tab Data Prestasi Siswa Content -->
@php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModePrestasi)
    $isEditMode = $editModeDataPrestasi ?? $editMode ?? false;
@endphp

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Data Prestasi Siswa</h3>
    @if($isEditMode)
        <div class="space-x-2">
            <button wire:click="saveDataPrestasiTerstruktur" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('data_prestasi')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    @else
        <button wire:click="toggleEdit('data_prestasi')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Data
        </button>
    @endif
</div>

@if($isEditMode)
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataPrestasiTerstruktur" class="space-y-6">

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex items-start space-x-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-900">Informasi</p>
                    <p class="text-xs text-blue-700 mt-1">Silakan masukkan prestasi yang pernah diraih. Anda dapat menambahkan lebih dari satu prestasi dengan menekan tombol "Tambah Prestasi".</p>
                </div>
            </div>
        </div>

        <!-- List Prestasi -->
        <div class="space-y-4">
            @foreach($prestasiList as $index => $prestasi)
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-base font-semibold text-yellow-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Prestasi #{{ $index + 1 }}
                        </h4>
                        @if(count($prestasiList) > 1)
                            <button type="button" wire:click="removePrestasi({{ $index }})" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-xs transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tahun -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="prestasiList.{{ $index }}.tahun" min="1900" max="{{ date('Y') + 1 }}" maxlength="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200" placeholder="Contoh: 2023">
                            @error('prestasiList.' . $index . '.tahun') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Lomba -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lomba <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="prestasiList.{{ $index }}.nama_lomba" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200" placeholder="Contoh: Olimpiade Sains Nasional">
                            @error('prestasiList.' . $index . '.nama_lomba') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bidang Lomba -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Lomba <span class="text-red-500">*</span></label>
                            <select wire:model="prestasiList.{{ $index }}.bidang_lomba" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                                <option value="">Pilih Bidang Lomba</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Keagamaan">Keagamaan</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Pramuka/Paskibraka">Pramuka/Paskibraka</option>
                                <option value="Karya Ilmiah">Karya Ilmiah</option>
                                <option value="Kesenian">Kesenian</option>
                                <option value="Pidato Bahasa Asing">Pidato Bahasa Asing</option>
                                <option value="Sains">Sains</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('prestasiList.' . $index . '.bidang_lomba') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Penyelenggara -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penyelenggara <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="prestasiList.{{ $index }}.nama_penyelenggara" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200" placeholder="Contoh: Kementerian Pendidikan">
                            @error('prestasiList.' . $index . '.nama_penyelenggara') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tingkat Lomba -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Lomba <span class="text-red-500">*</span></label>
                            <select wire:model="prestasiList.{{ $index }}.tingkat_lomba" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                                <option value="">Pilih Tingkat Lomba</option>
                                <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                <option value="Provinsi">Provinsi</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Internasional">Internasional</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('prestasiList.' . $index . '.tingkat_lomba') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Peringkat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peringkat <span class="text-red-500">*</span></label>
                            <select wire:model="prestasiList.{{ $index }}.peringkat" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                                <option value="">Pilih Peringkat</option>
                                <option value="Tidak Meraih Juara">Tidak Meraih Juara</option>
                                <option value="Juara 1/Medali Emas">Juara 1/Medali Emas</option>
                                <option value="Juara 2/Medali Perak">Juara 2/Medali Perak</option>
                                <option value="Juara 3/Medali Perunggu">Juara 3/Medali Perunggu</option>
                                <option value="Juara Harapan 1">Juara Harapan 1</option>
                                <option value="Juara Harapan 2">Juara Harapan 2</option>
                                <option value="Juara Harapan 3">Juara Harapan 3</option>
                                <option value="Juara Favorit">Juara Favorit</option>
                            </select>
                            @error('prestasiList.' . $index . '.peringkat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori Lomba -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Lomba <span class="text-red-500">*</span></label>
                            <select wire:model="prestasiList.{{ $index }}.kategori_lomba" class="w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                                <option value="">Pilih Kategori</option>
                                <option value="Individu">Individu</option>
                                <option value="Kelompok">Kelompok</option>
                            </select>
                            @error('prestasiList.' . $index . '.kategori_lomba') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Tambah Prestasi -->
        <div class="flex justify-center">
            <button type="button" wire:click="addPrestasi" class="px-6 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Prestasi
            </button>
        </div>

    </form>
@else
    <!-- VIEW MODE -->
    <div class="space-y-4">

        @if($pendaftar->prestasis && count($pendaftar->prestasis) > 0)
            <!-- Tabel Prestasi -->
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-yellow-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tahun</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Lomba</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bidang Lomba</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Penyelenggara</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tingkat Lomba</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Peringkat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori Lomba</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendaftar->prestasis as $index => $prestasi)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prestasi->tahun }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $prestasi->nama_lomba }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $prestasi->bidang_lomba }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $prestasi->nama_penyelenggara }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($prestasi->tingkat_lomba === 'Internasional') bg-purple-100 text-purple-800
                                            @elseif($prestasi->tingkat_lomba === 'Nasional') bg-blue-100 text-blue-800
                                            @elseif($prestasi->tingkat_lomba === 'Provinsi') bg-green-100 text-green-800
                                            @elseif($prestasi->tingkat_lomba === 'Kabupaten/Kota') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $prestasi->tingkat_lomba }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if(str_contains($prestasi->peringkat, 'Juara 1') || str_contains($prestasi->peringkat, 'Emas')) bg-yellow-100 text-yellow-800
                                            @elseif(str_contains($prestasi->peringkat, 'Juara 2') || str_contains($prestasi->peringkat, 'Perak')) bg-gray-100 text-gray-800
                                            @elseif(str_contains($prestasi->peringkat, 'Juara 3') || str_contains($prestasi->peringkat, 'Perunggu')) bg-orange-100 text-orange-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $prestasi->peringkat }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $prestasi->kategori_lomba }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-md p-4">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold text-emerald-900">Total Prestasi Tercatat</p>
                        <p class="text-xs text-emerald-700 mt-1">Siswa ini memiliki {{ count($pendaftar->prestasis) }} prestasi yang dapat menjadi nilai tambah dalam penilaian.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-md p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
                <p class="text-sm text-gray-500 italic">Belum ada data prestasi yang dicatat</p>
                <p class="text-xs text-gray-400 mt-1">Klik tombol "Edit Data" untuk menambahkan prestasi</p>
            </div>
        @endif

    </div>
@endif
