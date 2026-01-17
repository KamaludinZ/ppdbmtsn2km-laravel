<!-- Tab Pendidikan Lain Content -->
@php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeDataPendidikanLain)
    $isEditMode = $editModeDataPendidikanLain ?? $editMode ?? false;
@endphp

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Pendidikan Lain</h3>
    @if($isEditMode)
        <div class="space-x-2">
            <button wire:click="saveDataPendidikanLainTerstruktur" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('dataPendidikanLain')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    @else
        <button wire:click="toggleEdit('dataPendidikanLain')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Data
        </button>
    @endif
</div>

@if($isEditMode)
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataPendidikanLainTerstruktur" class="space-y-6">

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex items-start space-x-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-blue-900 mb-1">Informasi Pengisian</p>
                    <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                        <li>Isi data pendidikan lain seperti kursus, les, atau pendidikan non-formal lainnya</li>
                        <li>Klik tombol "Tambah Pendidikan" untuk menambah data lainnya</li>
                        <li>Semua field harus diisi dengan lengkap dan benar</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pendidikan Lain List -->
        @foreach($pendidikanLainList as $index => $pendidikan)
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm font-semibold text-gray-900">Pendidikan #{{ $index + 1 }}</h4>
                    @if(count($pendidikanLainList) > 1)
                        <button type="button" wire:click="removePendidikanLain({{ $index }})" class="text-red-600 hover:text-red-800 text-xs">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Lembaga -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Lembaga <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="pendidikanLainList.{{ $index }}.nama_lembaga"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Lembaga Kursus Bahasa Inggris ABC">
                        @error("pendidikanLainList.{$index}.nama_lembaga")
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Lembaga -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Jenis Lembaga <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="pendidikanLainList.{{ $index }}.jenis_lembaga"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Kursus, Les Privat, Pesantren, dll">
                        @error("pendidikanLainList.{$index}.jenis_lembaga")
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mulai Belajar -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Mulai Belajar <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               wire:model="pendidikanLainList.{{ $index }}.mulai_belajar"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                        @error("pendidikanLainList.{$index}.mulai_belajar")
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Frekuensi Belajar -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Frekuensi Belajar <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="pendidikanLainList.{{ $index }}.frekuensi_belajar"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Frekuensi --</option>
                            <option value="Setiap Hari">Setiap Hari</option>
                            <option value="Seminggu 2-3">Seminggu 2-3</option>
                            <option value="Seminggu Sekali">Seminggu Sekali</option>
                            <option value="Tidak Rutin">Tidak Rutin</option>
                        </select>
                        @error("pendidikanLainList.{$index}.frekuensi_belajar")
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi Lembaga -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Lokasi Lembaga <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="pendidikanLainList.{{ $index }}.lokasi_lembaga"
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                                  placeholder="Alamat lengkap lembaga"></textarea>
                        @error("pendidikanLainList.{$index}.lokasi_lembaga")
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Tambah Pendidikan Button -->
        <div class="flex justify-center">
            <button type="button" wire:click="addPendidikanLain" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Pendidikan
            </button>
        </div>

    </form>
@else
    <!-- VIEW MODE -->
    <div class="space-y-4">

        <!-- Tabel Pendidikan Lain -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Lembaga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Lembaga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mulai Belajar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Frekuensi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($pendaftar->pendidikanLains && count($pendaftar->pendidikanLains) > 0)
                            @foreach($pendaftar->pendidikanLains as $index => $pendidikan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $pendidikan->nama_lembaga }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $pendidikan->jenis_lembaga }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pendidikan->mulai_belajar ? $pendidikan->mulai_belajar->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($pendidikan->frekuensi_belajar === 'Setiap Hari') bg-green-100 text-green-800
                                            @elseif($pendidikan->frekuensi_belajar === 'Seminggu 2-3') bg-blue-100 text-blue-800
                                            @elseif($pendidikan->frekuensi_belajar === 'Seminggu Sekali') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $pendidikan->frekuensi_belajar }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $pendidikan->lokasi_lembaga }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 italic">Belum ada data pendidikan lain yang dicatat</p>
                                        <p class="text-xs text-gray-400 mt-1">Klik tombol "Edit Data" untuk menambahkan pendidikan lain</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Box - Only show if has data -->
        @if($pendaftar->pendidikanLains && count($pendaftar->pendidikanLains) > 0)
            <div class="bg-indigo-50 border border-indigo-200 rounded-md p-4">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-indigo-900">Ringkasan Pendidikan Lain</p>
                        <p class="text-xs text-indigo-700 mt-1">Total {{ count($pendaftar->pendidikanLains) }} pendidikan lain tercatat yang menunjukkan antusiasme belajar siswa</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endif
