<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * NOTE: 'role' and 'aktif' are intentionally excluded to prevent privilege escalation.
     * These must be set explicitly in code, not from user input.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Get the pendaftar profile
     */
    public function pendaftar()
    {
        return $this->hasOne(Pendaftar::class);
    }

    /**
     * Get the panitia profile
     */
    public function panitia()
    {
        return $this->hasOne(Panitia::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is pendaftar
     */
    public function isPendaftar(): bool
    {
        return $this->role === 'pendaftar';
    }

    /**
     * Check if user is panitia
     */
    public function isPanitia(): bool
    {
        return in_array($this->role, ['panitia_pendaftaran', 'panitia_daftarulang']);
    }
}
