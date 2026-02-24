<?php

namespace App\Livewire\Admin;

use App\Models\Pendaftar as PendaftarModel;
use App\Models\JalurPendaftaran;
use App\Models\RiwayatPesan;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Pendaftar extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $searchInput = ''; // Temporary input untuk user ketik
    public $filterJalur = '';
    public $filterStatus = '';
    public $filterEmisCompleteness = ''; // Filter berdasarkan persentase kelengkapan EMIS
    public $filterVerifPsikologi = ''; // Filter berdasarkan status verifikasi psikologi
    public $perPage = 10;

    // Properties for change password modal
    public $isChangePasswordModalOpen = false;
    public $selectedPendaftarId = null;
    public $new_password = '';
    public $new_password_confirmation = '';

    // Properties for send message modal
    public $isSendMessageModalOpen = false;
    public $messageType = 'email'; // 'email' or 'whatsapp'
    public $selectedMessageTemplate = '';
    public $messageSubject = '';
    public $messageBody = '';
    public $messageNote = '';

    // Properties for edit telepon ortu modal
    public $isEditTeleponOrtuModalOpen = false;
    public $editTeleponOrtu = '';

    // Properties for edit catatan modal
    public $isEditCatatanModalOpen = false;
    public $editCatatan = '';

    // Array to store status for each pendaftar
    public $statusSelections = [];

    // Properties for column visibility
    public $showColumns = [
        'no' => true,
        'nomor_pendaftaran' => true,
        'pendaftar' => true,
        'jenis_kelamin' => true,
        'kontak' => true,
        'password' => false,
        'ubah_password' => false,
        'ortu_whatsapp' => false,
        'asal_kabkota' => true,
        'jenis_sekolah' => true,
        'nsm_npsn' => true,
        'asal_sekolah' => true,
        'nama_ayah' => false,
        'nama_ibu' => false,
        'jalur' => true,
        'status' => true,
        'status_tes' => true, // Status kelulusan tes
        'setuju_daftar_ulang' => true, // Persetujuan daftar ulang
        'status_du_madrasah' => false, // Status DU Madrasah (Pembayaran)
        'status_du_seragam' => false, // Status DU Seragam
        'status_ukuran' => false, // Status Ukuran Seragam
        'status_keahlian' => false, // Status Keahlian
        'status_tahfidz' => false, // Status Tahfidz
        'status_daftar_ulang' => false,
        'verif_rapor' => false,
        'verif_akte' => false,
        'verif_kk' => false,
        'verif_nisn' => false,
        'verif_ijazah' => false,
        'verif_sertifikat' => false,
        'verif_kip' => false,
        'verif_pkh' => false,
        'verif_kks' => false,
        'verif_psikologi' => true,
        'riwayat_pesan' => false,
        'diperbarui' => false,
        'diperbarui_oleh' => false,
        'tanggal' => true,
        'emis_completeness' => true,
        'catatan' => true,
        'aksi' => true,
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterJalur' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterEmisCompleteness' => ['except' => ''],
        'filterVerifPsikologi' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Check if user is admin or panitia
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia'])) {
            return redirect()->to('/login/admin');
        }

        // Clear search if it contains role names (likely from browser autocomplete)
        if (in_array(strtolower($this->search), ['admin', 'panitia', 'panitia_pendaftaran', 'panitia_daftarulang'])) {
            $this->search = '';
        }

        // Sync searchInput with search value from query string
        $this->searchInput = $this->search;
    }

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchInput = '';
        $this->resetPage();
    }

    public function updatingFilterJalur()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterEmisCompleteness()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PendaftarModel::with(['jalurPendaftaran', 'user', 'diperbaruiOleh', 'daftarUlang', 'seragam', 'verifikasiBerkas', 'tesPsikologiPendaftar', 'keahlians', 'tahfidz'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_lengkap', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nomor_pendaftaran', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nisn', 'ilike', '%' . $this->search . '%')
                        ->orWhere('email', 'ilike', '%' . $this->search . '%')
                        ->orWhere('no_hp', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nama_ayah', 'ilike', '%' . $this->search . '%')
                        ->orWhere('nama_ibu', 'ilike', '%' . $this->search . '%')
                        ->orWhere('asal_kabkota', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->filterJalur, function ($q) {
                $q->where('jalur_pendaftaran_id', $this->filterJalur);
            })
            ->when($this->filterStatus, function ($q) {
                if ($this->filterStatus === 'data_disetujui') {
                    $q->whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak']);
                } else {
                    $q->where('status_pendaftaran', $this->filterStatus);
                }
            })
            ->when($this->filterVerifPsikologi, function ($q) {
                if ($this->filterVerifPsikologi === 'belum_upload') {
                    // Pendaftar yang belum upload tes psikologi sama sekali
                    $q->whereDoesntHave('tesPsikologiPendaftar');
                } else {
                    // Pendaftar yang sudah upload dan punya status tertentu
                    $q->whereHas('tesPsikologiPendaftar', function ($query) {
                        $query->where('status_verifikasi', $this->filterVerifPsikologi);
                    });
                }
            });

        // Apply sorting
        $query = $this->applySorting($query);

        // Filter by EMIS completeness if selected
        if ($this->filterEmisCompleteness) {
            $allRecords = $query->get();
            $filtered = $allRecords->filter(function ($pendaftar) {
                $percentage = $pendaftar->getEmisCompletenessPercentage();

                return match($this->filterEmisCompleteness) {
                    '0-25' => $percentage >= 0 && $percentage <= 25,
                    '26-50' => $percentage >= 26 && $percentage <= 50,
                    '51-75' => $percentage >= 51 && $percentage <= 75,
                    '76-100' => $percentage >= 76 && $percentage <= 100,
                    default => true,
                };
            });

            // Manual pagination
            $currentPage = $this->getPage();
            $perPage = $this->perPage;
            $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $pendaftar = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $pendaftar = $query->paginate($this->perPage);
        }

        // Initialize status selections for each pendaftar
        foreach ($pendaftar as $item) {
            if (!isset($this->statusSelections[$item->id])) {
                // If status is graduation status, show 'data_disetujui' in the selection
                if (in_array($item->status_pendaftaran, ['lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])) {
                    $this->statusSelections[$item->id] = 'data_disetujui';
                } else {
                    $this->statusSelections[$item->id] = $item->status_pendaftaran;
                }
            }
        }

        $jalurPendaftaran = JalurPendaftaran::aktif()->ordered()->get();

        $stats = [
            'total' => PendaftarModel::count(),
            'menunggu_verifikasi' => PendaftarModel::where('status_pendaftaran', 'menunggu_verifikasi')->count(),
            'lengkapi_data' => PendaftarModel::where('status_pendaftaran', 'lengkapi_data')->count(),
            'perbaiki_data' => PendaftarModel::where('status_pendaftaran', 'perbaiki_data')->count(),
            'data_ditolak' => PendaftarModel::where('status_pendaftaran', 'data_ditolak')->count(),
            'data_disetujui' => PendaftarModel::whereIn('status_pendaftaran', ['data_disetujui', 'lulus', 'diterima', 'tidak_lulus', 'cadangan', 'ditolak'])->count(),
        ];

        return view('livewire.admin.pendaftar', compact('pendaftar', 'jalurPendaftaran', 'stats'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Data Pendaftar',
                'pageDescription' => 'Kelola data pendaftar PPDB MTsN 2 Kota Malang'
            ]);
    }

    public function delete($id)
    {
        if (Auth::user()->role !== 'admin') {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data.');
            return;
        }

        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $pendaftar->delete();
            session()->flash('message', 'Data pendaftar berhasil dihapus.');
        }
    }

    public function updateStatus($id)
    {
        $newStatus = $this->statusSelections[$id] ?? null;

        if (!$newStatus) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $pendaftar->update([
                'status_pendaftaran' => $newStatus,
                'diperbarui_oleh' => Auth::id()
            ]);
            session()->flash('message', 'Status pendaftar berhasil diperbarui.');
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
        }
    }

    public function toggleColumn($column)
    {
        $this->showColumns[$column] = !$this->showColumns[$column];
    }

    public function showChangePasswordModal($id)
    {
        $this->selectedPendaftarId = $id;
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->isChangePasswordModalOpen = true;
    }

    public function closeChangePasswordModal()
    {
        $this->isChangePasswordModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function updatePassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pendaftar = PendaftarModel::find($this->selectedPendaftarId);
        if ($pendaftar && $pendaftar->user) {
            $pendaftar->user->update([
                'password' => bcrypt($this->new_password)
            ]);

            session()->flash('message', 'Password pendaftar berhasil diperbarui.');
            $this->closeChangePasswordModal();
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan atau tidak memiliki akun.');
        }
    }

    public function showEditTeleponOrtuModal($id)
    {
        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $this->selectedPendaftarId = $id;
            $this->editTeleponOrtu = $pendaftar->telepon_ortu ?? '';
            $this->isEditTeleponOrtuModalOpen = true;
        }
    }

    public function closeEditTeleponOrtuModal()
    {
        $this->isEditTeleponOrtuModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->editTeleponOrtu = '';
    }

    public function updateTeleponOrtu()
    {
        $this->validate([
            'editTeleponOrtu' => 'required|string|min:10|max:15|regex:/^[0-9+]+$/',
        ], [
            'editTeleponOrtu.required' => 'Nomor WhatsApp Orang Tua wajib diisi.',
            'editTeleponOrtu.min' => 'Nomor WhatsApp Orang Tua minimal 10 digit.',
            'editTeleponOrtu.max' => 'Nomor WhatsApp Orang Tua maksimal 15 digit.',
            'editTeleponOrtu.regex' => 'Nomor WhatsApp Orang Tua hanya boleh berisi angka dan tanda +.',
        ]);

        $pendaftar = PendaftarModel::find($this->selectedPendaftarId);
        if ($pendaftar) {
            $pendaftar->update([
                'telepon_ortu' => $this->editTeleponOrtu,
                'telepon' => $this->editTeleponOrtu, // Update both fields for consistency
            ]);

            session()->flash('message', 'Nomor WhatsApp Orang Tua berhasil diperbarui.');
            $this->closeEditTeleponOrtuModal();
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
        }
    }

    public function showEditCatatanModal($id)
    {
        $pendaftar = PendaftarModel::find($id);
        if ($pendaftar) {
            $this->selectedPendaftarId = $id;
            $this->editCatatan = $pendaftar->catatan_verifikasi ?? '';
            $this->isEditCatatanModalOpen = true;
        }
    }

    public function closeEditCatatanModal()
    {
        $this->isEditCatatanModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->editCatatan = '';
    }

    public function updateCatatan()
    {
        $this->validate([
            'editCatatan' => 'nullable|string|max:1000',
        ], [
            'editCatatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        $pendaftar = PendaftarModel::find($this->selectedPendaftarId);
        if ($pendaftar) {
            $pendaftar->update([
                'catatan_verifikasi' => $this->editCatatan,
                'diperbarui_oleh' => Auth::id()
            ]);

            session()->flash('message', 'Catatan berhasil diperbarui.');
            $this->closeEditCatatanModal();
        } else {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
        }
    }

    public function batalkanPersetujuanDaftarUlang($id)
    {
        try {
            $pendaftar = PendaftarModel::findOrFail($id);

            // Batalkan persetujuan daftar ulang
            $pendaftar->update([
                'setuju_daftar_ulang' => false,
                'tanggal_setuju_daftar_ulang' => null,
            ]);

            // Hapus record daftar ulang jika ada
            if ($pendaftar->daftarUlang) {
                $pendaftar->daftarUlang->delete();
            }

            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "Persetujuan daftar ulang {$pendaftar->nama_lengkap} berhasil dibatalkan"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function sendManualEmail($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function sendManualWhatsApp($id)
    {
        $this->selectedPendaftarId = $id;
        $this->messageType = 'whatsapp';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
        $this->isSendMessageModalOpen = true;
    }

    public function closeSendMessageModal()
    {
        $this->isSendMessageModalOpen = false;
        $this->selectedPendaftarId = null;
        $this->messageType = 'email';
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
        $this->messageNote = '';
    }

    // Livewire lifecycle hook - dipanggil otomatis saat selectedMessageTemplate berubah
    public function updatedSelectedMessageTemplate()
    {
        $this->loadMessageTemplate();
    }

    // Livewire lifecycle hook - dipanggil otomatis saat messageType berubah
    public function updatedMessageType()
    {
        // Reset template saat ganti jenis pesan
        $this->selectedMessageTemplate = '';
        $this->messageSubject = '';
        $this->messageBody = '';
    }

    /**
     * Get available message templates for this context (Data Pendaftar)
     * Context: Pendaftaran & Verifikasi only
     */
    public function getAvailableTemplates()
    {
        return [
            [
                'label' => '📋 Pendaftaran & Verifikasi',
                'options' => [
                    ['value' => 'pendaftaran', 'label' => 'Pendaftaran Berhasil'],
                    ['value' => 'verifikasi_disetujui', 'label' => 'Verifikasi Disetujui'],
                    ['value' => 'verifikasi_ditolak', 'label' => 'Verifikasi Ditolak'],
                    ['value' => 'verifikasi_perbaiki', 'label' => 'Verifikasi Perlu Perbaikan'],
                ]
            ]
        ];
    }

    public function loadMessageTemplate()
    {
        if (!$this->selectedMessageTemplate) {
            return;
        }

        $pendaftar = PendaftarModel::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            return;
        }

        // Load template dari settings
        if ($this->messageType === 'email') {
            $this->messageSubject = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_subject", '');
            $this->messageBody = \App\Models\Setting::get("email_{$this->selectedMessageTemplate}_body", '');
        } else {
            $this->messageBody = \App\Models\Setting::get("wa_{$this->selectedMessageTemplate}_body", '');
        }

        // Replace placeholders
        $replacements = [
            '{nama}' => $pendaftar->nama_lengkap ?? '',
            '{nomor_pendaftaran}' => $pendaftar->nomor_pendaftaran ?? '',
            '{nisn}' => $pendaftar->nisn ?? '',
            '{tempat_lahir}' => $pendaftar->tempat_lahir ?? '',
            '{tanggal_lahir}' => $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '',
            '{jenis_kelamin}' => $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin === 'P' ? 'Perempuan' : ''),
            '{alamat}' => $pendaftar->alamat ?? '',
            '{email}' => $pendaftar->email ?? '',
            '{telepon}' => $pendaftar->no_hp ?? '',
            '{no_hp}' => $pendaftar->no_hp ?? '',
            '{asal_sekolah}' => $pendaftar->asal_sekolah ?? '',
            '{jalur}' => $pendaftar->jalurPendaftaran->nama ?? '',
            '{status}' => $pendaftar->status_pendaftaran ?? '',
            '{catatan}' => $this->messageNote ?? '',
            '{url_dashboard}' => url('/dashboard'),
            '{password}' => '[Password telah dienkripsi, silakan reset jika lupa]',
            // Additional fields
            '{agama}' => $pendaftar->agama ?? '',
            '{nama_ayah}' => $pendaftar->nama_ayah ?? '',
            '{nama_ibu}' => $pendaftar->nama_ibu ?? '',
            '{pekerjaan_ayah}' => $pendaftar->pekerjaan_ayah ?? '',
            '{pekerjaan_ibu}' => $pendaftar->pekerjaan_ibu ?? '',
            '{provinsi}' => $pendaftar->provinsi ?? '',
            '{kabupaten}' => $pendaftar->kabupaten ?? '',
            '{kecamatan}' => $pendaftar->kecamatan ?? '',
            '{desa}' => $pendaftar->desa ?? '',
            '{kode_pos}' => $pendaftar->kode_pos ?? '',
            '{nilai_rata_rata}' => $pendaftar->nilai_rata_rata ?? '',
        ];

        $this->messageSubject = str_replace(array_keys($replacements), array_values($replacements), $this->messageSubject);
        $this->messageBody = str_replace(array_keys($replacements), array_values($replacements), $this->messageBody);
    }

    public function sendMessage()
    {
        $this->validate([
            'messageBody' => 'required|string',
        ]);

        if ($this->messageType === 'email') {
            $this->validate([
                'messageSubject' => 'required|string',
            ]);
        }

        $pendaftar = PendaftarModel::with(['jalurPendaftaran', 'user'])->find($this->selectedPendaftarId);
        if (!$pendaftar) {
            session()->flash('error', 'Pendaftar tidak ditemukan.');
            return;
        }

        // Determine recipient based on message type
        $penerima = $this->messageType === 'email' ? $pendaftar->email : $pendaftar->telepon_ortu;

        // Create log record with pending status
        $riwayatPesan = RiwayatPesan::create([
            'pendaftar_id' => $pendaftar->id,
            'user_id' => Auth::id(),
            'jenis_pesan' => $this->messageType,
            'judul' => $this->messageType === 'email' ? $this->messageSubject : null,
            'isi_pesan' => $this->messageBody,
            'penerima' => $penerima,
            'status' => 'pending',
        ]);

        try {
            if ($this->messageType === 'email') {
                // Kirim email
                \Illuminate\Support\Facades\Mail::raw($this->messageBody, function ($message) use ($pendaftar) {
                    $message->to($pendaftar->email)
                        ->subject($this->messageSubject);
                });

                // Update log to success
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                session()->flash('message', 'Email berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            } else {
                // Kirim WhatsApp via API/Service
                // Implementasi disesuaikan dengan service WhatsApp yang digunakan
                // Contoh: menggunakan Fonnte, Wablas, atau service lainnya

                $phoneNumber = $pendaftar->telepon_ortu;
                if (!$phoneNumber) {
                    // Update log to failed
                    $riwayatPesan->update([
                        'status' => 'gagal',
                        'error_message' => 'Nomor WhatsApp Orang Tua tidak ditemukan',
                    ]);

                    session()->flash('error', 'Nomor WhatsApp Orang Tua tidak ditemukan.');
                    return;
                }

                // Format nomor telepon
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                }

                // Placeholder untuk integrasi WhatsApp API
                // TODO: Implementasikan sesuai dengan service yang digunakan

                // Update log to success (for now, since WhatsApp API not implemented yet)
                $riwayatPesan->update([
                    'status' => 'terkirim',
                    'sent_at' => now(),
                ]);

                session()->flash('message', 'WhatsApp berhasil dikirim ke ' . $pendaftar->nama_lengkap);
            }

            $this->closeSendMessageModal();

        } catch (\Exception $e) {
            // Update log to failed with error message
            $riwayatPesan->update([
                'status' => 'gagal',
                'error_message' => $e->getMessage(),
            ]);

            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    public function getNamaKabupaten($code)
    {
        if (!$code) return '-';
        $city = \App\Models\Regency::where('id', $code)->first();
        return $city ? $city->name : '-';
    }

    /**
     * Ganti jalur pendaftaran dan regenerate nomor pendaftaran
     */
    public function gantiJalur($pendaftarId, $newJalurId)
    {
        try {
            $pendaftar = PendaftarModel::findOrFail($pendaftarId);
            $oldJalur = $pendaftar->jalurPendaftaran;
            $newJalur = JalurPendaftaran::findOrFail($newJalurId);

            // Check if jalur actually changed
            if ($pendaftar->jalur_pendaftaran_id == $newJalurId) {
                $this->dispatch('statusUpdated', [
                    'type' => 'info',
                    'message' => 'Jalur yang dipilih sama dengan jalur saat ini.'
                ]);
                return;
            }

            // Start transaction
            \DB::beginTransaction();

            // Generate nomor pendaftaran baru berdasarkan jalur baru
            $nomorPendaftaranBaru = $newJalur->generateNomorPendaftaran();

            // Update pendaftar dengan jalur dan nomor baru
            $pendaftar->update([
                'jalur_pendaftaran_id' => $newJalurId,
                'nomor_pendaftaran' => $nomorPendaftaranBaru,
                'diperbarui_oleh' => Auth::id()
            ]);

            \DB::commit();

            $this->dispatch('statusUpdated', [
                'type' => 'success',
                'message' => "Jalur berhasil diganti dari '{$oldJalur->nama}' ke '{$newJalur->nama}'. Nomor pendaftaran baru: {$nomorPendaftaranBaru}"
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->dispatch('statusUpdated', [
                'type' => 'error',
                'message' => 'Gagal mengganti jalur: ' . $e->getMessage()
            ]);
        }
    }

}
