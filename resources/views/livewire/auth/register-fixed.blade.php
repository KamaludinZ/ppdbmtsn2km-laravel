<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8"
     x-data="{
         showPasswordStrength: false,
         passwordStrength: 0,
         checkPasswordStrength(password) {
             let strength = 0;
             if (password.length >= 8) strength++;
             if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
             if (password.match(/[0-9]/)) strength++;
             if (password.match(/[^a-zA-Z0-9]/)) strength++;
             this.passwordStrength = strength;
         }
     }"
     @scroll-to-top.window="window.scrollTo({top: 0, behavior: 'smooth'})">

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Pendaftaran {{ $appAcronym }}</h1>
            <p class="text-lg text-gray-600">{{ \App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang') }} - Tahun Pelajaran {{ \App\Models\Setting::get('tahun_pelajaran', '2025/2026') }}</p>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-between relative">
                <!-- Progress Line -->
                <div class="absolute left-0 top-5 h-0.5 w-full bg-gray-200">
                    <div class="h-0.5 bg-emerald-600 transition-all duration-300"
                         style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>
                </div>

                @for ($step = 1; $step <= $totalSteps; $step++)
                    <div class="relative flex flex-col items-center">
                        <div class="w-10 h-10 {{ $currentStep >= $step ? 'bg-emerald-600' : 'bg-gray-300' }}
                                    rounded-full flex items-center justify-center text-white font-bold z-10
                                    transition-all duration-300 {{ $currentStep == $step ? 'ring-4 ring-emerald-200' : '' }}">
                            @if($currentStep > $step)
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-center
                                   {{ $currentStep >= $step ? 'text-emerald-600' : 'text-gray-500' }}">
                            @if($step == 1) Pilih Jalur
                            @elseif($step == 2) Input Nilai
                            @else Data Diri
                            @endif
                        </span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg animate-fade-in">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-fade-in">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Step 1: Pilih Jalur -->
            @if($currentStep == 1)
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pilih Jalur Pendaftaran</h2>
                    <p class="text-gray-600 mb-6">Pilih jalur pendaftaran yang sesuai dengan kualifikasi Anda</p>

                    @error('jalur_pendaftaran_id')
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        </div>
                    @enderror

                    @if($jalurPendaftaran && $jalurPendaftaran->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($jalurPendaftaran as $jalur)
                                @php
                                    // Hitung kuota tersedia dengan benar (on-the-fly)
                                    // Kuota 0 = unlimited, selalu tersedia
                                    $isKuotaPenuh = ($jalur->kuota > 0) && ($jalur->pendaftar_count >= $jalur->kuota);
                                    $isKuotaTersedia = ($jalur->kuota == 0) || ($jalur->pendaftar_count < $jalur->kuota);

                                    // Calculate sisa kuota on-the-fly (JANGAN pakai dari cache/object property!)
                                    $sisaKuota = ($jalur->kuota == 0)
                                        ? null
                                        : max(0, $jalur->kuota - $jalur->pendaftar_count);
                                @endphp

                                <div wire:click="selectJalur({{ $jalur->id }})"
                                     wire:loading.class="opacity-50 cursor-wait"
                                     wire:target="selectJalur({{ $jalur->id }})"
                                     class="relative border-2 rounded-xl p-6 cursor-pointer transition-all duration-200
                                            @if($jalur_pendaftaran_id == $jalur->id)
                                                border-emerald-500 bg-emerald-50 shadow-lg transform scale-[1.02]
                                            @else
                                                border-gray-200 hover:border-gray-300 hover:shadow-md
                                            @endif
                                            {{ $isKuotaPenuh ? 'opacity-50 cursor-not-allowed' : '' }}">

                                    @if($jalur_pendaftaran_id == $jalur->id)
                                        <div class="absolute -top-3 -right-3 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center animate-bounce-once">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    @if($isKuotaPenuh)
                                        <div class="absolute top-4 right-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Kuota Penuh
                                            </span>
                                        </div>
                                    @endif

                                    <h3 class="font-bold text-lg text-gray-900 mb-2 {{ $isKuotaPenuh ? 'line-through' : '' }}">
                                        {{ $jalur->nama }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ $jalur->deskripsi }}</p>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Min: {{ number_format($jalur->nilai_minimum, 0) }}
                                        </span>

                                        @if($jalur->kuota > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                       {{ $isKuotaTersedia ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Kuota: {{ $sisaKuota ?? 0 }}/{{ $jalur->kuota }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Kuota Tidak Terbatas
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-600">Belum ada jalur pendaftaran yang aktif saat ini</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Step 2: Nilai Rapor -->
            @if($currentStep == 2)
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Rapor</h2>
                    <p class="text-gray-600 mb-6">Masukkan nilai rapor untuk 3 mata pelajaran dari 3 semester</p>

                    @if($selectedJalur)
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <strong>Jalur: {{ $selectedJalur->nama }}</strong><br>
                                Nilai minimum yang diperlukan: <strong>{{ number_format($selectedJalur->nilai_minimum, 0) }}</strong>
                            </p>
                        </div>
                    @endif

                    {{-- Status Kelengkapan Data --}}
                    <div class="mb-6 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Status Kelengkapan Data
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                            @php
                                $nilaiFields = [
                                    ['label' => 'B. Indo 5.1', 'value' => $nilai_b_indo_5_1],
                                    ['label' => 'Mtk 5.1', 'value' => $nilai_matematika_5_1],
                                    ['label' => 'IPA 5.1', 'value' => $nilai_ipa_5_1],
                                    ['label' => 'B. Indo 5.2', 'value' => $nilai_b_indo_5_2],
                                    ['label' => 'Mtk 5.2', 'value' => $nilai_matematika_5_2],
                                    ['label' => 'IPA 5.2', 'value' => $nilai_ipa_5_2],
                                    ['label' => 'B. Indo 6.1', 'value' => $nilai_b_indo_6_1],
                                    ['label' => 'Mtk 6.1', 'value' => $nilai_matematika_6_1],
                                    ['label' => 'IPA 6.1', 'value' => $nilai_ipa_6_1],
                                ];
                            @endphp
                            @foreach($nilaiFields as $field)
                                <div class="flex items-center p-2 rounded {{ $field['value'] ? 'bg-green-100' : 'bg-gray-100' }}">
                                    @if($field['value'])
                                        <svg class="w-4 h-4 text-green-600 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-green-800 font-medium">{{ $field['label'] }}</span>
                                    @else
                                        <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-600">{{ $field['label'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                            <div class="flex items-center p-2 rounded {{ $file_rapor ? 'bg-green-100' : 'bg-gray-100' }}">
                                @if($file_rapor)
                                    <svg class="w-4 h-4 text-green-600 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium">File Rapor</span>
                                @else
                                    <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-600">File Rapor</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Error Summary untuk Step 2 --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-shake">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="font-bold text-red-800 mb-2">Mohon perbaiki kesalahan berikut:</h3>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    @if($errors->has('file_rapor') && str_contains($errors->first('file_rapor'), 'maksimal'))
                                        <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                                            <p class="text-xs text-yellow-800 font-medium flex items-start">
                                                <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span><strong>Tips:</strong> Gunakan tools online seperti ilovepdf.com atau smallpdf.com untuk kompres file PDF Anda.</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Kelas 5 Semester 1 -->
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-emerald-600 font-bold text-sm">5.1</span>
                                </span>
                                Kelas 5 Semester 1
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Bahasa Indonesia <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_b_indo_5_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_b_indo_5_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_b_indo_5_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Matematika <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_matematika_5_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_matematika_5_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_matematika_5_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        IPA <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_ipa_5_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_ipa_5_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_ipa_5_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kelas 5 Semester 2 -->
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-emerald-600 font-bold text-sm">5.2</span>
                                </span>
                                Kelas 5 Semester 2
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Bahasa Indonesia <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_b_indo_5_2"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_b_indo_5_2') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_b_indo_5_2')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Matematika <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_matematika_5_2"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_matematika_5_2') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_matematika_5_2')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        IPA <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_ipa_5_2"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_ipa_5_2') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_ipa_5_2')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kelas 6 Semester 1 -->
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-emerald-600 font-bold text-sm">6.1</span>
                                </span>
                                Kelas 6 Semester 1
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Bahasa Indonesia <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_b_indo_6_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_b_indo_6_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_b_indo_6_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Matematika <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_matematika_6_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_matematika_6_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_matematika_6_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        IPA <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number"
                                           wire:model.live="nilai_ipa_6_1"
                                           step="0.01" min="0" max="100"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nilai_ipa_6_1') border-red-500 @enderror"
                                           placeholder="0-100">
                                    @error('nilai_ipa_6_1')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Rata-rata Nilai Section -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Perhitungan Nilai Rata-Rata
                            </h3>

                            <!-- Rata-rata per Mapel -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Bahasa Indonesia -->
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Rata-rata Bahasa Indonesia</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ number_format($this->getAverageForSubject('b_indo'), 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
                                </div>

                                <!-- Matematika -->
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Rata-rata Matematika</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ number_format($this->getAverageForSubject('matematika'), 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
                                </div>

                                <!-- IPA -->
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <p class="text-xs text-gray-600 mb-1">Rata-rata IPA</p>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ number_format($this->getAverageForSubject('ipa'), 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">Dari 3 semester</p>
                                </div>
                            </div>

                            <!-- Rata-rata Total & Validasi -->
                            <div class="bg-white rounded-lg p-5 shadow-md border-2 {{ $isValid ? 'border-green-400' : 'border-yellow-400' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Rata-Rata Total</p>
                                        <p class="text-3xl font-bold {{ $isValid ? 'text-green-600' : 'text-yellow-600' }}">
                                            {{ number_format($rata_rata_nilai, 2) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 mb-1">Nilai Minimum</p>
                                        <p class="text-2xl font-bold text-gray-700">
                                            {{ $selectedJalur ? number_format($selectedJalur->nilai_minimum, 2) : '-' }}
                                        </p>
                                    </div>
                                </div>

                                @if($validationMessage)
                                    <div class="mt-4 flex items-start p-3 rounded-lg {{ $isValid ? 'bg-green-50' : 'bg-yellow-50' }}">
                                        <svg class="w-5 h-5 {{ $isValid ? 'text-green-500' : 'text-yellow-500' }} mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            @if($isValid)
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                        <p class="text-sm {{ $isValid ? 'text-green-700' : 'text-yellow-700' }}">
                                            {{ $validationMessage }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Upload Rapor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Scan Rapor (PDF) <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1"
                                 x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">

                                <label class="block w-full">
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition-colors cursor-pointer">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <span class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                                    Pilih file
                                                </span>
                                                <input type="file"
                                                       wire:model="file_rapor"
                                                       accept="application/pdf"
                                                       class="sr-only">
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PDF hingga 2MB</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Progress Bar -->
                                <div x-show="isUploading" class="mt-2">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
                                             :style="'width: ' + progress + '%'"></div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Uploading... <span x-text="progress"></span>%</p>
                                </div>

                                @if ($file_rapor)
                                    <div class="mt-3 flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $file_rapor->getClientOriginalName() }}</p>
                                                <p class="text-xs text-gray-500">{{ round($file_rapor->getSize() / 1024, 2) }} KB</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                                wire:click="resetFileRapor"
                                                class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                @error('file_rapor')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 3: Data Pribadi -->
            @if($currentStep == 3)
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Data Diri</h2>

                    {{-- Error Summary untuk Step 3 --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-shake">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="font-bold text-red-800 mb-2">Mohon lengkapi data berikut:</h3>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                                        <p class="text-xs text-blue-800 font-medium flex items-start">
                                            <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span><strong>Tip:</strong> Pastikan semua field yang bertanda <span class="text-red-600">*</span> (merah) sudah terisi dengan lengkap dan benar.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Data Pribadi -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Data Pribadi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- NISN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        NISN <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.lazy="nisn"
                                           wire:blur="validateNisn"
                                           maxlength="10"
                                           pattern="[0-9]{10}"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nisn') border-red-500 @enderror
                                                  @if($nisnValidationMessage) border-red-500 @endif"
                                           placeholder="10 digit NISN (hanya angka)">
                                    <p class="mt-1 text-xs text-gray-500">Masukkan 10 digit NISN (hanya angka)</p>
                                    @if($nisnValidationMessage)
                                        <p class="mt-1 text-xs text-red-500">{{ $nisnValidationMessage }}</p>
                                    @endif
                                    @error('nisn')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.lazy="nama_lengkap"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nama_lengkap') border-red-500 @enderror"
                                           placeholder="Nama sesuai ijazah/akta">
                                    @error('nama_lengkap')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tempat Lahir -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tempat Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.lazy="tempat_lahir"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('tempat_lahir') border-red-500 @enderror"
                                           placeholder="Kota/Kabupaten">
                                    @error('tempat_lahir')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Lahir -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date"
                                           wire:model.lazy="tanggal_lahir"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('tanggal_lahir') border-red-500 @enderror">
                                    @error('tanggal_lahir')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.lazy="jenis_kelamin"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                   @error('jenis_kelamin') border-red-500 @enderror">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Agama -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Agama <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.lazy="agama"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                   @error('agama') border-red-500 @enderror">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea wire:model.lazy="alamat"
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                     @error('alamat') border-red-500 @enderror"
                                              placeholder="Alamat lengkap tempat tinggal"></textarea>
                                    @error('alamat')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Telepon Orang Tua -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        No. Telepon/WhatsApp Orang Tua <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                           wire:model.live="telepon_ortu"
                                           wire:blur="formatPhoneNumber"
                                           maxlength="15"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 43"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('telepon_ortu') border-red-500 @enderror"
                                           placeholder="62812xxxxxxxx">
                                    <p class="mt-1 text-xs text-gray-500">Format: 62812xxxxxxxx (dimulai dengan 62)</p>
                                    @error('telepon_ortu')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data Sekolah Asal -->
                        <div x-data="{ jenisSekolah: @entangle('jenis_sekolah_asal') }">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Data Sekolah Asal
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Jenis Sekolah -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Sekolah Asal <span class="text-red-500">*</span>
                                    </label>
                                    <select x-model="jenisSekolah"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                   @error('jenis_sekolah_asal') border-red-500 @enderror">
                                        <option value="">-- Pilih Jenis Sekolah --</option>
                                        <option value="madrasah">Madrasah (MI)</option>
                                        <option value="umum">Sekolah Umum (SD)</option>
                                    </select>
                                    @error('jenis_sekolah_asal')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NSM (Madrasah) -->
                                <div x-show="jenisSekolah === 'madrasah'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        NSM (Nomor Statistik Madrasah) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.live="nsm_sekolah_asal"
                                           maxlength="12"
                                           pattern="[0-9]{12}"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('nsm_sekolah_asal') border-red-500 @enderror"
                                           placeholder="12 digit NSM (hanya angka)">
                                    <p class="mt-1 text-xs text-gray-500">Masukkan 12 digit NSM, nama sekolah akan terisi otomatis</p>
                                    @error('nsm_sekolah_asal')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NPSN (Umum) -->
                                <div x-show="jenisSekolah === 'umum'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        NPSN (Nomor Pokok Sekolah Nasional) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.live="npsn_sekolah_asal"
                                           maxlength="8"
                                           pattern="[0-9]{8}"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('npsn_sekolah_asal') border-red-500 @enderror"
                                           placeholder="8 digit NPSN (hanya angka)">
                                    <p class="mt-1 text-xs text-gray-500">Masukkan 8 digit NPSN, nama sekolah akan terisi otomatis</p>
                                    @error('npsn_sekolah_asal')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nama Sekolah -->
                                <div :class="jenisSekolah ? '' : 'md:col-span-2'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nama Sekolah Asal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           wire:model.lazy="asal_sekolah"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('asal_sekolah') border-red-500 @enderror"
                                           placeholder="Nama lengkap sekolah">
                                    @error('asal_sekolah')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data Akun -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Data Akun Login
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           wire:model.lazy="email"
                                           wire:blur="validateEmail"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                  @error('email') border-red-500 @enderror
                                                  @if($emailValidationMessage) border-red-500 @endif"
                                           placeholder="email@example.com">
                                    @if($emailValidationMessage)
                                        <p class="mt-1 text-xs text-red-500 flex items-start">
                                            <svg class="w-4 h-4 text-red-500 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $emailValidationMessage }}</span>
                                        </p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-500">
                                            <svg class="inline w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Gunakan email aktif yang dapat diakses untuk menerima informasi pendaftaran
                                        </p>
                                    @endif
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div x-data="{ showPassword: false }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'"
                                               wire:model.lazy="password"
                                               x-on:input="checkPasswordStrength($event.target.value); showPasswordStrength = true"
                                               x-on:blur="showPasswordStrength = false"
                                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                      @error('password') border-red-500 @enderror"
                                               placeholder="Minimal 8 karakter">
                                        <button type="button"
                                                @click="showPassword = !showPassword"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div x-show="showPasswordStrength" x-transition class="mt-2">
                                        <div class="flex gap-1">
                                            <div class="h-1 flex-1 rounded"
                                                 :class="passwordStrength >= 1 ? 'bg-red-500' : 'bg-gray-200'"></div>
                                            <div class="h-1 flex-1 rounded"
                                                 :class="passwordStrength >= 2 ? 'bg-yellow-500' : 'bg-gray-200'"></div>
                                            <div class="h-1 flex-1 rounded"
                                                 :class="passwordStrength >= 3 ? 'bg-blue-500' : 'bg-gray-200'"></div>
                                            <div class="h-1 flex-1 rounded"
                                                 :class="passwordStrength >= 4 ? 'bg-green-500' : 'bg-gray-200'"></div>
                                        </div>
                                        <p class="text-xs mt-1"
                                           :class="{
                                               'text-red-500': passwordStrength == 1,
                                               'text-yellow-500': passwordStrength == 2,
                                               'text-blue-500': passwordStrength == 3,
                                               'text-green-500': passwordStrength == 4,
                                               'text-gray-400': passwordStrength == 0
                                           }">
                                            <span x-show="passwordStrength == 0">Masukkan password</span>
                                            <span x-show="passwordStrength == 1">Lemah</span>
                                            <span x-show="passwordStrength == 2">Sedang</span>
                                            <span x-show="passwordStrength == 3">Kuat</span>
                                            <span x-show="passwordStrength == 4">Sangat Kuat</span>
                                        </p>
                                    </div>

                                    @error('password')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Konfirmasi Password -->
                                <div x-data="{ showPasswordConfirm: false }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input :type="showPasswordConfirm ? 'text' : 'password'"
                                               wire:model.lazy="password_confirmation"
                                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                                                      @error('password_confirmation') border-red-500 @enderror"
                                               placeholder="Ulangi password">
                                        <button type="button"
                                                @click="showPasswordConfirm = !showPasswordConfirm"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password_confirmation')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="bg-gray-50 px-8 py-4 flex justify-between">
                @if($currentStep > 1)
                    <button type="button"
                            wire:click="previousStep"
                            wire:loading.attr="disabled"
                            wire:target="previousStep"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span wire:loading.remove wire:target="previousStep">Kembali</span>
                        <span wire:loading wire:target="previousStep">Loading...</span>
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < $totalSteps)
                    <button type="button"
                            wire:click="nextStep"
                            wire:loading.attr="disabled"
                            wire:target="nextStep"
                            @if($currentStep == 1 && !$jalur_pendaftaran_id) disabled @endif
                            @if($currentStep == 2 && !$this->isStep2Valid) disabled @endif
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition disabled:opacity-50 disabled:cursor-not-allowed
                                   {{ ($currentStep == 1 && $jalur_pendaftaran_id) || ($currentStep == 2 && $this->isStep2Valid) ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-400' }}">
                        <span wire:loading.remove wire:target="nextStep" class="inline-flex items-center">
                            Lanjutkan
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="nextStep" class="inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                @else
                    <button type="button"
                            wire:click="confirmSubmit"
                            wire:loading.attr="disabled"
                            wire:target="confirmSubmit"
                            @if(!$this->isStep3Valid) disabled @endif
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition disabled:opacity-50 disabled:cursor-not-allowed
                                   {{ $this->isStep3Valid ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-gray-400' }}">
                        <span wire:loading.remove wire:target="confirmSubmit" class="inline-flex items-center">
                            Daftar Sekarang
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="confirmSubmit" class="inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmationModal)
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity z-50"
             wire:click="cancelSubmit"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">
                                    Konfirmasi Pendaftaran
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin data yang dimasukkan sudah benar? Data yang sudah didaftarkan tidak dapat diubah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button"
                                wire:click="register"
                                wire:loading.attr="disabled"
                                wire:target="register"
                                class="inline-flex w-full justify-center items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="register">Ya, Daftar Sekarang</span>
                            <span wire:loading wire:target="register" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Loading...
                            </span>
                        </button>
                        <button type="button"
                                wire:click="cancelSubmit"
                                wire:loading.attr="disabled"
                                wire:target="register"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Periksa Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Custom Styles -->
    <style>
        @keyframes bounce-once {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .animate-bounce-once {
            animation: bounce-once 0.5s ease-in-out;
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</div>