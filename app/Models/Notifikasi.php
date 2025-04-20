<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'notifikasi';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_notifikasi';

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
        'kode_notifikasi',
        'kategori_notifikasi',
        'deskripsi',
        'tanggal_notifikasi',
        'kode_admin',
        'kode_staff',
    ];

    /**
     * Relasi dengan Admin.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'kode_admin', 'kode_admin');
    }

    /**
     * Relasi dengan Staff.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'kode_staff', 'kode_staff');
    }
}