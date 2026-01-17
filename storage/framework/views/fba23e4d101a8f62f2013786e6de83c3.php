<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($pendaftar->status_pendaftaran, ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'ditolak', 'cadangan'])): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Kartu Tanda Peserta Ujian</h2>
                    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center print:hidden">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Kartu
                    </button>
                </div>

                <!-- Card Design - A4 Portrait Format for print -->
                <div class="mx-auto border-2 border-gray-300 bg-white shadow-lg print:shadow-none print:border-black" style="width: 210mm; min-height: 297mm; padding: 12mm 20mm;">
                    <!-- Kop Header (dari Settings) -->
                    <div class="flex items-start gap-6 pb-3 border-b-4 border-black mb-4">
                        <!-- Logo Kiri -->
                        <div class="flex-shrink-0 w-20 h-20 flex items-center justify-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($printSettings['print_logo_left'])): ?>
                                <img src="<?php echo e($printSettings['print_logo_left']); ?>" alt="Logo Kiri" class="max-w-full max-h-full object-contain">
                            <?php else: ?>
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center print:hidden">
                                    <span class="text-xs text-gray-500">LOGO</span>
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
                                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center print:hidden">
                                    <span class="text-xs text-gray-500">LOGO</span>
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
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->nomor_pendaftaran); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">Nama Lengkap</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->nama_lengkap); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">NISN</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->nisn); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">Jenis Kelamin</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">Jalur Pendaftaran</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->jalurPendaftaran->nama ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">Asal Sekolah</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->asal_sekolah); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1">Nomor WA Orang Tua</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($pendaftar->telepon_ortu ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="font-medium text-gray-700 py-1 align-top">Alamat</td>
                                    <td class="font-semibold text-gray-900 py-1">: <?php echo e($alamatLengkap); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="flex items-start justify-center">
                            <div class="w-28 h-36 border-2 border-gray-400 bg-gray-100 flex items-center justify-center overflow-hidden">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->foto): ?>
                                    <img src="<?php echo e(Storage::url($pendaftar->foto)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">Foto 3x4</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Prestasi (hanya muncul jika ada prestasi, maksimal 5 baris) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendaftar->prestasis && $pendaftar->prestasis->count() > 0): ?>
                        <?php
                            // Limit hanya 5 prestasi pertama
                            $prestasiTampil = $pendaftar->prestasis->take(5);
                        ?>
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
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->tahun); ?></td>
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->nama_lomba); ?></td>
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->bidang_lomba); ?></td>
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->nama_penyelenggara); ?></td>
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->tingkat_lomba); ?></td>
                                            <td class="border border-gray-300 px-1 py-1"><?php echo e($prestasi->peringkat); ?></td>
                                            <td class="border border-gray-300 px-1 py-1 text-center"><?php echo e(substr($prestasi->kategori_lomba, 0, 1)); ?></td>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($catatanKartuUjian): ?>
                    <div class="mt-4">
                        <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase">CATATAN:</h4>
                        <div class="border-2 border-gray-400 rounded p-2 bg-yellow-50">
                            <div class="whitespace-pre-wrap text-xs text-gray-700"><?php echo e($catatanKartuUjian); ?></div>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Print styles -->
        <style>
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 0;
                }
                body {
                    margin: 0;
                    padding: 0;
                }
                .print\:hidden {
                    display: none !important;
                }
            }
        </style>
    <?php else: ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Kartu ujian belum tersedia. Kartu ujian dapat dicetak setelah data pendaftaran Anda <strong>DISETUJUI</strong> oleh panitia.
                        Status saat ini: <strong class="uppercase"><?php echo e(str_replace('_', ' ', $pendaftar->status_pendaftaran ?? 'menunggu')); ?></strong>.
                        Silakan cek menu <a href="<?php echo e(route('pendaftar.dashboard')); ?>" class="font-bold underline">Dashboard</a> untuk informasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\pendaftar\kartu-ujian.blade.php ENDPATH**/ ?>