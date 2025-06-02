<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyediaCarbonCredit extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'penyedia_carbon_credit';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'kode_penyedia';

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
        'kode_penyedia',
        'kode_perusahaan',
        'nama_penyedia',
        'deskripsi',
        'harga_per_ton',
        'mata_uang',
        'is_active',
    ];

    /**
     * Cast attributes to appropriate types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_per_ton' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi dengan PembelianCarbonCredit.
     */
    public function pembelianCarbonCredit()
    {
        return $this->hasMany(PembelianCarbonCredit::class, 'kode_penyedia', 'kode_penyedia');
    }
    
    /**
     * Relasi dengan Perusahaan.
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'kode_perusahaan', 'kode_perusahaan');
    }
}