<?php

namespace App\Livewire\Panitia;

use App\Models\TimelinePendaftaran;
use App\Models\JalurPendaftaran;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Jadwal extends Component
{
    use WithPagination;

    public $jadwal_id;
    public $judul_kegiatan, $deskripsi, $tanggal_mulai, $tanggal_selesai;
    public $waktu_mulai, $waktu_selesai, $tempat, $jalur_kode, $warna, $aktif, $urutan;

    public $isEdit = false;
    public $showModal = false;

    // Filter
    public $filterJalur = '';
    public $filterStatus = '';
    public $perPage = 10;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }
    }

    public function render()
    {
        $query = TimelinePendaftaran::query();

        // Filter by jalur
        if ($this->filterJalur) {
            $query->where('jalur_kode', $this->filterJalur);
        }

        // Filter by status
        if ($this->filterStatus !== '') {
            $query->where('aktif', $this->filterStatus);
        }

        $data = $query->orderBy('urutan')->orderBy('tanggal_mulai')->paginate($this->perPage);

        // Get all jalur for filter dropdown
        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        return view('livewire.panitia.jadwal', compact('data', 'jalurPendaftaran'))
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
                'pageTitle' => 'Manajemen Jadwal',
                'pageDescription' => 'Kelola timeline dan jadwal kegiatan PPDB'
            ]);
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $data = TimelinePendaftaran::find($id);
        $this->jadwal_id = $data->id;
        $this->judul_kegiatan = $data->judul_kegiatan;
        $this->deskripsi = $data->deskripsi;
        $this->tanggal_mulai = $data->tanggal_mulai;
        $this->tanggal_selesai = $data->tanggal_selesai;
        $this->waktu_mulai = $data->waktu_mulai;
        $this->waktu_selesai = $data->waktu_selesai;
        $this->tempat = $data->tempat;
        $this->jalur_kode = $data->jalur_kode;
        $this->warna = $data->warna;
        $this->aktif = $data->aktif;
        $this->urutan = $data->urutan;
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'judul_kegiatan' => 'required|string|max:200',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'urutan' => 'integer',
        ]);

        TimelinePendaftaran::create([
            'judul_kegiatan' => $this->judul_kegiatan,
            'deskripsi' => $this->deskripsi,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'tempat' => $this->tempat,
            'jalur_kode' => $this->jalur_kode,
            'warna' => $this->warna ?? 'blue',
            'aktif' => $this->aktif ?? true,
            'urutan' => $this->urutan ?? 0,
        ]);

        $this->closeModal();
        $this->dispatch('saved', message: 'Jadwal berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'judul_kegiatan' => 'required|string|max:200',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'urutan' => 'integer',
        ]);

        if ($this->jadwal_id) {
            $jadwal = TimelinePendaftaran::find($this->jadwal_id);
            if ($jadwal) {
                $jadwal->update([
                    'judul_kegiatan' => $this->judul_kegiatan,
                    'deskripsi' => $this->deskripsi,
                    'tanggal_mulai' => $this->tanggal_mulai,
                    'tanggal_selesai' => $this->tanggal_selesai,
                    'waktu_mulai' => $this->waktu_mulai,
                    'waktu_selesai' => $this->waktu_selesai,
                    'tempat' => $this->tempat,
                    'jalur_kode' => $this->jalur_kode,
                    'warna' => $this->warna ?? 'blue',
                    'aktif' => $this->aktif ?? true,
                    'urutan' => $this->urutan ?? 0,
                ]);
            }
        }

        $this->closeModal();
        $this->dispatch('saved', message: 'Jadwal berhasil diperbarui.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->judul_kegiatan = '';
        $this->deskripsi = '';
        $this->tanggal_mulai = '';
        $this->tanggal_selesai = '';
        $this->waktu_mulai = null;
        $this->waktu_selesai = null;
        $this->tempat = '';
        $this->jalur_kode = '';
        $this->warna = 'blue';
        $this->aktif = true;
        $this->urutan = 0;
        $this->jadwal_id = null;
    }
}
