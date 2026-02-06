@php
    // Tentukan apakah user bisa akses kolom extended (Ubah Status, Kirim Pesan, Riwayat Pesan)
    $showExtendedColumns = Auth::user()->role === 'admin' || (isset($isPanitiaPsikologi) && $isPanitiaPsikologi);
    $userRole = Auth::user()->role;
@endphp

<!-- Box Informasi Admin & Panitia -->
@if($setting && $setting->informasi_admin_panitia)
<div class="mb-6 bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-600 rounded-lg shadow-sm overflow-hidden">
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-blue-900 mb-1">
                    📌 Informasi Penting untuk Admin & Panitia
                </h3>
                <div class="text-sm text-blue-800 whitespace-pre-wrap">{{ $setting->informasi_admin_panitia }}</div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Data Psikologi Tab Content -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-blue-600">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Data Tes Psikologi Pendaftar</h3>
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
                <!-- Filter Status -->
                <select wire:model.live="filterStatus" class="w-full sm:w-auto px-4 py-2 border-0 rounded-lg text-sm focus:ring-2 focus:ring-white">
                    <option value="all">Semua Status</option>
                    <option value="menunggu">Menunggu Verifikasi</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="perbaiki">Perlu Perbaikan</option>
                </select>
            </div>
        </div>
    </div>

    @if($dataPsikologi->count() === 0)
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-600 font-medium mb-2">Belum ada data tes psikologi</p>
            <p class="text-sm text-gray-500">Data akan muncul setelah ada pendaftar yang mendaftar tes psikologi</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Pendaftaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi/Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pengirim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Psikologi</th>
                        @if($setting && $setting->survey_aktif)
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Survey</th>
                        @endif
                        @if($showExtendedColumns)
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kirim Pesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dataPsikologi as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $data->pendaftar->nomor_pendaftaran }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->pendaftar->nisn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $data->pendaftar->nama_lengkap }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $data->pendaftar->jalurPendaftaran->nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $data->pendaftar->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $data->pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : ($data->pendaftar->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format(floatval(str_replace('.', '', $data->opsi->nama ?? '0')), 0, ',', '.') }}
                            </div>
                            @if($data->opsi && $data->opsi->deskripsi)
                                <div class="text-xs text-gray-500">{{ Str::limit($data->opsi->deskripsi, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $data->bank->nama_bank ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($data->waktu_transfer)
                                {{ $data->waktu_transfer->format('d M Y') }}
                                <br>
                                <span class="text-xs text-gray-500">{{ $data->waktu_transfer->format('H:i') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data->nama_pengirim ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($data->bukti_transfer)
                                <a href="{{ route('view.bukti-bayar', ['pendaftar' => $data->pendaftar_id, 'type' => 'psikologi']) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">Belum upload</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'disetujui' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'perbaiki' => 'bg-orange-100 text-orange-800',
                                ];
                                $statusLabels = [
                                    'menunggu' => 'Menunggu',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'perbaiki' => 'Perbaiki',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$data->status_verifikasi] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$data->status_verifikasi] ?? ucfirst($data->status_verifikasi) }}
                            </span>
                            @if($data->status_verifikasi !== 'menunggu')
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $data->tanggal_verifikasi ? $data->tanggal_verifikasi->format('d M Y') : '' }}
                                </div>
                                @if($data->verifiedBy)
                                    <div class="text-xs text-gray-500">oleh {{ $data->verifiedBy->name }}</div>
                                @endif
                            @endif
                            @if($data->catatan_verifikasi)
                                <div class="text-xs text-gray-600 mt-1 italic">
                                    "{{ Str::limit($data->catatan_verifikasi, 50) }}"
                                </div>
                            @endif
                        </td>

                        <!-- Kolom Jawaban Survey - HARUS SEBELUM KOLOM ADMIN -->
                        @if($setting && $setting->survey_aktif)
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    // Count only tes psikologi survey answers
                                    $surveyAnswersCount = \App\Models\SurveyAnswer::where('pendaftar_id', $data->pendaftar_id)
                                        ->whereHas('question', function($q) {
                                            $q->tesPsikologi();
                                        })
                                        ->count();
                                @endphp
                                @if($surveyAnswersCount > 0)
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Sudah Dijawab
                                        </span>
                                        <button wire:click="openSurveyAnswersModal({{ $data->pendaftar_id }})" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-xs font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat ({{ $surveyAnswersCount }})
                                        </button>
                                    </div>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belum Dijawab
                                    </span>
                                @endif
                            </td>
                        @endif

                        @if($showExtendedColumns)
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <select
                                        wire:change="changeStatus({{ $data->id }}, $event.target.value)"
                                        class="px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    >
                                        <option value="">-- Pilih Status --</option>
                                        <option value="disetujui" {{ $data->status_verifikasi === 'disetujui' ? 'selected' : '' }}>
                                            Disetujui
                                        </option>
                                        <option value="ditolak" {{ $data->status_verifikasi === 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                        <option value="perbaiki" {{ $data->status_verifikasi === 'perbaiki' ? 'selected' : '' }}>
                                            Perbaiki
                                        </option>
                                    </select>
                                    <a href="/{{ $userRole }}/pendaftar/{{ $data->pendaftar_id }}" target="_blank" class="inline-flex items-center px-2 py-1.5 text-blue-600 hover:text-blue-900 text-xs font-medium border border-blue-300 rounded-lg hover:bg-blue-50 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <button
                                        wire:click="openSendMessageModal({{ $data->id }}, 'email')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                        title="Kirim Email dengan Template">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Kirim Email
                                    </button>
                                    <button
                                        wire:click="openSendMessageModal({{ $data->id }}, 'whatsapp')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                        title="Kirim WhatsApp dengan Template">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        Kirim WhatsApp
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $data->pendaftar_id)
                                        ->where('context', 'tes_psikologi')
                                        ->orderBy('sent_at', 'desc')
                                        ->first();
                                @endphp
                                @if($lastMessage)
                                    <div class="text-xs">
                                        <div class="flex items-center text-gray-700 mb-1">
                                            @if($lastMessage->message_type === 'email')
                                                <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">Email</span>
                                            @elseif($lastMessage->message_type === 'whatsapp')
                                                <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347Z"/>
                                                </svg>
                                                <span class="font-medium">WhatsApp</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                                <span class="font-medium">Pesan</span>
                                            @endif
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $lastMessage->sent_at->diffForHumans() }}
                                        </div>
                                        <div class="text-gray-500 text-[10px] mt-0.5">
                                            {{ $lastMessage->sent_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="text-gray-500 text-[10px]">
                                            oleh: {{ $lastMessage->sender->name ?? 'System' }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $dataPsikologi->links() }}
        </div>
    @endif
</div>

<!-- Modal Jawaban Survey -->
@if($showSurveyAnswersModal)
    <div class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay with blur -->
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeSurveyAnswersModal"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative z-[10000]">
                <div class="bg-white px-6 pt-5 pb-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Jawaban Survey
                            </h3>
                            @if($selectedPendaftar)
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $selectedPendaftar->nama_lengkap }} - {{ $selectedPendaftar->nomor_pendaftaran }}
                                </p>
                            @endif
                        </div>
                        <button wire:click="closeSurveyAnswersModal" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="mt-5 max-h-[60vh] overflow-y-auto">
                        @if(count($pendaftarSurveyAnswers) > 0)
                            <div class="space-y-4">
                                @foreach($pendaftarSurveyAnswers as $index => $answer)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-semibold text-indigo-600">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <p class="text-sm font-medium text-gray-900 mb-2">{{ $answer['pertanyaan'] }}</p>
                                                <div class="bg-white border border-gray-300 rounded-lg p-3">
                                                    <p class="text-sm text-gray-700">{{ $answer['jawaban'] }}</p>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-2">
                                                    Tipe: <span class="font-medium">{{ ucfirst($answer['tipe_input']) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500">Belum ada jawaban survey</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button wire:click="closeSurveyAnswersModal" type="button" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
