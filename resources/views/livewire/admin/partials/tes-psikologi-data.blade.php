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
            <div class="w-full sm:w-auto">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Opsi/Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti Transfer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Psikologi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kirim Pesan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($dataPsikologi as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $data->pendaftar->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $data->pendaftar->nomor_pendaftaran }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $data->pendaftar->nisn ?? '-' }}</div>
                            </div>
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
                            @if($data->bukti_transfer)
                                <a href="{{ Storage::url($data->bukti_transfer) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs font-medium">
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
                                <a href="/admin/pendaftar/{{ $data->pendaftar_id }}" target="_blank" class="inline-flex items-center px-2 py-1.5 text-blue-600 hover:text-blue-900 text-xs font-medium border border-blue-300 rounded-lg hover:bg-blue-50 transition">
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
                                    wire:click="sendManualEmail({{ $data->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                    title="Kirim Email Manual">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Kirim Email
                                </button>
                                <button
                                    wire:click="sendManualWhatsApp({{ $data->id }})"
                                    class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                    title="Kirim WhatsApp Manual">
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
