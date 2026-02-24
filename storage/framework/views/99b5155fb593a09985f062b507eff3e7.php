<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="description" content="<?php echo e($appFullName); ?> MTsN 2 Kota Malang - Sistem Informasi <?php echo e($appAcronym); ?> Online">
    <meta name="keywords" content="<?php echo e($appAcronym); ?>, MTsN 2 Kota Malang, Pendaftaran Online, Madrasah Tsanawiyah">

    <title><?php echo e($title ?? \App\Models\Setting::get('app_name', 'PMBM MTsN 2 Kota Malang')); ?></title>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($favicon = \App\Models\Setting::get('app_favicon')): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e($favicon); ?>">
    <?php else: ?>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Assets Loading -->
    <?php if (isset($component)) { $__componentOriginalb21dc479da5ffed60a9cd74a19331f44 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb21dc479da5ffed60a9cd74a19331f44 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.asset-loader','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('asset-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb21dc479da5ffed60a9cd74a19331f44)): ?>
<?php $attributes = $__attributesOriginalb21dc479da5ffed60a9cd74a19331f44; ?>
<?php unset($__attributesOriginalb21dc479da5ffed60a9cd74a19331f44); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb21dc479da5ffed60a9cd74a19331f44)): ?>
<?php $component = $__componentOriginalb21dc479da5ffed60a9cd74a19331f44; ?>
<?php unset($__componentOriginalb21dc479da5ffed60a9cd74a19331f44); ?>
<?php endif; ?>
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="w-full px-6 lg:px-12">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <?php $logo = \App\Models\Setting::get('app_logo'); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo && !empty($logo)): ?>
                            <img src="<?php echo e($logo); ?>" alt="Logo" class="w-12 h-12 object-contain">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900"><?php echo e(\App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang')); ?></h1>
                            <p class="text-xs text-gray-600"><?php echo e($appAcronym); ?> Online <?php echo e(\App\Models\Setting::get('tahun_pelajaran', date('Y'))); ?></p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="/" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Beranda
                    </a>
                    <a href="/tentang" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Tentang
                    </a>
                    <a href="/jalur-pendaftaran" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Jalur Pendaftaran
                    </a>
                    <a href="/informasi" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Informasi & Panduan
                    </a>
                    <a href="/jadwal" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Jadwal
                    </a>
                    <a href="/alur-pendaftaran" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Alur Pendaftaran
                    </a>

                    <!-- Dropdown Cek Data -->
                    <div class="relative">
                        <button
                            id="cekDataDropdownBtn"
                            onclick="toggleCekDataDropdown()"
                            class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition inline-flex items-center"
                        >
                            Cek Data
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            id="cekDataDropdown"
                            class="hidden absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 transition-all duration-200"
                        >
                            <div class="py-1">
                                <a href="/cek-pendaftaran" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Cek Pendaftaran
                                    </div>
                                </a>
                                <a href="/cek-nsm-npsn" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Cek NSM/NPSN
                                    </div>
                                </a>
                                <a href="/cek-nisn" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        Cek NISN
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="/kontak-panitia" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Kontak
                    </a>
                    <a href="/pengumuman" class="text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 px-3 py-2 text-sm font-medium rounded-lg transition">
                        Pengumuman
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                        <!-- User Avatar Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                                </div>
                                <span class="hidden lg:block text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPendaftar()): ?>
                                    <a href="/pendaftar/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard
                                    </a>
                                <?php elseif(auth()->user()->isAdmin()): ?>
                                    <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Admin
                                    </a>
                                <?php elseif(auth()->user()->role === 'panitia_pendaftaran'): ?>
                                    <a href="/panitia-pendaftaran/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Panitia
                                    </a>
                                <?php elseif(auth()->user()->role === 'panitia_daftarulang'): ?>
                                    <a href="/panitia-daftarulang/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Panitia
                                    </a>
                                <?php elseif(auth()->user()->role === 'panitia_tes_psikologi'): ?>
                                    <a href="/panitia-psikologi/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Panitia
                                    </a>
                                <?php elseif(auth()->user()->role === 'panitia_seragam'): ?>
                                    <a href="/panitia-seragam/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Dashboard Panitia
                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <hr class="my-1">
                                <form action="/logout" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login Buttons (Guest) -->
                        <a href="/login" class="hidden lg:inline-flex items-center px-4 py-2 border border-emerald-600 text-sm font-medium rounded-lg text-emerald-600 hover:bg-emerald-50 transition">
                            Login Pendaftar
                        </a>
                        <a href="/login/admin" class="hidden lg:inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                            Login Panitia
                        </a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\App\Models\Setting::get('registration_phase') === 'dibuka'): ?>
                        <a href="/daftar" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-sm">
                            Daftar Sekarang
                        </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Mobile menu button -->
                    <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition">
                        <svg id="menuIconOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="menuIconClose" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenuPanel"
             class="hidden lg:hidden border-t border-gray-200 bg-white transition-all duration-200">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Beranda
                </a>
                <a href="/tentang" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Tentang
                </a>
                <a href="/jalur-pendaftaran" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Jalur Pendaftaran
                </a>
                <a href="/informasi" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Informasi & Panduan
                </a>
                <a href="/jadwal" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Jadwal
                </a>
                <a href="/alur-pendaftaran" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Alur Pendaftaran
                </a>

                <!-- Mobile Cek Data Section -->
                <div class="pt-2">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cek Data</p>
                    <a href="/cek-pendaftaran" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Cek Pendaftaran
                        </div>
                    </a>
                    <a href="/cek-nsm-npsn" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cek NSM/NPSN
                        </div>
                    </a>
                    <a href="/cek-nisn" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            Cek NISN
                        </div>
                    </a>
                </div>

                <a href="/kontak-panitia" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Kontak
                </a>
                <a href="/pengumuman" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">
                    Pengumuman
                </a>
            </div>
            <div class="px-4 py-3 border-t border-gray-200 space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <!-- User Profile (Mobile) -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-2">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-semibold">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isPendaftar()): ?>
                            <a href="/pendaftar/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition mb-2">
                                Ke Dashboard
                            </a>
                        <?php elseif(auth()->user()->isAdmin()): ?>
                            <a href="/admin/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                                Ke Dashboard Admin
                            </a>
                        <?php elseif(auth()->user()->role === 'panitia_pendaftaran'): ?>
                            <a href="/panitia-pendaftaran/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                                Ke Dashboard Panitia
                            </a>
                        <?php elseif(auth()->user()->role === 'panitia_daftarulang'): ?>
                            <a href="/panitia-daftarulang/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                                Ke Dashboard Panitia
                            </a>
                        <?php elseif(auth()->user()->role === 'panitia_tes_psikologi'): ?>
                            <a href="/panitia-psikologi/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                                Ke Dashboard Panitia
                            </a>
                        <?php elseif(auth()->user()->role === 'panitia_seragam'): ?>
                            <a href="/panitia-seragam/dashboard" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition mb-2">
                                Ke Dashboard Panitia
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <form action="/logout" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="block w-full px-4 py-2 text-center text-sm font-medium rounded-lg text-red-600 border border-red-600 hover:bg-red-50 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Login Buttons (Guest - Mobile) -->
                    <a href="/login" class="block w-full px-4 py-2 text-center border border-emerald-600 text-sm font-medium rounded-lg text-emerald-600 hover:bg-emerald-50 transition mb-2">
                        Login Pendaftar
                    </a>
                    <a href="/login/admin" class="block w-full px-4 py-2 text-center border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition">
                        Login Panitia
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        <?php echo e($slot); ?>

    </main>

    <!-- Floating Back to Top Button -->
    <div class="fixed bottom-6 left-6 z-50">
        <button
            id="backToTopBtn"
            class="hidden bg-emerald-600 text-white p-3 rounded-full shadow-lg hover:bg-emerald-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
            title="Kembali ke atas"
            aria-label="Kembali ke atas"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 10l7-7m0 0l7 7m-7-7v18">
                </path>
            </svg>
        </button>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="w-full px-6 lg:px-12 py-12">
            <?php
                $kontakPanitia = \App\Models\KontakPanitia::aktif()->ordered()->take(6)->get();
            ?>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <?php $logo = \App\Models\Setting::get('app_logo'); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($logo && !empty($logo)): ?>
                            <img src="<?php echo e($logo); ?>" alt="Logo" class="w-10 h-10 object-contain">
                        <?php else: ?>
                            <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div>
                            <h3 class="text-lg font-bold"><?php echo e(\App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang')); ?></h3>
                            <p class="text-sm text-gray-400"><?php echo e($appAcronym); ?> Online <?php echo e(\App\Models\Setting::get('tahun_pelajaran', date('Y'))); ?></p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        <?php echo e(\App\Models\Setting::get('app_footer_description', 'Madrasah Tsanawiyah Negeri 2 Kota Malang adalah lembaga pendidikan Islam yang berkomitmen untuk menghasilkan generasi yang berakhlak mulia dan berprestasi.')); ?>

                    </p>
                    <div class="flex space-x-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fb = \App\Models\Setting::get('app_social_facebook')): ?>
                            <a href="<?php echo e($fb); ?>" target="_blank" class="text-gray-400 hover:text-[#1877F2] transition shadow-sm hover:scale-110 transform" title="Facebook">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ig = \App\Models\Setting::get('app_social_instagram')): ?>
                            <a href="<?php echo e($ig); ?>" target="_blank" class="text-gray-400 hover:text-[#E4405F] transition shadow-sm hover:scale-110 transform" title="Instagram">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($yt = \App\Models\Setting::get('app_social_youtube')): ?>
                            <a href="<?php echo e($yt); ?>" target="_blank" class="text-gray-400 hover:text-[#FF0000] transition shadow-sm hover:scale-110 transform" title="YouTube">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tk = \App\Models\Setting::get('app_social_tiktok')): ?>
                            <a href="<?php echo e($tk); ?>" target="_blank" class="text-gray-400 hover:text-white transition shadow-sm hover:scale-110 transform" title="TikTok">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-.1-.01-.1 0-1.43.35-2.81 1.02-4.05 1.11-2.1 3.31-3.5 5.63-3.66.15-.02.31-.03.46-.03.01 1.42-.01 2.85 0 4.27-.11.01-.22.02-.33.03-1.1.14-2.12.82-2.63 1.81-.45.83-.53 1.83-.31 2.76.25 1.02.94 1.87 1.84 2.27.88.41 1.89.46 2.83.14 1.02-.33 1.86-1.08 2.27-2.05.35-.8.44-1.68.45-2.56V.02z"/>
                                </svg>
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Link Terkait -->
                <div class="col-span-1 md:col-span-1">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Link Terkait</h4>
                    <?php
                        $links = [];
                        if (Schema::hasTable('link_terkaits')) {
                            $links = \App\Models\LinkTerkait::aktif()->ordered()->get();
                        }
                    ?>
                    <ul class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <li><a href="<?php echo e($link->url); ?>" target="_blank" class="text-gray-400 hover:text-white text-sm transition"><?php echo e($link->nama); ?></a></li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-gray-500 text-xs italic">Belum ada link</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="col-span-1 md:col-span-1">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Link Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="/tentang" class="text-gray-400 hover:text-white text-sm transition">Tentang Kami</a></li>
                        <li><a href="/jalur-pendaftaran" class="text-gray-400 hover:text-white text-sm transition">Jalur Pendaftaran</a></li>
                        <li><a href="/informasi" class="text-gray-400 hover:text-white text-sm transition">Informasi & Panduan</a></li>
                        <li><a href="/jadwal" class="text-gray-400 hover:text-white text-sm transition">Jadwal</a></li>
                        <li><a href="/alur-pendaftaran" class="text-gray-400 hover:text-white text-sm transition">Alur Pendaftaran</a></li>
                        <li><a href="/pengumuman" class="text-gray-400 hover:text-white text-sm transition">Pengumuman</a></li>
                        <li><a href="/kontak-panitia" class="text-gray-400 hover:text-white text-sm transition">Kontak</a></li>
                        <li><a href="/cek-pendaftaran" class="text-gray-400 hover:text-white text-sm transition">Cek Pendaftaran</a></li>
                        <li><a href="/cek-nsm-npsn" class="text-gray-400 hover:text-white text-sm transition">Cek NSM/NPSN</a></li>
                        <li><a href="/cek-nisn" class="text-gray-400 hover:text-white text-sm transition">Cek NISN</a></li>
                    </ul>
                </div>

                <!-- Kontak WhatsApp Panitia -->
                <div class="col-span-1 md:col-span-1">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Kontak WhatsApp Panitia</h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kontakPanitia->count() > 0): ?>
                        <ul class="space-y-2 text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kontakPanitia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kontak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <li>
                                    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $kontak->nomor_wa)); ?>" target="_blank" class="text-gray-400 hover:text-emerald-400 transition flex items-center group">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-white group-hover:text-emerald-400"><?php echo e($kontak->nama); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kontak->keterangan): ?>
                                                <div class="text-xs text-gray-500"><?php echo e($kontak->keterangan); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </a>
                                </li>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Belum ada kontak tersedia</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Kontak PTSP -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Kontak PTSP</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?php echo e(\App\Models\Setting::get('app_address', 'Jl. Raya Sulfat No. 01')); ?></span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span><?php echo e(\App\Models\Setting::get('app_phone', '(0341) 123456')); ?></span>
                        </li>
                        <li class="flex items-center">
                            <!-- Icon Email -->
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span><?php echo e(\App\Models\Setting::get('app_email_ptsp', 'info@mtsn2kotamalang.sch.id')); ?></span>
                        </li>
                        <li class="flex items-center">
                            <!-- WhatsApp PTSP -->
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span><?php echo e(\App\Models\Setting::get('app_whatsapp_ptsp', 'Belum diatur di Admin')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-sm text-gray-400">
                    <?php echo e(\App\Models\Setting::get('app_footer_copyright', '© ' . date('Y') . ' MTsN 2 Kota Malang. All rights reserved.')); ?>

                </p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopBtn = document.getElementById('backToTopBtn');

            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.remove('hidden');
                } else {
                    backToTopBtn.classList.add('hidden');
                }
            });

            // Scroll to top when button is clicked
            backToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <!-- Mobile Menu & Dropdown Scripts -->
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenuPanel = document.getElementById('mobileMenuPanel');
            const menuIconOpen = document.getElementById('menuIconOpen');
            const menuIconClose = document.getElementById('menuIconClose');

            if (mobileMenuPanel.classList.contains('hidden')) {
                mobileMenuPanel.classList.remove('hidden');
                menuIconOpen.classList.add('hidden');
                menuIconClose.classList.remove('hidden');
            } else {
                mobileMenuPanel.classList.add('hidden');
                menuIconOpen.classList.remove('hidden');
                menuIconClose.classList.add('hidden');
            }
        }

        // Cek Data Dropdown Toggle
        function toggleCekDataDropdown() {
            const dropdown = document.getElementById('cekDataDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('cekDataDropdown');
            const dropdownBtn = document.getElementById('cekDataDropdownBtn');
            const mobileMenuPanel = document.getElementById('mobileMenuPanel');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');

            // Close Cek Data dropdown if clicking outside
            if (dropdown && dropdownBtn &&
                !dropdown.contains(event.target) &&
                !dropdownBtn.contains(event.target)) {
                dropdown.classList.add('hidden');
            }

            // Close mobile menu if clicking outside
            if (mobileMenuPanel && mobileMenuBtn &&
                !mobileMenuPanel.contains(event.target) &&
                !mobileMenuBtn.contains(event.target)) {
                mobileMenuPanel.classList.add('hidden');
                document.getElementById('menuIconOpen').classList.remove('hidden');
                document.getElementById('menuIconClose').classList.add('hidden');
            }
        });
    </script>

    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    
    <script>
        // Check if Alpine is already loaded (from Vite or other source)
        if (typeof window.Alpine === 'undefined') {
            // Load Alpine.js from CDN
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js';
            document.body.appendChild(script);
        } else {
            console.log('Alpine.js already loaded, skipping CDN');
        }
    </script>
</body>
</html><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/components/layouts/public.blade.php ENDPATH**/ ?>