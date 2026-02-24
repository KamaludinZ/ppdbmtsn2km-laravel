<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "=== SERAGAM TABLE COLUMNS ===\n\n";

$columns = Schema::getColumns('seragam');

foreach ($columns as $col) {
    echo $col['name'] . ': ' . $col['type'] . "\n";
}
