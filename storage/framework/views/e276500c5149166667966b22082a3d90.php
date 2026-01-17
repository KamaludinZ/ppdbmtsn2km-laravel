<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
</head>
<body class="antialiased bg-gradient-to-br from-purple-50 to-pink-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Icon & Error Code -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-purple-100 rounded-full mb-6">
                        <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h1 class="text-7xl font-bold text-gray-800 mb-4">500</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold text-gray-700 mb-4">
                        Server Error
                    </h2>
                    <p class="text-gray-600 text-lg max-w-md mx-auto">
                        Maaf, terjadi kesalahan pada server kami. Tim teknis kami telah diberitahu dan sedang memperbaikinya.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-purple-50 rounded-xl p-6 mb-8">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Apa yang bisa Anda lakukan?
                    </h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Tunggu beberapa saat dan coba kembali</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Refresh halaman ini</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Kembali ke halaman sebelumnya</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-purple-600 mr-2">•</span>
                            <span>Hubungi administrator jika masalah berlanjut</span>
                        </li>
                    </ul>
                </div>

                <!-- Error Details (Only in Development) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(app()->environment('local') && isset($exception)): ?>
                    <div class="bg-gray-100 rounded-lg p-4 mb-8 text-left overflow-auto">
                        <p class="text-xs font-mono text-gray-600 mb-2">
                            <strong>File:</strong> <?php echo e($exception->getFile()); ?>:<?php echo e($exception->getLine()); ?>

                        </p>
                        <p class="text-xs font-mono text-red-600">
                            <strong>Error:</strong> <?php echo e($exception->getMessage()); ?>

                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="location.reload()"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Halaman
                    </button>
                    <a href="/"
                       class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Halaman Utama
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center text-sm text-gray-600">
                &copy; <?php echo e(date('Y')); ?> MTsN 2 Kota Malang. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\errors\500.blade.php ENDPATH**/ ?>