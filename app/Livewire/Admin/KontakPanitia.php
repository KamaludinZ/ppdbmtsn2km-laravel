<?php

namespace App\Livewire\Admin;

use App\Models\KontakPanitia as KontakModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class KontakPanitia extends Component
{
    public $kontak;
    public $nama, $nomor_wa, $keterangan, $urutan, $aktif;
    public $isEdit = false;
    public $showModal = false;

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }
    }

    public function render()
    {
        $data = KontakModel::orderBy('urutan')->get();
        return view('livewire.admin.kontak-panitia', compact('data'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Kontak Panitia',
                'pageDescription' => 'Kelola daftar kontak panitia yang ditampilkan di halaman publik'
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
        $data = KontakModel::find($id);
        $this->kontak = $data;
        $this->nama = $data->nama;
        $this->nomor_wa = $data->nomor_wa;
        $this->keterangan = $data->keterangan;
        $this->urutan = $data->urutan;
        $this->aktif = $data->aktif;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:255',
            'urutan' => 'integer',
            'aktif' => 'boolean'
        ]);

        KontakModel::create([
            'nama' => $this->nama,
            'nomor_wa' => $this->nomor_wa,
            'keterangan' => $this->keterangan,
            'urutan' => $this->urutan ?? 0,
            'aktif' => $this->aktif ?? true,
        ]);

        $this->closeModal();
        $this->dispatch('kontak-saved', message: 'Kontak berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'required|string|max:20',
            'keterangan' => 'nullable|string|max:255',
            'urutan' => 'integer',
            'aktif' => 'boolean'
        ]);

        if ($this->kontak) {
            $this->kontak->update([
                'nama' => $this->nama,
                'nomor_wa' => $this->nomor_wa,
                'keterangan' => $this->keterangan,
                'urutan' => $this->urutan ?? 0,
                'aktif' => $this->aktif ?? true,
            ]);
        }

        $this->closeModal();
        $this->dispatch('kontak-saved', message: 'Kontak berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = KontakModel::find($id);
        if ($data) {
            $data->delete();
            $this->dispatch('kontak-saved', message: 'Kontak berhasil dihapus.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->nama = '';
        $this->nomor_wa = '';
        $this->keterangan = '';
        $this->urutan = 0;
        $this->aktif = true;
        $this->kontak = null;
    }
}
