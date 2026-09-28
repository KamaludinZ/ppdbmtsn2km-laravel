<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Daftar Ulang Madrasah</title>
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
            <h3>INVOICE DAFTAR ULANG MADRASAH</h3>
            <p class="invoice-no">No: {{ str_replace(['{TAHUN}', '{NOMOR}'], [date('Y'), str_pad($daftarUlang?->id, 3, '0', STR_PAD_LEFT)], $invoiceSettings['nomor_format'] ?? 'INV-MDR-{TAHUN}-{NOMOR}') }}</p>
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
                            <td class="value">{{ $daftarUlang?->nama_pengirim ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Waktu Transfer</td>
                            <td class="separator">:</td>
                            <td class="value">{{ $daftarUlang?->updated_at ? $daftarUlang?->updated_at->format('d/m/Y H:i') . ' WIB' : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="width: 30%;">Status</td>
                            <td class="separator">:</td>
                            <td class="value" style="color: {{ in_array($daftarUlang?->status, ['disetujui', 'selesai']) || $daftarUlang?->pembayaran_lunas ? 'green' : ($daftarUlang?->status == 'ditolak' ? 'red' : 'orange') }}; text-transform: uppercase;">
                                @if($daftarUlang?->pembayaran_lunas || in_array($daftarUlang?->status, ['disetujui', 'selesai']))
                                    LUNAS / DISETUJUI
                                @elseif($daftarUlang?->status == 'ditolak')
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
                                {{ $daftarUlang?->opsi_pembayaran ? strtoupper($daftarUlang?->opsi_pembayaran) : 'LUNAS' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

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
                        // Nominal Biaya Awal (yang dipilih di "Pilih Nominal Biaya")
                        $nominalBiayaAwal = $daftarUlang?->nominal_biaya_awal ?? [];
                        $keterangan = $nominalBiayaAwal['name'] ?? 'Biaya Daftar Ulang Madrasah';
                        $jumlahAwal = \App\Support\Nominal::toNumber($nominalBiayaAwal['amount'] ?? 0);

                        // Nominal Biaya Konfirmasi (yang dipilih di "Konfirmasi Nominal yang Dibayarkan")
                        $nominalBiayaKonfirmasi = $daftarUlang?->nominal_biaya ?? [];
                        $jumlahKonfirmasi = \App\Support\Nominal::toNumber($nominalBiayaKonfirmasi['amount'] ?? 0);

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
                    <tr>
                        <td>{{ $keterangan }}</td>
                        <td class="text-right">Rp {{ number_format($jumlahAwal, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($jumlahKonfirmasi, 0, ',', '.') }}</td>
                    </tr>
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
