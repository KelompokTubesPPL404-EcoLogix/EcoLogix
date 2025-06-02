<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'staff';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_staff';

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
        'kode_staff',
        'nama_staff',
        'alamat',
        'no_hp',
        'email',
        'password',
        'kode_perusahaan',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi dengan Perusahaan.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'kode_perusahaan', 'kode_perusahaan');
    }

    /**
     * Relasi dengan EmisiCarbon.
     */
    public function emisiCarbon()
    {
        return $this->hasMany(EmisiCarbon::class, 'kode_staff', 'kode_staff');
    }

    /**
     * Relasi dengan Notifikasi.
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'kode_staff', 'kode_staff');
    }
}