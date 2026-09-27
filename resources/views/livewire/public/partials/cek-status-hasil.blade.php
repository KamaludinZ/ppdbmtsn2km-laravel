{{-- Hasil cek status; $ringkasan dari App\Support\StatusPendaftaran::ringkasan() --}}
@if($ringkasan)
    <div class="mt-6 space-y-5" aria-live="polite">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-200 pb-4">
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Data ditemukan</p>
                <h3 class="text-lg font-bold text-gray-900 break-words">{{ $ringkasan['nama'] }}</h3>
                <p class="text-sm text-gray-600">{{ $ringkasan['nomor'] }} · NISN {{ $ringkasan['nisn'] }}</p>
            </div>
            <dl class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-0.5 text-sm">
                <dt class="text-gray-500">Jalur</dt><dd class="font-medium text-gray-800">{{ $ringkasan['jalur'] }}</dd>
                <dt class="text-gray-500">Tanggal daftar</dt><dd class="font-medium text-gray-800">{{ $ringkasan['tanggal_daftar'] }}</dd>
            </dl>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach($ringkasan['utama'] as $item)
                <div class="rounded-lg border {{ $item['tone']['border'] }} {{ $item['tone']['bg'] }} p-4">
                    <p class="text-xs font-medium text-gray-600">{{ $item['judul'] }}</p>
                    <p class="mt-1 flex items-center gap-2 text-sm font-bold {{ $item['tone']['text'] }}">
                        <span class="h-2 w-2 flex-shrink-0 rounded-full {{ $item['tone']['dot'] }}"></span>{{ $item['label'] }}
                    </p>
                </div>
            @endforeach
        </div>

        @if(count($ringkasan['daftar_ulang']))
            <div>
                <h4 class="mb-2 text-sm font-semibold text-gray-900">Status Daftar Ulang</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($ringkasan['daftar_ulang'] as $item)
                        <div class="rounded-lg border {{ $item['tone']['border'] }} {{ $item['tone']['bg'] }} p-4">
                            <p class="text-xs font-medium text-gray-600">{{ $item['judul'] }}</p>
                            <p class="mt-1 flex items-center gap-2 text-sm font-bold {{ $item['tone']['text'] }}">
                                <span class="h-2 w-2 flex-shrink-0 rounded-full {{ $item['tone']['dot'] }}"></span>{{ $item['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($ringkasan['catatan'])
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <p class="text-sm font-semibold text-blue-900">Catatan dari panitia</p>
                <p class="mt-1 text-sm text-blue-800 whitespace-pre-line break-words">{{ $ringkasan['catatan'] }}</p>
            </div>
        @endif

        @if($ringkasan['petunjuk'])
            @php $tone = \App\Support\StatusPendaftaran::TONE[$ringkasan['petunjuk'][0]] ?? \App\Support\StatusPendaftaran::TONE['gray']; @endphp
            <div class="rounded-lg border {{ $tone['border'] }} {{ $tone['bg'] }} p-4">
                <p class="text-sm font-semibold {{ $tone['text'] }}">{{ $ringkasan['petunjuk'][1] }}</p>
                <p class="mt-1 text-sm text-gray-700">{{ $ringkasan['petunjuk'][2] }}</p>
            </div>
        @endif

        <a href="/login" class="block w-full text-center bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-emerald-700 transition">
            Login ke Dashboard Pendaftar
        </a>
    </div>
@endif
