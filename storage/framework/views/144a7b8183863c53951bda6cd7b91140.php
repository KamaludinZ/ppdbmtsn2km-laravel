<?php
    $registrationPhase = \App\Models\Setting::get('registration_phase', 'belum_dibuka');
    $registrationPhaseLabel = \App\Models\Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
    $registrationPhaseDescription = \App\Models\Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');
    $startDate = \App\Models\Setting::get('registration_start_date');

    $phaseColors = [
        'belum_dibuka' => ['bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'text' => 'text-gray-800', 'icon' => 'text-gray-600'],
        'dibuka' => ['bg' => 'bg-green-100', 'border' => 'border-green-300', 'text' => 'text-green-800', 'icon' => 'text-green-600'],
        'proses' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-300', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
        'verifikasi' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-300', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
        'tes_seleksi' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-300', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-600'],
        'pengumuman' => ['bg' => 'bg-purple-100', 'border' => 'border-purple-300', 'text' => 'text-purple-800', 'icon' => 'text-purple-600'],
        'daftar_ulang' => ['bg' => 'bg-emerald-100', 'border' => 'border-emerald-300', 'text' => 'text-emerald-800', 'icon' => 'text-emerald-600'],
        'selesai' => ['bg' => 'bg-red-100', 'border' => 'border-red-300', 'text' => 'text-red-800', 'icon' => 'text-red-600'],
    ];
    $colors = $phaseColors[$registrationPhase] ?? $phaseColors['belum_dibuka'];
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registrationPhase !== 'dibuka'): ?>
<section class="bg-white border-b-2 border-emerald-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="<?php echo e($colors['bg']); ?> <?php echo e($colors['border']); ?> border-2 rounded-xl p-6 shadow-md">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 <?php echo e($colors['bg']); ?> rounded-full flex items-center justify-center border-2 <?php echo e($colors['border']); ?>">
                        <svg class="w-6 h-6 <?php echo e($colors['icon']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="font-bold <?php echo e($colors['text']); ?> text-lg mb-1"><?php echo e($registrationPhaseLabel); ?></h3>
                    <p class="<?php echo e($colors['text']); ?> text-sm opacity-90"><?php echo e($registrationPhaseDescription); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($registrationPhase === 'belum_dibuka' && $startDate): ?>
                <div class="flex-shrink-0 bg-white bg-opacity-50 rounded-lg p-3 border <?php echo e($colors['border']); ?>" x-data="countdown('<?php echo e($startDate->format('Y-m-d H:i:s')); ?>')" x-init="init()">
                    <p class="text-xs font-bold <?php echo e($colors['text']); ?> uppercase tracking-wider mb-1 text-center">Pendaftaran Dibuka Dalam:</p>
                    <div class="flex gap-2 justify-center">
                        <div class="text-center">
                            <span class="block text-xl font-bold <?php echo e($colors['text']); ?>" x-text="days">00</span>
                            <span class="text-[10px] uppercase <?php echo e($colors['text']); ?>">Hari</span>
                        </div>
                        <span class="text-xl font-bold <?php echo e($colors['text']); ?>">:</span>
                        <div class="text-center">
                            <span class="block text-xl font-bold <?php echo e($colors['text']); ?>" x-text="hours">00</span>
                            <span class="text-[10px] uppercase <?php echo e($colors['text']); ?>">Jam</span>
                        </div>
                        <span class="text-xl font-bold <?php echo e($colors['text']); ?>">:</span>
                        <div class="text-center">
                            <span class="block text-xl font-bold <?php echo e($colors['text']); ?>" x-text="minutes">00</span>
                            <span class="text-[10px] uppercase <?php echo e($colors['text']); ?>">Menit</span>
                        </div>
                        <span class="text-xl font-bold <?php echo e($colors['text']); ?>">:</span>
                        <div class="text-center">
                            <span class="block text-xl font-bold <?php echo e($colors['text']); ?>" x-text="seconds">00</span>
                            <span class="text-[10px] uppercase <?php echo e($colors['text']); ?>">Detik</span>
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\components\registration-phase-banner.blade.php ENDPATH**/ ?>