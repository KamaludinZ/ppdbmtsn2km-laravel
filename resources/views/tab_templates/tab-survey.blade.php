<!-- Tab Survey Content -->
@php
    // Survey hanya bisa diedit oleh Pendaftar, Admin/Panitia selalu read-only
    $currentUserRole = Auth::user()->role;
    $isPendaftar = $currentUserRole === 'pendaftar';

    // Edit mode hanya untuk Pendaftar
    $isEditMode = $isPendaftar ? ($editModeSurvey ?? $editMode ?? false) : false;

    // Load active survey questions - PENTING: Filter by context 'umum' untuk Survey Umum
    $surveyQuestions = \App\Models\SurveyQuestion::umum()->aktif()->ordered()->with('options')->get();
@endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Survey Pendaftaran</h3>
        <p class="text-sm text-gray-600 mt-1">Mohon isi survey berikut untuk membantu kami memahami kebutuhan Anda</p>
    </div>
    @php
        $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
        $shouldDisable = $isApproved && $isPendaftar;
    @endphp

    @if($isPendaftar)
        @if($isEditMode)
            <div class="space-x-2">
                <button wire:click="saveDataSurvey" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan
                </button>
                <button wire:click="toggleEdit('survey')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
            </div>
        @else
            <button
                wire:click="toggleEdit('survey')"
                @if($shouldDisable) disabled title="Data sudah disetujui, tidak dapat diubah" @endif
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition {{ $shouldDisable ? 'opacity-50 cursor-not-allowed' : '' }}">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Data
            </button>
        @endif
    @else
        <!-- Admin/Panitia: Read-only, tidak ada tombol edit -->
        <div class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            Mode Lihat
        </div>
    @endif
</div>

@if($surveyQuestions->count() === 0)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
        <svg class="w-16 h-16 mx-auto text-blue-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
        </svg>
        <p class="text-blue-800 font-medium">Belum ada pertanyaan survey yang aktif</p>
        <p class="text-blue-600 text-sm mt-2">Silakan hubungi panitia jika ada pertanyaan</p>
    </div>
@else
    @if($isEditMode)
        <!-- EDIT MODE -->
        <form wire:submit.prevent="saveDataSurvey" class="space-y-6">
            @foreach($surveyQuestions as $index => $question)
                <div class="bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-emerald-300 transition">
                    <div class="flex items-start mb-4">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 font-bold text-sm mr-3 flex-shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-900 mb-1">
                                {{ $question->pertanyaan }}
                                @if($question->wajib_diisi)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            @if($question->deskripsi)
                                <p class="text-xs text-gray-500 mb-3">{{ $question->deskripsi }}</p>
                            @endif

                            @php
                                $existingAnswer = $pendaftar->surveyAnswers->where('survey_question_id', $question->id)->first();
                            @endphp

                            @if($question->tipe_input === 'select')
                                <select wire:model="surveyAnswers.{{ $question->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" {{ $question->wajib_diisi ? 'required' : '' }}>
                                    <option value="">-- Pilih Jawaban --</option>
                                    @foreach($question->options->sortBy('urutan') as $option)
                                        {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                        <option value="{{ $option->id }}">{{ $option->label }}</option>
                                    @endforeach
                                </select>

                            @elseif($question->tipe_input === 'radio')
                                <div class="space-y-2 mt-2">
                                    @foreach($question->options->sortBy('urutan') as $option)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                            <input type="radio" wire:model="surveyAnswers.{{ $question->id }}" value="{{ $option->id }}" class="border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" {{ $question->wajib_diisi ? 'required' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $option->label }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->tipe_input === 'checkbox')
                                <div class="space-y-2 mt-2">
                                    @foreach($question->options->sortBy('urutan') as $option)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            {{-- NEW APPROACH: Simpan OPTION ID untuk data integrity --}}
                                            <input type="checkbox" wire:model="surveyAnswers.{{ $question->id }}" value="{{ $option->id }}" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <span class="text-sm text-gray-700">{{ $option->label }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->tipe_input === 'text' || $question->tipe_input === 'textarea')
                                <textarea wire:model="surveyAnswers.{{ $question->id }}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Masukkan jawaban Anda" {{ $question->wajib_diisi ? 'required' : '' }}></textarea>

                            @endif

                            @error('surveyAnswers.' . $question->id) <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    @else
        <!-- VIEW MODE -->
        <div class="space-y-4">
            @foreach($surveyQuestions as $index => $question)
                @php
                    $existingAnswer = $pendaftar->surveyAnswers->where('survey_question_id', $question->id)->first();
                @endphp
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                    <div class="flex items-start">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-600 font-bold text-sm mr-3 flex-shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $question->pertanyaan }}</h4>
                            <div class="text-sm text-gray-700">
                                @if($existingAnswer)
                                    <p class="bg-white px-3 py-2 rounded border border-gray-200">{{ $existingAnswer->getJawabanFormatted() ?? '-' }}</p>
                                @else
                                    <p class="text-gray-400 italic">Belum diisi</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif
