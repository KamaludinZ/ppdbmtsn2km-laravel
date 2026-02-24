<?php

namespace App\Livewire\Public;

use App\Models\Pendaftar;
use App\Models\Setting;
use Livewire\Component;

class CekPendaftaran extends Component
{
    public $searchQuery = '';
    public $result = null;
    public $errorMessage = '';

    public function search()
    {
        $this->result = null;
        $this->errorMessage = '';

        $this->validate([
            'searchQuery' => 'required|min:3',
        ], [
            'searchQuery.required' => 'Nomor pendaftaran atau NISN harus diisi',
            'searchQuery.min' => 'Minimal 3 karakter',
        ]);

        // Search by nomor_pendaftaran or nisn with all related data
        $pendaftar = Pendaftar::with([
                'jalurPendaftaran',
                'tesPsikologiPendaftar',
                'daftarUlang',
                'seragam'
            ])
            ->where(function($query) {
                $query->where('nomor_pendaftaran', $this->searchQuery)
                      ->orWhere('nisn', $this->searchQuery);
            })
            ->first();

        if ($pendaftar) {
            $this->result = $pendaftar;
        } else {
            $this->errorMessage = 'Data pendaftaran tidak ditemukan. Periksa kembali nomor pendaftaran atau NISN Anda.';
        }
    }

    public function resetSearch()
    {
        $this->reset(['searchQuery', 'result', 'errorMessage']);
    }

    public function render()
    {
        $registrationPhase = Setting::get('registration_phase', 'belum_dibuka');
        $registrationPhaseLabel = Setting::get('registration_phase_label', 'Pendaftaran Belum Dibuka');
        $registrationPhaseDescription = Setting::get('registration_phase_description', 'Pendaftaran akan segera dibuka.');

        return view('livewire.public.cek-pendaftaran', [
                'registrationPhase' => $registrationPhase,
                'registrationPhaseLabel' => $registrationPhaseLabel,
                'registrationPhaseDescription' => $registrationPhaseDescription
            ])
            ->layout('components.layouts.public', [
                'title' => 'Cek Pendaftaran - MTsN 2 Kota Malang'
            ]);
    }
}
