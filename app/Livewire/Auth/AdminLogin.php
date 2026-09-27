<?php

namespace App\Livewire\Auth;

use App\Support\Captcha;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Login Admin/Panitia - SPMBM MTsN 2 Kota Malang')]
class AdminLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $captcha = '';
    // Hanya penanda versi gambar captcha; kodenya tidak pernah dikirim ke browser
    public string $captchaVersion = '';

    public function mount()
    {
        // Generate captcha
        $this->generateCaptcha();
    }

    public function generateCaptcha()
    {
        // Kode baru dibuat saat gambar dimuat (route captcha.image)
        $this->captchaVersion = Str::random(8);
    }

    public function refreshCaptcha()
    {
        $this->generateCaptcha();
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'captcha' => 'required|size:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'captcha.required' => 'Kode keamanan wajib diisi',
            'captcha.size' => 'Kode keamanan harus 6 digit',
        ];
    }

    public function login()
    {
        $this->validate();

        // Batasi percobaan login (cegah brute-force): 5x per menit per email+IP
        $throttleKey = 'admin-login:' . Str::lower($this->email) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.");
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        // Verify captcha (sekali pakai)
        if (!Captcha::verify('admin_login_captcha', $this->captcha)) {
            $this->addError('captcha', 'Kode keamanan tidak sesuai');
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }

        // Attempt login without aktif condition first
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->aktif) {
                Auth::logout();
                $this->addError('email', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
                $this->captcha = '';
                $this->generateCaptcha();
                return;
            }

            // Check if user has admin or panitia role
            if (!$user->isAdmin() && !$user->isPanitia()) {
                Auth::logout();
                $this->addError('email', 'Akses ditolak. Halaman ini khusus untuk Admin dan Panitia.');
                $this->captcha = '';
                $this->generateCaptcha();
                return;
            }

            RateLimiter::clear($throttleKey);
            session()->regenerate();

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'panitia_pendaftaran') {
                return redirect()->intended('/panitia-pendaftaran/dashboard');
            } elseif ($user->role === 'panitia_daftarulang') {
                return redirect()->intended('/panitia-daftarulang/dashboard');
            } elseif ($user->role === 'panitia_tes_psikologi') {
                return redirect()->intended('/panitia-psikologi/dashboard');
            } elseif ($user->role === 'panitia_seragam') {
                return redirect()->intended('/panitia-seragam/dashboard');
            }

            // Fallback - should not reach here
            Auth::logout();
            $this->addError('email', 'Akses ditolak. Halaman ini khusus untuk Admin dan Panitia.');
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }

        $this->addError('email', 'Email atau password salah.');
        $this->captcha = '';
        $this->generateCaptcha();
    }

    public function render()
    {
        return view('livewire.auth.admin-login');
    }
}
