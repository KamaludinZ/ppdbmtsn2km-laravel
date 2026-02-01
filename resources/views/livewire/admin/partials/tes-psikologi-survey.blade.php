<div class="space-y-6 p-6">
    <!-- Survey Settings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Pengaturan Survey
        </h3>

        <div class="space-y-4">
            <!-- Aktifkan Survey Toggle -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="text-sm font-medium text-gray-700">Aktifkan Survey</label>
                    <p class="text-xs text-gray-500 mt-1">Jika diaktifkan, pendaftar wajib mengisi survey sebelum konfirmasi pembayaran</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" wire:model="survey_aktif">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <!-- Informasi Survey -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Informasi Survey (ditampilkan ke pendaftar)</label>
                <textarea wire:model="survey_informasi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Masukkan informasi atau instruksi untuk survey..."></textarea>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button wire:click="simpanSurveySetting" type="button" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>

    <!-- Form Pertanyaan Survey -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ $editingSurvey ? 'Edit' : 'Tambah' }} Pertanyaan
        </h3>

        @if(!$editingOptions)
            <!-- Form Pertanyaan -->
            <form wire:submit.prevent="simpanSurvey" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea wire:model="surveyPertanyaan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('surveyPertanyaan') border-red-500 @enderror" placeholder="Masukkan pertanyaan..."></textarea>
                    @error('surveyPertanyaan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi/Keterangan</label>
                    <textarea wire:model="surveyDeskripsi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Input <span class="text-red-500">*</span></label>
                        <select wire:model="surveyTipeInput" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="text">Text (Input Pendek)</option>
                            <option value="textarea">Textarea (Input Panjang)</option>
                            <option value="radio">Radio Button (Pilih Satu)</option>
                            <option value="checkbox">Checkbox (Pilih Banyak)</option>
                            <option value="select">Dropdown (Pilih Satu)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input type="number" wire:model="surveyUrutan" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center justify-between pt-7">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="surveyWajibDiisi" class="rounded text-purple-600 focus:ring-purple-500 mr-2">
                            <span class="text-sm text-gray-700">Wajib Diisi</span>
                        </label>

                        <label class="flex items-center cursor-pointer ml-4">
                            <input type="checkbox" wire:model="surveyAktif" class="rounded text-purple-600 focus:ring-purple-500 mr-2">
                            <span class="text-sm text-gray-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    @if($editingSurvey)
                        <button type="button" wire:click="resetSurveyForm" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            Batal
                        </button>
                    @endif
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        {{ $editingSurvey ? 'Update' : 'Simpan' }} Pertanyaan
                    </button>
                </div>
            </form>
        @else
            <!-- Form Opsi Jawaban (untuk radio, checkbox, select) -->
            <div class="space-y-4">
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm text-purple-800">
                        <strong>Pertanyaan:</strong> {{ $surveyPertanyaan }}<br>
                        <strong>Tipe:</strong> {{ ucfirst($surveyTipeInput) }}
                    </p>
                    <p class="text-xs text-purple-600 mt-2">
                        Tambahkan opsi jawaban untuk pertanyaan ini
                    </p>
                </div>

                <!-- Opsi List -->
                @foreach($surveyOptions as $index => $option)
                    <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg">
                        <div class="flex-1 grid grid-cols-3 gap-3">
                            <input type="text" wire:model="surveyOptions.{{ $index }}.label" placeholder="Label (ditampilkan)" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <input type="text" wire:model="surveyOptions.{{ $index }}.value" placeholder="Value (disimpan)" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <input type="number" wire:model="surveyOptions.{{ $index }}.urutan" placeholder="Urutan" min="0" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <button type="button" wire:click="removeSurveyOption({{ $index }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach

                <!-- Add Option Button -->
                <button type="button" wire:click="addSurveyOption" class="w-full py-2 border-2 border-dashed border-purple-300 text-purple-600 rounded-lg hover:bg-purple-50 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Opsi
                </button>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="resetSurveyForm" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Batal
                    </button>
                    <button type="button" wire:click="simpanOpsiSurvey" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Opsi
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- List Pertanyaan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            Daftar Pertanyaan ({{ $surveyList ? $surveyList->count() : 0 }})
        </h3>

        @if($surveyList && $surveyList->count() > 0)
            <div class="space-y-3">
                @foreach($surveyList as $survey)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $survey->aktif ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $survey->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                        {{ ucfirst($survey->tipe_input) }}
                                    </span>
                                    @if($survey->wajib_diisi)
                                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Wajib</span>
                                    @endif
                                    <span class="text-xs text-gray-500">Urutan: {{ $survey->urutan }}</span>
                                </div>
                                <p class="text-sm font-medium text-gray-900">{{ $survey->pertanyaan }}</p>
                                @if($survey->deskripsi)
                                    <p class="text-xs text-gray-500 mt-1">{{ $survey->deskripsi }}</p>
                                @endif

                                <!-- Show Options if exists -->
                                @if(in_array($survey->tipe_input, ['radio', 'checkbox', 'select']) && $survey->options->count() > 0)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($survey->options as $option)
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                                                {{ $option->label }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <button wire:click="editSurvey({{ $survey->id }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button wire:click="hapusSurvey({{ $survey->id }})" wire:confirm="Yakin ingin menghapus pertanyaan ini?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">Belum ada pertanyaan survey</p>
                <p class="text-sm text-gray-400 mt-1">Tambahkan pertanyaan menggunakan form di atas</p>
            </div>
        @endif
    </div>
</div>
