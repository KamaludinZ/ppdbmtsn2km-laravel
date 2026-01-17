<div>
    <div class="space-y-6">
        <!-- Notification -->
        <div 
            x-data="{ show: false, message: '' }" 
            x-on:kontak-saved.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)" 
            x-show="show" 
            x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-[60] flex items-center" 
            style="display: none;">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="message" class="font-bold"></span>
        </div>

        <!-- Global Contact Settings -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Kontak Utama & Media Sosial</h3>
            </div>
            <div class="p-6">
                <form wire:submit.prevent="saveGlobalSettings">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kontak Info -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold text-emerald-600 uppercase tracking-widest border-b pb-2">Informasi Kontak PTSP</h4>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Email PTSP</label>
                                <input type="email" wire:model="app_email_ptsp" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_email_ptsp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">WhatsApp PTSP (Footer Kanan)</label>
                                <input type="text" wire:model="app_whatsapp_ptsp" placeholder="628..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Format: 628... (Kosongkan jika tidak ada)</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_whatsapp_ptsp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Sosial Media -->
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold text-blue-600 uppercase tracking-widest border-b pb-2">Link Media Sosial</h4>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Facebook URL</label>
                                <input type="url" wire:model="app_social_facebook" placeholder="https://facebook.com/..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_social_facebook'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Instagram URL</label>
                                <input type="url" wire:model="app_social_instagram" placeholder="https://instagram.com/..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_social_instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">YouTube URL</label>
                                <input type="url" wire:model="app_social_youtube" placeholder="https://youtube.com/..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_social_youtube'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">TikTok URL</label>
                                <input type="url" wire:model="app_social_tiktok" placeholder="https://tiktok.com/@..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-gray-900 focus:ring focus:ring-gray-200">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_social_tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Maps Section -->
                    <div class="space-y-6 mt-8">
                        <h4 class="text-xs font-bold text-purple-600 uppercase tracking-widest border-b pb-2">Link & Embed Maps</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Link Maps (untuk tombol "Buka di Google Maps")</label>
                                <input type="url" wire:model="app_maps_link" placeholder="https://maps.google.com/..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Link yang akan dibuka saat klik tombol "Buka di Google Maps"</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_maps_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Embed Maps (untuk tampilan peta)</label>
                                <input type="text" wire:model="app_maps_embed" placeholder="&lt;iframe src=&quot;...&quot;&gt;&lt;/iframe&gt;" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Kode embed dari Google Maps untuk menampilkan peta. Ukuran custom: lebar 100%, tinggi 450px (akan diubah otomatis menjadi 1216x450)</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_maps_embed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Service Hours Section -->
                    <div class="space-y-6 mt-8">
                        <h4 class="text-xs font-bold text-orange-600 uppercase tracking-widest border-b pb-2">Jam Pelayanan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Senin</label>
                                <input type="text" wire:model="app_service_hours_monday" placeholder="07:00 - 15:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 15:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_monday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Selasa</label>
                                <input type="text" wire:model="app_service_hours_tuesday" placeholder="07:00 - 15:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 15:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_tuesday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Rabu</label>
                                <input type="text" wire:model="app_service_hours_wednesday" placeholder="07:00 - 15:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 15:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_wednesday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Kamis</label>
                                <input type="text" wire:model="app_service_hours_thursday" placeholder="07:00 - 15:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 15:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_thursday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Jumat</label>
                                <input type="text" wire:model="app_service_hours_friday" placeholder="07:00 - 11:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 11:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_friday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Sabtu</label>
                                <input type="text" wire:model="app_service_hours_saturday" placeholder="07:00 - 13:00 WIB" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: 07:00 - 13:00 WIB</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_saturday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Minggu</label>
                                <input type="text" wire:model="app_service_hours_sunday" placeholder="Tutup" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: Tutup atau Jam Operasional</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_sunday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase tracking-wider">Hari Besar/Libur</label>
                                <input type="text" wire:model="app_service_hours_holiday" placeholder="Tutup" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200">
                                <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold">Contoh: Tutup atau Jam Operasional</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['app_service_hours_holiday'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs font-semibold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl hover:bg-emerald-700 transition flex items-center font-bold shadow-md uppercase tracking-widest text-xs active:scale-95">
                            <svg wire:loading.remove wire:target="saveGlobalSettings" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="saveGlobalSettings" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Link Terkait Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Link Terkait (Footer)</h3>
                </div>
                <button wire:click="createLink" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-emerald-50 transition shadow-sm active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Link
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Link</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $linkTerkaits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($link->urutan); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($link->nama); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 italic">
                                    <a href="<?php echo e($link->url); ?>" target="_blank" class="hover:underline"><?php echo e(Str::limit($link->url, 40)); ?></a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($link->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($link->aktif ? 'Aktif' : 'Non-Aktif'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="editLink(<?php echo e($link->id); ?>)" class="text-blue-600 hover:text-blue-900 mr-3 font-bold uppercase tracking-tighter">Edit</button>
                                    <button wire:click="deleteLink(<?php echo e($link->id); ?>)" wire:confirm="Yakin ingin menghapus link ini?" class="text-red-600 hover:text-red-900 font-bold uppercase tracking-tighter">Hapus</button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 italic">Belum ada data link terkait.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panitia List Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Daftar Kontak Panitia (WA)</h3>
                </div>
                <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg font-bold uppercase tracking-wider text-xs hover:bg-blue-50 transition shadow-sm active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Panitia
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor WA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($item->urutan); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($item->nama); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($item->nomor_wa); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($item->keterangan ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($item->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($item->aktif ? 'Aktif' : 'Non-Aktif'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit(<?php echo e($item->id); ?>)" class="text-blue-600 hover:text-blue-900 mr-3 font-bold uppercase tracking-tighter">Edit</button>
                                    <button wire:click="delete(<?php echo e($item->id); ?>)" wire:confirm="Yakin ingin menghapus kontak ini?" class="text-red-600 hover:text-red-900 font-bold uppercase tracking-tighter">Hapus</button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data kontak panitia.</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals Container -->
    
    <!-- Modal Link Terkait -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModalLink): ?>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeModalLink"></div>

            <!-- Modal Box -->
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full z-[10000] border border-gray-100">
                <form wire:submit.prevent="<?php echo e($isEditLink ? 'updateLink' : 'storeLink'); ?>">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center">
                            <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <?php echo e($isEditLink ? 'Edit Link Terkait' : 'Tambah Link Terkait'); ?>

                        </h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Nama Link</label>
                                <input type="text" wire:model="link_nama" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all font-medium py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['link_nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">URL (Link Tujuan)</label>
                                <input type="text" wire:model="link_url" placeholder="https://..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all font-medium py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['link_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Urutan</label>
                                    <input type="number" wire:model="link_urutan" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all font-medium py-3">
                                </div>
                                                                    <div class="flex items-center pt-5">
                                                                        <label class="inline-flex items-center cursor-pointer group">
                                                                            <div class="relative">
                                                                                <input type="checkbox" wire:model.live="link_aktif" class="sr-only">
                                                                                <div class="block w-10 h-6 rounded-full transition-colors <?php echo e($link_aktif ? 'bg-emerald-500' : 'bg-gray-300'); ?>"></div>
                                                                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform <?php echo e($link_aktif ? 'translate-x-4' : ''); ?>"></div>
                                                                            </div>
                                                                            <span class="ml-3 text-sm font-bold text-gray-700 uppercase tracking-widest group-hover:text-emerald-600 transition-colors">Aktif</span>
                                                                        </label>
                                                                    </div>                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-5 flex flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-emerald-700 shadow-lg transform hover:-translate-y-0.5 active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="storeLink,updateLink">Simpan Link</span>
                            <span wire:loading wire:target="storeLink,updateLink">Proses...</span>
                        </button>
                        <button type="button" wire:click="closeModalLink" class="bg-white text-gray-700 px-8 py-3 rounded-xl font-bold border border-gray-200 uppercase tracking-widest text-xs hover:bg-gray-100 transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Modal Panitia -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-0 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/10 backdrop-blur-sm transition-opacity z-[9998]" wire:click="closeModal"></div>

            <!-- Modal Box -->
            <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full z-[10000] border border-gray-100">
                <form wire:submit.prevent="<?php echo e($isEdit ? 'update' : 'store'); ?>">
                    <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <?php echo e($isEdit ? 'Edit Kontak Panitia' : 'Tambah Kontak Panitia'); ?>

                        </h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Nama Panitia</label>
                                <input type="text" wire:model="nama" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all font-medium py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Nomor WhatsApp</label>
                                <input type="text" wire:model="nomor_wa" placeholder="628..." class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all font-medium py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_wa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Keterangan (Jabatan)</label>
                                <input type="text" wire:model="keterangan" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all font-medium py-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1 tracking-widest">Urutan</label>
                                    <input type="number" wire:model="urutan" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all font-medium py-3">
                                </div>
                                                                    <div class="flex items-center pt-5">
                                                                        <label class="inline-flex items-center cursor-pointer group">
                                                                            <div class="relative">
                                                                                <input type="checkbox" wire:model.live="aktif" class="sr-only">
                                                                                <div class="block w-10 h-6 rounded-full transition-colors <?php echo e($aktif ? 'bg-blue-500' : 'bg-gray-300'); ?>"></div>
                                                                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform <?php echo e($aktif ? 'translate-x-4' : ''); ?>"></div>
                                                                            </div>
                                                                            <span class="ml-3 text-sm font-bold text-gray-700 uppercase tracking-widest group-hover:text-blue-600 transition-colors">Aktif</span>
                                                                        </label>
                                                                    </div>                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-8 py-5 flex flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-blue-700 shadow-lg transform hover:-translate-y-0.5 active:scale-95 transition-all">
                            <span wire:loading.remove wire:target="store,update">Simpan Kontak</span>
                            <span wire:loading wire:target="store,update">Proses...</span>
                        </button>
                        <button type="button" wire:click="closeModal" class="bg-white text-gray-700 px-8 py-3 rounded-xl font-bold border border-gray-200 uppercase tracking-widest text-xs hover:bg-gray-100 transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\kontak.blade.php ENDPATH**/ ?>