<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Perusahaan extends Model
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'perusahaan';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_perusahaan';

    /**
     * Menunjukkan bahwa primary key bukan auto-increment.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Atribut yang dapat diisi (mass assignable).
     *
     * @var array<string>
     */
    protected $fillable = [
        'kode_perusahaan',
        'nama_perusahaan',
        'alamat_perusahaan',
        'no_telp_perusahaan',
        'email_perusahaan',
        'password_perusahaan',
        'kode_manager',
        'kode_super_admin',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password_perusahaan',
    ];

    /**
     * Relasi dengan Super Admin.
     */
    public function superAdmin()
    {
        return $this->belongsTo(SuperAdmin::class, 'kode_super_admin', 'kode_super_admin');
    }

    /**
     * Relasi dengan Manager.
     */
    public function manager()
    {
        return $this->hasOne(User::class, 'kode_perusahaan', 'kode_perusahaan')
                    ->where('role', 'manager');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'kode_perusahaan', 'kode_perusahaan');
    }

    public function admins()
    {
        return $this->hasMany(User::class, 'kode_perusahaan', 'kode_perusahaan')
                    ->where('role', 'admin');
    }

    public function staffs()
    {
        return $this->hasMany(User::class, 'kode_perusahaan', 'kode_perusahaan')
                    ->where('role', 'staff');
    }
    
    /**
     * Relasi dengan PenyediaCarbonCredit.
     */
    public function penyediaCarbonCredit()
    {
        return $this->hasMany(PenyediaCarbonCredit::class, 'kode_perusahaan', 'kode_perusahaan');
    }
}