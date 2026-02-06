<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Informasi Tes Psikologi -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Tes Psikologi</h3>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    {!! nl2br(e($setting->informasi)) !!}
                </div>
            </div>
        </div>

        <!-- Survey Pertanyaan (Muncul jika aktif) -->
        @if($setting->survey_aktif && $surveyQuestions && $surveyQuestions->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Survey</h3>
                    </div>
                    <div>
                        @if($surveyCompleted)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sudah Diisi
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Diisi
                            </span>
                        @endif
                    </div>
                </div>

                @if($surveyCompleted)
                    <!-- Survey Sudah Diisi - Hanya tampilkan pesan sukses -->
                    <div class="p-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-base font-bold text-green-900 mb-1">Survey Telah Dilengkapi</h4>
                            <p class="text-sm text-green-700">Terima kasih telah mengisi survey. Anda dapat melanjutkan ke upload bukti transfer di bawah.</p>
                        </div>
                    </div>
                @else
                    <!-- Survey Belum Diisi - Tampilkan form lengkap -->
                    <div class="p-6">
                        @if($setting->survey_informasi)
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-indigo-900">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {!! nl2br(e($setting->survey_informasi)) !!}
                                </p>
                            </div>
                        @endif

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-900 font-bold mb-1">Survey Wajib Diisi!</p>
                                    <p class="text-sm text-yellow-800">Anda harus mengisi survey terlebih dahulu sebelum dapat melakukan upload bukti pembayaran tes psikologi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-indigo-700">Progress Survey</span>
                                    <span class="text-sm font-medium text-indigo-700">{{ $currentQuestionIndex + 1 }} / {{ $surveyQuestions->count() }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-indigo-600 h-3 rounded-full transition-all duration-300"
                                         style="width: {{ (($currentQuestionIndex + 1) / $surveyQuestions->count()) * 100 }}%"></div>
                                </div>
                            </div>

                            @php
                                $question = $surveyQuestions[$currentQuestionIndex];
                            @endphp

                            <!-- Question Card -->
                            <div class="border-2 border-indigo-200 rounded-lg p-6 bg-indigo-50">
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-bold text-lg mr-4 flex-shrink-0">
                                            {{ $currentQuestionIndex + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <label class="block text-base font-bold text-gray-900 mb-1">
                                                {{ $question->pertanyaan }}
                                                @if($question->wajib_diisi)
                                                    <span class="text-red-500 text-xl">*</span>
                                                @endif
                                            </label>
                                            @if($question->deskripsi)
                                                <p class="text-sm text-gray-600 mt-1">{{ $question->deskripsi }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    @if($question->tipe_input === 'text')
                                        <input type="text"
                                               wire:model.live="surveyAnswers.{{ $question->id }}"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                               placeholder="Ketik jawaban Anda di sini...">

                                    @elseif($question->tipe_input === 'textarea')
                                        <textarea wire:model.live="surveyAnswers.{{ $question->id }}"
                                                  rows="5"
                                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                                  placeholder="Ketik jawaban Anda di sini..."></textarea>

                                    @elseif($question->tipe_input === 'radio')
                                        <div class="space-y-3">
                                            @foreach($question->options->sortBy('urutan') as $option)
                                                <label class="flex items-center cursor-pointer p-3 border-2 border-gray-300 rounded-lg hover:bg-white hover:border-indigo-500 transition">
                                                    {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                                    {{-- FIX: Tambah name attribute untuk uniqueness per question --}}
                                                    <input type="radio"
                                                           name="question_{{ $question->id }}"
                                                           wire:model.live="surveyAnswers.{{ $question->id }}"
                                                           value="{{ $option->id }}"
                                                           class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 mr-3 flex-shrink-0">
                                                    <span class="text-base text-gray-700">{{ $option->label }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($question->tipe_input === 'checkbox')
                                        <div class="space-y-3">
                                            @foreach($question->options->sortBy('urutan') as $option)
                                                <label class="flex items-center cursor-pointer p-3 border-2 border-gray-300 rounded-lg hover:bg-white hover:border-indigo-500 transition">
                                                    {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                                    <input type="checkbox"
                                                           wire:model.live="surveyAnswers.{{ $question->id }}"
                                                           value="{{ $option->id }}"
                                                           class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 mr-3 flex-shrink-0">
                                                    <span class="text-base text-gray-700">{{ $option->label }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($question->tipe_input === 'select')
                                        <select wire:model.live="surveyAnswers.{{ $question->id }}"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base">
                                            <option value="">-- Pilih Jawaban --</option>
                                            @foreach($question->options->sortBy('urutan') as $option)
                                                {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                                <option value="{{ $option->id }}">{{ $option->label }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between items-center pt-4">
                                <button type="button"
                                        wire:click="previousQuestion"
                                        @if($currentQuestionIndex === 0) disabled @endif
                                        class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Sebelumnya
                                </button>

                                @if($currentQuestionIndex < $surveyQuestions->count() - 1)
                                    {{-- Tombol Selanjutnya - Disabled jika pertanyaan wajib belum dijawab --}}
                                    <button type="button"
                                            wire:click="nextQuestion"
                                            @if(!$this->isCurrentQuestionAnswered) disabled @endif
                                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                                        Selanjutnya
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                @else
                                    {{-- Tombol Selesai - Disabled jika ada pertanyaan wajib yang belum dijawab --}}
                                    <button type="button"
                                            wire:click="simpanSurvey"
                                            @if(!$this->areAllRequiredQuestionsAnswered) disabled @endif
                                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai & Simpan
                                    </button>
                                @endif
                            </div>

                            {{-- Peringatan jika pertanyaan wajib belum dijawab --}}
                            @if($question->wajib_diisi && !$this->isCurrentQuestionAnswered)
                                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start">
                                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-red-900">Pertanyaan ini wajib diisi!</p>
                                        <p class="text-xs text-red-800 mt-1">Silakan jawab pertanyaan ini untuk melanjutkan ke pertanyaan berikutnya.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Informasi Rekening Tujuan -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Rekening Tujuan Transfer</h3>
            </div>
            <div class="p-6">
                @if($setting->rekening_bank_nama)
                    <div class="p-6 border-2 border-green-500 bg-green-50 rounded-lg">
                        <div class="text-center mb-4">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h4 class="text-xl font-bold text-green-900">{{ $setting->rekening_bank_nama }}</h4>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-green-200">
                                <span class="text-sm text-green-700 font-medium">Nomor Rekening:</span>
                                <span class="text-lg font-mono font-bold text-green-900">{{ $setting->rekening_nomor }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-green-200">
                                <span class="text-sm text-green-700 font-medium">Atas Nama:</span>
                                <span class="text-base font-semibold text-green-900">{{ $setting->rekening_atas_nama }}</span>
                            </div>
                        </div>
                        @if($setting->rekening_keterangan)
                            <div class="mt-4 p-3 bg-white rounded border border-green-200">
                                <p class="text-sm text-gray-700">{{ $setting->rekening_keterangan }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Informasi rekening belum diatur oleh admin</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Biaya Tes Psikologi -->
        @if($opsiList->count() > 0)
            @php
                // Check if payment is already approved - disable options if true
                $isPaymentApproved = $tesPsikologiData && $tesPsikologiData->status_verifikasi === 'disetujui';
            @endphp

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Biaya Tes Psikologi</h3>
                </div>
                <div class="p-6">
                    @if($isPaymentApproved)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-green-800 font-medium">Pembayaran Sudah Disetujui</p>
                                    <p class="text-xs text-green-600 mt-1">Opsi biaya tidak dapat diubah karena pembayaran sudah disetujui oleh panitia.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-900">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Petunjuk:</span> Pilih salah satu opsi biaya tes psikologi yang sesuai dengan pilihan Anda.
                            </p>
                        </div>
                    @endif

                    <div class="space-y-3 {{ $isPaymentApproved ? 'pointer-events-none opacity-60' : '' }}">
                        @foreach($opsiList as $opsi)
                            <label class="flex items-start p-4 border-2 rounded-lg {{ $isPaymentApproved ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-blue-50' }} transition {{ $selectedOpsi == $opsi->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input type="radio" wire:model.live="selectedOpsi" value="{{ $opsi->id }}" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500" {{ $isPaymentApproved ? 'disabled' : '' }}>
                                <div class="ml-3 flex-1">
                                    <h4 class="font-semibold text-gray-900">Rp {{ number_format(floatval(str_replace('.', '', $opsi->nama)), 0, ',', '.') }}</h4>
                                    @if($opsi->deskripsi)
                                        <p class="text-sm text-gray-600 mt-1">{{ $opsi->deskripsi }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Status Alert -->
        <x-status-alert
            :status="$tesPsikologiData->status_verifikasi"
            :catatan="$tesPsikologiData->catatan_verifikasi"
            type="tes_psikologi"
        />

        <!-- Upload Bukti Transfer -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Upload Bukti Transfer</h3>
                </div>
                <div>
                    @if($tesPsikologiData->status_verifikasi === 'belum_upload')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Belum Upload</span>
                    @elseif($tesPsikologiData->status_verifikasi === 'menunggu')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                    @elseif($tesPsikologiData->status_verifikasi === 'disetujui')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                    @elseif($tesPsikologiData->status_verifikasi === 'ditolak')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <!-- Peringatan Survey Belum Diisi -->
                @if($setting->survey_aktif && !$surveyCompleted && $tesPsikologiData->status_verifikasi === 'belum_upload')
                    <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-8 h-8 text-red-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h4 class="text-base font-bold text-red-900 mb-2">Tidak Dapat Upload Bukti Transfer</h4>
                                <p class="text-sm text-red-800 mb-3">Anda harus mengisi dan menyimpan <strong>Survey</strong> terlebih dahulu sebelum dapat melakukan upload bukti pembayaran.</p>
                                <p class="text-sm text-red-700">Silakan scroll ke atas dan lengkapi bagian <strong>"Informasi Survey"</strong> yang ditandai dengan warna ungu.</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($tesPsikologiData->status_verifikasi === 'disetujui')
                    <!-- Status Disetujui -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-green-900 mb-2">Bukti Transfer Disetujui</h3>
                        <p class="text-sm text-green-700">Pembayaran tes psikologi Anda telah diverifikasi dan disetujui. Anda dapat melanjutkan ke tahap berikutnya.</p>

                        <div class="mt-4 flex justify-center space-x-3">
                            @if($tesPsikologiData->bukti_transfer)
                                <a href="{{ route('view.bukti-bayar', ['pendaftar' => $pendaftar->id, 'type' => 'psikologi']) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Bukti
                                </a>
                            @endif
                            <button wire:click="downloadInvoice" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Invoice
                            </button>
                        </div>

                        @if($tesPsikologiData->catatan_verifikasi)
                            <div class="mt-4 p-3 bg-white rounded">
                                <p class="text-sm text-gray-600"><span class="font-semibold">Catatan:</span> {{ $tesPsikologiData->catatan_verifikasi }}</p>
                            </div>
                        @endif
                    </div>
                @elseif($tesPsikologiData->status_verifikasi === 'ditolak')
                    <!-- Status Ditolak -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-red-900">Bukti Transfer Ditolak</h4>
                                <p class="text-sm text-red-700 mt-1">Silakan upload ulang bukti transfer yang sesuai.</p>
                                @if($tesPsikologiData->catatan_verifikasi)
                                    <p class="text-sm text-red-700 mt-2"><span class="font-semibold">Alasan:</span> {{ $tesPsikologiData->catatan_verifikasi }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Form Upload Ulang -->
                    @include('livewire.pendaftar.tes-psikologi-form')
                @elseif($tesPsikologiData->status_verifikasi === 'menunggu')
                    <!-- Menunggu Verifikasi -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mb-6">
                        <svg class="w-16 h-16 text-yellow-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-yellow-900 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-sm text-yellow-700">Bukti transfer Anda sedang dalam proses verifikasi oleh panitia.</p>
                        <p class="text-xs text-yellow-600 mt-2">Upload: {{ $tesPsikologiData->tanggal_upload->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Detail Pembayaran yang Disubmit -->
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Detail Pembayaran yang Disubmit
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Bank/Metode:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->bank->nama_bank ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Waktu Transfer:</span>
                                <span class="ml-2 font-medium text-gray-900">
                                    {{ $tesPsikologiData->waktu_transfer ? $tesPsikologiData->waktu_transfer->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Nama Pengirim:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->nama_pengirim ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Upload:</span>
                                <span class="ml-2 font-medium text-gray-900">{{ $tesPsikologiData->tanggal_upload->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transfer yang Telah Diupload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer yang Telah Diupload</label>
                        <div class="relative">
                            <img
                                src="{{ route('view.bukti-bayar', ['pendaftar' => $pendaftar->id, 'type' => 'psikologi']) }}"
                                alt="Bukti Transfer"
                                class="w-full max-w-md rounded-lg border border-gray-300"
                                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%239ca3af\'%3EGagal memuat gambar%3C/text%3E%3C/svg%3E'; this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Gambar gagal dimuat.</strong> Silakan refresh halaman atau hubungi panitia jika masalah berlanjut.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button wire:click="hapusBukti" onclick="return confirm('Yakin ingin menghapus bukti transfer?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus & Upload Ulang
                    </button>
                @else
                    <!-- Form Upload (Muncul hanya jika survey sudah diisi atau survey tidak aktif) -->
                    @if($surveyCompleted)
                        @include('livewire.pendaftar.tes-psikologi-form')
                    @else
                        <!-- Pesan form disabled -->
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Form Upload Terkunci</h3>
                            <p class="text-sm text-gray-600 max-w-md mx-auto">Silakan lengkapi survey terlebih dahulu pada bagian <strong>"Informasi Survey"</strong> di atas untuk membuka form upload bukti transfer.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto hide notifications and handle survey saved event
    document.addEventListener('livewire:init', () => {
        Livewire.on('notification', (event) => {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });

        // Handle survey saved - scroll to upload section
        Livewire.on('survey-saved', (event) => {
            setTimeout(() => {
                // Smooth scroll to upload section
                const uploadSection = document.querySelector('.bg-purple-600')?.closest('.bg-white');
                if (uploadSection) {
                    uploadSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Add highlight effect
                    uploadSection.classList.add('ring-4', 'ring-purple-300');
                    setTimeout(() => {
                        uploadSection.classList.remove('ring-4', 'ring-purple-300');
                    }, 2000);
                }
            }, 500);
        });
    });
</script>
@endpush
