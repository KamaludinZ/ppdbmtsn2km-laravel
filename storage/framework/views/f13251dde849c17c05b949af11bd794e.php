<div>
    <!-- Modal Edit Profil -->
    <div x-data="{ show: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?> }" x-show="show" class="fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="edit-profil-modal" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9999]" aria-hidden="true" @click="show = false; $wire.closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">
                <form wire:submit.prevent="updateProfil">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="edit-profil-modal">
                                    Edit Profil
                                </h3>
                                <p class="text-sm text-gray-500">Perbarui informasi profil Anda</p>
                            </div>
                        </div>

                        <div class="space-y-4 mt-4">
                            <!-- Nama -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="Masukkan nama lengkap"
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input
                                    type="email"
                                    wire:model="email"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 disabled:bg-gray-100 disabled:text-gray-500"
                                    placeholder="Masukkan email"
                                    <?php if(Auth::user()->role !== 'admin'): ?> disabled <?php endif; ?>
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role !== 'admin'): ?>
                                    <p class="text-xs text-gray-500 mt-1">Email hanya dapat diubah oleh Admin.</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Nomor WhatsApp Orang Tua/Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp Orang Tua/Wali</label>
                                <input
                                    type="text"
                                    wire:model="telepon_ortu"
                                    id="telepon_edit"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    placeholder="Contoh: 628123456789"
                                    oninput="formatPhoneNumber(this)"
                                >
                                <p class="text-xs text-gray-500 mt-1">Format otomatis: 0xxx akan menjadi 62xxx</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['telepon_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Ubah Password (Opsional)</h4>
                                <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah password</p>

                                <!-- Password Lama -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                                    <input
                                        type="password"
                                        wire:model="current_password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                        placeholder="Masukkan password lama"
                                    >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- Password Baru -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input
                                        type="password"
                                        wire:model="new_password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                        placeholder="Masukkan password baru (min. 6 karakter)"
                                    >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <!-- Konfirmasi Password Baru -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                    <input
                                        type="password"
                                        wire:model="new_password_confirmation"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                        placeholder="Konfirmasi password baru"
                                    >
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['new_password_confirmation'];
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

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /**
         * Auto-format nomor WhatsApp ke format 62xxx
         * Mengubah:
         * - 0xxx menjadi 62xxx
         * - +62xxx menjadi 62xxx
         * - 62xxx tetap 62xxx
         */
        function formatPhoneNumber(input) {
            // Simpan nilai asli
            let originalValue = input.value;

            // Simpan posisi cursor
            let cursorPosition = input.selectionStart;
            let oldLength = originalValue.length;

            // Ambil nilai input
            let value = originalValue;

            // Hapus semua karakter non-digit
            value = value.replace(/\D/g, '');

            // Format berdasarkan awalan
            if (value.startsWith('0')) {
                // Jika dimulai dengan 0, ganti dengan 62
                value = '62' + value.substring(1);
            } else if (value.startsWith('62')) {
                // Jika sudah dimulai dengan 62, biarkan
                value = value;
            } else if (value.length > 0) {
                // Jika tidak dimulai dengan 0 atau 62, tambahkan 62 di depan
                value = '62' + value;
            }

            // Hanya update jika nilai berubah (mencegah infinite loop)
            if (value !== originalValue) {
                // Update nilai input
                input.value = value;

                // Dispatch event untuk Livewire hanya jika nilai berubah
                input.dispatchEvent(new Event('input', { bubbles: true }));

                // Restore posisi cursor (adjust untuk perubahan panjang)
                let newLength = value.length;
                let lengthDiff = newLength - oldLength;
                let newCursorPos = cursorPosition + lengthDiff;

                // Pastikan cursor position valid
                if (newCursorPos < 0) newCursorPos = 0;
                if (newCursorPos > value.length) newCursorPos = value.length;

                input.setSelectionRange(newCursorPos, newCursorPos);
            }
        }
    </script>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\edit-profil.blade.php ENDPATH**/ ?>