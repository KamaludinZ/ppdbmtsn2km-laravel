<?php

namespace App\Livewire\Admin;

use App\Models\VideoPanduan;
use App\Models\Infografis;
use App\Models\Testimoni;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoInfografis extends Component
{
    use WithPagination, WithFileUploads;

    public $activeTab = 'video'; // 'video', 'infografis', 'testimoni', or 'brosur'

    // Video fields
    public $video_id, $video_judul, $video_deskripsi, $video_url, $video_kategori, $video_aktif = true, $video_urutan = 0;
    public $showVideoForm = false;
    public $isEditingVideo = false;

    // Infografis fields
    public $infografis_id, $infografis_judul, $infografis_deskripsi, $infografis_kategori, $infografis_aktif = true, $infografis_urutan = 0;
    public $infografis_gambar_path;
    public $showInfografisForm = false;
    public $isEditingInfografis = false;
    public $gambar_baru;

    // Testimoni fields
    public $testimoni_id, $testimoni_nama, $testimoni_tahun_lulus, $testimoni_testimoni, $testimoni_institusi_sekarang, $testimoni_aktif = true, $testimoni_urutan = 0;
    public $testimoni_foto_path;
    public $showTestimoniForm = false;
    public $isEditingTestimoni = false;
    public $foto_baru;

    // Brosur fields
    public $brosur_active = false;
    public $brosur_file_path;
    public $brosur_file_baru;

    protected $rules = [
        // Video rules
        'video_judul' => 'required|string|max:200',
        'video_deskripsi' => 'nullable|string',
        'video_url' => 'required|url',
        'video_kategori' => 'required|string|max:100',
        'video_aktif' => 'boolean',
        'video_urutan' => 'integer|min:0',

        // Infografis rules
        'infografis_judul' => 'required|string|max:200',
        'infografis_deskripsi' => 'nullable|string',
        'infografis_kategori' => 'required|string|max:100',
        'infografis_aktif' => 'boolean',
        'infografis_urutan' => 'integer|min:0',
        'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        // Testimoni rules
        'testimoni_nama' => 'required|string|max:200',
        'testimoni_tahun_lulus' => 'required|string|max:10',
        'testimoni_testimoni' => 'required|string',
        'testimoni_institusi_sekarang' => 'nullable|string|max:200',
        'testimoni_aktif' => 'boolean',
        'testimoni_urutan' => 'integer|min:0',
        'foto_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        // Brosur rules
        'brosur_active' => 'boolean',
        'brosur_file_baru' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
    ];

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }

        // Load brosur settings
        $this->loadBrosurSettings();
    }

    public function render()
    {
        $videos = VideoPanduan::orderBy('urutan', 'asc')->paginate(10, ['*'], 'videoPage');
        $infografis = Infografis::orderBy('urutan', 'asc')->paginate(10, ['*'], 'infografisPage');
        $testimoni = Testimoni::orderBy('urutan', 'asc')->paginate(10, ['*'], 'testimoniPage');

        return view('livewire.admin.video-infografis', [
            'videos' => $videos,
            'infografis' => $infografis,
            'testimoni' => $testimoni
        ])->layout('components.layouts.dashboard', [
            'role' => 'admin',
            'pageTitle' => 'Video, Infografis & Testimoni',
            'pageDescription' => 'Kelola video, infografis dan testimoni untuk halaman beranda'
        ]);
    }

    // Video Methods
    public function createVideo()
    {
        $this->resetVideoForm();
        $this->isEditingVideo = false;
        $this->showVideoForm = true;
    }

    public function editVideo($id)
    {
        $video = VideoPanduan::findOrFail($id);

        $this->video_id = $video->id;
        $this->video_judul = $video->judul;
        $this->video_deskripsi = $video->deskripsi;
        $this->video_url = $video->url_video;
        $this->video_kategori = $video->kategori;
        $this->video_aktif = $video->aktif;
        $this->video_urutan = $video->urutan;

        $this->isEditingVideo = true;
        $this->showVideoForm = true;
    }

    public function saveVideo()
    {
        $this->validate([
            'video_judul' => 'required|string|max:200',
            'video_deskripsi' => 'nullable|string',
            'video_url' => 'required|url',
            'video_kategori' => 'required|string|max:100',
            'video_aktif' => 'boolean',
            'video_urutan' => 'integer|min:0',
        ]);

        if ($this->isEditingVideo) {
            $video = VideoPanduan::findOrFail($this->video_id);
            $video->update([
                'judul' => $this->video_judul,
                'deskripsi' => $this->video_deskripsi,
                'url_video' => $this->video_url,
                'kategori' => $this->video_kategori,
                'aktif' => $this->video_aktif,
                'urutan' => $this->video_urutan,
            ]);
            session()->flash('message', 'Video berhasil diperbarui.');
        } else {
            VideoPanduan::create([
                'judul' => $this->video_judul,
                'deskripsi' => $this->video_deskripsi,
                'url_video' => $this->video_url,
                'kategori' => $this->video_kategori,
                'aktif' => $this->video_aktif,
                'urutan' => $this->video_urutan,
            ]);
            session()->flash('message', 'Video berhasil ditambahkan.');
        }

        $this->showVideoForm = false;
        $this->resetVideoForm();
    }

    public function deleteVideo($id)
    {
        $video = VideoPanduan::findOrFail($id);
        $video->delete();
        session()->flash('message', 'Video berhasil dihapus.');
    }

    public function cancelVideo()
    {
        $this->showVideoForm = false;
        $this->resetVideoForm();
    }

    private function resetVideoForm()
    {
        $this->video_id = null;
        $this->video_judul = '';
        $this->video_deskripsi = '';
        $this->video_url = '';
        $this->video_kategori = 'lainnya';
        $this->video_aktif = true;
        $this->video_urutan = 0;
    }

    // Infografis Methods
    public function createInfografis()
    {
        $this->resetInfografisForm();
        $this->isEditingInfografis = false;
        $this->showInfografisForm = true;
    }

    public function editInfografis($id)
    {
        $infografis = Infografis::findOrFail($id);

        $this->infografis_id = $infografis->id;
        $this->infografis_judul = $infografis->judul;
        $this->infografis_deskripsi = $infografis->deskripsi;
        $this->infografis_kategori = $infografis->kategori;
        $this->infografis_aktif = $infografis->aktif;
        $this->infografis_urutan = $infografis->urutan;
        $this->infografis_gambar_path = $infografis->gambar_path;

        $this->isEditingInfografis = true;
        $this->showInfografisForm = true;
    }

    public function saveInfografis()
    {
        $this->validate([
            'infografis_judul' => 'required|string|max:200',
            'infografis_deskripsi' => 'nullable|string',
            'infografis_kategori' => 'required|string|max:100',
            'infografis_aktif' => 'boolean',
            'infografis_urutan' => 'integer|min:0',
            'gambar_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gambarPath = $this->infografis_gambar_path;

        // Handle file upload if provided
        if ($this->gambar_baru) {
            // Delete old image if exists
            if ($this->infografis_gambar_path && Storage::exists($this->infografis_gambar_path)) {
                Storage::delete($this->infografis_gambar_path);
            }

            $gambarPath = $this->gambar_baru->store('infografis', 'public');
        }

        if ($this->isEditingInfografis) {
            $infografis = Infografis::findOrFail($this->infografis_id);
            $infografis->update([
                'judul' => $this->infografis_judul,
                'deskripsi' => $this->infografis_deskripsi,
                'gambar_path' => $gambarPath,
                'kategori' => $this->infografis_kategori,
                'aktif' => $this->infografis_aktif,
                'urutan' => $this->infografis_urutan,
            ]);
            session()->flash('message', 'Infografis berhasil diperbarui.');
        } else {
            Infografis::create([
                'judul' => $this->infografis_judul,
                'deskripsi' => $this->infografis_deskripsi,
                'gambar_path' => $gambarPath,
                'kategori' => $this->infografis_kategori,
                'aktif' => $this->infografis_aktif,
                'urutan' => $this->infografis_urutan,
            ]);
            session()->flash('message', 'Infografis berhasil ditambahkan.');
        }

        $this->showInfografisForm = false;
        $this->resetInfografisForm();
    }

    public function deleteInfografis($id)
    {
        $infografis = Infografis::findOrFail($id);

        // Delete the image file if it exists
        if ($infografis->gambar_path && Storage::exists($infografis->gambar_path)) {
            Storage::delete($infografis->gambar_path);
        }

        $infografis->delete();
        session()->flash('message', 'Infografis berhasil dihapus.');
    }

    public function cancelInfografis()
    {
        $this->showInfografisForm = false;
        $this->resetInfografisForm();
    }

    private function resetInfografisForm()
    {
        $this->infografis_id = null;
        $this->infografis_judul = '';
        $this->infografis_deskripsi = '';
        $this->infografis_kategori = 'umum';
        $this->infografis_aktif = true;
        $this->infografis_urutan = 0;
        $this->infografis_gambar_path = '';
        $this->gambar_baru = null;
    }

    // Testimoni Methods
    public function createTestimoni()
    {
        $this->resetTestimoniForm();
        $this->isEditingTestimoni = false;
        $this->showTestimoniForm = true;
    }

    public function editTestimoni($id)
    {
        $testimoni = Testimoni::findOrFail($id);

        $this->testimoni_id = $testimoni->id;
        $this->testimoni_nama = $testimoni->nama;
        $this->testimoni_tahun_lulus = $testimoni->tahun_lulus;
        $this->testimoni_testimoni = $testimoni->testimoni;
        $this->testimoni_institusi_sekarang = $testimoni->institusi_sekarang;
        $this->testimoni_aktif = $testimoni->aktif;
        $this->testimoni_urutan = $testimoni->urutan;
        $this->testimoni_foto_path = $testimoni->foto_path;

        $this->isEditingTestimoni = true;
        $this->showTestimoniForm = true;
    }

    public function saveTestimoni()
    {
        $this->validate([
            'testimoni_nama' => 'required|string|max:200',
            'testimoni_tahun_lulus' => 'required|string|max:10',
            'testimoni_testimoni' => 'required|string',
            'testimoni_institusi_sekarang' => 'nullable|string|max:200',
            'testimoni_aktif' => 'boolean',
            'testimoni_urutan' => 'integer|min:0',
            'foto_baru' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fotoPath = $this->testimoni_foto_path;

        // Handle file upload if provided
        if ($this->foto_baru) {
            // Delete old image if exists
            if ($this->testimoni_foto_path && Storage::exists($this->testimoni_foto_path)) {
                Storage::delete($this->testimoni_foto_path);
            }

            $fotoPath = $this->foto_baru->store('testimoni', 'public');
        }

        if ($this->isEditingTestimoni) {
            $testimoni = Testimoni::findOrFail($this->testimoni_id);
            $testimoni->update([
                'nama' => $this->testimoni_nama,
                'tahun_lulus' => $this->testimoni_tahun_lulus,
                'foto_path' => $fotoPath,
                'testimoni' => $this->testimoni_testimoni,
                'institusi_sekarang' => $this->testimoni_institusi_sekarang,
                'aktif' => $this->testimoni_aktif,
                'urutan' => $this->testimoni_urutan,
            ]);
            session()->flash('message', 'Testimoni berhasil diperbarui.');
        } else {
            Testimoni::create([
                'nama' => $this->testimoni_nama,
                'tahun_lulus' => $this->testimoni_tahun_lulus,
                'foto_path' => $fotoPath,
                'testimoni' => $this->testimoni_testimoni,
                'institusi_sekarang' => $this->testimoni_institusi_sekarang,
                'aktif' => $this->testimoni_aktif,
                'urutan' => $this->testimoni_urutan,
            ]);
            session()->flash('message', 'Testimoni berhasil ditambahkan.');
        }

        $this->showTestimoniForm = false;
        $this->resetTestimoniForm();
    }

    public function deleteTestimoni($id)
    {
        $testimoni = Testimoni::findOrFail($id);

        // Delete the image file if it exists
        if ($testimoni->foto_path && Storage::exists($testimoni->foto_path)) {
            Storage::delete($testimoni->foto_path);
        }

        $testimoni->delete();
        session()->flash('message', 'Testimoni berhasil dihapus.');
    }

    public function cancelTestimoni()
    {
        $this->showTestimoniForm = false;
        $this->resetTestimoniForm();
    }

    private function resetTestimoniForm()
    {
        $this->testimoni_id = null;
        $this->testimoni_nama = '';
        $this->testimoni_tahun_lulus = '';
        $this->testimoni_testimoni = '';
        $this->testimoni_institusi_sekarang = '';
        $this->testimoni_aktif = true;
        $this->testimoni_urutan = 0;
        $this->testimoni_foto_path = '';
        $this->foto_baru = null;
    }

    // Brosur Methods
    public function loadBrosurSettings()
    {
        $this->brosur_file_path = Setting::get('brosur_file_path');
        $this->brosur_active = Setting::get('brosur_active', false);
    }

    public function saveBrosur()
    {
        $this->validate([
            'brosur_active' => 'boolean',
            'brosur_file_baru' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'brosur_file_baru.file' => 'File yang dipilih tidak valid. Pastikan Anda memilih file yang benar.',
            'brosur_file_baru.mimes' => 'File harus berformat PDF.',
            'brosur_file_baru.max' => 'Ukuran file maksimal 10 MB. File Anda terlalu besar.',
            'brosur_file_baru.uploaded' => 'Upload gagal. File terlalu besar atau koneksi terputus. Ukuran maksimal: 10 MB. Pastikan koneksi internet stabil dan coba lagi.',
        ]);

        $filePath = $this->brosur_file_path;

        // Handle file upload if provided
        if ($this->brosur_file_baru) {
            // Delete old file if exists
            if ($this->brosur_file_path && Storage::disk('public')->exists($this->brosur_file_path)) {
                Storage::disk('public')->delete($this->brosur_file_path);
            }

            $filePath = $this->brosur_file_baru->store('brosur', 'public');
        }

        // Save settings
        Setting::set('brosur_file_path', $filePath, 'string', 'Path to brochure PDF file');
        Setting::set('brosur_active', $this->brosur_active, 'boolean', 'Show/hide brochure download button on public pages');

        $this->brosur_file_path = $filePath;
        $this->brosur_file_baru = null;

        session()->flash('message', 'Pengaturan brosur berhasil disimpan.');
    }

    public function deleteBrosur()
    {
        if ($this->brosur_file_path && Storage::disk('public')->exists($this->brosur_file_path)) {
            Storage::disk('public')->delete($this->brosur_file_path);
        }

        Setting::set('brosur_file_path', null, 'string', 'Path to brochure PDF file');
        Setting::set('brosur_active', false, 'boolean', 'Show/hide brochure download button on public pages');

        $this->brosur_file_path = null;
        $this->brosur_active = false;

        session()->flash('message', 'Brosur berhasil dihapus.');
    }
}
