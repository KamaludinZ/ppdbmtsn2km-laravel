@php
    $countdownEnabled = \App\Models\Setting::get('countdown_pendaftar_enabled', false);
    $countdownLabel = \App\Models\Setting::get('countdown_pendaftar_label', 'Waktu Pendaftaran Tersisa');
    $countdownDescription = \App\Models\Setting::get('countdown_pendaftar_description', 'Segera lengkapi data pendaftaran Anda sebelum waktu berakhir.');
    $endDate = \App\Models\Setting::get('registration_end_date');
@endphp

@if($countdownEnabled && $endDate)
<div class="bg-gradient-to-r from-emerald-50 to-green-50 border-2 border-emerald-200 rounded-xl p-6 shadow-md">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <!-- Icon -->
        <div class="flex-shrink-0">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center border-2 border-emerald-300 shadow-sm">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Text Content -->
        <div class="flex-1 text-center md:text-left">
            <h3 class="font-bold text-emerald-900 text-xl mb-1">{{ $countdownLabel }}</h3>
            <p class="text-emerald-700 text-sm">{{ $countdownDescription }}</p>
        </div>

        <!-- Countdown Timer -->
        <div class="flex-shrink-0 bg-white bg-opacity-70 rounded-xl p-4 border-2 border-emerald-300 shadow-md"
             x-data="countdown('{{ $endDate->format('Y-m-d H:i:s') }}')"
             x-init="init()">
            <p class="text-xs font-bold text-emerald-900 uppercase tracking-wider mb-2 text-center">Berakhir Dalam:</p>
            <div class="flex gap-3 justify-center">
                <div class="text-center">
                    <div class="bg-emerald-600 rounded-lg px-3 py-2 min-w-[60px]">
                        <span class="block text-2xl font-bold text-white" x-text="days">00</span>
                    </div>
                    <span class="text-[10px] uppercase text-emerald-800 font-semibold mt-1 block">Hari</span>
                </div>
                <span class="text-3xl font-bold text-emerald-600 self-center">:</span>
                <div class="text-center">
                    <div class="bg-emerald-600 rounded-lg px-3 py-2 min-w-[60px]">
                        <span class="block text-2xl font-bold text-white" x-text="hours">00</span>
                    </div>
                    <span class="text-[10px] uppercase text-emerald-800 font-semibold mt-1 block">Jam</span>
                </div>
                <span class="text-3xl font-bold text-emerald-600 self-center">:</span>
                <div class="text-center">
                    <div class="bg-emerald-600 rounded-lg px-3 py-2 min-w-[60px]">
                        <span class="block text-2xl font-bold text-white" x-text="minutes">00</span>
                    </div>
                    <span class="text-[10px] uppercase text-emerald-800 font-semibold mt-1 block">Menit</span>
                </div>
                <span class="text-3xl font-bold text-emerald-600 self-center">:</span>
                <div class="text-center">
                    <div class="bg-emerald-600 rounded-lg px-3 py-2 min-w-[60px]">
                        <span class="block text-2xl font-bold text-white" x-text="seconds">00</span>
                    </div>
                    <span class="text-[10px] uppercase text-emerald-800 font-semibold mt-1 block">Detik</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
