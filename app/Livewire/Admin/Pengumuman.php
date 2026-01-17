<?php

namespace App\Livewire\Admin;

use App\Models\Pengumuman as PengumumanModel;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Pengumuman extends Component
{
    use WithPagination, WithSorting;

    public $judul, $konten, $kategori, $file_path, $aktif, $urutan, $tanggal_publikasi;
    public $pengumuman_id;
    public $isEdit = false;
    public $showModal = false;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }
    }

    public function render()
    {
        $query = PengumumanModel::query();
        $data = $this->applySorting($query)->paginate(10);
        return view('livewire.admin.pengumuman', compact('data'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Pengumuman',
                'pageDescription' => 'Kelola pengumuman untuk publik dan pendaftar'
            ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $data = PengumumanModel::find($id);
        $this->pengumuman_id = $data->id;
        $this->judul = $data->judul;
        $this->konten = $data->konten;
        $this->kategori = $data->kategori;
        $this->aktif = $data->aktif;
        $this->urutan = $data->urutan;
        $this->tanggal_publikasi = $data->tanggal_publikasi ? \Carbon\Carbon::parse($data->tanggal_publikasi)->format('Y-m-d') : null;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:umum,jadwal,pengumuman_lulus,pendaftar',
            'tanggal_publikasi' => 'nullable|date',
            'urutan' => 'integer',
        ]);

        PengumumanModel::create([
            'judul' => $this->judul,
            'konten' => $this->konten,
            'kategori' => $this->kategori,
            'aktif' => $this->aktif ?? true,
            'urutan' => $this->urutan ?? 0,
            'tanggal_publikasi' => $this->tanggal_publikasi ?? now(),
        ]);

        $this->closeModal();
        $this->dispatch('saved', message: 'Pengumuman berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:umum,jadwal,pengumuman_lulus,pendaftar',
            'tanggal_publikasi' => 'nullable|date',
            'urutan' => 'integer',
        ]);

        if ($this->pengumuman_id) {
            $pengumuman = PengumumanModel::find($this->pengumuman_id);
            if ($pengumuman) {
                $pengumuman->update([
                    'judul' => $this->judul,
                    'konten' => $this->konten,
                    'kategori' => $this->kategori,
                    'aktif' => $this->aktif ?? true,
                    'urutan' => $this->urutan ?? 0,
                    'tanggal_publikasi' => $this->tanggal_publikasi,
                ]);
            }
        }

        $this->closeModal();
        $this->dispatch('saved', message: 'Pengumuman berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (Auth::user()->role !== 'admin') {
            $this->dispatch('saved', message: 'Anda tidak memiliki izin menghapus.');
            return;
        }

        $data = PengumumanModel::find($id);
        if ($data) {
            $data->delete();
            $this->dispatch('saved', message: 'Pengumuman berhasil dihapus.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->judul = '';
        $this->konten = '';
        $this->kategori = 'umum';
        $this->aktif = true;
        $this->urutan = 0;
        $this->tanggal_publikasi = now()->format('Y-m-d');
        $this->pengumuman_id = null;
    }
}
