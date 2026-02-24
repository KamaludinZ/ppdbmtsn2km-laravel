<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span><?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[10000] flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="space-y-6">
        <!-- Catatan Psikologi (jika ada) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData && $tesPsikologiData->catatan_psikologi): ?>
            <div class="bg-purple-50 border-l-4 border-purple-500 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-purple-100 flex items-center">
                    <svg class="w-6 h-6 text-purple-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-purple-900 uppercase tracking-wider">Catatan Psikologi</h3>
                </div>
                <div class="p-6">
                    <div class="bg-white border border-purple-200 rounded-lg p-4">
                        <p class="text-gray-800 whitespace-pre-wrap"><?php echo e($tesPsikologiData->catatan_psikologi); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Informasi Tes Psikologi -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$tesPsikologiData || $tesPsikologiData->status_verifikasi !== 'disetujui'): ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-emerald-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Tes Psikologi</h3>
            </div>
            <div class="p-6">
                <div class="prose max-w-none">
                    <?php echo nl2br(e($setting->informasi)); ?>

                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Survey Pertanyaan (Muncul jika aktif) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$tesPsikologiData || $tesPsikologiData->status_verifikasi !== 'disetujui'): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->survey_aktif && $surveyQuestions && $surveyQuestions->count() > 0): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-indigo-600 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Survey</h3>
                    </div>
                    <div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($surveyCompleted): ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Sudah Diisi
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Diisi
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($surveyCompleted): ?>
                    <!-- Survey Sudah Diisi - Hanya tampilkan pesan sukses -->
                    <div class="p-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-base font-bold text-green-900 mb-1">Survey Telah Dilengkapi</h4>
                            <p class="text-sm text-green-700">Terima kasih telah mengisi survey. Anda dapat melanjutkan ke upload bukti transfer di bawah.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Survey Belum Diisi - Tampilkan form lengkap -->
                    <div class="p-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->survey_informasi): ?>
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                                <p class="text-sm text-indigo-900">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo nl2br(e($setting->survey_informasi)); ?>

                                </p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-900 font-bold mb-1">Survey Wajib Diisi!</p>
                                    <p class="text-sm text-yellow-800">Anda harus mengisi survey terlebih dahulu sebelum dapat melakukan upload bukti pembayaran tes psikologi.</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-indigo-700">Progress Survey</span>
                                    <span class="text-sm font-medium text-indigo-700"><?php echo e($currentQuestionIndex + 1); ?> / <?php echo e($surveyQuestions->count()); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-indigo-600 h-3 rounded-full transition-all duration-300"
                                         style="width: <?php echo e((($currentQuestionIndex + 1) / $surveyQuestions->count()) * 100); ?>%"></div>
                                </div>
                            </div>

                            <?php
                                $question = $surveyQuestions[$currentQuestionIndex];
                            ?>

                            <!-- Question Card -->
                            <div class="border-2 border-indigo-200 rounded-lg p-6 bg-indigo-50">
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-bold text-lg mr-4 flex-shrink-0">
                                            <?php echo e($currentQuestionIndex + 1); ?>

                                        </span>
                                        <div class="flex-1">
                                            <label class="block text-base font-bold text-gray-900 mb-1">
                                                <?php echo e($question->pertanyaan); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($question->wajib_diisi): ?>
                                                    <span class="text-red-500 text-xl">*</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($question->deskripsi): ?>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo e($question->deskripsi); ?></p>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($question->tipe_input === 'text'): ?>
                                        <input type="text"
                                               wire:model.live="surveyAnswers.<?php echo e($question->id); ?>"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                               placeholder="Ketik jawaban Anda di sini...">

                                    <?php elseif($question->tipe_input === 'textarea'): ?>
                                        <textarea wire:model.live="surveyAnswers.<?php echo e($question->id); ?>"
                                                  rows="5"
                                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base"
                                                  placeholder="Ketik jawaban Anda di sini..."></textarea>

                                    <?php elseif($question->tipe_input === 'radio'): ?>
                                        <div class="space-y-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $question->options->sortBy('urutan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <label class="flex items-center cursor-pointer p-3 border-2 border-gray-300 rounded-lg hover:bg-white hover:border-indigo-500 transition">
                                                    
                                                    
                                                    <input type="radio"
                                                           name="question_<?php echo e($question->id); ?>"
                                                           wire:model.live="surveyAnswers.<?php echo e($question->id); ?>"
                                                           value="<?php echo e($option->id); ?>"
                                                           class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 mr-3 flex-shrink-0">
                                                    <span class="text-base text-gray-700"><?php echo e($option->label); ?></span>
                                                </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>

                                    <?php elseif($question->tipe_input === 'checkbox'): ?>
                                        <div class="space-y-3">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $question->options->sortBy('urutan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <label class="flex items-center cursor-pointer p-3 border-2 border-gray-300 rounded-lg hover:bg-white hover:border-indigo-500 transition">
                                                    
                                                    <input type="checkbox"
                                                           wire:model.live="surveyAnswers.<?php echo e($question->id); ?>"
                                                           value="<?php echo e($option->id); ?>"
                                                           class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 mr-3 flex-shrink-0">
                                                    <span class="text-base text-gray-700"><?php echo e($option->label); ?></span>
                                                </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>

                                    <?php elseif($question->tipe_input === 'select'): ?>
                                        <select wire:model.live="surveyAnswers.<?php echo e($question->id); ?>"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-base">
                                            <option value="">-- Pilih Jawaban --</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $question->options->sortBy('urutan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                
                                                <option value="<?php echo e($option->id); ?>"><?php echo e($option->label); ?></option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </select>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between items-center pt-4">
                                <button type="button"
                                        wire:click="previousQuestion"
                                        <?php if($currentQuestionIndex === 0): ?> disabled <?php endif; ?>
                                        class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    Sebelumnya
                                </button>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentQuestionIndex < $surveyQuestions->count() - 1): ?>
                                    
                                    <button type="button"
                                            wire:click="nextQuestion"
                                            <?php if(!$this->isCurrentQuestionAnswered): ?> disabled <?php endif; ?>
                                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                                        Selanjutnya
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                <?php else: ?>
                                    
                                    <button type="button"
                                            wire:click="simpanSurvey"
                                            <?php if(!$this->areAllRequiredQuestionsAnswered): ?> disabled <?php endif; ?>
                                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai & Simpan
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($question->wajib_diisi && !$this->isCurrentQuestionAnswered): ?>
                                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start">
                                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-red-900">Pertanyaan ini wajib diisi!</p>
                                        <p class="text-xs text-red-800 mt-1">Silakan jawab pertanyaan ini untuk melanjutkan ke pertanyaan berikutnya.</p>
                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Informasi Rekening Tujuan -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$tesPsikologiData || $tesPsikologiData->status_verifikasi !== 'disetujui'): ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-600 flex items-center">
                <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Informasi Rekening Tujuan Transfer</h3>
            </div>
            <div class="p-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->rekening_bank_nama): ?>
                    <div class="p-6 border-2 border-green-500 bg-green-50 rounded-lg">
                        <div class="text-center mb-4">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <h4 class="text-xl font-bold text-green-900"><?php echo e($setting->rekening_bank_nama); ?></h4>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center py-2 border-b border-green-200">
                                <span class="text-sm text-green-700 font-medium">Nomor Rekening:</span>
                                <span class="text-lg font-mono font-bold text-green-900"><?php echo e($setting->rekening_nomor); ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-green-200">
                                <span class="text-sm text-green-700 font-medium">Atas Nama:</span>
                                <span class="text-base font-semibold text-green-900"><?php echo e($setting->rekening_atas_nama); ?></span>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->rekening_keterangan): ?>
                            <div class="mt-4 p-3 bg-white rounded border border-green-200">
                                <p class="text-sm text-gray-700"><?php echo e($setting->rekening_keterangan); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Informasi rekening belum diatur oleh admin</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Biaya Tes Psikologi -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($opsiList->count() > 0): ?>
            <?php
                // Check if payment is already approved - disable options if true
                $isPaymentApproved = $tesPsikologiData && $tesPsikologiData->status_verifikasi === 'disetujui';
            ?>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-600 flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Biaya Tes Psikologi</h3>
                </div>
                <div class="p-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPaymentApproved): ?>
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm text-green-800 font-medium">Pembayaran Sudah Disetujui</p>
                                    <p class="text-xs text-green-600 mt-1">Opsi biaya tidak dapat diubah karena pembayaran sudah disetujui oleh panitia.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-900">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Petunjuk:</span> Pilih salah satu opsi biaya tes psikologi yang sesuai dengan pilihan Anda.
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="space-y-3 <?php echo e($isPaymentApproved ? 'pointer-events-none opacity-60' : ''); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $opsiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <label class="flex items-start p-4 border-2 rounded-lg <?php echo e($isPaymentApproved ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-blue-50'); ?> transition <?php echo e($selectedOpsi == $opsi->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200'); ?>">
                                <input type="radio" wire:model.live="selectedOpsi" value="<?php echo e($opsi->id); ?>" class="mt-1 h-5 w-5 text-blue-600 focus:ring-blue-500" <?php echo e($isPaymentApproved ? 'disabled' : ''); ?>>
                                <div class="ml-3 flex-1">
                                    <h4 class="font-semibold text-gray-900">Rp <?php echo e(number_format(floatval(str_replace('.', '', $opsi->nama)), 0, ',', '.')); ?></h4>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($opsi->deskripsi): ?>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo e($opsi->deskripsi); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Status Alert -->
        <?php if (isset($component)) { $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-alert','data' => ['status' => $tesPsikologiData->status_verifikasi,'catatan' => $tesPsikologiData->catatan_verifikasi,'type' => 'tes_psikologi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tesPsikologiData->status_verifikasi),'catatan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tesPsikologiData->catatan_verifikasi),'type' => 'tes_psikologi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $attributes = $__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__attributesOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d)): ?>
