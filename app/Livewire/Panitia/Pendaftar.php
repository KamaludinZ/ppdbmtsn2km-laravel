<?php

namespace App\Livewire\Panitia;

use App\Models\Pendaftar as PendaftarModel;
use App\Models\JalurPendaftaran;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Pendaftar extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $searchInput = ''; // Temporary input untuk user ketik
    public $filterJalur = '';
    public $filterStatus = '';
    public $perPage = 10;

    // Properties for change password modal
    public $isChangePasswordModalOpen = false;
    public $selectedPendaftarId = null;
    public $new_password = '';
    public $new_password_confirmation = '';

    // Array to store status for each pendaftar
    public $statusSelections = [];

    // Properties for column visibility
    public $showColumns = [
        'no' => true,
        'nomor_pendaftaran' => true,
        'pendaftar' => true,
        'jenis_kelamin' => true,
        'kontak' => true,
        'password' => false,
        'ubah_password' => false,
        'ortu_whatsapp' => false,
        'asal_kabkota' => true,
        'jenis_sekolah' => true,
        'nsm_npsn' => true,
        'asal_sekolah' => true,
        'nama_ayah' => false,
        'nama_ibu' => false,
        'jalur' => true,
        'status' => true,
        'status_tes' => true, // Status kelulusan tes
        'setuju_daftar_ulang' => true, // Persetujuan daftar ulang
        'status_du_madrasah' => false, // Status DU Madrasah (Pembayaran)
        'status_du_seragam' => false, // Status DU Seragam
        'status_ukuran' => false, // Status Ukuran Seragam
        'status_daftar_ulang' => false,
        'verif_rapor' => false,
        'verif_akte' => false,
        'verif_kk' => false,
        'verif_nisn' => false,
        'verif_ijazah' => false,
        'verif_sertifikat' => false,
        'verif_kip' => false,
        'verif_psikologi' => true,
        'diperbarui' => false,
        'diperbarui_oleh' => false,
        'tanggal' => true,
        'emis_completeness' => true,
        'aksi' => false,
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterJalur' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Check if user is panitia
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        // Clear search if it contains role names (likely from browser autocomplete)
        if (in_array(strtolower($this->search), ['admin', 'panitia', 'panitia_pendaftaran', 'panitia_daftarulang'])) {
            $this->search = '';
        }

        // Sync searchInput with search value from query string
        $this->searchInput = $this->search;
    }

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchInput = '';
        $this->resetPage();
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PendaftarModel::with(['jalurPendaftaran', 'user', 'diperbaruiOleh', 'daftarUlang', 'verifikasiBerkas', 'tesPsikologiPendaftar'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        ->orWhere('nomor_pendaftaran', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_ayah', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_ibu', 'like', '%' . $this->search . '%')
                        ->orWhere('asal_kabkota', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status_pendaftaran', $this->filterStatus);
            });

        // Apply sorting
        $pendaftar = $this->applySorting($query)->paginate($this->perPage);

        // Initialize status selections for each pendaftar
        foreach ($pendaftar as $item) {
            if (!isset($this->statusSelections[$item->id])) {
                $this->statusSelections[$item->id] = $item->status_pendaftaran;
            }
        }

        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        $stats = [
            'total' => PendaftarModel::count(),
            'menunggu' => PendaftarModel::where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lulus' => PendaftarModel::where('status_pendaftaran', 'data_disetujui')->count(),
            'tidak_lulus' => PendaftarModel::where('status_pendaftaran', 'data_ditolak')->count(),
            'cadangan' => PendaftarModel::where('status_pendaftaran', 'lengkapi_data')->count(),
        ];

        return view('livewire.panitia.pendaftar', compact('pendaftar', 'jalurPendaftaran', 'stats'))
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role, // Use dynamic role
                'pageTitle' => 'Data Pendaftar',
                'pageDescription' => 'Kelola data pendaftar PPDB MTsN 2 Kota Malang'
            ]);
    }

    public function updateStatus($id)
    {
        $newStatus = $this->statusSelections[$id] ?? null;

        if (!$newStatus) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $pendaftar->update([
                'status_pendaftaran' => $newStatus,
                'diperbarui_oleh' => Auth::id()
            ]);
            session()->flash('message', 'Status pendaftar berhasil diperbarui.');
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
        }
    }

    public function toggleColumn($column)
    {
        $this->showColumns[$column] = !$this->showColumns[$column];
    }

    public function showChangePasswordModal($id)
    {
        $this->selectedPendaftarId = $id;
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->isChangePasswordModalOpen = true;
    }

    public function closeChangePasswordModal()
    {
        $this->isChangePasswordModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function updatePassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pendaftar = PendaftarModel::find($this->selectedPendaftarId);
        if ($pendaftar && $pendaftar->user) {
            $pendaftar->user->update([
                'password' => bcrypt($this->new_password)
            ]);

            session()->flash('message', 'Password pendaftar berhasil diperbarui.');
            $this->closeChangePasswordModal();
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan atau tidak memiliki akun.');
        }
    }

    public function batalkanPersetujuanDaftarUlang($id)
    {
        try {
            $pendaftar = PendaftarModel::findOrFail($id);

            // Batalkan persetujuan daftar ulang
            $pendaftar->update([
                'setuju_daftar_ulang' => false,
                'tanggal_setuju_daftar_ulang' => null,
            ]);

            // Hapus record daftar ulang jika ada
            if ($pendaftar->daftarUlang) {
                $pendaftar->daftarUlang->delete();
            }

            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "Persetujuan daftar ulang {$pendaftar->nama_lengkap} berhasil dibatalkan"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

}
