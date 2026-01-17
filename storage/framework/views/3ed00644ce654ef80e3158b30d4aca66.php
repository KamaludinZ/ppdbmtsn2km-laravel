<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($pendaftar->status_pendaftaran, ['data_disetujui', 'lulus', 'diterima'])): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Menu daftar ulang hanya tersedia bagi pendaftar dengan status <strong>DATA DISETUJUI</strong> atau <strong>LULUS</strong>.
                        Status Anda saat ini: <strong class="uppercase"><?php echo e(str_replace('_', ' ', $pendaftar->status_pendaftaran ?? 'menunggu')); ?></strong>.
                        Silakan cek <a href="<?php echo e(route('pendaftar.dashboard')); ?>" class="font-bold underline">Dashboard</a> untuk informasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    <?php elseif($pendaftar->status_pendaftaran == 'data_disetujui'): ?>
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Menunggu Pengumuman Kelulusan</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Data pendaftaran Anda telah disetujui. Silakan menunggu pengumuman kelulusan/tes untuk dapat melanjutkan ke tahap daftar ulang.
                        Pantau terus informasi terbaru di <a href="<?php echo e(route('pendaftar.dashboard')); ?>" class="font-bold underline">Dashboard</a>.
                    </p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pendaftar->setuju_daftar_ulang): ?>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Anda dinyatakan Lulus! Silakan lakukan konfirmasi persetujuan daftar ulang di <a href="<?php echo e(route('pendaftar.dashboard')); ?>" class="font-bold underline">Dashboard</a> terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Status Alerts for Daftar Ulang -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daftarUlang): ?>
                <?php if (isset($component)) { $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-alert','data' => ['status' => $daftarUlang->status ?? 'menunggu','catatan' => $daftarUlang->catatan ?? null,'type' => 'daftar_ulang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($daftarUlang->status ?? 'menunggu'),'catatan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($daftarUlang->catatan ?? null),'type' => 'daftar_ulang']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $attributes = $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $component = $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragam): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragam->status_seragam && in_array($seragam->status_seragam, ['ditolak', 'perbaiki', 'menunggu'])): ?>
                    <?php if (isset($component)) { $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-alert','data' => ['status' => $seragam->status_seragam,'catatan' => $seragam->catatan_seragam ?? null,'type' => 'daftar_ulang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seragam->status_seragam),'catatan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seragam->catatan_seragam ?? null),'type' => 'daftar_ulang']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $attributes = $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $component = $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragam->status_ukuran && in_array($seragam->status_ukuran, ['ditolak', 'perbaiki', 'menunggu'])): ?>
                    <?php if (isset($component)) { $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-alert','data' => ['status' => $seragam->status_ukuran,'catatan' => $seragam->catatan_ukuran ?? null,'type' => 'daftar_ulang']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seragam->status_ukuran),'catatan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seragam->catatan_ukuran ?? null),'type' => 'daftar_ulang']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $attributes = $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $component = $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Status Overview -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-blue-600">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Status Daftar Ulang</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <?php
                            $status = $daftarUlang->status ?? 'belum';
                            $statusInfo = match($status) {
                                'selesai' => ['label' => 'SELESAI', 'color' => 'bg-green-100 text-green-800', 'desc' => 'Selamat! Proses daftar ulang Anda telah selesai.'],
                                'proses' => ['label' => 'DALAM PROSES', 'color' => 'bg-yellow-100 text-yellow-800', 'desc' => 'Data Anda sedang diperiksa oleh panitia.'],
                                default => ['label' => 'BELUM SELESAI', 'color' => 'bg-gray-100 text-gray-800', 'desc' => 'Silakan lengkapi persyaratan di bawah ini.'],
                            };
                        ?>
                        <div class="mr-4">
                            <span class="px-4 py-2 rounded-full text-lg font-bold <?php echo e($statusInfo['color']); ?>">
                                <?php echo e($statusInfo['label']); ?>

                            </span>
                        </div>
                        <p class="text-gray-600"><?php echo e($statusInfo['desc']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Informasi Pembayaran</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Pembayaran disesuaikan dengan kemampuan orang tua/wali. Apabila ingin berkomunikasi perihal kompensasi waktu/biaya/lainnya lebih lanjut, dapat dibicarakan dengan komite madrasah/koperasi madrasah untuk menemukan solusi secara kesepakatan bersama. Waktu sesuai jadwal yang disampaikan oleh pihak komite madrasah/koperasi madrasah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Content -->
            <div x-data="{ activeTab: 'madrasah' }" class="bg-white rounded-lg shadow overflow-hidden">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button @click="activeTab = 'madrasah'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'madrasah', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'madrasah' }" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            Daftar Ulang Madrasah
                        </button>
                        <button @click="activeTab = 'seragam'" :class="{ 'border-blue-500 text-blue-600 bg-blue-50': activeTab === 'seragam', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'seragam' }" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            Daftar Ulang Seragam
                        </button>
                        <button @click="activeTab = 'ukuran'" :class="{ 'border-purple-500 text-purple-600 bg-purple-50': activeTab === 'ukuran', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ukuran' }" class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            Ukuran Seragam & Atribut
                        </button>
                    </nav>
                </div>

                <!-- Madrasah Tab -->
                <div x-show="activeTab === 'madrasah'" x-transition class="p-6 space-y-6">
                    <!-- Informasi/Pengumuman dari Admin -->
                    <?php
                        $madrasahInfo = \App\Models\Setting::get('daftar_ulang_madrasah_info', '');
                        $showPernyataan = \App\Models\Setting::get('daftar_ulang_show_pernyataan', true);
                        $showPembayaranMadrasah = \App\Models\Setting::get('daftar_ulang_show_pembayaran_madrasah', true);
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahInfo): ?>
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-indigo-900">
                                    <?php echo $madrasahInfo; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Daftar Ulang Madrasah</h4>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPernyataan): ?>
                    <!-- Surat Pernyataan -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Surat Pernyataan
                        </h5>
                        <p class="text-sm text-gray-600 mb-3">Download template surat pernyataan, isi, tandatangani, lalu upload kembali.</p>

                        <?php
                            $templatePath = \App\Models\Setting::get('daftar_ulang_madrasah_template', null);
                        ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($templatePath): ?>
                                                        <a href="<?php echo e(Storage::url($templatePath)); ?>" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium mb-4">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            Download Template
                                                        </a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daftarUlang && $daftarUlang->surat_pernyataan_path): ?>
                            <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded mb-3">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span class="text-sm text-gray-700 flex-1">Surat pernyataan telah diupload</span>
                                <a href="<?php echo e(Storage::url($daftarUlang->surat_pernyataan_path)); ?>" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat</a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <input type="file" wire:model="suratPernyataan" id="surat-pernyataan" class="hidden" accept=".pdf">
                            <label for="surat-pernyataan" class="cursor-pointer block w-full h-full">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($suratPernyataan): ?>
                                        File terpilih: <?php echo e($suratPernyataan->getClientOriginalName()); ?>

                                    <?php else: ?>
                                        Klik untuk upload surat pernyataan (jika ingin mengubah)
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Hanya PDF (Max. 2MB)</p>
                            </label>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['suratPernyataan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Seragam Tab -->
                <div x-show="activeTab === 'seragam'" x-transition class="p-6 space-y-6">
                    <!-- Informasi/Pengumuman dari Admin -->
                    <?php
                        $seragamInfo = \App\Models\Setting::get('daftar_ulang_seragam_info', '');
                        $showPembayaranSeragam = \App\Models\Setting::get('daftar_ulang_show_pembayaran_seragam', true);
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragamInfo): ?>
                        <div class="bg-gradient-to-r from-cyan-50 to-teal-50 border border-cyan-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-cyan-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-cyan-900">
                                    <?php echo $seragamInfo; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Daftar Ulang Seragam</h4>
                    </div>

                    <!-- Paket Seragam -->
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Paket Seragam
                        </h5>

                        <?php
                            $seragamPackages = \App\Models\Setting::get('daftar_ulang_seragam_packages', []);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragamPackages && count($seragamPackages) > 0): ?>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <label class="flex items-start p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                        <input type="radio" name="paket_seragam" value="<?php echo e($index); ?>" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-900"><?php echo e($package['name'] ?? '-'); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo e($package['description'] ?? '-'); ?></p>
                                        </div>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Paket seragam belum tersedia</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPembayaranSeragam): ?>
                    <!-- Biaya & Pembayaran Seragam -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Biaya Seragam
                        </h5>

                        <?php
                            $seragamNominals = \App\Models\Setting::get('daftar_ulang_seragam_nominals', []);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragamNominals && count($seragamNominals) > 0): ?>
                            <div class="space-y-2 mb-4">
                                <?php $total = 0; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamNominals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nominal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-700"><?php echo e($nominal['name'] ?? '-'); ?></span>
                                        <span class="font-semibold text-gray-900">Rp <?php echo e(number_format($nominal['amount'] ?? 0, 0, ',', '.')); ?></span>
                                    </div>
                                    <?php $total += ($nominal['amount'] ?? 0); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <div class="pt-2 border-t-2 border-green-300 flex justify-between items-center">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-emerald-700 text-lg">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Opsi Pembayaran Seragam -->
                        <?php
                            $seragamPaymentOptions = \App\Models\Setting::get('daftar_ulang_seragam_payment_options', ['lunas' => false, 'angsuran' => false, 'potongan' => false]);
                            $availableSeragamOptions = array_filter($seragamPaymentOptions);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($availableSeragamOptions)): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Opsi Pembayaran</label>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Opsi --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableSeragamOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option => $enabled): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabled): ?>
                                            <option value="<?php echo e($option); ?>"><?php echo e(ucfirst($option)); ?></option>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Bank Selection Seragam -->
                        <?php
                            $seragamBanks = \App\Models\Setting::get('daftar_ulang_seragam_banks', []);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragamBanks && count($seragamBanks) > 0): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Bank Tujuan</label>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Bank --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $seragamBanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e(json_encode($bank)); ?>">
                                            <?php echo e($bank['name'] ?? '-'); ?> - <?php echo e($bank['account_number'] ?? '-'); ?> (<?php echo e($bank['account_name'] ?? '-'); ?>)
                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Upload Bukti Pembayaran Seragam -->
                        <?php
                            $requireSeragamPaymentProof = \App\Models\Setting::get('daftar_ulang_seragam_require_payment_proof', false);
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requireSeragamPaymentProof): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->seragam && $pendaftar->seragam->bukti_pembayaran): ?>
                                    <div class="p-4 bg-green-50 border-2 border-green-200 rounded-lg">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm font-semibold text-green-800">Bukti pembayaran telah diupload</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="<?php echo e(Storage::url($pendaftar->seragam->bukti_pembayaran)); ?>" target="_blank"
                                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat Bukti
                                            </a>
                                            <a href="<?php echo e(Storage::url($pendaftar->seragam->bukti_pembayaran)); ?>" download
                                               class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Download
                                            </a>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->seragam->status_seragam == 'disetujui' || $pendaftar->seragam->status_seragam == 'selesai'): ?>
                                            <button wire:click="downloadInvoiceSeragam" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Invoice
                                            </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                        <input type="file" id="bukti-seragam" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                        <label for="bukti-seragam" class="cursor-pointer">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Klik untuk upload bukti</p>
                                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (Max. 5MB)</p>
                                        </label>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Ukuran Tab -->
                <div x-show="activeTab === 'ukuran'" x-transition class="p-6 space-y-6">
                    <!-- Informasi/Pengumuman dari Admin -->
                    <?php
                        $ukuranInfo = \App\Models\Setting::get('daftar_ulang_ukuran_info', '');
                        $showUkuranAtasan = \App\Models\Setting::get('daftar_ulang_show_ukuran_atasan', true);
                        $showUkuranBawahan = \App\Models\Setting::get('daftar_ulang_show_ukuran_bawahan', true);
                        $showAtribut = \App\Models\Setting::get('daftar_ulang_show_atribut', true);
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranInfo): ?>
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-sm text-purple-900">
                                    <?php echo $ukuranInfo; ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Ukuran Seragam & Atribut</h4>
                    </div>

                    <?php
                        $ukuranJasImage = \App\Models\Setting::get('daftar_ulang_ukuran_jas_image', null);
                        $ukuranJasOptions = \App\Models\Setting::get('daftar_ulang_ukuran_jas_options', []);
                        $ukuranAtasanImage = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_image', null);
                        $ukuranAtasanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_atasan_options', []);
                        $ukuranBawahanImage = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_image', null);
                        $ukuranBawahanOptions = \App\Models\Setting::get('daftar_ulang_ukuran_bawahan_options', []);
                        $ukuranKopyahImage = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_image', null);
                        $ukuranKopyahOptions = \App\Models\Setting::get('daftar_ulang_ukuran_kopyah_options', []);
                    ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Ukuran Jas -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Jas</h5>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranJasImage): ?>
                                <img src="<?php echo e(Storage::url($ukuranJasImage)); ?>" alt="Panduan Ukuran Jas" class="w-full rounded-lg mb-3">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranJasOptions && count($ukuranJasOptions) > 0): ?>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranJasOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e(json_encode($option)); ?>">
                                            <?php echo e($option['size'] ?? '-'); ?> (<?php echo e($option['measurement'] ?? '-'); ?>)
                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Ukuran Atasan -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showUkuranAtasan): ?>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Atasan/Baju</h5>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranAtasanImage): ?>
                                <img src="<?php echo e(Storage::url($ukuranAtasanImage)); ?>" alt="Panduan Ukuran Atasan" class="w-full rounded-lg mb-3">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranAtasanOptions && count($ukuranAtasanOptions) > 0): ?>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranAtasanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e(json_encode($option)); ?>">
                                            <?php echo e($option['size'] ?? '-'); ?> (<?php echo e($option['measurement'] ?? '-'); ?>)
                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Ukuran Bawahan -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showUkuranBawahan): ?>
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Bawahan/Rok/Celana</h5>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranBawahanImage): ?>
                                <img src="<?php echo e(Storage::url($ukuranBawahanImage)); ?>" alt="Panduan Ukuran Bawahan" class="w-full rounded-lg mb-3">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranBawahanOptions && count($ukuranBawahanOptions) > 0): ?>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranBawahanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e(json_encode($option)); ?>">
                                            <?php echo e($option['size'] ?? '-'); ?> (<?php echo e($option['measurement'] ?? '-'); ?>)
                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Ukuran Kopyah (Khusus Laki-laki) -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAtribut && $pendaftar->jenis_kelamin === 'L'): ?>
                        <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 mb-3">Ukuran Kopyah (Laki-laki)</h5>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranKopyahImage): ?>
                                <img src="<?php echo e(Storage::url($ukuranKopyahImage)); ?>" alt="Panduan Ukuran Kopyah" class="w-full rounded-lg mb-3">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ukuranKopyahOptions && count($ukuranKopyahOptions) > 0): ?>
                                <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <option value="">-- Pilih Ukuran --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ukuranKopyahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e(json_encode($option)); ?>">
                                            <?php echo e($option['size'] ?? '-'); ?> (<?php echo e($option['measurement'] ?? '-'); ?>)
                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Opsi ukuran belum tersedia</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Catatan:</strong> Pastikan Anda memilih ukuran yang sesuai dengan panduan gambar. Jika ragu, silakan hubungi panitia.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\daftar-ulang.blade.php ENDPATH**/ ?>