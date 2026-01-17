<?php
require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Faq;

// Create Laravel application instance
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Check if table exists
    $tableExists = DB::getSchemaBuilder()->hasTable('faqs');
    echo "Table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    
    if ($tableExists) {
        // Try to count records
        $count = Faq::count();
        echo "Record count: " . $count . "\n";
    } else {
        echo "Table does not exist\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}