<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\Misi;
use App\Models\Tujuan;
use App\Models\Fasilitas;
use Livewire\Component;
use Livewire\WithPagination;

class Tentang extends Component
{
    use WithPagination;

    public $profil;
    public $visi;
    public $sejarah;

    // Moved from Settings
    public $app_address;
    public $app_phone;
    public $app_email_ptsp;
    public $app_nsm;
    public $app_npsn;
    public $app_akreditasi;
    public $app_whatsapp_contact;

    // Misi Management
    public $misi_id;
    public $misi_text;
    public $urutan_misi = 0;
    public $aktif_misi = true;
    public $showMisiForm = false;
    public $isEditingMisi = false;

    // Tujuan Management
    public $tujuan_id;
    public $tujuan_text;
    public $urutan_tujuan = 0;
    public $aktif_tujuan = true;
    public $showTujuanForm = false;
    public $isEditingTujuan = false;

    // Fasilitas Management
    public $fasilitas_id;
    public $nama_fasilitas;
    public $deskripsi_fasilitas;
    public $icon_fasilitas = 'building';
    public $warna_fasilitas = 'emerald';
    public $urutan_fasilitas = 0;
    public $aktif_fasilitas = true;
    public $showFasilitasForm = false;
    public $isEditingFasilitas = false;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->profil = Setting::get('page_tentang_content', '');
        $this->visi = Setting::get('page_tentang_visi', '');
        $this->sejarah = Setting::get('page_tentang_sejarah', '');

