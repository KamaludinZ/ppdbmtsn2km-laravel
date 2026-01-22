<div x-data="{ show: <?php if ((object) ('isSendMessageModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isSendMessageModalOpen'->value()); ?>')<?php echo e('isSendMessageModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isSendMessageModalOpen'); ?>')<?php endif; ?> }" x-show="show" x-cloak>
    <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity z-[9998]" aria-hidden="true" @click="show = false; $wire.closeSendMessageModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[10000]" role="dialog" aria-modal="true">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" :class="$wire.messageType === 'email' ? 'text-blue-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="$wire.messageType === 'email'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        <path x-show="$wire.messageType === 'whatsapp'" fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                    </svg>
                    <span x-text="$wire.messageType === 'email' ? 'Kirim Email Manual' : 'Kirim WhatsApp Manual'"></span>
                </h3>

                <div class="space-y-4">
                    <!-- Template Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Template</label>
                        <select wire:model.live="selectedMessageTemplate" wire:change="loadMessageTemplate" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            <option value="">-- Pilih Template --</option>
                            <option value="pendaftaran">Pendaftaran Berhasil</option>
                            <option value="verifikasi_disetujui">Verifikasi Disetujui</option>
                            <option value="verifikasi_ditolak">Verifikasi Ditolak</option>
                            <option value="verifikasi_perbaiki">Verifikasi Perlu Perbaikan</option>
                            <option value="verifikasi_transfer_disetujui">Verifikasi Transfer Disetujui</option>
                            <option value="verifikasi_transfer_ditolak">Verifikasi Transfer Ditolak</option>
                            <option value="verifikasi_transfer_perbaiki">Verifikasi Transfer Perlu Perbaikan</option>
                            <option value="kelulusan_lulus">Kelulusan - Lulus Seleksi</option>
                            <option value="kelulusan_tidak_lulus">Kelulusan - Tidak Lulus</option>
                            <option value="kelulusan_cadangan">Kelulusan - Cadangan</option>
                            <option value="daftar_ulang_disetujui">Daftar Ulang Disetujui</option>
                            <option value="daftar_ulang_ditolak">Daftar Ulang Ditolak</option>
                            <option value="daftar_ulang_perbaiki">Daftar Ulang Perlu Perbaikan</option>
                        </select>
                    </div>

                    <!-- Catatan (untuk placeholder) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                        <input type="text" wire:model="messageNote" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Catatan tambahan untuk placeholder {catatan}">
                        <p class="text-xs text-gray-500 mt-1">Akan menggantikan placeholder {catatan} dalam template</p>
                    </div>

                    <!-- Subject (hanya untuk email) -->
                    <div x-show="$wire.messageType === 'email'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject Email</label>
                        <input type="text" wire:model="messageSubject" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" placeholder="Subject email">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['messageSubject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Message Body -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                        <textarea wire:model="messageBody" rows="8" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 font-mono text-sm" placeholder="Isi pesan..."></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['messageBody'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-500 mt-1">Anda dapat mengedit pesan sebelum dikirim</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    type="button"
                    wire:click="sendMessage"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                    :class="$wire.messageType === 'email' ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span x-text="$wire.messageType === 'email' ? 'Kirim Email' : 'Kirim WhatsApp'"></span>
                </button>
                <button
                    type="button"
                    wire:click="closeSendMessageModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/livewire/admin/partials/send-message-modal.blade.php ENDPATH**/ ?>