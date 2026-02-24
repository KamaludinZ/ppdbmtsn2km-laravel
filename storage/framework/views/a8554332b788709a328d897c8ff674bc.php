<!-- Tab Beasiswa & Bantuan Content -->
<?php
    // Support both Admin (single $editMode) and Pendaftar (per-tab $editModeDataBeasiswa)
    $isEditMode = $editModeDataBeasiswa ?? $editMode ?? false;
?>

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Beasiswa & Bantuan</h3>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
        <div class="space-x-2">
            <button wire:click="saveDataBeasiswaTerstruktur" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
            <button wire:click="toggleEdit('dataBeasiswa')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    <?php else: ?>
        <?php
            $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
            $isPendaftar = Auth::user()->role === 'pendaftar';
            // Hanya disable untuk role Pendaftar, Admin/Panitia tetap bisa edit
            $shouldDisable = $isApproved && $isPendaftar;
        ?>
        <button
            wire:click="toggleEdit('dataBeasiswa')"
            <?php if($shouldDisable): ?> disabled title="Data sudah disetujui, tidak dapat diubah" <?php endif; ?>
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition <?php echo e($shouldDisable ? 'opacity-50 cursor-not-allowed' : ''); ?>">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Data
        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataBeasiswaTerstruktur" class="space-y-6">

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex items-start space-x-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-blue-900 mb-1">Informasi Pengisian</p>
                    <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                        <li>Isi data beasiswa atau bantuan yang pernah diterima siswa</li>
                        <li>Klik tombol "Tambah Beasiswa" untuk menambah data beasiswa lainnya</li>
                        <li>Semua field harus diisi dengan lengkap dan benar</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Beasiswa List -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $beasiswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $beasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-sm font-semibold text-gray-900">Beasiswa #<?php echo e($index + 1); ?></h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($beasiswaList) > 1): ?>
                        <button type="button" wire:click="removeBeasiswa(<?php echo e($index); ?>)" class="text-red-600 hover:text-red-800 text-xs">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tahun -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="beasiswaList.<?php echo e($index); ?>.tahun"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: 2024"
                               min="1900"
                               max="<?php echo e(date('Y') + 1); ?>"
                               maxlength="4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.tahun"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="beasiswaList.<?php echo e($index); ?>.kategori"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Beasiswa Lainnya">Beasiswa Lainnya</option>
                            <option value="Beasiswa Berprestasi">Beasiswa Berprestasi</option>
                            <option value="Beasiswa Kurang Mampu/Miskin">Beasiswa Kurang Mampu/Miskin</option>
                            <option value="Beasiswa Miskin dan Berprestasi">Beasiswa Miskin dan Berprestasi</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.kategori"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Nama Beasiswa/Bantuan -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Beasiswa/Bantuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="beasiswaList.<?php echo e($index); ?>.nama_beasiswa"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Beasiswa Prestasi Kemenag">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.nama_beasiswa"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Jenis Instansi Pemberi -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Jenis Instansi Pemberi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="beasiswaList.<?php echo e($index); ?>.jenis_instansi_pemberi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Jenis Instansi --</option>
                            <option value="Kementerian Agama">Kementerian Agama</option>
                            <option value="Kementerian Lain">Kementerian Lain</option>
                            <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                            <option value="BUMN">BUMN</option>
                            <option value="BUMD">BUMD</option>
                            <option value="Instansi Swasta">Instansi Swasta</option>
                            <option value="Yayasan">Yayasan</option>
                            <option value="Perorangan">Perorangan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.jenis_instansi_pemberi"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Nama Instansi Pemberi -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Nama Instansi Pemberi <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="beasiswaList.<?php echo e($index); ?>.nama_instansi_pemberi"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: Kemenag Kota Malang">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.nama_instansi_pemberi"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Jangka Waktu -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Jangka Waktu (Bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="beasiswaList.<?php echo e($index); ?>.jangka_waktu_bulan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: 12"
                               min="1"
                               max="99">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.jangka_waktu_bulan"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Nominal Beasiswa -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Nominal Beasiswa (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               wire:model="beasiswaList.<?php echo e($index); ?>.nominal_beasiswa"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Contoh: 5000000"
                               min="0"
                               step="1000">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ["beasiswaList.{$index}.nominal_beasiswa"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <!-- Tambah Beasiswa Button -->
        <div class="flex justify-center">
            <button type="button" wire:click="addBeasiswa" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Beasiswa
            </button>
        </div>

    </form>
<?php else: ?>
    <!-- VIEW MODE -->
    <div class="space-y-4">

        <!-- Tabel Beasiswa -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-emerald-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Beasiswa/Bantuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis Instansi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Instansi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jangka Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->beasiswas && count($pendaftar->beasiswas) > 0): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendaftar->beasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $beasiswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php echo e($index + 1); ?>

                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($beasiswa->tahun); ?>

                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($beasiswa->kategori === 'Beasiswa Berprestasi'): ?> bg-yellow-100 text-yellow-800
                                            <?php elseif($beasiswa->kategori === 'Beasiswa Kurang Mampu/Miskin'): ?> bg-blue-100 text-blue-800
                                            <?php elseif($beasiswa->kategori === 'Beasiswa Miskin dan Berprestasi'): ?> bg-purple-100 text-purple-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e($beasiswa->kategori); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <?php echo e($beasiswa->nama_beasiswa); ?>

                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($beasiswa->jenis_instansi_pemberi === 'Kementerian Agama'): ?> bg-green-100 text-green-800
                                            <?php elseif($beasiswa->jenis_instansi_pemberi === 'Kementerian Lain'): ?> bg-blue-100 text-blue-800
                                            <?php elseif($beasiswa->jenis_instansi_pemberi === 'Pemerintah Daerah'): ?> bg-purple-100 text-purple-800
                                            <?php elseif(in_array($beasiswa->jenis_instansi_pemberi, ['BUMN', 'BUMD'])): ?> bg-orange-100 text-orange-800
                                            <?php else: ?> bg-gray-100 text-gray-800
                                            <?php endif; ?>">
                                            <?php echo e($beasiswa->jenis_instansi_pemberi); ?>

                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <?php echo e($beasiswa->nama_instansi_pemberi); ?>

                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($beasiswa->jangka_waktu_bulan); ?> bulan
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp <?php echo e(number_format((float)$beasiswa->nominal_beasiswa, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 italic">Belum ada data beasiswa yang dicatat</p>
                                        <p class="text-xs text-gray-400 mt-1">Klik tombol "Edit Data" untuk menambahkan beasiswa</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Box - Only show if has data -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->beasiswas && count($pendaftar->beasiswas) > 0): ?>
            <?php
                $totalNominal = $pendaftar->beasiswas->sum('nominal_beasiswa');
            ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-md p-4">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-emerald-900">Ringkasan Beasiswa</p>
                        <p class="text-xs text-emerald-700 mt-1">Total <?php echo e(count($pendaftar->beasiswas)); ?> beasiswa tercatat dengan total nominal <span class="font-semibold">Rp <?php echo e(number_format((float)$totalNominal, 0, ',', '.')); ?></span></p>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/tab_templates/tab-beasiswa.blade.php ENDPATH**/ ?>