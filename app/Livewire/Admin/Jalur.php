<?php

namespace App\Livewire\Admin;

use App\Models\JalurPendaftaran;
use App\Models\JalurPendaftaranHistory;
use App\Models\PersyaratanUmum;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Jalur extends Component
{
    use WithPagination, WithFileUploads, WithSorting;

    public $nama, $kode, $prefix_nomor, $deskripsi, $kuota, $nilai_minimum, $warna, $ikon, $aktif, $urutan, $paling_populer;
    public $jalur_id;
    public $isEdit = false;
    public $showModal = false;
    public $activeTab = 'general'; // general, persyaratan, history

    // Persyaratan Khusus Management (untuk jalur tertentu)
    public $persyaratan_khusus = [];
    public $new_persyaratan_khusus = '';

    // Persyaratan Umum Management
    public $syarat_id;
    public $persyaratan;
    public $urutan_syarat = 0;
    public $aktif_syarat = true;
    public $showSyaratForm = false;
    public $isEditingSyarat = false;

    protected $listeners = [];

    public function mount()
    {
        // Check if user is admin or panitia pendaftaran
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia'])) {
            return redirect()->to('/login/admin');
        }
    }

    public function render()
    {
        // Apply sorting to jalur pendaftaran
        $query = JalurPendaftaran::query();
        $jalurPendaftaran = $this->applySorting($query)->paginate(10);

        $stats = [
            'total' => JalurPendaftaran::count(),
            'aktif' => JalurPendaftaran::where('aktif', true)->count(),
            'total_kuota' => JalurPendaftaran::where('kuota', '>', 0)->sum('kuota'),
        ];

        // Query Persyaratan Umum
        $persyaratanUmum = PersyaratanUmum::ordered()->paginate(10, ['*'], 'syaratPage');

        return view('livewire.admin.jalur', compact('jalurPendaftaran', 'stats', 'persyaratanUmum'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Jalur Pendaftaran',
                'pageDescription' => 'Kelola jalur pendaftaran dan konten halaman PPDB'
            ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->activeTab = 'general';
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $jalur = JalurPendaftaran::findOrFail($id);
        $this->jalur_id = $jalur->id;
        $this->nama = $jalur->nama;
        $this->kode = $jalur->kode;
        $this->prefix_nomor = $jalur->prefix_nomor;
        $this->deskripsi = $jalur->deskripsi;
        $this->kuota = $jalur->kuota;
        $this->nilai_minimum = $jalur->nilai_minimum;
        $this->warna = $jalur->warna;
        $this->ikon = $jalur->icon;
        $this->aktif = $jalur->aktif;
        $this->urutan = $jalur->urutan;
        $this->paling_populer = $jalur->paling_populer;
        $this->persyaratan_khusus = $jalur->persyaratan ?? [];

        $this->isEdit = true;
        $this->activeTab = 'general';
        $this->showModal = true;

    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addPersyaratanKhusus()
    {
        if (!empty($this->new_persyaratan_khusus)) {
            $this->persyaratan_khusus[] = $this->new_persyaratan_khusus;
            $this->new_persyaratan_khusus = '';
        }
    }

    public function removePersyaratanKhusus($index)
    {
        if (isset($this->persyaratan_khusus[$index])) {
            unset($this->persyaratan_khusus[$index]);
            $this->persyaratan_khusus = array_values($this->persyaratan_khusus);
        }
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:jalur_pendaftaran,kode',
            'kuota' => 'required|integer|min:0',
            'nilai_minimum' => 'required|numeric|min:0|max:100',
            'warna' => 'required|string',
        ]);

        $jalur = JalurPendaftaran::create([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'prefix_nomor' => $this->prefix_nomor,
            'deskripsi' => $this->deskripsi,
            'kuota' => $this->kuota,
            'nilai_minimum' => $this->nilai_minimum,
            'warna' => $this->warna,
            'icon' => $this->ikon ?? 'default',
            'aktif' => $this->aktif ?? true,
            'paling_populer' => $this->paling_populer ?? false,
            'urutan' => $this->urutan ?? 0,
            'persyaratan' => $this->persyaratan_khusus,
        ]);

        $this->closeModal();
        session()->flash('message', 'Jalur pendaftaran berhasil ditambahkan.');
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:jalur_pendaftaran,kode,' . $this->jalur_id,
            'kuota' => 'required|integer|min:0',
            'nilai_minimum' => 'required|numeric|min:0|max:100',
            'warna' => 'required|string',
        ]);

        if ($this->jalur_id) {
            $jalur = JalurPendaftaran::findOrFail($this->jalur_id);

            $jalur->update([
                'nama' => $this->nama,
                'kode' => $this->kode,
                'prefix_nomor' => $this->prefix_nomor,
                'deskripsi' => $this->deskripsi,
                'kuota' => $this->kuota,
                'nilai_minimum' => $this->nilai_minimum,
                'warna' => $this->warna,
                'icon' => $this->ikon ?? 'default',
                'aktif' => $this->aktif ?? true,
                'paling_populer' => $this->paling_populer ?? false,
                'urutan' => $this->urutan ?? 0,
                'persyaratan' => $this->persyaratan_khusus,
            ]);
        }

        $this->closeModal();
        session()->flash('message', 'Jalur pendaftaran berhasil diperbarui.');
    }


    public function delete($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $jalur = JalurPendaftaran::find($id);
        if ($jalur) {
            $jalur->delete();
            session()->flash('message', 'Jalur pendaftaran berhasil dihapus.');
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
        $this->kode = '';
        $this->prefix_nomor = '';
        $this->deskripsi = '';
        $this->kuota = '';
        $this->nilai_minimum = '';
        $this->warna = 'blue';
        $this->ikon = '';
        $this->aktif = true;
        $this->paling_populer = false;
        $this->urutan = 0;
        $this->jalur_id = null;
        $this->persyaratan_khusus = [];
        $this->new_persyaratan_khusus = '';
        $this->activeTab = 'general';
    }

    // ===== PERSYARATAN UMUM METHODS =====
    public function createSyarat()
    {
        $this->resetSyaratFields();
        $this->isEditingSyarat = false;
        $this->showSyaratForm = true;
    }

    public function editSyarat($id)
    {
        $syarat = PersyaratanUmum::find($id);
        $this->syarat_id = $syarat->id;
        $this->persyaratan = $syarat->persyaratan;
        $this->urutan_syarat = $syarat->urutan;
        $this->aktif_syarat = $syarat->aktif;

        $this->isEditingSyarat = true;
        $this->showSyaratForm = true;
    }

    public function saveSyarat()
    {
        $this->validate([
            'persyaratan' => 'required|string|max:500',
            'urutan_syarat' => 'integer|min:0',
            'aktif_syarat' => 'boolean',
        ]);

        if ($this->isEditingSyarat && $this->syarat_id) {
            $syarat = PersyaratanUmum::find($this->syarat_id);
            $syarat->update([
                'persyaratan' => $this->persyaratan,
                'urutan' => $this->urutan_syarat,
                'aktif' => $this->aktif_syarat ?? true,
            ]);
            session()->flash('message', 'Persyaratan berhasil diperbarui.');
        } else {
            PersyaratanUmum::create([
                'persyaratan' => $this->persyaratan,
                'urutan' => $this->urutan_syarat ?? 0,
                'aktif' => $this->aktif_syarat ?? true,
            ]);
            session()->flash('message', 'Persyaratan berhasil ditambahkan.');
        }

        $this->showSyaratForm = false;
        $this->resetSyaratFields();
    }

    public function deleteSyarat($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $syarat = PersyaratanUmum::find($id);
        if ($syarat) {
            $syarat->delete();
            session()->flash('message', 'Persyaratan berhasil dihapus.');
        }
    }

    public function cancelSyarat()
    {
        $this->showSyaratForm = false;
        $this->resetSyaratFields();
    }

    private function resetSyaratFields()
    {
        $this->syarat_id = null;
        $this->persyaratan = '';
        $this->urutan_syarat = 0;
        $this->aktif_syarat = true;
    }

}