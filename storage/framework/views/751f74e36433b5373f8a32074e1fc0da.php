<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div
            x-data="{ show: false, message: '' }"
            x-on:saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center"
            style="display: none;">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message"></span>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Pengumuman</h3>
                </div>
                <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-emerald-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Pengumuman
                </button>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'tanggal_publikasi','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'tanggal_publikasi','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            Tanggal
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
                        <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'judul','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'judul','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            Judul
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
                        <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'kategori','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'kategori','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            Kategori
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
                        <?php if (isset($component)) { $__componentOriginal9f94e1f2665f26428c518049f3c9052b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f94e1f2665f26428c518049f3c9052b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sortable-header','data' => ['field' => 'aktif','sortField' => $sortField,'sortDirection' => $sortDirection,'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sortable-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => 'aktif','sort-field' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortField),'sort-direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sortDirection),'class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            Status
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
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($item->tanggal_publikasi)->format('d M Y')); ?>

                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($item->judul); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e(Str::limit(strip_tags($item->konten), 50)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php echo e($item->kategori === 'jadwal' ? 'bg-blue-100 text-blue-800' : ($item->kategori === 'pengumuman_lulus' ? 'bg-green-100 text-green-800' : ($item->kategori === 'pendaftar' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))); ?>">
                                    <?php echo e($item->kategori === 'jadwal' ? 'Jadwal' : ($item->kategori === 'pengumuman_lulus' ? 'Pengumuman Lulus' : ($item->kategori === 'pendaftar' ? 'Pendaftar' : 'Umum'))); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($item->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($item->aktif ? 'Aktif' : 'Non-Aktif'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit(<?php echo e($item->id); ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data pengumuman.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                <?php echo e($data->links()); ?>

            </div>
        </div>

        <!-- Modal Form -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
            <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
                <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full z-[10000]">
                    <form wire:submit.prevent="<?php echo e($isEdit ? 'update' : 'store'); ?>">
                        <!-- Modal Header -->
                        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white" id="modal-title">
                                            <?php echo e($isEdit ? 'Edit Pengumuman' : 'Tambah Pengumuman Baru'); ?>

                                        </h3>
                                        <p class="text-emerald-100 text-sm"><?php echo e($isEdit ? 'Perbarui informasi pengumuman' : 'Buat pengumuman untuk calon siswa'); ?></p>
                                    </div>
                                </div>
                                <button type="button" wire:click="closeModal" class="text-white/80 hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="px-6 py-6 space-y-6">
                            <!-- Informasi Utama Section -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Informasi Utama
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Judul Pengumuman <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            wire:model="judul"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                            placeholder="Contoh: Pengumuman Hasil Seleksi <?php echo e($appAcronym); ?> 2025"
                                        >
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            <?php echo e($message); ?>

                                        </span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Isi Pengumuman <span class="text-red-500">*</span>
                                        </label>
                                        <textarea
                                            wire:model="konten"
                                            rows="8"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 resize-y"
                                            placeholder="Tulis detail pengumuman di sini... Gunakan bahasa yang jelas dan mudah dipahami."
                                        ></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Minimum 10 karakter. Gunakan paragraf untuk memudahkan pembacaan.</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['konten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            <?php echo e($message); ?>

                                        </span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengaturan Section -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                    Pengaturan Pengumuman
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Kategori <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select
                                                wire:model="kategori"
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 appearance-none pr-10"
                                            >
                                                <option value="umum">📢 Umum</option>
                                                <option value="jadwal">📅 Jadwal</option>
                                                <option value="pengumuman_lulus">🎉 Pengumuman Lulus</option>
                                                <option value="pendaftar">👥 Pendaftar</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Pilih kategori untuk pengelompokan pengumuman</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Tanggal Publikasi <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            wire:model="tanggal_publikasi"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Tanggal pengumuman akan ditampilkan</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Urutan Tampilan
                                        </label>
                                        <input
                                            type="number"
                                            wire:model="urutan"
                                            min="0"
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                            placeholder="0"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Angka kecil = tampil lebih awal</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Status Publikasi
                                        </label>
                                        <div class="flex items-center h-10 bg-white border border-gray-300 rounded-lg px-4 mt-1">
                                            <input
                                                type="checkbox"
                                                wire:model="aktif"
                                                id="aktif"
                                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                            >
                                            <label for="aktif" class="ml-3 flex items-center cursor-pointer">
                                                <span class="text-sm font-medium text-gray-700">Aktifkan Pengumuman</span>
                                                <svg class="w-4 h-4 ml-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Centang untuk menampilkan di halaman publik</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row-reverse gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <?php echo e($isEdit ? 'Perbarui Pengumuman' : 'Simpan Pengumuman'); ?>

                            </button>
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\pengumuman.blade.php ENDPATH**/ ?>