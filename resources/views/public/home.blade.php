<x-layouts.public title="Beranda - {{ $appAcronym }} MTsN 2 Kota Malang">
    @push('styles')
    <style>
        /* Hero Section Animations */
        @-webkit-keyframes float {
            0%, 100% { -webkit-transform: translateY(0px) rotate(0deg); transform: translateY(0px) rotate(0deg); }
            50% { -webkit-transform: translateY(-20px) rotate(5deg); transform: translateY(-20px) rotate(5deg); }
        }
        @keyframes float {
            0%, 100% { -webkit-transform: translateY(0px) rotate(0deg); transform: translateY(0px) rotate(0deg); }
            50% { -webkit-transform: translateY(-20px) rotate(5deg); transform: translateY(-20px) rotate(5deg); }
        }
        
        @-webkit-keyframes float-delayed {
            0%, 100% { -webkit-transform: translateY(0px) rotate(0deg); transform: translateY(0px) rotate(0deg); }
            50% { -webkit-transform: translateY(-15px) rotate(-5deg); transform: translateY(-15px) rotate(-5deg); }
        }
        @keyframes float-delayed {
            0%, 100% { -webkit-transform: translateY(0px) rotate(0deg); transform: translateY(0px) rotate(0deg); }
            50% { -webkit-transform: translateY(-15px) rotate(-5deg); transform: translateY(-15px) rotate(-5deg); }
        }
        
        @-webkit-keyframes pulse-glow {
            0%, 100% { opacity: 0.3; -webkit-transform: scale(1); transform: scale(1); }
            50% { opacity: 0.5; -webkit-transform: scale(1.1); transform: scale(1.1); }
        }
        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; -webkit-transform: scale(1); transform: scale(1); }
            50% { opacity: 0.5; -webkit-transform: scale(1.1); transform: scale(1.1); }
        }
        
        @-webkit-keyframes drift {
            0% { -webkit-transform: translateX(0px) translateY(0px); transform: translateX(0px) translateY(0px); }
            25% { -webkit-transform: translateX(10px) translateY(-10px); transform: translateX(10px) translateY(-10px); }
            50% { -webkit-transform: translateX(0px) translateY(-20px); transform: translateX(0px) translateY(-20px); }
            75% { -webkit-transform: translateX(-10px) translateY(-10px); transform: translateX(-10px) translateY(-10px); }
            100% { -webkit-transform: translateX(0px) translateY(0px); transform: translateX(0px) translateY(0px); }
        }
        @keyframes drift {
            0% { -webkit-transform: translateX(0px) translateY(0px); transform: translateX(0px) translateY(0px); }
            25% { -webkit-transform: translateX(10px) translateY(-10px); transform: translateX(10px) translateY(-10px); }
            50% { -webkit-transform: translateX(0px) translateY(-20px); transform: translateX(0px) translateY(-20px); }
            75% { -webkit-transform: translateX(-10px) translateY(-10px); transform: translateX(-10px) translateY(-10px); }
            100% { -webkit-transform: translateX(0px) translateY(0px); transform: translateX(0px) translateY(0px); }
        }
        
        @-webkit-keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @-webkit-keyframes wave {
            0% { -webkit-transform: translateX(0); transform: translateX(0); }
            50% { -webkit-transform: translateX(-25%); transform: translateX(-25%); }
            100% { -webkit-transform: translateX(0); transform: translateX(0); }
        }
        @keyframes wave {
            0% { -webkit-transform: translateX(0); transform: translateX(0); }
            50% { -webkit-transform: translateX(-25%); transform: translateX(-25%); }
            100% { -webkit-transform: translateX(0); transform: translateX(0); }
        }
        
        @-webkit-keyframes swell {
            0%, 100% { -webkit-transform: scale(1); transform: scale(1); }
            50% { -webkit-transform: scale(1.1); transform: scale(1.1); }
        }
        @keyframes swell {
            0%, 100% { -webkit-transform: scale(1); transform: scale(1); }
            50% { -webkit-transform: scale(1.1); transform: scale(1.1); }
        }
        
        @-webkit-keyframes fadeInUp {
            from { opacity: 0; -webkit-transform: translateY(30px); transform: translateY(30px); }
            to { opacity: 1; -webkit-transform: translateY(0); transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; -webkit-transform: translateY(30px); transform: translateY(30px); }
            to { opacity: 1; -webkit-transform: translateY(0); transform: translateY(0); }
        }
        
        .hero-shape-1 { -webkit-animation: float 6s ease-in-out infinite; animation: float 6s ease-in-out infinite; left: 10%; top: 20%; }
        .hero-shape-2 { -webkit-animation: float-delayed 8s ease-in-out infinite; animation: float-delayed 8s ease-in-out infinite; left: 70%; top: 15%; }
        .hero-shape-3 { -webkit-animation: float 7s ease-in-out infinite; animation: float 7s ease-in-out infinite; left: 80%; top: 60%; }
        .hero-shape-4 { -webkit-animation: pulse-glow 4s ease-in-out infinite; animation: pulse-glow 4s ease-in-out infinite; left: 20%; top: 70%; }
        .hero-shape-5 { -webkit-animation: drift 10s ease-in-out infinite; animation: drift 10s ease-in-out infinite; left: 60%; top: 30%; }
        .hero-shape-6 { -webkit-animation: float-delayed 9s ease-in-out infinite; animation: float-delayed 9s ease-in-out infinite; left: 40%; top: 10%; }
        .hero-shape-7 { -webkit-animation: float 8s ease-in-out infinite; animation: float 8s ease-in-out infinite; left: 25%; top: 35%; }
        .hero-shape-8 { -webkit-animation: drift 12s ease-in-out infinite; animation: drift 12s ease-in-out infinite; left: 85%; top: 45%; }
        .hero-shape-9 { -webkit-animation: float-delayed 10s ease-in-out infinite; animation: float-delayed 10s ease-in-out infinite; left: 15%; top: 55%; }
        .hero-shape-10 { -webkit-animation: pulse-glow 5s ease-in-out infinite; animation: pulse-glow 5s ease-in-out infinite; left: 50%; top: 25%; }
        .hero-shape-11 { -webkit-animation: float 9s ease-in-out infinite; animation: float 9s ease-in-out infinite; left: 75%; top: 80%; }
        
        .hero-dot-1 { -webkit-animation: pulse-glow 3s ease-in-out infinite; animation: pulse-glow 3s ease-in-out infinite; left: 15%; top: 40%; }
        .hero-dot-2 { -webkit-animation: pulse-glow 4s ease-in-out infinite 0.5s; animation: pulse-glow 4s ease-in-out infinite 0.5s; left: 85%; top: 35%; }
        .hero-dot-3 { -webkit-animation: pulse-glow 5s ease-in-out infinite 1s; animation: pulse-glow 5s ease-in-out infinite 1s; left: 30%; top: 80%; }
        .hero-dot-4 { -webkit-animation: pulse-glow 3.5s ease-in-out infinite 1.5s; animation: pulse-glow 3.5s ease-in-out infinite 1.5s; left: 55%; top: 50%; }
        .hero-dot-5 { -webkit-animation: pulse-glow 4.5s ease-in-out infinite 2s; animation: pulse-glow 4.5s ease-in-out infinite 2s; left: 75%; top: 75%; }
        
        .bg-gradient-animate {
            background: linear-gradient(-45deg, rgba(16, 185, 129, 0.3), rgba(6, 182, 212, 0.3), rgba(34, 197, 94, 0.3), rgba(16, 185, 129, 0.3));
            background-size: 400% 400%;
            -webkit-animation: gradient-shift 15s ease infinite;
            animation: gradient-shift 15s ease infinite;
        }
        
        /* Wave Animation */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 1;
            pointer-events: none;
        }
        
        .wave {
            position: relative;
            display: block;
            width: calc(300% + 1.3px);
            height: 100px;
            -webkit-animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            animation: wave 15s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }
        
        /* Smooth entrance animation */
        .hero-content {
            -webkit-animation: fadeInUp 0.8s ease-out;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .hero-content-delay-1 { -webkit-animation: fadeInUp 0.8s ease-out 0.2s both; animation: fadeInUp 0.8s ease-out 0.2s both; }
        .hero-content-delay-2 { -webkit-animation: fadeInUp 0.8s ease-out 0.4s both; animation: fadeInUp 0.8s ease-out 0.4s both; }
        .hero-content-delay-3 { -webkit-animation: fadeInUp 0.8s ease-out 0.6s both; animation: fadeInUp 0.8s ease-out 0.6s both; }
        
        /* Fallback untuk Tailwind blur classes */
        .blur-lg { filter: blur(16px); -webkit-filter: blur(16px); }
        .blur-xl { filter: blur(24px); -webkit-filter: blur(24px); }
        .blur-2xl { filter: blur(40px); -webkit-filter: blur(40px); }
    </style>
    @endpush

    <x-registration-phase-banner />

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 text-white overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 40% 20%, rgba(255,255,255,0.08) 0%, transparent 50%);"></div>
        </div>
        
        <!-- Floating Shapes Animation -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Circles -->
            <div class="hero-shape hero-shape-1 absolute w-20 h-20 rounded-full" style="background-color: rgba(255, 255, 255, 0.1); filter: blur(24px); -webkit-filter: blur(24px);"></div>
            <div class="hero-shape hero-shape-2 absolute w-16 h-16 rounded-full" style="background-color: rgba(52, 211, 153, 0.4); filter: blur(16px); -webkit-filter: blur(16px);"></div>
            <div class="hero-shape hero-shape-3 absolute w-24 h-24 rounded-full" style="background-color: rgba(253, 224, 71, 0.4); filter: blur(24px); -webkit-filter: blur(24px);"></div>
            <!-- Stars -->
            <div class="hero-shape hero-shape-4 absolute w-12 h-12 opacity-10">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full" style="color: rgb(255, 255, 255);">
                    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                </svg>
            </div>
            <div class="hero-shape hero-shape-7 absolute w-10 h-10 opacity-10">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full" style="color: rgb(167, 243, 208);">
                    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                </svg>
            </div>
            <div class="hero-shape hero-shape-10 absolute w-14 h-14 opacity-10">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full" style="color: rgb(254, 240, 138);">
                    <path d="M12 0L14.59 9.41L24 12L14.59 14.59L12 24L9.41 14.59L0 12L9.41 9.41L12 0Z"/>
                </svg>
            </div>
            <!-- Book Icons -->
            <div class="hero-shape hero-shape-5 absolute w-10 h-10 opacity-10">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full" style="color: rgb(255, 255, 255);">
                    <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <div class="hero-shape hero-shape-8 absolute w-12 h-12 opacity-10">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full" style="color: rgb(167, 243, 208);">
                    <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <!-- Graduation Cap Icons -->
            <div class="hero-shape hero-shape-6 absolute w-14 h-14 opacity-10">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full" style="color: rgb(255, 255, 255);">
                    <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
            </div>
            <div class="hero-shape hero-shape-9 absolute w-12 h-12 opacity-10">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full" style="color: rgb(254, 240, 138);">
                    <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
            </div>
            <div class="hero-shape hero-shape-11 absolute w-10 h-10 opacity-10">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full" style="color: rgb(209, 250, 229);">
                    <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
            </div>
            <!-- Small Dots -->
            <div class="hero-dot hero-dot-1 absolute w-2 h-2 rounded-full" style="background-color: rgba(255, 255, 255, 0.3);"></div>
            <div class="hero-dot hero-dot-2 absolute w-1.5 h-1.5 rounded-full" style="background-color: rgba(167, 243, 208, 0.4);"></div>
            <div class="hero-dot hero-dot-3 absolute w-2 h-2 rounded-full" style="background-color: rgba(254, 240, 138, 0.3);"></div>
            <div class="hero-dot hero-dot-4 absolute w-1 h-1 rounded-full" style="background-color: rgba(255, 255, 255, 0.25);"></div>
            <div class="hero-dot hero-dot-5 absolute w-2 h-2 rounded-full" style="background-color: rgba(209, 250, 229, 0.35);"></div>
        </div>

        <!-- Gradient Animation Overlay -->
        <div class="absolute inset-0 bg-gradient-animate opacity-30"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24" style="z-index: 10;">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7">
                    <div class="inline-block bg-emerald-500/50 rounded-full px-4 py-2 mb-6 hero-content">
                        <p class="text-sm font-semibold">Tahun Pelajaran {{ \App\Models\Setting::get('tahun_pelajaran', '2025/2026') }}</p>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight hero-content-delay-1">
                        {{ $appFullName }}
                    </h1>
                    <p class="text-xl md:text-2xl mb-4 text-emerald-100 hero-content-delay-1">
                        MTsN 2 Kota Malang
                    </p>
                    <p class="text-base md:text-lg mb-8 text-emerald-50 leading-relaxed hero-content-delay-2">
                        Bergabunglah dengan kami untuk mendapatkan pendidikan Islam yang berkualitas dan berprestasi
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 hero-content-delay-3">
                        @if($registrationPhase === 'dibuka')
                        <a href="/daftar" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-medium rounded-lg text-emerald-600 bg-white hover:bg-gray-50 hover:scale-105 transition-all duration-200 shadow-lg">
                            Daftar Sekarang
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        @else
                        <button disabled class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-medium rounded-lg text-gray-400 bg-gray-100 cursor-not-allowed shadow-inner">
                            Pendaftaran Belum Dibuka
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </button>
                        @endif
                        <a href="/informasi" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-emerald-700 transition-all duration-200">
                            Info Lengkap
                        </a>
                        @if($brosurActive && $brosurFilePath)
                        <a href="{{ Storage::url($brosurFilePath) }}" target="_blank" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-white hover:text-emerald-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Brosur
                        </a>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-2xl border-2 border-emerald-200 hero-content-delay-3">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-emerald-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Login {{ $appAcronym }}</h3>
                                <p class="text-sm text-gray-600">Masuk untuk melanjutkan pendaftaran</p>
                            </div>
                        </div>

                        @if(!$loginEnabled)
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <div class="text-sm text-yellow-800">
                                    <p class="font-semibold mb-1">Login Pendaftar Sedang Dinonaktifkan</p>
                                    <p>Login hanya aktif ketika pendaftaran sedang dibuka.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Error Messages --}}
                        @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg animate-shake">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="font-semibold text-red-800 mb-1">Login Gagal</p>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <form action="/login" method="POST" class="space-y-5">
                        @csrf
                        <!-- Email -->
                        <div>
                            <label for="hero-email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $errors->has('email') ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="email"
                                    id="hero-email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="block w-full pl-10 pr-3 py-2.5 border rounded-lg focus:ring-2 text-sm text-gray-900 @if($errors->has('email')) border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @endif @if(!$loginEnabled) bg-gray-100 text-gray-500 cursor-not-allowed @endif"
                                    placeholder="email@example.com"
                                    @if($loginEnabled) required @endif
                                    @if(!$loginEnabled) disabled @endif
                                >
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="hero-password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 {{ $errors->has('password') ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    type="password"
                                    id="hero-password"
                                    name="password"
                                    class="block w-full pl-10 pr-10 py-2.5 border rounded-lg focus:ring-2 text-sm text-gray-900 @if($errors->has('password')) border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @endif @if(!$loginEnabled) bg-gray-100 text-gray-500 cursor-not-allowed @endif"
                                    placeholder="••••••••"
                                    @if($loginEnabled) required @endif
                                    @if(!$loginEnabled) disabled @endif
                                >
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('hero-password')">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="hero-password-toggle-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Captcha -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode Keamanan
                            </label>
                            <div class="flex gap-3 items-center mb-3">
                                <div class="flex-1 bg-white rounded-lg border-2 overflow-hidden {{ $errors->has('captcha') ? 'border-red-300' : 'border-gray-300' }}">
                                    <img id="home-login-captcha" src="{{ $loginCaptcha }}" alt="Kode keamanan: 6 angka pada gambar" width="200" height="60" class="block w-full h-14 object-contain select-none" draggable="false">
                                </div>
                                <button type="button" onclick="var i=document.getElementById('home-login-captcha'); i.src=i.src.split('?')[0]+'?v='+Date.now();" class="p-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition" title="Ganti gambar kode" aria-label="Ganti gambar kode keamanan">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </div>
                            <input
                                type="text"
                                name="captcha"
                                class="block w-full px-3 py-2.5 border rounded-lg focus:ring-2 text-sm text-gray-900 @if($errors->has('captcha')) border-red-500 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @endif"
                                placeholder="Masukkan 6 angka pada gambar"
                                inputmode="numeric"
                                autocomplete="off"
                                maxlength="6"
                                @if($loginEnabled) required @endif
                                @if(!$loginEnabled) disabled @endif
                            >
                            @error('captcha')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Ingat saya</span>
                            </label>
                            <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium" onclick="showForgotPasswordNotification(); return false;">Lupa password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-emerald-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 hover:scale-105 @if(!$loginEnabled) bg-gray-400 cursor-not-allowed hover:bg-gray-400 @endif"
                            @if(!$loginEnabled) disabled @endif
                        >
                            Masuk
                        </button>

                        <!-- Register Link -->
                        <p class="text-center text-sm text-gray-600">
                            Belum punya akun?
                            <a href="/daftar" class="text-emerald-600 hover:text-emerald-700 font-semibold">Daftar Sekarang</a>
                        </p>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="wave-container">
            <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path fill="#ffffff" fill-opacity="1" d="M0,50L48,55.3C96,61,192,71,288,66.7C384,63,480,43,576,37.3C672,32,768,40,864,45.3C960,51,1056,53,1152,48C1248,43,1344,32,1392,26.7L1440,21L1440,100L1392,100C1344,100,1248,100,1152,100C1056,100,960,100,864,100C768,100,672,100,576,100C480,100,384,100,288,100C192,100,96,100,48,100L0,100Z"></path>
            </svg>
        </div>
    </section>

    <!-- Jalur Pendaftaran -->
    <section id="jalur-pendaftaran" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Jalur Pendaftaran</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Pilih jalur pendaftaran yang sesuai dengan kualifikasi Anda
                </p>
            </div>

            @if($jalurPendaftaran->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($jalurPendaftaran as $index => $jalur)
                    @php
                        $colorClasses = [
                            'blue' => ['border' => 'border-blue-200 hover:border-blue-400', 'bg' => 'from-blue-500 to-blue-600', 'text' => 'text-blue-600', 'button' => 'bg-blue-600 hover:bg-blue-700'],
                            'emerald' => ['border' => 'border-emerald-200 hover:border-emerald-400', 'bg' => 'from-emerald-500 to-emerald-600', 'text' => 'text-emerald-600', 'button' => 'bg-emerald-600 hover:bg-emerald-700'],
                            'purple' => ['border' => 'border-purple-200 hover:border-purple-400', 'bg' => 'from-purple-500 to-purple-600', 'text' => 'text-purple-600', 'button' => 'bg-purple-600 hover:bg-purple-700'],
                            'indigo' => ['border' => 'border-indigo-200 hover:border-indigo-400', 'bg' => 'from-indigo-500 to-indigo-600', 'text' => 'text-indigo-600', 'button' => 'bg-indigo-600 hover:bg-indigo-700'],
                            'orange' => ['border' => 'border-orange-200 hover:border-orange-400', 'bg' => 'from-orange-500 to-orange-600', 'text' => 'text-orange-600', 'button' => 'bg-orange-600 hover:bg-orange-700'],
                            'teal' => ['border' => 'border-teal-200 hover:border-teal-400', 'bg' => 'from-teal-500 to-teal-600', 'text' => 'text-teal-600', 'button' => 'bg-teal-600 hover:bg-teal-700'],
                        ];
                        $colors = $colorClasses[$jalur->warna] ?? $colorClasses['blue'];
                        $isPopular = $jalur->paling_populer ?? false;
                    @endphp

                <!-- Jalur {{ $jalur->nama }} -->
                <div class="bg-white border-2 {{ $colors['border'] }} rounded-2xl p-8 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 {{ $isPopular ? 'ring-2 ring-emerald-100' : '' }} flex flex-col h-full">
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br {{ $colors['bg'] }} rounded-xl flex items-center justify-center mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        @if($isPopular)
                            <div class="absolute -top-2 -right-2 bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                PALING POPULER
                            </div>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $jalur->nama }}</h3>
                    <p class="text-gray-700 font-medium mb-6 flex-grow">
                        {{ $jalur->deskripsi }}
                    </p>

                    <!-- Nilai Minimum Badge -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Nilai Min: {{ number_format($jalur->nilai_minimum, 0) }}
                        </span>
                        @if($jalur->kuota > 0)
                            <span class="inline-block bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">
                                Kuota: {{ $jalur->kuota }} siswa
                            </span>
                        @endif
                    </div>

                    @if($jalur->persyaratan && is_array($jalur->persyaratan))
                    <ul class="space-y-3 mb-8">
                        @foreach(array_slice($jalur->persyaratan, 0, 3) as $syarat)
                        <li class="flex items-start">
                            <svg class="w-5 h-5 {{ $colors['text'] }} mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-800 font-medium text-sm">{{ $syarat }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <a href="/jalur-pendaftaran/{{ $jalur->kode }}" class="block w-full text-center mt-auto {{ $colors['button'] }} text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        Info Lengkap
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Jalur Pendaftaran Aktif</h3>
                <p class="text-gray-600">Jalur pendaftaran akan segera dibuka. Silakan cek kembali nanti.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Alur Pendaftaran di Aplikasi -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Alur Pendaftaran di Aplikasi</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Ikuti langkah-langkah berikut untuk menyelesaikan proses pendaftaran {{ $appAcronym }} secara online
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <!-- Step 1 -->
                <div class="relative flex flex-col h-full">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-emerald-100 hover:border-emerald-300 flex flex-col">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 mt-4 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pilih Jalur & Cek Kualifikasi</h3>
                        <p class="text-gray-600 leading-relaxed flex-grow">
                            Pilih jalur pendaftaran dan pastikan nilai rapor Anda memenuhi kualifikasi yang ditentukan
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex flex-col h-full">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-blue-100 hover:border-blue-300 flex flex-col">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 mt-4 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Buat Akun</h3>
                        <p class="text-gray-600 leading-relaxed flex-grow">
                            Klik tombol "Daftar Sekarang" dan isi formulir registrasi dengan email dan password yang valid
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-purple-100 hover:border-purple-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Login ke Sistem</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Masuk ke akun Anda menggunakan email dan password yang telah didaftarkan
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-orange-100 hover:border-orange-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">4</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Isi Formulir</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Lengkapi formulir pendaftaran dengan data diri, data orang tua, dan konfirmasi jalur pendaftaran
                        </p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-teal-100 hover:border-teal-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-teal-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">5</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Upload Dokumen</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Unggah semua dokumen persyaratan seperti ijazah, KK, akta kelahiran, rapor, dan dokumen prestasi (jika ada)
                        </p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-pink-100 hover:border-pink-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">6</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Verifikasi Berkas</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Panitia akan memverifikasi berkas Anda. Pantau status verifikasi melalui dashboard pendaftar
                        </p>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-indigo-100 hover:border-indigo-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">7</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Cetak Bukti Pendaftaran</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Setelah berkas diverifikasi dan diterima, cetak bukti pendaftaran sebagai tanda peserta seleksi
                        </p>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-yellow-100 hover:border-yellow-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">8</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Mengikuti Seleksi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Ikuti tes seleksi sesuai jalur yang dipilih (tes tertulis, tes tahfidz, atau seleksi prestasi) pada waktu yang ditentukan
                        </p>
                    </div>
                </div>

                <!-- Step 9 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-cyan-100 hover:border-cyan-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-cyan-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">9</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Cek Hasil Seleksi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Pantau pengumuman hasil seleksi melalui dashboard atau website. Hasil akan diumumkan sesuai jadwal yang ditentukan
                        </p>
                    </div>
                </div>

                <!-- Step 10 -->
                <div class="relative">
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 h-full border-2 border-green-100 hover:border-green-300">
                        <div class="absolute -top-4 -left-4 w-12 h-12 bg-green-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-xl">10</span>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-6 mt-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar Ulang</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Jika lulus seleksi, lakukan pembayaran daftar ulang, pengukuran seragam, dan lengkapi berkas di sekolah sesuai jadwal
                        </p>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-12 bg-gradient-to-r from-emerald-600 to-blue-600 rounded-2xl p-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold mb-2">Butuh Bantuan?</h3>
                        <p class="text-emerald-50">
                            Jika mengalami kendala dalam proses pendaftaran, silakan hubungi panitia melalui menu Kontak Panitia atau datang langsung ke sekolah
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="/kontak-panitia" class="inline-flex items-center px-6 py-3 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Hubungi Panitia
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cek Status Pendaftaran (tanpa login) -->
    <livewire:public.cek-pendaftaran :embedded="true" />

    <!-- Timeline Pendaftaran -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Timeline Pendaftaran</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Jadwal pendaftaran untuk setiap jalur {{ $appAcronym }} Tahun Pelajaran {{ \App\Models\Setting::get('tahun_pelajaran', '2025/2026') }}
                </p>
            </div>

            @if($timelinePendaftaran->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    // Kelompokkan timeline berdasarkan jalur
                    $timelineByJalur = $timelinePendaftaran->groupBy('jalur_kode');

                    // Ambil semua jalur dari database untuk mendapatkan nama asli
                    $jalurPendaftaranList = \App\Models\JalurPendaftaran::aktif()->get()->keyBy('kode');

                    // Function untuk mendapatkan warna jalur berdasarkan kode
                    $getJalurColor = function($kode) {
                        // Mapping warna berdasarkan pattern kode jalur
                        if (str_contains($kode, 'prestasi')) {
                            return [
                                'bgGradient' => 'background: linear-gradient(to bottom right, #EFF6FF, #ffffff)',
                                'border' => 'border-blue-200 hover:border-blue-400',
                                'iconBg' => 'background-color: #2563EB',
                                'dotBg' => 'background-color: #2563EB',
                                'borderLine' => 'border-blue-200',
                                'textColor' => 'color: #2563EB'
                            ];
                        } elseif (str_contains($kode, 'tahfidz') || str_contains($kode, 'tahfiz')) {
                            return [
                                'bgGradient' => 'background: linear-gradient(to bottom right, #FAF5FF, #ffffff)',
                                'border' => 'border-purple-200 hover:border-purple-400',
                                'iconBg' => 'background-color: #9333EA',
                                'dotBg' => 'background-color: #9333EA',
                                'borderLine' => 'border-purple-200',
                                'textColor' => 'color: #9333EA'
                            ];
                        } else {
                            // Default untuk reguler dan lainnya
                            return [
                                'bgGradient' => 'background: linear-gradient(to bottom right, #ECFDF5, #ffffff)',
                                'border' => 'border-emerald-200 hover:border-emerald-400',
                                'iconBg' => 'background-color: #059669',
                                'dotBg' => 'background-color: #059669',
                                'borderLine' => 'border-emerald-200',
                                'textColor' => 'color: #059669'
                            ];
                        }
                    };

                    // Function untuk format nama jalur
                    $formatJalurNama = function($kode) use ($jalurPendaftaranList) {
                        // Coba ambil dari database dulu
                        if (isset($jalurPendaftaranList[$kode])) {
                            return $jalurPendaftaranList[$kode]->nama;
                        }

                        // Jika tidak ada, format dari kode
                        // Ganti underscore dengan spasi dan capitalize setiap kata
                        return ucwords(str_replace('_', ' ', $kode));
                    };
                @endphp

                @foreach($timelineByJalur as $jalurKode => $timelines)
                    @php
                        $colorConfig = $getJalurColor($jalurKode);
                        $jalurNama = $formatJalurNama($jalurKode);
                    @endphp
                    <div class="border-2 {{ $colorConfig['border'] }} rounded-2xl p-6 hover:shadow-xl transition-all duration-300" style="{{ $colorConfig['bgGradient'] }}">
                        <div class="flex items-center mb-6">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center mr-4 shadow-md" style="{{ $colorConfig['iconBg'] }}">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $jalurNama }}</h3>
                        </div>

                        <div class="space-y-3">
                            @foreach($timelines->sortBy('urutan') as $timeline)
                                <div class="relative pl-6 {{ !$loop->last ? 'pb-6' : '' }} border-l-2 {{ $colorConfig['borderLine'] }}">
                                    <div class="absolute -left-2 top-0.5 w-4 h-4 rounded-full shadow-sm" style="{{ $colorConfig['dotBg'] }}"></div>
                                    <div class="bg-white/60 rounded-lg p-3 hover:bg-white transition-all">
                                        <div class="text-sm font-bold mb-1.5" style="{{ $colorConfig['textColor'] }}">{{ $timeline->judul_kegiatan }}</div>
                                        <div class="text-xs text-gray-700 font-semibold mb-1 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->format('j') }}
                                            @if($timeline->tanggal_mulai != $timeline->tanggal_selesai)
                                                - {{ \Carbon\Carbon::parse($timeline->tanggal_selesai)->format('j') }} {{ \Carbon\Carbon::parse($timeline->tanggal_selesai)->locale('id_ID')->monthName }} {{ \Carbon\Carbon::parse($timeline->tanggal_selesai)->year }}
                                            @else
                                                {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->locale('id_ID')->monthName }} {{ \Carbon\Carbon::parse($timeline->tanggal_mulai)->year }}
                                            @endif
                                        </div>
                                        @if($timeline->waktu_mulai)
                                            <div class="text-xs text-gray-600 mb-1 flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($timeline->waktu_mulai)->format('H:i') }}
                                                @if($timeline->waktu_selesai)
                                                    - {{ \Carbon\Carbon::parse($timeline->waktu_selesai)->format('H:i') }} WIB
                                                @endif
                                            </div>
                                        @endif
                                        @if($timeline->tempat)
                                            <div class="text-xs text-gray-600 mb-1 flex items-start">
                                                <svg class="w-3.5 h-3.5 mr-1.5 mt-0.5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ $timeline->tempat }}</span>
                                            </div>
                                        @endif
                                        @if($timeline->deskripsi)
                                            <div class="text-xs text-gray-600 mt-2 leading-relaxed">{{ $timeline->deskripsi }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Timeline Pendaftaran Belum Tersedia</h3>
                <p class="text-gray-600">Timeline pendaftaran akan segera ditampilkan. Silakan cek kembali nanti.</p>
            </div>
            @endif

            <!-- Additional Info -->
            <div class="mt-12 bg-gradient-to-r from-emerald-50 to-blue-50 border-l-4 border-emerald-600 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-emerald-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">Catatan Penting:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Pendaftar wajib melengkapi dokumen yang dipersyaratkan sesuai jalur yang dipilih</li>
                            <li>• Pengumuman hasil seleksi dapat dilihat di website resmi dan papan pengumuman sekolah</li>
                            <li>• Daftar ulang harus dilakukan sesuai jadwal, keterlambatan mengakibatkan gugurnya hak sebagai calon peserta didik</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video & Infografis -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Headers -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                <div class="lg:col-span-9">
                    <h2 class="text-3xl font-bold text-gray-900">Video</h2>
                </div>
                <div class="lg:col-span-3">
                    <h2 class="text-3xl font-bold text-gray-900">Infografis</h2>
                </div>
            </div>

            <!-- Content Section -->
            @if($videoPanduan->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Video Preview (Big Screen) -->
                    <div class="lg:col-span-6">
                            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                <div class="aspect-w-16 aspect-h-9">
                                    <!-- Embed YouTube video -->
                                    @php
                                        // Extract YouTube video ID from URL for the first video
                                        $firstVideo = $videoPanduan->first();
                                        $videoId = '';
                                        if ($firstVideo && preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $firstVideo->url_video, $matches)) {
                                            $videoId = $matches[1];
                                        }
                                    @endphp
                                    @if($videoId)
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                            class="w-full h-64 lg:h-96"
                                            id="mainVideoPlayer"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                        ></iframe>
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-64 lg:h-96 flex items-center justify-center text-gray-500">
                                            Video tidak ditemukan
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-bold text-gray-900" id="mainVideoTitle">{{ $firstVideo->judul ?? 'Video tidak ditemukan' }}</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Video List -->
                        <div class="lg:col-span-3">
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                @foreach($videoPanduan as $index => $video)
                                    @php
                                        $currentVideoId = '';
                                        if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video->url_video, $matches)) {
                                            $currentVideoId = $matches[1];
                                        }
                                    @endphp
                                    <div
                                        class="bg-white rounded-lg shadow-sm p-3 cursor-pointer hover:bg-gray-50 transition {{ $index === 0 ? 'border-2 border-emerald-500' : 'border border-gray-200' }}"
                                        data-video-index="{{ $index }}"
                                        onclick="loadVideo('{{ $currentVideoId }}', '{{ $video->judul }}', '{{ addslashes($video->deskripsi ?? '') }}', {{ $index }})"
                                    >
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-16 h-12 relative">
                                                @if($currentVideoId)
                                                    <img
                                                        src="https://img.youtube.com/vi/{{ $currentVideoId }}/mqdefault.jpg"
                                                        alt="{{ $video->judul }}"
                                                        class="w-full h-full object-cover rounded"
                                                        onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'320\' height=\'180\' viewBox=\'0 0 320 180\'><rect fill=\'%23f3f4f6\' width=\'320\' height=\'180\'/><text x=\'160\' y=\'90\' font-family=\'Arial\' font-size=\'14\' fill=\'%239ca3af\' text-anchor=\'middle\'>Thumbnail Tidak Ditemukan</text></svg>';"
                                                    >
                                                @else
                                                    <div class="bg-gray-200 w-full h-full rounded flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $video->judul }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Infografis Section -->
                        <div class="lg:col-span-3">
                            @if($infografis->count() > 0)
                                <!-- Carousel container -->
                                <div class="relative" id="infografisCarousel">
                                    <!-- Carousel slides - using smaller Instagram aspect ratio (1:1) -->
                                    <div class="overflow-hidden rounded-xl relative">
                                        <div class="flex transition-transform duration-500 ease-in-out transform" id="infografisSlidesContainer">
                                            @foreach($infografis as $index => $info)
                                                <div class="w-full flex-shrink-0" id="infografisSlide{{ $index }}">
                                                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                                        <div class="w-full relative" style="padding-bottom: 125%;"> <!-- 4:5 aspect ratio = 125% -->
                                                            <img
                                                                src="{{ str_starts_with($info->gambar_path, 'http') ? $info->gambar_path : asset('storage/' . $info->gambar_path) }}"
                                                                alt="{{ $info->judul }}"
                                                                class="absolute inset-0 w-full h-full object-cover"
                                                                onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\' viewBox=\'0 0 200 200\'><rect fill=\'%23f3f4f6\' width=\'200\' height=\'200\'/><text x=\'100\' y=\'100\' font-family=\'Arial\' font-size=\'12\' fill=\'%239ca3af\' text-anchor=\'middle\'>Info Tidak Ditemukan</text></svg>';"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Carousel indicators -->
                                    <div class="flex justify-center mt-2 space-x-1" id="infografisIndicators">
                                        @foreach($infografis as $index => $info)
                                            <button
                                                onclick="changeInfografisSlide({{ $index }})"
                                                class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-emerald-600' : 'bg-gray-300' }}"
                                                id="indicator{{ $index }}"
                                                aria-label="Slide {{ $index + 1 }}"
                                            ></button>
                                        @endforeach
                                    </div>

                                    <!-- Carousel navigation -->
                                    <div class="absolute inset-y-0 left-0 flex items-center">
                                        <button
                                            onclick="prevInfografisSlide()"
                                            class="bg-white/70 rounded-full p-1 ml-1 hover:bg-white transition"
                                            aria-label="Previous slide"
                                        >
                                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <button
                                            onclick="nextInfografisSlide()"
                                            class="bg-white/70 rounded-full p-1 mr-1 hover:bg-white transition"
                                            aria-label="Next slide"
                                        >
                                            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Empty state for infographics -->
                                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                    <div class="w-full relative bg-gray-100" style="padding-bottom: 125%;"> <!-- 4:5 aspect ratio = 125% -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center p-4">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-xs text-gray-600">Tidak Ada</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
            @else
                <!-- Empty state for videos -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-9">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9 flex items-center justify-center bg-gray-100">
                                <div class="text-center p-8">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold text-gray-700 mb-2">Video Belum Tersedia</h3>
                                    <p class="text-gray-600">Video akan segera ditambahkan. Silakan cek kembali nanti.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-3">
                        @if($infografis->count() > 0)
                            <!-- Show infografis even if no videos -->
                            <div class="relative" id="infografisCarousel">
                                <div class="overflow-hidden rounded-xl relative">
                                    <div class="flex transition-transform duration-500 ease-in-out transform" id="infografisSlidesContainer">
                                        @foreach($infografis as $index => $info)
                                            <div class="w-full flex-shrink-0" id="infografisSlide{{ $index }}">
                                                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                                    <div class="w-full relative" style="padding-bottom: 125%;"> <!-- 4:5 aspect ratio = 125% -->
                                                        <img
                                                            src="{{ str_starts_with($info->gambar_path, 'http') ? $info->gambar_path : asset('storage/' . $info->gambar_path) }}"
                                                            alt="{{ $info->judul }}"
                                                            class="absolute inset-0 w-full h-full object-cover"
                                                            onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\' viewBox=\'0 0 200 200\'><rect fill=\'%23f3f4f6\' width=\'200\' height=\'200\'/><text x=\'100\' y=\'100\' font-family=\'Arial\' font-size=\'12\' fill=\'%239ca3af\' text-anchor=\'middle\'>Info Tidak Ditemukan</text></svg>';"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="flex justify-center mt-2 space-x-1" id="infografisIndicators">
                                    @foreach($infografis as $index => $info)
                                        <button
                                            onclick="changeInfografisSlide({{ $index }})"
                                            class="w-2 h-2 rounded-full {{ $index === 0 ? 'bg-emerald-600' : 'bg-gray-300' }}"
                                            id="indicator{{ $index }}"
                                            aria-label="Slide {{ $index + 1 }}"
                                        ></button>
                                    @endforeach
                                </div>
                                <div class="absolute inset-y-0 left-0 flex items-center">
                                    <button
                                        onclick="prevInfografisSlide()"
                                        class="bg-white/70 rounded-full p-1 ml-1 hover:bg-white transition"
                                        aria-label="Previous slide"
                                    >
                                        <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <button
                                        onclick="nextInfografisSlide()"
                                        class="bg-white/70 rounded-full p-1 mr-1 hover:bg-white transition"
                                        aria-label="Next slide"
                                    >
                                        <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                <div class="w-full relative bg-gray-100" style="padding-bottom: 125%;"> <!-- 4:5 aspect ratio = 125% -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-xs text-gray-600">Tidak Ada</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- JavaScript for video player -->
            <script>
                function loadVideo(videoId, title, description, index) {
                    const player = document.getElementById('mainVideoPlayer');
                    const titleElement = document.getElementById('mainVideoTitle');

                    if(videoId) {
                        player.src = `https://www.youtube.com/embed/${videoId}`;
                        titleElement.textContent = title;

                        // Remove active class from all items
                        document.querySelectorAll('[data-video-index]').forEach(el => {
                            el.classList.remove('border-emerald-500');
                            el.classList.add('border-gray-200');
                        });

                        // Add active class to clicked item
                        const clickedElement = document.querySelector(`[data-video-index="${index}"]`);
                        if (clickedElement) {
                            clickedElement.classList.remove('border-gray-200');
                            clickedElement.classList.add('border-emerald-500');
                        }
                    }
                }
            </script>

            <!-- JavaScript for infografis carousel -->
            <script>
                let currentInfografisSlide = 0;
                const totalInfografisSlides = {{ $infografis->count() }};

                function updateInfografisCarousel() {
                    const container = document.getElementById('infografisSlidesContainer');
                    const indicators = document.querySelectorAll('[id^="indicator"]');

                    container.style.transform = `translateX(-${currentInfografisSlide * 100}%)`;

                    // Update indicators
                    indicators.forEach((indicator, index) => {
                        if (index === currentInfografisSlide) {
                            indicator.classList.remove('bg-gray-300');
                            indicator.classList.add('bg-emerald-600');
                        } else {
                            indicator.classList.remove('bg-emerald-600');
                            indicator.classList.add('bg-gray-300');
                        }
                    });
                }

                function changeInfografisSlide(slideIndex) {
                    currentInfografisSlide = slideIndex;
                    updateInfografisCarousel();
                }

                function nextInfografisSlide() {
                    currentInfografisSlide = (currentInfografisSlide + 1) % totalInfografisSlides;
                    updateInfografisCarousel();
                }

                function prevInfografisSlide() {
                    currentInfografisSlide = (currentInfografisSlide - 1 + totalInfografisSlides) % totalInfografisSlides;
                    updateInfografisCarousel();
                }

                // Auto-rotate every 5 seconds
                setInterval(() => {
                    nextInfografisSlide();
                }, 5000);
            </script>
        </div>
    </section>

    <!-- Testimoni Alumni Section -->
    @if($testimoni->count() > 0)
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Testimoni Alumni
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Dengarkan cerita sukses para alumni kami
                </p>
            </div>

            <!-- Testimonials Carousel -->
            <div class="relative" id="testimoniCarousel">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out" id="testimoniSlides">
                        @foreach($testimoni as $index => $testi)
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-3" data-testimoni-slide="{{ $index }}">
                            <div class="bg-white rounded-xl shadow-lg p-8 h-full hover:shadow-xl transition-shadow duration-300">
                                <!-- Quote Icon -->
                                <div class="mb-6">
                                    <svg class="w-10 h-10 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path>
                                    </svg>
                                </div>

                                <!-- Testimoni Text -->
                                <p class="text-gray-700 mb-6 italic leading-relaxed">
                                    "{{ $testi->testimoni }}"
                                </p>

                                <!-- Alumni Info -->
                                <div class="flex items-center pt-6 border-t border-gray-100">
                                    @if($testi->foto_path)
                                        <img src="{{ asset('storage/' . $testi->foto_path) }}" alt="{{ $testi->nama }}" class="w-16 h-16 rounded-full object-cover mr-4 ring-4 ring-emerald-50">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mr-4 ring-4 ring-emerald-50">
                                            <span class="text-emerald-600 font-bold text-xl">{{ substr($testi->nama, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $testi->nama }}</h4>
                                        <p class="text-sm text-gray-500">Lulusan {{ $testi->tahun_lulus }}</p>
                                        @if($testi->institusi_sekarang)
                                            <p class="text-sm text-emerald-600 font-medium mt-1">{{ $testi->institusi_sekarang }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Arrows -->
                @if($testimoni->count() > 3)
                <button onclick="prevTestimoniSlide()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition-all duration-200 z-10" aria-label="Previous testimonials">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button onclick="nextTestimoniSlide()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition-all duration-200 z-10" aria-label="Next testimonials">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @endif

                <!-- Indicators -->
                @if($testimoni->count() > 3)
                <div class="flex justify-center mt-8 space-x-2">
                    @for($i = 0; $i < ceil($testimoni->count() / 3); $i++)
                    <button
                        onclick="goToTestimoniSlide({{ $i }})"
                        class="w-3 h-3 rounded-full transition-all duration-200 {{ $i === 0 ? 'bg-emerald-600 w-8' : 'bg-gray-300 hover:bg-gray-400' }}"
                        data-testimoni-indicator="{{ $i }}"
                        aria-label="Go to slide {{ $i + 1 }}"
                    ></button>
                    @endfor
                </div>
                @endif
            </div>

            <!-- JavaScript for testimoni carousel -->
            <script>
                let currentTestimoniSlide = 0;
                const totalTestimoniSlides = Math.ceil({{ $testimoni->count() }} / 3);
                const testimoniPerSlide = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);

                function updateTestimoniCarousel() {
                    const container = document.getElementById('testimoniSlides');
                    const indicators = document.querySelectorAll('[data-testimoni-indicator]');

                    if (container) {
                        container.style.transform = `translateX(-${currentTestimoniSlide * 100}%)`;
                    }

                    // Update indicators
                    indicators.forEach((indicator, index) => {
                        if (index === currentTestimoniSlide) {
                            indicator.classList.remove('bg-gray-300', 'w-3');
                            indicator.classList.add('bg-emerald-600', 'w-8');
                        } else {
                            indicator.classList.remove('bg-emerald-600', 'w-8');
                            indicator.classList.add('bg-gray-300', 'w-3');
                        }
                    });
                }

                function nextTestimoniSlide() {
                    currentTestimoniSlide = (currentTestimoniSlide + 1) % totalTestimoniSlides;
                    updateTestimoniCarousel();
                }

                function prevTestimoniSlide() {
                    currentTestimoniSlide = (currentTestimoniSlide - 1 + totalTestimoniSlides) % totalTestimoniSlides;
                    updateTestimoniCarousel();
                }

                function goToTestimoniSlide(index) {
                    currentTestimoniSlide = index;
                    updateTestimoniCarousel();
                }

                // Auto-rotate every 7 seconds
                setInterval(() => {
                    if (totalTestimoniSlides > 1) {
                        nextTestimoniSlide();
                    }
                }, 7000);
            </script>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-emerald-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Siap Bergabung dengan Kami?
            </h2>
            <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
                Daftarkan diri Anda sekarang dan raih masa depan yang cerah bersama MTsN 2 Kota Malang
            </p>
            <a href="/daftar" class="inline-flex items-center px-8 py-4 border border-transparent text-base sm:text-lg font-medium rounded-lg text-emerald-600 bg-white hover:bg-gray-50 hover:scale-105 transition-all duration-200 shadow-lg">
                Mulai Pendaftaran
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </section>

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
        function showForgotPasswordNotification() {
            alert('Silahkan hubungi panitia untuk bantuan terkait lupa password.');
        }
    </script>
</x-layouts.public>
