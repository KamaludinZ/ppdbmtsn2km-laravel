<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Keamanan Sistem
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Pengaturan keamanan dan aktivitas login
                </p>
            </div>
            
            <div class="px-4 py-5 sm:p-6">
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Two Factor Authentication -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Autentikasi Dua Faktor</h4>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Aktifkan Autentikasi Dua Faktor</p>
                                    <p class="text-sm text-gray-500">Tambahkan lapisan keamanan ekstra pada akun Anda</p>
                                </div>
                                <button
                                    wire:click="toggleTwoFactor"
                                    class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 <?php echo e($twoFactorEnabled ? 'bg-emerald-600' : 'bg-gray-200'); ?>"
                                    role="switch"
                                >
                                    <span
                                        aria-hidden="true"
                                        class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200 <?php echo e($twoFactorEnabled ? 'translate-x-5' : 'translate-x-0'); ?>"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <!-- Recent Login Attempts -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Aktivitas Login Terakhir</h4>
                                <button
                                    wire:click="refreshSecurityData"
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Refresh
                                </button>
                            </div>

                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $loginAttempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm font-medium text-emerald-600 truncate"><?php echo e($attempt['ip']); ?></div>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            <?php echo e($attempt['status'] === 'success' ? 'bg-green-100 text-green-800' :
                                                               ($attempt['status'] === 'failed' ? 'bg-red-100 text-red-800' :
                                                               'bg-yellow-100 text-yellow-800')); ?>">
                                                            <?php echo e(ucfirst($attempt['status'])); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <div class="mr-6 flex items-center text-sm text-gray-500">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                            </svg>
                                                            <?php echo e($attempt['location']); ?>

                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            <?php echo e($attempt['time']); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-4 sm:px-6 text-center text-gray-500">
                                                Tidak ada aktivitas login terakhir
                                            </div>
                                        </li>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Security Logs -->
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-4">Log Keamanan</h4>

                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $securityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="text-sm font-medium text-emerald-600 truncate"><?php echo e($log['action']); ?></div>
                                                    <div class="text-sm text-gray-500"><?php echo e($log['user']); ?></div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <div class="mr-6 flex items-center text-sm text-gray-500">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                            </svg>
                                                            <?php echo e($log['ip']); ?>

                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            <?php echo e($log['time']); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-4 sm:px-6 text-center text-gray-500">
                                                Tidak ada log keamanan
                                            </div>
                                        </li>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Suspicious Activities / Deteksi Serangan -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">🚨 Aktivitas Mencurigakan & Deteksi Serangan</h4>
                                    <p class="text-sm text-gray-500 mt-1">Monitoring real-time terhadap potensi serangan hacker</p>
                                </div>
                            </div>

                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $suspiciousActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li class="hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            <?php echo e($activity['severity'] === 'critical' ? 'bg-red-600 text-white' :
                                                               ($activity['severity'] === 'high' ? 'bg-red-100 text-red-800' :
                                                               ($activity['severity'] === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                                               'bg-blue-100 text-blue-800'))); ?>">
                                                            <?php echo e(strtoupper($activity['severity'])); ?>

                                                        </span>
                                                        <div class="text-sm font-bold text-gray-900"><?php echo e($activity['type']); ?></div>
                                                    </div>
                                                    <button
                                                        wire:click="clearSuspiciousActivity(<?php echo e($index); ?>)"
                                                        class="text-sm text-red-600 hover:text-red-800"
                                                    >
                                                        Clear
                                                    </button>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700 bg-gray-50 p-3 rounded">
                                                    <strong>Detail:</strong> <?php echo e($activity['details']); ?>

                                                </div>
                                                <div class="mt-3 grid grid-cols-2 gap-4 text-sm text-gray-500">
                                                    <div class="flex items-center">
                                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="font-mono"><?php echo e($activity['ip']); ?></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                        <?php echo e($activity['time']); ?>

                                                    </div>
                                                    <div class="col-span-2 text-xs">
                                                        <strong>URL:</strong> <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?php echo e($activity['url']); ?></span>
                                                    </div>
                                                    <div class="col-span-2 text-xs">
                                                        <strong>User Agent:</strong> <span class="font-mono bg-gray-100 px-2 py-1 rounded"><?php echo e($activity['user_agent']); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-8 sm:px-6 text-center">
                                                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">Tidak ada aktivitas mencurigakan terdeteksi</p>
                                            </div>
                                        </li>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Blocked IPs -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h4 class="text-md font-medium text-gray-900">🚫 IP yang Diblokir</h4>
                                    <p class="text-sm text-gray-500 mt-1">Daftar IP address yang diblokir oleh sistem</p>
                                </div>
                            </div>

                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $blockedIps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blocked): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <li class="hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            BLOCKED
                                                        </span>
                                                        <div class="text-sm font-bold font-mono text-gray-900"><?php echo e($blocked['ip']); ?></div>
                                                    </div>
                                                    <button
                                                        wire:click="unblockIp('<?php echo e($blocked['ip']); ?>')"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700"
                                                    >
                                                        Unblock
                                                    </button>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700">
                                                    <strong>Alasan:</strong> <?php echo e($blocked['reason']); ?>

                                                </div>
                                                <div class="mt-3 grid grid-cols-3 gap-4 text-xs text-gray-500">
                                                    <div>
                                                        <strong>Diblokir:</strong> <?php echo e($blocked['blocked_at']); ?>

                                                    </div>
                                                    <div>
                                                        <strong>Percobaan:</strong> <?php echo e($blocked['attempts']); ?>x
                                                    </div>
                                                    <div>
                                                        <strong>Auto Unblock:</strong> <?php echo e($blocked['auto_unblock']); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <li>
                                            <div class="px-4 py-8 sm:px-6 text-center">
                                                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">Tidak ada IP yang diblokir</p>
                                            </div>
                                        </li>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\livewire\admin\security.blade.php ENDPATH**/ ?>