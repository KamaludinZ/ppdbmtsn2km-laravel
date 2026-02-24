<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Daftar Ulang Seragam</title>
    <style>
        @page {
            margin: 10mm;
            size: A5 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #000;
        }
        .container {
            width: 100%;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            color: #111;
        }
        .header p {
            margin: 1px 0;
            font-size: 10px;
            color: #333;
        }
        .header .contact {
            font-size: 9px;
            color: #555;
        }
        .title {
            text-align: center;
            margin-bottom: 10px;
        }
        .title h3 {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 2px 0;
            color: #111;
            text-transform: uppercase;
        }
        .title p {
            margin: 1px 0;
            color: #555;
        }
        .title .invoice-no {
            font-size: 10px;
            color: #333;
        }
        .title .date {
            font-size: 9px;
            color: #555;
        }
        .section {
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        .layout-table {
            width: 100%;
            margin-bottom: 10px;
        }
        .layout-table td {
            vertical-align: top;
        }
        .layout-table .left-col {
            width: 50%;
            padding-right: 10px;
        }
        .layout-table .right-col {
            width: 50%;
            padding-left: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table {
            font-size: 10px;
            line-height: 1.4;
        }
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
        }
        .info-table .label {
            width: 32%;
            color: #333;
        }
        .info-table .separator {
            width: 2%;
            text-align: center;
        }
        .info-table .value {
            width: 66%;
            font-weight: bold;
            color: #000;
        }
        .detail-table {
            width: 100%;
            border: 1px solid #ccc;
            font-size: 10px;
        }
        .detail-table th {
            background-color: #f3f4f6;
            padding: 4px 8px;
            text-align: left;
            border: 1px solid #ccc;
            font-weight: bold;
            color: #111;
        }
        .detail-table td {
            padding: 4px 8px;
            border: 1px solid #ccc;
            color: #000;
        }
        .col-keterangan { width: 75%; }
        .col-jumlah { width: 25%; }
        .text-right {
            text-align: right;
        }
        .bg-gray {
            background-color: #f9fafb;
        }
        .size-box {
            background-color: #f9fafb;
            border: 1px solid #ccc;
            padding: 5px;
            margin-bottom: 10px;
        }
        .size-table {
            font-size: 8px;
            line-height: 1.3;
            width: 100%;
        }
        .size-table td {
            padding: 2px 3px;
            vertical-align: top;
        }
        .size-table .size-label {
            width: 23%;
            color: #333;
            font-size: 8px;
        }
        .size-table .size-value {
            width: 27%;
            font-weight: bold;
            color: #000;
            font-size: 8px;
        }
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>{{ $printSettings['print_institution_name'] ?? 'NAMA LEMBAGA' }}</h2>
            <p>{{ $printSettings['print_institution_address'] ?? 'Alamat Lembaga' }}</p>
            <p class="contact">
                @if(!empty($printSettings['print_institution_phone'])) Telp: {{ $printSettings['print_institution_phone'] }} @endif
                @if(!empty($printSettings['print_institution_email'])) | Email: {{ $printSettings['print_institution_email'] }} @endif
            </p>
        </div>

        <!-- Title -->
        <div class="title">
            <h3>INVOICE DAFTAR ULANG SERAGAM</h3>
            <p class="invoice-no">No: {{ str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), str_pad($seragam->id, 3, '0', STR_PAD_LEFT)], $invoiceSettings['nomor_format'] ?? 'INV-SRG-{TAHUN}-{NOMOR}') }}</p>
            <p class="date">Tanggal: {{ now()->format('d/m/Y') }}</p>
        </div>

        <!-- Info Section (Side by Side) -->
        <table class="layout-table">
            <tr>
                <td class="left-col">
                    <div class="section-title">Informasi Pendaftar:</div>
                    <table class="info-table">
                        <tr>
                            <td class="label">No. Pendaftaran</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->nomor_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nama</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="label">NISN</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jenis Kelamin</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jalur Pendaftaran</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->jalurPendaftaran->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Asal Sekolah</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $pendaftar->asal_sekolah }}</td>
                        </tr>
                    </table>
                </td>
                <td class="right-col">
                    <div class="section-title">Informasi Pembayaran:</div>
                    <table class="info-table">
                        <tr>
                            <td class="label" style="width: 30%;">Bank Dari</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $bank['name'] ?? 'Bank' }} {{ isset($bank['account_number']) ? '- ' . $bank['account_number'] : '' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Nama Pengirim</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $seragam->nama_pengirim_seragam ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Waktu Transfer</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $seragam->updated_at ? $seragam->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Status</td>
                            <td class="separator">:</td>
                            <td class="value" style="color: {{ $seragam->status_seragam == 'disetujui' ? 'green' : ($seragam->status_seragam == 'ditolak' ? 'red' : 'orange') }}; text-transform: uppercase;">
                                @if($seragam->status_seragam == 'disetujui')
                                    LUNAS / DISETUJUI
                                @elseif($seragam->status_seragam == 'ditolak')
                                    DITOLAK
                                @else
                                    MENUNGGU VERIFIKASI
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Opsi Pembayaran</td>
                            <td class="separator">:</td>
                            <td class="value" style="text-transform: uppercase;">
                                {{ $seragam->opsi_pembayaran_seragam ? strtoupper($seragam->opsi_pembayaran_seragam) : 'LUNAS' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Uniform Size Info -->
        <div class="section">
            <div class="section-title">Ukuran Seragam & Atribut:</div>
            <div class="size-box">
                <table class="size-table">
                    <tr>
                        <td class="size-label">Jas</td>
                        <td class="size-value">: {{ $seragam->ukuran_jas ?? '-' }}</td>
                        <td class="size-label">Baju Olahraga</td>
                        <td class="size-value">: {{ $seragam->ukuran_baju_olahraga ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="size-label">Celana Olahraga</td>
                        <td class="size-value">: {{ $seragam->ukuran_celana_olahraga ?? '-' }}</td>
                        <td class="size-label">Baju Harian</td>
                        <td class="size-value">: {{ $seragam->ukuran_baju_harian ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="size-label">Celana/Rok Harian</td>
                        <td class="size-value">: {{ $seragam->ukuran_celana_rok_harian ?? '-' }}</td>
                        <td class="size-label">Kopyah</td>
                        <td class="size-value">: {{ $seragam->ukuran_kopyah ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Biaya -->
        <div class="section">
            <div class="section-title">Detail Biaya:</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Keterangan</th>
                        <th class="text-right" style="width: 30%;">Nominal Biaya</th>
                        <th class="text-right" style="width: 30%;">Konfirmasi Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Nominal Seragam Awal (yang dipilih di "Pilih Paket Seragam")
                        $nominalSeragamAwal = $seragam->nominal_seragam_awal ?? [];

                        // Deteksi apakah multi-select (array) atau single (object)
                        $isMultiSelect = is_array($nominalSeragamAwal) && isset($nominalSeragamAwal[0]);

                        // Hitung total nominal awal
                        if ($isMultiSelect) {
                            $jumlahAwal = 0;
                            foreach ($nominalSeragamAwal as $item) {
                                $jumlahAwal += $item['amount'] ?? 0;
                            }
                        } else {
                            $jumlahAwal = $nominalSeragamAwal['amount'] ?? 0;
                        }

                        // Nominal Seragam Konfirmasi (yang dipilih di "Konfirmasi Nominal yang Dibayarkan")
                        $nominalSeragamKonfirmasi = $seragam->nominal_seragam ?? [];
                        $jumlahKonfirmasi = $nominalSeragamKonfirmasi['amount'] ?? 0;

                        // Hitung selisih
                        $selisih = $jumlahKonfirmasi - $jumlahAwal;
                        $statusPembayaran = '';
                        if ($selisih == 0) {
                            $statusPembayaran = 'LUNAS';
                        } elseif ($selisih > 0) {
                            $statusPembayaran = 'KEMBALIAN: Rp ' . number_format($selisih, 0, ',', '.');
                        } else {
                            $statusPembayaran = 'KEKURANGAN: Rp ' . number_format(abs($selisih), 0, ',', '.');
                        }
                    @endphp

                    @if($isMultiSelect)
                        {{-- MULTI SELECT MODE: Tampilkan semua item dengan baris terpisah --}}
                        @foreach($nominalSeragamAwal as $idx => $item)
                            <tr>
                                <td>{{ ($idx + 1) }}. {{ $item['name'] ?? 'Biaya Seragam & Atribut' }}</td>
                                <td class="text-right">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    @if($idx == 0)
                                        {{-- Tampilkan total konfirmasi di baris pertama --}}
                                        <strong>Rp {{ number_format($jumlahKonfirmasi, 0, ',', '.') }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        {{-- Total Row --}}
                        <tr style="background-color: #e5e7eb; font-weight: bold;">
                            <td>TOTAL</td>
                            <td class="text-right">Rp {{ number_format($jumlahAwal, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($jumlahKonfirmasi, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        {{-- SINGLE SELECT MODE: Tampilan seperti sebelumnya --}}
                        <tr>
                            <td>{{ $nominalSeragamAwal['name'] ?? 'Biaya Seragam & Atribut' }}</td>
                            <td class="text-right">Rp {{ number_format($jumlahAwal, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($jumlahKonfirmasi, 0, ',', '.') }}</td>
                        </tr>
                    @endif

                    <tr class="bg-gray">
                        <td style="font-weight: bold;">Status Pembayaran</td>
                        <td colspan="2" class="text-right" style="font-weight: bold;">{{ $statusPembayaran }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            {{ !empty($invoiceSettings['footer']) ? $invoiceSettings['footer'] : 'Terima kasih atas kepercayaan Anda' }}
        </div>
    </div>
</body>
</html>
