<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .info-box p {
            margin: 3px 0;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
            width: 25%;
        }
        .stats-label {
            font-weight: bold;
            color: #555;
        }
        .stats-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #10b981;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #10b981;
            color: white;
        }
        .progress-bar {
            background-color: #e0e0e0;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }
        .progress-fill {
            background-color: #10b981;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header - Kop Surat -->
    <table style="width: 100%; border: none; margin-bottom: 10px;">
        <tr style="border: none; background: none;">
            <!-- Logo Kiri -->
            <td style="width: 15%; border: none; text-align: center; vertical-align: middle;">
                @if($printLogoLeft)
                    <img src="{{ public_path($printLogoLeft) }}" alt="Logo" style="max-width: 80px; max-height: 80px;">
                @endif
            </td>
            <!-- Identitas Tengah -->
            <td style="width: 70%; border: none; text-align: center; vertical-align: middle;">
                <h1 style="margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase;">{{ $printInstitutionName }}</h1>
                <p style="margin: 3px 0; font-size: 11px;">{{ $printInstitutionAddress }}</p>
                <p style="margin: 3px 0; font-size: 10px;">
                    @if($printInstitutionPhone) Telp: {{ $printInstitutionPhone }} @endif
                    @if($printInstitutionEmail) | Email: {{ $printInstitutionEmail }} @endif
                    @if($printInstitutionWebsite) | {{ $printInstitutionWebsite }} @endif
                </p>
            </td>
            <!-- Logo Kanan -->
            <td style="width: 15%; border: none; text-align: center; vertical-align: middle;">
                @if($printLogoRight)
                    <img src="{{ public_path($printLogoRight) }}" alt="Logo" style="max-width: 80px; max-height: 80px;">
                @endif
            </td>
        </tr>
    </table>

    <hr style="border: 2px solid #000; margin: 10px 0;">

    <!-- Document Title -->
    <div style="text-align: center; margin: 20px 0;">
        <h2 style="font-size: 16px; font-weight: bold; margin: 0;">{{ $title }}</h2>
        <p style="margin: 5px 0;">Tahun Pelajaran {{ $periode }}</p>
    </div>

    <!-- Per Jalur Statistics -->
    <div class="section-title">STATISTIK PER JALUR PENDAFTARAN</div>

    <table>
        <thead>
            <tr>
                <th style="width: 2%;">No</th>
                <th style="width: 12%;">Jalur Pendaftaran</th>
                <th style="width: 5%;">Kuota</th>
                <th style="width: 5%;">Total</th>
                <th style="width: 6%;">Menunggu</th>
                <th style="width: 6%;">Lengkapi</th>
                <th style="width: 6%;">Perbaiki</th>
                <th style="width: 5%;">Ditolak</th>
                <th style="width: 6%;">Disetujui</th>
                <th style="width: 5%;">Lulus</th>
                <th style="width: 6%;">Tdk Lulus</th>
                <th style="width: 6%;">Cadangan</th>
                <th style="width: 4%;">L</th>
                <th style="width: 4%;">P</th>
                <th style="width: 4%;">MI</th>
                <th style="width: 4%;">SD</th>
                <th style="width: 5%;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jalurStats as $index => $jalur)
                @php
                    $percentage = $jalur->kuota > 0 ? round(($jalur->pendaftar_count / $jalur->kuota) * 100, 1) : 0;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $jalur->nama }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($jalur->kuota) }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($jalur->pendaftar_count) }}</td>
                    <td style="text-align: center; color: #ca8a04;">{{ number_format($jalur->menunggu_count) }}</td>
                    <td style="text-align: center; color: #ea580c;">{{ number_format($jalur->lengkapi_count) }}</td>
                    <td style="text-align: center; color: #d97706;">{{ number_format($jalur->perbaiki_count) }}</td>
                    <td style="text-align: center; color: #ef4444; font-weight: bold;">{{ number_format($jalur->ditolak_count) }}</td>
                    <td style="text-align: center; color: #10b981; font-weight: bold;">{{ number_format($jalur->disetujui_count) }}</td>
                    <td style="text-align: center; color: #059669; font-weight: bold;">{{ number_format($jalur->lulus_count) }}</td>
                    <td style="text-align: center; color: #f43f5e;">{{ number_format($jalur->tidak_lulus_count) }}</td>
                    <td style="text-align: center; color: #3b82f6;">{{ number_format($jalur->cadangan_count) }}</td>
                    <td style="text-align: center; color: #0891b2;">{{ number_format($jalur->laki_laki_count) }}</td>
                    <td style="text-align: center; color: #db2777;">{{ number_format($jalur->perempuan_count) }}</td>
                    <td style="text-align: center; color: #0d9488;">{{ number_format($jalur->madrasah_count) }}</td>
                    <td style="text-align: center; color: #4f46e5;">{{ number_format($jalur->umum_count) }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $percentage }}%</td>
                </tr>
            @endforeach

            @if($jalurStats->count() === 0)
                <tr>
                    <td colspan="17" style="text-align: center; padding: 20px; color: #999;">
                        Tidak ada data jalur pendaftaran
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #e5e7eb;">
                <td colspan="2" style="text-align: right; padding-right: 10px;">TOTAL</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('kuota')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('pendaftar_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('menunggu_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('lengkapi_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('perbaiki_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('ditolak_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('disetujui_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('lulus_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('tidak_lulus_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('cadangan_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('laki_laki_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('perempuan_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('madrasah_count')) }}</td>
                <td style="text-align: center;">{{ number_format($jalurStats->sum('umum_count')) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
