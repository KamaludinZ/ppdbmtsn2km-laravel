<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Sesi Berakhir</title>
    {{-- Using compiled CSS for production-ready error pages --}}
    <link rel="stylesheet" href="{{ asset('css/errors.css') }}">
</head>
<body class="antialiased bg-gradient-to-br from-indigo-50 to-purple-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Icon & Error Code -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-100 rounded-full mb-6">
                        <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-7xl font-bold text-gray-800 mb-4">419</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold text-gray-700 mb-4">
                        Sesi Anda Telah Berakhir
                    </h2>
                    <p class="text-gray-600 text-lg max-w-md mx-auto">
                        Halaman Anda sudah kadaluarsa karena tidak aktif dalam waktu yang lama atau ada masalah dengan token keamanan.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-lg p-6 mb-8">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-indigo-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">Mengapa ini terjadi?</h3>
                            <ul class="space-y-1 text-gray-700 text-sm">
                                <li>• Halaman sudah terlalu lama terbuka tanpa aktivitas</li>
                                <li>• Token keamanan CSRF tidak valid atau telah berakhir</li>
                                <li>• Untuk keamanan akun Anda, sesi secara otomatis diakhiri</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Solution Steps -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 mb-8">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Solusi:
                    </h3>
                    <ol class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-200 text-indigo-800 rounded-full text-sm font-bold mr-3 flex-shrink-0">1</span>
                            <span>Refresh halaman ini untuk mendapatkan token keamanan yang baru</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-200 text-indigo-800 rounded-full text-sm font-bold mr-3 flex-shrink-0">2</span>
                            <span>Coba kirim ulang form atau permintaan Anda</span>
                        </li>
                        <li class="flex items-start">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-200 text-indigo-800 rounded-full text-sm font-bold mr-3 flex-shrink-0">3</span>
                            <span>Jika masalah berlanjut, hubungi administrator</span>
                        </li>
                    </ol>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="location.reload()"
                            class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Halaman
                    </button>
                    <a href="/"
                       class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Halaman Utama
                    </a>
                </div>

                <!-- Security Note -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 text-center">
                        <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Fitur keamanan ini melindungi akun Anda dari akses tidak sah
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center text-sm text-gray-600">
                @php
                    $appAcronym = \App\Models\Setting::get('app_acronym', 'PPDB');
                @endphp
                &copy; {{ date('Y') }} {{ $appAcronym }} MTsN 2 Kota Malang. Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</body>
</html>
