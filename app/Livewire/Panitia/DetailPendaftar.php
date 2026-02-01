<?php

namespace App\Livewire\Panitia;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pendaftar as PendaftarModel;
use App\Models\VerifikasiBerkas;
use App\Models\Dokumen;
use App\Models\DaftarUlang;
use App\Models\Panitia;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\Keahlian;
use App\Models\Tahfidz;
use App\Services\ImageService;
use App\Services\FileNamingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DetailPendaftar extends Component
{
    use WithFileUploads;

    public $pendaftarId;
    public $pendaftar;
    public $verifikasi;
    public $dokumen;
    public $daftarUlang;
    public $routePrefix = 'panitia-pendaftaran'; // Default route prefix

    // Mode edit
    public $editMode = false;

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

    // Status Pendaftaran
    public $status_pendaftaran;

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

    // Data Daftar Ulang
    public $du_status;
    public $du_pembayaran_lunas;
    public $du_seragam_diukur;
    public $du_berkas_lengkap;
    public $du_catatan;
    public $du_tanggal_daftar_ulang;
    public $du_batas_daftar_ulang;

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

    public function mount($id)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])) {
            return redirect()->to('/login/admin');
        }

        // Set route prefix based on user role
        if ($user->role === 'panitia_daftarulang') {
            $this->routePrefix = 'panitia-daftarulang';
        } elseif ($user->role === 'panitia_pendaftaran' || $user->role === 'panitia') {
            $this->routePrefix = 'panitia-pendaftaran';
        }

        $this->pendaftarId = $id;
        $this->loadPendaftar();
        $this->loadVerifikasi();
        $this->loadDokumen();
        $this->loadDaftarUlang();
        $this->loadDataToProperties();
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
     * Get panitia ID for current user
     * Returns panitia.id based on user_id
     */
    private function getPanitiaId()
    {
        $user = Auth::user();

        // Find panitia record by user_id
        $panitia = Panitia::where('user_id', $user->id)->first();
        if ($panitia) {
            return $panitia->id;
        }

        // If panitia record not found, return null
        return null;
    }

    public function updateStatusDokumen($jenis)
    {
        $statusField = 'status_' . $jenis;
        $catatanField = 'catatan_' . $jenis;

        $this->verifikasi->update([
            $statusField => $this->$statusField,
            $catatanField => $this->$catatanField,
            'panitia_id' => $this->getPanitiaId(),
            'tanggal_verifikasi' => now(),
        ]);

        // Send notification to pendaftar
        if ($this->pendaftar && $this->pendaftar->user) {
            $this->pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'upload_berkas',
                    $this->$statusField,
                    $this->$catatanField
                )
            );
        }

        session()->flash('message', 'Status dokumen berhasil diperbarui.');
        $this->loadVerifikasi();
    }

    public function updateStatusPendaftaran()
    {
        $this->pendaftar->update([
            'status_pendaftaran' => $this->status_pendaftaran,
            'diperbarui_oleh' => Auth::id(),
        ]);

        // Send notification to pendaftar
        if ($this->pendaftar && $this->pendaftar->user) {
            $this->pendaftar->user->notify(
                new \App\Notifications\StatusChangeNotification(
                    'formulir',
                    $this->status_pendaftaran,
                    $this->pendaftar->catatan_verifikasi
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

    public function saveDataPribadi()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'required|string|max:10',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:50',
            'newFoto' => 'nullable|image|max:2048',
        ]);

        $dataToUpdate = [
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
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

        session()->flash('message', 'Data pribadi berhasil diperbarui.');
    }

    public function saveDataOrangTua()
    {
        $this->pendaftar->update([
            'nama_ayah' => $this->nama_ayah,
            'nama_ibu' => $this->nama_ibu,
            'pekerjaan_ayah' => $this->pekerjaan_ayah,
            'pekerjaan_ibu' => $this->pekerjaan_ibu,
            'penghasilan_ortu' => $this->penghasilan_ortu,
            'telepon_ortu' => $this->telepon_ortu,
        ]);

        $this->loadPendaftar();
        session()->flash('message', 'Data orang tua berhasil diperbarui.');
    }

    public function saveDataAlamat()
    {
        $this->validate([
            'alamat' => 'nullable|string',
            'asal_kabkota' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email',
        ]);

        $this->pendaftar->update([
            'alamat' => $this->alamat,
            'asal_kabkota' => $this->asal_kabkota,
            'telepon' => $this->telepon,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
        ]);

        $this->loadPendaftar();
        session()->flash('message', 'Data alamat berhasil diperbarui.');
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

        $this->loadPendaftar();
        session()->flash('message', 'Data sekolah berhasil diperbarui.');
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

        $this->loadPendaftar();
        session()->flash('message', 'Data rapor berhasil diperbarui.');
    }

    public function saveDataPrestasi()
    {
        $this->pendaftar->update([
            'prestasi' => $this->prestasi,
        ]);

        $this->loadPendaftar();
        session()->flash('message', 'Data prestasi berhasil diperbarui.');
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
                'status' => $this->du_status ?? 'belum',
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

    // Region name lookup methods
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
    // KEAHLIAN METHODS
    // ========================================

    public function loadKeahlian()
    {
        $keahlians = Keahlian::where('pendaftar_id', $this->pendaftar->id)->get();

        if ($keahlians->isEmpty()) {
            // Initialize with one empty entry
            $this->keahlianList = [[
                'id' => null,
                'bidang_keahlian' => null,
                'nama_keahlian' => null,
                'sertifikasi' => null,
                'lembaga_penyelenggara' => null,
                'hasil_tingkat_skor' => null,
                'file_bukti_sertifikat' => null,
            ]];
        } else {
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
        // Delete existing keahlian that are not in the list anymore
        $existingIds = array_filter(array_column($this->keahlianList, 'id'));
        if (!empty($existingIds)) {
            Keahlian::where('pendaftar_id', $this->pendaftar->id)
                ->whereNotIn('id', $existingIds)
                ->get()
                ->each(function ($keahlian) {
                    if ($keahlian->file_bukti_sertifikat && Storage::disk('public')->exists($keahlian->file_bukti_sertifikat)) {
                        Storage::disk('public')->delete($keahlian->file_bukti_sertifikat);
                    }
                    $keahlian->delete();
                });
        } else {
            // If no existing IDs, delete all
            Keahlian::where('pendaftar_id', $this->pendaftar->id)->get()->each(function ($keahlian) {
                if ($keahlian->file_bukti_sertifikat && Storage::disk('public')->exists($keahlian->file_bukti_sertifikat)) {
                    Storage::disk('public')->delete($keahlian->file_bukti_sertifikat);
                }
                $keahlian->delete();
            });
        }

        // Save each keahlian
        foreach ($this->keahlianList as $index => $keahlianData) {
            // Skip if empty
            if (empty($keahlianData['bidang_keahlian']) && empty($keahlianData['nama_keahlian'])) {
                continue;
            }

            // Handle file upload
            $filePath = $keahlianData['file_bukti_sertifikat'] ?? null;
            if (isset($this->uploadKeahlianSertifikat[$index]) && $this->uploadKeahlianSertifikat[$index]) {
                $file = $this->uploadKeahlianSertifikat[$index];

                $filename = FileNamingService::generateFileName(
                    'sertifikat_keahlian',
                    $this->pendaftar->nomor_pendaftaran,
                    $file->getClientOriginalExtension(),
                    $this->pendaftar->nama_lengkap
                );

                $filePath = $file->storeAs('sertifikat_keahlian', $filename, 'public');

                // Delete old file if exists and updating
                if (isset($keahlianData['id']) && $keahlianData['id']) {
                    $oldKeahlian = Keahlian::find($keahlianData['id']);
                    if ($oldKeahlian && $oldKeahlian->file_bukti_sertifikat && Storage::disk('public')->exists($oldKeahlian->file_bukti_sertifikat)) {
                        Storage::disk('public')->delete($oldKeahlian->file_bukti_sertifikat);
                    }
                }
            }

            if (isset($keahlianData['id']) && $keahlianData['id']) {
                // Update existing
                $keahlian = Keahlian::find($keahlianData['id']);
                if ($keahlian) {
                    $keahlian->update([
                        'bidang_keahlian' => $keahlianData['bidang_keahlian'],
                        'nama_keahlian' => $keahlianData['nama_keahlian'],
                        'sertifikasi' => $keahlianData['sertifikasi'],
                        'lembaga_penyelenggara' => $keahlianData['lembaga_penyelenggara'],
                        'hasil_tingkat_skor' => $keahlianData['hasil_tingkat_skor'],
                        'file_bukti_sertifikat' => $filePath,
                    ]);
                }
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
            $filename = FileNamingService::generateFileName(
                'syahadah',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzSyahadah->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            $syahadahPath = $this->uploadTahfidzSyahadah->storeAs('tahfidz', $filename, 'public');

            // Delete old file if exists
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_syahadah && Storage::disk('public')->exists($oldTahfidz->file_bukti_syahadah)) {
                    Storage::disk('public')->delete($oldTahfidz->file_bukti_syahadah);
                }
            }

            $this->tahfidz['file_bukti_syahadah'] = $syahadahPath;
        }

        // Handle tahsin upload
        if ($this->uploadTahfidzTahsin) {
            $filename = FileNamingService::generateFileName(
                'tahsin',
                $this->pendaftar->nomor_pendaftaran,
                $this->uploadTahfidzTahsin->getClientOriginalExtension(),
                $this->pendaftar->nama_lengkap
            );

            $tahsinPath = $this->uploadTahfidzTahsin->storeAs('tahfidz', $filename, 'public');

            // Delete old file if exists
            if ($this->tahfidz['id']) {
                $oldTahfidz = Tahfidz::find($this->tahfidz['id']);
                if ($oldTahfidz && $oldTahfidz->file_bukti_tahsin && Storage::disk('public')->exists($oldTahfidz->file_bukti_tahsin)) {
                    Storage::disk('public')->delete($oldTahfidz->file_bukti_tahsin);
                }
            }

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
        session()->flash('message', 'Data tahfidz berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.panitia.detail-pendaftar')
            ->layout('components.layouts.dashboard', [
                'role' => Auth::user()->role,
                'pageTitle' => 'Detail Pendaftar',
                'pageDescription' => 'Lihat detail data pendaftar dan verifikasi berkas'
            ]);
    }
}
