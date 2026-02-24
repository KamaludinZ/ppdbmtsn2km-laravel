<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING ENUM VALUES ===\n\n";

$result = DB::select("SELECT unnest(enum_range(NULL::pendaftar_status_pendaftaran_final))::text as value");

echo "Valid status_pendaftaran values:\n";
foreach ($result as $row) {
    echo "- " . $row->value . "\n";
}
