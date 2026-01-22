<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - Maintenance Mode</title>
    {{-- Using compiled CSS for production-ready error pages --}}
    <link rel="stylesheet" href="{{ asset('css/errors.css') }}">
</head>
<body class="antialiased bg-gradient-to-br from-yellow-50 to-amber-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8 md:p-12">
                <!-- Icon & Message -->
                <div class="text-center mb-8">
                    <div class="maintenance-icon inline-flex items-center justify-center w-24 h-24 bg-yellow-100 rounded-full mb-6">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                        Sedang Maintenance
                    </h1>
                    <p class="text-gray-600 text-lg max-w-md mx-auto mb-6">
                        Kami sedang melakukan peningkatan sistem untuk memberikan pengalaman yang lebih baik.
                    </p>

                    @if (isset($exception) && $exception->getMessage())
                        <div class="inline-block bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                            <p class="text-sm text-yellow-800">
                                {{ $exception->getMessage() }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Info Box -->
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-6 mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-center">
                        Kami sedang melakukan perawatan sistem untuk memberikan pengalaman terbaik bagi Anda.
                    </p>
                </div>

                <!-- Estimated Time (Optional) -->
                <div class="text-center mb-8">
                    <p class="text-sm text-gray-500">
                        Silakan coba kembali beberapa saat lagi
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-center">
                    <button onclick="location.reload()"
                            class="inline-flex items-center justify-center px-8 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Coba Lagi
                    </button>
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
