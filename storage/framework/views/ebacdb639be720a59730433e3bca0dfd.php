<div class="p-4 md:p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Kartu Ujian</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola jadwal tes dan kartu kendali untuk kartu ujian peserta.</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap space-x-2 md:space-x-4">
            <button
                wire:click="$set('activeTab', 'jadwal-tes')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'jadwal-tes' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Jadwal Tes
            </button>
            <button
                wire:click="$set('activeTab', 'kartu-kendali')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'kartu-kendali' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Kartu Kendali
            </button>
            <button
                wire:click="$set('activeTab', 'catatan-kartu')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'catatan-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Catatan Kartu
            </button>
            <button
                wire:click="$set('activeTab', 'preview-kartu')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'preview-kartu' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Preview Kartu
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-psikologi')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'invoice-psikologi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Invoice Psikologi
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-madrasah')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'invoice-madrasah' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Invoice DU Madrasah
            </button>
            <button
                wire:click="$set('activeTab', 'invoice-seragam')"
                class="py-2 px-3 md:px-4 border-b-2 font-medium text-xs md:text-sm <?php echo e($activeTab === 'invoice-seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>">
                Invoice DU Seragam
            </button>
        </nav>
    </div>

    <!-- Jadwal Tes Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'jadwal-tes'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <?php echo e($isEditingJadwal ? 'Edit Jadwal Tes' : 'Tambah Jadwal Tes'); ?>

                    </h3>

                    <form wire:submit.prevent="saveJadwalTes">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tes</label>
                            <input type="text" wire:model="nama_tes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Tes Akademik">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_tes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Deskripsi tes (opsional)"></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                            <input type="datetime-local" wire:model="waktu"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['waktu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat</label>
                            <input type="text" wire:model="tempat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Ruang A1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tempat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                            <input type="number" wire:model="urutan_tes" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['urutan_tes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                <?php echo e($isEditingJadwal ? 'Perbarui' : 'Tambah'); ?>

                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditingJadwal): ?>
                                <button type="button" wire:click="resetJadwalTes"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                                    Batal
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Jadwal Tes</h3>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jadwalTesList->isEmpty()): ?>
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada jadwal tes.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jadwalTesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-emerald-300 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800"><?php echo e($jadwal->nama_tes); ?></h4>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jadwal->deskripsi): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e($jadwal->deskripsi); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">
                                            #<?php echo e($jadwal->urutan); ?>

                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo e($jadwal->waktu->format('d/m/Y H:i')); ?>

                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <?php echo e($jadwal->tempat); ?>

                                        </div>
                                    </div>
                                    <div class="mt-3 flex space-x-2">
                                        <button wire:click="editJadwalTes(<?php echo e($jadwal->id); ?>)"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </button>
                                        <button wire:click="deleteJadwalTes(<?php echo e($jadwal->id); ?>)"
                                            onclick="return confirm('Yakin ingin menghapus jadwal tes ini?')"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Kartu Kendali Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'kartu-kendali'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <?php echo e($isEditingKendali ? 'Edit Kartu Kendali' : 'Tambah Kartu Kendali'); ?>

                    </h3>

                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Info:</strong> Kartu kendali akan ditampilkan di bagian bawah kartu pendaftaran peserta.
                        </p>
                    </div>

                    <form wire:submit.prevent="saveKartuKendali">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kegiatan</label>
                            <input type="text" wire:model="nama_kegiatan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Verifikasi Berkas">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                            <textarea wire:model="catatan_kendali" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Catatan tambahan (opsional)"></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['catatan_kendali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                            <input type="number" wire:model="urutan_kendali" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['urutan_kendali'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                <?php echo e($isEditingKendali ? 'Perbarui' : 'Tambah'); ?>

                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditingKendali): ?>
                                <button type="button" wire:click="resetKartuKendali"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                                    Batal
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Daftar Kartu Kendali</h3>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kartuKendaliList->isEmpty()): ?>
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada kartu kendali.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kartuKendaliList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kendali): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-emerald-300 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-800"><?php echo e($kendali->nama_kegiatan); ?></h4>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kendali->catatan): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e($kendali->catatan); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded">
                                            #<?php echo e($kendali->urutan); ?>

                                        </span>
                                    </div>
                                    <div class="mt-3 flex space-x-2">
                                        <button wire:click="editKartuKendali(<?php echo e($kendali->id); ?>)"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </button>
                                        <button wire:click="deleteKartuKendali(<?php echo e($kendali->id); ?>)"
                                            onclick="return confirm('Yakin ingin menghapus kartu kendali ini?')"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Catatan Kartu Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'catatan-kartu'): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Catatan Kartu Ujian</h3>

            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Info:</strong> Catatan ini akan ditampilkan di bagian bawah kartu ujian peserta.
                </p>
            </div>

            <form wire:submit.prevent="saveCatatanKartu">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Kartu Ujian</label>
                    <textarea wire:model="catatan_kartu_ujian" rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="Masukkan catatan yang akan ditampilkan di kartu ujian..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Catatan ini akan muncul di bagian bawah kartu ujian</p>
                </div>

                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    Simpan Catatan
                </button>
            </form>

            <!-- Preview Catatan -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatan_kartu_ujian): ?>
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Preview Catatan:</h4>
                    <div class="border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="whitespace-pre-wrap text-sm text-gray-700"><?php echo e($catatan_kartu_ujian); ?></div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Preview Kartu Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'preview-kartu'): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Kartu Ujian (A4 Portrait)</h3>

            <!-- Sample Card Preview - A4 Portrait Format -->
            <div class="mx-auto border-2 border-gray-300 bg-white shadow-lg" style="width: 210mm; min-height: 297mm; padding: 12mm 20mm;">
                <!-- Kop Header (dari Settings) -->
                <div class="flex items-start gap-6 pb-3 border-b-4 border-black mb-4">
                    <!-- Logo Kiri -->
                    <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($printSettings['print_logo_left'])): ?>
                            <img src="<?php echo e($printSettings['print_logo_left']); ?>" alt="Logo Kiri" class="max-w-full max-h-full object-contain">
                        <?php else: ?>
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Identitas Tengah -->
                    <div class="flex-1 text-center">
                        <h2 class="text-xl font-bold text-gray-900 uppercase mb-1">
                            <?php echo e($printSettings['print_institution_name'] ?: 'NAMA LEMBAGA'); ?>

                        </h2>
                        <p class="text-sm text-gray-700 mb-1">
                            <?php echo e($printSettings['print_institution_address'] ?: 'Alamat Lembaga'); ?>

                        </p>
                        <p class="text-xs text-gray-600">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_phone']): ?> Telp: <?php echo e($printSettings['print_institution_phone']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_email']): ?> | Email: <?php echo e($printSettings['print_institution_email']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_website']): ?> | <?php echo e($printSettings['print_institution_website']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    <!-- Logo Kanan -->
                    <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($printSettings['print_logo_right'])): ?>
                            <img src="<?php echo e($printSettings['print_logo_right']); ?>" alt="Logo Kanan" class="max-w-full max-h-full object-contain">
                        <?php else: ?>
                            <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">KARTU UJIAN</h1>
                    <p class="text-base text-gray-600">Tahun Pelajaran <?php echo e($printSettings['tahun_pelajaran'] ?: date('Y') . '/' . (date('Y') + 1)); ?></p>
                </div>

                <!-- Student Info (Expanded) -->
                <div class="grid grid-cols-3 gap-6 mb-4">
                    <div class="col-span-2">
                        <table class="text-sm w-full">
                            <tr>
                                <td class="font-medium text-gray-700 py-1" style="width: 35%">No. Pendaftaran</td>
                                <td class="font-semibold text-gray-900 py-1">: PPDB-2024-001</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Nama Lengkap</td>
                                <td class="font-semibold text-gray-900 py-1">: CONTOH NAMA PESERTA</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">NISN</td>
                                <td class="font-semibold text-gray-900 py-1">: 0011223344</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Jenis Kelamin</td>
                                <td class="font-semibold text-gray-900 py-1">: Laki-laki</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Jalur Pendaftaran</td>
                                <td class="font-semibold text-gray-900 py-1">: Prestasi</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Asal Sekolah</td>
                                <td class="font-semibold text-gray-900 py-1">: SD Negeri 1 Contoh</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1">Nomor WA Orang Tua</td>
                                <td class="font-semibold text-gray-900 py-1">: 628123456789</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700 py-1 align-top">Alamat</td>
                                <td class="font-semibold text-gray-900 py-1">: Jl. Contoh No. 123, Desa Contoh, Kec. Contoh, Kab. Kulon Progo, DIY</td>
                            </tr>
                        </table>
                    </div>
                    <div class="flex items-start justify-center">
                        <div class="w-28 h-36 border-2 border-gray-400 bg-gray-100 flex items-center justify-center">
                            <span class="text-sm text-gray-500">Foto 3x4</span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Prestasi (hanya muncul jika ada prestasi, maksimal 5 baris) -->
                <?php
                    // Untuk preview, gunakan data contoh. Di implementasi nyata akan menggunakan data dari $pendaftar->prestasis
                    $prestasiContoh = [
                        [
                            'tahun' => '2023',
                            'nama_lomba' => 'Olimpiade Matematika',
                            'bidang_lomba' => 'Akademik',
                            'nama_penyelenggara' => 'Dinas Pendidikan Kabupaten',
                            'tingkat_lomba' => 'Kabupaten/Kota',
                            'peringkat' => 'Juara 1/Medali Emas',
                            'kategori_lomba' => 'Individu'
                        ],
                        [
                            'tahun' => '2023',
                            'nama_lomba' => 'Lomba Cerdas Cermat',
                            'bidang_lomba' => 'Akademik',
                            'nama_penyelenggara' => 'Dinas Pendidikan Provinsi',
                            'tingkat_lomba' => 'Provinsi',
                            'peringkat' => 'Juara 2/Medali Perak',
                            'kategori_lomba' => 'Kelompok'
                        ],
                        [
                            'tahun' => '2022',
                            'nama_lomba' => 'Lomba Pidato Bahasa Indonesia',
                            'bidang_lomba' => 'Kesenian',
                            'nama_penyelenggara' => 'Kementerian Pendidikan',
                            'tingkat_lomba' => 'Nasional',
                            'peringkat' => 'Juara 3/Medali Perunggu',
                            'kategori_lomba' => 'Individu'
                        ],
                        [
                            'tahun' => '2022',
                            'nama_lomba' => 'Lomba Karya Ilmiah Remaja',
                            'bidang_lomba' => 'Sains',
                            'nama_penyelenggara' => 'LIPI',
                            'tingkat_lomba' => 'Nasional',
                            'peringkat' => 'Juara Harapan 1',
                            'kategori_lomba' => 'Kelompok'
                        ],
                        [
                            'tahun' => '2021',
                            'nama_lomba' => 'Lomba Tahfidz Al-Quran',
                            'bidang_lomba' => 'Keagamaan',
                            'nama_penyelenggara' => 'Kemenag Kabupaten',
                            'tingkat_lomba' => 'Kabupaten/Kota',
                            'peringkat' => 'Juara 2/Medali Perak',
                            'kategori_lomba' => 'Individu'
                        ],
                    ];
                    // Limit hanya 5 prestasi pertama
                    $prestasiTampil = array_slice($prestasiContoh, 0, 5);
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($prestasiTampil) > 0): ?>
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 mb-2 text-sm uppercase">DATA PRESTASI (Maksimal 5 Prestasi):</h4>
                    <table class="w-full border-collapse border border-gray-300" style="font-size: 9px; line-height: 1.3;">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-1 py-1 text-center font-semibold" style="width: 4%">No</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 6%">Tahun</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 24%">Nama Lomba</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 10%">Bidang</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 22%">Penyelenggara</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 12%">Tingkat</th>
                                <th class="border border-gray-300 px-1 py-1 text-left font-semibold" style="width: 16%">Peringkat</th>
                                <th class="border border-gray-300 px-1 py-1 text-center font-semibold" style="width: 6%">Kat.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $prestasiTampil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="border border-gray-300 px-1 py-1 text-center font-semibold"><?php echo e($index + 1); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['tahun']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['nama_lomba']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['bidang_lomba']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['nama_penyelenggara']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['tingkat_lomba']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi['peringkat']); ?></td>
                                    <td class="border border-gray-300 px-1 py-1 text-center"><?php echo e(substr($prestasi['kategori_lomba'], 0, 1)); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Jadwal Tes (Table Format) -->
                <div class="mb-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">JADWAL TES:</h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jadwalTesList->isNotEmpty()): ?>
                        <table class="w-full text-xs border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 30%">Nama Tes</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 25%">Tanggal</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 20%">Waktu</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 25%">Tempat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jadwalTesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td class="border border-gray-300 px-1 py-0.5 font-semibold"><?php echo e($jadwal->nama_tes); ?></td>
                                        <td class="border border-gray-300 px-1 py-0.5"><?php echo e($jadwal->waktu->format('d/m/Y')); ?></td>
                                        <td class="border border-gray-300 px-1 py-0.5"><?php echo e($jadwal->waktu->format('H:i')); ?> WIB</td>
                                        <td class="border border-gray-300 px-1 py-0.5"><?php echo e($jadwal->tempat); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 italic">Belum ada jadwal tes yang dikonfigurasi</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Kartu Kendali (Table Format) -->
                <div>
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">KARTU KENDALI:</h4>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kartuKendaliList->isNotEmpty()): ?>
                        <table class="w-full text-xs border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 8%">No</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-left font-semibold" style="width: 40%">Kegiatan</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 20%">Ruang</th>
                                    <th class="border border-gray-300 px-1 py-0.5 text-center font-semibold" style="width: 32%">Paraf Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kartuKendaliList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kendali): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td class="border border-gray-300 px-1 py-0.5 text-center font-semibold"><?php echo e($loop->iteration); ?></td>
                                        <td class="border border-gray-300 px-1 py-0.5"><?php echo e($kendali->nama_kegiatan); ?></td>
                                        <td class="border border-gray-300 px-1 py-0.5 text-center">&nbsp;</td>
                                        <td class="border border-gray-300 px-1 py-0.5">&nbsp;</td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 italic">Belum ada kartu kendali yang dikonfigurasi</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Catatan Section dari Kartu Kendali -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kartuKendaliList->filter(function($k) { return !empty($k->catatan); })->isNotEmpty()): ?>
                <div class="mt-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">CATATAN:</h4>
                    <div class="border border-gray-300 rounded p-2 bg-gray-50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kartuKendaliList->filter(function($k) { return !empty($k->catatan); }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kendali): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="text-xs mb-1">
                                <span class="font-semibold"><?php echo e($kendali->nama_kegiatan); ?>:</span>
                                <span class="text-gray-700"><?php echo e($kendali->catatan); ?></span>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Catatan Kartu Ujian dari Admin -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatan_kartu_ujian): ?>
                <div class="mt-4">
                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">CATATAN:</h4>
                    <div class="border-2 border-gray-400 rounded p-2 bg-yellow-50">
                        <div class="whitespace-pre-wrap text-xs text-gray-700"><?php echo e($catatan_kartu_ujian); ?></div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Invoice Tes Psikologi Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'invoice-psikologi'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Tes Psikologi</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="psikologi_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-PSI-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="psikologi_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice tes psikologi yang diterima oleh pendaftar.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-3 pb-2 border-b-2 border-gray-800">
                            <h2 style="font-size: 13px;" class="font-bold text-gray-900 uppercase">
                                <?php echo e($printSettings['print_institution_name'] ?: 'NAMA LEMBAGA'); ?>

                            </h2>
                            <p style="font-size: 10px;" class="text-gray-700">
                                <?php echo e($printSettings['print_institution_address'] ?: 'Alamat Lembaga'); ?>

                            </p>
                            <p style="font-size: 9px;" class="text-gray-600">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_phone']): ?> Telp: <?php echo e($printSettings['print_institution_phone']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_email']): ?> | Email: <?php echo e($printSettings['print_institution_email']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 style="font-size: 12px;" class="font-bold text-gray-800">INVOICE TES PSIKOLOGI</h3>
                            <p style="font-size: 10px;" class="text-gray-600">
                                No: <?php echo e(str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $psikologi_nomor_format)); ?>

                            </p>
                            <p style="font-size: 9px;" class="text-gray-500">Tanggal: <?php echo e(date('d/m/Y')); ?></p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 30%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 10px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-3 py-2 text-left">Keterangan</th>
                                        <th class="border border-gray-300 px-3 py-2 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-3 py-2">Biaya Tes Psikologi</td>
                                        <td class="border border-gray-300 px-3 py-2 text-right">Rp 100.000</td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-3 py-2">Total</td>
                                        <td class="border border-gray-300 px-3 py-2 text-right">Rp 100.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="mt-3 pt-3 border-t border-gray-300 text-center" style="font-size: 10px;" class="text-gray-600">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($psikologi_footer): ?>
                                <?php echo e($psikologi_footer); ?>

                            <?php else: ?>
                                Terima kasih atas kepercayaan Anda
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Invoice DU Madrasah Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'invoice-madrasah'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Daftar Ulang Madrasah</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="madrasah_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-MDR-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="madrasah_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice daftar ulang madrasah yang diterima oleh pendaftar.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-2 pb-1 border-b border-gray-800">
                            <h2 style="font-size: 10px;" class="font-bold text-gray-900 uppercase">
                                <?php echo e($printSettings['print_institution_name'] ?: 'NAMA LEMBAGA'); ?>

                            </h2>
                            <p style="font-size: 8px;" class="text-gray-700">
                                <?php echo e($printSettings['print_institution_address'] ?: 'Alamat Lembaga'); ?>

                            </p>
                            <p style="font-size: 7px;" class="text-gray-600">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_phone']): ?> Telp: <?php echo e($printSettings['print_institution_phone']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_email']): ?> | Email: <?php echo e($printSettings['print_institution_email']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 class="text-xs font-bold text-gray-800">INVOICE DAFTAR ULANG MADRASAH</h3>
                            <p class="text-xs text-gray-600">
                                No: <?php echo e(str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $madrasah_nomor_format)); ?>

                            </p>
                            <p style="font-size: 8px;" class="text-gray-500">Tanggal: <?php echo e(date('d/m/Y')); ?></p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-2">
                            <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 8px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 text-left">Keterangan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">Biaya Pendaftaran</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 250.000</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">SPP Bulan Pertama</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 200.000</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">Biaya Kegiatan</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 150.000</td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-2 py-1">Total</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 600.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="mt-2 pt-2 border-t border-gray-300 text-center" style="font-size: 8px;" class="text-gray-600">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah_footer): ?>
                                <?php echo e($madrasah_footer); ?>

                            <?php else: ?>
                                Terima kasih atas kepercayaan Anda
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Invoice DU Seragam Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'invoice-seragam'): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Invoice Daftar Ulang Seragam</h3>

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format Nomor Invoice</label>
                            <input type="text" wire:model="seragam_nomor_format"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="INV-SRG-{TAHUN}-{NOMOR}">
                            <p class="text-xs text-gray-500 mt-1">Gunakan {TAHUN} dan {NOMOR} sebagai placeholder</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                            <textarea wire:model="seragam_footer" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="Contoh: Terima kasih atas kepercayaan Anda"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Teks yang akan ditampilkan di bagian bawah invoice</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Info:</strong> Pengaturan ini akan mempengaruhi tampilan invoice daftar ulang seragam yang diterima oleh pendaftar. Invoice ini juga akan menampilkan ukuran seragam peserta.
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Invoice</h3>

                    <!-- Invoice Preview - A5 Landscape (210mm x 148mm) -->
                    <div class="border-2 border-gray-300 bg-white mx-auto" style="width: 210mm; height: 148mm; padding: 10mm; font-size: 10px; overflow: hidden;">
                        <!-- Header dari Settings (tanpa logo) -->
                        <div class="text-center mb-2 pb-1 border-b border-gray-800">
                            <h2 style="font-size: 10px;" class="font-bold text-gray-900 uppercase">
                                <?php echo e($printSettings['print_institution_name'] ?: 'NAMA LEMBAGA'); ?>

                            </h2>
                            <p style="font-size: 8px;" class="text-gray-700">
                                <?php echo e($printSettings['print_institution_address'] ?: 'Alamat Lembaga'); ?>

                            </p>
                            <p style="font-size: 7px;" class="text-gray-600">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_phone']): ?> Telp: <?php echo e($printSettings['print_institution_phone']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($printSettings['print_institution_email']): ?> | Email: <?php echo e($printSettings['print_institution_email']); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>

                        <!-- Invoice Title -->
                        <div class="text-center mb-3">
                            <h3 class="text-xs font-bold text-gray-800">INVOICE DAFTAR ULANG SERAGAM</h3>
                            <p class="text-xs text-gray-600">
                                No: <?php echo e(str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), '001'], $seragam_nomor_format)); ?>

                            </p>
                            <p style="font-size: 8px;" class="text-gray-500">Tanggal: <?php echo e(date('d/m/Y')); ?></p>
                        </div>

                        <!-- Participant & Payment Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <!-- Participant Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pendaftar:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">No. Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: PPDB-2024-001</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Nama</td>
                                        <td class="font-semibold text-gray-900">: CONTOH NAMA PESERTA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">NISN</td>
                                        <td class="font-semibold text-gray-900">: 0011223344</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jenis Kelamin</td>
                                        <td class="font-semibold text-gray-900">: Laki-laki</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Jalur Pendaftaran</td>
                                        <td class="font-semibold text-gray-900">: Prestasi</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Asal Sekolah</td>
                                        <td class="font-semibold text-gray-900">: SD Negeri 1 Contoh</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Payment Info -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Informasi Pembayaran:</h4>
                                <table style="font-size: 8px; line-height: 1.4;" class="w-full">
                                    <tr>
                                        <td class="text-gray-700" style="width: 32%">Bank Dari</td>
                                        <td class="font-semibold text-gray-900">: BCA</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Waktu Transfer</td>
                                        <td class="font-semibold text-gray-900">: 15/01/2024 14:30 WIB</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-700">Status</td>
                                        <td class="font-bold text-emerald-600">: LUNAS / DISETUJUI</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Uniform Size -->
                        <div class="mb-3">
                            <h4 class="font-semibold text-gray-700 mb-2" style="font-size: 11px;">Ukuran Seragam & Atribut:</h4>
                            <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2">
                                <table style="font-size: 10px; line-height: 1.5;" class="w-full">
                                    <tr>
                                        <td style="width: 25%">Ukuran Jas</td>
                                        <td class="font-semibold" style="width: 25%">: M (Lingkar Dada 90cm)</td>
                                        <td style="width: 25%">Ukuran Atasan/Baju</td>
                                        <td class="font-semibold" style="width: 25%">: L (Lingkar Dada 95cm)</td>
                                    </tr>
                                    <tr>
                                        <td>Ukuran Bawahan/Celana</td>
                                        <td class="font-semibold">: L (Pinggang 75cm)</td>
                                        <td>Ukuran Kopyah</td>
                                        <td class="font-semibold">: M (Lingkar Kepala 56cm)</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-2">
                            <h4 class="font-semibold text-gray-700 mb-1" style="font-size: 9px;">Detail Biaya:</h4>
                            <table class="w-full border border-gray-300" style="font-size: 8px;">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 text-left">Keterangan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">Seragam Putih Abu-abu</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 200.000</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">Seragam Pramuka</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 180.000</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1">Atribut Seragam</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 120.000</td>
                                    </tr>
                                    <tr class="bg-gray-50 font-semibold">
                                        <td class="border border-gray-300 px-2 py-1">Total</td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp 500.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="mt-2 pt-2 border-t border-gray-300 text-center" style="font-size: 8px;" class="text-gray-600">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seragam_footer): ?>
                                <?php echo e($seragam_footer); ?>

                            <?php else: ?>
                                Terima kasih atas kepercayaan Anda
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pengaturan-kartu.blade.php ENDPATH**/ ?>