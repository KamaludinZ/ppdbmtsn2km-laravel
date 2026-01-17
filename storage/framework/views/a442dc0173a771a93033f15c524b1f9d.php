<div>
    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    wire:click="switchTab('pendaftaran')"
                    class="<?php echo e($activeTab === 'pendaftaran' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📊 Laporan Pendaftaran
                </button>
                <button
                    wire:click="switchTab('data_lengkap')"
                    class="<?php echo e($activeTab === 'data_lengkap' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📝 Data Pendaftar Lengkap
                </button>
                <button
                    wire:click="switchTab('daftar_ulang')"
                    class="<?php echo e($activeTab === 'daftar_ulang' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    ✅ Daftar Ulang Madrasah
                </button>
                <button
                    wire:click="switchTab('seragam')"
                    class="<?php echo e($activeTab === 'seragam' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👕 Seragam & Ukuran
                </button>
            </nav>
        </div>
    </div>

    <div class="space-y-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'pendaftaran'): ?>
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Filter Laporan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" wire:model.live="tanggalMulai" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" wire:model.live="tanggalAkhir" class="w-full rounded-lg border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jalur</label>
                    <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">Semua Jalur</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                        <option value="data_disetujui">Data Disetujui</option>
                        <option value="lulus">Lulus</option>
                        <option value="tidak_lulus">Tidak Lulus</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e(number_format($stats['total'])); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Menunggu</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1"><?php echo e(number_format($stats['menunggu'])); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Lulus</p>
                <p class="text-2xl font-bold text-green-600 mt-1"><?php echo e(number_format($stats['lulus'])); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Tidak Lulus</p>
                <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e(number_format($stats['tidak_lulus'])); ?></p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm font-medium text-gray-600">Cadangan</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo e(number_format($stats['cadangan'])); ?></p>
            </div>
        </div>

        <!-- Per Jalur Statistics -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Statistik Per Jalur</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jalur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pendaftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php
                                $percentage = $jalur->kuota > 0 ? round(($jalur->pendaftar_count / $jalur->kuota) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-<?php echo e($jalur->warna); ?>-100 text-<?php echo e($jalur->warna); ?>-800">
                                        <?php echo e($jalur->nama); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(number_format($jalur->kuota)); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(number_format($jalur->pendaftar_count)); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-<?php echo e($jalur->warna); ?>-600 h-2.5 rounded-full" style="width: <?php echo e(min($percentage, 100)); ?>%"></div>
                                        </div>
                                        <span class="text-sm text-gray-900"><?php echo e($percentage); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="flex justify-end gap-3">
            <button wire:click="exportExcel" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>
            <button wire:click="exportPdf" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF
            </button>
        </div>

        <?php elseif($activeTab === 'data_lengkap'): ?>
        <!-- Laporan Data Lengkap Pendaftar -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Data Lengkap Pendaftar</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan seluruh data pendaftar secara lengkap termasuk data pribadi, asal sekolah, prestasi, data orang tua, pendidikan orang tua, dan penghasilan orang tua.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pendaftaran</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                            <option value="data_disetujui">Data Disetujui</option>
                            <option value="lulus">Lulus</option>
                            <option value="diterima">Diterima</option>
                            <option value="tidak_lulus">Tidak Lulus</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportDataLengkap" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi: Nama, NISN, Tempat/Tanggal Lahir, Jenis Kelamin, Agama, Alamat</li>
                        <li>Data Asal Sekolah: Nama Sekolah, Jenis Sekolah, NSM, NPSN, Alamat Sekolah</li>
                        <li>Data Prestasi: Daftar Prestasi yang Dimiliki</li>
                        <li>Data Orang Tua: Nama Ayah, Nama Ibu, Pekerjaan Ayah/Ibu, Telepon</li>
                        <li>Data Penghasilan: Penghasilan Orang Tua</li>
                        <li>Nilai Rapor: Nilai B.Indo, Matematika, IPA kelas 5-6</li>
                        <li>Status Pendaftaran & Jalur</li>
                    </ul>
                </div>
            </div>
        </div>

        <?php elseif($activeTab === 'daftar_ulang'): ?>
        <!-- Laporan Daftar Ulang Madrasah -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Daftar Ulang Madrasah</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data siswa yang telah melakukan daftar ulang madrasah, termasuk status pembayaran dan kelengkapan berkas.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Daftar Ulang</label>
                        <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="sudah_daftar_ulang">Sudah Daftar Ulang</option>
                            <option value="belum_daftar_ulang">Belum Daftar Ulang</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportDaftarUlang" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa</li>
                        <li>Status Daftar Ulang</li>
                        <li>Tanggal Daftar Ulang</li>
                        <li>Status Pembayaran Daftar Ulang</li>
                        <li>Bukti Pembayaran</li>
                        <li>Status Pernyataan</li>
                        <li>Kelengkapan Berkas</li>
                    </ul>
                </div>
            </div>
        </div>

        <?php elseif($activeTab === 'seragam'): ?>
        <!-- Laporan Seragam & Ukuran -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Laporan Seragam & Ukuran</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    Laporan ini menampilkan data ukuran seragam siswa baru yang telah diterima, untuk keperluan pemesanan seragam.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jalur Pendaftaran</label>
                        <select wire:model.live="filterJalur" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua Jalur</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $jalurPendaftaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jalur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($jalur->id); ?>"><?php echo e($jalur->nama); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                        <select wire:model.live="filterJenisKelamin" class="w-full rounded-lg border-gray-300 shadow-sm">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button wire:click="exportSeragam" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">
                    <p class="font-semibold mb-2">Data yang akan diexport meliputi:</p>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        <li>Data Pribadi Siswa (Nama, NISN, Jenis Kelamin)</li>
                        <li>Ukuran Seragam Atas (S, M, L, XL, XXL, XXXL)</li>
                        <li>Ukuran Seragam Bawah (Celana/Rok)</li>
                        <li>Ukuran Sepatu</li>
                        <li>Ukuran Atribut Lainnya (Topi, Dasi, dll)</li>
                        <li>Status Pembayaran Seragam</li>
                        <li>Tanggal Pemesanan</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\laporan.blade.php ENDPATH**/ ?>