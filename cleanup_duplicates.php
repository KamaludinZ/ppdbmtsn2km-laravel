<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DaftarUlang;
use Illuminate\Support\Facades\DB;

echo "=== CLEANUP DUPLICATE DAFTAR ULANG RECORDS ===\n\n";

// Find duplicates
$duplicates = DB::table('daftar_ulang')
    ->select('pendaftar_id', DB::raw('COUNT(*) as total_count'))
    ->groupBy('pendaftar_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();

echo "Found {$duplicates->count()} pendaftar with duplicate records\n\n";

foreach ($duplicates as $dup) {
    $pendaftarId = $dup->pendaftar_id;
    echo "Pendaftar ID: {$pendaftarId} has {$dup->total_count} records\n";

    // Get all records for this pendaftar, ordered by updated_at DESC (newest first)
    $records = DaftarUlang::where('pendaftar_id', $pendaftarId)
        ->orderBy('updated_at', 'desc')
        ->get();

    // Keep the most recently updated record
    $keepRecord = $records->first();
    echo "  Keeping: ID {$keepRecord->id} (updated: {$keepRecord->updated_at})\n";

    // Merge data from other records if needed
    foreach ($records->skip(1) as $oldRecord) {
        echo "  Checking: ID {$oldRecord->id}\n";

        // If the old record has catatan but the keep record doesn't, copy it
        if (!empty($oldRecord->catatan) && empty($keepRecord->catatan)) {
            echo "    -> Merging catatan from ID {$oldRecord->id}\n";
            $keepRecord->catatan = $oldRecord->catatan;
            $keepRecord->save();
        }

        // Delete old record
        echo "    -> Deleting ID {$oldRecord->id}\n";
        $oldRecord->delete();
    }

    echo "  Cleanup done for pendaftar {$pendaftarId}\n\n";
}

echo "=== CLEANUP COMPLETE ===\n";
echo "Total cleaned: {$duplicates->count()} pendaftar\n";
