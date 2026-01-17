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
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Biaya Tes Psikologi</h3>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-900">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Petunjuk:</span> Pilih salah satu opsi biaya tes psikologi yang sesuai dengan pilihan Anda.
                        </p>
                    </div>
                    <div class="space-y-3">
                        @foreach($opsiList as $opsi)
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50 transition {{ $selectedOpsi == $opsi->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input type="radio" wire:model.live="selectedOpsi" value="{{ $opsi->id }}" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500">
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

        <!-- Status & Upload Form -->
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
                @if($tesPsikologiData->status_verifikasi === 'disetujui')
                    <!-- Status Disetujui -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-green-900 mb-2">Bukti Transfer Disetujui</h3>
                        <p class="text-sm text-green-700">Pembayaran tes psikologi Anda telah diverifikasi dan disetujui. Anda dapat melanjutkan ke tahap berikutnya.</p>

                        <div class="mt-4 flex justify-center space-x-3">
                            <a href="{{ route('pendaftar.tes-psikologi.preview-invoice') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview Invoice
                            </a>
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

                    <!-- Preview Bukti -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                        <img src="{{ asset('storage/' . $tesPsikologiData->bukti_transfer) }}" alt="Bukti Transfer" class="w-full max-w-md rounded-lg border border-gray-300">
                    </div>

                    <button wire:click="hapusBukti" onclick="return confirm('Yakin ingin menghapus bukti transfer?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus & Upload Ulang
                    </button>
                @else
                    <!-- Form Upload -->
                    @include('livewire.pendaftar.tes-psikologi-form')
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto hide notifications
    document.addEventListener('livewire:init', () => {
        Livewire.on('notification', (event) => {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });
    });
</script>
@endpush
