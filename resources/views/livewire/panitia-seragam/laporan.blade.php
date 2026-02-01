<div>
    <!-- Clean URL (remove activeTab parameter) -->
    <div x-data x-init="
        // Clean URL on component init
        if (window.location.search.includes('activeTab=')) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
        // Also clean URL after any Livewire update
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (window.location.search.includes('activeTab=')) {
                    const cleanUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            });
        });
    " style="display: none;"></div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    wire:click="switchTab('daftar_ulang_seragam')"
                    class="{{$activeTab === 'daftar_ulang_seragam' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    🧾 Daftar Ulang Seragam
                </button>
                <button
                    wire:click="switchTab('seragam')"
                    class="{{$activeTab === 'seragam' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'}} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👕 Ukuran Seragam
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        @if($activeTab === 'daftar_ulang_seragam')
        <!-- Laporan Daftar Ulang Seragam -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-orange-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Daftar Ulang Seragam</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data siswa yang telah melakukan daftar ulang seragam, termasuk status pembayaran dan pemesanan seragam.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pembayaran</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="pembayaran_diterima">Pembayaran Diterima</option>
                            <option value="seragam_disiapkan">Seragam Disiapkan</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgressDUS: false, progressDUS: 0, elapsedDUS: 0, stageDUS: '', totalRecordsDUS: 0 }">
                        <button @click="showProgressDUS = true; progressDUS = 0; elapsedDUS = 0; stageDUS = 'Memulai proses export...'; totalRecordsDUS = 0; $wire.exportDaftarUlangSeragam()"
                                :disabled="showProgressDUS"
                                :class="{ 'opacity-50 cursor-wait': showProgressDUS }"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgressDUS" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgressDUS" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgressDUS ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal Daftar Ulang Seragam -->
                        <div x-show="showProgressDUS" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                             x-init="$watch('showProgressDUS', value => { if (value) { let startTime = Date.now(); let interval = setInterval(() => { elapsedDUS = Math.floor((Date.now() - startTime) / 1000); if (progressDUS < 20) { stageDUS = 'Mempersiapkan data...'; progressDUS += 2; } else if (progressDUS < 40) { stageDUS = 'Mengambil data daftar ulang seragam...'; progressDUS += 1.5; } else if (progressDUS < 60) { stageDUS = 'Memproses data pembayaran...'; progressDUS += 1; } else if (progressDUS < 80) { stageDUS = 'Menyusun struktur Excel...'; progressDUS += 1; } else if (progressDUS < 95) { stageDUS = 'Generating file Excel...'; progressDUS += 0.5; } else { stageDUS = 'Menyelesaikan export...'; if (progressDUS < 99) progressDUS += 0.2; } if (progressDUS >= 100) { setTimeout(() => { showProgressDUS = false; progressDUS = 0; elapsedDUS = 0; clearInterval(interval); }, 1500); } }, 200); setTimeout(() => { showProgressDUS = false; progressDUS = 0; elapsedDUS = 0; clearInterval(interval); }, 30000); } });">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 z-40 bg-gray-500 bg-opacity-75" @click="showProgressDUS = false"></div>
                                <div class="relative z-50 inline-block bg-white rounded-lg px-6 py-6 shadow-xl max-w-lg w-full">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                                        <svg class="h-8 w-8 text-orange-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Export Daftar Ulang Seragam</h3>
                                        <p class="text-sm text-gray-500" x-text="stageDUS"></p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-orange-600" x-text="Math.floor(progressDUS) + '%'"></span>
                                            <span class="text-sm text-gray-500"><span x-text="Math.floor(elapsedDUS / 60)"></span>:<span x-text="(elapsedDUS % 60).toString().padStart(2, '0')"></span></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.floor(progressDUS) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progressDUS > 10" x-text="Math.floor(progressDUS) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-orange-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-orange-800">Download akan dimulai otomatis setelah proses selesai.</p>
                                    </div>
                                    <div x-show="progressDUS >= 95">
                                        <button @click="showProgressDUS = false" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $seragamStats, 'tab' => 'seragam'])

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa</li>
                        <li>Paket Seragam</li>
                        <li>Total Pembayaran</li>
                        <li>Status Pembayaran Seragam</li>
                        <li>Tanggal Pembayaran</li>
                        <li>Bukti Pembayaran</li>
                        <li>Tanggal Verifikasi</li>
                        <li>Catatan</li>
                    </ul>
                </div>
            </div>
        </div>

        @elseif($activeTab === 'seragam')
        <!-- Laporan Seragam & Ukuran -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Seragam & Ukuran</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data ukuran seragam siswa baru yang telah diterima, untuk keperluan pemesanan seragam.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            @foreach($jalurPendaftaran as $jalur)
                                <option value="{{ $jalur->id }}">{{ $jalur->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <select wire:model.live="filterJenisKelamin" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Ukuran</label>
                        <select wire:model.live="filterStatusSeragam" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan</option>
                        </select>
                    </div>
                    <div class="flex items-end" x-data="{ showProgressSR: false, progressSR: 0, elapsedSR: 0, stageSR: '', totalRecordsSR: 0 }">
                        <button @click="showProgressSR = true; progressSR = 0; elapsedSR = 0; stageSR = 'Memulai proses export...'; totalRecordsSR = 0; $wire.exportSeragam()"
                                :disabled="showProgressSR"
                                :class="{ 'opacity-50 cursor-wait': showProgressSR }"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg flex items-center justify-center transition-all">
                            <svg x-show="!showProgressSR" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <svg x-show="showProgressSR" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="showProgressSR ? 'Processing...' : 'Export Excel'"></span>
                        </button>

                        <!-- Progress Modal Seragam -->
                        <div x-show="showProgressSR" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                             x-init="$watch('showProgressSR', value => { if (value) { let startTime = Date.now(); let interval = setInterval(() => { elapsedSR = Math.floor((Date.now() - startTime) / 1000); if (progressSR < 20) { stageSR = 'Mempersiapkan data...'; progressSR += 2; } else if (progressSR < 40) { stageSR = 'Mengambil data seragam...'; progressSR += 1.5; } else if (progressSR < 60) { stageSR = 'Memproses ukuran seragam...'; progressSR += 1; } else if (progressSR < 80) { stageSR = 'Menyusun struktur Excel...'; progressSR += 1; } else if (progressSR < 95) { stageSR = 'Generating file Excel...'; progressSR += 0.5; } else { stageSR = 'Menyelesaikan export...'; if (progressSR < 99) progressSR += 0.2; } if (progressSR >= 100) { setTimeout(() => { showProgressSR = false; progressSR = 0; elapsedSR = 0; clearInterval(interval); }, 1500); } }, 200); setTimeout(() => { showProgressSR = false; progressSR = 0; elapsedSR = 0; clearInterval(interval); }, 30000); } });">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div class="fixed inset-0 z-40 bg-gray-500 bg-opacity-75" @click="showProgressSR = false"></div>
                                <div class="relative z-50 inline-block bg-white rounded-lg px-6 py-6 shadow-xl max-w-lg w-full">
                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 mb-4">
                                        <svg class="h-8 w-8 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">Export Seragam & Ukuran</h3>
                                        <p class="text-sm text-gray-500" x-text="stageSR"></p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-semibold text-indigo-600" x-text="Math.floor(progressSR) + '%'"></span>
                                            <span class="text-sm text-gray-500"><span x-text="Math.floor(elapsedSR / 60)"></span>:<span x-text="(elapsedSR % 60).toString().padStart(2, '0')"></span></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full transition-all duration-300 flex items-center justify-end pr-2" :style="'width: ' + Math.floor(progressSR) + '%'">
                                                <span class="text-xs font-bold text-white" x-show="progressSR > 10" x-text="Math.floor(progressSR) + '%'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-indigo-800">Download akan dimulai otomatis setelah proses selesai.</p>
                                    </div>
                                    <div x-show="progressSR >= 95">
                                        <button @click="showProgressSR = false" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                @include('livewire.admin.partials.daftar-ulang-stats', ['stats' => $ukuranStats, 'tab' => 'ukuran'])

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa (Nama, NISN, Jenis Kelamin, Jalur Pendaftaran)</li>
                        <li>Ukuran Jas</li>
                        <li>Ukuran Baju Olahraga</li>
                        <li>Ukuran Celana Olahraga</li>
                        <li>Ukuran Baju Seragam Harian</li>
                        <li>Ukuran Celana/Rok Harian</li>
                        <li>Ukuran Kopyah (khusus laki-laki)</li>
                        <li>Paket Seragam yang Dipilih</li>
                        <li>Status Ukuran Seragam (Menunggu/Disetujui/Ditolak)</li>
                        <li>Status Pembayaran Seragam</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
