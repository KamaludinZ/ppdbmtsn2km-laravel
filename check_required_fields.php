<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING REQUIRED FIELDS IN PENDAFTAR TABLE ===\n\n";

$columns = DB::select("
    SELECT column_name, data_type, column_default
    FROM information_schema.columns
    WHERE table_name = 'pendaftar'
    AND is_nullable = 'NO'
    AND column_default IS NULL
    ORDER BY ordinal_position
");

echo "Required NOT NULL fields (without defaults):\n\n";

foreach ($columns as $col) {
    echo "- {$col->column_name} ({$col->data_type})\n";
}
