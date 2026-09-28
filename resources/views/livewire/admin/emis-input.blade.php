<div>
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Input EMIS - Data Lengkap Pendaftar
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Data lengkap pendaftar yang telah lulus dan melakukan daftar ulang untuk input EMIS
                </p>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <!-- Messages -->
                @if(session()->has('message'))
                    <div class="mb-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search and Controls -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
                    <div class="w-full md:w-1/2">
                        <label for="search" class="sr-only">Cari</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="search"
                                wire:model.debounce.300ms="search"
                                class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 pr-12 py-2 sm:text-sm border-gray-300 rounded-md"
                                placeholder="Cari nama, NISN, nomor pendaftaran, email..."
                            >
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <button
                            wire:click="refreshData"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Info Summary -->
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-sm text-blue-700">
                            <span class="font-semibold">Total: {{ count($acceptedApplicants) }} pendaftar</span> yang telah diterima dan siap input EMIS
                        </div>
                    </div>
                </div>

                <!-- Scrollable Table -->
                <div class="bg-white shadow overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <!-- No -->
                                <th scope="col" class="sticky left-0 z-10 bg-gray-50 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">
                                    No
                                </th>

                                <!-- IDENTITAS DASAR -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    No. Pendaftaran
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    NISN
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    NIK
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Nama Lengkap
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    JK
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    TTL
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Agama
                                </th>

                                <!-- KONTAK -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    No. HP
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Email
                                </th>

                                <!-- ALAMAT -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Alamat
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Desa/Kel
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Kecamatan
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Kab/Kota
                                </th>

                                <!-- ORANG TUA -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Nama Ayah
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Pekerjaan Ayah
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Nama Ibu
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Pekerjaan Ibu
                                </th>

                                <!-- SEKOLAH ASAL -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Sekolah Asal
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    NPSN
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    NSM
                                </th>

                                <!-- JALUR -->
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                    Jalur
                                </th>

                                <!-- STATUS DOKUMEN -->
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    KK
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    Akta
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    Ijazah
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    SKHUN
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    Foto
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-blue-50">
                                    Ket. Lulus
                                </th>

                                <!-- STATUS PENDAFTARAN -->
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-green-50">
                                    Status Pendaftaran
                                </th>

                                <!-- STATUS TES PSIKOLOGI -->
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-purple-50">
                                    Tes Psikologi
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-purple-50">
                                    Status Tes
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-purple-50">
                                    Hasil Tes
                                </th>

                                <!-- STATUS DAFTAR ULANG -->
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-yellow-50">
                                    DU: Pembayaran
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-yellow-50">
                                    DU: Seragam
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-yellow-50">
                                    DU: Berkas
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap bg-yellow-50">
                                    DU: Status
                                </th>

                                <!-- ACTION -->
                                <th scope="col" class="sticky right-0 z-10 bg-gray-50 px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-l border-gray-200">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($acceptedApplicants as $index => $applicant)
                                <tr class="hover:bg-gray-50">
                                    <!-- No -->
                                    <td class="sticky left-0 z-10 bg-white px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                        {{ $index + 1 }}
                                    </td>

                                    <!-- IDENTITAS DASAR -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $applicant->nomor_pendaftaran }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nisn }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nik ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        {{ $applicant->nama_lengkap }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->tempat_lahir }}, {{ \Carbon\Carbon::parse($applicant->tanggal_lahir)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->agama ?? '-' }}
                                    </td>

                                    <!-- KONTAK -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nomor_hp ?? $applicant->no_hp ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->email ?? $applicant->user->email ?? '-' }}
                                    </td>

                                    <!-- ALAMAT -->
                                    <td class="px-3 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $applicant->alamat }}">
                                        {{ $applicant->alamat ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->desa ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->kecamatan ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->kabupaten ?? '-' }}
                                    </td>

                                    <!-- ORANG TUA -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nama_ayah ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->pekerjaan_ayah ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nama_ibu ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->pekerjaan_ibu ?? '-' }}
                                    </td>

                                    <!-- SEKOLAH ASAL -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->asal_sekolah ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->npsn_sekolah_asal ?? '-' }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $applicant->nsm_sekolah_asal ?? '-' }}
                                    </td>

                                    <!-- JALUR -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $applicant->jalurPendaftaran->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- STATUS DOKUMEN -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_kartu_keluarga)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_akta_kelahiran)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_ijazah)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_skhun)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_pas_foto)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-blue-50">
                                        @if($applicant->file_surat_keterangan_lulus)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Ada">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- STATUS PENDAFTARAN -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-green-50">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $applicant->status_pendaftaran === 'data_disetujui' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $applicant->status_pendaftaran)) }}
                                        </span>
                                    </td>

                                    <!-- STATUS TES PSIKOLOGI -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-purple-50">
                                        @if($applicant->tesPsikologiPendaftar)
                                            @if($applicant->tesPsikologiPendaftar->status === 'lulus')
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Lulus">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @elseif($applicant->tesPsikologiPendaftar->status === 'tidak_lulus')
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Tidak Lulus">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-800" title="Pending">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-xs bg-purple-50">
                                        @if($applicant->tesPsikologiPendaftar)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $applicant->tesPsikologiPendaftar->status === 'lulus' ? 'bg-green-100 text-green-800' :
                                                   ($applicant->tesPsikologiPendaftar->status === 'tidak_lulus' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $applicant->tesPsikologiPendaftar->status)) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center text-xs bg-purple-50">
                                        @if($applicant->tesPsikologiPendaftar && $applicant->tesPsikologiPendaftar->opsi)
                                            <span class="font-medium">{{ $applicant->tesPsikologiPendaftar->opsi->nama_opsi }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- STATUS DAFTAR ULANG -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-yellow-50">
                                        @if($applicant->daftarUlang)
                                            @if($applicant->daftarUlang->pembayaran_lunas)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Lunas">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-yellow-50">
                                        @if($applicant->daftarUlang)
                                            @if($applicant->daftarUlang->seragam_diukur)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Sudah">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-yellow-50">
                                        @if($applicant->daftarUlang)
                                            @if($applicant->daftarUlang->berkas_lengkap)
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800" title="Lengkap">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-800" title="Belum">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-center bg-yellow-50">
                                        @if($applicant->daftarUlang)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $applicant->daftarUlang->status === 'selesai' ? 'bg-green-100 text-green-800' :
                                                   ($applicant->daftarUlang->status === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($applicant->daftarUlang->status) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- ACTION -->
                                    <td class="sticky right-0 z-10 bg-white px-3 py-4 whitespace-nowrap text-center text-sm border-l border-gray-200">
                                        <button
                                            wire:click="showDetail({{ $applicant->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700"
                                        >
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="40" class="px-4 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data pendaftar</h3>
                                        <p class="mt-1 text-sm text-gray-500">Tidak ditemukan pendaftar yang telah diterima atau melakukan daftar ulang.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Legend -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 bg-blue-50 rounded">
                        <h4 class="font-semibold text-blue-900 mb-1">Status Dokumen</h4>
                        <div class="space-y-1 text-blue-700">
                            <div>✓ = Dokumen sudah diupload</div>
                            <div>✗ = Dokumen belum diupload</div>
                        </div>
                    </div>
                    <div class="p-3 bg-purple-50 rounded">
                        <h4 class="font-semibold text-purple-900 mb-1">Status Tes Psikologi</h4>
                        <div class="space-y-1 text-purple-700">
                            <div>✓ = Lulus tes</div>
                            <div>✗ = Tidak lulus</div>
                            <div>- = Belum tes</div>
                        </div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded">
                        <h4 class="font-semibold text-yellow-900 mb-1">Status Daftar Ulang</h4>
                        <div class="space-y-1 text-yellow-700">
                            <div>DU = Daftar Ulang</div>
                            <div>✓ = Sudah / Selesai</div>
                            <div>✗ = Belum / Pending</div>
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 rounded">
                        <h4 class="font-semibold text-green-900 mb-1">Keterangan</h4>
                        <div class="space-y-1 text-green-700">
                            <div>Total: {{ count($acceptedApplicants) }} siswa</div>
                            <div>Status: Data Disetujui</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal (unchanged from original) -->
    @if($showDetailModal && $selectedApplicant)
        <div class="fixed inset-0 bg-gray-600/50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
                <div class="mt-3">
                    <div class="flex items-center justify-between pb-3 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Detail Lengkap Pendaftar</h3>
                        <button
                            wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-6">
                        <!-- Identitas Section -->
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 mb-3">Identitas Dasar</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">No. Pendaftaran</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nomor_pendaftaran }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">NISN</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nisn }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">NIK</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nik ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Jenis Kelamin</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->tempat_lahir }}, {{ \Carbon\Carbon::parse($selectedApplicant->tanggal_lahir)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Agama</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->agama ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">No. HP</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nomor_hp ?? $selectedApplicant->no_hp ?? '-' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->email ?? $selectedApplicant->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Section -->
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 mb-3">Alamat</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-500">Alamat Lengkap</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->alamat ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Desa/Kelurahan</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->desa ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kecamatan</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->kecamatan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kabupaten/Kota</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->kabupaten ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Provinsi</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->provinsi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Orang Tua Section -->
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 mb-3">Data Orang Tua</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Ayah</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nama_ayah ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Pekerjaan Ayah</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->pekerjaan_ayah ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Ibu</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nama_ibu ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Pekerjaan Ibu</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->pekerjaan_ibu ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Sekolah Asal Section -->
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 mb-3">Sekolah Asal</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Sekolah</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->asal_sekolah ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">NPSN</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->npsn_sekolah_asal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">NSM</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->nsm_sekolah_asal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Jalur Pendaftaran</p>
                                    <p class="text-sm text-gray-900">{{ $selectedApplicant->jalurPendaftaran->nama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div>
                            <h4 class="text-base font-semibold text-gray-900 mb-3">Status</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status Pendaftaran</p>
                                    <p class="text-sm">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst(str_replace('_', ' ', $selectedApplicant->status_pendaftaran)) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status Tes Psikologi</p>
                                    <p class="text-sm">
                                        @if($selectedApplicant->tesPsikologiPendaftar)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $selectedApplicant->tesPsikologiPendaftar->status === 'lulus' ? 'bg-green-100 text-green-800' :
                                                   ($selectedApplicant->tesPsikologiPendaftar->status === 'tidak_lulus' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $selectedApplicant->tesPsikologiPendaftar->status)) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status Daftar Ulang</p>
                                    <p class="text-sm">
                                        @if($selectedApplicant->daftarUlang)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $selectedApplicant->daftarUlang->status === 'selesai' ? 'bg-green-100 text-green-800' :
                                                   ($selectedApplicant->daftarUlang->status === 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($selectedApplicant->daftarUlang->status) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="items-center px-4 py-3 mt-4 border-t">
                        <button
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
