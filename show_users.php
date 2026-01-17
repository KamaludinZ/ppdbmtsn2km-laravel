<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "\n╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║                      DAFTAR USER UNTUK LOGIN                              ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

$users = User::orderBy('role')->orderBy('name')->get();

$currentRole = null;
foreach ($users as $user) {
    if ($currentRole !== $user->role) {
        $currentRole = $user->role;
        $roleTitle = strtoupper(str_replace('_', ' ', $currentRole));
        echo "\n┌─────────────────────────────────────────────────────────────────────────┐\n";
        echo "│ ROLE: {$roleTitle}\n";
        echo "└─────────────────────────────────────────────────────────────────────────┘\n";
    }

    $aktifStatus = $user->aktif ? '✓ Aktif' : '✗ Tidak Aktif';
    echo sprintf(
        "  %-30s │ %-40s │ %s\n",
        $user->name,
        $user->email,
        $aktifStatus
    );
}

echo "\n╔═══════════════════════════════════════════════════════════════════════════╗\n";
echo "║ DEFAULT PASSWORD: password                                                 ║\n";
echo "╚═══════════════════════════════════════════════════════════════════════════╝\n\n";

echo "CONTOH LOGIN:\n";
echo "  Admin         : admin@mtsn2kotamalang.sch.id / password\n";
echo "  Panitia       : panitia1@mtsn2kotamalang.sch.id / password\n";
echo "  Pendaftar     : ahmad.maulana@example.com / password\n\n";

echo "Total Users: " . $users->count() . "\n\n";
