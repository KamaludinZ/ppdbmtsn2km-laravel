<div>
    <!-- Global Loading Overlay -->
    <div wire:loading class="fixed inset-0 bg-gray-900 bg-opacity-50 z-[9999] flex items-center justify-center">
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

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-sm sm:text-lg font-bold text-white uppercase tracking-wider">Filter Kelulusan</h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex flex-col lg:flex-row justify-between gap-4">
                    <div class="flex-1">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, NISN, atau no. pendaftaran..." class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                        <select wire:model.live="filterJalur" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Jalur</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <select wire:model.live="filterStatus" class="w-full sm:w-auto text-sm rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="menunggu_verifikasi">Menunggu</option>
                            <option value="lulus">Lulus</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                            <option value="cadangan">Cadangan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview - Status Kelulusan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Total Pendaftar</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['total'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Menunggu</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['menunggu'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lulus -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Lulus</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['lulus'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cadangan -->
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Cadangan</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['cadangan'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tidak Lulus -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium opacity-90">Tidak Lulus</p>
                        <p class="text-3xl font-bold mt-2"><?php echo e(number_format($stats['tidak_lulus'] ?? 0)); ?></p>
                    </div>
                    <div class="bg-black bg-opacity-10 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-emerald-600 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-sm sm:text-lg font-bold text-white uppercase tracking-wider">Daftar Pendaftar & Status Kelulusan</h3>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['total'] > 0): ?>
                <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-2 flex-wrap">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-white text-xs sm:text-sm mr-1 sm:mr-2">Aksi Massal:</span>
                        <button
                            wire:click="bulkUpdateStatus('lulus')"
                            wire:loading.attr="disabled"
                            onclick="return confirm('Yakin ingin mengubah SEMUA data yang terfilter (<?php echo e($stats['total']); ?> pendaftar) menjadi LULUS?')"
                            class="text-white bg-green-500 hover:bg-green-600 px-2 sm:px-3 py-1 rounded text-xs font-medium transition disabled:opacity-50">
                            Tandai Lulus
                        </button>
                        <button
                            wire:click="bulkUpdateStatus('cadangan')"
                            wire:loading.attr="disabled"
                            onclick="return confirm('Yakin ingin mengubah SEMUA data yang terfilter (<?php echo e($stats['total']); ?> pendaftar) menjadi CADANGAN?')"
                            class="text-white bg-orange-500 hover:bg-orange-600 px-2 sm:px-3 py-1 rounded text-xs font-medium transition disabled:opacity-50">
                            Tandai Cadangan
                        </button>
                        <button
                            wire:click="bulkUpdateStatus('tidak_lulus')"
                            wire:loading.attr="disabled"
                            onclick="return confirm('Yakin ingin mengubah SEMUA data yang terfilter (<?php echo e($stats['total']); ?> pendaftar) menjadi TIDAK LULUS?')"
                            class="text-white bg-red-500 hover:bg-red-600 px-2 sm:px-3 py-1 rounded text-xs font-medium transition disabled:opacity-50">
                            Tandai Tidak Lulus
                        </button>
                    </div>

                    <span class="hidden sm:inline text-white text-sm mx-2">|</span>

                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-white text-xs sm:text-sm">Kirim Pesan:</span>
                        <button
                            wire:click="openBulkSendEmail"
                            class="inline-flex items-center text-white bg-blue-500 hover:bg-blue-600 px-2 sm:px-3 py-1 rounded text-xs font-medium transition">
                            <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Kirim </span>Email
                        </button>
                        <button
                            wire:click="openBulkSendWhatsApp"
                            class="inline-flex items-center text-white bg-green-500 hover:bg-green-600 px-2 sm:px-3 py-1 rounded text-xs font-medium transition">
                            <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span class="hidden sm:inline">Kirim </span>WhatsApp
                        </button>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Nomor Pendaftaran</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendaftar</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jalur</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status Saat Ini</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Kelulusan</th>
                        <th class="px-3 sm:px-6 py-2 sm:py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Pesan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm font-medium text-gray-900"><?php echo e($item->nomor_pendaftaran); ?></div>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm font-medium text-gray-900"><?php echo e($item->nama_lengkap); ?></div>
                                <div class="text-xs sm:text-sm text-gray-500">NISN: <?php echo e($item->nisn); ?></div>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->jenis_kelamin): ?>
                                    <span class="px-1 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($item->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'); ?>">
                                        <span class="hidden sm:inline"><?php echo e($item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'); ?></span>
                                        <span class="sm:hidden"><?php echo e($item->jenis_kelamin); ?></span>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs sm:text-sm text-gray-500">-</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                <?php echo e($item->jalurPendaftaran->nama ?? '-'); ?>

                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap">
                                <?php
                                    $colors = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'lulus' => 'bg-green-100 text-green-800',
                                        'tidak_lulus' => 'bg-red-100 text-red-800',
                                        'cadangan' => 'bg-orange-100 text-orange-800',
                                    ];
                                    $color = $colors[$item->status_pendaftaran] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-1 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($color); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $item->status_pendaftaran))); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                <div class="flex flex-wrap gap-1 sm:gap-2">
                                    <button
                                        wire:click="updateStatus(<?php echo e($item->id); ?>, 'lulus')"
                                        wire:loading.attr="disabled"
                                        wire:target="updateStatus(<?php echo e($item->id); ?>, 'lulus')"
                                        onclick="return confirm('Yakin ingin mengubah status menjadi LULUS?')"
                                        class="text-green-600 hover:text-green-900 bg-green-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded hover:bg-green-100 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium text-xs">
                                        <span wire:loading.remove wire:target="updateStatus(<?php echo e($item->id); ?>, 'lulus')">
                                            <span class="hidden sm:inline">✓ </span>Lulus
                                        </span>
                                        <span wire:loading wire:target="updateStatus(<?php echo e($item->id); ?>, 'lulus')">...</span>
                                    </button>
                                    <button
                                        wire:click="updateStatus(<?php echo e($item->id); ?>, 'cadangan')"
                                        wire:loading.attr="disabled"
                                        wire:target="updateStatus(<?php echo e($item->id); ?>, 'cadangan')"
                                        onclick="return confirm('Yakin ingin mengubah status menjadi CADANGAN?')"
                                        class="text-orange-600 hover:text-orange-900 bg-orange-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded hover:bg-orange-100 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium text-xs">
                                        <span wire:loading.remove wire:target="updateStatus(<?php echo e($item->id); ?>, 'cadangan')">
                                            <span class="hidden sm:inline">⚠ </span>Cadangan
                                        </span>
                                        <span wire:loading wire:target="updateStatus(<?php echo e($item->id); ?>, 'cadangan')">...</span>
                                    </button>
                                    <button
                                        wire:click="updateStatus(<?php echo e($item->id); ?>, 'tidak_lulus')"
                                        wire:loading.attr="disabled"
                                        wire:target="updateStatus(<?php echo e($item->id); ?>, 'tidak_lulus')"
                                        onclick="return confirm('Yakin ingin mengubah status menjadi TIDAK LULUS?')"
                                        class="text-red-600 hover:text-red-900 bg-red-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium text-xs">
                                        <span wire:loading.remove wire:target="updateStatus(<?php echo e($item->id); ?>, 'tidak_lulus')">
                                            <span class="hidden sm:inline">✗ </span>Tidak<span class="hidden sm:inline"> Lulus</span>
                                        </span>
                                        <span wire:loading wire:target="updateStatus(<?php echo e($item->id); ?>, 'tidak_lulus')">...</span>
                                    </button>
                                    <button
                                        wire:click="updateStatus(<?php echo e($item->id); ?>, 'menunggu_verifikasi')"
                                        wire:loading.attr="disabled"
                                        wire:target="updateStatus(<?php echo e($item->id); ?>, 'menunggu_verifikasi')"
                                        onclick="return confirm('Yakin ingin mereset status?')"
                                        class="text-gray-600 hover:text-gray-900 bg-gray-50 px-2 sm:px-3 py-1 sm:py-1.5 rounded hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium text-xs">
                                        <span wire:loading.remove wire:target="updateStatus(<?php echo e($item->id); ?>, 'menunggu_verifikasi')">
                                            <span class="hidden sm:inline">↻ </span>Reset
                                        </span>
                                        <span wire:loading wire:target="updateStatus(<?php echo e($item->id); ?>, 'menunggu_verifikasi')">...</span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-2 sm:py-4 whitespace-nowrap text-center text-xs sm:text-sm font-medium">
                                <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                    <button
                                        wire:click="sendManualEmail(<?php echo e($item->id); ?>)"
                                        class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition"
                                        title="Kirim Email Manual">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline ml-1">Email</span>
                                    </button>
                                    <button
                                        wire:click="sendManualWhatsApp(<?php echo e($item->id); ?>)"
                                        class="inline-flex items-center justify-center px-2 sm:px-3 py-1 sm:py-2 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition"
                                        title="Kirim WhatsApp Manual">
                                        <svg class="w-3 sm:w-4 h-3 sm:h-4 sm:mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                        </svg>
                                        <span class="hidden sm:inline ml-1">WA</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="7" class="px-3 sm:px-6 py-2 sm:py-4 text-center text-xs sm:text-sm text-gray-500">Tidak ada data pendaftar ditemukan.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            </div>
            <div class="px-3 sm:px-6 py-2 sm:py-4 border-t border-gray-200">
                <?php echo e($pendaftar->links()); ?>

            </div>
        </div>
    </div>

    <!-- Send Message Modal (Per Pendaftar) -->
    <?php echo $__env->make('livewire.admin.partials.send-message-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Bulk Send Message Modal -->
    <?php echo $__env->make('livewire.admin.partials.bulk-send-message-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/livewire/admin/kelulusan.blade.php ENDPATH**/ ?>