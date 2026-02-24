<div>
    <!-- Global Loading Overlay -->
    <div wire:loading.delay.long class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 shadow-xl flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 font-medium">Memproses...</span>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{
                show: false,
                message: '',
                type: 'success',
                init() {
                    window.addEventListener('statusUpdated', (event) => {
                        this.type = event.detail[0].type || 'success';
                        this.message = event.detail[0].message;
                        this.show = true;
                        setTimeout(() => this.show = false, 4000);
                    });
                }
            }"
            x-show="show"
            x-transition
            :class="{
                'bg-emerald-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-blue-500': type === 'info'
            }"
            class="fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center max-w-md"
            style="display: none;">
            <svg x-show="type === 'success'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span x-text="message" class="flex-1"></span>
        </div>

        <!-- Flash Message -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Stats Overview - Status Pendaftaran -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['total'])); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['menunggu_verifikasi'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lengkapi Data -->
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Lengkapi Data</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['lengkapi_data'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perbaiki Data -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Perbaiki Data</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['perbaiki_data'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Data Ditolak -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Data Ditolak</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['data_ditolak'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Data Disetujui -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Data Disetujui</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['data_disetujui'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Data</h3>
                </div>
                <!-- Search Field -->
                <div class="flex gap-2 flex-1 max-w-xl ml-6">
                    <input
                        type="text"
                        wire:model="searchInput"
                        wire:keydown.enter="applySearch"
                        placeholder="Cari nama, NISN, No. Daftar..."
                        autocomplete="new-password"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        name="search_pendaftar_<?php echo e(uniqid()); ?>"
                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                    <button wire:click="applySearch" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-1 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari</span>
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                        <button wire:click="clearSearch" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition" title="Hapus pencarian">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelengkapan EMIS</label>
                    <select wire:model.live="filterEmisCompleteness" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Persentase</option>
                        <option value="0-25">0% - 25% (Sangat Kurang)</option>
                        <option value="26-50">26% - 50% (Kurang)</option>
                        <option value="51-75">51% - 75% (Cukup)</option>
                        <option value="76-100">76% - 100% (Lengkap)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                    <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Jalur</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                        <option value="lengkapi_data">Lengkapi Data</option>
                        <option value="perbaiki_data">Perbaiki Data</option>
                        <option value="data_ditolak">Data Ditolak</option>
                        <option value="data_disetujui">Data Disetujui</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verif Psikologi</label>
                    <select wire:model.live="filterVerifPsikologi" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="belum_upload">Belum Upload</option>
                        <option value="menunggu">Menunggu Verifikasi</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="perbaiki">Perlu Perbaikan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                    <select wire:model.live="perPage" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Pendaftar</h3>
                </div>
                <!-- Column Filter Dropdown -->
                <div x-data="columnFilter()" class="relative">
                    <button @click="open = !open" class="flex items-center px-4 py-2 bg-white text-emerald-600 rounded-md hover:bg-emerald-50 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                        <span class="font-medium">Filter Kolom</span>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 border border-gray-200" x-cloak>
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-700 mb-3 text-sm uppercase">Pilih Kolom yang Ditampilkan</h4>
                            <!-- Bulk Actions -->
                            <div class="flex gap-2 mb-3 pb-3 border-b border-gray-200">
                                <button
                                    @click="checkAll()"
                                    class="flex-1 px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded hover:bg-emerald-700 transition">
                                    ✓ Ceklist Semua
                                </button>
                                <button
                                    @click="uncheckAll()"
                                    class="flex-1 px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition">
                                    ✗ Hapus Semua
                                </button>
                            </div>
                            <!-- Checkboxes -->
                            <div class="max-h-96 overflow-y-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $showColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <input type="checkbox" wire:model.live="showColumns.<?php echo e($key); ?>" @change="toggleColumn('<?php echo e($key); ?>', $event.target.checked)" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-gray-700"><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></span>
                                    </label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="group">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['no']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nomor_pendaftaran']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nomor_pendaftaran','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nomor_pendaftaran','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Nomor Pendaftaran
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['pendaftar']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nama_lengkap','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nama_lengkap','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Pendaftar
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jenis_kelamin']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'jenis_kelamin','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'jenis_kelamin','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Jenis Kelamin
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jalur']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['kontak']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['emis_completeness']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelengkapan EMIS</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['ortu_whatsapp']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ortu WhatsApp</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['asal_kabkota']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'asal_kabkota','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'asal_kabkota','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Asal Kab/Kota
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jenis_sekolah']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'jenis_sekolah_asal','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'jenis_sekolah_asal','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Jenis Sekolah
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nsm_npsn']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'npsn_sekolah_asal','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'npsn_sekolah_asal','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    NSM/NPSN
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['asal_sekolah']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'asal_sekolah','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'asal_sekolah','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Asal Sekolah
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nama_ayah']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nama_ayah','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nama_ayah','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Nama Ayah
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nama_ibu']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'nama_ibu','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'nama_ibu','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Nama Ibu
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_psikologi']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Psikologi</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'status_pendaftaran','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'status_pendaftaran','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Status Pendaftaran
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_keahlian']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Keahlian</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_tahfidz']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tahfidz</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_tes']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'status_kelulusan_tes','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'status_kelulusan_tes','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Status Tes
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['setuju_daftar_ulang']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'setuju_daftar_ulang','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'setuju_daftar_ulang','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Persetujuan Daftar Ulang
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_du_madrasah']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status DU Madrasah</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_du_seragam']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status DU Seragam</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_ukuran']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Ukuran</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_daftar_ulang']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Daftar Ulang</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_rapor']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Rapor</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_akte']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Akta Kelahiran</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kk']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Kartu Keluarga</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_nisn']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. NISN</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_ijazah']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Ijazah/SKL</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_sertifikat']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. Sertifikat/Piagam</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kip']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. KIP</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_pkh']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. PKH</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kks']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verif. KKS</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['diperbarui']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'updated_at','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'updated_at','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Diperbarui
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['diperbarui_oleh']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperbarui Oleh</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['tanggal']): ?>
                                <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'tanggal_daftar','sortField' => $sortField,'sortDirection' => $sortDirection]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'tanggal_daftar','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                                    Tanggal Daftar
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $attributes = $__attributesOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__attributesOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f94e1f2665f26428c518049f3c9052b)): ?>
<?php $component = $__componentOriginal9f94e1f2665f26428c518049f3c9052b; ?>
<?php unset($__componentOriginal9f94e1f2665f26428c518049f3c9052b); ?>
<?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['password']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['ubah_password']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ubah Password</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['catatan']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['aksi']): ?>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pendaftaran</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['riwayat_pesan']): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Riwayat Pesan</th>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ganti Jalur</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr class="hover:bg-gray-50">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['no']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($pendaftar->firstItem() + $index); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nomor_pendaftaran']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900"><?php echo e($item->nomor_pendaftaran); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['pendaftar']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                                <span class="font-semibold"><?php echo e(strtoupper(substr($item->nama_lengkap ?? '?', 0, 2))); ?></span>
                                            </div>
                                            <div class="ml-4">
                                                <a href="<?php echo e(route('admin.pendaftar.detail', $item->id)); ?>" target="_blank" rel="noopener noreferrer" wire:navigate.hover="false" class="text-sm font-medium text-gray-900 hover:text-emerald-600 hover:underline flex items-center" onclick="event.stopPropagation();">
                                                    <?php echo e($item->nama_lengkap); ?>

                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                                <div class="text-sm text-gray-500">NISN: <?php echo e($item->nisn); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jenis_kelamin']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_kelamin): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'); ?>">
                                                <?php echo e($item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jalur']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($item->jalurPendaftaran->warna ?? 'blue'); ?>-100 text-<?php echo e($item->jalurPendaftaran->warna ?? 'blue'); ?>-800">
                                            <?php echo e($item->jalurPendaftaran->nama ?? '-'); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['kontak']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo e($item->email); ?></div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['emis_completeness']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $emisBadge = $item->getEmisCompletenessBadge();
                                            $percentage = $emisBadge['percentage'];
                                            $label = $emisBadge['label'];
                                            $color = $emisBadge['color'];
                                        ?>
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1">
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="font-medium text-gray-700"><?php echo e($percentage); ?>%</span>
                                                    <span class="text-<?php echo e($color); ?>-600"><?php echo e($label); ?></span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-<?php echo e($color); ?>-600 h-1.5 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['ortu_whatsapp']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->telepon_ortu): ?>
                                                <?php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $item->telepon_ortu);
                                                    if (substr($cleanPhone, 0, 1) === '0') {
                                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                                    } elseif (substr($cleanPhone, 0, 2) !== '62') {
                                                        $cleanPhone = '62' . $cleanPhone;
                                                    }
                                                    $waUrl = 'https://wa.me/' . $cleanPhone;
                                                ?>
                                                <a href="<?php echo e($waUrl); ?>" target="_blank" class="inline-flex items-center text-sm text-green-600 hover:text-green-800 hover:underline transition">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                                                    </svg>
                                                    <?php echo e($item->telepon_ortu); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-sm text-gray-400">-</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <button
                                                wire:click="showEditTeleponOrtuModal(<?php echo e($item->id); ?>)"
                                                class="text-blue-600 hover:text-blue-900 transition"
                                                title="Edit Nomor WhatsApp Orang Tua">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['asal_kabkota']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($this->getNamaKabupaten($item->siswa_kabupaten)); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['jenis_sekolah']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_sekolah_asal): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php echo e($item->jenis_sekolah_asal === 'madrasah' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800'); ?>">
                                                <?php echo e(ucfirst($item->jenis_sekolah_asal)); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-500">-</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nsm_npsn']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_sekolah_asal === 'madrasah'): ?>
                                            <div class="text-xs text-gray-500">NSM:</div>
                                            <div class="font-medium"><?php echo e($item->nsm_sekolah_asal ?? '-'); ?></div>
                                        <?php elseif($item->jenis_sekolah_asal === 'umum'): ?>
                                            <div class="text-xs text-gray-500">NPSN:</div>
                                            <div class="font-medium"><?php echo e($item->npsn_sekolah_asal ?? '-'); ?></div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['asal_sekolah']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo e($item->asal_sekolah ?? '-'); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->alamat_sekolah): ?>
                                            <div class="text-xs text-gray-500"><?php echo e($item->alamat_sekolah); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nama_ayah']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($item->nama_ayah ?? '-'); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['nama_ibu']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($item->nama_ibu ?? '-'); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_psikologi']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusPsikologi = $item->tesPsikologiPendaftar->status_verifikasi ?? 'belum_upload';
                                            $psikologiColors = ['belum_upload' => 'gray', 'menunggu' => 'yellow', 'ditolak' => 'red', 'disetujui' => 'green'];
                                            $psikologiLabels = ['belum_upload' => 'Belum Upload', 'menunggu' => 'Menunggu', 'ditolak' => 'Ditolak', 'disetujui' => 'Disetujui'];
                                            $psiColor = $psikologiColors[$statusPsikologi] ?? 'gray';
                                            $psiLabel = $psikologiLabels[$statusPsikologi] ?? $statusPsikologi;
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($psiColor); ?>-100 text-<?php echo e($psiColor); ?>-800"><?php echo e($psiLabel); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            // Map actual status to registration phase status
                                            $displayStatus = $item->status_pendaftaran;
                                            $registrationStatuses = ['menunggu_verifikasi', 'lengkapi_data', 'perbaiki_data', 'data_ditolak', 'data_disetujui'];

                                            // If status is a graduation status, it implies Data Disetujui for registration phase
                                            if (in_array($item->status_pendaftaran, ['lulus', 'diterima', 'tidak_lulus', 'ditolak', 'cadangan'])) {
                                                $displayStatus = 'data_disetujui';
                                            }
                                            // Legacy/Other mapping
                                            if ($item->status_pendaftaran == 'menunggu' || $item->status_pendaftaran == 'verifikasi') $displayStatus = 'menunggu_verifikasi';
                                            if ($item->status_pendaftaran == 'draft') $displayStatus = 'draft';

                                            $statusColors = [
                                                'menunggu_verifikasi' => 'yellow',
                                                'lengkapi_data' => 'orange',
                                                'perbaiki_data' => 'orange',
                                                'data_ditolak' => 'red',
                                                'data_disetujui' => 'green',
                                                'draft' => 'gray',
                                            ];
                                            $statusLabels = [
                                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                                'lengkapi_data' => 'Lengkapi Data',
                                                'perbaiki_data' => 'Perbaiki Data',
                                                'data_ditolak' => 'Data Ditolak',
                                                'data_disetujui' => 'Data Disetujui',
                                                'draft' => 'Draft',
                                            ];

                                            $color = $statusColors[$displayStatus] ?? 'gray';
                                            $label = $statusLabels[$displayStatus] ?? $displayStatus;
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800">
                                            <?php echo e($label); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_keahlian']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $hasKeahlian = $item->keahlians && $item->keahlians->count() > 0;
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasKeahlian): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Terisi (<?php echo e($item->keahlians->count()); ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                                Kosong
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_tahfidz']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $hasTahfidz = $item->tahfidz && (
                                                !empty($item->tahfidz->juz_alquran_dihafal) ||
                                                !empty($item->tahfidz->file_bukti_syahadah) ||
                                                !empty($item->tahfidz->file_bukti_tahsin)
                                            );
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasTahfidz): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Terisi
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                                Kosong
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_tes']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusTesColors = [
                                                'menunggu' => 'yellow',
                                                'diverifikasi' => 'blue',
                                                'lulus' => 'green',
                                                'diterima' => 'green',
                                                'tidak_lulus' => 'red',
                                                'ditolak' => 'red',
                                                'cadangan' => 'orange',
                                            ];
                                            $statusTesLabels = [
                                                'menunggu' => 'Menunggu',
                                                'diverifikasi' => 'Diverifikasi',
                                                'lulus' => 'LULUS',
                                                'diterima' => 'LULUS',
                                                'tidak_lulus' => 'TIDAK LULUS',
                                                'ditolak' => 'TIDAK LULUS',
                                                'cadangan' => 'CADANGAN',
                                            ];
                                            $tesColor = $statusTesColors[$item->status_pendaftaran] ?? 'gray';
                                            $tesLabel = $statusTesLabels[$item->status_pendaftaran] ?? 'Belum Ditentukan';
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-<?php echo e($tesColor); ?>-100 text-<?php echo e($tesColor); ?>-800">
                                            <?php echo e($tesLabel); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['setuju_daftar_ulang']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($item->status_pendaftaran, ['lulus', 'diterima'])): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->setuju_daftar_ulang): ?>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        ✓ Sudah Setuju
                                                    </span>
                                                    <button
                                                        wire:click="batalkanPersetujuanDaftarUlang(<?php echo e($item->id); ?>)"
                                                        wire:loading.attr="disabled"
                                                        onclick="return confirm('Yakin ingin membatalkan persetujuan daftar ulang?')"
                                                        class="text-xs text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded hover:bg-red-100 transition disabled:opacity-50"
                                                        title="Batalkan Persetujuan">
                                                        <span wire:loading.remove wire:target="batalkanPersetujuanDaftarUlang(<?php echo e($item->id); ?>)">✗ Batalkan</span>
                                                        <span wire:loading wire:target="batalkanPersetujuanDaftarUlang(<?php echo e($item->id); ?>)">...</span>
                                                    </button>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->tanggal_setuju_daftar_ulang): ?>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <?php echo e($item->tanggal_setuju_daftar_ulang->format('d/m/Y H:i')); ?>

                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Persetujuan
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                -
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_du_madrasah']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusDuMadrasah = $item->daftarUlang->status ?? 'menunggu';
                                            $duMadrasahColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $duMadrasahLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorDuMadrasah = $duMadrasahColors[$statusDuMadrasah] ?? 'bg-gray-100 text-gray-800';
                                            $labelDuMadrasah = $duMadrasahLabels[$statusDuMadrasah] ?? ucfirst($statusDuMadrasah);
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($colorDuMadrasah); ?>">
                                            <?php echo e($labelDuMadrasah); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_du_seragam']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusDuSeragam = $item->seragam->status_seragam ?? 'menunggu';
                                            $duSeragamColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $duSeragamLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorDuSeragam = $duSeragamColors[$statusDuSeragam] ?? 'bg-gray-100 text-gray-800';
                                            $labelDuSeragam = $duSeragamLabels[$statusDuSeragam] ?? ucfirst($statusDuSeragam);
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($colorDuSeragam); ?>">
                                            <?php echo e($labelDuSeragam); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_ukuran']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusUkuran = $item->seragam->status_ukuran ?? 'menunggu';
                                            $ukuranColors = [
                                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                                'disetujui' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                'perbaiki' => 'bg-orange-100 text-orange-800',
                                            ];
                                            $ukuranLabels = [
                                                'menunggu' => 'Menunggu',
                                                'disetujui' => 'Disetujui',
                                                'ditolak' => 'Ditolak',
                                                'perbaiki' => 'Perbaiki',
                                            ];
                                            $colorUkuran = $ukuranColors[$statusUkuran] ?? 'bg-gray-100 text-gray-800';
                                            $labelUkuran = $ukuranLabels[$statusUkuran] ?? ucfirst($statusUkuran);
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($colorUkuran); ?>">
                                            <?php echo e($labelUkuran); ?>

                                        </span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['status_daftar_ulang']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->daftarUlang): ?>
                                            <?php
                                                $daftarUlangColors = [
                                                    'belum' => 'gray',
                                                    'proses' => 'yellow',
                                                    'selesai' => 'green',
                                                ];
                                                $daftarUlangLabels = [
                                                    'belum' => 'Belum Daftar Ulang',
                                                    'proses' => 'Sedang Proses',
                                                    'selesai' => 'Selesai',
                                                ];
                                                $duColor = $daftarUlangColors[$item->daftarUlang->status] ?? 'gray';
                                                $duLabel = $daftarUlangLabels[$item->daftarUlang->status] ?? $item->daftarUlang->status;
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($duColor); ?>-100 text-<?php echo e($duColor); ?>-800">
                                                <?php echo e($duLabel); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Belum Daftar Ulang
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <!-- Verification columns omitted for brevity but preserved in output -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_rapor']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $statusRapor = $item->verifikasiBerkas->status_rapor ?? 'belum_upload';
                                            $verifColors = ['belum_upload' => 'gray', 'menunggu_verifikasi' => 'yellow', 'tidak_sesuai' => 'red', 'perlu_perbaikan' => 'orange', 'disetujui' => 'green'];
                                            $verifLabels = ['belum_upload' => 'Belum Upload', 'menunggu_verifikasi' => 'Menunggu', 'tidak_sesuai' => 'Tidak Sesuai', 'perlu_perbaikan' => 'Perlu Perbaikan', 'disetujui' => 'Disetujui'];
                                            $color = $verifColors[$statusRapor] ?? 'gray';
                                            $label = $verifLabels[$statusRapor] ?? $statusRapor;
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_akte']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_akte ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kk']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_kk ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_nisn']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_nisn ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_ijazah']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_ijazah ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_sertifikat']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_sertifikat ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kip']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_kip ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_pkh']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_pkh ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['verif_kks']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php $status = $item->verifikasiBerkas->status_kks ?? 'belum_upload'; $color = $verifColors[$status] ?? 'gray'; $label = $verifLabels[$status] ?? $status; ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800"><?php echo e($label); ?></span>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['diperbarui']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-'); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['diperbarui_oleh']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($item->diperbaruiOleh->name ?? '-'); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['tanggal']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($item->tanggal_daftar ? \Carbon\Carbon::parse($item->tanggal_daftar)->format('d M Y') : '-'); ?>

                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['password']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative inline-block">
                                            <input
                                                type="password"
                                                value="••••••••"
                                                readonly
                                                class="text-sm text-gray-900 bg-transparent border-none focus:outline-none w-24 overflow-hidden truncate"
                                                id="password-<?php echo e($item->id); ?>"
                                            >
                                            <button
                                                type="button"
                                                class="absolute right-0 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                                onclick="togglePasswordVisibility('password-<?php echo e($item->id); ?>', '<?php echo e($item->user->password ?? ''); ?>')"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['ubah_password']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            wire:click="showChangePasswordModal(<?php echo e($item->id); ?>)"
                                            class="text-blue-600 hover:text-blue-900 hover:underline"
                                            title="Ubah Password"
                                        >
                                            Ubah
                                        </button>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['catatan']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <div class="max-w-xs">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->catatan_verifikasi): ?>
                                                    <p class="text-sm text-gray-700 truncate" title="<?php echo e($item->catatan_verifikasi); ?>">
                                                        <?php echo e(Str::limit($item->catatan_verifikasi, 50)); ?>

                                                    </p>
                                                <?php else: ?>
                                                    <span class="text-sm text-gray-400 italic">Belum ada catatan</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <button
                                                wire:click="showEditCatatanModal(<?php echo e($item->id); ?>)"
                                                class="text-blue-600 hover:text-blue-900 transition flex-shrink-0"
                                                title="Edit Catatan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['aksi']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex flex-col space-y-2">
                                            <select wire:model.live="statusSelections.<?php echo e($item->id); ?>" class="text-xs rounded border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                                                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                                <option value="lengkapi_data">Lengkapi Data</option>
                                                <option value="perbaiki_data">Perbaiki Data</option>
                                                <option value="data_ditolak">Data Ditolak</option>
                                                <option value="data_disetujui">Data Disetujui</option>
                                            </select>
                                            <button wire:click="updateStatus(<?php echo e($item->id); ?>)" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Ubah Status</button>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin'): ?>
                                                <button wire:click="delete(<?php echo e($item->id); ?>)" wire:confirm="Yakin ingin menghapus data pendaftar ini?" class="text-xs text-red-600 hover:text-red-900">Hapus</button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex flex-col space-y-2">
                                        <button
                                            wire:click="sendManualEmail(<?php echo e($item->id); ?>)"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                            title="Kirim Email Manual">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            Kirim Email
                                        </button>
                                        <button
                                            wire:click="sendManualWhatsApp(<?php echo e($item->id); ?>)"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                            title="Kirim WhatsApp Manual">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            Kirim WhatsApp
                                        </button>
                                    </div>
                                </td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showColumns['riwayat_pesan']): ?>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                            $lastMessage = \App\Models\RiwayatPesan::where('pendaftar_id', $item->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage): ?>
                                            <div class="text-xs">
                                                <div class="flex items-center text-gray-700 mb-1">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage->jenis_pesan === 'email'): ?>
                                                        <svg class="w-3.5 h-3.5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="font-medium">Email</span>
                                                    <?php elseif($lastMessage->jenis_pesan === 'whatsapp'): ?>
                                                        <svg class="w-3.5 h-3.5 text-green-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347Z"/>
                                                        </svg>
                                                        <span class="font-medium">WhatsApp</span>
                                                    <?php else: ?>
                                                        <svg class="w-3.5 h-3.5 text-emerald-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                        </svg>
                                                        <span class="font-medium">Pesan</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <div class="mb-1">
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold <?php echo e($lastMessage->getStatusBadgeClass()); ?>">
                                                        <?php echo e($lastMessage->getStatusLabel()); ?>

                                                    </span>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage->sent_at): ?>
                                                    <div class="text-gray-600">
                                                        <?php echo e($lastMessage->sent_at->diffForHumans()); ?>

                                                    </div>
                                                    <div class="text-gray-500 text-[10px] mt-0.5">
                                                        <?php echo e($lastMessage->sent_at->format('d/m/Y H:i')); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-gray-500 text-[10px] mt-0.5">
                                                        <?php echo e($lastMessage->created_at->format('d/m/Y H:i')); ?>

                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage->user): ?>
                                                    <div class="text-gray-500 text-[10px]">
                                                        oleh: <?php echo e($lastMessage->user->name); ?>

                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <!-- Ganti Jalur Column -->
                                <td class="px-6 py-4" x-data="{ selectedJalur: <?php echo e($item->jalur_pendaftaran_id); ?> }">
                                    <div class="flex flex-col gap-2 min-w-[180px]">
                                        <!-- Dropdown Jalur -->
                                        <select
                                            x-model="selectedJalur"
                                            class="text-xs border border-gray-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <option value="<?php echo e($jalur->id); ?>" <?php echo e($item->jalur_pendaftaran_id == $jalur->id ? 'selected' : ''); ?>>
                                                    <?php echo e($jalur->nama); ?>

                                                </option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>

                                        <!-- Generate Button -->
                                        <button
                                            @click="if(confirm('Yakin ingin mengganti jalur pendaftaran? Nomor pendaftaran akan di-generate ulang.')) { $wire.gantiJalur(<?php echo e($item->id); ?>, selectedJalur) }"
                                            :disabled="selectedJalur == <?php echo e($item->jalur_pendaftaran_id); ?>"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-md transition-colors duration-150"
                                            :class="selectedJalur == <?php echo e($item->jalur_pendaftaran_id); ?>

                                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                                : 'bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1'">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Generate
                                        </button>

                                        <!-- Current Info -->
                                        <div class="text-[10px] text-gray-500 text-center">
                                            <span class="font-medium">Saat ini:</span> <?php echo e($item->nomor_pendaftaran); ?>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="30" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Belum ada data pendaftar</p>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($pendaftar->links()); ?>

            </div>
        </div>
    </div>

    <!-- Script for Password Toggle and Column Filter (Keep existing) -->
    <script>
        function togglePasswordVisibility(passwordFieldId, hashedPassword) {
            const passwordField = document.getElementById(passwordFieldId);
            const button = passwordField.parentNode.querySelector('button');
            const icon = button.querySelector('svg');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordField.value = hashedPassword;
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>`;
            } else {
                passwordField.type = 'password';
                passwordField.value = '••••••••';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>`;
            }
        }

        function columnFilter() {
            return {
                open: false,
                init() {
                    // Load saved column preferences from localStorage
                    this.$nextTick(() => {
                        const savedColumns = localStorage.getItem('admin_pendaftar_column_preferences');
                        if (savedColumns) {
                            try {
                                const columns = JSON.parse(savedColumns);
                                Object.keys(columns).forEach(key => {
                                    this.$wire.set('showColumns.' + key, columns[key]);
                                });
                            } catch (e) {
                                console.error('Error loading column preferences:', e);
                            }
                        }
                    });
                },
                toggleColumn(columnName, isChecked) {
                    // Save individual column preference to localStorage
                    let savedColumns = {};
                    try {
                        const saved = localStorage.getItem('admin_pendaftar_column_preferences');
                        if (saved) savedColumns = JSON.parse(saved);
                    } catch (e) {
                        console.error('Error parsing saved columns:', e);
                    }
                    savedColumns[columnName] = isChecked;
                    localStorage.setItem('admin_pendaftar_column_preferences', JSON.stringify(savedColumns));
                },
                checkAll() {
                    // Check all columns
                    const columns = <?php echo json_encode(array_keys($showColumns), 15, 512) ?>;
                    const allChecked = {};
                    columns.forEach(key => {
                        this.$wire.set('showColumns.' + key, true);
                        allChecked[key] = true;
                    });
                    // Save to localStorage
                    localStorage.setItem('admin_pendaftar_column_preferences', JSON.stringify(allChecked));
                },
                uncheckAll() {
                    // Uncheck all columns
                    const columns = <?php echo json_encode(array_keys($showColumns), 15, 512) ?>;
                    const allUnchecked = {};
                    columns.forEach(key => {
                        this.$wire.set('showColumns.' + key, false);
                        allUnchecked[key] = false;
                    });
                    // Save to localStorage
                    localStorage.setItem('admin_pendaftar_column_preferences', JSON.stringify(allUnchecked));
                }
            }
        }
    </script>

    <!-- Send Message Modal -->
    <div x-data="{ show: <?php if ((object) ('isSendMessageModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isSendMessageModalOpen'->value()); ?>')<?php echo e('isSendMessageModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isSendMessageModalOpen'); ?>')<?php endif; ?> }" x-show="show" x-cloak>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeSendMessageModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[10000]" role="dialog" aria-modal="true">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" :class="$wire.messageType === 'email' ? 'text-blue-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="$wire.messageType === 'email'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            <path x-show="$wire.messageType === 'whatsapp'" fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                        </svg>
                        <span x-text="$wire.messageType === 'email' ? 'Kirim Email Manual' : 'Kirim WhatsApp Manual'"></span>
                    </h3>

                    <div class="space-y-4">
                        <!-- Template Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Template</label>
                            <select wire:model.live="selectedMessageTemplate" wire:change="loadMessageTemplate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                <option value="">-- Pilih Template --</option>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->getAvailableTemplates(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <optgroup label="<?php echo e($group['label']); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($option['value']); ?>"><?php echo e($option['label']); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </optgroup>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>

                        <!-- Catatan (untuk placeholder) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                            <input type="text" wire:model="messageNote" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Catatan tambahan untuk placeholder {catatan}">
                            <p class="text-xs text-gray-500 mt-1">Akan menggantikan placeholder {catatan} dalam template</p>
                        </div>

                        <!-- Subject (hanya untuk email) -->
                        <div x-show="$wire.messageType === 'email'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject Email</label>
                            <input type="text" wire:model="messageSubject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Subject email">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['messageSubject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Message Body -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                            <textarea wire:model="messageBody" rows="8" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm" placeholder="Isi pesan..."></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['messageBody'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Anda dapat mengedit pesan sebelum dikirim</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        wire:click="sendMessage"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        :class="$wire.messageType === 'email' ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span x-text="$wire.messageType === 'email' ? 'Kirim Email' : 'Kirim WhatsApp'"></span>
                    </button>
                    <button
                        type="button"
                        wire:click="closeSendMessageModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal (Keep existing) -->
    <div x-data="{ show: <?php if ((object) ('isChangePasswordModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isChangePasswordModalOpen'->value()); ?>')<?php echo e('isChangePasswordModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isChangePasswordModalOpen'); ?>')<?php endif; ?> }" x-show="show" x-cloak>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeChangePasswordModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]" role="dialog" aria-modal="true" aria-labelledby="change-password-modal">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="change-password-modal">
                            Ubah Password Pendaftar
                        </h3>
                        <div class="space-y-4" x-data="{ showPassword: false, showConfirmPassword: false }">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        wire:model="new_password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 pr-10"
                                        placeholder="Masukkan password baru"
                                    >
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        wire:model="new_password_confirmation"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 pr-10"
                                        placeholder="Konfirmasi password baru"
                                    >
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            wire:click="updatePassword"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Simpan
                        </button>
                        <button
                            type="button"
                            wire:click="closeChangePasswordModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Telepon Ortu Modal -->
    <div x-data="{ show: <?php if ((object) ('isEditTeleponOrtuModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isEditTeleponOrtuModalOpen'->value()); ?>')<?php echo e('isEditTeleponOrtuModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isEditTeleponOrtuModalOpen'); ?>')<?php endif; ?> }" x-show="show" x-cloak>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeEditTeleponOrtuModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-[10000]" role="dialog" aria-modal="true" aria-labelledby="edit-telepon-ortu-modal">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="edit-telepon-ortu-modal">
                        Edit Nomor WhatsApp Orang Tua
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp Orang Tua</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    wire:model="editTeleponOrtu"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="Contoh: 081234567890 atau +6281234567890"
                                    maxlength="15"
                                >
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editTeleponOrtu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Format: Awali dengan 08 atau +62</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        wire:click="updateTeleponOrtu"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Simpan
                    </button>
                    <button
                        type="button"
                        wire:click="closeEditTeleponOrtuModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Catatan Modal -->
    <div x-data="{ show: <?php if ((object) ('isEditCatatanModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isEditCatatanModalOpen'->value()); ?>')<?php echo e('isEditCatatanModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isEditCatatanModalOpen'); ?>')<?php endif; ?> }" x-show="show" x-cloak>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeEditCatatanModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[10000]" role="dialog" aria-modal="true" aria-labelledby="edit-catatan-modal">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-catatan-modal">
                            Edit Catatan Verifikasi
                        </h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan untuk Pendaftar
                                <span class="text-gray-500 font-normal">(Opsional)</span>
                            </label>
                            <textarea
                                wire:model="editCatatan"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                placeholder="Tulis catatan untuk pendaftar, misalnya: apa yang perlu diperbaiki atau dilengkapi..."
                                maxlength="1000"
                            ></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editCatatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500">
                                    💡 Catatan ini akan ditampilkan di dashboard pendaftar untuk memberikan feedback yang jelas.
                                </p>
                                <p class="text-xs text-gray-500">
                                    <span x-data="{ length: <?php if ((object) ('editCatatan') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('editCatatan'->value()); ?>')<?php echo e('editCatatan'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('editCatatan'); ?>')<?php endif; ?>.live }" x-text="(length || '').length"></span>/1000
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        wire:click="updateCatatan"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Catatan
                    </button>
                    <button
                        type="button"
                        wire:click="closeEditCatatanModal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/livewire/admin/pendaftar.blade.php ENDPATH**/ ?>