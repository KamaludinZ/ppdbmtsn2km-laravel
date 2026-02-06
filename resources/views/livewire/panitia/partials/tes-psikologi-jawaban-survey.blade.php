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
                <div class="relative w-full sm:w-64">
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Cari nama, NISN, nomor..."
                        class="w-full pl-10 pr-4 py-2 border-0 rounded-lg text-sm text-gray-900 placeholder:text-gray-900 focus:ring-2 focus:ring-white"
                    >
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
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
    @elseif(count($surveyQuestionsForAnswers) === 0)
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Survey</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>

                        <!-- Dynamic columns for each survey question -->
                        @foreach($surveyQuestionsForAnswers as $question)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                <div class="flex flex-col">
                                    <span>{{ Str::limit($question->pertanyaan, 50) }}</span>
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
                        @foreach($surveyQuestionsForAnswers as $question)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs">
                                    @php
                                        $answer = $data['answers'][$question->id] ?? '-';

                                        // Handle array answers (for checkbox/multiple select)
                                        if (is_array($answer)) {
                                            $answer = implode(', ', $answer);
                                        }

                                        // Convert to string if not already
                                        $answerStr = (string) $answer;
                                    @endphp
                                    @if($answer !== '-')
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
                                    @else
                                        <span class="text-gray-400 italic">-</span>
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
