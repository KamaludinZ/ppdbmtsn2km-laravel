<!-- Tab Data Orang Tua & Wali Content -->
<?php
    // Support all controllers: Admin/Panitia (editModeOrangtua) and Pendaftar (editModeOrangTua)
    $isEditMode = $editModeOrangtua ?? $editModeOrangTua ?? $editMode ?? false;
?>

<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-semibold text-gray-900">Data Orang Tua & Wali</h3>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
        <div class="space-x-2">
            <button wire:click="saveDataOrangTua" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm transition">
                <svg wire:loading.remove wire:target="saveDataOrangTua" class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg wire:loading wire:target="saveDataOrangTua" class="animate-spin w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="saveDataOrangTua">Simpan</span>
                <span wire:loading wire:target="saveDataOrangTua">Menyimpan...</span>
            </button>
            <button wire:click="toggleEdit('orangtua')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm transition">Batal</button>
        </div>
    <?php else: ?>
        <?php
            $isApproved = $pendaftar->status_pendaftaran === 'data_disetujui';
            $isPendaftar = Auth::user()->role === 'pendaftar';
            // Hanya disable untuk role Pendaftar, Admin/Panitia tetap bisa edit
            $shouldDisable = $isApproved && $isPendaftar;
        ?>
        <button
            wire:click="toggleEdit('orangtua')"
            <?php if($shouldDisable): ?> disabled title="Data sudah disetujui, tidak dapat diubah" <?php endif; ?>
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm transition <?php echo e($shouldDisable ? 'opacity-50 cursor-not-allowed' : ''); ?>">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            <?php echo e($shouldDisable ? 'Data Sudah Disetujui' : 'Edit Data'); ?>

        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode): ?>
    <!-- EDIT MODE -->
    <form wire:submit.prevent="saveDataOrangTua" class="space-y-6">

        <!-- Section 1: AYAH KANDUNG -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                AYAH KANDUNG
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_ayah" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select wire:model.live="ayah_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Pilih Status</option>
                        <option value="masih_hidup">Masih Hidup</option>
                        <option value="meninggal">Sudah Meninggal</option>
                        <option value="tidak_diketahui">Tidak Diketahui</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ayah_status === 'masih_hidup'): ?>
                    <!-- Kewarganegaraan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan <span class="text-red-500">*</span></label>
                        <select wire:model.live="ayah_kewarganegaraan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Kewarganegaraan</option>
                            <option value="WNI">WNI (Warga Negara Indonesia)</option>
                            <option value="WNA">WNA (Warga Negara Asing)</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_kewarganegaraan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- NIK (WNI) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ayah_kewarganegaraan === 'WNI'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ayah_nik" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="16 digit" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Asal Negara & KITAS (WNA) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ayah_kewarganegaraan === 'WNA'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asal Negara <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ayah_negara_asal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_negara_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Izin Tinggal (KITAS) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ayah_nomor_kitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_nomor_kitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ayah_tempat_lahir" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="ayah_tanggal_lahir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select wire:model="ayah_pendidikan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak sekolah">Tidak sekolah</option>
                            <option value="Putus SD">Putus SD</option>
                            <option value="SD Sederajat">SD Sederajat</option>
                            <option value="SMP Sederajat">SMP Sederajat</option>
                            <option value="SMA Sederajat">SMA Sederajat</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4/S1">D4/S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_pendidikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Pekerjaan Utama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Utama <span class="text-red-500">*</span></label>
                        <select wire:model="ayah_pekerjaan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Pensiunan">Pensiunan</option>
                            <option value="PNS">PNS</option>
                            <option value="TNI/Polri">TNI/Polri</option>
                            <option value="Guru/Dosen">Guru/Dosen</option>
                            <option value="Pegawai Swasta">Pegawai Swasta</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Pengacara/Jaksa/Hakim/Notaris">Pengacara/Jaksa/Hakim/Notaris</option>
                            <option value="Seniman/Pelukis/Artis/Sejenis">Seniman/Pelukis/Artis/Sejenis</option>
                            <option value="Dokter/Bidan/Perawat">Dokter/Bidan/Perawat</option>
                            <option value="Pilot/Pramugara">Pilot/Pramugara</option>
                            <option value="Pedagang">Pedagang</option>
                            <option value="Petani/Peternak">Petani/Peternak</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Buruh (Tani/Pabrik/Bangunan)">Buruh (Tani/Pabrik/Bangunan)</option>
                            <option value="Sopir/Masinis/Kondektur/Tukang Ojek">Sopir/Masinis/Kondektur/Tukang Ojek</option>
                            <option value="Politikus">Politikus</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Penghasilan Bulanan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan <span class="text-red-500">*</span></label>
                        <select wire:model="ayah_penghasilan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <option value="">Pilih Penghasilan</option>
                            <option value="Di bawah Rp. 800,000">Di bawah Rp. 800,000</option>
                            <option value="Rp. 800,001 - Rp. 1,200,000">Rp. 800,001 - Rp. 1,200,000</option>
                            <option value="Rp. 1,200,001 - Rp. 1,800,000">Rp. 1,200,001 - Rp. 1,800,000</option>
                            <option value="Rp. 1,800,001 - Rp. 2,500,000">Rp. 1,800,001 - Rp. 2,500,000</option>
                            <option value="Rp. 2,500,001 - Rp. 3,500,000">Rp. 2,500,001 - Rp. 3,500,000</option>
                            <option value="Rp. 3,500,001 - Rp. 4,800,000">Rp. 3,500,001 - Rp. 4,800,000</option>
                            <option value="Rp. 4,800,001 - Rp. 6,500,000">Rp. 4,800,001 - Rp. 6,500,000</option>
                            <option value="Rp. 6,500,001 - Rp. 10,000,000">Rp. 6,500,001 - Rp. 10,000,000</option>
                            <option value="Rp. 10,000,001 - Rp. 20,000,000">Rp. 10,000,001 - Rp. 20,000,000</option>
                            <option value="Lebih dari Rp. 20,000,000">Lebih dari Rp. 20,000,000</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_penghasilan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Checkbox Tidak Punya HP -->
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="ayah_tidak_punya_hp" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <span class="text-sm font-medium text-gray-700">Tidak memiliki nomor HP</span>
                        </label>
                    </div>

                    <!-- Nomor HP -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$ayah_tidak_punya_hp): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                            <input type="tel" wire:model="ayah_no_hp" maxlength="15" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ayah_no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 2: IBU KANDUNG -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                IBU KANDUNG
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_ibu" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select wire:model.live="ibu_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <option value="">Pilih Status</option>
                        <option value="masih_hidup">Masih Hidup</option>
                        <option value="meninggal">Sudah Meninggal</option>
                        <option value="tidak_diketahui">Tidak Diketahui</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ibu_status === 'masih_hidup'): ?>
                    <!-- Kewarganegaraan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan <span class="text-red-500">*</span></label>
                        <select wire:model.live="ibu_kewarganegaraan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Kewarganegaraan</option>
                            <option value="WNI">WNI (Warga Negara Indonesia)</option>
                            <option value="WNA">WNA (Warga Negara Asing)</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_kewarganegaraan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- NIK (WNI) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ibu_kewarganegaraan === 'WNI'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ibu_nik" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" placeholder="16 digit" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Asal Negara & KITAS (WNA) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ibu_kewarganegaraan === 'WNA'): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asal Negara <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ibu_negara_asal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_negara_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Izin Tinggal (KITAS) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="ibu_nomor_kitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_nomor_kitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Tempat Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ibu_tempat_lahir" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="ibu_tanggal_lahir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select wire:model="ibu_pendidikan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak sekolah">Tidak sekolah</option>
                            <option value="Putus SD">Putus SD</option>
                            <option value="SD Sederajat">SD Sederajat</option>
                            <option value="SMP Sederajat">SMP Sederajat</option>
                            <option value="SMA Sederajat">SMA Sederajat</option>
                            <option value="D1">D1</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4/S1">D4/S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_pendidikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Pekerjaan Utama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Utama <span class="text-red-500">*</span></label>
                        <select wire:model="ibu_pekerjaan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Pekerjaan</option>
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="Pensiunan">Pensiunan</option>
                            <option value="PNS">PNS</option>
                            <option value="TNI/Polri">TNI/Polri</option>
                            <option value="Guru/Dosen">Guru/Dosen</option>
                            <option value="Pegawai Swasta">Pegawai Swasta</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Pengacara/Jaksa/Hakim/Notaris">Pengacara/Jaksa/Hakim/Notaris</option>
                            <option value="Seniman/Pelukis/Artis/Sejenis">Seniman/Pelukis/Artis/Sejenis</option>
                            <option value="Dokter/Bidan/Perawat">Dokter/Bidan/Perawat</option>
                            <option value="Pilot/Pramugara">Pilot/Pramugara</option>
                            <option value="Pedagang">Pedagang</option>
                            <option value="Petani/Peternak">Petani/Peternak</option>
                            <option value="Nelayan">Nelayan</option>
                            <option value="Buruh (Tani/Pabrik/Bangunan)">Buruh (Tani/Pabrik/Bangunan)</option>
                            <option value="Sopir/Masinis/Kondektur/Tukang Ojek">Sopir/Masinis/Kondektur/Tukang Ojek</option>
                            <option value="Politikus">Politikus</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Penghasilan Bulanan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan <span class="text-red-500">*</span></label>
                        <select wire:model="ibu_penghasilan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <option value="">Pilih Penghasilan</option>
                            <option value="Di bawah Rp. 800,000">Di bawah Rp. 800,000</option>
                            <option value="Rp. 800,001 - Rp. 1,200,000">Rp. 800,001 - Rp. 1,200,000</option>
                            <option value="Rp. 1,200,001 - Rp. 1,800,000">Rp. 1,200,001 - Rp. 1,800,000</option>
                            <option value="Rp. 1,800,001 - Rp. 2,500,000">Rp. 1,800,001 - Rp. 2,500,000</option>
                            <option value="Rp. 2,500,001 - Rp. 3,500,000">Rp. 2,500,001 - Rp. 3,500,000</option>
                            <option value="Rp. 3,500,001 - Rp. 4,800,000">Rp. 3,500,001 - Rp. 4,800,000</option>
                            <option value="Rp. 4,800,001 - Rp. 6,500,000">Rp. 4,800,001 - Rp. 6,500,000</option>
                            <option value="Rp. 6,500,001 - Rp. 10,000,000">Rp. 6,500,001 - Rp. 10,000,000</option>
                            <option value="Rp. 10,000,001 - Rp. 20,000,000">Rp. 10,000,001 - Rp. 20,000,000</option>
                            <option value="Lebih dari Rp. 20,000,000">Lebih dari Rp. 20,000,000</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_penghasilan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Checkbox Tidak Punya HP -->
                    <div class="md:col-span-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="ibu_tidak_punya_hp" class="rounded border-gray-300 text-pink-600 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                            <span class="text-sm font-medium text-gray-700">Tidak memiliki nomor HP</span>
                        </label>
                    </div>

                    <!-- Nomor HP -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$ibu_tidak_punya_hp): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                            <input type="tel" wire:model="ibu_no_hp" maxlength="15" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ibu_no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 3: DATA WALI -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                DATA WALI
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Hubungan Wali -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hubungan Wali <span class="text-red-500">*</span></label>
                    <select wire:model.live="wali_hubungan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        <option value="">Pilih Hubungan Wali</option>
                        <option value="sama_ayah">Sama dengan ayah kandung</option>
                        <option value="sama_ibu">Sama dengan ibu kandung</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_hubungan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-500 mt-1">Jika memilih "Sama dengan ayah/ibu", data akan otomatis terisi</p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wali_hubungan === 'lainnya'): ?>
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="wali_nama" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select wire:model.live="wali_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                            <option value="">Pilih Status</option>
                            <option value="masih_hidup">Masih Hidup</option>
                            <option value="meninggal">Sudah Meninggal</option>
                            <option value="tidak_diketahui">Tidak Diketahui</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wali_status === 'masih_hidup'): ?>
                        <!-- Kewarganegaraan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kewarganegaraan <span class="text-red-500">*</span></label>
                            <select wire:model.live="wali_kewarganegaraan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <option value="">Pilih Kewarganegaraan</option>
                                <option value="WNI">WNI (Warga Negara Indonesia)</option>
                                <option value="WNA">WNA (Warga Negara Asing)</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_kewarganegaraan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- NIK (WNI) -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wali_kewarganegaraan === 'WNI'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="wali_nik" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" placeholder="16 digit" required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Asal Negara & KITAS (WNA) -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wali_kewarganegaraan === 'WNA'): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Asal Negara <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="wali_negara_asal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_negara_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Izin Tinggal (KITAS) <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="wali_nomor_kitas" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_nomor_kitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="wali_tempat_lahir" pattern="[A-Za-z\s.,'\-]+" oninput="this.value = this.value.replace(/[0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="wali_tanggal_lahir" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Pendidikan Terakhir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                            <select wire:model="wali_pendidikan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="Tidak sekolah">Tidak sekolah</option>
                                <option value="Putus SD">Putus SD</option>
                                <option value="SD Sederajat">SD Sederajat</option>
                                <option value="SMP Sederajat">SMP Sederajat</option>
                                <option value="SMA Sederajat">SMA Sederajat</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4/S1">D4/S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_pendidikan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Pekerjaan Utama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Utama <span class="text-red-500">*</span></label>
                            <select wire:model="wali_pekerjaan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <option value="">Pilih Pekerjaan</option>
                                <option value="Tidak Bekerja">Tidak Bekerja</option>
                                <option value="Pensiunan">Pensiunan</option>
                                <option value="PNS">PNS</option>
                                <option value="TNI/Polri">TNI/Polri</option>
                                <option value="Guru/Dosen">Guru/Dosen</option>
                                <option value="Pegawai Swasta">Pegawai Swasta</option>
                                <option value="Wiraswasta">Wiraswasta</option>
                                <option value="Pengacara/Jaksa/Hakim/Notaris">Pengacara/Jaksa/Hakim/Notaris</option>
                                <option value="Seniman/Pelukis/Artis/Sejenis">Seniman/Pelukis/Artis/Sejenis</option>
                                <option value="Dokter/Bidan/Perawat">Dokter/Bidan/Perawat</option>
                                <option value="Pilot/Pramugara">Pilot/Pramugara</option>
                                <option value="Pedagang">Pedagang</option>
                                <option value="Petani/Peternak">Petani/Peternak</option>
                                <option value="Nelayan">Nelayan</option>
                                <option value="Buruh (Tani/Pabrik/Bangunan)">Buruh (Tani/Pabrik/Bangunan)</option>
                                <option value="Sopir/Masinis/Kondektur/Tukang Ojek">Sopir/Masinis/Kondektur/Tukang Ojek</option>
                                <option value="Politikus">Politikus</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Penghasilan Bulanan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penghasilan Bulanan <span class="text-red-500">*</span></label>
                            <select wire:model="wali_penghasilan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <option value="">Pilih Penghasilan</option>
                                <option value="Di bawah Rp. 800,000">Di bawah Rp. 800,000</option>
                                <option value="Rp. 800,001 - Rp. 1,200,000">Rp. 800,001 - Rp. 1,200,000</option>
                                <option value="Rp. 1,200,001 - Rp. 1,800,000">Rp. 1,200,001 - Rp. 1,800,000</option>
                                <option value="Rp. 1,800,001 - Rp. 2,500,000">Rp. 1,800,001 - Rp. 2,500,000</option>
                                <option value="Rp. 2,500,001 - Rp. 3,500,000">Rp. 2,500,001 - Rp. 3,500,000</option>
                                <option value="Rp. 3,500,001 - Rp. 4,800,000">Rp. 3,500,001 - Rp. 4,800,000</option>
                                <option value="Rp. 4,800,001 - Rp. 6,500,000">Rp. 4,800,001 - Rp. 6,500,000</option>
                                <option value="Rp. 6,500,001 - Rp. 10,000,000">Rp. 6,500,001 - Rp. 10,000,000</option>
                                <option value="Rp. 10,000,001 - Rp. 20,000,000">Rp. 10,000,001 - Rp. 20,000,000</option>
                                <option value="Lebih dari Rp. 20,000,000">Lebih dari Rp. 20,000,000</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_penghasilan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Checkbox Tidak Punya HP -->
                        <div class="md:col-span-2">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="wali_tidak_punya_hp" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <span class="text-sm font-medium text-gray-700">Tidak memiliki nomor HP</span>
                            </label>
                        </div>

                        <!-- Nomor HP -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$wali_tidak_punya_hp): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP <span class="text-red-500">*</span></label>
                                <input type="tel" wire:model="wali_no_hp" maxlength="15" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200" required>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wali_no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 4: KARTU BANTUAN (Optional) -->
        <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-amber-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                KARTU BANTUAN (Opsional)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor KKS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KKS (Kartu Keluarga Sejahtera)</label>
                    <input type="text" wire:model="nomor_kks" maxlength="25" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_kks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload KKS akan aktif di menu Upload Dokumen</p>
                </div>

                <!-- Nomor PKH -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor PKH (Program Keluarga Harapan)</label>
                    <input type="text" wire:model="nomor_pkh" maxlength="25" oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')" class="w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_pkh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 25 karakter (huruf dan angka). Jika diisi, field upload PKH akan aktif di menu Upload Dokumen</p>
                </div>
            </div>
        </div>

    </form>
