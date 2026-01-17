<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pendaftar): ?>
        <div class="text-center py-12">
            <p class="text-gray-500">Data pendaftar tidak ditemukan.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <!-- Flash Message -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Header Info -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-blue-600 px-6 py-8 text-white">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <!-- Left Section: Photo & Info -->
                        <div class="flex items-start space-x-4 flex-1">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->foto): ?>
                                <img src="<?php echo e(Storage::url($pendaftar->foto)); ?>" alt="Foto" class="w-24 h-24 rounded-lg object-cover border-4 border-white shadow-lg flex-shrink-0">
                            <?php else: ?>
                                <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center border-4 border-white shadow-lg flex-shrink-0">
                                    <span class="text-4xl font-bold text-emerald-600"><?php echo e(strtoupper(substr($pendaftar->nama_lengkap, 0, 2))); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-2xl md:text-3xl font-bold mb-2"><?php echo e($pendaftar->nama_lengkap); ?></h2>
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">No. Pendaftaran: <span class="font-semibold text-white"><?php echo e($pendaftar->nomor_pendaftaran); ?></span></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">NISN: <span class="font-semibold text-white"><?php echo e($pendaftar->nisn); ?></span></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-emerald-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                        </svg>
                                        <span class="text-emerald-100 text-sm">Jalur: <span class="font-semibold text-white"><?php echo e($pendaftar->jalurPendaftaran->nama ?? '-'); ?></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section: Status Badge -->
                        <div class="flex md:flex-col items-start md:items-end justify-start md:justify-center gap-3">
                            <?php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800 border-gray-300',
                                    'menunggu' => 'bg-yellow-50 text-yellow-800 border-yellow-300',
                                    'diverifikasi' => 'bg-blue-50 text-blue-800 border-blue-300',
                                    'lulus' => 'bg-green-50 text-green-800 border-green-300',
                                    'tidak_lulus' => 'bg-red-50 text-red-800 border-red-300',
                                    'cadangan' => 'bg-blue-50 text-blue-800 border-blue-300',
                                ];
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'menunggu' => 'Menunggu Verifikasi',
                                    'diverifikasi' => 'Diverifikasi',
                                    'lulus' => 'Lulus Seleksi',
                                    'tidak_lulus' => 'Tidak Lulus',
                                    'cadangan' => 'Cadangan',
                                ];
                                $statusClass = $statusColors[$pendaftar->status_pendaftaran] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                $statusLabel = $statusLabels[$pendaftar->status_pendaftaran] ?? ucfirst(str_replace('_', ' ', $pendaftar->status_pendaftaran));
                            ?>
                            <div class="text-left md:text-right">
                                <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide">Status Pendaftaran</p>
                                <span class="inline-block px-4 py-2.5 rounded-lg text-sm font-bold border-2 shadow-md <?php echo e($statusClass); ?>">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </div>
                            <div class="text-left md:text-right">
                                <p class="text-emerald-100 text-xs font-semibold mb-2 uppercase tracking-wide">Ubah Status</p>
                                <select wire:model.live="status_pendaftaran" wire:change="updateStatusPendaftaran" class="rounded-lg border-2 border-white shadow-md text-sm font-semibold focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                                    <option value="draft">Draft</option>
                                    <option value="menunggu">Menunggu Verifikasi</option>
                                    <option value="diverifikasi">Diverifikasi</option>
                                    <option value="lulus">Lulus Seleksi</option>
                                    <option value="tidak_lulus">Tidak Lulus</option>
                                    <option value="cadangan">Cadangan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div x-data="{ activeTab: 'rapor' }" class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Tab Buttons -->
                <div class="border-b border-gray-200 overflow-x-auto">
                    <nav class="flex -mb-px whitespace-nowrap">
                        <button @click="activeTab = 'rapor'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'rapor', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'rapor' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Data Rapor
                        </button>
                        <button @click="activeTab = 'pribadi'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'pribadi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pribadi' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Data Pribadi
                        </button>
                        <button @click="activeTab = 'orangtua'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'orangtua' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Orang Tua
                        </button>
                        <button @click="activeTab = 'alamat'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'alamat', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'alamat' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Data Alamat
                        </button>
                        <button @click="activeTab = 'sekolah'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'sekolah', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'sekolah' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Data Sekolah
                        </button>
                        <button @click="activeTab = 'prestasi'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'prestasi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'prestasi' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Data Prestasi
                        </button>
                        <button @click="activeTab = 'emisidentitas'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'emisidentitas', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'emisidentitas' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            Data EMIS
                        </button>
                        <button @click="activeTab = 'dokumen'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'dokumen', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'dokumen' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Verifikasi Dokumen
                        </button>
                        <button @click="activeTab = 'daftarulang'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'daftarulang', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'daftarulang' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Daftar Ulang
                        </button>
                        <button @click="activeTab = 'survey'" :class="{ 'border-emerald-500 text-emerald-600 bg-emerald-50': activeTab === 'survey', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'survey' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm transition focus:outline-none">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Survey
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Data Pribadi Tab -->
                    <div x-show="activeTab === 'pribadi'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pribadi</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataPribadi" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataPribadi" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Upload Foto -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Pendaftar</label>
                                    <div class="flex items-center space-x-4">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foto): ?>
                                            <img src="<?php echo e(Storage::url($foto)); ?>" alt="Current foto" class="w-20 h-20 rounded-full object-cover">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div>
                                            <input type="file" wire:model="newFoto" accept="image/*" class="text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['newFoto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                                    <input type="text" wire:model="nama_lengkap" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NISN *</label>
                                    <input type="text" wire:model="nisn" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                    <input type="text" wire:model="tempat_lahir" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <input type="date" wire:model="tanggal_lahir" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                                    <select wire:model="jenis_kelamin" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agama</label>
                                    <input type="text" wire:model="agama" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nama_lengkap); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">NISN</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nisn); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Tempat Lahir</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->tempat_lahir ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Tanggal Lahir</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d F Y') : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Jenis Kelamin</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Agama</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->agama ?? '-'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data Orang Tua Tab -->
                    <div x-show="activeTab === 'orangtua'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Orang Tua</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataOrangTua" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataOrangTua" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                                    <input type="text" wire:model="nama_ayah" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pekerjaan Ayah</label>
                                    <input type="text" wire:model="pekerjaan_ayah" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                                    <input type="text" wire:model="nama_ibu" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pekerjaan Ibu</label>
                                    <input type="text" wire:model="pekerjaan_ibu" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Penghasilan Orang Tua</label>
                                    <input type="text" wire:model="penghasilan_ortu" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No. Telepon/WA Orang Tua</label>
                                    <input type="text" wire:model="telepon_ortu" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Nama Ayah</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nama_ayah ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Pekerjaan Ayah</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->pekerjaan_ayah ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Nama Ibu</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nama_ibu ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Pekerjaan Ibu</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->pekerjaan_ibu ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Penghasilan Orang Tua</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->penghasilan_ortu ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">No. Telepon/WA Orang Tua</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->telepon_ortu ?? '-'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data Alamat Tab -->
                    <div x-show="activeTab === 'alamat'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Alamat & Kontak</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataAlamat" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataAlamat" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                    <textarea wire:model="alamat" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kab/Kota</label>
                                    <input type="text" wire:model="asal_kabkota" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                    <input type="text" wire:model="telepon" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                    <input type="text" wire:model="no_hp" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" wire:model="email" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-600">Alamat Lengkap</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->alamat ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Kab/Kota</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->asal_kabkota ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">No. Telepon</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->telepon ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">No. HP</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->no_hp ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Email</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->email); ?></p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data Sekolah Asal Tab -->
                    <div x-show="activeTab === 'sekolah'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Sekolah Asal</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataSekolah" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataSekolah" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                                    <input type="text" wire:model="asal_sekolah" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Sekolah</label>
                                    <select wire:model="jenis_sekolah_asal" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        <option value="">Pilih Jenis</option>
                                        <option value="madrasah">Madrasah</option>
                                        <option value="umum">Umum</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NSM (untuk Madrasah)</label>
                                    <input type="text" wire:model="nsm_sekolah_asal" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NPSN (untuk Sekolah Umum)</label>
                                    <input type="text" wire:model="npsn_sekolah_asal" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nilai Rata-Rata</label>
                                    <input type="number" step="0.01" wire:model="nilai_rata_rata" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Alamat Sekolah</label>
                                    <textarea wire:model="alamat_sekolah" rows="2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200"></textarea>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Nama Sekolah</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->asal_sekolah ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Jenis Sekolah</label>
                                    <p class="mt-1 text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->jenis_sekolah_asal): ?>
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?php echo e($pendaftar->jenis_sekolah_asal === 'madrasah' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800'); ?>">
                                                <?php echo e(ucfirst($pendaftar->jenis_sekolah_asal)); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">
                                        <?php echo e($pendaftar->jenis_sekolah_asal === 'madrasah' ? 'NSM' : 'NPSN'); ?>

                                    </label>
                                    <p class="mt-1 text-base text-gray-900">
                                        <?php echo e($pendaftar->jenis_sekolah_asal === 'madrasah' ? ($pendaftar->nsm_sekolah_asal ?? '-') : ($pendaftar->npsn_sekolah_asal ?? '-')); ?>

                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Nilai Rata-Rata</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_rata_rata ?? '-'); ?></p>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->alamat_sekolah): ?>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-600">Alamat Sekolah</label>
                                    <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->alamat_sekolah); ?></p>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data Rapor Tab -->
                    <div x-show="activeTab === 'rapor'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Nilai Rapor</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataRapor" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataRapor" class="space-y-6">
                                <!-- Kelas 5 Semester 1 -->
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 5 Semester 1</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Bahasa Indonesia</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_b_indo_5_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_5_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Matematika</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_matematika_5_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_5_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">IPA</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_ipa_5_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_5_1'];
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

                                <!-- Kelas 5 Semester 2 -->
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 5 Semester 2</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Bahasa Indonesia</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_b_indo_5_2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_5_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Matematika</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_matematika_5_2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_5_2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">IPA</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_ipa_5_2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_5_2'];
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

                                <!-- Kelas 6 Semester 1 -->
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 6 Semester 1</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Bahasa Indonesia</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_b_indo_6_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_b_indo_6_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Matematika</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_matematika_6_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_matematika_6_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">IPA</label>
                                            <input type="number" step="0.01" min="0" max="100" wire:model="nilai_ipa_6_1" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nilai_ipa_6_1'];
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

                                <!-- Rata-rata -->
                                <div class="bg-emerald-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Rata-rata Nilai:</span>
                                        <span class="text-2xl font-bold text-emerald-600"><?php echo e($pendaftar->hitungRataRataRapor()); ?></span>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="space-y-6">
                                <!-- Kelas 5 Semester 1 -->
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 5 Semester 1</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Bahasa Indonesia</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_b_indo_5_1 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Matematika</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_matematika_5_1 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">IPA</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_ipa_5_1 ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kelas 5 Semester 2 -->
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 5 Semester 2</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Bahasa Indonesia</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_b_indo_5_2 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Matematika</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_matematika_5_2 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">IPA</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_ipa_5_2 ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kelas 6 Semester 1 -->
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="font-semibold text-gray-900 mb-3">Kelas 6 Semester 1</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Bahasa Indonesia</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_b_indo_6_1 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">Matematika</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_matematika_6_1 ?? '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-600">IPA</label>
                                            <p class="mt-1 text-base text-gray-900"><?php echo e($pendaftar->nilai_ipa_6_1 ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rata-rata -->
                                <div class="bg-emerald-50 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Rata-rata Nilai:</span>
                                        <span class="text-2xl font-bold text-emerald-600"><?php echo e($pendaftar->hitungRataRataRapor()); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data Prestasi Tab -->
                    <div x-show="activeTab === 'prestasi'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Prestasi</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataPrestasi" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataPrestasi">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Prestasi yang Dimiliki</label>
                                    <textarea wire:model="prestasi" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Tuliskan prestasi yang pernah diraih (akademik maupun non-akademik)"></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Contoh: Juara 1 Lomba MTQ Tingkat Kota, Juara 2 Olimpiade Matematika, dll.</p>
                                </div>
                            </form>
                        <?php else: ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Prestasi yang Dimiliki</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->prestasi): ?>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-base text-gray-900 whitespace-pre-line"><?php echo e($pendaftar->prestasi); ?></p>
                                    </div>
                                <?php else: ?>
                                    <p class="text-base text-gray-500 italic">Tidak ada data prestasi</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Data EMIS Tab -->
                    <div x-show="activeTab === 'emisidentitas'" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Data EMIS Kemenag 4.0</h3>

                        <!-- 1. Data Identitas & Keluarga -->
                        <div class="mb-8 bg-emerald-50 p-6 rounded-lg border border-emerald-200">
                            <h4 class="text-md font-semibold text-emerald-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Data Identitas & Keluarga
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NIK</label>
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($pendaftar->nik ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Panggilan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->nama_panggilan ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kewarganegaraan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->kewarganegaraan ? ucfirst($pendaftar->kewarganegaraan) : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nomor Kartu Keluarga</label>
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($pendaftar->nomor_kk ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Anak Ke-</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->anak_ke ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jumlah Saudara Kandung</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->jumlah_saudara_kandung ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Alamat Detail -->
                        <div class="mb-8 bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Alamat Detail
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">RT</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->rt ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">RW</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->rw ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Dusun</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->dusun ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kelurahan/Desa</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->kelurahan ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kecamatan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->kecamatan ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kabupaten/Kota</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->kabupaten ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Provinsi</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->provinsi ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Kode Pos</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->kode_pos ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lintang</label>
                                    <p class="text-base text-gray-900 font-mono"><?php echo e($pendaftar->lintang ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Bujur</label>
                                    <p class="text-base text-gray-900 font-mono"><?php echo e($pendaftar->bujur ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Tempat Tinggal & Transportasi -->
                        <div class="mb-8 bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <h4 class="text-md font-semibold text-purple-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                Tempat Tinggal & Transportasi
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jenis Tinggal</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->jenis_tinggal ? ucwords(str_replace('_', ' ', $pendaftar->jenis_tinggal)) : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Alat Transportasi</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->alat_transportasi ? ucwords(str_replace('_', ' ', $pendaftar->alat_transportasi)) : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Jarak Tempat Tinggal</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->jarak_tempat_tinggal ? $pendaftar->jarak_tempat_tinggal . ' km' : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Waktu Tempuh</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->waktu_tempuh ? $pendaftar->waktu_tempuh . ' menit' : '-'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Data Ayah -->
                        <div class="mb-8 bg-cyan-50 p-6 rounded-lg border border-cyan-200">
                            <h4 class="text-md font-semibold text-cyan-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Data Ayah
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NIK Ayah</label>
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($pendaftar->ayah_nik ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Ayah</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->nama_ayah ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tahun Lahir</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ayah_tahun_lahir ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pendidikan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ayah_pendidikan ? strtoupper($pendaftar->ayah_pendidikan) : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pekerjaan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->pekerjaan_ayah ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Penghasilan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ayah_penghasilan ? 'Rp ' . number_format($pendaftar->ayah_penghasilan, 0, ',', '.') : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">No. HP</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ayah_no_hp ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Berkebutuhan Khusus</label>
                                    <p class="text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ayah_berkebutuhan_khusus): ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Data Ibu -->
                        <div class="mb-8 bg-pink-50 p-6 rounded-lg border border-pink-200">
                            <h4 class="text-md font-semibold text-pink-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Data Ibu
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NIK Ibu</label>
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($pendaftar->ibu_nik ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Ibu</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->nama_ibu ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tahun Lahir</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ibu_tahun_lahir ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pendidikan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ibu_pendidikan ? strtoupper($pendaftar->ibu_pendidikan) : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pekerjaan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->pekerjaan_ibu ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Penghasilan</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ibu_penghasilan ? 'Rp ' . number_format($pendaftar->ibu_penghasilan, 0, ',', '.') : '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">No. HP</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->ibu_no_hp ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Berkebutuhan Khusus</label>
                                    <p class="text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->ibu_berkebutuhan_khusus): ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Data Wali (Conditional) -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->memiliki_wali): ?>
                            <div class="mb-8 bg-indigo-50 p-6 rounded-lg border border-indigo-200">
                                <h4 class="text-md font-semibold text-indigo-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Data Wali
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NIK Wali</label>
                                        <p class="text-base font-semibold text-gray-900"><?php echo e($pendaftar->wali_nik ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama Wali</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_nama ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tahun Lahir</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_tahun_lahir ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pendidikan</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_pendidikan ? strtoupper($pendaftar->wali_pendidikan) : '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Pekerjaan</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_pekerjaan ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Penghasilan</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_penghasilan ? 'Rp ' . number_format($pendaftar->wali_penghasilan, 0, ',', '.') : '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">No. HP</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->wali_no_hp ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- 7. Riwayat Pendidikan -->
                        <div class="mb-8 bg-amber-50 p-6 rounded-lg border border-amber-200">
                            <h4 class="text-md font-semibold text-amber-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Riwayat Pendidikan
                            </h4>

                            <!-- TK/RA -->
                            <div class="bg-white p-4 rounded-lg mb-4">
                                <h5 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="w-8 h-8 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mr-2 text-sm font-bold">TK</span>
                                    TK/RA
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama TK/RA</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->tk_nama ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NPSN</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->tk_npsn ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tahun Lulus</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->tk_tahun_lulus ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- SD/MI -->
                            <div class="bg-white p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-700 mb-3 flex items-center">
                                    <span class="w-8 h-8 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center mr-2 text-sm font-bold">SD</span>
                                    SD/MI
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Nama SD/MI</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->sd_nama ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">NPSN</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->sd_npsn ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Tahun Lulus</label>
                                        <p class="text-base text-gray-900"><?php echo e($pendaftar->sd_tahun_lulus ?? '-'); ?></p>
                                    </div>
                                </div>

                                <!-- Dokumen SD -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_ijazah_sd || $pendaftar->file_skhun_sd): ?>
                                    <div class="border-t pt-3 mt-3">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Dokumen</label>
                                        <div class="flex gap-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_ijazah_sd): ?>
                                                <a href="<?php echo e(Storage::url($pendaftar->file_ijazah_sd)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 text-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Ijazah
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_skhun_sd): ?>
                                                <a href="<?php echo e(Storage::url($pendaftar->file_skhun_sd)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 text-sm">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    SKHUN
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Nilai SD -->
                                <div class="border-t pt-3 mt-3">
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Nilai Rata-rata Rapor SD</label>
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                        <div class="text-center p-2 bg-blue-50 rounded">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Matematika</label>
                                            <p class="text-lg font-bold text-blue-900"><?php echo e($pendaftar->sd_nilai_matematika ?? '-'); ?></p>
                                        </div>
                                        <div class="text-center p-2 bg-green-50 rounded">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">IPA</label>
                                            <p class="text-lg font-bold text-green-900"><?php echo e($pendaftar->sd_nilai_ipa ?? '-'); ?></p>
                                        </div>
                                        <div class="text-center p-2 bg-purple-50 rounded">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">IPS</label>
                                            <p class="text-lg font-bold text-purple-900"><?php echo e($pendaftar->sd_nilai_ips ?? '-'); ?></p>
                                        </div>
                                        <div class="text-center p-2 bg-yellow-50 rounded">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">B. Indonesia</label>
                                            <p class="text-lg font-bold text-yellow-900"><?php echo e($pendaftar->sd_nilai_bahasa_indonesia ?? '-'); ?></p>
                                        </div>
                                        <div class="text-center p-2 bg-red-50 rounded">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">B. Inggris</label>
                                            <p class="text-lg font-bold text-red-900"><?php echo e($pendaftar->sd_nilai_bahasa_inggris ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 8. Program Bantuan Pemerintah -->
                        <div class="mb-8 bg-green-50 p-6 rounded-lg border border-green-200">
                            <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Program Bantuan Pemerintah
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Penerima KPS</label>
                                    <p class="text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->penerima_kps): ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->nomor_kps): ?>
                                                <span class="block mt-1 text-xs text-gray-600">No: <?php echo e($pendaftar->nomor_kps); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Penerima KIP</label>
                                    <p class="text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->penerima_kip): ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->nomor_kip): ?>
                                                <span class="block mt-1 text-xs text-gray-600">No: <?php echo e($pendaftar->nomor_kip); ?></span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Layak PIP</label>
                                    <p class="text-base text-gray-900">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->layak_pip): ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Ya</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Data Tambahan -->
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Data Tambahan
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-4 rounded-lg">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Hobi</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->hobi ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Cita-cita</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->cita_cita ?? '-'); ?></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Berkebutuhan Khusus</label>
                                    <p class="text-base text-gray-900"><?php echo e($pendaftar->berkebutuhan_khusus ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- 10. Dokumen Pendukung EMIS -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_kartu_keluarga || $pendaftar->file_akta_kelahiran || $pendaftar->file_ijazah_sd || $pendaftar->file_skhun_sd): ?>
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    Dokumen Pendukung EMIS
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_kartu_keluarga): ?>
                                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">Kartu Keluarga</span>
                                            </div>
                                            <a href="<?php echo e(Storage::url($pendaftar->file_kartu_keluarga)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_akta_kelahiran): ?>
                                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">Akta Kelahiran</span>
                                            </div>
                                            <a href="<?php echo e(Storage::url($pendaftar->file_akta_kelahiran)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_ijazah_sd): ?>
                                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">Ijazah SD/MI</span>
                                            </div>
                                            <a href="<?php echo e(Storage::url($pendaftar->file_ijazah_sd)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->file_skhun_sd): ?>
                                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                <span class="text-sm font-medium text-gray-700">SKHUN SD/MI</span>
                                            </div>
                                            <a href="<?php echo e(Storage::url($pendaftar->file_skhun_sd)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Tab Survey -->
                    <div x-show="activeTab === 'survey'" x-transition>
                        <?php echo $__env->make('tab_templates.tab-survey', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>

                    <!-- Data Daftar Ulang Tab -->
                    <div x-show="activeTab === 'daftarulang'" x-transition>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Data Daftar Ulang</h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                                <div class="space-x-2">
                                    <button wire:click="saveDataDaftarUlang" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan
                                    </button>
                                    <button wire:click="toggleEditMode" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">Batal</button>
                                </div>
                            <?php else: ?>
                                <button wire:click="toggleEditMode" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Data
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editMode): ?>
                            <form wire:submit.prevent="saveDataDaftarUlang" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status Daftar Ulang</label>
                                    <select wire:model="du_status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                        <option value="belum">Belum Daftar Ulang</option>
                                        <option value="proses">Sedang Proses</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Daftar Ulang</label>
                                    <input type="date" wire:model="du_tanggal_daftar_ulang" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Daftar Ulang</label>
                                    <input type="date" wire:model="du_batas_daftar_ulang" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                </div>
                                <div class="flex items-center space-x-4 mt-6">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="du_pembayaran_lunas" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-gray-700">Pembayaran Lunas</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="du_seragam_diukur" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-gray-700">Seragam Diukur</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="du_berkas_lengkap" class="rounded text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-2 text-sm text-gray-700">Berkas Lengkap</span>
                                    </label>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea wire:model="du_catatan" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Catatan terkait daftar ulang"></textarea>
                                </div>
                            </form>
                        <?php else: ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daftarUlang): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Status</label>
                                        <p class="mt-1">
                                            <?php
                                                $duColors = ['belum' => 'bg-gray-100 text-gray-800', 'proses' => 'bg-yellow-100 text-yellow-800', 'selesai' => 'bg-green-100 text-green-800'];
                                                $duLabels = ['belum' => 'Belum Daftar Ulang', 'proses' => 'Sedang Proses', 'selesai' => 'Selesai'];
                                            ?>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo e($duColors[$daftarUlang->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e($duLabels[$daftarUlang->status] ?? $daftarUlang->status); ?>

                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Tanggal Daftar Ulang</label>
                                        <p class="mt-1 text-base text-gray-900"><?php echo e($daftarUlang->tanggal_daftar_ulang ? $daftarUlang->tanggal_daftar_ulang->format('d F Y') : '-'); ?></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600">Batas Daftar Ulang</label>
                                        <p class="mt-1 text-base text-gray-900"><?php echo e($daftarUlang->batas_daftar_ulang ? $daftarUlang->batas_daftar_ulang->format('d F Y') : '-'); ?></p>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-600">Checklist:</label>
                                        <div class="space-y-1">
                                            <p class="text-sm">
                                                <svg class="w-5 h-5 inline <?php echo e($daftarUlang->pembayaran_lunas ? 'text-green-600' : 'text-gray-400'); ?>" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Pembayaran Lunas
                                            </p>
                                            <p class="text-sm">
                                                <svg class="w-5 h-5 inline <?php echo e($daftarUlang->seragam_diukur ? 'text-green-600' : 'text-gray-400'); ?>" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Seragam Diukur
                                            </p>
                                            <p class="text-sm">
                                                <svg class="w-5 h-5 inline <?php echo e($daftarUlang->berkas_lengkap ? 'text-green-600' : 'text-gray-400'); ?>" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Berkas Lengkap
                                            </p>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($daftarUlang->catatan): ?>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-600">Catatan</label>
                                        <div class="mt-1 bg-gray-50 p-3 rounded-lg">
                                            <p class="text-base text-gray-900"><?php echo e($daftarUlang->catatan); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Belum ada data daftar ulang</p>
                                    <p class="text-xs text-gray-500">Klik tombol Edit Data untuk menambahkan data</p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Verifikasi Dokumen Tab -->
                    <div x-show="activeTab === 'dokumen'" x-transition>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Berkas / Dokumen</h3>
                        <div class="space-y-6">
                            <?php
                                $dokumenList = [
                                    ['key' => 'rapor', 'label' => 'Scan Rapor'],
                                    ['key' => 'akte', 'label' => 'Akte Kelahiran'],
                                    ['key' => 'kk', 'label' => 'Kartu Keluarga'],
                                    ['key' => 'nisn', 'label' => 'Scan NISN'],
                                    ['key' => 'ijazah', 'label' => 'Ijazah/Surat Keterangan Aktif'],
                                    ['key' => 'sertifikat', 'label' => 'Sertifikat/Piagam'],
                                    ['key' => 'kip', 'label' => 'Scan KIP/PKH/KKS'],
                                ];

                                $statusOptions = [
                                    'belum_upload' => ['label' => 'Belum Upload', 'color' => 'bg-gray-100 text-gray-800'],
                                    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'color' => 'bg-yellow-100 text-yellow-800'],
                                    'tidak_sesuai' => ['label' => 'Tidak Sesuai', 'color' => 'bg-red-100 text-red-800'],
                                    'perlu_perbaikan' => ['label' => 'Perlu Perbaikan', 'color' => 'bg-orange-100 text-orange-800'],
                                    'disetujui' => ['label' => 'Disetujui', 'color' => 'bg-green-100 text-green-800'],
                                ];
                            ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dokumenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $statusField = 'status_' . $dok['key'];
                                    $catatanField = 'catatan_' . $dok['key'];
                                    $currentStatus = $this->$statusField ?? 'belum_upload';
                                    $statusInfo = $statusOptions[$currentStatus] ?? ['label' => $currentStatus, 'color' => 'bg-gray-100 text-gray-800'];

                                    // Cari dokumen yang sudah di-upload berdasarkan jenis
                                    $uploadedDokumen = $dokumen->firstWhere('jenis_dokumen', $dok['key']);

                                    // Khusus untuk rapor, cek juga field file_rapor di pendaftar
                                    $fileRapor = null;
                                    if ($dok['key'] === 'rapor' && $pendaftar->file_rapor) {
                                        $fileRapor = $pendaftar->file_rapor;
                                    }
                                ?>

                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 mb-2"><?php echo e($dok['label']); ?></h4>
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?php echo e($statusInfo['color']); ?>">
                                                <?php echo e($statusInfo['label']); ?>

                                            </span>
                                        </div>
                                    </div>

                                    <!-- File yang Sudah Di-upload -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadedDokumen || $fileRapor): ?>
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                            <div class="flex items-start space-x-3">
                                                <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        <?php echo e($uploadedDokumen ? $uploadedDokumen->nama_file : basename($fileRapor)); ?>

                                                    </p>
                                                    <div class="flex items-center space-x-3 mt-1">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadedDokumen): ?>
                                                            <span class="text-xs text-gray-500"><?php echo e(number_format($uploadedDokumen->ukuran_file / 1024, 2)); ?> KB</span>
                                                            <span class="text-xs text-gray-500"><?php echo e($uploadedDokumen->tanggal_upload->format('d/m/Y H:i')); ?></span>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uploadedDokumen): ?>
                                                            <a href="<?php echo e(route('dokumen.view', $uploadedDokumen->id)); ?>"
                                                               target="_blank"
                                                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                </svg>
                                                                Lihat File
                                                            </a>
                                                        <?php elseif($fileRapor): ?>
                                                            <span class="text-xs text-amber-600 font-medium">Legacy file (refresh page to migrate)</span>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3 text-center">
                                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-500">Belum ada file yang di-upload</p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <!-- Status Dropdown -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status</label>
                                            <select wire:model="<?php echo e($statusField); ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm">
                                                <option value="belum_upload">Belum Upload</option>
                                                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                                                <option value="tidak_sesuai">Tidak Sesuai</option>
                                                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                                <option value="disetujui">Disetujui</option>
                                            </select>
                                        </div>

                                        <!-- Catatan -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                            <input
                                                type="text"
                                                wire:model="<?php echo e($catatanField); ?>"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm"
                                                placeholder="Tambahkan catatan (opsional)"
                                            >
                                        </div>
                                    </div>

                                    <!-- Tombol Simpan -->
                                    <div class="mt-3">
                                        <button
                                            wire:click="updateStatusDokumen('<?php echo e($dok['key']); ?>')"
                                            class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition"
                                        >
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Simpan Status <?php echo e($dok['label']); ?>

                                        </button>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="flex justify-end">
                <a href="<?php echo e(route('panitia.pendaftar')); ?>" class="px-6 py-3 bg-gray-600 text-white font-medium rounded-md hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Pendaftar
                </a>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\panitia\detail-pendaftar.blade.php ENDPATH**/ ?>