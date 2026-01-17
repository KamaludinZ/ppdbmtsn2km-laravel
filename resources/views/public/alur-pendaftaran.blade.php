<x-layouts.public title="Alur Pendaftaran - {{ \App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang') }}">
    <x-registration-phase-banner />

    <!-- Breadcrumb -->
    <section class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Alur Pendaftaran</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-600 to-emerald-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Alur Pendaftaran {{ $appAcronym }}</h1>
                <p class="text-xl text-emerald-100 max-w-3xl mx-auto">
                    Panduan lengkap proses pendaftaran peserta didik baru {{ \App\Models\Setting::get('app_name', 'MTsN 2 Kota Malang') }} tahun {{ date('Y') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Steps -->
            <div class="space-y-8">
                <!-- Step 1 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-emerald-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold text-xl mr-4">
                            1
                        </div>
                        <h3 class="text-2xl font-bold text-white">Pilih Jalur & Cek Kualifikasi</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Pilih jalur pendaftaran dan pastikan nilai rapor Anda memenuhi kualifikasi yang ditentukan.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Pilih jalur pendaftaran yang sesuai dengan kualifikasi Anda</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Pastikan nilai rapor Anda memenuhi persyaratan jalur yang dipilih</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Periksa kuota yang tersedia untuk jalur yang dipilih</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-blue-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-xl mr-4">
                            2
                        </div>
                        <h3 class="text-2xl font-bold text-white">Buat Akun</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Klik tombol "Daftar Sekarang" dan isi formulir registrasi dengan email dan password yang valid.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Klik tombol "Daftar Sekarang" di menu utama</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Isi form pendaftaran dengan data yang valid</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Simpan username dan password dengan baik</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-purple-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-purple-600 font-bold text-xl mr-4">
                            3
                        </div>
                        <h3 class="text-2xl font-bold text-white">Login ke Sistem</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Masuk ke akun Anda menggunakan email dan password yang telah didaftarkan.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Klik "Login Pendaftar" di menu utama</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Masukkan email dan password yang telah didaftarkan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Akses dashboard pendaftar untuk melengkapi formulir</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-orange-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-orange-600 font-bold text-xl mr-4">
                            4
                        </div>
                        <h3 class="text-2xl font-bold text-white">Isi Formulir</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Lengkapi formulir pendaftaran dengan data diri, data orang tua, dan konfirmasi jalur pendaftaran.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Data diri (nama, tempat/tanggal lahir, alamat, dll)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Data orang tua (ayah, ibu, wali)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-orange-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Konfirmasi jalur pendaftaran yang dipilih</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-teal-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-teal-600 font-bold text-xl mr-4">
                            5
                        </div>
                        <h3 class="text-2xl font-bold text-white">Upload Dokumen</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Unggah semua dokumen persyaratan seperti ijazah, KK, akta kelahiran, rapor, dan dokumen prestasi (jika ada).
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Ijazah/SKHUN</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Akta Kelahiran</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Kartu Keluarga</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Rapor (semester 1-5)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-teal-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Dokumen prestasi (jika ada)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-pink-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-pink-600 font-bold text-xl mr-4">
                            6
                        </div>
                        <h3 class="text-2xl font-bold text-white">Verifikasi Berkas</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Panitia akan memverifikasi berkas Anda. Pantau status verifikasi melalui dashboard pendaftar.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-pink-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Cek status verifikasi di dashboard</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-pink-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Jika ditolak, upload ulang berkas yang bermasalah</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-pink-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Tunggu konfirmasi verifikasi dari panitia</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-indigo-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-indigo-600 font-bold text-xl mr-4">
                            7
                        </div>
                        <h3 class="text-2xl font-bold text-white">Cetak Bukti Pendaftaran</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Setelah berkas diverifikasi dan diterima, cetak bukti pendaftaran sebagai tanda peserta seleksi.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Cetak bukti pendaftaran dari dashboard</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Gunakan bukti pendaftaran untuk mengikuti seleksi</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Simpan bukti pendaftaran untuk referensi</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-yellow-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-yellow-600 font-bold text-xl mr-4">
                            8
                        </div>
                        <h3 class="text-2xl font-bold text-white">Mengikuti Seleksi</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Ikuti tes seleksi sesuai jalur yang dipilih (tes tertulis, tes tahfidz, atau seleksi prestasi) pada waktu yang ditentukan.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Ikuti tes sesuai jadwal yang ditentukan</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Bawa perlengkapan yang diperlukan (kartu peserta, alat tulis, dll)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Patuhi protokol kesehatan dan tata tertib pelaksanaan tes</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 9 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-cyan-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-cyan-600 font-bold text-xl mr-4">
                            9
                        </div>
                        <h3 class="text-2xl font-bold text-white">Cek Hasil Seleksi</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Pantau pengumuman hasil seleksi melalui dashboard atau website. Hasil akan diumumkan sesuai jadwal yang ditentukan.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-cyan-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Cek hasil seleksi di dashboard pendaftar</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-cyan-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Periksa apakah Anda dinyatakan lulus seleksi</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-cyan-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Siapkan dokumen untuk daftar ulang jika dinyatakan lulus</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 10 -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-green-600 px-8 py-4 flex items-center">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-green-600 font-bold text-xl mr-4">
                            10
                        </div>
                        <h3 class="text-2xl font-bold text-white">Daftar Ulang</h3>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 mb-4">
                            Jika lulus seleksi, lakukan pembayaran daftar ulang, pengukuran seragam, dan lengkapi berkas di sekolah sesuai jadwal.
                        </p>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Lakukan pembayaran daftar ulang sesuai petunjuk</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Hadir ke sekolah untuk pengukuran seragam dan pelengkapan berkas</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Ikuti orientasi awal masuk sekolah (OSIS) sesuai jadwal</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Penting -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">Perhatian Penting:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Pastikan semua data yang diisi benar dan sesuai dengan dokumen asli</li>
                            <li>• Simpan username dan password dengan baik, jangan berikan kepada siapa pun</li>
                            <li>• Ukuran file dokumen maksimal 2MB per file</li>
                            <li>• Jika mengalami kesulitan, hubungi panitia melalui kontak yang tersedia</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Memulai Pendaftaran?</h2>
            <p class="text-purple-100 mb-8 max-w-2xl mx-auto">
                Ikuti langkah-langkah di atas dan segera daftarkan diri Anda
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/daftar" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-purple-600 bg-white hover:bg-gray-50 transition shadow-lg">
                    Daftar Sekarang
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="/informasi" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-lg text-white hover:bg-purple-700 transition">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
