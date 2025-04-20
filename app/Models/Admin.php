<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_admin';

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
        'kode_admin',
        'nama_admin',
        'email',
        'password',
        'no_hp',
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
     * Relasi dengan Notifikasi.
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'kode_admin', 'kode_admin');
    }

    /**
     * Relasi dengan PembelianCarbonCredit.
     */
    public function pembelianCarbonCredit()
    {
        return $this->hasMany(PembelianCarbonCredit::class, 'kode_admin', 'kode_admin');
    }
}