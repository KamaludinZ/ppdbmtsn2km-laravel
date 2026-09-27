<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\Pendaftar as PendaftarModel;
use App\Models\Prestasi;
use App\Models\Beasiswa;
use App\Models\PendidikanLain;
use App\Models\Keahlian;
use App\Models\Tahfidz;
use App\Models\VerifikasiBerkas;
use App\Models\Dokumen;
use App\Models\DaftarUlang;
use App\Models\Panitia;
use App\Services\ImageService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class DetailPendaftar extends Component
{
    use WithFileUploads;

    public $pendaftarId;
    public $pendaftar;
    public $verifikasi;
    public $dokumen;
    public $daftarUlang;

    // Active Tab with URL support
    #[Url]
    public $activeTab = 'siswa';

    // Mode edit - Section specific
    public $editMode = false;
    public $editModeSiswa = false;
    public $editModeOrangtua = false;
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
    public $editModeInfoAkun = false;

    // Status dokumen
    public $status_rapor;
    public $catatan_rapor;
    public $status_akte;
    public $catatan_akte;
    public $status_kk;
    public $catatan_kk;
    public $status_nisn;
    public $catatan_nisn;
    public $status_ijazah;
    public $catatan_ijazah;
    public $status_sertifikat;
    public $catatan_sertifikat;
    public $status_kip;
    public $catatan_kip;
    public $status_pkh;
    public $catatan_pkh;
    public $status_kks;
    public $catatan_kks;

    // Status Pendaftaran
    public $status_pendaftaran;
    public $catatan_verifikasi;

    // Data Pribadi
    public $nama_lengkap;
    public $nisn;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $agama;
    public $foto;
    public $newFoto;

    // Data Orang Tua
    public $nama_ayah;
    public $nama_ibu;
    public $pekerjaan_ayah;
    public $pekerjaan_ibu;
    public $penghasilan_ortu;
    public $telepon_ortu;

    // Data Alamat
    public $alamat;
    public $asal_kabkota;
    public $telepon;

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
    public $no_hp;
    public $email;
    public $password_baru;

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

    // Edit Nilai Rapor
    public $showEditNilaiRapor = false;
    public $edit_nilai_b_indo_5_1;
    public $edit_nilai_matematika_5_1;
    public $edit_nilai_ipa_5_1;
    public $edit_nilai_b_indo_5_2;
    public $edit_nilai_matematika_5_2;
    public $edit_nilai_ipa_5_2;
    public $edit_nilai_b_indo_6_1;
    public $edit_nilai_matematika_6_1;
    public $edit_nilai_ipa_6_1;

    // Data Prestasi
    public $prestasi;
    public $uploadPrestasiSertifikat = [];

    // Data Daftar Ulang
    public $du_status;
    public $du_pembayaran_lunas;
    public $du_seragam_diukur;
    public $du_berkas_lengkap;
    public $du_catatan;
    public $du_tanggal_daftar_ulang;
    public $du_batas_daftar_ulang;

    // Data Siswa EMIS
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

    // Alamat Detail
    public $rt;
    public $rw;
    public $dusun;
    public $desa;
    public $kecamatan;
    public $kabupaten;
    public $provinsi;
    public $kode_pos;
    public $latitude;
    public $longitude;

    // Alamat Orang Tua/Wali
    public $alamat_ayah;
    public $alamat_ibu;
    public $alamat_wali;

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
    public $siswa_kode_pos;
    public $siswa_alamat;

    // Tempat Tinggal & Transportasi
    public $jenis_tinggal;
    public $alat_transportasi;
    public $jarak_tempat_tinggal;
    public $waktu_tempuh;
    public $jarak_ke_sekolah;
    public $transportasi;

    // Minat & Bakat
    public $hobi;
    public $cita_cita;
    public $yang_membiayai_sekolah;
    public $berkebutuhan_khusus;
    public $jenis_kebutuhan_khusus = [];

    // Data Ayah EMIS
    public $ayah_nik;
    public $ayah_tahun_lahir;
    public $ayah_pendidikan;
    public $ayah_penghasilan;
    public $ayah_no_hp;
    public $ayah_berkebutuhan_khusus;

    // Data Ibu EMIS
    public $ibu_nik;
    public $ibu_tahun_lahir;
    public $ibu_pendidikan;
    public $ibu_penghasilan;
    public $ibu_no_hp;
    public $ibu_berkebutuhan_khusus;

    // Data Wali
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

    // Beasiswa & Bantuan
    public $penerima_kps;
    public $nomor_kps;
    public $penerima_kip;
    public $nomor_kip;
    public $layak_pip;
    public $alasan_layak_pip;

    // File Upload Beasiswa
    public $newFileKartuKeluarga;
    public $newFileAktaKelahiran;
    public $file_kartu_keluarga;
    public $file_akta_kelahiran;

    // Pendidikan Lain - TK
    public $tk_nama;
    public $tk_npsn;
    public $tk_tahun_lulus;

    // Pendidikan Lain - SD
    public $sd_nama;
    public $sd_npsn;
    public $sd_tahun_lulus;
    public $sd_nomor_peserta_ujian;
    public $sd_nomor_seri_ijazah;
    public $sd_nomor_skhun;

    // Nilai SD
    public $sd_nilai_matematika;
    public $sd_nilai_ipa;
    public $sd_nilai_ips;
    public $sd_nilai_bahasa_indonesia;
    public $sd_nilai_bahasa_inggris;

    // File Upload Pendidikan
    public $newFileIjazahSd;
    public $newFileSkhunSd;
    public $file_ijazah_sd;
    public $file_skhun_sd;

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

    // Upload properties for Dokumen (Admin/Panitia can help upload)
    public $uploadDokumenRapor;
    public $uploadDokumenAkte;
    public $uploadDokumenKk;
    public $uploadDokumenNisn;
    public $uploadDokumenIjazah;
    public $uploadDokumenSertifikat;
    public $uploadDokumenKip;
    public $uploadDokumenPkh;
    public $uploadDokumenKks;

    public function mount($id)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        $this->pendaftarId = $id;
        $this->loadPendaftar();
        $this->loadVerifikasi();
        $this->loadDokumen();
        $this->loadDaftarUlang();
        $this->loadDataToProperties();

        // Load provinces list for cascade dropdowns - explicitly cast IDs to strings to prevent Livewire serialization issues
        $this->provincesList = Province::orderBy('name')->get()->map(function($item) {
            return ['id' => (string)$item->id, 'name' => $item->name];
        })->toArray();
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
                })->toArray();
            } elseif (is_array($this->$property)) {
                // Ensure all IDs in existing arrays are strings
                $this->$property = array_map(function($item) {
                    if (isset($item['id'])) {
                        $item['id'] = (string)$item['id'];
                    }
                    return $item;
                }, $this->$property);
            }
        }
    }

    public function loadPendaftar()
    {
        $this->pendaftar = PendaftarModel::with(['jalurPendaftaran', 'user', 'daftarUlang'])
            ->findOrFail($this->pendaftarId);
    }

    public function loadVerifikasi()
    {
        $this->verifikasi = VerifikasiBerkas::firstOrCreate(
            ['pendaftar_id' => $this->pendaftarId],
            [
                'status' => 'menunggu',
                'status_rapor' => 'belum_upload',
                'status_akte' => 'belum_upload',
                'status_kk' => 'belum_upload',
                'status_nisn' => 'belum_upload',
                'status_ijazah' => 'belum_upload',
                'status_sertifikat' => 'belum_upload',
                'status_kip' => 'belum_upload',
                'status_pkh' => 'belum_upload',
                'status_kks' => 'belum_upload',
            ]
        );

        // Load status ke properties
        $this->status_rapor = $this->verifikasi->status_rapor;
        $this->catatan_rapor = $this->verifikasi->catatan_rapor;
        $this->status_akte = $this->verifikasi->status_akte;
        $this->catatan_akte = $this->verifikasi->catatan_akte;
        $this->status_kk = $this->verifikasi->status_kk;
        $this->catatan_kk = $this->verifikasi->catatan_kk;
        $this->status_nisn = $this->verifikasi->status_nisn;
        $this->catatan_nisn = $this->verifikasi->catatan_nisn;
        $this->status_ijazah = $this->verifikasi->status_ijazah;
        $this->catatan_ijazah = $this->verifikasi->catatan_ijazah;
        $this->status_sertifikat = $this->verifikasi->status_sertifikat;
        $this->catatan_sertifikat = $this->verifikasi->catatan_sertifikat;
        $this->status_kip = $this->verifikasi->status_kip;
        $this->catatan_kip = $this->verifikasi->catatan_kip;
        $this->status_pkh = $this->verifikasi->status_pkh;
        $this->catatan_pkh = $this->verifikasi->catatan_pkh;
        $this->status_kks = $this->verifikasi->status_kks;
        $this->catatan_kks = $this->verifikasi->catatan_kks;
    }

    public function loadDokumen()
    {
        // Migrate legacy rapor from Pendaftar model to Dokumen model if needed
        $pendaftar = PendaftarModel::find($this->pendaftarId);
        if ($pendaftar && $pendaftar->file_rapor) {
            $existingRapor = Dokumen::where('pendaftar_id', $this->pendaftarId)
                ->where('jenis_dokumen', 'rapor')
                ->first();

            if (!$existingRapor && \Illuminate\Support\Facades\Storage::exists($pendaftar->file_rapor)) {
                Dokumen::create([
                    'pendaftar_id' => $this->pendaftarId,
                    'jenis_dokumen' => 'rapor',
                    'nama_file' => 'Rapor (Migrated)',
                    'path_file' => $pendaftar->file_rapor,
                    'mime_type' => 'application/pdf',
                    'ukuran_file' => \Illuminate\Support\Facades\Storage::size($pendaftar->file_rapor),
                    'tanggal_upload' => $pendaftar->updated_at,
                ]);
            }
        }

        $this->dokumen = Dokumen::where('pendaftar_id', $this->pendaftarId)->get();
    }

    public function loadDaftarUlang()
    {
        $this->daftarUlang = DaftarUlang::where('pendaftar_id', $this->pendaftarId)->first();

        if ($this->daftarUlang) {
            $this->du_status = $this->daftarUlang->status;
            $this->du_pembayaran_lunas = $this->daftarUlang->pembayaran_lunas;
            $this->du_seragam_diukur = $this->daftarUlang->seragam_diukur;
            $this->du_berkas_lengkap = $this->daftarUlang->berkas_lengkap;
            $this->du_catatan = $this->daftarUlang->catatan;
            $this->du_tanggal_daftar_ulang = $this->daftarUlang->tanggal_daftar_ulang ? $this->daftarUlang->tanggal_daftar_ulang->format('Y-m-d') : null;
            $this->du_batas_daftar_ulang = $this->daftarUlang->batas_daftar_ulang ? $this->daftarUlang->batas_daftar_ulang->format('Y-m-d') : null;
        }
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

        // Data Orang Tua
        $this->nama_ayah = $this->pendaftar->nama_ayah;
        $this->nama_ibu = $this->pendaftar->nama_ibu;
        $this->pekerjaan_ayah = $this->pendaftar->pekerjaan_ayah;
        $this->pekerjaan_ibu = $this->pendaftar->pekerjaan_ibu;
        $this->penghasilan_ortu = $this->pendaftar->penghasilan_ortu;
        $this->telepon_ortu = $this->pendaftar->telepon_ortu;

        // Data Alamat
        $this->alamat = $this->pendaftar->alamat;
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

        // Status Pendaftaran
        $this->status_pendaftaran = $this->pendaftar->status_pendaftaran;
        $this->catatan_verifikasi = $this->pendaftar->catatan_verifikasi;

        // EMIS - Data Siswa
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
        $this->pra_sekolah_tk = $this->pendaftar->pra_sekolah_tk;
        $this->pra_sekolah_paud = $this->pendaftar->pra_sekolah_paud;
        $this->imunisasi_hepatitis_b = $this->pendaftar->imunisasi_hepatitis_b;
        $this->imunisasi_bcg = $this->pendaftar->imunisasi_bcg;
        $this->imunisasi_dpt = $this->pendaftar->imunisasi_dpt;
        $this->imunisasi_polio = $this->pendaftar->imunisasi_polio;
        $this->imunisasi_campak = $this->pendaftar->imunisasi_campak;
        $this->imunisasi_covid = $this->pendaftar->imunisasi_covid;

        // EMIS - Alamat Detail
        $this->rt = $this->pendaftar->rt;
        $this->rw = $this->pendaftar->rw;
        $this->dusun = $this->pendaftar->dusun;
        $this->desa = $this->pendaftar->desa;
        $this->kecamatan = $this->pendaftar->kecamatan;
        $this->kabupaten = $this->pendaftar->kabupaten;
        $this->provinsi = $this->pendaftar->provinsi;
        $this->kode_pos = $this->pendaftar->kode_pos;
        $this->latitude = $this->pendaftar->latitude;
        $this->longitude = $this->pendaftar->longitude;

        // EMIS - Alamat Orang Tua/Wali
        $this->alamat_ayah = $this->pendaftar->alamat_ayah;
        // alamat_ibu loaded ke ibu_alamat (template menggunakan ibu_alamat)
        $this->ibu_alamat = $this->pendaftar->alamat_ibu;
        $this->alamat_wali = $this->pendaftar->alamat_wali;

        // Detail Address - Ayah
        $this->ayah_tinggal_luar_negeri = $this->pendaftar->ayah_tinggal_luar_negeri ?? false;
        $this->ayah_status_kepemilikan_rumah = $this->pendaftar->ayah_status_kepemilikan_rumah;
        $this->ayah_provinsi = $this->pendaftar->ayah_provinsi;
        $this->ayah_kabupaten = $this->pendaftar->ayah_kabupaten;
        $this->ayah_kecamatan = $this->pendaftar->ayah_kecamatan;
        $this->ayah_kelurahan = $this->pendaftar->ayah_kelurahan;
        $this->ayah_rt = $this->pendaftar->ayah_rt;
        $this->ayah_rw = $this->pendaftar->ayah_rw;
        $this->ayah_kode_pos = $this->pendaftar->ayah_kode_pos;

        // Detail Address - Ibu
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

        // Detail Address - Wali
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

        // Detail Address - Siswa
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

        // Alamat Siswa - Jarak & Transportasi (for tab Alamat)
        $this->jarak_ke_sekolah = $this->pendaftar->jarak_ke_sekolah;
        $this->transportasi = $this->pendaftar->transportasi;
        $this->waktu_tempuh = $this->pendaftar->waktu_tempuh;

        // EMIS - Tempat Tinggal & Transportasi (for EMIS data)
        $this->jenis_tinggal = $this->pendaftar->jenis_tinggal;
        $this->alat_transportasi = $this->pendaftar->alat_transportasi;
        $this->jarak_tempat_tinggal = $this->pendaftar->jarak_tempat_tinggal;

        // EMIS - Minat & Bakat
        $this->hobi = $this->pendaftar->hobi;
        $this->cita_cita = $this->pendaftar->cita_cita;
        $this->yang_membiayai_sekolah = $this->pendaftar->yang_membiayai_sekolah;
        $this->berkebutuhan_khusus = $this->pendaftar->berkebutuhan_khusus;
        $this->jenis_kebutuhan_khusus = $this->pendaftar->jenis_kebutuhan_khusus ?? [];

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

        // EMIS - Beasiswa & Bantuan
        $this->penerima_kps = $this->pendaftar->penerima_kps;
        $this->nomor_kps = $this->pendaftar->nomor_kps;
        $this->penerima_kip = $this->pendaftar->penerima_kip;
        $this->nomor_kip = $this->pendaftar->nomor_kip;
        $this->layak_pip = $this->pendaftar->layak_pip;
        $this->alasan_layak_pip = $this->pendaftar->alasan_layak_pip;
        $this->file_kartu_keluarga = $this->pendaftar->file_kartu_keluarga;
        $this->file_akta_kelahiran = $this->pendaftar->file_akta_kelahiran;

        // EMIS - Pendidikan TK
        $this->tk_nama = $this->pendaftar->tk_nama;
        $this->tk_npsn = $this->pendaftar->tk_npsn;
        $this->tk_tahun_lulus = $this->pendaftar->tk_tahun_lulus;

        // EMIS - Pendidikan SD
        $this->sd_nama = $this->pendaftar->sd_nama;
        $this->sd_npsn = $this->pendaftar->sd_npsn;
        $this->sd_tahun_lulus = $this->pendaftar->sd_tahun_lulus;
        $this->sd_nomor_peserta_ujian = $this->pendaftar->sd_nomor_peserta_ujian;
        $this->sd_nomor_seri_ijazah = $this->pendaftar->sd_nomor_seri_ijazah;
        $this->sd_nomor_skhun = $this->pendaftar->sd_nomor_skhun;

        // EMIS - Nilai SD
        $this->sd_nilai_matematika = $this->pendaftar->sd_nilai_matematika;
        $this->sd_nilai_ipa = $this->pendaftar->sd_nilai_ipa;
        $this->sd_nilai_ips = $this->pendaftar->sd_nilai_ips;
        $this->sd_nilai_bahasa_indonesia = $this->pendaftar->sd_nilai_bahasa_indonesia;
        $this->sd_nilai_bahasa_inggris = $this->pendaftar->sd_nilai_bahasa_inggris;

        // EMIS - File Upload Pendidikan
        $this->file_ijazah_sd = $this->pendaftar->file_ijazah_sd;
        $this->file_skhun_sd = $this->pendaftar->file_skhun_sd;

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
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            $this->loadDataToProperties();
        }
    }

    /**
     * Toggle edit mode for specific section (for template compatibility)
     * Admin uses single edit mode, so section parameter is ignored
     */
    public function toggleEdit($section)
    {
        $property = 'editMode' . ucfirst($section);
        if (property_exists($this, $property)) {
            $isEnabling = !$this->$property;
            $this->$property = $isEnabling;

            // Only reload data when ENABLING edit mode
            if ($isEnabling) {
                $this->loadDataToProperties();

                // Populate cascade dropdown lists for alamat section
                if ($section === 'alamat') {
                    $this->populateCascadeDropdowns();
                }
            }
        }
    }

    /**
     * Populate cascade dropdown lists based on existing data
     * This is needed when entering edit mode to show the correct options
     */
    private function populateCascadeDropdowns()
    {
        // Populate Ayah dropdowns
        if ($this->ayah_provinsi) {
            $this->ayahCitiesList = Regency::where('province_id', $this->ayah_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->ayah_kabupaten) {
            $this->ayahDistrictsList = District::where('regency_id', $this->ayah_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->ayah_kecamatan) {
            $this->ayahVillagesList = Village::where('district_id', $this->ayah_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        // Populate Ibu dropdowns
        if ($this->ibu_provinsi) {
            $this->ibuCitiesList = Regency::where('province_id', $this->ibu_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->ibu_kabupaten) {
            $this->ibuDistrictsList = District::where('regency_id', $this->ibu_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->ibu_kecamatan) {
            $this->ibuVillagesList = Village::where('district_id', $this->ibu_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        // Populate Wali dropdowns
        if ($this->wali_provinsi) {
            $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->wali_kabupaten) {
            $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->wali_kecamatan) {
            $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        // Populate Siswa dropdowns
        if ($this->siswa_provinsi) {
            $this->siswaCitiesList = Regency::where('province_id', $this->siswa_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->siswa_kabupaten) {
            $this->siswaDistrictsList = District::where('regency_id', $this->siswa_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }

        if ($this->siswa_kecamatan) {
            $this->siswaVillagesList = Village::where('district_id', $this->siswa_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        }
    }

    /**
     * Get panitia ID for current user
     * Returns panitia.id if user is panitia, null if admin
     */
    private function getPanitiaId()
    {
        $user = Auth::user();

        // If user is panitia, find panitia record by user_id
        if (in_array($user->role, ['panitia', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            $panitia = Panitia::where('user_id', $user->id)->first();
            if ($panitia) {
                return $panitia->id;
            }
        }

        // If admin or panitia record not found, return null
        return null;
    }

    public function updateStatusDokumen($jenis)
    {
        $statusField = 'status_' . $jenis;
        $catatanField = 'catatan_' . $jenis;

        // Validasi
        if (!isset($this->$statusField)) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Status dokumen tidak valid.'
            ]);
            return;
        }

        // Update hanya field yang diperlukan
        $this->verifikasi->update([
            $statusField => $this->$statusField,
            $catatanField => $this->$catatanField,
            'panitia_id' => $this->getPanitiaId(),
            'tanggal_verifikasi' => now(),
        ]);

        // Dispatch toast notification
        $dokumenLabels = [
            'rapor' => 'Scan Rapor',
            'akte' => 'Akta Kelahiran',
            'kk' => 'Kartu Keluarga',
            'nisn' => 'Scan/Bukti NISN',
            'ijazah' => 'Ijazah/SKL',
            'sertifikat' => 'Sertifikat/Piagam',
            'kip' => 'KIP',
            'pkh' => 'PKH',
            'kks' => 'KKS',
        ];

        $label = $dokumenLabels[$jenis] ?? ucfirst($jenis);

        $this->dispatch('statusUpdated', [
            'type' => 'success',
            'message' => "Status {$label} berhasil diperbarui menjadi: " . $this->$statusField
        ]);
    }

    /**
     * Upload dokumen untuk pendaftar (Admin/Panitia can help upload)
     */
    public function uploadDokumen($jenis)
    {
        $uploadField = 'uploadDokumen' . ucfirst($jenis);

        // Map jenis key to database jenis_dokumen value
        $jenisMapping = [
            'rapor' => 'rapor',
            'akte' => 'akta_kelahiran',
            'kk' => 'kk',
            'nisn' => 'nisn',
            'ijazah' => 'ijazah',
            'sertifikat' => 'sertifikat',
            'kip' => 'kip',
            'pkh' => 'pkh',
            'kks' => 'kks',
        ];

        $jenisDokumen = $jenisMapping[$jenis] ?? $jenis;

        // Validasi file
        $this->validate([
            $uploadField => 'required|file|mimes:pdf|max:2048',
        ], [
            $uploadField . '.required' => 'File dokumen wajib dipilih',
            $uploadField . '.file' => 'File tidak valid',
            $uploadField . '.mimes' => 'File harus berupa PDF',
            $uploadField . '.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            $file = $this->$uploadField;

            // Check if document already exists (use $jenisDokumen for database query) - CHECK FIRST!
            $existingDokumen = Dokumen::where('pendaftar_id', $this->pendaftarId)
                ->where('jenis_dokumen', $jenisDokumen)
                ->first();

            // Delete old file from private storage BEFORE uploading new file
            if ($existingDokumen && Storage::disk('private')->exists($existingDokumen->path_file)) {
                Storage::disk('private')->delete($existingDokumen->path_file);
            }

            // Generate file name using FileNamingService
            $filename = FileNamingService::generateFileName(
                $jenis,
                $this->pendaftar->nomor_pendaftaran,
                $file->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            // Store NEW file to private storage (dengan subdirektori pendaftar_id untuk konsistensi)
            $path = $file->storeAs('dokumen/' . $this->pendaftarId, $filename, 'private');

            // Get file info
            $mimeType = $file->getMimeType();
            $size = $file->getSize();

            if ($existingDokumen) {
                // Update existing document record
                $existingDokumen->update([
                    'nama_file' => $filename,
                    'path_file' => $path,
                    'mime_type' => $mimeType,
                    'ukuran_file' => $size,
                    'tanggal_upload' => now(),
                ]);
            } else {
                // Create new document (use $jenisDokumen for database insert)
                Dokumen::create([
                    'pendaftar_id' => $this->pendaftarId,
                    'jenis_dokumen' => $jenisDokumen,
                    'nama_file' => $filename,
                    'path_file' => $path,
                    'mime_type' => $mimeType,
                    'ukuran_file' => $size,
                    'tanggal_upload' => now(),
                ]);
            }

            // Update status to menunggu_verifikasi
            $statusField = 'status_' . $jenis;
            $this->$statusField = 'menunggu_verifikasi';
            $this->verifikasi->update([
                $statusField => 'menunggu_verifikasi',
            ]);

            // Reload dokumen and verifikasi with fresh data
            $this->loadDokumen();
            $this->loadVerifikasi();

            // Force refresh pendaftar data
            $this->pendaftar = $this->pendaftar->fresh();

            // Clear upload field
            $this->$uploadField = null;

            // Dispatch success notification
            $dokumenLabels = [
                'rapor' => 'Scan Rapor',
                'akte' => 'Akta Kelahiran',
                'kk' => 'Kartu Keluarga',
                'nisn' => 'Scan/Bukti NISN',
                'ijazah' => 'Ijazah/SKL',
                'sertifikat' => 'Sertifikat/Piagam',
                'kip' => 'KIP',
                'pkh' => 'PKH',
                'kks' => 'KKS',
            ];

            $label = $dokumenLabels[$jenis] ?? ucfirst($jenis);

            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "Dokumen {$label} berhasil di-upload!"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if all required documents are verified (status = disetujui)
     * - All WAJIB documents must be "disetujui"
     * - All uploaded non-wajib documents must also be "disetujui"
     */
    public function getIsAllDocumentsVerifiedProperty()
    {
        // Define wajib documents
        $wajibDokumen = ['rapor', 'akte', 'kk', 'nisn', 'ijazah'];

        // Check if any wajib documents are still "menunggu_verifikasi"
        foreach ($wajibDokumen as $dok) {
            $statusField = 'status_' . $dok;
            if ($this->$statusField === 'menunggu_verifikasi') {
                return false; // Ada dokumen wajib yang masih menunggu verifikasi
            }
        }

        // Define optional documents with their conditions
        $optionalDokumen = [
            'sertifikat' => null, // Always check if uploaded
            'kip' => 'nomor_kip', // Check if nomor_kip exists
            'pkh' => 'nomor_pkh', // Check if nomor_pkh exists
            'kks' => 'nomor_kks', // Check if nomor_kks exists
        ];

        foreach ($optionalDokumen as $dok => $conditionalField) {
            $statusField = 'status_' . $dok;

            // Check if document has been uploaded
            $hasUpload = $this->dokumen->contains(function($dokumen) use ($dok) {
                return $dokumen->jenis_dokumen === $dok;
            });

            // For conditional documents, also check if the condition field is filled
            $isConditionalFilled = false;
            if ($conditionalField && !empty($this->pendaftar->$conditionalField)) {
                $isConditionalFilled = true;
            }

            // If uploaded or conditional field filled, check if still "menunggu_verifikasi"
            if ($hasUpload || $isConditionalFilled) {
                if ($this->$statusField === 'menunggu_verifikasi') {
                    return false; // Ada dokumen optional yang masih menunggu verifikasi
                }
            }
        }

        // Semua dokumen sudah diverifikasi (disetujui/perbaiki_data/data_ditolak)
        // Tombol Ubah Status aktif
        return true;
    }

    public function updateStatusPendaftaran()
    {
        $this->pendaftar->update([
            'status_pendaftaran' => $this->status_pendaftaran,
            'catatan_verifikasi' => $this->catatan_verifikasi,
            'diperbarui_oleh' => Auth::id(),
        ]);

        // Send notification to pendaftar
        if ($this->pendaftar && $this->pendaftar->user) {
            $this->pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'formulir',
                    $this->status_pendaftaran,
                    $this->catatan_verifikasi
                )
            );
        }

        session()->flash('message', 'Status pendaftaran berhasil diperbarui.');
        $this->loadPendaftar();

        // Dispatch event untuk update tampilan
        $this->dispatch('statusUpdated', [
            'type' => 'success',
            'message' => 'Status pendaftaran berhasil diperbarui menjadi ' . $this->getStatusLabel($this->status_pendaftaran)
        ]);
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'menunggu' => 'Menunggu Verifikasi',
            'diverifikasi' => 'Diverifikasi',
            'lulus' => 'Lulus Seleksi',
            'tidak_lulus' => 'Tidak Lulus',
            'cadangan' => 'Cadangan',
        ];
        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    public function saveDataSiswa()
    {
        // Validasi foto wajib jika belum ada
        $fotoValidation = $this->pendaftar->foto ? 'nullable|image|max:2048' : 'required|image|max:2048';

        $this->validate([
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'nisn' => 'required|digits:10',
            'nik' => 'nullable|digits:16|required_if:kewarganegaraan,WNI',
            'nama_panggilan' => 'nullable|string|max:100',
            'tempat_lahir' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'negara_asal' => 'nullable|string|max:100|required_if:kewarganegaraan,WNA',
            'nomor_kitas' => 'nullable|string|max:50|required_if:kewarganegaraan,WNA',
            'nomor_kk' => 'required|digits:16',
            'nama_kepala_keluarga' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.,\'-]+$/'],
            'anak_ke' => 'required|integer|min:1|max:99',
            'jumlah_saudara' => 'required|integer|min:0|max:99',
            'jumlah_saudara_kandung' => 'nullable|integer|min:0',
            'nomor_telepon_rumah' => 'nullable|numeric|digits_between:10,15',
            'email_pribadi' => 'nullable|email',
            'tidak_punya_hp' => 'nullable|boolean',
            'no_hp' => 'nullable|numeric|digits_between:10,15',
            'hobi' => 'required|string',
            'cita_cita' => 'required|string',
            'yang_membiayai_sekolah' => 'required|string',
            'pra_sekolah_tk' => 'nullable|boolean',
            'pra_sekolah_paud' => 'nullable|boolean',
            'imunisasi_hepatitis_b' => 'nullable|boolean',
            'imunisasi_bcg' => 'nullable|boolean',
            'imunisasi_dpt' => 'nullable|boolean',
            'imunisasi_polio' => 'nullable|boolean',
            'imunisasi_campak' => 'nullable|boolean',
            'imunisasi_covid' => 'nullable|boolean',
            'berkebutuhan_khusus' => 'nullable|boolean',
            'jenis_kebutuhan_khusus' => 'nullable|array',
            'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/',
            'newFoto' => $fotoValidation,
        ], [
            'newFoto.required' => 'Foto pendaftar wajib diupload',
            'newFoto.image' => 'File harus berupa gambar',
            'newFoto.max' => 'Ukuran foto maksimal 2MB',
            'nomor_kip.max' => 'Nomor KIP maksimal 25 karakter',
            'nomor_kip.regex' => 'Nomor KIP hanya boleh berisi huruf dan angka',
        ]);

        $dataToUpdate = [
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn,
            'nik' => $this->nik,
            'nama_panggilan' => $this->nama_panggilan,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'kewarganegaraan' => $this->kewarganegaraan,
            'negara_asal' => $this->negara_asal,
            'nomor_kitas' => $this->nomor_kitas,
            'nomor_kk' => $this->nomor_kk,
            'nama_kepala_keluarga' => $this->nama_kepala_keluarga,
            'anak_ke' => $this->anak_ke,
            'jumlah_saudara' => $this->jumlah_saudara,
            'jumlah_saudara_kandung' => $this->jumlah_saudara_kandung,
            'nomor_telepon_rumah' => $this->nomor_telepon_rumah,
            'email_pribadi' => $this->email_pribadi,
            'tidak_punya_hp' => $this->tidak_punya_hp ?? false,
            'no_hp' => $this->no_hp,
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
            'berkebutuhan_khusus' => $this->berkebutuhan_khusus ?? false,
            'jenis_kebutuhan_khusus' => $this->jenis_kebutuhan_khusus,
            'nomor_kip' => $this->nomor_kip,
        ];

        // Handle foto upload
        if ($this->newFoto) {
            $imageService = new ImageService();

            // Hapus foto lama jika ada
            $imageService->deleteOldFile($this->pendaftar->foto);

            // Upload dan konversi ke WebP
            $fotoPath = $imageService->uploadProfilePhoto($this->newFoto, 'photos', 500, 80);
            $dataToUpdate['foto'] = $fotoPath;
            $this->foto = $fotoPath;
        }

        $this->pendaftar->update($dataToUpdate);
        $this->newFoto = null;
        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModeSiswa = false;

        session()->flash('message', 'Data siswa berhasil diperbarui.');
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

        $this->validate($validationRules);

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
        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModeOrangtua = false;

        session()->flash('message', 'Data orang tua berhasil diperbarui.');
    }

    public function saveDataAlamat()
    {
        // Build conditional validation rules
        $validationRules = [
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'dusun' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kabupaten' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'alamat_ayah' => 'nullable|string',
            'alamat_ibu' => 'nullable|string',
            'asal_kabkota' => 'nullable|string|max:100',
            'no_hp' => 'nullable|numeric|digits_between:10,15',
            'email' => 'required|email',

            // Ayah address fields - base validation
            'ayah_tinggal_luar_negeri' => 'nullable|boolean',
        ];

        // AYAH ADDRESS - Conditional validation based on ayah_status
        if ($this->ayah_status === 'masih_hidup' && !$this->ayah_tinggal_luar_negeri) {
            $validationRules['ayah_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['ayah_provinsi'] = 'nullable';
            $validationRules['ayah_kabupaten'] = 'nullable';
            $validationRules['ayah_kecamatan'] = 'nullable';
            $validationRules['ayah_kelurahan'] = 'nullable';
            $validationRules['ayah_rt'] = 'nullable|string|max:3';
            $validationRules['ayah_rw'] = 'nullable|string|max:3';
            $validationRules['ayah_kode_pos'] = 'nullable|string|max:6';
        } else {
            $validationRules['ayah_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['ayah_provinsi'] = 'nullable';
            $validationRules['ayah_kabupaten'] = 'nullable';
            $validationRules['ayah_kecamatan'] = 'nullable';
            $validationRules['ayah_kelurahan'] = 'nullable';
            $validationRules['ayah_rt'] = 'nullable|string|max:3';
            $validationRules['ayah_rw'] = 'nullable|string|max:3';
            $validationRules['ayah_kode_pos'] = 'nullable|string|max:6';
        }

        // IBU ADDRESS - Conditional validation based on ibu_status
        $validationRules['ibu_tinggal_luar_negeri'] = 'nullable|boolean';
        $validationRules['ibu_sama_dengan_ayah'] = 'nullable|boolean';

        if ($this->ibu_status === 'masih_hidup' && !$this->ibu_sama_dengan_ayah && !$this->ibu_tinggal_luar_negeri) {
            $validationRules['ibu_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['ibu_provinsi'] = 'nullable';
            $validationRules['ibu_kabupaten'] = 'nullable';
            $validationRules['ibu_kecamatan'] = 'nullable';
            $validationRules['ibu_kelurahan'] = 'nullable';
            $validationRules['ibu_rt'] = 'nullable|string|max:3';
            $validationRules['ibu_rw'] = 'nullable|string|max:3';
            $validationRules['ibu_kode_pos'] = 'nullable|string|max:6';
            $validationRules['ibu_alamat'] = 'nullable|string';
        } else {
            $validationRules['ibu_status_kepemilikan_rumah'] = 'nullable|string|max:100';
            $validationRules['ibu_provinsi'] = 'nullable';
            $validationRules['ibu_kabupaten'] = 'nullable';
            $validationRules['ibu_kecamatan'] = 'nullable';
            $validationRules['ibu_kelurahan'] = 'nullable';
            $validationRules['ibu_rt'] = 'nullable|string|max:3';
            $validationRules['ibu_rw'] = 'nullable|string|max:3';
            $validationRules['ibu_kode_pos'] = 'nullable|string|max:6';
            $validationRules['ibu_alamat'] = 'nullable|string';
        }

        // Continue with other validation rules
        $validationRules = array_merge($validationRules, [

            // Siswa address fields
            'status_tempat_tinggal' => 'nullable|string|max:100',
            'siswa_status_kepemilikan_rumah' => 'nullable|string|max:100',
            'siswa_provinsi' => 'nullable',
            'siswa_kabupaten' => 'nullable',
            'siswa_kecamatan' => 'nullable',
            'siswa_kelurahan' => 'nullable',
            'siswa_rt' => 'nullable|string|max:3',
            'siswa_rw' => 'nullable|string|max:3',
            'siswa_kode_pos' => 'nullable|string|max:6',
            'siswa_alamat' => 'nullable|string',

            // Jarak, transportasi, waktu tempuh
            'jarak_ke_sekolah' => 'nullable|string|max:50',
            'transportasi' => 'nullable|string|max:100',
            'waktu_tempuh' => 'nullable|string|max:50',
        ]);

        // WALI ADDRESS - Conditional validation based on wali_status_wali
        $validationRules['wali_status_wali'] = 'nullable|string|max:50';
        $validationRules['wali_tinggal_luar_negeri'] = 'nullable|boolean';
        $validationRules['wali_sama_dengan_ayah'] = 'nullable|boolean';

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

        $this->validate($validationRules);

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

        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModeAlamat = false;

        session()->flash('message', 'Data alamat berhasil diperbarui.');
    }

    public function saveDataPrestasi()
    {
        $this->pendaftar->update([
            'prestasi' => $this->prestasi,
        ]);

        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModeDataPrestasi = false;

        session()->flash('message', 'Data prestasi berhasil diperbarui.');
    }

    public function saveDataBeasiswa()
    {
        $this->validate([
            'penerima_kps' => 'nullable|boolean',
            'nomor_kps' => 'nullable|numeric|digits_between:1,17|required_if:penerima_kps,true',
            'penerima_kip' => 'nullable|boolean',
            'nomor_kip' => 'nullable|string|max:25|regex:/^[A-Za-z0-9]+$/|required_if:penerima_kip,true',
            'layak_pip' => 'nullable|boolean',
            'alasan_layak_pip' => 'nullable|string|required_if:layak_pip,true',
            'newFileKartuKeluarga' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'newFileAktaKelahiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $dataToUpdate = [
            'penerima_kps' => $this->penerima_kps ?? false,
            'nomor_kps' => $this->nomor_kps,
            'penerima_kip' => $this->penerima_kip ?? false,
            'nomor_kip' => $this->nomor_kip,
            'layak_pip' => $this->layak_pip ?? false,
            'alasan_layak_pip' => $this->alasan_layak_pip,
        ];

        // Handle file uploads
        if ($this->newFileKartuKeluarga) {
            if ($this->pendaftar->file_kartu_keluarga) {
                Storage::disk('public')->delete($this->pendaftar->file_kartu_keluarga);
            }
            $kkPath = $this->newFileKartuKeluarga->store('dokumen/kk', 'public');
            $dataToUpdate['file_kartu_keluarga'] = $kkPath;
            $this->file_kartu_keluarga = $kkPath;
        }

        if ($this->newFileAktaKelahiran) {
            if ($this->pendaftar->file_akta_kelahiran) {
                Storage::disk('public')->delete($this->pendaftar->file_akta_kelahiran);
            }
            $aktaPath = $this->newFileAktaKelahiran->store('dokumen/akta', 'public');
            $dataToUpdate['file_akta_kelahiran'] = $aktaPath;
            $this->file_akta_kelahiran = $aktaPath;
        }

        $this->pendaftar->update($dataToUpdate);
        $this->newFileKartuKeluarga = null;
        $this->newFileAktaKelahiran = null;
        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModeBeasiswa = false;

        session()->flash('message', 'Data beasiswa & bantuan berhasil diperbarui.');
    }

    public function saveDataPendidikan()
    {
        $this->validate([
            'tk_nama' => 'nullable|string|max:255',
            'tk_npsn' => 'nullable|string|max:8',
            'tk_tahun_lulus' => 'nullable|integer|min:1990|max:' . date('Y'),
            'sd_nama' => 'nullable|string|max:255',
            'sd_npsn' => 'nullable|string|max:8',
            'sd_tahun_lulus' => 'nullable|integer|min:1990|max:' . date('Y'),
            'sd_nomor_peserta_ujian' => 'nullable|string|max:50',
            'sd_nomor_seri_ijazah' => 'nullable|string|max:50',
            'sd_nomor_skhun' => 'nullable|string|max:50',
            'sd_nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_ipa' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_ips' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_bahasa_indonesia' => 'nullable|numeric|min:0|max:100',
            'sd_nilai_bahasa_inggris' => 'nullable|numeric|min:0|max:100',
            'newFileIjazahSd' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'newFileSkhunSd' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
        ];

        // Handle file uploads
        if ($this->newFileIjazahSd) {
            if ($this->pendaftar->file_ijazah_sd) {
                Storage::disk('public')->delete($this->pendaftar->file_ijazah_sd);
            }
            $ijazahPath = $this->newFileIjazahSd->store('dokumen/ijazah', 'public');
            $dataToUpdate['file_ijazah_sd'] = $ijazahPath;
            $this->file_ijazah_sd = $ijazahPath;
        }

        if ($this->newFileSkhunSd) {
            if ($this->pendaftar->file_skhun_sd) {
                Storage::disk('public')->delete($this->pendaftar->file_skhun_sd);
            }
            $skhunPath = $this->newFileSkhunSd->store('dokumen/skhun', 'public');
            $dataToUpdate['file_skhun_sd'] = $skhunPath;
            $this->file_skhun_sd = $skhunPath;
        }

        $this->pendaftar->update($dataToUpdate);
        $this->newFileIjazahSd = null;
        $this->newFileSkhunSd = null;
        $this->loadPendaftar();

        // Exit edit mode after successful save
        $this->editModePendidikan = false;

        session()->flash('message', 'Data pendidikan lain berhasil diperbarui.');
    }

    public function saveDataDaftarUlang()
    {
        if ($this->daftarUlang) {
            $this->daftarUlang->update([
                'status' => $this->du_status,
                'pembayaran_lunas' => $this->du_pembayaran_lunas ?? false,
                'seragam_diukur' => $this->du_seragam_diukur ?? false,
                'berkas_lengkap' => $this->du_berkas_lengkap ?? false,
                'catatan' => $this->du_catatan,
                'tanggal_daftar_ulang' => $this->du_tanggal_daftar_ulang,
                'batas_daftar_ulang' => $this->du_batas_daftar_ulang,
                'panitia_id' => $this->getPanitiaId(),
            ]);
        } else {
            $this->daftarUlang = DaftarUlang::create([
                'pendaftar_id' => $this->pendaftarId,
                'status' => $this->du_status ?? 'belum_diisi',
                'pembayaran_lunas' => $this->du_pembayaran_lunas ?? false,
                'seragam_diukur' => $this->du_seragam_diukur ?? false,
                'berkas_lengkap' => $this->du_berkas_lengkap ?? false,
                'catatan' => $this->du_catatan,
                'tanggal_daftar_ulang' => $this->du_tanggal_daftar_ulang,
                'batas_daftar_ulang' => $this->du_batas_daftar_ulang,
                'panitia_id' => $this->getPanitiaId(),
            ]);
        }

        $this->loadDaftarUlang();
        $this->loadPendaftar();
        session()->flash('message', 'Data daftar ulang berhasil diperbarui.');
    }

    /**
     * Calculate average nilai from SD grades
     */
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

    public function getProvincesProperty()
    {
        return Province::orderBy('name')->get();
    }

    public function getAyahCitiesProperty()
    {
        if ($this->ayah_provinsi) {
            return City::where('province_code', $this->ayah_provinsi)->orderBy('name')->get();
        }
        return collect();
    }

    public function getAyahDistrictsProperty()
    {
        if ($this->ayah_kabupaten) {
            return District::where('city_code', $this->ayah_kabupaten)->orderBy('name')->get();
        }
        return collect();
    }

    public function getAyahVillagesProperty()
    {
        if ($this->ayah_kecamatan) {
            return Village::where('district_code', $this->ayah_kecamatan)->orderBy('name')->get();
        }
        return collect();
    }

    public function updatedAyahProvinsi()
    {
        $this->ayah_kabupaten = null;
        $this->ayah_kecamatan = null;
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Populate cities list for the selected province
        if ($this->ayah_provinsi) {
            $this->ayahCitiesList = Regency::where('province_id', $this->ayah_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->ayahCitiesList = [];
        }
        $this->ayahDistrictsList = [];
        $this->ayahVillagesList = [];
    }

    public function updatedAyahKabupaten()
    {
        $this->ayah_kecamatan = null;
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Populate districts list for the selected city
        if ($this->ayah_kabupaten) {
            $this->ayahDistrictsList = District::where('regency_id', $this->ayah_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->ayahDistrictsList = [];
        }
        $this->ayahVillagesList = [];
    }

    public function updatedAyahKecamatan()
    {
        $this->ayah_kelurahan = null;
        $this->ayah_kode_pos = null;

        // Populate villages list for the selected district
        if ($this->ayah_kecamatan) {
            $this->ayahVillagesList = Village::where('district_id', $this->ayah_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
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
                try {
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
                } catch (\Exception $e) {
                    // If postal_codes table doesn't exist or query fails, keep field empty
                    \Log::warning('Failed to auto-fill ayah_kode_pos: ' . $e->getMessage());
                }
            }
        }
    }

    // ========================================
    // CASCADE DROPDOWN METHODS - IBU
    // ========================================

    public function getIbuCitiesProperty()
    {
        if ($this->ibu_provinsi) {
            return City::where('province_code', $this->ibu_provinsi)->orderBy('name')->get();
        }
        return collect();
    }

    public function getIbuDistrictsProperty()
    {
        if ($this->ibu_kabupaten) {
            return District::where('city_code', $this->ibu_kabupaten)->orderBy('name')->get();
        }
        return collect();
    }

    public function getIbuVillagesProperty()
    {
        if ($this->ibu_kecamatan) {
            return Village::where('district_code', $this->ibu_kecamatan)->orderBy('name')->get();
        }
        return collect();
    }

    public function updatedIbuProvinsi()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            $this->ibu_kabupaten = null;
            $this->ibu_kecamatan = null;
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;
        }

        // Populate cities list for the selected province
        if ($this->ibu_provinsi) {
            $this->ibuCitiesList = Regency::where('province_id', $this->ibu_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->ibuCitiesList = [];
        }
        if (!$this->skipCascadeReset) {
            $this->ibuDistrictsList = [];
            $this->ibuVillagesList = [];
        }
    }

    public function updatedIbuKabupaten()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            $this->ibu_kecamatan = null;
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;
        }

        // Populate districts list for the selected city
        if ($this->ibu_kabupaten) {
            $this->ibuDistrictsList = District::where('regency_id', $this->ibu_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->ibuDistrictsList = [];
        }
        if (!$this->skipCascadeReset) {
            $this->ibuVillagesList = [];
        }
    }

    public function updatedIbuKecamatan()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->ibu_sama_dengan_ayah) {
            $this->ibu_kelurahan = null;
            $this->ibu_kode_pos = null;
        }

        // Populate villages list for the selected district
        if ($this->ibu_kecamatan) {
            $this->ibuVillagesList = Village::where('district_id', $this->ibu_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->ibuVillagesList = [];
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
                try {
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
                } catch (\Exception $e) {
                    // If postal_codes table doesn't exist or query fails, keep field empty
                    \Log::warning('Failed to auto-fill ibu_kode_pos: ' . $e->getMessage());
                }
            }
        }
    }

    public function copyAyahToIbu()
    {
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

    // ========================================
    // CASCADE DROPDOWN METHODS - WALI
    // ========================================

    public function getWaliCitiesProperty()
    {
        if ($this->wali_provinsi) {
            return City::where('province_code', $this->wali_provinsi)->orderBy('name')->get();
        }
        return collect();
    }

    public function getWaliDistrictsProperty()
    {
        if ($this->wali_kabupaten) {
            return District::where('city_code', $this->wali_kabupaten)->orderBy('name')->get();
        }
        return collect();
    }

    public function getWaliVillagesProperty()
    {
        if ($this->wali_kecamatan) {
            return Village::where('district_code', $this->wali_kecamatan)->orderBy('name')->get();
        }
        return collect();
    }

    public function updatedWaliProvinsi()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            $this->wali_kabupaten = null;
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;
        }

        // Populate cities list for the selected province
        if ($this->wali_provinsi) {
            $this->waliCitiesList = Regency::where('province_id', $this->wali_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->waliCitiesList = [];
        }
        if (!$this->skipCascadeReset) {
            $this->waliDistrictsList = [];
            $this->waliVillagesList = [];
        }
    }

    public function updatedWaliKabupaten()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            $this->wali_kecamatan = null;
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;
        }

        // Populate districts list for the selected city
        if ($this->wali_kabupaten) {
            $this->waliDistrictsList = District::where('regency_id', $this->wali_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->waliDistrictsList = [];
        }
        if (!$this->skipCascadeReset) {
            $this->waliVillagesList = [];
        }
    }

    public function updatedWaliKecamatan()
    {
        // Jangan reset jika sedang copy dari alamat ayah ATAU flag skipCascadeReset aktif
        if (!$this->skipCascadeReset && !$this->wali_sama_dengan_ayah) {
            $this->wali_kelurahan = null;
            $this->wali_kode_pos = null;
        }

        // Populate villages list for the selected district
        if ($this->wali_kecamatan) {
            $this->waliVillagesList = Village::where('district_id', $this->wali_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->waliVillagesList = [];
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
                try {
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
                } catch (\Exception $e) {
                    // If postal_codes table doesn't exist or query fails, keep field empty
                    \Log::warning('Failed to auto-fill wali_kode_pos: ' . $e->getMessage());
                }
            }
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
    // CASCADE DROPDOWN METHODS - SISWA
    // ========================================

    public function getSiswaCitiesProperty()
    {
        if ($this->siswa_provinsi) {
            return City::where('province_code', $this->siswa_provinsi)->orderBy('name')->get();
        }
        return collect();
    }

    public function getSiswaDistrictsProperty()
    {
        if ($this->siswa_kabupaten) {
            return District::where('city_code', $this->siswa_kabupaten)->orderBy('name')->get();
        }
        return collect();
    }

    public function getSiswaVillagesProperty()
    {
        if ($this->siswa_kecamatan) {
            return Village::where('district_code', $this->siswa_kecamatan)->orderBy('name')->get();
        }
        return collect();
    }

    public function updatedSiswaProvinsi()
    {
        $this->siswa_kabupaten = null;
        $this->siswa_kecamatan = null;
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Populate cities list for the selected province
        if ($this->siswa_provinsi) {
            $this->siswaCitiesList = Regency::where('province_id', $this->siswa_provinsi)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->siswaCitiesList = [];
        }
        $this->siswaDistrictsList = [];
        $this->siswaVillagesList = [];
    }

    public function updatedSiswaKabupaten()
    {
        $this->siswa_kecamatan = null;
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Populate districts list for the selected city
        if ($this->siswa_kabupaten) {
            $this->siswaDistrictsList = District::where('regency_id', $this->siswa_kabupaten)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
        } else {
            $this->siswaDistrictsList = [];
        }
        $this->siswaVillagesList = [];
    }

    public function updatedSiswaKecamatan()
    {
        $this->siswa_kelurahan = null;
        $this->siswa_kode_pos = null;

        // Populate villages list for the selected district
        if ($this->siswa_kecamatan) {
            $this->siswaVillagesList = Village::where('district_id', $this->siswa_kecamatan)
                ->orderBy('name')
                ->get()
                ->map(function($item) {
                    return ['id' => (string)$item->id, 'name' => $item->name];
                })->toArray();
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
                try {
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
                } catch (\Exception $e) {
                    // If postal_codes table doesn't exist or query fails, keep field empty
                    \Log::warning('Failed to auto-fill siswa_kode_pos: ' . $e->getMessage());
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
        // Jika ada id, hapus dari database dan file
        if (isset($this->prestasiList[$index]['id']) && $this->prestasiList[$index]['id']) {
            $prestasi = Prestasi::find($this->prestasiList[$index]['id']);
            if ($prestasi) {
                // Delete file sertifikat if exists
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
        $rules = [
            'prestasiList.*.tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'prestasiList.*.nama_lomba' => 'required|string|max:255',
            'prestasiList.*.bidang_lomba' => 'required|string|max:100',
            'prestasiList.*.nama_penyelenggara' => 'required|string|max:255',
            'prestasiList.*.tingkat_lomba' => 'required|string|max:50',
            'prestasiList.*.peringkat' => 'required|string|max:100',
            'prestasiList.*.kategori_lomba' => 'required|string|max:50',
        ];

        // Validate file upload for each prestasi
        foreach ($this->prestasiList as $index => $prestasiData) {
            // Jika prestasi baru (tidak ada id) dan tidak ada file existing, file upload wajib
            if (!$prestasiData['id'] && !isset($prestasiData['file_sertifikat'])) {
                $rules["uploadPrestasiSertifikat.{$index}"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            } elseif (isset($this->uploadPrestasiSertifikat[$index])) {
                // Jika ada file baru diupload (update), validasi file
                $rules["uploadPrestasiSertifikat.{$index}"] = 'file|mimes:pdf,jpg,jpeg,png|max:2048';
            }
        }

        $this->validate($rules);

        // Delete existing prestasi yang tidak ada di list (plus files)
        $existingIds = collect($this->prestasiList)->pluck('id')->filter()->toArray();
        $prestasisToDelete = $this->pendaftar->prestasis()->whereNotIn('id', $existingIds)->get();
        foreach ($prestasisToDelete as $prestasi) {
            if ($prestasi->file_sertifikat && Storage::disk('public')->exists($prestasi->file_sertifikat)) {
                Storage::disk('public')->delete($prestasi->file_sertifikat);
            }
            $prestasi->delete();
        }

        // Save atau update each prestasi
        foreach ($this->prestasiList as $index => $prestasiData) {
            // Handle file upload
            $filePath = $prestasiData['file_sertifikat'] ?? null;

            if (isset($this->uploadPrestasiSertifikat[$index]) && $this->uploadPrestasiSertifikat[$index]) {
                $file = $this->uploadPrestasiSertifikat[$index];

                // Delete old file BEFORE uploading new file (if updating existing)
                if ($prestasiData['id']) {
                    $oldPrestasi = Prestasi::find($prestasiData['id']);
                    if ($oldPrestasi && $oldPrestasi->file_sertifikat && Storage::disk('private')->exists($oldPrestasi->file_sertifikat)) {
                        Storage::disk('private')->delete($oldPrestasi->file_sertifikat);
                    }
                }

                // Generate filename using FileNamingService with index to prevent collision
                $filename = FileNamingService::generateFileName(
                    'sertifikat_prestasi_' . ($index + 1), // Add index to make unique
                    $this->pendaftar->nomor_pendaftaran,
                    $file->getClientOriginalExtension(),
                    $this->pendaftar->nama_lengkap
                );

                // Upload NEW file to private storage dengan subdirektori pendaftar_id
                $filePath = $file->storeAs('sertifikat_prestasi/' . $this->pendaftar->id, $filename, 'private');
            }

            if ($prestasiData['id']) {
                // Update existing
                Prestasi::where('id', $prestasiData['id'])->update([
                    'tahun' => $prestasiData['tahun'],
                    'nama_lomba' => $prestasiData['nama_lomba'],
                    'bidang_lomba' => $prestasiData['bidang_lomba'],
                    'nama_penyelenggara' => $prestasiData['nama_penyelenggara'],
                    'tingkat_lomba' => $prestasiData['tingkat_lomba'],
                    'peringkat' => $prestasiData['peringkat'],
                    'kategori_lomba' => $prestasiData['kategori_lomba'],
                    'file_sertifikat' => $filePath,
                ]);
            } else {
                // Create new
                Prestasi::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'tahun' => $prestasiData['tahun'],
                    'nama_lomba' => $prestasiData['nama_lomba'],
                    'bidang_lomba' => $prestasiData['bidang_lomba'],
                    'nama_penyelenggara' => $prestasiData['nama_penyelenggara'],
                    'tingkat_lomba' => $prestasiData['tingkat_lomba'],
                    'peringkat' => $prestasiData['peringkat'],
                    'kategori_lomba' => $prestasiData['kategori_lomba'],
                    'file_sertifikat' => $filePath,
                ]);
            }
        }

        // Reset upload arrays
        $this->uploadPrestasiSertifikat = [];

        $this->loadPrestasi(); // Reload data
        $this->loadPendaftar(); // Reload pendaftar untuk update relasi

        // Exit edit mode after successful save
        $this->editModeDataPrestasi = false;

        session()->flash('message', 'Data prestasi berhasil diperbarui.');
    }

    // ========================================
    // BEASISWA TERSTRUKTUR METHODS
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

        $this->loadBeasiswa(); // Reload data
        $this->loadPendaftar(); // Reload pendaftar untuk update relasi

        // Exit edit mode after successful save
        $this->editModeDataBeasiswa = false;

        session()->flash('message', 'Data beasiswa berhasil diperbarui.');
    }

    // ========================================
    // PENDIDIKAN LAIN TERSTRUKTUR METHODS
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

        $this->loadPendidikanLain(); // Reload data
        $this->loadPendaftar(); // Reload pendaftar untuk update relasi

        // Exit edit mode after successful save
        $this->editModeDataPendidikanLain = false;

        session()->flash('message', 'Data pendidikan lain berhasil diperbarui.');
    }

    // ========================================
    // KEAHLIAN METHODS
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
        // If it has an ID, delete from database
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

        // Remove from array
        unset($this->keahlianList[$index]);
        $this->keahlianList = array_values($this->keahlianList); // Re-index array

        // If empty, add one empty entry
        if (empty($this->keahlianList)) {
            $this->addKeahlian();
        }
    }

    public function saveDataKeahlian()
    {
        // Delete existing keahlian yang tidak ada di list (plus files)
        $existingIds = collect($this->keahlianList)->pluck('id')->filter()->toArray();
        $keahliansToDelete = $this->pendaftar->keahlians()->whereNotIn('id', $existingIds)->get();
        foreach ($keahliansToDelete as $keahlian) {
            if ($keahlian->file_bukti_sertifikat && Storage::disk('public')->exists($keahlian->file_bukti_sertifikat)) {
                Storage::disk('public')->delete($keahlian->file_bukti_sertifikat);
            }
            $keahlian->delete();
        }

        // Save atau update each keahlian
        foreach ($this->keahlianList as $index => $keahlianData) {
            // Handle file upload
            $filePath = $keahlianData['file_bukti_sertifikat'] ?? null;
            if (isset($this->uploadKeahlianSertifikat[$index]) && $this->uploadKeahlianSertifikat[$index]) {
                $file = $this->uploadKeahlianSertifikat[$index];

                // Delete old file BEFORE uploading new file (if updating existing)
                if (isset($keahlianData['id']) && $keahlianData['id']) {
                    $oldKeahlian = Keahlian::find($keahlianData['id']);
                    if ($oldKeahlian && $oldKeahlian->file_bukti_sertifikat && Storage::disk('private')->exists($oldKeahlian->file_bukti_sertifikat)) {
                        Storage::disk('private')->delete($oldKeahlian->file_bukti_sertifikat);
                    }
                }

                // Generate filename using FileNamingService with index to prevent collision
                $filename = FileNamingService::generateFileName(
                    'sertifikat_keahlian_' . ($index + 1), // Add index to make unique
                    $this->pendaftar->nomor_pendaftaran,
                    $file->getClientOriginalExtension(),
                    $this->pendaftar->nama_lengkap
                );

                // Upload NEW file to private storage dengan subdirektori pendaftar_id
                $filePath = $file->storeAs('sertifikat_keahlian/' . $this->pendaftar->id, $filename, 'private');
            }

            if ($keahlianData['id']) {
                // Update existing
                Keahlian::where('id', $keahlianData['id'])->update([
                    'bidang_keahlian' => $keahlianData['bidang_keahlian'],
                    'nama_keahlian' => $keahlianData['nama_keahlian'],
                    'sertifikasi' => $keahlianData['sertifikasi'],
                    'lembaga_penyelenggara' => $keahlianData['lembaga_penyelenggara'],
                    'hasil_tingkat_skor' => $keahlianData['hasil_tingkat_skor'],
                    'file_bukti_sertifikat' => $filePath,
                ]);
            } else {
                // Create new
                Keahlian::create([
                    'pendaftar_id' => $this->pendaftar->id,
                    'bidang_keahlian' => $keahlianData['bidang_keahlian'],
                    'nama_keahlian' => $keahlianData['nama_keahlian'],
                    'sertifikasi' => $keahlianData['sertifikasi'],
                    'lembaga_penyelenggara' => $keahlianData['lembaga_penyelenggara'],
                    'hasil_tingkat_skor' => $keahlianData['hasil_tingkat_skor'],
                    'file_bukti_sertifikat' => $filePath,
                ]);
            }
        }

        // Reset upload arrays
        $this->uploadKeahlianSertifikat = [];

        $this->loadKeahlian(); // Reload data
        $this->loadPendaftar(); // Reload pendaftar untuk update relasi

        // Exit edit mode after successful save
        $this->editModeDataKeahlian = false;

        session()->flash('message', 'Data keahlian berhasil diperbarui.');
    }

    // ========================================
    // TAHFIDZ METHODS
    // ========================================

    public function loadTahfidz()
    {
        $tahfidz = Tahfidz::where('pendaftar_id', $this->pendaftar->id)->first();

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
        // Handle syahadah upload
        if ($this->uploadTahfidzSyahadah) {
            // Delete old file BEFORE uploading new file
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_syahadah && Storage::disk('private')->exists($oldTahfidz->file_bukti_syahadah)) {
                    Storage::disk('private')->delete($oldTahfidz->file_bukti_syahadah);
                }
            }

            // Generate filename using FileNamingService
            $filename = FileNamingService::generateFileName(
                'syahadah',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzSyahadah->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            // Upload NEW file to private storage dengan subdirektori pendaftar_id
            $syahadahPath = $this->uploadTahfidzSyahadah->storeAs('tahfidz/' . $this->pendaftar->id, $filename, 'private');
            $this->tahfidz['file_bukti_syahadah'] = $syahadahPath;
        }

        // Handle tahsin upload
        if ($this->uploadTahfidzTahsin) {
            // Delete old file BEFORE uploading new file
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_tahsin && Storage::disk('private')->exists($oldTahfidz->file_bukti_tahsin)) {
                    Storage::disk('private')->delete($oldTahfidz->file_bukti_tahsin);
                }
            }

            // Generate filename using FileNamingService
            $filename = FileNamingService::generateFileName(
                'tahsin',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzTahsin->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            // Upload NEW file to private storage dengan subdirektori pendaftar_id
            $tahsinPath = $this->uploadTahfidzTahsin->storeAs('tahfidz/' . $this->pendaftar->id, $filename, 'private');
            $this->tahfidz['file_bukti_tahsin'] = $tahsinPath;
        }

        if ($this->tahfidz['id']) {
            // Update existing
            $tahfidz = Tahfidz::find($this->tahfidz['id']);
            if ($tahfidz) {
                $tahfidz->update([
                    'juz_alquran_dihafal' => $this->tahfidz['juz_alquran_dihafal'],
                    'file_bukti_syahadah' => $this->tahfidz['file_bukti_syahadah'],
                    'file_bukti_tahsin' => $this->tahfidz['file_bukti_tahsin'],
                ]);
            }
        } else {
            // Create new
            $newTahfidz = Tahfidz::create([
                'pendaftar_id' => $this->pendaftar->id,
                'juz_alquran_dihafal' => $this->tahfidz['juz_alquran_dihafal'],
                'file_bukti_syahadah' => $this->tahfidz['file_bukti_syahadah'],
                'file_bukti_tahsin' => $this->tahfidz['file_bukti_tahsin'],
            ]);
            $this->tahfidz['id'] = $newTahfidz->id;
        }

        // Reset upload properties
        $this->uploadTahfidzSyahadah = null;
        $this->uploadTahfidzTahsin = null;

        $this->loadTahfidz(); // Reload data
        $this->loadPendaftar(); // Reload pendaftar untuk update relasi

        // Exit edit mode after successful save
        $this->editModeDataTahfidz = false;

        session()->flash('message', 'Data tahfidz berhasil diperbarui.');
    }

    // Method untuk Edit Nilai Rapor
    public function openEditNilaiRapor()
    {
        // Load nilai rapor saat ini ke form edit
        $this->edit_nilai_b_indo_5_1 = $this->pendaftar->nilai_b_indo_5_1;
        $this->edit_nilai_matematika_5_1 = $this->pendaftar->nilai_matematika_5_1;
        $this->edit_nilai_ipa_5_1 = $this->pendaftar->nilai_ipa_5_1;
        $this->edit_nilai_b_indo_5_2 = $this->pendaftar->nilai_b_indo_5_2;
        $this->edit_nilai_matematika_5_2 = $this->pendaftar->nilai_matematika_5_2;
        $this->edit_nilai_ipa_5_2 = $this->pendaftar->nilai_ipa_5_2;
        $this->edit_nilai_b_indo_6_1 = $this->pendaftar->nilai_b_indo_6_1;
        $this->edit_nilai_matematika_6_1 = $this->pendaftar->nilai_matematika_6_1;
        $this->edit_nilai_ipa_6_1 = $this->pendaftar->nilai_ipa_6_1;

        $this->showEditNilaiRapor = true;
    }

    public function closeEditNilaiRapor()
    {
        $this->showEditNilaiRapor = false;
        $this->resetErrorBag();
    }

    public function saveNilaiRapor()
    {
        // Validasi
        $this->validate([
            'edit_nilai_b_indo_5_1' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_matematika_5_1' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_ipa_5_1' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_b_indo_5_2' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_matematika_5_2' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_ipa_5_2' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_b_indo_6_1' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_matematika_6_1' => 'nullable|numeric|min:0|max:100',
            'edit_nilai_ipa_6_1' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            // Update nilai rapor di database
            $this->pendaftar->update([
                'nilai_b_indo_5_1' => $this->edit_nilai_b_indo_5_1,
                'nilai_matematika_5_1' => $this->edit_nilai_matematika_5_1,
                'nilai_ipa_5_1' => $this->edit_nilai_ipa_5_1,
                'nilai_b_indo_5_2' => $this->edit_nilai_b_indo_5_2,
                'nilai_matematika_5_2' => $this->edit_nilai_matematika_5_2,
                'nilai_ipa_5_2' => $this->edit_nilai_ipa_5_2,
                'nilai_b_indo_6_1' => $this->edit_nilai_b_indo_6_1,
                'nilai_matematika_6_1' => $this->edit_nilai_matematika_6_1,
                'nilai_ipa_6_1' => $this->edit_nilai_ipa_6_1,
            ]);

            // Hitung ulang rata-rata
            $this->calculateAverageNilai();

            // Reload data pendaftar
            $this->loadPendaftar();

            // Tutup modal
            $this->closeEditNilaiRapor();

            // Flash message sukses
            session()->flash('message', 'Nilai rapor berhasil diperbarui.');
            session()->flash('message_type', 'success');

        } catch (\Exception $e) {
            session()->flash('message', 'Gagal memperbarui nilai rapor: ' . $e->getMessage());
            session()->flash('message_type', 'error');
        }
    }

    // ===================================
    // INFO AKUN METHODS
    // ===================================

    public function toggleEditInfoAkun()
    {
        $this->editModeInfoAkun = !$this->editModeInfoAkun;

        // Load current data when entering edit mode
        if ($this->editModeInfoAkun) {
            $this->email = $this->pendaftar->user->email ?? '';
            $this->telepon_ortu = $this->pendaftar->telepon_ortu;
            $this->jenis_sekolah_asal = $this->pendaftar->jenis_sekolah_asal;
            $this->nsm_sekolah_asal = $this->pendaftar->nsm_sekolah_asal;
            $this->npsn_sekolah_asal = $this->pendaftar->npsn_sekolah_asal;
            $this->asal_sekolah = $this->pendaftar->asal_sekolah;
            $this->password_baru = '';
        }
    }

    public function cancelEditInfoAkun()
    {
        $this->editModeInfoAkun = false;
        $this->password_baru = '';
        $this->resetErrorBag();
    }

    public function saveInfoAkun()
    {
        // Build validation rules
        $rules = [
            'email' => 'required|email|unique:users,email,' . $this->pendaftar->user_id,
            'telepon_ortu' => 'required|string|regex:/^62[0-9]{9,13}$/|min:10|max:15',
            'jenis_sekolah_asal' => 'required|in:madrasah,umum',
            'asal_sekolah' => 'required|string|max:255',
        ];

        // Conditional validation untuk NSM/NPSN
        if ($this->jenis_sekolah_asal == 'madrasah') {
            $rules['nsm_sekolah_asal'] = 'required|digits:12';
        } elseif ($this->jenis_sekolah_asal == 'umum') {
            $rules['npsn_sekolah_asal'] = 'required|digits:8';
        }

        // Validation untuk password jika diisi
        if (!empty($this->password_baru)) {
            $rules['password_baru'] = 'min:8';
        }

        $this->validate($rules);

        try {
            // Update data pendaftar
            $this->pendaftar->update([
                'telepon_ortu' => $this->telepon_ortu,
                'jenis_sekolah_asal' => $this->jenis_sekolah_asal,
                'nsm_sekolah_asal' => $this->jenis_sekolah_asal == 'madrasah' ? $this->nsm_sekolah_asal : null,
                'npsn_sekolah_asal' => $this->jenis_sekolah_asal == 'umum' ? $this->npsn_sekolah_asal : null,
                'asal_sekolah' => $this->asal_sekolah,
            ]);

            // Update user email
            if ($this->pendaftar->user) {
                $this->pendaftar->user->update([
                    'email' => $this->email,
                ]);

                // Update password jika diisi
                if (!empty($this->password_baru)) {
                    $this->pendaftar->user->update([
                        'password' => \Hash::make($this->password_baru),
                    ]);
                }
            }

            // Reload data
            $this->loadPendaftar();
            $this->editModeInfoAkun = false;
            $this->password_baru = '';

            // Flash message
            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => 'Info akun berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal memperbarui info akun: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.detail-pendaftar')
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Detail Pendaftar',
                'pageDescription' => 'Lihat detail data pendaftar dan verifikasi berkas'
            ]);
    }
}
