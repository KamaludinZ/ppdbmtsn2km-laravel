<?php

namespace App\Livewire\Admin;

use App\Models\KontakPanitia;
use App\Models\LinkTerkait;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Kontak extends Component
{
    // Global Settings
    public $app_email_ptsp;
    public $app_whatsapp_ptsp;
    
    // Social Media Links
    public $app_social_facebook;
    public $app_social_instagram;
    public $app_social_youtube;
    public $app_social_tiktok;

    // Maps Links
    public $app_maps_link;
    public $app_maps_embed;

    // Service Hours
    public $app_service_hours_monday;
    public $app_service_hours_tuesday;
    public $app_service_hours_wednesday;
    public $app_service_hours_thursday;
    public $app_service_hours_friday;
    public $app_service_hours_saturday;
    public $app_service_hours_sunday;
    public $app_service_hours_holiday;

    // Panitia List Logic
    public $kontak_id;
    public $nama, $nomor_wa, $keterangan, $urutan, $aktif;
    public $isEdit = false;
    public $showModal = false;

    // Link Terkait Logic
    public $link_id;
    public $link_nama, $link_url, $link_urutan, $link_aktif;
    public $isEditLink = false;
    public $showModalLink = false;

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        $this->loadGlobalSettings();
    }

    public function loadGlobalSettings()
    {
        $this->app_email_ptsp = Setting::get('app_email_ptsp', '');
        $this->app_whatsapp_ptsp = Setting::get('app_whatsapp_ptsp', '');
        
        // Load Social Media
        $this->app_social_facebook = Setting::get('app_social_facebook', '');
        $this->app_social_instagram = Setting::get('app_social_instagram', '');
        $this->app_social_youtube = Setting::get('app_social_youtube', '');
        $this->app_social_tiktok = Setting::get('app_social_tiktok', '');

        // Load Maps
        $this->app_maps_link = Setting::get('app_maps_link', '');
        $this->app_maps_embed = Setting::get('app_maps_embed', '');

        // Load Service Hours
        $this->app_service_hours_monday = Setting::get('app_service_hours_monday', '07:00 - 15:00 WIB');
        $this->app_service_hours_tuesday = Setting::get('app_service_hours_tuesday', '07:00 - 15:00 WIB');
        $this->app_service_hours_wednesday = Setting::get('app_service_hours_wednesday', '07:00 - 15:00 WIB');
        $this->app_service_hours_thursday = Setting::get('app_service_hours_thursday', '07:00 - 15:00 WIB');
        $this->app_service_hours_friday = Setting::get('app_service_hours_friday', '07:00 - 11:00 WIB');
        $this->app_service_hours_saturday = Setting::get('app_service_hours_saturday', '07:00 - 13:00 WIB');
        $this->app_service_hours_sunday = Setting::get('app_service_hours_sunday', 'Tutup');
        $this->app_service_hours_holiday = Setting::get('app_service_hours_holiday', 'Tutup');
    }

    public function saveGlobalSettings()
    {
        $this->validate([
            'app_email_ptsp' => 'required|email',
            'app_whatsapp_ptsp' => 'nullable|string',
            'app_social_facebook' => 'nullable|url',
            'app_social_instagram' => 'nullable|url',
            'app_social_youtube' => 'nullable|url',
            'app_social_tiktok' => 'nullable|url',
            'app_maps_link' => 'nullable|url',
            'app_maps_embed' => 'nullable|string',
            'app_service_hours_monday' => 'nullable|string|max:255',
            'app_service_hours_tuesday' => 'nullable|string|max:255',
            'app_service_hours_wednesday' => 'nullable|string|max:255',
            'app_service_hours_thursday' => 'nullable|string|max:255',
            'app_service_hours_friday' => 'nullable|string|max:255',
            'app_service_hours_saturday' => 'nullable|string|max:255',
            'app_service_hours_sunday' => 'nullable|string|max:255',
            'app_service_hours_holiday' => 'nullable|string|max:255',
        ]);

        Setting::set('app_email_ptsp', $this->app_email_ptsp, 'string', 'Email PTSP');
        Setting::set('app_whatsapp_ptsp', $this->app_whatsapp_ptsp, 'string', 'Kontak WhatsApp PTSP');

        // Save Social Media
        Setting::set('app_social_facebook', $this->app_social_facebook, 'string', 'Link Facebook');
        Setting::set('app_social_instagram', $this->app_social_instagram, 'string', 'Link Instagram');
        Setting::set('app_social_youtube', $this->app_social_youtube, 'string', 'Link Youtube');
        Setting::set('app_social_tiktok', $this->app_social_tiktok, 'string', 'Link Tiktok');

        // Save Maps
        Setting::set('app_maps_link', $this->app_maps_link, 'string', 'Link Google Maps');
        Setting::set('app_maps_embed', $this->app_maps_embed, 'string', 'Embed Google Maps');

        // Save Service Hours
        Setting::set('app_service_hours_monday', $this->app_service_hours_monday, 'string', 'Jam Pelayanan Senin');
        Setting::set('app_service_hours_tuesday', $this->app_service_hours_tuesday, 'string', 'Jam Pelayanan Selasa');
        Setting::set('app_service_hours_wednesday', $this->app_service_hours_wednesday, 'string', 'Jam Pelayanan Rabu');
        Setting::set('app_service_hours_thursday', $this->app_service_hours_thursday, 'string', 'Jam Pelayanan Kamis');
        Setting::set('app_service_hours_friday', $this->app_service_hours_friday, 'string', 'Jam Pelayanan Jumat');
        Setting::set('app_service_hours_saturday', $this->app_service_hours_saturday, 'string', 'Jam Pelayanan Sabtu');
        Setting::set('app_service_hours_sunday', $this->app_service_hours_sunday, 'string', 'Jam Pelayanan Minggu');
        Setting::set('app_service_hours_holiday', $this->app_service_hours_holiday, 'string', 'Jam Pelayanan Hari Besar/Libur');

        $this->dispatch('kontak-saved', message: 'Pengaturan Kontak Global & Media Sosial berhasil disimpan!');
    }

    // --- Panitia List CRUD ---

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $data = KontakPanitia::find($id);
        $this->kontak_id = $data->id;
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

        KontakPanitia::create([
            'nama' => $this->nama,
            'nomor_wa' => $this->nomor_wa,
            'keterangan' => $this->keterangan,
            'urutan' => $this->urutan ?? 0,
            'aktif' => $this->aktif ?? true,
        ]);

        $this->resetErrorBag();
        $this->closeModal();
        $this->dispatch('kontak-saved', message: 'Kontak panitia berhasil ditambahkan.');
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

        if ($this->kontak_id) {
            $kontak = KontakPanitia::find($this->kontak_id);
            if ($kontak) {
                $kontak->update([
                    'nama' => $this->nama,
                    'nomor_wa' => $this->nomor_wa,
                    'keterangan' => $this->keterangan,
                    'urutan' => $this->urutan ?? 0,
                    'aktif' => $this->aktif ?? true,
                ]);
            }
        }

        $this->resetErrorBag();
        $this->closeModal();
        $this->dispatch('kontak-saved', message: 'Kontak panitia berhasil diperbarui.');
    }

    public function delete($id)
    {
        $data = KontakPanitia::find($id);
        if ($data) {
            $data->delete();
            $this->resetErrorBag();
            $this->dispatch('kontak-saved', message: 'Kontak panitia berhasil dihapus.');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->nama = '';
        $this->nomor_wa = '';
        $this->keterangan = '';
        $this->urutan = 0;
        $this->aktif = true;
        $this->kontak_id = null;
    }

    // --- Link Terkait CRUD ---

    public function createLink()
    {
        $this->resetFieldsLink();
        $this->isEditLink = false;
        $this->showModalLink = true;
    }

    public function editLink($id)
    {
        $data = LinkTerkait::find($id);
        $this->link_id = $data->id;
        $this->link_nama = $data->nama;
        $this->link_url = $data->url;
        $this->link_urutan = $data->urutan;
        $this->link_aktif = $data->aktif;
        $this->isEditLink = true;
        $this->showModalLink = true;
    }

    public function storeLink()
    {
        $this->validate([
            'link_nama' => 'required|string|max:255',
            'link_url' => 'required|url|max:255',
            'link_urutan' => 'integer',
            'link_aktif' => 'boolean'
        ]);

        LinkTerkait::create([
            'nama' => $this->link_nama,
            'url' => $this->link_url,
            'urutan' => $this->link_urutan ?? 0,
            'aktif' => $this->link_aktif ?? true,
        ]);

        $this->resetErrorBag();
        $this->closeModalLink();
        $this->dispatch('kontak-saved', message: 'Link terkait berhasil ditambahkan.');
    }

    public function updateLink()
    {
        $this->validate([
            'link_nama' => 'required|string|max:255',
            'link_url' => 'required|url|max:255',
            'link_urutan' => 'integer',
            'link_aktif' => 'boolean'
        ]);

        if ($this->link_id) {
            $link = LinkTerkait::find($this->link_id);
            if ($link) {
                $link->update([
                    'nama' => $this->link_nama,
                    'url' => $this->link_url,
                    'urutan' => $this->link_urutan ?? 0,
                    'aktif' => $this->link_aktif ?? true,
                ]);
            }
        }

        $this->resetErrorBag();
        $this->closeModalLink();
        $this->dispatch('kontak-saved', message: 'Link terkait berhasil diperbarui.');
    }

    public function deleteLink($id)
    {
        $data = LinkTerkait::find($id);
        if ($data) {
            $data->delete();
            $this->resetErrorBag();
            $this->dispatch('kontak-saved', message: 'Link terkait berhasil dihapus.');
        }
    }

    public function closeModalLink()
    {
        $this->showModalLink = false;
        $this->resetErrorBag();
        $this->resetFieldsLink();
    }

    private function resetFieldsLink()
    {
        $this->link_nama = '';
        $this->link_url = '';
        $this->link_urutan = 0;
        $this->link_aktif = true;
        $this->link_id = null;
    }

    public function render()
    {
        $data = KontakPanitia::orderBy('urutan')->get();
        $linkTerkaits = LinkTerkait::orderBy('urutan')->get();
        
        return view('livewire.admin.kontak', compact('data', 'linkTerkaits'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Kontak & Link',
                'pageDescription' => 'Kelola kontak global, panitia, media sosial dan link terkait'
            ]);
    }
}
