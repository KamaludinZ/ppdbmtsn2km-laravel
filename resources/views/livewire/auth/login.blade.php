<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Left Side - Branding & Info (Hidden on mobile, visible on desktop) -->
                <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 to-emerald-700 p-12 flex-col justify-between relative overflow-hidden">
                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-800 opacity-10 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-800 opacity-10 rounded-full -ml-48 -mb-48"></div>

                    <div class="relative z-10">
                        <!-- Logo & Title -->
                        <div class="flex items-start mb-8">
                            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0">
                                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h1 class="text-3xl font-bold text-white">Login Pendaftar</h1>
                                <p class="text-emerald-100 mt-2 text-lg">MTsN 2 Kota Malang</p>
                                <p class="text-emerald-200 text-sm">Tahun Pelajaran {{ \App\Models\Setting::get('tahun_pelajaran', '2025/2026') }}</p>
                            </div>
                        </div>

                        <!-- Info Cards -->
                        <div class="space-y-4 mb-8">
                            <div class="bg-emerald-700 bg-opacity-40 rounded-xl p-4 border border-emerald-400 border-opacity-30">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-white mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-white">
                                        <p class="font-semibold text-white">Halaman Khusus Pendaftar</p>
                                        <p class="text-sm text-white mt-1 opacity-90">Admin/Panitia silakan login di <a href="/login/admin" class="underline font-medium hover:opacity-100">halaman admin</a></p>
                                    </div>
                                </div>
                            </div>

                            @if(!$loginEnabled)
                            <div class="bg-yellow-600 bg-opacity-30 rounded-xl p-4 border border-yellow-400 border-opacity-40">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-yellow-200 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div class="text-white">
                                        <p class="font-semibold text-white">Login Dinonaktifkan</p>
                                        <p class="text-sm text-white mt-1 opacity-90">Login hanya aktif saat pendaftaran dibuka</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bottom Info -->
                    <div class="relative z-10 mt-auto">
                        <div class="bg-emerald-700 bg-opacity-40 rounded-xl p-5 border border-emerald-400 border-opacity-30">
                            <div class="flex items-start mb-3">
                                <svg class="w-6 h-6 text-white mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <div>
                                    <p class="text-base font-semibold text-white mb-1">Belum punya akun?</p>
                                    <p class="text-sm text-white opacity-90 mb-3">Daftar sekarang dan mulai proses pendaftaran {{ $appAcronym }}</p>
                                    <a href="/daftar" class="inline-flex items-center px-4 py-2 bg-white text-emerald-700 rounded-lg font-semibold text-sm hover:bg-emerald-50 transition-all duration-200 hover:scale-105 shadow-md">
                                        <span>Daftar Sekarang</span>
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="w-full lg:w-1/2 p-8 lg:p-12">
                    <!-- Mobile Logo & Title (Visible on mobile only) -->
                    <div class="lg:hidden mb-8 text-center">
                        <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mx-auto mb-4">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Login Pendaftar</h2>
                        <p class="text-gray-600 mt-1">{{ $appAcronym }} MTsN 2 Kota Malang</p>
                    </div>

                    @if(!$loginEnabled)
                        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg lg:hidden">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold mb-1">Login Dinonaktifkan</p>
                                    <p>Login hanya aktif saat pendaftaran dibuka</p>
                                </div>
                            </div>
                        </div>
                    @endif

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
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror @if(!$loginEnabled) bg-gray-100 text-gray-500 cursor-not-allowed @endif"
                                    placeholder="nama@email.com"
                                    @if($loginEnabled) autofocus @endif
                                    @if(!$loginEnabled) disabled @endif
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
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror @if(!$loginEnabled) bg-gray-100 text-gray-500 cursor-not-allowed @endif"
                                    placeholder="••••••••"
                                    @if(!$loginEnabled) disabled @endif
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
                                <div class="flex-1 bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-3 border-2 border-emerald-300">
                                    <p class="text-2xl font-bold text-center text-emerald-800 tracking-wider select-none" style="font-family: 'Courier New', monospace; letter-spacing: 8px;">
                                        {{ $captchaCode }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="refreshCaptcha"
                                    class="p-3 bg-emerald-100 hover:bg-emerald-200 rounded-lg transition @if(!$loginEnabled) bg-gray-100 cursor-not-allowed hover:bg-gray-100 @endif"
                                    title="Refresh Captcha"
                                    @if(!$loginEnabled) disabled @endif
                                >
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <input
                                type="text"
                                id="captcha"
                                wire:model="captcha"
                                maxlength="6"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('captcha') border-red-500 @enderror @if(!$loginEnabled) bg-gray-100 text-gray-500 cursor-not-allowed @endif"
                                placeholder="Masukkan kode di atas"
                                @if(!$loginEnabled) disabled @endif
                            >
                            @error('captcha')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input
                                    id="remember"
                                    type="checkbox"
                                    wire:model="remember"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded @if(!$loginEnabled) cursor-not-allowed @endif"
                                    @if(!$loginEnabled) disabled @endif
                                >
                                <label for="remember" class="ml-2 block text-sm text-gray-700 @if(!$loginEnabled) text-gray-400 @endif">
                                    Ingat saya
                                </label>
                            </div>
                            <div class="text-sm">
                                <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500 @if(!$loginEnabled) text-gray-400 cursor-not-allowed @endif" onclick="showForgotPasswordNotification(); return false;">
                                    Lupa password?
                                </a>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 @if(!$loginEnabled) bg-gray-400 cursor-not-allowed hover:bg-gray-400 @endif"
                                wire:loading.attr="disabled"
                                @if(!$loginEnabled) disabled @endif
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

                    <!-- Divider (Mobile only) -->
                    <div class="mt-8 lg:hidden">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">atau</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-xl p-6 border-2 border-emerald-200">
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-emerald-900 mb-3">Belum punya akun?</p>
                                    <p class="text-xs text-emerald-700 mb-4">Daftar sekarang untuk memulai proses pendaftaran {{ $appAcronym }}</p>
                                    <a
                                        href="/daftar"
                                        class="inline-flex items-center justify-center px-6 py-3 border-2 border-emerald-600 rounded-lg shadow-sm text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 hover:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 hover:scale-105"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        Daftar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back to Home Link -->
                    <div class="mt-8 text-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-emerald-600 transition">
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
        function showForgotPasswordNotification() {
            alert('Silahkan hubungi panitia untuk bantuan terkait lupa password.');
        }

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
