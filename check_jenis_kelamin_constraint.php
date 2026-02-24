<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING JENIS_KELAMIN CONSTRAINT ===\n\n";

$result = DB::select("
    SELECT con.conname AS constraint_name,
           pg_get_constraintdef(con.oid) AS constraint_def
    FROM pg_constraint con
    INNER JOIN pg_class rel ON rel.oid = con.conrelid
    WHERE rel.relname = 'pendaftar' AND con.conname LIKE '%jenis_kelamin%'
");

foreach ($result as $row) {
    echo "Constraint: {$row->constraint_name}\n";
    echo "Definition: {$row->constraint_def}\n\n";
}
