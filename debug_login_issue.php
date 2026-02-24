<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Session;

echo "=== DEBUG LOGIN ISSUE ===\n\n";

echo "1. Checking Session Configuration:\n";
echo "   Session Driver: " . config('session.driver') . "\n";
echo "   Session Lifetime: " . config('session.lifetime') . " minutes\n";
echo "   Session Path: " . config('session.path') . "\n\n";

echo "2. Testing Session Storage:\n";
// Test session
Session::put('test_key', 'test_value');
$testValue = Session::get('test_key');
if ($testValue === 'test_value') {
    echo "   ✅ Session working correctly\n\n";
} else {
    echo "   ❌ Session NOT working!\n";
    echo "   This is likely your problem.\n\n";
}

echo "3. Simulating Captcha Flow:\n";
// Simulate captcha generation
$captchaCode = (string) rand(100000, 999999);
Session::put('admin_login_captcha', $captchaCode);
echo "   Generated Captcha: $captchaCode\n";
echo "   Stored in Session: " . Session::get('admin_login_captcha') . "\n";

// Simulate user input
$userInput = $captchaCode; // Correct input
if ($userInput === Session::get('admin_login_captcha')) {
    echo "   ✅ Captcha validation would PASS\n\n";
} else {
    echo "   ❌ Captcha validation would FAIL\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "KEMUNGKINAN MASALAH:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Jika Anda yakin email, password, dan captcha sudah benar,\n";
echo "masalahnya kemungkinan:\n\n";

echo "1. CAPTCHA TIDAK TERSINKRONISASI\n";
echo "   - Session tidak terbawa dari satu request ke request lain\n";
echo "   - Browser memblokir cookies\n";
echo "   - CORS atau SameSite cookie issue\n\n";

echo "2. LIVEWIRE SESSION ISSUE\n";
echo "   - Livewire tidak mengirim data dengan benar\n";
echo "   - CSRF token tidak valid\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "SOLUSI SEMENTARA - DISABLE CAPTCHA UNTUK TEST:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "Saya akan membuat versi login tanpa captcha untuk testing.\n";
echo "File: app/Livewire/Auth/AdminLoginNoCaptcha.php\n\n";
