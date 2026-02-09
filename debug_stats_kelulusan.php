<?php
/**
 * Debug Script for Stats Kelulusan Issue
 * Run: php debug_stats_kelulusan.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pendaftar;

echo "===========================================\n";
echo "DIAGNOSTIC: Stats Data Disetujui Kelulusan\n";
echo "===========================================\n\n";

// 1. Check total pendaftar
$total = Pendaftar::count();
echo "1. TOTAL PENDAFTAR: {$total}\n\n";

// 2. Group by status_pendaftaran
echo "2. GROUP BY STATUS_PENDAFTARAN:\n";
$statuses = Pendaftar::selectRaw('status_pendaftaran, COUNT(*) as count')
    ->groupBy('status_pendaftaran')
    ->orderBy('count', 'DESC')
    ->get();

foreach ($statuses as $status) {
    echo "   - {$status->status_pendaftaran}: {$status->count}\n";
}

// 3. Current stats query (what we use in component)
echo "\n3. CURRENT STATS QUERY RESULT:\n";
$statsData = Pendaftar::query()
    ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status_pendaftaran = 'data_disetujui' THEN 1 ELSE 0 END) as data_disetujui,
        SUM(CASE WHEN status_pendaftaran IN ('lulus', 'diterima') THEN 1 ELSE 0 END) as lulus,
        SUM(CASE WHEN status_pendaftaran = 'cadangan' THEN 1 ELSE 0 END) as cadangan,
        SUM(CASE WHEN status_pendaftaran IN ('tidak_lulus', 'ditolak', 'data_ditolak') THEN 1 ELSE 0 END) as tidak_lulus
    ")
    ->first();

echo "   - Total: {$statsData->total}\n";
echo "   - Data Disetujui: {$statsData->data_disetujui}\n";
echo "   - Lulus: {$statsData->lulus}\n";
echo "   - Cadangan: {$statsData->cadangan}\n";
echo "   - Tidak Lulus: {$statsData->tidak_lulus}\n";

// 4. Manual count for data_disetujui
echo "\n4. MANUAL COUNT (status = 'data_disetujui'):\n";
$manualCount = Pendaftar::where('status_pendaftaran', 'data_disetujui')->count();
echo "   - Count: {$manualCount}\n";

// 5. Check for potential issues
echo "\n5. POTENTIAL ISSUES:\n";

// Check for null status
$nullCount = Pendaftar::whereNull('status_pendaftaran')->count();
echo "   - NULL status: {$nullCount}\n";

// Check for empty string status
$emptyCount = Pendaftar::where('status_pendaftaran', '')->count();
echo "   - Empty string status: {$emptyCount}\n";

// Check for status with spaces
$withSpaces = Pendaftar::whereRaw("status_pendaftaran LIKE '% %'")->count();
echo "   - Status with leading/trailing spaces: {$withSpaces}\n";

// List first 5 pendaftar with data_disetujui
echo "\n6. SAMPLE DATA (first 5 with data_disetujui):\n";
$samples = Pendaftar::where('status_pendaftaran', 'data_disetujui')
    ->select('id', 'nama_lengkap', 'nomor_pendaftaran', 'status_pendaftaran')
    ->limit(5)
    ->get();

if ($samples->count() > 0) {
    foreach ($samples as $s) {
        echo "   - ID {$s->id}: {$s->nama_lengkap} ({$s->nomor_pendaftaran}) - Status: '{$s->status_pendaftaran}'\n";
    }
} else {
    echo "   - No records found with status 'data_disetujui'\n";
}

// 7. Check enum definition (if applicable)
echo "\n7. CHECKING DATABASE SCHEMA:\n";
try {
    $columns = DB::select("SELECT column_name, data_type, udt_name
                          FROM information_schema.columns
                          WHERE table_name = 'pendaftar'
                          AND column_name = 'status_pendaftaran'");

    if (count($columns) > 0) {
        $col = $columns[0];
        echo "   - Column: {$col->column_name}\n";
        echo "   - Data Type: {$col->data_type}\n";
        echo "   - UDT Name: {$col->udt_name}\n";
    }
} catch (\Exception $e) {
    echo "   - Error checking schema: {$e->getMessage()}\n";
}

echo "\n===========================================\n";
echo "DIAGNOSTIC COMPLETE\n";
echo "===========================================\n";
