<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 3px 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data th {
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table.data td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }
        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
            text-align: center;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">MTS N 2 KM</div>

    <div class="header">
        <h1>MTsN 2 Kota Malang</h1>
        <p>Jl. Raya Sulfat No. 01, Kota Malang, Jawa Timur 65152</p>
        <p>Telp: (0341) 123456 | Email: spmbm@mtsn2kotamalang.sch.id</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="100"><strong>Judul</strong></td>
                <td width="10">:</td>
                <td>{{ $title }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td><strong>Keterangan</strong></td>
                <td>:</td>
                <td>Laporan Data Siswa Terdaftar</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="40">No</th>
                <th>Nama Lengkap</th>
                <th width="100">NISN</th>
                <th width="60">Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td style="text-align: center;">{{ $item['no'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td style="text-align: center;">{{ $item['nisn'] }}</td>
                <td style="text-align: center;">{{ $item['kelas'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Total: {{ count($items) }} siswa</strong></p>

        <div class="signature">
            <p>MTsN 2 Kota Malang, {{ $date }}</p>
            <p style="margin-top: 60px; border-top: 1px solid #333; display: inline-block; padding-top: 5px;">
                <strong>Kepala Sekolah</strong>
            </p>
        </div>
    </div>
</body>
</html>
