<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== CHECKING USERS ===\n\n";

$users = User::all(['id', 'name', 'email', 'role', 'email_verified_at']);

if ($users->count() === 0) {
    echo "❌ NO USERS FOUND!\n";
    exit;
}

echo "Found {$users->count()} users:\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Verified: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
