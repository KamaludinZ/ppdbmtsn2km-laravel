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
    public $filterEmisCompleteness = ''; // Filter berdasarkan persentase kelengkapan EMIS
    public $perPage = 10;
    public $routePrefix = 'panitia-pendaftaran'; // Default route prefix

    // Properties for change password modal
    public $isChangePasswordModalOpen = false;
    public $selectedPendaftarId = null;
    public $new_password = '';
    public $new_password_confirmation = '';

    // Properties for edit telepon ortu modal
    public $isEditTeleponOrtuModalOpen = false;
    public $editTeleponOrtu = '';

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
        'status_keahlian' => false, // Status Keahlian
        'status_tahfidz' => false, // Status Tahfidz
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
        'filterEmisCompleteness' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Check if user is panitia
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Set route prefix based on user role
        if ($user->role === 'panitia_daftarulang') {
            $this->routePrefix = 'panitia-daftarulang';
        } elseif ($user->role === 'panitia_pendaftaran' || $user->role === 'panitia') {
            $this->routePrefix = 'panitia-pendaftaran';
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

    public function updatingFilterEmisCompleteness()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PendaftarModel::with(['jalurPendaftaran', 'user', 'diperbaruiOleh', 'daftarUlang', 'seragam', 'verifikasiBerkas', 'tesPsikologiPendaftar', 'keahlians', 'tahfidz'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nisn', 'ilike', '%' . $this->search . '%')
                        ->orWhere('email', 'ilike', '%' . $this->search . '%')
                        ->orWhere('no_hp', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nama_ayah', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nama_ibu', 'ilike', '%' . $this->search . '%')
                        ->orWhere('asal_kabkota', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status_pendaftaran', $this->filterStatus);
            });

        // Apply sorting
        $query = $this->applySorting($query);

        // Filter by EMIS completeness if selected
        if ($this->filterEmisCompleteness) {
            $allRecords = $query->get();
            $filtered = $allRecords->filter(function ($pendaftar) {
                $percentage = $pendaftar->getEmisCompletenessPercentage();

                return match($this->filterEmisCompleteness) {
                    '0-25' => $percentage >= 0 && $percentage <= 25,
                    '26-50' => $percentage >= 26 && $percentage <= 50,
                    '51-75' => $percentage >= 51 && $percentage <= 75,
                    '76-100' => $percentage >= 76 && $percentage <= 100,
                    default => true,
                };
            });

            // Manual pagination
            $currentPage = $this->getPage();
            $perPage = $this->perPage;
            $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $pendaftar = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $pendaftar = $query->paginate($this->perPage);
        }

        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        $stats = [
            'total' => PendaftarModel::count(),
            'menunggu_verifikasi' => PendaftarModel::where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lengkapi_data' => PendaftarModel::where('status_pendaftaran', 'lengkapi_data')->count(),
            'perbaiki_data' => PendaftarModel::where('status_pendaftaran', 'perbaiki_data')->count(),
            'data_ditolak' => PendaftarModel::where('status_pendaftaran', 'data_ditolak')->count(),
            'data_disetujui' => PendaftarModel::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])->count(),
        ];

        return view('livewire.panitia.pendaftar', compact('pendaftar', 'jalurPendaftaran', 'stats'))
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role, // Use dynamic role
                'pageTitle' => 'Data Pendaftar',
                'pageDescription' => 'Kelola data pendaftar PPDB MTsN 2 Kota Malang'
            ]);
    }

    public function getStatusSelection($id, $currentStatus)
    {
        return $this->statusSelections[$id] ?? $currentStatus;
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

    public function showEditTeleponOrtuModal($id)
    {
        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $this->selectedPendaftarId = $id;
            $this->editTeleponOrtu = $pendaftar->telepon_ortu ?? '';
            $this->isEditTeleponOrtuModalOpen = true;
        }
    }

    public function closeEditTeleponOrtuModal()
    {
        $this->isEditTeleponOrtuModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->editTeleponOrtu = '';
    }

    public function updateTeleponOrtu()
    {
        $this->validate([
            'editTeleponOrtu' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ], [
            'editTeleponOrtu.required' => 'Nomor WhatsApp Orang Tua wajib diisi.',
            'editTeleponOrtu.min' => 'Nomor WhatsApp Orang Tua minimal 10 digit.',
            'editTeleponOrtu.max' => 'Nomor WhatsApp Orang Tua maksimal 15 digit.',
            'editTeleponOrtu.regex' => 'Nomor WhatsApp Orang Tua hanya boleh berisi angka dan tanda +.',
        ]);

        $pendaftar = PendaftarModel::find($this->selectedPendaftarId);
        if ($pendaftar) {
            $pendaftar->update([
                'telepon_ortu' => $this->editTeleponOrtu,
                'telepon' => $this->editTeleponOrtu, // Update both fields for consistency
            ]);

            session()->flash('message', 'Nomor WhatsApp Orang Tua berhasil diperbarui.');
            $this->closeEditTeleponOrtuModal();
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
        }
    }

    public function batalkanPersetujuanDaftarUlang($id)
    {
        try {
            $pendaftar = PendaftarModel::findOrFail($id);

            // Batalkan persetujuan daftar ulang
            // Kolom tanggal_setuju_daftar_ulang sudah di-rename menjadi tanggal_pilihan_daftar_ulang;
            // status persetujuan juga dikembalikan agar rekap daftar ulang tetap konsisten.
            $pendaftar->update([
                'setuju_daftar_ulang' => false,
                'status_persetujuan_daftar_ulang' => 'belum_memilih',
                'tanggal_pilihan_daftar_ulang' => null,
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
