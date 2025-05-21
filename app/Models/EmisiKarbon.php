<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmisiKarbon extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'emisi_carbon';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_emisi_carbon';

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
     *
     * @var array<string>
     */
    protected $fillable = [
        'kode_emisi_carbon',
        'kategori_emisi_karbon',
        'sub_kategori',
        'nilai_aktivitas',
        'faktor_emisi', 
        'kadar_emisi_karbon', 
        'deskripsi',
        'status',
        'tanggal_emisi',
        'kode_staff',
        'kode_faktor_emisi',
        'kode_perusahaan',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data asli.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_emisi' => 'date',
        'nilai_aktivitas' => 'decimal:2',
        'faktor_emisi' => 'decimal:2',
        'kadar_emisi_karbon' => 'decimal:2',
    ];

    /**
     * Mendapatkan staff (User) yang menginput emisi ini.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'kode_staff', 'kode_user');
    }

    /**
     * Mendapatkan faktor emisi yang terkait dengan emisi ini.
     */
    public function faktorEmisi()
    {
        return $this->belongsTo(FaktorEmisi::class, 'kode_faktor_emisi', 'kode_faktor');
    }
    
    /**
     * Mendapatkan perusahaan yang terkait dengan emisi ini.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'kode_perusahaan', 'kode_perusahaan');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'kode_emisi_carbon';
    }
}
