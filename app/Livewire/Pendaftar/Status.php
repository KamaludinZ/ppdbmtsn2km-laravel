<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\TesPsikologiSetting;

class Status extends Component
{
    public $pendaftar;
    public $tesPsikologiAktif = false;

    public $documentLabels = [
        'rapor' => ['label' => 'Scan Rapor', 'keterangan' => 'Upload dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'akta_kelahiran' => ['label' => 'Akta Kelahiran', 'keterangan' => 'Scan akta kelahiran dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'kartu_keluarga' => ['label' => 'Kartu Keluarga', 'keterangan' => 'Scan Kartu Keluarga dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'nisn' => ['label' => 'Scan/Bukti NISN', 'keterangan' => 'Kartu NISN atau screenshot cek NISN dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'ijazah' => ['label' => 'Ijazah/SKL/Surat Keterangan Aktif', 'keterangan' => 'Dari sekolah asal (SD/MI) dalam format PDF, maksimal 2 MB', 'wajib' => true],
        'sertifikat' => ['label' => 'Sertifikat/Piagam', 'keterangan' => 'Jika memiliki prestasi, upload dalam format PDF, maksimal 2 MB', 'wajib' => false],
        'kip' => ['label' => 'Kartu Indonesia Pintar (KIP)', 'keterangan' => 'Upload jika memiliki nomor KIP di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kip'],
        'pkh' => ['label' => 'Program Keluarga Harapan (PKH)', 'keterangan' => 'Upload jika memiliki nomor PKH di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_pkh'],
        'kks' => ['label' => 'Kartu Keluarga Sejahtera (KKS)', 'keterangan' => 'Upload jika memiliki nomor KKS di formulir, format PDF maksimal 2 MB', 'wajib' => false, 'conditional' => 'nomor_kks'],
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar;
        if (!$this->pendaftar) {
            return redirect()->to('/daftar');
        }

        // Load relationships needed for status check
        $this->pendaftar->load(['dokumen', 'verifikasiBerkas', 'tesPsikologiPendaftar', 'daftarUlang', 'seragam']);

        // Check if tes psikologi is active
        $tesPsikologiSetting = TesPsikologiSetting::getSetting();
        $this->tesPsikologiAktif = $tesPsikologiSetting->aktif;
    }

    public function render()
    {
        return view('livewire.pendaftar.status')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Status Pendaftaran',
                'pageDescription' => 'Pantau status pendaftaran Anda'
            ]);
    }
}
