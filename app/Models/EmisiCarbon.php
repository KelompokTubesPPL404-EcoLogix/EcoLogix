<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmisiCarbon extends Model
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
     * Atribut yang dapat diisi (mass assignable).
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
    ];

    /**
     * Relasi dengan Staff.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'kode_staff', 'kode_staff');
    }

    /**
     * Relasi dengan FaktorEmisi.
     */
    public function faktorEmisi()
    {
        return $this->belongsTo(FaktorEmisi::class, 'kode_faktor_emisi', 'kode_faktor');
    }

    /**
     * Relasi dengan KompensasiEmisiCarbon.
     */
    public function kompensasiEmisiCarbon()
    {
        return $this->hasMany(KompensasiEmisiCarbon::class, 'kode_emisi_carbon', 'kode_emisi_carbon');
    }
}