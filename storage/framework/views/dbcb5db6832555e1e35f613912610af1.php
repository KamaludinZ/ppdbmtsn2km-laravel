<div>
    <!-- Success Message -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pengaturan Pesan Notifikasi</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola template pesan untuk notifikasi email dan WhatsApp</p>
            </div>
            <button wire:click="resetToDefault" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Reset ke Default
            </button>
        </div>
    </div>

    <!-- Info Variabel -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h4 class="font-bold text-blue-800 mb-2">Variabel yang Tersedia</h4>
                <p class="text-sm text-blue-700 mb-2">Gunakan variabel berikut dalam template pesan Anda:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{nama}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{nomor_pendaftaran}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{email}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{telepon}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{catatan}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{url_dashboard}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{jalur}</code>
                    <code class="bg-white px-2 py-1 rounded border border-blue-200">{status}</code>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button
                wire:click="$set('activeTab', 'email')"
                class="<?php if($activeTab === 'email'): ?> border-emerald-500 text-emerald-600 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Template Email
            </button>
            <button
                wire:click="$set('activeTab', 'whatsapp')"
                class="<?php if($activeTab === 'whatsapp'): ?> border-emerald-500 text-emerald-600 <?php else: ?> border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 <?php endif; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Template WhatsApp
            </button>
        </nav>
    </div>

    <!-- Email Templates Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'email'): ?>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form wire:submit.prevent="saveEmailSettings">
                <!-- Pendaftaran Berhasil -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                        Email Pendaftaran Berhasil
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat user berhasil membuat akun dan menyelesaikan formulir pendaftaran</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_pendaftaran_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_pendaftaran_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_pendaftaran_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_pendaftaran_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Verifikasi Disetujui -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                        Email Verifikasi Disetujui
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar diverifikasi dan disetujui oleh panitia</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_verifikasi_disetujui_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_disetujui_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_verifikasi_disetujui_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_disetujui_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Verifikasi Ditolak -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-100 text-red-700 rounded-full flex items-center justify-center mr-2 text-sm">3</span>
                        Email Verifikasi Ditolak
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar ditolak oleh panitia</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_verifikasi_ditolak_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_ditolak_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_verifikasi_ditolak_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_ditolak_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Verifikasi Perlu Perbaikan -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-orange-100 text-orange-700 rounded-full flex items-center justify-center mr-2 text-sm">4</span>
                        Email Verifikasi Perlu Perbaikan
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar perlu diperbaiki</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_verifikasi_perbaiki_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_perbaiki_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_verifikasi_perbaiki_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_verifikasi_perbaiki_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Kelulusan Lulus -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center mr-2 text-sm">5</span>
                        Email Kelulusan - Lulus Seleksi
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat pendaftar dinyatakan lulus seleksi</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_kelulusan_lulus_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_kelulusan_lulus_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_kelulusan_lulus_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_kelulusan_lulus_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Kelulusan Tidak Lulus -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-100 text-red-700 rounded-full flex items-center justify-center mr-2 text-sm">6</span>
                        Email Kelulusan - Tidak Lulus
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat pendaftar tidak lulus seleksi</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_kelulusan_tidak_lulus_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_kelulusan_tidak_lulus_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_kelulusan_tidak_lulus_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_kelulusan_tidak_lulus_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Daftar Ulang Disetujui -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center mr-2 text-sm">7</span>
                        Email Daftar Ulang Disetujui
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat daftar ulang pendaftar disetujui</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_daftar_ulang_disetujui_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_daftar_ulang_disetujui_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_daftar_ulang_disetujui_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_daftar_ulang_disetujui_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Daftar Ulang Ditolak -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center mr-2 text-sm">8</span>
                        Email Daftar Ulang Ditolak
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat daftar ulang pendaftar ditolak</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject Email</label>
                        <input type="text" wire:model="email_daftar_ulang_ditolak_subject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_daftar_ulang_ditolak_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Isi Email</label>
                        <textarea wire:model="email_daftar_ulang_ditolak_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_daftar_ulang_ditolak_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Template Email
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- WhatsApp Templates Tab -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'whatsapp'): ?>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form wire:submit.prevent="saveWhatsAppSettings">
                <!-- Pendaftaran Berhasil -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                        WhatsApp Pendaftaran Berhasil
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat user berhasil membuat akun dan menyelesaikan formulir pendaftaran</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_pendaftaran_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm" placeholder="Gunakan *text* untuk bold, _text_ untuk italic"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_pendaftaran_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Format: gunakan *bold* untuk tebal, _italic_ untuk miring</p>
                    </div>
                </div>

                <!-- Verifikasi Disetujui -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                        WhatsApp Verifikasi Disetujui
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar diverifikasi dan disetujui</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_verifikasi_disetujui_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_verifikasi_disetujui_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Verifikasi Ditolak -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-100 text-red-700 rounded-full flex items-center justify-center mr-2 text-sm">3</span>
                        WhatsApp Verifikasi Ditolak
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar ditolak</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_verifikasi_ditolak_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_verifikasi_ditolak_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Verifikasi Perlu Perbaikan -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-orange-100 text-orange-700 rounded-full flex items-center justify-center mr-2 text-sm">4</span>
                        WhatsApp Verifikasi Perlu Perbaikan
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat berkas pendaftar perlu diperbaiki</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_verifikasi_perbaiki_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_verifikasi_perbaiki_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Kelulusan Lulus -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center mr-2 text-sm">5</span>
                        WhatsApp Kelulusan - Lulus Seleksi
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat pendaftar dinyatakan lulus seleksi</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_kelulusan_lulus_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_kelulusan_lulus_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Kelulusan Tidak Lulus -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-red-100 text-red-700 rounded-full flex items-center justify-center mr-2 text-sm">6</span>
                        WhatsApp Kelulusan - Tidak Lulus
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat pendaftar tidak lulus seleksi</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_kelulusan_tidak_lulus_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_kelulusan_tidak_lulus_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Daftar Ulang Disetujui -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center mr-2 text-sm">7</span>
                        WhatsApp Daftar Ulang Disetujui
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat daftar ulang pendaftar disetujui</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_daftar_ulang_disetujui_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_daftar_ulang_disetujui_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Daftar Ulang Ditolak -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center mr-2 text-sm">8</span>
                        WhatsApp Daftar Ulang Ditolak
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Dikirim saat daftar ulang pendaftar ditolak</p>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan WhatsApp</label>
                        <textarea wire:model="wa_daftar_ulang_ditolak_body" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm"></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['wa_daftar_ulang_ditolak_body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Template WhatsApp
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\pengaturan-pesan.blade.php ENDPATH**/ ?>