<!-- Jawaban Survey Tab Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Jawaban Survey Tes Psikologi</h3>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Search Field -->
                <div class="relative w-full sm:w-48">
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari nama, NISN, nomor..."
                        class="w-full pl-10 pr-4 py-2 border-0 rounded-lg text-sm text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-white"
                    >
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Filter Status Survey -->
                <select wire:model.live="filterStatusSurvey" class="px-4 py-2 border-0 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-white bg-white">
                    <option value="all">Semua Status</option>
                    <option value="sudah">Sudah Dijawab</option>
                    <option value="belum">Belum Dijawab</option>
                </select>

                <!-- Filter Jalur -->
                <select wire:model.live="filterJalur" class="px-4 py-2 border-0 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-white bg-white">
                    <option value="all">Semua Jalur</option>
                    @foreach($jalurList as $jalur)
                        <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                    @endforeach
                </select>

                <!-- Filter Jenis Kelamin -->
                <select wire:model.live="filterJenisKelamin" class="px-4 py-2 border-0 rounded-lg text-sm text-gray-900 focus:ring-2 focus:ring-white bg-white">
                    <option value="all">Semua Gender</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

                <!-- Toggle Edit Mode Button (Admin Only - Crucial Case Handling) -->
                <button
                    wire:click="toggleEditMode"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border-0 rounded-lg text-sm font-semibold transition-all duration-200 {{ $editMode ? 'bg-yellow-500 text-white hover:bg-yellow-600' : 'bg-white text-gray-700 hover:bg-gray-100' }}"
                    title="Toggle Edit Mode untuk edit jawaban survey (Admin Only)"
                >
                    @if($editMode)
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Mode Edit: ON
                    @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        Mode Edit: OFF
                    @endif
                </button>
            </div>
        </div>
    </div>

    @if(count($surveyAnswersData) === 0)
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 font-medium mb-2">Belum ada data jawaban survey</p>
            <p class="text-sm text-gray-500">Data akan muncul setelah pendaftar mengisi survey tes psikologi</p>
        </div>
    @elseif(count($surveyQuestions) === 0)
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-gray-600 font-medium mb-2">Belum ada pertanyaan survey aktif</p>
            <p class="text-sm text-gray-500">
                Silakan buat pertanyaan survey terlebih dahulu di tab
                <a href="?mainTab=survey" class="text-indigo-600 hover:text-indigo-800 font-medium">Pertanyaan Survey</a>
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">Nomor Pendaftaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Survey</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>

                        <!-- Dynamic columns for each survey question -->
                        @foreach($surveyQuestions as $question)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-1 mb-1">
                                        {{-- DEBUG: Show Question ID (Admin only) --}}
                                        <span class="px-1.5 py-0.5 text-[10px] font-mono font-bold bg-yellow-100 text-yellow-800 rounded">Q{{ $question->id }}</span>
                                        <span>{{ Str::limit($question->pertanyaan, 50) }}</span>
                                    </div>
                                    @if($question->deskripsi)
                                        <span class="text-[10px] text-gray-400 normal-case mt-1">{{ Str::limit($question->deskripsi, 60) }}</span>
                                    @endif
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($surveyAnswersData as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10">
                            <div class="text-sm font-medium text-gray-900">{{ $data['nomor_pendaftaran'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data['nisn'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $data['nama_lengkap'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $data['jalur'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $data['jenis_kelamin'] === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $data['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($data['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($data['survey_status'] === 'Sudah Dijawab')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Dijawab
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Belum Dijawab
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($data['survey_status'] === 'Sudah Dijawab')
                                <button
                                    wire:click="resetSurveyAnswers({{ $data['pendaftar_id'] }})"
                                    wire:confirm="Apakah Anda yakin ingin mereset semua jawaban survey pendaftar ini?"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition"
                                    title="Reset Jawaban Survey">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </button>
                            @else
                                <span class="text-gray-400 text-xs italic">-</span>
                            @endif
                        </td>

                        <!-- Dynamic answer cells for each question -->
                        @foreach($surveyQuestions as $question)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @php
                                        $answer = $data['answers'][$question->id] ?? '-';

                                        // Get RAW data from database for debugging (Admin only)
                                        $rawAnswer = null;
                                        $surveyAnswer = \App\Models\SurveyAnswer::where('pendaftar_id', $data['pendaftar_id'])
                                            ->where('survey_question_id', $question->id)
                                            ->first();
                                        if ($surveyAnswer) {
                                            $rawAnswer = $surveyAnswer->jawaban;
                                        }

                                        // Handle array answers (for checkbox/multiple select)
                                        if (is_array($answer)) {
                                            $answer = implode(', ', $answer);
                                        }

                                        // Convert to string if not already
                                        $answerStr = (string) $answer;
                                    @endphp
                                    @if($answer !== '-')
                                        <div class="space-y-1">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1">
                                                    {{-- DEBUG: Show RAW data from database (Admin only) --}}
                                                    @if($rawAnswer !== null)
                                                        <div class="text-[10px] font-mono bg-gray-100 text-gray-700 px-2 py-1 rounded border border-gray-300 mb-1">
                                                            <span class="font-bold text-red-600">RAW:</span>
                                                            @if(is_array($rawAnswer))
                                                                [{{ implode(', ', $rawAnswer) }}]
                                                            @else
                                                                {{ $rawAnswer }}
                                                            @endif
                                                            @if(is_numeric($rawAnswer) || (is_array($rawAnswer) && isset($rawAnswer[0]) && is_numeric($rawAnswer[0])))
                                                                <span class="text-green-600 font-bold ml-1">✓ ID</span>
                                                            @else
                                                                <span class="text-orange-600 font-bold ml-1">⚠ VALUE/LABEL</span>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    {{-- Display formatted answer --}}
                                                    <div class="font-medium">
                                                        @if(strlen($answerStr) > 100)
                                                            <div x-data="{ expanded: false }">
                                                                <div x-show="!expanded">
                                                                    {{ Str::limit($answerStr, 100) }}
                                                                    <button @click="expanded = true" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium ml-1">
                                                                        Lihat Selengkapnya
                                                                    </button>
                                                                </div>
                                                                <div x-show="expanded" x-cloak>
                                                                    {{ $answerStr }}
                                                                    <button @click="expanded = false" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium ml-1">
                                                                        Sembunyikan
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{ $answerStr }}
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- EDIT ICON (Admin Only - Crucial Case Handling) - Only visible when editMode ON --}}
                                                @if($editMode)
                                                <button
                                                    wire:click="openEditAnswerModal({{ $data['pendaftar_id'] }}, {{ $question->id }})"
                                                    class="flex-shrink-0 p-1 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded transition"
                                                    title="Edit Jawaban (Admin Only)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-gray-400 italic">-</span>
                                            {{-- EDIT ICON (untuk create jawaban baru) - Only visible when editMode ON --}}
                                            @if($editMode)
                                            <button
                                                wire:click="openEditAnswerModal({{ $data['pendaftar_id'] }}, {{ $question->id }})"
                                                class="flex-shrink-0 p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition"
                                                title="Tambah Jawaban (Admin Only)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $dataPsikologi->links() }}
        </div>
    @endif

    <!-- Ringkasan Jawaban Section (Similar to Survey Umum) -->
    @if(count($surveyQuestions) > 0 && count($surveyAnswersData) > 0)
    <div class="border-t border-gray-200 bg-gray-50 p-6">
        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Ringkasan Jawaban Survey Tes Psikologi
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($surveyQuestions as $question)
                @php
                    $questionAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
                        ->get();
                    $totalAnswers = $questionAnswers->count();
                @endphp

                @if($totalAnswers > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h5 class="text-sm font-semibold text-gray-800 mb-3">
                        <span class="px-1.5 py-0.5 text-[10px] font-mono font-bold bg-yellow-100 text-yellow-800 rounded mr-1">Q{{ $question->id }}</span>
                        {{ $question->pertanyaan }}
                    </h5>

                    @if($question->tipe_input === 'text' || $question->tipe_input === 'textarea')
                        <p class="text-xs text-gray-500 italic">{{ $totalAnswers }} jawaban text</p>
                    @else
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                @php
                                    // CRITICAL: Count answers that contain this option's ID (or value/label for backward compatibility)
                                    $optionCount = $questionAnswers->filter(function($answer) use ($option) {
                                        $jawaban = $answer->getJawabanArray();

                                        // Check if answer contains:
                                        // 1. Option ID (integer) - NEW SYSTEM
                                        // 2. Option ID (string) - CONVERTED STRING IDs
                                        // 3. Option value (string) - FALLBACK
                                        // 4. Option label (string) - FALLBACK
                                        return in_array($option->id, $jawaban)
                                            || in_array((string)$option->id, $jawaban)
                                            || in_array($option->value, $jawaban)
                                            || in_array($option->label, $jawaban);
                                    })->count();
                                    $percentage = $totalAnswers > 0 ? round(($optionCount / $totalAnswers) * 100, 1) : 0;
                                @endphp
                                @if($optionCount > 0)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-700">{{ $option->label }}</span>
                                        <span class="text-xs font-semibold text-gray-900">{{ $optionCount }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Edit Answer Modal (Admin Only - Crucial Case Handling) -->
@if($showEditAnswerModal && $editingAnswerQuestion)
<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" x-data="{ show: @entangle('showEditAnswerModal') }" x-show="show" x-cloak>
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">
                                Edit Jawaban Survey (Admin Only)
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-semibold">Pertanyaan:</span> {{ $editingAnswerQuestion->pertanyaan }}
                                </p>
                                <p class="text-xs text-gray-500 mb-4">
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded">ID Pertanyaan: {{ $editingAnswerQuestion->id }}</span>
                                </p>

                                <!-- Input Field based on Question Type -->
                                @if($editingAnswerQuestion->tipe_input === 'textarea')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban</label>
                                        <textarea
                                            wire:model="editingAnswerValue"
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Masukkan jawaban..."></textarea>
                                        @error('editingAnswerValue') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                @elseif($editingAnswerQuestion->tipe_input === 'text')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban</label>
                                        <input
                                            type="text"
                                            wire:model="editingAnswerValue"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Masukkan jawaban...">
                                        @error('editingAnswerValue') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                @elseif($editingAnswerQuestion->tipe_input === 'radio' || $editingAnswerQuestion->tipe_input === 'select')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jawaban</label>
                                        <div class="space-y-2">
                                            @foreach($editingAnswerQuestion->options as $option)
                                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        wire:model="editingAnswerValue"
                                                        value="{{ $option->id }}"
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                                    <span class="ml-3 text-sm text-gray-700">
                                                        {{ $option->label }}
                                                        <span class="ml-2 text-xs font-mono text-gray-500">(ID: {{ $option->id }})</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('editingAnswerValue') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                @elseif($editingAnswerQuestion->tipe_input === 'checkbox')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jawaban (Multiple Choice)</label>
                                        <p class="text-xs text-gray-500 mb-3">Anda bisa pilih lebih dari satu opsi</p>

                                        <div class="space-y-2">
                                            @foreach($editingAnswerQuestion->options as $option)
                                                <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array((string)$option->id, $editingAnswerCheckboxValues) ? 'bg-indigo-50 border-indigo-300' : '' }}">
                                                    <input
                                                        type="checkbox"
                                                        wire:model="editingAnswerCheckboxValues"
                                                        value="{{ $option->id }}"
                                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mt-0.5">
                                                    <div class="ml-3 flex-1">
                                                        <span class="text-sm text-gray-700">{{ $option->label }}</span>
                                                        <span class="ml-2 text-xs font-mono text-gray-500">(ID: {{ $option->id }})</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('editingAnswerCheckboxValues') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @endif

                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                <strong>Peringatan:</strong> Edit jawaban hanya untuk kasus crucial. Perubahan akan langsung tersimpan ke database.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <button
                        type="button"
                        wire:click="updateSurveyAnswer"
                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 sm:w-auto">
                        Simpan Perubahan
                    </button>
                    <button
                        type="button"
                        wire:click="closeEditAnswerModal"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
