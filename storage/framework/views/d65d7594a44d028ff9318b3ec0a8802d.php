<?php if (isset($component)) { $__componentOriginal1a6cca1fb3b05e19b47840b98800a235 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1a6cca1fb3b05e19b47840b98800a235 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.dashboard','data' => ['role' => 'pendaftar','pageTitle' => 'Dashboard Pendaftar','pageDescription' => 'Selamat datang di dashboard pendaftaran Anda']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.dashboard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['role' => 'pendaftar','pageTitle' => 'Dashboard Pendaftar','pageDescription' => 'Selamat datang di dashboard pendaftaran Anda']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Status Pendaftaran -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Status Pendaftaran Anda</h2>
                <p class="text-emerald-100 mb-4">Nomor Pendaftaran: <span class="font-bold"><?php echo e($appAcronym); ?>-2025-001234</span></p>
                <div class="flex items-center space-x-2">
                    <span class="px-4 py-2 bg-yellow-500 rounded-lg font-semibold text-white">
                        Menunggu Verifikasi
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <svg class="w-32 h-32 text-emerald-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-lg shadow mb-8 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Progress Pendaftaran</h3>
        <div class="flex items-center justify-between">
            <!-- Step 1 - Completed -->
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-emerald-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Formulir</p>
                        <p class="text-xs text-emerald-600">Selesai</p>
                    </div>
                </div>
            </div>

            <!-- Step 2 - Current -->
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full animate-pulse">
                        <span class="text-white font-bold">2</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Verifikasi</p>
                        <p class="text-xs text-yellow-600">Dalam Proses</p>
                    </div>
                </div>
            </div>

            <!-- Step 3 - Pending -->
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                        <span class="text-gray-600 font-bold">3</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Ujian</p>
                        <p class="text-xs text-gray-400">Menunggu</p>
                    </div>
                </div>
            </div>

            <!-- Step 4 - Pending -->
            <div class="flex-1">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                        <span class="text-gray-600 font-bold">4</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pengumuman</p>
                        <p class="text-xs text-gray-400">Menunggu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Pribadi -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pribadi</h3>
                    <a href="/pendaftar/formulir" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        Edit →
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-medium text-gray-900">Ahmad Maulana Yusuf</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NISN</p>
                        <p class="font-medium text-gray-900">0012345678</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                        <p class="font-medium text-gray-900">Malang, 15 Januari 2012</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jalur Pendaftaran</p>
                        <p class="font-medium text-gray-900">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Prestasi</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Dokumen</h3>
                    <a href="/pendaftar/dokumen" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        Kelola →
                    </a>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Foto 4x6</span>
                        </div>
                        <span class="text-xs text-green-600 font-semibold">Terverifikasi</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Kartu Keluarga</span>
                        </div>
                        <span class="text-xs text-green-600 font-semibold">Terverifikasi</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Ijazah SD</span>
                        </div>
                        <span class="text-xs text-yellow-600 font-semibold">Menunggu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Cepat</h3>
                <div class="space-y-3">
                    <a href="/pendaftar/formulir" class="flex items-center px-4 py-3 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Edit Formulir</span>
                    </a>
                    <a href="/pendaftar/dokumen" class="flex items-center px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Upload Dokumen</span>
                    </a>
                    <a href="/pendaftar/kartu-ujian" class="flex items-center px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900">Cetak Kartu</span>
                    </a>
                </div>
            </div>

            <!-- Jadwal Ujian -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Ujian</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">Tanggal Ujian</p>
                    <p class="text-lg font-bold text-gray-900 mb-1">20 April 2025</p>
                    <p class="text-sm text-gray-600 mb-3">Pukul 08.00 - 11.00 WIB</p>
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                        <p class="text-sm font-medium text-gray-900">MTsN 2 Kota Malang</p>
                        <p class="text-xs text-gray-600">Jl. Raya Sulfat No. 01</p>
                    </div>
                </div>
            </div>

            <!-- Info Penting -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Peringatan</p>
                        <p class="text-xs text-yellow-700 mt-1">Pastikan semua dokumen sudah diupload sebelum tanggal 15 Maret 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1a6cca1fb3b05e19b47840b98800a235)): ?>
<?php $attributes = $__attributesOriginal1a6cca1fb3b05e19b47840b98800a235; ?>
<?php unset($__attributesOriginal1a6cca1fb3b05e19b47840b98800a235); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1a6cca1fb3b05e19b47840b98800a235)): ?>
<?php $component = $__componentOriginal1a6cca1fb3b05e19b47840b98800a235; ?>
<?php unset($__componentOriginal1a6cca1fb3b05e19b47840b98800a235); ?>
<?php endif; ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\dashboard\pendaftar\index.blade.php ENDPATH**/ ?>