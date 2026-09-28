<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Left Side - Branding & Info (Hidden on mobile, visible on desktop) -->
                <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 p-12 flex-col justify-between relative overflow-hidden">
                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-800 opacity-20 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-900 opacity-20 rounded-full -ml-48 -mb-48"></div>

                    <div class="relative z-10">
                        <!-- Logo & Title -->
                        <div class="flex items-start mb-8">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0">
                                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h1 class="text-3xl font-bold text-white">Admin & Panitia</h1>
                                <p class="text-white mt-2 text-lg opacity-90">MTsN 2 Kota Malang</p>
                                <p class="text-white text-sm opacity-80">Tahun Pelajaran {{ \App\Models\Setting::get('tahun_pelajaran', '2025/2026') }}</p>
                            </div>
                        </div>

                        <!-- Info Cards -->
                        <div class="space-y-4 mb-8">
                            <div class="bg-blue-700/40 rounded-xl p-4 border border-blue-400/30 ">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-white mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <div class="text-white">
                                        <p class="font-semibold text-white">Portal Khusus Admin & Panitia</p>
                                        <p class="text-sm text-white mt-1 opacity-90">Akses penuh untuk mengelola sistem {{ $appAcronym }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-700/40 rounded-xl p-4 border border-blue-400/30 ">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-white mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-white">
                                        <p class="font-semibold text-white">Bukan Admin/Panitia?</p>
                                        <p class="text-sm text-white mt-1 opacity-90">Pendaftar silakan login di <a href="/login" class="underline font-medium hover:opacity-100">halaman pendaftar</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-3 mb-8">
                            <p class="text-white font-semibold mb-3">Fitur Admin & Panitia:</p>
                            <div class="flex items-center text-white">
                                <svg class="w-5 h-5 mr-3 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm opacity-90">Manajemen Data Pendaftar</span>
                            </div>
                            <div class="flex items-center text-white">
                                <svg class="w-5 h-5 mr-3 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm opacity-90">Verifikasi & Validasi Berkas</span>
                            </div>
                            <div class="flex items-center text-white">
                                <svg class="w-5 h-5 mr-3 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm opacity-90">Laporan & Analisis Data</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Security Info -->
                    <div class="relative z-10 mt-auto">
                        <div class="bg-blue-700/40 rounded-xl p-4 border border-blue-400/30 ">
                            <div class="flex items-center text-white text-sm">
                                <svg class="w-5 h-5 mr-2 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="opacity-90">Login dengan akun resmi admin/panitia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="w-full lg:w-1/2 p-8 lg:p-12">
                    <!-- Mobile Logo & Title (Visible on mobile only) -->
                    <div class="lg:hidden mb-8 text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Login Admin/Panitia</h2>
                        <p class="text-gray-600 mt-1">{{ $appAcronym }} MTsN 2 Kota Malang</p>
                    </div>

                    <form wire:submit="login" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="email"
                                    wire:model="email"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                    placeholder="admin@mtsn2kotamalang.sch.id"
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="password"
                                    wire:model="password"
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                    placeholder="••••••••"
                                >
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password')">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password-toggle-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Captcha -->
                        <div>
                            <label for="captcha" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode Keamanan
                            </label>
                            <div class="flex gap-3 items-center mb-3">
                                <div class="flex-1 bg-white rounded-lg border-2 border-blue-300 overflow-hidden">
                                    <img src="{{ \App\Support\Captcha::url('admin_login_captcha', $captchaVersion) }}" alt="Kode keamanan: 6 angka pada gambar" width="200" height="60" class="block w-full h-14 object-contain select-none" draggable="false">
                                </div>
                                <button
                                    type="button"
                                    wire:click="refreshCaptcha"
                                    class="p-3 bg-blue-100 hover:bg-blue-200 rounded-lg transition"
                                    title="Ganti gambar kode" aria-label="Ganti gambar kode keamanan"
                                >
                                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <input
                                type="text"
                                id="captcha"
                                wire:model="captcha"
                                maxlength="6"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('captcha') border-red-500 @enderror"
                                placeholder="Masukkan 6 angka pada gambar"
                                inputmode="numeric"
                                autocomplete="off"
                            >
                            @error('captcha')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    wire:model="remember"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>Masuk</span>
                                <span wire:loading>
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to Home Link -->
                    <div class="mt-8 text-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(passwordFieldId) {
            const passwordField = document.getElementById(passwordFieldId);
            const icon = document.getElementById(passwordFieldId + '-toggle-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                // Change to eye slash icon
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordField.type = 'password';
                // Change back to eye icon
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
    </script>
</div>
