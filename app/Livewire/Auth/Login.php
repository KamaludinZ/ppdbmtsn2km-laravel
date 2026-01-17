<?php

namespace App\Livewire\Auth;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Login - SPMBM MTsN 2 Kota Malang')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $captcha = '';
    public string $captchaCode = '';

    public function mount()
    {
        // Check if registration is open for login
        $registrationPhase = Setting::get('registration_phase', 'belum_dibuka');

        // If registration is not open, prevent login
        if (!in_array($registrationPhase, ['dibuka', 'proses', 'pengumuman', 'daftar_ulang'])) {
            $this->addError('email', 'Login pendaftar sedang tidak aktif. Silakan cek kembali jadwal pendaftaran.');
        }

        // Generate captcha
        $this->generateCaptcha();
    }

    public function generateCaptcha()
    {
        $this->captchaCode = (string) rand(100000, 999999);
        session(['login_captcha' => $this->captchaCode]);
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
        // Check if registration is open for login
        $registrationPhase = Setting::get('registration_phase', 'belum_dibuka');

        // If registration is not open, prevent login
        if (!in_array($registrationPhase, ['dibuka', 'proses', 'pengumuman', 'daftar_ulang'])) {
            $this->addError('email', 'Login pendaftar sedang tidak aktif. Silakan cek kembali jadwal pendaftaran.');
            return;
        }

        $this->validate();

        // Verify captcha
        if ($this->captcha !== session('login_captcha')) {
            $this->addError('captcha', 'Kode keamanan tidak sesuai');
            $this->captcha = '';
            $this->generateCaptcha();
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password, 'aktif' => true], $this->remember)) {
            session()->regenerate();
            session()->forget('login_captcha');

            $user = Auth::user();

            // Only allow pendaftar role
            if ($user->isPendaftar()) {
                return redirect()->intended('/pendaftar/dashboard');
            }

            // If admin or panitia, logout and show error
            Auth::logout();
            $this->addError('email', 'Akses ditolak. Admin dan Panitia silakan login di halaman khusus.');
            $this->generateCaptcha();
            return;
        }

        $this->addError('email', 'Email atau password salah, atau akun tidak aktif.');
        $this->captcha = '';
        $this->generateCaptcha();
    }

    public function render()
    {
        $registrationPhase = Setting::get('registration_phase', 'belum_dibuka');
        $loginEnabled = in_array($registrationPhase, ['dibuka', 'proses', 'pengumuman', 'daftar_ulang']);

        return view('livewire.auth.login', [
            'loginEnabled' => $loginEnabled
        ]);
    }
}
