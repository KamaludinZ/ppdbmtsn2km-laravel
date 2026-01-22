<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfil extends Component
{
    public $showModal = false;
    public $name;
    public $email;
    public $telepon_ortu;
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    // Support both Livewire v2 and v3
    protected $listeners = ['openModal', 'open-modal' => 'openModal'];

    protected function rules()
    {
        $user = Auth::user();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|required_with:current_password|string|min:6|confirmed',
        ];

        // Hanya validasi telepon_ortu untuk role pendaftar
        if ($user->role === 'pendaftar') {
            $rules['telepon_ortu'] = 'nullable|string|max:20|regex:/^62[0-9]{9,13}$/';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah digunakan.',
        'telepon_ortu.regex' => 'Nomor WhatsApp harus dalam format 62xxx (contoh: 628123456789).',
        'telepon_ortu.max' => 'Nomor WhatsApp maksimal 20 karakter.',
        'current_password.required_with' => 'Password lama wajib diisi jika ingin mengubah password.',
        'new_password.required_with' => 'Password baru wajib diisi.',
        'new_password.min' => 'Password baru minimal 6 karakter.',
        'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        // Hanya load telepon_ortu untuk role pendaftar
        if ($user->role === 'pendaftar' && $user->pendaftar) {
            $this->telepon_ortu = $user->pendaftar->telepon_ortu ?? null;
        }
    }

    public function openModal()
    {
        $this->showModal = true;
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        // Hanya load telepon_ortu untuk role pendaftar
        if ($user->role === 'pendaftar' && $user->pendaftar) {
            $this->telepon_ortu = $user->pendaftar->telepon_ortu ?? null;
        }

        $this->resetPasswordFields();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetPasswordFields();
        $this->resetValidation();
    }

    public function resetPasswordFields()
    {
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function updateProfil()
    {
        $this->validate();

        $user = Auth::user();

        // Jika user ingin mengubah password, validasi password lama
        if ($this->new_password) {
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Password lama tidak sesuai.');
                return;
            }
        }

        // Update nama
        $user->name = $this->name;

        // Update email hanya jika admin
        if ($user->role === 'admin') {
            $user->email = $this->email;
        }

        // Update nomor WhatsApp - hanya untuk role pendaftar
        if ($user->role === 'pendaftar' && $user->pendaftar) {
            $user->pendaftar->telepon_ortu = $this->telepon_ortu;
            $user->pendaftar->save();
        }

        // Update password jika ada
        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }

        $user->save();

        // Reset password fields setelah berhasil simpan
        $this->resetPasswordFields();

        session()->flash('message', 'Profil berhasil diperbarui.');
        $this->dispatch('profileUpdated');
    }

    public function render()
    {
        return view('livewire.edit-profil');
    }
}
