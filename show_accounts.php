<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Create Laravel application instance
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get all users from the database
$users = DB::table('users')->select('name', 'email', 'role', 'aktif')->get();

echo "=== Available Accounts ===\n\n";

$accountsByRole = [];
foreach ($users as $user) {
    $role = $user->role;
    if (!isset($accountsByRole[$role])) {
        $accountsByRole[$role] = [];
    }
    $accountsByRole[$role][] = [
        'name' => $user->name,
        'email' => $user->email,
        'aktif' => $user->aktif ? 'Active' : 'Inactive'
    ];
}

foreach ($accountsByRole as $role => $accounts) {
    echo strtoupper(str_replace('_', ' ', $role)) . " ACCOUNTS:\n";
    echo str_repeat("-", strlen(strtoupper(str_replace('_', ' ', $role)) . " ACCOUNTS:") + 1) . "\n";
    
    foreach ($accounts as $account) {
        echo "- Name: {$account['name']}\n";
        echo "  Email: {$account['email']}\n";
        echo "  Status: {$account['aktif']}\n";
        echo "  Password: password\n"; // Assuming all accounts use 'password'
        echo "\n";
    }
    echo "\n";
}

echo "=== Account Summary ===\n";
foreach ($accountsByRole as $role => $accounts) {
    $activeCount = count(array_filter($accounts, fn($acc) => strpos($acc['aktif'], 'Active') !== false));
    echo sprintf("%s: %d account(s) (%d active)\n", 
        strtoupper(str_replace('_', ' ', $role)), 
        count($accounts), 
        $activeCount
    );
}