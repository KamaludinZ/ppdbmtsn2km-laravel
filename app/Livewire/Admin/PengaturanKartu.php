<?php

namespace App\Livewire\Admin;

use App\Models\JadwalTes;
use App\Models\KartuKendali;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('components.layouts.dashboard')]
class PengaturanKartu extends Component
{
    #[Url(keep: true)]
    public $activeTab = 'jadwal-tes';

    // JadwalTes properties
    public $jadwalTesId = null;
    public $nama_tes = '';
    public $deskripsi = '';
    public $waktu = '';
    public $tempat = '';
    public $urutan_tes = 0;
    public $isEditingJadwal = false;

    // KartuKendali properties
    public $kartuKendaliId = null;
    public $nama_kegiatan = '';
    public $catatan_kendali = '';
    public $urutan_kendali = 0;
    public $isEditingKendali = false;

    // Invoice Tes Psikologi properties
    public $psikologi_header = '';
    public $psikologi_logo = null;
    public $psikologi_footer = '';
    public $psikologi_nomor_format = 'INV-PSI-{TAHUN}-{NOMOR}';

    // Invoice DU Madrasah properties
    public $madrasah_header = '';
    public $madrasah_logo = null;
    public $madrasah_footer = '';
    public $madrasah_nomor_format = 'INV-MDR-{TAHUN}-{NOMOR}';

    // Invoice DU Seragam properties
    public $seragam_header = '';
    public $seragam_logo = null;
    public $seragam_footer = '';
    public $seragam_nomor_format = 'INV-SRG-{TAHUN}-{NOMOR}';

    // Catatan Kartu Ujian
    public $catatan_kartu_ujian = '';

    protected $rules = [
        'nama_tes' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'waktu' => 'required|date',
        'tempat' => 'required|string|max:255',
        'urutan_tes' => 'required|integer|min:0',
    ];

    protected $rulesKendali = [
        'nama_kegiatan' => 'required|string|max:255',
        'catatan_kendali' => 'nullable|string',
        'urutan_kendali' => 'required|integer|min:0',
    ];

    public function mount()
    {
        // Load catatan kartu ujian from settings
        $this->catatan_kartu_ujian = Setting::get('catatan_kartu_ujian', '');
    }

    public function saveCatatanKartu()
    {
        $this->validate([
            'catatan_kartu_ujian' => 'nullable|string'
        ]);

        Setting::set('catatan_kartu_ujian', $this->catatan_kartu_ujian);
        session()->flash('message', 'Catatan kartu tes seleksi berhasil disimpan.');
    }

    public function saveJadwalTes()
    {
        $this->validate($this->rules);

        if ($this->isEditingJadwal && $this->jadwalTesId) {
            $jadwalTes = JadwalTes::findOrFail($this->jadwalTesId);
            $jadwalTes->update([
                'nama_tes' => $this->nama_tes,
                'deskripsi' => $this->deskripsi,
                'waktu' => $this->waktu,
                'tempat' => $this->tempat,
                'urutan' => $this->urutan_tes,
            ]);
            session()->flash('message', 'Jadwal tes berhasil diperbarui.');
        } else {
            JadwalTes::create([
                'nama_tes' => $this->nama_tes,
                'deskripsi' => $this->deskripsi,
                'waktu' => $this->waktu,
                'tempat' => $this->tempat,
                'urutan' => $this->urutan_tes,
            ]);
            session()->flash('message', 'Jadwal tes berhasil ditambahkan.');
        }

        $this->resetJadwalTes();
    }

    public function editJadwalTes($id)
    {
        $jadwalTes = JadwalTes::findOrFail($id);
        $this->jadwalTesId = $jadwalTes->id;
        $this->nama_tes = $jadwalTes->nama_tes;
        $this->deskripsi = $jadwalTes->deskripsi;
        $this->waktu = $jadwalTes->waktu->format('Y-m-d\TH:i');
        $this->tempat = $jadwalTes->tempat;
        $this->urutan_tes = $jadwalTes->urutan;
        $this->isEditingJadwal = true;
    }

    public function deleteJadwalTes($id)
    {
        JadwalTes::findOrFail($id)->delete();
        session()->flash('message', 'Jadwal tes berhasil dihapus.');
    }

    public function resetJadwalTes()
    {
        $this->jadwalTesId = null;
        $this->nama_tes = '';
        $this->deskripsi = '';
        $this->waktu = '';
        $this->tempat = '';
        $this->urutan_tes = 0;
        $this->isEditingJadwal = false;
        $this->resetValidation();
    }

    public function saveKartuKendali()
    {
        $this->validate($this->rulesKendali);

        if ($this->isEditingKendali && $this->kartuKendaliId) {
            $kartuKendali = KartuKendali::findOrFail($this->kartuKendaliId);
            $kartuKendali->update([
                'nama_kegiatan' => $this->nama_kegiatan,
                'catatan' => $this->catatan_kendali,
                'urutan' => $this->urutan_kendali,
            ]);
            session()->flash('message', 'Kartu kendali berhasil diperbarui.');
        } else {
            KartuKendali::create([
                'nama_kegiatan' => $this->nama_kegiatan,
                'catatan' => $this->catatan_kendali,
                'urutan' => $this->urutan_kendali,
            ]);
            session()->flash('message', 'Kartu kendali berhasil ditambahkan.');
        }

        $this->resetKartuKendali();
    }

    public function editKartuKendali($id)
    {
        $kartuKendali = KartuKendali::findOrFail($id);
        $this->kartuKendaliId = $kartuKendali->id;
        $this->nama_kegiatan = $kartuKendali->nama_kegiatan;
        $this->catatan_kendali = $kartuKendali->catatan ?? '';
        $this->urutan_kendali = $kartuKendali->urutan;
        $this->isEditingKendali = true;
    }

    public function deleteKartuKendali($id)
    {
        KartuKendali::findOrFail($id)->delete();
        session()->flash('message', 'Kartu kendali berhasil dihapus.');
    }

    public function resetKartuKendali()
    {
        $this->kartuKendaliId = null;
        $this->nama_kegiatan = '';
        $this->catatan_kendali = '';
        $this->urutan_kendali = 0;
        $this->isEditingKendali = false;
        $this->resetValidation();
    }

    public function render()
    {
        $jadwalTesList = JadwalTes::orderBy('urutan', 'asc')->get();
        $kartuKendaliList = KartuKendali::orderBy('urutan', 'asc')->get();

        // Load kop PDF settings
        $printSettings = [
            'print_logo_left' => Setting::get('print_logo_left'),
            'print_logo_right' => Setting::get('print_logo_right'),
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
            'print_institution_website' => Setting::get('print_institution_website'),
            'tahun_pelajaran' => Setting::get('tahun_pelajaran'),
        ];

        // Reload catatan kartu ujian for preview
        $this->catatan_kartu_ujian = Setting::get('catatan_kartu_ujian', '');

        return view('livewire.admin.pengaturan-kartu', [
            'jadwalTesList' => $jadwalTesList,
            'kartuKendaliList' => $kartuKendaliList,
            'printSettings' => $printSettings,
        ]);
    }
}
