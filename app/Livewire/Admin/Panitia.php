<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Panitia extends Component
{
    use WithPagination, WithSorting;

    public $name, $email, $password, $role, $aktif;
    public $user_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';
    public $filterRole = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->to('/login/admin');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query()
            ->whereIn('role', ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($q) {
                $q->where('role', $this->filterRole);
            });

        // Apply sorting
        $users = $this->applySorting($query)->paginate(10);

        $stats = [
            'total' => User::whereIn('role', ['admin', 'panitia_pendaftaran', 'panitia_daftarulang', 'panitia_tes_psikologi', 'panitia_seragam'])->count(),
            'admin' => User::where('role', 'admin')->count(),
            'panitia_pendaftaran' => User::where('role', 'panitia_pendaftaran')->count(),
            'panitia_daftarulang' => User::where('role', 'panitia_daftarulang')->count(),
            'panitia_tes_psikologi' => User::where('role', 'panitia_tes_psikologi')->count(),
            'panitia_seragam' => User::where('role', 'panitia_seragam')->count(),
        ];

        return view('livewire.admin.panitia', compact('users', 'stats'))
            ->layout('components.layouts.dashboard', [
                'role' => 'admin',
                'pageTitle' => 'Manajemen Panitia',
                'pageDescription' => 'Kelola akun panitia PPDB MTsN 2 Kota Malang'
            ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->aktif = $user->aktif;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,panitia_pendaftaran,panitia_daftarulang,panitia_tes_psikologi,panitia_seragam',
        ]);

        // Ensure role is set, if empty use panitia_pendaftaran as fallback
        $roleToSave = $this->role ?: 'panitia_pendaftaran';

        // Create user with only fillable fields, then set role and aktif explicitly
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Set role and aktif explicitly (not in fillable for security)
        $user->role = $roleToSave;
        $user->aktif = $this->aktif ?? true;
        $user->save();

        // Reset pagination and filters to show new data
        $this->resetPage();
        $this->search = '';
        $this->filterRole = '';

        $this->closeModal();
        session()->flash('message', 'Akun panitia berhasil ditambahkan.');

        // Dispatch browser event to refresh the list
        $this->dispatch('panitia-updated');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,panitia_pendaftaran,panitia_daftarulang,panitia_tes_psikologi,panitia_seragam',
        ]);

        if ($this->user_id) {
            $user = User::find($this->user_id);
            if ($user) {
                // Update fillable fields only
                $user->name = $this->name;
                $user->email = $this->email;

                if ($this->password) {
                    $user->password = Hash::make($this->password);
                }

                // Set role and aktif explicitly (not in fillable for security)
                $user->role = $this->role;
                $user->aktif = $this->aktif ?? true;

                $user->save();
            }
        }

        // Reset to first page to ensure updated data is visible
        $this->resetPage();

        $this->closeModal();
        session()->flash('message', 'Akun panitia berhasil diperbarui.');

        // Dispatch browser event to refresh the list
        $this->dispatch('panitia-updated');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user && $user->id !== Auth::id()) {
            $user->delete();

            // Reset to first page after delete
            $this->resetPage();

            session()->flash('message', 'Akun panitia berhasil dihapus.');

            // Dispatch browser event to refresh the list
            $this->dispatch('panitia-updated');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'panitia_pendaftaran';
        $this->aktif = true;
        $this->user_id = null;
    }
}
