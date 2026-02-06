<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role' => 'admin', 'title' => null, 'pageTitle' => null]));

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

foreach (array_filter((['role' => 'admin', 'title' => null, 'pageTitle' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? ($pageTitle ? $pageTitle . ' - ' . \App\Models\Setting::get('app_name', 'PMBM MTsN 2 Kota Malang') : 'Dashboard - ' . \App\Models\Setting::get('app_name', 'PMBM MTsN 2 Kota Malang'))); ?></title>

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

    <!-- Trix Editor (for admin forms) -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

    <style>
        /* Customize Trix editor appearance */
        .trix-content {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem !important;
        }

        .trix-toolbar {
            border: 1px solid #d1d5db !important;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }

        .trix-button {
            background-color: #f9fafb !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            margin: 0.1rem !important;
            padding: 0.25rem 0.5rem !important;
        }

        .trix-button:hover {
            background-color: #e5e7eb !important;
        }

        /* Styling untuk toolbar khusus */
        .trix-button-row {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.1rem !important;
            padding: 0.25rem !important;
        }

        .trix-button-group {
            display: flex !important;
            margin: 0.1rem !important;
        }

        .trix-button-group-spacer {
            flex-grow: 1 !important;
        }
    </style>
</head>
<body class="antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 flex-shrink-0 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="flex items-center px-6 py-4 border-b border-gray-200 justify-between lg:justify-start">
                    <div class="flex items-center space-x-3">
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
                            <h2 class="text-sm font-bold text-gray-900"><?php echo e(\App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang')); ?></h2>
                            <p class="text-xs text-gray-600"><?php echo e($appAcronym); ?> <?php echo e(\App\Models\Setting::get('tahun_pelajaran', date('Y'))); ?></p>
                        </div>
                    </div>
                    <!-- Mobile Close Button -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-1 pb-4">
                    <?php
                        $activeClass = 'bg-emerald-50 text-emerald-700 font-semibold';
                        $inactiveClass = 'text-gray-700 hover:bg-gray-100';
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'admin'): ?>
                        <!-- Admin Menu -->
                        <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="/admin/settings" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/settings*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/settings*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan
                        </a>
                        <a href="/admin/pengaturan-pesan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/pengaturan-pesan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/pengaturan-pesan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Pengaturan Pesan
                        </a>
                        <a href="/admin/pengaturan-kartu" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/pengaturan-kartu*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/pengaturan-kartu*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            Pengaturan Kartu
                        </a>
                        <a href="/admin/tentang" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/tentang') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/tentang') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tentang
                        </a>
                        <a href="/admin/kontak" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/kontak*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/kontak*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Kontak
                        </a>
                        <a href="/admin/jalur" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/jalur*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/jalur*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Jalur Pendaftaran
                        </a>
                        <a href="/admin/jadwal" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/jadwal') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/jadwal') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal
                        </a>
                        <a href="/admin/panitia" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/panitia*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/panitia*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Manajemen Panitia
                        </a>
                        <a href="/admin/pendaftar" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/pendaftar*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/pendaftar*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Data Pendaftar
                        </a>
                        <a href="/admin/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/tes-psikologi*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/tes-psikologi*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tes Psikologi
                        </a>
                        <a href="/admin/kelulusan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/kelulusan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/kelulusan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelulusan
                        </a>
                        <a href="/admin/daftar-ulang" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/daftar-ulang*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/daftar-ulang*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Daftar Ulang
                        </a>
                        <a href="/admin/data-asal-sekolah" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/data-asal-sekolah*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/data-asal-sekolah*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Data Asal Sekolah
                        </a>
                        <a href="/admin/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/laporan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/laporan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>
                        <a href="/admin/survey" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/survey*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/survey*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Survey Pendaftaran
                        </a>
                        <a href="/admin/pengumuman" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/pengumuman*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/pengumuman*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Pengumuman
                        </a>
                        <a href="/admin/informasi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/informasi') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/informasi') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Informasi & Panduan
                        </a>
                        <a href="/admin/video-infografis" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/video-infografis*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/video-infografis*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Video & Infografis
                        </a>
                        <a href="/admin/monitoring" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/monitoring*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/monitoring*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Monitoring
                        </a>
                        <a href="/admin/security" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/security*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/security*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Keamanan
                        </a>
                        <a href="/admin/backup-restore" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/backup-restore*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/backup-restore*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Backup & Restore
                        </a>
                        <a href="/admin/emis-input" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('admin/emis-input*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('admin/emis-input*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Input EMIS
                        </a>

                    <?php elseif($role === 'pendaftar'): ?>
                        <!-- Pendaftar Menu -->
                        <?php
                            $currentPendaftar = Auth::user()->pendaftar ?? null;
                            // Kartu Tes Seleksi can be accessed ONLY if:
                            // 1. Status pendaftaran = data_disetujui
                            // 2. Pembayaran tes psikologi sudah DISETUJUI (bukan hanya uploaded!)
                            $canAccessKartuUjian = $currentPendaftar
                                && $currentPendaftar->status_pendaftaran === 'data_disetujui'
                                && $currentPendaftar->tesPsikologiPendaftar
                                && $currentPendaftar->tesPsikologiPendaftar->bukti_transfer
                                && $currentPendaftar->tesPsikologiPendaftar->status_verifikasi === 'disetujui';
                            $tesPsikologiSetting = \App\Models\TesPsikologiSetting::getSetting();
                            $tesPsikologiAktif = $tesPsikologiSetting->aktif;
                        ?>

                        <a href="/pendaftar/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="/pendaftar/formulir" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/formulir*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/formulir*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Formulir Pendaftaran
                        </a>
                        <a href="/pendaftar/dokumen" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/dokumen*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/dokumen*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Upload Dokumen
                        </a>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiAktif): ?>
                            <a href="/pendaftar/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/tes-psikologi*') ? $activeClass : $inactiveClass); ?>">
                                <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/tes-psikologi*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Tes Psikologi
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <a href="/pendaftar/status" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/status*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/status*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Status Pendaftaran
                        </a>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canAccessKartuUjian): ?>
                            <a href="/pendaftar/kartu-tes-seleksi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/kartu-tes-seleksi*') ? $activeClass : $inactiveClass); ?>">
                                <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/kartu-tes-seleksi*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                Kartu Tes Seleksi
                            </a>
                        <?php else: ?>
                            <div class="flex items-center px-4 py-3 text-sm rounded-lg opacity-50 cursor-not-allowed" title="Menu ini dapat diakses setelah: 1) Data Anda disetujui, dan 2) Pembayaran tes psikologi disetujui">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="text-gray-400">Kartu Tes Seleksi</span>
                                <svg class="w-4 h-4 ml-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <a href="/pendaftar/daftarulang" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('pendaftar/daftarulang*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('pendaftar/daftarulang*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Daftar Ulang
                        </a>

                    <?php elseif($role === 'panitia_pendaftaran' || $role === 'panitia'): ?>
                        <!-- Panitia Pendaftaran Menu -->
                        <a href="/panitia-pendaftaran/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="/panitia-pendaftaran/pendaftar" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/pendaftar*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/pendaftar*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Data Pendaftar
                        </a>
                        <a href="/panitia-pendaftaran/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/tes-psikologi*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/tes-psikologi*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tes Psikologi
                        </a>
                        <a href="/panitia-pendaftaran/kelulusan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/kelulusan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/kelulusan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelulusan
                        </a>
                        <a href="/panitia-pendaftaran/data-asal-sekolah" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/data-asal-sekolah*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/data-asal-sekolah*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Data Asal Sekolah
                        </a>
                        <a href="/panitia-pendaftaran/jalur" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/jalur*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/jalur*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Jalur Pendaftaran
                        </a>
                        <a href="/panitia-pendaftaran/jadwal" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/jadwal') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/jadwal') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal
                        </a>
                        <a href="/panitia-pendaftaran/pengumuman" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/pengumuman*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/pengumuman*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Pengumuman
                        </a>
                        <a href="/panitia-pendaftaran/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-pendaftaran/laporan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-pendaftaran/laporan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>

                    <?php elseif($role === 'panitia_daftarulang'): ?>
                        <!-- Panitia Daftar Ulang Menu -->
                        <a href="/panitia-daftarulang/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-daftarulang/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-daftarulang/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="/panitia-daftarulang/kelulusan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-daftarulang/kelulusan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-daftarulang/kelulusan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelulusan
                        </a>
                        <a href="/panitia-daftarulang/daftar-ulang" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-daftarulang/daftar-ulang*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-daftarulang/daftar-ulang*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Daftar Ulang
                        </a>
                        <a href="/panitia-daftarulang/pengumuman" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-daftarulang/pengumuman*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-daftarulang/pengumuman*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Pengumuman
                        </a>
                        <a href="/panitia-daftarulang/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-daftarulang/laporan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-daftarulang/laporan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>

                    <?php elseif($role === 'panitia_tes_psikologi'): ?>
                        <!-- Panitia Tes Psikologi Menu -->
                        <a href="/panitia-psikologi/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-psikologi/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-psikologi/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="/panitia-psikologi/tes-psikologi" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-psikologi/tes-psikologi*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-psikologi/tes-psikologi*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tes Psikologi
                        </a>
                        <a href="/panitia-psikologi/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-psikologi/laporan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-psikologi/laporan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>

                    <?php elseif($role === 'panitia_seragam'): ?>
                        <!-- Panitia Seragam Menu - Fokus manajemen seragam dan ukuran -->
                        <a href="/panitia-seragam/dashboard" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-seragam/dashboard') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-seragam/dashboard') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="/panitia-seragam/kelulusan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-seragam/kelulusan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-seragam/kelulusan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelulusan
                        </a>
                        <a href="/panitia-seragam/daftar-ulang" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-seragam/daftar-ulang*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-seragam/daftar-ulang*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Daftar Ulang
                        </a>
                        <a href="/panitia-seragam/laporan" class="flex items-center px-4 py-3 text-sm rounded-lg transition <?php echo e(request()->is('panitia-seragam/laporan*') ? $activeClass : $inactiveClass); ?>">
                            <svg class="w-5 h-5 mr-3 <?php echo e(request()->is('panitia-seragam/laporan*') ? 'text-emerald-600' : ''); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Laporan
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6 py-4">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Button -->
                    <button type="button" @click="sidebarOpen = true" class="lg:hidden text-gray-700 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div>
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900"><?php echo e($pageTitle ?? 'Dashboard'); ?></h1>
                        <p class="text-xs lg:text-sm text-gray-600 hidden sm:block"><?php echo e($pageDescription ?? ('Selamat datang di dashboard ' . $appAcronym)); ?></p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Notifications -->
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <?php
                                // Count real unread notifications from database
                                $unreadCount = Auth::user()->unreadNotifications()->count();
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                            <span class="absolute top-1 right-1 flex items-center justify-center min-w-[18px] h-[18px] text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white px-1">
                                <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

                            </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-lg overflow-hidden z-50 border border-gray-200"
                             style="display: none;">
                            <div class="px-4 py-3 bg-emerald-600 text-white">
                                <h3 class="font-semibold">Notifikasi</h3>
                                <p class="text-xs opacity-90"><?php echo e($unreadCount); ?> notifikasi belum dibaca</p>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <?php
                                    // Fetch real notifications from database, limited to 5 latest
                                    $recentNotifications = Auth::user()->notifications()->take(5)->get();
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php
                                        $data = $notification->data;
                                        $isUnread = is_null($notification->read_at);

                                        // Determine icon based on notification type
                                        $icon = '🔔';
                                        if (isset($data['type'])) {
                                            switch($data['type']) {
                                                case 'pendaftar':
                                                case 'new_registration':
                                                    $icon = '👤';
                                                    break;
                                                case 'verifikasi':
                                                case 'verification':
                                                    $icon = '✅';
                                                    break;
                                                case 'daftar_ulang':
                                                case 're_registration':
                                                    $icon = '📝';
                                                    break;
                                                case 'pengumuman':
                                                case 'announcement':
                                                    $icon = '📢';
                                                    break;
                                                case 'system':
                                                case 'backup':
                                                    $icon = '💾';
                                                    break;
                                                case 'warning':
                                                    $icon = '⚠️';
                                                    break;
                                                case 'success':
                                                    $icon = '🎉';
                                                    break;
                                            }
                                        }

                                        $title = $data['title'] ?? 'Notifikasi';
                                        $message = $data['message'] ?? '';
                                        $link = $data['link'] ?? null;
                                    ?>

                                    <a href="<?php echo e($link ?? '#'); ?>"
                                       onclick="event.preventDefault(); <?php if($link): ?> window.location.href='<?php echo e($link); ?>'; <?php endif; ?>"
                                       class="block hover:bg-gray-50 border-b border-gray-100 transition <?php echo e($isUnread ? 'bg-emerald-50/30' : ''); ?>">
                                        <div class="px-4 py-3">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 text-xl">
                                                    <?php echo e($icon); ?>

                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 <?php echo e($isUnread ? 'font-semibold' : ''); ?>">
                                                        <?php echo e($title); ?>

                                                    </p>
                                                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-2"><?php echo e($message); ?></p>
                                                    <p class="text-xs text-gray-400 mt-1"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isUnread): ?>
                                                <div class="flex-shrink-0">
                                                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                                                </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="mt-2 text-sm">Tidak ada notifikasi</p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-center border-t">
                                <?php
                                    $notificationRoute = $role === 'pendaftar' ? 'pendaftar.notifikasi' : 'admin.notifikasi';
                                ?>
                                <a href="<?php echo e(route($notificationRoute)); ?>" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div x-data="{ profilOpen: false }" @click.away="profilOpen = false" class="relative">
                        <button @click="profilOpen = !profilOpen" class="flex items-center space-x-2 sm:space-x-3 px-2 sm:px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="hidden md:block text-left min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-gray-600 truncate capitalize"><?php echo e(str_replace('_', ' ', $role)); ?></p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform hidden sm:block" :class="profilOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="profilOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg overflow-hidden z-50 border border-gray-200"
                             style="display: none;">
                            <!-- User Info Header -->
                            <div class="px-4 py-3 bg-emerald-50 border-b border-emerald-100">
                                <p class="text-sm font-medium text-gray-900 truncate"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-xs text-gray-600 truncate"><?php echo e(Auth::user()->email); ?></p>
                                <p class="text-xs text-emerald-700 font-medium mt-1 capitalize"><?php echo e(str_replace('_', ' ', $role)); ?></p>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-2">
                                <button
                                    type="button"
                                    @click="profilOpen = false; $dispatch('open-edit-profil')"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Profil
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="/logout" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-6">
                <?php echo e($slot); ?>

            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 mt-auto">
                <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
                    <p class="text-center text-sm text-gray-500">
                        <?php echo e(\App\Models\Setting::get('app_footer_copyright', '© ' . date('Y') . ' MTsN 2 Kota Malang. All rights reserved.')); ?>

                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Edit Profil Modal -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('edit-profil');

$key = null;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1902772661-0', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/components/layouts/dashboard.blade.php ENDPATH**/ ?>