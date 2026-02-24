<!-- Tab Data Alamat Content -->
<?php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeAlamat)
    $isEditMode = $editModeAlamat ?? $editMode ?? false;
?>

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Data Alamat</h3>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
        <div class="space-x-2">
            <button wire:click="saveDataAlamat" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('alamat')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    <?php else: ?>
        <?php
            $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
            $isPendaftar = Auth::user()->role === 'pendaftar';
            // Hanya disable untuk role Pendaftar, Admin/Panitia tetap bisa edit
            $shouldDisable = $isApproved && $isPendaftar;
        ?>
        <button
            wire:click="toggleEdit('alamat')"
            <?php if($shouldDisable): ?> disabled title="Data sudah disetujui, tidak dapat diubah" <?php endif; ?>
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition <?php echo e($shouldDisable ? 'opacity-50 cursor-not-allowed' : ''); ?>">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <?php echo e($shouldDisable ? 'Data Sudah Disetujui' : 'Edit Data'); ?>

        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataAlamat" class="space-y-6" x-data="{
        ayah_luar_negeri: <?php if ((object) ('ayah_tinggal_luar_negeri') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ayah_tinggal_luar_negeri'->value()); ?>')<?php echo e('ayah_tinggal_luar_negeri'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ayah_tinggal_luar_negeri'); ?>')<?php endif; ?>,
        ibu_luar_negeri: <?php if ((object) ('ibu_tinggal_luar_negeri') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ibu_tinggal_luar_negeri'->value()); ?>')<?php echo e('ibu_tinggal_luar_negeri'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ibu_tinggal_luar_negeri'); ?>')<?php endif; ?>,
        ibu_sama_ayah: <?php if ((object) ('ibu_sama_dengan_ayah') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ibu_sama_dengan_ayah'->value()); ?>')<?php echo e('ibu_sama_dengan_ayah'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('ibu_sama_dengan_ayah'); ?>')<?php endif; ?>,
        wali_status: <?php if ((object) ('wali_status_wali') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_status_wali'->value()); ?>')<?php echo e('wali_status_wali'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_status_wali'); ?>')<?php endif; ?>,
        wali_luar_negeri: <?php if ((object) ('wali_tinggal_luar_negeri') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_tinggal_luar_negeri'->value()); ?>')<?php echo e('wali_tinggal_luar_negeri'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_tinggal_luar_negeri'); ?>')<?php endif; ?>,
        wali_sama_ayah: <?php if ((object) ('wali_sama_dengan_ayah') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_sama_dengan_ayah'->value()); ?>')<?php echo e('wali_sama_dengan_ayah'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('wali_sama_dengan_ayah'); ?>')<?php endif; ?>,
        status_tempat_tinggal: <?php if ((object) ('status_tempat_tinggal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('status_tempat_tinggal'->value()); ?>')<?php echo e('status_tempat_tinggal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('status_tempat_tinggal'); ?>')<?php endif; ?>,
        ayah_status: '<?php echo e($ayah_status ?? 'masih_hidup'); ?>',
        ibu_status: '<?php echo e($ibu_status ?? 'masih_hidup'); ?>'
    }">

        <!-- ===================== AYAH KANDUNG ===================== -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ayah Kandung
            </h4>

            <!-- Checkbox Tinggal Diluar Negeri -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="ayah_tinggal_luar_negeri" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                </label>
            </div>

            <!-- Info jika status bukan masih hidup -->
            <div x-show="ayah_luar_negeri && ayah_status !== 'masih_hidup'" x-transition class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Alamat ayah tidak perlu diisi karena status ayah: <strong class="ml-1"><?php echo e(ucfirst(str_replace('_', ' ', $ayah_status ?? ''))); ?></strong>
                </p>
            </div>

            <!-- Jika Tinggal Diluar Negeri -->
            <div x-show="ayah_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Kepemilikan Rumah
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model="ayah_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="alamat_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Alamat lengkap di luar negeri"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Info jika status bukan masih hidup (dalam negeri) -->
            <div x-show="!ayah_luar_negeri && ayah_status !== 'masih_hidup'" x-transition class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Alamat ayah tidak perlu diisi karena status ayah: <strong class="ml-1"><?php echo e(ucfirst(str_replace('_', ' ', $ayah_status ?? ''))); ?></strong>
                </p>
            </div>

            <!-- Jika Tinggal Didalam Negeri -->
            <div x-show="!ayah_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Kepemilikan Rumah
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model="ayah_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Provinsi
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="ayah_provinsi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Pilih Provinsi</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $provincesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($province['id']); ?>"><?php echo e($province['name']); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kabupaten/Kota
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="ayah_kabupaten" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" <?php if(!$ayah_provinsi): ?> disabled <?php endif; ?>>
                        <option value="">Pilih Kabupaten/Kota</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ayahCitiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($city['id']); ?>"><?php echo e($city['name']); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kecamatan
                            <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="ayah_kecamatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" <?php if(!$ayah_kabupaten): ?> disabled <?php endif; ?>>
                            <option value="">Pilih Kecamatan</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ayahDistrictsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($district['id']); ?>"><?php echo e($district['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kelurahan/Desa
                            <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="ayah_kelurahan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" <?php if(!$ayah_kecamatan): ?> disabled <?php endif; ?>>
                            <option value="">Pilih Kelurahan/Desa</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ayahVillagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($village['id']); ?>"><?php echo e($village['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_kelurahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RT
                            <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ayah_rt" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="001">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RW
                            <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ayah_rw" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="001">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Pos
                            <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ayah_kode_pos" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Otomatis terisi atau input manual">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                        <span x-show="ayah_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="alamat_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===================== IBU KANDUNG ===================== -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ibu Kandung
            </h4>

            <!-- Checkbox Tinggal Diluar Negeri -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.live="ibu_tinggal_luar_negeri" class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                </label>
            </div>

            <!-- Checkbox Sama dengan Ayah Kandung -->
            <div class="mb-4" x-show="!ibu_luar_negeri">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           wire:model.live="ibu_sama_dengan_ayah"
                           @change="setTimeout(() => $wire.copyAyahToIbu(), 100)"
                           class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200">
                    <span class="ml-2 text-sm font-medium text-gray-700">Sama dengan Ayah Kandung</span>
                </label>
            </div>

            <!-- Info jika status bukan masih hidup (luar negeri) -->
            <div x-show="ibu_luar_negeri && ibu_status !== 'masih_hidup'" x-transition class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Alamat ibu tidak perlu diisi karena status ibu: <strong class="ml-1"><?php echo e(ucfirst(str_replace('_', ' ', $ibu_status ?? ''))); ?></strong>
                </p>
            </div>

            <!-- Jika Tinggal Diluar Negeri -->
            <div x-show="ibu_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Kepemilikan Rumah
                        <span x-show="ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model="ibu_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                        <span x-show="ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="ibu_alamat" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Alamat lengkap di luar negeri"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Info jika status bukan masih hidup (dalam negeri) -->
            <div x-show="!ibu_luar_negeri && ibu_status !== 'masih_hidup'" x-transition class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-600 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Alamat ibu tidak perlu diisi karena status ibu: <strong class="ml-1"><?php echo e(ucfirst(str_replace('_', ' ', $ibu_status ?? ''))); ?></strong>
                </p>
            </div>

            <!-- Jika Tinggal Didalam Negeri -->
            <div x-show="!ibu_luar_negeri" x-transition class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Kepemilikan Rumah
                        <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model="ibu_status_kepemilikan_rumah" :disabled="ibu_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">Pilih Status Kepemilikan</option>
                        <option value="Milik Sendiri">Milik Sendiri</option>
                        <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                        <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                        <option value="Rumah Dinas">Rumah Dinas</option>
                        <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Provinsi
                        <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="ibu_provinsi" :disabled="ibu_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">Pilih Provinsi</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $provincesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($province['id']); ?>"><?php echo e($province['name']); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kabupaten/Kota
                        <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <select wire:model.live="ibu_kabupaten" :disabled="ibu_sama_ayah" <?php if(!$ibu_provinsi): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">Pilih Kabupaten/Kota</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ibuCitiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($city['id']); ?>"><?php echo e($city['name']); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kecamatan
                            <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="ibu_kecamatan" :disabled="ibu_sama_ayah" <?php if(!$ibu_kabupaten): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                            <option value="">Pilih Kecamatan</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ibuDistrictsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($district['id']); ?>"><?php echo e($district['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kelurahan/Desa
                            <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="ibu_kelurahan" :disabled="ibu_sama_ayah" <?php if(!$ibu_kecamatan): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                            <option value="">Pilih Kelurahan/Desa</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $ibuVillagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($village['id']); ?>"><?php echo e($village['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_kelurahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RT
                            <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ibu_rt" :disabled="ibu_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="001">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RW
                            <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ibu_rw" :disabled="ibu_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="001">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Pos
                            <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="ibu_kode_pos" :disabled="ibu_sama_ayah" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Otomatis terisi atau input manual">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap
                        <span x-show="!ibu_sama_ayah && ibu_status === 'masih_hidup'" class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="ibu_alamat" :disabled="ibu_sama_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===================== DATA WALI ===================== -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Wali
            </h4>

            <!-- Status Wali -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Wali <span class="text-red-500">*</span></label>
                <select wire:model.live="wali_status_wali" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                    <option value="">Pilih Status Wali</option>
                    <option value="sama_dengan_ayah">Sama dengan Ayah Kandung</option>
                    <option value="sama_dengan_ibu">Sama dengan Ibu Kandung</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_status_wali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="text-xs text-gray-500 mt-1">Jika memilih "Sama dengan Ayah/Ibu", field alamat akan terisi otomatis</p>
            </div>

            <!-- Jika Status Wali = Lainnya -->
            <div x-show="wali_status === 'lainnya'" x-transition>
                <!-- Checkbox Tinggal Diluar Negeri -->
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model.live="wali_tinggal_luar_negeri" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200">
                        <span class="ml-2 text-sm font-medium text-gray-700">Tinggal Diluar Negeri</span>
                    </label>
                </div>

                <!-- Checkbox Sama dengan Ayah Kandung -->
                <div class="mb-4" x-show="!wali_luar_negeri">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                               wire:model.live="wali_sama_dengan_ayah"
                               @change="setTimeout(() => $wire.copyAyahToWali(), 100)"
                               class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200">
                        <span class="ml-2 text-sm font-medium text-gray-700">Sama dengan Ayah Kandung</span>
                    </label>
                </div>

                <!-- Jika Tinggal Diluar Negeri -->
                <div x-show="wali_luar_negeri" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status Kepemilikan Rumah
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <select wire:model="wali_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat_wali" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Alamat lengkap di luar negeri"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_wali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Jika Tinggal Didalam Negeri -->
                <div x-show="!wali_luar_negeri" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status Kepemilikan Rumah
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <select wire:model="wali_status_kepemilikan_rumah" :disabled="wali_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi
                            <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="wali_provinsi" :disabled="wali_sama_ayah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Provinsi</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $provincesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($province['id']); ?>"><?php echo e($province['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kabupaten/Kota
                            <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="wali_kabupaten" :disabled="wali_sama_ayah" <?php if(!$wali_provinsi): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Kabupaten/Kota</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $waliCitiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($city['id']); ?>"><?php echo e($city['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kecamatan
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="wali_kecamatan" :disabled="wali_sama_ayah" <?php if(!$wali_kabupaten): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <option value="">Pilih Kecamatan</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $waliDistrictsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($district['id']); ?>"><?php echo e($district['name']); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kelurahan/Desa
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="wali_kelurahan" :disabled="wali_sama_ayah" <?php if(!$wali_kecamatan): ?> disabled <?php endif; ?> class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <option value="">Pilih Kelurahan/Desa</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $waliVillagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($village['id']); ?>"><?php echo e($village['name']); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_kelurahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                RT
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_rt" :disabled="wali_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="001">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                RW
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_rw" :disabled="wali_sama_ayah" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="001">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kode Pos
                                <span x-show="wali_status === 'lainnya' && !wali_sama_ayah" class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="wali_kode_pos" :disabled="wali_sama_ayah" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Otomatis terisi atau input manual">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat Lengkap
                            <span x-show="wali_status === 'lainnya'" class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="alamat_wali" :disabled="wali_sama_ayah" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_wali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== DATA SISWA ===================== -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Alamat Tempat Tinggal Siswa
            </h4>

            <div class="space-y-4">
                <!-- Status Tempat Tinggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Tempat Tinggal <span class="text-red-500">*</span></label>
                    <select wire:model.live="status_tempat_tinggal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                        <option value="">Pilih Status Tempat Tinggal</option>
                        <option value="Tinggal dengan Ayah Kandung">Tinggal dengan Ayah Kandung</option>
                        <option value="Tinggal dengan Ibu Kandung">Tinggal dengan Ibu Kandung</option>
                        <option value="Tinggal dengan Wali">Tinggal dengan Wali</option>
                        <option value="Ikut Saudara/Kerabat">Ikut Saudara/Kerabat</option>
                        <option value="Asrama Madrasah">Asrama Madrasah</option>
                        <option value="Kontrak/Kost">Kontrak/Kost</option>
                        <option value="Tinggal di Asrama Pesantren">Tinggal di Asrama Pesantren</option>
                        <option value="Panti Asuhan">Panti Asuhan</option>
                        <option value="Rumah Singgah">Rumah Singgah</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status_tempat_tinggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-500 mt-1">Jika tinggal dengan Ayah/Ibu/Wali, alamat akan mengikuti data yang sudah diisi di atas</p>
                </div>

                <!-- Hanya tampilkan form alamat detail jika tidak tinggal dengan orang tua/wali -->
                <div x-show="status_tempat_tinggal && !['Tinggal dengan Ayah Kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali'].includes(status_tempat_tinggal)" x-transition class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kepemilikan Rumah <span class="text-red-500">*</span></label>
                        <select wire:model="siswa_status_kepemilikan_rumah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Milik Sendiri">Milik Sendiri</option>
                            <option value="Rumah Orang Tua">Rumah Orang Tua</option>
                            <option value="Rumah Saudara/Kerabat">Rumah Saudara/Kerabat</option>
                            <option value="Rumah Dinas">Rumah Dinas</option>
                            <option value="Sewa/Kontrak">Sewa/Kontrak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_status_kepemilikan_rumah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <select wire:model.live="siswa_provinsi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Provinsi</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $provincesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($province['id']); ?>"><?php echo e($province['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                        <select wire:model.live="siswa_kabupaten" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required <?php if(!$siswa_provinsi): ?> disabled <?php endif; ?>>
                            <option value="">Pilih Kabupaten/Kota</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $siswaCitiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($city['id']); ?>"><?php echo e($city['name']); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <select wire:model.live="siswa_kecamatan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required <?php if(!$siswa_kabupaten): ?> disabled <?php endif; ?>>
                                <option value="">Pilih Kecamatan</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $siswaDistrictsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($district['id']); ?>"><?php echo e($district['name']); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select wire:model.live="siswa_kelurahan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required <?php if(!$siswa_kecamatan): ?> disabled <?php endif; ?>>
                                <option value="">Pilih Kelurahan/Desa</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $siswaVillagesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($village['id']); ?>"><?php echo e($village['name']); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_kelurahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RT <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_rt" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="001">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">RW <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_rw" maxlength="3" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="001">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Maksimal 3 digit</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="siswa_kode_pos" maxlength="6" class="w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Otomatis terisi atau input manual">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1">Otomatis terisi atau dapat diedit manual</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea wire:model="siswa_alamat" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Jalan, No. Rumah, Gang/Kampung"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siswa_alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Jarak, Transportasi, Waktu Tempuh (selalu tampil) -->
                <div class="border-t pt-4 mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jarak Tempuh (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="jarak_ke_sekolah" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Jarak Tempuh</option>
                            <option value="Kurang dari 5 km">Kurang dari 5 km</option>
                            <option value="5-10 km">5-10 km</option>
                            <option value="11-20 km">11-20 km</option>
                            <option value="21-30 km">21-30 km</option>
                            <option value="Lebih dari 30 km">Lebih dari 30 km</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jarak_ke_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transportasi (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="transportasi" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Transportasi</option>
                            <option value="Jalan Kaki">Jalan Kaki</option>
                            <option value="Sepeda">Sepeda</option>
                            <option value="Sepeda Motor">Sepeda Motor</option>
                            <option value="Mobil Pribadi">Mobil Pribadi</option>
                            <option value="Antar Jemput Sekolah">Antar Jemput Sekolah</option>
                            <option value="Angkutan Umum">Angkutan Umum</option>
                            <option value="Perahu/Sampan">Perahu/Sampan</option>
                            <option value="Kendaraan Pribadi">Kendaraan Pribadi</option>
                            <option value="Kereta Api">Kereta Api</option>
                            <option value="Ojek">Ojek</option>
                            <option value="Andong/Bendi/Sado/Dokar/Delman/Becak">Andong/Bendi/Sado/Dokar/Delman/Becak</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['transportasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Tempuh (Dari Tempat Tinggal ke MTsN 2 Kota Malang) <span class="text-red-500">*</span></label>
                        <select wire:model="waktu_tempuh" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" required>
                            <option value="">Pilih Waktu Tempuh</option>
                            <option value="1-10 menit">1-10 menit</option>
                            <option value="10-19 menit">10-19 menit</option>
                            <option value="20-29 menit">20-29 menit</option>
                            <option value="30-39 menit">30-39 menit</option>
                            <option value="1-2 jam">1-2 jam</option>
                            <option value="Lebih dari 2 jam">Lebih dari 2 jam</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['waktu_tempuh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </form>
<?php else: ?>
    <!-- VIEW MODE -->
    <div class="space-y-6">
        <!-- ===================== AYAH KANDUNG ===================== -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ayah Kandung
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->ayah_tinggal_luar_negeri ? 'Ya' : 'Tidak'); ?></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->ayah_status_kepemilikan_rumah ?? '-'); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pendaftar->ayah_tinggal_luar_negeri): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaProvinsi($pendaftar->ayah_provinsi)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKabupaten($pendaftar->ayah_kabupaten)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKecamatan($pendaftar->ayah_kecamatan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKelurahan($pendaftar->ayah_kelurahan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ayah_rt ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ayah_rw ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ayah_kode_pos ?? '-'); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->alamat_ayah ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <!-- ===================== IBU KANDUNG ===================== -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Alamat Ibu Kandung
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_tinggal_luar_negeri ? 'Ya' : 'Tidak'); ?></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Sama dengan Ayah Kandung</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_sama_dengan_ayah ? 'Ya' : 'Tidak'); ?></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_status_kepemilikan_rumah ?? '-'); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pendaftar->ibu_tinggal_luar_negeri): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaProvinsi($pendaftar->ibu_provinsi)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKabupaten($pendaftar->ibu_kabupaten)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKecamatan($pendaftar->ibu_kecamatan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKelurahan($pendaftar->ibu_kelurahan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_rt ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_rw ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->ibu_kode_pos ?? '-'); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->alamat_ibu ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <!-- ===================== DATA WALI ===================== -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
                <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Alamat Wali
                </h4>
                <?php
                    // Tentukan apakah alamat wali sama dengan ayah atau ibu
                    $waliSamaDenganAyah = ($pendaftar->wali_status_wali === 'sama_dengan_ayah' || $pendaftar->wali_sama_dengan_ayah);
                    $waliSamaDenganIbu = ($pendaftar->wali_status_wali === 'sama_dengan_ibu');

                    // Pilih data yang akan ditampilkan
                    if ($waliSamaDenganAyah) {
                        $displayTinggalLuarNegeri = $pendaftar->ayah_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->ayah_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->ayah_provinsi;
                        $displayKabupaten = $pendaftar->ayah_kabupaten;
                        $displayKecamatan = $pendaftar->ayah_kecamatan;
                        $displayKelurahan = $pendaftar->ayah_kelurahan;
                        $displayRt = $pendaftar->ayah_rt;
                        $displayRw = $pendaftar->ayah_rw;
                        $displayKodePos = $pendaftar->ayah_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_ayah;
                    } elseif ($waliSamaDenganIbu) {
                        $displayTinggalLuarNegeri = $pendaftar->ibu_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->ibu_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->ibu_provinsi;
                        $displayKabupaten = $pendaftar->ibu_kabupaten;
                        $displayKecamatan = $pendaftar->ibu_kecamatan;
                        $displayKelurahan = $pendaftar->ibu_kelurahan;
                        $displayRt = $pendaftar->ibu_rt;
                        $displayRw = $pendaftar->ibu_rw;
                        $displayKodePos = $pendaftar->ibu_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_ibu;
                    } else {
                        $displayTinggalLuarNegeri = $pendaftar->wali_tinggal_luar_negeri;
                        $displayStatusKepemilikan = $pendaftar->wali_status_kepemilikan_rumah;
                        $displayProvinsi = $pendaftar->wali_provinsi;
                        $displayKabupaten = $pendaftar->wali_kabupaten;
                        $displayKecamatan = $pendaftar->wali_kecamatan;
                        $displayKelurahan = $pendaftar->wali_kelurahan;
                        $displayRt = $pendaftar->wali_rt;
                        $displayRw = $pendaftar->wali_rw;
                        $displayKodePos = $pendaftar->wali_kode_pos;
                        $displayAlamatLengkap = $pendaftar->alamat_wali;
                    }
                ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Wali</label>
                        <p class="text-sm text-gray-900">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_status_wali === 'sama_dengan_ayah'): ?>
                                Sama dengan Ayah Kandung
                            <?php elseif($pendaftar->wali_status_wali === 'sama_dengan_ibu'): ?>
                                Sama dengan Ibu Kandung
                            <?php elseif($pendaftar->wali_status_wali === 'lainnya'): ?>
                                Lainnya
                            <?php else: ?>
                                -
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tinggal Diluar Negeri</label>
                        <p class="text-sm text-gray-900"><?php echo e($displayTinggalLuarNegeri ? 'Ya' : 'Tidak'); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_status_wali === 'lainnya'): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Sama dengan Ayah Kandung</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->wali_sama_dengan_ayah ? 'Ya' : 'Tidak'); ?></p>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                        <p class="text-sm text-gray-900"><?php echo e($displayStatusKepemilikan ?? '-'); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$displayTinggalLuarNegeri): ?>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaProvinsi($displayProvinsi)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKabupaten($displayKabupaten)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKecamatan($displayKecamatan)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKelurahan($displayKelurahan)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                            <p class="text-sm text-gray-900"><?php echo e($displayRt ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                            <p class="text-sm text-gray-900"><?php echo e($displayRw ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                            <p class="text-sm text-gray-900"><?php echo e($displayKodePos ?? '-'); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <p class="text-sm text-gray-900"><?php echo e($displayAlamatLengkap ?? '-'); ?></p>
                    </div>
                </div>
            </div>

        <!-- ===================== DATA SISWA ===================== -->
        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Alamat Tempat Tinggal Siswa
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Tempat Tinggal</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->status_tempat_tinggal ?? '-'); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($pendaftar->status_tempat_tinggal, ['Tinggal dengan Ayah Kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali'])): ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->siswa_status_kepemilikan_rumah ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaProvinsi($pendaftar->siswa_provinsi)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKabupaten($pendaftar->siswa_kabupaten)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKecamatan($pendaftar->siswa_kecamatan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                        <p class="text-sm text-gray-900"><?php echo e($this->getNamaKelurahan($pendaftar->siswa_kelurahan)); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->siswa_rt ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->siswa_rw ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->siswa_kode_pos ?? '-'); ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                        <p class="text-sm text-gray-900"><?php echo e($pendaftar->siswa_alamat ?? '-'); ?></p>
                    </div>
                <?php else: ?>
                    <?php
                        // Display alamat detail based on status tempat tinggal
                        $displaySiswaKepemilikan = null;
                        $displaySiswaProvinsi = null;
                        $displaySiswaKabupaten = null;
                        $displaySiswaKecamatan = null;
                        $displaySiswaKelurahan = null;
                        $displaySiswaRt = null;
                        $displaySiswaRw = null;
                        $displaySiswaKodePos = null;
                        $displaySiswaAlamat = null;

                        if ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Ayah Kandung') {
                            $displaySiswaKepemilikan = $pendaftar->ayah_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->ayah_provinsi;
                            $displaySiswaKabupaten = $pendaftar->ayah_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->ayah_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->ayah_kelurahan;
                            $displaySiswaRt = $pendaftar->ayah_rt;
                            $displaySiswaRw = $pendaftar->ayah_rw;
                            $displaySiswaKodePos = $pendaftar->ayah_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_ayah;
                        } elseif ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Ibu Kandung') {
                            $displaySiswaKepemilikan = $pendaftar->ibu_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->ibu_provinsi;
                            $displaySiswaKabupaten = $pendaftar->ibu_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->ibu_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->ibu_kelurahan;
                            $displaySiswaRt = $pendaftar->ibu_rt;
                            $displaySiswaRw = $pendaftar->ibu_rw;
                            $displaySiswaKodePos = $pendaftar->ibu_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_ibu;
                        } elseif ($pendaftar->status_tempat_tinggal === 'Tinggal dengan Wali') {
                            $displaySiswaKepemilikan = $pendaftar->wali_status_kepemilikan_rumah;
                            $displaySiswaProvinsi = $pendaftar->wali_provinsi;
                            $displaySiswaKabupaten = $pendaftar->wali_kabupaten;
                            $displaySiswaKecamatan = $pendaftar->wali_kecamatan;
                            $displaySiswaKelurahan = $pendaftar->wali_kelurahan;
                            $displaySiswaRt = $pendaftar->wali_rt;
                            $displaySiswaRw = $pendaftar->wali_rw;
                            $displaySiswaKodePos = $pendaftar->wali_kode_pos;
                            $displaySiswaAlamat = $pendaftar->alamat_wali;
                        }
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displaySiswaKepemilikan): ?>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Kepemilikan Rumah</label>
                            <p class="text-sm text-gray-900"><?php echo e($displaySiswaKepemilikan ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Provinsi</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaProvinsi($displaySiswaProvinsi)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kabupaten/Kota</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKabupaten($displaySiswaKabupaten)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kecamatan</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKecamatan($displaySiswaKecamatan)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kelurahan/Desa</label>
                            <p class="text-sm text-gray-900"><?php echo e($this->getNamaKelurahan($displaySiswaKelurahan)); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RT</label>
                            <p class="text-sm text-gray-900"><?php echo e($displaySiswaRt ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">RW</label>
                            <p class="text-sm text-gray-900"><?php echo e($displaySiswaRw ?? '-'); ?></p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Kode Pos</label>
                            <p class="text-sm text-gray-900"><?php echo e($displaySiswaKodePos ?? '-'); ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <p class="text-sm text-gray-900"><?php echo e($displaySiswaAlamat ?? '-'); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Jarak Tempuh ke Sekolah</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->jarak_ke_sekolah ?? '-'); ?></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Transportasi ke Sekolah</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->transportasi ?? '-'); ?></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Waktu Tempuh ke Sekolah</label>
                    <p class="text-sm text-gray-900"><?php echo e($pendaftar->waktu_tempuh ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/tab_templates/tab-data-alamat.blade.php ENDPATH**/ ?>