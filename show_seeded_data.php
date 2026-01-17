<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Pendaftar;
use App\Models\JalurPendaftaran;
use App\Models\Panitia;
use App\Models\Setting;
use App\Models\Fasilitas;
use App\Models\TimelinePendaftaran;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║              RINGKASAN DATA SEEDER - PPDB MTsN 2 KOTA MALANG             ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

// Count all data
$totalUsers = User::count();
$totalPendaftar = Pendaftar::count();
$totalPanitia = Panitia::count();
$totalJalur = JalurPendaftaran::count();
$totalSettings = Setting::count();
$totalFasilitas = Fasilitas::count();
$totalTimeline = TimelinePendaftaran::count();

echo "┌─────────────────────────────────────────────────────────────────────────┐\n";
echo "│ STATISTIK DATA                                                          │\n";
echo "└─────────────────────────────────────────────────────────────────────────┘\n";
echo sprintf("  %-30s : %d\n", "Total Users", $totalUsers);
echo sprintf("  %-30s : %d\n", "Total Pendaftar", $totalPendaftar);
echo sprintf("  %-30s : %d\n", "Total Panitia", $totalPanitia);
echo sprintf("  %-30s : %d\n", "Jalur Pendaftaran", $totalJalur);
echo sprintf("  %-30s : %d\n", "Settings", $totalSettings);
echo sprintf("  %-30s : %d\n", "Fasilitas", $totalFasilitas);
echo sprintf("  %-30s : %d\n", "Timeline Pendaftaran", $totalTimeline);

echo "\n┌─────────────────────────────────────────────────────────────────────────┐\n";
echo "│ USERS BY ROLE                                                           │\n";
echo "└─────────────────────────────────────────────────────────────────────────┘\n";
$usersByRole = User::select('role', DB::raw('count(*) as total'))
    ->groupBy('role')
    ->get();
foreach ($usersByRole as $role) {
    $roleName = ucwords(str_replace('_', ' ', $role->role));
    echo sprintf("  %-30s : %d users\n", $roleName, $role->total);
}

echo "\n┌─────────────────────────────────────────────────────────────────────────┐\n";
echo "│ JALUR PENDAFTARAN                                                       │\n";
echo "└─────────────────────────────────────────────────────────────────────────┘\n";
$jalurList = JalurPendaftaran::all();
foreach ($jalurList as $jalur) {
    $jumlahPendaftar = Pendaftar::where('jalur_pendaftaran_id', $jalur->id)->count();
    echo sprintf("  %-40s : %d pendaftar\n", $jalur->nama, $jumlahPendaftar);
}

echo "\n┌─────────────────────────────────────────────────────────────────────────┐\n";
echo "│ PENDAFTAR BY STATUS                                                     │\n";
echo "└─────────────────────────────────────────────────────────────────────────┘\n";
$pendaftarByStatus = Pendaftar::select('status_pendaftaran', DB::raw('count(*) as total'))
    ->groupBy('status_pendaftaran')
    ->get();
foreach ($pendaftarByStatus as $status) {
    $statusName = ucwords(str_replace('_', ' ', $status->status_pendaftaran));
    echo sprintf("  %-30s : %d pendaftar\n", $statusName, $status->total);
}

echo "\n┌─────────────────────────────────────────────────────────────────────────┐\n";
echo "│ SAMPLE PENDAFTAR DATA                                                   │\n";
echo "└─────────────────────────────────────────────────────────────────────────┘\n";
$samplePendaftar = Pendaftar::with('jalurPendaftaran')->take(5)->get();
foreach ($samplePendaftar as $p) {
    echo sprintf("  %s\n", $p->nama_lengkap);
    echo sprintf("    Nomor Pendaftaran : %s\n", $p->nomor_pendaftaran);
    echo sprintf("    NISN              : %s\n", $p->nisn);
    echo sprintf("    Jalur             : %s\n", $p->jalurPendaftaran->nama ?? '-');
    echo sprintf("    Status            : %s\n", ucwords($p->status_pendaftaran));
    echo "    " . str_repeat('-', 70) . "\n";
}

echo "\n╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║ LOGIN CREDENTIALS                                                          ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "ADMIN:\n";
echo "  Email    : admin@mtsn2kotamalang.sch.id\n";
echo "  Password : password\n\n";

echo "SUPER ADMIN:\n";
echo "  Email    : superadmin@mtsn2kotamalang.sch.id\n";
echo "  Password : password\n\n";

echo "PANITIA PENDAFTARAN:\n";
echo "  Email    : panitia1@mtsn2kotamalang.sch.id\n";
echo "  Password : password\n\n";

echo "PANITIA DAFTAR ULANG:\n";
echo "  Email    : daftarulang1@mtsn2kotamalang.sch.id\n";
echo "  Password : password\n\n";

echo "PENDAFTAR:\n";
echo "  Email    : ahmad.maulana@example.com\n";
echo "  Password : password\n\n";

echo "╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║ NOTE: Semua password default adalah 'password'                            ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";
