<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perawatan Sistem - <?php echo e($appAcronym); ?> MTsN 2 Kota Malang</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #f0fdf4 100%);
        }
        .maintenance-icon {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 text-center">
            <div class="maintenance-icon mb-6">
                <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Sistem Sedang Dalam Perawatan</h1>
            <p class="text-lg text-gray-600 mb-8">
                Kami sedang melakukan perawatan sistem <?php echo e($appAcronym); ?> untuk memberikan pengalaman terbaik bagi Anda.
                Silakan kembali beberapa saat lagi.
            </p>
            
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Perkiraan Waktu Pemulihan</h2>
                <div id="countdown" class="flex justify-center space-x-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600" id="days">00</div>
                        <div class="text-sm text-gray-500">Hari</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600" id="hours">00</div>
                        <div class="text-sm text-gray-500">Jam</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600" id="minutes">00</div>
                        <div class="text-sm text-gray-500">Menit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-emerald-600" id="seconds">00</div>
                        <div class="text-sm text-gray-500">Detik</div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-gray-700">
                    <strong>Informasi lebih lanjut:</strong>
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="tel:+62341551024" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        (0341) 551024
                    </a>
                    <a href="mailto:info@mtsn2kotamalang.sch.id" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        info@mtsn2kotamalang.sch.id
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-8 py-4 border-t">
            <p class="text-center text-gray-600 text-sm">
                &copy; <?php echo e(date('Y')); ?> <?php echo e($appAcronym); ?> MTsN 2 Kota Malang. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>

    <script>
        // Set maintenance end time (you can modify this)
        // For now, setting it to 2 hours from now as an example
        const maintenanceEndTime = new Date();
        maintenanceEndTime.setHours(maintenanceEndTime.getHours() + 2); // 2 hours from now
        
        // Update countdown every second
        const countdownInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = maintenanceEndTime - now;
            
            // Calculate days, hours, minutes, seconds
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Display the results
            document.getElementById('days').innerHTML = days.toString().padStart(2, '0');
            document.getElementById('hours').innerHTML = hours.toString().padStart(2, '0');
            document.getElementById('minutes').innerHTML = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').innerHTML = seconds.toString().padStart(2, '0');
            
            // If the countdown is finished, display a message
            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('countdown').innerHTML = '<div class="text-center"><p class="text-xl text-emerald-600 font-semibold">Sistem telah kembali aktif!</p><p class="text-gray-600 mt-2">Silakan refresh halaman untuk melanjutkan.</p></div>';
            }
        }, 1000);
    </script>
</body>
</html><?php /**PATH C:\ppdbmtsn2km-laravel\resources\views\maintenance.blade.php ENDPATH**/ ?>