        // Load Identity Fields
        $this->app_address = Setting::get('app_address', 'Jl. Raya Cemorokandang 77 Kota Malang');
        $this->app_phone = Setting::get('app_phone', '(0341) 711500');
        $this->app_email_ptsp = Setting::get('app_email_ptsp', 'mtsnmalang2adm@gmail.com');
        $this->app_nsm = Setting::get('app_nsm', '121135740002');
        $this->app_npsn = Setting::get('app_npsn', '20536326');
        $this->app_akreditasi = Setting::get('app_akreditasi', 'A (Unggul)');
        $this->app_whatsapp_contact = Setting::get('app_whatsapp_contact', '628123456789');
    }

    // ===== MISI METHODS =====
    public function createMisi()
    {
        $this->resetMisiFields();
        $this->isEditingMisi = false;
        $this->showMisiForm = true;
    }

    public function editMisi($id)
    {
        $misi = Misi::find($id);
        $this->misi_id = $misi->id;
        $this->misi_text = $misi->misi;
        $this->urutan_misi = $misi->urutan;
        $this->aktif_misi = $misi->aktif;

        $this->isEditingMisi = true;
        $this->showMisiForm = true;
    }

    public function saveMisi()
    {
        $this->validate([
            'misi_text' => 'required|string|min:3',
            'urutan_misi' => 'integer|min:0',
            'aktif_misi' => 'boolean',
        ]);

        if ($this->isEditingMisi && $this->misi_id) {
            $misi = Misi::find($this->misi_id);
            $misi->update([
                'misi' => $this->misi_text,
                'urutan' => $this->urutan_misi,
                'aktif' => $this->aktif_misi ?? true,
            ]);
            session()->flash('message', 'Misi berhasil diperbarui.');
        } else {
            Misi::create([
                'misi' => $this->misi_text,
                'urutan' => $this->urutan_misi ?? 0,
                'aktif' => $this->aktif_misi ?? true,
            ]);
            session()->flash('message', 'Misi berhasil ditambahkan.');
        }

        $this->showMisiForm = false;
        $this->resetMisiFields();
    }

    public function deleteMisi($id)
    {
        $misi = Misi::find($id);
        if ($misi) {
            $misi->delete();
            session()->flash('message', 'Misi berhasil dihapus.');
        }
    }

    public function cancelMisi()
    {
        $this->showMisiForm = false;
        $this->resetMisiFields();
    }

    private function resetMisiFields()
    {
        $this->misi_id = null;
        $this->misi_text = '';
        $this->urutan_misi = 0;
        $this->aktif_misi = true;
    }

    // ===== TUJUAN METHODS =====
    public function createTujuan()
    {
        $this->resetTujuanFields();
        $this->isEditingTujuan = false;
        $this->showTujuanForm = true;
    }

    public function editTujuan($id)
    {
        $tujuan = Tujuan::find($id);
        $this->tujuan_id = $tujuan->id;
        $this->tujuan_text = $tujuan->tujuan;
        $this->urutan_tujuan = $tujuan->urutan;
        $this->aktif_tujuan = $tujuan->aktif;

        $this->isEditingTujuan = true;
        $this->showTujuanForm = true;
    }

    public function saveTujuan()
    {
        $this->validate([
            'tujuan_text' => 'required|string|min:3',
            'urutan_tujuan' => 'integer|min:0',
            'aktif_tujuan' => 'boolean',
        ]);

        if ($this->isEditingTujuan && $this->tujuan_id) {
            $tujuan = Tujuan::find($this->tujuan_id);
            $tujuan->update([
                'tujuan' => $this->tujuan_text,
                'urutan' => $this->urutan_tujuan,
                'aktif' => $this->aktif_tujuan ?? true,
            ]);
            session()->flash('message', 'Tujuan berhasil diperbarui.');
        } else {
            Tujuan::create([
                'tujuan' => $this->tujuan_text,
                'urutan' => $this->urutan_tujuan ?? 0,
                'aktif' => $this->aktif_tujuan ?? true,
            ]);
            session()->flash('message', 'Tujuan berhasil ditambahkan.');
        }

        $this->showTujuanForm = false;
        $this->resetTujuanFields();
    }

    public function deleteTujuan($id)
    {
        $tujuan = Tujuan::find($id);
        if ($tujuan) {
            $tujuan->delete();
            session()->flash('message', 'Tujuan berhasil dihapus.');
        }
    }

    public function cancelTujuan()
    {
        $this->showTujuanForm = false;
        $this->resetTujuanFields();
    }

    private function resetTujuanFields()
    {
        $this->tujuan_id = null;
        $this->tujuan_text = '';
        $this->urutan_tujuan = 0;
        $this->aktif_tujuan = true;
    }

    // ===== FASILITAS METHODS =====
    public function createFasilitas()
    {
        $this->resetFasilitasFields();
        $this->isEditingFasilitas = false;
        $this->showFasilitasForm = true;
    }

    public function editFasilitas($id)
    {
        $fasilitas = Fasilitas::find($id);
        $this->fasilitas_id = $fasilitas->id;
        $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        $this->deskripsi_fasilitas = $fasilitas->deskripsi;
        $this->icon_fasilitas = $fasilitas->icon;
        $this->warna_fasilitas = $fasilitas->warna;
        $this->urutan_fasilitas = $fasilitas->urutan;
        $this->aktif_fasilitas = $fasilitas->aktif;

        $this->isEditingFasilitas = true;
        $this->showFasilitasForm = true;
    }

    public function saveFasilitas()
    {
        $this->validate([
            'nama_fasilitas' => 'required|string|max:100',
            'deskripsi_fasilitas' => 'nullable|string|max:200',
            'icon_fasilitas' => 'required|string|max:50',
            'warna_fasilitas' => 'required|string|max:20',
            'urutan_fasilitas' => 'integer|min:0',
            'aktif_fasilitas' => 'boolean',
        ]);

        if ($this->isEditingFasilitas && $this->fasilitas_id) {
            $fasilitas = Fasilitas::find($this->fasilitas_id);
            $fasilitas->update([
                'nama_fasilitas' => $this->nama_fasilitas,
                'deskripsi' => $this->deskripsi_fasilitas,
                'icon' => $this->icon_fasilitas,
                'warna' => $this->warna_fasilitas,
                'urutan' => $this->urutan_fasilitas,
                'aktif' => $this->aktif_fasilitas ?? true,
            ]);
            session()->flash('message', 'Fasilitas berhasil diperbarui.');
        } else {
            Fasilitas::create([
                'nama_fasilitas' => $this->nama_fasilitas,
                'deskripsi' => $this->deskripsi_fasilitas,
                'icon' => $this->icon_fasilitas,
                'warna' => $this->warna_fasilitas,
                'urutan' => $this->urutan_fasilitas ?? 0,
                'aktif' => $this->aktif_fasilitas ?? true,
            ]);
            session()->flash('message', 'Fasilitas berhasil ditambahkan.');
        }

        $this->showFasilitasForm = false;
        $this->resetFasilitasFields();
    }

    public function deleteFasilitas($id)
    {
        $fasilitas = Fasilitas::find($id);
        if ($fasilitas) {
            $fasilitas->delete();
            session()->flash('message', 'Fasilitas berhasil dihapus.');
        }
    }

    public function cancelFasilitas()
    {
        $this->showFasilitasForm = false;
        $this->resetFasilitasFields();
    }

    private function resetFasilitasFields()
    {
        $this->fasilitas_id = null;
        $this->nama_fasilitas = '';
        $this->deskripsi_fasilitas = '';
        $this->icon_fasilitas = 'building';
        $this->warna_fasilitas = 'emerald';
        $this->urutan_fasilitas = 0;
        $this->aktif_fasilitas = true;
    }

    public function saveContent()
    {
        $this->validate([
            'profil' => 'required|string',
            'visi' => 'nullable|string',
        ]);

        Setting::set('page_tentang_content', $this->profil, 'text', 'Konten Profil Madrasah');
        Setting::set('page_tentang_visi', $this->visi, 'text', 'Visi Madrasah');

        // Reload settings untuk memastikan data tersimpan
        $this->loadSettings();

        session()->flash('message', 'Konten Profil & Visi berhasil diperbarui!');
    }

    public function saveIdentity()
    {
        $this->validate([
            'app_address' => 'required|string',
            'app_phone' => 'required|string',
            'app_email_ptsp' => 'required|email',
            'app_nsm' => 'required|string',
            'app_npsn' => 'required|string',
            'app_akreditasi' => 'required|string',
            'app_whatsapp_contact' => 'required|string',
        ]);

        Setting::set('app_address', $this->app_address, 'string', 'Alamat Sekolah');
        Setting::set('app_phone', $this->app_phone, 'string', 'Nomor Telepon Sekolah');
        Setting::set('app_email_ptsp', $this->app_email_ptsp, 'string', 'Email PTSP');
        Setting::set('app_nsm', $this->app_nsm, 'string', 'NSM Sekolah');
        Setting::set('app_npsn', $this->app_npsn, 'string', 'NPSN Sekolah');
        Setting::set('app_akreditasi', $this->app_akreditasi, 'string', 'Akreditasi Sekolah');
        Setting::set('app_whatsapp_contact', $this->app_whatsapp_contact, 'string', 'Kontak WhatsApp Panitia');

        $this->dispatch('saved', message: 'Identitas Madrasah berhasil diperbarui!');
    }

    public function render()
    {
        // Query Misi, Tujuan, dan Fasilitas
        $misiList = Misi::ordered()->paginate(10, ['*'], 'misiPage');
        $tujuanList = Tujuan::ordered()->paginate(10, ['*'], 'tujuanPage');
        $fasilitasList = Fasilitas::ordered()->paginate(10, ['*'], 'fasilitasPage');

        return view('livewire.admin.tentang', compact('misiList', 'tujuanList', 'fasilitasList'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Halaman Tentang',
                'pageDescription' => 'Edit profil dan identitas madrasah'
            ]);
    }

}