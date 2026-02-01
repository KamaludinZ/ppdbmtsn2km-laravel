<!-- Tab Data Alamat Content -->
@php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeAlamat)
    $isEditMode = $editModeAlamat ?? $editMode ?? false;
@endphp

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Data Alamat</h3>
    @if($isEditMode)
        <div class="space-x-2">
            <button wire:click="saveDataAlamat" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('alamat')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    @else
        @php
            $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
            $isPendaftar = Auth::user()->role === 'pendaftar';
            // Hanya disable untuk role Pendaftar, Admin/Panitia tetap bisa edit
            $shouldDisable = $isApproved && $isPendaftar;
        @endphp
        <button
            wire:click="toggleEdit('alamat')"
            @if($shouldDisable) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition {{ $shouldDisable ? 'opacity-50 cursor-not-allowed' : '' }}">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ $shouldDisable ? 'Data Sudah Disetujui' : 'Edit Data' }}
        </button>
    @endif
</div>

@if($isEditMode)
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataAlamat" class="space-y-6" x-data="{
        ayah_luar_negeri: @entangle('ayah_tinggal_luar_negeri'),
        ibu_luar_negeri: @entangle('ibu_tinggal_luar_negeri'),
        ibu_sama_ayah: @entangle('ibu_sama_dengan_ayah'),
        wali_status: @entangle('wali_status_wali'),
        wali_luar_negeri: @entangle('wali_tinggal_luar_negeri'),
        wali_sama_ayah: @entangle('wali_sama_dengan_ayah'),
        status_tempat_tinggal: @entangle('status_tempat_tinggal')
    }">

        <!-- ===================== AYAH KANDUNG ===================== -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ayah Kandung
            </h4>

            <!-- Checkbox Tinggal Diluar Negeri -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="ayah_tinggal_luar_negeri" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                </label>
            </div>

            <!-- Jika Tinggal Diluar Negeri -->
            <div x-show="ayah_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select wire:model="ayah_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('ayah_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="alamat_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Alamat lengkap di luar negeri" required></textarea>
                    @error('alamat_ayah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Jika Tinggal Didalam Negeri -->
            <div x-show="!ayah_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select wire:model="ayah_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('ayah_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <select wire:model.live="ayah_provinsi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach($provincesList as $province)
                            <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                        @endforeach
                    </select>
                    @error('ayah_provinsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select wire:model.live="ayah_kabupaten" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required @if(!$ayah_provinsi) disabled @endif>
                        <option value="">Pilih Kabupaten/Kota</option>
                        @foreach($ayahCitiesList as $city)
                            <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                        @endforeach
                    </select>
                    @error('ayah_kabupaten') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                        <select wire:model.live="ayah_kecamatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required @if(!$ayah_kabupaten) disabled @endif>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($ayahDistrictsList as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                        @error('ayah_kecamatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <select wire:model.live="ayah_kelurahan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required @if(!$ayah_kecamatan) disabled @endif>
                            <option value="">Pilih Kelurahan/Desa</option>
                            @foreach($ayahVillagesList as $village)
                                <option value="{{ $village['id'] }}">{{ $village['name'] }}</option>
                            @endforeach
                        </select>
                        @error('ayah_kelurahan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RT <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ayah_rt" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="001" required>
                        @error('ayah_rt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RW <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ayah_rw" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="001" required>
                        @error('ayah_rw') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ayah_kode_pos" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Otomatis terisi atau input manual">
                        @error('ayah_kode_pos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="alamat_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Jalan, No. Rumah, Gang/Kampung" required></textarea>
                    @error('alamat_ayah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- ===================== IBU KANDUNG ===================== -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ibu Kandung
            </h4>

            <!-- Checkbox Tinggal Diluar Negeri -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="ibu_tinggal_luar_negeri" class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                </label>
            </div>

            <!-- Checkbox Sama dengan Ayah Kandung -->
            <div class="mb-4" x-show="!ibu_luar_negeri">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           wire:model.live="ibu_sama_dengan_ayah"
                           @change="setTimeout(() => $wire.copyAyahToIbu(), 100)"
                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Sama dengan Ayah Kandung</span>
                </label>
            </div>

            <!-- Jika Tinggal Diluar Negeri -->
            <div x-show="ibu_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select wire:model="ibu_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('ibu_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="ibu_alamat" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Alamat lengkap di luar negeri" required></textarea>
                    @error('ibu_alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Jika Tinggal Didalam Negeri -->
            <div x-show="!ibu_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                    <select wire:model="ibu_status_kepemilikan_rumah" :disabled="ibu_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('ibu_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <select wire:model.live="ibu_provinsi" :disabled="ibu_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach($provincesList as $province)
                            <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                        @endforeach
                    </select>
                    @error('ibu_provinsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                    <select wire:model.live="ibu_kabupaten" :disabled="ibu_sama_ayah" @if(!$ibu_provinsi) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Kabupaten/Kota</option>
                        @foreach($ibuCitiesList as $city)
                            <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                        @endforeach
                    </select>
                    @error('ibu_kabupaten') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                        <select wire:model.live="ibu_kecamatan" :disabled="ibu_sama_ayah" @if(!$ibu_kabupaten) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($ibuDistrictsList as $district)
                                <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                        @error('ibu_kecamatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa <span class="text-red-500">*</span></label>
                        <select wire:model.live="ibu_kelurahan" :disabled="ibu_sama_ayah" @if(!$ibu_kecamatan) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Kelurahan/Desa</option>
                            @foreach($ibuVillagesList as $village)
                                <option value="{{ $village['id'] }}">{{ $village['name'] }}</option>
                            @endforeach
                        </select>
                        @error('ibu_kelurahan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RT <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ibu_rt" :disabled="ibu_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="001" required>
                        @error('ibu_rt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RW <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ibu_rw" :disabled="ibu_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="001" required>
                        @error('ibu_rw') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ibu_kode_pos" :disabled="ibu_sama_ayah" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Otomatis terisi atau input manual">
                        @error('ibu_kode_pos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model="ibu_alamat" :disabled="ibu_sama_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Jalan, No. Rumah, Gang/Kampung" required></textarea>
                    @error('ibu_alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- ===================== DATA WALI ===================== -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Wali
            </h4>

            <!-- Status Wali -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Wali <span class="text-red-500">*</span></label>
                <select wire:model.live="wali_status_wali" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                    <option value="">Pilih Status Wali</option>
                    <option value="sama_dengan_ayah">Sama dengan Ayah Kandung</option>
                    <option value="sama_dengan_ibu">Sama dengan Ibu Kandung</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                @error('wali_status_wali') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                <p class="text-xs text-gray-500 mt-1">Jika memilih "Sama dengan Ayah/Ibu", field alamat akan terisi otomatis</p>
            </div>

            <!-- Jika Status Wali = Lainnya -->
            <div x-show="wali_status === 'lainnya'" x-transition>
                <!-- Checkbox Tinggal Diluar Negeri -->
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model.live="wali_tinggal_luar_negeri" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200">
                        <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                    </label>
                </div>

                <!-- Checkbox Sama dengan Ayah Kandung -->
                <div class="mb-4" x-show="!wali_luar_negeri">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                               wire:model.live="wali_sama_dengan_ayah"
                               @change="setTimeout(() => $wire.copyAyahToWali(), 100)"
                               class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200">
                        <span class="ml-2 text-sm font-medium text-gray-700">Sama dengan Ayah Kandung</span>
                    </label>
                </div>

                <!-- Jika Tinggal Diluar Negeri -->
                <div x-show="wali_luar_negeri" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status Kepemilikan Rumah
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <select wire:model="wali_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('wali_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat_wali" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Alamat lengkap di luar negeri"></textarea>
                        @error('alamat_wali') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Jika Tinggal Didalam Negeri -->
                <div x-show="!wali_luar_negeri" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status Kepemilikan Rumah
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <select wire:model="wali_status_kepemilikan_rumah" :disabled="wali_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('wali_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi
                            <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="wali_provinsi" :disabled="wali_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provincesList as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                        @error('wali_provinsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kabupaten/Kota
                            <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="wali_kabupaten" :disabled="wali_sama_ayah" @if(!$wali_provinsi) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach($waliCitiesList as $city)
                                <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                            @endforeach
                        </select>
                        @error('wali_kabupaten') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kecamatan
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="wali_kecamatan" :disabled="wali_sama_ayah" @if(!$wali_kabupaten) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <option value="">Pilih Kecamatan</option>
                                @foreach($waliDistrictsList as $district)
                                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            @error('wali_kecamatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kelurahan/Desa
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="wali_kelurahan" :disabled="wali_sama_ayah" @if(!$wali_kecamatan) disabled @endif class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <option value="">Pilih Kelurahan/Desa</option>
                                @foreach($waliVillagesList as $village)
                                    <option value="{{ $village['id'] }}">{{ $village['name'] }}</option>
                                @endforeach
                            </select>
                            @error('wali_kelurahan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                RT
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_rt" :disabled="wali_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="001">
                            @error('wali_rt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                RW
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_rw" :disabled="wali_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="001">
                            @error('wali_rw') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Pos
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_kode_pos" :disabled="wali_sama_ayah" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Otomatis terisi atau input manual">
                            @error('wali_kode_pos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat_wali" :disabled="wali_sama_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                        @error('alamat_wali') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== DATA SISWA ===================== -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Alamat Tempat Tinggal Siswa
            </h4>

            <div class="space-y-4">
                <!-- Status Tempat Tinggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Tempat Tinggal <span class="text-red-500">*</span></label>
                    <select wire:model.live="status_tempat_tinggal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                        <option value="">Pilih Status Tempat Tinggal</option>
                        <option value="Tinggal dengan Ayah Kandung">Tinggal dengan Ayah Kandung</option>
                        <option value="Tinggal dengan Ibu Kandung">Tinggal dengan Ibu Kandung</option>
                        <option value="Tinggal dengan Wali">Tinggal dengan Wali</option>
                        <option value="Ikut Saudara/Kerabat">Ikut Saudara/Kerabat</option>
                        <option value="Asrama Madrasah">Asrama Madrasah</option>
                        <option value="Kontrak/Kost">Kontrak/Kost</option>
                        <option value="Tinggal di Asrama Pesantren">Tinggal di Asrama Pesantren</option>
                        <option value="Panti Asuhan">Panti Asuhan</option>
                        <option value="Rumah Singgah">Rumah Singgah</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('status_tempat_tinggal') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Jika tinggal dengan Ayah/Ibu/Wali, alamat akan mengikuti data yang sudah diisi di atas</p>
                </div>

                <!-- Hanya tampilkan form alamat detail jika tidak tinggal dengan orang tua/wali -->
                <div x-show="status_tempat_tinggal && !['Tinggal dengan Ayah Kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali'].includes(status_tempat_tinggal)" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                        <select wire:model="siswa_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('siswa_status_kepemilikan_rumah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <select wire:model.live="siswa_provinsi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Provinsi</option>
                            @foreach($provincesList as $province)
                                <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                        @error('siswa_provinsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select wire:model.live="siswa_kabupaten" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required @if(!$siswa_provinsi) disabled @endif>
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach($siswaCitiesList as $city)
                                <option value="{{ $city['id'] }}">{{ $city['name'] }}</option>
                            @endforeach
                        </select>
                        @error('siswa_kabupaten') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select wire:model.live="siswa_kecamatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required @if(!$siswa_kabupaten) disabled @endif>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($siswaDistrictsList as $district)
                                    <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                @endforeach
                            </select>
                            @error('siswa_kecamatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select wire:model.live="siswa_kelurahan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required @if(!$siswa_kecamatan) disabled @endif>
                                <option value="">Pilih Kelurahan/Desa</option>
                                @foreach($siswaVillagesList as $village)
                                    <option value="{{ $village['id'] }}">{{ $village['name'] }}</option>
                                @endforeach
                            </select>
                            @error('siswa_kelurahan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_rt" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="001">
                            @error('siswa_rt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_rw" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="001">
                            @error('siswa_rw') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_kode_pos" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Otomatis terisi atau input manual">
                            @error('siswa_kode_pos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea wire:model="siswa_alamat" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                        @error('siswa_alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Jarak, Transportasi, Waktu Tempuh (selalu tampil) -->
                <div class="border-t pt-4 mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jarak Tempuh (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="jarak_ke_sekolah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Jarak Tempuh</option>
                            <option value="Kurang dari 5 km">Kurang dari 5 km</option>
                            <option value="5-10 km">5-10 km</option>
                            <option value="11-20 km">11-20 km</option>
                            <option value="21-30 km">21-30 km</option>
                            <option value="Lebih dari 30 km">Lebih dari 30 km</option>
                        </select>
                        @error('jarak_ke_sekolah') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transportasi (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="transportasi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Transportasi</option>
                            <option value="Jalan Kaki">Jalan Kaki</option>
                            <option value="Sepeda">Sepeda</option>
                            <option value="Sepeda Motor">Sepeda Motor</option>
                            <option value="Mobil Pribadi">Mobil Pribadi</option>
                            <option value="Antar Jemput Sekolah">Antar Jemput Sekolah</option>
                            <option value="Angkutan Umum">Angkutan Umum</option>
                            <option value="Perahu/Sampan">Perahu/Sampan</option>
                            <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                            <option value="Kereta Api">Kereta Api</option>
                            <option value="Ojek">Ojek</option>
                            <option value="Andong/Bendi/Sado/Dokar/Delman/Becak">Andong/Bendi/Sado/Dokar/Delman/Becak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('transportasi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Tempuh (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="waktu_tempuh" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Waktu Tempuh</option>
                            <option value="1-10 menit">1-10 menit</option>
                            <option value="10-19 menit">10-19 menit</option>
                            <option value="20-29 menit">20-29 menit</option>
                            <option value="30-39 menit">30-39 menit</option>
                            <option value="1-2 jam">1-2 jam</option>
                            <option value="Lebih dari 2 jam">Lebih dari 2 jam</option>
                        </select>
                        @error('waktu_tempuh') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

    </form>
@else
    <!-- VIEW MODE -->
    <div class="space-y-6">
        <!-- ===================== AYAH KANDUNG ===================== -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ayah Kandung
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->ayah_tinggal_luar_negeri ? 'Ya' : 'Tidak' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->ayah_status_kepemilikan_rumah ?? '-' }}</p>
                </div>
                @if(!$pendaftar->ayah_tinggal_luar_negeri)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaProvinsi($pendaftar->ayah_provinsi) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKabupaten($pendaftar->ayah_kabupaten) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKecamatan($pendaftar->ayah_kecamatan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKelurahan($pendaftar->ayah_kelurahan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ayah_rt ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ayah_rw ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ayah_kode_pos ?? '-' }}</p>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->alamat_ayah ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- ===================== IBU KANDUNG ===================== -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ibu Kandung
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->ibu_tinggal_luar_negeri ? 'Ya' : 'Tidak' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Sama dengan Ayah Kandung</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->ibu_sama_dengan_ayah ? 'Ya' : 'Tidak' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->ibu_status_kepemilikan_rumah ?? '-' }}</p>
                </div>
                @if(!$pendaftar->ibu_tinggal_luar_negeri)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaProvinsi($pendaftar->ibu_provinsi) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKabupaten($pendaftar->ibu_kabupaten) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKecamatan($pendaftar->ibu_kecamatan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKelurahan($pendaftar->ibu_kelurahan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ibu_rt ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ibu_rw ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->ibu_kode_pos ?? '-' }}</p>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->alamat_ibu ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- ===================== DATA WALI ===================== -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
                <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Alamat Wali
                </h4>
                @php
                    // Tentukan apakah alamat wali sama dengan ayah atau ibu
                    $waliSamaDenganAyah = ($pendaftar->wali_status_wali === 'sama_dengan_ayah' || $pendaftar->wali_sama_dengan_ayah);
                    $waliSamaDenganIbu = ($pendaftar->wali_status_wali === 'sama_dengan_ibu');

                    // Pilih data yang akan ditampilkan
                    if ($waliSamaDenganAyah) {
                        $displayTinggalLuarNegeri = $pendaftar->ayah_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->ayah_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->ayah_provinsi;
                        $displayKabupaten = $pendaftar->ayah_kabupaten;
                        $displayKecamatan = $pendaftar->ayah_kecamatan;
                        $displayKelurahan = $pendaftar->ayah_kelurahan;
                        $displayRt = $pendaftar->ayah_rt;
                        $displayRw = $pendaftar->ayah_rw;
                        $displayKodePos = $pendaftar->ayah_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_ayah;
                    } elseif ($waliSamaDenganIbu) {
                        $displayTinggalLuarNegeri = $pendaftar->ibu_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->ibu_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->ibu_provinsi;
                        $displayKabupaten = $pendaftar->ibu_kabupaten;
                        $displayKecamatan = $pendaftar->ibu_kecamatan;
                        $displayKelurahan = $pendaftar->ibu_kelurahan;
                        $displayRt = $pendaftar->ibu_rt;
                        $displayRw = $pendaftar->ibu_rw;
                        $displayKodePos = $pendaftar->ibu_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_ibu;
                    } else {
                        $displayTinggalLuarNegeri = $pendaftar->wali_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->wali_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->wali_provinsi;
                        $displayKabupaten = $pendaftar->wali_kabupaten;
                        $displayKecamatan = $pendaftar->wali_kecamatan;
                        $displayKelurahan = $pendaftar->wali_kelurahan;
                        $displayRt = $pendaftar->wali_rt;
                        $displayRw = $pendaftar->wali_rw;
                        $displayKodePos = $pendaftar->wali_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_wali;
                    }
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Wali</label>
                        <p class="text-sm text-gray-900">
                            @if($pendaftar->wali_status_wali === 'sama_dengan_ayah')
                                Sama dengan Ayah Kandung
                            @elseif($pendaftar->wali_status_wali === 'sama_dengan_ibu')
                                Sama dengan Ibu Kandung
                            @elseif($pendaftar->wali_status_wali === 'lainnya')
                                Lainnya
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                        <p class="text-sm text-gray-900">{{ $displayTinggalLuarNegeri ? 'Ya' : 'Tidak' }}</p>
                    </div>
                    @if($pendaftar->wali_status_wali === 'lainnya')
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Sama dengan Ayah Kandung</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->wali_sama_dengan_ayah ? 'Ya' : 'Tidak' }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                        <p class="text-sm text-gray-900">{{ $displayStatusKepemilikan ?? '-' }}</p>
                    </div>
                    @if(!$displayTinggalLuarNegeri)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaProvinsi($displayProvinsi) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKabupaten($displayKabupaten) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKecamatan($displayKecamatan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKelurahan($displayKelurahan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                            <p class="text-sm text-gray-900">{{ $displayRt ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                            <p class="text-sm text-gray-900">{{ $displayRw ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                            <p class="text-sm text-gray-900">{{ $displayKodePos ?? '-' }}</p>
                        </div>
                    @endif
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <p class="text-sm text-gray-900">{{ $displayAlamatLengkap ?? '-' }}</p>
                    </div>
                </div>
            </div>

        <!-- ===================== DATA SISWA ===================== -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Alamat Tempat Tinggal Siswa
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Tempat Tinggal</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->status_tempat_tinggal ?? '-' }}</p>
                </div>
                @if(!in_array($pendaftar->status_tempat_tinggal, ['Tinggal dengan Ayah Kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali']))
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->siswa_status_kepemilikan_rumah ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaProvinsi($pendaftar->siswa_provinsi) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKabupaten($pendaftar->siswa_kabupaten) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKecamatan($pendaftar->siswa_kecamatan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900">{{ $this->getNamaKelurahan($pendaftar->siswa_kelurahan) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->siswa_rt ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->siswa_rw ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->siswa_kode_pos ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <p class="text-sm text-gray-900">{{ $pendaftar->siswa_alamat ?? '-' }}</p>
                    </div>
                @else
                    @php
                        // Display alamat detail based on status tempat tinggal
                        $displaySiswaKepemilikan = null;
                        $displaySiswaProvinsi = null;
                        $displaySiswaKabupaten = null;
                        $displaySiswaKecamatan = null;
                        $displaySiswaKelurahan = null;
                        $displaySiswaRt = null;
                        $displaySiswaRw = null;
                        $displaySiswaKodePos = null;
                        $displaySiswaAlamat = null;

                        if ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Ayah Kandung') {
                            $displaySiswaKepemilikan = $pendaftar->ayah_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->ayah_provinsi;
                            $displaySiswaKabupaten = $pendaftar->ayah_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->ayah_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->ayah_kelurahan;
                            $displaySiswaRt = $pendaftar->ayah_rt;
                            $displaySiswaRw = $pendaftar->ayah_rw;
                            $displaySiswaKodePos = $pendaftar->ayah_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_ayah;
                        } elseif ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Ibu Kandung') {
                            $displaySiswaKepemilikan = $pendaftar->ibu_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->ibu_provinsi;
                            $displaySiswaKabupaten = $pendaftar->ibu_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->ibu_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->ibu_kelurahan;
                            $displaySiswaRt = $pendaftar->ibu_rt;
                            $displaySiswaRw = $pendaftar->ibu_rw;
                            $displaySiswaKodePos = $pendaftar->ibu_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_ibu;
                        } elseif ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Wali') {
                            $displaySiswaKepemilikan = $pendaftar->wali_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->wali_provinsi;
                            $displaySiswaKabupaten = $pendaftar->wali_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->wali_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->wali_kelurahan;
                            $displaySiswaRt = $pendaftar->wali_rt;
                            $displaySiswaRw = $pendaftar->wali_rw;
                            $displaySiswaKodePos = $pendaftar->wali_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_wali;
                        }
                    @endphp

                    @if($displaySiswaKepemilikan)
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                            <p class="text-sm text-gray-900">{{ $displaySiswaKepemilikan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaProvinsi($displaySiswaProvinsi) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKabupaten($displaySiswaKabupaten) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKecamatan($displaySiswaKecamatan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                            <p class="text-sm text-gray-900">{{ $this->getNamaKelurahan($displaySiswaKelurahan) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                            <p class="text-sm text-gray-900">{{ $displaySiswaRt ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                            <p class="text-sm text-gray-900">{{ $displaySiswaRw ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                            <p class="text-sm text-gray-900">{{ $displaySiswaKodePos ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <p class="text-sm text-gray-900">{{ $displaySiswaAlamat ?? '-' }}</p>
                        </div>
                    @endif
                @endif
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jarak Tempuh ke Sekolah</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->jarak_ke_sekolah ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Transportasi ke Sekolah</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->transportasi ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Waktu Tempuh ke Sekolah</label>
                    <p class="text-sm text-gray-900">{{ $pendaftar->waktu_tempuh ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
