@props(['status', 'catatan' => null, 'type' => 'general'])

@php
    // Determine which statuses should show the alert
    $showAlert = in_array($status, ['ditolak', 'perbaiki', 'menunggu', 'belum_upload', 'menunggu_verifikasi', 'lengkapi_data', 'perbaiki_data', 'data_ditolak']);

    // Configure colors and icons based on status
    $config = [
        'ditolak' => [
            'color' => 'red',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'title' => 'Ditolak',
        ],
        'data_ditolak' => [
            'color' => 'red',
            'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'title' => 'Data Ditolak',
        ],
        'perbaiki' => [
            'color' => 'orange',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'title' => 'Perlu Perbaikan',
        ],
        'perbaiki_data' => [
            'color' => 'orange',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'title' => 'Perbaiki Data',
        ],
        'lengkapi_data' => [
            'color' => 'orange',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
            'title' => 'Lengkapi Data',
        ],
        'menunggu' => [
            'color' => 'yellow',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'title' => 'Menunggu Verifikasi',
        ],
        'menunggu_verifikasi' => [
            'color' => 'yellow',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'title' => 'Menunggu Verifikasi',
        ],
        'belum_upload' => [
            'color' => 'blue',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'title' => 'Belum Upload',
        ],
    ];

    $currentConfig = $config[$status] ?? null;

    // Instructions based on type and status
    $instructions = [];

    if ($type === 'tes_psikologi') {
        if ($status === 'ditolak') {
            $instructions = [
                'Pembayaran tes psikologi Anda telah ditolak.',
                'Silakan periksa catatan dari admin/panitia di bawah ini.',
                'Lakukan pembayaran ulang dengan benar sesuai instruksi.',
            ];
        } elseif ($status === 'perbaiki') {
            $instructions = [
                'Bukti pembayaran Anda perlu diperbaiki.',
                'Silakan periksa catatan dari admin/panitia untuk detail perbaikan yang diperlukan.',
                'Upload ulang bukti pembayaran yang sudah diperbaiki.',
            ];
        } elseif ($status === 'menunggu' || $status === 'menunggu_verifikasi') {
            $instructions = [
                'Bukti pembayaran Anda sedang dalam proses verifikasi.',
                'Mohon tunggu hingga admin/panitia memverifikasi pembayaran Anda.',
                'Anda akan mendapatkan notifikasi setelah verifikasi selesai.',
            ];
        } elseif ($status === 'belum_upload') {
            $instructions = [
                'Anda belum mengupload bukti pembayaran tes psikologi.',
                'Silakan lakukan pembayaran dan upload bukti pembayaran.',
                'Pastikan bukti pembayaran jelas dan sesuai dengan jumlah yang tertera.',
            ];
        }
    } elseif ($type === 'formulir') {
        if ($status === 'ditolak' || $status === 'data_ditolak') {
            $instructions = [
                'Data formulir pendaftaran Anda ditolak.',
                'Silakan periksa catatan dari admin/panitia.',
                'Perbaiki data sesuai dengan instruksi yang diberikan.',
            ];
        } elseif ($status === 'perbaiki' || $status === 'lengkapi_data') {
            $instructions = [
                'Data formulir pendaftaran Anda perlu dilengkapi/diperbaiki.',
                'Silakan periksa bagian mana yang perlu diperbaiki.',
                'Lengkapi atau perbaiki data kemudian simpan kembali.',
            ];
        } elseif ($status === 'menunggu' || $status === 'menunggu_verifikasi') {
            $instructions = [
                'Formulir pendaftaran Anda sedang dalam proses verifikasi.',
                'Mohon tunggu hingga admin/panitia memverifikasi data Anda.',
                'Pastikan semua data sudah terisi dengan benar.',
            ];
        }
    } elseif ($type === 'upload_berkas') {
        if ($status === 'ditolak' || $status === 'data_ditolak') {
            $instructions = [
                'Dokumen yang Anda upload ditolak.',
                'Silakan periksa catatan dari admin/panitia mengenai dokumen yang ditolak.',
                'Upload ulang dokumen yang benar dan sesuai persyaratan.',
            ];
        } elseif ($status === 'perbaiki') {
            $instructions = [
                'Dokumen Anda perlu diperbaiki.',
                'Periksa catatan admin/panitia untuk detail perbaikan.',
                'Upload ulang dokumen yang sudah diperbaiki.',
            ];
        } elseif ($status === 'menunggu' || $status === 'menunggu_verifikasi') {
            $instructions = [
                'Dokumen Anda sedang dalam proses verifikasi.',
                'Mohon tunggu hingga admin/panitia memverifikasi dokumen.',
                'Pastikan semua dokumen sudah diupload dengan jelas.',
            ];
        } elseif ($status === 'belum_upload') {
            $instructions = [
                'Anda belum mengupload dokumen yang diperlukan.',
                'Silakan upload semua dokumen yang diminta.',
                'Pastikan dokumen yang diupload jelas dan sesuai dengan aslinya.',
            ];
        }
    } elseif ($type === 'daftar_ulang') {
        if ($status === 'ditolak') {
            $instructions = [
                'Daftar ulang Anda ditolak.',
                'Silakan periksa catatan dari admin/panitia.',
                'Perbaiki sesuai instruksi yang diberikan.',
            ];
        } elseif ($status === 'perbaiki') {
            $instructions = [
                'Data daftar ulang Anda perlu diperbaiki.',
                'Periksa catatan admin/panitia untuk detail perbaikan.',
                'Lengkapi atau perbaiki data yang diminta.',
            ];
        } elseif ($status === 'menunggu') {
            $instructions = [
                'Data daftar ulang Anda sedang diverifikasi.',
                'Mohon tunggu konfirmasi dari admin/panitia.',
                'Pastikan semua data sudah lengkap dan benar.',
            ];
        }
    }
@endphp

@if($showAlert && $currentConfig)
<div class="mb-6 bg-{{ $currentConfig['color'] }}-50 border-l-4 border-{{ $currentConfig['color'] }}-400 p-4 rounded-lg shadow-sm">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-{{ $currentConfig['color'] }}-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $currentConfig['icon'] }}" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-{{ $currentConfig['color'] }}-800">
                {{ $currentConfig['title'] }}
            </h3>

            @if(!empty($instructions))
            <div class="mt-2 text-sm text-{{ $currentConfig['color'] }}-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($instructions as $instruction)
                    <li>{{ $instruction }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($catatan)
            <div class="mt-3 p-3 bg-white rounded border border-{{ $currentConfig['color'] }}-200">
                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia', 'panitia_seragam']))
                    <p class="text-xs font-semibold text-{{ $currentConfig['color'] }}-800 mb-1">Catatan Internal (Admin/Panitia):</p>
                @else
                    <p class="text-xs font-semibold text-{{ $currentConfig['color'] }}-800 mb-1">Catatan dari Admin/Panitia:</p>
                @endif
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $catatan }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