<?php $component = $__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d; ?>
<?php unset($__componentOriginal03ac6fe0abc1abf485c1b5ef9dba247d); ?>
<?php endif; ?>

        <!-- Upload Bukti Transfer -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-600 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider">Upload Bukti Transfer</h3>
                </div>
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData->status_verifikasi === 'belum_upload'): ?>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Belum Upload</span>
                    <?php elseif($tesPsikologiData->status_verifikasi === 'menunggu'): ?>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                    <?php elseif($tesPsikologiData->status_verifikasi === 'disetujui'): ?>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                    <?php elseif($tesPsikologiData->status_verifikasi === 'ditolak'): ?>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="p-6">
                <!-- Peringatan Survey Belum Diisi -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($setting->survey_aktif && !$surveyCompleted && $tesPsikologiData->status_verifikasi === 'belum_upload'): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-8 h-8 text-red-600 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h4 class="text-base font-bold text-red-900 mb-2">Tidak Dapat Upload Bukti Transfer</h4>
                                <p class="text-sm text-red-800 mb-3">Anda harus mengisi dan menyimpan <strong>Survey</strong> terlebih dahulu sebelum dapat melakukan upload bukti pembayaran.</p>
                                <p class="text-sm text-red-700">Silakan scroll ke atas dan lengkapi bagian <strong>"Informasi Survey"</strong> yang ditandai dengan warna ungu.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData->status_verifikasi === 'disetujui'): ?>
                    <!-- Status Disetujui -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-green-900 mb-2">Bukti Transfer Disetujui</h3>
                        <p class="text-sm text-green-700">Pembayaran tes psikologi Anda telah diverifikasi dan disetujui. Anda dapat melanjutkan ke tahap berikutnya.</p>

                        <div class="mt-4 flex justify-center space-x-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData->bukti_transfer): ?>
                                <a href="<?php echo e(route('view.bukti-bayar', ['pendaftar' => $pendaftar->id, 'type' => 'psikologi'])); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Bukti
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <button wire:click="downloadInvoice" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Invoice
                            </button>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData->catatan_verifikasi): ?>
                            <div class="mt-4 p-3 bg-white rounded">
                                <p class="text-sm text-gray-600"><span class="font-semibold">Catatan:</span> <?php echo e($tesPsikologiData->catatan_verifikasi); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php elseif($tesPsikologiData->status_verifikasi === 'ditolak'): ?>
                    <!-- Status Ditolak -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-red-900">Bukti Transfer Ditolak</h4>
                                <p class="text-sm text-red-700 mt-1">Silakan upload ulang bukti transfer yang sesuai.</p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tesPsikologiData->catatan_verifikasi): ?>
                                    <p class="text-sm text-red-700 mt-2"><span class="font-semibold">Alasan:</span> <?php echo e($tesPsikologiData->catatan_verifikasi); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Form Upload Ulang -->
                    <?php echo $__env->make('livewire.pendaftar.tes-psikologi-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php elseif($tesPsikologiData->status_verifikasi === 'menunggu'): ?>
                    <!-- Menunggu Verifikasi -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center mb-6">
                        <svg class="w-16 h-16 text-yellow-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-yellow-900 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-sm text-yellow-700">Bukti transfer Anda sedang dalam proses verifikasi oleh panitia.</p>
                        <p class="text-xs text-yellow-600 mt-2">Upload: <?php echo e($tesPsikologiData->tanggal_upload->format('d M Y H:i')); ?></p>
                    </div>

                    <!-- Detail Pembayaran yang Disubmit -->
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Detail Pembayaran yang Disubmit
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600">Bank/Metode:</span>
                                <span class="ml-2 font-medium text-gray-900"><?php echo e($tesPsikologiData->bank->nama_bank ?? '-'); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Waktu Transfer:</span>
                                <span class="ml-2 font-medium text-gray-900">
                                    <?php echo e($tesPsikologiData->waktu_transfer ? $tesPsikologiData->waktu_transfer->format('d M Y, H:i') : '-'); ?>

                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Nama Pengirim:</span>
                                <span class="ml-2 font-medium text-gray-900"><?php echo e($tesPsikologiData->nama_pengirim ?? '-'); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Upload:</span>
                                <span class="ml-2 font-medium text-gray-900"><?php echo e($tesPsikologiData->tanggal_upload->format('d M Y, H:i')); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transfer yang Telah Diupload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer yang Telah Diupload</label>
                        <div class="relative">
                            <img
                                src="<?php echo e(route('view.bukti-bayar', ['pendaftar' => $pendaftar->id, 'type' => 'psikologi'])); ?>"
                                alt="Bukti Transfer"
                                class="w-full max-w-md rounded-lg border border-gray-300"
                                onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\' viewBox=\'0 0 400 300\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'300\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-family=\'sans-serif\' font-size=\'14\' fill=\'%239ca3af\'%3EGagal memuat gambar%3C/text%3E%3C/svg%3E'; this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Gambar gagal dimuat.</strong> Silakan refresh halaman atau hubungi panitia jika masalah berlanjut.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button wire:click="hapusBukti" onclick="return confirm('Yakin ingin menghapus bukti transfer?')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus & Upload Ulang
                    </button>
                <?php else: ?>
                    <!-- Form Upload (Muncul hanya jika survey sudah diisi atau survey tidak aktif) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($surveyCompleted): ?>
                        <?php echo $__env->make('livewire.pendaftar.tes-psikologi-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php else: ?>
                        <!-- Pesan form disabled -->
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Form Upload Terkunci</h3>
                            <p class="text-sm text-gray-600 max-w-md mx-auto">Silakan lengkapi survey terlebih dahulu pada bagian <strong>"Informasi Survey"</strong> di atas untuk membuka form upload bukti transfer.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto hide notifications and handle survey saved event
    document.addEventListener('livewire:init', () => {
        Livewire.on('notification', (event) => {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });

        // Handle survey saved - scroll to upload section
        Livewire.on('survey-saved', (event) => {
            setTimeout(() => {
                // Smooth scroll to upload section
                const uploadSection = document.querySelector('.bg-purple-600')?.closest('.bg-white');
                if (uploadSection) {
                    uploadSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Add highlight effect
                    uploadSection.classList.add('ring-4', 'ring-purple-300');
                    setTimeout(() => {
                        uploadSection.classList.remove('ring-4', 'ring-purple-300');
                    }, 2000);
                }
            }, 500);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views/livewire/pendaftar/tes-psikologi.blade.php ENDPATH**/ ?>