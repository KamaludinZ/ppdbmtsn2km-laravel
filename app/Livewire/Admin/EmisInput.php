<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EmisInput extends Component
{
    public $acceptedApplicants = [];
    public $search = '';
    public $selectedApplicant = null;
    public $showDetailModal = false;
    
    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        $this->loadAcceptedApplicants();
    }

    public function render()
    {
        return view('livewire.admin.emis-input', [
            'acceptedApplicants' => $this->acceptedApplicants,
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Input EMIS',
            'pageDescription' => 'Data siswa diterima untuk input EMIS'
        ]);
    }

    public function loadAcceptedApplicants()
    {
        // Get applicants who have completed daftar ulang with complete relationships
        $query = Pendaftar::with([
            'jalurPendaftaran',
            'daftarUlang',
            'seragam',
            'dokumen',
            'verifikasiBerkas',
            'tesPsikologiPendaftar.opsi',
            'prestasis',
            'beasiswas',
            'user'
        ])
            ->where('status_pendaftaran', 'data_disetujui') // Only students with approved data
            ->whereHas('daftarUlang', function($q) {
                // Only students who have started daftar ulang
                $q->where('status', '!=', 'belum');
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nisn', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $this->acceptedApplicants = $query->orderBy('nama_lengkap', 'asc')->get();
    }

    public function updatingSearch()
    {
        $this->loadAcceptedApplicants();
    }

    public function showDetail($applicantId)
    {
        $this->selectedApplicant = Pendaftar::find($applicantId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedApplicant = null;
    }

    public function downloadDocument($documentType, $applicantId)
    {
        $applicant = Pendaftar::find($applicantId);
        
        if (!$applicant) {
            session()->flash('error', 'Data pendaftar tidak ditemukan.');
            return;
        }
        
        $documentPath = null;
        $documentName = '';
        
        switch ($documentType) {
            case 'kartu_keluarga':
                $documentPath = $applicant->file_kartu_keluarga;
                $documentName = 'Kartu_Keluarga_' . $applicant->nama_lengkap . '.pdf';
                break;
            case 'akta_kelahiran':
                $documentPath = $applicant->file_akta_kelahiran;
                $documentName = 'Akta_Kelahiran_' . $applicant->nama_lengkap . '.pdf';
                break;
            case 'ijazah':
                $documentPath = $applicant->file_ijazah_sd;
                $documentName = 'Ijazah_' . $applicant->nama_lengkap . '.pdf';
                break;
            case 'skhun':
                $documentPath = $applicant->file_skhun_sd;
                $documentName = 'SKHUN_' . $applicant->nama_lengkap . '.pdf';
                break;
            case 'pas_foto':
                $documentPath = $applicant->file_pas_foto;
                $documentName = 'Pas_Foto_' . $applicant->nama_lengkap . '.jpg';
                break;
            case 'surat_keterangan_lulus':
                $documentPath = $applicant->file_surat_keterangan_lulus;
                $documentName = 'Surat_Keterangan_Lulus_' . $applicant->nama_lengkap . '.pdf';
                break;
            default:
                session()->flash('error', 'Jenis dokumen tidak valid.');
                return;
        }
        
        if ($documentPath && Storage::exists($documentPath)) {
            return Storage::download($documentPath, $documentName);
        } else {
            session()->flash('error', 'File dokumen tidak ditemukan.');
        }
    }

    public function refreshData()
    {
        $this->loadAcceptedApplicants();
        session()->flash('message', 'Data berhasil diperbarui.');
    }
}