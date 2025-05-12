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
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_user',
        'nama',
        'email',
        'password',
        'role',
        'no_hp',
        'alamat',
        'kode_perusahaan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Cek apakah user adalah super admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Cek apakah user adalah manager
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->role === 'manager';
    }

    /**
     * Cek apakah user adalah admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah staff
     *
     * @return bool
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Relasi ke perusahaan
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'kode_perusahaan', 'kode_perusahaan');
    }
}
