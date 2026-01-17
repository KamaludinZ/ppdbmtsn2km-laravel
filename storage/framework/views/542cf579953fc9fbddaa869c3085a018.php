<?php if (isset($component)) { $__componentOriginal8c0e86a062c1c5bb6d0e151b7076f3fd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c0e86a062c1c5bb6d0e151b7076f3fd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.public','data' => ['title' => $jalur->nama . ' - ' . $appAcronym . ' MTsN 2 Kota Malang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.public'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jalur->nama . ' - ' . $appAcronym . ' MTsN 2 Kota Malang')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <?php
        $colorClasses = [
            'blue' => [
                'bg' => 'bg-blue-600',
                'bgLight' => 'bg-blue-50',
                'bgGradient' => 'from-blue-500 to-blue-600',
                'border' => 'border-blue-200',
                'text' => 'text-blue-600',
                'textDark' => 'text-blue-800',
                'button' => 'bg-blue-600 hover:bg-blue-700',
            ],
            'emerald' => [
                'bg' => 'bg-emerald-600',
                'bgLight' => 'bg-emerald-50',
                'bgGradient' => 'from-emerald-500 to-emerald-600',
                'border' => 'border-emerald-200',
                'text' => 'text-emerald-600',
                'textDark' => 'text-emerald-800',
                'button' => 'bg-emerald-600 hover:bg-emerald-700',
            ],
            'purple' => [
                'bg' => 'bg-purple-600',
                'bgLight' => 'bg-purple-50',
                'bgGradient' => 'from-purple-500 to-purple-600',
                'border' => 'border-purple-200',
                'text' => 'text-purple-600',
                'textDark' => 'text-purple-800',
                'button' => 'bg-purple-600 hover:bg-purple-700',
            ],
            'indigo' => [
                'bg' => 'bg-indigo-600',
                'bgLight' => 'bg-indigo-50',
                'bgGradient' => 'from-indigo-500 to-indigo-600',
                'border' => 'border-indigo-200',
                'text' => 'text-indigo-600',
                'textDark' => 'text-indigo-800',
                'button' => 'bg-indigo-600 hover:bg-indigo-700',
            ],
            'orange' => [
                'bg' => 'bg-orange-600',
                'bgLight' => 'bg-orange-50',
                'bgGradient' => 'from-orange-500 to-orange-600',
                'border' => 'border-orange-200',
                'text' => 'text-orange-600',
                'textDark' => 'text-orange-800',
                'button' => 'bg-orange-600 hover:bg-orange-700',
            ],
            'teal' => [
                'bg' => 'bg-teal-600',
                'bgLight' => 'bg-teal-50',
                'bgGradient' => 'from-teal-500 to-teal-600',
                'border' => 'border-teal-200',
                'text' => 'text-teal-600',
                'textDark' => 'text-teal-800',
                'button' => 'bg-teal-600 hover:bg-teal-700',
            ],
        ];
        $colors = $colorClasses[$jalur->warna] ?? $colorClasses['blue'];

        $phaseColors = [
            'belum_dibuka' => ['bg' => 'bg-gray-100', 'border' => 'border-gray-300', 'text' => 'text-gray-800', 'icon' => 'text-gray-600'],
            'dibuka' => ['bg' => 'bg-green-100', 'border' => 'border-green-300', 'text' => 'text-green-800', 'icon' => 'text-green-600'],
            'verifikasi' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-300', 'text' => 'text-blue-800', 'icon' => 'text-blue-600'],
            'tes_seleksi' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-300', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-600'],
            'pengumuman' => ['bg' => 'bg-purple-100', 'border' => 'border-purple-300', 'text' => 'text-purple-800', 'icon' => 'text-purple-600'],
            'daftar_ulang' => ['bg' => 'bg-emerald-100', 'border' => 'border-emerald-300', 'text' => 'text-emerald-800', 'icon' => 'text-emerald-600'],
            'selesai' => ['bg' => 'bg-red-100', 'border' => 'border-red-300', 'text' => 'text-red-800', 'icon' => 'text-red-600'],
        ];
        $phaseColor = $phaseColors[$registrationPhase] ?? $phaseColors['belum_dibuka'];
    ?>

    <?php if (isset($component)) { $__componentOriginal45e45946f88a0fab05db0e63b1d2c266 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal45e45946f88a0fab05db0e63b1d2c266 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.registration-phase-banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('registration-phase-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal45e45946f88a0fab05db0e63b1d2c266)): ?>
<?php $attributes = $__attributesOriginal45e45946f88a0fab05db0e63b1d2c266; ?>
<?php unset($__attributesOriginal45e45946f88a0fab05db0e63b1d2c266); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal45e45946f88a0fab05db0e63b1d2c266)): ?>
<?php $component = $__componentOriginal45e45946f88a0fab05db0e63b1d2c266; ?>
<?php unset($__componentOriginal45e45946f88a0fab05db0e63b1d2c266); ?>
<?php endif; ?>

    <!-- Breadcrumb -->
    <section class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?php echo e($jalur->nama); ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br <?php echo e($colors['bgGradient']); ?> text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php echo e($jalur->nama); ?></h1>

                <!-- Badges -->
                <div class="flex flex-wrap justify-center gap-4 mb-8 mt-8">
                    <div class="bg-white rounded-full px-6 py-3 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold <?php echo e($colors['textDark']); ?>">Nilai Minimum: <?php echo e(number_format($jalur->nilai_minimum, 0)); ?></span>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur->kuota > 0): ?>
                        <div class="bg-white rounded-full px-6 py-3 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="font-semibold <?php echo e($colors['textDark']); ?>">Kuota: <?php echo e($jalur->kuota); ?> Siswa</span>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Informasi Jalur -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 <?php echo e($colors['text']); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi <?php echo e($jalur->nama); ?>

                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            <!-- Deskripsi Singkat -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur->deskripsi): ?>
                                <div class="bg-<?php echo e($colors['bgLight']); ?> rounded-lg p-6 mb-6 border-l-4 <?php echo e($colors['border']); ?>">
                                    <p class="text-lg font-medium <?php echo e($colors['textDark']); ?> leading-relaxed">
                                        <?php echo e($jalur->deskripsi); ?>

                                    </p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Konten HTML atau Deskripsi Lengkap -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur->konten_html): ?>
                                <?php echo $jalur->konten_html; ?>

                            <?php elseif($jalur->deskripsi_lengkap): ?>
                                <p><?php echo e($jalur->deskripsi_lengkap); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Persyaratan Umum -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($persyaratanUmum) && $persyaratanUmum->count() > 0): ?>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 <?php echo e($colors['text']); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Persyaratan Umum
                        </h2>
                        <p class="text-gray-600 mb-6">Persyaratan yang berlaku untuk semua jalur pendaftaran <?php echo e($appAcronym); ?> MTsN 2 Kota Malang:</p>
                        <ul class="space-y-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $persyaratanUmum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $syarat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 <?php echo e($colors['bg']); ?> rounded-full flex items-center justify-center mr-4 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-gray-800 font-medium"><?php echo e($syarat->persyaratan); ?></span>
                            </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Persyaratan Khusus -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jalur->persyaratan && is_array($jalur->persyaratan) && count($jalur->persyaratan) > 0): ?>
                    <div class="bg-white rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 <?php echo e($colors['text']); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Persyaratan Khusus
                        </h2>
                        <p class="text-gray-600 mb-6">Persyaratan tambahan khusus untuk <?php echo e($jalur->nama); ?>:</p>
                        <ul class="space-y-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalur->persyaratan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $syarat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 <?php echo e($colors['bg']); ?> rounded-full flex items-center justify-center mr-4 mt-0.5">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <span class="text-gray-800 font-medium"><?php echo e($syarat); ?></span>
                            </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Nilai Rapor Info -->
                    <div class="bg-gradient-to-br <?php echo e($colors['bgLight']); ?> rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <h2 class="text-2xl font-bold <?php echo e($colors['textDark']); ?> mb-6 flex items-center">
                            <svg class="w-6 h-6 <?php echo e($colors['text']); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Ketentuan Nilai Rapor
                        </h2>
                        <div class="space-y-4">
                            <div class="bg-white rounded-xl p-6 border <?php echo e($colors['border']); ?>">
                                <h3 class="font-bold <?php echo e($colors['textDark']); ?> mb-3">Nilai Rata-Rata Minimum: <?php echo e(number_format($jalur->nilai_minimum, 0)); ?></h3>
                                <p class="text-gray-700 mb-4">
                                    Nilai rata-rata dihitung dari 3 mata pelajaran berikut untuk semester yang ditentukan:
                                </p>
                                <ul class="space-y-2 mb-4">
                                    <li class="flex items-center <?php echo e($colors['text']); ?> font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Bahasa Indonesia
                                    </li>
                                    <li class="flex items-center <?php echo e($colors['text']); ?> font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Matematika
                                    </li>
                                    <li class="flex items-center <?php echo e($colors['text']); ?> font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        IPA
                                    </li>
                                </ul>
                                <p class="text-gray-700">
                                    <strong>Periode rapor:</strong> Kelas 5 Semester 1 & 2, dan Kelas 6 Semester 1 (total 9 nilai)
                                </p>
                            </div>
                            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-yellow-800 mb-1">Penting!</h4>
                                        <p class="text-sm text-yellow-700">
                                            Anda wajib mengunggah scan/foto rapor asli saat mendaftar. Pastikan scan jelas dan terbaca.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal & Timeline Kegiatan -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?>">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 <?php echo e($colors['text']); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal & Timeline Kegiatan
                        </h2>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($timelineJalur) && $timelineJalur->count() > 0): ?>
                            <div class="space-y-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $timelineJalur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="border-l-4 <?php echo e($colors['border']); ?> pl-6 py-4 hover:bg-gray-50 rounded-r-lg transition">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold <?php echo e($colors['textDark']); ?> mb-2"><?php echo e($timeline->judul_kegiatan); ?></h3>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline->deskripsi): ?>
                                                <p class="text-gray-600 mb-3"><?php echo e($timeline->deskripsi); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-medium">
                                                        <?php echo e(\Carbon\Carbon::parse($timeline->tanggal_mulai)->format('d M Y')); ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline->tanggal_selesai && $timeline->tanggal_selesai != $timeline->tanggal_mulai): ?>
                                                            - <?php echo e(\Carbon\Carbon::parse($timeline->tanggal_selesai)->format('d M Y')); ?>

                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </span>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline->waktu_mulai): ?>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-medium">
                                                        <?php echo e(\Carbon\Carbon::parse($timeline->waktu_mulai)->format('H:i')); ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline->waktu_selesai): ?>
                                                            - <?php echo e(\Carbon\Carbon::parse($timeline->waktu_selesai)->format('H:i')); ?>

                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </span>
                                                </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($timeline->tempat): ?>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span class="font-medium"><?php echo e($timeline->tempat); ?></span>
                                                </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full <?php echo e($colors['bgLight']); ?> mb-4">
                                    <svg class="w-8 h-8 <?php echo e($colors['text']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Timeline Pendaftaran Belum Tersedia</h3>
                                <p class="text-gray-600">Timeline pendaftaran akan segera ditampilkan. Silakan cek kembali nanti.</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- CTA Card -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg border-2 <?php echo e($colors['border']); ?> sticky top-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Tertarik Mendaftar?</h3>
                        <p class="text-gray-600 mb-6">
                            Pastikan Anda memenuhi semua persyaratan sebelum mendaftar melalui jalur ini.
                        </p>
                        <a href="/daftar" class="block w-full text-center <?php echo e($colors['button']); ?> text-white px-6 py-4 rounded-lg font-semibold hover:shadow-xl transition-all duration-200 transform hover:scale-105 mb-4">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Daftar Sekarang
                        </a>
                        <a href="/" class="block w-full text-center border-2 <?php echo e($colors['border']); ?> <?php echo e($colors['text']); ?> px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200">
                            Kembali ke Beranda
                        </a>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-gradient-to-br <?php echo e($colors['bgLight']); ?> rounded-2xl p-6 border-2 <?php echo e($colors['border']); ?>">
                        <h4 class="font-bold <?php echo e($colors['textDark']); ?> mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Penting
                        </h4>
                        <ul class="space-y-2 text-sm <?php echo e($colors['textDark']); ?>">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Pendaftaran hanya dapat dilakukan 1 kali</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Semua data yang diinput harus valid</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Berkas yang tidak sesuai akan ditolak</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Keputusan panitia tidak dapat diganggu gugat</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg border-2 border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Butuh Bantuan?
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Jika ada pertanyaan tentang jalur ini, silakan hubungi panitia.
                        </p>
                        <a href="/kontak-panitia" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm">
                            Hubungi Panitia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Other Jalur Section -->
    <section class="py-16 bg-white border-t-2 border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Jalur Pendaftaran Lainnya</h2>
                <p class="text-lg text-gray-600">
                    Lihat jalur pendaftaran lain yang mungkin sesuai dengan kualifikasi Anda
                </p>
            </div>
            <div class="flex justify-center">
                <a href="/#jalur-pendaftaran" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Lihat Semua Jalur
                </a>
            </div>
        </div>
    </section>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c0e86a062c1c5bb6d0e151b7076f3fd)): ?>
<?php $attributes = $__attributesOriginal8c0e86a062c1c5bb6d0e151b7076f3fd; ?>
<?php unset($__attributesOriginal8c0e86a062c1c5bb6d0e151b7076f3fd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c0e86a062c1c5bb6d0e151b7076f3fd)): ?>
<?php $component = $__componentOriginal8c0e86a062c1c5bb6d0e151b7076f3fd; ?>
<?php unset($__componentOriginal8c0e86a062c1c5bb6d0e151b7076f3fd); ?>
<?php endif; ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\public\jalur-detail.blade.php ENDPATH**/ ?>