<?php

use Illuminate\Support\Facades\Route;
use App\Models\JalurPendaftaran;

// Test route untuk debug jalur yang muncul di /daftar
Route::get('/test-jalur-daftar', function () {
    $jalurPendaftaran = JalurPendaftaran::tampilDiDaftar()->ordered()->get();

    $html = '<!DOCTYPE html>
<html>
<head>
    <title>Test Jalur Daftar</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; border-bottom: 3px solid #16a34a; padding-bottom: 10px; }
        h2 { color: #2563eb; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #16a34a; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .info-box { background: #dbeafe; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .success-box { background: #dcfce7; border-left: 4px solid #16a34a; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .query-box { background: #f3f4f6; padding: 15px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
        .count { font-size: 24px; font-weight: bold; color: #16a34a; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Query Jalur yang Muncul di /daftar</h1>

        <div class="info-box">
            <strong>📍 URL Test:</strong> ' . url('/test-jalur-daftar') . '<br>
            <strong>⏰ Waktu Test:</strong> ' . now()->format('d/m/Y H:i:s') . '<br>
            <strong>🔍 Query yang Digunakan:</strong>
            <div class="query-box">JalurPendaftaran::tampilDiDaftar()->ordered()->get()</div>
        </div>

        <div class="success-box">
            <strong>Jumlah Jalur yang MUNCUL di /daftar:</strong>
            <span class="count">' . $jalurPendaftaran->count() . '</span> jalur
        </div>

        <h2>Daftar Jalur yang AKAN MUNCUL di /daftar</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jalur</th>
                    <th>Status Aktif</th>
                    <th>Status Daftar</th>
                    <th>Paling Populer</th>
                    <th>Urutan</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($jalurPendaftaran as $jalur) {
        $html .= '<tr>
            <td>' . $jalur->id . '</td>
            <td><strong>' . e($jalur->nama) . '</strong></td>
            <td>' . ($jalur->aktif
                ? '<span class="badge badge-success">✅ Aktif</span>'
                : '<span class="badge badge-danger">❌ Nonaktif</span>') . '</td>
            <td><span class="badge badge-info">✅ Tampil</span></td>
            <td>' . ($jalur->paling_populer
                ? '<span class="badge badge-warning">⭐ Ya</span>'
                : '<span class="badge">Tidak</span>') . '</td>
            <td>' . $jalur->urutan . '</td>
        </tr>';
    }

    $html .= '</tbody>
        </table>

        <h2>Jalur yang TIDAK AKAN MUNCUL di /daftar</h2>';

    $jalurTersembunyi = JalurPendaftaran::where('tampil_di_daftar', false)->orderBy('urutan')->get();

    if ($jalurTersembunyi->count() > 0) {
        $html .= '<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jalur</th>
                    <th>Status Aktif</th>
                    <th>Status Daftar</th>
                    <th>Urutan</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($jalurTersembunyi as $jalur) {
            $html .= '<tr>
                <td>' . $jalur->id . '</td>
                <td><strong>' . e($jalur->nama) . '</strong></td>
                <td>' . ($jalur->aktif
                    ? '<span class="badge badge-success">✅ Aktif</span>'
                    : '<span class="badge badge-danger">❌ Nonaktif</span>') . '</td>
                <td><span class="badge badge-danger">❌ Tersembunyi</span></td>
                <td>' . $jalur->urutan . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';
    } else {
        $html .= '<p style="color: #16a34a;">✅ Tidak ada jalur yang disembunyikan</p>';
    }

    $html .= '
        <div class="info-box" style="margin-top: 40px;">
            <strong>💡 Cara Menggunakan:</strong>
            <ol>
                <li>Buka <a href="' . url('/admin/jalur') . '" target="_blank">/admin/jalur</a> di tab baru</li>
                <li>Ubah status "Tampil di Halaman Daftar" pada jalur yang ingin Anda hide/show</li>
                <li>Kembali ke halaman ini dan <strong>refresh (F5)</strong></li>
                <li>Verifikasi perubahan sudah muncul di tabel di atas</li>
                <li>Buka <a href="' . url('/daftar') . '" target="_blank">/daftar</a> dengan <strong>Ctrl+Shift+R</strong> (hard refresh)</li>
            </ol>
        </div>

        <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 4px;">
            <strong>⚠️ Catatan:</strong> Jika jalur masih tidak berubah di /daftar setelah hard refresh,
            kemungkinan ada masalah dengan Livewire cache. Jalankan:
            <code style="background: white; padding: 2px 8px; border-radius: 3px;">php artisan optimize:clear</code>
        </div>
    </div>
</body>
</html>';

    return $html;
});