<?php else: ?>
    <!-- VIEW MODE -->
    <div class="space-y-6">

        <!-- Section 1: AYAH KANDUNG - View Mode -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                AYAH KANDUNG
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-blue-700 font-medium mb-1">Nama Lengkap</p>
                    <p class="text-gray-900 font-medium"><?php echo e($pendaftar->nama_ayah ?: '-'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-blue-700 font-medium mb-1">Status</p>
                    <p class="text-gray-900">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ayah_status === 'masih_hidup'): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Masih Hidup</span>
                        <?php elseif($pendaftar->ayah_status === 'meninggal'): ?>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Sudah Meninggal</span>
                        <?php elseif($pendaftar->ayah_status === 'tidak_diketahui'): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Tidak Diketahui</span>
                        <?php else: ?>
                            -
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ayah_status === 'masih_hidup'): ?>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Kewarganegaraan</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_kewarganegaraan ?: '-'); ?></p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ayah_kewarganegaraan === 'WNI'): ?>
                        <div>
                            <p class="text-xs text-blue-700 font-medium mb-1">NIK</p>
                            <p class="text-gray-900 font-mono"><?php echo e($pendaftar->ayah_nik ?: '-'); ?></p>
                        </div>
                    <?php elseif($pendaftar->ayah_kewarganegaraan === 'WNA'): ?>
                        <div>
                            <p class="text-xs text-blue-700 font-medium mb-1">Asal Negara</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->ayah_negara_asal ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-blue-700 font-medium mb-1">Nomor KITAS</p>
                            <p class="text-gray-900 font-mono"><?php echo e($pendaftar->ayah_nomor_kitas ?: '-'); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Tempat Lahir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_tempat_lahir ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Tanggal Lahir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->ayah_tanggal_lahir)->format('d/m/Y') : '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Pendidikan Terakhir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_pendidikan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Pekerjaan Utama</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_pekerjaan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Penghasilan Bulanan</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ayah_penghasilan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-blue-700 font-medium mb-1">Nomor HP</p>
                        <p class="text-gray-900">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ayah_tidak_punya_hp): ?>
                                <span class="text-gray-500 italic">Tidak memiliki nomor HP</span>
                            <?php else: ?>
                                <?php echo e($pendaftar->ayah_no_hp ?: '-'); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 2: IBU KANDUNG - View Mode -->
        <div class="bg-pink-50 border-2 border-pink-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                IBU KANDUNG
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-pink-700 font-medium mb-1">Nama Lengkap</p>
                    <p class="text-gray-900 font-medium"><?php echo e($pendaftar->nama_ibu ?: '-'); ?></p>
                </div>
                <div>
                    <p class="text-xs text-pink-700 font-medium mb-1">Status</p>
                    <p class="text-gray-900">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ibu_status === 'masih_hidup'): ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Masih Hidup</span>
                        <?php elseif($pendaftar->ibu_status === 'meninggal'): ?>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Sudah Meninggal</span>
                        <?php elseif($pendaftar->ibu_status === 'tidak_diketahui'): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Tidak Diketahui</span>
                        <?php else: ?>
                            -
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ibu_status === 'masih_hidup'): ?>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Kewarganegaraan</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_kewarganegaraan ?: '-'); ?></p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ibu_kewarganegaraan === 'WNI'): ?>
                        <div>
                            <p class="text-xs text-pink-700 font-medium mb-1">NIK</p>
                            <p class="text-gray-900 font-mono"><?php echo e($pendaftar->ibu_nik ?: '-'); ?></p>
                        </div>
                    <?php elseif($pendaftar->ibu_kewarganegaraan === 'WNA'): ?>
                        <div>
                            <p class="text-xs text-pink-700 font-medium mb-1">Asal Negara</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->ibu_negara_asal ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-pink-700 font-medium mb-1">Nomor KITAS</p>
                            <p class="text-gray-900 font-mono"><?php echo e($pendaftar->ibu_nomor_kitas ?: '-'); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Tempat Lahir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_tempat_lahir ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Tanggal Lahir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->ibu_tanggal_lahir)->format('d/m/Y') : '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Pendidikan Terakhir</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_pendidikan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Pekerjaan Utama</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_pekerjaan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Penghasilan Bulanan</p>
                        <p class="text-gray-900"><?php echo e($pendaftar->ibu_penghasilan ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-pink-700 font-medium mb-1">Nomor HP</p>
                        <p class="text-gray-900">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ibu_tidak_punya_hp): ?>
                                <span class="text-gray-500 italic">Tidak memiliki nomor HP</span>
                            <?php else: ?>
                                <?php echo e($pendaftar->ibu_no_hp ?: '-'); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 3: DATA WALI - View Mode -->
        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                DATA WALI
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-purple-700 font-medium mb-1">Hubungan Wali</p>
                    <p class="text-gray-900">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_hubungan === 'sama_ayah'): ?>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">Sama dengan ayah kandung</span>
                        <?php elseif($pendaftar->wali_hubungan === 'sama_ibu'): ?>
                            <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded text-sm">Sama dengan ibu kandung</span>
                        <?php elseif($pendaftar->wali_hubungan === 'lainnya'): ?>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-sm">Lainnya</span>
                        <?php else: ?>
                            <span class="text-gray-500 italic">Tidak ada wali</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_hubungan === 'lainnya'): ?>
                    <div>
                        <p class="text-xs text-purple-700 font-medium mb-1">Nama Lengkap</p>
                        <p class="text-gray-900 font-medium"><?php echo e($pendaftar->wali_nama ?: '-'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-purple-700 font-medium mb-1">Status</p>
                        <p class="text-gray-900">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_status === 'masih_hidup'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Masih Hidup</span>
                            <?php elseif($pendaftar->wali_status === 'meninggal'): ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Sudah Meninggal</span>
                            <?php elseif($pendaftar->wali_status === 'tidak_diketahui'): ?>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">Tidak Diketahui</span>
                            <?php else: ?>
                                -
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_status === 'masih_hidup'): ?>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Kewarganegaraan</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_kewarganegaraan ?: '-'); ?></p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_kewarganegaraan === 'WNI'): ?>
                            <div>
                                <p class="text-xs text-purple-700 font-medium mb-1">NIK</p>
                                <p class="text-gray-900 font-mono"><?php echo e($pendaftar->wali_nik ?: '-'); ?></p>
                            </div>
                        <?php elseif($pendaftar->wali_kewarganegaraan === 'WNA'): ?>
                            <div>
                                <p class="text-xs text-purple-700 font-medium mb-1">Asal Negara</p>
                                <p class="text-gray-900"><?php echo e($pendaftar->wali_negara_asal ?: '-'); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-purple-700 font-medium mb-1">Nomor KITAS</p>
                                <p class="text-gray-900 font-mono"><?php echo e($pendaftar->wali_nomor_kitas ?: '-'); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Tempat Lahir</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_tempat_lahir ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Tanggal Lahir</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->wali_tanggal_lahir)->format('d/m/Y') : '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Pendidikan Terakhir</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_pendidikan ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Pekerjaan Utama</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_pekerjaan ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Penghasilan Bulanan</p>
                            <p class="text-gray-900"><?php echo e($pendaftar->wali_penghasilan ?: '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-purple-700 font-medium mb-1">Nomor HP</p>
                            <p class="text-gray-900">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->wali_tidak_punya_hp): ?>
                                    <span class="text-gray-500 italic">Tidak memiliki nomor HP</span>
                                <?php else: ?>
                                    <?php echo e($pendaftar->wali_no_hp ?: '-'); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($pendaftar->wali_hubungan === 'sama_ayah'): ?>
                    <div class="md:col-span-2">
                        <div class="bg-blue-100 border border-blue-300 rounded-lg p-4">
                            <p class="text-blue-800 text-sm">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Data wali sama dengan data ayah kandung (lihat bagian AYAH KANDUNG di atas)
                            </p>
                        </div>
                    </div>
                <?php elseif($pendaftar->wali_hubungan === 'sama_ibu'): ?>
                    <div class="md:col-span-2">
                        <div class="bg-pink-100 border border-pink-300 rounded-lg p-4">
                            <p class="text-pink-800 text-sm">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Data wali sama dengan data ibu kandung (lihat bagian IBU KANDUNG di atas)
                            </p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Section 4: KARTU BANTUAN - View Mode -->
        <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-6">
            <h4 class="text-md font-semibold text-amber-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                KARTU BANTUAN (Opsional)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-amber-700 font-medium mb-1">Nomor KKS (Kartu Keluarga Sejahtera)</p>
                    <p class="text-gray-900 font-mono">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->nomor_kks): ?>
                            <?php echo e($pendaftar->nomor_kks); ?>

                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Terdaftar</span>
                        <?php else: ?>
                            <span class="text-gray-500 italic">Tidak memiliki</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-amber-700 font-medium mb-1">Nomor PKH (Program Keluarga Harapan)</p>
                    <p class="text-gray-900 font-mono">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->nomor_pkh): ?>
                            <?php echo e($pendaftar->nomor_pkh); ?>

                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Terdaftar</span>
                        <?php else: ?>
                            <span class="text-gray-500 italic">Tidak memiliki</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->nomor_kks || $pendaftar->nomor_pkh): ?>
                <div class="mt-4 pt-4 border-t border-amber-300">
                    <p class="text-xs text-amber-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Silakan upload dokumen pendukung di menu <strong>Upload Dokumen</strong>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/tab_templates/tab-data-orangtua.blade.php ENDPATH**/ ?>