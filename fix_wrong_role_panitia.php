<?php
/**
 * Fix Wrong Role Data - Convert 'pendaftar' to Correct Panitia Role
 *
 * Script untuk memperbaiki data panitia yang tersimpan dengan role 'pendaftar'
 * Jalankan di production: php fix_wrong_role_panitia.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Pendaftar;
use Carbon\Carbon;

echo "================================================\n";
echo "🔧 Fix Wrong Role Data - Panitia\n";
echo "================================================\n\n";

// Find users with role 'pendaftar' but NO pendaftar profile (these are wrongly saved panitia)
$wrongUsers = User::where('role', 'pendaftar')
    ->doesntHave('pendaftar')
    ->orderBy('created_at', 'desc')
    ->get();

if ($wrongUsers->isEmpty()) {
    echo "✅ No wrong role data found. All good!\n\n";
    exit(0);
}

echo "⚠️  Found {$wrongUsers->count()} users with wrong role:\n";
echo "================================================\n\n";

foreach ($wrongUsers as $user) {
    $created = Carbon::parse($user->created_at)->format('d-m-Y H:i:s');
    echo "📋 User Details:\n";
    echo "   ID: {$user->id}\n";
    echo "   Name: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Current Role: {$user->role}\n";
    echo "   Created: {$created}\n";
    echo "\n";
}

echo "================================================\n";
echo "🔍 What should we do?\n";
echo "================================================\n\n";

echo "These users have role 'pendaftar' but NO pendaftar profile.\n";
echo "They are likely panitia accounts that were saved incorrectly.\n\n";

echo "Options:\n";
echo "1. Convert to panitia_pendaftaran (default panitia role)\n";
echo "2. Convert to panitia_daftarulang\n";
echo "3. Convert to panitia_tes_psikologi\n";
echo "4. Convert to panitia_seragam\n";
echo "5. Convert to admin\n";
echo "6. Skip - do nothing\n\n";

// Interactive mode
echo "Enter option (1-6) [default: 1]: ";
$option = trim(fgets(STDIN));
if (empty($option)) {
    $option = '1';
}

$roleMap = [
    '1' => 'panitia_pendaftaran',
    '2' => 'panitia_daftarulang',
    '3' => 'panitia_tes_psikologi',
    '4' => 'panitia_seragam',
    '5' => 'admin',
    '6' => null,
];

if (!isset($roleMap[$option])) {
    echo "❌ Invalid option. Exiting...\n";
    exit(1);
}

$newRole = $roleMap[$option];

if ($newRole === null) {
    echo "⏭️  Skipped. No changes made.\n";
    exit(0);
}

echo "\n================================================\n";
echo "🚀 Converting to: {$newRole}\n";
echo "================================================\n\n";

$fixed = 0;
foreach ($wrongUsers as $user) {
    echo "Fixing: {$user->name} ({$user->email})... ";

    $user->role = $newRole;
    $user->aktif = true; // Ensure aktif is set
    $user->save();

    echo "✅ Done!\n";
    $fixed++;
}

echo "\n================================================\n";
echo "✅ Fix Complete!\n";
echo "================================================\n\n";

echo "📊 Summary:\n";
echo "   Fixed: {$fixed} users\n";
echo "   New Role: {$newRole}\n\n";

echo "🔍 Verification:\n";
$verification = User::where('id', '>=', $wrongUsers->first()->id)
    ->where('role', $newRole)
    ->get(['id', 'name', 'email', 'role']);

foreach ($verification as $v) {
    echo "   ✅ {$v->name} ({$v->email}) - Role: {$v->role}\n";
}

echo "\n✅ All users have been fixed!\n";
echo "Please check in admin panel: /admin/panitia\n\n";
