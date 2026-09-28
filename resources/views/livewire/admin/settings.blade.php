<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div 
            x-data="{ show: false, message: '' }" 
            x-on:settings-saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)" 
            x-show="show" 
            x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-[10000] flex items-center" 
            style="display: none;">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message" class="font-bold"></span>
        </div>

        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Umum</h3>
                </div>
                <p class="text-xs text-emerald-100 mt-1">Konfigurasi fase dan jadwal pendaftaran</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveGeneralSettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Fase Pendaftaran</label>
                            <div class="relative">
                                <select wire:model="registration_phase" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 appearance-none font-medium text-gray-900">
                                    <option value="belum_dibuka">Belum Dibuka</option>
                                    <option value="dibuka">Dibuka</option>
                                    <option value="proses">Proses Seleksi</option>
                                    <option value="pengumuman">Pengumuman Kelulusan</option>
                                    <option value="daftar_ulang">Daftar Ulang</option>
                                    <option value="ditutup">Ditutup</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Label Fase</label>
                            <input type="text" wire:model="registration_phase_label" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Deskripsi Fase</label>
                            <textarea wire:model="registration_phase_description" rows="3" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900" placeholder="Jelaskan detail fase pendaftaran saat ini..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tanggal Mulai</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" wire:model="registration_start_date" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tanggal Selesai</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="date" wire:model="registration_end_date" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="md:col-span-2 my-4">
                            <div class="border-t-2 border-gray-200"></div>
                        </div>

                        <!-- Countdown Timer Settings -->
                        <div class="md:col-span-2" x-data="{ countdownEnabled: @entangle('countdown_pendaftar_enabled') }">
                            <div class="bg-emerald-50 border-2 border-emerald-200 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-bold text-emerald-900 uppercase">Countdown Timer Dashboard Pendaftar</h4>
                                            <p class="text-xs text-emerald-700 mt-0.5">Tampilkan hitung mundur waktu pendaftaran di dashboard pendaftar</p>
                                        </div>
                                    </div>

                                    <!-- Toggle Switch -->
                                    <div class="flex items-center">
                                        <label class="relative inline-block w-14 h-7">
                                            <input type="checkbox"
                                                   wire:model.live="countdown_pendaftar_enabled"
                                                   class="opacity-0 w-0 h-0"
                                                   x-model="countdownEnabled">
                                            <span class="absolute cursor-pointer top-0 left-0 right-0 bottom-0 rounded-full transition-all duration-300"
                                                  :class="countdownEnabled ? 'bg-emerald-600' : 'bg-gray-300'">
                                                <span class="absolute left-1 top-0.5 bg-white w-6 h-6 rounded-full transition-transform duration-300"
                                                      :class="countdownEnabled ? 'transform translate-x-7' : ''"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div x-show="countdownEnabled" x-transition class="space-y-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Label Countdown</label>
                                        <input type="text" wire:model="countdown_pendaftar_label" class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900" placeholder="Waktu Pendaftaran Tersisa">
                                        <p class="text-xs text-gray-500 mt-1">Label yang ditampilkan di atas countdown timer</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Deskripsi Countdown</label>
                                        <textarea wire:model="countdown_pendaftar_description" rows="2" class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900" placeholder="Segera lengkapi data pendaftaran Anda sebelum waktu berakhir."></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Pesan yang ditampilkan di bawah label countdown</p>
                                    </div>
                                    <div class="bg-white border border-emerald-200 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 flex items-start">
                                            <svg class="w-4 h-4 text-emerald-500 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span><strong>Info:</strong> Countdown akan menghitung mundur waktu hingga <strong>Tanggal Selesai</strong> di atas. Countdown otomatis muncul di dashboard pendaftar jika fitur ini diaktifkan.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition flex items-center font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95">
                            <svg wire:loading.remove wire:target="saveGeneralSettings" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveGeneralSettings" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            SIMPAN PENGATURAN UMUM
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Identitas Aplikasi -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Identitas Aplikasi</h3>
                </div>
                <p class="text-xs text-blue-100 mt-1">Konfigurasi nama, logo, dan informasi footer aplikasi</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveIdentitySettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Text Info -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Aplikasi / Sekolah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <input type="text" wire:model="app_name" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                @error('app_name') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Singkatan Sistem (Akronim)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                    </div>
                                    <input type="text" wire:model="app_acronym" placeholder="contoh: PMBM, PPDB" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Singkatan yang akan muncul di berbagai tempat (misal: PMBM, PPDB, SPMBM)</p>
                                @error('app_acronym') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Lengkap Sistem</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <input type="text" wire:model="app_full_name" placeholder="contoh: Penerimaan Murid Baru Madrasah" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Nama lengkap sistem yang akan ditampilkan (misal: Penerimaan Murid Baru Madrasah)</p>
                                @error('app_full_name') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Tahun Pelajaran</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="text" wire:model="tahun_pelajaran" placeholder="contoh: 2025/2026" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                @error('tahun_pelajaran') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Teks Copyright Footer</label>
                                <input type="text" wire:model="app_footer_copyright" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                @error('app_footer_copyright') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Deskripsi Lembaga (Footer)</label>
                                <textarea wire:model="app_footer_description" rows="4" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900" placeholder="Deskripsi singkat untuk ditampilkan di footer..."></textarea>
                                @error('app_footer_description') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Column: Logo & Favicon -->
                        <div class="space-y-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Logo Aplikasi</label>
                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0">
                                        @if ($app_logo)
                                            <img class="h-24 w-24 object-contain rounded-lg border-2 border-white shadow-md bg-white" src="{{ $app_logo->temporaryUrl() }}">
                                        @elseif ($app_logo_url)
                                            <img class="h-24 w-24 object-contain rounded-lg border-2 border-white shadow-md bg-white" src="{{ $app_logo_url }}">
                                        @else
                                            <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center border-2 border-dashed border-gray-300">
                                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" wire:model="app_logo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="mt-2 text-xs text-gray-500 uppercase font-bold tracking-tighter">PNG, JPG up to 1MB</p>
                                    </div>
                                </div>
                                @error('app_logo') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="border-t border-gray-200 pt-6">
                                <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Icon Browser (Favicon)</label>
                                <div class="flex items-center space-x-6">
                                    <div class="flex-shrink-0">
                                        @if ($app_favicon)
                                            <img class="h-12 w-12 object-contain rounded border-2 border-white shadow-sm bg-white" src="{{ $app_favicon->temporaryUrl() }}">
                                        @elseif ($app_favicon_url)
                                            <img class="h-12 w-12 object-contain rounded border-2 border-white shadow-sm bg-white" src="{{ $app_favicon_url }}">
                                        @else
                                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center border border-dashed border-gray-300">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" wire:model="app_favicon" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="mt-2 text-xs text-gray-500 uppercase font-bold tracking-tighter">ICO, PNG up to 512KB</p>
                                    </div>
                                </div>
                                @error('app_favicon') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-xl hover:bg-blue-700 transition flex items-center font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-xs">
                            <svg wire:loading.remove wire:target="saveIdentitySettings" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveIdentitySettings" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            SIMPAN IDENTITAS APLIKASI
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan Email (SMTP)</h3>
                </div>
                <p class="text-xs text-emerald-100 mt-1">Konfigurasi server pengiriman notifikasi email</p>
            </div>
            <div class="p-6">
                <!-- Email Enable/Disable Toggle -->
                <div class="mb-6">
                    <div class="p-4 rounded-xl border {{ $email_enabled ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }}">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" wire:model.live="email_enabled" id="email_enabled" class="sr-only">
                                <div class="block w-10 h-6 rounded-full transition-colors {{ $email_enabled ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform {{ $email_enabled ? 'translate-x-4' : '' }}"></div>
                            </div>
                            <span class="ml-3 block text-sm font-bold uppercase tracking-wide {{ $email_enabled ? 'text-emerald-900' : 'text-red-900' }}">
                                {{ $email_enabled ? 'Notifikasi Email Aktif' : 'Notifikasi Email Nonaktif' }}
                            </span>
                        </label>
                    </div>
                </div>

                <form wire:submit.prevent="saveEmailSettings">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">SMTP Host</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <input type="text" wire:model="smtp_host" placeholder="smtp.gmail.com" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            </div>
                            @error('smtp_host') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Port</label>
                            <input type="text" wire:model="smtp_port" placeholder="587" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            @error('smtp_port') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input type="text" wire:model="smtp_username" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            </div>
                            @error('smtp_username') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input type="password" wire:model="smtp_password" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                            </div>
                            @error('smtp_password') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Enkripsi</label>
                            <select wire:model="smtp_encryption" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900 appearance-none">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        
                        <div class="col-span-1 md:col-span-3 border-t border-gray-100 pt-6 mt-2">
                            <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider flex items-center">
                                <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mr-2 text-xs">01</span>
                                Identitas Pengirim Email
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Pengirim</label>
                                    <input type="text" wire:model="email_from_name" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                                    @error('email_from_name') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Pengirim</label>
                                    <input type="email" wire:model="email_from_address" class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 font-medium text-gray-900">
                                    @error('email_from_address') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-6 border-t border-gray-100 pt-8">
                        <div class="w-full md:max-w-md">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Email Penerima Tes</label>
                            <div class="flex flex-wrap sm:flex-nowrap gap-2 p-1 bg-gray-100 rounded-2xl border border-gray-200">
                                <input type="email" wire:model="test_email_recipient" class="flex-1 min-w-0 bg-transparent border-none focus:ring-0 px-4 py-2 text-sm font-medium" placeholder="email@tujuan.com">
                                <button type="button" wire:click="sendTestEmail" class="bg-white text-blue-600 px-6 py-2 rounded-xl hover:bg-blue-50 transition text-xs font-bold shadow-sm whitespace-nowrap border border-blue-100 uppercase tracking-widest active:scale-95">
                                    <span wire:loading.remove wire:target="sendTestEmail">Kirim Tes</span>
                                    <span wire:loading wire:target="sendTestEmail">Mengirim...</span>
                                </button>
                            </div>
                            @error('test_email_recipient') <span class="text-red-500 text-[10px] font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-3 w-full md:w-auto">
                            <button type="button" wire:click="testEmailConnection" class="flex-1 md:flex-none bg-amber-50 text-amber-700 px-6 py-3 rounded-xl hover:bg-amber-100 transition text-xs font-bold border border-amber-200 uppercase tracking-widest active:scale-95">
                                <span wire:loading.remove wire:target="testEmailConnection">Tes Koneksi</span>
                                <span wire:loading wire:target="testEmailConnection">Menghubungkan...</span>
                            </button>
                            <button type="submit" class="flex-1 md:flex-none bg-emerald-600 text-white px-8 py-3 rounded-xl hover:bg-emerald-700 transition flex items-center justify-center font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-xs">
                                <svg wire:loading.remove wire:target="saveEmailSettings" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span wire:loading.remove wire:target="saveEmailSettings">Simpan Pengaturan</span>
                                <span wire:loading wire:target="saveEmailSettings">Menyimpan...</span>
                            </button>
                        </div>
                    </div>

                    <!-- Email Tips & Troubleshooting -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-blue-900 mb-2">Tips Konfigurasi Email</h5>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li><strong>Gmail:</strong> Host: smtp.gmail.com, Port: 587, Encryption: TLS. Gunakan App Password jika 2FA aktif.</li>
                                    <li><strong>Outlook:</strong> Host: smtp-mail.outlook.com, Port: 587, Encryption: TLS.</li>
                                    <li><strong>Yahoo:</strong> Host: smtp.mail.yahoo.com, Port: 465, Encryption: SSL.</li>
                                    <li><strong>Troubleshooting:</strong> Jika gagal kirim, cek folder spam, pastikan "Less Secure Apps" diaktifkan (Gmail), atau gunakan App Password.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- WhatsApp Settings -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Pengaturan WhatsApp Gateway</h3>
                </div>
                <p class="text-xs text-blue-100 mt-1">Konfigurasi API untuk pengiriman notifikasi WhatsApp</p>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveWhatsAppSettings">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="p-4 rounded-xl border {{ $whatsapp_enabled ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }}">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" wire:model.live="whatsapp_enabled" id="whatsapp_enabled" class="sr-only">
                                    <div class="block w-10 h-6 rounded-full transition-colors {{ $whatsapp_enabled ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform {{ $whatsapp_enabled ? 'translate-x-4' : '' }}"></div>
                                </div>
                                <span class="ml-3 block text-sm font-bold uppercase tracking-wide {{ $whatsapp_enabled ? 'text-emerald-900' : 'text-red-900' }}">
                                    {{ $whatsapp_enabled ? 'Notifikasi WhatsApp Aktif' : 'Notifikasi WhatsApp Nonaktif' }}
                                </span>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">API URL</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    </div>
                                    <input type="url" wire:model="whatsapp_api_url" placeholder="https://api.whatsapp-gateway.com/send" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                @error('whatsapp_api_url') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">API Key / Token</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    </div>
                                    <input type="text" wire:model="whatsapp_api_key" class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900">
                                </div>
                                @error('whatsapp_api_key') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nomor Pengirim (Sender)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <input
                                        type="text"
                                        wire:model="whatsapp_sender_number"
                                        id="whatsapp_sender_number"
                                        placeholder="628123456789"
                                        class="w-full pl-12 rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 font-medium text-gray-900"
                                        oninput="formatPhoneNumber(this)"
                                    >
                                </div>
                                <p class="text-[10px] text-blue-600 font-bold uppercase mt-1 ml-1 tracking-wider">Format otomatis: 0xxx → 62xxx</p>
                                @error('whatsapp_sender_number') <span class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-6 border-t border-gray-100 pt-8">
                        <div class="w-full md:max-w-md">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">Nomor Penerima Tes</label>
                            <div class="flex flex-wrap sm:flex-nowrap gap-2 p-1 bg-gray-100 rounded-2xl border border-gray-200">
                                <input
                                    type="text"
                                    wire:model="test_whatsapp_number"
                                    id="test_whatsapp_number"
                                    class="flex-1 min-w-0 bg-transparent border-none focus:ring-0 px-4 py-2 text-sm font-medium"
                                    placeholder="628..."
                                    oninput="formatPhoneNumber(this)"
                                >
                                <button type="button" wire:click="sendTestWhatsApp" class="bg-white text-blue-600 px-6 py-2 rounded-xl hover:bg-blue-50 transition text-xs font-bold shadow-sm whitespace-nowrap border border-blue-100 uppercase tracking-widest active:scale-95">
                                    <span wire:loading.remove wire:target="sendTestWhatsApp">Kirim Tes</span>
                                    <span wire:loading wire:target="sendTestWhatsApp">Mengirim...</span>
                                </button>
                            </div>
                            <p class="text-[10px] text-blue-600 font-bold uppercase mt-1 ml-1 tracking-wider">Format otomatis: 0xxx → 62xxx</p>
                            @error('test_whatsapp_number') <span class="text-red-500 text-[10px] font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex gap-3 w-full md:w-auto">
                            <button type="button" wire:click="testWhatsAppConnection" class="flex-1 md:flex-none bg-amber-50 text-amber-700 px-6 py-3 rounded-xl hover:bg-amber-100 transition text-xs font-bold border border-amber-200 uppercase tracking-widest active:scale-95">
                                <span wire:loading.remove wire:target="testWhatsAppConnection">Tes Koneksi</span>
                                <span wire:loading wire:target="testWhatsAppConnection">Menghubungkan...</span>
                            </button>
                            <button type="submit" class="flex-1 md:flex-none bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition flex items-center justify-center font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-xs">
                                <svg wire:loading.remove wire:target="saveWhatsAppSettings" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span wire:loading.remove wire:target="saveWhatsAppSettings">Simpan Pengaturan</span>
                                <span wire:loading wire:target="saveWhatsAppSettings">Menyimpan...</span>
                            </button>
                        </div>
                    </div>

                    <!-- WhatsApp Tips & Troubleshooting -->
                    <div class="mt-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-green-900 mb-2">Tips Konfigurasi WhatsApp Gateway</h5>
                                <ul class="text-xs text-green-800 space-y-1">
                                    <li><strong>Fonnte:</strong> URL: https://api.fonnte.com/send, Authorization header dengan token dari dashboard.</li>
                                    <li><strong>Wablas:</strong> URL: https://api.wablas.com, gunakan domain sesuai paket Anda.</li>
                                    <li><strong>MTsN 2 Malang Gateway:</strong> URL: https://wgw.mtsn2kotamalang.sch.id (tanpa /send-message), wajib isi Nomor Pengirim.</li>
                                    <li><strong>Format Nomor:</strong> Gunakan format 628xxx (62 + nomor tanpa 0 di depan). Contoh: 628123456789</li>
                                    <li><strong>Troubleshooting:</strong> Pastikan API Key aktif, device WhatsApp tersambung, dan kuota API mencukupi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Print Identity Settings -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Identitas Cetak (Kop PDF)</h3>
                </div>
                <p class="text-xs text-purple-100 mt-1">Konfigurasi kop surat untuk cetak/download PDF</p>
            </div>
            <div class="p-6">
                <!-- Preview Kop PDF -->
                <div class="mb-8 bg-gray-50 rounded-xl p-6 border-2 border-dashed border-gray-300">
                    <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">Preview Kop PDF</h4>
                    <div class="bg-white p-8 rounded-lg border-2 border-gray-200 shadow-inner">
                        <!-- Kop Header -->
                        <div class="flex items-start gap-6 pb-4 border-b-4 border-black">
                            <!-- Logo Kiri -->
                            <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                                @if($print_logo_left_url)
                                    <img src="{{ $print_logo_left_url }}" alt="Logo Kiri" class="max-w-full max-h-full object-contain">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Identitas Tengah -->
                            <div class="flex-1 text-center">
                                <h2 class="text-xl font-bold text-gray-900 uppercase mb-1">
                                    {{ $print_institution_name ?: 'NAMA LEMBAGA' }}
                                </h2>
                                <p class="text-sm text-gray-700 mb-1">
                                    {{ $print_institution_address ?: 'Alamat Lembaga' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    @if($print_institution_phone) Telp: {{ $print_institution_phone }} @endif
                                    @if($print_institution_email) | Email: {{ $print_institution_email }} @endif
                                    @if($print_institution_website) | {{ $print_institution_website }} @endif
                                </p>
                            </div>

                            <!-- Logo Kanan -->
                            <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                                @if($print_logo_right_url)
                                    <img src="{{ $print_logo_right_url }}" alt="Logo Kanan" class="max-w-full max-h-full object-contain">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sample Content -->
                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500 italic">Konten dokumen akan ditampilkan di sini...</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="savePrintIdentitySettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Kiri -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Logo Kiri</label>
                            <div class="space-y-3">
                                @if($print_logo_left_url)
                                    <div class="relative inline-block">
                                        <img src="{{ $print_logo_left_url }}" alt="Logo Kiri" class="h-24 w-24 object-contain border-2 border-gray-200 rounded-lg p-2">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    wire:model="print_logo_left"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500"
                                >
                                <p class="text-xs text-gray-500">Ukuran maksimal: 1MB. Format: JPG, PNG</p>
                                @error('print_logo_left') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Logo Kanan -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Logo Kanan</label>
                            <div class="space-y-3">
                                @if($print_logo_right_url)
                                    <div class="relative inline-block">
                                        <img src="{{ $print_logo_right_url }}" alt="Logo Kanan" class="h-24 w-24 object-contain border-2 border-gray-200 rounded-lg p-2">
                                    </div>
                                @endif
                                <input
                                    type="file"
                                    wire:model="print_logo_right"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500"
                                >
                                <p class="text-xs text-gray-500">Ukuran maksimal: 1MB. Format: JPG, PNG</p>
                                @error('print_logo_right') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Nama Lembaga -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Lembaga *</label>
                            <input
                                type="text"
                                wire:model="print_institution_name"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 font-medium text-gray-900"
                                placeholder="MADRASAH TSANAWIYAH NEGERI 2 KOTA MALANG"
                            >
                            @error('print_institution_name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Alamat Lembaga *</label>
                            <input
                                type="text"
                                wire:model="print_institution_address"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 font-medium text-gray-900"
                                placeholder="Jl. Bandung No. 7 Kota Malang, Jawa Timur 65117"
                            >
                            @error('print_institution_address') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Telepon</label>
                            <input
                                type="text"
                                wire:model="print_institution_phone"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 font-medium text-gray-900"
                                placeholder="(0341) 551276"
                            >
                            @error('print_institution_phone') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email</label>
                            <input
                                type="email"
                                wire:model="print_institution_email"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 font-medium text-gray-900"
                                placeholder="ppdb@mtsn2kotamalang.sch.id"
                            >
                            @error('print_institution_email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Website -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Website</label>
                            <input
                                type="text"
                                wire:model="print_institution_website"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 px-4 py-3 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 font-medium text-gray-900"
                                placeholder="www.mtsn2kotamalang.sch.id"
                            >
                            @error('print_institution_website') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-xl hover:bg-purple-700 transition flex items-center justify-center font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-xs">
                            <svg wire:loading.remove wire:target="savePrintIdentitySettings" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span wire:loading.remove wire:target="savePrintIdentitySettings">Simpan Identitas Cetak</span>
                            <span wire:loading wire:target="savePrintIdentitySettings">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /**
         * Auto-format nomor WhatsApp ke format 62xxx
         * Mengubah:
         * - 0xxx menjadi 62xxx
         * - +62xxx menjadi 62xxx
         * - 62xxx tetap 62xxx
         */
        function formatPhoneNumber(input) {
            // Simpan nilai asli
            let originalValue = input.value;

            // Simpan posisi cursor
            let cursorPosition = input.selectionStart;
            let oldLength = originalValue.length;

            // Ambil nilai input
            let value = originalValue;

            // Hapus semua karakter non-digit
            value = value.replace(/\D/g, '');

            // Format berdasarkan awalan
            if (value.startsWith('0')) {
                // Jika dimulai dengan 0, ganti dengan 62
                value = '62' + value.substring(1);
            } else if (value.startsWith('62')) {
                // Jika sudah dimulai dengan 62, biarkan
                value = value;
            } else if (value.length > 0) {
                // Jika tidak dimulai dengan 0 atau 62, tambahkan 62 di depan
                value = '62' + value;
            }

            // Hanya update jika nilai berubah (mencegah infinite loop)
            if (value !== originalValue) {
                // Update nilai input
                input.value = value;

                // Dispatch event untuk Livewire hanya jika nilai berubah
                input.dispatchEvent(new Event('input', { bubbles: true }));

                // Restore posisi cursor (adjust untuk perubahan panjang)
                let newLength = value.length;
                let lengthDiff = newLength - oldLength;
                let newCursorPos = cursorPosition + lengthDiff;

                // Pastikan cursor position valid
                if (newCursorPos < 0) newCursorPos = 0;
                if (newCursorPos > value.length) newCursorPos = value.length;

                input.setSelectionRange(newCursorPos, newCursorPos);
            }
        }
    </script>
</div>
