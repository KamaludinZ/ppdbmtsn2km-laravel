<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use App\Models\JadwalTes;
use App\Models\KartuKendali;
use App\Models\Setting;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class PreviewKartuTes extends Component
{
    public $pendaftar;
    public $appAcronym;
    public $alamatLengkap;
    public $linkGrupUrl = '';
    public $linkGrupEnabled = false;
    public $isPreviewMode = true; // Flag untuk mode preview admin

    public function mount($pendaftar_id)
    {
        // Load pendaftar berdasarkan ID
        $this->pendaftar = Pendaftar::with(['jalurPendaftaran', 'tesPsikologiPendaftar'])->findOrFail($pendaftar_id);

        // Load app acronym
        $appName = Setting::get('app_name', 'PPDB');
        $words = explode(' ', $appName);
        $this->appAcronym = '';
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $this->appAcronym .= strtoupper($word[0]);
            }
        }

        // Load link grup settings
        $this->linkGrupUrl = Setting::get('link_grup_url', '');
        $this->linkGrupEnabled = Setting::get('link_grup_enabled', false);

        $this->generateAlamatLengkap();
    }

    private function generateAlamatLengkap()
    {
        $parts = [];

        // 1. Alamat Jalan
        $alamatJalan = $this->pendaftar->siswa_alamat ?: $this->pendaftar->alamat;
        if ($alamatJalan) $parts[] = $alamatJalan;

        // 2. RT/RW
        $rt = $this->pendaftar->siswa_rt ?: $this->pendaftar->rt;
        $rw = $this->pendaftar->siswa_rw ?: $this->pendaftar->rw;
        if ($rt && $rw) {
            $parts[] = "RT. $rt / RW. $rw";
        }

        // 3. Desa / Kelurahan
        $desaId = $this->pendaftar->siswa_kelurahan ?: $this->pendaftar->desa;
        if ($desaId) {
            $desaName = is_numeric($desaId) ? Village::find($desaId)?->name : $desaId;
            if ($desaName) $parts[] = "Desa/Kel. $desaName";
        }

        // 4. Kecamatan
        $kecId = $this->pendaftar->siswa_kecamatan ?: $this->pendaftar->kecamatan;
        if ($kecId) {
            $kecName = is_numeric($kecId) ? District::find($kecId)?->name : $kecId;
            if ($kecName) $parts[] = "Kec. $kecName";
        }

        // 5. Kabupaten / Kota
        $kabId = $this->pendaftar->siswa_kabupaten ?: $this->pendaftar->kabupaten;
        if ($kabId) {
            $kabName = is_numeric($kabId) ? Regency::find($kabId)?->name : $kabId;
            if ($kabName) $parts[] = $kabName;
        }

        // 6. Provinsi
        $provId = $this->pendaftar->siswa_provinsi ?: $this->pendaftar->provinsi;
        if ($provId) {
            $provName = is_numeric($provId) ? Province::find($provId)?->name : $provId;
            if ($provName) $parts[] = $provName;
        }

        $this->alamatLengkap = !empty($parts) ? implode(', ', $parts) : '-';
    }

    public function render()
    {
        // Load jadwal tes and kartu kendali
        $jadwalTesList = JadwalTes::orderBy('urutan', 'asc')->get();
        $kartuKendaliList = KartuKendali::orderBy('urutan', 'asc')->get();

        // Load print settings
        $printSettings = [
            'print_logo_left' => Setting::get('print_logo_left'),
            'print_logo_right' => Setting::get('print_logo_right'),
            'print_institution_name' => Setting::get('print_institution_name'),
            'print_institution_address' => Setting::get('print_institution_address'),
            'print_institution_phone' => Setting::get('print_institution_phone'),
            'print_institution_email' => Setting::get('print_institution_email'),
            'print_institution_website' => Setting::get('print_institution_website'),
            'tahun_pelajaran' => Setting::get('tahun_pelajaran', date('Y') . '/' . (date('Y') + 1)),
        ];

        // Load catatan kartu tes seleksi
        $catatanKartuTesSeleksi = Setting::get('catatan_kartu_ujian', '');

        return view('livewire.pendaftar.kartu-tes-seleksi', [
            'jadwalTesList' => $jadwalTesList,
            'kartuKendaliList' => $kartuKendaliList,
            'printSettings' => $printSettings,
            'catatanKartuTesSeleksi' => $catatanKartuTesSeleksi,
        ])
            ->layout('components.layouts.print', [
                'pageTitle' => 'Preview Kartu Tes Seleksi - ' . $this->pendaftar->nama_lengkap,
            ]);
    }
}
