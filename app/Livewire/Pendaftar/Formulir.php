<?php

namespace App\Livewire\Pendaftar;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use App\Models\Pendaftar as PendaftarModel;
use App\Models\Prestasi;
use App\Models\Beasiswa;
use App\Models\PendidikanLain;
use App\Models\Keahlian;
use App\Models\Tahfidz;
use App\Models\VerifikasiBerkas;
use App\Models\Dokumen;
use App\Services\ImageService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class Formulir extends Component
{
    use WithFileUploads;

    public $pendaftar;
    public $verifikasi;
    public $dokumen;

    // Active Tab with URL support
    #[Url]
    public $activeTab = 'siswa';

    // Mode edit per section
    public $editModeSiswa = false;
    public $editModeOrangtua = false;  // Changed from OrangTua to Orangtua (lowercase t)
    public $editModeAlamat = false;
    public $editModeSekolah = false;
    public $editModeRapor = false;
    public $editModePrestasi = false;
    public $editModeDataPrestasi = false;
    public $editModeDataBeasiswa = false;
    public $editModeDataPendidikanLain = false;
    public $editModeBeasiswa = false;
    public $editModePendidikan = false;
    public $editModeSurvey = false;
    public $editModeDataKeahlian = false;
    public $editModeDataTahfidz = false;

    // Survey Answers (dynamic properties for survey_answer_X)
    public $surveyAnswers = [];

    // Prestasi List (for structured prestasi data)
    public $prestasiList = [];

    // Beasiswa List (for structured beasiswa data)
    public $beasiswaList = [];

    // Pendidikan Lain List (for structured pendidikan lain data)
    public $pendidikanLainList = [];

    // Keahlian List (for structured keahlian data)
    public $keahlianList = [];

    // Tahfidz Data (single record)
    public $tahfidz = [
        'id' => null,
        'juz_alquran_dihafal' => null,
        'file_bukti_syahadah' => null,
        'file_bukti_tahsin' => null,
    ];

    // Upload properties for Keahlian & Tahfidz
    public $uploadKeahlianSertifikat = [];
    public $uploadTahfidzSyahadah;
    public $uploadTahfidzTahsin;

    // Upload properties for Prestasi
    public $uploadPrestasiSertifikat = [];

    // Upload properties
    public $file_rapor;
    public $file_akte;
    public $file_kk;
    public $file_nisn;
    public $file_ijazah;
    public $file_sertifikat;
    public $file_kip;

    // Data Pribadi
    public $nama_lengkap;
    public $nisn;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $agama;
    public $foto;
    public $newFoto;

    // Additional Data Siswa
    public $no_akta_kelahiran;
    public $jumlah_saudara_tiri;
    public $golongan_darah;
    public $tinggi_badan;
    public $berat_badan;
    public $bahasa_sehari_hari;
    public $yatim_piatu;
    public $keterangan_kondisi_khusus;

    // Data Orang Tua
    public $nama_ayah;
    public $nama_ibu;
    public $pekerjaan_ayah;
    public $pekerjaan_ibu;
    public $penghasilan_ortu;
    public $telepon_ortu;

    // Data Alamat (Extended)
    public $alamat; // Alamat Siswa
    public $alamat_ayah;
    public $alamat_ibu;
    public $alamat_wali;
    public $provinsi;
    public $kabupaten;
    public $kecamatan;
    public $desa;
    public $kode_pos;
    public $jarak_ke_sekolah;
    public $transportasi;

    // Ayah Address Fields
    public $ayah_tinggal_luar_negeri;
    public $ayah_status_kepemilikan_rumah;
    public $ayah_provinsi;
    public $ayah_kabupaten;
    public $ayah_kecamatan;
    public $ayah_kelurahan;
    public $ayah_rt;
    public $ayah_rw;
    public $ayah_kode_pos;

    // Ibu Address Fields
    public $ibu_tinggal_luar_negeri;
    public $ibu_sama_dengan_ayah;
    public $ibu_status_kepemilikan_rumah;
    public $ibu_provinsi;
    public $ibu_kabupaten;
    public $ibu_kecamatan;
    public $ibu_kelurahan;
    public $ibu_rt;
    public $ibu_rw;
    public $ibu_kode_pos;
    public $ibu_alamat;

    // Wali Address Fields
    public $wali_status_wali;
    public $wali_tinggal_luar_negeri;
    public $wali_sama_dengan_ayah;

    // Flag untuk menandai sedang copy data
    public $skipCascadeReset = false;
    public $wali_status_kepemilikan_rumah;
    public $wali_provinsi;
    public $wali_kabupaten;
    public $wali_kecamatan;
    public $wali_kelurahan;
    public $wali_rt;
    public $wali_rw;
    public $wali_kode_pos;

    // Siswa Address Fields
    public $status_tempat_tinggal;
    public $siswa_status_kepemilikan_rumah;
    public $siswa_provinsi;
    public $siswa_kabupaten;
    public $siswa_kecamatan;
    public $siswa_kelurahan;
    public $siswa_rt;
    public $siswa_rw;

    // Cascade dropdown data collections
    public $provincesList = [];
    public $ayahCitiesList = [];
    public $ayahDistrictsList = [];
    public $ayahVillagesList = [];
    public $ibuCitiesList = [];
    public $ibuDistrictsList = [];
    public $ibuVillagesList = [];
    public $waliCitiesList = [];
    public $waliDistrictsList = [];
    public $waliVillagesList = [];
    public $siswaCitiesList = [];
    public $siswaDistrictsList = [];
    public $siswaVillagesList = [];
    public $siswa_kode_pos;
    public $siswa_alamat;

    public $asal_kabkota;
    public $telepon;
    public $no_hp;
    public $email;

    // Data Sekolah
    public $asal_sekolah;
    public $jenis_sekolah_asal;
    public $nsm_sekolah_asal;
    public $npsn_sekolah_asal;
    public $alamat_sekolah;
    public $nilai_rata_rata;

    // Data Rapor
    public $nilai_b_indo_5_1;
    public $nilai_matematika_5_1;
    public $nilai_ipa_5_1;
    public $nilai_b_indo_5_2;
    public $nilai_matematika_5_2;
    public $nilai_ipa_5_2;
    public $nilai_b_indo_6_1;
    public $nilai_matematika_6_1;
    public $nilai_ipa_6_1;

    // Data Prestasi
    public $prestasi;

    // EMIS - Data Identitas
    public $nik;
    public $nama_panggilan;
    public $kewarganegaraan;
    public $negara_asal;
    public $nomor_kitas;
    public $nomor_kk;
    public $nama_kepala_keluarga;
    public $anak_ke;
    public $jumlah_saudara_kandung;
    public $jumlah_saudara;
    public $nomor_telepon_rumah;
    public $email_pribadi;
    public $tidak_punya_hp;
    public $pra_sekolah_tk;
    public $pra_sekolah_paud;
    public $imunisasi_hepatitis_b;
    public $imunisasi_bcg;
    public $imunisasi_dpt;
    public $imunisasi_polio;
    public $imunisasi_campak;
    public $imunisasi_covid;
    public $jenis_tinggal;
    public $alat_transportasi;
    public $jarak_tempat_tinggal;
    public $waktu_tempuh;
    public $penerima_kps;
    public $nomor_kps;
    public $penerima_kip;
    public $nomor_kip;
    public $layak_pip;
    public $alasan_layak_pip;

    // EMIS - Data Alamat Detail
    public $rt;
    public $rw;
    public $dusun;
    public $latitude;
    public $longitude;

    // EMIS - Data Ayah
    public $ayah_nik;
    public $ayah_tahun_lahir;
    public $ayah_pendidikan;
    public $ayah_penghasilan;
    public $ayah_no_hp;
    public $ayah_berkebutuhan_khusus;

    // EMIS - Data Ibu
    public $ibu_nik;
    public $ibu_tahun_lahir;
    public $ibu_pendidikan;
    public $ibu_penghasilan;
    public $ibu_no_hp;
    public $ibu_berkebutuhan_khusus;

    // EMIS - Data Wali
    public $memiliki_wali;
    public $wali_nik;
    public $wali_nama;
    public $wali_tahun_lahir;
    public $wali_pendidikan;
    public $wali_pekerjaan;
    public $wali_penghasilan;
    public $wali_no_hp;

    // Tab Data Orang Tua & Wali - Ayah
    public $ayah_status;
    public $ayah_kewarganegaraan;
    public $ayah_negara_asal;
    public $ayah_nomor_kitas;
    public $ayah_tempat_lahir;
    public $ayah_tanggal_lahir;
    public $ayah_pekerjaan;
    public $ayah_tidak_punya_hp;

    // Tab Data Orang Tua & Wali - Ibu
    public $ibu_status;
    public $ibu_kewarganegaraan;
    public $ibu_negara_asal;
    public $ibu_nomor_kitas;
    public $ibu_tempat_lahir;
    public $ibu_tanggal_lahir;
    public $ibu_pekerjaan;
    public $ibu_tidak_punya_hp;

    // Tab Data Orang Tua & Wali - Wali
    public $wali_hubungan;
    public $wali_status;
    public $wali_kewarganegaraan;
    public $wali_negara_asal;
    public $wali_nomor_kitas;
    public $wali_tempat_lahir;
    public $wali_tanggal_lahir;
    public $wali_tidak_punya_hp;

    // Tab Data Orang Tua & Wali - Kartu Bantuan
    public $nomor_kks;
    public $nomor_pkh;

    // EMIS - Riwayat Pendidikan
    public $tk_nama;
    public $tk_npsn;
    public $tk_tahun_lulus;
    public $sd_nama;
    public $sd_npsn;
    public $sd_tahun_lulus;
    public $sd_nomor_peserta_ujian;
    public $sd_nomor_seri_ijazah;
    public $sd_nomor_skhun;
    public $sd_nilai_matematika;
    public $sd_nilai_ipa;
    public $sd_nilai_ips;
    public $sd_nilai_bahasa_indonesia;
    public $sd_nilai_bahasa_inggris;
    public $hobi;
    public $cita_cita;
    public $yang_membiayai_sekolah;
    public $berkebutuhan_khusus;
    public $jenis_kebutuhan_khusus = [];

    // EMIS - File uploads (read-only properties, actual upload handled in Dokumen.php)
    public $file_ijazah_sd;
    public $file_skhun_sd;
    public $file_kartu_keluarga;
    public $file_akta_kelahiran;

    // Magic methods for dynamic survey answer properties
    public function __get($property)
    {
        if (str_starts_with($property, 'survey_answer_')) {
            $questionId = str_replace('survey_answer_', '', $property);
            return $this->surveyAnswers[$questionId] ?? null;
        }
        return null;
    }

    public function __set($property, $value)
    {
        if (str_starts_with($property, 'survey_answer_')) {
            $questionId = str_replace('survey_answer_', '', $property);
            $this->surveyAnswers[$questionId] = $value;
        }
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'pendaftar') {
            return redirect()->to('/login');
        }

        $this->pendaftar = $user->pendaftar()->with('surveyAnswers.question')->first();
        if (!$this->pendaftar) {
            return redirect()->to('/daftar');
        }

        $this->loadVerifikasi();
        $this->loadDokumen();
        $this->loadDataToProperties();
    }

    public function loadVerifikasi()
    {
        $this->verifikasi = VerifikasiBerkas::firstOrCreate(
            ['pendaftar_id' => $this->pendaftar->id],
            [
                'status' => 'menunggu',
                'status_rapor' => 'belum_upload',
                'status_akte' => 'belum_upload',
                'status_kk' => 'belum_upload',
                'status_nisn' => 'belum_upload',
                'status_ijazah' => 'belum_upload',
                'status_sertifikat' => 'belum_upload',
                'status_kip' => 'belum_upload',
            ]
        );
    }

    public function loadDokumen()
    {
        $this->dokumen = Dokumen::where('pendaftar_id', $this->pendaftar->id)->get();
    }

    public function loadDataToProperties()
    {
        // Data Pribadi
        $this->nama_lengkap = $this->pendaftar->nama_lengkap;
        $this->nisn = $this->pendaftar->nisn;
        $this->tempat_lahir = $this->pendaftar->tempat_lahir;
        $this->tanggal_lahir = $this->pendaftar->tanggal_lahir ? $this->pendaftar->tanggal_lahir->format('Y-m-d') : null;
        $this->jenis_kelamin = $this->pendaftar->jenis_kelamin;
        $this->agama = $this->pendaftar->agama;
        $this->foto = $this->pendaftar->foto;

        // Additional Data Siswa
        $this->no_akta_kelahiran = $this->pendaftar->no_akta_kelahiran;
        $this->jumlah_saudara_tiri = $this->pendaftar->jumlah_saudara_tiri;
        $this->golongan_darah = $this->pendaftar->golongan_darah;
        $this->tinggi_badan = $this->pendaftar->tinggi_badan;
        $this->berat_badan = $this->pendaftar->berat_badan;
        $this->bahasa_sehari_hari = $this->pendaftar->bahasa_sehari_hari;
        $this->yatim_piatu = $this->pendaftar->yatim_piatu;
        $this->keterangan_kondisi_khusus = $this->pendaftar->keterangan_kondisi_khusus;

        // Data Orang Tua
        $this->nama_ayah = $this->pendaftar->nama_ayah;
        $this->nama_ibu = $this->pendaftar->nama_ibu;
        $this->pekerjaan_ayah = $this->pendaftar->pekerjaan_ayah;
        $this->pekerjaan_ibu = $this->pendaftar->pekerjaan_ibu;
        $this->penghasilan_ortu = $this->pendaftar->penghasilan_ortu;
        $this->telepon_ortu = $this->pendaftar->telepon_ortu;

        // Data Alamat
        $this->alamat = $this->pendaftar->alamat;
        $this->alamat_ayah = $this->pendaftar->alamat_ayah;
        // alamat_ibu dimuat di line 396 ke property ibu_alamat
        $this->alamat_wali = $this->pendaftar->alamat_wali;
        $this->provinsi = $this->pendaftar->provinsi;
        $this->kabupaten = $this->pendaftar->kabupaten;
        $this->kecamatan = $this->pendaftar->kecamatan;
        $this->desa = $this->pendaftar->desa;
        $this->kode_pos = $this->pendaftar->kode_pos;
        $this->jarak_ke_sekolah = $this->pendaftar->jarak_ke_sekolah;
        $this->transportasi = $this->pendaftar->transportasi;
        $this->waktu_tempuh = $this->pendaftar->waktu_tempuh;

        // Ayah Address
        $this->ayah_tinggal_luar_negeri = $this->pendaftar->ayah_tinggal_luar_negeri ?? false;
        $this->ayah_status_kepemilikan_rumah = $this->pendaftar->ayah_status_kepemilikan_rumah;
        $this->ayah_provinsi = $this->pendaftar->ayah_provinsi;
        $this->ayah_kabupaten = $this->pendaftar->ayah_kabupaten;
        $this->ayah_kecamatan = $this->pendaftar->ayah_kecamatan;
        $this->ayah_kelurahan = $this->pendaftar->ayah_kelurahan;
        $this->ayah_rt = $this->pendaftar->ayah_rt;
        $this->ayah_rw = $this->pendaftar->ayah_rw;
        $this->ayah_kode_pos = $this->pendaftar->ayah_kode_pos;

        // Ibu Address
        $this->ibu_tinggal_luar_negeri = $this->pendaftar->ibu_tinggal_luar_negeri ?? false;
        $this->ibu_sama_dengan_ayah = $this->pendaftar->ibu_sama_dengan_ayah ?? false;
        $this->ibu_status_kepemilikan_rumah = $this->pendaftar->ibu_status_kepemilikan_rumah;
        $this->ibu_provinsi = $this->pendaftar->ibu_provinsi;
        $this->ibu_kabupaten = $this->pendaftar->ibu_kabupaten;
        $this->ibu_kecamatan = $this->pendaftar->ibu_kecamatan;
        $this->ibu_kelurahan = $this->pendaftar->ibu_kelurahan;
        $this->ibu_rt = $this->pendaftar->ibu_rt;
        $this->ibu_rw = $this->pendaftar->ibu_rw;
        $this->ibu_kode_pos = $this->pendaftar->ibu_kode_pos;
        $this->ibu_alamat = $this->pendaftar->alamat_ibu;

        // Wali Address
        $this->wali_status_wali = $this->pendaftar->wali_status_wali;
        $this->wali_tinggal_luar_negeri = $this->pendaftar->wali_tinggal_luar_negeri ?? false;
        $this->wali_sama_dengan_ayah = $this->pendaftar->wali_sama_dengan_ayah ?? false;
        $this->wali_status_kepemilikan_rumah = $this->pendaftar->wali_status_kepemilikan_rumah;
        $this->wali_provinsi = $this->pendaftar->wali_provinsi;
        $this->wali_kabupaten = $this->pendaftar->wali_kabupaten;
        $this->wali_kecamatan = $this->pendaftar->wali_kecamatan;
        $this->wali_kelurahan = $this->pendaftar->wali_kelurahan;
        $this->wali_rt = $this->pendaftar->wali_rt;
        $this->wali_rw = $this->pendaftar->wali_rw;
        $this->wali_kode_pos = $this->pendaftar->wali_kode_pos;

        // Siswa Address
        $this->status_tempat_tinggal = $this->pendaftar->status_tempat_tinggal;
        $this->siswa_status_kepemilikan_rumah = $this->pendaftar->siswa_status_kepemilikan_rumah;
        $this->siswa_provinsi = $this->pendaftar->siswa_provinsi;
        $this->siswa_kabupaten = $this->pendaftar->siswa_kabupaten;
        $this->siswa_kecamatan = $this->pendaftar->siswa_kecamatan;
        $this->siswa_kelurahan = $this->pendaftar->siswa_kelurahan;
        $this->siswa_rt = $this->pendaftar->siswa_rt;
        $this->siswa_rw = $this->pendaftar->siswa_rw;
        $this->siswa_kode_pos = $this->pendaftar->siswa_kode_pos;
        $this->siswa_alamat = $this->pendaftar->siswa_alamat;

        $this->asal_kabkota = $this->pendaftar->asal_kabkota;
        $this->telepon = $this->pendaftar->telepon;
        $this->no_hp = $this->pendaftar->no_hp;
        $this->email = $this->pendaftar->email;

        // Data Sekolah
        $this->asal_sekolah = $this->pendaftar->asal_sekolah;
        $this->jenis_sekolah_asal = $this->pendaftar->jenis_sekolah_asal;
        $this->nsm_sekolah_asal = $this->pendaftar->nsm_sekolah_asal;
        $this->npsn_sekolah_asal = $this->pendaftar->npsn_sekolah_asal;
        $this->alamat_sekolah = $this->pendaftar->alamat_sekolah;
        $this->nilai_rata_rata = $this->pendaftar->nilai_rata_rata;

        // Data Rapor
        $this->nilai_b_indo_5_1 = $this->pendaftar->nilai_b_indo_5_1;
        $this->nilai_matematika_5_1 = $this->pendaftar->nilai_matematika_5_1;
        $this->nilai_ipa_5_1 = $this->pendaftar->nilai_ipa_5_1;
        $this->nilai_b_indo_5_2 = $this->pendaftar->nilai_b_indo_5_2;
        $this->nilai_matematika_5_2 = $this->pendaftar->nilai_matematika_5_2;
        $this->nilai_ipa_5_2 = $this->pendaftar->nilai_ipa_5_2;
        $this->nilai_b_indo_6_1 = $this->pendaftar->nilai_b_indo_6_1;
        $this->nilai_matematika_6_1 = $this->pendaftar->nilai_matematika_6_1;
        $this->nilai_ipa_6_1 = $this->pendaftar->nilai_ipa_6_1;

        // Data Prestasi
        $this->prestasi = $this->pendaftar->prestasi;

        // EMIS - Data Identitas
        $this->nik = $this->pendaftar->nik;
        $this->nama_panggilan = $this->pendaftar->nama_panggilan;
        $this->kewarganegaraan = $this->pendaftar->kewarganegaraan ?? 'WNI';
        $this->negara_asal = $this->pendaftar->negara_asal;
        $this->nomor_kitas = $this->pendaftar->nomor_kitas;
        $this->nomor_kk = $this->pendaftar->nomor_kk;
        $this->nama_kepala_keluarga = $this->pendaftar->nama_kepala_keluarga;
        $this->anak_ke = $this->pendaftar->anak_ke;
        $this->jumlah_saudara_kandung = $this->pendaftar->jumlah_saudara_kandung;
        $this->jumlah_saudara = $this->pendaftar->jumlah_saudara;
        $this->nomor_telepon_rumah = $this->pendaftar->nomor_telepon_rumah;
        $this->email_pribadi = $this->pendaftar->email_pribadi;
        $this->tidak_punya_hp = $this->pendaftar->tidak_punya_hp;
        $this->jenis_tinggal = $this->pendaftar->jenis_tinggal;
        $this->alat_transportasi = $this->pendaftar->alat_transportasi;
        $this->jarak_tempat_tinggal = $this->pendaftar->jarak_tempat_tinggal;
        $this->waktu_tempuh = $this->pendaftar->waktu_tempuh;
        $this->penerima_kps = $this->pendaftar->penerima_kps;
        $this->nomor_kps = $this->pendaftar->nomor_kps;
        $this->penerima_kip = $this->pendaftar->penerima_kip;
        $this->nomor_kip = $this->pendaftar->nomor_kip;
        $this->layak_pip = $this->pendaftar->layak_pip;
        $this->alasan_layak_pip = $this->pendaftar->alasan_layak_pip;

        // EMIS - Data Alamat Detail
        $this->rt = $this->pendaftar->rt;
        $this->rw = $this->pendaftar->rw;
        $this->dusun = $this->pendaftar->dusun;
        $this->latitude = $this->pendaftar->latitude;
        $this->longitude = $this->pendaftar->longitude;

        // EMIS - Data Ayah
        $this->ayah_nik = $this->pendaftar->ayah_nik;
        $this->ayah_tahun_lahir = $this->pendaftar->ayah_tahun_lahir;
        $this->ayah_pendidikan = $this->pendaftar->ayah_pendidikan;
        $this->ayah_penghasilan = $this->pendaftar->ayah_penghasilan;
        $this->ayah_no_hp = $this->pendaftar->ayah_no_hp;
        $this->ayah_berkebutuhan_khusus = $this->pendaftar->ayah_berkebutuhan_khusus;

        // EMIS - Data Ibu
        $this->ibu_nik = $this->pendaftar->ibu_nik;
        $this->ibu_tahun_lahir = $this->pendaftar->ibu_tahun_lahir;
        $this->ibu_pendidikan = $this->pendaftar->ibu_pendidikan;
        $this->ibu_penghasilan = $this->pendaftar->ibu_penghasilan;
        $this->ibu_no_hp = $this->pendaftar->ibu_no_hp;
        $this->ibu_berkebutuhan_khusus = $this->pendaftar->ibu_berkebutuhan_khusus;

        // EMIS - Data Wali
        $this->memiliki_wali = $this->pendaftar->memiliki_wali;
        $this->wali_nik = $this->pendaftar->wali_nik;
        $this->wali_nama = $this->pendaftar->wali_nama;
        $this->wali_tahun_lahir = $this->pendaftar->wali_tahun_lahir;
        $this->wali_pendidikan = $this->pendaftar->wali_pendidikan;
        $this->wali_pekerjaan = $this->pendaftar->wali_pekerjaan;
        $this->wali_penghasilan = $this->pendaftar->wali_penghasilan;
        $this->wali_no_hp = $this->pendaftar->wali_no_hp;

        // Tab Data Orang Tua & Wali - Ayah
        $this->ayah_status = $this->pendaftar->ayah_status;
        $this->ayah_kewarganegaraan = $this->pendaftar->ayah_kewarganegaraan;
        $this->ayah_negara_asal = $this->pendaftar->ayah_negara_asal;
        $this->ayah_nomor_kitas = $this->pendaftar->ayah_nomor_kitas;
        $this->ayah_tempat_lahir = $this->pendaftar->ayah_tempat_lahir;
        $this->ayah_tanggal_lahir = $this->pendaftar->ayah_tanggal_lahir ? $this->pendaftar->ayah_tanggal_lahir->format('Y-m-d') : null;
        $this->ayah_pekerjaan = $this->pendaftar->ayah_pekerjaan;
        $this->ayah_tidak_punya_hp = $this->pendaftar->ayah_tidak_punya_hp;

        // Tab Data Orang Tua & Wali - Ibu
        $this->ibu_status = $this->pendaftar->ibu_status;
        $this->ibu_kewarganegaraan = $this->pendaftar->ibu_kewarganegaraan;
        $this->ibu_negara_asal = $this->pendaftar->ibu_negara_asal;
        $this->ibu_nomor_kitas = $this->pendaftar->ibu_nomor_kitas;
        $this->ibu_tempat_lahir = $this->pendaftar->ibu_tempat_lahir;
        $this->ibu_tanggal_lahir = $this->pendaftar->ibu_tanggal_lahir ? $this->pendaftar->ibu_tanggal_lahir->format('Y-m-d') : null;
        $this->ibu_pekerjaan = $this->pendaftar->ibu_pekerjaan;
        $this->ibu_tidak_punya_hp = $this->pendaftar->ibu_tidak_punya_hp;

        // Tab Data Orang Tua & Wali - Wali
        $this->wali_hubungan = $this->pendaftar->wali_hubungan;
        $this->wali_status = $this->pendaftar->wali_status;
        $this->wali_kewarganegaraan = $this->pendaftar->wali_kewarganegaraan;
        $this->wali_negara_asal = $this->pendaftar->wali_negara_asal;
        $this->wali_nomor_kitas = $this->pendaftar->wali_nomor_kitas;
        $this->wali_tempat_lahir = $this->pendaftar->wali_tempat_lahir;
        $this->wali_tanggal_lahir = $this->pendaftar->wali_tanggal_lahir ? $this->pendaftar->wali_tanggal_lahir->format('Y-m-d') : null;
        $this->wali_tidak_punya_hp = $this->pendaftar->wali_tidak_punya_hp;

        // Tab Data Orang Tua & Wali - Kartu Bantuan
        $this->nomor_kks = $this->pendaftar->nomor_kks;
        $this->nomor_pkh = $this->pendaftar->nomor_pkh;

        // EMIS - Riwayat Pendidikan
        $this->tk_nama = $this->pendaftar->tk_nama;
        $this->tk_npsn = $this->pendaftar->tk_npsn;
        $this->tk_tahun_lulus = $this->pendaftar->tk_tahun_lulus;
        $this->sd_nama = $this->pendaftar->sd_nama;
        $this->sd_npsn = $this->pendaftar->sd_npsn;
        $this->sd_tahun_lulus = $this->pendaftar->sd_tahun_lulus;
        $this->sd_nomor_peserta_ujian = $this->pendaftar->sd_nomor_peserta_ujian;
        $this->sd_nomor_seri_ijazah = $this->pendaftar->sd_nomor_seri_ijazah;
        $this->sd_nomor_skhun = $this->pendaftar->sd_nomor_skhun;
        $this->sd_nilai_matematika = $this->pendaftar->sd_nilai_matematika;
        $this->sd_nilai_ipa = $this->pendaftar->sd_nilai_ipa;
        $this->sd_nilai_ips = $this->pendaftar->sd_nilai_ips;
        $this->sd_nilai_bahasa_indonesia = $this->pendaftar->sd_nilai_bahasa_indonesia;
        $this->sd_nilai_bahasa_inggris = $this->pendaftar->sd_nilai_bahasa_inggris;
        $this->hobi = $this->pendaftar->hobi;
        $this->cita_cita = $this->pendaftar->cita_cita;
        $this->yang_membiayai_sekolah = $this->pendaftar->yang_membiayai_sekolah;
        $this->pra_sekolah_tk = $this->pendaftar->pra_sekolah_tk;
        $this->pra_sekolah_paud = $this->pendaftar->pra_sekolah_paud;
        $this->imunisasi_hepatitis_b = $this->pendaftar->imunisasi_hepatitis_b;
        $this->imunisasi_bcg = $this->pendaftar->imunisasi_bcg;
        $this->imunisasi_dpt = $this->pendaftar->imunisasi_dpt;
        $this->imunisasi_polio = $this->pendaftar->imunisasi_polio;
        $this->imunisasi_campak = $this->pendaftar->imunisasi_campak;
        $this->imunisasi_covid = $this->pendaftar->imunisasi_covid;
        $this->berkebutuhan_khusus = $this->pendaftar->berkebutuhan_khusus;
        $this->jenis_kebutuhan_khusus = $this->pendaftar->jenis_kebutuhan_khusus ?? [];

        // EMIS - Files
        $this->file_ijazah_sd = $this->pendaftar->file_ijazah_sd;
        $this->file_skhun_sd = $this->pendaftar->file_skhun_sd;
        $this->file_kartu_keluarga = $this->pendaftar->file_kartu_keluarga;
        $this->file_akta_kelahiran = $this->pendaftar->file_akta_kelahiran;

        // Load Survey Answers
        $this->loadSurveyAnswers();

        // Load Prestasi Data
        $this->loadPrestasi();

        // Load Beasiswa Data
        $this->loadBeasiswa();

        // Load Pendidikan Lain Data
        $this->loadPendidikanLain();

        // Load Keahlian Data
        $this->loadKeahlian();

        // Load Tahfidz Data
        $this->loadTahfidz();

        // Load Cascade Dropdown Data
        $this->loadCascadeData();
    }

    public function loadCascadeData()
    {
        // Load all provinces (always available) - explicitly cast ID to string
        $this->provincesList = Province::orderBy('name')->get()->map(function($item) {
            return ['id' => (string)$item->id, 'name' => $item->name];
        })->toArray();

        // Ayah cascade data
        if ($this->ayah_provinsi) {
            $this->ayahCitiesList = Regency::where('province_id', $this->ayah_provinsi)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->ayah_kabupaten) {
            $this->ayahDistrictsList = District::where('regency_id', $this->ayah_kabupaten)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->ayah_kecamatan) {
            $this->ayahVillagesList = Village::where('district_id', $this->ayah_kecamatan)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }

        // Ibu cascade data
        if ($this->ibu_provinsi) {
            $this->ibuCitiesList = Regency::where('province_id', $this->ibu_provinsi)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->ibu_kabupaten) {
            $this->ibuDistrictsList = District::where('regency_id', $this->ibu_kabupaten)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->ibu_kecamatan) {
            $this->ibuVillagesList = Village::where('district_id', $this->ibu_kecamatan)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }

        // Wali cascade data
        if ($this->wali_provinsi) {
            $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->wali_kabupaten) {
            $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->wali_kecamatan) {
            $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }

        // Siswa cascade data
        if ($this->siswa_provinsi) {
            $this->siswaCitiesList = Regency::where('province_id', $this->siswa_provinsi)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->siswa_kabupaten) {
            $this->siswaDistrictsList = District::where('regency_id', $this->siswa_kabupaten)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
        if ($this->siswa_kecamatan) {
            $this->siswaVillagesList = Village::where('district_id', $this->siswa_kecamatan)->orderBy('name')->get()->map(function($item) {
                return ['id' => (string)$item->id, 'name' => $item->name];
            })->toArray();
        }
    }

    /**
     * Load survey answers for the current pendaftar
     * Used in mount() and when re-entering edit mode
     */
    public function loadSurveyAnswers()
    {
        // Initialize survey answers array
        if (!is_array($this->surveyAnswers)) {
            $this->surveyAnswers = [];
        }

        // Load Survey Answers from database
        $surveyAnswers = $this->pendaftar->surveyAnswers;
        foreach ($surveyAnswers as $answer) {
            $questionId = $answer->survey_question_id;
            if ($answer->question->tipe_input === 'text' || $answer->question->tipe_input === 'textarea') {
                // For text: jawaban contains the text string
                $this->surveyAnswers[$questionId] = $answer->jawaban;
            } elseif ($answer->question->tipe_input === 'checkbox') {
                // NEW APPROACH: For checkbox, jawaban is LINEAR array of option IDs [1, 3, 5]
                // NOT associative array [1 => true, 3 => true]
                $selectedIds = is_array($answer->jawaban) ? $answer->jawaban : [$answer->jawaban];
                $this->surveyAnswers[$questionId] = $selectedIds;
            } else {
                // For radio/dropdown: jawaban contains single option ID
                $this->surveyAnswers[$questionId] = $answer->jawaban;
            }
        }

        // CRITICAL FIX: Initialize checkbox questions dengan empty array jika belum ada jawaban
        // Ini penting agar Livewire wire:model tahu bahwa ini adalah multi-select checkbox
        $allSurveyQuestions = \App\Models\SurveyQuestion::umum()->aktif()->with('options')->get();
        foreach ($allSurveyQuestions as $question) {
            if ($question->tipe_input === 'checkbox' && !isset($this->surveyAnswers[$question->id])) {
                $this->surveyAnswers[$question->id] = [];
            }
        }
    }

    /**
     * Ensure cascade dropdown arrays are always plain arrays, not Eloquent Collections
     * This prevents Livewire serialization issues with PostgreSQL string IDs
     */
    public function dehydrate()
    {
        // Convert any Collection objects to arrays with string IDs
        $cascadeProperties = [
            'provincesList', 'ayahCitiesList', 'ayahDistrictsList', 'ayahVillagesList',
            'ibuCitiesList', 'ibuDistrictsList', 'ibuVillagesList',
            'waliCitiesList', 'waliDistrictsList', 'waliVillagesList',
            'siswaCitiesList', 'siswaDistrictsList', 'siswaVillagesList'
        ];

        foreach ($cascadeProperties as $property) {
            if (is_object($this->$property)) {
                // Convert Collection to array if needed
                $this->$property = collect($this->$property)->map(function($item) {
                    if (is_object($item)) {
                        return ['id' => (string)($item->id ?? $item['id']), 'name' => $item->name ?? $item['name']];
                    }
                    // Already an array, ensure ID is string
                    if (isset($item['id'])) {
                        $item['id'] = (string)$item['id'];
                    }
                    return $item;
                })->values()->toArray(); // Add values() to re-index array
            } elseif (is_array($this->$property)) {
                // Ensure all IDs in existing arrays are strings and re-index
                $this->$property = array_values(array_map(function($item) {
                    if (is_array($item) && isset($item['id'])) {
                        $item['id'] = (string)$item['id'];
                    }
                    return $item;
                }, $this->$property));
            }
        }
    }

    public function toggleEdit($section)
    {
        $property = 'editMode' . ucfirst($section);
        if (property_exists($this, $property)) {
            $isEnabling = !$this->$property;
            $this->$property = $isEnabling;

            // Only reload data when ENABLING edit mode (not when disabling/canceling)
            // This avoids unnecessary database queries when user clicks "Edit"
            if ($isEnabling) {
                // Just load the cascade dropdown data for the current state
                $this->loadCascadeData();

                // CRITICAL FIX: Reload survey answers when entering edit mode
                if ($section === 'survey') {
                    $this->loadSurveyAnswers();
                }
            }
            // When canceling (disabling), Livewire will restore the previous state automatically
        }
    }

    public function saveDataSiswa()
    {
        \Log::info('=== SAVE DATA SISWA CALLED ===');
        \Log::info('Pendaftar ID: ' . $this->pendaftar->id);
        \Log::info('Foto exists: ' . ($this->pendaftar->foto ? 'Yes' : 'No'));

        // Validasi foto wajib jika belum ada
        $fotoValidation = $this->pendaftar->foto ? 'nullable|image|max:2048' : 'required|image|max:2048';

        try {
            // Validasi hanya field yang ADA di Tab Data Siswa
            $validationRules = [
                'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
                'nisn' => 'required|digits:10',
                'tempat_lahir' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'required|string|max:50',
                'kewarganegaraan' => 'required|in:WNI,WNA',
                'nomor_kk' => 'required|digits:16',
                'nama_kepala_keluarga' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
                'anak_ke' => 'required|integer|min:1|max:99',
                'jumlah_saudara' => 'required|integer|min:0|max:99',
                'hobi' => 'required|string',
                'cita_cita' => 'required|string',
                'yang_membiayai_sekolah' => 'required|string',
                'tidak_punya_hp' => 'nullable|boolean',
                'no_hp' => $this->tidak_punya_hp ? 'nullable' : 'nullable|numeric|digits_between:10,15',
                'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
                'newFoto' => $fotoValidation,
            ];

            // Tambahkan validasi conditional untuk NIK/KITAS
            if ($this->kewarganegaraan === 'WNI') {
                $validationRules['nik'] = 'required|digits:16';
            } elseif ($this->kewarganegaraan === 'WNA') {
                $validationRules['negara_asal'] = 'required|string|max:100';
                $validationRules['nomor_kitas'] = 'required|string|max:50';
            }

            $this->validate($validationRules, [
                // Nama Lengkap
                'nama_lengkap.required' => 'Nama lengkap wajib diisi',
                'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',
                'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',

                // NISN
                'nisn.required' => 'NISN wajib diisi',
                'nisn.digits' => 'NISN harus 10 digit',

                // Tempat Lahir
                'tempat_lahir.required' => 'Tempat lahir wajib diisi',
                'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
                'tempat_lahir.regex' => 'Tempat lahir hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',

                // Tanggal Lahir
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
                'tanggal_lahir.date' => 'Tanggal lahir harus format tanggal yang valid',

                // Jenis Kelamin
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki (L) atau Perempuan (P)',

                // Agama
                'agama.required' => 'Agama wajib dipilih',

                // Kewarganegaraan
                'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih',
                'kewarganegaraan.in' => 'Kewarganegaraan harus WNI atau WNA',

                // Nomor KK
                'nomor_kk.required' => 'Nomor Kartu Keluarga wajib diisi',
                'nomor_kk.digits' => 'Nomor Kartu Keluarga harus 16 digit',

                // Nama Kepala Keluarga
                'nama_kepala_keluarga.required' => 'Nama kepala keluarga wajib diisi',
                'nama_kepala_keluarga.max' => 'Nama kepala keluarga maksimal 255 karakter',
                'nama_kepala_keluarga.regex' => 'Nama kepala keluarga hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',

                // Anak Ke
                'anak_ke.required' => 'Anak ke berapa wajib diisi',
                'anak_ke.integer' => 'Anak ke harus berupa angka',
                'anak_ke.min' => 'Anak ke minimal 1',
                'anak_ke.max' => 'Anak ke maksimal 99',

                // Jumlah Saudara
                'jumlah_saudara.required' => 'Jumlah saudara wajib diisi',
                'jumlah_saudara.integer' => 'Jumlah saudara harus berupa angka',
                'jumlah_saudara.min' => 'Jumlah saudara minimal 0',
                'jumlah_saudara.max' => 'Jumlah saudara maksimal 99',

                // Hobi
                'hobi.required' => 'Hobi wajib diisi',

                // Cita-cita
                'cita_cita.required' => 'Cita-cita wajib diisi',

                // Yang Membiayai Sekolah
                'yang_membiayai_sekolah.required' => 'Yang membiayai sekolah wajib diisi',

                // No HP
                'no_hp.numeric' => 'Nomor HP harus berupa angka',
                'no_hp.digits_between' => 'Nomor HP harus 10-15 digit',

                // NIK / KITAS
                'nik.required' => 'NIK wajib diisi untuk WNI',
                'nik.digits' => 'NIK harus 16 digit',
                'negara_asal.required' => 'Asal negara wajib diisi untuk WNA',
                'nomor_kitas.required' => 'Nomor KITAS wajib diisi untuk WNA',

                // Foto
                'newFoto.required' => 'Foto pendaftar wajib diupload',
                'newFoto.image' => 'File harus berupa gambar',
                'newFoto.max' => 'Ukuran foto maksimal 2MB',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error in saveDataSiswa: ' . json_encode($e->errors()));
            $this->dispatch('statusUpdated', ['type' => 'error', 'message' => 'Validasi gagal: ' . implode(', ', array_map(fn($errors) => implode(', ', $errors), $e->errors()))]);
            throw $e;
        }

        \Log::info('Validation passed');

        // Update hanya field yang ADA di Tab Data Siswa
        $dataToUpdate = [
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'kewarganegaraan' => $this->kewarganegaraan,
            'nomor_kk' => $this->nomor_kk,
            'nama_kepala_keluarga' => $this->nama_kepala_keluarga,
            'anak_ke' => $this->anak_ke,
            'jumlah_saudara' => $this->jumlah_saudara,
            'hobi' => $this->hobi,
            'cita_cita' => $this->cita_cita,
            'yang_membiayai_sekolah' => $this->yang_membiayai_sekolah,
            'pra_sekolah_tk' => $this->pra_sekolah_tk ?? false,
            'pra_sekolah_paud' => $this->pra_sekolah_paud ?? false,
            'imunisasi_hepatitis_b' => $this->imunisasi_hepatitis_b ?? false,
            'imunisasi_bcg' => $this->imunisasi_bcg ?? false,
            'imunisasi_dpt' => $this->imunisasi_dpt ?? false,
            'imunisasi_polio' => $this->imunisasi_polio ?? false,
            'imunisasi_campak' => $this->imunisasi_campak ?? false,
            'imunisasi_covid' => $this->imunisasi_covid ?? false,
            'tidak_punya_hp' => $this->tidak_punya_hp ?? false,
            'no_hp' => $this->tidak_punya_hp ? null : $this->no_hp,
            'nomor_kip' => $this->nomor_kip,
            'penerima_kip' => !empty($this->nomor_kip),
        ];

        // Tambahkan NIK/KITAS berdasarkan kewarganegaraan
        if ($this->kewarganegaraan === 'WNI') {
            $dataToUpdate['nik'] = $this->nik;
            $dataToUpdate['negara_asal'] = null;
            $dataToUpdate['nomor_kitas'] = null;
        } elseif ($this->kewarganegaraan === 'WNA') {
            $dataToUpdate['nik'] = null;
            $dataToUpdate['negara_asal'] = $this->negara_asal;
            $dataToUpdate['nomor_kitas'] = $this->nomor_kitas;
        }

        \Log::info('Processing file upload...');
        if ($this->newFoto) {
            try {
                $imageService = new ImageService();

                // Hapus foto lama jika ada
                $imageService->deleteOldFile($this->pendaftar->foto);

                // Upload dan konversi ke WebP dengan nama file sistematis
                // Format: foto_profil-{nomor_pendaftaran}-{nama_pendaftar}-{timestamp}.webp
                $fotoPath = $imageService->uploadProfilePhoto(
                    $this->newFoto,
                    'photos',
                    500,
                    80,
                    $this->pendaftar->nomor_pendaftaran,
                    'foto_profil',
                    $this->pendaftar->nama_lengkap
                );
                $dataToUpdate['foto'] = $fotoPath;
                $this->foto = $fotoPath;
                \Log::info('Photo uploaded successfully: ' . $fotoPath);
            } catch (\Exception $e) {
                \Log::error('Error uploading photo: ' . $e->getMessage());
                $this->dispatch('statusUpdated', ['type' => 'error', 'message' => 'Gagal upload foto: ' . $e->getMessage()]);
                return;
            }
        }

        try {
            \Log::info('Updating pendaftar data...');
            \Log::info('Data to update: ' . json_encode($dataToUpdate));

            $this->pendaftar->update($dataToUpdate);
            $this->pendaftar->refresh();

            \Log::info('Data updated successfully');

            $this->newFoto = null;
            $this->editModeSiswa = false;
            $this->loadDataToProperties(); // Reload data after save

            \Log::info('Dispatching success event');
            $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data siswa berhasil diperbarui.']);

        } catch (\Exception $e) {
            \Log::error('Error saving data siswa: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->dispatch('statusUpdated', ['type' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function saveDataOrangTua()
    {
        // Base validation rules
        $validationRules = [
            // Ayah - Basic fields always required
            'nama_ayah' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'ayah_status' => 'required|in:masih_hidup,meninggal,tidak_diketahui',

            // Ibu - Basic fields always required
            'nama_ibu' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'ibu_status' => 'required|in:masih_hidup,meninggal,tidak_diketahui',

            // Wali - Hubungan always required
            'wali_hubungan' => 'nullable|in:sama_ayah,sama_ibu,lainnya',

            // Kartu Bantuan - Optional
            'nomor_kks' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
            'nomor_pkh' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',

            // Keep EMIS fields validation
            'ayah_nik' => 'nullable|digits:16',
            'ayah_tahun_lahir' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pekerjaan_ayah' => 'nullable|string',
            'ayah_pendidikan' => 'nullable|string',
            'ayah_penghasilan' => 'nullable|string',
            'ayah_no_hp' => 'nullable|numeric|digits_between:10,15',
            'ayah_berkebutuhan_khusus' => 'nullable|boolean',
            'ibu_nik' => 'nullable|digits:16',
            'ibu_tahun_lahir' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pekerjaan_ibu' => 'nullable|string',
            'ibu_pendidikan' => 'nullable|string',
            'ibu_penghasilan' => 'nullable|string',
            'ibu_no_hp' => 'nullable|numeric|digits_between:10,15',
            'ibu_berkebutuhan_khusus' => 'nullable|boolean',
            'memiliki_wali' => 'nullable|boolean',
            'wali_nik' => 'nullable|digits:16',
            'wali_nama' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'wali_tahun_lahir' => 'nullable|integer|min:1900|max:' . date('Y'),
            'wali_pendidikan' => 'nullable|string',
            'wali_pekerjaan' => 'nullable|string',
            'wali_penghasilan' => 'nullable|string',
            'wali_no_hp' => 'nullable|numeric|digits_between:10,15',
        ];

        // AYAH - Add conditional validation if status = masih_hidup
        if ($this->ayah_status === 'masih_hidup') {
            $validationRules['ayah_kewarganegaraan'] = 'required|in:WNI,WNA';
            $validationRules['ayah_tempat_lahir'] = ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'];
            $validationRules['ayah_tanggal_lahir'] = 'required|date';
            $validationRules['ayah_pekerjaan'] = 'required|string|max:100';

            if ($this->ayah_kewarganegaraan === 'WNI') {
                $validationRules['ayah_nik'] = 'required|digits:16';
            } elseif ($this->ayah_kewarganegaraan === 'WNA') {
                $validationRules['ayah_negara_asal'] = 'required|string|max:100';
                $validationRules['ayah_nomor_kitas'] = 'required|string|max:50';
            }

            if (!$this->ayah_tidak_punya_hp) {
                $validationRules['ayah_no_hp'] = 'required|numeric|digits_between:10,15';
            }
        }

        // IBU - Add conditional validation if status = masih_hidup
        if ($this->ibu_status === 'masih_hidup') {
            $validationRules['ibu_kewarganegaraan'] = 'required|in:WNI,WNA';
            $validationRules['ibu_tempat_lahir'] = ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'];
            $validationRules['ibu_tanggal_lahir'] = 'required|date';
            $validationRules['ibu_pekerjaan'] = 'required|string|max:100';

            if ($this->ibu_kewarganegaraan === 'WNI') {
                $validationRules['ibu_nik'] = 'required|digits:16';
            } elseif ($this->ibu_kewarganegaraan === 'WNA') {
                $validationRules['ibu_negara_asal'] = 'required|string|max:100';
                $validationRules['ibu_nomor_kitas'] = 'required|string|max:50';
            }

            if (!$this->ibu_tidak_punya_hp) {
                $validationRules['ibu_no_hp'] = 'required|numeric|digits_between:10,15';
            }
        }

        // WALI - Add conditional validation if hubungan = lainnya
        if ($this->wali_hubungan === 'lainnya') {
            $validationRules['wali_nama'] = ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'];
            $validationRules['wali_status'] = 'required|in:masih_hidup,meninggal,tidak_diketahui';

            if ($this->wali_status === 'masih_hidup') {
                $validationRules['wali_kewarganegaraan'] = 'required|in:WNI,WNA';
                $validationRules['wali_tempat_lahir'] = ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'];
                $validationRules['wali_tanggal_lahir'] = 'required|date';
                $validationRules['wali_pekerjaan'] = 'required|string|max:100';

                if ($this->wali_kewarganegaraan === 'WNI') {
                    $validationRules['wali_nik'] = 'required|digits:16';
                } elseif ($this->wali_kewarganegaraan === 'WNA') {
                    $validationRules['wali_negara_asal'] = 'required|string|max:100';
                    $validationRules['wali_nomor_kitas'] = 'required|string|max:50';
                }

                if (!$this->wali_tidak_punya_hp) {
                    $validationRules['wali_no_hp'] = 'required|numeric|digits_between:10,15';
                }
            }
        }

        $this->validate($validationRules, [
            // Ayah - Base fields
            'nama_ayah.required' => 'Nama ayah wajib diisi',
            'nama_ayah.max' => 'Nama ayah maksimal 255 karakter',
            'nama_ayah.regex' => 'Nama ayah hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'ayah_status.required' => 'Status ayah wajib dipilih',
            'ayah_status.in' => 'Status ayah tidak valid',

            // Ibu - Base fields
            'nama_ibu.required' => 'Nama ibu wajib diisi',
            'nama_ibu.max' => 'Nama ibu maksimal 255 karakter',
            'nama_ibu.regex' => 'Nama ibu hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'ibu_status.required' => 'Status ibu wajib dipilih',
            'ibu_status.in' => 'Status ibu tidak valid',

            // Wali - Hubungan
            'wali_hubungan.in' => 'Hubungan wali tidak valid',

            // Kartu Bantuan
            'nomor_kks.max' => 'Nomor KKS maksimal 25 karakter',
            'nomor_kks.regex' => 'Nomor KKS hanya boleh huruf dan angka',
            'nomor_pkh.max' => 'Nomor PKH maksimal 25 karakter',
            'nomor_pkh.regex' => 'Nomor PKH hanya boleh huruf dan angka',

            // Ayah - Conditional fields
            'ayah_kewarganegaraan.required' => 'Kewarganegaraan ayah wajib dipilih',
            'ayah_kewarganegaraan.in' => 'Kewarganegaraan ayah harus WNI atau WNA',
            'ayah_tempat_lahir.required' => 'Tempat lahir ayah wajib diisi',
            'ayah_tempat_lahir.max' => 'Tempat lahir ayah maksimal 255 karakter',
            'ayah_tempat_lahir.regex' => 'Tempat lahir ayah hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'ayah_tanggal_lahir.required' => 'Tanggal lahir ayah wajib diisi',
            'ayah_tanggal_lahir.date' => 'Tanggal lahir ayah harus format tanggal yang valid',
            'ayah_pekerjaan.required' => 'Pekerjaan ayah wajib diisi',
            'ayah_pekerjaan.max' => 'Pekerjaan ayah maksimal 100 karakter',
            'ayah_nik.required' => 'NIK ayah wajib diisi',
            'ayah_nik.digits' => 'NIK ayah harus 16 digit',
            'ayah_negara_asal.required' => 'Negara asal ayah wajib diisi untuk WNA',
            'ayah_negara_asal.max' => 'Negara asal ayah maksimal 100 karakter',
            'ayah_nomor_kitas.required' => 'Nomor KITAS ayah wajib diisi untuk WNA',
            'ayah_nomor_kitas.max' => 'Nomor KITAS ayah maksimal 50 karakter',
            'ayah_no_hp.required' => 'Nomor HP ayah wajib diisi',
            'ayah_no_hp.numeric' => 'Nomor HP ayah harus berupa angka',
            'ayah_no_hp.digits_between' => 'Nomor HP ayah harus 10-15 digit',
            'ayah_tahun_lahir.integer' => 'Tahun lahir ayah harus berupa angka',
            'ayah_tahun_lahir.min' => 'Tahun lahir ayah tidak valid',
            'ayah_tahun_lahir.max' => 'Tahun lahir ayah tidak boleh melebihi tahun ini',

            // Ibu - Conditional fields
            'ibu_kewarganegaraan.required' => 'Kewarganegaraan ibu wajib dipilih',
            'ibu_kewarganegaraan.in' => 'Kewarganegaraan ibu harus WNI atau WNA',
            'ibu_tempat_lahir.required' => 'Tempat lahir ibu wajib diisi',
            'ibu_tempat_lahir.max' => 'Tempat lahir ibu maksimal 255 karakter',
            'ibu_tempat_lahir.regex' => 'Tempat lahir ibu hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'ibu_tanggal_lahir.required' => 'Tanggal lahir ibu wajib diisi',
            'ibu_tanggal_lahir.date' => 'Tanggal lahir ibu harus format tanggal yang valid',
            'ibu_pekerjaan.required' => 'Pekerjaan ibu wajib diisi',
            'ibu_pekerjaan.max' => 'Pekerjaan ibu maksimal 100 karakter',
            'ibu_nik.required' => 'NIK ibu wajib diisi',
            'ibu_nik.digits' => 'NIK ibu harus 16 digit',
            'ibu_negara_asal.required' => 'Negara asal ibu wajib diisi untuk WNA',
            'ibu_negara_asal.max' => 'Negara asal ibu maksimal 100 karakter',
            'ibu_nomor_kitas.required' => 'Nomor KITAS ibu wajib diisi untuk WNA',
            'ibu_nomor_kitas.max' => 'Nomor KITAS ibu maksimal 50 karakter',
            'ibu_no_hp.required' => 'Nomor HP ibu wajib diisi',
            'ibu_no_hp.numeric' => 'Nomor HP ibu harus berupa angka',
            'ibu_no_hp.digits_between' => 'Nomor HP ibu harus 10-15 digit',
            'ibu_tahun_lahir.integer' => 'Tahun lahir ibu harus berupa angka',
            'ibu_tahun_lahir.min' => 'Tahun lahir ibu tidak valid',
            'ibu_tahun_lahir.max' => 'Tahun lahir ibu tidak boleh melebihi tahun ini',

            // Wali - Conditional fields
            'wali_nama.required' => 'Nama wali wajib diisi',
            'wali_nama.max' => 'Nama wali maksimal 255 karakter',
            'wali_nama.regex' => 'Nama wali hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'wali_status.required' => 'Status wali wajib dipilih',
            'wali_status.in' => 'Status wali tidak valid',
            'wali_kewarganegaraan.required' => 'Kewarganegaraan wali wajib dipilih',
            'wali_kewarganegaraan.in' => 'Kewarganegaraan wali harus WNI atau WNA',
            'wali_tempat_lahir.required' => 'Tempat lahir wali wajib diisi',
            'wali_tempat_lahir.max' => 'Tempat lahir wali maksimal 255 karakter',
            'wali_tempat_lahir.regex' => 'Tempat lahir wali hanya boleh berisi huruf, spasi, titik, koma, dan tanda hubung',
            'wali_tanggal_lahir.required' => 'Tanggal lahir wali wajib diisi',
            'wali_tanggal_lahir.date' => 'Tanggal lahir wali harus format tanggal yang valid',
            'wali_pekerjaan.required' => 'Pekerjaan wali wajib diisi',
            'wali_pekerjaan.max' => 'Pekerjaan wali maksimal 100 karakter',
            'wali_nik.required' => 'NIK wali wajib diisi',
            'wali_nik.digits' => 'NIK wali harus 16 digit',
            'wali_negara_asal.required' => 'Negara asal wali wajib diisi untuk WNA',
            'wali_negara_asal.max' => 'Negara asal wali maksimal 100 karakter',
            'wali_nomor_kitas.required' => 'Nomor KITAS wali wajib diisi untuk WNA',
            'wali_nomor_kitas.max' => 'Nomor KITAS wali maksimal 50 karakter',
            'wali_no_hp.required' => 'Nomor HP wali wajib diisi',
            'wali_no_hp.numeric' => 'Nomor HP wali harus berupa angka',
            'wali_no_hp.digits_between' => 'Nomor HP wali harus 10-15 digit',
            'wali_tahun_lahir.integer' => 'Tahun lahir wali harus berupa angka',
            'wali_tahun_lahir.min' => 'Tahun lahir wali tidak valid',
            'wali_tahun_lahir.max' => 'Tahun lahir wali tidak boleh melebihi tahun ini',
        ]);

        // Prepare data for update
        $updateData = [
            // Ayah - Base fields
            'nama_ayah' => $this->nama_ayah,
            'ayah_status' => $this->ayah_status,
            'ayah_kewarganegaraan' => $this->ayah_status === 'masih_hidup' ? $this->ayah_kewarganegaraan : null,
            'ayah_negara_asal' => ($this->ayah_status === 'masih_hidup' && $this->ayah_kewarganegaraan === 'WNA') ? $this->ayah_negara_asal : null,
            'ayah_nomor_kitas' => ($this->ayah_status === 'masih_hidup' && $this->ayah_kewarganegaraan === 'WNA') ? $this->ayah_nomor_kitas : null,
            'ayah_tempat_lahir' => $this->ayah_status === 'masih_hidup' ? $this->ayah_tempat_lahir : null,
            'ayah_tanggal_lahir' => $this->ayah_status === 'masih_hidup' ? $this->ayah_tanggal_lahir : null,
            'ayah_pekerjaan' => $this->ayah_status === 'masih_hidup' ? $this->ayah_pekerjaan : null,
            'ayah_tidak_punya_hp' => $this->ayah_status === 'masih_hidup' ? ($this->ayah_tidak_punya_hp ?? false) : false,

            // Ibu - Base fields
            'nama_ibu' => $this->nama_ibu,
            'ibu_status' => $this->ibu_status,
            'ibu_kewarganegaraan' => $this->ibu_status === 'masih_hidup' ? $this->ibu_kewarganegaraan : null,
            'ibu_negara_asal' => ($this->ibu_status === 'masih_hidup' && $this->ibu_kewarganegaraan === 'WNA') ? $this->ibu_negara_asal : null,
            'ibu_nomor_kitas' => ($this->ibu_status === 'masih_hidup' && $this->ibu_kewarganegaraan === 'WNA') ? $this->ibu_nomor_kitas : null,
            'ibu_tempat_lahir' => $this->ibu_status === 'masih_hidup' ? $this->ibu_tempat_lahir : null,
            'ibu_tanggal_lahir' => $this->ibu_status === 'masih_hidup' ? $this->ibu_tanggal_lahir : null,
            'ibu_pekerjaan' => $this->ibu_status === 'masih_hidup' ? $this->ibu_pekerjaan : null,
            'ibu_tidak_punya_hp' => $this->ibu_status === 'masih_hidup' ? ($this->ibu_tidak_punya_hp ?? false) : false,

            // Wali
            'wali_hubungan' => $this->wali_hubungan,
            'wali_status' => ($this->wali_hubungan === 'lainnya') ? $this->wali_status : null,
            'wali_kewarganegaraan' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup') ? $this->wali_kewarganegaraan : null,
            'wali_negara_asal' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup' && $this->wali_kewarganegaraan === 'WNA') ? $this->wali_negara_asal : null,
            'wali_nomor_kitas' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup' && $this->wali_kewarganegaraan === 'WNA') ? $this->wali_nomor_kitas : null,
            'wali_tempat_lahir' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup') ? $this->wali_tempat_lahir : null,
            'wali_tanggal_lahir' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup') ? $this->wali_tanggal_lahir : null,
            'wali_tidak_punya_hp' => ($this->wali_hubungan === 'lainnya' && $this->wali_status === 'masih_hidup') ? ($this->wali_tidak_punya_hp ?? false) : false,

            // Kartu Bantuan
            'nomor_kks' => $this->nomor_kks,
            'nomor_pkh' => $this->nomor_pkh,

            // Keep EMIS fields
            'ayah_nik' => $this->ayah_nik,
            'ayah_tahun_lahir' => $this->ayah_tahun_lahir,
            'pekerjaan_ayah' => $this->pekerjaan_ayah,
            'ayah_pendidikan' => $this->ayah_pendidikan,
            'ayah_penghasilan' => $this->ayah_penghasilan,
            'ayah_no_hp' => $this->ayah_no_hp,
            'ayah_berkebutuhan_khusus' => $this->ayah_berkebutuhan_khusus ?? false,
            'ibu_nik' => $this->ibu_nik,
            'ibu_tahun_lahir' => $this->ibu_tahun_lahir,
            'pekerjaan_ibu' => $this->pekerjaan_ibu,
            'ibu_pendidikan' => $this->ibu_pendidikan,
            'ibu_penghasilan' => $this->ibu_penghasilan,
            'ibu_no_hp' => $this->ibu_no_hp,
            'ibu_berkebutuhan_khusus' => $this->ibu_berkebutuhan_khusus ?? false,
            'memiliki_wali' => $this->memiliki_wali ?? false,
            'wali_nik' => $this->wali_nik,
            'wali_nama' => $this->wali_nama,
            'wali_tahun_lahir' => $this->wali_tahun_lahir,
            'wali_pendidikan' => $this->wali_pendidikan,
            'wali_pekerjaan' => $this->wali_pekerjaan,
            'wali_penghasilan' => $this->wali_penghasilan,
            'wali_no_hp' => $this->wali_no_hp,
            'penghasilan_ortu' => $this->penghasilan_ortu,
            'telepon_ortu' => $this->telepon_ortu,
        ];

        $this->pendaftar->update($updateData);
        $this->editModeOrangtua = false;  // Changed from OrangTua to Orangtua (lowercase t)
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data orang tua berhasil diperbarui.']);
    }

    public function saveDataAlamat()
    {
        // Base validation rules
        $validationRules = [
            'email' => 'required|email',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'no_hp' => 'nullable|numeric|digits_between:10,15',
            'asal_kabkota' => 'nullable|string|max:100',

            // Boolean flags
            'ayah_tinggal_luar_negeri' => 'nullable|boolean',
            'ibu_tinggal_luar_negeri' => 'nullable|boolean',
            'ibu_sama_dengan_ayah' => 'nullable|boolean',
            'wali_tinggal_luar_negeri' => 'nullable|boolean',
            'wali_sama_dengan_ayah' => 'nullable|boolean',
            'wali_status_wali' => 'required|in:sama_dengan_ayah,sama_dengan_ibu,lainnya',
        ];

        // AYAH ADDRESS - Required if ayah status = masih_hidup AND not living abroad
        if ($this->pendaftar->ayah_status === 'masih_hidup' && !$this->ayah_tinggal_luar_negeri) {
            $validationRules['ayah_status_kepemilikan_rumah'] = 'required|string|max:100';
            $validationRules['alamat_ayah'] = 'required|string';
            $validationRules['ayah_provinsi'] = 'required';
            $validationRules['ayah_kabupaten'] = 'required';
            $validationRules['ayah_kecamatan'] = 'required';
            $validationRules['ayah_kelurahan'] = 'required';
            $validationRules['ayah_rt'] = 'required|string|max:3';
            $validationRules['ayah_rw'] = 'required|string|max:3';
            $validationRules['ayah_kode_pos'] = 'required|string|max:6';
        } else {
            $validationRules['ayah_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['alamat_ayah'] = 'nullable|string';
            $validationRules['ayah_provinsi'] = 'nullable';
            $validationRules['ayah_kabupaten'] = 'nullable';
            $validationRules['ayah_kecamatan'] = 'nullable';
            $validationRules['ayah_kelurahan'] = 'nullable';
            $validationRules['ayah_rt'] = 'nullable|string|max:3';
            $validationRules['ayah_rw'] = 'nullable|string|max:3';
            $validationRules['ayah_kode_pos'] = 'nullable|string|max:6';
        }

        // IBU ADDRESS - Required if ibu status = masih_hidup AND not same as ayah AND not living abroad
        if ($this->pendaftar->ibu_status === 'masih_hidup' && !$this->ibu_sama_dengan_ayah && !$this->ibu_tinggal_luar_negeri) {
            $validationRules['ibu_status_kepemilikan_rumah'] = 'required|string|max:100';
            $validationRules['ibu_alamat'] = 'required|string';
            $validationRules['ibu_provinsi'] = 'required';
            $validationRules['ibu_kabupaten'] = 'required';
            $validationRules['ibu_kecamatan'] = 'required';
            $validationRules['ibu_kelurahan'] = 'required';
            $validationRules['ibu_rt'] = 'required|string|max:3';
            $validationRules['ibu_rw'] = 'required|string|max:3';
            $validationRules['ibu_kode_pos'] = 'required|string|max:6';
        } else {
            $validationRules['ibu_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['ibu_alamat'] = 'nullable|string';
            $validationRules['ibu_provinsi'] = 'nullable';
            $validationRules['ibu_kabupaten'] = 'nullable';
            $validationRules['ibu_kecamatan'] = 'nullable';
            $validationRules['ibu_kelurahan'] = 'nullable';
            $validationRules['ibu_rt'] = 'nullable|string|max:3';
            $validationRules['ibu_rw'] = 'nullable|string|max:3';
            $validationRules['ibu_kode_pos'] = 'nullable|string|max:6';
        }

        // WALI ADDRESS - Required if wali_status_wali = 'lainnya'
        if ($this->wali_status_wali === 'lainnya') {
            // Status kepemilikan dan alamat lengkap selalu wajib jika memilih lainnya
            $validationRules['wali_status_kepemilikan_rumah'] = 'required|string|max:100';
            $validationRules['alamat_wali'] = 'required|string';

            // Jika tidak tinggal di luar negeri DAN tidak sama dengan ayah, field detail alamat Indonesia wajib
            if (!$this->wali_tinggal_luar_negeri && !$this->wali_sama_dengan_ayah) {
                $validationRules['wali_provinsi'] = 'required';
                $validationRules['wali_kabupaten'] = 'required';
                $validationRules['wali_kecamatan'] = 'required';
                $validationRules['wali_kelurahan'] = 'required';
                $validationRules['wali_rt'] = 'required|string|max:3';
                $validationRules['wali_rw'] = 'required|string|max:3';
                $validationRules['wali_kode_pos'] = 'required|string|max:6';
            } else {
                $validationRules['wali_provinsi'] = 'nullable';
                $validationRules['wali_kabupaten'] = 'nullable';
                $validationRules['wali_kecamatan'] = 'nullable';
                $validationRules['wali_kelurahan'] = 'nullable';
                $validationRules['wali_rt'] = 'nullable|string|max:3';
                $validationRules['wali_rw'] = 'nullable|string|max:3';
                $validationRules['wali_kode_pos'] = 'nullable|string|max:6';
            }
        } else {
            // Jika tidak memilih lainnya, semua field wali tidak wajib
            $validationRules['wali_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['alamat_wali'] = 'nullable|string';
            $validationRules['wali_provinsi'] = 'nullable';
            $validationRules['wali_kabupaten'] = 'nullable';
            $validationRules['wali_kecamatan'] = 'nullable';
            $validationRules['wali_kelurahan'] = 'nullable';
            $validationRules['wali_rt'] = 'nullable|string|max:3';
            $validationRules['wali_rw'] = 'nullable|string|max:3';
            $validationRules['wali_kode_pos'] = 'nullable|string|max:6';
        }

        // SISWA ADDRESS - Required only if not living with parents/wali
        $validationRules['status_tempat_tinggal'] = 'required|string|max:100';

        // If not living with ayah/ibu/wali, address fields are required
        if (!in_array($this->status_tempat_tinggal, ['Tinggal dengan Ayah Kandung', 'Tinggal dengan Ibu Kandung', 'Tinggal dengan Wali'])) {
            $validationRules['siswa_status_kepemilikan_rumah'] = 'required|string|max:100';
            $validationRules['siswa_provinsi'] = 'required';
            $validationRules['siswa_kabupaten'] = 'required';
            $validationRules['siswa_kecamatan'] = 'required';
            $validationRules['siswa_kelurahan'] = 'required';
            $validationRules['siswa_rt'] = 'required|string|max:3';
            $validationRules['siswa_rw'] = 'required|string|max:3';
            $validationRules['siswa_kode_pos'] = 'required|string|max:6';
            $validationRules['siswa_alamat'] = 'required|string';
        } else {
            // If living with ayah/ibu/wali, fields are optional (will be auto-copied)
            $validationRules['siswa_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['siswa_provinsi'] = 'nullable';
            $validationRules['siswa_kabupaten'] = 'nullable';
            $validationRules['siswa_kecamatan'] = 'nullable';
            $validationRules['siswa_kelurahan'] = 'nullable';
            $validationRules['siswa_rt'] = 'nullable|string|max:3';
            $validationRules['siswa_rw'] = 'nullable|string|max:3';
            $validationRules['siswa_kode_pos'] = 'nullable|string|max:6';
            $validationRules['siswa_alamat'] = 'nullable|string';
        }

        $validationRules['jarak_ke_sekolah'] = 'required|string|max:50';
        $validationRules['transportasi'] = 'required|string|max:100';
        $validationRules['waktu_tempuh'] = 'required|string|max:50';

        $this->validate($validationRules, [
            // Email dan kontak
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 dan 90',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 dan 180',
            'no_hp.numeric' => 'Nomor HP harus berupa angka',
            'no_hp.digits_between' => 'Nomor HP harus 10-15 digit',
            'asal_kabkota.max' => 'Asal Kabupaten/Kota maksimal 100 karakter',

            // Wali status
            'wali_status_wali.required' => 'Status wali wajib dipilih',
            'wali_status_wali.in' => 'Status wali tidak valid',

            // Ayah Address
            'ayah_status_kepemilikan_rumah.required' => 'Status kepemilikan rumah ayah wajib dipilih',
            'ayah_status_kepemilikan_rumah.max' => 'Status kepemilikan rumah ayah maksimal 100 karakter',
            'alamat_ayah.required' => 'Alamat ayah wajib diisi',
            'ayah_provinsi.required' => 'Provinsi ayah wajib dipilih',
            'ayah_kabupaten.required' => 'Kabupaten ayah wajib dipilih',
            'ayah_kecamatan.required' => 'Kecamatan ayah wajib dipilih',
            'ayah_kelurahan.required' => 'Kelurahan ayah wajib dipilih',
            'ayah_rt.required' => 'RT ayah wajib diisi',
            'ayah_rt.max' => 'RT ayah maksimal 3 karakter',
            'ayah_rw.required' => 'RW ayah wajib diisi',
            'ayah_rw.max' => 'RW ayah maksimal 3 karakter',
            'ayah_kode_pos.required' => 'Kode pos ayah wajib diisi',
            'ayah_kode_pos.max' => 'Kode pos ayah maksimal 6 karakter',

            // Ibu Address
            'ibu_status_kepemilikan_rumah.required' => 'Status kepemilikan rumah ibu wajib dipilih',
            'ibu_status_kepemilikan_rumah.max' => 'Status kepemilikan rumah ibu maksimal 100 karakter',
            'ibu_alamat.required' => 'Alamat ibu wajib diisi',
            'ibu_provinsi.required' => 'Provinsi ibu wajib dipilih',
            'ibu_kabupaten.required' => 'Kabupaten ibu wajib dipilih',
            'ibu_kecamatan.required' => 'Kecamatan ibu wajib dipilih',
            'ibu_kelurahan.required' => 'Kelurahan ibu wajib dipilih',
            'ibu_rt.required' => 'RT ibu wajib diisi',
            'ibu_rt.max' => 'RT ibu maksimal 3 karakter',
            'ibu_rw.required' => 'RW ibu wajib diisi',
            'ibu_rw.max' => 'RW ibu maksimal 3 karakter',
            'ibu_kode_pos.required' => 'Kode pos ibu wajib diisi',
            'ibu_kode_pos.max' => 'Kode pos ibu maksimal 6 karakter',

            // Wali Address
            'wali_status_kepemilikan_rumah.required' => 'Status kepemilikan rumah wali wajib dipilih',
            'wali_status_kepemilikan_rumah.max' => 'Status kepemilikan rumah wali maksimal 100 karakter',
            'alamat_wali.required' => 'Alamat wali wajib diisi',
            'wali_provinsi.required' => 'Provinsi wali wajib dipilih',
            'wali_kabupaten.required' => 'Kabupaten wali wajib dipilih',
            'wali_kecamatan.required' => 'Kecamatan wali wajib dipilih',
            'wali_kelurahan.required' => 'Kelurahan wali wajib dipilih',
            'wali_rt.required' => 'RT wali wajib diisi',
            'wali_rt.max' => 'RT wali maksimal 3 karakter',
            'wali_rw.required' => 'RW wali wajib diisi',
            'wali_rw.max' => 'RW wali maksimal 3 karakter',
            'wali_kode_pos.required' => 'Kode pos wali wajib diisi',
            'wali_kode_pos.max' => 'Kode pos wali maksimal 6 karakter',

            // Siswa Address
            'status_tempat_tinggal.required' => 'Status tempat tinggal wajib dipilih',
            'status_tempat_tinggal.max' => 'Status tempat tinggal maksimal 100 karakter',
            'siswa_status_kepemilikan_rumah.required' => 'Status kepemilikan rumah wajib dipilih',
            'siswa_status_kepemilikan_rumah.max' => 'Status kepemilikan rumah maksimal 100 karakter',
            'siswa_provinsi.required' => 'Provinsi tempat tinggal wajib dipilih',
            'siswa_kabupaten.required' => 'Kabupaten tempat tinggal wajib dipilih',
            'siswa_kecamatan.required' => 'Kecamatan tempat tinggal wajib dipilih',
            'siswa_kelurahan.required' => 'Kelurahan tempat tinggal wajib dipilih',
            'siswa_rt.required' => 'RT tempat tinggal wajib diisi',
            'siswa_rt.max' => 'RT tempat tinggal maksimal 3 karakter',
            'siswa_rw.required' => 'RW tempat tinggal wajib diisi',
            'siswa_rw.max' => 'RW tempat tinggal maksimal 3 karakter',
            'siswa_kode_pos.required' => 'Kode pos tempat tinggal wajib diisi',
            'siswa_kode_pos.max' => 'Kode pos tempat tinggal maksimal 6 karakter',
            'siswa_alamat.required' => 'Alamat tempat tinggal wajib diisi',

            // Jarak, Transportasi, Waktu Tempuh
            'jarak_ke_sekolah.required' => 'Jarak ke sekolah wajib diisi',
            'jarak_ke_sekolah.max' => 'Jarak ke sekolah maksimal 50 karakter',
            'transportasi.required' => 'Jenis transportasi wajib dipilih',
            'transportasi.max' => 'Jenis transportasi maksimal 100 karakter',
            'waktu_tempuh.required' => 'Waktu tempuh wajib diisi',
            'waktu_tempuh.max' => 'Waktu tempuh maksimal 50 karakter',
        ]);

        // Auto-copy alamat siswa from ayah/ibu/wali if applicable
        if ($this->status_tempat_tinggal === 'Tinggal dengan Ayah Kandung') {
            $this->siswa_status_kepemilikan_rumah = $this->ayah_status_kepemilikan_rumah;
            $this->siswa_provinsi = $this->ayah_provinsi;
            $this->siswa_kabupaten = $this->ayah_kabupaten;
            $this->siswa_kecamatan = $this->ayah_kecamatan;
            $this->siswa_kelurahan = $this->ayah_kelurahan;
            $this->siswa_rt = $this->ayah_rt;
            $this->siswa_rw = $this->ayah_rw;
            $this->siswa_kode_pos = $this->ayah_kode_pos;
            $this->siswa_alamat = $this->alamat_ayah;
        } elseif ($this->status_tempat_tinggal === 'Tinggal dengan Ibu Kandung') {
            $this->siswa_status_kepemilikan_rumah = $this->ibu_status_kepemilikan_rumah;
            $this->siswa_provinsi = $this->ibu_provinsi;
            $this->siswa_kabupaten = $this->ibu_kabupaten;
            $this->siswa_kecamatan = $this->ibu_kecamatan;
            $this->siswa_kelurahan = $this->ibu_kelurahan;
            $this->siswa_rt = $this->ibu_rt;
            $this->siswa_rw = $this->ibu_rw;
            $this->siswa_kode_pos = $this->ibu_kode_pos;
            $this->siswa_alamat = $this->ibu_alamat;
        } elseif ($this->status_tempat_tinggal === 'Tinggal dengan Wali') {
            $this->siswa_status_kepemilikan_rumah = $this->wali_status_kepemilikan_rumah;
            $this->siswa_provinsi = $this->wali_provinsi;
            $this->siswa_kabupaten = $this->wali_kabupaten;
            $this->siswa_kecamatan = $this->wali_kecamatan;
            $this->siswa_kelurahan = $this->wali_kelurahan;
            $this->siswa_rt = $this->wali_rt;
            $this->siswa_rw = $this->wali_rw;
            $this->siswa_kode_pos = $this->wali_kode_pos;
            $this->siswa_alamat = $this->alamat_wali;
        }

        $this->pendaftar->update([
            'alamat' => $this->alamat,
            'rt' => $this->rt,
            'rw' => $this->rw,
            'dusun' => $this->dusun,
            'desa' => $this->desa,
            'kecamatan' => $this->kecamatan,
            'kabupaten' => $this->kabupaten,
            'provinsi' => $this->provinsi,
            'kode_pos' => $this->kode_pos,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'alamat_ayah' => $this->alamat_ayah,
            'alamat_wali' => $this->alamat_wali,
            'asal_kabkota' => $this->asal_kabkota,
            'telepon' => $this->telepon,
            'no_hp' => $this->no_hp,
            'email' => $this->email,

            // Ayah address
            'ayah_tinggal_luar_negeri' => $this->ayah_tinggal_luar_negeri ?? false,
            'ayah_status_kepemilikan_rumah' => $this->ayah_status_kepemilikan_rumah,
            'ayah_provinsi' => $this->ayah_provinsi,
            'ayah_kabupaten' => $this->ayah_kabupaten,
            'ayah_kecamatan' => $this->ayah_kecamatan,
            'ayah_kelurahan' => $this->ayah_kelurahan,
            'ayah_rt' => $this->ayah_rt,
            'ayah_rw' => $this->ayah_rw,
            'ayah_kode_pos' => $this->ayah_kode_pos,

            // Ibu address
            'ibu_tinggal_luar_negeri' => $this->ibu_tinggal_luar_negeri ?? false,
            'ibu_sama_dengan_ayah' => $this->ibu_sama_dengan_ayah ?? false,
            'ibu_status_kepemilikan_rumah' => $this->ibu_status_kepemilikan_rumah,
            'ibu_provinsi' => $this->ibu_provinsi,
            'ibu_kabupaten' => $this->ibu_kabupaten,
            'ibu_kecamatan' => $this->ibu_kecamatan,
            'ibu_kelurahan' => $this->ibu_kelurahan,
            'ibu_rt' => $this->ibu_rt,
            'ibu_rw' => $this->ibu_rw,
            'ibu_kode_pos' => $this->ibu_kode_pos,
            'alamat_ibu' => $this->ibu_alamat,

            // Wali address
            'wali_status_wali' => $this->wali_status_wali,
            'wali_tinggal_luar_negeri' => $this->wali_tinggal_luar_negeri ?? false,
            'wali_sama_dengan_ayah' => $this->wali_sama_dengan_ayah ?? false,
            'wali_status_kepemilikan_rumah' => $this->wali_status_kepemilikan_rumah,
            'wali_provinsi' => $this->wali_provinsi,
            'wali_kabupaten' => $this->wali_kabupaten,
            'wali_kecamatan' => $this->wali_kecamatan,
            'wali_kelurahan' => $this->wali_kelurahan,
            'wali_rt' => $this->wali_rt,
            'wali_rw' => $this->wali_rw,
            'wali_kode_pos' => $this->wali_kode_pos,

            // Siswa address
            'status_tempat_tinggal' => $this->status_tempat_tinggal,
            'siswa_status_kepemilikan_rumah' => $this->siswa_status_kepemilikan_rumah,
            'siswa_provinsi' => $this->siswa_provinsi,
            'siswa_kabupaten' => $this->siswa_kabupaten,
            'siswa_kecamatan' => $this->siswa_kecamatan,
            'siswa_kelurahan' => $this->siswa_kelurahan,
            'siswa_rt' => $this->siswa_rt,
            'siswa_rw' => $this->siswa_rw,
            'siswa_kode_pos' => $this->siswa_kode_pos,
            'siswa_alamat' => $this->siswa_alamat,

            // Jarak, transportasi, waktu tempuh
            'jarak_ke_sekolah' => $this->jarak_ke_sekolah,
            'transportasi' => $this->transportasi,
            'waktu_tempuh' => $this->waktu_tempuh,
        ]);

        // No need to refresh or reload properties - Livewire will handle the state
        $this->editModeAlamat = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data alamat berhasil diperbarui.']);
    }

    public function saveDataSekolah()
    {
        $this->pendaftar->update([
            'asal_sekolah' => $this->asal_sekolah,
            'jenis_sekolah_asal' => $this->jenis_sekolah_asal,
            'nsm_sekolah_asal' => $this->nsm_sekolah_asal,
            'npsn_sekolah_asal' => $this->npsn_sekolah_asal,
            'alamat_sekolah' => $this->alamat_sekolah,
            'nilai_rata_rata' => $this->nilai_rata_rata,
        ]);
        $this->editModeSekolah = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data sekolah berhasil diperbarui.']);
    }

    public function saveDataRapor()
    {
        $this->validate([
            'nilai_b_indo_5_1' => 'nullable|numeric|min:0|max:100',
            'nilai_matematika_5_1' => 'nullable|numeric|min:0|max:100',
            'nilai_ipa_5_1' => 'nullable|numeric|min:0|max:100',
            'nilai_b_indo_5_2' => 'nullable|numeric|min:0|max:100',
            'nilai_matematika_5_2' => 'nullable|numeric|min:0|max:100',
            'nilai_ipa_5_2' => 'nullable|numeric|min:0|max:100',
            'nilai_b_indo_6_1' => 'nullable|numeric|min:0|max:100',
            'nilai_matematika_6_1' => 'nullable|numeric|min:0|max:100',
            'nilai_ipa_6_1' => 'nullable|numeric|min:0|max:100',
        ]);

        $this->pendaftar->update([
            'nilai_b_indo_5_1' => $this->nilai_b_indo_5_1,
            'nilai_matematika_5_1' => $this->nilai_matematika_5_1,
            'nilai_ipa_5_1' => $this->nilai_ipa_5_1,
            'nilai_b_indo_5_2' => $this->nilai_b_indo_5_2,
            'nilai_matematika_5_2' => $this->nilai_matematika_5_2,
            'nilai_ipa_5_2' => $this->nilai_ipa_5_2,
            'nilai_b_indo_6_1' => $this->nilai_b_indo_6_1,
            'nilai_matematika_6_1' => $this->nilai_matematika_6_1,
            'nilai_ipa_6_1' => $this->nilai_ipa_6_1,
        ]);
        $this->editModeRapor = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data rapor berhasil diperbarui.']);
    }

    public function saveDataPrestasi()
    {
        // Handle old prestasi format (textarea)
        $this->pendaftar->update([
            'prestasi' => $this->prestasi,
        ]);
        $this->editModePrestasi = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data prestasi berhasil diperbarui.']);
    }

    // ========================================
    // PRESTASI TERSTRUKTUR METHODS
    // ========================================

    public function loadPrestasi()
    {
        $prestasis = $this->pendaftar->prestasis;

        if ($prestasis->count() > 0) {
            $this->prestasiList = $prestasis->map(function ($prestasi) {
                return [
                    'id' => $prestasi->id,
                    'tahun' => $prestasi->tahun,
                    'nama_lomba' => $prestasi->nama_lomba,
                    'bidang_lomba' => $prestasi->bidang_lomba,
                    'nama_penyelenggara' => $prestasi->nama_penyelenggara,
                    'tingkat_lomba' => $prestasi->tingkat_lomba,
                    'peringkat' => $prestasi->peringkat,
                    'kategori_lomba' => $prestasi->kategori_lomba,
                    'file_sertifikat' => $prestasi->file_sertifikat,
                ];
            })->toArray();
        } else {
            // Initialize dengan satu prestasi kosong
            $this->prestasiList = [[
                'id' => null,
                'tahun' => null,
                'nama_lomba' => null,
                'bidang_lomba' => null,
                'nama_penyelenggara' => null,
                'tingkat_lomba' => null,
                'peringkat' => null,
                'kategori_lomba' => null,
                'file_sertifikat' => null,
            ]];
        }
    }

    public function addPrestasi()
    {
        $this->prestasiList[] = [
            'id' => null,
            'tahun' => null,
            'nama_lomba' => null,
            'bidang_lomba' => null,
            'nama_penyelenggara' => null,
            'tingkat_lomba' => null,
            'peringkat' => null,
            'kategori_lomba' => null,
            'file_sertifikat' => null,
        ];
    }

    public function removePrestasi($index)
    {
        // Jika ada id, hapus dari database
        if (isset($this->prestasiList[$index]['id']) && $this->prestasiList[$index]['id']) {
            $prestasi = Prestasi::find($this->prestasiList[$index]['id']);
            if ($prestasi) {
                // Delete file if exists
                if ($prestasi->file_sertifikat && Storage::disk('public')->exists($prestasi->file_sertifikat)) {
                    Storage::disk('public')->delete($prestasi->file_sertifikat);
                }
                $prestasi->delete();
            }
        }

        // Hapus dari array
        unset($this->prestasiList[$index]);
        $this->prestasiList = array_values($this->prestasiList); // Re-index array
    }

    public function saveDataPrestasiTerstruktur()
    {
        // Validate all prestasi entries
        $this->validate([
            'prestasiList.*.tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'prestasiList.*.nama_lomba' => 'required|string|max:255',
            'prestasiList.*.bidang_lomba' => 'required|string|max:100',
            'prestasiList.*.nama_penyelenggara' => 'required|string|max:255',
            'prestasiList.*.tingkat_lomba' => 'required|string|max:50',
            'prestasiList.*.peringkat' => 'required|string|max:100',
            'prestasiList.*.kategori_lomba' => 'required|string|max:50',
            'uploadPrestasiSertifikat.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Delete existing prestasi yang tidak ada di list
        $existingIds = collect($this->prestasiList)->pluck('id')->filter()->toArray();
        $this->pendaftar->prestasis()->whereNotIn('id', $existingIds)->delete();

        // Save atau update each prestasi
        foreach ($this->prestasiList as $index => $prestasiData) {
            $dataToSave = [
                'tahun' => $prestasiData['tahun'],
                'nama_lomba' => $prestasiData['nama_lomba'],
                'bidang_lomba' => $prestasiData['bidang_lomba'],
                'nama_penyelenggara' => $prestasiData['nama_penyelenggara'],
                'tingkat_lomba' => $prestasiData['tingkat_lomba'],
                'peringkat' => $prestasiData['peringkat'],
                'kategori_lomba' => $prestasiData['kategori_lomba'],
            ];

            // Handle file upload
            if (isset($this->uploadPrestasiSertifikat[$index])) {
                $file = $this->uploadPrestasiSertifikat[$index];

                // Delete old file FIRST if exists
                if ($prestasiData['id']) {
                    $oldPrestasi = Prestasi::find($prestasiData['id']);
                    if ($oldPrestasi && $oldPrestasi->file_sertifikat) {
                        // Try both public and private storage for backward compatibility
                        if (Storage::disk('private')->exists($oldPrestasi->file_sertifikat)) {
                            Storage::disk('private')->delete($oldPrestasi->file_sertifikat);
                        } elseif (Storage::disk('public')->exists($oldPrestasi->file_sertifikat)) {
                            Storage::disk('public')->delete($oldPrestasi->file_sertifikat);
                        }
                    }
                }

                // Add index to filename to prevent collision when multiple prestasi uploaded
                $filename = FileNamingService::generateFileName(
                    'sertifikat_prestasi_' . ($index + 1), // Add index to make unique
                    $this->pendaftar->nomor_pendaftaran,
                    $file->getClientOriginalExtension(),
                    $this->pendaftar->nama_lengkap
                );

                // Store to private storage untuk keamanan
                $path = $file->storeAs('sertifikat_prestasi/' . $this->pendaftar->id, $filename, 'private');
                $dataToSave['file_sertifikat'] = $path;
            }

            if ($prestasiData['id']) {
                // Update existing
                Prestasi::where('id', $prestasiData['id'])->update($dataToSave);
            } else {
                // Create new
                $dataToSave['pendaftar_id'] = $this->pendaftar->id;
                Prestasi::create($dataToSave);
            }
        }

        $this->editModeDataPrestasi = false;
        $this->uploadPrestasiSertifikat = [];
        $this->loadPrestasi(); // Reload data
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data prestasi berhasil diperbarui.']);
    }

    // ========================================
    // Beasiswa Methods (Structured)
    // ========================================

    public function loadBeasiswa()
    {
        $beasiswas = $this->pendaftar->beasiswas;

        if ($beasiswas->count() > 0) {
            $this->beasiswaList = $beasiswas->map(function ($beasiswa) {
                return [
                    'id' => $beasiswa->id,
                    'tahun' => $beasiswa->tahun,
                    'kategori' => $beasiswa->kategori,
                    'nama_beasiswa' => $beasiswa->nama_beasiswa,
                    'jenis_instansi_pemberi' => $beasiswa->jenis_instansi_pemberi,
                    'nama_instansi_pemberi' => $beasiswa->nama_instansi_pemberi,
                    'jangka_waktu_bulan' => $beasiswa->jangka_waktu_bulan,
                    'nominal_beasiswa' => $beasiswa->nominal_beasiswa,
                ];
            })->toArray();
        } else {
            // Initialize dengan satu beasiswa kosong
            $this->beasiswaList = [[
                'id' => null,
                'tahun' => null,
                'kategori' => null,
                'nama_beasiswa' => null,
                'jenis_instansi_pemberi' => null,
                'nama_instansi_pemberi' => null,
                'jangka_waktu_bulan' => null,
                'nominal_beasiswa' => null,
            ]];
        }
    }

    public function addBeasiswa()
    {
        $this->beasiswaList[] = [
            'id' => null,
            'tahun' => null,
            'kategori' => null,
            'nama_beasiswa' => null,
            'jenis_instansi_pemberi' => null,
            'nama_instansi_pemberi' => null,
            'jangka_waktu_bulan' => null,
            'nominal_beasiswa' => null,
        ];
    }

    public function removeBeasiswa($index)
    {
        // Jika ada id, hapus dari database
        if (isset($this->beasiswaList[$index]['id']) && $this->beasiswaList[$index]['id']) {
            Beasiswa::find($this->beasiswaList[$index]['id'])?->delete();
        }

        // Hapus dari array
        unset($this->beasiswaList[$index]);
        $this->beasiswaList = array_values($this->beasiswaList); // Re-index array
    }

    public function saveDataBeasiswaTerstruktur()
    {
        // Validate all beasiswa entries
        $this->validate([
            'beasiswaList.*.tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'beasiswaList.*.kategori' => 'required|string|max:100',
            'beasiswaList.*.nama_beasiswa' => 'required|string|max:255',
            'beasiswaList.*.jenis_instansi_pemberi' => 'required|string|max:100',
            'beasiswaList.*.nama_instansi_pemberi' => 'required|string|max:255',
            'beasiswaList.*.jangka_waktu_bulan' => 'required|integer|min:1|max:99',
            'beasiswaList.*.nominal_beasiswa' => 'required|numeric|min:0',
        ]);

        // Delete existing beasiswa yang tidak ada di list
        $existingIds = collect($this->beasiswaList)->pluck('id')->filter()->toArray();
        $this->pendaftar->beasiswas()->whereNotIn('id', $existingIds)->delete();

        // Save atau update each beasiswa
        foreach ($this->beasiswaList as $beasiswaData) {
            if ($beasiswaData['id']) {
                // Update existing
                Beasiswa::where('id', $beasiswaData['id'])->update([
                    'tahun' => $beasiswaData['tahun'],
                    'kategori' => $beasiswaData['kategori'],
                    'nama_beasiswa' => $beasiswaData['nama_beasiswa'],
                    'jenis_instansi_pemberi' => $beasiswaData['jenis_instansi_pemberi'],
                    'nama_instansi_pemberi' => $beasiswaData['nama_instansi_pemberi'],
                    'jangka_waktu_bulan' => $beasiswaData['jangka_waktu_bulan'],
                    'nominal_beasiswa' => $beasiswaData['nominal_beasiswa'],
                ]);
            } else {
                // Create new
                Beasiswa::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'tahun' => $beasiswaData['tahun'],
                    'kategori' => $beasiswaData['kategori'],
                    'nama_beasiswa' => $beasiswaData['nama_beasiswa'],
                    'jenis_instansi_pemberi' => $beasiswaData['jenis_instansi_pemberi'],
                    'nama_instansi_pemberi' => $beasiswaData['nama_instansi_pemberi'],
                    'jangka_waktu_bulan' => $beasiswaData['jangka_waktu_bulan'],
                    'nominal_beasiswa' => $beasiswaData['nominal_beasiswa'],
                ]);
            }
        }

        $this->editModeDataBeasiswa = false;
        $this->loadBeasiswa(); // Reload data
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data beasiswa berhasil diperbarui.']);
    }

    // ========================================
    // Pendidikan Lain Methods (Structured)
    // ========================================

    public function loadPendidikanLain()
    {
        $pendidikanLains = $this->pendaftar->pendidikanLains;

        if ($pendidikanLains->count() > 0) {
            $this->pendidikanLainList = $pendidikanLains->map(function ($pendidikan) {
                return [
                    'id' => $pendidikan->id,
                    'nama_lembaga' => $pendidikan->nama_lembaga,
                    'jenis_lembaga' => $pendidikan->jenis_lembaga,
                    'mulai_belajar' => $pendidikan->mulai_belajar ? $pendidikan->mulai_belajar->format('Y-m-d') : null,
                    'frekuensi_belajar' => $pendidikan->frekuensi_belajar,
                    'lokasi_lembaga' => $pendidikan->lokasi_lembaga,
                ];
            })->toArray();
        } else {
            // Initialize dengan satu pendidikan kosong
            $this->pendidikanLainList = [[
                'id' => null,
                'nama_lembaga' => null,
                'jenis_lembaga' => null,
                'mulai_belajar' => null,
                'frekuensi_belajar' => null,
                'lokasi_lembaga' => null,
            ]];
        }
    }

    public function addPendidikanLain()
    {
        $this->pendidikanLainList[] = [
            'id' => null,
            'nama_lembaga' => null,
            'jenis_lembaga' => null,
            'mulai_belajar' => null,
            'frekuensi_belajar' => null,
            'lokasi_lembaga' => null,
        ];
    }

    public function removePendidikanLain($index)
    {
        // Jika ada id, hapus dari database
        if (isset($this->pendidikanLainList[$index]['id']) && $this->pendidikanLainList[$index]['id']) {
            PendidikanLain::find($this->pendidikanLainList[$index]['id'])?->delete();
        }

        // Hapus dari array
        unset($this->pendidikanLainList[$index]);
        $this->pendidikanLainList = array_values($this->pendidikanLainList); // Re-index array
    }

    public function saveDataPendidikanLainTerstruktur()
    {
        // Validate all pendidikan lain entries
        $this->validate([
            'pendidikanLainList.*.nama_lembaga' => 'required|string|max:255',
            'pendidikanLainList.*.jenis_lembaga' => 'required|string|max:255',
            'pendidikanLainList.*.mulai_belajar' => 'required|date',
            'pendidikanLainList.*.frekuensi_belajar' => 'required|string|max:50',
            'pendidikanLainList.*.lokasi_lembaga' => 'required|string',
        ]);

        // Delete existing pendidikan lain yang tidak ada di list
        $existingIds = collect($this->pendidikanLainList)->pluck('id')->filter()->toArray();
        $this->pendaftar->pendidikanLains()->whereNotIn('id', $existingIds)->delete();

        // Save atau update each pendidikan lain
        foreach ($this->pendidikanLainList as $pendidikanData) {
            if ($pendidikanData['id']) {
                // Update existing
                PendidikanLain::where('id', $pendidikanData['id'])->update([
                    'nama_lembaga' => $pendidikanData['nama_lembaga'],
                    'jenis_lembaga' => $pendidikanData['jenis_lembaga'],
                    'mulai_belajar' => $pendidikanData['mulai_belajar'],
                    'frekuensi_belajar' => $pendidikanData['frekuensi_belajar'],
                    'lokasi_lembaga' => $pendidikanData['lokasi_lembaga'],
                ]);
            } else {
                // Create new
                PendidikanLain::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'nama_lembaga' => $pendidikanData['nama_lembaga'],
                    'jenis_lembaga' => $pendidikanData['jenis_lembaga'],
                    'mulai_belajar' => $pendidikanData['mulai_belajar'],
                    'frekuensi_belajar' => $pendidikanData['frekuensi_belajar'],
                    'lokasi_lembaga' => $pendidikanData['lokasi_lembaga'],
                ]);
            }
        }

        $this->editModeDataPendidikanLain = false;
        $this->loadPendidikanLain(); // Reload data
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data pendidikan lain berhasil diperbarui.']);
    }

    public function saveDataBeasiswa()
    {
        $this->validate([
            'penerima_kps' => 'nullable|boolean',
            'nomor_kps' => 'nullable|numeric|digits_between:1,17|required_if:penerima_kps,true',
            'penerima_kip' => 'nullable|boolean',
            'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
            'layak_pip' => 'nullable|boolean',
            'alasan_layak_pip' => 'nullable|string|required_if:layak_pip,true',
        ]);

        // Auto set penerima_kip to true if nomor_kip is filled
        $isPenerimaKip = !empty($this->nomor_kip);

        $dataToUpdate = [
            'penerima_kps' => $this->penerima_kps ?? false,
            'nomor_kps' => $this->nomor_kps,
            'penerima_kip' => $isPenerimaKip,
            'nomor_kip' => $this->nomor_kip,
            'layak_pip' => $this->layak_pip ?? false,
            'alasan_layak_pip' => $this->alasan_layak_pip,
        ];

        $this->pendaftar->update($dataToUpdate);
        $this->editModeBeasiswa = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data beasiswa & bantuan berhasil diperbarui.']);
    }

    public function saveDataPendidikan()
    {
        $this->validate([
            'tk_npsn' => 'nullable|string|max:20',
            'sd_npsn' => 'nullable|string|max:20',
            'sd_tahun_lulus' => 'nullable|integer|min:1990|max:' . date('Y'),
            'sd_nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_ipa' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_ips' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_bahasa_indonesia' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_bahasa_inggris' => 'nullable|numeric|min:0|max:100',
        ]);

        $dataToUpdate = [
            'tk_nama' => $this->tk_nama,
            'tk_npsn' => $this->tk_npsn,
            'tk_tahun_lulus' => $this->tk_tahun_lulus,
            'sd_nama' => $this->sd_nama,
            'sd_npsn' => $this->sd_npsn,
            'sd_tahun_lulus' => $this->sd_tahun_lulus,
            'sd_nomor_peserta_ujian' => $this->sd_nomor_peserta_ujian,
            'sd_nomor_seri_ijazah' => $this->sd_nomor_seri_ijazah,
            'sd_nomor_skhun' => $this->sd_nomor_skhun,
            'sd_nilai_matematika' => $this->sd_nilai_matematika,
            'sd_nilai_ipa' => $this->sd_nilai_ipa,
            'sd_nilai_ips' => $this->sd_nilai_ips,
            'sd_nilai_bahasa_indonesia' => $this->sd_nilai_bahasa_indonesia,
            'sd_nilai_bahasa_inggris' => $this->sd_nilai_bahasa_inggris,
            'hobi' => $this->hobi,
            'cita_cita' => $this->cita_cita,
            'berkebutuhan_khusus' => $this->berkebutuhan_khusus,
            'jenis_kebutuhan_khusus' => $this->jenis_kebutuhan_khusus,
        ];

        $this->pendaftar->update($dataToUpdate);
        $this->editModePendidikan = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data pendidikan lain berhasil diperbarui.']);
    }

    public function saveDataSurvey()
    {
        // Build validation rules for required questions
        $surveyQuestions = \App\Models\SurveyQuestion::aktif()->umum()->ordered()->get();
        $validationRules = [];
        $validationMessages = [];

        foreach ($surveyQuestions as $question) {
            if ($question->wajib_diisi) {
                $validationRules["surveyAnswers.{$question->id}"] = 'required';
                $validationMessages["surveyAnswers.{$question->id}.required"] = "Pertanyaan '{$question->pertanyaan}' wajib diisi.";
            }
        }

        // Validate if there are required questions
        if (!empty($validationRules)) {
            $this->validate($validationRules, $validationMessages);
        }

        foreach ($surveyQuestions as $question) {
            $answerValue = $this->surveyAnswers[$question->id] ?? null;

            if ($answerValue) {
                // Delete existing answer
                \App\Models\SurveyAnswer::where('pendaftar_id', $this->pendaftar->id)
                    ->where('survey_question_id', $question->id)
                    ->delete();

                // Prepare jawaban based on question type
                $jawaban = null;

                if ($question->tipe_input === 'text' || $question->tipe_input === 'textarea') {
                    // For text type: store text directly
                    $jawaban = $answerValue;
                } elseif ($question->tipe_input === 'checkbox') {
                    // NEW APPROACH: For checkbox, answerValue is LINEAR array [1, 3, 5]
                    // CRITICAL FIX: Filter out boolean values dari checkbox array
                    // Livewire kadang inject boolean true ke array checkbox
                    if (is_array($answerValue)) {
                        $filteredValues = array_filter($answerValue, function($val) {
                            return !is_bool($val) && $val !== '' && $val !== null;
                        });
                        // Re-index array (remove gaps dari filter)
                        $jawaban = array_values($filteredValues);
                    } else {
                        $jawaban = [];
                    }
                } else {
                    // For dropdown and radio: store single option ID
                    $jawaban = $answerValue;
                }

                // Create new answer with jawaban field
                if ($jawaban !== null && (!is_array($jawaban) || !empty($jawaban))) {
                    // CRITICAL FIX: JSON encode arrays (checkbox) before saving to TEXT column
                    $jawabanToSave = is_array($jawaban) ? json_encode($jawaban) : $jawaban;

                    \App\Models\SurveyAnswer::create([
                        'pendaftar_id' => $this->pendaftar->id,
                        'survey_question_id' => $question->id,
                        'jawaban' => $jawabanToSave,
                    ]);
                }
            }
        }

        $this->editModeSurvey = false;
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data survey berhasil diperbarui.']);
    }

    public function calculateAverageNilai()
    {
        $nilai = [
            $this->sd_nilai_matematika,
            $this->sd_nilai_ipa,
            $this->sd_nilai_ips,
            $this->sd_nilai_bahasa_indonesia,
            $this->sd_nilai_bahasa_inggris
        ];

        $nilaiValid = array_filter($nilai, fn($n) => !is_null($n) && $n !== '');

        if (count($nilaiValid) === 0) {
            return '-';
        }

        $average = array_sum($nilaiValid) / count($nilaiValid);
        return number_format($average, 2);
    }

    // ========================================
    // CASCADE DROPDOWN METHODS - AYAH
    // ========================================

    // Provinces are loaded via $provincesList property in loadCascadeData()
    // Ayah Cities, Districts, and Villages are loaded via array properties
    // populated in updatedXxx() methods for better Livewire 4.0 compatibility

    public function updatedAyahProvinsi($value)
    {
        // Reset dependent fields
        $this->ayah_kabupaten = null;
        $this->ayah_kecamatan = null;
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Load cities for selected province
        if ($this->ayah_provinsi) {
            $this->ayahCitiesList = Regency::where('province_id', $this->ayah_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->ayahCitiesList = [];
        }

        // Reset dependent lists
        $this->ayahDistrictsList = [];
        $this->ayahVillagesList = [];
    }

    public function updatedAyahKabupaten($value)
    {
        // Reset dependent fields
        $this->ayah_kecamatan = null;
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Load districts for selected city
        if ($this->ayah_kabupaten) {
            $this->ayahDistrictsList = District::where('regency_id', $this->ayah_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->ayahDistrictsList = [];
        }

        // Reset dependent lists
        $this->ayahVillagesList = [];
    }

    public function updatedAyahKecamatan($value)
    {
        // Reset dependent fields
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Load villages for selected district
        if ($this->ayah_kecamatan) {
            $this->ayahVillagesList = Village::where('district_id', $this->ayah_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->ayahVillagesList = [];
        }
    }

    public function updatedAyahKelurahan()
    {
        if ($this->ayah_kelurahan && $this->ayah_kecamatan && $this->ayah_kabupaten) {
            // Get village, district, and city names
            $village = Village::where('id', $this->ayah_kelurahan)->first();
            $district = District::where('id', $this->ayah_kecamatan)->first();
            $regency = Regency::where('id', $this->ayah_kabupaten)->first();

            if ($village && $district && $regency) {
                // Remove KABUPATEN/KOTA prefix from regency name for matching
                $cityName = preg_replace('/^(KABUPATEN|KOTA)\s+/i', '', $regency->name);

                // Query postal_codes table
                $postalCode = \DB::table('postal_codes')
                    ->where('urban', 'LIKE', '%' . $village->name . '%')
                    ->where('sub_district', 'LIKE', '%' . $district->name . '%')
                    ->where('city', 'LIKE', '%' . $cityName . '%')
                    ->first();

                if ($postalCode) {
                    $this->ayah_kode_pos = $postalCode->postal_code;
                }
            }
        }
    }

    // ========================================
    // CASCADE DROPDOWN METHODS - IBU
    // ========================================

    // Ibu Cities, Districts, and Villages are loaded via array properties
    // populated in updatedXxx() methods for better Livewire 4.0 compatibility

    public function updatedIbuProvinsi()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            // Reset dependent fields
            $this->ibu_kabupaten = null;
            $this->ibu_kecamatan = null;
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;

            // Load cities for selected province
            if ($this->ibu_provinsi) {
                $this->ibuCitiesList = Regency::where('province_id', $this->ibu_provinsi)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->ibuCitiesList = [];
            }

            // Reset dependent lists
            $this->ibuDistrictsList = [];
            $this->ibuVillagesList = [];
        }
    }

    public function updatedIbuKabupaten()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            // Reset dependent fields
            $this->ibu_kecamatan = null;
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;

            // Load districts for selected city
            if ($this->ibu_kabupaten) {
                $this->ibuDistrictsList = District::where('regency_id', $this->ibu_kabupaten)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->ibuDistrictsList = [];
            }

            // Reset dependent lists
            $this->ibuVillagesList = [];
        }
    }

    public function updatedIbuKecamatan()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            // Reset dependent fields
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;

            // Load villages for selected district
            if ($this->ibu_kecamatan) {
                $this->ibuVillagesList = Village::where('district_id', $this->ibu_kecamatan)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->ibuVillagesList = [];
            }
        }
    }

    public function updatedIbuKelurahan()
    {
        if ($this->ibu_kelurahan && $this->ibu_kecamatan && $this->ibu_kabupaten) {
            // Get village, district, and city names
            $village = Village::where('id', $this->ibu_kelurahan)->first();
            $district = District::where('id', $this->ibu_kecamatan)->first();
            $regency = Regency::where('id', $this->ibu_kabupaten)->first();

            if ($village && $district && $regency) {
                // Remove KABUPATEN/KOTA prefix from regency name for matching
                $cityName = preg_replace('/^(KABUPATEN|KOTA)\s+/i', '', $regency->name);

                // Query postal_codes table
                $postalCode = \DB::table('postal_codes')
                    ->where('urban', 'LIKE', '%' . $village->name . '%')
                    ->where('sub_district', 'LIKE', '%' . $district->name . '%')
                    ->where('city', 'LIKE', '%' . $cityName . '%')
                    ->first();

                if ($postalCode) {
                    $this->ibu_kode_pos = $postalCode->postal_code;
                }
            }
        }
    }

    public function copyAyahToIbu()
    {
        \Log::info('copyAyahToIbu called', [
            'ibu_sama_dengan_ayah' => $this->ibu_sama_dengan_ayah,
            'ayah_provinsi' => $this->ayah_provinsi,
            'ayah_kabupaten' => $this->ayah_kabupaten
        ]);

        if ($this->ibu_sama_dengan_ayah) {
            // Set flag to skip cascade reset
            $this->skipCascadeReset = true;

            // Copy semua data alamat ayah ke ibu
            $this->ibu_status_kepemilikan_rumah = $this->ayah_status_kepemilikan_rumah;
            $this->ibu_alamat = $this->alamat_ayah;
            $this->ibu_rt = $this->ayah_rt;
            $this->ibu_rw = $this->ayah_rw;
            $this->ibu_provinsi = $this->ayah_provinsi;
            $this->ibu_kabupaten = $this->ayah_kabupaten;
            $this->ibu_kecamatan = $this->ayah_kecamatan;
            $this->ibu_kelurahan = $this->ayah_kelurahan;
            $this->ibu_kode_pos = $this->ayah_kode_pos;

            // Reset flag after copy
            $this->skipCascadeReset = false;

            \Log::info('copyAyahToIbu copied', [
                'ibu_provinsi' => $this->ibu_provinsi,
                'ibu_kabupaten' => $this->ibu_kabupaten
            ]);
        } else {
            // Reset when unchecked
            $this->ibu_status_kepemilikan_rumah = null;
            $this->ibu_alamat = null;
            $this->ibu_rt = null;
            $this->ibu_rw = null;
            $this->ibu_provinsi = null;
            $this->ibu_kabupaten = null;
            $this->ibu_kecamatan = null;
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;
        }
    }

    public function copyAyahToWali()
    {
        if ($this->wali_sama_dengan_ayah) {
            // Set flag to skip cascade reset
            $this->skipCascadeReset = true;

            // Copy semua data alamat ayah ke wali
            $this->wali_status_kepemilikan_rumah = $this->ayah_status_kepemilikan_rumah;
            $this->alamat_wali = $this->alamat_ayah;
            $this->wali_rt = $this->ayah_rt;
            $this->wali_rw = $this->ayah_rw;
            $this->wali_provinsi = $this->ayah_provinsi;
            $this->wali_kabupaten = $this->ayah_kabupaten;
            $this->wali_kecamatan = $this->ayah_kecamatan;
            $this->wali_kelurahan = $this->ayah_kelurahan;
            $this->wali_kode_pos = $this->ayah_kode_pos;

            // Reset flag after copy
            $this->skipCascadeReset = false;
        } else {
            // Reset when unchecked
            $this->wali_status_kepemilikan_rumah = null;
            $this->alamat_wali = null;
            $this->wali_rt = null;
            $this->wali_rw = null;
            $this->wali_provinsi = null;
            $this->wali_kabupaten = null;
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;
        }
    }

    // ========================================
    // CASCADE DROPDOWN METHODS - WALI
    // ========================================

    // Wali Cities, Districts, and Villages are loaded via array properties
    // populated in updatedXxx() methods for better Livewire 4.0 compatibility

    public function updatedWaliStatusWali()
    {
        if ($this->wali_status_wali === 'sama_dengan_ayah') {
            // Copy all ayah address data to wali (cast IDs to string)
            $this->wali_status_kepemilikan_rumah = $this->ayah_status_kepemilikan_rumah;
            $this->alamat_wali = $this->alamat_ayah;
            $this->wali_provinsi = $this->ayah_provinsi ? (string)$this->ayah_provinsi : null;
            $this->wali_kabupaten = $this->ayah_kabupaten ? (string)$this->ayah_kabupaten : null;
            $this->wali_kecamatan = $this->ayah_kecamatan ? (string)$this->ayah_kecamatan : null;
            $this->wali_kelurahan = $this->ayah_kelurahan ? (string)$this->ayah_kelurahan : null;
            $this->wali_rt = $this->ayah_rt;
            $this->wali_rw = $this->ayah_rw;
            $this->wali_kode_pos = $this->ayah_kode_pos;

            // Load dropdown data for wali
            if ($this->wali_provinsi) {
                $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
            if ($this->wali_kabupaten) {
                $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
            if ($this->wali_kecamatan) {
                $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
        } elseif ($this->wali_status_wali === 'sama_dengan_ibu') {
            // Copy all ibu address data to wali (cast IDs to string)
            $this->wali_status_kepemilikan_rumah = $this->ibu_status_kepemilikan_rumah;
            $this->alamat_wali = $this->ibu_alamat;
            $this->wali_provinsi = $this->ibu_provinsi ? (string)$this->ibu_provinsi : null;
            $this->wali_kabupaten = $this->ibu_kabupaten ? (string)$this->ibu_kabupaten : null;
            $this->wali_kecamatan = $this->ibu_kecamatan ? (string)$this->ibu_kecamatan : null;
            $this->wali_kelurahan = $this->ibu_kelurahan ? (string)$this->ibu_kelurahan : null;
            $this->wali_rt = $this->ibu_rt;
            $this->wali_rw = $this->ibu_rw;
            $this->wali_kode_pos = $this->ibu_kode_pos;

            // Load dropdown data for wali
            if ($this->wali_provinsi) {
                $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
            if ($this->wali_kabupaten) {
                $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
            if ($this->wali_kecamatan) {
                $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)->orderBy('name')->get()->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
            }
        } else {
            // Reset all wali address fields when selecting "lainnya"
            $this->wali_status_kepemilikan_rumah = null;
            $this->alamat_wali = null;
            $this->wali_provinsi = null;
            $this->wali_kabupaten = null;
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_rt = null;
            $this->wali_rw = null;
            $this->wali_kode_pos = null;

            // Clear dropdown lists
            $this->waliCitiesList = [];
            $this->waliDistrictsList = [];
            $this->waliVillagesList = [];
        }
    }

    public function updatedWaliProvinsi()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            // Reset dependent fields
            $this->wali_kabupaten = null;
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;

            // Load cities for selected province
            if ($this->wali_provinsi) {
                $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->waliCitiesList = [];
            }

            // Reset dependent lists
            $this->waliDistrictsList = [];
            $this->waliVillagesList = [];
        }
    }

    public function updatedWaliKabupaten()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            // Reset dependent fields
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;

            // Load districts for selected city
            if ($this->wali_kabupaten) {
                $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->waliDistrictsList = [];
            }

            // Reset dependent lists
            $this->waliVillagesList = [];
        }
    }

    public function updatedWaliKecamatan()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            // Reset dependent fields
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;

            // Load villages for selected district
            if ($this->wali_kecamatan) {
                $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)
                    ->orderBy('name')
                    ->get()
                    ->map(function($item) {
                        return ['id' => (string)$item->id, 'name' => $item->name];
                    })
                    ->toArray();
            } else {
                $this->waliVillagesList = [];
            }
        }
    }

    public function updatedWaliKelurahan()
    {
        if ($this->wali_kelurahan && $this->wali_kecamatan && $this->wali_kabupaten) {
            // Get village, district, and city names
            $village = Village::where('id', $this->wali_kelurahan)->first();
            $district = District::where('id', $this->wali_kecamatan)->first();
            $regency = Regency::where('id', $this->wali_kabupaten)->first();

            if ($village && $district && $regency) {
                // Remove KABUPATEN/KOTA prefix from regency name for matching
                $cityName = preg_replace('/^(KABUPATEN|KOTA)\s+/i', '', $regency->name);

                // Query postal_codes table
                $postalCode = \DB::table('postal_codes')
                    ->where('urban', 'LIKE', '%' . $village->name . '%')
                    ->where('sub_district', 'LIKE', '%' . $district->name . '%')
                    ->where('city', 'LIKE', '%' . $cityName . '%')
                    ->first();

                if ($postalCode) {
                    $this->wali_kode_pos = $postalCode->postal_code;
                }
            }
        }
    }


    // ========================================
    // HELPER METHODS - GET NAMA WILAYAH
    // ========================================

    public function getNamaProvinsi($code)
    {
        if (!$code) return '-';
        $province = Province::where('id', $code)->first();
        return $province ? $province->name : $code;
    }

    public function getNamaKabupaten($code)
    {
        if (!$code) return '-';
        $city = Regency::where('id', $code)->first();
        return $city ? $city->name : $code;
    }

    public function getNamaKecamatan($code)
    {
        if (!$code) return '-';
        $district = District::where('id', $code)->first();
        return $district ? $district->name : $code;
    }

    public function getNamaKelurahan($code)
    {
        if (!$code) return '-';
        $village = Village::where('id', $code)->first();
        return $village ? $village->name : $code;
    }

    // ========================================
    // CASCADE DROPDOWN METHODS - SISWA
    // ========================================

    // Siswa Cities, Districts, and Villages are loaded via array properties
    // populated in updatedXxx() methods for better Livewire 4.0 compatibility

    public function updatedSiswaProvinsi()
    {
        // Reset dependent fields
        $this->siswa_kabupaten = null;
        $this->siswa_kecamatan = null;
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Load cities for selected province
        if ($this->siswa_provinsi) {
            $this->siswaCitiesList = Regency::where('province_id', $this->siswa_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->siswaCitiesList = [];
        }

        // Reset dependent lists
        $this->siswaDistrictsList = [];
        $this->siswaVillagesList = [];
    }

    public function updatedSiswaKabupaten()
    {
        // Reset dependent fields
        $this->siswa_kecamatan = null;
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Load districts for selected city
        if ($this->siswa_kabupaten) {
            $this->siswaDistrictsList = District::where('regency_id', $this->siswa_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->siswaDistrictsList = [];
        }

        // Reset dependent lists
        $this->siswaVillagesList = [];
    }

    public function updatedSiswaKecamatan()
    {
        // Reset dependent fields
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Load villages for selected district
        if ($this->siswa_kecamatan) {
            $this->siswaVillagesList = Village::where('district_id', $this->siswa_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })
                ->toArray();
        } else {
            $this->siswaVillagesList = [];
        }
    }

    public function updatedSiswaKelurahan()
    {
        if ($this->siswa_kelurahan && $this->siswa_kecamatan && $this->siswa_kabupaten) {
            // Get village, district, and city names
            $village = Village::where('id', $this->siswa_kelurahan)->first();
            $district = District::where('id', $this->siswa_kecamatan)->first();
            $regency = Regency::where('id', $this->siswa_kabupaten)->first();

            if ($village && $district && $regency) {
                // Remove KABUPATEN/KOTA prefix from regency name for matching
                $cityName = preg_replace('/^(KABUPATEN|KOTA)\s+/i', '', $regency->name);

                // Query postal_codes table
                $postalCode = \DB::table('postal_codes')
                    ->where('urban', 'LIKE', '%' . $village->name . '%')
                    ->where('sub_district', 'LIKE', '%' . $district->name . '%')
                    ->where('city', 'LIKE', '%' . $cityName . '%')
                    ->first();

                if ($postalCode) {
                    $this->siswa_kode_pos = $postalCode->postal_code;
                }
            }
        }
    }

    // ========================================
    // Keahlian Methods (Kursus/Club/Sanggar)
    // ========================================

    public function loadKeahlian()
    {
        $keahlians = $this->pendaftar->keahlians;

        if ($keahlians->count() > 0) {
            $this->keahlianList = $keahlians->map(function ($keahlian) {
                return [
                    'id' => $keahlian->id,
                    'bidang_keahlian' => $keahlian->bidang_keahlian,
                    'nama_keahlian' => $keahlian->nama_keahlian,
                    'sertifikasi' => $keahlian->sertifikasi,
                    'lembaga_penyelenggara' => $keahlian->lembaga_penyelenggara,
                    'hasil_tingkat_skor' => $keahlian->hasil_tingkat_skor,
                    'file_bukti_sertifikat' => $keahlian->file_bukti_sertifikat,
                ];
            })->toArray();
        } else {
            // Initialize dengan satu keahlian kosong
            $this->keahlianList = [[
                'id' => null,
                'bidang_keahlian' => null,
                'nama_keahlian' => null,
                'sertifikasi' => null,
                'lembaga_penyelenggara' => null,
                'hasil_tingkat_skor' => null,
                'file_bukti_sertifikat' => null,
            ]];
        }
    }

    public function addKeahlian()
    {
        $this->keahlianList[] = [
            'id' => null,
            'bidang_keahlian' => null,
            'nama_keahlian' => null,
            'sertifikasi' => null,
            'lembaga_penyelenggara' => null,
            'hasil_tingkat_skor' => null,
            'file_bukti_sertifikat' => null,
        ];
    }

    public function removeKeahlian($index)
    {
        // Jika ada id, hapus dari database
        if (isset($this->keahlianList[$index]['id']) && $this->keahlianList[$index]['id']) {
            $keahlian = Keahlian::find($this->keahlianList[$index]['id']);
            if ($keahlian) {
                // Delete file if exists
                if ($keahlian->file_bukti_sertifikat && Storage::disk('public')->exists($keahlian->file_bukti_sertifikat)) {
                    Storage::disk('public')->delete($keahlian->file_bukti_sertifikat);
                }
                $keahlian->delete();
            }
        }

        // Hapus dari array
        unset($this->keahlianList[$index]);
        $this->keahlianList = array_values($this->keahlianList); // Re-index array
    }

    public function saveDataKeahlian()
    {
        // Validate all keahlian entries
        $this->validate([
            'keahlianList.*.bidang_keahlian' => 'nullable|string|max:255',
            'keahlianList.*.nama_keahlian' => 'nullable|string|max:255',
            'keahlianList.*.sertifikasi' => 'nullable|string|max:255',
            'keahlianList.*.lembaga_penyelenggara' => 'nullable|string|max:255',
            'keahlianList.*.hasil_tingkat_skor' => 'nullable|string|max:100',
            'uploadKeahlianSertifikat.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Delete existing keahlian yang tidak ada di list
        $existingIds = collect($this->keahlianList)->pluck('id')->filter()->toArray();
        $this->pendaftar->keahlians()->whereNotIn('id', $existingIds)->delete();

        // Save atau update each keahlian
        foreach ($this->keahlianList as $index => $keahlianData) {
            // Skip if all fields are empty
            if (empty($keahlianData['bidang_keahlian']) &&
                empty($keahlianData['nama_keahlian']) &&
                empty($keahlianData['sertifikasi']) &&
                empty($keahlianData['lembaga_penyelenggara']) &&
                empty($keahlianData['hasil_tingkat_skor']) &&
                !isset($this->uploadKeahlianSertifikat[$index])) {
                continue;
            }

            $dataToSave = [
                'bidang_keahlian' => $keahlianData['bidang_keahlian'],
                'nama_keahlian' => $keahlianData['nama_keahlian'],
                'sertifikasi' => $keahlianData['sertifikasi'],
                'lembaga_penyelenggara' => $keahlianData['lembaga_penyelenggara'],
                'hasil_tingkat_skor' => $keahlianData['hasil_tingkat_skor'],
            ];

            // Handle file upload
            if (isset($this->uploadKeahlianSertifikat[$index])) {
                $file = $this->uploadKeahlianSertifikat[$index];

                // Delete old file FIRST if exists
                if ($keahlianData['id']) {
                    $oldKeahlian = Keahlian::find($keahlianData['id']);
                    if ($oldKeahlian && $oldKeahlian->file_bukti_sertifikat) {
                        // Try both public and private storage for backward compatibility
                        if (Storage::disk('private')->exists($oldKeahlian->file_bukti_sertifikat)) {
                            Storage::disk('private')->delete($oldKeahlian->file_bukti_sertifikat);
                        } elseif (Storage::disk('public')->exists($oldKeahlian->file_bukti_sertifikat)) {
                            Storage::disk('public')->delete($oldKeahlian->file_bukti_sertifikat);
                        }
                    }
                }

                // Add index to filename to prevent collision when multiple keahlian uploaded
                $filename = FileNamingService::generateFileName(
                    'sertifikat_keahlian_' . ($index + 1), // Add index to make unique
                    $this->pendaftar->nomor_pendaftaran,
                    $file->getClientOriginalExtension(),
                    $this->pendaftar->nama_lengkap
                );

                // Store to private storage untuk keamanan
                $path = $file->storeAs('sertifikat_keahlian/' . $this->pendaftar->id, $filename, 'private');
                $dataToSave['file_bukti_sertifikat'] = $path;
            }

            if ($keahlianData['id']) {
                // Update existing
                Keahlian::where('id', $keahlianData['id'])->update($dataToSave);
            } else {
                // Create new
                $dataToSave['pendaftar_id'] = $this->pendaftar->id;
                Keahlian::create($dataToSave);
            }
        }

        $this->editModeDataKeahlian = false;
        $this->uploadKeahlianSertifikat = [];
        $this->loadKeahlian(); // Reload data
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data keahlian berhasil diperbarui.']);
    }

    // ========================================
    // Tahfidz Methods
    // ========================================

    public function loadTahfidz()
    {
        $tahfidz = $this->pendaftar->tahfidz;

        if ($tahfidz) {
            $this->tahfidz = [
                'id' => $tahfidz->id,
                'juz_alquran_dihafal' => $tahfidz->juz_alquran_dihafal,
                'file_bukti_syahadah' => $tahfidz->file_bukti_syahadah,
                'file_bukti_tahsin' => $tahfidz->file_bukti_tahsin,
            ];
        } else {
            $this->tahfidz = [
                'id' => null,
                'juz_alquran_dihafal' => null,
                'file_bukti_syahadah' => null,
                'file_bukti_tahsin' => null,
            ];
        }
    }

    public function saveDataTahfidz()
    {
        // Validate tahfidz data
        $this->validate([
            'tahfidz.juz_alquran_dihafal' => 'nullable|string|max:255',
            'uploadTahfidzSyahadah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'uploadTahfidzTahsin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $dataToSave = [
            'juz_alquran_dihafal' => $this->tahfidz['juz_alquran_dihafal'],
        ];

        // Handle syahadah file upload
        if ($this->uploadTahfidzSyahadah) {
            // Delete old file FIRST if exists
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_syahadah) {
                    // Try both public and private storage for backward compatibility
                    if (Storage::disk('private')->exists($oldTahfidz->file_bukti_syahadah)) {
                        Storage::disk('private')->delete($oldTahfidz->file_bukti_syahadah);
                    } elseif (Storage::disk('public')->exists($oldTahfidz->file_bukti_syahadah)) {
                        Storage::disk('public')->delete($oldTahfidz->file_bukti_syahadah);
                    }
                }
            }

            $filename = FileNamingService::generateFileName(
                'syahadah',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzSyahadah->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            // Store to private storage untuk keamanan
            $path = $this->uploadTahfidzSyahadah->storeAs('tahfidz/' . $this->pendaftar->id, $filename, 'private');
            $dataToSave['file_bukti_syahadah'] = $path;
        }

        // Handle tahsin file upload
        if ($this->uploadTahfidzTahsin) {
            // Delete old file FIRST if exists
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_tahsin) {
                    // Try both public and private storage for backward compatibility
                    if (Storage::disk('private')->exists($oldTahfidz->file_bukti_tahsin)) {
                        Storage::disk('private')->delete($oldTahfidz->file_bukti_tahsin);
                    } elseif (Storage::disk('public')->exists($oldTahfidz->file_bukti_tahsin)) {
                        Storage::disk('public')->delete($oldTahfidz->file_bukti_tahsin);
                    }
                }
            }

            $filename = FileNamingService::generateFileName(
                'tahsin',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzTahsin->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            // Store to private storage untuk keamanan
            $path = $this->uploadTahfidzTahsin->storeAs('tahfidz/' . $this->pendaftar->id, $filename, 'private');
            $dataToSave['file_bukti_tahsin'] = $path;
        }

        if ($this->tahfidz['id']) {
            // Update existing
            Tahfidz::where('id', $this->tahfidz['id'])->update($dataToSave);
        } else {
            // Create new
            $dataToSave['pendaftar_id'] = $this->pendaftar->id;
            Tahfidz::create($dataToSave);
        }

        $this->editModeDataTahfidz = false;
        $this->uploadTahfidzSyahadah = null;
        $this->uploadTahfidzTahsin = null;
        $this->loadTahfidz(); // Reload data
        $this->dispatch('statusUpdated', ['type' => 'success', 'message' => 'Data tahfidz berhasil diperbarui.']);
    }

    public function render()
    {
        return view('livewire.pendaftar.formulir')
            ->layout('components.layouts.dashboard', [
                'role' => 'pendaftar',
                'pageTitle' => 'Formulir Pendaftaran',
                'pageDescription' => 'Lengkapi data pendaftaran Anda'
            ]);
    }
}