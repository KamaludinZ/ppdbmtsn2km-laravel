<!-- Tab Survey Content -->
@php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeSurvey)
    $isEditMode = $editModeSurvey ?? $editMode ?? false;

    // Load active survey questions
    $surveyQuestions = \App\Models\SurveyQuestion::active()->ordered()->with('options')->get();
@endphp

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Survey Pendaftaran</h3>
        <p class="text-sm text-gray-600 mt-1">Mohon isi survey berikut untuk membantu kami memahami kebutuhan Anda</p>
    </div>
    @if($isEditMode)
        <div class="space-x-2">
            <button wire:click="saveDataSurvey" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('survey')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    @else
        <button wire:click="toggleEdit('survey')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Data
        </button>
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
                                {{ $question->question_text }}
                                @if($question->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            @if($question->description)
                                <p class="text-xs text-gray-500 mb-3">{{ $question->description }}</p>
                            @endif

                            @php
                                $existingAnswer = $pendaftar->surveyAnswers->where('survey_question_id', $question->id)->first();
                            @endphp

                            @if($question->question_type === 'dropdown')
                                <select wire:model="surveyAnswers.{{ $question->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" {{ $question->is_required ? 'required' : '' }}>
                                    <option value="">-- Pilih Jawaban --</option>
                                    @foreach($question->options as $option)
                                        <option value="{{ $option->id }}">{{ $option->option_text }}</option>
                                    @endforeach
                                </select>

                            @elseif($question->question_type === 'radio')
                                <div class="space-y-2 mt-2">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="radio" wire:model="surveyAnswers.{{ $question->id }}" value="{{ $option->id }}" class="border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" {{ $question->is_required ? 'required' : '' }}>
                                            <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'checkbox')
                                <div class="space-y-2 mt-2">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" wire:model="surveyAnswers.{{ $question->id }}.{{ $option->id }}" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'text')
                                <textarea wire:model="surveyAnswers.{{ $question->id }}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Masukkan jawaban Anda" {{ $question->is_required ? 'required' : '' }}></textarea>

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
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $question->question_text }}</h4>
                            <div class="text-sm text-gray-700">
                                @if($existingAnswer)
                                    @if($question->question_type === 'text')
                                        <p class="bg-white px-3 py-2 rounded border border-gray-200">{{ $existingAnswer->answer_text ?? '-' }}</p>
                                    @else
                                        <p class="bg-white px-3 py-2 rounded border border-gray-200">{{ $existingAnswer->option->option_text ?? '-' }}</p>
                                    @endif
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
