<!-- Survey Responses Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Hasil Survey Responden</h3>
        </div>
    </div>

    @if($responses->count() === 0)
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 font-medium mb-2">Belum ada responden yang mengisi survey</p>
            <p class="text-sm text-gray-500">Data akan muncul setelah ada pendaftar yang mengisi survey</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kabupaten/Kota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah Asal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jawaban</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($responses as $index => $pendaftar)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $pendaftar->nomor_pendaftaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $pendaftar->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $pendaftar->nisn ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($pendaftar->jenis_kelamin === 'L')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                    Laki-laki
                                </span>
                            @elseif($pendaftar->jenis_kelamin === 'P')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                    Perempuan
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $this->getNamaKabupaten($pendaftar->siswa_kabupaten) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                {{ $pendaftar->asal_sekolah ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="space-y-1">
                                @foreach($pendaftar->surveyAnswers as $answer)
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <span class="text-xs font-semibold text-gray-700">{{ $answer->question->question_text }}:</span>
                                            <span class="text-xs text-gray-600">
                                                @if($answer->question->question_type === 'text')
                                                    {{ $answer->answer_text }}
                                                @else
                                                    {{ $answer->option->option_text ?? '-' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button
                                onclick="event.preventDefault(); if(confirm('Lihat detail jawaban untuk {{ $pendaftar->nama_lengkap }}?')) window.open('/admin/pendaftar/{{ $pendaftar->id }}', '_blank')"
                                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                            >
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $responses->links() }}
        </div>
    @endif

    <!-- Summary Section -->
    @if($allQuestions->count() > 0 && $responses->count() > 0)
    <div class="border-t border-gray-200 bg-gray-50 p-6">
        <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Ringkasan Jawaban
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($allQuestions as $question)
                @php
                    $questionAnswers = \App\Models\SurveyAnswer::where('survey_question_id', $question->id)
                        ->with('option')
                        ->get();
                    $totalAnswers = $questionAnswers->count();
                @endphp

                @if($totalAnswers > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <h5 class="text-sm font-semibold text-gray-800 mb-3">{{ $question->question_text }}</h5>

                    @if($question->question_type === 'text')
                        <p class="text-xs text-gray-500 italic">{{ $totalAnswers }} jawaban text</p>
                    @else
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                @php
                                    $optionCount = $questionAnswers->where('survey_option_id', $option->id)->count();
                                    $percentage = $totalAnswers > 0 ? round(($optionCount / $totalAnswers) * 100, 1) : 0;
                                @endphp
                                @if($optionCount > 0)
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-xs text-gray-700">{{ $option->option_text }}</span>
                                        <span class="text-xs font-semibold text-gray-900">{{ $optionCount }} ({{ $percentage }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
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
