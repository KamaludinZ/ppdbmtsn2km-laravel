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

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">Data Tes Psikologi Pendaftar</h3>
        </div>

        <!-- Filters -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pendaftar</label>
                    <input wire:model.live="search" type="text" placeholder="Nama atau NISN..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select wire:model.live="filterStatus" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <option value="">Semua Status</option>
                        <option value="belum_upload">Belum Upload</option>
                        <option value="menunggu">Menunggu Verifikasi</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kirim Pesan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Riwayat Pesan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->pendaftar->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->pendaftar->nama_lengkap }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->opsi->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->bank->nama_bank ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->tanggal_upload ? $item->tanggal_upload->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status_verifikasi === 'belum_upload')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Belum Upload</span>
                                @elseif($item->status_verifikasi === 'menunggu')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                @elseif($item->status_verifikasi === 'disetujui')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                @elseif($item->status_verifikasi === 'ditolak')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                @if($item->bukti_transfer)
                                    <button wire:click="openDetail({{ $item->id }})" class="text-emerald-600 hover:text-emerald-900 font-medium">
                                        Detail
                                    </button>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <button
                                        wire:click="sendEmail({{ $item->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                        title="Kirim Email">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Kirim Email
                                    </button>
                                    <button
                                        wire:click="openMessageModal({{ $item->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-emerald-600 text-white text-xs rounded-md hover:bg-emerald-700 transition"
                                        title="Kirim Pesan Internal">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                        </svg>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $lastMessage = \App\Models\MessageHistory::where('pendaftar_id', $item->pendaftar_id)
                                        ->where('context', 'tes_psikologi')
                                        ->orderBy('sent_at', 'desc')
                                        ->first();
                                @endphp
                                @if($lastMessage)
                                    <div class="text-xs">
                                        <div class="flex items-center text-gray-700 mb-1">
                                            @if($lastMessage->message_type === 'email')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">Email</span>
                                            @elseif($lastMessage->message_type === 'whatsapp')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                                <span class="font-medium">WhatsApp</span>
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                                <span class="font-medium">Pesan</span>
                                            @endif
                                        </div>
                                        <div class="text-gray-600">{{ $lastMessage->sent_at->diffForHumans() }}</div>
                                        <div class="text-gray-500 text-[10px]">{{ $lastMessage->sent_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-gray-500 text-[10px]">oleh: {{ $lastMessage->sender->name }}</div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $data->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    @if($detailModal && $selectedData)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" x-data="{ show: @entangle('detailModal') }" x-show="show" x-transition>
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" @click.away="$wire.closeDetail()">
                <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Detail Bukti Transfer</h3>
                    <button wire:click="closeDetail" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Info Pendaftar -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Pendaftar</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->pendaftar->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NISN</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->pendaftar->nisn }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biaya Tes Psikologi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->opsi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi Biaya</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->opsi->deskripsi ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waktu Transfer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->waktu_transfer ? $selectedData->waktu_transfer->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bank</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->bank->nama_bank ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Rekening</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->bank->nomor_rekening ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Upload</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedData->tanggal_upload ? $selectedData->tanggal_upload->format('d M Y H:i') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1">
                                @if($selectedData->status_verifikasi === 'menunggu')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                @elseif($selectedData->status_verifikasi === 'disetujui')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                @elseif($selectedData->status_verifikasi === 'ditolak')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Bukti Transfer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                        <img src="{{ asset('storage/' . $selectedData->bukti_transfer) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-gray-300">
                    </div>

                    <!-- Catatan Verifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi</label>
                        <textarea wire:model="catatanVerifikasi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Tulis catatan jika perlu..."></textarea>
                    </div>

                    @if($selectedData->status_verifikasi === 'disetujui' || $selectedData->status_verifikasi === 'ditolak')
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Diverifikasi oleh: <span class="font-medium">{{ $selectedData->verifiedBy->name ?? '-' }}</span></p>
                            <p class="text-sm text-gray-600">Tanggal: <span class="font-medium">{{ $selectedData->tanggal_verifikasi ? $selectedData->tanggal_verifikasi->format('d M Y H:i') : '-' }}</span></p>
                            @if($selectedData->catatan_verifikasi)
                                <p class="text-sm text-gray-600 mt-2">Catatan: <span class="font-medium">{{ $selectedData->catatan_verifikasi }}</span></p>
                            @endif
                        </div>
                    @endif

                    <!-- Actions -->
                    @if($selectedData->status_verifikasi === 'menunggu')
                        <div class="flex gap-3">
                            <button wire:click="setujui({{ $selectedData->id }})" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                Setujui
                            </button>
                            <button wire:click="tolak({{ $selectedData->id }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                Tolak
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Message Modal -->
    @if($showMessageModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="message-modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeMessageModal"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="message-modal-title">
                                    Kirim Pesan ke Pendaftar
                                </h3>
                                @if($messagingPendaftar)
                                    <p class="text-sm text-gray-600 mt-2">
                                        Kepada: <span class="font-semibold">{{ $messagingPendaftar->pendaftar->nama_lengkap }}</span>
                                        <br>
                                        <span class="text-xs text-gray-500">NISN: {{ $messagingPendaftar->pendaftar->nisn }}</span>
                                    </p>
                                @endif
                                <div class="mt-4">
                                    <label for="messageContent" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pesan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        wire:model="messageContent"
                                        id="messageContent"
                                        rows="5"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200"
                                        placeholder="Tulis pesan Anda di sini..."
                                    ></textarea>
                                    @error('messageContent')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">Pesan akan dikirim sebagai notifikasi ke pendaftar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="sendMessage"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Kirim Pesan
                        </button>
                        <button
                            wire:click="closeMessageModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
