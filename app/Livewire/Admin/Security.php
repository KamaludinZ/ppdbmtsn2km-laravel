<?php

namespace App\Livewire\Admin;

use App\Support\StatusKeamananPhp;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Security extends Component
{
    public $loginAttempts = [];
    public $securityLogs = [];
    public $suspiciousActivities = [];
    public $blockedIps = [];
    public $twoFactorEnabled = false;

    public function mount()
    {
        $this->loadSecurityData();
    }

    public function render()
    {
        $statusPhp = StatusKeamananPhp::periksa();

        return view('livewire.admin.security', [
            'statusPhp' => $statusPhp,
            'ringkasanPhp' => StatusKeamananPhp::ringkasan($statusPhp),
            'indikatorPhp' => StatusKeamananPhp::indikatorSerangan(),
            'loginAttempts' => $this->loginAttempts,
            'securityLogs' => $this->securityLogs,
            'suspiciousActivities' => $this->suspiciousActivities,
            'blockedIps' => $this->blockedIps,
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Keamanan Sistem',
            'pageDescription' => 'Pengaturan keamanan dan monitoring ancaman'
        ]);
    }

    public function toggleTwoFactor()
    {
        $user = Auth::user();
        $this->twoFactorEnabled = !$this->twoFactorEnabled;
        $user->two_factor_enabled = $this->twoFactorEnabled;
        $user->save();
        
        $status = $this->twoFactorEnabled ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Autentikasi dua faktor berhasil {$status}.");
    }

    private function loadSecurityData()
    {
        // For demo purposes, we'll simulate some security data
        // In a real application, you would fetch this from your security log tables

        $this->loginAttempts = [
            ['ip' => '127.0.0.1', 'time' => now()->subMinutes(10)->format('d M Y H:i:s'), 'status' => 'success', 'location' => 'Local'],
            ['ip' => '192.168.1.100', 'time' => now()->subHour()->format('d M Y H:i:s'), 'status' => 'failed', 'location' => 'Local'],
            ['ip' => '203.0.113.45', 'time' => now()->subDay()->format('d M Y H:i:s'), 'status' => 'success', 'location' => 'Remote'],
            ['ip' => '198.51.100.23', 'time' => now()->subWeek()->format('d M Y H:i:s'), 'status' => 'blocked', 'location' => 'Remote'],
        ];

        $this->securityLogs = [
            ['action' => 'Login', 'user' => Auth::user()->name, 'time' => now()->subMinutes(15)->format('d M Y H:i:s'), 'ip' => '127.0.0.1'],
            ['action' => 'Password Change', 'user' => Auth::user()->name, 'time' => now()->subDay()->format('d M Y H:i:s'), 'ip' => '127.0.0.1'],
            ['action' => 'Session Started', 'user' => Auth::user()->name, 'time' => now()->subWeek()->format('d M Y H:i:s'), 'ip' => '127.0.0.1'],
        ];

        // Suspicious Activities - Deteksi serangan
        $this->suspiciousActivities = [
            [
                'type' => 'SQL Injection Attempt',
                'severity' => 'high',
                'ip' => '45.142.120.23',
                'time' => now()->subMinutes(5)->format('d M Y H:i:s'),
                'details' => "Detected SQL injection pattern: ' OR 1=1--",
                'url' => '/admin/login',
                'user_agent' => 'sqlmap/1.5',
            ],
            [
                'type' => 'XSS Attempt',
                'severity' => 'medium',
                'ip' => '185.220.101.45',
                'time' => now()->subMinutes(15)->format('d M Y H:i:s'),
                'details' => 'Detected XSS pattern: <script>alert(1)</script>',
                'url' => '/pendaftaran/form',
                'user_agent' => 'Mozilla/5.0',
            ],
            [
                'type' => 'Brute Force Attack',
                'severity' => 'high',
                'ip' => '103.28.114.234',
                'time' => now()->subHour()->format('d M Y H:i:s'),
                'details' => '50 failed login attempts in 5 minutes',
                'url' => '/login/admin',
                'user_agent' => 'Python-requests/2.25.1',
            ],
            [
                'type' => 'Path Traversal',
                'severity' => 'high',
                'ip' => '89.248.165.91',
                'time' => now()->subHours(2)->format('d M Y H:i:s'),
                'details' => 'Detected path traversal: ../../etc/passwd',
                'url' => '/download?file=../../etc/passwd',
                'user_agent' => 'curl/7.68.0',
            ],
            [
                'type' => 'CSRF Token Mismatch',
                'severity' => 'medium',
                'ip' => '192.241.135.67',
                'time' => now()->subHours(3)->format('d M Y H:i:s'),
                'details' => 'Invalid CSRF token detected',
                'url' => '/admin/settings',
                'user_agent' => 'Mozilla/5.0',
            ],
            [
                'type' => 'Directory Scanning',
                'severity' => 'low',
                'ip' => '167.99.45.78',
                'time' => now()->subHours(6)->format('d M Y H:i:s'),
                'details' => 'Multiple 404 requests to admin paths',
                'url' => '/admin, /administrator, /wp-admin',
                'user_agent' => 'Nikto/2.1.6',
            ],
            [
                'type' => 'Command Injection',
                'severity' => 'critical',
                'ip' => '198.251.80.102',
                'time' => now()->subHours(12)->format('d M Y H:i:s'),
                'details' => 'Shell command detected: ;cat /etc/passwd',
                'url' => '/search?q=;cat /etc/passwd',
                'user_agent' => 'Wget/1.20.3',
            ],
        ];

        // Blocked IPs
        $this->blockedIps = [
            [
                'ip' => '45.142.120.23',
                'reason' => 'SQL Injection attempts',
                'blocked_at' => now()->subMinutes(5)->format('d M Y H:i:s'),
                'attempts' => 15,
                'auto_unblock' => now()->addHours(24)->format('d M Y H:i:s'),
            ],
            [
                'ip' => '103.28.114.234',
                'reason' => 'Brute force attack',
                'blocked_at' => now()->subHour()->format('d M Y H:i:s'),
                'attempts' => 50,
                'auto_unblock' => now()->addHours(23)->format('d M Y H:i:s'),
            ],
            [
                'ip' => '198.251.80.102',
                'reason' => 'Command injection',
                'blocked_at' => now()->subHours(12)->format('d M Y H:i:s'),
                'attempts' => 8,
                'auto_unblock' => now()->addHours(12)->format('d M Y H:i:s'),
            ],
            [
                'ip' => '89.248.165.91',
                'reason' => 'Path traversal attacks',
                'blocked_at' => now()->subHours(2)->format('d M Y H:i:s'),
                'attempts' => 12,
                'auto_unblock' => now()->addHours(22)->format('d M Y H:i:s'),
            ],
        ];

        $this->twoFactorEnabled = Auth::user()->two_factor_enabled ?? false;
    }

    public function refreshSecurityData()
    {
        $this->loadSecurityData();
        session()->flash('message', 'Data keamanan berhasil diperbarui.');
    }

    public function unblockIp($ip)
    {
        // In a real application, you would remove the IP from your blocked IPs table
        $this->blockedIps = array_filter($this->blockedIps, function($item) use ($ip) {
            return $item['ip'] !== $ip;
        });

        session()->flash('message', "IP {$ip} berhasil di-unblock.");
    }

    public function clearSuspiciousActivity($index)
    {
        // In a real application, you would mark this activity as reviewed
        unset($this->suspiciousActivities[$index]);
        $this->suspiciousActivities = array_values($this->suspiciousActivities);

        session()->flash('message', 'Aktivitas mencurigakan berhasil dihapus.');
    }
}