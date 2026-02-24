<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tes Seleksi - {{ $pendaftar->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #1f2937;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .card {
            width: 180mm;
            padding: 6mm 0;
            margin: 0 auto;
            border: none;
            background: #fff;
            box-sizing: border-box;
        }

        /* Header Kop Surat */
        .header {
            width: 100%;
            max-width: 180mm;
            border-bottom: 4px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .header table {
            width: 100%;
            max-width: 180mm;
            border-collapse: collapse;
        }

        .header-logo {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
            text-align: center;
            vertical-align: middle;
        }

        .header-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .header-content {
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
            padding: 0 10px;
        }

        .header-content h2 {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
            color: #111827;
            line-height: 1.2;
            width: 100%;
        }

        .header-content .address {
            font-size: 10.5pt;
            margin: 2px 0;
            color: #374151;
            line-height: 1.2;
            width: 100%;
        }

        .header-content .contact {
            font-size: 9pt;
            color: #6b7280;
            margin-top: 1px;
            line-height: 1.2;
            width: 100%;
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin: 12px 0;
            width: 100%;
            max-width: 180mm;
            overflow: hidden;
        }

        .title-section h1 {
            font-size: 17pt;
            font-weight: bold;
            color: #111827;
            margin: 0 0 3px 0;
            line-height: 1.2;
        }

        .title-section p {
            font-size: 12pt;
            color: #4b5563;
            line-height: 1.3;
        }

        /* Student Info Section */
        .student-info {
            width: 100%;
            max-width: 180mm;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .student-info table {
            width: 100%;
            max-width: 180mm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .student-data {
            width: 69%;
            vertical-align: top;
            padding-right: 6px;
        }

        .student-photo {
            width: 31%;
            text-align: center;
            vertical-align: top;
        }

        .data-table {
            width: 100%;
            max-width: 124mm;
            font-size: 10pt;
            table-layout: fixed;
        }

        .data-table td {
            padding: 3px 0;
            vertical-align: top;
            line-height: 1.3;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .data-table .label {
            width: 35%;
            font-weight: 500;
            color: #374151;
        }

        .data-table .value {
            font-weight: 600;
            color: #111827;
            width: 65%;
        }

        .photo-box {
            width: 108px;
            height: 138px;
            border: 2px solid #9ca3af;
            background: #f3f4f6;
            display: inline-block;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: table;
            text-align: center;
        }

        .photo-placeholder span {
            display: table-cell;
            vertical-align: middle;
            font-size: 8pt;
            color: #6b7280;
        }

        /* Section Title */
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #111827;
            margin: 8px 0 4px 0;
            line-height: 1.2;
            width: 100%;
            max-width: 180mm;
            overflow: hidden;
            box-sizing: border-box;
        }

        /* Prestasi Table */
        .prestasi-table {
            width: 100%;
            max-width: 180mm;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 6.5pt;
            line-height: 1.2;
            table-layout: fixed;
            box-sizing: border-box;
        }

        .prestasi-table th,
        .prestasi-table td {
            border: 0.5px solid #d1d5db;
            padding: 2px 3px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .prestasi-table th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #111827;
        }

        .prestasi-table td {
            color: #374151;
        }

        /* Jadwal & Kendali Tables */
        .schedule-table,
        .control-table {
            width: 100%;
            max-width: 180mm;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 8px;
            line-height: 1.2;
            table-layout: fixed;
            box-sizing: border-box;
        }

        .schedule-table th,
        .schedule-table td,
        .control-table th,
        .control-table td {
            border: 0.5px solid #d1d5db;
            padding: 2px 3px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .schedule-table th,
        .control-table th {
            background-color: #f3f4f6;
            font-weight: 600;
            color: #111827;
            text-align: left;
        }

        .schedule-table td,
        .control-table td {
            color: #374151;
        }

        /* Notes Table */
        .notes-table {
            width: 100%;
            max-width: 180mm;
            border-collapse: collapse;
            margin: 8px 0 0 0;
            font-size: 8pt;
            line-height: 1.2;
            box-sizing: border-box;
        }

        .notes-content {
            border: 0.5px solid #9ca3af;
            padding: 5px;
            background-color: #fef3c7;
            color: #374151;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header Kop Surat -->
        <div class="header">
            <table>
                <tr>
                    <td class="header-logo">
                        @if(!empty($printSettings['print_logo_left']))
                            @php
                                $logoLeft = $printSettings['print_logo_left'];
                                // Convert URL ke absolute path
                                if (strpos($logoLeft, 'http') !== false) {
                                    $logoLeft = str_replace(url('/storage/'), '', $logoLeft);
                                    $logoLeft = storage_path('app/public/' . $logoLeft);
                                } elseif (strpos($logoLeft, 'storage/') === 0) {
                                    $logoLeft = str_replace('storage/', '', $logoLeft);
                                    $logoLeft = storage_path('app/public/' . $logoLeft);
                                } else {
                                    $logoLeft = public_path($logoLeft);
                                }
                            @endphp
                            @if(file_exists($logoLeft))
                                <img src="{{ $logoLeft }}" alt="Logo Kiri">
                            @endif
                        @endif
                    </td>
                    <td class="header-content">
                        <h2>{{ $printSettings['print_institution_name'] ?? 'NAMA LEMBAGA' }}</h2>
                        <p class="address">{{ $printSettings['print_institution_address'] ?? 'Alamat Lembaga' }}</p>
                        <p class="contact">
                            @if(!empty($printSettings['print_institution_phone'])) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                            @if(!empty($printSettings['print_institution_email'])) | Email: {{ $printSettings['print_institution_email'] }} @endif
                            @if(!empty($printSettings['print_institution_website'])) | {{ $printSettings['print_institution_website'] }} @endif
                        </p>
                    </td>
                    <td class="header-logo">
                        @if(!empty($printSettings['print_logo_right']))
                            @php
                                $logoRight = $printSettings['print_logo_right'];
                                // Convert URL ke absolute path
                                if (strpos($logoRight, 'http') !== false) {
                                    $logoRight = str_replace(url('/storage/'), '', $logoRight);
                                    $logoRight = storage_path('app/public/' . $logoRight);
                                } elseif (strpos($logoRight, 'storage/') === 0) {
                                    $logoRight = str_replace('storage/', '', $logoRight);
                                    $logoRight = storage_path('app/public/' . $logoRight);
                                } else {
                                    $logoRight = public_path($logoRight);
                                }
                            @endphp
                            @if(file_exists($logoRight))
                                <img src="{{ $logoRight }}" alt="Logo Kanan">
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Title -->
        <div class="title-section">
            <h1>KARTU TES SELEKSI</h1>
            <p>Tahun Pelajaran {{ $printSettings['tahun_pelajaran'] ?? date('Y') . '/' . (date('Y') + 1) }}</p>
        </div>

        <!-- Student Info -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="student-data">
                        <table class="data-table">
                            <tr>
                                <td class="label">No. Pendaftaran</td>
                                <td class="value">: {{ $pendaftar->nomor_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td class="label">Nama Lengkap</td>
                                <td class="value">: {{ $pendaftar->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td class="label">NISN</td>
                                <td class="value">: {{ $pendaftar->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jenis Kelamin</td>
                                <td class="value">: {{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Jalur Pendaftaran</td>
                                <td class="value">: {{ $pendaftar->jalurPendaftaran->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Asal Sekolah</td>
                                <td class="value">: {{ $pendaftar->asal_sekolah }}</td>
                            </tr>
                            <tr>
                                <td class="label">No. WA Orang Tua</td>
                                <td class="value">: {{ $pendaftar->telepon_ortu ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Alamat</td>
                                <td class="value">: {{ $alamatLengkap }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="student-photo">
                        <div class="photo-box">
                            @if($pendaftar->foto)
                                @php
                                    $fotoPath = public_path('storage/' . $pendaftar->foto);
                                @endphp
                                @if(file_exists($fotoPath))
                                    <img src="{{ $fotoPath }}" alt="Foto">
                                @else
                                    <div class="photo-placeholder"><span>Foto 3x4</span></div>
                                @endif
                            @else
                                <div class="photo-placeholder"><span>Foto 3x4</span></div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Prestasi (if exists, max 5) -->
        @if($pendaftar->prestasis && $pendaftar->prestasis->count() > 0)
            @php
                $prestasiTampil = $pendaftar->prestasis->take(5);
            @endphp
            <div class="section-title">DATA PRESTASI (Maksimal 5 Prestasi):</div>
            <table class="prestasi-table">
                <thead>
                    <tr>
                        <th style="width: 4%; text-align: center;">No</th>
                        <th style="width: 6%;">Tahun</th>
                        <th style="width: 24%;">Nama Lomba</th>
                        <th style="width: 10%;">Bidang</th>
                        <th style="width: 22%;">Penyelenggara</th>
                        <th style="width: 12%;">Tingkat</th>
                        <th style="width: 16%;">Peringkat</th>
                        <th style="width: 6%; text-align: center;">Kat.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prestasiTampil as $index => $prestasi)
                        <tr>
                            <td class="text-center font-bold">{{ $index + 1 }}</td>
                            <td>{{ $prestasi->tahun }}</td>
                            <td>{{ $prestasi->nama_lomba }}</td>
                            <td>{{ $prestasi->bidang_lomba }}</td>
                            <td>{{ $prestasi->nama_penyelenggara }}</td>
                            <td>{{ $prestasi->tingkat_lomba }}</td>
                            <td>{{ $prestasi->peringkat }}</td>
                            <td class="text-center">{{ substr($prestasi->kategori_lomba, 0, 1) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Jadwal Tes -->
        <div class="section-title">JADWAL TES:</div>
        @if($jadwalTesList->isNotEmpty())
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama Tes</th>
                        <th style="width: 25%;">Tanggal</th>
                        <th style="width: 20%;">Waktu</th>
                        <th style="width: 25%;">Tempat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwalTesList as $jadwal)
                        <tr>
                            <td class="font-bold">{{ $jadwal->nama_tes }}</td>
                            <td>{{ $jadwal->waktu->format('d/m/Y') }}</td>
                            <td>{{ $jadwal->waktu->format('H:i') }} WIB</td>
                            <td>{{ $jadwal->tempat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="font-size: 7pt; font-style: italic; color: #6b7280; margin-bottom: 0;">Belum ada jadwal tes yang dikonfigurasi</p>
        @endif

        <!-- Kartu Kendali -->
        <div class="section-title">KARTU KENDALI:</div>
        @if($kartuKendaliList->isNotEmpty())
            <table class="control-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">No</th>
                        <th style="width: 40%;">Kegiatan</th>
                        <th style="width: 20%; text-align: center;">Ruang</th>
                        <th style="width: 32%; text-align: center;">Paraf Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kartuKendaliList as $kendali)
                        <tr>
                            <td class="text-center font-bold">{{ $loop->iteration }}</td>
                            <td>{{ $kendali->nama_kegiatan }}</td>
                            <td class="text-center">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="font-size: 7pt; font-style: italic; color: #6b7280; margin-bottom: 0;">Belum ada kartu kendali yang dikonfigurasi</p>
        @endif

        <!-- Catatan dari Kartu Kendali -->
        @if($kartuKendaliList->filter(function($k) { return !empty($k->catatan); })->isNotEmpty())
            <div class="section-title">CATATAN:</div>
            <table class="notes-table">
                <tr>
                    <td class="notes-content">
                        @foreach($kartuKendaliList->filter(function($k) { return !empty($k->catatan); }) as $kendali)
                            <div style="margin-bottom: 2px;">
                                <strong>{{ $kendali->nama_kegiatan }}:</strong> {{ $kendali->catatan }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            </table>
        @endif

        <!-- Catatan Kartu Tes Seleksi dari Admin -->
        @if($catatanKartuTesSeleksi)
            <div class="section-title">CATATAN:</div>
            <table class="notes-table">
                <tr>
                    <td class="notes-content">{{ $catatanKartuTesSeleksi }}</td>
                </tr>
            </table>
        @endif
    </div>
</body>
</html>
