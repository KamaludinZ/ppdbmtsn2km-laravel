<?php

namespace App\Livewire\Public;

use App\Models\Pendaftar;
use App\Models\Setting;
use App\Support\StatusPendaftaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Cek status pendaftaran tanpa login.
 * - Halaman penuh: /cek-pendaftaran
 * - Versi ringkas di beranda: <livewire:public.cek-pendaftaran :embedded="true" />
 */
class CekPendaftaran extends Component
{
    public $searchQuery = '';

    public string $errorMessage = '';

    #[Locked]
    public bool $embedded = false;

    // Hanya ID yang disimpan di state Livewire (tidak bisa diubah dari browser)
    #[Locked]
    public ?int $resultId = null;

    public function mount(bool $embedded = false)
    {
        $this->embedded = $embedded;
    }

    public function search()
    {
        $this->resultId = null;
        $this->errorMessage = '';
        $this->resetErrorBag();

        $kode = StatusPendaftaran::normalisasi($this->searchQuery);
        if ($kode === null) {
            $this->addError('searchQuery', trim((string) $this->searchQuery) === ''
                ? 'Nomor pendaftaran atau NISN harus diisi.'
                : 'Format tidak valid. Masukkan nomor pendaftaran atau NISN (minimal 3 karakter, huruf/angka).');
            return;
        }

        // Batasi percobaan per IP agar NISN tidak bisa ditebak massal
        $throttleKey = 'cek-status:' . request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, StatusPendaftaran::MAX_PERCOBAAN_PER_MENIT)) {
            $this->errorMessage = 'Terlalu banyak pencarian. Coba lagi dalam ' . RateLimiter::availableIn($throttleKey) . ' detik.';
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        try {
            $pendaftar = StatusPendaftaran::cari($kode);
        } catch (\Throwable $e) {
            Log::error('Cek status pendaftaran gagal: ' . $e->getMessage());
            $this->errorMessage = 'Terjadi gangguan saat mencari data. Silakan coba beberapa saat lagi.';
            return;
        }

        if (!$pendaftar) {
            $this->errorMessage = 'Data pendaftaran tidak ditemukan. Periksa kembali nomor pendaftaran atau NISN Anda.';
            return;
        }

        $this->searchQuery = $kode;
        $this->resultId = $pendaftar->id;
    }

    public function resetSearch()
    {
        $this->reset(['searchQuery', 'resultId', 'errorMessage']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $ringkasan = null;
        if ($this->resultId) {
            $pendaftar = Pendaftar::find($this->resultId);
            $ringkasan = $pendaftar ? StatusPendaftaran::ringkasan($pendaftar) : null;
        }

        if ($this->embedded) {
            return view('livewire.public.cek-pendaftaran-embed', ['ringkasan' => $ringkasan]);
        }

        return view('livewire.public.cek-pendaftaran', [
                'ringkasan' => $ringkasan,
                'registrationPhase' => Setting::get('registration_phase', 'belum_dibuka'),
                'registrationPhaseLabel' => Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka'),
                'registrationPhaseDescription' => Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.'),
            ])
            ->layout('components.layouts.public', [
                'title' => 'Cek Pendaftaran - MTsN 2 Kota Malang',
            ]);
    }
}
