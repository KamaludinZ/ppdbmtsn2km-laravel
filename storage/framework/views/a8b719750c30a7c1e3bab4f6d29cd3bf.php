<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Pendaftaran <?php echo e($appAcronym); ?></h1>
                            <p class="text-lg text-gray-600"><?php echo e(\App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang')); ?> - Tahun Pelajaran <?php echo e(\App\Models\Setting::get('tahun_pelajaran', '2025/2026')); ?></p>        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <!-- Step 1: Pilih Jalur -->
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 <?php echo e($currentStep >= 1 ? 'bg-emerald-600' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center text-white font-bold z-10">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > 1): ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            <?php else: ?>
                                1
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-center <?php echo e($currentStep >= 1 ? 'text-emerald-600' : 'text-gray-500'); ?>">Pilih Jalur</span>
                    </div>
                </div>

                <!-- Step 2: Input Nilai -->
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 <?php echo e($currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center text-white font-bold z-10">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > 2): ?>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            <?php else: ?>
                                2
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-center <?php echo e($currentStep >= 2 ? 'text-emerald-600' : 'text-gray-500'); ?>">Input Nilai</span>
                    </div>
                </div>

                <!-- Step 3: Data Diri (sebelumnya Step 4) -->
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 <?php echo e($currentStep >= 3 ? 'bg-emerald-600' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center text-white font-bold z-10">
                            3
                        </div>
                        <span class="mt-2 text-xs sm:text-sm font-medium text-center <?php echo e($currentStep >= 3 ? 'text-emerald-600' : 'text-gray-500'); ?>">Data Diri</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(session()->has('error')): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-700"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form wire:submit.prevent="<?php echo e($currentStep == 4 ? 'register' : 'nextStep'); ?>">
                <!-- Step 1: Pilih Jalur -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 1): ?>
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Pilih Jalur Pendaftaran</h2>
                        <p class="text-gray-600 mb-6">Pilih jalur pendaftaran yang sesuai dengan kualifikasi Anda</p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalurPendaftaran->count() > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php
                                        $colorClasses = [
                                            'blue' => ['border' => 'border-blue-300', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'ring' => 'ring-blue-500'],
                                            'emerald' => ['border' => 'border-emerald-300', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-500'],
                                            'purple' => ['border' => 'border-purple-300', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'ring' => 'ring-purple-500'],
                                            'indigo' => ['border' => 'border-indigo-300', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'ring' => 'ring-indigo-500'],
                                            'orange' => ['border' => 'border-orange-300', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'ring' => 'ring-orange-500'],
                                            'teal' => ['border' => 'border-teal-300', 'bg' => 'bg-teal-50', 'text' => 'text-teal-600', 'ring' => 'ring-teal-500'],
                                        ];
                                        $colors = $colorClasses[$jalur->warna] ?? $colorClasses['blue'];
                                    ?>

                                    <div
                                        wire:click.debounce.100ms="selectJalur(<?php echo e($jalur->id); ?>)"
                                        class="relative border-2 <?php echo e($jalur_pendaftaran_id == $jalur->id ? $colors['border'] . ' ' . $colors['bg'] . ' ring-2 ' . $colors['ring'] : 'border-gray-200 hover:border-gray-300'); ?> rounded-xl p-6 cursor-pointer transition-all hover:shadow-lg"
                                    >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur_pendaftaran_id == $jalur->id): ?>
                                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <h3 class="font-bold text-lg text-gray-900 mb-2"><?php echo e($jalur->nama); ?></h3>
                                        <p class="text-sm text-gray-600 mb-4"><?php echo e($jalur->deskripsi); ?></p>

                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Min: <?php echo e(number_format($jalur->nilai_minimum, 0)); ?>

                                            </span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur->kuota > 0): ?>
                                                <?php
                                                    $sisaKuota = $jalur->getSisaKuota();
                                                    $kuotaTersedia = $jalur->isKuotaTersedia();
                                                ?>

                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($kuotaTersedia ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'); ?>">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Kuota: <?php echo e($sisaKuota); ?>/<?php echo e($jalur->kuota); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jalur_pendaftaran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-gray-600">Belum ada jalur pendaftaran yang aktif saat ini</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Step 2: Nilai Rapor -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 2): ?>
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Rapor</h2>
                        <p class="text-gray-600 mb-6">Masukkan nilai rapor Anda untuk 3 mata pelajaran dari 3 semester</p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedJalur): ?>
                            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                <p class="text-sm text-blue-700">
                                    <strong>Jalur: <?php echo e($selectedJalur->nama); ?></strong><br>
                                    Nilai minimum yang diperlukan: <strong><?php echo e(number_format($selectedJalur->nilai_minimum, 0)); ?></strong>
                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Kelas 5 Semester 1 -->
                        <div class="mb-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Kelas 5 Semester 1
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa Indonesia <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_b_indo_5_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_5_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_matematika_5_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_5_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IPA <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_ipa_5_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_5_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kelas 5 Semester 2 -->
                        <div class="mb-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Kelas 5 Semester 2
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa Indonesia <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_b_indo_5_2" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_5_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_matematika_5_2" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_5_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IPA <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_ipa_5_2" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_5_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kelas 6 Semester 1 -->
                        <div class="mb-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Kelas 6 Semester 1
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa Indonesia <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_b_indo_6_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_6_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_matematika_6_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_6_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">IPA <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.live="nilai_ipa_6_1" step="0.01" min="0" max="100" oninput="if(this.value.length > 3) this.value = this.value.slice(0,3); if(parseFloat(this.value) > 100) this.value = 100;" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0-100" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_6_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Perhitungan Nilai -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-bold text-lg text-blue-800 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Hasil Perhitungan Nilai
                            </h3>

                            <!-- Rata-rata per Mata Pelajaran -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                    <div class="text-sm text-blue-600 font-medium">Bahasa Indonesia</div>
                                    <div class="text-lg font-bold text-gray-900"><?php echo e(number_format($this->getAverageForSubject('b_indo'), 2)); ?></div>
                                    <div class="text-xs text-gray-500">Rata-rata dari 3 semester</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                    <div class="text-sm text-blue-600 font-medium">Matematika</div>
                                    <div class="text-lg font-bold text-gray-900"><?php echo e(number_format($this->getAverageForSubject('matematika'), 2)); ?></div>
                                    <div class="text-xs text-gray-500">Rata-rata dari 3 semester</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                    <div class="text-sm text-blue-600 font-medium">IPA</div>
                                    <div class="text-lg font-bold text-gray-900"><?php echo e(number_format($this->getAverageForSubject('ipa'), 2)); ?></div>
                                    <div class="text-xs text-gray-500">Rata-rata dari 3 semester</div>
                                </div>
                            </div>

                            <!-- Rata-rata Keseluruhan -->
                            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg p-4 text-white">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm opacity-90">Rata-rata Keseluruhan</div>
                                        <div class="text-2xl font-bold"><?php echo e(number_format($this->getOverallAverage(), 2)); ?></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm opacity-90">Status Kelulusan</div>
                                        <div class="text-lg font-bold">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->getOverallAverage() >= $selectedJalur->nilai_minimum): ?>
                                                <span class="text-green-200">Memenuhi Syarat ✓</span>
                                            <?php else: ?>
                                                <span class="text-yellow-200">Belum Memenuhi Syarat ⚠</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Rapor -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-900 mb-2">Upload Scan Rapor <span class="text-red-500">*</span></label>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$file_rapor): ?>
                                <!-- Area upload jika belum ada file -->
                                <div class="flex items-center justify-center w-full">
                                    <label class="flex flex-col w-full border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 transition-all duration-200 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center pt-10 pb-10">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold text-emerald-600">Klik untuk upload</span> atau drag and drop</p>
                                            <p class="text-xs text-gray-500">PDF (MAX. 2MB)</p>
                                        </div>
                                        <input type="file" wire:model="file_rapor" class="hidden" accept=".pdf" required>
                                    </label>
                                </div>
                            <?php else: ?>
                                <!-- Tampilan ketika file sudah diupload -->
                                <div class="p-5 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <h3 class="text-sm font-medium text-green-800">File Berhasil Diupload!</h3>
                                            <p class="text-sm text-green-700 mt-1 break-words"><?php echo e($file_rapor->getClientOriginalName()); ?></p>
                                            <div class="mt-2 flex items-center text-xs text-green-600">
                                                <span><?php echo e(number_format($file_rapor->getSize() / 1024, 2)); ?> KB</span>
                                                <span class="mx-2">•</span>
                                                <span>PDF</span>
                                                <span class="mx-2">•</span>
                                                <span><?php echo e(now()->format('d M Y H:i')); ?></span>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            wire:click="resetFileRapor"
                                            class="ml-4 text-gray-400 hover:text-gray-600"
                                            title="Hapus file"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-green-200">
                                        <div class="flex items-center text-xs text-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>File siap disimpan dan telah diverifikasi</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['file_rapor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-red-600"><?php echo e($message); ?></span>
                                    </div>
                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Petunjuk Upload -->
                            <div class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-blue-800">
                                            <span class="font-semibold">Petunjuk Upload:</span> File harus dalam format PDF dan berisi scan/hasil foto rapor asli dari kelas 5 semester 1, kelas 5 semester 2, dan kelas 6 semester 1. Pastikan nilai terlihat jelas dan terbaca.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Step 3: Data Pribadi -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 3): ?>
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Lengkapi Data Pendaftaran</h2>
                        <p class="text-gray-600 mb-6">Isi data diri dan buat akun pendaftar Anda</p>

                        <!-- Data Pribadi -->
                        <div class="mb-8">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Data Pribadi Calon Siswa
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nisn" maxlength="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="10 digit">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama_lengkap" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="tempat_lahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="tanggal_lahir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select wire:model="jenis_kelamin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="">Pilih</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                                    <select wire:model="agama" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['agama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea wire:model="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. WhatsApp Orang Tua/Wali <span class="text-red-500">*</span></label>
                                    <input
                                        type="text"
                                        wire:model="telepon_ortu"
                                        id="telepon_ortu"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="Contoh: 081234567890"
                                        oninput="formatPhoneNumber(this)"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">Format otomatis: 0xxx akan menjadi 62xxx</p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['telepon_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- Jenis Sekolah Asal -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Sekolah Asal <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" wire:model.live="jenis_sekolah_asal" value="madrasah" class="mr-2 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm">Madrasah (MI/MTs)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" wire:model.live="jenis_sekolah_asal" value="umum" class="mr-2 text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-sm">Sekolah Umum (SD/SMP)</span>
                                        </label>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jenis_sekolah_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- NSM (untuk Madrasah) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jenis_sekolah_asal === 'madrasah'): ?>
                                <div
                                    x-data="{
                                        nomorSekolah: <?php if ((object) ('nsm_sekolah_asal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('nsm_sekolah_asal'->value()); ?>')<?php echo e('nsm_sekolah_asal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('nsm_sekolah_asal'); ?>')<?php endif; ?>.live,
                                        namaSekolah: <?php if ((object) ('asal_sekolah') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('asal_sekolah'->value()); ?>')<?php echo e('asal_sekolah'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('asal_sekolah'); ?>')<?php endif; ?>.live,
                                        loading: false,
                                        autoFilled: false,
                                        async cekSekolah() {
                                            if (this.nomorSekolah && this.nomorSekolah.length === 12) {
                                                this.loading = true;
                                                this.autoFilled = false;
                                                try {
                                                    const response = await fetch(`/api/cek-sekolah?jenis_sekolah_asal=madrasah&nomor_sekolah=${this.nomorSekolah}`);
                                                    const data = await response.json();
                                                    if (data.found) {
                                                        this.namaSekolah = data.nama_sekolah;
                                                        this.autoFilled = true;
                                                    }
                                                } catch (error) {
                                                    console.error('Error:', error);
                                                } finally {
                                                    this.loading = false;
                                                }
                                            }
                                        }
                                    }"
                                >
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Statistik Madrasah (NSM) <span class="text-red-500">*</span>
                                        <span class="text-xs text-gray-500">(12 digit)</span>
                                    </label>
                                    <input
                                        type="text"
                                        x-model="nomorSekolah"
                                        @input.debounce.500ms="cekSekolah()"
                                        maxlength="12"
                                        placeholder="Contoh: 121135740001"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    >
                                    <div class="mt-1 flex items-center gap-2">
                                        <span x-show="loading" class="text-xs text-gray-500">Mencari sekolah...</span>
                                        <span x-show="autoFilled && !loading" class="text-xs text-green-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Nama sekolah terisi otomatis
                                        </span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nsm_sekolah_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- NPSN (untuk Sekolah Umum) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jenis_sekolah_asal === 'umum'): ?>
                                <div
                                    x-data="{
                                        nomorSekolah: <?php if ((object) ('npsn_sekolah_asal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('npsn_sekolah_asal'->value()); ?>')<?php echo e('npsn_sekolah_asal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('npsn_sekolah_asal'); ?>')<?php endif; ?>.live,
                                        namaSekolah: <?php if ((object) ('asal_sekolah') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('asal_sekolah'->value()); ?>')<?php echo e('asal_sekolah'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('asal_sekolah'); ?>')<?php endif; ?>.live,
                                        loading: false,
                                        autoFilled: false,
                                        async cekSekolah() {
                                            if (this.nomorSekolah && this.nomorSekolah.length === 8) {
                                                this.loading = true;
                                                this.autoFilled = false;
                                                try {
                                                    const response = await fetch(`/api/cek-sekolah?jenis_sekolah_asal=umum&nomor_sekolah=${this.nomorSekolah}`);
                                                    const data = await response.json();
                                                    if (data.found) {
                                                        this.namaSekolah = data.nama_sekolah;
                                                        this.autoFilled = true;
                                                    }
                                                } catch (error) {
                                                    console.error('Error:', error);
                                                } finally {
                                                    this.loading = false;
                                                }
                                            }
                                        }
                                    }"
                                >
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nomor Pokok Sekolah Nasional (NPSN) <span class="text-red-500">*</span>
                                        <span class="text-xs text-gray-500">(8 digit)</span>
                                    </label>
                                    <input
                                        type="text"
                                        x-model="nomorSekolah"
                                        @input.debounce.500ms="cekSekolah()"
                                        maxlength="8"
                                        placeholder="Contoh: 20536326"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    >
                                    <div class="mt-1 flex items-center gap-2">
                                        <span x-show="loading" class="text-xs text-gray-500">Mencari sekolah...</span>
                                        <span x-show="autoFilled && !loading" class="text-xs text-green-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Nama sekolah terisi otomatis
                                        </span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['npsn_sekolah_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Nama Sekolah -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jenis_sekolah_asal): ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah Asal <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="asal_sekolah" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Nama lengkap sekolah">
                                    <p class="mt-1 text-xs text-gray-500">Nama sekolah akan terisi otomatis jika NSM/NPSN sudah terdaftar di sistem</p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Akun -->
                        <div class="mb-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                Buat Akun Pendaftar
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input
                                        type="email"
                                        wire:model="email"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                        placeholder="contoh@email.com"
                                        required
                                    >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <div x-data="{ showPassword: false }" class="relative">
                                        <input
                                            :type="showPassword ? 'text' : 'password'"
                                            wire:model="password"
                                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="••••••••"
                                            required
                                        >
                                        <button
                                            type="button"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                            @click="showPassword = !showPassword"
                                        >
                                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                    <div x-data="{ showPasswordConfirmation: false }" class="relative">
                                        <input
                                            :type="showPasswordConfirmation ? 'text' : 'password'"
                                            wire:model="password_confirmation"
                                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="••••••••"
                                            required
                                        >
                                        <button
                                            type="button"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                                        >
                                            <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-sm text-red-600"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- Peringatan penting -->
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-blue-800">
                                            <span class="font-semibold">Penting:</span> Simpan email dan password Anda untuk login kembali dan melengkapi formulir pendaftaran. Anda akan menerima nomor pendaftaran setelah berhasil mendaftar.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Navigation Buttons -->
                <div class="bg-gray-50 px-8 py-4 flex justify-between">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > 1): ?>
                        <button type="button" wire:click="previousStep" class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </button>
                    <?php else: ?>
                        <a href="/" class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep < 3): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep == 1): ?>
                            <button
                                type="button"
                                wire:click="nextStep"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="
                                    $wire.jalur_pendaftaran_id
                                        ? 'bg-emerald-600 hover:bg-emerald-700'
                                        : 'bg-gray-400 cursor-not-allowed'
                                "
                                :disabled="!$wire.jalur_pendaftaran_id"
                            >
                                Lanjutkan
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        <?php elseif($currentStep == 2): ?>
                            <button
                                type="button"
                                wire:click="nextStep"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="
                                    $wire.isValid && $wire.file_rapor
                                        ? 'bg-emerald-600 hover:bg-emerald-700'
                                        : 'bg-gray-400 cursor-not-allowed'
                                "
                                :disabled="!$wire.isValid || !$wire.file_rapor"
                            >
                                Lanjutkan
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php elseif($currentStep == 3): ?>
                        <button
                            type="button"
                            wire:click="confirmSubmit()"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white transition shadow-lg"
                            :class="
                                $wire.nisn &&
                                $wire.nama_lengkap &&
                                $wire.tempat_lahir &&
                                $wire.tanggal_lahir &&
                                $wire.jenis_kelamin &&
                                $wire.agama &&
                                $wire.alamat &&
                                $wire.telepon_ortu &&
                                $wire.email &&
                                $wire.password &&
                                $wire.password_confirmation &&
                                $wire.jenis_sekolah_asal &&
                                $wire.asal_sekolah &&
                                ($wire.jenis_sekolah_asal === 'madrasah' ? $wire.nsm_sekolah_asal : $wire.npsn_sekolah_asal) &&
                                ($wire.jenis_sekolah_asal !== 'madrasah' || $wire.nsm_sekolah_asal) &&
                                ($wire.jenis_sekolah_asal !== 'umum' || $wire.npsn_sekolah_asal)
                                    ? 'bg-emerald-600 hover:bg-emerald-700'
                                    : 'bg-gray-400 cursor-not-allowed'
                            "
                            :disabled="!(
                                $wire.nisn &&
                                $wire.nama_lengkap &&
                                $wire.tempat_lahir &&
                                $wire.tanggal_lahir &&
                                $wire.jenis_kelamin &&
                                $wire.agama &&
                                $wire.alamat &&
                                $wire.telepon_ortu &&
                                $wire.email &&
                                $wire.password &&
                                $wire.password_confirmation &&
                                $wire.jenis_sekolah_asal &&
                                $wire.asal_sekolah &&
                                ($wire.jenis_sekolah_asal === 'madrasah' ? $wire.nsm_sekolah_asal : $wire.npsn_sekolah_asal) &&
                                ($wire.jenis_sekolah_asal !== 'madrasah' || $wire.nsm_sekolah_asal) &&
                                ($wire.jenis_sekolah_asal !== 'umum' || $wire.npsn_sekolah_asal)
                            )"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kirim
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Confirmation Modal -->
                    <div x-data="{ show: <?php if ((object) ('showConfirmationModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showConfirmationModal'->value()); ?>')<?php echo e('showConfirmationModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showConfirmationModal'); ?>')<?php endif; ?> }"
                         x-show="show"
                         x-cloak
                         class="fixed inset-0 z-[9999] overflow-y-auto"
                         aria-labelledby="modal-title"
                         role="dialog"
                         aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="show"
                                 class="fixed inset-0 bg-black/10 backdrop-blur-sm z-[9998]"
                                 aria-hidden="true"
                                 @click="$wire.cancelSubmit()">
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div x-show="show"
                                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-visible shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[9999]"
                                 style="position: relative; margin: auto; margin-top: 2rem; margin-bottom: 2rem;"
                                 @click.outside="$wire.cancelSubmit()">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Konfirmasi Pendaftaran
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Data pendaftaran Anda sudah sesuai. Setelah pembuatan akun ini, silakan lanjutkan mengisi formulir pendaftaran dengan login ke akun Anda.
                                                </p>
                                                <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <p class="text-sm text-blue-800">
                                                        <span class="font-semibold">Info yang akan dikirim:</span> Email dan password akan dikirimkan beserta link untuk login dan melengkapi formulir pendaftaran.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button
                                        type="button"
                                        wire:click="register"
                                        wire:loading.attr="disabled"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span wire:loading.remove wire:target="register">Ya, Kirim Pendaftaran</span>
                                        <span wire:loading wire:target="register" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses...
                                        </span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="$wire.cancelSubmit()"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm text-blue-700">
                    <p class="font-bold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Pastikan semua data yang Anda input adalah benar dan valid</li>
                        <li>Nomor pendaftaran akan digenerate otomatis setelah Anda berhasil mendaftar</li>
                        <li>Anda dapat login menggunakan email dan password yang didaftarkan</li>
                    </ul>
                </div>
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
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\auth\register.blade.php ENDPATH**/ ?>